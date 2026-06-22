@extends('layouts.app')
@section('content')

<!-- Premium Header -->
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">
            <i class="fas fa-cubes"></i> Kelola Paket Tryout
        </h1>
        <p class="admin-page-subtitle">
            Manajemen paket tryout premium, harga, deskripsi, dan integrasi ujian secara real-time.
        </p>
    </div>
    <button class="btn-add-paket-p" onclick="document.getElementById('addModal').style.display='flex'">
        <i class="fas fa-plus-circle"></i> Tambah Paket Baru
    </button>
</div>

@if(session('success'))
    <div class="alert-success-p animated fadeInDown">
        <div class="alert-content-p">
            <i class="fas fa-check-circle alert-icon-p"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif

<!-- Quick Statistics -->
<div class="stats-grid-p">
    <!-- Total Paket -->
    <div class="stat-card-p">
        <div class="stat-icon-wrapper-p color-blue">
            <i class="fas fa-box-open"></i>
        </div>
        <div class="stat-info-p">
            <span class="stat-value-p">{{ $packages->count() }}</span>
            <span class="stat-label-p">Total Paket Aktif</span>
        </div>
    </div>
    <!-- Rata-rata Harga -->
    <div class="stat-card-p">
        <div class="stat-icon-wrapper-p color-emerald">
            <i class="fas fa-tags"></i>
        </div>
        <div class="stat-info-p">
            <span class="stat-value-p">Rp {{ number_format($packages->avg('price'), 0, ',', '.') }}</span>
            <span class="stat-label-p">Rata-rata Harga</span>
        </div>
    </div>
    <!-- Total Tryout Terhubung -->
    <div class="stat-card-p">
        <div class="stat-icon-wrapper-p color-amber">
            <i class="fas fa-file-signature"></i>
        </div>
        <div class="stat-info-p">
            <span class="stat-value-p">{{ $packages->sum('tryouts_count') }}</span>
            <span class="stat-label-p">Tryout Terhubung</span>
        </div>
    </div>
</div>

<!-- Package Grid Section -->
<div class="packages-grid-p">
    @forelse($packages as $p)
    <div class="package-card-p">
        <!-- Card Ribbon/Header Info -->
        <div class="package-card-header-p">
            <span class="package-badge-p">
                <i class="fas fa-graduation-cap"></i> {{ $p->tryouts_count }} Tryout Terhubung
            </span>
            <h3 class="package-name-p">{{ $p->name }}</h3>
        </div>
        
        <!-- Card Body -->
        <div class="package-card-body-p">
            <div class="package-price-p">
                <span class="currency-p">Rp</span>
                <span class="amount-p">{{ number_format($p->price, 0, ',', '.') }}</span>
            </div>
            <p class="package-description-p">
                {{ $p->description ?: 'Tidak ada deskripsi untuk paket ini. Silakan klik edit untuk menambahkan deskripsi.' }}
            </p>
        </div>
        
        <!-- Card Footer Actions -->
        <div class="package-card-footer-p">
            <button class="btn-action-p btn-edit-p" onclick="editPackage({{ $p->id }}, '{{ addslashes($p->name) }}', {{ $p->price }}, '{{ addslashes($p->description) }}')">
                <i class="fas fa-edit"></i> Edit Paket
            </button>
            <form action="{{ route('admin.paket.destroy', $p->id) }}" method="POST" class="delete-form-p" data-confirm="Yakin ingin menghapus paket ini? Seluruh tryout yang terhubung dengan paket ini akan kehilangan paket asosiasinya." data-type="confirm" data-title="Konfirmasi Hapus Paket">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-action-p btn-delete-p">
                    <i class="fas fa-trash-alt"></i> Hapus
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="empty-state-p">
        <i class="fas fa-box-open empty-icon-p"></i>
        <h4>Belum Ada Paket Ujian</h4>
        <p>Silakan buat paket ujian baru untuk mengelompokkan tryout dan memasarkannya ke siswa.</p>
        <button class="btn btn-primary" onclick="document.getElementById('addModal').style.display='flex'">
            <i class="fas fa-plus"></i> Tambah Paket Pertama
        </button>
    </div>
    @endforelse
</div>

