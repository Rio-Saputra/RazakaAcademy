<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Tryout;
use App\Models\Question;
use App\Models\TryoutAttempt;
use App\Models\Result;
use App\Models\User;

try {
    $user = User::find(8); // Muhammad Rumii
    echo "User: " . ($user ? $user->name : 'Not Found') . " (ID: " . ($user ? $user->id : '') . ")\n\n";

    echo "Tryouts:\n";
    $tryouts = Tryout::withCount('questions')->get();
    foreach ($tryouts as $t) {
        echo "- ID: {$t->id}, Title: {$t->title}, Questions Count: {$t->questions_count}\n";
        // Let's count questions per category
        $cats = Question::where('tryout_id', $t->id)
            ->select('jenis_soal', \DB::raw('count(*) as count'))
            ->groupBy('jenis_soal')
            ->get();
        foreach ($cats as $c) {
            echo "   * {$c->jenis_soal}: {$c->count} questions\n";
        }
    }

    echo "\nAttempts for User 8:\n";
    $attempts = TryoutAttempt::where('user_id', 8)->with('tryout')->get();
    foreach ($attempts as $att) {
        echo "- Attempt ID: {$att->id}, Tryout ID: {$att->tryout_id} ({$att->tryout->title}), Transaction ID: {$att->transaction_id}, Status: {$att->status}, Attempt Count: {$att->attempt_count}\n";
        $results = Result::where('tryout_attempt_id', $att->id)->get();
        foreach ($results as $res) {
            echo "   * Result ID: {$res->id}, Score: {$res->score}, TWK: {$res->score_twk}, TIU: {$res->score_tiu}, TKP: {$res->score_tkp}, Status: {$res->status}, Passed: " . ($res->is_passed ? 'YES' : 'NO') . "\n";
        }
    }
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
