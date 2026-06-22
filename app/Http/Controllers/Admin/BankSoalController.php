<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankSoal;
use App\Models\KategoriBankSoal;
use Illuminate\Http\Request;

class BankSoalController extends Controller
{
    private function ensureDefaultCategories()
    {
        $categories = ['TWK', 'TIU', 'TKP'];
        foreach ($categories as $cat) {
            KategoriBankSoal::firstOrCreate(
                ['nama_kategori' => $cat],
                ['deskripsi' => 'Kategori Soal ' . $cat]
            );
        }
    }

    public function index(Request $request)
    {
        $this->ensureDefaultCategories();
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
        $this->ensureDefaultCategories();
        $kategori_id = $request->kategori_id;
        $kategori = null;
        if ($kategori_id) {
            $kategori = KategoriBankSoal::findOrFail($kategori_id);
        }
        $kategoris = KategoriBankSoal::all();
        return view('admin.bank-soal.create', compact('kategori', 'kategoris', 'kategori_id'));
    }

    private function uploadSoalImage($file, $prefix = 'img')
    {
        if (!$file) return null;
        
        $destinationPath = public_path('uploads/soal');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        
        $fileName = $prefix . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move($destinationPath, $fileName);
        
        return '/uploads/soal/' . $fileName;
    }

    private function deleteExistingImage($content)
    {
        if (preg_match('/<img[^>]+src="([^"]+)"/i', $content, $match)) {
            $path = public_path($match[1]);
            if (file_exists($path)) {
                @unlink($path);
            }
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori_bank_soals,id',
            'soals' => 'required|array|min:1',
            'soals.*.pertanyaan' => 'required_without:soals.*.gambar_pertanyaan|nullable|string',
            'soals.*.gambar_pertanyaan' => 'nullable|image|max:5120',
            'soals.*.opsi_a' => 'required_without:soals.*.gambar_opsi_a|nullable|string',
            'soals.*.gambar_opsi_a' => 'nullable|image|max:2048',
            'soals.*.opsi_b' => 'required_without:soals.*.gambar_opsi_b|nullable|string',
            'soals.*.gambar_opsi_b' => 'nullable|image|max:2048',
            'soals.*.opsi_c' => 'required_without:soals.*.gambar_opsi_c|nullable|string',
            'soals.*.gambar_opsi_c' => 'nullable|image|max:2048',
            'soals.*.opsi_d' => 'required_without:soals.*.gambar_opsi_d|nullable|string',
            'soals.*.gambar_opsi_d' => 'nullable|image|max:2048',
            'soals.*.opsi_e' => 'nullable|string',
            'soals.*.gambar_opsi_e' => 'nullable|image|max:2048',
            'soals.*.jenis_soal' => 'required|string|in:TWK,TIU,TKP',
            'soals.*.jawaban_benar' => 'nullable|string|in:a,b,c,d,e',
            'soals.*.option_points_a' => 'nullable|integer|min:1|max:5',
            'soals.*.option_points_b' => 'nullable|integer|min:1|max:5',
            'soals.*.option_points_c' => 'nullable|integer|min:1|max:5',
            'soals.*.option_points_d' => 'nullable|integer|min:1|max:5',
            'soals.*.option_points_e' => 'nullable|integer|min:1|max:5',
        ]);

