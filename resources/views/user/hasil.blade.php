@extends('layouts.app')
@section('content')

<div class="page-header" style="text-align: center; margin-bottom: 3rem;">
    <h1 class="page-title" style="font-size: 2.5rem; margin-bottom: 0.5rem;">Hasil Tryout</h1>
    <p class="subtitle" style="font-size: 1.25rem; font-weight: 500; color: var(--primary);">{{ $tryout->title }}</p>
</div>

<div class="row" style="display:flex; justify-content: center; margin-bottom: 3rem;">
    <div class="card" style="text-align: center; width: 100%; max-width: 500px; padding: 3rem; position: relative; overflow: hidden;">
        <!-- Decorative bg -->
        <div style="position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: radial-gradient(circle, rgba(36,58,94,0.03) 0%, rgba(255,255,255,0) 70%); pointer-events: none;"></div>
        
        <h3 style="color: var(--text-muted); margin-top:0; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">Nilai Akhir</h3>
        
        <div style="font-size: 6rem; font-weight: 800; color: {{ $result->score >= 70 ? '#10B981' : ($result->score >= 50 ? '#F59E0B' : '#EF4444') }}; line-height: 1; margin: 1.5rem 0; text-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            {{ number_format($result->score, 1) }}
        </div>
        
        <p style="color: var(--text-muted); font-size: 0.9rem; font-weight: 500;">Skala 0 - 100</p>
        
        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-top: 2.5rem; border-top: 1px solid var(--border); padding-top: 2.5rem;">
            <div style="background: rgba(16,185,129,0.05); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(16,185,129,0.1);">
                <div style="color: #10B981; font-weight: 800; font-size: 2rem; margin-bottom: 0.5rem;">
                    {{ $correctCount }}
                </div>
                <div style="color: #059669; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Jawaban Benar</div>
            </div>
            
            <div style="background: rgba(239,68,68,0.05); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(239,68,68,0.1);">
                <div style="color: #EF4444; font-weight: 800; font-size: 2rem; margin-bottom: 0.5rem;">
                    {{ $wrongCount }}
                </div>
                <div style="color: #B91C1C; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Jawaban Salah</div>
            </div>
            
            <div style="background: rgba(100,116,139,0.05); padding: 1.5rem; border-radius: 12px; border: 1px solid rgba(100,116,139,0.1);">
                <div style="color: #64748B; font-weight: 800; font-size: 2rem; margin-bottom: 0.5rem;">
                    {{ $unansweredCount }}
                </div>
                <div style="color: #475569; font-size: 0.85rem; font-weight: 600; text-transform: uppercase;">Tidak Dijawab</div>
            </div>
        </div>
    </div>
</div>

