<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankSoal;
use App\Models\KategoriBankSoal;
use Illuminate\Http\Request;

class BankSoalController extends Controller
{
    public function index(Request $request)
    {
        $kategori_id = $request->kategori_id;
        $kategori = null;
        
        if ($kategori_id) {
            $kategori = KategoriBankSoal::findOrFail($kategori_id);
            $query = BankSoal::where('kategori_id', $kategori_id);
        } else {
            $query = BankSoal::with('kategori');
        }

        if ($request->filled('search')) {
            $query->where('pertanyaan', 'like', '%' . $request->search . '%');
        }

        $bankSoals = $query->latest()->paginate(10)->withQueryString();
        $kategoris = KategoriBankSoal::all();

        return view('admin.bank-soal.index', compact('bankSoals', 'kategori', 'kategoris', 'kategori_id'));
    }

    public function create(Request $request)
    {
        $kategori_id = $request->kategori_id;
        $kategori = null;
        if ($kategori_id) {
            $kategori = KategoriBankSoal::findOrFail($kategori_id);
        }
        $kategoris = KategoriBankSoal::all();
        return view('admin.bank-soal.create', compact('kategori', 'kategoris', 'kategori_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori_bank_soals,id',
            'soals' => 'required|array|min:1',
            'soals.*.pertanyaan' => 'required|string',
            'soals.*.opsi_a' => 'required|string',
            'soals.*.opsi_b' => 'required|string',
            'soals.*.opsi_c' => 'required|string',
            'soals.*.opsi_d' => 'required|string',
            'soals.*.opsi_e' => 'nullable|string',
            'soals.*.jawaban_benar' => 'required|string|in:a,b,c,d,e',
        ]);

        foreach ($request->soals as $soal) {
            BankSoal::create(array_merge($soal, ['kategori_id' => $request->kategori_id]));
        }

        return redirect()->route('admin.bank-soal.index', ['kategori_id' => $request->kategori_id])
            ->with('success', 'Soal berhasil ditambahkan ke bank soal.');
    }

    public function edit($id)
    {
        $bankSoal = BankSoal::findOrFail($id);
        $kategori = KategoriBankSoal::findOrFail($bankSoal->kategori_id);
        return view('admin.bank-soal.edit', compact('bankSoal', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pertanyaan' => 'required|string',
            'opsi_a' => 'required|string',
            'opsi_b' => 'required|string',
            'opsi_c' => 'required|string',
            'opsi_d' => 'required|string',
            'opsi_e' => 'nullable|string',
            'jawaban_benar' => 'required|string|in:a,b,c,d,e',
        ]);

        $bankSoal = BankSoal::findOrFail($id);
        $bankSoal->update($request->all());

        return redirect()->route('admin.bank-soal.index', ['kategori_id' => $bankSoal->kategori_id])
            ->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $bankSoal = BankSoal::findOrFail($id);
        $kategori_id = $bankSoal->kategori_id;
        $bankSoal->delete();

        return redirect()->route('admin.bank-soal.index', ['kategori_id' => $kategori_id])
            ->with('success', 'Soal berhasil dihapus.');
    }