        foreach ($request->soals as $index => $soal) {
            $jenis_soal = $soal['jenis_soal'] ?? 'TWK';
            $option_points = null;
            $jawaban_benar = $soal['jawaban_benar'] ?? 'a';

            // Process Question Image
            $pertanyaanText = $soal['pertanyaan'] ?? '';
            if ($request->hasFile("soals.{$index}.gambar_pertanyaan")) {
                $file = $request->file("soals.{$index}.gambar_pertanyaan");
                $path = $this->uploadSoalImage($file, 'q');
                if ($path) {
                    $pertanyaanText .= ($pertanyaanText ? '<br>' : '') . '<img src="' . $path . '" style="max-width:100%; height:auto; display:block; margin-top:10px;" />';
                }
            }

            // Process Options Images
            $opsiA = $soal['opsi_a'] ?? '';
            if ($request->hasFile("soals.{$index}.gambar_opsi_a")) {
                $file = $request->file("soals.{$index}.gambar_opsi_a");
                $path = $this->uploadSoalImage($file, 'opt_a');
                if ($path) {
                    $opsiA = ($opsiA ? $opsiA . '<br>' : '') . '<img src="' . $path . '" style="max-height:150px; max-width:100%; height:auto; display:block;" />';
                }
            }

            $opsiB = $soal['opsi_b'] ?? '';
            if ($request->hasFile("soals.{$index}.gambar_opsi_b")) {
                $file = $request->file("soals.{$index}.gambar_opsi_b");
                $path = $this->uploadSoalImage($file, 'opt_b');
                if ($path) {
                    $opsiB = ($opsiB ? $opsiB . '<br>' : '') . '<img src="' . $path . '" style="max-height:150px; max-width:100%; height:auto; display:block;" />';
                }
            }

            $opsiC = $soal['opsi_c'] ?? '';
            if ($request->hasFile("soals.{$index}.gambar_opsi_c")) {
                $file = $request->file("soals.{$index}.gambar_opsi_c");
                $path = $this->uploadSoalImage($file, 'opt_c');
                if ($path) {
                    $opsiC = ($opsiC ? $opsiC . '<br>' : '') . '<img src="' . $path . '" style="max-height:150px; max-width:100%; height:auto; display:block;" />';
                }
            }

            $opsiD = $soal['opsi_d'] ?? '';
            if ($request->hasFile("soals.{$index}.gambar_opsi_d")) {
                $file = $request->file("soals.{$index}.gambar_opsi_d");
                $path = $this->uploadSoalImage($file, 'opt_d');
                if ($path) {
                    $opsiD = ($opsiD ? $opsiD . '<br>' : '') . '<img src="' . $path . '" style="max-height:150px; max-width:100%; height:auto; display:block;" />';
                }
            }

            $opsiE = $soal['opsi_e'] ?? null;
            if ($request->hasFile("soals.{$index}.gambar_opsi_e")) {
                $file = $request->file("soals.{$index}.gambar_opsi_e");
                $path = $this->uploadSoalImage($file, 'opt_e');
                if ($path) {
                    $opsiE = (($opsiE && $opsiE !== '') ? $opsiE . '<br>' : '') . '<img src="' . $path . '" style="max-height:150px; max-width:100%; height:auto; display:block;" />';
                }
            }

            if ($jenis_soal === 'TKP') {
                $option_points = [
                    'A' => (int)($soal['option_points_a'] ?? 0),
                    'B' => (int)($soal['option_points_b'] ?? 0),
                    'C' => (int)($soal['option_points_c'] ?? 0),
                    'D' => (int)($soal['option_points_d'] ?? 0),
                    'E' => isset($soal['opsi_e']) ? (int)($soal['option_points_e'] ?? 0) : 0,
                ];
                // default jawaban_benar ke poin tertinggi
                $maxPt = -1;
                foreach ($option_points as $optK => $val) {
                    if ($val > $maxPt) {
                        $maxPt = $val;
                        $jawaban_benar = strtolower($optK);
                    }
                }
            }

            BankSoal::create([
                'kategori_id' => $request->kategori_id,
                'pertanyaan' => $pertanyaanText,
                'opsi_a' => $opsiA,
                'opsi_b' => $opsiB,
                'opsi_c' => $opsiC,
                'opsi_d' => $opsiD,
                'opsi_e' => $opsiE,
                'jawaban_benar' => $jawaban_benar,
                'jenis_soal' => $jenis_soal,
                'option_points' => $option_points,
            ]);
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
        $rules = [
            'pertanyaan' => 'required_without:gambar_pertanyaan|nullable|string',
            'gambar_pertanyaan' => 'nullable|image|max:5120',
            'opsi_a' => 'required_without:gambar_opsi_a|nullable|string',
            'gambar_opsi_a' => 'nullable|image|max:2048',
            'opsi_b' => 'required_without:gambar_opsi_b|nullable|string',
            'gambar_opsi_b' => 'nullable|image|max:2048',
            'opsi_c' => 'required_without:gambar_opsi_c|nullable|string',
            'gambar_opsi_c' => 'nullable|image|max:2048',
            'opsi_d' => 'required_without:gambar_opsi_d|nullable|string',
            'gambar_opsi_d' => 'nullable|image|max:2048',
            'opsi_e' => 'nullable|string',
            'gambar_opsi_e' => 'nullable|image|max:2048',
            'jenis_soal' => 'required|string|in:TWK,TIU,TKP',
        ];

        if ($request->jenis_soal === 'TKP') {
            $rules['option_points_a'] = 'required|integer|min:1|max:5';
            $rules['option_points_b'] = 'required|integer|min:1|max:5';
            $rules['option_points_c'] = 'required|integer|min:1|max:5';
            $rules['option_points_d'] = 'required|integer|min:1|max:5';
            $rules['option_points_e'] = 'nullable|integer|min:1|max:5';
        } else {
            $rules['jawaban_benar'] = 'required|string|in:a,b,c,d,e';
        }

        $request->validate($rules);

        $bankSoal = BankSoal::findOrFail($id);

        $option_points = null;
        $jawaban_benar = $request->jawaban_benar;

        if ($request->jenis_soal === 'TKP') {
            $option_points = [
                'A' => (int)$request->option_points_a,
                'B' => (int)$request->option_points_b,
                'C' => (int)$request->option_points_c,
                'D' => (int)$request->option_points_d,
                'E' => $request->filled('opsi_e') ? (int)$request->option_points_e : 0,
            ];
            // Find option with max point
            $maxPointVal = -1;
            foreach ($option_points as $optKey => $val) {
                if ($val > $maxPointVal) {
                    $maxPointVal = $val;
                    $jawaban_benar = strtolower($optKey);
                }
            }
        }

        // Process image uploads & deletes for update
        $pertanyaanText = $request->pertanyaan ?? '';
        if ($request->hasFile('gambar_pertanyaan')) {
            $this->deleteExistingImage($bankSoal->pertanyaan);
            $path = $this->uploadSoalImage($request->file('gambar_pertanyaan'), 'q');
            if ($path) {
                $pertanyaanText .= ($pertanyaanText ? '<br>' : '') . '<img src="' . $path . '" style="max-width:100%; height:auto; display:block; margin-top:10px;" />';
            }
        } elseif ($request->has('hapus_gambar_pertanyaan')) {
            $this->deleteExistingImage($bankSoal->pertanyaan);
            $pertanyaanText = preg_replace('/<img[^>]+>/i', '', $pertanyaanText);
        } else {
            // Keep existing image if present in the model
            if (preg_match('/<img[^>]+>/i', $bankSoal->pertanyaan, $m)) {
                if (!preg_match('/<img[^>]+>/i', $pertanyaanText)) {
                    $pertanyaanText .= ($pertanyaanText ? '<br>' : '') . $m[0];
                }
            }
        }

        $opsiA = $request->opsi_a ?? '';
        if ($request->hasFile('gambar_opsi_a')) {
            $this->deleteExistingImage($bankSoal->opsi_a);
            $path = $this->uploadSoalImage($request->file('gambar_opsi_a'), 'opt_a');
            if ($path) {
                $opsiA = ($opsiA ? $opsiA . '<br>' : '') . '<img src="' . $path . '" style="max-height:150px; max-width:100%; height:auto; display:block;" />';
            }
        } elseif ($request->has('hapus_gambar_opsi_a')) {
            $this->deleteExistingImage($bankSoal->opsi_a);
            $opsiA = preg_replace('/<img[^>]+>/i', '', $opsiA);
        } else {
            if (preg_match('/<img[^>]+>/i', $bankSoal->opsi_a, $m)) {
                if (!preg_match('/<img[^>]+>/i', $opsiA)) {
                    $opsiA .= ($opsiA ? $opsiA . '<br>' : '') . $m[0];
                }
            }
        }

        $opsiB = $request->opsi_b ?? '';
        if ($request->hasFile('gambar_opsi_b')) {
            $this->deleteExistingImage($bankSoal->opsi_b);
            $path = $this->uploadSoalImage($request->file('gambar_opsi_b'), 'opt_b');
            if ($path) {
                $opsiB = ($opsiB ? $opsiB . '<br>' : '') . '<img src="' . $path . '" style="max-height:150px; max-width:100%; height:auto; display:block;" />';
            }
        } elseif ($request->has('hapus_gambar_opsi_b')) {
            $this->deleteExistingImage($bankSoal->opsi_b);
            $opsiB = preg_replace('/<img[^>]+>/i', '', $opsiB);
        } else {
            if (preg_match('/<img[^>]+>/i', $bankSoal->opsi_b, $m)) {
                if (!preg_match('/<img[^>]+>/i', $opsiB)) {
                    $opsiB .= ($opsiB ? $opsiB . '<br>' : '') . $m[0];
                }
            }
        }

        $opsiC = $request->opsi_c ?? '';
        if ($request->hasFile('gambar_opsi_c')) {
            $this->deleteExistingImage($bankSoal->opsi_c);
            $path = $this->uploadSoalImage($request->file('gambar_opsi_c'), 'opt_c');
            if ($path) {
                $opsiC = ($opsiC ? $opsiC . '<br>' : '') . '<img src="' . $path . '" style="max-height:150px; max-width:100%; height:auto; display:block;" />';
            }
        } elseif ($request->has('hapus_gambar_opsi_c')) {
            $this->deleteExistingImage($bankSoal->opsi_c);
            $opsiC = preg_replace('/<img[^>]+>/i', '', $opsiC);
        } else {
            if (preg_match('/<img[^>]+>/i', $bankSoal->opsi_c, $m)) {
                if (!preg_match('/<img[^>]+>/i', $opsiC)) {
                    $opsiC .= ($opsiC ? $opsiC . '<br>' : '') . $m[0];
                }
            }
        }

        $opsiD = $request->opsi_d ?? '';
        if ($request->hasFile('gambar_opsi_d')) {
            $this->deleteExistingImage($bankSoal->opsi_d);
            $path = $this->uploadSoalImage($request->file('gambar_opsi_d'), 'opt_d');
            if ($path) {
                $opsiD = ($opsiD ? $opsiD . '<br>' : '') . '<img src="' . $path . '" style="max-height:150px; max-width:100%; height:auto; display:block;" />';
            }
        } elseif ($request->has('hapus_gambar_opsi_d')) {
            $this->deleteExistingImage($bankSoal->opsi_d);
            $opsiD = preg_replace('/<img[^>]+>/i', '', $opsiD);
        } else {
            if (preg_match('/<img[^>]+>/i', $bankSoal->opsi_d, $m)) {
                if (!preg_match('/<img[^>]+>/i', $opsiD)) {
                    $opsiD .= ($opsiD ? $opsiD . '<br>' : '') . $m[0];
                }
            }
        }

        $opsiE = $request->opsi_e ?? null;
        if ($request->hasFile('gambar_opsi_e')) {
            $this->deleteExistingImage($bankSoal->opsi_e);
            $path = $this->uploadSoalImage($request->file('gambar_opsi_e'), 'opt_e');
            if ($path) {
                $opsiE = (($opsiE && $opsiE !== '') ? $opsiE . '<br>' : '') . '<img src="' . $path . '" style="max-height:150px; max-width:100%; height:auto; display:block;" />';
            }
        } elseif ($request->has('hapus_gambar_opsi_e')) {
            $this->deleteExistingImage($bankSoal->opsi_e);
            $opsiE = preg_replace('/<img[^>]+>/i', '', $opsiE);
        } else {
            if (preg_match('/<img[^>]+>/i', $bankSoal->opsi_e, $m)) {
                if (!preg_match('/<img[^>]+>/i', $opsiE)) {
                    $opsiE .= ($opsiE ? $opsiE . '<br>' : '') . $m[0];
                }
            }
        }

        $bankSoal->update([
            'pertanyaan' => $pertanyaanText,
            'opsi_a' => $opsiA,
            'opsi_b' => $opsiB,
            'opsi_c' => $opsiC,
            'opsi_d' => $opsiD,
            'opsi_e' => $opsiE,
            'jawaban_benar' => $jawaban_benar,
            'jenis_soal' => $request->jenis_soal,
            'option_points' => $option_points,
        ]);

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

    private function cleanPdfText($text)
    {
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $lines = explode("\n", $text);
        
        $cleanedLines = [];
        $currentLine = '';
        
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed === '') {
                if ($currentLine !== '') {
                    $cleanedLines[] = $currentLine;
                    $currentLine = '';
                }
                $cleanedLines[] = '';
                continue;
            }
            
            $isSpecial = false;
            $allowMerge = true;
            
            // 1. Table row (contains | or 3+ spaces)
            if (strpos($trimmed, '|') !== false || preg_match('/\s{3,}/', $trimmed)) {
                $isSpecial = true;
                $allowMerge = false;
            }
            // 2. Math fraction bar / line separator
            elseif (preg_match('/^[\-\_=\s\+\*\/]{3,}$/', $trimmed)) {
                $isSpecial = true;
                $allowMerge = false;
            }
            // 3. Starts with list/question marker, e.g. [1], 1., or bullets
            elseif (preg_match('/^\s*(?:\[\d+\]|\d+[.)\:\-\]]+|[\x{2022}\x{2023}\x{2043}\x{204C}\x{204D}\x{2219}\x{25E6}\x{25C6}\x{25C9}\x{25CB}\x{25A0}\x{25A1}\x{25AA}\x{25AB}\x{25B6}\x{25B8}\x{25C0}\x{25C2}\-\*•])\s+/u', $trimmed)) {
                $isSpecial = true;
                $allowMerge = true;
            }
            // 4. Starts with option marker like A. B. C. etc. or A (with space/punctuation)
            elseif (preg_match('/^\s*[A-J](?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-\s]+/i', $trimmed)) {
                $isSpecial = true;
                $allowMerge = true;
            }
            // 5. Starts with keyword
            elseif (preg_match('/^\s*(?:Kunci|Jawaban|Pembahasan|Penjelasan|Diskusi|Kumci)\b/i', $trimmed)) {
                $isSpecial = true;
                $allowMerge = true;
            }
            
            if ($isSpecial) {
                if ($currentLine !== '') {
                    $cleanedLines[] = $currentLine;
                    $currentLine = '';
                }
                
                if ($allowMerge) {
                    $currentLine = $line;
                } else {
                    $cleanedLines[] = $line;
                }
            } else {
                if ($currentLine === '') {
                    $currentLine = $line;
                } else {
                    if (substr($currentLine, -1) === '-') {
                        $currentLine = rtrim($currentLine, '-') . $trimmed;
                    } else {
                        $currentLine .= ' ' . $trimmed;
                    }
                }
            }
        }
        
