@extends('layouts.app')
@section('content')

<!-- Header Tryout Premium -->
<div class="card header-tryout-premium">
    <div class="header-main-content">
        <div>
            <h1 class="tryout-title-text">{{ $tryout->title }}</h1>
            <p class="tryout-desc-text"><i class="fas fa-info-circle"></i> Selesaikan seluruh soal dengan teliti. Selamat berjuang!</p>
        </div>
        <div class="timer-widget">
            <i class="far fa-clock timer-icon"></i>
            <span id="timer">{{ $tryout->duration }}:00</span>
        </div>
    </div>
    
    <!-- Real-time Progress Bar -->
    <div class="progress-bar-container">
        <div class="progress-info">
            <span>Progress Pengerjaan</span>
            <span id="progressPercent">0% (0/{{ count($questions) }} Soal Terjawab)</span>
        </div>
        <div class="progress-track">
            <div id="progressBar"></div>
        </div>
    </div>
</div>

<form id="tryoutForm" action="{{ route('user.tryout.submit', [$tryout->id, 'purchase' => $userTryout->id]) }}" method="POST" autocomplete="off">
    @csrf
    <input type="hidden" name="purchase_id" value="{{ $userTryout->id }}">
    <input type="hidden" name="tryout_id" value="{{ $tryout->id }}">
    <div class="exam-layout-grid" id="exam-layout">
        
        <!-- Kiri: Soal -->
        <div class="question-column">
            <div class="card question-card-premium">
                @foreach($questions as $index => $q)
                <div class="question-container" id="question-{{ $index }}" style="display: {{ $index == 0 ? 'block' : 'none' }};">
                    
                    <!-- Question Badge & Header -->
                    <div class="question-header-wrapper">
                        <div class="question-number-badge">
                            Pertanyaan {{ $index + 1 }} dari {{ count($tryout->questions) }}
                        </div>
                        <h3 class="question-text-content">{!! $q->clean_question_text_formatted !!}</h3>
                    </div>
                    
                    <!-- Options Grid -->
                    <div class="options-vertical-grid">
                        @php $userAns = isset($jawaban) && $jawaban->has($q->id) ? $jawaban[$q->id]->user_answer : null; @endphp
                        
                        <label class="option-card-label">
                            <input type="radio" name="answers[{{ $q->id }}]" value="A" {{ $userAns == 'A' ? 'checked' : '' }} onchange="markAnswered({{ $index }})">
                            <span class="option-letter-badge">A</span>
                            <span class="option-text-span">{!! $q->formatted_option_a !!}</span>
                            <i class="fas fa-check-circle option-check-icon"></i>
                        </label>
                        
                        <label class="option-card-label">
                            <input type="radio" name="answers[{{ $q->id }}]" value="B" {{ $userAns == 'B' ? 'checked' : '' }} onchange="markAnswered({{ $index }})">
                            <span class="option-letter-badge">B</span>
                            <span class="option-text-span">{!! $q->formatted_option_b !!}</span>
                            <i class="fas fa-check-circle option-check-icon"></i>
                        </label>
                        
                        <label class="option-card-label">
                            <input type="radio" name="answers[{{ $q->id }}]" value="C" {{ $userAns == 'C' ? 'checked' : '' }} onchange="markAnswered({{ $index }})">
                            <span class="option-letter-badge">C</span>
                            <span class="option-text-span">{!! $q->formatted_option_c !!}</span>
                            <i class="fas fa-check-circle option-check-icon"></i>
                        </label>
                        
                        <label class="option-card-label">
                            <input type="radio" name="answers[{{ $q->id }}]" value="D" {{ $userAns == 'D' ? 'checked' : '' }} onchange="markAnswered({{ $index }})">
                            <span class="option-letter-badge">D</span>
                            <span class="option-text-span">{!! $q->formatted_option_d !!}</span>
                            <i class="fas fa-check-circle option-check-icon"></i>
                        </label>

                        @if(!empty($q->option_e))
                        <label class="option-card-label">
                            <input type="radio" name="answers[{{ $q->id }}]" value="E" {{ $userAns == 'E' ? 'checked' : '' }} onchange="markAnswered({{ $index }})">
                            <span class="option-letter-badge">E</span>
                            <span class="option-text-span">{!! $q->formatted_option_e !!}</span>
                            <i class="fas fa-check-circle option-check-icon"></i>
                        </label>
                        @endif
                    </div>
                </div>
                @endforeach
                
                <!-- Navigation Buttons inside Question Card -->
                <div class="question-action-bar">
                    <button type="button" class="btn btn-secondary btn-action-prev" onclick="prevQuestion()" id="btnPrev">
                        <i class="fas fa-arrow-left"></i> Sebelumnya
                    </button>
                    <button type="button" class="btn btn-primary btn-action-next" onclick="nextQuestion()" id="btnNext">
                        Selanjutnya <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Kanan: Navigasi Sidebar -->
        <div class="navigation-sidebar-column">
            <div class="card navigation-card-premium">
                <h3 class="nav-card-title">
                    <i class="fas fa-th-large"></i> Navigasi Soal
                </h3>
                
                <div class="nav-btn-grid">
                    @foreach($questions as $index => $q)
                    <button type="button" id="nav-{{ $index }}" onclick="goToQuestion({{ $index }})" class="nav-btn-premium {{ isset($jawaban) && $jawaban->has($q->id) ? 'answered' : '' }}">
                        {{ $index + 1 }}
                    </button>
                    @endforeach
                </div>
                
                <!-- Status Legend -->
                <div class="nav-legend-wrapper">
                    <div class="legend-item">
                        <div class="legend-color unanswered"></div> <span>Belum dijawab</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color answered"></div> <span>Sudah dijawab</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color current"></div> <span>Posisi saat ini</span>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="button" class="btn btn-submit-tryout" onclick="confirmSubmit()">
                    <i class="fas fa-check-double"></i> Selesai & Kumpulkan
                </button>
            </div>
        </div>
        
    </div>
