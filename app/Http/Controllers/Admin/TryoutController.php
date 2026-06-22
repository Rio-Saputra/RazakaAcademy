<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tryout;
use App\Models\Package;
use Illuminate\Http\Request;

class TryoutController extends Controller
{
    public function index()
    {
        $tryouts = Tryout::with('package')->withCount('questions')->get();
        $packages = Package::all();
        $kategoris = \App\Models\KategoriBankSoal::all();
        return view('admin.tryout', compact('tryouts', 'packages', 'kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'package_id' => 'required',
            'duration' => 'required|integer',
            'batas_pengerjaan' => 'required|integer|min:1',
        ]);
        Tryout::create($request->all());
        return redirect()->route('admin.tryout.index')->with('success', 'Tryout berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $tryout = Tryout::findOrFail($id);
        $tryout->update($request->all());
        return redirect()->route('admin.tryout.index')->with('success', 'Tryout berhasil diupdate');
    }

    public function destroy($id)
    {
        Tryout::destroy($id);
        return redirect()->route('admin.tryout.index')->with('success', 'Tryout berhasil dihapus');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'tryout_id' => 'required|exists:tryouts,id',
            'kategori_id' => 'required|exists:kategori_bank_soals,id',
        ]);

        $query = \App\Models\BankSoal::query();
        
        $query->where('kategori_id', $request->kategori_id);

        $bankSoals = $query->inRandomOrder()->get();

        if ($bankSoals->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada soal di Bank Soal untuk kategori tersebut.');
        }

        $inserted = 0;
        foreach ($bankSoals as $soal) {
            // Check to avoid duplicates maybe? Or just insert.
            // For now, simple insert.
            \App\Models\Question::create([
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

        return redirect()->back()->with('success', "Berhasil men-generate $inserted soal dari Bank Soal.");
    }
}
