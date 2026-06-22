<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$q = App\Models\Question::where('jenis_soal', 'TIU')
    ->where(function($q) {
        $q->where('question_text', 'like', '%soal-bergambar-placeholder%')
          ->orWhere('question_text', 'like', '%[Gambar%')
          ->orWhere('option_a', 'like', '%opsi-bergambar-placeholder%')
          ->orWhere('option_b', 'like', '%opsi-bergambar-placeholder%')
          ->orWhere('option_c', 'like', '%opsi-bergambar-placeholder%')
          ->orWhere('option_d', 'like', '%opsi-bergambar-placeholder%')
          ->orWhere('option_e', 'like', '%opsi-bergambar-placeholder%');
    })->get();

echo "Remaining items: " . $q->count() . "\n";