</form>

<style>
    /* Styling Premium & Animasi Baru */
    @keyframes slideInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes rotateClock {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    @keyframes pulseTimer {
        0% { background: rgba(239, 68, 68, 0.08); border-color: rgba(239, 68, 68, 0.2); box-shadow: 0 0 0 rgba(239, 68, 68, 0); }
        100% { background: rgba(239, 68, 68, 0.2); border-color: rgba(239, 68, 68, 0.4); box-shadow: 0 0 15px rgba(239, 68, 68, 0.15); }
    }

    /* Floating Header Premium Glassmorphism */
    .header-tryout-premium {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
        margin-bottom: 2rem;
        padding: 1.75rem 2.5rem;
        position: sticky;
        top: 80px;
        z-index: 30;
        border-radius: 20px !important;
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
        background: rgba(36, 58, 94, 0.95) !important;
        backdrop-filter: blur(12px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
    }

    .header-main-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .tryout-title-text {
        margin: 0;
        font-size: 1.6rem;
        font-weight: 700;
        color: #FFFFFF !important;
    }

    .tryout-desc-text {
        margin: 0.25rem 0 0 0;
        color: #CBD5E1 !important;
        font-size: 0.95rem;
        font-weight: 500;
    }

    /* Timer Widget */
    .timer-widget {
        background: rgba(255, 255, 255, 0.08) !important;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        border: 1.5px solid rgba(255, 255, 255, 0.12) !important;
        transition: all 0.3s ease;
    }

    .timer-icon {
        color: #38BDF8 !important;
        font-size: 1.3rem;
    }

    #timer {
        font-size: 1.35rem;
        font-weight: 700;
        color: #38BDF8 !important;
        font-family: 'Courier New', Courier, monospace;
        font-variant-numeric: tabular-nums;
        letter-spacing: 0.5px;
    }

    /* Progress Bar */
    .progress-bar-container {
        width: 100%;
        border-top: 1px solid rgba(255, 255, 255, 0.12);
        padding-top: 1.25rem;
    }

    .progress-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
        font-size: 0.85rem;
        font-weight: 700;
        color: #CBD5E1 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .progress-info span:last-child {
        color: #38BDF8 !important;
    }

    .progress-track {
        width: 100%;
        height: 10px;
        background: rgba(255, 255, 255, 0.12) !important;
        border-radius: 999px;
        overflow: hidden;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
    }

    #progressBar {
        width: 0%;
        height: 100%;
        background: linear-gradient(90deg, #38BDF8 0%, #0EA5E9 100%) !important;
        border-radius: 999px;
        transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Grid Layout */
    .exam-layout-grid {
        display: flex;
        gap: 2.5rem;
        align-items: flex-start;
    }

    .question-column {
        flex: 1;
        min-width: 0;
    }

    .question-card-premium {
        min-height: 520px;
        position: relative;
        padding: 3rem !important;
        border-radius: 24px !important;
        box-shadow: 0 15px 35px -10px rgba(0, 0, 0, 0.03) !important;
    }

    .question-container {
        animation: slideInUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .question-header-wrapper {
        margin-bottom: 2.5rem;
    }

    .question-number-badge {
        display: inline-block;
        background: linear-gradient(135deg, rgba(36, 58, 94, 0.08) 0%, rgba(47, 79, 127, 0.08) 100%);
        color: var(--primary);
        padding: 0.5rem 1.25rem;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(36, 58, 94, 0.05);
    }

    .question-text-content {
        margin: 0;
        line-height: 1.8;
        font-weight: 600;
        font-size: 1.3rem;
        color: #1E293B;
    }

    /* Options Styling Premium */
    .options-vertical-grid {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .option-card-label {
        display: flex;
        align-items: center;
        padding: 1.25rem 1.75rem;
        border: 2px solid rgba(255, 255, 255, 0.12) !important;
        border-radius: 16px;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        background: rgba(255, 255, 255, 0.05) !important;
        position: relative;
        overflow: hidden;
    }

    .option-card-label input[type="radio"] {
        display: none;
    }

    .option-letter-badge {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.08) !important;
        color: #CBD5E1 !important;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        margin-right: 1.25rem;
        transition: all 0.25s ease;
        flex-shrink: 0;
        border: 1px solid rgba(255, 255, 255, 0.12) !important;
    }

    .option-text-span {
        font-size: 1.08rem;
        color: #FFFFFF !important;
        line-height: 1.5;
        font-weight: 500;
        flex-grow: 1;
    }

    .option-check-icon {
        color: #38BDF8 !important;
        font-size: 1.25rem;
        opacity: 0;
        transform: scale(0.5);
        transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    /* Option Hover State */
    .option-card-label:hover {
        border-color: #38BDF8 !important;
        background: rgba(255, 255, 255, 0.08) !important;
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(56, 189, 248, 0.15) !important;
    }

    .option-card-label:hover .option-letter-badge {
        background: rgba(56, 189, 248, 0.15) !important;
        color: #38BDF8 !important;
        border-color: #38BDF8 !important;
    }

    /* Option Checked State */
    .option-card-label:has(input:checked) {
        border-color: #38BDF8 !important;
        background: rgba(56, 189, 248, 0.1) !important;
        box-shadow: 0 0 15px rgba(56, 189, 248, 0.15) !important;
    }

    .option-card-label:has(input:checked) .option-letter-badge {
        background: #38BDF8 !important;
        color: #1E293B !important;
        border-color: #38BDF8 !important;
        transform: scale(1.05);
    }

    .option-card-label:has(input:checked) .option-check-icon {
        opacity: 1 !important;
        transform: scale(1) !important;
    }

    /* Action bar inside Card */
    .question-action-bar {
        display: flex;
        justify-content: space-between;
        margin-top: 3.5rem;
        border-top: 1px solid rgba(226, 232, 240, 0.7);
        padding-top: 2rem;
    }

    .btn-action-prev {
        border-radius: 14px !important;
        padding: 0.85rem 1.75rem !important;
    }

    .btn-action-next {
        border-radius: 14px !important;
        padding: 0.85rem 1.75rem !important;
    }

    /* Sidebar Column */
    .navigation-sidebar-column {
        width: 320px;
        flex-shrink: 0;
        position: sticky;
        top: 280px;
    }

    .navigation-card-premium {
        padding: 2rem !important;
        border-radius: 24px !important;
        box-shadow: 0 15px 35px -10px rgba(0, 0, 0, 0.03) !important;
    }

    .nav-card-title {
        margin-top: 0;
        border-bottom: 1px solid rgba(226, 232, 240, 0.7);
        padding-bottom: 1.25rem;
        margin-bottom: 1.5rem;
        font-size: 1.15rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: var(--primary);
    }

    .nav-btn-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 0.65rem;
    }

    .nav-btn-premium {
        padding: 0;
        border: 2px solid rgba(255, 255, 255, 0.12) !important;
        background: rgba(255, 255, 255, 0.08) !important;
        border-radius: 12px;
        cursor: pointer;
        font-weight: 700;
        font-size: 0.95rem;
        color: #FFFFFF !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .nav-btn-premium:hover {
        background: rgba(255, 255, 255, 0.15) !important;
        border-color: #38BDF8 !important;
        transform: translateY(-1px);
    }

    /* States of Nav Buttons */
    .nav-btn-premium.answered {
        background: #10B981 !important;
        color: white !important;
        border-color: #10B981 !important;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.15);
    }

    .nav-btn-premium.active-pos {
        border-color: #38BDF8 !important;
        background: #38BDF8 !important;
        color: #1E293B !important;
        box-shadow: 0 4px 12px rgba(56, 189, 248, 0.25);
    }

    .nav-btn-premium.answered.active-pos {
        background: #059669 !important;
        border-color: #059669 !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
    }

    /* Legends styling */
    .nav-legend-wrapper {
        margin-top: 1.75rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.12) !important;
        display: flex;
        flex-direction: column;
        gap: 0.65rem;
        font-size: 0.85rem;
        font-weight: 600;
        color: #CBD5E1 !important;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .legend-color {
        width: 14px;
        height: 14px;
        border-radius: 4px;
    }

    .legend-color.unanswered {
        background: rgba(255, 255, 255, 0.08) !important;
        border: 2px solid rgba(255, 255, 255, 0.15) !important;
    }

    .legend-color.answered {
        background: #10B981;
    }

    .legend-color.current {
        background: #38BDF8 !important;
    }

    /* Submit Button Sidebar */
    .btn-submit-tryout {
        width: 100%;
        margin-top: 2rem;
        background: linear-gradient(135deg, #10B981 0%, #059669 100%) !important;
        color: white !important;
        border-radius: 16px !important;
        padding: 1rem !important;
        font-size: 1.05rem !important;
        box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.3) !important;
    }

    .btn-submit-tryout:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 15px 25px -5px rgba(16, 185, 129, 0.4) !important;
    }

    @media (max-width: 1024px) {
        .header-tryout-premium {
            position: static !important;
        }

        .exam-layout-grid {
            flex-direction: column;
        }

        .navigation-sidebar-column {
            position: static !important;
            width: 100% !important;
        }
    }
