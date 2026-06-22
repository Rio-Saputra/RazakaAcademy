@extends('layouts.app')
@section('content')

<!-- Header Halaman -->
<div class="page-header" style="margin-bottom: 2.25rem;">
    <h1 class="page-title" style="display: flex; align-items: center; gap: 0.75rem;">
        <i class="fas fa-receipt" style="color: #243A5E;"></i> Data Transaksi Pembayaran
    </h1>
    <p class="subtitle" style="margin-top: 0.25rem;">Riwayat pembelian paket tryout oleh pengguna dan data pembayaran terintegrasi.</p>
</div>

<!-- Panel Kartu Statistik Finansial -->
<div class="stats-grid-p">
    <!-- Total Pendapatan -->
    <div class="stat-card-p">
        <div class="stat-icon-wrapper-p color-emerald">
            <i class="fas fa-wallet"></i>
        </div>
        <div>
            <span class="stat-label-p" style="font-size: 0.85rem; color: #64748B; font-weight: 500;">Total Pendapatan</span>
            <h3 class="stat-value-p" style="font-size: 1.5rem; font-weight: 700; color: #1E293B; margin: 0.2rem 0 0 0;">Rp {{ number_format($total_income, 0, ',', '.') }}</h3>
        </div>
    </div>
    
    <!-- Transaksi Sukses -->
    <div class="stat-card-p">
        <div class="stat-icon-wrapper-p color-indigo">
            <i class="fas fa-check-circle"></i>
        </div>
        <div>
            <span class="stat-label-p" style="font-size: 0.85rem; color: #64748B; font-weight: 500;">Transaksi Sukses</span>
            <h3 class="stat-value-p" style="font-size: 1.75rem; font-weight: 700; color: #1E293B; margin: 0.2rem 0 0 0;">{{ $successful_trxs }}</h3>
        </div>
    </div>

    <!-- Transaksi Pending -->
    <div class="stat-card-p">
        <div class="stat-icon-wrapper-p color-amber">
            <i class="fas fa-clock"></i>
        </div>
        <div>
            <span class="stat-label-p" style="font-size: 0.85rem; color: #64748B; font-weight: 500;">Transaksi Pending</span>
            <h3 class="stat-value-p" style="font-size: 1.75rem; font-weight: 700; color: #1E293B; margin: 0.2rem 0 0 0;">{{ $pending_trxs }}</h3>
        </div>
    </div>

    <!-- Transaksi Gagal -->
    <div class="stat-card-p">
        <div class="stat-icon-wrapper-p color-red">
            <i class="fas fa-times-circle"></i>
        </div>
        <div>
            <span class="stat-label-p" style="font-size: 0.85rem; color: #64748B; font-weight: 500;">Gagal / Batal</span>
            <h3 class="stat-value-p" style="font-size: 1.75rem; font-weight: 700; color: #1E293B; margin: 0.2rem 0 0 0;">{{ $failed_trxs }}</h3>
        </div>
    </div>
</div>

<!-- Pencarian dan Filter -->
<div class="card" style="margin-bottom: 2rem; padding: 1.25rem 1.5rem; border-radius: 16px; border: 1px solid #E2E8F0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01);">
    <form action="{{ route('admin.transaksi') }}" method="GET" style="margin: 0;">
        <div style="display: flex; gap: 1.25rem; align-items: center; flex-wrap: wrap;">
            <!-- Input Cari -->
            <div style="flex: 1; min-width: 280px; position: relative;">
                <i class="fas fa-search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94A3B8;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pengguna, email, paket, atau token..." style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.75rem; border-radius: 12px; border: 1px solid #CBD5E1; font-size: 0.95rem; transition: border-color 0.2s, box-shadow 0.2s; background: white;" />
            </div>
            
            <!-- Dropdown Status -->
            <div style="width: 180px; min-width: 150px;">
                <select name="status" style="width: 100%; padding: 0.75rem 1rem; border-radius: 12px; border: 1px solid #CBD5E1; font-size: 0.95rem; background: white; cursor: pointer;">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Sukses (Success)</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Tertunda (Pending)</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal / Batal</option>
                </select>
            </div>

            <!-- Tombol Submit & Reset -->
            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem; font-weight: 600; border-radius: 12px; border: none; display: inline-flex; align-items: center; gap: 0.35rem;">
                    <i class="fas fa-filter"></i> Filter
                </button>
                @if(request()->filled('search') || request('status', 'all') !== 'all')
                    <a href="{{ route('admin.transaksi') }}" class="btn btn-secondary" style="padding: 0.75rem 1.5rem; font-weight: 600; border-radius: 12px; border: 1px solid #CBD5E1; background: white; color: #475569; display: inline-flex; align-items: center; gap: 0.35rem; justify-content: center; text-decoration: none;">
                        <i class="fas fa-sync-alt"></i> Reset
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>

<!-- Tabel Transaksi -->
<div class="card" style="border-radius: 16px; overflow: hidden; border: 1px solid #E2E8F0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02); margin-bottom: 2rem;">
    <div class="table-container" style="margin: 0; border-radius: 0;">
        <table>
            <thead>
                <tr style="background: #F8FAFC;">
                    <th style="padding: 1.25rem 1.5rem; font-weight: 600; color: #475569; font-size: 0.9rem; border-bottom: 1px solid #E2E8F0;">Info Transaksi</th>
                    <th style="padding: 1.25rem 1.5rem; font-weight: 600; color: #475569; font-size: 0.9rem; border-bottom: 1px solid #E2E8F0;">Pengguna</th>
                    <th style="padding: 1.25rem 1.5rem; font-weight: 600; color: #475569; font-size: 0.9rem; border-bottom: 1px solid #E2E8F0;">Paket Ujian</th>
                    <th style="padding: 1.25rem 1.5rem; font-weight: 600; color: #475569; font-size: 0.9rem; border-bottom: 1px solid #E2E8F0;">Nominal</th>
                    <th style="padding: 1.25rem 1.5rem; font-weight: 600; color: #475569; font-size: 0.9rem; border-bottom: 1px solid #E2E8F0; text-align: right;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                @php
                    $pkgName = $trx->package ? strtolower($trx->package->name) : '';
                    $iconClass = 'fa-receipt';
                    $iconColor = '#6366F1';
                    $iconBg = '#EEF2FF';
                    
                    if (str_contains($pkgName, 'cpns') || str_contains($pkgName, 'skd') || str_contains($pkgName, 'twk') || str_contains($pkgName, 'tiu') || str_contains($pkgName, 'tkp')) {
                        $iconClass = 'fa-building-columns';
                        $iconColor = '#10B981';
                        $iconBg = '#ECFDF5';
                    } elseif (str_contains($pkgName, 'tps') || str_contains($pkgName, 'tpa')) {
                        $iconClass = 'fa-brain';
                        $iconColor = '#A855F7';
                        $iconBg = '#FAF5FF';
                    } elseif (str_contains($pkgName, 'premium') || str_contains($pkgName, 'crown') || str_contains($pkgName, 'vip')) {
                        $iconClass = 'fa-crown';
                        $iconColor = '#D97706';
                        $iconBg = '#FFFDF5';
                    }
                @endphp
                <tr>
                    <!-- Info Transaksi -->
                    <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #F1F5F9; width: 22%;">
                        <span style="color: #1E293B; font-size: 0.9rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                            <i class="far fa-calendar-alt" style="color: #243A5E;"></i> {{ $trx->created_at->format('d M Y') }}
                        </span>
                        <span style="display: block; color: #64748B; font-size: 0.78rem; margin-top: 4px; padding-left: 20px;">
                            Pukul {{ $trx->created_at->format('H:i') }} WIB
                        </span>
                    </td>
                    
                    <!-- Pengguna -->
                    <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #F1F5F9; width: 25%;">
                        <span style="font-weight: 600; color: #1E293B; font-size: 0.95rem; display: block;">{{ $trx->user->name ?? 'User Terhapus' }}</span>
                        <span style="color: #64748B; font-size: 0.8rem; display: block; margin-top: 2px;">{{ $trx->user->email ?? '-' }}</span>
                    </td>
                    
                    <!-- Paket Ujian -->
                    <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #F1F5F9; width: 25%;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 36px; height: 36px; border-radius: 8px; background: {{ $iconBg }}; color: {{ $iconColor }}; display: flex; align-items: center; justify-content: center; font-size: 0.95rem; flex-shrink: 0; border: 1px solid rgba(0, 0, 0, 0.02);">
                                <i class="fas {{ $iconClass }}"></i>
                            </div>
                            <span style="font-weight: 600; color: #334155; font-size: 0.95rem;">{{ $trx->package->name ?? 'Paket Tidak Tersedia' }}</span>
                        </div>
                    </td>
                    
                    <!-- Nominal -->
                    <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #F1F5F9; width: 15%;">
                        <span style="font-weight: 700; font-size: 1.05rem; color: #1E293B;">Rp {{ number_format($trx->amount, 0, ',', '.') }}</span>
                    </td>
                    
                    <!-- Status -->
                    <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #F1F5F9; text-align: right; width: 13%;">
                        @php
                            $status = strtolower($trx->status);
                            $badgeBg = '#FEF3C7';
                            $badgeText = '#92400E';
                            $badgeBorder = '#FDE68A';
                            
                            if ($status == 'success' || $status == 'settlement' || $status == 'capture') {
                                $badgeBg = '#DEF7EC';
                                $badgeText = '#03543F';
                                $badgeBorder = '#BCF0DA';
                            } elseif ($status == 'failed' || $status == 'deny' || $status == 'cancel' || $status == 'expire') {
                                $badgeBg = '#FDE8E8';
                                $badgeText = '#9B1C1C';
                                $badgeBorder = '#FCD9D9';
                            }
                        @endphp
                        <span class="badge" style="background: {{ $badgeBg }} !important; color: {{ $badgeText }} !important; font-size: 0.78rem; padding: 0.35rem 0.8rem; font-weight: 700; border-radius: 8px; border: 1px solid {{ $badgeBorder }} !important; display: inline-flex; align-items: center; gap: 4px; text-transform: uppercase;">
                            <span style="width: 5px; height: 5px; border-radius: 50%; background: {{ $badgeText }}; display: inline-block;"></span>
                            {{ $trx->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 4rem 1.5rem; text-align: center; color: #64748B;">
                        <div style="font-size: 3rem; margin-bottom: 1rem;"><i class="fas fa-receipt" style="color: #CBD5E1;"></i></div>
                        <h4 style="font-weight: 600; color: #475569; margin: 0 0 0.25rem 0;">Belum Ada Transaksi</h4>
                        <p style="font-size: 0.9rem; color: #94A3B8; margin: 0;">Tidak ditemukan data transaksi pembayaran dalam sistem.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination / Penomoran Halaman -->
@if($transactions->hasPages())
<div class="custom-pagination-wrapper">
    {{ $transactions->links() }}
</div>
@endif

<!-- Styles khusus visual premium -->
<style>
.stats-grid-p {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2.25rem;
}

.stat-card-p {
    background: white;
    border-radius: 16px;
    padding: 1.5rem 1.75rem;
    border: 1px solid #E2E8F0;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01), 0 2px 4px -1px rgba(0, 0, 0, 0.005);
    display: flex;
    align-items: center;
    gap: 1.25rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    text-align: left;
}

.stat-card-p:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.08);
    border-color: rgba(36, 58, 94, 0.15);
}

.stat-icon-wrapper-p {
    width: 52px;
    height: 52px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    flex-shrink: 0;
}

.color-indigo { background: #EEF2FF; color: #4F46E5; }
.color-amber { background: #FFFDF5; color: #D97706; border: 1px solid #FEF3C7; }
.color-emerald { background: #ECFDF5; color: #059669; }
.color-red { background: #FEF2F2; color: #DC2626; border: 1px solid #FEE2E2; }

input:focus, select:focus {
    outline: none;
    border-color: #243A5E !important;
    box-shadow: 0 0 0 3px rgba(36, 58, 94, 0.1) !important;
}

/* Custom light-theme pagination styles matching Razaka layout */
.custom-pagination-wrapper nav {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    width: 100%;
}

@media(min-width: 640px) {
    .custom-pagination-wrapper nav {
        flex-direction: row;
        justify-content: space-between;
    }
}

.custom-pagination-wrapper nav > div:first-child {
    display: none !important;
}

.custom-pagination-wrapper nav > div:last-child {
    display: flex !important;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    width: 100%;
}

@media(min-width: 768px) {
    .custom-pagination-wrapper nav > div:last-child {
        flex-direction: row;
        justify-content: space-between;
    }
}

.custom-pagination-wrapper nav p {
    margin: 0;
    font-size: 0.9rem;
    color: #475569 !important;
}

.custom-pagination-wrapper nav p span {
    font-weight: 600;
    color: #243A5E !important;
}

.custom-pagination-wrapper nav span.relative.z-0 {
    display: inline-flex;
    border-radius: 10px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    background: white;
    overflow: hidden;
    border: 1px solid #CBD5E1 !important;
}

.custom-pagination-wrapper nav span.relative.z-0 a,
.custom-pagination-wrapper nav span.relative.z-0 span.relative {
    padding: 8px 14px !important;
    font-size: 0.85rem !important;
    font-weight: 600 !important;
    color: #334155 !important;
    border-right: 1px solid #E2E8F0 !important;
    border-top: none !important;
    border-bottom: none !important;
    border-left: none !important;
    text-decoration: none !important;
    transition: all 0.2s ease !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    margin: 0 !important;
    border-radius: 0 !important;
    background: transparent !important;
}

.custom-pagination-wrapper nav span.relative.z-0 a:last-child,
.custom-pagination-wrapper nav span.relative.z-0 span.relative:last-child {
    border-right: none !important;
}

.custom-pagination-wrapper nav span.relative.z-0 a:hover {
    background: #F1F5F9 !important;
    color: #243A5E !important;
}

.custom-pagination-wrapper nav span.relative.z-0 span.relative[aria-current="page"] {
    background: linear-gradient(135deg, #243A5E, #2F4F7F) !important;
    color: white !important;
    border-color: rgba(36, 58, 94, 0.1) !important;
}

.custom-pagination-wrapper svg {
    width: 14px !important;
    height: 14px !important;
    display: inline-block !important;
    vertical-align: middle !important;
    fill: currentColor;
}
</style>

@endsection
