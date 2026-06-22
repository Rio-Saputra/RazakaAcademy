@extends('layouts.app')
@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h1 class="page-title" style="margin: 0;">Kelola Soal</h1>
    <a href="{{ url('admin/tambah_soal') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Soal Baru</a>
</div>
<div class="card">
    <div class="table-container">
        <table>
            <tr>
                <th>ID</th>
                <th>Kategori</th>
                <th>Potongan Soal</th>
                <th>Aksi</th>
            </tr>
            <tr>
                <td>#102</td>
                <td>Penalaran Umum</td>
                <td>Jika semua A adalah B...</td>
                <td>
                    <button class="btn btn-primary" style="padding: 0.25rem 0.5rem;"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-danger" style="padding: 0.25rem 0.5rem;"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        </table>
    </div>
</div>
@endsection
