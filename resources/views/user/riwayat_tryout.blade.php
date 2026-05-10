@extends('layouts.app')
@section('content')

<div class="page-header">
    <h1 class="page-title">Riwayat Tryout</h1>
    <p class="subtitle">Daftar lengkap tryout yang sudah Anda kerjakan beserta nilainya.</p>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Tryout</th>
                    <th>Nilai</th>
                    <th>Tanggal Mengerjakan</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $res)
                <tr>
                    <td>
                        <span style="font-weight: 600; color: var(--text);">{{ $res->tryout->title ?? 'Tryout Tidak Tersedia' }}</span>
                    </td>
                    <td>
                        <span class="badge {{ $res->score >= 70 ? 'badge-success' : 'badge-warning' }}" style="font-size: 1rem;">
                            {{ number_format($res->score, 1) }}
                        </span>
                    </td>
                    <td>
                        <span style="color:var(--text-muted);">{{ $res->created_at->format('d M Y, H:i') }}</span>
                    </td>
                    <td style="text-align: right;">
                        <a href="{{ route('user.tryout.hasil', $res->tryout_id) }}" class="btn btn-secondary" style="padding: 0.5rem 1rem;">
                            <i class="fas fa-search"></i> Review
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center; padding: 3rem 1rem; color: var(--text-muted);">
                        <i class="fas fa-tasks" style="font-size: 3rem; color: #CBD5E1; margin-bottom: 1rem;"></i>
                        <p>Belum ada riwayat tryout. Ayo mulai kerjakan!</p>
                        <a href="{{ route('user.tryout.index') }}" class="btn btn-primary" style="margin-top: 1rem;">Lihat Tryout Saya</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
