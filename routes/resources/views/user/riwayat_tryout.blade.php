@extends('layouts.app')
@section('content')

<div class="page-header" style="padding: 3rem 2rem; border-radius: 24px; background: white; border: 1px solid #E2E8F0; box-shadow: var(--shadow-sm); margin-bottom: 2.5rem; position: relative; overflow: hidden;">
    <!-- Abstract background decorations for premium feel -->
    <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; border-radius: 50%; background: rgba(56, 189, 248, 0.08); filter: blur(30px); pointer-events: none;"></div>
    <div style="position: absolute; bottom: -50px; left: -50px; width: 150px; height: 150px; border-radius: 50%; background: rgba(36, 58, 94, 0.08); filter: blur(30px); pointer-events: none;"></div>

    <h1 class="page-title" style="font-size: 2.6rem; font-weight: 800; color: #1E293B; letter-spacing: -0.5px; margin-bottom: 0.5rem;">Riwayat Tryout</h1>
    <p class="subtitle" style="font-size: 1.1rem; max-width: 600px; color: #64748B; margin: 0; line-height: 1.6;">Tinjau kembali pencapaian Anda, analisis kelemahan per kategori, dan pelajari pembahasan soal.</p>
</div>

@if($results->isEmpty())
<div class="card" style="text-align:center; padding: 4.5rem 2rem; border-radius: 24px !important; background: rgba(36, 58, 94, 0.95) !important; border: 1px solid rgba(255, 255, 255, 0.12) !important;">
    <div style="width: 100px; height: 100px; border-radius: 50%; background: rgba(255, 255, 255, 0.04); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.75rem auto; font-size: 3rem; color: #94A3B8; border: 1px solid rgba(255, 255, 255, 0.08);">
        <i class="fas fa-history" style="color: #38BDF8;"></i>
    </div>
    <h2 style="color: white; font-weight: 800; margin: 0 0 0.5rem 0; font-size: 1.6rem;">Belum Ada Riwayat Ujian</h2>
    <p style="color: #CBD5E1; max-width: 480px; margin: 0 auto 2.5rem auto; font-size: 0.95rem; line-height: 1.6;">Anda belum menyelesaikan simulasi ujian satu pun. Silakan kunjungi menu Tryout Saya untuk memulai latihan terukur Anda.</p>
    <a href="{{ route('user.tryout.index') }}" class="btn btn-primary" style="padding: 0.9rem 2.25rem; font-size: 1rem; border-radius: 50px; font-weight: 700; justify-content: center; margin: 0 auto; display: inline-flex; gap: 0.5rem;">
        Lihat Tryout Saya
    </a>
