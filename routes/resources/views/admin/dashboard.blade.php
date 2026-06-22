@extends('layouts.app')

@section('content')

<div class="page-header" style="padding: 2.5rem 2rem; border-radius: 24px; background: white; border: 1px solid #E2E8F0; box-shadow: var(--shadow-sm); margin-bottom: 2.5rem; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; border-radius: 50%; background: rgba(56, 189, 248, 0.08); filter: blur(30px); pointer-events: none;"></div>
    <div style="position: absolute; bottom: -50px; left: -50px; width: 150px; height: 150px; border-radius: 50%; background: rgba(36, 58, 94, 0.08); filter: blur(30px); pointer-events: none;"></div>

    <h1 class="page-title" style="font-size: 2.4rem; font-weight: 800; color: #1E293B; letter-spacing: -0.5px; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.75rem;">
        Dashboard Owner 👋
    </h1>
    <p class="subtitle" style="font-size: 1.05rem; max-width: 600px; color: #64748B; margin: 0; line-height: 1.6;">Selamat datang kembali! Berikut ringkasan operasional dan analisis finansial dari Razaka Academy.</p>
</div>

<!-- Metrics Cards Grid -->
<div class="admin-stats-grid">
    
    <!-- Card 1: Monthly Revenue -->
    <div class="admin-stat-card bg-gradient-emerald">
        <div class="admin-stat-icon">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="admin-stat-body">
            <span class="admin-stat-label">Pendapatan Bulan Ini</span>
            <h2 class="admin-stat-value">Rp {{ number_format($monthly_revenue, 0, ',', '.') }}</h2>
            <div class="admin-stat-footer">
                <span class="footer-primary"><i class="far fa-calendar-alt"></i> Periode: 1 {{ now()->translatedFormat('M') }} - {{ now()->endOfMonth()->translatedFormat('d M') }}</span>
                <span class="footer-secondary"><i class="fas fa-sync-alt"></i> Reset otomatis awal bulan</span>
            </div>
        </div>
    </div>

    <!-- Card 2: Yearly Revenue -->
    <div class="admin-stat-card bg-gradient-blue">
        <div class="admin-stat-icon">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="admin-stat-body">
            <span class="admin-stat-label">Pendapatan Tahun Ini</span>
            <h2 class="admin-stat-value">Rp {{ number_format($yearly_revenue, 0, ',', '.') }}</h2>
            <div class="admin-stat-footer">
                <span class="footer-primary"><i class="far fa-clock"></i> Tahun Anggaran {{ now()->year }}</span>
                <span class="footer-secondary"><i class="fas fa-globe"></i> Terakumulasi secara real-time</span>
            </div>
        </div>
    </div>

    <!-- Card 3: Total Transactions -->
    <div class="admin-stat-card bg-gradient-purple">
        <div class="admin-stat-icon">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <div class="admin-stat-body">
            <span class="admin-stat-label">Total Transaksi Sukses</span>
            <h2 class="admin-stat-value">{{ number_format($total_transaksi, 0, ',', '.') }}</h2>
            <div class="admin-stat-footer">
                <span class="footer-primary"><i class="fas fa-receipt"></i> Seluruh waktu</span>
                <span class="footer-secondary"><i class="fas fa-check-circle"></i> Status pembayaran sukses</span>
            </div>
        </div>
    </div>

    <!-- Card 4: Registered Users & Conversion -->
    <div class="admin-stat-card bg-gradient-orange">
        <div class="admin-stat-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="admin-stat-body">
            <span class="admin-stat-label">Siswa Terdaftar / Pembeli</span>
            <h2 class="admin-stat-value">{{ $total_user }} / {{ $total_buyers }}</h2>
            <div class="admin-stat-footer">
                <span class="footer-primary"><i class="fas fa-percentage"></i> Rasio Konversi: {{ $total_user > 0 ? number_format(($total_buyers / $total_user) * 100, 1) : 0 }}%</span>
                <span class="footer-secondary"><i class="fas fa-user-plus"></i> Akun siswa aktif</span>
            </div>
        </div>
    </div>

    <!-- Card 5: Best Selling Package -->
    <div class="admin-stat-card bg-gradient-rose">
        <div class="admin-stat-icon">
            <i class="fas fa-star"></i>
        </div>
        <div class="admin-stat-body">
            <span class="admin-stat-label">Paket Terlaris (Best-Seller)</span>
            <h2 class="admin-stat-value" style="font-size: 1.45rem; line-height: 1.2; margin: 0.5rem 0;" title="{{ optional($best_seller)->package->name ?? 'Belum Ada' }}">
                {{ optional($best_seller)->package->name ?? 'Belum Ada' }}
            </h2>
            <div class="admin-stat-footer">
                <span class="footer-primary"><i class="fas fa-fire"></i> Terjual sebanyak: <strong>{{ optional($best_seller)->total_sales ?? 0 }}x</strong></span>
                <span class="footer-secondary"><i class="fas fa-award"></i> Pilihan favorit siswa</span>
            </div>
        </div>
    </div>

