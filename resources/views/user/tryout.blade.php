@extends('layouts.app')
@section('content')

<!-- Header Tryout -->
<div class="card" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; padding: 1.5rem 2.5rem; position: sticky; top: 80px; z-index: 30; border-radius: 16px; border-bottom: 4px solid var(--primary);">
    <div>
        <h1 class="page-title" style="margin: 0; font-size: 1.5rem;">{{ $tryout->title }}</h1>
        <p style="margin: 0; color: var(--text-muted); font-size: 0.95rem;">Fokus dan kerjakan dengan teliti.</p>
    </div>
    <div style="background: rgba(220, 38, 38, 0.1); padding: 0.75rem 1.5rem; border-radius: 50px; display: flex; align-items: center; gap: 0.75rem;">
        <i class="far fa-clock" style="color: #DC2626; font-size: 1.25rem;"></i>
        <span id="timer" style="font-size: 1.25rem; font-weight: 700; color: #DC2626; font-variant-numeric: tabular-nums;">{{ $tryout->duration }}:00</span>
    </div>
</div>

<form id="tryoutForm" action="{{ route('user.tryout.submit', [$tryout->id, 'purchase' => $userTryout->id]) }}" method="POST" autocomplete="off">
    @csrf
    <input type="hidden" name="purchase_id" value="{{ $userTryout->id }}">
    <input type="hidden" name="tryout_id" value="{{ $tryout->id }}">
    <div style="display:flex; gap: 2.5rem; align-items: flex-start;" id="exam-layout">
        
        <!-- Kiri: Soal -->
        <div style="flex: 1; min-width: 0;"> <!-- min-width: 0 prevents flex child from overflowing -->
            <div class="card" style="min-height: 500px; position:relative; padding: 3rem;">
                @foreach($questions as $index => $q)
                <div class="question-container" id="question-{{ $index }}" style="display: {{ $index == 0 ? 'block' : 'none' }}; animation: fadeIn 0.3s ease;">
                    <div style="margin-bottom: 2.5rem;">
                        <div style="display: inline-block; background: var(--primary-gradient); color: white; padding: 0.5rem 1rem; border-radius: 50px; font-size: 0.9rem; font-weight:600; margin-bottom: 1.5rem;">
                            Pertanyaan {{ $index + 1 }} dari {{ count($tryout->questions) }}
                        </div>
                        <h3 style="margin: 0; line-height: 1.8; font-weight: 500; font-size: 1.25rem;">{!! nl2br(e($q->question_text)) !!}</h3>
                    </div>
                    
                    <div style="display:flex; flex-direction:column; gap: 1.25rem;">
                        @php $userAns = isset($jawaban) && $jawaban->has($q->id) ? $jawaban[$q->id]->user_answer : null; @endphp
                        <label class="option-label">
                            <input type="radio" name="answers[{{ $q->id }}]" value="A" {{ $userAns == 'A' ? 'checked' : '' }} onchange="markAnswered({{ $index }})">
                            <span class="option-letter">A</span>
                            <span class="option-text">{{ $q->option_a }}</span>
                        </label>
                        <label class="option-label">
                            <input type="radio" name="answers[{{ $q->id }}]" value="B" {{ $userAns == 'B' ? 'checked' : '' }} onchange="markAnswered({{ $index }})">
                            <span class="option-letter">B</span>
                            <span class="option-text">{{ $q->option_b }}</span>
                        </label>
                        <label class="option-label">
                            <input type="radio" name="answers[{{ $q->id }}]" value="C" {{ $userAns == 'C' ? 'checked' : '' }} onchange="markAnswered({{ $index }})">
                            <span class="option-letter">C</span>
                            <span class="option-text">{{ $q->option_c }}</span>
                        </label>
                        <label class="option-label">
                            <input type="radio" name="answers[{{ $q->id }}]" value="D" {{ $userAns == 'D' ? 'checked' : '' }} onchange="markAnswered({{ $index }})">
                            <span class="option-letter">D</span>
                            <span class="option-text">{{ $q->option_d }}</span>
                        </label>
                    </div>
                </div>
                @endforeach
                
                <div style="display:flex; justify-content: space-between; margin-top: 3rem; border-top: 1px solid var(--border); padding-top: 2rem;">
                    <button type="button" class="btn btn-secondary" onclick="prevQuestion()" id="btnPrev">
                        <i class="fas fa-chevron-left"></i> Sebelumnya
                    </button>
                    <button type="button" class="btn btn-primary" onclick="nextQuestion()" id="btnNext">
                        Selanjutnya <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Kanan: Navigasi -->
        <div style="width: 320px; flex-shrink: 0;">
            <div class="card" style="position: sticky; top: 180px; padding: 2rem;">
                <h3 style="margin-top:0; border-bottom: 1px solid var(--border); padding-bottom: 1rem; margin-bottom: 1.5rem; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-th" style="color: var(--primary);"></i> Navigasi Soal
                </h3>
                
                <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 0.75rem;">
                    @foreach($questions as $index => $q)
                    <button type="button" id="nav-{{ $index }}" onclick="goToQuestion({{ $index }})" class="nav-btn {{ isset($jawaban) && $jawaban->has($q->id) ? 'nav-answered' : '' }}">
                        {{ $index + 1 }}
                    </button>
                    @endforeach
                </div>
                
                <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border); display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.85rem; color: var(--text-muted);">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <div style="width: 12px; height: 12px; background: white; border: 1px solid var(--border); border-radius: 3px;"></div> Belum dijawab
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <div style="width: 12px; height: 12px; background: #10B981; border-radius: 3px;"></div> Sudah dijawab
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <div style="width: 12px; height: 12px; background: var(--primary); border-radius: 3px;"></div> Posisi saat ini
                    </div>
                </div>

                <button type="button" class="btn" style="width: 100%; margin-top: 2rem; background: #10B981; color: white; border-radius: 12px; padding: 1rem; font-size: 1rem;" onclick="confirmSubmit()">
                    <i class="fas fa-check-circle"></i> Selesai & Kumpulkan
                </button>
            </div>
        </div>
        
    </div>
