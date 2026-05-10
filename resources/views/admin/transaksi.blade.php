@extends('layouts.app')
@section('content')

<div class="page-header">
    <h1 class="page-title">Data Transaksi Pembayaran</h1>
    <p class="subtitle">Riwayat pembelian paket oleh pengguna.</p>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>User</th>
                    <th>Paket</th>
                    <th>Nominal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $trx)
                <tr>
                    <td><span style="color:var(--text-muted);">{{ $trx->created_at->format('d M Y, H:i') }}</span></td>
                    <td style="font-weight: 600;">{{ $trx->user->name ?? '-' }}</td>
                    <td>{{ $trx->package->name ?? '-' }}</td>
                    <td style="font-weight: 600;">Rp {{ number_format($trx->amount, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge {{ $trx->status == 'success' ? 'badge-success' : 'badge-warning' }}">
                            {{ ucfirst($trx->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
