<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'tryout_attempt_id',
        'days_passed',
        'title',
        'message',
        'is_read',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tryoutAttempt()
    {
        return $this->belongsTo(TryoutAttempt::class);
    }
}
