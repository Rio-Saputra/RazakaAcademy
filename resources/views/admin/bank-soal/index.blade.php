@extends('layouts.app')
@section('content')

<!-- Header Pusat Bank Soal Premium -->
<div class="admin-page-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 2rem;">
    <div>
        <h1 class="page-title" style="margin: 0; font-size: 2rem; font-weight: 700; color: var(--primary);"><i class="fas fa-database"></i> Pusat Bank Soal</h1>
        <p class="subtitle" style="color: var(--text-muted); margin-top: 0.25rem;">Pusat manajemen dan kumpulan soal materi ujian Razaka Academy.</p>
    </div>
    <div style="display: flex; gap: 0.75rem; align-items: center;">
        <button onclick="openImportModal()" class="btn" style="background: rgba(16, 185, 129, 0.1); color: #10B981; border: 1.5px solid rgba(16, 185, 129, 0.4); border-radius: 12px; padding: 0.75rem 1.25rem; font-weight: 600; cursor: pointer; transition: var(--transition); display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-file-import"></i> Impor Soal PDF
        </button>
        <a href="{{ route('admin.bank-soal.create', ['kategori_id' => $kategori_id]) }}" class="btn btn-primary" style="border-radius: 12px; padding: 0.75rem 1.5rem; display: flex; align-items: center; gap: 0.5rem; text-decoration: none;">
            <i class="fas fa-plus"></i> Tambah Soal Baru
        </a>
    </div>
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
.badge-category {
    background: rgba(36, 58, 94, 0.08);
    color: var(--primary);
    padding: 0.4rem 0.8rem;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    border: 1px solid rgba(36, 58, 94, 0.15);
}
.badge-answer-benar {
    background: #D1FAE5;
    color: #065F46;
    padding: 0.4rem 0.8rem;
    border-radius: 8px;
    font-size: 0.95rem;
    font-weight: 700;
    display: inline-block;
    border: 1px solid #A7F3D0;
    text-align: center;
    width: 32px;
}
.pembahasan-premium-block {
    display: block;
    padding: 12px 15px;
    background: rgba(37,99,235,0.06) !important;
    border-left: 4px solid #2563EB !important;
    border-radius: 8px !important;
    color: #1E3A8A !important;
    font-size: 0.92rem !important;
    margin-top: 12px !important;
    font-weight: normal !important;
    line-height: 1.6 !important;
}
/* Style custom pagination */
.custom-pagination-wrapper nav {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    width: 100%;
}

@media(min-width: 640px) {
    .custom-pagination-wrapper nav {
        flex-direction: row;
        justify-content: space-between;
    }
}

/* Hide the duplicate mobile pagination container */
.custom-pagination-wrapper nav > div:first-child {
    display: none !important;
}

/* Desktop/Main pagination container */
.custom-pagination-wrapper nav > div:last-child {
    display: flex !important;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    width: 100%;
}

@media(min-width: 768px) {
    .custom-pagination-wrapper nav > div:last-child {
        flex-direction: row;
        justify-content: space-between;
    }
}

/* Showing results text style */
.custom-pagination-wrapper nav p {
    margin: 0;
    font-size: 0.92rem;
    color: var(--text-muted);
}

.custom-pagination-wrapper nav p span {
    font-weight: 600;
    color: var(--primary);
}

/* Container for pagination links */
.custom-pagination-wrapper nav span.relative.z-0 {
    display: inline-flex;
    border-radius: 10px;
    box-shadow: var(--shadow-sm);
    background: white;
    overflow: hidden;
    border: 1px solid var(--border);
}

/* Style individual pagination buttons */
.custom-pagination-wrapper nav span.relative.z-0 a,
.custom-pagination-wrapper nav span.relative.z-0 span.relative {
    padding: 10px 16px !important;
    font-size: 0.9rem !important;
    font-weight: 600 !important;
    color: var(--text) !important;
    border-right: 1px solid var(--border) !important;
    text-decoration: none !important;
    transition: var(--transition) !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    margin: 0 !important;
    border-radius: 0 !important;
}

.custom-pagination-wrapper nav span.relative.z-0 a:last-child,
.custom-pagination-wrapper nav span.relative.z-0 span.relative:last-child {
    border-right: none !important;
}

