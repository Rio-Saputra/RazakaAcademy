@extends('layouts.app')
@section('content')

<!-- Header Halaman -->
<div class="page-header" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 2.25rem;">
    <div>
        <h1 class="page-title" style="display: flex; align-items: center; gap: 0.75rem;">
            <i class="fas fa-users-cog" style="color: #243A5E;"></i> Kelola User
        </h1>
        <p class="subtitle" style="margin-top: 0.25rem;">Kelola hak akses administrator dan database siswa terdaftar di Razaka Academy.</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary" onclick="openAddModal()" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; font-weight: 600; border-radius: 12px; box-shadow: 0 4px 10px rgba(36, 58, 94, 0.15); border: none;">
            <i class="fas fa-user-plus"></i> Tambah User Baru
        </button>
    </div>
</div>

<!-- Pesan Flash Notifikasi -->
@if(session('success'))
    <div class="badge badge-success" style="padding: 1.25rem; border-radius: 16px; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem; font-size: 1rem; width: 100%; border: 1px solid #10B981; background: #ECFDF5; color: #065F46; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.05);">
        <i class="fas fa-check-circle" style="font-size: 1.25rem;"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="badge badge-danger" style="padding: 1.25rem; border-radius: 16px; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem; font-size: 1rem; width: 100%; border: 1px solid #EF4444; background: #FEF2F2; color: #991B1B; box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.05);">
        <i class="fas fa-exclamation-circle" style="font-size: 1.25rem;"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

<!-- Panel Kartu Statistik -->
<div class="stats-grid-p">
    <!-- Total Pengguna -->
    <div class="stat-card-p">
        <div class="stat-icon-wrapper-p color-indigo">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <span class="stat-label-p" style="font-size: 0.85rem; color: #64748B; font-weight: 500;">Total Pengguna</span>
            <h3 class="stat-value-p" style="font-size: 1.75rem; font-weight: 700; color: #1E293B; margin: 0.2rem 0 0 0;">{{ $total_users }}</h3>
        </div>
    </div>
    
    <!-- Administrator -->
    <div class="stat-card-p">
        <div class="stat-icon-wrapper-p color-amber">
            <i class="fas fa-user-shield"></i>
        </div>
        <div>
            <span class="stat-label-p" style="font-size: 0.85rem; color: #64748B; font-weight: 500;">Administrator</span>
            <h3 class="stat-value-p" style="font-size: 1.75rem; font-weight: 700; color: #1E293B; margin: 0.2rem 0 0 0;">{{ $total_admins }}</h3>
        </div>
    </div>

    <!-- Siswa (User) -->
    <div class="stat-card-p">
        <div class="stat-icon-wrapper-p color-emerald">
            <i class="fas fa-user-graduate"></i>
        </div>
        <div>
            <span class="stat-label-p" style="font-size: 0.85rem; color: #64748B; font-weight: 500;">Siswa (User)</span>
            <h3 class="stat-value-p" style="font-size: 1.75rem; font-weight: 700; color: #1E293B; margin: 0.2rem 0 0 0;">{{ $total_students }}</h3>
        </div>
    </div>

    <!-- Pendaftar Baru (Bulan Ini) -->
    <div class="stat-card-p">
        <div class="stat-icon-wrapper-p color-blue">
            <i class="fas fa-user-plus"></i>
        </div>
        <div>
            <span class="stat-label-p" style="font-size: 0.85rem; color: #64748B; font-weight: 500;">Baru Bulan Ini</span>
            <h3 class="stat-value-p" style="font-size: 1.75rem; font-weight: 700; color: #1E293B; margin: 0.2rem 0 0 0;">{{ $total_new_this_month }}</h3>
        </div>
    </div>
</div>

<!-- Pencarian dan Filter -->
<div class="card" style="margin-bottom: 2rem; padding: 1.25rem 1.5rem; border-radius: 16px; border: 1px solid #E2E8F0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01);">
    <div style="display: flex; gap: 1.25rem; align-items: center; flex-wrap: wrap;">
        <!-- Input Cari -->
        <div style="flex: 1; min-width: 280px; position: relative;">
            <i class="fas fa-search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94A3B8;"></i>
            <input type="text" id="user-search-input" placeholder="Cari nama atau email pengguna..." onkeyup="filterUserTable()" style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.75rem; border-radius: 12px; border: 1px solid #CBD5E1; font-size: 0.95rem; transition: border-color 0.2s, box-shadow 0.2s; background: white;" />
        </div>
        
        <!-- Dropdown Role -->
        <div style="width: 200px; min-width: 150px;">
            <select id="user-role-filter" onchange="filterUserTable()" style="width: 100%; padding: 0.75rem 1rem; border-radius: 12px; border: 1px solid #CBD5E1; font-size: 0.95rem; background: white; cursor: pointer;">
                <option value="all">Semua Role</option>
                <option value="admin">Administrator</option>
                <option value="user">Siswa (User)</option>
            </select>
        </div>
    </div>
</div>

<!-- Tabel Pengguna -->
<div class="card" style="border-radius: 16px; overflow: hidden; border: 1px solid #E2E8F0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);">
    <div class="table-container" style="margin: 0; border-radius: 0;">
        <table id="users-table">
            <thead>
                <tr style="background: #F8FAFC;">
                    <th style="padding: 1.25rem 1.5rem; font-weight: 600; color: #475569; font-size: 0.9rem; border-bottom: 1px solid #E2E8F0;">Pengguna</th>
                    <th style="padding: 1.25rem 1.5rem; font-weight: 600; color: #475569; font-size: 0.9rem; border-bottom: 1px solid #E2E8F0;">Email</th>
                    <th style="padding: 1.25rem 1.5rem; font-weight: 600; color: #475569; font-size: 0.9rem; border-bottom: 1px solid #E2E8F0;">Hak Akses (Role)</th>
                    <th style="padding: 1.25rem 1.5rem; font-weight: 600; color: #475569; font-size: 0.9rem; border-bottom: 1px solid #E2E8F0;">Tanggal Bergabung</th>
                    <th style="padding: 1.25rem 1.5rem; font-weight: 600; color: #475569; font-size: 0.9rem; text-align: right; border-bottom: 1px solid #E2E8F0;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $index => $u)
                <!-- Ambil Inisial Nama -->
                @php
                    $words = explode(' ', trim($u->name));
                    $initials = '';
                    if (count($words) >= 2) {
                        $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                    } else if (count($words) == 1 && strlen($words[0]) > 0) {
                        $initials = strtoupper(substr($words[0], 0, 2));
                    } else {
                        $initials = 'US';
                    }
                    
                    // Skema warna background inisial dinamis
                    $colors = [
                        'linear-gradient(135deg, #4F46E5, #818CF8)', // indigo
                        'linear-gradient(135deg, #059669, #34D399)', // emerald
                        'linear-gradient(135deg, #D97706, #FBBF24)', // amber
                        'linear-gradient(135deg, #2563EB, #60A5FA)', // blue
                        'linear-gradient(135deg, #7C3AED, #A78BFA)', // violet
                        'linear-gradient(135deg, #DB2777, #F472B6)'  // pink
                    ];
                    $avatar_bg = $colors[$u->id % count($colors)];
                @endphp
                <tr class="user-row" data-name="{{ strtolower($u->name) }}" data-email="{{ strtolower($u->email) }}" data-role="{{ $u->role }}">
                    <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #F1F5F9;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <!-- Initials Avatar -->
                            <div class="user-avatar-circle" style="width: 42px; height: 42px; border-radius: 50%; background: {!! $avatar_bg !!}; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.95rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); letter-spacing: 0.5px;">
                                {{ $initials }}
                            </div>
                            <div>
                                <span style="font-weight: 600; color: #1E293B; font-size: 1rem; display: block;">{{ $u->name }}</span>
                                @if($u->id === auth()->id())
                                    <span style="font-size: 0.75rem; color: #6366F1; font-weight: 600; background: #EEF2FF; padding: 2px 8px; border-radius: 9999px; margin-top: 2px; display: inline-block; border: 1px solid #C7D2FE;">Akun Anda</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #F1F5F9; color: #334155; font-size: 0.95rem;">
                        {{ $u->email }}
                    </td>
                    <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #F1F5F9;">
                        @if($u->role == 'admin')
                            <span class="badge" style="background: #FEF3C7; color: #92400E; border: 1px solid #FDE68A; padding: 0.35rem 0.85rem; border-radius: 8px; font-weight: 600; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.35rem;">
                                <i class="fas fa-user-shield" style="font-size: 0.75rem;"></i> Administrator
                            </span>
                        @else
                            <span class="badge" style="background: #DEF7EC; color: #03543F; border: 1px solid #BCF0DA; padding: 0.35rem 0.85rem; border-radius: 8px; font-weight: 600; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.35rem;">
                                <i class="fas fa-graduation-cap" style="font-size: 0.75rem;"></i> Siswa
                            </span>
                        @endif
                    </td>
                    <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #F1F5F9; color: #64748B; font-size: 0.9rem;">
                        {{ $u->created_at->format('d M Y') }}
                    </td>
                    <td style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #F1F5F9; text-align: right;">
                        <div style="display: inline-flex; gap: 0.5rem; align-items: center;">

                            <!-- Tombol Hapus (Kecuali Akun Sendiri) -->
                            @if($u->id !== auth()->id())
                                <form action="{{ route('admin.user.destroy', $u->id) }}" method="POST" style="display: inline;" data-confirm="Apakah Anda yakin ingin menghapus user '{{ addslashes($u->name) }}' dari database? Seluruh data riwayat ujian terkait juga akan terhapus." data-type="confirm" data-title="Konfirmasi Hapus User">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 0.5rem 0.85rem; font-size: 0.85rem; font-weight: 600; border-radius: 10px; display: inline-flex; align-items: center; gap: 0.35rem;">
                                        <i class="fas fa-trash-alt"></i> Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
                
                <!-- State Table Kosong (Pencarian Tidak Ditemukan) -->
                <tr id="empty-state-row" style="display: none;">
                    <td colspan="5" style="padding: 3rem 1.5rem; text-align: center; color: #64748B;">
                        <div style="font-size: 2.5rem; margin-bottom: 1rem;"><i class="fas fa-search-minus" style="color: #CBD5E1;"></i></div>
                        <h4 style="font-weight: 600; color: #475569; margin: 0 0 0.25rem 0;">Pencarian Tidak Ditemukan</h4>
                        <p style="font-size: 0.9rem; color: #94A3B8; margin: 0;">Coba periksa kembali ejaan kata kunci pencarian Anda.</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- ========================================= -->
