<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$q = App\Models\Question::where('jenis_soal', 'TIU')->orderBy('id')->skip(6)->take(5)->get(['id', 'question_text', 'option_a']);
foreach($q as $i => $x) {
    echo "Q" . (7 + $i) . " (ID: {$x->id}): " . substr($x->question_text, 0, 100) . "\n";
    echo "   A: " . substr($x->option_a, 0, 100) . "\n";
}
