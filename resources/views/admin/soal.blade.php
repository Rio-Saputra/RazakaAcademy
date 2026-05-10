@extends('layouts.app')
@section('content')

<div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h1 class="page-title" style="margin: 0;">Kelola Soal</h1>
        <p class="subtitle">Manajemen daftar pertanyaan untuk setiap tryout.</p>
    </div>
    @if($tryout_id)
    <a href="{{ route('admin.soal.create', ['tryout_id' => $tryout_id]) }}" class="btn btn-primary" style="background: white; color: var(--primary);"><i class="fas fa-plus"></i> Tambah Soal</a>
    @endif
</div>

@if(session('success'))
    <div class="badge badge-success" style="padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; display: block; font-size: 1rem;">
        <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i> {{ session('success') }}
    </div>
@endif

<div class="card" style="margin-bottom: 1.5rem;">
    <form action="{{ route('admin.soal.index') }}" method="GET" style="display:flex; gap:1.5rem; align-items:center;">
        <div style="flex-grow:1;">
            <label class="form-label" style="margin-bottom: 0.5rem; display: block;">Pilih Tryout</label>
            <div style="position: relative;">
                <i class="fas fa-filter" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                <select name="tryout_id" id="tryout_id" onchange="this.form.submit()" class="form-control select-search">
                    <option value="">-- Silakan Pilih Tryout --</option>
                    @foreach($tryouts as $t)
                        <option value="{{ $t->id }}" {{ $tryout_id == $t->id ? 'selected' : '' }}>{{ $t->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
</div>

@if($tryout_id)
<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 50%;">Pertanyaan</th>
                    <th>Kunci Jawaban</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($questions as $q)
                <tr>
                    <td>
                        <div style="background: #F8FAFC; padding: 1rem; border-radius: 8px; border: 1px solid var(--border);">
                            {!! Str::limit($q->question_text, 150) !!}
                        </div>
                    </td>
                    <td><span class="badge badge-success" style="font-size: 1rem;">{{ $q->correct_answer }}</span></td>
                    <td style="text-align: right;">
                        <a href="{{ route('admin.soal.edit', $q->id) }}" class="btn btn-secondary" style="padding: 0.5rem 1rem;"><i class="fas fa-edit"></i> Edit</a>
                        <form action="{{ route('admin.soal.destroy', $q->id) }}" method="POST" style="display:inline;" data-confirm="Yakin ingin menghapus soal ini?" data-type="confirm" data-title="Konfirmasi Hapus">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 0.5rem 1rem;"><i class="fas fa-trash"></i> Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align:center; padding: 3rem 1rem; color: var(--text-muted);">
                        <i class="fas fa-file-excel" style="font-size: 3rem; color: #CBD5E1; margin-bottom: 1rem;"></i>
                        <p>Belum ada soal untuk tryout ini.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@else
<div class="card" style="text-align:center; padding: 4rem 2rem;">
    <i class="fas fa-hand-pointer" style="font-size: 4rem; color: #CBD5E1; margin-bottom: 1.5rem;"></i>
    <h3 style="font-weight: 600; margin-bottom: 0.5rem;">Pilih Tryout Terlebih Dahulu</h3>
    <p style="color: var(--text-muted);">Silakan pilih tryout dari dropdown di atas untuk mulai mengelola soal.</p>
</div>
@endif

@endsection
