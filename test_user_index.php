<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\TryoutAttempt;

$users = User::all();
foreach ($users as $user) {
    echo "=== SIMULATING FOR USER: {$user->name} (ID: {$user->id}) ===\n";
    
    $attempts = TryoutAttempt::with(['tryout.package', 'transaction'])
        ->where('user_id', $user->id)
        ->whereColumn('attempt_count', '<', 'max_attempt')
        ->whereHas('transaction', function ($q) {
            $q->where('status', 'success');
        })
        ->orderBy('created_at', 'desc')
        ->get();
        
    echo "Attempts Count: " . $attempts->count() . "\n";
    foreach ($attempts as $att) {
        $tTitle = $att->tryout ? $att->tryout->title : 'N/A';
        $pkgName = ($att->tryout && $att->tryout->package) ? $att->tryout->package->name : 'N/A';
        echo "  - Attempt ID: {$att->id} | Tryout: {$tTitle} (ID: {$att->tryout_id}) | Package: {$pkgName} | Trx ID: {$att->transaction_id}\n";
    }
}
