@php
    $pertanyaanText = preg_replace('/<img[^>]+>/i', '', $bankSoal->pertanyaan);
    $pertanyaanImg = null;
    if (preg_match('/<img[^>]+src="([^"]+)"/i', $bankSoal->pertanyaan, $m)) {
        $pertanyaanImg = $m[1];
    }

    $opsiAText = preg_replace('/<img[^>]+>/i', '', $bankSoal->opsi_a);
    $opsiAImg = null;
    if (preg_match('/<img[^>]+src="([^"]+)"/i', $bankSoal->opsi_a, $m)) {
        $opsiAImg = $m[1];
    }

    $opsiBText = preg_replace('/<img[^>]+>/i', '', $bankSoal->opsi_b);
    $opsiBImg = null;
    if (preg_match('/<img[^>]+src="([^"]+)"/i', $bankSoal->opsi_b, $m)) {
        $opsiBImg = $m[1];
    }

    $opsiCText = preg_replace('/<img[^>]+>/i', '', $bankSoal->opsi_c);
    $opsiCImg = null;
    if (preg_match('/<img[^>]+src="([^"]+)"/i', $bankSoal->opsi_c, $m)) {
        $opsiCImg = $m[1];
    }

    $opsiDText = preg_replace('/<img[^>]+>/i', '', $bankSoal->opsi_d);
    $opsiDImg = null;
    if (preg_match('/<img[^>]+src="([^"]+)"/i', $bankSoal->opsi_d, $m)) {
        $opsiDImg = $m[1];
    }

    $opsiEText = preg_replace('/<img[^>]+>/i', '', $bankSoal->opsi_e ?? '');
    $opsiEImg = null;
    if ($bankSoal->opsi_e && preg_match('/<img[^>]+src="([^"]+)"/i', $bankSoal->opsi_e, $m)) {
        $opsiEImg = $m[1];
    }
@endphp

@extends('layouts.app')
@section('content')

<div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <div style="display:flex; align-items:center; gap: 0.5rem; margin-bottom: 0.5rem;">
            <a href="{{ route('admin.bank-soal.index', ['kategori_id' => $kategori->id]) }}" style="color: rgba(255,255,255,0.7); font-weight: 500;"><i class="fas fa-arrow-left"></i> Kembali ke Soal</a>
        </div>
        <h1 class="page-title" style="margin: 0;">Edit Soal: {{ $kategori->nama_kategori }}</h1>
        <p class="subtitle">Perbarui pertanyaan atau opsi jawaban.</p>
    </div>
</div>

