<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationSubmission extends Model
{
    // Kolom yang boleh diisi melalui mass assignment.
    protected $fillable = [
        'registration_id',
        'submission_number',
        'payment_proof_path',
        'payment_description',
        'appeal_message',
        'status',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
    ];

    // Mengubah tipe data agar mudah digunakan di Laravel.
    protected function casts(): array
    {
        return [
            'submission_number' => 'integer',
            'reviewed_at' => 'datetime',
        ];
    }

    /**
     * Mengambil data pendaftaran kompetisi milik pengajuan ini.
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(CompetitionRegistration::class, 'registration_id');
    }

    /**
     * Mengambil admin yang memeriksa pengajuan ini.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
