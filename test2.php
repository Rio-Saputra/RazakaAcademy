<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

try {
    Schema::create('questions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('tryout_id')->constrained()->onDelete('cascade');
        $table->text('question_text');
        $table->text('option_a');
        $table->text('option_b');
        $table->text('option_c');
        $table->text('option_d');
        $table->char('correct_answer', 1);
        $table->timestamps();
    });
    echo "Success\n";
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
