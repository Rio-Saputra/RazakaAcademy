<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function paket()
    {
        $packages = Package::all();
        return view('user.paket', compact('packages'));
    }

    public function beli($id)
    {
        $user = Auth::user();
        $package = Package::findOrFail($id);
        
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'package_id' => $package->id,
            'amount' => $package->price,
            'status' => 'success'
        ]);

        foreach ($package->tryouts as $tryout) {
            \App\Models\TryoutAttempt::create([
                'user_id' => $user->id,
                'tryout_id' => $tryout->id,
                'transaction_id' => $transaction->id,
                'status' => 'available',
                'attempt_count' => 0,
                'max_attempt' => $tryout->batas_pengerjaan ?? 1
            ]);
        }

        return redirect()->route('user.tryout.index')->with('success', 'Paket berhasil dibeli, silakan mulai tryout.');
    }

    public function riwayat()
    {
        $transactions = Auth::user()->transactions()->with('package')->orderBy('created_at', 'desc')->get();
        return view('user.riwayat', compact('transactions'));
    }
}
