@extends('layouts.auth')

@section('content')

<h2>Buat Akun Baru</h2>
<p>Bergabunglah bersama kami untuk persiapan ujian yang lebih baik.</p>

@if ($errors->any())
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <div>
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    </div>
@endif

<form action="{{ url('auth/register') }}" method="POST">
    @csrf

    <div class="form-group">
        <label class="form-label">Nama Lengkap</label>
        <div style="position: relative;">
            <i class="fas fa-user" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
            <input type="text" name="name" class="form-control" required placeholder="Masukkan nama lengkap" value="{{ old('name') }}" style="padding-left: 2.75rem;">
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Email</label>
        <div style="position: relative;">
            <i class="fas fa-envelope" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
            <input type="email" name="email" class="form-control" required placeholder="nama@email.com" value="{{ old('email') }}" style="padding-left: 2.75rem;">
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Password</label>
        <div style="position: relative;">
            <i class="fas fa-lock" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
            <input type="password" name="password" class="form-control" required placeholder="Buat password" style="padding-left: 2.75rem;">
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Konfirmasi Password</label>
        <div style="position: relative;">
            <i class="fas fa-check-circle" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
            <input type="password" name="password_confirmation" class="form-control" required placeholder="Ulangi password" style="padding-left: 2.75rem;">
        </div>
    </div>

    <button type="submit" class="btn">
        Daftar Sekarang <i class="fas fa-user-plus" style="margin-left: 0.5rem;"></i>
    </button>
</form>

<div class="footer">
    Sudah punya akun? <a href="{{ url('auth/login') }}">Masuk ke Dashboard</a>
</div>

@endsection