@extends('layouts.app')
@section('content')

<div class="page-header" style="padding: 3rem 2rem; border-radius: 24px; background: white; border: 1px solid #E2E8F0; box-shadow: var(--shadow-sm); margin-bottom: 2.5rem; position: relative; overflow: hidden;">
    <!-- Abstract background decorations for premium feel -->
    <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; border-radius: 50%; background: rgba(56, 189, 248, 0.08); filter: blur(30px); pointer-events: none;"></div>
    <div style="position: absolute; bottom: -50px; left: -50px; width: 150px; height: 150px; border-radius: 50%; background: rgba(36, 58, 94, 0.08); filter: blur(30px); pointer-events: none;"></div>

    <h1 class="page-title" style="font-size: 2.6rem; font-weight: 800; color: #1E293B; letter-spacing: -0.5px; margin-bottom: 0.5rem;">Riwayat Transaksi</h1>
    <p class="subtitle" style="font-size: 1.1rem; max-width: 600px; color: #64748B; margin: 0; line-height: 1.6;">Daftar lengkap transaksi dan pembayaran paket tryout premium Anda.</p>
</div>

@if($transactions->isEmpty())
<div class="card" style="text-align:center; padding: 4.5rem 2rem; border-radius: 24px !important; background: rgba(36, 58, 94, 0.95) !important; border: 1px solid rgba(255, 255, 255, 0.12) !important;">
    <div style="width: 100px; height: 100px; border-radius: 50%; background: rgba(255, 255, 255, 0.04); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.75rem auto; font-size: 3rem; color: #94A3B8; border: 1px solid rgba(255, 255, 255, 0.08);">
        <i class="fas fa-receipt" style="color: #38BDF8;"></i>
    </div>
    <h2 style="color: white; font-weight: 800; margin: 0 0 0.5rem 0; font-size: 1.6rem;">Belum Ada Transaksi</h2>
    <p style="color: #CBD5E1; max-width: 480px; margin: 0 auto 2.5rem auto; font-size: 0.95rem; line-height: 1.6;">Anda belum pernah melakukan pembelian paket. Silakan kunjungi galeri paket kami untuk membeli simulasi tryout pilihan Anda.</p>
    <a href="{{ route('user.paket') }}" class="btn btn-primary" style="padding: 0.9rem 2.25rem; font-size: 1rem; border-radius: 50px; font-weight: 700; justify-content: center; margin: 0 auto; display: inline-flex; gap: 0.5rem;">
        Pilih Paket Sekarang
    </a>
</div>
@else
<div class="card" style="border-radius: 24px !important; background: rgba(36, 58, 94, 0.95) !important; border: 1px solid rgba(255, 255, 255, 0.12) !important; padding: 2rem !important; margin-bottom: 3rem;">
    <div class="table-container" style="border: none !important; box-shadow: none !important; padding: 0 1.5rem;">
        <table style="width: 100%; border-collapse: separate; border-spacing: 0 0.75rem;">
            <thead>
                <tr>
                    <th style="padding: 1rem 1.25rem 1rem 2rem; border-radius: 12px 0 0 12px; font-family: 'Poppins', sans-serif;">Tanggal Transaksi</th>
                    <th style="padding: 1rem 1.25rem; font-family: 'Poppins', sans-serif;">Nama Paket</th>
                    <th style="padding: 1rem 1.25rem; font-family: 'Poppins', sans-serif;">Nominal Pembayaran</th>
                    <th style="padding: 1rem 2rem 1rem 1.25rem; border-radius: 0 12px 12px 0; text-align: right; font-family: 'Poppins', sans-serif;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $trx)
                @php
                    $pkgName = $trx->package ? strtolower($trx->package->name) : '';
                    $iconClass = 'fa-receipt';
                    $iconColor = '#38BDF8';
                    
                    if (str_contains($pkgName, 'cpns') || str_contains($pkgName, 'skd') || str_contains($pkgName, 'twk') || str_contains($pkgName, 'tiu') || str_contains($pkgName, 'tkp')) {
                        $iconClass = 'fa-building-columns';
                        $iconColor = '#10B981';
                    } elseif (str_contains($pkgName, 'tps') || str_contains($pkgName, 'tpa')) {
                        $iconClass = 'fa-brain';
                        $iconColor = '#A855F7';
                    } elseif (str_contains($pkgName, 'premium') || str_contains($pkgName, 'crown') || str_contains($pkgName, 'vip')) {
                        $iconClass = 'fa-crown';
                        $iconColor = '#EAB308';
                    }
                @endphp
                <tr style="background: rgba(255, 255, 255, 0.02); transition: all 0.2s ease;">
                    <td style="padding: 1.5rem 1.25rem 1.5rem 2rem; border-top: 1px solid rgba(255,255,255,0.06); border-bottom: 1px solid rgba(255,255,255,0.06); border-left: 1px solid rgba(255,255,255,0.06); border-radius: 16px 0 0 16px; width: 25%;">
                        <span style="color: #CBD5E1; font-size: 0.88rem; font-weight: 500; display: inline-flex; align-items: center; gap: 6px;">
                            <i class="far fa-calendar-alt" style="color: #38BDF8;"></i> {{ $trx->created_at->format('d M Y') }}
                        </span>
                        <small style="display: block; color: #CBD5E1; font-size: 0.75rem; margin-top: 4px; opacity: 0.8; padding-left: 20px;">
                            Pukul {{ $trx->created_at->format('H:i') }} WIB
                        </small>
                    </td>
                    <td style="padding: 1.5rem 1.25rem; border-top: 1px solid rgba(255,255,255,0.06); border-bottom: 1px solid rgba(255,255,255,0.06); width: 35%;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(255,255,255,0.04); color: {{ $iconColor }}; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; border: 1px solid rgba(255,255,255,0.06); flex-shrink: 0;">
                                <i class="fas {{ $iconClass }}"></i>
                            </div>
                            <span style="font-weight: 700; color: white; font-size: 1.05rem;">{{ $trx->package->name ?? 'Paket Tidak Tersedia' }}</span>
                        </div>
                    </td>
                    <td style="padding: 1.5rem 1.25rem; border-top: 1px solid rgba(255,255,255,0.06); border-bottom: 1px solid rgba(255,255,255,0.06); width: 25%;">
                        <span style="font-weight: 800; font-size: 1.15rem; color: #38BDF8 !important;">Rp {{ number_format($trx->amount, 0, ',', '.') }}</span>
                    </td>
                    <td style="padding: 1.5rem 2rem 1.5rem 1.25rem; border-top: 1px solid rgba(255,255,255,0.06); border-bottom: 1px solid rgba(255,255,255,0.06); border-right: 1px solid rgba(255,255,255,0.06); border-radius: 0 16px 16px 0; text-align: right; width: 15%;">
                        @php
                            $status = strtolower($trx->status);
                            $badgeBg = '#FEF3C7';
                            $badgeText = '#92400E';
                            $badgeBorder = 'rgba(245, 158, 11, 0.2)';
                            
                            if ($status == 'success' || $status == 'settlement' || $status == 'capture') {
                                $badgeBg = '#DEF7EC';
                                $badgeText = '#03543F';
                                $badgeBorder = 'rgba(16, 185, 129, 0.2)';
                            } elseif ($status == 'failed' || $status == 'deny' || $status == 'cancel' || $status == 'expire') {
                                $badgeBg = '#FDE8E8';
                                $badgeText = '#9B1C1C';
                                $badgeBorder = 'rgba(239, 68, 68, 0.2)';
                            }
                        @endphp
                        <span class="badge" style="background: {{ $badgeBg }} !important; color: {{ $badgeText }} !important; font-size: 0.72rem; padding: 0.35rem 0.8rem; font-weight: 800; border-radius: 50px; border: 1px solid {{ $badgeBorder }} !important; display: inline-flex; align-items: center; gap: 4px; text-transform: uppercase;">
                            <span style="width: 6px; height: 6px; border-radius: 50%; background: {{ $badgeText }}; display: inline-block;"></span>
                            {{ $trx->status }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination Section -->
    <div class="custom-pagination-wrapper" style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.08);">
        {{ $transactions->links() }}
    </div>
</div>
@endif

<style>
    tbody tr {
        background: transparent !important;
    }
    tbody tr:hover {
        background: rgba(255, 255, 255, 0.04) !important;
        transform: scale(1.005);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    /* Style custom pagination inside dark cards */
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
    
    /* Hide the duplicate mobile pagination container */
    .custom-pagination-wrapper nav > div:first-child {
        display: none !important;
    }
    
    /* Desktop/Main pagination container */
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
    
    /* Showing results text style */
    .custom-pagination-wrapper nav p {
        margin: 0;
        font-size: 0.9rem;
        color: #CBD5E1 !important;
    }
    
    .custom-pagination-wrapper nav p span {
        font-weight: 600;
        color: #38BDF8 !important;
    }
    
    /* Container for pagination links */
    .custom-pagination-wrapper nav span.relative.z-0 {
        display: inline-flex;
        border-radius: 10px;
        box-shadow: var(--shadow-sm);
        background: rgba(255, 255, 255, 0.05);
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
    }
    
    /* Style individual pagination buttons */
    .custom-pagination-wrapper nav span.relative.z-0 a,
    .custom-pagination-wrapper nav span.relative.z-0 span.relative {
        padding: 8px 14px !important;
        font-size: 0.85rem !important;
        font-weight: 600 !important;
        color: white !important;
        border-right: 1px solid rgba(255, 255, 255, 0.1) !important;
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
    
    /* Hover effects */
    .custom-pagination-wrapper nav span.relative.z-0 a:hover {
        background: rgba(255, 255, 255, 0.1) !important;
        color: #38BDF8 !important;
    }
    
    /* Active page styling */
    .custom-pagination-wrapper nav span.relative.z-0 span.relative[aria-current="page"] {
        background: var(--primary-gradient) !important;
        color: white !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
    }
    
    /* Fix SVG sizing inside pagination */
    .custom-pagination-wrapper svg {
        width: 14px !important;
        height: 14px !important;
        display: inline-block !important;
        vertical-align: middle !important;
        fill: currentColor;
    }
</style>

@endsection