</form>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateX(10px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .option-label {
        display:flex; 
        align-items:center; 
        padding: 1.25rem 1.5rem; 
        border: 2px solid var(--border); 
        border-radius: 12px; 
        cursor:pointer; 
        transition: var(--transition);
        background: white;
    }
    
    .option-label input[type="radio"] {
        display: none;
    }
    
    .option-letter {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #F1F5F9;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 1rem;
        transition: var(--transition);
        flex-shrink: 0;
    }
    
    .option-text {
        font-size: 1.05rem;
        color: var(--text);
        line-height: 1.5;
    }

    .option-label:hover { 
        border-color: #CBD5E1; 
        background: #F8FAFC; 
    }

    .option-label:has(input:checked) { 
        border-color: var(--primary); 
        background: rgba(36, 58, 94, 0.03); 
        box-shadow: 0 4px 12px rgba(36, 58, 94, 0.05);
    }
    
    .option-label:has(input:checked) .option-letter {
        background: var(--primary);
        color: white;
    }

    .nav-btn {
        padding: 0.5rem; 
        border: 2px solid var(--border); 
        background: white; 
        border-radius: 8px; 
        cursor:pointer; 
        font-weight: 600; 
        color: var(--text-muted);
        transition: var(--transition);
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .nav-btn:hover {
        border-color: #94A3B8;
    }

    .nav-answered { 
        background: #10B981 !important; 
        color: white !important; 
        border-color: #10B981 !important; 
    }

    .nav-active { 
        border-color: var(--primary) !important; 
        background: var(--primary) !important; 
        color: white !important; 
        box-shadow: 0 4px 8px rgba(36, 58, 94, 0.2);
    }

    /* Override active style if it's already answered */
    .nav-answered.nav-active {
        background: #059669 !important;
        border-color: #059669 !important;
    }

    @media (max-width: 1024px) {
        #exam-layout {
            flex-direction: column;
        }
        .card[style*="position: sticky"] {
            position: static !important;
            width: 100% !important;
        }
        #exam-layout > div:nth-child(2) {
            width: 100% !important;
        }
    }
</style>

