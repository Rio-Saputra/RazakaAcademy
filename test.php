<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

try {
    Schema::create('tryouts', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->foreignId('package_id')->constrained()->onDelete('cascade');
        $table->integer('duration');
        $table->timestamps();
    });
    echo "Success\n";
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
