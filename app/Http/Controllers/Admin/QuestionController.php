<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Tryout;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
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

    public function index(Request $request)
    {
        $tryouts = Tryout::all();
        $tryout_id = $request->get('tryout_id');
        $questions = [];

        if ($tryout_id) {
            $questions = Question::where('tryout_id', $tryout_id)->get();
        }

        $bankSoals = \App\Models\BankSoal::with('kategori')->latest()->get();
        $kategoris = \App\Models\KategoriBankSoal::all();

        return view('admin.soal', compact('questions', 'tryouts', 'tryout_id', 'bankSoals', 'kategoris'));
    }

    // CREATE
    public function create(Request $request)
    {
        $tryout_id = $request->get('tryout_id');

        return view('admin.tambah_soal', [
            'tryout_id' => $tryout_id,
            'question' => null // biar blade aman
        ]);
    }

    public function store(Request $request)
    {
        // =========================
        // MODE SELEKSI DARI BANK SOAL
        // =========================
        if ($request->has('bank_soal_ids')) {
            $request->validate([
                'tryout_id' => 'required|exists:tryouts,id',
                'bank_soal_ids' => 'required|array|min:1',
                'bank_soal_ids.*' => 'exists:bank_soals,id',
            ]);

            $inserted = 0;
            foreach ($request->bank_soal_ids as $id) {
                $soal = \App\Models\BankSoal::find($id);
                if ($soal) {
                    Question::create([
                        'tryout_id' => $request->tryout_id,
                        'question_text' => $soal->pertanyaan,
                        'option_a' => $soal->opsi_a,
                        'option_b' => $soal->opsi_b,
                        'option_c' => $soal->opsi_c,
                        'option_d' => $soal->opsi_d,
                        'option_e' => $soal->opsi_e,
                        'correct_answer' => strtoupper($soal->jawaban_benar),
                        'jenis_soal' => $soal->jenis_soal ?? 'TWK',
                        'option_points' => $soal->option_points,
                    ]);
                    $inserted++;
                }
            }

            return redirect()
                ->route('admin.soal.index', ['tryout_id' => $request->tryout_id])
                ->with('success', "Berhasil menambahkan $inserted soal dari Bank Soal!");
        }

        // =========================
        // MODE MULTI INPUT
        // =========================
        if (is_array($request->questions)) {

            foreach ($request->questions as $index => $q) {
                $has_q = !empty($q['question_text']) || $request->hasFile("questions.{$index}.gambar_pertanyaan");
                $has_a = !empty($q['option_a']) || $request->hasFile("questions.{$index}.gambar_option_a");
                $has_b = !empty($q['option_b']) || $request->hasFile("questions.{$index}.gambar_option_b");
                $has_c = !empty($q['option_c']) || $request->hasFile("questions.{$index}.gambar_option_c");
                $has_d = !empty($q['option_d']) || $request->hasFile("questions.{$index}.gambar_option_d");

                if (!$has_q || !$has_a || !$has_b || !$has_c || !$has_d) {
                    continue;
                }

                $jenis_soal = $q['jenis_soal'] ?? 'TWK';
                $option_points = null;
                $correct_answer = $q['correct_answer'] ?? 'A';

                // Process Question Image
                $questionText = $q['question_text'] ?? '';
                if ($request->hasFile("questions.{$index}.gambar_pertanyaan")) {
                    $file = $request->file("questions.{$index}.gambar_pertanyaan");
                    $path = $this->uploadSoalImage($file, 'q');
                    if ($path) {
                        $questionText .= ($questionText ? '<br>' : '') . '<img src="' . $path . '" style="max-width:100%; height:auto; display:block; margin-top:10px;" />';
                    }
                }

                // Process Option Images
                $optionA = $q['option_a'] ?? '';
                if ($request->hasFile("questions.{$index}.gambar_option_a")) {
                    $file = $request->file("questions.{$index}.gambar_option_a");
                    $path = $this->uploadSoalImage($file, 'opt_a');
                    if ($path) {
                        $optionA = ($optionA ? $optionA . '<br>' : '') . '<img src="' . $path . '" style="max-height:150px; max-width:100%; height:auto; display:block;" />';
                    }
                }

                $optionB = $q['option_b'] ?? '';
                if ($request->hasFile("questions.{$index}.gambar_option_b")) {
                    $file = $request->file("questions.{$index}.gambar_option_b");
                    $path = $this->uploadSoalImage($file, 'opt_b');
                    if ($path) {
                        $optionB = ($optionB ? $optionB . '<br>' : '') . '<img src="' . $path . '" style="max-height:150px; max-width:100%; height:auto; display:block;" />';
                    }
                }

                $optionC = $q['option_c'] ?? '';
                if ($request->hasFile("questions.{$index}.gambar_option_c")) {
                    $file = $request->file("questions.{$index}.gambar_option_c");
                    $path = $this->uploadSoalImage($file, 'opt_c');
                    if ($path) {
                        $optionC = ($optionC ? $optionC . '<br>' : '') . '<img src="' . $path . '" style="max-height:150px; max-width:100%; height:auto; display:block;" />';
                    }
                }

                $optionD = $q['option_d'] ?? '';
                if ($request->hasFile("questions.{$index}.gambar_option_d")) {
                    $file = $request->file("questions.{$index}.gambar_option_d");
                    $path = $this->uploadSoalImage($file, 'opt_d');
                    if ($path) {
                        $optionD = ($optionD ? $optionD . '<br>' : '') . '<img src="' . $path . '" style="max-height:150px; max-width:100%; height:auto; display:block;" />';
                    }
                }

                $optionE = $q['option_e'] ?? null;
                if ($request->hasFile("questions.{$index}.gambar_option_e")) {
                    $file = $request->file("questions.{$index}.gambar_option_e");
                    $path = $this->uploadSoalImage($file, 'opt_e');
                    if ($path) {
                        $optionE = (($optionE && $optionE !== '') ? $optionE . '<br>' : '') . '<img src="' . $path . '" style="max-height:150px; max-width:100%; height:auto; display:block;" />';
                    }
                }

                if ($jenis_soal === 'TKP') {
                    $option_points = [
                        'A' => (int)($q['option_points_a'] ?? 0),
                        'B' => (int)($q['option_points_b'] ?? 0),
                        'C' => (int)($q['option_points_c'] ?? 0),
                        'D' => (int)($q['option_points_d'] ?? 0),
                        'E' => isset($q['option_e']) ? (int)($q['option_points_e'] ?? 0) : 0,
                    ];
                    // Set correct_answer to highest point option
                    $maxPointVal = -1;
                    foreach ($option_points as $optKey => $val) {
                        if ($val > $maxPointVal) {
                            $maxPointVal = $val;
                            $correct_answer = $optKey;
                        }
                    }
                }

                Question::create([
                    'tryout_id' => $request->tryout_id,
                    'question_text' => $questionText,
                    'option_a' => $optionA,
                    'option_b' => $optionB,
                    'option_c' => $optionC,
                    'option_d' => $optionD,
                    'option_e' => $optionE,
                    'correct_answer' => $correct_answer,
                    'jenis_soal' => $jenis_soal,
                    'option_points' => $option_points,
                ]);
            }

            return redirect()
                ->route('admin.soal.index', ['tryout_id' => $request->tryout_id])
                ->with('success', 'Semua soal berhasil ditambahkan!');
        }

        // =========================
        // MODE SINGLE
        // =========================
        $rules = [
            'tryout_id' => 'required',
            'question_text' => 'required_without:gambar_pertanyaan|nullable|string',
            'gambar_pertanyaan' => 'nullable|image|max:5120',
            'option_a' => 'required_without:gambar_option_a|nullable|string',
            'gambar_option_a' => 'nullable|image|max:2048',
            'option_b' => 'required_without:gambar_option_b|nullable|string',
            'gambar_option_b' => 'nullable|image|max:2048',
            'option_c' => 'required_without:gambar_option_c|nullable|string',
            'gambar_option_c' => 'nullable|image|max:2048',
            'option_d' => 'required_without:gambar_option_d|nullable|string',
            'gambar_option_d' => 'nullable|image|max:2048',
            'option_e' => 'nullable|string',
            'gambar_option_e' => 'nullable|image|max:2048',
            'jenis_soal' => 'required|in:TWK,TIU,TKP',
        ];

        if ($request->jenis_soal === 'TKP') {
            $rules['option_points_a'] = 'required|integer|min:1|max:5';
            $rules['option_points_b'] = 'required|integer|min:1|max:5';
            $rules['option_points_c'] = 'required|integer|min:1|max:5';
            $rules['option_points_d'] = 'required|integer|min:1|max:5';
            $rules['option_points_e'] = 'nullable|integer|min:1|max:5';
        } else {
            $rules['correct_answer'] = 'required|in:A,B,C,D,E';
        }

        $request->validate($rules);

        $option_points = null;
        $correct_answer = $request->correct_answer;

        if ($request->jenis_soal === 'TKP') {
            $option_points = [
                'A' => (int)$request->option_points_a,
                'B' => (int)$request->option_points_b,
                'C' => (int)$request->option_points_c,
                'D' => (int)$request->option_points_d,
                'E' => $request->filled('option_e') ? (int)$request->option_points_e : 0,
            ];
            // Find option with max point
            $maxPointVal = -1;
            foreach ($option_points as $optKey => $val) {
                if ($val > $maxPointVal) {
                    $maxPointVal = $val;
                    $correct_answer = $optKey;
                }
            }
        }

        $questionText = $request->question_text ?? '';
        if ($request->hasFile('gambar_pertanyaan')) {
            $path = $this->uploadSoalImage($request->file('gambar_pertanyaan'), 'q');
            if ($path) {
                $questionText .= ($questionText ? '<br>' : '') . '<img src="' . $path . '" style="max-width:100%; height:auto; display:block; margin-top:10px;" />';
            }
        }

        $optionA = $request->option_a ?? '';
        if ($request->hasFile('gambar_option_a')) {
            $path = $this->uploadSoalImage($request->file('gambar_option_a'), 'opt_a');
            if ($path) {
                $optionA = ($optionA ? $optionA . '<br>' : '') . '<img src="' . $path . '" style="max-height:150px; max-width:100%; height:auto; display:block;" />';
            }
        }

        $optionB = $request->option_b ?? '';
        if ($request->hasFile('gambar_option_b')) {
            $path = $this->uploadSoalImage($request->file('gambar_option_b'), 'opt_b');
            if ($path) {
                $optionB = ($optionB ? $optionB . '<br>' : '') . '<img src="' . $path . '" style="max-height:150px; max-width:100%; height:auto; display:block;" />';
            }
        }

        $optionC = $request->option_c ?? '';
        if ($request->hasFile('gambar_option_c')) {
            $path = $this->uploadSoalImage($request->file('gambar_option_c'), 'opt_c');
            if ($path) {
                $optionC = ($optionC ? $optionC . '<br>' : '') . '<img src="' . $path . '" style="max-height:150px; max-width:100%; height:auto; display:block;" />';
            }
        }

        $optionD = $request->option_d ?? '';
        if ($request->hasFile('gambar_option_d')) {
            $path = $this->uploadSoalImage($request->file('gambar_option_d'), 'opt_d');
            if ($path) {
                $optionD = ($optionD ? $optionD . '<br>' : '') . '<img src="' . $path . '" style="max-height:150px; max-width:100%; height:auto; display:block;" />';
            }
        }

        $optionE = $request->option_e ?? null;
        if ($request->hasFile('gambar_option_e')) {
            $path = $this->uploadSoalImage($request->file('gambar_option_e'), 'opt_e');
            if ($path) {
                $optionE = (($optionE && $optionE !== '') ? $optionE . '<br>' : '') . '<img src="' . $path . '" style="max-height:150px; max-width:100%; height:auto; display:block;" />';
            }
        }

        Question::create([
            'tryout_id' => $request->tryout_id,
            'question_text' => $questionText,
            'option_a' => $optionA,
            'option_b' => $optionB,
            'option_c' => $optionC,
            'option_d' => $optionD,
            'option_e' => $optionE,
            'correct_answer' => $correct_answer,
            'jenis_soal' => $request->jenis_soal,
            'option_points' => $option_points,
        ]);

        return redirect()
            ->route('admin.soal.index', ['tryout_id' => $request->tryout_id])
            ->with('success', 'Soal berhasil ditambahkan');
    }

    // EDIT
    public function edit($id)
    {
        $question = Question::findOrFail($id);
        $tryout_id = $question->tryout_id;

        return view('admin.tambah_soal', [
            'question' => $question,
            'tryout_id' => $tryout_id
        ]);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $question = Question::findOrFail($id);

        $rules = [
            'question_text' => 'required_without:gambar_pertanyaan|nullable|string',
            'gambar_pertanyaan' => 'nullable|image|max:5120',
            'option_a' => 'required_without:gambar_option_a|nullable|string',
            'gambar_option_a' => 'nullable|image|max:2048',
            'option_b' => 'required_without:gambar_option_b|nullable|string',
            'gambar_option_b' => 'nullable|image|max:2048',
            'option_c' => 'required_without:gambar_option_c|nullable|string',
            'gambar_option_c' => 'nullable|image|max:2048',
            'option_d' => 'required_without:gambar_option_d|nullable|string',
            'gambar_option_d' => 'nullable|image|max:2048',
            'option_e' => 'nullable|string',
            'gambar_option_e' => 'nullable|image|max:2048',
            'jenis_soal' => 'required|in:TWK,TIU,TKP',
        ];

        if ($request->jenis_soal === 'TKP') {
            $rules['option_points_a'] = 'required|integer|min:1|max:5';
            $rules['option_points_b'] = 'required|integer|min:1|max:5';
            $rules['option_points_c'] = 'required|integer|min:1|max:5';
            $rules['option_points_d'] = 'required|integer|min:1|max:5';
            $rules['option_points_e'] = 'nullable|integer|min:1|max:5';
        } else {
            $rules['correct_answer'] = 'required|in:A,B,C,D,E';
        }

        $request->validate($rules);

        $option_points = null;
        $correct_answer = $request->correct_answer;

        if ($request->jenis_soal === 'TKP') {
            $option_points = [
                'A' => (int)$request->option_points_a,
                'B' => (int)$request->option_points_b,
                'C' => (int)$request->option_points_c,
                'D' => (int)$request->option_points_d,
                'E' => $request->filled('option_e') ? (int)$request->option_points_e : 0,
            ];
            // Find option with max point
            $maxPointVal = -1;
            foreach ($option_points as $optKey => $val) {
                if ($val > $maxPointVal) {
                    $maxPointVal = $val;
                    $correct_answer = $optKey;
                }
            }
        }

        // Process image uploads & deletes for update
        $questionText = $request->question_text ?? '';
        if ($request->hasFile('gambar_pertanyaan')) {
            $this->deleteExistingImage($question->question_text);
            $path = $this->uploadSoalImage($request->file('gambar_pertanyaan'), 'q');
            if ($path) {
                $questionText .= ($questionText ? '<br>' : '') . '<img src="' . $path . '" style="max-width:100%; height:auto; display:block; margin-top:10px;" />';
            }
        } elseif ($request->has('hapus_gambar_pertanyaan')) {
            $this->deleteExistingImage($question->question_text);
            $questionText = preg_replace('/<img[^>]+>/i', '', $questionText);
        } else {
            if (preg_match('/<img[^>]+>/i', $question->question_text, $m)) {
                if (!preg_match('/<img[^>]+>/i', $questionText)) {
                    $questionText .= ($questionText ? '<br>' : '') . $m[0];
                }
            }
        }

        $optionA = $request->option_a ?? '';
        if ($request->hasFile('gambar_option_a')) {
            $this->deleteExistingImage($question->option_a);
            $path = $this->uploadSoalImage($request->file('gambar_option_a'), 'opt_a');
            if ($path) {
                $optionA = ($optionA ? $optionA . '<br>' : '') . '<img src="' . $path . '" style="max-height:150px; max-width:100%; height:auto; display:block;" />';
            }
        } elseif ($request->has('hapus_gambar_option_a')) {
            $this->deleteExistingImage($question->option_a);
            $optionA = preg_replace('/<img[^>]+>/i', '', $optionA);
        } else {
            if (preg_match('/<img[^>]+>/i', $question->option_a, $m)) {
                if (!preg_match('/<img[^>]+>/i', $optionA)) {
                    $optionA .= ($optionA ? $optionA . '<br>' : '') . $m[0];
                }
            }
        }

        $optionB = $request->option_b ?? '';
        if ($request->hasFile('gambar_option_b')) {
            $this->deleteExistingImage($question->option_b);
            $path = $this->uploadSoalImage($request->file('gambar_option_b'), 'opt_b');
            if ($path) {
                $optionB = ($optionB ? $optionB . '<br>' : '') . '<img src="' . $path . '" style="max-height:150px; max-width:100%; height:auto; display:block;" />';
            }
        } elseif ($request->has('hapus_gambar_option_b')) {
            $this->deleteExistingImage($question->option_b);
            $optionB = preg_replace('/<img[^>]+>/i', '', $optionB);
        } else {
            if (preg_match('/<img[^>]+>/i', $question->option_b, $m)) {
                if (!preg_match('/<img[^>]+>/i', $optionB)) {
                    $optionB .= ($optionB ? $optionB . '<br>' : '') . $m[0];
                }
            }
        }

        $optionC = $request->option_c ?? '';
        if ($request->hasFile('gambar_option_c')) {
            $this->deleteExistingImage($question->option_c);
            $path = $this->uploadSoalImage($request->file('gambar_option_c'), 'opt_c');
            if ($path) {
                $optionC = ($optionC ? $optionC . '<br>' : '') . '<img src="' . $path . '" style="max-height:150px; max-width:100%; height:auto; display:block;" />';
            }
        } elseif ($request->has('hapus_gambar_option_c')) {
            $this->deleteExistingImage($question->option_c);
            $optionC = preg_replace('/<img[^>]+>/i', '', $optionC);
        } else {
            if (preg_match('/<img[^>]+>/i', $question->option_c, $m)) {
                if (!preg_match('/<img[^>]+>/i', $optionC)) {
                    $optionC .= ($optionC ? $optionC . '<br>' : '') . $m[0];
                }
            }
        }

        $optionD = $request->option_d ?? '';
        if ($request->hasFile('gambar_option_d')) {
            $this->deleteExistingImage($question->option_d);
            $path = $this->uploadSoalImage($request->file('gambar_option_d'), 'opt_d');
            if ($path) {
                $optionD = ($optionD ? $optionD . '<br>' : '') . '<img src="' . $path . '" style="max-height:150px; max-width:100%; height:auto; display:block;" />';
            }
        } elseif ($request->has('hapus_gambar_option_d')) {
            $this->deleteExistingImage($question->option_d);
            $optionD = preg_replace('/<img[^>]+>/i', '', $optionD);
        } else {
            if (preg_match('/<img[^>]+>/i', $question->option_d, $m)) {
                if (!preg_match('/<img[^>]+>/i', $optionD)) {
                    $optionD .= ($optionD ? $optionD . '<br>' : '') . $m[0];
                }
            }
        }

        $optionE = $request->option_e ?? null;
        if ($request->hasFile('gambar_option_e')) {
            $this->deleteExistingImage($question->option_e);
            $path = $this->uploadSoalImage($request->file('gambar_option_e'), 'opt_e');
            if ($path) {
                $optionE = (($optionE && $optionE !== '') ? $optionE . '<br>' : '') . '<img src="' . $path . '" style="max-height:150px; max-width:100%; height:auto; display:block;" />';
            }
        } elseif ($request->has('hapus_gambar_option_e')) {
            $this->deleteExistingImage($question->option_e);
            $optionE = preg_replace('/<img[^>]+>/i', '', $optionE);
        } else {
            if (preg_match('/<img[^>]+>/i', $question->option_e, $m)) {
                if (!preg_match('/<img[^>]+>/i', $optionE)) {
                    $optionE .= ($optionE ? $optionE . '<br>' : '') . $m[0];
                }
            }
        }

        $question->update([
            'question_text' => $questionText,
            'option_a' => $optionA,
            'option_b' => $optionB,
            'option_c' => $optionC,
            'option_d' => $optionD,
            'option_e' => $optionE,
            'correct_answer' => $correct_answer,
            'jenis_soal' => $request->jenis_soal,
            'option_points' => $option_points,
        ]);

        return redirect()
            ->route('admin.soal.index', ['tryout_id' => $question->tryout_id])
            ->with('success', 'Soal berhasil diupdate');
    }

    // DELETE
    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $tryout_id = $question->tryout_id;

        $question->delete();

        return redirect()
            ->route('admin.soal.index', ['tryout_id' => $tryout_id])
            ->with('success', 'Soal berhasil dihapus');
    }

    // EXPORT PDF
    public function exportPdf($tryout_id)
    {
        $tryout = Tryout::findOrFail($tryout_id);
        $questions = Question::where('tryout_id', $tryout_id)->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.soal_pdf', compact('tryout', 'questions'));
        
        $filename = 'soal-tryout-' . \Illuminate\Support\Str::slug($tryout->title) . '.pdf';
        return $pdf->download($filename);
    }
}