</div>
@else
<div class="card" style="border-radius: 24px !important; background: rgba(36, 58, 94, 0.95) !important; border: 1px solid rgba(255, 255, 255, 0.12) !important; padding: 2rem !important; margin-bottom: 3rem;">
    <div class="table-container" style="border: none !important; box-shadow: none !important; padding: 0 1.5rem;">
        <table style="width: 100%; border-collapse: separate; border-spacing: 0 0.75rem;">
            <thead>
                <tr>
                    <th style="padding: 1rem 1.25rem 1rem 2rem; border-radius: 12px 0 0 12px; font-family: 'Poppins', sans-serif;">Tryout</th>
                    <th style="padding: 1rem 1.25rem; font-family: 'Poppins', sans-serif;">Analisis Skor SKD</th>
                    <th style="padding: 1rem 1.25rem; font-family: 'Poppins', sans-serif;">Tanggal Pengerjaan</th>
                    <th style="padding: 1rem 2rem 1rem 1.25rem; border-radius: 0 12px 12px 0; text-align: right; font-family: 'Poppins', sans-serif;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results as $res)
                @php
                    $t = $res->tryout;
                    $titleLower = $t ? strtolower($t->title) : '';
                    $iconClass = 'fa-graduation-cap';
                    $iconColor = '#38BDF8';
                    
                    if (str_contains($titleLower, 'cpns') || str_contains($titleLower, 'skd') || str_contains($titleLower, 'twk') || str_contains($titleLower, 'tiu') || str_contains($titleLower, 'tkp')) {
                        $iconClass = 'fa-building-columns';
                        $iconColor = '#10B981';
                    } elseif (str_contains($titleLower, 'tps') || str_contains($titleLower, 'tpa')) {
                        $iconClass = 'fa-brain';
                        $iconColor = '#A855F7';
                    }
                @endphp
                <tr style="background: rgba(255, 255, 255, 0.02); transition: all 0.2s ease;">
                    <td style="padding: 1.5rem 1.25rem 1.5rem 2rem; border-top: 1px solid rgba(255,255,255,0.06); border-bottom: 1px solid rgba(255,255,255,0.06); border-left: 1px solid rgba(255,255,255,0.06); border-radius: 16px 0 0 16px; width: 35%;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(255,255,255,0.04); color: {{ $iconColor }}; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; border: 1px solid rgba(255,255,255,0.06); flex-shrink: 0;">
                                <i class="fas {{ $iconClass }}"></i>
                            </div>
                            <div>
                                <span style="font-weight: 700; color: white; display: block; font-size: 1.05rem; line-height: 1.3;">{{ $t->title ?? 'Tryout Ujian' }}</span>
                                <span style="font-size: 0.78rem; color: #CBD5E1; display: inline-flex; align-items: center; gap: 4px; margin-top: 4px;">
                                    <i class="far fa-clock"></i> Durasi: {{ $t->duration ?? 0 }} Menit
                                </span>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1.5rem 1.25rem; border-top: 1px solid rgba(255,255,255,0.06); border-bottom: 1px solid rgba(255,255,255,0.06); width: 35%;">
                        <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                            <div>
                                <div style="font-weight: 800; font-size: 1.3rem; color: #38BDF8 !important; display: flex; align-items: baseline; line-height: 1;">
                                    {{ $res->score }}
                                    <span style="font-size: 0.8rem; font-weight: 500; color: #CBD5E1; margin-left: 2px;">/550</span>
                                </div>
                                <span class="badge" style="background: {{ $res->is_passed ? '#DEF7EC' : '#FDE8E8' }} !important; color: {{ $res->is_passed ? '#03543F' : '#9B1C1C' }} !important; font-size: 0.72rem; padding: 0.25rem 0.6rem; font-weight: 800; border-radius: 50px; margin-top: 0.5rem; display: inline-block; border: 1px solid {{ $res->is_passed ? 'rgba(16, 185, 129, 0.2)' : 'rgba(239, 68, 68, 0.2)' }} !important;">
                                    {{ $res->is_passed ? 'LULUS SKD' : 'TIDAK LULUS' }}
                                </span>
                            </div>
                            
                            <!-- Category Breakdown Badges -->
                            <div style="display: flex; flex-direction: column; gap: 0.35rem; border-left: 1px solid rgba(255,255,255,0.12); padding-left: 1rem;">
                                <span style="font-size: 0.75rem; font-weight: 600; color: {{ $res->passed_twk ? '#34D399' : '#F87171' }};">
                                    TWK: {{ $res->score_twk }} <small style="opacity: 0.7;">(PG: 65)</small>
                                </span>
                                <span style="font-size: 0.75rem; font-weight: 600; color: {{ $res->passed_tiu ? '#34D399' : '#F87171' }};">
                                    TIU: {{ $res->score_tiu }} <small style="opacity: 0.7;">(PG: 80)</small>
                                </span>
                                <span style="font-size: 0.75rem; font-weight: 600; color: {{ $res->passed_tkp ? '#34D399' : '#F87171' }};">
                                    TKP: {{ $res->score_tkp }} <small style="opacity: 0.7;">(PG: 166)</small>
                                </span>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1.5rem 1.25rem; border-top: 1px solid rgba(255,255,255,0.06); border-bottom: 1px solid rgba(255,255,255,0.06); width: 18%;">
                        <span style="color: #CBD5E1; font-size: 0.88rem; font-weight: 500; display: inline-flex; align-items: center; gap: 6px;">
                            <i class="far fa-calendar-alt" style="color: #38BDF8;"></i> {{ $res->created_at->format('d M Y') }}
                        </span>
                        <small style="display: block; color: #CBD5E1; font-size: 0.75rem; margin-top: 4px; opacity: 0.8; padding-left: 20px;">
                            Pukul {{ $res->created_at->format('H:i') }} WIB
                        </small>
                    </td>
                    <td style="padding: 1.5rem 2rem 1.5rem 1.25rem; border-top: 1px solid rgba(255,255,255,0.06); border-bottom: 1px solid rgba(255,255,255,0.06); border-right: 1px solid rgba(255,255,255,0.06); border-radius: 0 16px 16px 0; text-align: right; width: 12%;">
                        <a href="{{ route('user.tryout.hasil', $res->id) }}" class="btn btn-secondary btn-review-riwayat" style="padding: 0.65rem 1.25rem; font-size: 0.85rem; font-weight: 700; border-radius: 50px; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 0.5rem; border: 1.5px solid #CBD5E1 !important;">
                            <i class="fas fa-search" style="font-size: 0.8rem;"></i> Review
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination Section -->
    <div class="custom-pagination-wrapper" style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.08);">
        {{ $results->links() }}
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
    .btn-review-riwayat {
        background: white !important;
        color: #1E293B !important;
    }
    .btn-review-riwayat:hover {
        background: #F8FAFC !important;
        color: #0F172A !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(255, 255, 255, 0.1);
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
