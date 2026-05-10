<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TryoutAnswer extends Model
{
    protected $table = 'tryout_answers';

    protected $fillable = [
        'user_id',
        'tryout_id',
        'tryout_attempt_id',
        'question_id',
        'user_answer',
        'correct_answer',
        'is_correct'
    ];

    protected $casts = [
        'is_correct' => 'boolean'
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
