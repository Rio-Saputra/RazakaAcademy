<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriBankSoal;
use Illuminate\Http\Request;

class KategoriBankSoalController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.bank-soal.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string'
        ]);

        KategoriBankSoal::create($request->all());

        return redirect()->route('admin.kategori-bank-soal.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string'
        ]);

        $kategori = KategoriBankSoal::findOrFail($id);
        $kategori->update($request->all());

        return redirect()->route('admin.kategori-bank-soal.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kategori = KategoriBankSoal::findOrFail($id);
        $kategori->delete();

        return redirect()->route('admin.kategori-bank-soal.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