<h2 style="margin-bottom: 1.5rem; font-size: 1.5rem; font-weight: 700; color: var(--text);">Pembahasan & Review Jawaban</h2>
<div style="display:flex; flex-direction:column; gap: 1.5rem;">
    @foreach($tryout->questions as $index => $q)
    @php
        $userAns = $answers->has($q->id) ? $answers[$q->id]->user_answer : '-';
        if (empty($userAns)) $userAns = '-';
        $isCorrect = $answers->has($q->id) ? $answers[$q->id]->is_correct : false;
    @endphp
    
    <div class="card" style="border-left: 4px solid {{ $isCorrect ? '#10B981' : '#EF4444' }}; padding: 2rem;">
        <div style="display:flex; justify-content:space-between; align-items: center; margin-bottom: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--primary-gradient); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                    {{ $index + 1 }}
                </div>
                <span style="font-weight: 600; color: var(--text);">Pertanyaan</span>
            </div>
            <span class="badge {{ $isCorrect ? 'badge-success' : 'badge-danger' }}" style="padding: 0.5rem 1rem; font-size: 0.85rem;">
                <i class="fas {{ $isCorrect ? 'fa-check-circle' : 'fa-times-circle' }}" style="margin-right: 0.25rem;"></i> {{ $isCorrect ? 'Benar' : 'Salah' }}
            </span>
        </div>
        
        <h4 style="margin: 0 0 1.5rem 0; line-height: 1.6; font-weight: 500; font-size: 1.1rem;">{!! nl2br(e($q->question_text)) !!}</h4>
        
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div style="padding: 1rem 1.25rem; border-radius: 8px; display: flex; align-items: center; {{ $q->correct_answer == 'A' ? 'background: rgba(16,185,129,0.1); border: 2px solid #10B981;' : ($userAns == 'A' ? 'background: rgba(239,68,68,0.1); border: 2px solid #EF4444;' : 'border: 2px solid var(--border); background: white;') }}">
                <span style="font-weight: bold; margin-right: 1rem; color: {{ $q->correct_answer == 'A' ? '#10B981' : ($userAns == 'A' ? '#EF4444' : 'var(--text-muted)') }}">A.</span> {{ $q->option_a }}
            </div>
            <div style="padding: 1rem 1.25rem; border-radius: 8px; display: flex; align-items: center; {{ $q->correct_answer == 'B' ? 'background: rgba(16,185,129,0.1); border: 2px solid #10B981;' : ($userAns == 'B' ? 'background: rgba(239,68,68,0.1); border: 2px solid #EF4444;' : 'border: 2px solid var(--border); background: white;') }}">
                <span style="font-weight: bold; margin-right: 1rem; color: {{ $q->correct_answer == 'B' ? '#10B981' : ($userAns == 'B' ? '#EF4444' : 'var(--text-muted)') }}">B.</span> {{ $q->option_b }}
            </div>
            <div style="padding: 1rem 1.25rem; border-radius: 8px; display: flex; align-items: center; {{ $q->correct_answer == 'C' ? 'background: rgba(16,185,129,0.1); border: 2px solid #10B981;' : ($userAns == 'C' ? 'background: rgba(239,68,68,0.1); border: 2px solid #EF4444;' : 'border: 2px solid var(--border); background: white;') }}">
                <span style="font-weight: bold; margin-right: 1rem; color: {{ $q->correct_answer == 'C' ? '#10B981' : ($userAns == 'C' ? '#EF4444' : 'var(--text-muted)') }}">C.</span> {{ $q->option_c }}
            </div>
            <div style="padding: 1rem 1.25rem; border-radius: 8px; display: flex; align-items: center; {{ $q->correct_answer == 'D' ? 'background: rgba(16,185,129,0.1); border: 2px solid #10B981;' : ($userAns == 'D' ? 'background: rgba(239,68,68,0.1); border: 2px solid #EF4444;' : 'border: 2px solid var(--border); background: white;') }}">
                <span style="font-weight: bold; margin-right: 1rem; color: {{ $q->correct_answer == 'D' ? '#10B981' : ($userAns == 'D' ? '#EF4444' : 'var(--text-muted)') }}">D.</span> {{ $q->option_d }}
            </div>
        </div>
        
        <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px dashed var(--border); display: flex; gap: 2rem;">
            <div>
                <span style="color: var(--text-muted); font-size: 0.85rem; display: block; margin-bottom: 0.25rem;">Jawaban Anda:</span>
                <span style="font-weight: bold; font-size: 1.1rem; color: {{ $isCorrect ? '#10B981' : '#EF4444' }};">{{ $userAns }}</span>
            </div>
            <div>
                <span style="color: var(--text-muted); font-size: 0.85rem; display: block; margin-bottom: 0.25rem;">Kunci Jawaban:</span>
                <span style="font-weight: bold; font-size: 1.1rem; color: #10B981;">{{ $q->correct_answer }}</span>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div style="text-align: center; margin-top: 4rem;">
    <a href="{{ route('user.dashboard') }}" class="btn btn-primary" style="padding: 1rem 2.5rem; font-size: 1.1rem; border-radius: 50px;">
        <i class="fas fa-home" style="margin-right: 0.5rem;"></i> Kembali ke Dashboard
    </a>
</div>

@endsection
