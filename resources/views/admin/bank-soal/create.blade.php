@extends('layouts.app')
@section('content')

<div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <div style="display:flex; align-items:center; gap: 0.5rem; margin-bottom: 0.5rem;">
            <a href="{{ route('admin.bank-soal.index', ['kategori_id' => $kategori->id]) }}" style="color: rgba(255,255,255,0.7); font-weight: 500;"><i class="fas fa-arrow-left"></i> Kembali ke Soal</a>
        </div>
        <h1 class="page-title" style="margin: 0;">Tambah Soal: {{ $kategori->nama_kategori }}</h1>
        <p class="subtitle">Tambahkan satu atau lebih pertanyaan baru sekaligus.</p>
    </div>
</div>

<form action="{{ route('admin.bank-soal.store') }}" method="POST" id="form-multi-soal">
    @csrf
    <input type="hidden" name="kategori_id" value="{{ $kategori->id }}">
    
    <div id="soal-container">
        <!-- Item Soal (Template) -->
        <div class="card soal-item" data-index="0" style="position: relative;">
            <button type="button" class="btn-remove-soal" style="display:none; position: absolute; top: 1rem; right: 1rem; background: #FEF2F2; color: #EF4444; border: 1px solid #FEE2E2; padding: 0.5rem; border-radius: 8px; cursor: pointer;">
                <i class="fas fa-trash-alt"></i> Hapus Soal Ini
            </button>
            <h3 style="margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border); color: var(--primary);">Soal #<span class="soal-number">1</span></h3>

            <div class="form-group">
                <label class="form-label">Pertanyaan <span style="color:#EF4444">*</span></label>
                <textarea name="soals[0][pertanyaan]" class="form-control" rows="4" required placeholder="Tuliskan pertanyaan di sini..."></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Opsi A <span style="color:#EF4444">*</span></label>
                    <div style="display:flex; align-items:center;">
                        <span style="background: #E2E8F0; padding: 0.875rem 1rem; border: 1px solid var(--border); border-right: none; border-radius: 12px 0 0 12px; font-weight: 600;">A</span>
                        <input type="text" name="soals[0][opsi_a]" class="form-control" style="border-radius: 0 12px 12px 0;" required>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Opsi B <span style="color:#EF4444">*</span></label>
                    <div style="display:flex; align-items:center;">
                        <span style="background: #E2E8F0; padding: 0.875rem 1rem; border: 1px solid var(--border); border-right: none; border-radius: 12px 0 0 12px; font-weight: 600;">B</span>
                        <input type="text" name="soals[0][opsi_b]" class="form-control" style="border-radius: 0 12px 12px 0;" required>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Opsi C <span style="color:#EF4444">*</span></label>
                    <div style="display:flex; align-items:center;">
                        <span style="background: #E2E8F0; padding: 0.875rem 1rem; border: 1px solid var(--border); border-right: none; border-radius: 12px 0 0 12px; font-weight: 600;">C</span>
                        <input type="text" name="soals[0][opsi_c]" class="form-control" style="border-radius: 0 12px 12px 0;" required>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Opsi D <span style="color:#EF4444">*</span></label>
                    <div style="display:flex; align-items:center;">
                        <span style="background: #E2E8F0; padding: 0.875rem 1rem; border: 1px solid var(--border); border-right: none; border-radius: 12px 0 0 12px; font-weight: 600;">D</span>
                        <input type="text" name="soals[0][opsi_d]" class="form-control" style="border-radius: 0 12px 12px 0;" required>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Kunci Jawaban <span style="color:#EF4444">*</span></label>
                <select name="soals[0][jawaban_benar]" class="form-control" required style="cursor: pointer; appearance: none; background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%231F2937%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 1rem top 50%; background-size: 0.65rem auto;">
                    <option value="" disabled selected>-- Pilih Jawaban Benar --</option>
                    <option value="a">Opsi A</option>
                    <option value="b">Opsi B</option>
                    <option value="c">Opsi C</option>
                    <option value="d">Opsi D</option>
                </select>
            </div>
        </div>
    </div>

    <div style="display: flex; justify-content: center; margin-bottom: 2rem;">
        <button type="button" id="btn-add-soal" class="btn btn-secondary" style="border: 2px dashed var(--border); padding: 1rem 2rem; width: 100%; color: var(--primary); font-weight: 600; background: transparent;">
            <i class="fas fa-plus-circle"></i> Tambah Form Soal Lagi
        </button>
    </div>

    <div class="card" style="display: flex; justify-content: flex-end; gap: 1rem; position: sticky; bottom: 1rem; z-index: 10;">
        <a href="{{ route('admin.bank-soal.index', ['kategori_id' => $kategori->id]) }}" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Semua Soal</button>
    </div>
</form>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('soal-container');
        const btnAdd = document.getElementById('btn-add-soal');
        let soalIndex = 0;

        function updateSoalNumbers() {
            const items = container.querySelectorAll('.soal-item');
            items.forEach((item, index) => {
                item.querySelector('.soal-number').textContent = index + 1;
                const btnRemove = item.querySelector('.btn-remove-soal');
                if (items.length > 1) {
                    btnRemove.style.display = 'block';
                } else {
                    btnRemove.style.display = 'none';
                }
            });
        }

        btnAdd.addEventListener('click', function() {
            soalIndex++;
            const template = container.querySelector('.soal-item').cloneNode(true);
            template.dataset.index = soalIndex;
            
            // Clear inputs
            template.querySelectorAll('input, textarea, select').forEach(input => {
                if(input.tagName === 'SELECT') {
                    input.value = "";
                } else {
                    input.value = "";
                }
                // Update names
                const name = input.getAttribute('name');
                if(name) {
                    input.setAttribute('name', name.replace(/\[\d+\]/, `[${soalIndex}]`));
                }
            });

            // Add remove event listener
            template.querySelector('.btn-remove-soal').addEventListener('click', function() {
                template.remove();
                updateSoalNumbers();
            });

            container.appendChild(template);
            updateSoalNumbers();
            
            // Scroll to new item
            template.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });

        // Add remove listener to first item
        document.querySelector('.btn-remove-soal').addEventListener('click', function(e) {
            e.target.closest('.soal-item').remove();
            updateSoalNumbers();
        });
    });
</script>
@endpush