        if ($currentLine !== '') {
            $cleanedLines[] = $currentLine;
        }
        
        return implode("\n", $cleanedLines);
    }

    public function importPdf(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori_bank_soals,id',
            'pdf_file'    => 'required|file|mimes:pdf|max:10240',
        ]);

        $kategori_id = $request->kategori_id;
        $file        = $request->file('pdf_file');

        $kategori = \App\Models\KategoriBankSoal::find($kategori_id);
        $catName = $kategori ? $kategori->nama_kategori : '';
        $isCategoryTkp = (stripos($catName, 'TKP') !== false || stripos($catName, 'Karakteristik') !== false);
        $isCategoryTiu = (stripos($catName, 'TIU') !== false || stripos($catName, 'Intelegensi') !== false);
        
        $defaultJenis = 'TWK';
        if ($isCategoryTiu) {
            $defaultJenis = 'TIU';
        } elseif ($isCategoryTkp) {
            $defaultJenis = 'TKP';
        }

        try {
            // ----- 1. Extract raw text from PDF -----
            $parser = new \Smalot\PdfParser\Parser();
            $pdf    = $parser->parseFile($file->getPathname());
            $text   = $pdf->getText();

            // Normalize and clean text linebreaks
            $text = $this->cleanPdfText($text);

            // ----- 2. ROBUST BLOCK SPLITTING -----
            // Use preg_match_all to find the start position of every [N] marker,
            // then slice the text between consecutive anchors.
            // This approach is immune to extra whitespace/newlines before [N].
            $blocks = [];

            preg_match_all('/(?:^|[\s\n\xA0\x{00a0}\x{2007}\x{202f}])(\[\s*\d+\s*\])/mu', $text, $anchorMatches, PREG_OFFSET_CAPTURE);

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
                $rawBlocks  = preg_split('/(?:\n\s*)(?=\[\s*\d+\s*\])/u', $text);
                $lastQNum   = 0;
                foreach ($rawBlocks as $block) {
                    $block = trim($block);
                    if (empty($block)) continue;
                    if (preg_match('/^\[\s*(\d+)\s*\]/u', $block, $m)) {
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

                if (preg_match('/^\[\s*(\d+)\s*\]/u', $block, $m)) {
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
                if (empty($blockTrimmed) || !preg_match('/^\[\s*\d+\s*\]/u', $blockTrimmed)) {
                    continue; // Skip non-question blocks
                }

                // Get the question number
                preg_match('/^\[\s*(\d+)\s*\]/u', $blockTrimmed, $m);
                $qNum = (int)$m[1];

                // Auto-filter based on CPNS SKD standard ranges if applicable
                if ($defaultJenis === 'TWK' && ($qNum < 1 || $qNum > 30)) {
                    continue;
                }
                if ($defaultJenis === 'TIU' && ($qNum < 31 || $qNum > 65)) {
                    continue;
                }
                if ($defaultJenis === 'TKP' && ($qNum < 66 || $qNum > 110)) {
                    continue;
                }

                // Remove the [N] prefix and any dot/spaces right after it
                $blockClean = preg_replace('/^\[\s*\d+\s*\][.)\:\-\s]*/u', '', $blockTrimmed);

                // Strip Pembahasan/Penjelasan block from question text (colon/hyphen/dot is optional)
                $pembahasan = '';
                if (preg_match('/(?:^|[\r\n])\s*(?:Pembahasan)\s*[:\-.]?\s*(.*)$/si', $blockClean, $pembMatches)) {
                    $pembahasan = trim($pembMatches[1]);
                    $blockClean = preg_replace('/(?:^|[\r\n])\s*(?:Pembahasan)\s*[:\-.]?\s*(.*)$/si', '', $blockClean);
                }

            // Detect alternative option letters (F–J for some CPNS question formats)
                $la = 'A'; $lb = 'B'; $lc = 'C'; $ld = 'D'; $le = 'E';
                if (preg_match('/\bF[.)\:\-]?[\s\xA0]+/u', $blockClean) &&
                     preg_match('/\bG[.)\:\-]?[\s\xA0]+/u', $blockClean)) {
                    $la = 'F'; $lb = 'G'; $lc = 'H'; $ld = 'I'; $le = 'J';
                }

                // Build option patterns (flexible: A. or A) or A: or A-)
                $pa  = '(?:(?:^|[\r\n])\s*' . $la . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-\s]+|\b' . $la . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-])';
                $pb  = '(?:(?:^|[\r\n])\s*' . $lb . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-\s]+|\b' . $lb . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-])';
                $pc  = '(?:(?:^|[\r\n])\s*' . $lc . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-\s]+|\b' . $lc . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-])';
                $pd  = '(?:(?:^|[\r\n])\s*' . $ld . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-\s]+|\b' . $ld . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-])';
                $pe  = '(?:(?:^|[\r\n])\s*' . $le . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-\s]+|\b' . $le . '(?:\s*\(\s*\d+\s*poin\s*\))?[.)\:\-])';
                $pk  = '\b(?:Kunci|Jawaban|Kunci Jawaban)\s*[:\-\.]?\s*';

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

                // Option A
                if (preg_match('/' . $pa . '(.*?)(?=' . $pb . ')/sui', $blockClean, $m)) {
                    $optionA = trim($m[1]);
                }

                // Option B
                if (preg_match('/' . $pb . '(.*?)(?=' . $pc . ')/sui', $blockClean, $m)) {
                    $optionB = trim($m[1]);
                }

                // Option C
                if (preg_match('/' . $pc . '(.*?)(?=' . $pd . ')/sui', $blockClean, $m)) {
                    $optionC = trim($m[1]);
                }

                // Option D + E (detect whether E exists)
                $hasE = (bool) preg_match('/' . $pe . '/ui', $blockClean);
                if ($hasE) {
                    if (preg_match('/' . $pd . '(.*?)(?=' . $pe . ')/sui', $blockClean, $m)) {
                        $optionD = trim($m[1]);
                    }
                    if (preg_match('/' . $pe . '(.*?)(?=' . $pk . '|$)/sui', $blockClean, $m)) {
                        $optionE = trim(preg_replace('/(?:Kunci|Jawaban|Kunci Jawaban)\s*[:\-.]?\s*[A-Z]/i', '', $m[1]));
                    }
                } else {
                    if (preg_match('/' . $pd . '(.*?)(?=' . $pk . '|$)/sui', $blockClean, $m)) {
                        $optionD = trim($m[1]);
                    }
                    $optionE = null;
                }

                // Clean stray key references from option D
                $optionD = trim(preg_replace('/(?:Kunci|Jawaban|Kunci Jawaban)\s*[:\-.]?\s*[A-Z]/i', '', $optionD));

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

                    // Split inline statement markers (e.g. "Nusa  1. Pak Ahmad")
                    $inlinePattern = '/(\s{2,})(?=(?:\d+[.)\:\-]+|\[\s*\d+\s*\]|[\x{2022}\x{2023}\x{2043}\x{204C}\x{204D}\x{2219}\x{25E6}\x{25C6}\x{25C9}\x{25CB}\x{25A0}\x{25A1}\x{25AA}\x{25AB}\x{25B6}\x{25B8}\x{25C0}\x{25C2}\-\*•]\s+))/u';
                    if (preg_match($inlinePattern, $optVal, $matches, PREG_OFFSET_CAPTURE)) {
                        $offset = $matches[0][1];
                        $firstPart = substr($optVal, 0, $offset);
                        $secondPart = substr($optVal, $offset + strlen($matches[0][0]));
                        $optVal = trim($firstPart);
                        $linesInput = $secondPart;
                    } else {
                        $linesInput = $optVal;
                        $optVal = '';
                    }

                    $lines = explode("\n", $linesInput);
                    $newOptLines = [];
                    $bulletLines = [];

                    foreach ($lines as $line) {
                        $trimmedLine = trim($line);
                        if (empty($trimmedLine)) continue;
                        
                        if (preg_match('/^[\x{2022}\x{2023}\x{2043}\x{204C}\x{204D}\x{2219}\x{25E6}\x{25C6}\x{25C9}\x{25CB}\x{25A0}\x{25A1}\x{25AA}\x{25AB}\x{25B6}\x{25B8}\x{25C0}\x{25C2}\-\*•]/u', $trimmedLine) || 
                            preg_match('/^\d+[.)\:\-]+/u', $trimmedLine) || 
                            preg_match('/^\[\s*\d+\s*\]/u', $trimmedLine) || 
                            preg_match('/^\(\s*\d+\s*\)/u', $trimmedLine) ||
                            (!empty($bulletLines) && !preg_match('/^[A-Z][.)\:\-]/i', $trimmedLine))) {
                            $bulletLines[] = $line;
                        } else {
                            $newOptLines[] = $line;
                        }
                    }

                    if (!empty($newOptLines)) {
                        $optVal = trim(($optVal !== '' ? $optVal . "\n" : '') . implode("\n", $newOptLines));
                    }
                    if (!empty($bulletLines)) {
                        $extractedBullets[] = implode("\n", $bulletLines);
                    }
                }

                if (!empty($extractedBullets)) {
                    $questionText .= "\n\n" . implode("\n", $extractedBullets);
                }

                // Removed HTML placeholder injection for empty question text
                // Let the database store empty string or simple [Soal Bergambar] tags

                // Removed HTML placeholder injection for empty options
                // They will remain empty or as [Gambar X] tags

                // ----- Detect correct answer key -----
                if (preg_match('/(?:Kunci|Jawaban|Kunci Jawaban|Kumci)\s*[:\-\.]?\s*([A-Z])\b/i', $blockTrimmed, $keyMatch)) {
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
                    $pemClean = trim(preg_replace('/(?:Kunci|Jawaban|Kunci Jawaban)\s*[:\-.]?\s*[A-Z]/i', '', $pembahasan));
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

                // Ekstraksi poin opsi TKP jika ada penanda skor (misal "A (5 poin)")
                $optionPoints = null;
                $isTkp = $isCategoryTkp;
                $extractedPoints = [];

                foreach (['a' => $optionA, 'b' => $optionB, 'c' => $optionC, 'd' => $optionD, 'e' => $optionE] as $key => $optVal) {
                    if (empty($optVal)) continue;
                    if (preg_match('/\(\s*(\d+)\s*poin\s*\)/ui', $optVal, $pm)) {
                        $isTkp = true;
                        $extractedPoints[strtoupper($key)] = (int)$pm[1];
                    }
                }

                if ($isTkp) {
                    $optionPoints = [
                        'A' => $extractedPoints['A'] ?? ($isTkp ? 5 : 0),
                        'B' => $extractedPoints['B'] ?? ($isTkp ? 4 : 0),
                        'C' => $extractedPoints['C'] ?? ($isTkp ? 3 : 0),
                        'D' => $extractedPoints['D'] ?? ($isTkp ? 2 : 0),
                        'E' => isset($optionE) ? ($extractedPoints['E'] ?? ($isTkp ? 1 : 0)) : 0,
                    ];
                    
                    // Bersihkan opsi dari teks "(N poin)"
                    $optionA = trim(preg_replace('/\s*\(\s*\d+\s*poin\s*\)/ui', '', $optionA));
                    $optionB = trim(preg_replace('/\s*\(\s*\d+\s*poin\s*\)/ui', '', $optionB));
                    $optionC = trim(preg_replace('/\s*\(\s*\d+\s*poin\s*\)/ui', '', $optionC));
                    $optionD = trim(preg_replace('/\s*\(\s*\d+\s*poin\s*\)/ui', '', $optionD));
                    if (!empty($optionE)) {
                        $optionE = trim(preg_replace('/\s*\(\s*\d+\s*poin\s*\)/ui', '', $optionE));
                    }

                    // Tentukan jawaban_benar berdasarkan poin tertinggi
                    $maxPt = -1;
                    foreach ($optionPoints as $optK => $val) {
                        if ($val > $maxPt) {
                            $maxPt = $val;
                            $correctAnswer = strtolower($optK);
                        }
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
                    'jenis_soal'    => $isTkp ? 'TKP' : $defaultJenis,
                    'option_points' => $optionPoints,
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

    public function destroyAll(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori_bank_soals,id',
        ]);

        $kategori_id = $request->kategori_id;
        $kategori = KategoriBankSoal::findOrFail($kategori_id);
        
        BankSoal::where('kategori_id', $kategori_id)->delete();

        return redirect()->route('admin.bank-soal.index', ['kategori_id' => $kategori_id])
            ->with('success', 'Semua soal pada kategori ' . $kategori->nama_kategori . ' berhasil dihapus.');
    }
}
