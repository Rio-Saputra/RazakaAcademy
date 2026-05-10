@extends('layouts.app')
@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h1 class="page-title" style="margin: 0;">Kelola Paket</h1>
    <button class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Paket</button>
</div>
<div class="card">
    <div class="table-container">
        <table>
            <tr>
                <th>Nama Paket</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
            <tr>
                <td>Paket Premium</td>
                <td>Rp 150.000</td>
                <td>
                    <button class="btn btn-primary" style="padding: 0.25rem 0.5rem;"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-danger" style="padding: 0.25rem 0.5rem;"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        </table>
    </div>
</div>
@endsection
