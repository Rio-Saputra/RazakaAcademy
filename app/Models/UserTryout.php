<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTryout extends Model
{
    protected $fillable = ['user_id', 'tryout_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tryout()
    {
        return $this->belongsTo(Tryout::class);
    }
}
