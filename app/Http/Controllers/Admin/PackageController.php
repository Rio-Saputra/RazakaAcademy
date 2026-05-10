<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::all();
        return view('admin.paket', compact('packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
        ]);
        Package::create($request->all());
        return redirect()->route('admin.paket.index')->with('success', 'Paket berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $package = Package::findOrFail($id);
        $package->update($request->all());
        return redirect()->route('admin.paket.index')->with('success', 'Paket berhasil diubah');
    }

    public function destroy($id)
    {
        Package::destroy($id);
        return redirect()->route('admin.paket.index')->with('success', 'Paket berhasil dihapus');
    }
}
