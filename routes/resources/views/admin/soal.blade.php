@extends('layouts.app')
@section('content')

<!-- Header Kelola Soal Premium -->
<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title"><i class="fas fa-list-ol"></i> Kelola Soal</h1>
        <p class="admin-page-subtitle">Manajemen daftar pertanyaan, pilihan ganda, dan kunci jawaban untuk setiap tryout.</p>
    </div>
    @if($tryout_id)
    <div class="admin-action-buttons-group">
        <a href="{{ route('admin.soal.export_pdf', ['tryout_id' => $tryout_id]) }}" class="btn btn-export-pdf-p">
            <i class="fas fa-file-pdf"></i> Ekspor PDF
        </a>
        <button type="button" class="btn btn-select-bank-soal-p" onclick="openSelectBankModal()">
            <i class="fas fa-database"></i> Pilih dari Bank Soal
        </button>
        <a href="{{ route('admin.soal.create', ['tryout_id' => $tryout_id]) }}" class="btn btn-add-soal-p">
            <i class="fas fa-plus"></i> Tambah Soal Baru
        </a>
    </div>
    @endif
</div>

<div class="workspace-tabs" style="display: flex; gap: 1rem; margin-bottom: 2.5rem; border-bottom: 2px solid var(--border); padding-bottom: 0.75rem;">
    <a href="{{ route('admin.tryout.index') }}" class="tab-item" style="font-size: 1.1rem; font-weight: 600; padding: 0.75rem 1.5rem; color: var(--text-muted); border-radius: 12px; transition: var(--transition); display: flex; align-items: center; gap: 0.5rem; text-decoration: none;">
        <i class="fas fa-clock"></i> Ujian Tryout
    </a>
    <a href="{{ route('admin.soal.index') }}" class="tab-item active-tab" style="font-size: 1.1rem; font-weight: 600; padding: 0.75rem 1.5rem; border-radius: 12px; transition: var(--transition); display: flex; align-items: center; gap: 0.5rem; text-decoration: none;">
        <i class="fas fa-file-alt"></i> Daftar Soal Tryout
    </a>
</div>

