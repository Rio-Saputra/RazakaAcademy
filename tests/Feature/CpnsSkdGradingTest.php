<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Package;
use App\Models\Tryout;
use App\Models\Question;
use App\Models\TryoutAttempt;
use App\Models\Result;
use App\Models\TryoutAnswer;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CpnsSkdGradingTest extends TestCase
{
    use DatabaseTransactions;

    public function test_tryout_grading_with_passing_score()
    {
        // 1. Create a user
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        // 2. Create a package & tryout
        $package = Package::create([
            'name' => 'Paket SKD CPNS Premium',
            'price' => 150000,
        ]);

        $tryout = Tryout::create([
            'title' => 'Tryout SKD CPNS Simulasi',
            'package_id' => $package->id,
            'duration' => 100,
            'batas_pengerjaan' => 3,
        ]);

        // 3. Create questions:
        // TWK: We need enough TWK points (e.g. correct answers) to pass.
        // Thresholds: TWK >= 65, TIU >= 80, TKP >= 166.
        // Each correct TWK/TIU answer is 5 points.
        // - 13 correct TWK answers -> 65 points.
        // - 16 correct TIU answers -> 80 points.
        // - 34 TKP questions answered with A (5 points) -> 170 points.
        
        $questions = [];
        
        // TWK Questions: 13 questions, correct key 'A'
        for ($i = 1; $i <= 13; $i++) {
            $questions[] = Question::create([
                'tryout_id' => $tryout->id,
                'question_text' => "Soal TWK $i",
                'option_a' => 'Jawaban Benar',
                'option_b' => 'Jawaban Salah',
                'option_c' => 'Jawaban Salah',
                'option_d' => 'Jawaban Salah',
                'option_e' => 'Jawaban Salah',
                'correct_answer' => 'A',
                'jenis_soal' => 'TWK',
            ]);
        }

        // TIU Questions: 16 questions, correct key 'B'
        for ($i = 1; $i <= 16; $i++) {
            $questions[] = Question::create([
                'tryout_id' => $tryout->id,
                'question_text' => "Soal TIU $i",
                'option_a' => 'Jawaban Salah',
                'option_b' => 'Jawaban Benar',
                'option_c' => 'Jawaban Salah',
                'option_d' => 'Jawaban Salah',
                'option_e' => 'Jawaban Salah',
                'correct_answer' => 'B',
                'jenis_soal' => 'TIU',
            ]);
        }

        // TKP Questions: 34 questions, option points: A=5, B=4, C=3, D=2, E=1
        for ($i = 1; $i <= 34; $i++) {
            $questions[] = Question::create([
                'tryout_id' => $tryout->id,
                'question_text' => "Soal TKP $i",
                'option_a' => 'Sangat Setuju',
                'option_b' => 'Setuju',
                'option_c' => 'Ragu-ragu',
                'option_d' => 'Tidak Setuju',
                'option_e' => 'Sangat Tidak Setuju',
                'correct_answer' => 'A',
                'jenis_soal' => 'TKP',
                'option_points' => ['A' => 5, 'B' => 4, 'C' => 3, 'D' => 2, 'E' => 1],
            ]);
        }

        // Create transaction & tryout attempt access
        $transaction = \App\Models\Transaction::create([
            'user_id' => $user->id,
            'package_id' => $package->id,
            'status' => 'success',
            'amount' => 150000,
            'snap_token' => 'mock-token',
        ]);

        $attempt = TryoutAttempt::create([
            'user_id' => $user->id,
            'tryout_id' => $tryout->id,
            'transaction_id' => $transaction->id,
            'status' => 'available',
            'attempt_count' => 0,
            'max_attempt' => 3,
        ]);

        // Start tryout by setting up active session & ongoing result
        $questionIds = collect($questions)->pluck('id')->toArray();
        $sessionKey = 'tryout_session_' . $attempt->id;

        // Mock user answers:
        // - TWK: all 13 answered 'A' (correct) -> score 13 * 5 = 65.
        // - TIU: all 16 answered 'B' (correct) -> score 16 * 5 = 80.
        // - TKP: all 34 answered 'A' (5 points) -> score 34 * 5 = 170.
        $answers = [];
        foreach ($questions as $q) {
            if ($q->jenis_soal === 'TWK') {
                $answers[$q->id] = 'A';
            } elseif ($q->jenis_soal === 'TIU') {
                $answers[$q->id] = 'B';
            } elseif ($q->jenis_soal === 'TKP') {
                $answers[$q->id] = 'A';
            }
        }

        // Submit the request
        $response = $this->actingAs($user)
            ->withSession([
                $sessionKey => [
                    'tryout_id' => $tryout->id,
                    'question_ids' => $questionIds,
                    'started_at' => now()->timestamp,
                ],
                'active_tryout_purchase' => $attempt->id,
            ])
            ->post(route('user.tryout.submit', $tryout->id), [
                'answers' => $answers,
                'purchase_id' => $attempt->id,
            ]);

        // Check redirect to result page
        $result = Result::where('user_id', $user->id)
            ->where('tryout_attempt_id', $attempt->id)
            ->first();

        $this->assertNotNull($result);
        $response->assertRedirect(route('user.tryout.hasil', $result->id));

        // Check scores and passing status
        $this->assertEquals(65, $result->score_twk);
        $this->assertEquals(80, $result->score_tiu);
        $this->assertEquals(170, $result->score_tkp);
        $this->assertEquals(315, $result->score);

        $this->assertTrue($result->passed_twk);
        $this->assertTrue($result->passed_tiu);
        $this->assertTrue($result->passed_tkp);
        $this->assertTrue($result->is_passed); // Passed all categories -> Passed SKD!
    }

    public function test_tryout_grading_with_failing_score()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $package = Package::create([
            'name' => 'Paket SKD CPNS Premium 2',
            'price' => 150000,
        ]);

        $tryout = Tryout::create([
            'title' => 'Tryout SKD CPNS Simulasi 2',
            'package_id' => $package->id,
            'duration' => 100,
            'batas_pengerjaan' => 3,
        ]);

        $questions = [];
        
        // TWK Questions: 13 questions, correct key 'A'
        for ($i = 1; $i <= 13; $i++) {
            $questions[] = Question::create([
                'tryout_id' => $tryout->id,
                'question_text' => "Soal TWK $i",
                'option_a' => 'Jawaban Benar',
                'option_b' => 'Jawaban Salah',
                'option_c' => 'Jawaban Salah',
                'option_d' => 'Jawaban Salah',
                'option_e' => 'Jawaban Salah',
                'correct_answer' => 'A',
                'jenis_soal' => 'TWK',
            ]);
        }

        // TIU Questions: 16 questions, correct key 'B'
        for ($i = 1; $i <= 16; $i++) {
            $questions[] = Question::create([
                'tryout_id' => $tryout->id,
                'question_text' => "Soal TIU $i",
                'option_a' => 'Jawaban Salah',
                'option_b' => 'Jawaban Benar',
                'option_c' => 'Jawaban Salah',
                'option_d' => 'Jawaban Salah',
                'option_e' => 'Jawaban Salah',
                'correct_answer' => 'B',
                'jenis_soal' => 'TIU',
            ]);
        }

        // TKP Questions: 34 questions, option points: A=5, B=4, C=3, D=2, E=1
        for ($i = 1; $i <= 34; $i++) {
            $questions[] = Question::create([
                'tryout_id' => $tryout->id,
                'question_text' => "Soal TKP $i",
                'option_a' => 'Sangat Setuju',
                'option_b' => 'Setuju',
                'option_c' => 'Ragu-ragu',
                'option_d' => 'Tidak Setuju',
                'option_e' => 'Sangat Tidak Setuju',
                'correct_answer' => 'A',
                'jenis_soal' => 'TKP',
                'option_points' => ['A' => 5, 'B' => 4, 'C' => 3, 'D' => 2, 'E' => 1],
            ]);
        }

        $transaction = \App\Models\Transaction::create([
            'user_id' => $user->id,
            'package_id' => $package->id,
            'status' => 'success',
            'amount' => 150000,
            'snap_token' => 'mock-token2',
        ]);

        $attempt = TryoutAttempt::create([
            'user_id' => $user->id,
            'tryout_id' => $tryout->id,
            'transaction_id' => $transaction->id,
            'status' => 'available',
            'attempt_count' => 0,
            'max_attempt' => 3,
        ]);

        $questionIds = collect($questions)->pluck('id')->toArray();
        $sessionKey = 'tryout_session_' . $attempt->id;

        // Mock user answers:
        // - TWK: all 13 answered 'B' (wrong!) -> score 0. (Fails TWK)
        // - TIU: all 16 answered 'B' (correct) -> score 16 * 5 = 80. (Passes TIU)
        // - TKP: all 34 answered 'A' (5 points) -> score 34 * 5 = 170. (Passes TKP)
        $answers = [];
        foreach ($questions as $q) {
            if ($q->jenis_soal === 'TWK') {
                $answers[$q->id] = 'B';
            } elseif ($q->jenis_soal === 'TIU') {
                $answers[$q->id] = 'B';
            } elseif ($q->jenis_soal === 'TKP') {
                $answers[$q->id] = 'A';
            }
        }

        $response = $this->actingAs($user)
            ->withSession([
                $sessionKey => [
                    'tryout_id' => $tryout->id,
                    'question_ids' => $questionIds,
                    'started_at' => now()->timestamp,
                ],
                'active_tryout_purchase' => $attempt->id,
            ])
            ->post(route('user.tryout.submit', $tryout->id), [
                'answers' => $answers,
                'purchase_id' => $attempt->id,
            ]);

        $result = Result::where('user_id', $user->id)
            ->where('tryout_attempt_id', $attempt->id)
            ->first();

        $this->assertNotNull($result);
        $response->assertRedirect(route('user.tryout.hasil', $result->id));

        // Check scores and passing status
        $this->assertEquals(0, $result->score_twk);
        $this->assertEquals(80, $result->score_tiu);
        $this->assertEquals(170, $result->score_tkp);
        $this->assertEquals(250, $result->score);

        $this->assertFalse($result->passed_twk);
        $this->assertTrue($result->passed_tiu);
        $this->assertTrue($result->passed_tkp);
        $this->assertFalse($result->is_passed); // Fails TWK -> Fails SKD!
    }
}
