<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat tabel anggota tambahan dalam pendaftaran kompetisi.
     */
    public function up(): void
    {
        Schema::create('competition_registration_members', function (Blueprint $table) {
            $table->id();

            // Menghubungkan anggota dengan data pendaftaran tim.
            $table->foreignId('registration_id')
                ->constrained('competition_registrations')
                ->cascadeOnDelete();

            // Menyimpan nama anggota tambahan, tanpa membutuhkan akun.
            $table->string('name');

            // Menentukan urutan anggota ketika ditampilkan.
            $table->unsignedSmallInteger('sort_order')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Menghapus tabel anggota ketika migration di-rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_registration_members');
    }
};
