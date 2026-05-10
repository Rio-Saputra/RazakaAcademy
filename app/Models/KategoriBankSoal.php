<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriBankSoal extends Model
{
    protected $fillable = [
        'nama_kategori',
        'deskripsi'
    ];

    public function bank_soals()
    {
        return $this->hasMany(BankSoal::class, 'kategori_id');
    }
}
