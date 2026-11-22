<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Competition extends Model
{
    use SoftDeletes;

    // Kolom kompetisi yang boleh diisi melalui mass assignment.
    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'type',
        'status',
        'is_active',
        'registration_start',
        'registration_end',
        'competition_start',
        'competition_end',
        'min_team_members',
        'max_team_members',
        'quota',
        'thumbnail',
    ];

    // Mengubah tipe data database menjadi tipe PHP yang sesuai.
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'registration_start' => 'datetime',
            'registration_end' => 'datetime',
            'competition_start' => 'datetime',
            'competition_end' => 'datetime',
        ];
    }

    /**
     * Memeriksa apakah pendaftaran kompetisi sedang dibuka.
     */
    public function isRegistrationOpen(): bool
    {
        $now = now();

        return $this->is_active
            && $this->status === 'open'
            && $this->registration_start !== null
            && $this->registration_end !== null
            && $now->greaterThanOrEqualTo($this->registration_start)
            && $now->lessThanOrEqualTo($this->registration_end);
    }

    /**
     * Mengambil seluruh data pendaftaran pada kompetisi ini.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(CompetitionRegistration::class);
    }

    /**
     * Mengambil seluruh pertanyaan formulir milik kompetisi ini.
     */
    public function formFields(): HasMany
    {
        return $this->hasMany(CompetitionFormField::class);
    }

    /**
     * Mengambil satu konfigurasi pembayaran milik kompetisi ini.
     */
    public function paymentSetting(): HasOne
    {
        return $this->hasOne(CompetitionPaymentSetting::class);
    }
}