<!-- Filter Selection Card -->
<div class="filter-card-premium">
    <form action="{{ route('admin.soal.index') }}" method="GET">
        <div>
            <label class="filter-label-p">
                <i class="fas fa-filter"></i> Filter Berdasarkan Paket Tryout
            </label>
            <div style="position: relative;">
                <select name="tryout_id" id="tryout_id" onchange="this.form.submit()" class="form-control select-search">
                    <option value="">-- Silakan Pilih Tryout --</option>
                    @foreach($tryouts as $t)
                        <option value="{{ $t->id }}" {{ $tryout_id == $t->id ? 'selected' : '' }}>{{ $t->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
</div>

@if($tryout_id)
<!-- Question Breakdown Stats -->
<div class="stats-grid-p" style="margin-bottom: 2rem;">
    <!-- Total Soal -->
    <div class="stat-card-p">
        <div class="stat-icon-wrapper-p color-blue">
            <i class="fas fa-file-alt"></i>
        </div>
        <div class="stat-info-p">
            <span class="stat-value-p">{{ $questions->count() }} / 110</span>
            <span class="stat-label-p">Total Soal (Target: 110)</span>
        </div>
    </div>
    <!-- Soal TWK -->
    <div class="stat-card-p">
        <div class="stat-icon-wrapper-p color-emerald">
            <i class="fas fa-landmark"></i>
        </div>
        <div class="stat-info-p">
            <span class="stat-value-p">{{ $questions->where('jenis_soal', 'TWK')->count() }} / 30</span>
            <span class="stat-label-p">Soal TWK (Target: 30)</span>
        </div>
    </div>
    <!-- Soal TIU -->
    <div class="stat-card-p">
        <div class="stat-icon-wrapper-p color-amber">
            <i class="fas fa-brain"></i>
        </div>
        <div class="stat-info-p">
            <span class="stat-value-p">{{ $questions->where('jenis_soal', 'TIU')->count() }} / 35</span>
            <span class="stat-label-p">Soal TIU (Target: 35)</span>
        </div>
    </div>
    <!-- Soal TKP -->
    <div class="stat-card-p">
        <div class="stat-icon-wrapper-p color-indigo">
            <i class="fas fa-user-tie"></i>
        </div>
        <div class="stat-info-p">
            <span class="stat-value-p">{{ $questions->where('jenis_soal', 'TKP')->count() }} / 45</span>
            <span class="stat-label-p">Soal TKP (Target: 45)</span>
        </div>
    </div>
</div>
@endif

<!-- Question List Workspace -->
@if($tryout_id)
<div class="soal-list-container">
    @forelse($questions as $index => $q)
    <div class="soal-card-premium">
        <!-- Card Header -->
        <div class="soal-card-header" style="display:flex; justify-content:space-between; align-items:center;">
            <span class="badge-soal-num">Soal #{{ $loop->iteration }}</span>
            <span class="badge-category-p" style="background:#243A5E; color:white; padding: 0.35rem 0.75rem; border-radius: 50px; font-weight: 700; font-size: 0.8rem; margin-right: auto; margin-left: 0.75rem;">
                Kategori: {{ $q->jenis_soal ?? 'TWK' }}
            </span>
            @if(($q->jenis_soal ?? 'TWK') === 'TKP')
                <span class="badge-kunci-jawaban" style="background:#FEF3C7; color:#92400E;">
                    <i class="fas fa-star"></i> Poin TKP: A={{ $q->option_points['A'] ?? 5 }}, B={{ $q->option_points['B'] ?? 4 }}, C={{ $q->option_points['C'] ?? 3 }}, D={{ $q->option_points['D'] ?? 2 }}, E={{ $q->option_points['E'] ?? 1 }}
                </span>
            @else
                <span class="badge-kunci-jawaban">
                    <i class="fas fa-check-circle"></i> Kunci Jawaban: {{ strtoupper($q->correct_answer) }}
                </span>
            @endif
        </div>

        <!-- Card Body -->
        <div class="soal-card-body">
            <div class="soal-text-box">
                {!! $q->question_text !!}
            </div>

            <!-- Options Grid -->
            <div class="soal-options-grid">
                @php
                    $previewOptions = ['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D'];
                    if (!empty($q->option_e)) {
                        $previewOptions['e'] = 'E';
                    }
                @endphp
                @foreach($previewOptions as $key => $letter)
                <div class="soal-option-item {{ ($q->jenis_soal ?? 'TWK') !== 'TKP' && strtoupper($q->correct_answer) === $letter ? 'correct-option' : '' }}">
                    <div class="soal-option-letter">{{ $letter }}</div>
                    <div class="soal-option-text">
                        {!! $q->{'formatted_option_'.$key} !!}
                        @if(($q->jenis_soal ?? 'TWK') === 'TKP')
                            <span style="font-weight:700; color:#3B82F6; font-size:0.85rem; margin-left:0.5rem;">(Skor: {{ $q->option_points[strtoupper($key)] ?? 0 }} pt)</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Card Footer -->
        <div class="soal-card-footer">
            <a href="{{ route('admin.soal.edit', $q->id) }}" class="btn btn-edit-soal-p">
                <i class="fas fa-pencil-alt"></i> Edit Soal
            </a>
            <form action="{{ route('admin.soal.destroy', $q->id) }}" method="POST" style="display:inline;" data-confirm="Apakah Anda yakin ingin menghapus soal ini?" data-type="confirm" data-title="Konfirmasi Hapus Soal">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-delete-soal-p">
                    <i class="fas fa-trash-alt"></i> Hapus
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="empty-state-card">
        <i class="fas fa-file-excel empty-state-icon"></i>
        <h3 class="empty-state-title">Belum Ada Soal</h3>
        <p class="empty-state-desc">Tryout ini belum memiliki daftar pertanyaan. Klik tombol "Tambah Soal Baru" di kanan atas untuk mulai membuat soal.</p>
        <a href="{{ route('admin.soal.create', ['tryout_id' => $tryout_id]) }}" class="btn btn-primary" style="margin-top: 1.5rem;">
            <i class="fas fa-plus"></i> Buat Soal Pertama
        </a>
    </div>
    @endforelse
</div>
@else
<div class="empty-state-card">
    <i class="fas fa-hand-pointer empty-state-icon" style="color: #64748B;"></i>
    <h3 class="empty-state-title">Pilih Tryout Terlebih Dahulu</h3>
    <p class="empty-state-desc">Silakan pilih salah satu paket tryout dari dropdown filter di atas untuk menampilkan dan mengelola data soal.</p>
</div>
@endif

<style>
    /* Statistics Cards */
    .stats-grid-p {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.25rem;
    }

    .stat-card-p {
        background: white;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        border: 1px solid #E2E8F0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.006);
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-align: left;
    }

    .stat-card-p:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.08);
        border-color: rgba(36, 58, 94, 0.15);
    }

    .stat-icon-wrapper-p {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
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
        background: #FFFBEB;
        color: #D97706;
    }

    .color-indigo {
        background: #EEF2FF;
        color: #4F46E5;
    }

    .stat-info-p {
        display: flex;
        flex-direction: column;
    }

    .stat-value-p {
        font-size: 1.35rem;
        font-weight: 700;
        color: #1E293B;
        line-height: 1.2;
    }

    .stat-label-p {
        font-size: 0.8rem;
        font-weight: 500;
        color: #64748B;
        margin-top: 0.15rem;
    }

    /* Action Buttons Group & Premium Buttons */
    .admin-action-buttons-group {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .btn-export-pdf-p {
        background: rgba(255, 255, 255, 0.15) !important;
        color: white !important;
        border: 1px solid rgba(255, 255, 255, 0.35) !important;
        backdrop-filter: blur(8px);
        padding: 0.85rem 1.5rem !important;
        border-radius: 14px !important;
        font-size: 0.95rem !important;
        font-weight: 600 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: var(--transition) !important;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-export-pdf-p:hover {
        background: white !important;
        color: var(--primary) !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 20px rgba(255, 255, 255, 0.25) !important;
    }

    .btn-select-bank-soal-p {
        background: rgba(59, 130, 246, 0.2) !important;
        color: #93C5FD !important;
        border: 1px solid rgba(59, 130, 246, 0.4) !important;
        backdrop-filter: blur(8px);
        padding: 0.85rem 1.5rem !important;
        border-radius: 14px !important;
        font-size: 0.95rem !important;
        font-weight: 600 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: var(--transition) !important;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .btn-select-bank-soal-p:hover {
        background: #3B82F6 !important;
        color: white !important;
        border-color: #3B82F6 !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.25) !important;
    }

    .active-tab {
        background: var(--primary-gradient) !important;
        color: white !important;
        box-shadow: var(--shadow-sm);
    }
    .tab-item:not(.active-tab):hover {
        background: #E2E8F0;
        color: var(--primary);
    }

    /* Premium Modal Backdrop & Content */
    .modal-backdrop-premium {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(8px);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .modal-backdrop-premium.active {
        opacity: 1;
    }

    .modal-content-premium {
        background: white;
        border-radius: 20px;
        width: 100%;
        max-width: 650px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        overflow: hidden;
        border: 1px solid rgba(226, 232, 240, 0.8);
        transform: scale(0.9);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    
    .modal-backdrop-premium.active .modal-content-premium {
        transform: scale(1);
    }

    .modal-header-p {
        background: var(--primary-gradient);
        padding: 1.5rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: white;
    }

    .modal-title-p {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .btn-close-modal {
        background: none;
        border: none;
        color: white;
        font-size: 1.75rem;
        cursor: pointer;
        line-height: 1;
        opacity: 0.8;
        transition: opacity 0.2s;
    }

    .btn-close-modal:hover {
        opacity: 1;
    }

    .modal-body-p {
        padding: 2rem;
        max-height: 60vh;
        overflow-y: auto;
        text-align: left;
    }

    /* Format Guide Box */
    .format-guide-box {
        background: #F8FAFC;
        border-radius: 12px;
        border-left: 4px solid #3B82F6;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
    }

    .guide-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1E3A8A;
        margin-top: 0;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .guide-subtitle {
        font-size: 0.85rem;
        color: #475569;
        margin-bottom: 0.75rem;
        line-height: 1.5;
    }

    .guide-pre {
        background: #0F172A;
        color: #38BDF8;
        padding: 1rem;
        border-radius: 8px;
        font-family: 'Courier New', Courier, monospace;
        font-size: 0.8rem;
        line-height: 1.5;
        margin: 0;
        overflow-x: auto;
        text-align: left;
    }

    /* File Uploader */
    .file-uploader-box {
        margin-bottom: 1.5rem;
    }

    .file-uploader-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 2px dashed #CBD5E1;
        border-radius: 14px;
        padding: 2rem 1.5rem;
        background: #FAFAFA;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
    }

    .file-uploader-label:hover {
        border-color: #3B82F6;
        background: #EFF6FF;
    }

    .file-uploader-icon {
        font-size: 2.5rem;
        color: #94A3B8;
        margin-bottom: 0.75rem;
        transition: color 0.2s;
    }

    .file-uploader-label:hover .file-uploader-icon {
        color: #3B82F6;
    }

    .file-uploader-text {
        font-weight: 600;
        color: #334155;
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
    }

    .file-uploader-info {
        font-size: 0.8rem;
        color: #64748B;
    }

    .file-uploader-input {
        display: none;
    }

    /* Premium Checkbox Styling */
    .delete-old-option {
        background: #FFFBEB;
        border: 1px solid #FDE68A;
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        text-align: left;
    }

    .checkbox-container-premium {
        display: flex;
        position: relative;
        cursor: pointer;
        user-select: none;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .checkbox-container-premium input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .checkmark-premium {
        height: 20px;
        width: 20px;
        background-color: #FFF;
        border: 2px solid #D1D5DB;
        border-radius: 6px;
        flex-shrink: 0;
        position: relative;
        transition: all 0.2s ease;
        margin-top: 2px;
    }

    .checkbox-container-premium:hover input ~ .checkmark-premium {
        border-color: #F59E0B;
        background-color: #FFFDF5;
    }

    .checkbox-container-premium input:checked ~ .checkmark-premium {
        background-color: #F59E0B;
        border-color: #F59E0B;
    }

    .checkmark-premium:after {
        content: "";
        position: absolute;
        display: none;
    }

    .checkbox-container-premium input:checked ~ .checkmark-premium:after {
        display: block;
    }

    .checkbox-container-premium .checkmark-premium:after {
        left: 6px;
        top: 2px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

    .checkbox-text {
        font-size: 0.95rem;
        color: #78350F;
        line-height: 1.4;
    }

    .checkbox-warning {
        font-size: 0.8rem;
        color: #B45309;
        display: inline-block;
        margin-top: 0.25rem;
    }

    /* Modal Footer */
    .modal-footer-p {
        background: #F8FAFC;
        padding: 1.25rem 2rem;
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        border-top: 1px solid #E2E8F0;
    }

    .btn-secondary-p {
        background: #E2E8F0 !important;
        color: #475569 !important;
        border: 1px solid #CBD5E1 !important;
        padding: 0.75rem 1.5rem !important;
        border-radius: 12px !important;
        font-weight: 600 !important;
        font-size: 0.9rem !important;
        transition: all 0.2s !important;
    }

    .btn-secondary-p:hover {
        background: #CBD5E1 !important;
        color: #334155 !important;
    }

    .btn-primary-p {
        background: var(--primary-gradient) !important;
        color: white !important;
        border: none !important;
        padding: 0.75rem 1.5rem !important;
        border-radius: 12px !important;
        font-weight: 600 !important;
        font-size: 0.9rem !important;
        box-shadow: 0 4px 12px rgba(36, 58, 94, 0.15) !important;
        transition: all 0.2s !important;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .btn-primary-p:hover {
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 15px rgba(36, 58, 94, 0.25) !important;
    }

    /* Gradient Banner Override */
    .admin-page-header {
        background: var(--primary-gradient);
        border-radius: var(--radius);
        padding: 2.25rem 2.5rem;
        color: white;
        margin-bottom: 2.25rem;
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
        margin-bottom: 0.35rem;
        color: white;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .admin-page-subtitle {
        color: rgba(255, 255, 255, 0.85);
        font-size: 1rem;
        font-weight: 400;
    }

    .btn-add-soal-p {
        background: rgba(255, 255, 255, 0.15) !important;
        color: white !important;
        border: 1px solid rgba(255, 255, 255, 0.35) !important;
        backdrop-filter: blur(8px);
        padding: 0.85rem 1.75rem !important;
        border-radius: 14px !important;
        font-size: 0.95rem !important;
        font-weight: 600 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        transition: var(--transition) !important;
    }

    .btn-add-soal-p:hover {
        background: white !important;
        color: var(--primary) !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 20px rgba(255, 255, 255, 0.25) !important;
    }

    /* Filter Card Styling */
    .filter-card-premium {
        border-radius: var(--radius);
        background: white;
        padding: 1.75rem;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        margin-bottom: 2rem;
        position: relative;
    }

    .filter-card-premium::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: var(--primary-gradient);
        border-radius: var(--radius) 0 0 var(--radius);
    }

    .filter-label-p {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Questions Wrapper & Cards */
    .soal-list-container {
        display: flex;
        flex-direction: column;
        gap: 1.75rem;
    }

    .soal-card-premium {
        background: white;
        border-radius: var(--radius);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
        overflow: hidden;
        position: relative;
    }

    .soal-card-premium:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-hover);
        border-color: rgba(36, 58, 94, 0.2);
    }

    .soal-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem 1.75rem;
        background: #F8FAFC;
        border-bottom: 1px solid var(--border);
    }

    .badge-soal-num {
        background: var(--primary-gradient);
        color: white;
        padding: 0.45rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 10px rgba(36, 58, 94, 0.15);
    }

    .badge-kunci-jawaban {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        color: white;
        padding: 0.45rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2);
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .soal-card-body {
        padding: 1.75rem;
    }

    .soal-text-box {
        font-size: 1.05rem;
        color: var(--text);
        line-height: 1.7;
        margin-bottom: 1.5rem;
        padding: 1rem 1.25rem;
        background: #F8FAFC;
        border-radius: 12px;
        border-left: 4px solid var(--border);
    }

    /* Options Grid (2x2) */
    .soal-options-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-top: 1.25rem;
    }

    .soal-option-item {
        display: flex;
        align-items: flex-start;
        gap: 0.85rem;
        padding: 0.9rem 1.25rem;
        background: #FFFFFF;
        border: 1.5px solid var(--border);
        border-radius: 12px;
        font-size: 0.95rem;
        color: var(--text);
        transition: var(--transition);
    }

    .soal-option-letter {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #E2E8F0;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.8rem;
        flex-shrink: 0;
    }

    /* Highlight Correct Option */
    .soal-option-item.correct-option {
        background: #ECFDF5;
        border-color: #34D399;
        box-shadow: 0 4px 12px rgba(52, 211, 153, 0.1);
    }

    .soal-option-item.correct-option .soal-option-letter {
        background: #10B981;
        color: white;
    }

    /* Card Footer Actions */
    .soal-card-footer {
        padding: 1.25rem 1.75rem;
        background: #FFFFFF;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: flex-end;
        gap: 0.85rem;
    }

    .btn-edit-soal-p {
        background: #EFF6FF !important;
        color: #2563EB !important;
        border: 1px solid #BFDBFE !important;
        padding: 0.65rem 1.25rem !important;
        border-radius: 12px !important;
        font-size: 0.9rem !important;
    }

    .btn-edit-soal-p:hover {
        background: #2563EB !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2) !important;
    }

    .btn-delete-soal-p {
        background: #FEF2F2 !important;
        color: #DC2626 !important;
        border: 1px solid #FCA5A5 !important;
        padding: 0.65rem 1.25rem !important;
        border-radius: 12px !important;
        font-size: 0.9rem !important;
    }

    .btn-delete-soal-p:hover {
        background: #DC2626 !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2) !important;
    }

    /* Empty States */
    .empty-state-card {
        text-align: center;
        padding: 4.5rem 2rem;
        background: white;
        border-radius: var(--radius);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-sm);
    }

    .empty-state-icon {
        font-size: 4rem;
        color: #CBD5E1;
        margin-bottom: 1.5rem;
        animation: floatIcon 3s ease-in-out infinite;
        display: block;
    }

    @keyframes floatIcon {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .empty-state-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .empty-state-desc {
        color: var(--text-muted);
        font-size: 1rem;
        max-width: 450px;
        margin: 0 auto;
    }

    @media (max-width: 768px) {
        .admin-page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1.25rem;
            padding: 1.75rem;
        }

        .btn-add-soal-p {
            width: 100%;
            justify-content: center;
        }

        .soal-options-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Styles for the Select Bank Soal Modal */
    .select-soal-modal {
        max-width: 850px !important; /* wider for comfortable reading */
    }

    .modal-filter-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.25rem;
    }

    .modal-search-wrapper {
        position: relative;
    }

    .modal-search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94A3B8;
    }

    .modal-search-input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border: 2px solid #E2E8F0;
        border-radius: 12px;
        font-size: 0.95rem;
        background: #F8FAFC;
    }

    .modal-search-input:focus {
        border-color: #3B82F6;
        background: white;
        outline: none;
    }

    .modal-select-filter {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #E2E8F0;
        border-radius: 12px;
        font-size: 0.95rem;
        background: #F8FAFC;
    }

    .modal-table-container {
        border: 1px solid #E2E8F0;
        border-radius: 14px;
        max-height: 380px;
        overflow-y: auto;
        margin-bottom: 1.5rem;
        background: #F8FAFC;
    }

    .modal-soal-table {
        width: 100%;
        border-collapse: collapse;
    }

    .modal-soal-table th {
        background: #FFFFFF;
        padding: 0.85rem 1rem;
        font-size: 0.85rem;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        border-bottom: 1px solid #E2E8F0;
        position: sticky;
        top: 0;
        z-index: 5;
    }

    .modal-soal-table td {
        padding: 1rem;
        background: #FFFFFF;
        border-bottom: 1px solid #F1F5F9;
        font-size: 0.9rem;
        color: #334155;
        vertical-align: middle;
    }

    .modal-soal-table tr:hover td {
        background: #F8FAFC;
    }

    .badge-kategori-bs {
        background: rgba(59, 130, 246, 0.1);
        color: #2563EB;
        padding: 0.3rem 0.75rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
    }

    .text-pertanyaan-bs {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.5;
        font-weight: 500;
    }

    .counter-badge {
        background: #2563EB;
        color: white;
        padding: 0.15rem 0.5rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 700;
        margin-left: 0.25rem;
    }
</style>

@if($tryout_id)
<!-- Modal Seleksi Soal Interaktif dari Bank Soal -->
<div id="selectBankModal" class="modal-backdrop-premium" style="display: none;">
    <div class="modal-content-premium select-soal-modal">
        <!-- Modal Header -->
        <div class="modal-header-p">
            <h3 class="modal-title-p">
                <i class="fas fa-database"></i> Pilih Soal dari Bank Soal
                <span id="selectedCountBadge" class="counter-badge">0</span>
            </h3>
            <button type="button" class="btn-close-modal" onclick="closeSelectBankModal()">&times;</button>
        </div>

        <form action="{{ route('admin.soal.store') }}" method="POST" id="selectBankForm">
            @csrf
            <input type="hidden" name="tryout_id" value="{{ $tryout_id }}">

            <!-- Modal Body -->
            <div class="modal-body-p">
                <!-- Search and Filter Row -->
                <div class="modal-filter-row">
                    <div class="modal-search-wrapper">
                        <i class="fas fa-search modal-search-icon"></i>
                        <input type="text" id="searchBankSoal" class="modal-search-input" placeholder="Cari pertanyaan soal..." onkeyup="filterBankSoal()">
                    </div>
                    <div>
                        <select id="filterKategoriBank" class="modal-select-filter" onchange="filterBankSoal()">
                            <option value="">-- Semua Kategori --</option>
                            @foreach($kategoris as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Table of Bank Soal -->
                <div class="modal-table-container">
                    <table class="modal-soal-table">
                        <thead>
                            <tr>
                                <th style="width: 50px; text-align: center;">
                                    <input type="checkbox" id="selectAllCb" onchange="toggleSelectAll(this)">
                                </th>
                                <th style="width: 150px;">Kategori</th>
                                <th>Pertanyaan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bankSoals as $bs)
                                <tr class="bs-row" data-kategori-id="{{ $bs->kategori_id }}" data-pertanyaan="{{ strtolower(strip_tags($bs->pertanyaan)) }}">
                                    <td style="text-align: center;">
                                        <input type="checkbox" name="bank_soal_ids[]" value="{{ $bs->id }}" class="select-soal-cb" onchange="updateSelectedCount()">
                                    </td>
                                    <td>
                                        <span class="badge-kategori-bs">{{ $bs->kategori->nama_kategori ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <div class="text-pertanyaan-bs">{!! strip_tags($bs->pertanyaan) !!}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                                        <i class="fas fa-database" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; color: #CBD5E1;"></i>
                                        Belum ada soal di Bank Soal. Silakan tambah soal ke Bank Soal terlebih dahulu.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer-p">
                <button type="button" class="btn btn-secondary-p" onclick="closeSelectBankModal()">Batal</button>
                <button type="submit" class="btn btn-primary-p">
                    <i class="fas fa-plus-circle"></i> Tambahkan Soal Terpilih (<span id="selectedCount">0</span>)
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openSelectBankModal() {
    const modal = document.getElementById('selectBankModal');
    modal.style.display = 'flex';
    setTimeout(() => {
        modal.classList.add('active');
    }, 10);
}

function closeSelectBankModal() {
    const modal = document.getElementById('selectBankModal');
    modal.classList.remove('active');
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);
}

function filterBankSoal() {
    const searchVal = document.getElementById('searchBankSoal').value.toLowerCase();
    const categoryVal = document.getElementById('filterKategoriBank').value;
    const rows = document.querySelectorAll('.bs-row');
    
    rows.forEach(row => {
        const catId = row.getAttribute('data-kategori-id');
        const text = row.getAttribute('data-pertanyaan');
        
        const matchCategory = !categoryVal || catId === categoryVal;
        const matchSearch = !searchVal || text.includes(searchVal);
        
        if (matchCategory && matchSearch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    updateSelectAllCheckboxState();
}

function toggleSelectAll(selectAllCheckbox) {
    const isChecked = selectAllCheckbox.checked;
    const visibleRows = document.querySelectorAll('.bs-row:not([style*="display: none"])');
    
    visibleRows.forEach(row => {
        const cb = row.querySelector('.select-soal-cb');
        if (cb) {
            cb.checked = isChecked;
        }
    });
    updateSelectedCount();
}

function updateSelectAllCheckboxState() {
    const selectAllCb = document.getElementById('selectAllCb');
    if (!selectAllCb) return;
    
    const visibleCbs = document.querySelectorAll('.bs-row:not([style*="display: none"]) .select-soal-cb');
    
    if (visibleCbs.length === 0) {
        selectAllCb.checked = false;
        return;
    }
    
    let allChecked = true;
    visibleCbs.forEach(cb => {
        if (!cb.checked) {
            allChecked = false;
        }
    });
    selectAllCb.checked = allChecked;
}

function updateSelectedCount() {
    const checkedCbs = document.querySelectorAll('.select-soal-cb:checked');
    const count = checkedCbs.length;
    document.getElementById('selectedCount').textContent = count;
    document.getElementById('selectedCountBadge').textContent = count;
    
    updateSelectAllCheckboxState();
}

// Close modals when clicking backdrop
window.addEventListener('click', function(e) {
    const selectModal = document.getElementById('selectBankModal');
    if (e.target === selectModal) {
        closeSelectBankModal();
    }
});
</script>
@endif

@endsection
