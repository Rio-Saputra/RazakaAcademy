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

        $total_tryout = $user->results()->where('status', 'finished')->count();
        $rata_rata = $user->results()->where('status', 'finished')->avg('score') ?? 0;

        // Ambil ID paket yang sudah sukses dibeli oleh user
        $purchasedPackageIds = \App\Models\Transaction::where('user_id', $user->id)
            ->where('status', 'success')
            ->whereNotNull('package_id')
            ->pluck('package_id');

        // Ambil 2 paket tryout yang BELUM dibeli oleh user sebagai rekomendasi
        $recommended_packages = \App\Models\Package::whereNotIn('id', $purchasedPackageIds)
            ->limit(2)
            ->get();

        // Ambil 3 hasil pengerjaan ujian teranyar beserta informasi tryoutnya
        $recent_results = $user->results()
            ->with('tryout')
            ->where('status', 'finished')
            ->latest()
            ->limit(3)
            ->get();

        // Kalkulasi nilai per paket yang dibeli
        $purchased_packages = \App\Models\Package::whereIn('id', $purchasedPackageIds)->get();
        $package_stats = [];

        foreach ($purchased_packages as $pkg) {
            $tryoutIds = \App\Models\Tryout::where('package_id', $pkg->id)->pluck('id');
            $totalTryoutsInPkg = $tryoutIds->count();

            $tryoutsInPkg = \App\Models\Tryout::where('package_id', $pkg->id)->get();
            
            $twkScores = [];
            $tiuScores = [];
            $tkpScores = [];
            $completedCount = 0;

            foreach ($tryoutsInPkg as $t) {
                $resultsForTryout = \App\Models\Result::where('user_id', $user->id)
                    ->where('tryout_id', $t->id)
                    ->where('status', 'finished')
                    ->get();
                    
                if ($resultsForTryout->isNotEmpty()) {
                    $completedCount += $resultsForTryout->count();
                    $title = strtolower($t->title);
                    
                    $type = null;
                    $firstQ = $t->questions()->first();
                    if ($firstQ) {
                        $type = $firstQ->jenis_soal;
                    } else {
                        if (str_contains($title, 'twk')) $type = 'TWK';
                        elseif (str_contains($title, 'tiu')) $type = 'TIU';
                        elseif (str_contains($title, 'tkp')) $type = 'TKP';
                    }

                    if ($type === 'TWK') {
                        $twkScores = array_merge($twkScores, $resultsForTryout->pluck('score_twk')->toArray());
                    } elseif ($type === 'TIU') {
                        $tiuScores = array_merge($tiuScores, $resultsForTryout->pluck('score_tiu')->toArray());
                    } elseif ($type === 'TKP') {
                        $tkpScores = array_merge($tkpScores, $resultsForTryout->pluck('score_tkp')->toArray());
                    }
                }
            }

            $avgTwk = count($twkScores) > 0 ? array_sum($twkScores) / count($twkScores) : 0;
            $avgTiu = count($tiuScores) > 0 ? array_sum($tiuScores) / count($tiuScores) : 0;
            $avgTkp = count($tkpScores) > 0 ? array_sum($tkpScores) / count($tkpScores) : 0;
            $avgScore = $avgTwk + $avgTiu + $avgTkp;

            if ($completedCount > 0) {
                $passedTwk = ($avgTwk >= 65);
                $passedTiu = ($avgTiu >= 80);
                $passedTkp = ($avgTkp >= 166);
                $isPassed = ($passedTwk && $passedTiu && $passedTkp);
            } else {
                $isPassed = false;
            }

            $package_stats[] = [
                'package' => $pkg,
                'total_tryouts' => $totalTryoutsInPkg,
                'completed_count' => $completedCount,
                'avg_score' => $avgScore,
                'avg_twk' => $avgTwk,
                'avg_tiu' => $avgTiu,
                'avg_tkp' => $avgTkp,
                'is_passed' => $isPassed
            ];
        }

        // Ambil data untuk grafik perkembangan skor (kronologis, limit 7)
        $chart_results = $user->results()
            ->with('tryout')
            ->where('status', 'finished')
            ->latest()
            ->limit(7)
            ->get()
            ->reverse()
            ->values();

        $chart_labels = $chart_results->map(fn($r) => $r->tryout->title ?? 'Ujian')->toArray();
        $chart_scores = $chart_results->map(fn($r) => (int)$r->score)->toArray();

        return view('user.dashboard', compact(
            'total_tryout',
            'rata_rata',
            'recommended_packages',
            'recent_results',
            'package_stats',
            'chart_labels',
            'chart_scores'
        ));
    }

    public function index()
    {
        $user = Auth::user();

        $this->ensureAttemptsExist($user);

        $attempts = TryoutAttempt::with(['tryout.package', 'transaction'])
            ->where('user_id', $user->id)
            ->whereColumn('attempt_count', '<', 'max_attempt')
            ->whereHas('transaction', function ($q) {
                $q->where('status', 'success');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $groupedAttempts = $attempts->groupBy(function ($attempt) {
            return $attempt->tryout->package_id ?? 0;
        });

        return view('user.tryout_saya', compact('attempts', 'groupedAttempts'));
    }

    private function ensureAttemptsExist($user)
    {
        if (!$user) {
            return;
        }

        $successTransactions = \App\Models\Transaction::where('user_id', $user->id)
            ->where('status', 'success')
            ->whereNotNull('package_id')
            ->get();

        foreach ($successTransactions as $trx) {
            $package = $trx->package;
            if ($package) {
                foreach ($package->tryouts as $tryout) {
                    $exists = TryoutAttempt::where('user_id', $user->id)
                        ->where('tryout_id', $tryout->id)
                        ->where('transaction_id', $trx->id)
                        ->exists();

                    if (!$exists) {
                        TryoutAttempt::create([
                            'user_id' => $user->id,
                            'tryout_id' => $tryout->id,
                            'transaction_id' => $trx->id,
                            'status' => 'available',
                            'attempt_count' => 0,
                            'max_attempt' => $tryout->batas_pengerjaan ?? 1
                        ]);
                    }
                }
            }
        }
    }

    public function kerjakan($id)
    {
        $user = Auth::user();

        $this->ensureAttemptsExist($user);

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

        // Cek kadaluwarsa
        if ($userTryout->status === 'expired' || now()->greaterThanOrEqualTo($userTryout->created_at->addDays(7))) {
            if ($userTryout->status !== 'expired') {
                $userTryout->update(['status' => 'expired']);
            }
            return redirect()
                ->route('user.tryout.index')
                ->with('error', 'Akses tryout ini telah kadaluwarsa (melebihi batas 7 hari).');
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

        $scoreTwk = 0;
        $scoreTiu = 0;
        $scoreTkp = 0;

        // Hapus jawaban lama
        TryoutAnswer::where('tryout_attempt_id', $userTryout->id)->delete();

        foreach ($questions as $question) {
            $userAnswer = $answers[$question->id] ?? null;
            $jenis_soal = $question->jenis_soal ?? 'TWK';
            
            $qScore = 0;
            $isCorrect = false;

            if ($jenis_soal === 'TKP') {
                if ($userAnswer) {
                    $optPoints = $question->option_points;
                    $qScore = (int)($optPoints[strtoupper($userAnswer)] ?? 0);
                    $isCorrect = ($qScore > 0);
                } else {
                    $qScore = 0;
                    $isCorrect = false;
                }
                $scoreTkp += $qScore;
            } else {
                // TWK or TIU
                $isCorrect = $userAnswer && (strtoupper($userAnswer) === strtoupper($question->correct_answer));
                if ($isCorrect) {
                    $qScore = 5;
                } else {
                    $qScore = 0;
                }
                
                if ($jenis_soal === 'TIU') {
                    $scoreTiu += $qScore;
                } else {
                    $scoreTwk += $qScore;
                }
            }

            TryoutAnswer::create([
                'user_id' => $user->id,
                'tryout_id' => $question->tryout_id,
                'tryout_attempt_id' => $userTryout->id,
                'question_id' => $question->id,
                'user_answer' => $userAnswer,
                'correct_answer' => $question->correct_answer ?? '',
                'is_correct' => $isCorrect,
                'score' => $qScore,
            ]);
        }

        // Dapatkan skor kategori lain dari attempt dalam transaksi yang sama (sibling attempts)
        if ($userTryout->transaction_id) {
            $siblingAttempts = TryoutAttempt::where('transaction_id', $userTryout->transaction_id)
                ->where('id', '!=', $userTryout->id)
                ->get();

            foreach ($siblingAttempts as $sib) {
                $sibResult = Result::where('tryout_attempt_id', $sib->id)
                    ->where('status', 'finished')
                    ->latest()
                    ->first();

                if ($sibResult) {
                    $sibTryout = $sib->tryout;
                    $sibType = null;
                    $firstQ = $sibTryout?->questions()->first();
                    if ($firstQ) {
                        $sibType = $firstQ->jenis_soal;
                    } else {
                        $sibTitle = strtolower($sibTryout?->title ?? '');
                        if (str_contains($sibTitle, 'twk')) $sibType = 'TWK';
                        elseif (str_contains($sibTitle, 'tiu')) $sibType = 'TIU';
                        elseif (str_contains($sibTitle, 'tkp')) $sibType = 'TKP';
                    }

                    if ($sibType === 'TWK') {
                        $scoreTwk = $sibResult->score_twk;
                    } elseif ($sibType === 'TIU') {
                        $scoreTiu = $sibResult->score_tiu;
                    } elseif ($sibType === 'TKP') {
                        $scoreTkp = $sibResult->score_tkp;
                    }
                }
            }
        }

        // Hitung passing grade
        $passedTwk = ($scoreTwk >= 65);
        $passedTiu = ($scoreTiu >= 80);
        $passedTkp = ($scoreTkp >= 166);
        $isPassed = ($passedTwk && $passedTiu && $passedTkp);
        
        $totalScore = $scoreTwk + $scoreTiu + $scoreTkp;

        // Update result
        $attempt->update([
            'score' => $totalScore,
            'score_twk' => $scoreTwk,
            'score_tiu' => $scoreTiu,
            'score_tkp' => $scoreTkp,
            'passed_twk' => $passedTwk,
            'passed_tiu' => $passedTiu,
            'passed_tkp' => $passedTkp,
            'is_passed' => $isPassed,
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

        // Muat secara dinamis skor dari kategori lain untuk kelengkapan rapor visual review
        $userTryout = TryoutAttempt::find($result->tryout_attempt_id);
        if ($userTryout && $userTryout->transaction_id) {
            $siblingAttempts = TryoutAttempt::where('transaction_id', $userTryout->transaction_id)
                ->where('id', '!=', $userTryout->id)
                ->get();

            foreach ($siblingAttempts as $sib) {
                $sibResult = Result::where('tryout_attempt_id', $sib->id)
                    ->where('status', 'finished')
                    ->latest()
                    ->first();

                if ($sibResult) {
                    $sibTryout = $sib->tryout;
                    $sibType = null;
                    $firstQ = $sibTryout?->questions()->first();
                    if ($firstQ) {
                        $sibType = $firstQ->jenis_soal;
                    } else {
                        $sibTitle = strtolower($sibTryout?->title ?? '');
                        if (str_contains($sibTitle, 'twk')) $sibType = 'TWK';
                        elseif (str_contains($sibTitle, 'tiu')) $sibType = 'TIU';
                        elseif (str_contains($sibTitle, 'tkp')) $sibType = 'TKP';
                    }

                    if ($sibType === 'TWK' && $result->score_twk == 0) {
                        $result->score_twk = $sibResult->score_twk;
                        $result->passed_twk = $sibResult->passed_twk;
                    } elseif ($sibType === 'TIU' && $result->score_tiu == 0) {
                        $result->score_tiu = $sibResult->score_tiu;
                        $result->passed_tiu = $sibResult->passed_tiu;
                    } elseif ($sibType === 'TKP' && $result->score_tkp == 0) {
                        $result->score_tkp = $sibResult->score_tkp;
                        $result->passed_tkp = $sibResult->passed_tkp;
                    }
                }
            }
            // Rekalkulasi total score & kelulusan visual
            $result->score = $result->score_twk + $result->score_tiu + $result->score_tkp;
            $result->passed_twk = ($result->score_twk >= 65);
            $result->passed_tiu = ($result->score_tiu >= 80);
            $result->passed_tkp = ($result->score_tkp >= 166);
            $result->is_passed = ($result->passed_twk && $result->passed_tiu && $result->passed_tkp);
        }

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
            ->paginate(5);

        return view('user.riwayat_tryout', compact('results'));
    }

    public function readNotification($id)
    {
        $notification = \App\Models\Notification::where('user_id', auth()->id())->findOrFail($id);
        $notification->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }

    public function readAllNotifications()
    {
        \App\Models\Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }
}