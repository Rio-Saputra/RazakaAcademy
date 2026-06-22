<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\BankSoal;
use App\Models\Question;

try {
    echo "=== INSPECTING BANK SOAL ===\n";
    $soal = BankSoal::where('pertanyaan', 'LIKE', '%Bacalah pernyataan%')->first();
    if ($soal) {
        echo "ID: {$soal->id}\n";
        echo "PERTANYAAN:\n{$soal->pertanyaan}\n\n";
        echo "OPSI A:\n{$soal->opsi_a}\n\n";
        echo "OPSI B:\n{$soal->opsi_b}\n\n";
        echo "OPSI C:\n{$soal->opsi_c}\n\n";
        echo "OPSI D:\n{$soal->opsi_d}\n\n";
        echo "OPSI E:\n{$soal->opsi_e}\n\n";
        echo "JAWABAN BENAR: {$soal->jawaban_benar}\n";
        echo "PEMBAHASAN:\n" . (method_exists($soal, 'pembahasan') ? 'method exists' : 'no method') . "\n";
    } else {
        echo "No matching BankSoal found!\n";
    }

    echo "\n=== INSPECTING QUESTIONS ===\n";
    $q = Question::where('question_text', 'LIKE', '%Bacalah pernyataan%')->first();
    if ($q) {
        echo "ID: {$q->id}\n";
        echo "QUESTION TEXT:\n{$q->question_text}\n\n";
        echo "OPTION A:\n{$q->option_a}\n\n";
        echo "OPTION B:\n{$q->option_b}\n\n";
        echo "OPTION C:\n{$q->option_c}\n\n";
        echo "OPTION D:\n{$q->option_d}\n\n";
        echo "OPTION E:\n{$q->option_e}\n\n";
        echo "CORRECT ANSWER: {$q->correct_answer}\n";
    } else {
        echo "No matching Question found!\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
