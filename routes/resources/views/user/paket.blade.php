@extends('layouts.app')
@section('content')

<div class="page-header" style="text-align: center; padding: 3.5rem 2rem; border-radius: 24px; background: white; border: 1px solid #E2E8F0; box-shadow: var(--shadow-sm); margin-bottom: 2.5rem; position: relative; overflow: hidden;">
    <!-- Abstract background decorations for premium feel -->
    <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; border-radius: 50%; background: rgba(56, 189, 248, 0.08); filter: blur(30px); pointer-events: none;"></div>
    <div style="position: absolute; bottom: -50px; left: -50px; width: 150px; height: 150px; border-radius: 50%; background: rgba(36, 58, 94, 0.08); filter: blur(30px); pointer-events: none;"></div>

    <h1 class="page-title" style="font-size: 2.6rem; font-weight: 800; color: #1E293B; letter-spacing: -0.5px; margin-bottom: 0.75rem;">Pilih Paket Tryout</h1>
    <p class="subtitle" style="font-size: 1.1rem; max-width: 650px; margin: 0 auto; color: #64748B; line-height: 1.6;">Investasikan masa depanmu dengan paket simulasi tryout berkualitas tinggi. Dapatkan kemudahan pembayaran otomatis, grafik analisis kelulusan, dan review soal yang mendalam.</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 2rem; max-width: 1200px; margin: 0 auto 3rem auto; padding: 0 1rem;">
    @foreach($packages as $p)
        @php
            $packageName = strtolower($p->name);
            $iconClass = 'fa-graduation-cap'; // Default
            $iconColor = '#38BDF8'; // cyan
            $iconBg = 'rgba(56, 189, 248, 0.08)';
            $iconBorder = 'rgba(56, 189, 248, 0.15)';
            $isPopular = false;

            if (str_contains($packageName, 'cpns') || str_contains($packageName, 'pns') || str_contains($packageName, 'asn')) {
                $iconClass = 'fa-building-columns';
                $iconColor = '#10B981'; // emerald
                $iconBg = 'rgba(16, 185, 129, 0.08)';
                $iconBorder = 'rgba(16, 185, 129, 0.15)';
                $isPopular = true;
            } elseif (str_contains($packageName, 'tps') || str_contains($packageName, 'tpa') || str_contains($packageName, 'brain') || str_contains($packageName, 'potensi')) {
                $iconClass = 'fa-brain';
                $iconColor = '#A855F7'; // purple
                $iconBg = 'rgba(168, 85, 247, 0.08)';
                $iconBorder = 'rgba(168, 85, 247, 0.15)';
            } elseif (str_contains($packageName, 'utbk') || str_contains($packageName, 'snbt') || str_contains($packageName, 'sbmptn')) {
                $iconClass = 'fa-university';
                $iconColor = '#F59E0B'; // amber
                $iconBg = 'rgba(245, 158, 11, 0.08)';
                $iconBorder = 'rgba(245, 158, 11, 0.15)';
            } elseif (str_contains($packageName, 'premium') || str_contains($packageName, 'gold') || str_contains($packageName, 'vip') || str_contains($packageName, 'eksklusif')) {
                $iconClass = 'fa-crown';
                $iconColor = '#EAB308'; // gold
                $iconBg = 'rgba(234, 179, 8, 0.08)';
                $iconBorder = 'rgba(234, 179, 8, 0.15)';
                $isPopular = true;
            }
        @endphp
        
        <div class="card pricing-card-premium" style="display: flex; flex-direction: column; justify-content: space-between; padding: 2.75rem 2rem; position: relative; overflow: hidden; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border: 2px solid {{ $isPopular ? '#38BDF8' : 'rgba(255,255,255,0.06)' }} !important; border-radius: 24px !important; background: rgba(36, 58, 94, 0.95) !important; box-shadow: {{ $isPopular ? '0 20px 40px rgba(56, 189, 248, 0.12)' : 'var(--shadow-sm)' }} !important;">
            
            <!-- Highlight Top Border -->
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 6px; background: {{ $isPopular ? 'linear-gradient(90deg, #38BDF8 0%, #0EA5E9 100%)' : 'var(--primary-gradient)' }};"></div>
            
            <!-- Popular Ribbon Badge -->
            @if($isPopular)
                <div style="position: absolute; top: 15px; right: 15px; background: #38BDF8; color: #1E293B; font-size: 0.72rem; font-weight: 800; padding: 0.35rem 0.85rem; border-radius: 50px; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 4px 10px rgba(56, 189, 248, 0.25);">
                    Paling Populer
                </div>
            @endif

            <div>
                <!-- Centered Icon Box -->
                <div style="display: flex; justify-content: center; margin-bottom: 1.5rem;">
                    <div style="width: 64px; height: 64px; border-radius: 18px; background: {{ $iconBg }} !important; color: {{ $iconColor }} !important; border: 1.5px solid {{ $iconBorder }} !important; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 8px 16px rgba(0,0,0,0.1);">
                        <i class="fas {{ $iconClass }}"></i>
                    </div>
                </div>

                <h2 style="color: white; margin: 0 0 0.5rem 0; font-size: 1.45rem; font-weight: 800; text-align: center; text-transform: uppercase; letter-spacing: 0.5px;">{{ $p->name }}</h2>
                
                <div style="display: flex; justify-content: center; align-items: baseline; margin: 1.5rem 0; gap: 0.25rem;">
                    <span style="font-size: 1.25rem; font-weight: 600; color: #CBD5E1;">Rp</span>
                    <h1 style="color: #38BDF8 !important; font-size: 2.85rem; font-weight: 900; margin: 0; line-height: 1; letter-spacing: -1px;">{{ number_format($p->price, 0, ',', '.') }}</h1>
                </div>

                <p style="color: #CBD5E1; font-size: 0.85rem; text-align: center; margin: -0.75rem 0 2rem 0; font-weight: 500;">
                    <i class="far fa-clock" style="margin-right: 4px;"></i> Akses Selamanya (Satu Kali Bayar)
                </p>
                
                <!-- Features List Grid -->
                <div style="margin-bottom: 2.5rem; background: rgba(255, 255, 255, 0.04) !important; padding: 1.75rem 1.5rem; border-radius: 16px; border: 1px solid rgba(255, 255, 255, 0.08) !important;">
                    <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.95rem;">
                        <li style="display: flex; align-items: flex-start; gap: 0.75rem;">
                            <span style="color: #38BDF8; background: rgba(56, 189, 248, 0.12); width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; flex-shrink: 0; margin-top: 2px;">
                                <i class="fas fa-check"></i>
                            </span>
                            <span style="color: #CBD5E1; font-size: 0.9rem; line-height: 1.4; font-weight: 500;">{{ $p->description ?? 'Paket simulasi tryout lengkap.' }}</span>
                        </li>
                        <li style="display: flex; align-items: flex-start; gap: 0.75rem;">
                            <span style="color: #38BDF8; background: rgba(56, 189, 248, 0.12); width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; flex-shrink: 0; margin-top: 2px;">
                                <i class="fas fa-check"></i>
                            </span>
                            <span style="color: #CBD5E1; font-size: 0.9rem; line-height: 1.4; font-weight: 500;">Akses penuh ke semua simulasi kategori ujian</span>
                        </li>
                        <li style="display: flex; align-items: flex-start; gap: 0.75rem;">
                            <span style="color: #38BDF8; background: rgba(56, 189, 248, 0.12); width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; flex-shrink: 0; margin-top: 2px;">
                                <i class="fas fa-check"></i>
                            </span>
                            <span style="color: #CBD5E1; font-size: 0.9rem; line-height: 1.4; font-weight: 500;">Pembahasan super detail & analisis nilai PG</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div>
                <form class="midtrans-buy-form" data-package-id="{{ $p->id }}" data-url="{{ route('user.paket.beli', $p->id) }}">
                    @csrf
                    <button type="button" class="btn btn-primary btn-buy-package" style="width: 100%; padding: 1.1rem; font-size: 1.05rem; font-weight: 700; border-radius: 50px; justify-content: center; gap: 0.75rem; background: {{ $isPopular ? 'var(--primary-gradient)' : 'transparent' }} !important; border: 2px solid {{ $isPopular ? 'transparent' : 'white' }} !important; color: white !important; transition: all 0.3s ease;">
                        <span>Beli Paket Ini</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    @endforeach
</div>

<style>
    .pricing-card-premium {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    .pricing-card-premium:hover {
        transform: translateY(-8px) scale(1.02);
        border-color: #38BDF8 !important;
        box-shadow: 0 25px 50px rgba(36, 58, 94, 0.3) !important;
    }
    .pricing-card-premium:not(:hover) .btn-buy-package {
        opacity: 0.95;
    }
    .pricing-card-premium .btn-buy-package:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(56, 189, 248, 0.25) !important;
        background: white !important;
        color: #1E2F4D !important;
        border-color: white !important;
    }
</style>

@push('scripts')
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
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                
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

@endsection
