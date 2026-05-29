@extends('layouts.app')
@section('content')

<div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <div style="display:flex; align-items:center; gap: 0.5rem; margin-bottom: 0.5rem;">
            <a href="{{ route('admin.bank-soal.index', ['kategori_id' => $kategori->id]) }}" style="color: rgba(255,255,255,0.7); font-weight: 500;"><i class="fas fa-arrow-left"></i> Kembali ke Soal</a>
        </div>
        <h1 class="page-title" style="margin: 0;">Edit Soal: {{ $kategori->nama_kategori }}</h1>
        <p class="subtitle">Perbarui pertanyaan atau opsi jawaban.</p>
    </div>
</div>

<div class="card">
    <form action="{{ route('admin.bank-soal.update', $bankSoal->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label class="form-label">Pertanyaan <span style="color:#EF4444">*</span></label>
            <textarea name="pertanyaan" class="form-control" rows="5" required>{{ old('pertanyaan', $bankSoal->pertanyaan) }}</textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Opsi A <span style="color:#EF4444">*</span></label>
                <div style="display:flex; align-items:center;">
                    <span style="background: #E2E8F0; padding: 0.875rem 1rem; border: 1px solid var(--border); border-right: none; border-radius: 12px 0 0 12px; font-weight: 600;">A</span>
                    <input type="text" name="opsi_a" class="form-control" style="border-radius: 0 12px 12px 0;" required value="{{ old('opsi_a', $bankSoal->opsi_a) }}">
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Opsi B <span style="color:#EF4444">*</span></label>
                <div style="display:flex; align-items:center;">
                    <span style="background: #E2E8F0; padding: 0.875rem 1rem; border: 1px solid var(--border); border-right: none; border-radius: 12px 0 0 12px; font-weight: 600;">B</span>
                    <input type="text" name="opsi_b" class="form-control" style="border-radius: 0 12px 12px 0;" required value="{{ old('opsi_b', $bankSoal->opsi_b) }}">
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Opsi C <span style="color:#EF4444">*</span></label>
                <div style="display:flex; align-items:center;">
                    <span style="background: #E2E8F0; padding: 0.875rem 1rem; border: 1px solid var(--border); border-right: none; border-radius: 12px 0 0 12px; font-weight: 600;">C</span>
                    <input type="text" name="opsi_c" class="form-control" style="border-radius: 0 12px 12px 0;" required value="{{ old('opsi_c', $bankSoal->opsi_c) }}">
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Opsi D <span style="color:#EF4444">*</span></label>
                <div style="display:flex; align-items:center;">
                    <span style="background: #E2E8F0; padding: 0.875rem 1rem; border: 1px solid var(--border); border-right: none; border-radius: 12px 0 0 12px; font-weight: 600;">D</span>
                    <input type="text" name="opsi_d" class="form-control" style="border-radius: 0 12px 12px 0;" required value="{{ old('opsi_d', $bankSoal->opsi_d) }}">
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Opsi E <span class="text-muted" style="font-weight:normal; font-size:0.85rem;">(Opsional)</span></label>
                <div style="display:flex; align-items:center;">
                    <span style="background: #E2E8F0; padding: 0.875rem 1rem; border: 1px solid var(--border); border-right: none; border-radius: 12px 0 0 12px; font-weight: 600;">E</span>
                    <input type="text" name="opsi_e" class="form-control" style="border-radius: 0 12px 12px 0;" value="{{ old('opsi_e', $bankSoal->opsi_e) }}">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Kunci Jawaban <span style="color:#EF4444">*</span></label>
            <select name="jawaban_benar" class="form-control" required style="cursor: pointer; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%231F2937%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 1rem top 50%; background-size: 0.65rem auto;">
                <option value="a" {{ old('jawaban_benar', $bankSoal->jawaban_benar) == 'a' ? 'selected' : '' }}>Opsi A</option>
                <option value="b" {{ old('jawaban_benar', $bankSoal->jawaban_benar) == 'b' ? 'selected' : '' }}>Opsi B</option>
                <option value="c" {{ old('jawaban_benar', $bankSoal->jawaban_benar) == 'c' ? 'selected' : '' }}>Opsi C</option>
                <option value="d" {{ old('jawaban_benar', $bankSoal->jawaban_benar) == 'd' ? 'selected' : '' }}>Opsi D</option>
                <option value="e" {{ old('jawaban_benar', $bankSoal->jawaban_benar) == 'e' ? 'selected' : '' }}>Opsi E</option>
            </select>
        </div>

        <div style="border-top: 1px solid var(--border); padding-top: 1.5rem; display: flex; justify-content: flex-end; gap: 1rem;">
            <a href="{{ route('admin.bank-soal.index', ['kategori_id' => $kategori->id]) }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
        </div>
    </form>
</div>

@endsection
