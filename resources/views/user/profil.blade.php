@extends('layouts.app')
@section('content')

<div class="page-header">
    <h1 class="page-title">Profil Pengguna</h1>
    <p class="subtitle">Kelola informasi pribadi dan keamanan akun Anda.</p>
</div>

<div class="row" style="display: flex; gap: 2rem; align-items: flex-start; max-width: 1000px;">
    
    <!-- Bagian Avatar/Info -->
    <div class="card" style="flex: 1; text-align: center; padding: 3rem 2rem;">
        <div style="width: 120px; height: 120px; border-radius: 50%; background: var(--primary-gradient); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto; box-shadow: 0 10px 25px rgba(36,58,94,0.2);">
            <i class="fas fa-user" style="font-size: 3rem; color: white;"></i>
        </div>
        <h3 style="margin: 0; color: var(--text); font-weight: 700;">{{ Auth::user()->name }}</h3>
        <p style="color: var(--text-muted); margin: 0.5rem 0 1.5rem 0;">{{ Auth::user()->email }}</p>
        <span class="badge badge-success" style="padding: 0.5rem 1rem;">User Aktif</span>
    </div>

    <!-- Bagian Form -->
    <div class="card" style="flex: 2; padding: 2.5rem;">
        <h3 style="margin-top:0; border-bottom: 1px solid var(--border); padding-bottom: 1rem; margin-bottom: 1.5rem; font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-user-edit" style="color: var(--primary);"></i> Edit Profil
        </h3>
        
        @if(session('success'))
            <div class="badge badge-success" style="padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; display: block; font-size: 1rem;">
                <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: #EF4444; color: white; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; display: block; font-size: 1rem;">
                <i class="fas fa-exclamation-circle" style="margin-right: 0.5rem;"></i> {{ session('error') }}
            </div>
        @endif
        
        @if ($errors->any())
            <div style="background: #EF4444; color: white; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; display: block; font-size: 1rem;">
                <i class="fas fa-exclamation-circle" style="margin-right: 0.5rem;"></i>
                @foreach ($errors->all() as $error)
                    <div style="margin-left: 1.5rem;">{{ $error }}</div>
                @endforeach
            </div>
        @endif
        
        <form action="{{ route('user.profile.update') }}" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label">Nama Lengkap</label>
                <div style="position: relative;">
                    <i class="fas fa-user" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                    <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" style="padding-left: 2.75rem;" required>
                </div>
            </div>
            
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label">Alamat Email</label>
                <div style="position: relative;">
                    <i class="fas fa-envelope" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                    <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly style="background: #F8FAFC; color: var(--text-muted); cursor: not-allowed; padding-left: 2.75rem;">
                </div>
                <small style="color: var(--text-muted); display: block; margin-top: 0.5rem;">Email digunakan untuk login dan tidak dapat diubah.</small>
            </div>
            
            <div style="border-top: 1px solid var(--border); margin: 2rem 0; padding-top: 2rem;">
                <h4 style="margin-top:0; margin-bottom: 1.5rem; font-size: 1rem; color: var(--text);">Ubah Password</h4>
                
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Password Lama</label>
                    <div style="position: relative;">
                        <i class="fas fa-key" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                        <input type="password" name="old_password" class="form-control" placeholder="Masukkan password lama" style="padding-left: 2.75rem;">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password Baru</label>
                    <div style="position: relative;">
                        <i class="fas fa-lock" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password" style="padding-left: 2.75rem;">
                    </div>
                </div>
                
                <div style="text-align: right; margin-top: 0.5rem;">
                    <a href="{{ route('password.request') }}" style="font-size: 0.875rem; color: var(--primary); text-decoration: none;">Lupa Password?</a>
                </div>
            </div>
            
            <div style="text-align: right; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem;">
                    <i class="fas fa-save" style="margin-right: 0.5rem;"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@media (max-width: 768px) {
    .row {
        flex-direction: column;
    }
}

@endsection
