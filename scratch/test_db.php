<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Result;
use App\Models\TryoutAttempt;

try {
    echo "Querying TryoutAttempt...\n";
    $att = TryoutAttempt::find(67);
    echo "Found Attempt ID: " . ($att ? $att->id : 'null') . "\n";

    echo "Querying Result...\n";
    $res = Result::where('tryout_attempt_id', 67)->first();
    echo "Found Result ID: " . ($res ? $res->id : 'null') . "\n";

    echo "Done.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