<!-- MODAL: TAMBAH USER BARU -->
<!-- ========================================= -->
<div id="add-user-modal" class="modal-overlay-p" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(8px); z-index: 1000; align-items: center; justify-content: center; padding: 1.5rem; transition: opacity 0.3s ease;">
    <div class="modal-box-p" style="background: white; border-radius: 20px; width: 100%; max-width: 520px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); border-top: 6px solid #243A5E; animation: modalPopUp 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); overflow: hidden;">
        <!-- Modal Header -->
        <div style="padding: 1.5rem 1.75rem; border-bottom: 1px solid #F1F5F9; display: flex; align-items: center; justify-content: space-between;">
            <h3 style="margin: 0; font-weight: 700; color: #1E293B; font-size: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-user-plus" style="color: #243A5E;"></i> Tambah User Baru
            </h3>
            <button type="button" onclick="closeAddModal()" style="border: none; background: transparent; color: #94A3B8; font-size: 1.25rem; cursor: pointer; transition: color 0.2s;"><i class="fas fa-times"></i></button>
        </div>
        
        <!-- Modal Body Form -->
        <form action="{{ route('admin.user.store') }}" method="POST" style="margin: 0;">
            @csrf
            <div style="padding: 1.75rem; display: flex; flex-direction: column; gap: 1.25rem;">
                <!-- Nama Lengkap -->
                <div>
                    <label style="display: block; font-weight: 600; color: #475569; font-size: 0.9rem; margin-bottom: 0.5rem;">Nama Lengkap</label>
                    <input type="text" name="name" required placeholder="Masukkan nama lengkap user..." style="width: 100%; padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid #CBD5E1; font-size: 0.95rem; background: white;" />
                </div>
                
                <!-- Alamat Email -->
                <div>
                    <label style="display: block; font-weight: 600; color: #475569; font-size: 0.9rem; margin-bottom: 0.5rem;">Alamat Email</label>
                    <input type="email" name="email" required placeholder="name@domain.com" style="width: 100%; padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid #CBD5E1; font-size: 0.95rem; background: white;" />
                </div>
                
                <!-- Password -->
                <div>
                    <label style="display: block; font-weight: 600; color: #475569; font-size: 0.9rem; margin-bottom: 0.5rem;">Password Akses</label>
                    <input type="password" name="password" required minlength="8" placeholder="Minimal 8 karakter..." style="width: 100%; padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid #CBD5E1; font-size: 0.95rem; background: white;" />
                </div>
                
                <!-- Role / Hak Akses -->
                <div>
                    <label style="display: block; font-weight: 600; color: #475569; font-size: 0.9rem; margin-bottom: 0.5rem;">Hak Akses (Role)</label>
                    <select name="role" required style="width: 100%; padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid #CBD5E1; font-size: 0.95rem; background: white; cursor: pointer;">
                        <option value="user" selected>Siswa (User)</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div style="padding: 1.25rem 1.75rem; background: #F8FAFC; border-top: 1px solid #F1F5F9; display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeAddModal()" style="border-radius: 10px; padding: 0.65rem 1.25rem; font-weight: 600; border-color: #CBD5E1; color: #475569;">Batal</button>
                <button type="submit" class="btn btn-primary" style="border-radius: 10px; padding: 0.65rem 1.25rem; font-weight: 600;">Simpan Pengguna</button>
            </div>
        </form>
    </div>
