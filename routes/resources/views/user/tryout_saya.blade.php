@extends('layouts.app')
@section('content')

<div class="page-header" style="padding: 3rem 2rem; border-radius: 24px; background: white; border: 1px solid #E2E8F0; box-shadow: var(--shadow-sm); margin-bottom: 2.5rem; position: relative; overflow: hidden;">
    <!-- Abstract background decorations for premium feel -->
    <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; border-radius: 50%; background: rgba(56, 189, 248, 0.08); filter: blur(30px); pointer-events: none;"></div>
    <div style="position: absolute; bottom: -50px; left: -50px; width: 150px; height: 150px; border-radius: 50%; background: rgba(36, 58, 94, 0.08); filter: blur(30px); pointer-events: none;"></div>

    <h1 class="page-title" style="font-size: 2.6rem; font-weight: 800; color: #1E293B; letter-spacing: -0.5px; margin-bottom: 0.5rem;">Tryout Saya</h1>
    <p class="subtitle" style="font-size: 1.1rem; max-width: 600px; color: #64748B; margin: 0; line-height: 1.6;">Daftar simulasi tryout aktif yang Anda miliki dari paket pembelian resmi Anda.</p>
</div>

@if(session('success'))
    <div class="badge badge-success" style="padding: 1rem 1.5rem; border-radius: 14px; margin-bottom: 1.5rem; display: block; font-size: 0.95rem; background: #DEF7EC !important; color: #03543F !important; font-weight: 700; border: 1px solid rgba(16, 185, 129, 0.2) !important;">
        <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i> {{ session('success') }}
    </div>
@endif

@if($attempts->isEmpty())
<div class="card" style="text-align:center; padding: 4.5rem 2rem; border-radius: 24px !important; background: rgba(36, 58, 94, 0.95) !important; border: 1px solid rgba(255, 255, 255, 0.12) !important;">
    <div style="width: 100px; height: 100px; border-radius: 50%; background: rgba(255, 255, 255, 0.04); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.75rem auto; font-size: 3rem; color: #94A3B8; border: 1px solid rgba(255, 255, 255, 0.08);">
        <i class="fas fa-folder-open" style="color: #38BDF8;"></i>
    </div>
    <h2 style="color: white; font-weight: 800; margin: 0 0 0.5rem 0; font-size: 1.6rem;">Belum Ada Tryout Aktif</h2>
    <p style="color: #CBD5E1; max-width: 480px; margin: 0 auto 2.5rem auto; font-size: 0.95rem; line-height: 1.6;">Anda belum memiliki paket tryout yang dibeli atau aktif. Silakan kunjungi galeri paket kami untuk mulai berlatih dengan ribuan soal terstandarisasi.</p>
    <a href="{{ route('user.paket') }}" class="btn btn-primary" style="padding: 0.9rem 2.25rem; font-size: 1rem; border-radius: 50px; font-weight: 700; justify-content: center; margin: 0 auto; display: inline-flex; gap: 0.5rem;">
        <i class="fas fa-shopping-cart"></i> Beli Paket Sekarang
    </a>
