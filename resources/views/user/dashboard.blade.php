@extends('layouts.app')
 
@section('content')
 
<!-- Wrapper Grid Dashboard Baru -->
<div class="dashboard-grid-layout">
    
    <!-- Kolom Kiri: Panel Utama -->
    <div class="dashboard-main-panel">
        
        <!-- Sapaan Kartu Sambutan Minimalis -->
        <div class="minimal-greeting-card">
            <div class="greeting-text-box">
                <h1 class="greeting-title">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
                <p class="greeting-subtitle">Mari tingkatkan kemampuan belajarmu hari ini dengan latihan terukur.</p>
                
                <!-- Stat Ringkas Didalam Greeting -->
                <div class="greeting-stats-row">
                    <div class="greeting-stat-item">
                        <span class="greeting-stat-val">{{ $total_tryout }}</span>
                        <span class="greeting-stat-lbl">Ujian Selesai</span>
                    </div>
                    <div class="greeting-divider"></div>
                    <div class="greeting-stat-item">
                        <span class="greeting-stat-val">{{ number_format($rata_rata, 1) }}</span>
                        <span class="greeting-stat-lbl">Rata-rata Nilai</span>
                    </div>
                </div>
            </div>
            <div class="greeting-icon-box">
                <i class="fas fa-graduation-cap"></i>
            </div>
        </div>
 
        <!-- Ubin Pintas Navigasi Minimalis (Quick-Navigation Tiles) -->
        <div class="quick-tiles-grid">
            <a href="{{ route('user.tryout.index') }}" class="quick-tile-card">
                <div class="tile-icon-wrapper" style="background: rgba(36, 58, 94, 0.08); color: var(--primary);">
                    <i class="fas fa-laptop-code"></i>
                </div>
                <div class="tile-info">
                    <h4>Mulai Belajar</h4>
                    <p>Lihat Tryout Saya</p>
                </div>
                <div class="tile-arrow"><i class="fas fa-chevron-right"></i></div>
            </a>
            
            <a href="{{ route('user.riwayat-tryout') }}" class="quick-tile-card">
                <div class="tile-icon-wrapper" style="background: rgba(16, 185, 129, 0.08); color: #10B981;">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="tile-info">
                    <h4>Riwayat Nilai</h4>
                    <p>Evaluasi Progress</p>
                </div>
                <div class="tile-arrow"><i class="fas fa-chevron-right"></i></div>
            </a>
 
            <a href="{{ route('user.paket') }}" class="quick-tile-card">
                <div class="tile-icon-wrapper" style="background: rgba(245, 158, 11, 0.08); color: #F59E0B;">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="tile-info">
                    <h4>Beli Paket</h4>
                    <p>Akses Premium</p>
                </div>
                <div class="tile-arrow"><i class="fas fa-chevron-right"></i></div>
            </a>
        </div>
 
        <!-- Section Rekomendasi Paket Ujian -->
        <h3 class="section-title-min">Rekomendasi Paket Ujian</h3>
        <div class="recommended-packages-grid">
            @forelse($recommended_packages as $p)
                @php
                    $packageName = strtolower($p->name);
                    $iconClass = 'fa-graduation-cap'; // Default beautiful icon
                    $iconColor = '#38BDF8'; // cyan
                    $iconBg = 'rgba(56, 189, 248, 0.12)';
                    $iconBorder = 'rgba(56, 189, 248, 0.2)';

                    if (str_contains($packageName, 'cpns') || str_contains($packageName, 'pns') || str_contains($packageName, 'asn')) {
                        $iconClass = 'fa-building-columns';
                        $iconColor = '#10B981'; // emerald green
                        $iconBg = 'rgba(16, 185, 129, 0.12)';
                        $iconBorder = 'rgba(16, 185, 129, 0.2)';
                    } elseif (str_contains($packageName, 'tps') || str_contains($packageName, 'tpa') || str_contains($packageName, 'brain') || str_contains($packageName, 'potensi')) {
                        $iconClass = 'fa-brain';
                        $iconColor = '#A855F7'; // purple
                        $iconBg = 'rgba(168, 85, 247, 0.12)';
                        $iconBorder = 'rgba(168, 85, 247, 0.2)';
                    } elseif (str_contains($packageName, 'utbk') || str_contains($packageName, 'snbt') || str_contains($packageName, 'sbmptn')) {
                        $iconClass = 'fa-university';
                        $iconColor = '#F59E0B'; // amber/orange
                        $iconBg = 'rgba(245, 158, 11, 0.12)';
                        $iconBorder = 'rgba(245, 158, 11, 0.2)';
                    } elseif (str_contains($packageName, 'kebab') || str_contains($packageName, 'food') || str_contains($packageName, 'kuliner')) {
                        $iconClass = 'fa-hamburger';
                        $iconColor = '#EF4444'; // red
                        $iconBg = 'rgba(239, 68, 68, 0.12)';
                        $iconBorder = 'rgba(239, 68, 68, 0.2)';
                    } elseif (str_contains($packageName, 'premium') || str_contains($packageName, 'gold') || str_contains($packageName, 'vip') || str_contains($packageName, 'eksklusif')) {
                        $iconClass = 'fa-crown';
                        $iconColor = '#EAB308'; // yellow/gold
                        $iconBg = 'rgba(234, 179, 8, 0.12)';
                        $iconBorder = 'rgba(234, 179, 8, 0.2)';
                    } elseif (str_contains($packageName, 'toefl') || str_contains($packageName, 'english') || str_contains($packageName, 'bahasa')) {
                        $iconClass = 'fa-language';
                        $iconColor = '#06B6D4'; // cyan-blue
                        $iconBg = 'rgba(6, 182, 212, 0.12)';
                        $iconBorder = 'rgba(6, 182, 212, 0.2)';
                    }
                @endphp
                <div class="rec-package-card">
                    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 5px; background: var(--primary-gradient);"></div>
                    <div class="rec-header">
                        <div class="rec-icon-box" style="background: {{ $iconBg }} !important; color: {{ $iconColor }} !important; border: 1px solid {{ $iconBorder }} !important;">
                            <i class="fas {{ $iconClass }}"></i>
                        </div>
                        <div class="rec-title-meta">
                            <h4>{{ $p->name }}</h4>
                            <span class="rec-price">Rp {{ number_format($p->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <p class="rec-desc">
                        {{ $p->description ?? 'Dapatkan akses penuh ke seluruh simulasi tryout berkualitas tinggi beserta pembahasan super lengkap.' }}
                    </p>
                    <form class="midtrans-buy-form" data-package-id="{{ $p->id }}" data-url="{{ route('user.paket.beli', $p->id) }}">
                        @csrf
                        <button type="button" class="btn btn-outline btn-buy-min btn-buy-package">
                            <i class="fas fa-shopping-cart"></i> Beli Paket Ini
                        </button>
                    </form>
                </div>
            @empty
                <div class="empty-packages-card">
                    <i class="fas fa-box-open empty-icon"></i>
                    <p>Belum ada rekomendasi paket tryout untuk Anda saat ini.</p>
                </div>
            @endforelse
        </div>
 
    </div>
 
    <!-- Kolom Kanan: Sidebar Panel -->
    <div class="dashboard-sidebar-panel">
        
        <!-- Kartu Profil Ringkas Siswa -->
        <div class="min-profile-card">
            <div class="avatar-wrapper">
                @php
                    $initials = '';
                    $words = explode(' ', Auth::user()->name);
                    foreach($words as $w) {
                        if(!empty($w)) $initials .= strtoupper($w[0]);
                    }
                    $initials = substr($initials, 0, 2);
                @endphp
                <div class="profile-avatar-circle">{{ $initials }}</div>
            </div>
            <h3 class="profile-name">{{ Auth::user()->name }}</h3>
            <p class="profile-email">{{ Auth::user()->email }}</p>
            <span class="profile-badge-role"><i class="fas fa-graduation-cap"></i> Siswa Razaka</span>
        </div>
 
        <!-- Log Pengerjaan Terakhir (Recent Activity) -->
        <div class="recent-activities-card">
            <h4 class="sidebar-sec-title">Ujian Terakhir Anda</h4>
            
            <div class="activities-list">
                @forelse($recent_results as $res)
                    <div class="activity-item-card">
                        <div class="activity-meta">
                            <span class="activity-date">{{ $res->created_at->locale('id')->diffForHumans() }}</span>
                            <span class="activity-score-badge {{ $res->score >= 70 ? 'badge-high' : 'badge-mid' }}">
                                Skor: {{ number_format($res->score, 0) }}
                            </span>
                        </div>
                        <h5 class="activity-tryout-name">{{ $res->tryout->title ?? 'Ujian' }}</h5>
                        <div class="activity-footer">
                            <a href="{{ route('user.tryout.hasil', $res->id) }}" class="activity-link-btn">
                                Lihat Review <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="empty-activity-box">
                        <i class="fas fa-edit" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.4;"></i>
                        <p>Belum ada ujian yang selesai dikerjakan.</p>
                        <a href="{{ route('user.tryout.index') }}" class="btn btn-outline" style="font-size:0.85rem; padding:0.4rem 1rem; border-radius: 50px;">Mulai Ujian</a>
                    </div>
                @endforelse
            </div>
        </div>
 
    </div>
 
</div>
 
@endsection
 
@push('styles')
<style>
/* Modern Minimalist Layout Styles for Student Dashboard */
.dashboard-grid-layout {
    display: grid;
    grid-template-columns: 2.2fr 1fr;
    gap: 2rem;
    align-items: start;
    margin-top: 1rem;
}
 
/* LEFT PANEL */
.minimal-greeting-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 2.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    overflow: hidden;
    margin-bottom: 2rem;
}
 
.greeting-text-box {
    position: relative;
    z-index: 2;
}
 
.greeting-title {
    font-size: 1.85rem;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 0.5rem;
}
 
.greeting-subtitle {
    color: var(--text-muted);
    font-size: 1rem;
    margin-bottom: 1.75rem;
    max-width: 480px;
}
 
.greeting-stats-row {
    display: flex;
    align-items: center;
    gap: 2rem;
}
 
.greeting-stat-item {
    display: flex;
    flex-direction: column;
}
 
.greeting-stat-val {
    font-size: 1.75rem;
    font-weight: 800;
    color: var(--primary);
    line-height: 1.1;
}
 
.greeting-stat-lbl {
    font-size: 0.8rem;
    color: var(--text-muted);
    font-weight: 500;
    margin-top: 0.25rem;
}
 
.greeting-divider {
    width: 1px;
    height: 35px;
    background: var(--border);
}
 
.greeting-icon-box {
    font-size: 5rem;
    color: rgba(36, 58, 94, 0.04);
    position: absolute;
    right: 2rem;
    bottom: -1rem;
    transform: rotate(-15deg);
    pointer-events: none;
    z-index: 1;
}
 
/* QUICK TILES */
.quick-tiles-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.25rem;
    margin-bottom: 2.5rem;
}
 
.quick-tile-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: var(--transition);
    cursor: pointer;
    position: relative;
}
 