    public function importPdf(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori_bank_soals,id',
            'pdf_file'    => 'required|file|mimes:pdf|max:10240',
        ]);

        $kategori_id = $request->kategori_id;
        $file        = $request->file('pdf_file');

        try {
            // ----- 1. Extract raw text from PDF -----
            $parser = new \Smalot\PdfParser\Parser();
            $pdf    = $parser->parseFile($file->getPathname());
            $text   = $pdf->getText();

            // Normalize all line endings to \n
            $text = str_replace(["\r\n", "\r"], "\n", $text);

            // ----- 2. ROBUST BLOCK SPLITTING -----
            // Use preg_match_all to find the start position of every [N] marker,
            // then slice the text between consecutive anchors.
            // This approach is immune to extra whitespace/newlines before [N].
            $blocks = [];

            preg_match_all('/(?:^|[\s\n])(\[\d+\])/m', $text, $anchorMatches, PREG_OFFSET_CAPTURE);

            if (!empty($anchorMatches[1])) {
                $anchors = $anchorMatches[1]; // [ [matchedText, offset], ... ]

                foreach ($anchors as $idx => $anchor) {
                    $start = $anchor[1];

                    if (isset($anchors[$idx + 1])) {
                        $end = $anchors[$idx + 1][1];
                        // Trim trailing whitespace before next anchor
                        while ($end > $start && in_array($text[$end - 1], [' ', "\n", "\t", "\r"])) {
                            $end--;
                        }
                        $block = substr($text, $start, $end - $start);
                    } else {
                        $block = substr($text, $start);
                    }

                    $block = trim($block);
                    if (!empty($block)) {
                        $blocks[] = $block;
                    }
                }
            }

            // ----- Fallback: newline-based split if anchor scan found nothing -----
            if (empty($blocks)) {
                $rawBlocks  = preg_split('/(?:\n\s*)(?=\[\d+\])/', $text);
                $lastQNum   = 0;
                foreach ($rawBlocks as $block) {
                    $block = trim($block);
                    if (empty($block)) continue;
                    if (preg_match('/^\[(\d+)\]/', $block, $m)) {
                        $qNum = (int) $m[1];
                        if ($qNum > $lastQNum) {
                            $blocks[]  = $block;
                            $lastQNum  = $qNum;
                        } else {
                            // False split – merge back
                            if (!empty($blocks)) {
                                $blocks[count($blocks) - 1] .= "\n" . $block;
                            } else {
                                $blocks[] = $block;
                            }
                        }
                    }
                }
            }

            // ----- False-split guard -----
            // If a merged block contains an internal [N] where N <= previous question number,
            // it means the [N] is part of the question text (e.g. enumeration inside a passage).
            $mergedBlocks = [];
            $lastQNum     = 0;
            foreach ($blocks as $block) {
                $block = trim($block);
                if (empty($block)) continue;

                if (preg_match('/^\[(\d+)\]/', $block, $m)) {
                    $qNum = (int) $m[1];
                    if ($qNum > $lastQNum) {
                        $mergedBlocks[] = $block;
                        $lastQNum       = $qNum;
                    } else {
                        // Merge into previous block (false split)
                        if (!empty($mergedBlocks)) {
                            $mergedBlocks[count($mergedBlocks) - 1] .= "\n" . $block;
                        } else {
                            $mergedBlocks[] = $block;
                        }
                    }
                } else {
                    // Non-numbered text (header/intro) – attach to previous block or skip
                    if (!empty($mergedBlocks)) {
                        $mergedBlocks[count($mergedBlocks) - 1] .= "\n" . $block;
                    }
                }
            }
            $blocks = $mergedBlocks;

            // ----- 3. PARSE EACH BLOCK INTO A QUESTION -----
            $questionsData = [];

            foreach ($blocks as $block) {
                $blockTrimmed = trim($block);
                if (empty($blockTrimmed) || !preg_match('/^\[\d+\]/', $blockTrimmed)) {
                    continue; // Skip non-question blocks
                }

                // Remove the [N] prefix
                $blockClean = preg_replace('/^\[\d+\]\s*/', '', $blockTrimmed);

                // Strip Pembahasan/Penjelasan block from question text
                $pembahasan = '';
                if (preg_match('/(?:Pembahasan|Penjelasan|Diskusi):\s*(.*)$/si', $blockClean, $pembMatches)) {
                    $pembahasan = trim($pembMatches[1]);
                    $blockClean = preg_replace('/(?:Pembahasan|Penjelasan|Diskusi):\s*(.*)$/si', '', $blockClean);
                }

                // Detect alternative option letters (F–J for some CPNS question formats)
                $la = 'A'; $lb = 'B'; $lc = 'C'; $ld = 'D'; $le = 'E';
                if (preg_match('/\bF[.)\:\-]?[\s\xA0]+/u', $blockClean) &&
                    preg_match('/\bG[.)\:\-]?[\s\xA0]+/u', $blockClean)) {
                    $la = 'F'; $lb = 'G'; $lc = 'H'; $ld = 'I'; $le = 'J';
                }

                // Build option patterns (flexible: A. or A) or A: or A-)
                $pa  = '\b' . $la . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-]?[\s\xA0]+';
                $pb  = '\b' . $lb . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-]?[\s\xA0]+';
                $pc  = '\b' . $lc . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-]?[\s\xA0]+';
                $pd  = '\b' . $ld . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-]?[\s\xA0]+';
                $pe  = '\b' . $le . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-]?[\s\xA0]+';
                $pk  = '\b(?:Kunci|Jawaban|Kunci Jawaban):\s*';

                $questionText = '';
                $optionA = ''; $optionB = ''; $optionC = ''; $optionD = '';
                $optionE = null;
                $correctAnswer = 'a';

                // Question text = everything before first option A
                if (preg_match('/^(.*?)(?=' . $pa . ')/sui', $blockClean, $m)) {
                    $questionText = trim($m[1]);
                } else {
                    $questionText = trim($blockClean);
                }
                if (empty($questionText)) {
                    $questionText = '[Soal Bergambar – Silakan merujuk pada PDF]';
                }

                // Option A
                if (preg_match('/' . $pa . '(.*?)(?=' . $pb . ')/sui', $blockClean, $m)) {
                    $optionA = trim($m[1]);
                }
                if (empty($optionA)) $optionA = '[Gambar ' . $la . ']';

                // Option B
                if (preg_match('/' . $pb . '(.*?)(?=' . $pc . ')/sui', $blockClean, $m)) {
                    $optionB = trim($m[1]);
                }
                if (empty($optionB)) $optionB = '[Gambar ' . $lb . ']';

                // Option C
                if (preg_match('/' . $pc . '(.*?)(?=' . $pd . ')/sui', $blockClean, $m)) {
                    $optionC = trim($m[1]);
                }
                if (empty($optionC)) $optionC = '[Gambar ' . $lc . ']';

                // Option D + E (detect whether E exists)
                $hasE = (bool) preg_match('/' . $pe . '/ui', $blockClean);
                if ($hasE) {
                    if (preg_match('/' . $pd . '(.*?)(?=' . $pe . ')/sui', $blockClean, $m)) {
                        $optionD = trim($m[1]);
                    }
                    if (preg_match('/' . $pe . '(.*?)(?=' . $pk . '|$)/sui', $blockClean, $m)) {
                        $optionE = trim(preg_replace('/(?:Kunci|Jawaban|Kunci Jawaban):\s*[A-Z]/i', '', $m[1]));
                    }
                    if (empty($optionE)) $optionE = '[Gambar ' . $le . ']';
                } else {
                    if (preg_match('/' . $pd . '(.*?)(?=' . $pk . '|$)/sui', $blockClean, $m)) {
                        $optionD = trim($m[1]);
                    }
                    $optionE = null;
                }

                // Clean stray key references from option D
                $optionD = trim(preg_replace('/(?:Kunci|Jawaban|Kunci Jawaban):\s*[A-Z]/i', '', $optionD));
                if (empty($optionD)) $optionD = '[Gambar ' . $ld . ']';

                // --- Clean options from bullet lists or passages that should belong to the question text ---
                $optionsToClean = [
                    'opsi_a' => &$optionA,
                    'opsi_b' => &$optionB,
                    'opsi_c' => &$optionC,
                    'opsi_d' => &$optionD,
                    'opsi_e' => &$optionE
                ];
                $extractedBullets = [];

                foreach ($optionsToClean as $key => &$optVal) {
                    if (empty($optVal)) continue;

                    $lines = explode("\n", $optVal);
                    $newOptLines = [];
                    $bulletLines = [];

                    foreach ($lines as $line) {
                        $trimmedLine = trim($line);
                        if (preg_match('/^[\x{2022}\x{2023}\x{2043}\x{204C}\x{204D}\x{2219}\x{25E6}\x{25C6}\x{25C9}\x{25CB}\x{25A0}\x{25A1}\x{25AA}\x{25AB}\x{25B6}\x{25B8}\x{25C0}\x{25C2}\-\*]/u', $trimmedLine) || 
                            (!empty($bulletLines) && !preg_match('/^[A-Z][.)\:\-]/i', $trimmedLine))) {
                            $bulletLines[] = $line;
                        } else {
                            $newOptLines[] = $line;
                        }
                    }

                    if (!empty($bulletLines)) {
                        $optVal = trim(implode("\n", $newOptLines));
                        $extractedBullets[] = implode("\n", $bulletLines);
                    }
                }

                if (!empty($extractedBullets)) {
                    $questionText .= "\n\n" . implode("\n", $extractedBullets);
                }

                // ----- Detect correct answer key -----
                if (preg_match('/(?:Kunci|Jawaban|Kunci Jawaban):\s*([A-Z])\b/i', $blockTrimmed, $keyMatch)) {
                    $correctAnswer = strtolower($keyMatch[1]);
                } elseif (!empty($pembahasan)) {
                    if (preg_match('/(?:kunci|jawaban|jawaban yang benar adalah|kunci jawaban adalah|maka jawaban|yaitu|adalah)\s*([A-Z])\b/i', $pembahasan, $keyMatch)) {
                        $correctAnswer = strtolower($keyMatch[1]);
                    } elseif (preg_match('/\b([A-Z])\b\s*(?:adalah|merupakan)\s*(?:pilihan|jawaban)/i', $pembahasan, $keyMatch)) {
                        $correctAnswer = strtolower($keyMatch[1]);
                    }
                }

                // Map F–J keys to a–e
                $keyMap = [
                    'a' => 'a', 'b' => 'b', 'c' => 'c', 'd' => 'd', 'e' => 'e',
                    'f' => 'a', 'g' => 'b', 'h' => 'c', 'i' => 'd', 'j' => 'e',
                ];
                $correctAnswer = $keyMap[$correctAnswer] ?? 'a';

                // ----- Format question HTML + append pembahasan block -----
                $questionHtml = nl2br(e($questionText));
                if (!empty($pembahasan)) {
                    $pemClean = trim(preg_replace('/(?:Kunci|Jawaban|Kunci Jawaban):\s*[A-Z]/i', '', $pembahasan));
                    if (!empty($pemClean)) {
                        $questionHtml .= '<!--pembahasan_start--><br><br>'
                            . '<span class="pembahasan-premium-block" style="display:block;padding:12px 15px;'
                            . 'background:rgba(37,99,235,0.06);border-left:4px solid #2563EB;border-radius:8px;'
                            . 'color:#1E3A8A;font-size:0.92rem;margin-top:12px;font-weight:normal;line-height:1.6;">'
                            . '<i class="fas fa-info-circle" style="color:#2563EB;margin-right:6px;"></i>'
                            . '<strong>Pembahasan Ujian:</strong> ' . nl2br(e($pemClean))
                            . '</span><!--pembahasan_end-->';
                    }
                }

                $questionsData[] = [
                    'kategori_id'   => $kategori_id,
                    'pertanyaan'    => $questionHtml,
                    'opsi_a'        => $optionA,
                    'opsi_b'        => $optionB,
                    'opsi_c'        => $optionC,
                    'opsi_d'        => $optionD,
                    'opsi_e'        => $optionE,
                    'jawaban_benar' => $correctAnswer,
                ];
            }

            if (empty($questionsData)) {
                return redirect()
                    ->back()
                    ->with('error', 'Tidak ada soal yang berhasil diparsing. Harap periksa kembali format PDF Anda!');
            }

            // Delete old questions if checkbox checked
            if ($request->has('delete_old') && $request->delete_old == '1') {
                BankSoal::where('kategori_id', $kategori_id)->delete();
            }

            // Bulk insert
            foreach ($questionsData as $qData) {
                BankSoal::create($qData);
            }

            return redirect()
                ->route('admin.bank-soal.index', ['kategori_id' => $kategori_id])
                ->with('success', 'Berhasil mengimpor ' . count($questionsData) . ' soal dari file PDF ke Bank Soal!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat mengimpor: ' . $e->getMessage());
        }
    }
}
