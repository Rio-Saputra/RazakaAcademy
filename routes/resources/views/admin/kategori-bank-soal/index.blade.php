@extends('layouts.app')
@section('content')

<div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h1 class="page-title" style="margin: 0;">Kategori Bank Soal</h1>
        <p class="subtitle">Manajemen kategori untuk mengelompokkan soal-soal tryout.</p>
    </div>
    <button onclick="document.getElementById('modal-tambah-kategori').style.display='flex'; setTimeout(()=>document.getElementById('modal-tambah-kategori').style.opacity='1', 10);" class="btn btn-primary" style="background: white; color: var(--primary);"><i class="fas fa-plus"></i> Tambah Kategori</button>
</div>

<div class="workspace-tabs" style="display: flex; gap: 1rem; margin-bottom: 2.5rem; border-bottom: 2px solid var(--border); padding-bottom: 0.75rem;">
    <a href="{{ route('admin.kategori-bank-soal.index') }}" class="tab-item active-tab" style="font-size: 1.1rem; font-weight: 600; padding: 0.75rem 1.5rem; border-radius: 12px; transition: var(--transition); display: flex; align-items: center; gap: 0.5rem; text-decoration: none;">
        <i class="fas fa-folder"></i> Kategori Bank Soal
    </a>
    <a href="{{ route('admin.bank-soal.index') }}" class="tab-item" style="font-size: 1.1rem; font-weight: 600; padding: 0.75rem 1.5rem; color: var(--text-muted); border-radius: 12px; transition: var(--transition); display: flex; align-items: center; gap: 0.5rem; text-decoration: none;">
        <i class="fas fa-database"></i> Kumpulan Bank Soal
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

<div class="stats-grid">
    @forelse($kategoris as $kategori)
    <div class="card" style="margin-bottom: 0; display:flex; flex-direction:column; padding: 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
            <div class="stat-icon" style="background: var(--primary-gradient); color: white;">
                <i class="fas fa-folder-open"></i>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button onclick="editKategori({{ $kategori->id }}, '{{ addslashes($kategori->nama_kategori) }}', '{{ addslashes($kategori->deskripsi) }}')" class="btn btn-secondary" style="padding: 0.5rem; border-radius: 8px;"><i class="fas fa-edit" style="color: var(--text-muted);"></i></button>
                <form action="{{ route('admin.kategori-bank-soal.destroy', $kategori->id) }}" method="POST" style="display:inline;" data-confirm="Hapus kategori ini? Semua soal di dalamnya juga akan terhapus." data-type="confirm" data-title="Konfirmasi Hapus">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-secondary" style="padding: 0.5rem; border-radius: 8px; border-color: #FEE2E2; background: #FEF2F2;"><i class="fas fa-trash-alt" style="color: #EF4444;"></i></button>
                </form>
            </div>
        </div>
        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem; color: var(--text);">{{ $kategori->nama_kategori }}</h3>
        <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1.5rem; flex-grow:1;">{{ $kategori->deskripsi ?: 'Tidak ada deskripsi' }}</p>
        
        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border); padding-top: 1rem;">
            <span style="font-size: 0.875rem; font-weight: 600; color: var(--primary);"><i class="fas fa-file-alt"></i> {{ $kategori->bank_soals_count }} Soal</span>
            <a href="{{ route('admin.bank-soal.index', ['kategori_id' => $kategori->id]) }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">Kelola Soal <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
    @empty
    <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; background: var(--card); border-radius: var(--radius); border: 1px solid var(--border);">
        <i class="fas fa-folder-open" style="font-size: 3rem; color: #CBD5E1; margin-bottom: 1rem;"></i>
        <p style="color: var(--text-muted);">Belum ada kategori bank soal.</p>
    </div>
    @endforelse
</div>

<!-- Modal Tambah Kategori -->
<div id="modal-tambah-kategori" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); z-index:9000; align-items:center; justify-content:center; opacity:0; transition:opacity 0.3s ease;">
    <div style="background:white; border-radius:1rem; width:100%; max-width:500px; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="padding: 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.25rem; font-weight: 700;">Tambah Kategori</h3>
            <button onclick="document.getElementById('modal-tambah-kategori').style.opacity='0'; setTimeout(()=>document.getElementById('modal-tambah-kategori').style.display='none', 300);" style="background:none; border:none; font-size:1.25rem; color:var(--text-muted); cursor:pointer;"><i class="fas fa-times"></i></button>
        </div>
        <form action="{{ route('admin.kategori-bank-soal.store') }}" method="POST">
            @csrf
            <div style="padding: 1.5rem;">
                <div class="form-group">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="nama_kategori" class="form-control" required placeholder="Mis. TPS, Literasi, dsb.">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3" placeholder="Opsional"></textarea>
                </div>
            </div>
            <div style="padding: 1.5rem; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 1rem;">
                <button type="button" onclick="document.getElementById('modal-tambah-kategori').style.opacity='0'; setTimeout(()=>document.getElementById('modal-tambah-kategori').style.display='none', 300);" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Kategori -->
<div id="modal-edit-kategori" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); z-index:9000; align-items:center; justify-content:center; opacity:0; transition:opacity 0.3s ease;">
    <div style="background:white; border-radius:1rem; width:100%; max-width:500px; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="padding: 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.25rem; font-weight: 700;">Edit Kategori</h3>
            <button onclick="document.getElementById('modal-edit-kategori').style.opacity='0'; setTimeout(()=>document.getElementById('modal-edit-kategori').style.display='none', 300);" style="background:none; border:none; font-size:1.25rem; color:var(--text-muted); cursor:pointer;"><i class="fas fa-times"></i></button>
        </div>
        <form id="form-edit-kategori" method="POST">
            @csrf
            @method('PUT')
            <div style="padding: 1.5rem;">
                <div class="form-group">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="edit-nama-kategori" class="form-control" required>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" id="edit-deskripsi" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div style="padding: 1.5rem; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 1rem;">
                <button type="button" onclick="document.getElementById('modal-edit-kategori').style.opacity='0'; setTimeout(()=>document.getElementById('modal-edit-kategori').style.display='none', 300);" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function editKategori(id, nama, deskripsi) {
    document.getElementById('edit-nama-kategori').value = nama;
    document.getElementById('edit-deskripsi').value = deskripsi;
    document.getElementById('form-edit-kategori').action = `/admin/kategori-bank-soal/${id}`;
    
    const modal = document.getElementById('modal-edit-kategori');
    modal.style.display = 'flex';
    setTimeout(() => modal.style.opacity = '1', 10);
}
</script>
@endpush
