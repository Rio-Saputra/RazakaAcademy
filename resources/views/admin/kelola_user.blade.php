@extends('layouts.app')
@section('content')

<div class="page-header">
    <h1 class="page-title">Kelola User</h1>
    <p class="subtitle">Daftar pengguna terdaftar di sistem RAZAKA ACADEMY.</p>
</div>

@if(session('success'))
    <div class="badge badge-success" style="padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; display: block; font-size: 1rem;">
        <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="badge badge-danger" style="padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; display: block; font-size: 1rem; background: #FEF2F2; color: #991B1B; border: 1px solid #F87171;">
        <i class="fas fa-exclamation-circle" style="margin-right: 0.5rem;"></i> {{ session('error') }}
    </div>
@endif

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Status / Role</th>
                    <th>Tgl Daftar</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                <tr>
                    <td>
                        <div style="display:flex; align-items:center; gap:0.75rem;">
                            <div style="width:36px; height:36px; border-radius:50%; background:var(--primary-gradient); display:flex; align-items:center; justify-content:center; color: white;">
                                <i class="fas fa-user"></i>
                            </div>
                            <span style="font-weight:600;">{{ $u->name }}</span>
                        </div>
                    </td>
                    <td>{{ $u->email }}</td>
                    <td>
                        <span class="badge {{ $u->role == 'admin' ? 'badge-warning' : 'badge-success' }}">
                            {{ ucfirst($u->role) }}
                        </span>
                    </td>
                    <td><span style="color:var(--text-muted);">{{ $u->created_at->format('d M Y') }}</span></td>
                    <td style="text-align: right;">
                        @if($u->id !== auth()->id())
                        <form action="{{ route('admin.user.destroy', $u->id) }}" method="POST" style="display:inline;" data-confirm="Yakin ingin menghapus user ini dari database?" data-type="confirm" data-title="Konfirmasi Hapus">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 0.5rem 1rem;">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
