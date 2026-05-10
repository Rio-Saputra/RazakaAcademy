<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TryoutAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'tryout_id',
        'transaction_id',
        'attempt_count',
        'max_attempt',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tryout()
    {
        return $this->belongsTo(Tryout::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
