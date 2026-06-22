<?php

namespace App\Services;

use App\Models\User;
use App\Models\TryoutAttempt;
use App\Models\Notification;
use Carbon\Carbon;

class TryoutExpirationService
{
    public static function checkExpirationForUser(User $user)
    {
        // Ambil attempt yang masih 'available'
        $attempts = TryoutAttempt::with(['tryout.package'])
            ->where('user_id', $user->id)
            ->where('status', 'available')
            ->get();

        $now = Carbon::now();

        foreach ($attempts as $attempt) {
            $purchaseDate = $attempt->created_at;
            $expiryDate = $purchaseDate->copy()->addDays(7);

            // Cek apakah sudah kadaluwarsa
            if ($now->greaterThanOrEqualTo($expiryDate)) {
                $attempt->update([
                    'status' => 'expired'
                ]);

                // Kirim notifikasi kadaluwarsa
                Notification::create([
                    'user_id' => $user->id,
                    'tryout_attempt_id' => $attempt->id,
                    'title' => 'Akses Tryout Kadaluwarsa',
                    'message' => "Akses pengerjaan tryout '{$attempt->tryout->title}' dari paket '{$attempt->tryout->package->name}' telah kadaluwarsa (melebihi batas 7 hari dari tanggal pembelian).",
                ]);
                
                continue;
            }

            // Hitung selisih hari
            $daysPassed = (int) $now->diffInDays($purchaseDate);

            // Kirim notifikasi harian jika belum dikerjakan (attempt_count == 0)
            if ($daysPassed >= 1 && $daysPassed <= 6 && $attempt->attempt_count === 0) {
                $daysRemaining = 7 - $daysPassed;

                // Cegah duplikasi notifikasi untuk hari yang sama
                $alreadyNotified = Notification::where('user_id', $user->id)
                    ->where('tryout_attempt_id', $attempt->id)
                    ->where('days_passed', $daysPassed)
                    ->exists();

                if (!$alreadyNotified) {
                    Notification::create([
                        'user_id' => $user->id,
                        'tryout_attempt_id' => $attempt->id,
                        'days_passed' => $daysPassed,
                        'title' => 'Pengingat Waktu Tryout',
                        'message' => "Tryout '{$attempt->tryout->title}' dari paket '{$attempt->tryout->package->name}' belum Anda kerjakan. Sisa waktu pengerjaan sebelum kadaluwarsa adalah {$daysRemaining} hari.",
                    ]);
                }
            }
        }
    }
}
