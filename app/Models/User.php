<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Mengambil seluruh pendaftaran kompetisi milik pengguna.
     */
    public function competitionRegistrations(): HasMany
    {
        return $this->hasMany(CompetitionRegistration::class);
    }

    /**
     * Mengambil seluruh pengajuan yang diperiksa oleh admin ini.
     */
    public function reviewedSubmissions(): HasMany
    {
        return $this->hasMany(RegistrationSubmission::class, 'reviewed_by');
    }

    /**
     * Mengambil seluruh berita yang ditulis oleh pengguna.
     */
    public function news(): HasMany
    {
        return $this->hasMany(News::class);
    }
}