.quick-tile-card:hover {
    transform: translateY(-4px);
    border-color: rgba(36, 58, 94, 0.15);
    box-shadow: var(--shadow-md);
}
 
.tile-icon-wrapper {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}
 
.tile-info {
    flex: 1;
}
 
.tile-info h4 {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--text);
    margin: 0;
}
 
.tile-info p {
    font-size: 0.8rem;
    color: var(--text-muted);
    margin: 0;
}
 
.tile-arrow {
    font-size: 0.8rem;
    color: var(--text-muted);
    opacity: 0;
    transform: translateX(-5px);
    transition: var(--transition);
}
 
.quick-tile-card:hover .tile-arrow {
    opacity: 1;
    transform: translateX(0);
}
 
/* RECOMMENDED PACKAGES */
.section-title-min {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text);
    margin: 0 0 1.25rem;
    display: flex;
    align-items: center;
}
 
.recommended-packages-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
}
 
.rec-package-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 2rem 1.75rem;
    display: flex;
    flex-direction: column;
    height: 100%;
    position: relative;
    overflow: hidden;
    transition: var(--transition);
}
 
.rec-package-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-hover);
    border-color: rgba(36, 58, 94, 0.12);
}
 
.rec-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.25rem;
}
 
.rec-icon-box {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: rgba(36, 58, 94, 0.06);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    flex-shrink: 0;
}
 