/* Hover effects */
.custom-pagination-wrapper nav span.relative.z-0 a:hover {
    background: #F1F5F9 !important;
    color: var(--primary) !important;
}

/* Active page styling */
.custom-pagination-wrapper nav span.relative.z-0 span.relative[aria-current="page"] {
    background: var(--primary-gradient) !important;
    color: white !important;
    border-color: var(--primary) !important;
}

/* Fix SVG sizing inside pagination */
.custom-pagination-wrapper svg {
    width: 16px !important;
    height: 16px !important;
    display: inline-block !important;
    vertical-align: middle !important;
}
</style>

<!-- Active Filter Notice -->
@if($kategori)
<div style="background: rgba(36, 58, 94, 0.05); border-left: 4px solid var(--primary); padding: 1.25rem 1.5rem; border-radius: 0 12px 12px 0; margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h4 style="font-size: 1.1rem; font-weight: 700; color: var(--primary);"><i class="fas fa-filter"></i> Saringan Kategori Aktif</h4>
        <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 0.15rem;">Menampilkan soal-soal Bank Soal dalam Kategori: <strong>{{ $kategori->nama_kategori }}</strong>.</p>
    </div>
    <div style="display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap;">
        <form action="{{ route('admin.bank-soal.destroy_all') }}" method="POST" style="display: inline;" data-confirm="Apakah Anda yakin ingin menghapus SEMUA soal dalam kategori {{ $kategori->nama_kategori }}? Tindakan ini bersifat permanen dan tidak dapat dibatalkan!" data-type="confirm" data-title="Konfirmasi Hapus Semua Soal">
            @csrf
            <input type="hidden" name="kategori_id" value="{{ $kategori->id }}">
            <button type="submit" class="btn" style="background: rgba(239, 68, 68, 0.1); color: #EF4444; border: 1.5px solid rgba(239, 68, 68, 0.4); border-radius: 12px; padding: 0.6rem 1.25rem; font-weight: 600; cursor: pointer; transition: var(--transition); display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-trash-alt"></i> Hapus Semua Soal
            </button>
        </form>
        <a href="{{ route('admin.bank-soal.index') }}" style="color: #475569; font-weight: 600; font-size: 0.95rem; text-decoration: none; display: flex; align-items: center; gap: 0.25rem;">
            <i class="fas fa-times-circle"></i> Bersihkan Saringan
        </a>
    </div>
</div>
@endif

