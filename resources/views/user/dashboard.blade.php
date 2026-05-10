@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
    <p class="subtitle">Siap untuk belajar dan mencapai impianmu hari ini?</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(56, 189, 248, 0.1); color: #38BDF8;">
            <i class="fas fa-edit"></i>
        </div>
        <div class="stat-info">
            <h3>Tryout Dikerjakan</h3>
            <p>{{ $total_tryout }}</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="stat-info">
            <h3>Rata-rata Nilai</h3>
            <p>{{ number_format($rata_rata, 1) }}</p>
        </div>
    </div>
</div>

<div class="card" style="margin-top: 2rem; position: relative; overflow: hidden; background: var(--primary-gradient); color: white; border: none; box-shadow: var(--shadow-md);">
    <!-- Decorative Circle -->
    <div style="position: absolute; right: -5%; top: -50%; width: 300px; height: 300px; border-radius: 50%; background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%); pointer-events: none;"></div>
    
    <div style="display:flex; justify-content:space-between; align-items:center; position: relative; z-index: 1;">
        <div>
            <h2 style="margin-top:0; color: white; font-size: 1.75rem; font-weight: 700; margin-bottom: 0.5rem;">Tingkatkan Kemampuanmu! 🚀</h2>
            <p style="opacity:0.9; font-size: 1.1rem; max-width: 500px;">Beli paket tryout premium sekarang dan akses ribuan soal berkualitas beserta pembahasan detailnya.</p>
        </div>
        <div style="flex-shrink: 0; margin-left: 2rem;">
            <a href="{{ route('user.paket') }}" class="btn" style="background: white; color: var(--primary); font-weight: 700; padding: 1rem 2rem; font-size: 1rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">Lihat Paket Premium</a>
        </div>
    </div>
</div>

<!-- Recommended Tryout Section -->
<h3 style="margin: 2.5rem 0 1.5rem; font-size: 1.25rem;">Rekomendasi Tryout</h3>
<div class="stats-grid">
    <div class="card" style="padding: 1.5rem; display: flex; flex-direction: column; height: 100%;">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(36,58,94,0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                <i class="fas fa-book"></i>
            </div>
            <div>
                <h4 style="font-weight: 600;">Tryout UTBK SNBT</h4>
                <span class="badge badge-warning">Populer</span>
            </div>
        </div>
        <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1.5rem; flex: 1;">Simulasi UTBK lengkap dengan TPS, Literasi Bahasa, dan Penalaran Matematika.</p>
        <a href="{{ route('user.tryout.index') }}" class="btn btn-outline" style="width: 100%; border: 1.5px solid var(--primary); color: var(--primary); padding: 0.5rem;">Mulai Kerjakan</a>
    </div>

    <div class="card" style="padding: 1.5rem; display: flex; flex-direction: column; height: 100%;">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(16,185,129,0.1); color: #10B981; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                <i class="fas fa-flask"></i>
            </div>
            <div>
                <h4 style="font-weight: 600;">Tryout Mandiri SAINTEK</h4>
                <span class="badge badge-success">Baru</span>
            </div>
        </div>
        <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1.5rem; flex: 1;">Persiapan ujian mandiri dengan soal-soal berstandar tinggi untuk program studi SAINTEK.</p>
        <a href="{{ route('user.tryout.index') }}" class="btn btn-outline" style="width: 100%; border: 1.5px solid var(--primary); color: var(--primary); padding: 0.5rem;">Mulai Kerjakan</a>
    </div>
</div>

@endsection
