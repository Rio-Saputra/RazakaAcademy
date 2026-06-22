@extends('layouts.app')
@section('content')

<div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h1 class="page-title" style="margin: 0;">Profil Admin</h1>
        <p class="subtitle">Kelola informasi akun dan kata sandi Anda.</p>
    </div>
</div>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('admin.profile.update') }}" method="POST">
        @csrf
        
        <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem; color: var(--primary); border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">Informasi Dasar</h3>
        
        <div class="form-group">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="form-group" style="margin-bottom: 2rem;">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <h3 style="font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem; color: var(--primary); border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">Ganti Kata Sandi (Opsional)</h3>
        
        <div class="form-group">
            <label class="form-label">Kata Sandi Lama</label>
            <input type="password" name="old_password" class="form-control" placeholder="Masukkan kata sandi saat ini jika ingin mengganti kata sandi">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Kata Sandi Baru</label>
                <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter">
            </div>

            <div class="form-group">
                <label class="form-label">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi kata sandi baru">
            </div>
        </div>

        <div style="border-top: 1px solid var(--border); padding-top: 1.5rem; margin-top: 1rem; display: flex; justify-content: flex-end;">
            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2.5rem;"><i class="fas fa-save"></i> Simpan Perubahan</button>
        </div>
    </form>
</div>

@endsection
