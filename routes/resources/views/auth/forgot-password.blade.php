@extends('layouts.auth')

@section('content')

<h2>Lupa Password</h2>
<p>Masukkan email Anda untuk menerima kode OTP.</p>

@if (session('success'))
    <div class="alert alert-success" style="background: #10B981; color: white; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; text-align: left;">
        <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger" style="background: #EF4444; color: white; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; text-align: left;">
        <i class="fas fa-exclamation-circle" style="margin-right: 0.5rem;"></i>
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger" style="background: #EF4444; color: white; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; text-align: left;">
        <i class="fas fa-exclamation-circle" style="margin-right: 0.5rem;"></i>
        @foreach ($errors->all() as $error)
            <div style="margin-left: 1.5rem;">{{ $error }}</div>
        @endforeach
    </div>
@endif

@if(!session('email_sent') && !session('email_verified'))
    <!-- STEP 1: SEND OTP -->
    <form action="{{ route('password.email') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Email</label>
            <div style="position: relative;">
                <i class="fas fa-envelope" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                <input type="email" name="email" class="form-control" required placeholder="nama@email.com" style="padding-left: 2.75rem;">
            </div>
        </div>

        <button type="submit" class="btn" style="width: 100%; margin-top: 1rem;">
            Kirim Kode OTP <i class="fas fa-paper-plane" style="margin-left: 0.5rem;"></i>
        </button>
    </form>
@elseif(session('email_sent'))
    <!-- STEP 2: VERIFY OTP -->
    <form action="{{ route('password.verify') }}" method="POST">
        @csrf
        <input type="hidden" name="email" value="{{ session('email_sent') }}">
        
        <div class="form-group">
            <label class="form-label">Kode OTP</label>
            <div style="position: relative;">
                <i class="fas fa-key" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                <input type="text" name="otp_code" class="form-control" required placeholder="123456" style="padding-left: 2.75rem;" maxlength="6">
            </div>
            <small style="color: var(--text-muted); display: block; margin-top: 0.5rem; text-align: left;">Cek folder Inbox atau Spam pada email Anda.</small>
        </div>

        <button type="submit" class="btn" style="width: 100%; margin-top: 1rem;">
            Verifikasi Kode <i class="fas fa-check" style="margin-left: 0.5rem;"></i>
        </button>
    </form>
@elseif(session('email_verified'))
    <!-- STEP 3: RESET PASSWORD -->
    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="email" value="{{ session('email_verified') }}">
        
        <div class="form-group">
            <label class="form-label">Password Baru</label>
            <div style="position: relative;">
                <i class="fas fa-lock" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                <input type="password" name="password" class="form-control" required placeholder="••••••••" style="padding-left: 2.75rem;">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Konfirmasi Password</label>
            <div style="position: relative;">
                <i class="fas fa-lock" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                <input type="password" name="password_confirmation" class="form-control" required placeholder="••••••••" style="padding-left: 2.75rem;">
            </div>
        </div>

        <button type="submit" class="btn" style="width: 100%; margin-top: 1rem;">
            Reset Password <i class="fas fa-save" style="margin-left: 0.5rem;"></i>
        </button>
    </form>
@endif

<div class="footer" style="margin-top: 1.5rem;">
    Kembali ke <a href="{{ route('login') }}">Halaman Login</a>
</div>

@endsection
