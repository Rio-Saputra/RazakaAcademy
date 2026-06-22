@extends('layouts.app')
@section('content')

<div class="page-header" style="padding: 3rem 2rem; border-radius: 24px; background: white; border: 1px solid #E2E8F0; box-shadow: var(--shadow-sm); margin-bottom: 2.5rem; position: relative; overflow: hidden;">
    <!-- Abstract background decorations for premium feel -->
    <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; border-radius: 50%; background: rgba(56, 189, 248, 0.08); filter: blur(30px); pointer-events: none;"></div>
    <div style="position: absolute; bottom: -50px; left: -50px; width: 150px; height: 150px; border-radius: 50%; background: rgba(36, 58, 94, 0.08); filter: blur(30px); pointer-events: none;"></div>

    <h1 class="page-title" style="font-size: 2.6rem; font-weight: 800; color: #1E293B; letter-spacing: -0.5px; margin-bottom: 0.5rem;">Profil Pengguna</h1>
    <p class="subtitle" style="font-size: 1.1rem; max-width: 600px; color: #64748B; margin: 0; line-height: 1.6;">Kelola informasi pribadi, lengkapi identitas diri, dan amankan akun Anda.</p>
</div>

<div class="profile-grid" style="display: grid; grid-template-columns: 1fr 2.2fr; gap: 2rem; max-width: 1100px; margin: 0 auto 3rem auto; align-items: start; padding: 0 1rem;">
    
    <!-- Bagian Avatar/Info -->
    <div class="card" style="text-align: center; padding: 3.5rem 2rem; border-radius: 24px !important; background: rgba(36, 58, 94, 0.95) !important; border: 1px solid rgba(255, 255, 255, 0.12) !important; box-shadow: var(--shadow-sm);">
        @php
            $initials = '';
            $words = explode(' ', Auth::user()->name);
            foreach($words as $w) {
                if(!empty($w)) $initials .= strtoupper($w[0]);
            }
            $initials = substr($initials, 0, 2);
        @endphp
        <div style="width: 110px; height: 110px; border-radius: 50%; background: var(--primary-gradient); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.75rem auto; box-shadow: 0 10px 25px rgba(56,189,248,0.2); border: 4px solid rgba(255,255,255,0.15);">
            <span style="font-size: 2.3rem; font-weight: 800; color: white; font-family: 'Poppins', sans-serif;">{{ $initials }}</span>
        </div>
        <h3 style="margin: 0 0 0.5rem 0; color: white; font-weight: 800; font-size: 1.35rem;">{{ Auth::user()->name }}</h3>
        <p style="color: #CBD5E1; margin: 0 0 2rem 0; font-size: 0.9rem; font-weight: 500;">{{ Auth::user()->email }}</p>
        <span class="badge" style="background: rgba(16, 185, 129, 0.15) !important; color: #34D399 !important; padding: 0.5rem 1.25rem; font-size: 0.8rem; font-weight: 700; border-radius: 50px; border: 1px solid rgba(16, 185, 129, 0.2) !important;">
            <i class="fas fa-check-circle" style="margin-right: 4px;"></i> Siswa Aktif
        </span>
    </div>

    <!-- Bagian Form -->
    <div class="card" style="padding: 3rem; border-radius: 24px !important; background: rgba(36, 58, 94, 0.95) !important; border: 1px solid rgba(255, 255, 255, 0.12) !important; box-shadow: var(--shadow-sm);">
        <h3 style="margin-top:0; border-bottom: 1px solid rgba(255,255,255,0.12); padding-bottom: 1.25rem; margin-bottom: 2rem; font-size: 1.25rem; display: flex; align-items: center; gap: 0.75rem; color: white; font-weight: 800;">
            <i class="fas fa-user-edit" style="color: #38BDF8;"></i> Pengaturan Profil
        </h3>
        
        @if(session('success'))
            <div class="badge badge-success" style="padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.75rem; display: block; font-size: 0.92rem; font-weight: 700; background: #DEF7EC !important; color: #03543F !important; border: 1px solid rgba(16, 185, 129, 0.2) !important;">
                <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: #FDE8E8; color: #9B1C1C; padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.75rem; display: block; font-size: 0.92rem; font-weight: 700; border: 1px solid rgba(239, 68, 68, 0.2);">
                <i class="fas fa-exclamation-circle" style="margin-right: 0.5rem;"></i> {{ session('error') }}
            </div>
        @endif
        
        @if ($errors->any())
            <div style="background: #FDE8E8; color: #9B1C1C; padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.75rem; display: block; font-size: 0.92rem; font-weight: 700; border: 1px solid rgba(239, 68, 68, 0.2);">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>Tolong periksa kesalahan berikut:</span>
                </div>
                @foreach ($errors->all() as $error)
                    <div style="margin-left: 1.5rem; font-weight: 500;">• {{ $error }}</div>
                @endforeach
            </div>
        @endif
        
        <form action="{{ route('user.profile.update') }}" method="POST">
            @csrf
            <div class="form-group" style="margin-bottom: 1.75rem;">
                <label class="form-label" style="color: white; font-weight: 600; margin-bottom: 0.6rem; display: block;">Nama Lengkap</label>
                <div style="position: relative;">
                    <i class="fas fa-user" style="position: absolute; left: 1.25rem; top: 50%; transform: translateY(-50%); color: #94A3B8;"></i>
                    <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" style="padding-left: 3.25rem; height: 50px; font-weight: 500;" required>
                </div>
            </div>
            
            <div class="form-group" style="margin-bottom: 1.75rem;">
                <label class="form-label" style="color: white; font-weight: 600; margin-bottom: 0.6rem; display: block;">Alamat Email</label>
                <div style="position: relative;">
                    <i class="fas fa-envelope" style="position: absolute; left: 1.25rem; top: 50%; transform: translateY(-50%); color: #94A3B8;"></i>
                    <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly style="background: rgba(255, 255, 255, 0.08) !important; color: #CBD5E1 !important; border: 1.5px solid rgba(255,255,255,0.12) !important; cursor: not-allowed; padding-left: 3.25rem; height: 50px; font-weight: 500;">
                </div>
                <small style="color: #CBD5E1; display: block; margin-top: 0.6rem; font-size: 0.8rem; font-weight: 500;">
                    <i class="fas fa-info-circle" style="color: #38BDF8; margin-right: 4px;"></i> Email digunakan untuk login dan tidak dapat diubah.
                </small>
            </div>
            
            <div style="border-top: 1px solid rgba(255,255,255,0.12); margin: 2.5rem 0; padding-top: 2rem;">
                <h4 style="margin-top:0; margin-bottom: 1.5rem; font-size: 1.15rem; color: white; font-weight: 800;">
                    <i class="fas fa-shield-alt" style="color: #38BDF8; margin-right: 6px;"></i> Ubah Password Keamanan
                </h4>
                
                <div class="form-group" style="margin-bottom: 1.75rem;">
                    <label class="form-label" style="color: white; font-weight: 600; margin-bottom: 0.6rem; display: block;">Password Lama</label>
                    <div style="position: relative;">
                        <i class="fas fa-key" style="position: absolute; left: 1.25rem; top: 50%; transform: translateY(-50%); color: #94A3B8;"></i>
                        <input type="password" name="old_password" class="form-control" placeholder="Masukkan password lama" style="padding-left: 3.25rem; height: 50px;">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label class="form-label" style="color: white; font-weight: 600; margin-bottom: 0.6rem; display: block;">Password Baru</label>
                    <div style="position: relative;">
                        <i class="fas fa-lock" style="position: absolute; left: 1.25rem; top: 50%; transform: translateY(-50%); color: #94A3B8;"></i>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password" style="padding-left: 3.25rem; height: 50px;">
                    </div>
                </div>
                
                <div style="text-align: right; margin-bottom: 1rem;">
                    <a href="{{ route('password.request') }}" style="font-size: 0.85rem; color: #38BDF8; text-decoration: none; font-weight: 600; transition: color 0.2s ease;">Lupa Password?</a>
                </div>
            </div>
            
            <div style="text-align: right; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.9rem 2.25rem; font-size: 1rem; border-radius: 50px; font-weight: 700; gap: 0.5rem; justify-content: center;">
                    <i class="fas fa-save" style="font-size: 0.9rem;"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .profile-grid {
        transition: all 0.3s ease;
    }
    @media (max-width: 992px) {
        .profile-grid {
            grid-template-columns: 1fr !important;
            gap: 1.5rem !important;
        }
    }
</style>

@endsection