</div>

<!-- Charts & Tables Row Grid -->
<div class="admin-dashboard-row" style="display: grid; grid-template-columns: 3fr 2fr; gap: 1.5rem; margin-bottom: 2.5rem; align-items: start;">
    
    <!-- Revenue Trend Chart -->
    <div class="card" style="padding: 2rem; border-radius: 20px; background: white; border: 1px solid #E2E8F0; box-shadow: var(--shadow-sm); margin-bottom: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.2rem; font-weight: 800; color: #1E293B; margin: 0;">Grafik Pendapatan Bulanan ({{ now()->year }})</h3>
            <span style="font-size: 0.82rem; font-weight: 600; color: #3B82F6; background: rgba(59, 130, 246, 0.08); padding: 0.35rem 0.75rem; border-radius: 50px; border: 1px solid rgba(59, 130, 246, 0.15);">
                <i class="far fa-chart-bar" style="margin-right: 4px;"></i> Tren Keuangan
            </span>
        </div>
        <div style="position: relative; height: 320px; width: 100%;">
            <canvas id="revenueTrendChart"></canvas>
        </div>
    </div>

    <!-- Top Selling Packages List -->
    <div class="card" style="padding: 2rem; border-radius: 20px; background: white; border: 1px solid #E2E8F0; box-shadow: var(--shadow-sm); margin-bottom: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.2rem; font-weight: 800; color: #1E293B; margin: 0;">Top 5 Paket Ujian</h3>
            <span style="font-size: 0.82rem; font-weight: 600; color: #10B981; background: rgba(16, 185, 129, 0.08); padding: 0.35rem 0.75rem; border-radius: 50px; border: 1px solid rgba(16, 185, 129, 0.15);">
                <i class="fas fa-trophy" style="margin-right: 4px;"></i> Terlaris
            </span>
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @forelse($top_packages as $index => $item)
                @php $p = $item->package; @endphp
                <div style="display: flex; align-items: center; gap: 1rem; padding: 0.9rem 1.1rem; border-radius: 14px; background: #F8FAFC; border: 1px solid #F1F5F9; transition: transform 0.2s ease;" onmouseover="this.style.transform='translateX(4px)'" onmouseout="this.style.transform='translateX(0)'">
                    <div style="width: 34px; height: 34px; border-radius: 50%; background: {{ ['rgba(251, 191, 36, 0.15)', 'rgba(148, 163, 184, 0.15)', 'rgba(180, 83, 9, 0.15)', 'rgba(30, 41, 59, 0.08)', 'rgba(30, 41, 59, 0.08)'][$index] ?? 'rgba(30, 41, 59, 0.08)' }}; color: {{ ['#D97706', '#475569', '#92400E', '#64748B', '#64748B'][$index] ?? '#64748B' }}; font-weight: 800; display: flex; align-items: center; justify-content: center; font-size: 0.95rem;">
                        {{ $index + 1 }}
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-weight: 700; font-size: 0.92rem; color: #1E293B; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $p->name ?? 'Paket Terhapus' }}</div>
                        <div style="font-size: 0.8rem; color: #64748B;">{{ $item->total_sales }}x terjual</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-weight: 800; font-size: 0.92rem; color: #10B981;">Rp {{ number_format($item->revenue, 0, ',', '.') }}</div>
                        <div style="font-size: 0.75rem; color: #94A3B8;">Pendapatan</div>
                    </div>
                </div>
            @empty
                <div style="padding: 3rem 1rem; text-align: center; color: #94A3B8;">
                    <i class="fas fa-box" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; opacity: 0.5;"></i>
                    Belum ada data penjualan paket.
                </div>
            @endforelse
        </div>
    </div>

</div>

<!-- Recent Transactions Table Card -->
<div class="card" style="padding: 2rem; border-radius: 20px; background: white; border: 1px solid #E2E8F0; box-shadow: var(--shadow-sm);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h3 style="font-size: 1.25rem; font-weight: 800; color: #1E293B; margin: 0;">Transaksi Sukses Terbaru</h3>
        <a href="{{ route('admin.transaksi') }}" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.85rem; border-radius: 50px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem;">
            Lihat Kelola Transaksi <i class="fas fa-chevron-right" style="font-size: 0.75rem;"></i>
        </a>
    </div>

    <div class="table-container" style="border: 1px solid #F1F5F9; border-radius: 14px; overflow: hidden; background: #F8FAFC;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #E2E8F0; border-bottom: 1px solid #CBD5E1;">
                    <th style="padding: 1rem 1.25rem; font-weight: 700; color: #1E293B; font-size: 0.88rem;">Nama Pembeli</th>
                    <th style="padding: 1rem 1.25rem; font-weight: 700; color: #1E293B; font-size: 0.88rem;">Paket Ujian</th>
                    <th style="padding: 1rem 1.25rem; font-weight: 700; color: #1E293B; font-size: 0.88rem;">Nominal Harga</th>
                    <th style="padding: 1rem 1.25rem; font-weight: 700; color: #1E293B; font-size: 0.88rem;">Metode Bayar</th>
                    <th style="padding: 1rem 1.25rem; font-weight: 700; color: #1E293B; font-size: 0.88rem;">Waktu Sukses</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent_transactions as $trx)
                <tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.2s;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 1rem 1.25rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: #243A5E; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.85rem;">
                                {{ substr($trx->user->name ?? 'U', 0, 1) }}
                            </div>
                            <div>
                                <span style="font-weight: 700; color: #1E293B; display: block; font-size: 0.9rem;">{{ $trx->user->name ?? 'User Unknown' }}</span>
                                <span style="font-size: 0.75rem; color: #64748B;">{{ $trx->user->email ?? '-' }}</span>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1rem 1.25rem; font-weight: 600; color: #1E293B; font-size: 0.9rem;">
                        {{ $trx->package->name ?? 'Paket Unknown' }}
                    </td>
                    <td style="padding: 1rem 1.25rem; font-weight: 800; color: #10B981; font-size: 0.92rem;">
                        Rp {{ number_format($trx->amount, 0, ',', '.') }}
                    </td>
                    <td style="padding: 1rem 1.25rem;">
                        <span class="badge" style="background: rgba(59, 130, 246, 0.1) !important; color: #2563EB !important; font-weight: 700; font-size: 0.75rem; padding: 0.35rem 0.75rem; border-radius: 50px; border: 1px solid rgba(59, 130, 246, 0.15) !important; text-transform: uppercase;">
                            <i class="fas fa-credit-card" style="margin-right: 4px; font-size: 0.72rem;"></i> {{ $trx->payment_type ?? 'Midtrans' }}
                        </span>
                    </td>
                    <td style="padding: 1rem 1.25rem; color: #64748B; font-size: 0.82rem; font-weight: 500;">
                        {{ $trx->created_at->translatedFormat('d M Y, H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 3rem 1rem; color: #94A3B8;">
                        <i class="fas fa-receipt" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; opacity: 0.5;"></i>
                        Belum ada transaksi sukses tercatat.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    /* Admin Grid Styles */
    .admin-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }
    .admin-stat-card {
        border-radius: 24px;
        padding: 1.8rem;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(36, 58, 94, 0.05);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 160px;
    }
    .admin-stat-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 35px rgba(36, 58, 94, 0.15);
    }
    .admin-stat-icon {
        position: absolute;
        right: -15px;
        bottom: -15px;
        font-size: 6.5rem;
        opacity: 0.12;
        transform: rotate(-15deg);
        pointer-events: none;
    }
    .admin-stat-body {
        display: flex;
        flex-direction: column;
        height: 100%;
        justify-content: space-between;
    }
    .admin-stat-label {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.9;
        margin-bottom: 0.5rem;
    }
    .admin-stat-value {
        font-size: 1.95rem;
        font-weight: 800;
        margin: 0;
        letter-spacing: -0.5px;
        word-break: break-all;
    }
    .admin-stat-footer {
        margin-top: auto;
        padding-top: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
        border-top: 1px solid rgba(255, 255, 255, 0.15);
    }
    .admin-stat-footer span {
        font-size: 0.72rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .admin-stat-footer span.footer-primary {
        color: white;
    }
    .admin-stat-footer span.footer-secondary {
        opacity: 0.75;
    }

    /* Gradients */
    .bg-gradient-emerald {
        background: linear-gradient(135deg, #059669, #10B981) !important;
    }
    .bg-gradient-blue {
        background: linear-gradient(135deg, #1D4ED8, #3B82F6) !important;
    }
    .bg-gradient-purple {
        background: linear-gradient(135deg, #6D28D9, #8B5CF6) !important;
    }
    .bg-gradient-orange {
        background: linear-gradient(135deg, #EA580C, #F97316) !important;
    }
    .bg-gradient-rose {
        background: linear-gradient(135deg, #E11D48, #F43F5E) !important;
    }

    @media (max-width: 991px) {
        .admin-dashboard-row {
            grid-template-columns: 1fr !important;
        }
    }
</style>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('revenueTrendChart').getContext('2d');
        
        // Data dari controller
        const revenueData = @json($monthly_revenue_data);
        const transactionData = @json($monthly_transaction_data);
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        // Buat gradient background untuk area grafik
        const primaryGradient = ctx.createLinearGradient(0, 0, 0, 300);
        primaryGradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
        primaryGradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Pendapatan (Rp)',
                        data: revenueData,
                        borderColor: '#3B82F6',
                        borderWidth: 3,
                        backgroundColor: primaryGradient,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#FFFFFF',
                        pointBorderColor: '#3B82F6',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Transaksi Sukses',
                        data: transactionData,
                        borderColor: '#8B5CF6',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        backgroundColor: 'transparent',
                        fill: false,
                        tension: 0.35,
                        pointBackgroundColor: '#FFFFFF',
                        pointBorderColor: '#8B5CF6',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            padding: 15,
                            font: {
                                family: "'Poppins', sans-serif",
                                weight: 600,
                                size: 11
                            },
                            color: '#475569'
                        }
                    },
                    tooltip: {
                        padding: 12,
                        backgroundColor: 'rgba(30, 41, 59, 0.95)',
                        titleColor: '#FFFFFF',
                        titleFont: {
                            family: "'Poppins', sans-serif",
                            weight: 700,
                            size: 13
                        },
                        bodyFont: {
                            family: "'Poppins', sans-serif",
                            size: 12
                        },
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.datasetIndex === 0) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed.y);
                                } else {
                                    label += context.parsed.y + ' Transaksi';
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: "'Poppins', sans-serif",
                                size: 11
                            },
                            color: '#64748B'
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        grid: {
                            color: '#F1F5F9'
                        },
                        ticks: {
                            font: {
                                family: "'Poppins', sans-serif",
                                size: 11
                            },
                            color: '#64748B',
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000) + 'jt';
                                } else if (value >= 1000) {
                                    return 'Rp ' + (value / 1000) + 'rb';
                                }
                                return 'Rp ' + value;
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false // Mencegah tumpang tindih grid lines
                        },
                        ticks: {
                            font: {
                                family: "'Poppins', sans-serif",
                                size: 11
                            },
                            color: '#64748B',
                            stepSize: 1,
                            callback: function(value) {
                                return value + ' trx';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush