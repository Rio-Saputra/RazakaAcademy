@extends('layouts.auth')

@section('content')

<h2>Selamat Datang Kembali</h2>
<p>Silakan login untuk mengakses dashboard Anda.</p>

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

<form action="{{ url('auth/login') }}" method="POST">
    @csrf

    <div class="form-group">
        <label class="form-label">Email</label>
        <div style="position: relative;">
            <i class="fas fa-envelope" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
            <input type="email" name="email" class="form-control" required placeholder="nama@email.com" style="padding-left: 2.75rem;">
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Password</label>
        <div style="position: relative;">
            <i class="fas fa-lock" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
            <input type="password" name="password" class="form-control" required placeholder="••••••••" style="padding-left: 2.75rem;">
        </div>
        <div style="text-align: right; margin-top: 0.5rem;">
            <a href="{{ route('password.request') }}" style="font-size: 0.875rem; color: var(--primary); text-decoration: none;">Lupa Password?</a>
        </div>
    </div>

    <button type="submit" class="btn">
        Masuk ke Akun <i class="fas fa-sign-in-alt" style="margin-left: 0.5rem;"></i>
    </button>
</form>

<div class="footer">
    Belum punya akun? <a href="{{ url('auth/register') }}">Daftar Sekarang</a>
</div>

@endsection