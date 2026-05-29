@extends('layouts.app')
@section('content')

<!-- Header Kelola Tryout Premium -->
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title"><i class="fas fa-clock"></i> Kelola Ujian Tryout</h1>
        <p class="admin-page-subtitle">Buat, perbarui, dan generate soal tryout secara otomatis dari bank soal.</p>
    </div>
    <button class="btn btn-primary btn-add-tryout-p" onclick="openAddModal()">
        <i class="fas fa-plus"></i> Tambah Tryout Baru
    </button>
</div>

<div class="workspace-tabs" style="display: flex; gap: 1rem; margin-bottom: 2.5rem; border-bottom: 2px solid var(--border); padding-bottom: 0.75rem;">
    <a href="{{ route('admin.tryout.index') }}" class="tab-item active-tab" style="font-size: 1.1rem; font-weight: 600; padding: 0.75rem 1.5rem; border-radius: 12px; transition: var(--transition); display: flex; align-items: center; gap: 0.5rem; text-decoration: none;">
        <i class="fas fa-clock"></i> Ujian Tryout
    </a>
    <a href="{{ route('admin.soal.index') }}" class="tab-item" style="font-size: 1.1rem; font-weight: 600; padding: 0.75rem 1.5rem; color: var(--text-muted); border-radius: 12px; transition: var(--transition); display: flex; align-items: center; gap: 0.5rem; text-decoration: none;">
        <i class="fas fa-file-alt"></i> Daftar Soal Tryout
    </a>
</div>

<style>
.active-tab {
    background: var(--primary-gradient) !important;
    color: white !important;
    box-shadow: var(--shadow-sm);
}
.tab-item:not(.active-tab):hover {
    background: #E2E8F0;
    color: var(--primary);
}
</style>

