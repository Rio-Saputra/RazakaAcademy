<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$q = App\Models\Question::where('jenis_soal', 'TIU')->where('question_text', 'like', '%soal-bergambar-placeholder%')->first();
echo $q->question_text;