<!-- Modal Tambah/Edit Premium -->
<div id="addModal" class="modal-overlay-p" style="display:none;">
    <div class="modal-box-p">
        <button class="modal-close-btn-p" onclick="closeModal()">
            <i class="fas fa-times"></i>
        </button>
        
        <h2 id="modalTitle" class="modal-title-p">Tambah Paket</h2>
        
        <form id="packageForm" action="{{ route('admin.paket.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            
            <div class="form-group-p">
                <label class="form-label-p">Nama Paket <span style="color:#EF4444">*</span></label>
                <input type="text" name="name" id="name" class="form-control-p" required placeholder="Contoh: Paket Premium UTBK">
            </div>
            
            <div class="form-group-p">
                <label class="form-label-p">Harga Paket (Rp) <span style="color:#EF4444">*</span></label>
                <input type="number" name="price" id="price" class="form-control-p" required placeholder="Contoh: 150000">
            </div>
            
            <div class="form-group-p">
                <label class="form-label-p">Deskripsi Singkat</label>
                <textarea name="description" id="description" class="form-control-p textarea-p" placeholder="Masukkan deskripsi mengenai materi tryout, benefit, atau masa aktif paket..."></textarea>
            </div>
            
            <div class="modal-actions-p">
                <button type="button" class="btn-cancel-p" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn-save-p">Simpan Paket</button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Premium Header */
    .admin-page-header {
        background: var(--primary-gradient, linear-gradient(135deg, #243A5E, #2F4F7F));
        border-radius: var(--radius, 16px);
        padding: 2.25rem 2.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 25px -5px rgba(36, 58, 94, 0.15);
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .admin-page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.12) 0%, rgba(255, 255, 255, 0) 70%);
        pointer-events: none;
        border-radius: 50%;
    }

    .admin-page-title {
        font-size: 1.85rem;
        font-weight: 700;
        margin: 0 0 0.35rem 0;
        color: white;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .admin-page-subtitle {
        color: rgba(255, 255, 255, 0.85);
        font-size: 1rem;
        font-weight: 400;
        margin: 0;
    }

    .btn-add-paket-p {
        background: rgba(255, 255, 255, 0.15) !important;
        color: white !important;
        border: 1px solid rgba(255, 255, 255, 0.35) !important;
        backdrop-filter: blur(8px);
        padding: 0.85rem 1.75rem !important;
        border-radius: 14px !important;
        font-size: 0.95rem !important;
        font-weight: 600 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-add-paket-p:hover {
        background: white !important;
        color: #243A5E !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 20px rgba(255, 255, 255, 0.25) !important;
    }

    /* Alert Success */
    .alert-success-p {
        background: #ECFDF5;
        border-left: 5px solid #10B981;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.08);
    }

    .alert-content-p {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #065F46;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .alert-icon-p {
        font-size: 1.25rem;
        color: #10B981;
    }

    /* Statistics Cards */
    .stats-grid-p {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.25rem;
    }

    .stat-card-p {
        background: white;
        border-radius: 16px;
        padding: 1.5rem 1.75rem;
        border: 1px solid #E2E8F0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.006);
        display: flex;
        align-items: center;
        gap: 1.25rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
    }

    .color-blue {
        background: #EFF6FF;
        color: #2563EB;
    }

    .color-emerald {
        background: #ECFDF5;
        color: #10B981;
    }

    .color-amber {
        background: #FFFBP7;
        background: #FFFBEB;
        color: #D97706;
    }

    .stat-info-p {
        display: flex;
        flex-direction: column;
    }

    .stat-value-p {
        font-size: 1.45rem;
        font-weight: 700;
        color: #1E293B;
        line-height: 1.25;
    }

    .stat-label-p {
        font-size: 0.85rem;
        font-weight: 500;
        color: #64748B;
        margin-top: 0.15rem;
    }

    /* Packages Grid */
    .packages-grid-p {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.75rem;
        margin-bottom: 3rem;
    }

    .package-card-p {
        background: white;
        border-radius: 16px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .package-card-p:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 10px 10px -5px rgba(0, 0, 0, 0.03);
        border-color: rgba(36, 58, 94, 0.18);
    }

    .package-card-header-p {
        padding: 1.75rem 1.75rem 1rem 1.75rem;
        background: #FAFBFC;
        border-bottom: 1px solid #F1F5F9;
    }

    .package-badge-p {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: #FEF3C7;
        color: #92400E;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        border: 1px solid #FDE68A;
    }

    .package-name-p {
        font-size: 1.25rem;
        font-weight: 750;
        color: #1E293B;
        margin: 0;
        line-height: 1.4;
    }

    .package-card-body-p {
        padding: 1.5rem 1.75rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .package-price-p {
        display: flex;
        align-items: baseline;
        gap: 0.25rem;
        color: #10B981;
        font-family: sans-serif;
    }

    .currency-p {
        font-size: 1.15rem;
        font-weight: 700;
    }

    .amount-p {
        font-size: 2.15rem;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .package-description-p {
        font-size: 0.9rem;
        color: #475569;
        line-height: 1.6;
        margin: 0;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        height: 4.8em; /* Fallback */
    }

    .package-card-footer-p {
        padding: 1.25rem 1.75rem;
        background: #FAFBFC;
        border-top: 1px solid #F1F5F9;
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 0.75rem;
    }

    .btn-action-p {
        padding: 0.75rem 1rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.88rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        outline: none;
    }

    .btn-edit-p {
        background: #EFF6FF;
        color: #2563EB;
        border: 1px solid #BFDBFE;
    }

    .btn-edit-p:hover {
        background: #2563EB;
        color: white;
        border-color: #2563EB;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
    }

    .btn-delete-p {
        background: #FEF2F2;
        color: #DC2626;
        border: 1px solid #FCA5A5;
        width: 100%;
    }

    .btn-delete-p:hover {
        background: #DC2626;
        color: white;
        border-color: #DC2626;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.15);
    }

    .delete-form-p {
        margin: 0;
        display: block;
        width: 100%;
    }

    /* Empty State */
    .empty-state-p {
        grid-column: 1 / -1;
        background: white;
        border-radius: 16px;
        padding: 4rem 2rem;
        text-align: center;
        border: 2px dashed #CBD5E1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }

    .empty-icon-p {
        font-size: 3.5rem;
        color: #94A3B8;
    }

    .empty-state-p h4 {
        font-size: 1.35rem;
        font-weight: 700;
        color: #1E293B;
        margin: 0;
    }

    .empty-state-p p {
        font-size: 0.95rem;
        color: #64748B;
        max-width: 420px;
        margin: 0 0 1rem 0;
        line-height: 1.5;
    }

    /* Modal Overlay */
    .modal-overlay-p {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.7);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(8px);
        animation: fadeIn 0.25s ease;
    }

    .modal-box-p {
        background: white;
        width: 100%;
        max-width: 480px;
        padding: 2.5rem;
        border-radius: 20px;
        position: relative;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        animation: scaleUp 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        border-top: 6px solid #243A5E;
    }

    .modal-close-btn-p {
        position: absolute;
        top: 1.25rem;
        right: 1.25rem;
        background: #F1F5F9;
        color: #64748B;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .modal-close-btn-p:hover {
        background: #E2E8F0;
        color: #1E293B;
        transform: rotate(90deg);
    }

    .modal-title-p {
        margin: 0 0 1.75rem 0;
        font-size: 1.4rem;
        font-weight: 800;
        color: #1E293B;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-group-p {
        margin-bottom: 1.5rem;
    }

    .form-label-p {
        font-weight: 600;
        font-size: 0.88rem;
        color: #334155;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control-p {
        width: 100%;
        padding: 0.85rem 1.15rem;
        border-radius: 12px;
        border: 1.5px solid #CBD5E1;
        background: #F8FAFC;
        font-size: 0.95rem;
        color: #1E293B;
        font-weight: 500;
        transition: all 0.2s ease;
        outline: none;
        box-sizing: border-box;
    }

    .form-control-p:focus {
        border-color: #3B82F6;
        background: white;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
    }

    .textarea-p {
        height: 110px;
        resize: none;
        line-height: 1.5;
    }

    .modal-actions-p {
        display: flex;
        justify-content: flex-end;
        gap: 0.85rem;
        margin-top: 2.25rem;
    }

    .btn-cancel-p {
        background: #F1F5F9;
        color: #475569;
        border: 1px solid #E2E8F0;
        padding: 0.8rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-cancel-p:hover {
        background: #E2E8F0;
        color: #1E293B;
    }

    .btn-save-p {
        background: var(--primary-gradient, linear-gradient(135deg, #243A5E, #2F4F7F));
        color: white;
        border: none;
        padding: 0.8rem 1.75rem;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(36, 58, 94, 0.15);
        transition: all 0.2s ease;
    }

    .btn-save-p:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(36, 58, 94, 0.25);
    }

    /* Keyframes */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes scaleUp {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }

    .fadeInDown {
        animation: fadeInDown 0.4s ease;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-12px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
function closeModal() {
    document.getElementById('addModal').style.display = 'none';
    document.getElementById('packageForm').reset();
    document.getElementById('packageForm').action = "{{ route('admin.paket.store') }}";
    document.getElementById('formMethod').value = "POST";
    document.getElementById('modalTitle').innerHTML = "<i class='fas fa-cube'></i> Tambah Paket";
}

function editPackage(id, name, price, description) {
    document.getElementById('addModal').style.display = 'flex';
    document.getElementById('modalTitle').innerHTML = "<i class='fas fa-edit'></i> Edit Paket";
    document.getElementById('packageForm').action = "/admin/paket/" + id;
    document.getElementById('formMethod').value = "PUT";
    document.getElementById('name').value = name;
    document.getElementById('price').value = price;
    document.getElementById('description').value = description;
}
</script>
@endsection
