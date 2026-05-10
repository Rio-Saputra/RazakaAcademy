<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        return view('user.profil', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'old_password' => 'nullable|string',
            'password' => 'nullable|string|min:6'
        ]);

        $user->name = $request->name;
        
        if ($request->filled('password')) {
            if (!$request->filled('old_password')) {
                return redirect()->back()->with('error', 'Silakan masukkan Password Lama Anda untuk mengganti password.');
            }
            if (!Hash::check($request->old_password, $user->password)) {
                return redirect()->back()->with('error', 'Password Lama yang Anda masukkan salah.');
            }
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
