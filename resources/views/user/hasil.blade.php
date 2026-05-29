@extends('layouts.app')
@section('content')

<!-- Header Hasil Ujian Premium -->
<div class="card header-hasil-premium">
    <h1 class="hasil-title"><i class="fas fa-chart-line"></i> Analisis Hasil Tryout</h1>
    <p class="hasil-subtitle">{{ $tryout->title }}</p>
</div>

<!-- Score Dashboard Panel -->
<div class="hasil-score-row">
    <div class="card score-card-premium">
        <div class="glass-deco"></div>
        
        <h3 class="score-card-header-text">Nilai Ujian Anda</h3>
        
        <!-- Score Display Ring -->
        <div class="score-ring-wrapper">
            <div class="score-circle-inner" style="border-color: {{ $result->score >= 70 ? '#10B981' : ($result->score >= 50 ? '#F59E0B' : '#EF4444') }};">
                <span class="score-number-text">{{ number_format($result->score, 1) }}</span>
                <span class="score-max-text">Skala 100</span>
            </div>
        </div>
        
        <!-- Qualification Pill -->
        @php
            $qual = 'Perlu Belajar';
            $qualColor = '#EF4444';
            $qualBg = 'rgba(239, 68, 68, 0.08)';
            if ($result->score >= 80) {
                $qual = 'Sangat Baik';
                $qualColor = '#10B981';
                $qualBg = 'rgba(16, 185, 129, 0.08)';
            } elseif ($result->score >= 60) {
                $qual = 'Cukup Baik';
                $qualColor = '#F59E0B';
                $qualBg = 'rgba(245, 158, 11, 0.08)';
            }
        @endphp
        <div class="qualification-badge" style="color: {{ $qualColor }}; background: {{ $qualBg }}; border: 1px solid rgba(0,0,0,0.03);">
            <i class="fas {{ $result->score >= 60 ? 'fa-award' : 'fa-graduation-cap' }}"></i> Predikat: {{ $qual }}
        </div>
        
        <!-- Statistics Pastel Cards Grid -->
        <div class="stats-grid-hasil">
            <div class="stat-hasil-card correct">
                <div class="stat-hasil-icon"><i class="fas fa-check"></i></div>
                <div class="stat-hasil-val">{{ $correctCount }}</div>
                <div class="stat-hasil-lbl">Jawaban Benar</div>
            </div>
            
            <div class="stat-hasil-card wrong">
                <div class="stat-hasil-icon"><i class="fas fa-times"></i></div>
                <div class="stat-hasil-val">{{ $wrongCount }}</div>
                <div class="stat-hasil-lbl">Jawaban Salah</div>
            </div>
            
            <div class="stat-hasil-card empty">
                <div class="stat-hasil-icon"><i class="fas fa-minus"></i></div>
                <div class="stat-hasil-val">{{ $unansweredCount }}</div>
                <div class="stat-hasil-lbl">Kosong</div>
            </div>
        </div>
    </div>
</div>

<div class="review-section-title-wrapper">
    <h2 class="review-section-title"><i class="fas fa-tasks"></i> Review Jawaban & Pembahasan</h2>
    <p class="review-section-subtitle">Pelajari setiap pembahasan di bawah untuk meningkatkan kemampuan Anda.</p>
</div>

