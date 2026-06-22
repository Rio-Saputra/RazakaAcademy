<?php

require __DIR__.'/vendor/autoload.php';
if (!isset($app) || !($app instanceof \Illuminate\Foundation\Application)) {
    $app = require __DIR__.'/bootstrap/app.php';
}

if (!isset($kernel)) {
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
}

use Illuminate\Support\Facades\DB;

try {
    DB::beginTransaction();

    // Mapping: Tryout ID => Category ID
    $mappings = [
        35 => 13, // Tryout TWK (ID 35) => Bank Soal TWK (ID 13)
        36 => 14, // Tryout TIU (ID 36) => Bank Soal TIU (ID 14)
        37 => 15  // Tryout TKP (ID 37) => Bank Soal TKP (ID 15)
    ];

    foreach ($mappings as $tryoutId => $catId) {
        $tryout = \App\Models\Tryout::find($tryoutId);
        if (!$tryout) {
            echo "Warning: Tryout ID {$tryoutId} tidak ditemukan.\n";
            continue;
        }

        echo "=== SYNCHRONIZING TRYOUT: {$tryout->title} (ID: {$tryoutId}) ===\n";

        // 1. Delete old questions in Tryout
        $deletedCount = \App\Models\Question::where('tryout_id', $tryoutId)->delete();
        echo "  -> Dihapus {$deletedCount} soal lama dari Tryout.\n";

        // 2. Fetch all questions from Bank Soal Category
        $bankSoals = \App\Models\BankSoal::where('kategori_id', $catId)->orderBy('id')->get();
        echo "  -> Ditemukan {$bankSoals->count()} soal di Bank Soal Kategori ID {$catId}.\n";

        // 3. Copy to Tryout Questions
        $inserted = 0;
        foreach ($bankSoals as $soal) {
            \App\Models\Question::create([
                'tryout_id' => $tryoutId,
                'question_text' => $soal->pertanyaan,
                'option_a' => $soal->opsi_a,
                'option_b' => $soal->opsi_b,
                'option_c' => $soal->opsi_c,
                'option_d' => $soal->opsi_d,
                'option_e' => $soal->opsi_e,
                'correct_answer' => strtoupper($soal->jawaban_benar),
                'jenis_soal' => $soal->jenis_soal ?? ($tryoutId === 15 ? 'TWK' : ($tryoutId === 16 ? 'TIU' : 'TKP')),
                'option_points' => $soal->option_points,
            ]);
            $inserted++;
        }

        echo "  -> Berhasil menyalin {$inserted} soal dari Bank Soal ke Tryout.\n\n";
    }

    DB::commit();
    echo "=== SYNC COMPLETED SUCCESSFULLY ===\n";

} catch (\Exception $e) {
    DB::rollBack();
    echo "\nERROR: " . $e->getMessage() . "\n";
}
