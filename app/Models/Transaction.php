<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['user_id', 'package_id', 'tryout_id', 'amount', 'status', 'snap_token', 'payment_type', 'payment_code'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function tryout()
    {
        return $this->belongsTo(Tryout::class);
    }
}
