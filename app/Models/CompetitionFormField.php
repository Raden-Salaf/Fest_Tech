<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompetitionFormField extends Model
{
    // Kolom yang boleh diisi melalui mass assignment.
    protected $fillable = [
        'competition_id',
        'label',
        'name',
        'type',
        'placeholder',
        'options',
        'is_required',
        'sort_order',
        'is_active',
    ];

    // Mengubah JSON menjadi array dan nilai boolean menjadi true/false.
    protected function casts(): array
    {
        return [
            'options' => 'array',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Menghubungkan pertanyaan dengan kompetisi pemiliknya.
     */
    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    /**
     * Mengambil seluruh jawaban dari pertanyaan ini.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(
            CompetitionRegistrationAnswer::class,
            'form_field_id'
        );
    }
}
