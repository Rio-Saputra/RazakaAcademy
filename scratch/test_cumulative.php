<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

config(['session.driver' => 'array']);
$app['request']->setLaravelSession(app('session')->driver('array'));
session()->start();

use App\Models\User;
use App\Models\Tryout;
use App\Models\Question;
use App\Models\TryoutAttempt;
use App\Models\Result;
use App\Models\TryoutAnswer;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

try {
    DB::beginTransaction();

    $user = User::find(8); // Muhammad Rumii
    if (!$user) {
        throw new \Exception("User ID 8 not found");
    }
    \Illuminate\Support\Facades\Auth::login($user);

    echo "--- SIMULATION START ---\n";
    echo "User: {$user->name} (ID: {$user->id})\n";

    // 1. Let's inspect current TWK attempt status (Attempt 66, Tryout 15)
    $twkAttempt = TryoutAttempt::find(66);
    $twkResult = Result::where('tryout_attempt_id', 66)->where('status', 'finished')->latest()->first();
    
    echo "TWK Attempt ID: {$twkAttempt->id}, Status: {$twkAttempt->status}\n";
    if ($twkResult) {
        echo "TWK Result - Score: {$twkResult->score}, TWK: {$twkResult->score_twk}, TIU: {$twkResult->score_tiu}, TKP: {$twkResult->score_tkp}\n";
    } else {
        echo "No finished TWK result found!\n";
    }

    // 2. Let's simulate submitting TIU (Attempt 67, Tryout 16)
    $tiuAttempt = TryoutAttempt::find(67);
    echo "\nSimulating TIU Submission (Attempt ID: {$tiuAttempt->id})...\n";
    
    // Let's create an ongoing result first if not exists
    $tiuResult = Result::create([
        'user_id' => $user->id,
        'tryout_id' => 16,
        'tryout_attempt_id' => $tiuAttempt->id,
        'status' => 'ongoing',
        'score' => null
    ]);

    // Let's mock a request to submit
    $request = new Request();
    $request->merge([
        'purchase_id' => $tiuAttempt->id,
        'answers' => [] // empty answers means 0 score for TIU, but let's mock all answers correct or just some points
    ]);

    // Let's mock the session values that the controller expects
    $sessionKey = 'tryout_session_' . $tiuAttempt->id;
    $tiuQuestions = Question::where('tryout_id', 16)->get();
    $tiuQuestionIds = $tiuQuestions->pluck('id')->toArray();
    session([
        $sessionKey => [
            'tryout_id' => 16,
            'question_ids' => $tiuQuestionIds,
            'started_at' => now()->timestamp,
        ],
        'active_tryout_purchase' => $tiuAttempt->id
    ]);

    // Let's mock answers where the user gets 40 correct answers * 5 = 200 points
    $answers = [];
    foreach ($tiuQuestions as $q) {
        $answers[$q->id] = $q->correct_answer; // answer correctly
    }
    $request->merge(['answers' => $answers]);

    // Call submit via controller
    $controller = app(\App\Http\Controllers\User\UserTryoutController::class);
    $response = $controller->submit($request, 16);

    // Let's fetch the new TIU result
    $newTiuResult = Result::where('tryout_attempt_id', $tiuAttempt->id)->where('status', 'finished')->latest()->first();
    echo "TIU Submission Completed. Redirect: " . $response->getTargetUrl() . "\n";
    echo "TIU Result - Score: {$newTiuResult->score}, TWK: {$newTiuResult->score_twk}, TIU: {$newTiuResult->score_tiu}, TKP: {$newTiuResult->score_tkp}\n";
    echo "TIU Passed Status - TWK: " . ($newTiuResult->passed_twk ? 'PASS' : 'FAIL') . ", TIU: " . ($newTiuResult->passed_tiu ? 'PASS' : 'FAIL') . ", TKP: " . ($newTiuResult->passed_tkp ? 'PASS' : 'FAIL') . ", Total Passed: " . ($newTiuResult->is_passed ? 'YES' : 'NO') . "\n";

    // 3. Let's simulate submitting TKP (Attempt 68, Tryout 34)
    $tkpAttempt = TryoutAttempt::find(68);
    echo "\nSimulating TKP Submission (Attempt ID: {$tkpAttempt->id})...\n";

    $tkpResult = Result::create([
        'user_id' => $user->id,
        'tryout_id' => 34,
        'tryout_attempt_id' => $tkpAttempt->id,
        'status' => 'ongoing',
        'score' => null
    ]);

    $sessionKeyTkp = 'tryout_session_' . $tkpAttempt->id;
    $tkpQuestions = Question::where('tryout_id', 34)->get();
    $tkpQuestionIds = $tkpQuestions->pluck('id')->toArray();
    session([
        $sessionKeyTkp => [
            'tryout_id' => 34,
            'question_ids' => $tkpQuestionIds,
            'started_at' => now()->timestamp,
        ],
        'active_tryout_purchase' => $tkpAttempt->id
    ]);

    // Let's mock answers where the user gets 5 points for each TKP question
    $tkpAnswers = [];
    foreach ($tkpQuestions as $q) {
        $tkpAnswers[$q->id] = 'A'; // Option A gets 5 points
    }
    $request = new Request();
    $request->merge([
        'purchase_id' => $tkpAttempt->id,
        'answers' => $tkpAnswers
    ]);

    $response = $controller->submit($request, 34);

    // Let's fetch the new TKP result
    $newTkpResult = Result::where('tryout_attempt_id', $tkpAttempt->id)->where('status', 'finished')->latest()->first();
    echo "TKP Submission Completed. Redirect: " . $response->getTargetUrl() . "\n";
    echo "TKP Result - Score: {$newTkpResult->score}, TWK: {$newTkpResult->score_twk}, TIU: {$newTkpResult->score_tiu}, TKP: {$newTkpResult->score_tkp}\n";
    echo "TKP Passed Status - TWK: " . ($newTkpResult->passed_twk ? 'PASS' : 'FAIL') . ", TIU: " . ($newTkpResult->passed_tiu ? 'PASS' : 'FAIL') . ", TKP: " . ($newTkpResult->passed_tkp ? 'PASS' : 'FAIL') . ", Total Passed: " . ($newTkpResult->is_passed ? 'YES' : 'NO') . "\n";

    // Let's call dashboard to make sure it doesn't fail
    echo "\nSimulating Dashboard...\n";
    $dashboardData = $controller->dashboard();
    echo "Dashboard loaded successfully!\n";

    DB::rollBack();
    echo "\n--- TRANSACTION ROLLED BACK SUCCESSFULLY ---\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Exception: " . $e->getMessage() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