.rec-title-meta {
    flex: 1;
}
 
.rec-title-meta h4 {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--text);
    margin: 0;
}
 
.rec-price {
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--primary);
}
 
.rec-desc {
    font-size: 0.9rem;
    color: var(--text-muted);
    margin-bottom: 1.75rem;
    flex: 1;
    line-height: 1.5;
}
 
.btn-buy-min {
    width: 100%;
    border: 1.5px solid var(--primary);
    color: var(--primary);
    background: transparent;
    padding: 0.65rem;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 700;
    justify-content: center;
    transition: var(--transition);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
 
.btn-buy-min:hover {
    background: var(--primary);
    color: white;
    box-shadow: 0 5px 15px rgba(36, 58, 94, 0.15);
}
 
.empty-packages-card {
    background: white;
    border: 1.5px dashed var(--border);
    border-radius: var(--radius);
    padding: 3rem 2rem;
    text-align: center;
    grid-column: span 2;
    color: var(--text-muted);
}
 
.empty-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}
 
/* RIGHT PANEL (SIDEBAR) */
.min-profile-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 2.25rem 1.5rem;
    text-align: center;
    margin-bottom: 2rem;
}
 
.avatar-wrapper {
    display: flex;
    justify-content: center;
    margin-bottom: 1.25rem;
}
 