<div class="questions-review-list">
    @foreach($tryout->questions as $index => $q)
    @php
        $userAns = $answers->has($q->id) ? $answers[$q->id]->user_answer : '-';
        if (empty($userAns)) $userAns = '-';
        $isCorrect = $answers->has($q->id) ? $answers[$q->id]->is_correct : false;
    @endphp
    
    <div class="card question-review-card {{ $isCorrect ? 'correct-border' : 'wrong-border' }}">
        <!-- Review Card Header -->
        <div class="review-card-header">
            <div class="review-question-info">
                <div class="review-number-badge">
                    {{ $index + 1 }}
                </div>
                <span class="review-label-text">Pertanyaan {{ $index + 1 }}</span>
            </div>
            <span class="badge {{ $isCorrect ? 'badge-correct-p' : 'badge-wrong-p' }}">
                <i class="fas {{ $isCorrect ? 'fa-check-circle' : 'fa-times-circle' }}"></i> {{ $isCorrect ? 'BENAR' : 'SALAH' }}
            </span>
        </div>
        
        <!-- Question Text -->
        <h4 class="review-question-text">{!! $q->clean_question_text_formatted !!}</h4>
        
        <!-- Choices Highlighted Grid -->
        <div class="review-options-grid">
            @php
                $choices = [
                    'A' => $q->formatted_option_a,
                    'B' => $q->formatted_option_b,
                    'C' => $q->formatted_option_c,
                    'D' => $q->formatted_option_d,
                ];
                if (!empty($q->option_e)) {
                    $choices['E'] = $q->formatted_option_e;
                }
            @endphp
            @foreach($choices as $key => $val)
                @php
                    $optionClass = 'option-review-card';
                    $iconClass = 'far fa-circle';
                    
                    if ($q->correct_answer == $key) {
                        $optionClass .= ' correct-choice';
                        $iconClass = 'fas fa-check-circle';
                    } elseif ($userAns == $key) {
                        $optionClass .= ' wrong-choice';
                        $iconClass = 'fas fa-times-circle';
                    }
                @endphp
                <div class="{{ $optionClass }}">
                    <span class="option-key-badge">{{ $key }}.</span>
                    <span class="option-val-text">{!! $val !!}</span>
                    <i class="{{ $iconClass }} option-status-icon"></i>
                </div>
            @endforeach
        </div>
        
        <!-- Comparison Summary Footer -->
        <div class="review-footer-comparison">
            <div class="footer-comp-item user-selection">
                <span class="comp-label">Jawaban Anda:</span>
                <span class="comp-val {{ $isCorrect ? 'text-correct' : 'text-wrong' }}">
                    <i class="fas {{ $isCorrect ? 'fa-user-check' : 'fa-user-times' }}"></i> {{ $userAns }}
                </span>
            </div>
            <div class="footer-comp-item correct-key">
                <span class="comp-label">Kunci Jawaban:</span>
                <span class="comp-val text-correct">
                    <i class="fas fa-check-double"></i> {{ $q->correct_answer }}
                </span>
            </div>
        </div>

        <!-- Pembahasan Premium Block -->
        @if(!empty($q->pembahasan_html))
            <div class="pembahasan-wrapper-hasil" style="margin-top: 1.25rem; border-top: 1.5px dashed var(--border); padding-top: 1.25rem;">
                {!! $q->pembahasan_html !!}
            </div>
        @endif
    </div>
    @endforeach
</div>

<div class="back-dashboard-wrapper">
    <a href="{{ route('user.dashboard') }}" class="btn btn-primary btn-back-dashboard">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>
</div>

