@extends('layouts.app')
@section('content')

<div class="page-header" style="text-align: center; padding: 3rem 2rem;">
    <h1 class="page-title" style="font-size: 2.5rem;">Pilih Paket Tryout</h1>
    <p class="subtitle" style="font-size: 1.1rem; max-width: 600px; margin: 0 auto;">Pilih paket yang sesuai dengan kebutuhan belajarmu dan mulailah berlatih dengan soal-soal berkualitas dari RAZAKA ACADEMY.</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2rem; max-width: 1200px; margin: 0 auto;">
    @foreach($packages as $p)
    <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; text-align: center; padding: 2.5rem 2rem; position: relative; overflow: hidden; transition: all 0.3s ease; border: 2px solid transparent;">
        <!-- Hover Border Effect -->
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 6px; background: var(--primary-gradient);"></div>
        
        <div>
            <h2 style="color: var(--text); margin-top:0; font-size: 1.5rem; font-weight: 700;">{{ $p->name }}</h2>
            <div style="display: flex; justify-content: center; align-items: baseline; margin: 1.5rem 0;">
                <span style="font-size: 1.5rem; font-weight: 600; color: var(--text-muted);">Rp</span>
                <h1 style="color: var(--primary); font-size: 3rem; margin: 0; line-height: 1;">{{ number_format($p->price, 0, ',', '.') }}</h1>
            </div>
            
            <div style="margin-bottom: 2rem; background: #F8FAFC; padding: 1.5rem; border-radius: 12px; text-align: left;">
                <p style="color: var(--text-muted); margin: 0;">
                    <i class="fas fa-check-circle" style="color: #10B981; margin-right: 0.5rem;"></i>
                    {{ $p->description }}
                </p>
                <p style="color: var(--text-muted); margin: 0.75rem 0 0 0;">
                    <i class="fas fa-check-circle" style="color: #10B981; margin-right: 0.5rem;"></i>
                    Akses penuh ke semua soal
                </p>
                <p style="color: var(--text-muted); margin: 0.75rem 0 0 0;">
                    <i class="fas fa-check-circle" style="color: #10B981; margin-right: 0.5rem;"></i>
                    Pembahasan detail & analitik
                </p>
            </div>
        </div>
        <div>
            <form class="midtrans-buy-form" data-package-id="{{ $p->id }}" data-url="{{ route('user.paket.beli', $p->id) }}">
                @csrf
                <button type="button" class="btn btn-primary btn-buy-package" style="width: 100%; padding: 1rem; font-size: 1.1rem; border-radius: 50px;">
                    Beli Paket Ini <i class="fas fa-arrow-right" style="margin-left: 0.5rem;"></i>
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>

<style>
    .card:hover {
        border-color: rgba(36, 58, 94, 0.2);
        transform: translateY(-10px);
        box-shadow: var(--shadow-lg);
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
