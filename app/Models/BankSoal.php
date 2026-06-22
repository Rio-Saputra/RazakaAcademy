<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankSoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'pertanyaan',
        'opsi_a',
        'opsi_b',
        'opsi_c',
        'opsi_d',
        'opsi_e',
        'jawaban_benar',
        'kategori_id',
        'jenis_soal',
        'option_points'
    ];

    protected $casts = [
        'option_points' => 'array',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriBankSoal::class, 'kategori_id');
    }

    public function getCleanPertanyaanAttribute()
    {
        $text = $this->pertanyaan;
        // Strip out pembahasan_start comment and everything until pembahasan_end
        $text = preg_replace('/<!--pembahasan_start-->.*?<!--pembahasan_end-->/si', '', $text);
        // Fallback: strip out any span with class pembahasan-premium-block
        $text = preg_replace('/<span class="pembahasan-premium-block".*?<\/span>/si', '', $text);
        return trim($text);
    }

    public function getCleanPertanyaanFormattedAttribute()
    {
        $text = $this->clean_pertanyaan;
        // If it already has HTML tags (like <br> or similar), it is pre-formatted
        if (preg_match('/<[a-z\/][^>]*>/i', $text)) {
            return $text;
        }
        // If it is plain text, run e() and nl2br()
        return nl2br(e($text));
    }

    public function getPembahasanHtmlAttribute()
    {
        $text = $this->pertanyaan;
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

    public function getFormattedOpsiAAttribute() { return $this->formatOption($this->opsi_a); }
    public function getFormattedOpsiBAttribute() { return $this->formatOption($this->opsi_b); }
    public function getFormattedOpsiCAttribute() { return $this->formatOption($this->opsi_c); }
    public function getFormattedOpsiDAttribute() { return $this->formatOption($this->opsi_d); }
    public function getFormattedOpsiEAttribute() { return $this->formatOption($this->opsi_e); }

    private function formatOption($option)
    {
        if (preg_match('/<[a-z\/][^>]*>/i', $option)) {
            return $option;
        }
        return e($option);
    }
}
