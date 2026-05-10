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
        
        if (!$kategori_id) {
            return redirect()->route('admin.kategori-bank-soal.index')->with('error', 'Silakan pilih kategori terlebih dahulu.');
        }

        $kategori = KategoriBankSoal::findOrFail($kategori_id);
        $query = BankSoal::where('kategori_id', $kategori_id);

        if ($request->has('search')) {
            $query->where('pertanyaan', 'like', '%' . $request->search . '%');
        }

        $bankSoals = $query->latest()->get();

        return view('admin.bank-soal.index', compact('bankSoals', 'kategori'));
    }

    public function create(Request $request)
    {
        $kategori = KategoriBankSoal::findOrFail($request->kategori_id);
        return view('admin.bank-soal.create', compact('kategori'));
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
            'soals.*.jawaban_benar' => 'required|string|in:a,b,c,d',
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
            'jawaban_benar' => 'required|string|in:a,b,c,d',
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
}
