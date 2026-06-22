<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = [
        'user_id', 
        'tryout_id', 
        'tryout_attempt_id', 
        'score', 
        'status',
        'score_twk',
        'score_tiu',
        'score_tkp',
        'passed_twk',
        'passed_tiu',
        'passed_tkp',
        'is_passed'
    ];

    protected $casts = [
        'passed_twk' => 'boolean',
        'passed_tiu' => 'boolean',
        'passed_tkp' => 'boolean',
        'is_passed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tryout()
    {
        return $this->belongsTo(Tryout::class);
    }
}
