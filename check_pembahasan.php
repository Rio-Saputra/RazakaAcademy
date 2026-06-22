<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$q448 = App\Models\BankSoal::find(448);
$q451 = App\Models\BankSoal::find(451);

echo "=== ID 448 ===\n";
echo "PERTANYAAN:\n" . $q448->pertanyaan . "\n\n";

echo "=== ID 451 ===\n";
echo "PERTANYAAN:\n" . $q451->pertanyaan . "\n\n";
