<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Tryout;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
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
                        'correct_answer' => strtoupper($soal->jawaban_benar)
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

            foreach ($request->questions as $q) {

                if (
                    empty($q['question_text']) ||
                    empty($q['option_a']) ||
                    empty($q['option_b']) ||
                    empty($q['option_c']) ||
                    empty($q['option_d']) ||
                    empty($q['correct_answer'])
                ) continue;

                Question::create([
                    'tryout_id' => $request->tryout_id,
                    'question_text' => $q['question_text'],
                    'option_a' => $q['option_a'],
                    'option_b' => $q['option_b'],
                    'option_c' => $q['option_c'],
                    'option_d' => $q['option_d'],
                    'option_e' => isset($q['option_e']) ? $q['option_e'] : null,
                    'correct_answer' => $q['correct_answer'],
                ]);
            }

            return redirect()
                ->route('admin.soal.index', ['tryout_id' => $request->tryout_id])
                ->with('success', 'Semua soal berhasil ditambahkan!');
        }

        // =========================
        // MODE SINGLE
        // =========================
        $request->validate([
            'tryout_id' => 'required',
            'question_text' => 'required',
            'option_a' => 'required',
            'option_b' => 'required',
            'option_c' => 'required',
            'option_d' => 'required',
            'option_e' => 'nullable',
            'correct_answer' => 'required|in:A,B,C,D,E',
        ]);

        Question::create([
            'tryout_id' => $request->tryout_id,
            'question_text' => $request->question_text,
            'option_a' => $request->option_a,
            'option_b' => $request->option_b,
            'option_c' => $request->option_c,
            'option_d' => $request->option_d,
            'option_e' => $request->option_e,
            'correct_answer' => $request->correct_answer,
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

        $request->validate([
            'question_text' => 'required',
            'option_a' => 'required',
            'option_b' => 'required',
            'option_c' => 'required',
            'option_d' => 'required',
            'option_e' => 'nullable',
            'correct_answer' => 'required|in:A,B,C,D,E',
        ]);

        $question->update([
            'question_text' => $request->question_text,
            'option_a' => $request->option_a,
            'option_b' => $request->option_b,
            'option_c' => $request->option_c,
            'option_d' => $request->option_d,
            'option_e' => $request->option_e,
            'correct_answer' => $request->correct_answer,
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