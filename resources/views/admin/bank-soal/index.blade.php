@extends('layouts.app')
@section('content')

<div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <div style="display:flex; align-items:center; gap: 0.5rem; margin-bottom: 0.5rem;">
            <a href="{{ route('admin.kategori-bank-soal.index') }}" style="color: rgba(255,255,255,0.7); font-weight: 500;"><i class="fas fa-arrow-left"></i> Kembali ke Kategori</a>
        </div>
        <h1 class="page-title" style="margin: 0;">Bank Soal: {{ $kategori->nama_kategori }}</h1>
        <p class="subtitle">{{ $kategori->deskripsi ?: 'Kelola semua soal untuk kategori ini.' }}</p>
    </div>
    <a href="{{ route('admin.bank-soal.create', ['kategori_id' => $kategori->id]) }}" class="btn btn-primary" style="background: white; color: var(--primary);"><i class="fas fa-plus"></i> Tambah Soal Baru</a>
</div>

<div class="card" style="margin-bottom: 1.5rem;">
    <form action="{{ route('admin.bank-soal.index') }}" method="GET" style="display:flex; gap:1.5rem; align-items:center;">
        <input type="hidden" name="kategori_id" value="{{ $kategori->id }}">
        <div style="flex-grow:1;">
            <label class="form-label" style="margin-bottom: 0.5rem; display: block;">Cari Pertanyaan</label>
            <div style="position: relative;">
                <i class="fas fa-search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik kata kunci pertanyaan..." class="form-control" style="padding-left: 2.75rem;">
            </div>
        </div>
        <div style="align-self: flex-end;">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>
</div>

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
                @forelse($bankSoals as $soal)
                <tr>
                    <td>
                        <div style="background: #F8FAFC; padding: 1rem; border-radius: 8px; border: 1px solid var(--border);">
                            {!! Str::limit($soal->pertanyaan, 200) !!}
                        </div>
                    </td>
                    <td><span class="badge badge-success" style="font-size: 1rem;">{{ strtoupper($soal->jawaban_benar) }}</span></td>
                    <td style="text-align: right;">
                        <div style="display:flex; gap:0.5rem; justify-content: flex-end;">
                            <a href="{{ route('admin.bank-soal.edit', $soal->id) }}" class="btn btn-secondary" style="padding: 0.5rem 1rem;"><i class="fas fa-edit"></i> Edit</a>
                            <form action="{{ route('admin.bank-soal.destroy', $soal->id) }}" method="POST" style="display:inline;" data-confirm="Apakah Anda yakin ingin menghapus data ini?" data-type="confirm" data-title="Konfirmasi Hapus">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary" style="padding: 0.5rem 1rem; border-color: #FEE2E2; background: #FEF2F2; color: #EF4444;"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align:center; padding: 3rem 1rem; color: var(--text-muted);">
                        <i class="fas fa-file-excel" style="font-size: 3rem; color: #CBD5E1; margin-bottom: 1rem;"></i>
                        <p>Belum ada soal di kategori ini.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
