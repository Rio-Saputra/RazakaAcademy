<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TryoutAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function handleNotification(Request $request)
    {
        Log::info('Midtrans Webhook Received: ', $request->all());

        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;
        $serverKey = config('midtrans.server_key');

        // 1. Validasi Signature Key dari Midtrans
        $calculatedSignature = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);

        if ($calculatedSignature !== $request->signature_key) {
            Log::warning('Midtrans Webhook: Invalid Signature Key');
            return response()->json([
                'success' => false,
                'message' => 'Invalid signature key'
            ], 403);
        }

        // 2. Ekstrak Transaction ID dari Order ID (Format: TRX-{transaction_id}-{timestamp})
        $parts = explode('-', $orderId);
        $transactionId = $parts[1] ?? null;

        if (!$transactionId) {
            Log::error('Midtrans Webhook: Transaction ID not found in order_id ' . $orderId);
            return response()->json([
                'success' => false,
                'message' => 'Transaction ID not found'
            ], 404);
        }

        $transaction = Transaction::find($transactionId);

        if (!$transaction) {
            Log::error('Midtrans Webhook: Transaction not found for ID ' . $transactionId);
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found in database'
            ], 404);
        }

        $transactionStatus = $request->transaction_status;
        $paymentType = $request->payment_type;

        // Ambil kode bayar / nomor VA jika ada
        $paymentCode = null;
        if (isset($request->va_numbers[0])) {
            $paymentCode = $request->va_numbers[0]['va_number'];
        } elseif (isset($request->bill_key)) {
            $paymentCode = $request->bill_key;
        } elseif (isset($request->payment_code)) {
            $paymentCode = $request->payment_code;
        }

        // 3. Tangani Status Transaksi Midtrans
        if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
            
            // Perbarui status transaksi menjadi success
            $transaction->update([
                'status' => 'success',
                'payment_type' => $paymentType,
                'payment_code' => $paymentCode
            ]);

            // Buka akses tryout untuk user dengan men-generate TryoutAttempt
            $package = $transaction->package;
            if ($package) {
                foreach ($package->tryouts as $tryout) {
                    // Hindari duplikasi jika webhook terpanggil berkali-kali
                    $exists = TryoutAttempt::where('user_id', $transaction->user_id)
                        ->where('tryout_id', $tryout->id)
                        ->where('transaction_id', $transaction->id)
                        ->exists();

                    if (!$exists) {
                        TryoutAttempt::create([
                            'user_id' => $transaction->user_id,
                            'tryout_id' => $tryout->id,
                            'transaction_id' => $transaction->id,
                            'status' => 'available',
                            'attempt_count' => 0,
                            'max_attempt' => $tryout->batas_pengerjaan ?? 1
                        ]);
                    }
                }
            }

            Log::info("Midtrans Webhook: Transaction {$transactionId} successfully settlement & package tryouts unlocked.");

        } elseif ($transactionStatus == 'pending') {
            
            $transaction->update([
                'status' => 'pending',
                'payment_type' => $paymentType,
                'payment_code' => $paymentCode
            ]);

        } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            
            $transaction->update([
                'status' => 'failed'
            ]);

            Log::info("Midtrans Webhook: Transaction {$transactionId} status failed ({$transactionStatus}).");
        }

        return response()->json([
            'success' => true,
            'message' => 'Notification processed successfully'
        ], 200);
    }
}
