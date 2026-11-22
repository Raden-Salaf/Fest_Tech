<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitionRegistrationAnswer extends Model
{
    // Kolom yang boleh diisi melalui mass assignment.
    protected $fillable = [
        'registration_id',
        'form_field_id',
        'answer',
    ];

    /**
     * Menghubungkan jawaban dengan data pendaftaran.
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(
            CompetitionRegistration::class,
            'registration_id'
        );
    }

    /**
     * Menghubungkan jawaban dengan pertanyaan formulir.
     */
    public function formField(): BelongsTo
    {
        return $this->belongsTo(
            CompetitionFormField::class,
            'form_field_id'
        );
    }
}
