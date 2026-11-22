<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitionPaymentMethod extends Model
{
    // Kolom yang boleh diisi melalui mass assignment.
    protected $fillable = [
        'competition_payment_setting_id',
        'method_type',
        'provider_name',
        'account_name',
        'account_number',
        'image_path',
        'sort_order',
        'is_active',
    ];

    // Mengatur tipe data otomatis.
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    // Metode pembayaran dimiliki oleh satu pengaturan pembayaran.
    public function paymentSetting(): BelongsTo
    {
        return $this->belongsTo(CompetitionPaymentSetting::class, 'competition_payment_setting_id');
    }
}
