@extends('layouts.app')
@section('content')

<div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h1 class="page-title" style="margin: 0;">Laporan Analisis Nilai</h1>
        <p class="subtitle">Rekapitulasi performa peserta di setiap tryout.</p>
    </div>
    <button class="btn btn-secondary" style="background: white; color: var(--primary); border: 1px solid var(--border);"><i class="fas fa-download"></i> Export PDF</button>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Tryout</th>
                    <th>Rata-rata Nilai</th>
                    <th>Peserta Tertinggi</th>
                    <th style="text-align: right;">Detail</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-weight: 600;">Tryout Akbar 1</td>
                    <td><span class="badge badge-warning" style="font-size: 1rem;">650.0</span></td>
                    <td>
                        <div style="display:flex; align-items:center; gap:0.5rem;">
                            <i class="fas fa-trophy" style="color: #F59E0B;"></i> 
                            <span style="font-weight: 500;">Siswa B</span> 
                            <span style="color: var(--text-muted); font-size: 0.85rem;">(890.0)</span>
                        </div>
                    </td>
                    <td style="text-align: right;">
                        <button class="btn btn-secondary" style="padding: 0.25rem 0.75rem; font-size: 0.85rem;">Lihat</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection
