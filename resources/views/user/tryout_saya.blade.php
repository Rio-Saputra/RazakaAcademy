@extends('layouts.app')
@section('content')

<div class="page-header">
    <h1 class="page-title">Tryout Saya</h1>
    <p class="subtitle">Daftar tryout yang tersedia dari paket yang Anda miliki.</p>
</div>

@if(session('success'))
    <div class="badge badge-success" style="padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; display: block; font-size: 1rem;">
        <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i> {{ session('success') }}
    </div>
@endif

@if($attempts->isEmpty())
<div class="card" style="text-align:center; padding: 4rem 2rem;">
    <div style="font-size: 5rem; color: #CBD5E1; margin-bottom: 1.5rem;"><i class="fas fa-box-open"></i></div>
    <h2 style="color: var(--text); font-weight: 700; margin-bottom: 0.5rem;">Belum Ada Tryout</h2>
    <p style="color: var(--text-muted); margin-bottom: 2rem;">Anda belum memiliki paket tryout yang aktif. Silakan beli paket terlebih dahulu.</p>
    <a href="{{ route('user.paket') }}" class="btn btn-primary" style="padding: 0.75rem 2rem;"><i class="fas fa-shopping-cart"></i> Beli Paket Sekarang</a>
</div>
@else
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem;">
    @foreach($attempts as $attempt)
    @php $t = $attempt->tryout; @endphp
    <div class="card" style="position: relative; overflow: hidden; border-top: 4px solid var(--primary); display: flex; flex-direction: column;">
        <h3 style="margin-top: 0; color: var(--text); font-weight: 700; font-size: 1.25rem;">{{ $t->title }}</h3>
        
        @php
            $sisa = $attempt->max_attempt - $attempt->attempt_count;
            $isActive = $sisa > 0;
        @endphp
        
        <div style="display: flex; gap: 1rem; margin: 1rem 0 1.5rem 0;">
            <span class="badge badge-warning" style="font-size: 0.85rem;"><i class="far fa-clock"></i> {{ $t->duration }} Menit</span>
            @if($isActive)
                <span class="badge badge-success" style="font-size: 0.85rem;"><i class="fas fa-check"></i> Tersedia ({{ $sisa }}x lagi)</span>
            @else
                <span class="badge badge-danger" style="font-size: 0.85rem;"><i class="fas fa-times"></i> Tidak tersedia</span>
            @endif
        </div>
        
        <p style="color: var(--text-muted); font-size: 0.95rem; flex-grow: 1; margin-bottom: 1rem;">Siapkan diri Anda dengan baik sebelum memulai. Pastikan koneksi internet stabil.</p>
        
        @if($isActive)
        <a href="{{ route('user.tryout.kerjakan', $t->id) }}" class="btn btn-primary" style="width: 100%; text-align: center;">
            <i class="fas fa-play" style="margin-right: 0.5rem;"></i> Mulai Kerjakan
        </a>
        @else
        <p style="color: #F59E0B; font-size: 0.85rem; text-align: center; margin-bottom: 1rem;">Kesempatan baru tersedia setelah pembelian ulang</p>
        <button class="btn btn-secondary" disabled style="width: 100%; text-align: center; opacity: 0.7; cursor: not-allowed;">
            Selesai Dikerjakan
        </button>
        @endif
    </div>
    @endforeach
</div>
@endif

@endsection
