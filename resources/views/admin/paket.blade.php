@extends('layouts.app')
@section('content')

<div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h1 class="page-title" style="margin: 0;">Kelola Paket</h1>
        <p class="subtitle">Manajemen paket tryout premium.</p>
    </div>
    <button class="btn btn-primary" onclick="document.getElementById('addModal').style.display='flex'" style="background: white; color: var(--primary);"><i class="fas fa-plus"></i> Tambah Paket</button>
</div>

@if(session('success'))
    <div class="badge badge-success" style="padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; display: block; font-size: 1rem;">
        <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i> {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nama Paket</th>
                    <th>Harga</th>
                    <th>Deskripsi</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($packages as $p)
                <tr>
                    <td style="font-weight: 600;">{{ $p->name }}</td>
                    <td style="color: #10B981; font-weight: 600;">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                    <td><span style="color:var(--text-muted);">{{ $p->description }}</span></td>
                    <td style="text-align: right;">
                        <button class="btn btn-secondary" onclick="editPackage({{ $p->id }}, '{{ $p->name }}', {{ $p->price }}, '{{ $p->description }}')" style="padding: 0.5rem 1rem;"><i class="fas fa-edit"></i> Edit</button>
                        <form action="{{ route('admin.paket.destroy', $p->id) }}" method="POST" style="display:inline;" data-confirm="Yakin ingin menghapus paket ini?" data-type="confirm" data-title="Konfirmasi Hapus">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 0.5rem 1rem;"><i class="fas fa-trash"></i> Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah/Edit -->
<div id="addModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div style="background:white; width: 450px; padding: 2.5rem; border-radius: var(--radius); position:relative; box-shadow: var(--shadow-lg); animation: slideUp 0.3s ease;">
        <h2 id="modalTitle" style="margin-top:0; margin-bottom: 1.5rem; font-weight: 700;">Tambah Paket</h2>
        <form id="packageForm" action="{{ route('admin.paket.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div class="form-group">
                <label class="form-label">Nama Paket</label>
                <input type="text" name="name" id="name" class="form-control" required placeholder="Contoh: Paket Premium UTBK">
            </div>
            <div class="form-group">
                <label class="form-label">Harga (Rp)</label>
                <input type="number" name="price" id="price" class="form-control" required placeholder="Contoh: 50000">
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" id="description" class="form-control" style="height: 100px; resize: none;" placeholder="Masukkan deskripsi paket..."></textarea>
            </div>
            <div style="text-align: right; margin-top: 2rem; display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Paket</button>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes slideUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<script>
function closeModal() {
    document.getElementById('addModal').style.display='none';
    document.getElementById('packageForm').reset();
    document.getElementById('packageForm').action = "{{ route('admin.paket.store') }}";
    document.getElementById('formMethod').value = "POST";
    document.getElementById('modalTitle').innerText = "Tambah Paket";
}

function editPackage(id, name, price, description) {
    document.getElementById('addModal').style.display='flex';
    document.getElementById('modalTitle').innerText = "Edit Paket";
    document.getElementById('packageForm').action = "/admin/paket/" + id;
    document.getElementById('formMethod').value = "PUT";
    document.getElementById('name').value = name;
    document.getElementById('price').value = price;
    document.getElementById('description').value = description;
}
</script>
@endsection
