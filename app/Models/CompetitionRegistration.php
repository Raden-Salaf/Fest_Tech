<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompetitionRegistration extends Model
{
    protected $fillable = [
        'user_id',
        'competition_id',
        'team_name',
        'status',
        'verification_status',
        'registration_code',
        'registered_at',
    ];

    protected function casts(): array
    {
        return [
            'registered_at' => 'datetime',
        ];
    }

    /**
     * Membuat kode unik setelah data pendaftaran berhasil dibuat.
     */
    protected static function booted(): void
    {
        static::created(function (CompetitionRegistration $registration) {
            // Ambil slug kompetisi untuk membuat identitas singkat.
            $competitionSlug = $registration->competition()->value('slug');

            // Ambil tiga karakter pertama dari slug kompetisi.
            $competitionCode = strtoupper(substr($competitionSlug, 0, 3));

            // Gunakan tahun ketika pendaftaran dibuat.
            $registrationYear = $registration->created_at->format('Y');

            // ID database menjamin nomor unik untuk setiap pendaftaran.
            $registrationNumber = str_pad(
                (string) $registration->id,
                6,
                '0',
                STR_PAD_LEFT
            );

            // Susun kode pendaftaran, misalnya FT-2026-WEB-000001.
            $registration->forceFill([
                'registration_code' => "FT-{$registrationYear}-{$competitionCode}-{$registrationNumber}",
            ])->saveQuietly();
        });
    }

    // Relasi ke akun peserta sebagai perwakilan.
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke kompetisi yang diikuti.
    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    // Relasi ke anggota tambahan tim.
    public function members(): HasMany
    {
        return $this->hasMany(CompetitionRegistrationMember::class, 'registration_id');
    }

    // Relasi ke jawaban formulir dinamis.
    public function answers(): HasMany
    {
        return $this->hasMany(CompetitionRegistrationAnswer::class, 'registration_id');
    }

    // Relasi ke seluruh riwayat pengajuan dokumen.
    public function submissions(): HasMany
    {
        return $this->hasMany(RegistrationSubmission::class, 'registration_id')
            ->orderBy('submission_number');
    }
}
