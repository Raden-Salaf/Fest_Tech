<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompetitionPaymentSetting extends Model
{
    // Kolom yang boleh diisi melalui mass assignment.
    protected $fillable = [
        'competition_id',
        'is_payment_required',
        'fee_amount',
        'description',
        'instructions',
    ];

    // Mengubah tipe data secara otomatis ketika data diambil dari database.
    protected function casts(): array
    {
        return [
            'is_payment_required' => 'boolean',
            'fee_amount' => 'integer',
        ];
    }

    // Pengaturan pembayaran dimiliki oleh satu kompetisi.
    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    // Satu pengaturan pembayaran dapat memiliki banyak metode pembayaran.
    public function paymentMethods(): HasMany
    {
        return $this->hasMany(CompetitionPaymentMethod::class);
    }
}
