<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('competition_registrations', function (Blueprint $table) {
            // Kode unik untuk identitas setiap pendaftaran.
            // Nullable agar aman terhadap data pendaftaran lama.
            $table->string('registration_code', 40)
                ->nullable()
                ->unique('uq_registration_code');

            // Status pemeriksaan berkas dipisahkan dari status pendaftaran.
            $table->string('verification_status', 20)
                ->default('pending')
                ->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('competition_registrations', function (Blueprint $table) {
            // Hapus unique index sebelum menghapus kolom kode.
            $table->dropUnique('uq_registration_code');

            // Menghapus kedua kolom jika migration di-rollback.
            $table->dropColumn([
                'registration_code',
                'verification_status',
            ]);
        });
    }
};
