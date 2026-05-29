<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\Tryout;
use App\Models\Question;
use App\Models\TryoutAnswer;
use App\Models\TryoutAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserTryoutController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $total_tryout = $user->results()->count();
        $rata_rata = $user->results()->avg('score') ?? 0;

        // Ambil ID paket yang sudah sukses dibeli oleh user
        $purchasedPackageIds = \App\Models\Transaction::where('user_id', $user->id)
            ->where('status', 'success')
            ->pluck('package_id');

        // Ambil 2 paket tryout yang BELUM dibeli oleh user sebagai rekomendasi
        $recommended_packages = \App\Models\Package::whereNotIn('id', $purchasedPackageIds)
            ->limit(2)
            ->get();

        // Ambil 3 hasil pengerjaan ujian teranyar beserta informasi tryoutnya
        $recent_results = $user->results()
            ->with('tryout')
            ->latest()
            ->limit(3)
            ->get();

        return view('user.dashboard', compact(
            'total_tryout',
            'rata_rata',
            'recommended_packages',
            'recent_results'
        ));
    }

    public function index()
    {
        $user = Auth::user();

        $attempts = TryoutAttempt::with(['tryout', 'transaction'])
            ->where('user_id', $user->id)
            ->whereHas('transaction', function ($q) {
                $q->where('status', 'success');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.tryout_saya', compact('attempts'));
    }

    public function kerjakan($id)
    {
        $user = Auth::user();

        $tryout = Tryout::with('questions')->findOrFail($id);

        // Cari akses tryout aktif
        $userTryout = TryoutAttempt::where('user_id', $user->id)
            ->where('tryout_id', $tryout->id)
            ->whereColumn('attempt_count', '<', 'max_attempt')
            ->latest()
            ->first();

        if (!$userTryout) {
            return redirect()
                ->route('user.tryout.index')
                ->with('error', 'Akses tryout tidak ditemukan atau batas pengerjaan habis.');
        }

        // Cari result ongoing
        $attempt = Result::where('user_id', $user->id)
            ->where('tryout_attempt_id', $userTryout->id)
            ->where('status', 'ongoing')
            ->first();

        // Buat jika belum ada
        if (!$attempt) {
            $attempt = Result::create([
                'user_id' => $user->id,
                'tryout_id' => $tryout->id,
                'tryout_attempt_id' => $userTryout->id,
                'status' => 'ongoing',
                'score' => null
            ]);
        }

        // SESSION KEY
        $sessionKey = 'tryout_session_' . $userTryout->id;

        // Jika session belum ada
        if (!session()->has($sessionKey)) {

            $questions = $tryout->questions()
                ->inRandomOrder()
                ->get();

            session([
                $sessionKey => [
                    'tryout_id' => $tryout->id,
                    'question_ids' => $questions->pluck('id')->toArray(),
                    'started_at' => now()->timestamp,
                ]
            ]);

        } else {

            $savedSession = session($sessionKey);

            $questionIds = $savedSession['question_ids'] ?? [];

            $questions = Question::whereIn('id', $questionIds)
                ->get()
                ->sortBy(function ($q) use ($questionIds) {
                    return array_search($q->id, $questionIds);
                })
                ->values();
        }

        // Simpan purchase aktif
        session([
            'active_tryout_purchase' => $userTryout->id
        ]);

        // Ambil jawaban lama
        $jawaban = TryoutAnswer::where(
            'tryout_attempt_id',
            $userTryout->id
        )->get()->keyBy('question_id');

        return view('user.tryout', compact(
            'tryout',
            'questions',
            'attempt',
            'userTryout',
            'jawaban'
        ));
    }

    public function submit(Request $request, $id)
    {
        $user = Auth::user();

        // Ambil purchase id
        $purchaseId =
            $request->purchase_id
            ?? session('active_tryout_purchase');

        if (!$purchaseId) {
            return redirect()
                ->route('user.tryout.index')
                ->with('error', 'Sesi pengerjaan tidak ditemukan.');
        }

        $sessionKey = 'tryout_session_' . $purchaseId;

        $tryoutSession = session($sessionKey);

        if (!$tryoutSession) {
            return redirect()
                ->route('user.tryout.index')
                ->with('error', 'Session tryout sudah berakhir.');
        }

        // Cari akses tryout
        $userTryout = TryoutAttempt::find($purchaseId);

        if (!$userTryout) {
            return redirect()
                ->route('user.tryout.index')
                ->with('error', 'Akses tryout tidak valid.');
        }

        // Ambil result ongoing
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

        if (!$attempt) {
            return redirect()
                ->route('user.tryout.index')
                ->with('error', 'Result tryout tidak ditemukan.');
        }

        $questionIds = $tryoutSession['question_ids'];

        $questions = Question::whereIn('id', $questionIds)->get();

        $answers = $request->input('answers', []);

        if (empty($answers)) {
            return redirect()->back()->with('error', 'Jawaban tidak boleh kosong');
        }

        $correctCount = 0;
        $wrongCount = 0;

        // Hapus jawaban lama
        TryoutAnswer::where(
            'tryout_attempt_id',
            $userTryout->id
        )->delete();

        foreach ($questions as $question) {

            $userAnswer = $answers[$question->id] ?? null;

            $isCorrect = $userAnswer == $question->correct_answer;

            if ($isCorrect) {
                $correctCount++;
            } else {
                $wrongCount++;
            }

            TryoutAnswer::create([
                'user_id' => $user->id,
                'tryout_id' => $question->tryout_id,
                'tryout_attempt_id' => $userTryout->id,
                'question_id' => $question->id,
                'user_answer' => $userAnswer,
                'correct_answer' => $question->correct_answer,
                'is_correct' => $isCorrect,
            ]);
        }

        $totalQuestions = count($questions);

        $score = $totalQuestions > 0
            ? ($correctCount / $totalQuestions) * 100
            : 0;

        // Update result
        $attempt->update([
            'score' => round($score, 2),
            'status' => 'finished'
        ]);

        // Increment attempt
        $userTryout->increment('attempt_count');

        // Jika habis
        if ($userTryout->attempt_count >= $userTryout->max_attempt) {

            $userTryout->update([
                'status' => 'finished'
            ]);
        }

        // Hapus session
        session()->forget($sessionKey);

        session()->forget('active_tryout_purchase');

        return redirect()
            ->route('user.tryout.hasil', $attempt->id)
            ->with('success', 'Tryout berhasil diselesaikan');
    }

    public function hasil($id)
    {
        $user = Auth::user();

        $result = Result::with('tryout.questions')
            ->where('user_id', $user->id)
            ->findOrFail($id);

        $tryout = $result->tryout;

        $answers = TryoutAnswer::where(
            'tryout_attempt_id',
            $result->tryout_attempt_id
        )->get()->keyBy('question_id');

        $correctCount = $answers
            ->where('is_correct', true)
            ->count();

        $wrongCount = $answers
            ->where('is_correct', false)
            ->whereNotNull('user_answer')
            ->count();

        $unansweredCount = $tryout->questions->count()
            - ($correctCount + $wrongCount);

        return view('user.hasil', compact(
            'tryout',
            'result',
            'answers',
            'correctCount',
            'wrongCount',
            'unansweredCount'
        ));
    }

    public function riwayatTryout()
    {
        $user = Auth::user();

        $results = Result::where('user_id', $user->id)
            ->with('tryout')
            ->latest()
            ->get();

        return view('user.riwayat_tryout', compact('results'));
    }
}