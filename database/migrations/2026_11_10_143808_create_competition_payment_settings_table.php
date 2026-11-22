<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competition_payment_settings', function (Blueprint $table) {
            $table->id();

            // Satu kompetisi hanya memiliki satu konfigurasi pembayaran.
            $table->foreignId('competition_id')
                ->unique()
                ->constrained('competitions')
                ->cascadeOnDelete();

            // Menentukan apakah kompetisi ini berbayar atau gratis.
            $table->boolean('is_payment_required')->default(false);

            // Nominal biaya pendaftaran dalam Rupiah.
            $table->unsignedBigInteger('fee_amount')->default(0);

            // Deskripsi dan instruksi pembayaran dari admin.
            $table->text('description')->nullable();
            $table->longText('instructions')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competition_payment_settings');
    }
};
