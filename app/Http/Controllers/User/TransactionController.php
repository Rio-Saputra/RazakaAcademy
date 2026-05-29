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
        
        // Cek apakah user sudah memiliki transaksi pending untuk paket ini
        $transaction = Transaction::where('user_id', $user->id)
            ->where('package_id', $package->id)
            ->where('status', 'pending')
            ->first();

        if (!$transaction) {
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'amount' => $package->price,
                'status' => 'pending'
            ]);
        }

        // Generate atau ambil snap_token yang sudah ada
        if (!$transaction->snap_token) {
            try {
                // Konfigurasi Midtrans
                \Midtrans\Config::$serverKey = config('midtrans.server_key');
                \Midtrans\Config::$isProduction = config('midtrans.is_production');
                \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized') ?? true;
                \Midtrans\Config::$is3ds = config('midtrans.is_3ds') ?? true;

                // Mengatasi error SSL cURL ("unable to get local issuer certificate") pada OS Windows lokal
                \Midtrans\Config::$curlOptions = [
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_HTTPHEADER => [], // Mencegah bug "Undefined array key 10023" pada SDK Midtrans di PHP 8.x
                ];

                $orderId = 'TRX-' . $transaction->id . '-' . time();

                $params = [
                    'transaction_details' => [
                        'order_id' => $orderId,
                        'gross_amount' => (int) $transaction->amount,
                    ],
                    'customer_details' => [
                        'first_name' => $user->name,
                        'email' => $user->email,
                    ],
                    'item_details' => [
                        [
                            'id' => $package->id,
                            'price' => (int) $package->price,
                            'quantity' => 1,
                            'name' => substr($package->name ?? 'Paket Ujian', 0, 50),
                        ]
                    ]
                ];

                $snapToken = \Midtrans\Snap::getSnapToken($params);
                
                $transaction->update([
                    'snap_token' => $snapToken
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Midtrans getSnapToken failed: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghubungkan ke payment gateway Midtrans: ' . $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'success' => true,
            'snap_token' => $transaction->snap_token,
            'amount' => $transaction->amount,
            'package_name' => $package->name
        ]);
    }

    public function riwayat()
    {
        $transactions = Auth::user()->transactions()->with('package')->orderBy('created_at', 'desc')->get();
        return view('user.riwayat', compact('transactions'));
    }
}