</style>

<script>
    let currentQuestion = 0;
    const totalQuestions = {{ count($questions) }};
    
    function updateProgressBar() {
        const answeredCount = document.querySelectorAll('.nav-btn-premium.answered').length;
        const percent = Math.round((answeredCount / totalQuestions) * 100);
        document.getElementById('progressBar').style.width = percent + '%';
        document.getElementById('progressPercent').innerText = percent + '% (' + answeredCount + '/' + totalQuestions + ' Soal Terjawab)';
    }

    function updateNav() {
        for(let i=0; i<totalQuestions; i++) {
            document.getElementById('question-' + i).style.display = (i === currentQuestion) ? 'block' : 'none';
            let navBtn = document.getElementById('nav-' + i);
            navBtn.classList.remove('active-pos');
            if(i === currentQuestion) {
                navBtn.classList.add('active-pos');
            }
        }
        
        // Update button states
        document.getElementById('btnPrev').style.visibility = currentQuestion === 0 ? 'hidden' : 'visible';
        
        if (currentQuestion === totalQuestions - 1) {
            document.getElementById('btnNext').innerHTML = 'Selesai & Kumpulkan <i class="fas fa-check-double"></i>';
            document.getElementById('btnNext').style.background = 'linear-gradient(135deg, #10B981 0%, #059669 100%)';
            document.getElementById('btnNext').style.borderColor = '#10B981';
            document.getElementById('btnNext').style.boxShadow = '0 10px 20px -5px rgba(16, 185, 129, 0.3)';
        } else {
            document.getElementById('btnNext').innerHTML = 'Selanjutnya <i class="fas fa-arrow-right"></i>';
            document.getElementById('btnNext').style.background = ''; // reset to default
            document.getElementById('btnNext').style.borderColor = '';
            document.getElementById('btnNext').style.boxShadow = '';
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
        navBtn.classList.add('answered');
        updateProgressBar();
    }

    function confirmSubmit() {
        showFinishTryoutPopup();
    }
    
    function showFinishTryoutPopup() {
        const answeredCount = document.querySelectorAll('.nav-btn-premium.answered').length;
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
    updateProgressBar();
    
    // Automatically collapse sidebar on desktop to maximize exam screen space
    if (window.innerWidth > 1024) {
        document.body.classList.add('sidebar-collapsed');
    }
    
    // Timer setup
    let timeLeft = {{ $tryout->duration * 60 }};
    let timerElement = document.getElementById('timer');
    
    let timerId = setInterval(() => {
        timeLeft--;
        let m = Math.floor(timeLeft / 60);
        let s = timeLeft % 60;
        timerElement.innerText = (m < 10 ? "0" : "") + m + ":" + (s < 10 ? "0" : "") + s;
        
        // Peringatan sisa waktu kritis (kurang dari 5 menit)
        if (timeLeft <= 300) {
            const widget = timerElement.parentElement;
            widget.style.animation = 'pulseTimer 1s infinite alternate';
            timerElement.style.color = '#EF4444';
            widget.querySelector('.timer-icon').style.color = '#EF4444';
            widget.querySelector('.timer-icon').style.animation = 'rotateClock 2s linear infinite';
        }
        
        if(timeLeft <= 0) {
            clearInterval(timerId);
            alert('Waktu habis! Jawaban Anda akan dikumpulkan secara otomatis.');
            document.getElementById('tryoutForm').submit();
        }
    }, 1000);
</script>

<!-- Custom Finish Tryout Modal Premium -->
<div id="finishTryoutModal" style="display: none; position: fixed; inset: 0; z-index: 9999; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(10px);">
    <div class="modal-content" style="background: white; border-radius: 24px; padding: 32px; max-width: 480px; width: 90%; box-shadow: 0 25px 50px rgba(0,0,0,0.15); transform: scale(0.9); transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); text-align: center; position: relative;">
        
        <div style="width: 80px; height: 80px; background: #DCFCE7; color: #10B981; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 1.5rem auto; border: 4px solid #F0FDF4; box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.15);">
            <i class="fas fa-check-double"></i>
        </div>
        
        <h2 style="color: #243A5E; font-size: 1.6rem; font-weight: 700; margin-bottom: 0.75rem; margin-top: 0; font-family: 'Poppins', sans-serif;">Kumpulkan Hasil Ujian?</h2>
        
        <p style="color: #475569; font-size: 1.05rem; line-height: 1.6; margin-bottom: 1.5rem;">
            Anda telah menjawab <strong id="answeredCountSpan" style="color: #10B981; font-size: 1.25rem;">0</strong> dari <strong id="totalCountSpan" style="color: #243A5E; font-size: 1.25rem;">0</strong> soal. Pastikan semua soal telah terjawab dengan benar.
        </p>
        
        <div style="background: #FFFBEB; border: 1px solid #FEF3C7; padding: 1rem 1.25rem; border-radius: 14px; margin-bottom: 2rem;">
            <p style="color: #D97706; margin: 0; font-size: 0.9rem; font-weight: 600; line-height: 1.5;">
                <i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem; font-size: 1rem;"></i>
                Ujian yang sudah dikumpulkan tidak dapat dibatalkan atau diulang kembali.
            </p>
        </div>
        
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <button onclick="closeFinishTryoutPopup()" type="button" style="flex: 1; min-width: 140px; padding: 1rem; border-radius: 14px; border: none; font-weight: 700; font-size: 0.95rem; cursor: pointer; transition: all 0.2s; background: #F1F5F9; color: #64748B; display: flex; align-items: center; justify-content: center; gap: 0.5rem;" onmouseover="this.style.background='#E2E8F0'" onmouseout="this.style.background='#F1F5F9'">
                <i class="fas fa-arrow-left"></i> Periksa Lagi
            </button>
            <button id="btnConfirmSubmit" onclick="submitTryoutConfirmed()" type="button" style="flex: 1; min-width: 140px; padding: 1rem; border-radius: 14px; border: none; font-weight: 700; font-size: 0.95rem; cursor: pointer; transition: all 0.2s; background: linear-gradient(135deg, #10B981, #059669); color: white; display: flex; align-items: center; justify-content: center; gap: 0.5rem; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 8px 16px rgba(16, 185, 129, 0.3)'" onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.2)'">
                <i class="fas fa-paper-plane"></i> Ya, Kumpulkan
            </button>
        </div>
    </div>
</div>
@endsection

