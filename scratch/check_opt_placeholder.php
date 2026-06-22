<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$q = App\Models\Question::where('jenis_soal', 'TIU')->where('option_a', 'like', '%opsi-bergambar-placeholder%')->first();
if ($q) {
    echo "Option A: " . $q->option_a . "\n";
} else {
    echo "No options with placeholder found.";
}
