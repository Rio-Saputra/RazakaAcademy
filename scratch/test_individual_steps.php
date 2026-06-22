<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Question;
use App\Models\TryoutAttempt;
use App\Models\Result;
use App\Models\TryoutAnswer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

try {
    DB::beginTransaction();

    $user = User::find(8);
    Auth::login($user);
    echo "1. Authenticated user Muhammad Rumii\n";

    $userTryout = TryoutAttempt::find(67);
    echo "2. Found TryoutAttempt: ID = {$userTryout->id}, Tryout ID = {$userTryout->tryout_id}\n";

    echo "3. Querying or creating Result...\n";
    $attempt = Result::firstOrCreate(
        [
            'user_id' => $user->id,
            'tryout_attempt_id' => $userTryout->id,
        ],
        [
            'tryout_id' => $userTryout->tryout_id,
            'status' => 'ongoing',
            'score' => null
        ]
    );
    echo "   Result ID: {$attempt->id}, status: {$attempt->status}\n";

    echo "4. Getting questions...\n";
    $questions = Question::where('tryout_id', 16)->limit(5)->get(); // limit to 5 to avoid slow insertions
    echo "   Found " . $questions->count() . " questions\n";

    echo "5. Deleting old answers...\n";
    TryoutAnswer::where('tryout_attempt_id', $userTryout->id)->delete();
    echo "   Old answers deleted.\n";

    echo "6. Inserting dummy answers...\n";
    foreach ($questions as $question) {
        TryoutAnswer::create([
            'user_id' => $user->id,
            'tryout_id' => $question->tryout_id,
            'tryout_attempt_id' => $userTryout->id,
            'question_id' => $question->id,
            'user_answer' => 'A',
            'correct_answer' => $question->correct_answer ?? '',
            'is_correct' => ($question->correct_answer === 'A'),
            'score' => ($question->correct_answer === 'A' ? 5 : 0),
        ]);
        echo "   Inserted answer for Question {$question->id}\n";
    }

    echo "7. Fetching siblings...\n";
    if ($userTryout->transaction_id) {
        $siblingAttempts = TryoutAttempt::where('transaction_id', $userTryout->transaction_id)
            ->where('id', '!=', $userTryout->id)
            ->get();
        echo "   Found " . $siblingAttempts->count() . " sibling attempts\n";

        foreach ($siblingAttempts as $sib) {
            $sibResult = Result::where('tryout_attempt_id', $sib->id)
                ->where('status', 'finished')
                ->latest()
                ->first();
            if ($sibResult) {
                echo "   Sibling ID: {$sib->id} has finished result. Score: {$sibResult->score}\n";
            } else {
                echo "   Sibling ID: {$sib->id} has no finished result\n";
            }
        }
    }

    echo "8. Updating Result...\n";
    $attempt->update([
        'score' => 100,
        'status' => 'finished'
    ]);
    echo "   Result updated.\n";

    DB::rollBack();
    echo "--- SUCCESS: TRANSACTION ROLLED BACK ---\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Exception: " . $e->getMessage() . "\n";
}