.profile-avatar-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--primary-gradient);
    color: white;
    font-size: 1.85rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 20px rgba(36, 58, 94, 0.15);
    border: 4px solid white;
}
 
.profile-name {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 0.25rem;
}
 
.profile-email {
    font-size: 0.85rem;
    color: var(--text-muted);
    margin-bottom: 1.25rem;
}
 
.profile-badge-role {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(36, 58, 94, 0.06);
    color: var(--primary);
    padding: 0.4rem 1rem;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
}
 
/* RECENT ACTIVITIES */
.recent-activities-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 1.75rem 1.5rem;
}
 
.sidebar-sec-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 1.25rem;
    border-bottom: 1px solid var(--border);
    padding-bottom: 0.75rem;
}
 
.activities-list {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}
 
.activity-item-card {
    background: #F8FAFC;
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 1rem;
    transition: var(--transition);
}
 
.activity-item-card:hover {
    transform: translateY(-2px);
    border-color: rgba(36, 58, 94, 0.1);
}
 
.activity-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}
 
.activity-date {
    font-size: 0.75rem;
    color: var(--text-muted);
    font-weight: 500;
}
 
.activity-score-badge {
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.2rem 0.5rem;
    border-radius: 6px;
}
 
.badge-high {
    background: #DEF7EC;
    color: #03543F;
}
 
.badge-mid {
    background: #E2E8F0;
    color: #475569;
}
 
.activity-tryout-name {
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 0.75rem;
    line-height: 1.4;
}
 
.activity-footer {
    display: flex;
    justify-content: flex-end;
}
 
.activity-link-btn {
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--primary);
    display: flex;
    align-items: center;
    gap: 0.25rem;
    transition: var(--transition);
}
 
.activity-link-btn:hover {
    gap: 0.4rem;
    color: var(--primary-light);
}
 
.empty-activity-box {
    text-align: center;
    padding: 2rem 1rem;
    color: var(--text-muted);
}
 
.empty-activity-box i {
    font-size: 2rem;
    margin-bottom: 0.75rem;
    opacity: 0.4;
}
 
.empty-activity-box p {
    font-size: 0.85rem;
    margin-bottom: 1rem;
}
 
/* RESPONSIVE LAYOUT OVERRIDES */
@media (max-width: 992px) {
    .dashboard-grid-layout {
        grid-template-columns: 1fr;
    }
}
 
@media (max-width: 768px) {
    .quick-tiles-grid {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }
    
    .recommended-packages-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
    document.querySelectorAll('.midtrans-buy-form .btn-buy-package').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('.midtrans-buy-form');
            const url = form.getAttribute('data-url');
            
            showModernAlert('buy', 'Konfirmasi Pembelian', 'Yakin ingin membeli paket tryout ini?', () => {
                // Show loading state
                const originalText = button.innerHTML;
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>...';
                
                // Perform AJAX request to get Snap Token
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        button.disabled = false;
                        button.innerHTML = originalText;
                        
                        if (response.success && response.snap_token) {
                            window.snap.pay(response.snap_token, {
                                onSuccess: function(result) {
                                    showModernAlert('success', 'Pembayaran Berhasil', 'Terima kasih, pembayaran sukses dilakukan! Halaman akan dialihkan.');
                                    setTimeout(() => {
                                        window.location.href = "{{ route('user.tryout.index') }}";
                                    }, 2000);
                                },
                                onPending: function(result) {
                                    showModernAlert('warning', 'Pembayaran Pending', 'Silakan selesaikan pembayaran Anda sebelum batas waktu berakhir.');
                                    setTimeout(() => {
                                        window.location.href = "{{ route('user.riwayat') }}";
                                    }, 2500);
                                },
                                onError: function(result) {
                                    showModernAlert('error', 'Pembayaran Gagal', 'Maaf, pembayaran gagal diproses.');
                                },
                                onClose: function() {
                                    showModernAlert('warning', 'Pembayaran Dibatalkan', 'Anda menutup panel pembayaran sebelum menyelesaikannya.');
                                }
                            });
                        } else {
                            showModernAlert('error', 'Gagal', response.message || 'Gagal memproses transaksi.');
                        }
                    },
                    error: function(xhr) {
                        button.disabled = false;
                        button.innerHTML = originalText;
                        
                        let msg = 'Terjadi kesalahan sistem.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        showModernAlert('error', 'Kesalahan', msg);
                    }
                });
            });
        });
    });
</script>
@endpush