<style>
    /* Styling Premium Hasil Ujian */
    .header-hasil-premium {
        background: var(--primary-gradient) !important;
        border: none !important;
        color: white !important;
        padding: 2.5rem !important;
        border-radius: 24px !important;
        box-shadow: var(--shadow-md) !important;
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .hasil-title {
        color: white !important;
        font-size: 2.2rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
    }

    .hasil-subtitle {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1.15rem;
        margin: 0;
        font-weight: 500;
    }

    .hasil-score-row {
        display: flex;
        justify-content: center;
        margin-bottom: 3.5rem;
    }

    .score-card-premium {
        text-align: center;
        width: 100%;
        max-width: 580px;
        padding: 3rem !important;
        border-radius: 24px !important;
        box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
        background: rgba(36, 58, 94, 0.95) !important;
        position: relative;
        overflow: hidden;
    }

    .glass-deco {
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.025) 0%, rgba(255,255,255,0) 70%);
        pointer-events: none;
    }

    .score-card-header-text {
        color: #CBD5E1 !important;
        margin-top: 0;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-size: 0.95rem;
        margin-bottom: 2rem;
    }

    /* Score Ring */
    .score-ring-wrapper {
        display: flex;
        justify-content: center;
        margin-bottom: 2rem;
    }

    .score-circle-inner {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        border: 10px solid #E2E8F0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.15);
        background: rgba(255, 255, 255, 0.05) !important;
        transition: all 0.3s ease;
    }

    .score-number-text {
        font-size: 4rem;
        font-weight: 800;
        color: #FFFFFF !important;
        line-height: 1;
    }

    .score-max-text {
        font-size: 0.85rem;
        color: #CBD5E1 !important;
        font-weight: 600;
        text-transform: uppercase;
        margin-top: 0.25rem;
    }

    /* Qualification */
    .qualification-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1.5rem;
        border-radius: 50px;
        font-size: 0.95rem;
        font-weight: 700;
        margin-bottom: 2.5rem;
        background: rgba(255, 255, 255, 0.08) !important;
        color: #38BDF8 !important;
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
    }

    /* Pastel Stats Cards Grid */
    .stats-grid-hasil {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1.25rem;
        border-top: 1px solid rgba(255, 255, 255, 0.12);
        padding-top: 2.5rem;
    }

    .stat-hasil-card {
        padding: 1.25rem 0.75rem;
        border-radius: 16px;
        display: flex;
        flex-direction: column;
        align-items: center;
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .stat-hasil-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .stat-hasil-card.correct {
        background: rgba(16, 185, 129, 0.12) !important;
        color: #34D399 !important;
    }

    .stat-hasil-card.correct .stat-hasil-icon {
        background: rgba(16, 185, 129, 0.2) !important;
        color: #34D399 !important;
    }

    .stat-hasil-card.wrong {
        background: rgba(239, 68, 68, 0.12) !important;
        color: #F87171 !important;
    }

    .stat-hasil-card.wrong .stat-hasil-icon {
        background: rgba(239, 68, 68, 0.2) !important;
        color: #F87171 !important;
    }

    .stat-hasil-card.empty {
        background: rgba(255, 255, 255, 0.06) !important;
        color: #E2E8F0 !important;
    }

    .stat-hasil-card.empty .stat-hasil-icon {
        background: rgba(255, 255, 255, 0.1) !important;
        color: #E2E8F0 !important;
    }

    .stat-hasil-val {
        font-size: 1.8rem;
        font-weight: 800;
        line-height: 1.2;
    }

    .stat-hasil-lbl {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #CBD5E1 !important;
        margin-top: 0.25rem;
    }

    /* Review Title Area */
    .review-section-title-wrapper {
        margin-bottom: 2rem;
        border-bottom: 2px solid rgba(255, 255, 255, 0.12) !important;
        padding-bottom: 1.25rem;
    }

    .review-section-title {
        font-size: 1.6rem;
        font-weight: 700;
        color: #1E293B !important;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .review-section-subtitle {
        margin: 0.25rem 0 0 0;
        color: #64748B !important;
        font-size: 0.95rem;
    }

    /* Question Review Card */
    .question-review-card {
        padding: 2.25rem !important;
        border-radius: 20px !important;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        border-top: 1px solid rgba(255, 255, 255, 0.12) !important;
        border-right: 1px solid rgba(255, 255, 255, 0.12) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.12) !important;
        background: rgba(36, 58, 94, 0.95) !important;
        margin-bottom: 2rem;
    }

    .question-review-card.correct-border {
        border-left: 6px solid #10B981 !important;
    }

    .question-review-card.wrong-border {
        border-left: 6px solid #EF4444 !important;
    }

    .review-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .review-question-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .review-number-badge {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.08) !important;
        color: #38BDF8 !important;
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }

    .review-label-text {
        font-weight: 700;
        color: #FFFFFF !important;
    }

    /* Badges */
    .card span.badge-correct-p, .card .badge-correct-p, .badge-correct-p {
        background: #DEF7EC !important;
        color: #03543F !important;
        font-weight: 700;
        font-size: 0.8rem;
        padding: 0.4rem 1rem;
        border-radius: 50px;
    }

    .card span.badge-wrong-p, .card .badge-wrong-p, .badge-wrong-p {
        background: #FDE8E8 !important;
        color: #9B1C1C !important;
        font-weight: 700;
        font-size: 0.8rem;
        padding: 0.4rem 1rem;
        border-radius: 50px;
    }

    .review-question-text {
        margin: 0 0 2rem 0;
        font-size: 1.15rem;
        font-weight: 600;
        color: #FFFFFF !important;
        line-height: 1.7;
    }

    /* Choices reviews */
    .review-options-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .option-review-card {
        padding: 1.15rem 1.5rem;
        border-radius: 14px;
        border: 2px solid rgba(255, 255, 255, 0.12) !important;
        display: flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.05) !important;
        transition: all 0.2s ease;
        color: #FFFFFF !important;
    }

    .option-key-badge {
        font-weight: 700;
        margin-right: 0.75rem;
        color: #CBD5E1 !important;
    }

    .option-val-text {
        color: #FFFFFF !important;
        font-weight: 500;
        font-size: 0.95rem;
        flex-grow: 1;
    }

    .option-status-icon {
        font-size: 1.15rem;
        color: rgba(255, 255, 255, 0.3) !important;
    }

    /* Correct option layout */
    .option-review-card.correct-choice {
        background: rgba(16, 185, 129, 0.12) !important;
        border-color: #10B981 !important;
        box-shadow: 0 0 15px rgba(16, 185, 129, 0.15) !important;
    }

    .option-review-card.correct-choice .option-key-badge,
    .option-review-card.correct-choice .option-val-text,
    .option-review-card.correct-choice .option-status-icon {
        color: #34D399 !important;
    }

    /* Wrong option layout */
    .option-review-card.wrong-choice {
        background: rgba(239, 68, 68, 0.12) !important;
        border-color: #EF4444 !important;
        box-shadow: 0 0 15px rgba(239, 68, 68, 0.15) !important;
    }

    .option-review-card.wrong-choice .option-key-badge,
    .option-review-card.wrong-choice .option-val-text,
    .option-review-card.wrong-choice .option-status-icon {
        color: #F87171 !important;
    }

    /* Review Footer Panel */
    .review-footer-comparison {
        border-top: 1px dashed rgba(255, 255, 255, 0.12) !important;
        padding-top: 1.5rem;
        display: flex;
        gap: 3rem;
        background: rgba(255, 255, 255, 0.04) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-radius: 12px;
        padding: 1.25rem 2rem;
        flex-wrap: wrap;
    }

    .comp-label {
        font-size: 0.85rem;
        color: #CBD5E1 !important;
        display: block;
        margin-bottom: 0.25rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .comp-val {
        font-weight: 700;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .text-correct {
        color: #34D399 !important;
    }

    .text-wrong {
        color: #F87171 !important;
    }

    .pembahasan-wrapper-hasil, .pembahasan-premium-block, .pembahasan-wrapper-hasil * {
        color: #E2E8F0 !important;
    }

    .pembahasan-wrapper-hasil strong {
        color: #FFFFFF !important;
    }

    .back-dashboard-wrapper {
        text-align: center;
        margin-top: 4rem;
    }

    .btn-back-dashboard {
        padding: 1rem 2.75rem !important;
        font-size: 1.05rem !important;
        border-radius: 50px !important;
        box-shadow: 0 10px 20px -5px rgba(36, 58, 94, 0.3) !important;
    }

    @media(max-width: 768px) {
        .review-options-grid {
            grid-template-columns: 1fr;
        }
        .review-footer-comparison {
            gap: 1.5rem;
            padding: 1rem;
        }
    }
</style>

@endsection