<div class="card">
    <form action="{{ route('admin.bank-soal.update', $bankSoal->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label class="form-label">Kategori SKD CPNS <span style="color:#EF4444">*</span></label>
            <select name="jenis_soal" id="jenis_soal" class="form-control" onchange="toggleJenisSoal()" style="cursor: pointer; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%231F2937%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 1rem top 50%; background-size: 0.65rem auto;">
                <option value="TWK" {{ old('jenis_soal', $bankSoal->jenis_soal) == 'TWK' ? 'selected' : '' }}>TWK (Tes Wawasan Kebangsaan)</option>
                <option value="TIU" {{ old('jenis_soal', $bankSoal->jenis_soal) == 'TIU' ? 'selected' : '' }}>TIU (Tes Intelegensi Umum)</option>
                <option value="TKP" {{ old('jenis_soal', $bankSoal->jenis_soal) == 'TKP' ? 'selected' : '' }}>TKP (Tes Karakteristik Pribadi)</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Pertanyaan <span style="color:#EF4444">*</span></label>
            <textarea name="pertanyaan" class="form-control" rows="5">{{ old('pertanyaan', $pertanyaanText) }}</textarea>
            @if($pertanyaanImg)
                <div style="margin-top: 0.75rem; padding: 0.5rem; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; max-width: fit-content;">
                    <p style="margin: 0 0 0.5rem 0; font-size: 0.85rem; font-weight: 600; color: #475569;">Gambar Saat Ini:</p>
                    <img src="{{ $pertanyaanImg }}" style="max-height: 180px; max-width: 100%; border-radius: 4px; display: block; margin-bottom: 0.5rem;">
                    <label style="display: inline-flex; align-items: center; gap: 0.35rem; font-size: 0.8rem; color: #EF4444; cursor: pointer; margin: 0;">
                        <input type="checkbox" name="hapus_gambar_pertanyaan" value="1"> Hapus gambar ini
                    </label>
                </div>
            @endif
            <input type="file" name="gambar_pertanyaan" accept="image/*" class="form-control" style="font-size: 0.8rem; padding: 0.25rem 0.5rem; height: auto; margin-top: 0.5rem;">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <!-- Opsi A -->
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Opsi A <span style="color:#EF4444">*</span></label>
                <div style="display:flex; align-items:center; margin-bottom: 0.25rem;">
                    <span style="background: #E2E8F0; padding: 0.875rem 1rem; border: 1px solid var(--border); border-right: none; border-radius: 12px 0 0 12px; font-weight: 600;">A</span>
                    <input type="text" name="opsi_a" class="form-control" style="border-radius: 0 12px 12px 0;" value="{{ old('opsi_a', $opsiAText) }}">
                </div>
                @if($opsiAImg)
                    <div style="margin: 0.5rem 0; padding: 0.25rem; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; display: inline-block;">
                        <img src="{{ $opsiAImg }}" style="max-height: 80px; display: block; border-radius: 4px; margin-bottom: 0.25rem;">
                        <label style="display: inline-flex; align-items: center; gap: 0.25rem; font-size: 0.75rem; color: #EF4444; cursor: pointer; margin: 0;">
                            <input type="checkbox" name="hapus_gambar_opsi_a" value="1"> Hapus gambar
                        </label>
                    </div>
                @endif
                <input type="file" name="gambar_opsi_a" accept="image/*" class="form-control" style="font-size: 0.8rem; padding: 0.25rem 0.5rem; height: auto;">
            </div>

            <!-- Opsi B -->
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Opsi B <span style="color:#EF4444">*</span></label>
                <div style="display:flex; align-items:center; margin-bottom: 0.25rem;">
                    <span style="background: #E2E8F0; padding: 0.875rem 1rem; border: 1px solid var(--border); border-right: none; border-radius: 12px 0 0 12px; font-weight: 600;">B</span>
                    <input type="text" name="opsi_b" class="form-control" style="border-radius: 0 12px 12px 0;" value="{{ old('opsi_b', $opsiBText) }}">
                </div>
                @if($opsiBImg)
                    <div style="margin: 0.5rem 0; padding: 0.25rem; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; display: inline-block;">
                        <img src="{{ $opsiBImg }}" style="max-height: 80px; display: block; border-radius: 4px; margin-bottom: 0.25rem;">
                        <label style="display: inline-flex; align-items: center; gap: 0.25rem; font-size: 0.75rem; color: #EF4444; cursor: pointer; margin: 0;">
                            <input type="checkbox" name="hapus_gambar_opsi_b" value="1"> Hapus gambar
                        </label>
                    </div>
                @endif
                <input type="file" name="gambar_opsi_b" accept="image/*" class="form-control" style="font-size: 0.8rem; padding: 0.25rem 0.5rem; height: auto;">
            </div>

            <!-- Opsi C -->
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Opsi C <span style="color:#EF4444">*</span></label>
                <div style="display:flex; align-items:center; margin-bottom: 0.25rem;">
                    <span style="background: #E2E8F0; padding: 0.875rem 1rem; border: 1px solid var(--border); border-right: none; border-radius: 12px 0 0 12px; font-weight: 600;">C</span>
                    <input type="text" name="opsi_c" class="form-control" style="border-radius: 0 12px 12px 0;" value="{{ old('opsi_c', $opsiCText) }}">
                </div>
                @if($opsiCImg)
                    <div style="margin: 0.5rem 0; padding: 0.25rem; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; display: inline-block;">
                        <img src="{{ $opsiCImg }}" style="max-height: 80px; display: block; border-radius: 4px; margin-bottom: 0.25rem;">
                        <label style="display: inline-flex; align-items: center; gap: 0.25rem; font-size: 0.75rem; color: #EF4444; cursor: pointer; margin: 0;">
                            <input type="checkbox" name="hapus_gambar_opsi_c" value="1"> Hapus gambar
                        </label>
                    </div>
                @endif
                <input type="file" name="gambar_opsi_c" accept="image/*" class="form-control" style="font-size: 0.8rem; padding: 0.25rem 0.5rem; height: auto;">
            </div>

            <!-- Opsi D -->
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Opsi D <span style="color:#EF4444">*</span></label>
                <div style="display:flex; align-items:center; margin-bottom: 0.25rem;">
                    <span style="background: #E2E8F0; padding: 0.875rem 1rem; border: 1px solid var(--border); border-right: none; border-radius: 12px 0 0 12px; font-weight: 600;">D</span>
                    <input type="text" name="opsi_d" class="form-control" style="border-radius: 0 12px 12px 0;" value="{{ old('opsi_d', $opsiDText) }}">
                </div>
                @if($opsiDImg)
                    <div style="margin: 0.5rem 0; padding: 0.25rem; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; display: inline-block;">
                        <img src="{{ $opsiDImg }}" style="max-height: 80px; display: block; border-radius: 4px; margin-bottom: 0.25rem;">
                        <label style="display: inline-flex; align-items: center; gap: 0.25rem; font-size: 0.75rem; color: #EF4444; cursor: pointer; margin: 0;">
                            <input type="checkbox" name="hapus_gambar_opsi_d" value="1"> Hapus gambar
                        </label>
                    </div>
                @endif
                <input type="file" name="gambar_opsi_d" accept="image/*" class="form-control" style="font-size: 0.8rem; padding: 0.25rem 0.5rem; height: auto;">
            </div>

            <!-- Opsi E -->
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Opsi E <span class="text-muted" style="font-weight:normal; font-size:0.85rem;">(Opsional)</span></label>
                <div style="display:flex; align-items:center; margin-bottom: 0.25rem;">
                    <span style="background: #E2E8F0; padding: 0.875rem 1rem; border: 1px solid var(--border); border-right: none; border-radius: 12px 0 0 12px; font-weight: 600;">E</span>
                    <input type="text" name="opsi_e" class="form-control" style="border-radius: 0 12px 12px 0;" value="{{ old('opsi_e', $opsiEText) }}">
                </div>
                @if($opsiEImg)
                    <div style="margin: 0.5rem 0; padding: 0.25rem; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; display: inline-block;">
                        <img src="{{ $opsiEImg }}" style="max-height: 80px; display: block; border-radius: 4px; margin-bottom: 0.25rem;">
                        <label style="display: inline-flex; align-items: center; gap: 0.25rem; font-size: 0.75rem; color: #EF4444; cursor: pointer; margin: 0;">
                            <input type="checkbox" name="hapus_gambar_opsi_e" value="1"> Hapus gambar
                        </label>
                    </div>
                @endif
                <input type="file" name="gambar_opsi_e" accept="image/*" class="form-control" style="font-size: 0.8rem; padding: 0.25rem 0.5rem; height: auto;">
            </div>
        </div>

        <div class="form-group" id="correct_answer_section" style="{{ old('jenis_soal', $bankSoal->jenis_soal) === 'TKP' ? 'display:none;' : '' }}">
            <label class="form-label">Kunci Jawaban <span style="color:#EF4444">*</span></label>
            <select name="jawaban_benar" class="form-control" required style="cursor: pointer; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%231F2937%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 1rem top 50%; background-size: 0.65rem auto;">
                <option value="a" {{ old('jawaban_benar', $bankSoal->jawaban_benar) == 'a' ? 'selected' : '' }}>Opsi A</option>
                <option value="b" {{ old('jawaban_benar', $bankSoal->jawaban_benar) == 'b' ? 'selected' : '' }}>Opsi B</option>
                <option value="c" {{ old('jawaban_benar', $bankSoal->jawaban_benar) == 'c' ? 'selected' : '' }}>Opsi C</option>
                <option value="d" {{ old('jawaban_benar', $bankSoal->jawaban_benar) == 'd' ? 'selected' : '' }}>Opsi D</option>
                <option value="e" {{ old('jawaban_benar', $bankSoal->jawaban_benar) == 'e' ? 'selected' : '' }}>Opsi E</option>
            </select>
        </div>

        <div class="form-group" id="tkp_points_section" style="{{ old('jenis_soal', $bankSoal->jenis_soal) === 'TKP' ? '' : 'display:none;' }}">
            <label class="form-label">Bobot Nilai Poin TKP (1 s.d. 5) <span style="color:#EF4444">*</span></label>
            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                @foreach(['A','B','C','D','E'] as $opt)
                <div style="display: flex; align-items: center; gap: 0.25rem; background: #F1F5F9; padding: 0.5rem 0.75rem; border-radius: 8px; border: 1px solid var(--border);">
                    <span style="font-weight: 700; font-size: 0.9rem; color:#1E293B;">{{ $opt }}:</span>
                    <input type="number" name="option_points_{{ strtolower($opt) }}" min="1" max="5" value="{{ $bankSoal->option_points ? ($bankSoal->option_points[$opt] ?? '') : ($opt === 'A' ? 5 : ($opt === 'B' ? 4 : ($opt === 'C' ? 3 : ($opt === 'D' ? 2 : 1)))) }}" style="width: 50px; border: 1px solid #CBD5E1; border-radius: 4px; padding: 2px 4px; font-weight: bold; text-align: center; color: #1E293B; background: white;">
                </div>
                @endforeach
            </div>
        </div>

        <script>
        function toggleJenisSoal() {
            const val = document.getElementById('jenis_soal').value;
            const correctSection = document.getElementById('correct_answer_section');
            const tkpSection = document.getElementById('tkp_points_section');
            if (val === 'TKP') {
                correctSection.style.display = 'none';
                tkpSection.style.display = 'block';
            } else {
                correctSection.style.display = 'block';
                tkpSection.style.display = 'none';
            }
        }
        </script>

        <div style="border-top: 1px solid var(--border); padding-top: 1.5rem; display: flex; justify-content: flex-end; gap: 1rem;">
            <a href="{{ route('admin.bank-soal.index', ['kategori_id' => $kategori->id]) }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
        </div>
    </form>
</div>

@endsection