</div>
@else
    @foreach($groupedAttempts as $packageId => $packageAttempts)
        @php
            $package = $packageAttempts->first()->tryout->package;
            $packageName = $package->name ?? 'Lainnya';
            $packageDesc = $package->description ?? 'Daftar tryout dari paket ini.';
        @endphp
        
        <!-- Package Section Header -->
        <div class="package-section-header" style="margin-top: 2.5rem; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 1px solid rgba(0, 0, 0, 0.08); display: flex; flex-direction: column; gap: 0.25rem;">
            <h2 style="font-size: 1.45rem; font-weight: 800; color: #1E293B; margin: 0;">
                <i class="fas fa-box-open" style="color: #243A5E; margin-right: 0.5rem; font-size: 1.25rem;"></i> {{ $packageName }}
            </h2>
            <p style="font-size: 0.9rem; color: #64748B; margin: 0;">{{ $packageDesc }}</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
            @foreach($packageAttempts as $attempt)
            @php
                $t = $attempt->tryout;
                $titleLower = strtolower($t->title);
                $iconClass = 'fa-graduation-cap';
                $iconColor = '#38BDF8';
                $iconBg = 'rgba(56, 189, 248, 0.08)';
                $iconBorder = 'rgba(56, 189, 248, 0.15)';
                $sisa = $attempt->max_attempt - $attempt->attempt_count;

                // Calculate expiry
                $expiryDate = $attempt->created_at->addDays(7);
                $daysRemaining = (int) ceil(now()->diffInDays($expiryDate, false));
                $isExpired = $attempt->status === 'expired' || $daysRemaining <= 0;

                if (str_contains($titleLower, 'cpns') || str_contains($titleLower, 'skd') || str_contains($titleLower, 'twk') || str_contains($titleLower, 'tiu') || str_contains($titleLower, 'tkp')) {
                    $iconClass = 'fa-building-columns';
                    $iconColor = '#10B981'; // emerald
                    $iconBg = 'rgba(16, 185, 129, 0.08)';
                    $iconBorder = 'rgba(16, 185, 129, 0.15)';
                } elseif (str_contains($titleLower, 'tps') || str_contains($titleLower, 'tpa') || str_contains($titleLower, 'potensi')) {
                    $iconClass = 'fa-brain';
                    $iconColor = '#A855F7'; // purple
                    $iconBg = 'rgba(168, 85, 247, 0.08)';
                    $iconBorder = 'rgba(168, 85, 247, 0.15)';
                } elseif (str_contains($titleLower, 'utbk') || str_contains($titleLower, 'snbt')) {
                    $iconClass = 'fa-university';
                    $iconColor = '#F59E0B'; // amber
                    $iconBg = 'rgba(245, 158, 11, 0.08)';
                    $iconBorder = 'rgba(245, 158, 11, 0.15)';
                }
            @endphp
            <div class="card tryout-card-premium {{ $isExpired ? 'expired-state' : '' }}" style="position: relative; overflow: hidden; border: 1.5px solid rgba(255,255,255,0.06) !important; border-top: 5px solid {{ $isExpired ? '#64748B' : $iconColor }} !important; display: flex; flex-direction: column; padding: 2.25rem 1.75rem; border-radius: 24px !important; background: {{ $isExpired ? 'rgba(30, 41, 59, 0.6)' : 'rgba(36, 58, 94, 0.95)' }} !important; opacity: {{ $isExpired ? 0.75 : 1 }}; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: var(--shadow-sm);">
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                    <div style="width: 48px; height: 48px; border-radius: 14px; background: {{ $isExpired ? 'rgba(148, 163, 184, 0.08)' : $iconBg }} !important; color: {{ $isExpired ? '#94A3B8' : $iconColor }} !important; border: 1px solid {{ $isExpired ? 'rgba(148, 163, 184, 0.15)' : $iconBorder }} !important; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <i class="fas {{ $isExpired ? 'fa-lock' : $iconClass }}"></i>
                    </div>
                    
                    @if($isExpired)
                        <span class="badge" style="background: rgba(239, 68, 68, 0.15) !important; color: #F87171 !important; font-size: 0.72rem; font-weight: 700; border-radius: 50px; padding: 0.35rem 0.8rem; border: 1px solid rgba(239, 68, 68, 0.2) !important; display: inline-flex; align-items: center; gap: 0.25rem;">
                            <span style="width: 6px; height: 6px; border-radius: 50%; background: #EF4444; display: inline-block;"></span>
                            Kadaluwarsa
                        </span>
                    @elseif($daysRemaining <= 3)
                        <span class="badge" style="background: rgba(245, 158, 11, 0.15) !important; color: #FBBF24 !important; font-size: 0.72rem; font-weight: 700; border-radius: 50px; padding: 0.35rem 0.8rem; border: 1px solid rgba(245, 158, 11, 0.2) !important; display: inline-flex; align-items: center; gap: 0.25rem; animation: pulse-yellow-glow 2s infinite;">
                            <span style="width: 6px; height: 6px; border-radius: 50%; background: #F59E0B; display: inline-block;"></span>
                            Sisa: {{ $daysRemaining }} Hari
                        </span>
                    @else
                        <span class="badge" style="background: rgba(16, 185, 129, 0.15) !important; color: #34D399 !important; font-size: 0.72rem; font-weight: 700; border-radius: 50px; padding: 0.35rem 0.8rem; border: 1px solid rgba(16, 185, 129, 0.2) !important; display: inline-flex; align-items: center; gap: 0.25rem;">
                            <span style="width: 6px; height: 6px; border-radius: 50%; background: #34D399; display: inline-block;"></span>
                            Sisa: {{ $daysRemaining }} Hari
                        </span>
                    @endif
                </div>

                <h3 style="margin: 0 0 0.75rem 0; color: white; font-weight: 800; font-size: 1.25rem; line-height: 1.4;">{{ $t->title }}</h3>
                
                <div style="display: flex; gap: 0.75rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
                    <span class="badge" style="background: rgba(255, 255, 255, 0.05) !important; color: white !important; font-size: 0.75rem; font-weight: 600; border-radius: 50px; padding: 0.3rem 0.75rem; border: 1px solid rgba(255,255,255,0.08) !important;">
                        <i class="far fa-clock" style="margin-right: 4px; color: #38BDF8;"></i> {{ $t->duration }} Menit
                    </span>
                    <span class="badge" style="background: rgba(255, 255, 255, 0.05) !important; color: white !important; font-size: 0.75rem; font-weight: 600; border-radius: 50px; padding: 0.3rem 0.75rem; border: 1px solid rgba(255,255,255,0.08) !important;">
                        <i class="fas fa-redo" style="margin-right: 4px; color: #F59E0B;"></i> Sisa: {{ $sisa }} / {{ $attempt->max_attempt }}x
                    </span>
                </div>
                
                <p style="color: #CBD5E1; font-size: 0.88rem; flex-grow: 1; margin: 0 0 1.75rem 0; line-height: 1.55;">
                    @if($isExpired)
                        Akses pengerjaan tryout ini telah kadaluwarsa karena melebihi batas waktu 7 hari setelah paket berhasil diaktifkan.
                    @else
                        Pastikan Anda memiliki waktu luang dan koneksi internet yang stabil sebelum menekan tombol mulai ujian. Nilai Anda akan dihitung secara otomatis.
                    @endif
                </p>
                
                @if($isExpired)
                    <button class="btn btn-secondary" style="width: 100%; padding: 0.95rem; font-size: 1rem; font-weight: 700; border-radius: 50px; justify-content: center; gap: 0.5rem; background: rgba(255,255,255,0.08) !important; border: 1px solid rgba(255,255,255,0.05) !important; color: rgba(255,255,255,0.4) !important; cursor: not-allowed; display: flex; align-items: center;" disabled>
                        <i class="fas fa-lock" style="font-size: 0.85rem;"></i> Akses Kadaluwarsa
                    </button>
                @else
                    <a href="{{ route('user.tryout.kerjakan', $t->id) }}" class="btn btn-primary btn-start-tryout" style="width: 100%; padding: 0.95rem; font-size: 1rem; font-weight: 700; border-radius: 50px; justify-content: center; gap: 0.5rem; transition: all 0.3s ease;">
                        <i class="fas fa-play" style="font-size: 0.85rem;"></i> Mulai Kerjakan
                    </a>
                @endif
            </div>
            @endforeach
        </div>
    @endforeach
@endif

<style>
    .tryout-card-premium {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    .tryout-card-premium:not(.expired-state):hover {
        transform: translateY(-8px);
        border-color: rgba(56, 189, 248, 0.35) !important;
        box-shadow: 0 20px 40px rgba(36, 58, 94, 0.25) !important;
    }
    .tryout-card-premium.expired-state {
        box-shadow: none !important;
    }
    .btn-start-tryout {
        box-shadow: 0 4px 12px rgba(56, 189, 248, 0.15) !important;
    }
    .btn-start-tryout:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(56, 189, 248, 0.3) !important;
    }
    
    @keyframes pulse-yellow-glow {
        0% {
            box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(245, 158, 11, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(245, 158, 11, 0);
        }
    }
</style>

@endsection
