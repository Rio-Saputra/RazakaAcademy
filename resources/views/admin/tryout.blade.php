@extends('layouts.app')
@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h1 class="page-title" style="margin: 0;">Kelola Tryout</h1>
    <button class="btn btn-primary" onclick="document.getElementById('addModal').style.display='block'"><i class="fas fa-plus"></i> Tambah Tryout</button>
</div>

@if(session('success'))
    <div style="background: #10B981; color: white; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background: #EF4444; color: white; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
        {{ session('error') }}
    </div>
@endif

<div class="card">
    <div class="table-container">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 1px solid #E2E8F0;">
                    <th style="padding: 1rem;">Judul Tryout</th>
                    <th style="padding: 1rem;">Paket</th>
                    <th style="padding: 1rem;">Durasi (menit)</th>
                    <th style="padding: 1rem;">Jumlah Soal</th>
                    <th style="padding: 1rem;">Batas Pengerjaan</th>
                    <th style="padding: 1rem;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tryouts as $t)
                <tr style="border-bottom: 1px solid #E2E8F0;">
                    <td style="padding: 1rem;">{{ $t->title }}</td>
                    <td style="padding: 1rem;">{{ $t->package->name ?? '-' }}</td>
                    <td style="padding: 1rem;">{{ $t->duration }}</td>
                    <td style="padding: 1rem;">{{ $t->questions_count }} Soal</td>
                    <td style="padding: 1rem;">{{ $t->batas_pengerjaan }}x</td>
                    <td style="padding: 1rem;">
                        <button class="btn btn-outline-primary" onclick="generateSoal({{ $t->id }}, '{{ $t->title }}')" style="padding: 0.25rem 0.5rem;"><i class="fas fa-cogs"></i> Generate Soal dari Bank Soal</button>
                        <a href="{{ route('admin.soal.index', ['tryout_id' => $t->id]) }}" class="btn" style="background:#10B981; color:white; padding: 0.25rem 0.5rem;"><i class="fas fa-list"></i> Soal</a>
                        <button class="btn btn-primary" onclick="editTryout({{ $t->id }}, '{{ $t->title }}', {{ $t->package_id }}, {{ $t->duration }}, {{ $t->batas_pengerjaan }})" style="padding: 0.25rem 0.5rem;"><i class="fas fa-edit"></i></button>
                        <form action="{{ route('admin.tryout.destroy', $t->id) }}" method="POST" style="display:inline;" data-confirm="Yakin ingin menghapus tryout ini?" data-type="confirm" data-title="Konfirmasi Hapus">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.5rem;"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah/Edit -->
<div id="addModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999;">
    <div style="background:white; width: 400px; margin: 100px auto; padding: 2rem; border-radius: 12px; position:relative;">
        <h2 id="modalTitle" style="margin-top:0;">Tambah Tryout</h2>
        <form id="tryoutForm" action="{{ route('admin.tryout.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div style="margin-bottom: 1rem;">
                <label style="display:block; margin-bottom:0.5rem;">Judul Tryout</label>
                <input type="text" name="title" id="title" style="width: 100%; padding: 0.75rem; border: 1px solid #E2E8F0; border-radius: 8px;" required>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display:block; margin-bottom:0.5rem;">Paket</label>
                <select name="package_id" id="package_id" style="width: 100%; padding: 0.75rem; border: 1px solid #E2E8F0; border-radius: 8px;" required>
                    @foreach($packages as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; margin-bottom:0.5rem;">Durasi (menit)</label>
                <input type="number" name="duration" id="duration" style="width: 100%; padding: 0.75rem; border: 1px solid #E2E8F0; border-radius: 8px;" required>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; margin-bottom:0.5rem;">Batas Pengerjaan (kali)</label>
                <input type="number" name="batas_pengerjaan" id="batas_pengerjaan" style="width: 100%; padding: 0.75rem; border: 1px solid #E2E8F0; border-radius: 8px;" required min="1" value="1">
            </div>
            <div style="text-align: right;">
                <button type="button" class="btn" style="background:#E2E8F0; color:#333;" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Generate Soal -->
<div id="generateModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999;">
    <div style="background:white; width: 400px; margin: 100px auto; padding: 2rem; border-radius: 12px; position:relative;">
        <h2 style="margin-top:0;">Generate Soal Tryout</h2>
        <p id="generateTryoutTitle" style="color: var(--text-muted); margin-bottom: 1.5rem;"></p>
        <form action="{{ route('admin.tryout.generate') }}" method="POST">
            @csrf
            <input type="hidden" name="tryout_id" id="generateTryoutId">
            <div style="margin-bottom: 1rem;">
                <label style="display:block; margin-bottom:0.5rem;">Kategori Bank Soal</label>
                <select name="kategori_id" style="width: 100%; padding: 0.75rem; border: 1px solid #E2E8F0; border-radius: 8px;">
                    <option value="">Semua Kategori (Opsional)</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display:block; margin-bottom:0.5rem;">Jumlah Soal yang Diambil</label>
                <input type="number" name="jumlah_soal" style="width: 100%; padding: 0.75rem; border: 1px solid #E2E8F0; border-radius: 8px;" required min="1" value="20">
                <small style="color: #64748B;">Sistem akan mengambil soal secara acak dari bank soal.</small>
            </div>
            <div style="text-align: right;">
                <button type="button" class="btn" style="background:#E2E8F0; color:#333;" onclick="closeGenerateModal()">Batal</button>
                <button type="submit" class="btn btn-primary" style="background: var(--secondary);">Generate</button>
            </div>
        </form>
    </div>
</div>

<script>
function closeModal() {
    document.getElementById('addModal').style.display='none';
    document.getElementById('tryoutForm').reset();
    document.getElementById('tryoutForm').action = "{{ route('admin.tryout.store') }}";
    document.getElementById('formMethod').value = "POST";
    document.getElementById('modalTitle').innerText = "Tambah Tryout";
}

function editTryout(id, title, package_id, duration, batas) {
    document.getElementById('addModal').style.display='block';
    document.getElementById('modalTitle').innerText = "Edit Tryout";
    document.getElementById('tryoutForm').action = "/admin/tryout/" + id;
    document.getElementById('formMethod').value = "PUT";
    document.getElementById('title').value = title;
    document.getElementById('package_id').value = package_id;
    document.getElementById('duration').value = duration;
    document.getElementById('batas_pengerjaan').value = batas;
}

function generateSoal(id, title) {
    document.getElementById('generateModal').style.display = 'block';
    document.getElementById('generateTryoutId').value = id;
    document.getElementById('generateTryoutTitle').innerText = "Tryout: " + title;
}

function closeGenerateModal() {
    document.getElementById('generateModal').style.display = 'none';
}
</script>
@endsection