<script>
    let currentQuestion = 0;
    const totalQuestions = {{ count($questions) }};
    
    function updateNav() {
        for(let i=0; i<totalQuestions; i++) {
            document.getElementById('question-' + i).style.display = (i === currentQuestion) ? 'block' : 'none';
            let navBtn = document.getElementById('nav-' + i);
            navBtn.classList.remove('nav-active');
            if(i === currentQuestion) {
                navBtn.classList.add('nav-active');
            }
        }
        
        // Update button states
        document.getElementById('btnPrev').style.visibility = currentQuestion === 0 ? 'hidden' : 'visible';
        
        if (currentQuestion === totalQuestions - 1) {
            document.getElementById('btnNext').innerHTML = 'Selesai <i class="fas fa-check"></i>';
            document.getElementById('btnNext').classList.replace('btn-primary', 'btn-success');
            document.getElementById('btnNext').style.background = '#10B981';
        } else {
            document.getElementById('btnNext').innerHTML = 'Selanjutnya <i class="fas fa-chevron-right"></i>';
            document.getElementById('btnNext').style.background = ''; // reset to default
        }
    }
    
    function goToQuestion(index) {
        currentQuestion = index;
        updateNav();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    
    function nextQuestion() {
        if(currentQuestion < totalQuestions - 1) {
            currentQuestion++;
            updateNav();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            confirmSubmit();
        }
    }
    
    function prevQuestion() {
        if(currentQuestion > 0) {
            currentQuestion--;
            updateNav();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }
    
    function markAnswered(index) {
        let navBtn = document.getElementById('nav-' + index);
        navBtn.classList.add('nav-answered');
        
        // Optional: auto next question after short delay
        // setTimeout(() => { if(currentQuestion < totalQuestions - 1) nextQuestion(); }, 500);
    }

    function confirmSubmit() {
        showFinishTryoutPopup();
    }
    
    function showFinishTryoutPopup() {
        const answeredCount = document.querySelectorAll('.nav-answered').length;
        document.getElementById('answeredCountSpan').textContent = answeredCount;
        document.getElementById('totalCountSpan').textContent = totalQuestions;
        
        const modal = document.getElementById('finishTryoutModal');
        modal.style.display = 'flex';
        // Trigger reflow
        void modal.offsetWidth;
        modal.style.opacity = '1';
        modal.querySelector('.modal-content').style.transform = 'scale(1)';
    }

    function closeFinishTryoutPopup() {
        const modal = document.getElementById('finishTryoutModal');
        modal.style.opacity = '0';
        modal.querySelector('.modal-content').style.transform = 'scale(0.9)';
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }

    function submitTryoutConfirmed() {
        const btn = document.getElementById('btnConfirmSubmit');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengumpulkan...';
        btn.style.pointerEvents = 'none';
        document.getElementById('tryoutForm').submit();
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('finishTryoutModal');
            if (modal && modal.style.display === 'flex') {
                closeFinishTryoutPopup();
            }
        }
    });
    
    // Initial setup
    updateNav();
    
    // Timer
    let timeLeft = {{ $tryout->duration * 60 }};
    let timerElement = document.getElementById('timer');
    
    let timerId = setInterval(() => {
        timeLeft--;
        let m = Math.floor(timeLeft / 60);
        let s = timeLeft % 60;
        timerElement.innerText = m + ":" + (s < 10 ? "0" : "") + s;
        
        // Visual warning when less than 5 minutes
        if (timeLeft <= 300) {
            timerElement.parentElement.style.animation = 'pulse 1s infinite alternate';
        }
        
        if(timeLeft <= 0) {
            clearInterval(timerId);
            alert('Waktu habis! Jawaban Anda akan dikumpulkan secara otomatis.');
            document.getElementById('tryoutForm').submit();
        }
    }, 1000);
</script>
<style>
    @keyframes pulse {
        from { background: rgba(220, 38, 38, 0.1); }
        to { background: rgba(220, 38, 38, 0.3); }
    }
</style>

<!-- Custom Finish Tryout Modal -->
<div id="finishTryoutModal" style="display: none; position: fixed; inset: 0; z-index: 9999; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(10px);">
    <div class="modal-content" style="background: white; border-radius: 24px; padding: 32px; max-width: 480px; width: 90%; box-shadow: 0 25px 50px rgba(0,0,0,0.15); transform: scale(0.9); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); text-align: center; position: relative;">
        
        <div style="width: 80px; height: 80px; background: #DCFCE7; color: #22C55E; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 1.5rem auto;">
            <i class="fas fa-circle-check"></i>
        </div>
        
        <h2 style="color: #243A5E; font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem; margin-top: 0;">Selesaikan Tryout?</h2>
        
        <p style="color: #475569; font-size: 1.05rem; line-height: 1.6; margin-bottom: 1rem;">
            Anda telah menjawab <strong id="answeredCountSpan" style="color: #243A5E; font-size: 1.2rem;">0</strong> dari <strong id="totalCountSpan" style="color: #243A5E; font-size: 1.2rem;">0</strong> soal. Pastikan semua jawaban sudah benar sebelum mengumpulkan tryout.
        </p>
        
        <div style="background: #FFFBEB; border: 1px solid #FEF3C7; padding: 1rem; border-radius: 12px; margin-bottom: 2rem;">
            <p style="color: #D97706; margin: 0; font-size: 0.9rem; font-weight: 500;">
                <i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem;"></i>
                Tryout yang sudah dikumpulkan tidak dapat diulang kembali.
            </p>
        </div>
        
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <button onclick="closeFinishTryoutPopup()" type="button" style="flex: 1; min-width: 140px; padding: 1rem; border-radius: 12px; border: none; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.2s; background: #F1F5F9; color: #64748B; display: flex; align-items: center; justify-content: center; gap: 0.5rem;" onmouseover="this.style.background='#E2E8F0'" onmouseout="this.style.background='#F1F5F9'">
                <i class="fas fa-arrow-left"></i> Periksa Lagi
            </button>
            <button id="btnConfirmSubmit" onclick="submitTryoutConfirmed()" type="button" style="flex: 1; min-width: 140px; padding: 1rem; border-radius: 12px; border: none; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.2s; background: linear-gradient(135deg, #22C55E, #16A34A); color: white; display: flex; align-items: center; justify-content: center; gap: 0.5rem;" onmouseover="this.style.background='#15803D'; this.style.boxShadow='0 4px 12px rgba(22, 163, 74, 0.3)'" onmouseout="this.style.background='linear-gradient(135deg, #22C55E, #16A34A)'; this.style.boxShadow='none'">
                <i class="fas fa-paper-plane"></i> Ya, Selesaikan
            </button>
        </div>
    </div>
</div>
@endsection
