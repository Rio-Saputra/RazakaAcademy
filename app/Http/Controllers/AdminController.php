<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Tryout;
use App\Models\Result;

class AdminController extends Controller
{
    public function dashboard()
    {
        $total_user = User::where('role', 'user')->count();
        $total_tryout = Tryout::count();
        $total_transaksi = Transaction::where('status', 'success')->count();
        $recent_activities = Result::with(['user', 'tryout'])->orderBy('created_at', 'desc')->take(5)->get();
        
        return view('admin.dashboard', compact('total_user', 'total_tryout', 'total_transaksi', 'recent_activities'));
    }

    public function kelolaUser()
    {
        $users = User::all();
        return view('admin.kelola_user', compact('users'));
    }
    
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }
        $user->delete();
        return redirect()->back()->with('success', 'User berhasil dihapus dari database.');
    }

    public function transaksi()
    {
        $transactions = Transaction::with(['user', 'package', 'tryout'])->get();
        return view('admin.transaksi', compact('transactions'));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('admin.profile', compact('user'));
    }

    public function updateProfile(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'old_password' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('old_password')) {
            if (!\Illuminate\Support\Facades\Hash::check($request->old_password, $user->password)) {
                return redirect()->back()->with('error', 'Password lama tidak sesuai.');
            }
            if ($request->filled('password')) {
                $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
            }
        }

        $user->save();

        return redirect()->route('admin.profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