<!-- Table Container Card -->
<div class="card table-card-premium">
    <div class="table-container">
        <table class="premium-admin-table">
            <thead>
                <tr>
                    <th>Judul Tryout</th>
                    <th>Paket Soal</th>
                    <th>Durasi Ujian</th>
                    <th>Jumlah Soal</th>
                    <th>Batas Kerjakan</th>
                    <th style="text-align: right;">Aksi Panel</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tryouts as $t)
                <tr>
                    <td class="td-title-text">{{ $t->title }}</td>
                    <td>
                        <span class="badge-package-name">
                            <i class="fas fa-box-open"></i> {{ $t->package->name ?? '-' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge-duration-p">
                            <i class="far fa-clock"></i> {{ $t->duration }} Menit
                        </span>
                    </td>
                    <td>
                        <span class="badge-questions-count">
                            <i class="fas fa-file-alt"></i> {{ $t->questions_count }} Soal
                        </span>
                    </td>
                    <td>
                        <span class="badge-attempts-limit">
                            <i class="fas fa-redo"></i> Maks {{ $t->batas_pengerjaan }}x
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div class="admin-actions-wrapper">
                            <button class="btn btn-generate-soal-p" onclick="generateSoal({{ $t->id }}, '{{ $t->title }}')">
                                <i class="fas fa-cogs"></i> Generate
                            </button>
                            <a href="{{ route('admin.soal.index', ['tryout_id' => $t->id]) }}" class="btn btn-view-soal-p">
                                <i class="fas fa-list-ol"></i> Soal
                            </a>
                            <button class="btn btn-edit-tryout-p" onclick="editTryout({{ $t->id }}, '{{ $t->title }}', {{ $t->package_id }}, {{ $t->duration }}, {{ $t->batas_pengerjaan }})">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <form action="{{ route('admin.tryout.destroy', $t->id) }}" method="POST" style="display:inline;" data-confirm="Yakin ingin menghapus tryout ini?" data-type="confirm" data-title="Konfirmasi Hapus">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete-tryout-p">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="empty-table-state">
                        <i class="fas fa-clock empty-icon"></i>
                        <p class="empty-text-main">Belum Ada Tryout</p>
                        <p class="empty-text-sub">Silakan tambahkan tryout baru dengan mengklik tombol di kanan atas.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah/Edit Premium -->
<div id="addModal" class="modal-backdrop-premium" style="display:none;">
    <div class="modal-content-premium">
        <button class="modal-close-btn" onclick="closeModal()"><i class="fas fa-times"></i></button>
        <h2 id="modalTitle" class="modal-title-p">Tambah Tryout Baru</h2>
        <p class="modal-desc-p">Silakan isi formulir di bawah ini dengan lengkap.</p>
        
        <form id="tryoutForm" action="{{ route('admin.tryout.store') }}" method="POST" class="modal-form-grid">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            
            <div class="form-group">
                <label class="form-label-p">Judul Tryout</label>
                <div class="input-icon-wrapper">
                    <i class="fas fa-font input-icon-l"></i>
                    <input type="text" name="title" id="title" class="form-control-p" placeholder="Contoh: UTBK Matematika Saintek" required>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label-p">Paket Ujian</label>
                <div class="input-icon-wrapper">
                    <i class="fas fa-box open input-icon-l"></i>
                    <select name="package_id" id="package_id" class="form-control-p select-p" required>
                        @foreach($packages as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="form-group-split">
                <div class="form-group">
                    <label class="form-label-p">Durasi (Menit)</label>
                    <div class="input-icon-wrapper">
                        <i class="far fa-clock input-icon-l"></i>
                        <input type="number" name="duration" id="duration" class="form-control-p" placeholder="60" required min="1">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label-p">Batas Pengerjaan (Kali)</label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-redo input-icon-l"></i>
                        <input type="number" name="batas_pengerjaan" id="batas_pengerjaan" class="form-control-p" required min="1" value="1">
                    </div>
                </div>
            </div>
            
            <div class="modal-footer-actions">
                <button type="button" class="btn btn-modal-cancel" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn btn-modal-submit">Simpan Tryout</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Generate Soal Premium -->
<div id="generateModal" class="modal-backdrop-premium" style="display:none;">
    <div class="modal-content-premium">
        <button class="modal-close-btn" onclick="closeGenerateModal()"><i class="fas fa-times"></i></button>
        <h2 class="modal-title-p"><i class="fas fa-cogs" style="color: var(--primary);"></i> Generate Soal Ujian</h2>
        <p id="generateTryoutTitle" class="modal-desc-p" style="color: var(--primary); font-weight: 700;"></p>
        <p class="modal-info-alert">Sistem akan menyalin soal secara acak dari Kumpulan Bank Soal berdasarkan filter kategori di bawah.</p>
        
        <form action="{{ route('admin.tryout.generate') }}" method="POST" class="modal-form-grid">
            @csrf
            <input type="hidden" name="tryout_id" id="generateTryoutId">
            
            <div class="form-group">
                <label class="form-label-p">Pilih Kategori Bank Soal</label>
                <div class="input-icon-wrapper">
                    <i class="fas fa-filter input-icon-l"></i>
                    <select name="kategori_id" class="form-control-p select-p">
                        <option value="">Semua Kategori (Acak Keseluruhan)</option>
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label-p">Jumlah Soal yang Diambil</label>
                <div class="input-icon-wrapper">
                    <i class="fas fa-file-signature input-icon-l"></i>
                    <input type="number" name="jumlah_soal" class="form-control-p" required min="1" value="20">
                </div>
                <small class="form-helper-text">Pastikan jumlah soal tersedia di Bank Soal mencukupi.</small>
            </div>
            
            <div class="modal-footer-actions">
                <button type="button" class="btn btn-modal-cancel" onclick="closeGenerateModal()">Batal</button>
                <button type="submit" class="btn btn-modal-submit btn-generate-gradient">Mulai Generate Soal</button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Styling & Animasi Admin Kelola Tryout */
    @keyframes modalZoomIn {
        from { opacity: 0; transform: scale(0.92); }
        to { opacity: 1; transform: scale(1); }
    }

    .admin-page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .admin-page-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0;
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .admin-page-subtitle {
        margin: 0.25rem 0 0 0;
        color: var(--text-muted);
        font-size: 0.95rem;
    }

    .btn-add-tryout-p {
        background: var(--primary-gradient) !important;
        color: white !important;
        border-radius: 14px !important;
        padding: 0.85rem 1.75rem !important;
        box-shadow: 0 10px 20px -5px rgba(36, 58, 94, 0.25) !important;
    }

    .btn-add-tryout-p:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 12px 22px -5px rgba(36, 58, 94, 0.35) !important;
    }

    /* Tabel Kelola Premium */
    .table-card-premium {
        border-radius: 20px !important;
        padding: 1.5rem !important;
        box-shadow: 0 10px 30px -10px rgba(0,0,0,0.02) !important;
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
    }

    .premium-admin-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .premium-admin-table th {
        background: #F8FAFC;
        padding: 1.1rem 1rem !important;
        color: var(--text-muted);
        font-weight: 700;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        border-bottom: 1px solid var(--border);
    }

    .premium-admin-table td {
        padding: 1.25rem 1rem !important;
        border-bottom: 1px solid #F1F5F9;
        font-size: 0.95rem;
        color: #334155;
        vertical-align: middle;
    }

    .premium-admin-table tbody tr {
        transition: all 0.2s ease;
    }

    .premium-admin-table tbody tr:hover {
        background: #F8FAFC;
    }

    .td-title-text {
        font-weight: 700;
        color: var(--primary) !important;
    }

    /* Badges Premium Admin */
    .badge-package-name {
        background: rgba(59, 130, 246, 0.08);
        color: #3B82F6;
        padding: 0.4rem 0.85rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .badge-duration-p {
        background: rgba(100, 116, 139, 0.08);
        color: #475569;
        padding: 0.4rem 0.85rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .badge-questions-count {
        background: rgba(16, 185, 129, 0.08);
        color: #059669;
        padding: 0.4rem 0.85rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .badge-attempts-limit {
        background: rgba(245, 158, 11, 0.08);
        color: #D97706;
        padding: 0.4rem 0.85rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    /* Empty State */
    .empty-table-state {
        text-align: center !important;
        padding: 4rem 1rem !important;
        color: var(--text-muted);
    }

    .empty-icon {
        font-size: 3rem;
        color: #CBD5E1;
        margin-bottom: 1rem;
    }

    .empty-text-main {
        font-weight: 700;
        font-size: 1.15rem;
        color: var(--primary);
        margin: 0 0 0.25rem 0;
    }

    .empty-text-sub {
        font-size: 0.9rem;
        margin: 0;
    }

    /* Action Buttons Admin */
    .admin-actions-wrapper {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-generate-soal-p {
        background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%) !important;
        color: white !important;
        padding: 0.5rem 1rem !important;
        font-size: 0.85rem !important;
        border-radius: 10px !important;
        box-shadow: 0 4px 10px rgba(59, 130, 246, 0.15) !important;
    }

    .btn-generate-soal-p:hover {
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 14px rgba(59, 130, 246, 0.25) !important;
    }

    .btn-view-soal-p {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%) !important;
        color: white !important;
        padding: 0.5rem 1rem !important;
        font-size: 0.85rem !important;
        border-radius: 10px !important;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.15) !important;
    }

    .btn-view-soal-p:hover {
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 14px rgba(16, 185, 129, 0.25) !important;
    }

    .btn-edit-tryout-p {
        background: #F1F5F9 !important;
        color: #475569 !important;
        border: 1px solid #E2E8F0 !important;
        padding: 0.5rem 0.75rem !important;
        font-size: 0.85rem !important;
        border-radius: 10px !important;
    }

    .btn-edit-tryout-p:hover {
        background: #E2E8F0 !important;
        color: var(--primary) !important;
    }

    .btn-delete-tryout-p {
        background: rgba(239, 68, 68, 0.08) !important;
        color: #EF4444 !important;
        padding: 0.5rem 0.75rem !important;
        font-size: 0.85rem !important;
        border-radius: 10px !important;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-delete-tryout-p:hover {
        background: #EF4444 !important;
        color: white !important;
        transform: scale(1.05);
    }

    /* Modal Backdrop Blur */
    .modal-backdrop-premium {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(8px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content-premium {
        background: white;
        width: 100%;
        max-width: 520px;
        padding: 2.5rem;
        border-radius: 24px;
        position: relative;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        animation: modalZoomIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .modal-close-btn {
        position: absolute;
        right: 1.5rem;
        top: 1.5rem;
        background: #F1F5F9;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        color: #64748B;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .modal-close-btn:hover {
        background: #E2E8F0;
        color: var(--primary);
    }

    .modal-title-p {
        margin: 0 0 0.5rem 0;
        font-size: 1.45rem;
        font-weight: 700;
        color: var(--primary);
    }

    .modal-desc-p {
        margin: 0 0 2rem 0;
        font-size: 0.95rem;
        color: var(--text-muted);
    }

    .modal-info-alert {
        background: rgba(59, 130, 246, 0.05);
        color: #1E3A8A;
        border: 1px solid rgba(59, 130, 246, 0.15);
        padding: 0.75rem 1rem;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 500;
        line-height: 1.5;
        margin-bottom: 1.5rem;
    }

    /* Modal Form Elements */
    .modal-form-grid {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .form-label-p {
        display: block;
        font-weight: 600;
        font-size: 0.85rem;
        color: #475569;
        margin-bottom: 0.45rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .input-icon-wrapper {
        position: relative;
    }

    .input-icon-l {
        position: absolute;
        left: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94A3B8;
        font-size: 0.95rem;
    }

    .form-control-p {
        width: 100%;
        padding: 0.85rem 1rem 0.85rem 2.75rem;
        border: 2px solid #E2E8F0;
        border-radius: 14px;
        font-size: 0.98rem;
        transition: all 0.25s;
        background: #F8FAFC;
        font-weight: 500;
        color: #334155;
    }

    .form-control-p:focus {
        border-color: var(--primary);
        background: white;
        outline: none;
        box-shadow: 0 0 0 4px rgba(36, 58, 94, 0.08);
    }

    .select-p {
        appearance: none;
        cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19.5 8.25l-7.5 7.5-7.5-7.5'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1.25rem center;
        background-size: 1.1rem;
        padding-right: 2.75rem;
    }

    .form-group-split {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }

    .form-helper-text {
        color: var(--text-muted);
        font-size: 0.8rem;
        margin-top: 0.35rem;
        display: block;
    }

    /* Modal Footer Actions */
    .modal-footer-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        justify-content: flex-end;
    }

    .btn-modal-cancel {
        background: #F1F5F9 !important;
        color: #64748B !important;
        border: none !important;
        padding: 0.85rem 1.75rem !important;
        border-radius: 14px !important;
    }

    .btn-modal-cancel:hover {
        background: #E2E8F0 !important;
        color: var(--primary) !important;
    }

    .btn-modal-submit {
        background: var(--primary-gradient) !important;
        color: white !important;
        border: none !important;
        padding: 0.85rem 1.75rem !important;
        border-radius: 14px !important;
        box-shadow: 0 8px 16px rgba(36, 58, 94, 0.2) !important;
    }

    .btn-modal-submit:hover {
        transform: translateY(-1px) !important;
        box-shadow: 0 10px 20px rgba(36, 58, 94, 0.3) !important;
    }

    .btn-generate-gradient {
        background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%) !important;
        box-shadow: 0 8px 16px rgba(59, 130, 246, 0.2) !important;
    }

    .btn-generate-gradient:hover {
        box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3) !important;
    }

    @media (max-width: 768px) {
        .form-group-split {
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }
    }
</style>

<script>
function openAddModal() {
    document.getElementById('addModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('addModal').style.display = 'none';
    document.getElementById('tryoutForm').reset();
    document.getElementById('tryoutForm').action = "{{ route('admin.tryout.store') }}";
    document.getElementById('formMethod').value = "POST";
    document.getElementById('modalTitle').innerText = "Tambah Tryout Baru";
}

function editTryout(id, title, package_id, duration, batas) {
    document.getElementById('addModal').style.display = 'flex';
    document.getElementById('modalTitle').innerText = "Edit Detail Tryout";
    document.getElementById('tryoutForm').action = "/admin/tryout/" + id;
    document.getElementById('formMethod').value = "PUT";
    document.getElementById('title').value = title;
    document.getElementById('package_id').value = package_id;
    document.getElementById('duration').value = duration;
    document.getElementById('batas_pengerjaan').value = batas;
}

function generateSoal(id, title) {
    document.getElementById('generateModal').style.display = 'flex';
    document.getElementById('generateTryoutId').value = id;
    document.getElementById('generateTryoutTitle').innerText = "Tryout: " + title;
}

function closeGenerateModal() {
    document.getElementById('generateModal').style.display = 'none';
}

// Close modals when clicking backdrop
window.addEventListener('click', function(e) {
    const addModal = document.getElementById('addModal');
    const genModal = document.getElementById('generateModal');
    if (e.target === addModal) closeModal();
    if (e.target === genModal) closeGenerateModal();
});
</script>
@endsection

