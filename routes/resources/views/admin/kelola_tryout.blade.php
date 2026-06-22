@extends('layouts.app')
@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <div>
        <h1 class="page-title" style="margin: 0;">Kelola Tryout</h1>
        <p class="subtitle">Manajemen daftar tryout dan sesi ujian.</p>
    </div>
    <button class="btn btn-primary"><i class="fas fa-plus"></i> Buat Tryout</button>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nama Tryout</th>
                    <th>Durasi</th>
                    <th>Jadwal</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-weight: 600;">Tryout Akbar 1</td>
                    <td><span class="badge badge-warning"><i class="far fa-clock"></i> 120 Menit</span></td>
                    <td><span style="color: var(--text-muted);"><i class="far fa-calendar-alt"></i> 12 Des 2023</span></td>
                    <td style="text-align: right;">
                        <button class="btn btn-outline-primary" style="padding: 0.5rem 1rem;"><i class="fas fa-cogs"></i> Generate Soal dari Bank Soal</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection
