<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'tryout_id',
        'question_text',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'option_e',
        'correct_answer',
        'jenis_soal',
        'option_points'
    ];

    protected $casts = [
        'option_points' => 'array',
    ];

    public function tryout()
    {
        return $this->belongsTo(Tryout::class);
    }

    public function getCleanQuestionTextAttribute()
    {
        $text = $this->question_text;
        // Strip out pembahasan_start comment and everything until pembahasan_end
        $text = preg_replace('/<!--pembahasan_start-->.*?<!--pembahasan_end-->/si', '', $text);
        // Fallback: strip out any span with class pembahasan-premium-block
        $text = preg_replace('/<span class="pembahasan-premium-block".*?<\/span>/si', '', $text);
        return trim($text);
    }

    public function getCleanQuestionTextFormattedAttribute()
    {
        $text = $this->clean_question_text;
        // If it already has HTML tags (like <br> or similar), it is pre-formatted
        if (preg_match('/<[a-z\/][^>]*>/i', $text)) {
            return $text;
        }
        // If it is plain text, run e() and nl2br()
        return nl2br(e($text));
    }

    public function getPembahasanHtmlAttribute()
    {
        $text = $this->question_text;
        // First try to match between the comments
        if (preg_match('/<!--pembahasan_start-->(.*?)<!--pembahasan_end-->/si', $text, $matches)) {
            return trim($matches[1]);
        }
        // Fallback: match the span with class pembahasan-premium-block
        if (preg_match('/(<span class="pembahasan-premium-block".*?<\/span>)/si', $text, $matches)) {
            return trim($matches[1]);
        }
        return '';
    }

    public function getFormattedOptionAAttribute() { return $this->formatOption($this->option_a); }
    public function getFormattedOptionBAttribute() { return $this->formatOption($this->option_b); }
    public function getFormattedOptionCAttribute() { return $this->formatOption($this->option_c); }
    public function getFormattedOptionDAttribute() { return $this->formatOption($this->option_d); }
    public function getFormattedOptionEAttribute() { return $this->formatOption($this->option_e); }

    private function formatOption($option)
    {
        if (preg_match('/<[a-z\/][^>]*>/i', $option)) {
            return $option;
        }
        return e($option);
    }
}
