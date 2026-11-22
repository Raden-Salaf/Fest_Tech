<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitionRegistrationMember extends Model
{
    // Kolom yang boleh diisi melalui mass assignment.
    protected $fillable = [
        'registration_id',
        'name',
        'sort_order',
    ];

    /**
     * Menghubungkan anggota dengan data pendaftaran tim.
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(
            CompetitionRegistration::class,
            'registration_id'
        );
    }
}