<!-- Search & Filter Card -->
<div class="card" style="margin-bottom: 2rem; padding: 1.5rem;">
    <form action="{{ route('admin.bank-soal.index') }}" method="GET" style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 1.25rem; align-items: flex-end;">
        <div>
            <label class="form-label" style="font-weight: 600; color: var(--primary); margin-bottom: 0.5rem;"><i class="fas fa-folder"></i> Saring Kategori</label>
            <select name="kategori_id" class="form-control" style="cursor: pointer; appearance: none; background: #F8FAFC url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%231F2937%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E') no-repeat right 1rem top 50%; background-size: 0.65rem auto;">
                <option value="">-- Semua Kategori Bank Soal --</option>
                @foreach($kategoris as $k)
                    <option value="{{ $k->id }}" {{ $kategori_id == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label" style="font-weight: 600; color: var(--primary); margin-bottom: 0.5rem;"><i class="fas fa-search"></i> Pencarian Pertanyaan</label>
            <div style="position: relative;">
                <i class="fas fa-search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari isi pertanyaan bank soal..." class="form-control" style="padding-left: 2.75rem;">
            </div>
        </div>
        <div>
            <button type="submit" class="btn btn-primary" style="padding: 0.85rem 2rem; border-radius: 12px; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; width: 100%; justify-content: center;">
                <i class="fas fa-filter"></i> Terapkan
            </button>
        </div>
    </form>
</div>

<!-- Bank Soal List Card -->
<div class="card" style="padding: 1.75rem; border-radius: 16px;">
    <div class="table-container" style="border: none;">
        <table class="premium-admin-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid var(--border);">
                    @if(!$kategori)
                        <th style="width: 15%; padding: 1.25rem 1rem; font-weight: 700; color: var(--primary);">Kategori</th>
                    @endif
                    <th style="width: 60%; padding: 1.25rem 1rem; font-weight: 700; color: var(--primary);">Pertanyaan Bank Soal</th>
                    <th style="width: 10%; padding: 1.25rem 1rem; font-weight: 700; color: var(--primary); text-align: center;">Jawaban</th>
                    <th style="width: 15%; padding: 1.25rem 1rem; font-weight: 700; color: var(--primary); text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bankSoals as $soal)
                <tr style="border-bottom: 1px solid var(--border); transition: var(--transition);">
                    @if(!$kategori)
                        <td style="padding: 1.5rem 1rem; vertical-align: middle;">
                            <span class="badge-category">
                                <i class="fas fa-folder-open" style="margin-right: 4px;"></i> {{ $soal->kategori->nama_kategori ?? 'Umum' }}
                            </span>
                        </td>
                    @endif
                    <td style="padding: 1.5rem 1rem;">
                        <div style="background: #F8FAFC; padding: 1.25rem; border-radius: 12px; border: 1.5px solid var(--border); font-size: 0.98rem; line-height: 1.6; color: var(--text);">
                            {!! $soal->pertanyaan !!}
                        </div>
                    </td>
                    <td style="padding: 1.5rem 1rem; text-align: center; vertical-align: middle;">
                        <span class="badge-category" style="background:#243A5E; color:white; padding:0.25rem 0.5rem; border-radius:5px; font-weight:700; font-size:0.75rem; display:block; margin-bottom:0.5rem;">
                            {{ $soal->jenis_soal ?? 'TWK' }}
                        </span>
                        @if(($soal->jenis_soal ?? 'TWK') === 'TKP')
                            <span class="badge-answer-benar" style="background:#FEF3C7; color:#92400E; font-size:0.75rem; padding:0.25rem 0.5rem; display:block; white-space:nowrap;">
                                A={{ $soal->option_points['A'] ?? 5 }}, B={{ $soal->option_points['B'] ?? 4 }}, C={{ $soal->option_points['C'] ?? 3 }}, D={{ $soal->option_points['D'] ?? 2 }}, E={{ $soal->option_points['E'] ?? 1 }}
                            </span>
                        @else
                            <span class="badge-answer-benar">{{ strtoupper($soal->jawaban_benar) }}</span>
                        @endif
                    </td>
                    <td style="padding: 1.5rem 1rem; text-align: right; vertical-align: middle;">
                        <div style="display: flex; gap: 0.5rem; justify-content: flex-end; align-items: center;">
                            <a href="{{ route('admin.bank-soal.edit', $soal->id) }}" class="btn btn-secondary" style="padding: 0.5rem 0.9rem; border-radius: 8px; font-size: 0.88rem; display: flex; align-items: center; gap: 0.25rem;">
                                <i class="fas fa-edit" style="color: var(--text-muted);"></i> Edit
                            </a>
                            <form action="{{ route('admin.bank-soal.destroy', $soal->id) }}" method="POST" style="display:inline;" data-confirm="Apakah Anda yakin ingin menghapus data soal ini?" data-type="confirm" data-title="Konfirmasi Hapus Soal">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary" style="padding: 0.5rem 0.9rem; border-radius: 8px; border-color: #FEE2E2; background: #FEF2F2; color: #EF4444; font-size: 0.88rem; cursor: pointer; display: flex; align-items: center; gap: 0.25rem;">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ !$kategori ? 4 : 3 }}" style="text-align:center; padding: 5rem 2rem; color: var(--text-muted);">
                        <div style="background: rgba(36, 58, 94, 0.05); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                            <i class="fas fa-file-excel" style="font-size: 2.5rem; color: #94A3B8;"></i>
                        </div>
                        <h4 style="font-size: 1.25rem; font-weight: 700; color: var(--text); margin-bottom: 0.5rem;">Belum Ada Pertanyaan Bank Soal</h4>
                        <p style="font-size: 0.95rem; max-width: 450px; margin: 0 auto;">Daftar bank soal kosong. Tambahkan soal baru atau gunakan fitur "Impor Soal PDF" untuk mengisi bank soal dengan cepat.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Section -->
    @if($bankSoals->hasPages())
    <div class="custom-pagination-wrapper" style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border);">
        {{ $bankSoals->links() }}
    </div>
    @endif
</div>

<!-- Modal Impor PDF Premium -->
<div id="modal-import-pdf" class="modal-backdrop-premium" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); backdrop-filter:blur(6px); z-index:9000; align-items:center; justify-content:center; opacity:0; transition:opacity 0.3s ease;">
    <div class="modal-content-premium" style="background:white; border-radius:20px; width:100%; max-width:600px; box-shadow:0 25px 50px -12px rgba(0, 0, 0, 0.25); overflow:hidden; border:1px solid rgba(226, 232, 240, 0.8); transform:scale(0.9); transition:transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);">
        <div style="background: var(--primary-gradient); padding: 1.5rem 2rem; display: flex; justify-content: space-between; align-items: center; color: white;">
            <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 0.75rem;"><i class="fas fa-file-import"></i> Impor Soal dari File PDF</h3>
            <button onclick="closeImportModal()" style="background:none; border:none; color:white; font-size:1.5rem; cursor:pointer; line-height: 1;"><i class="fas fa-times"></i></button>
        </div>
        <form action="{{ route('admin.bank-soal.import_pdf') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="padding: 2rem;">
                <div class="format-guide-box" style="background: #F8FAFC; border-radius: 12px; border-left: 4px solid #3B82F6; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem;">
                    <h4 style="font-size: 0.95rem; font-weight: 700; color: #1E3A8A; margin-bottom: 0.5rem;"><i class="fas fa-info-circle"></i> Panduan Format Impor PDF:</h4>
                    <ul style="font-size: 0.88rem; color: #1E40AF; padding-left: 1.25rem; line-height: 1.6;">
                        <li>Format soal harus menggunakan nomor terbungkus kurung siku: <strong>[1]</strong>, <strong>[2]</strong>, dst.</li>
                        <li>Opsi pilihan ganda diawali dengan: <strong>A.</strong>, <strong>B.</strong>, <strong>C.</strong>, <strong>D.</strong></li>
                        <li>Menentukan Kunci Jawaban di baris baru: <strong>Kunci: A</strong></li>
                        <li>Untuk menyertakan pembahasan, tulis di bagian bawah: <strong>Pembahasan: isi penjelasan...</strong></li>
                    </ul>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; color: var(--primary); margin-bottom: 0.5rem;">Pilih Kategori Bank Soal <span style="color: #EF4444;">*</span></label>
                    <select name="kategori_id" class="form-control" required style="cursor: pointer;">
                        <option value="" disabled selected>-- Pilih Kategori Tujuan --</option>
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id }}" {{ $kategori_id == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label" style="font-weight: 600; color: var(--primary); margin-bottom: 0.5rem;">Pilih File PDF Ujian <span style="color: #EF4444;">*</span></label>
                    <input type="file" name="pdf_file" class="form-control" required accept=".pdf" style="padding: 0.65rem 1rem;">
                </div>

                <div style="background: #FFFBEB; border: 1px solid #FDE68A; padding: 1rem; border-radius: 12px; display: flex; align-items: center; gap: 0.75rem;">
                    <input type="checkbox" name="delete_old" value="1" id="delete_old" style="width: 18px; height: 18px; cursor: pointer;">
                    <label for="delete_old" style="font-size: 0.92rem; color: #92400E; font-weight: 600; cursor: pointer; user-select: none;">Hapus semua soal lama di kategori yang dipilih</label>
                </div>
            </div>
            <div style="padding: 1.5rem 2rem; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 1rem; background: #F8FAFC;">
                <button type="button" onclick="closeImportModal()" class="btn btn-secondary" style="border-radius: 10px; padding: 0.75rem 1.5rem;">Batal</button>
                <button type="submit" class="btn btn-primary" style="border-radius: 10px; padding: 0.75rem 2rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-file-upload"></i> Proses Impor PDF
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openImportModal() {
        const modal = document.getElementById('modal-import-pdf');
        modal.style.display = 'flex';
        setTimeout(() => modal.style.opacity = '1', 10);
    }

    function closeImportModal() {
        const modal = document.getElementById('modal-import-pdf');
        modal.style.opacity = '0';
        setTimeout(() => modal.style.display = 'none', 300);
    }
</script>
@endpush
