<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competition_payment_methods', function (Blueprint $table) {
            $table->id();

            // Kolom foreign key untuk menghubungkan metode dengan pengaturan pembayaran.
            $table->unsignedBigInteger('competition_payment_setting_id');

            // Nama foreign key ditentukan manual agar tidak terlalu panjang.
            $table->foreign(
                'competition_payment_setting_id',
                'fk_pay_methods_setting'
            )
                ->references('id')
                ->on('competition_payment_settings')
                ->cascadeOnDelete();

            // Jenis metode pembayaran: bank, e-wallet, atau QRIS.
            $table->string('method_type', 30);

            // Nama bank atau penyedia pembayaran.
            $table->string('provider_name');

            // Nama pemilik rekening atau akun pembayaran.
            $table->string('account_name')->nullable();

            // Nomor rekening atau nomor akun pembayaran.
            $table->string('account_number')->nullable();

            // Lokasi penyimpanan gambar QRIS.
            $table->string('image_path')->nullable();

            // Urutan tampilan metode pembayaran.
            $table->unsignedSmallInteger('sort_order')->default(0);

            // Admin dapat menonaktifkan metode tanpa menghapus datanya.
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Index dengan nama pendek agar sesuai batas MySQL.
            $table->index(
                ['competition_payment_setting_id', 'is_active', 'sort_order'],
                'idx_pay_methods_setting_active_order'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competition_payment_methods');
    }
};
