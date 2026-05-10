@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1 class="page-title">Halo, Admin 👋</h1>
    <p class="subtitle">Berikut ringkasan sistem RAZAKA ACADEMY hari ini</p>
</div>
<div class="card" style="background: linear-gradient(135deg,#243A5E,#2F4F7F); color:white;">
    <h3 style="color:white;">Selamat Datang di Dashboard Admin</h3>
    <p style="opacity:0.8;">Kelola sistem, pantau user, dan kontrol tryout dengan mudah.</p>
</div>

<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(56, 189, 248, 0.1); color: #38BDF8;">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <h3>Total User</h3>
           <p style="font-size:28px; background:linear-gradient(135deg,#243A5E,#38BDF8); -webkit-background-clip:text; -webkit-text-fill-color:transparent;">
    {{ $total_user }}
</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">
            <i class="fas fa-file-alt"></i>
        </div>
        <div class="stat-info">
            <h3>Total Tryout</h3>
            <p>{{ $total_tryout }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(249, 115, 22, 0.1); color: #F97316;">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <div class="stat-info">
            <h3>Total Transaksi</h3>
            <p>{{ $total_transaksi }}</p>
        </div>
    </div>

</div>

<!-- OPTIONAL TABLE -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Aktivitas Terbaru</h3>
        <button class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Lihat Semua</button>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Tryout yang Dikerjakan</th>
                    <th>Nilai</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent_activities as $activity)
                <tr>
                    <td>
                        <div style="display:flex; align-items:center; gap:0.75rem;">
                            <div style="width:32px; height:32px; border-radius:50%; background:#E2E8F0; display:flex; align-items:center; justify-content:center;">
                                <i class="fas fa-user" style="color:#64748B;"></i>
                            </div>
                            <span style="font-weight:500;">{{ $activity->user->name ?? 'User Unknown' }}</span>
                        </div>
                    </td>
                    <td>{{ $activity->tryout->title ?? 'Tryout Unknown' }}</td>
                    <td><span class="badge {{ $activity->score >= 70 ? 'badge-success' : 'badge-warning' }}">{{ number_format($activity->score, 1) }}</span></td>
                    <td><span style="color:var(--text-muted);">{{ $activity->created_at->format('d M Y, H:i') }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 2rem;">Belum ada aktivitas tryout terbaru.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection