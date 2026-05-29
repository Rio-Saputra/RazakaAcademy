@extends('layouts.app')

@section('content')

<!-- Premium Header -->
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">
            <i class="fas fa-edit"></i> {{ $question ? 'Edit Soal Ujian' : 'Tambah Banyak Soal' }}
        </h1>
        <p class="admin-page-subtitle">
            {{ $question ? 'Perbarui informasi pertanyaan, pilihan ganda, dan kunci jawaban.' : 'Tambahkan beberapa daftar pertanyaan secara dinamis untuk tryout ini.' }}
        </p>
    </div>
    <a href="{{ route('admin.soal.index', ['tryout_id' => $tryout_id]) }}" class="btn btn-add-soal-p">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Soal
    </a>
</div>

<form action="{{ $question ? route('admin.soal.update', $question->id) : route('admin.soal.store') }}" method="POST">
@csrf

@if($question)
    @method('PUT')
@endif

<input type="hidden" name="tryout_id" value="{{ $tryout_id }}">

<div id="question-wrapper">

    @if($question)
    <!-- ================= EDIT MODE ================= -->
    <div class="question-card-premium">

        <div class="question-card-header">
            <div class="badge-step-num">1</div>
            <h4 class="question-card-title">Edit Pertanyaan</h4>
        </div>

        <div class="form-group">
            <label class="form-label-p">Teks Soal</label>
            <textarea name="question_text" class="form-control-p" placeholder="Masukkan teks pertanyaan..." required style="height: 140px;">{{ $question->question_text }}</textarea>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-top:1.5rem;">
            @foreach(['a','b','c','d','e'] as $opt)
            <div class="form-group">
                <label class="form-label-p">Pilihan {{ strtoupper($opt) }} {!! $opt === 'e' ? '<span class="text-muted" style="font-weight:normal; font-size:0.85rem;">(Opsional)</span>' : '' !!}</label>
                <div class="option-input-wrapper">
                    <input type="text" 
                        name="option_{{ $opt }}" 
                        value="{{ $question['option_'.$opt] }}"
                        class="form-control-p form-control-p-option" 
                        placeholder="Masukkan pilihan jawaban {{ strtoupper($opt) }}..."
                        {{ $opt !== 'e' ? 'required' : '' }}>
                    <div class="option-input-badge">{{ strtoupper($opt) }}</div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="correct-answer-section">
            <span class="correct-answer-label">
                <i class="fas fa-check-circle" style="color: #10B981; font-size: 1.25rem;"></i> Kunci Jawaban Benar
            </span>
            <select name="correct_answer" class="select-correct-p">
                @foreach(['A','B','C','D','E'] as $opt)
                    <option value="{{ $opt }}" {{ $question->correct_answer == $opt ? 'selected' : '' }}>
                        Pilihan {{ $opt }}
                    </option>
                @endforeach
            </select>
        </div>

    </div>

    @else
    <!-- ================= CREATE MODE ================= -->

    <!-- SOAL PERTAMA -->
    <div class="question-card-premium">
        <div class="question-card-header">
            <div class="badge-step-num">1</div>
            <h4 class="question-card-title">Pertanyaan Pertama</h4>
        </div>

        <div class="form-group">
            <label class="form-label-p">Teks Soal</label>
            <textarea name="questions[0][question_text]" class="form-control-p" placeholder="Masukkan teks pertanyaan..." required style="height: 140px;"></textarea>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-top:1.5rem;">
            @foreach(['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D', 'e' => 'E'] as $key => $letter)
            <div class="form-group">
                <label class="form-label-p">Pilihan {{ $letter }} {!! $letter === 'E' ? '<span class="text-muted" style="font-weight:normal; font-size:0.85rem;">(Opsional)</span>' : '' !!}</label>
                <div class="option-input-wrapper">
                    <input type="text" name="questions[0][option_{{ $key }}]" class="form-control-p form-control-p-option" placeholder="Masukkan pilihan jawaban {{ $letter }}..." {{ $letter !== 'E' ? 'required' : '' }}>
                    <div class="option-input-badge">{{ $letter }}</div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="correct-answer-section">
            <span class="correct-answer-label">
                <i class="fas fa-check-circle" style="color: #10B981; font-size: 1.25rem;"></i> Kunci Jawaban Benar
            </span>
            <select name="questions[0][correct_answer]" class="select-correct-p">
                <option value="A">Pilihan A</option>
                <option value="B">Pilihan B</option>
                <option value="C">Pilihan C</option>
                <option value="D">Pilihan D</option>
                <option value="E">Pilihan E</option>
            </select>
        </div>
    </div>

    @endif

</div>

<!-- Actions Panel -->
<div class="actions-panel-p">
    @if(!$question)
    <button type="button" onclick="addQuestion()" class="btn btn-add-more-p">
        <i class="fas fa-plus-circle"></i> + Tambah Soal Lagi
    </button>
    @else
    <div></div>
    @endif

    <button type="submit" class="btn btn-submit-all-p">
        <i class="fas fa-save"></i> {{ $question ? 'Perbarui Soal' : 'Simpan Semua Soal' }}
    </button>
</div>

</form>

<style>
    /* Premium Header */
    .admin-page-header {
        background: var(--primary-gradient);
        border-radius: var(--radius);
        padding: 2.25rem 2.5rem;
        color: white;
        margin-bottom: 2.25rem;
        box-shadow: 0 10px 25px -5px rgba(36, 58, 94, 0.15);
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .admin-page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.12) 0%, rgba(255, 255, 255, 0) 70%);
        pointer-events: none;
        border-radius: 50%;
    }

    .admin-page-title {
        font-size: 1.85rem;
        font-weight: 700;
        margin-bottom: 0.35rem;
        color: white;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .admin-page-subtitle {
        color: rgba(255, 255, 255, 0.85);
        font-size: 1rem;
        font-weight: 400;
    }

    .btn-add-soal-p {
        background: rgba(255, 255, 255, 0.15) !important;
        color: white !important;
        border: 1px solid rgba(255, 255, 255, 0.35) !important;
        backdrop-filter: blur(8px);
        padding: 0.85rem 1.75rem !important;
        border-radius: 14px !important;
        font-size: 0.95rem !important;
        font-weight: 600 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: var(--transition) !important;
    }

    .btn-add-soal-p:hover {
        background: white !important;
        color: var(--primary) !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 20px rgba(255, 255, 255, 0.25) !important;
    }

    /* Card Question Item */
    .question-card-premium {
        background: white;
        border-radius: var(--radius);
        padding: 2rem;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        margin-bottom: 2rem;
        position: relative;
        transition: var(--transition);
    }

    .question-card-premium:hover {
        box-shadow: var(--shadow-hover);
        border-color: rgba(36, 58, 94, 0.15);
    }

    .question-card-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.75rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px solid var(--border);
    }

    .badge-step-num {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--primary-gradient);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1rem;
        box-shadow: 0 4px 10px rgba(36, 58, 94, 0.2);
    }

    .question-card-title {
        margin: 0;
        font-weight: 700;
        font-size: 1.2rem;
        color: var(--primary);
    }

    /* Inputs Focus & Glow Styling */
    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label-p {
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--text);
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control-p {
        width: 100%;
        padding: 0.875rem 1.25rem;
        border-radius: 12px;
        border: 1.5px solid var(--border);
        background: #F8FAFC;
        font-size: 0.95rem;
        color: var(--text);
        font-weight: 500;
        transition: var(--transition);
        outline: none;
        box-sizing: border-box;
    }

    .form-control-p:focus {
        border-color: #3B82F6;
        background: white;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    /* Option Prefix Badge in Input */
    .option-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        width: 100%;
    }

    .option-input-badge {
        position: absolute;
        left: 1rem;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #E2E8F0;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        transition: var(--transition);
        pointer-events: none;
    }

    .form-control-p-option {
        padding-left: 3.25rem !important;
    }

    .form-control-p-option:focus + .option-input-badge {
        background: #3B82F6;
        color: white;
    }

    /* Select Answer Area */
    .correct-answer-section {
        margin-top: 1.75rem;
        background: #F8FAFC;
        padding: 1.5rem;
        border-radius: 14px;
        border: 1px dashed var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .correct-answer-label {
        font-weight: 600;
        color: var(--primary);
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .select-correct-p {
        background: white;
        border: 1.5px solid var(--border);
        padding: 0.65rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        color: var(--text);
        outline: none;
        transition: var(--transition);
        cursor: pointer;
    }

    .select-correct-p:focus {
        border-color: #10B981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    /* Delete Button on Dynamic Cards */
    .btn-delete-card-p {
        position: absolute;
        right: 1.5rem;
        top: 1.5rem;
        background: #FEF2F2 !important;
        color: #DC2626 !important;
        border: 1px solid #FCA5A5 !important;
        width: 36px;
        height: 36px;
        border-radius: 50% !important;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition);
        padding: 0 !important;
    }

    .btn-delete-card-p:hover {
        background: #DC2626 !important;
        color: white !important;
        transform: rotate(90deg) scale(1.1);
    }

    /* Navigation & Actions Panel */
    .actions-panel-p {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 2.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border);
    }

    .btn-add-more-p {
        background: white !important;
        color: #2563EB !important;
        border: 1.5px solid #BFDBFE !important;
        padding: 0.85rem 1.75rem !important;
        border-radius: 14px !important;
        font-weight: 600 !important;
        box-shadow: var(--shadow-sm) !important;
    }

    .btn-add-more-p:hover {
        background: #EFF6FF !important;
        border-color: #2563EB !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 16px rgba(37, 99, 235, 0.1) !important;
    }

    .btn-submit-all-p {
        background: var(--primary-gradient) !important;
        color: white !important;
        padding: 0.85rem 2rem !important;
        border-radius: 14px !important;
        font-weight: 600 !important;
        box-shadow: 0 8px 20px rgba(36, 58, 94, 0.2) !important;
    }

    .btn-submit-all-p:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 10px 25px rgba(36, 58, 94, 0.3) !important;
    }

    /* Keyframes Slide In */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .slide-in-animation {
        animation: slideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>

@endsection


@push('scripts')
@if(!$question)
<script>
let index = 1;

function addQuestion() {
    let wrapper = document.getElementById('question-wrapper');

    let html = `
    <div class="question-card-premium slide-in-animation" style="position:relative;">
        
        <button type="button" onclick="removeQuestion(this)" class="btn-delete-card-p" title="Hapus Soal Ini">
            <i class="fas fa-times"></i>
        </button>

        <div class="question-card-header">
            <div class="badge-step-num">${index + 1}</div>
            <h4 class="question-card-title">Pertanyaan Tambahan</h4>
        </div>

        <div class="form-group">
            <label class="form-label-p">Teks Soal</label>
            <textarea name="questions[${index}][question_text]" class="form-control-p" placeholder="Masukkan teks pertanyaan..." required style="height: 140px;"></textarea>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-top:1.5rem;">
            
            <div class="form-group">
                <label class="form-label-p">Pilihan A</label>
                <div class="option-input-wrapper">
                    <input type="text" name="questions[${index}][option_a]" class="form-control-p form-control-p-option" placeholder="Masukkan pilihan jawaban A..." required>
                    <div class="option-input-badge">A</div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label-p">Pilihan B</label>
                <div class="option-input-wrapper">
                    <input type="text" name="questions[${index}][option_b]" class="form-control-p form-control-p-option" placeholder="Masukkan pilihan jawaban B..." required>
                    <div class="option-input-badge">B</div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label-p">Pilihan C</label>
                <div class="option-input-wrapper">
                    <input type="text" name="questions[${index}][option_c]" class="form-control-p form-control-p-option" placeholder="Masukkan pilihan jawaban C..." required>
                    <div class="option-input-badge">C</div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label-p">Pilihan D</label>
                <div class="option-input-wrapper">
                    <input type="text" name="questions[${index}][option_d]" class="form-control-p form-control-p-option" placeholder="Masukkan pilihan jawaban D..." required>
                    <div class="option-input-badge">D</div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label-p">Pilihan E <span class="text-muted" style="font-weight:normal; font-size:0.85rem;">(Opsional)</span></label>
                <div class="option-input-wrapper">
                    <input type="text" name="questions[${index}][option_e]" class="form-control-p form-control-p-option" placeholder="Masukkan pilihan jawaban E...">
                    <div class="option-input-badge">E</div>
                </div>
            </div>

        </div>

        <div class="correct-answer-section">
            <span class="correct-answer-label">
                <i class="fas fa-check-circle" style="color: #10B981; font-size: 1.25rem;"></i> Kunci Jawaban Benar
            </span>
            <select name="questions[${index}][correct_answer]" class="select-correct-p">
                <option value="A">Pilihan A</option>
                <option value="B">Pilihan B</option>
                <option value="C">Pilihan C</option>
                <option value="D">Pilihan D</option>
                <option value="E">Pilihan E</option>
            </select>
        </div>

    </div>
    `;

    wrapper.insertAdjacentHTML('beforeend', html);
    index++;
    
    // Update badge step numbers dynamically in case they were disrupted
    updateStepNumbers();
}

function removeQuestion(btn) {
    btn.closest('.question-card-premium').remove();
    updateStepNumbers();
}

function updateStepNumbers() {
    const cards = document.querySelectorAll('#question-wrapper .question-card-premium');
    cards.forEach((card, idx) => {
        const badge = card.querySelector('.badge-step-num');
        if (badge) {
            badge.textContent = idx + 1;
        }
    });
}
</script>
@endif
@endpush