</div>



<!-- Styles khusus visual premium -->
<style>
.stats-grid-p {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2.25rem;
}

.stat-card-p {
    background: white;
    border-radius: 16px;
    padding: 1.5rem 1.75rem;
    border: 1px solid #E2E8F0;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01), 0 2px 4px -1px rgba(0, 0, 0, 0.005);
    display: flex;
    align-items: center;
    gap: 1.25rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    text-align: left;
}

.stat-card-p:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.08);
    border-color: rgba(36, 58, 94, 0.15);
}

.stat-icon-wrapper-p {
    width: 52px;
    height: 52px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    flex-shrink: 0;
}

.color-indigo { background: #EEF2FF; color: #4F46E5; }
.color-amber { background: #FFFDF5; color: #D97706; border: 1px solid #FEF3C7; }
.color-emerald { background: #ECFDF5; color: #059669; }
.color-blue { background: #EFF6FF; color: #2563EB; }

#user-search-input:focus,
#add-user-modal input:focus,
#add-user-modal select:focus {
    outline: none;
    border-color: #243A5E !important;
    box-shadow: 0 0 0 3px rgba(36, 58, 94, 0.1) !important;
}

/* Animasi Pop-up Modal */
@keyframes modalPopUp {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(-20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}
</style>

<!-- Script filter client-side instan & control modal -->
<script>
function filterUserTable() {
    const searchVal = document.getElementById('user-search-input').value.toLowerCase().trim();
    const roleVal = document.getElementById('user-role-filter').value;
    const rows = document.querySelectorAll('.user-row');
    let matchesCount = 0;
    
    rows.forEach(row => {
        const name = row.getAttribute('data-name');
        const email = row.getAttribute('data-email');
        const role = row.getAttribute('data-role');
        
        const matchesSearch = name.includes(searchVal) || email.includes(searchVal);
        const matchesRole = roleVal === 'all' || role === roleVal;
        
        if (matchesSearch && matchesRole) {
            row.style.display = '';
            matchesCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    const emptyRow = document.getElementById('empty-state-row');
    if (matchesCount === 0) {
        emptyRow.style.display = '';
    } else {
        emptyRow.style.display = 'none';
    }
}

// Control Modal Tambah
function openAddModal() {
    const modal = document.getElementById('add-user-modal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeAddModal() {
    const modal = document.getElementById('add-user-modal');
    modal.style.display = 'none';
    document.body.style.overflow = '';
}

// Tutup modal jika mengklik area overlay di luar box modal
window.onclick = function(event) {
    const addModal = document.getElementById('add-user-modal');
    if (event.target === addModal) {
        closeAddModal();
    }
}
</script>

@endsection
