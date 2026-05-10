@extends('layouts.app')
@section('content')

<div class="page-header">
    <h1 class="page-title">Riwayat Transaksi</h1>
    <p class="subtitle">Daftar lengkap riwayat pembelian paket tryout Anda.</p>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Paket</th>
                    <th>Nominal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                <tr>
                    <td><span style="color:var(--text-muted);">{{ $trx->created_at->format('d M Y, H:i') }}</span></td>
                    <td style="font-weight: 600; color: var(--text);">{{ $trx->package->name ?? '-' }}</td>
                    <td style="font-weight: 600; color: #10B981;">Rp {{ number_format($trx->amount, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge {{ $trx->status == 'success' ? 'badge-success' : 'badge-warning' }}">
                            {{ ucfirst($trx->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center; padding: 3rem 1rem; color: var(--text-muted);">
                        <i class="fas fa-receipt" style="font-size: 3rem; color: #CBD5E1; margin-bottom: 1rem;"></i>
                        <p>Belum ada riwayat transaksi.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
