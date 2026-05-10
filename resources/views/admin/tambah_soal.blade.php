@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">
        {{ $question ? 'Edit Soal' : 'Tambah Banyak Soal' }}
    </h1>
    <p class="subtitle">
        {{ $question ? 'Perbarui soal yang dipilih.' : 'Tambahkan beberapa soal sekaligus untuk tryout terpilih.' }}
    </p>
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
    <div class="card question-item" style="position:relative; margin-bottom:1.5rem; box-shadow: var(--shadow-sm);">

        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border);">
            <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary-gradient); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                1
            </div>
            <h4 style="margin: 0; font-weight: 600;">Edit Pertanyaan</h4>
        </div>

        <div class="form-group">
            <label class="form-label">Teks Soal</label>
            <textarea name="question_text" class="form-control" required style="height: 120px;">{{ $question->question_text }}</textarea>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-top:1.5rem;">
            @foreach(['a','b','c','d'] as $opt)
            <div class="form-group">
                <label class="form-label">Pilihan {{ strtoupper($opt) }}</label>
                <div style="position: relative;">
                    <div style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); font-weight: bold; color: var(--primary);">
                        {{ strtoupper($opt) }}.
                    </div>
                    <input type="text" 
                        name="option_{{ $opt }}" 
                        value="{{ $question['option_'.$opt] }}"
                        class="form-control" 
                        required 
                        style="padding-left: 2.75rem;">
                </div>
            </div>
            @endforeach
        </div>

        <div class="form-group" style="margin-top: 1rem; background: #F8FAFC; padding: 1.5rem; border-radius: 12px; border: 1px solid var(--border);">
            <label class="form-label" style="font-weight: 600;">Jawaban Benar</label>
            <select name="correct_answer" class="form-control" style="width: auto; min-width: 200px;">
                @foreach(['A','B','C','D'] as $opt)
                    <option value="{{ $opt }}" {{ $question->correct_answer == $opt ? 'selected' : '' }}>
                        Pilihan {{ $opt }}
                    </option>
                @endforeach
            </select>
        </div>

    </div>

    @else
    <!-- ================= CREATE MODE (ASLI KAMU, TIDAK DIUBAH) ================= -->

    <!-- SOAL PERTAMA -->
    <div class="card question-item" style="position:relative; margin-bottom:1.5rem; box-shadow: var(--shadow-sm);">
        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border);">
            <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary-gradient); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                1
            </div>
            <h4 style="margin: 0; font-weight: 600;">Pertanyaan</h4>
        </div>

        <div class="form-group">
            <label class="form-label">Teks Soal</label>
            <textarea name="questions[0][question_text]" class="form-control" placeholder="Masukkan teks soal..." required style="height: 120px;"></textarea>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-top:1.5rem;">
            <input type="text" name="questions[0][option_a]" class="form-control" placeholder="A" required>
            <input type="text" name="questions[0][option_b]" class="form-control" placeholder="B" required>
            <input type="text" name="questions[0][option_c]" class="form-control" placeholder="C" required>
            <input type="text" name="questions[0][option_d]" class="form-control" placeholder="D" required>
        </div>

        <div class="form-group" style="margin-top: 1rem;">
            <select name="questions[0][correct_answer]" class="form-control">
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
            </select>
        </div>
    </div>

    @endif

</div>

<!-- BUTTON -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-top: 2rem;">
    
    @if(!$question)
    <button type="button" onclick="addQuestion()" class="btn btn-secondary">
        + Tambah Soal Lagi
    </button>
    @endif

    <button type="submit" class="btn btn-primary">
        {{ $question ? 'Update Soal' : 'Simpan Semua Soal' }}
    </button>
</div>

</form>

@endsection


@push('scripts')
@if(!$question)
<script>
let index = 1;

function addQuestion() {

    let wrapper = document.getElementById('question-wrapper');

    let html = `
    <div class="card question-item" style="position:relative; margin-bottom:1.5rem; box-shadow: var(--shadow-sm); animation: slideIn 0.3s ease;">
        
        <button type="button" onclick="removeQuestion(this)" class="btn btn-danger"
            style="position:absolute; right:1.5rem; top:1.5rem; padding: 0.5rem; border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-times"></i>
        </button>

        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border);">
            <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary-gradient); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                ${index + 1}
            </div>
            <h4 style="margin: 0; font-weight: 600;">Pertanyaan</h4>
        </div>

        <div class="form-group">
            <label class="form-label">Teks Soal</label>
            <textarea name="questions[${index}][question_text]" class="form-control" required style="height: 120px;"></textarea>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-top:1.5rem;">

            <div class="form-group">
                <label class="form-label">Pilihan A</label>
                <input type="text" name="questions[${index}][option_a]" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Pilihan B</label>
                <input type="text" name="questions[${index}][option_b]" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Pilihan C</label>
                <input type="text" name="questions[${index}][option_c]" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Pilihan D</label>
                <input type="text" name="questions[${index}][option_d]" class="form-control" required>
            </div>

        </div>

        <div class="form-group" style="margin-top: 1rem; background: #F8FAFC; padding: 1.5rem; border-radius: 12px;">
            <label class="form-label" style="font-weight: 600;">Jawaban Benar</label>
            <select name="questions[${index}][correct_answer]" class="form-control">
                <option value="A">Pilihan A</option>
                <option value="B">Pilihan B</option>
                <option value="C">Pilihan C</option>
                <option value="D">Pilihan D</option>
            </select>
        </div>

    </div>
    `;

    wrapper.insertAdjacentHTML('beforeend', html);

    index++;
}

function removeQuestion(btn){
    btn.closest('.question-item').remove();
}
</script>
@endif
@endpush