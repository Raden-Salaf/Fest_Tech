<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat tabel untuk menyimpan pendaftaran kompetisi.
     */
    public function up(): void
    {
        Schema::create('competition_registrations', function (Blueprint $table) {
            $table->id();

            // Menghubungkan pendaftaran dengan akun perwakilan.
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            // Menghubungkan pendaftaran dengan kompetisi yang dipilih.
            $table->foreignId('competition_id')
                ->constrained('competitions')
                ->restrictOnDelete();

            // Nama tim hanya digunakan untuk kompetisi beregu.
            $table->string('team_name')->nullable();

            // Status verifikasi pendaftaran oleh administrator.
            $table->string('status', 20)->default('pending');

            // Waktu ketika formulir berhasil dikirim.
            $table->timestamp('registered_at')->useCurrent();

            $table->timestamps();

            // Mencegah satu akun mendaftar dua kali pada kompetisi yang sama.
            $table->unique(
                ['user_id', 'competition_id'],
                'unique_user_competition_registration'
            );

            // Mempermudah pencarian pendaftaran berdasarkan kompetisi dan status.
            $table->index(['competition_id', 'status']);
        });
    }

    /**
     * Menghapus tabel ketika migration di-rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_registrations');
    }
};
