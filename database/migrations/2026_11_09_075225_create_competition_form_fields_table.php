<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat tabel konfigurasi pertanyaan formulir kompetisi.
     */
    public function up(): void
    {
        Schema::create('competition_form_fields', function (Blueprint $table) {
            $table->id();

            // Menghubungkan pertanyaan dengan kompetisi tertentu.
            $table->foreignId('competition_id')
                ->constrained('competitions')
                ->cascadeOnDelete();

            // Nama pertanyaan yang ditampilkan kepada peserta.
            $table->string('label');

            // Nama unik untuk mengidentifikasi jawaban di sistem.
            $table->string('name');

            // Jenis input: text, textarea, select, radio, checkbox, file, dan lainnya.
            $table->string('type', 30);

            // Petunjuk tambahan di dalam input.
            $table->string('placeholder')->nullable();

            // Pilihan jawaban untuk select, radio, atau checkbox.
            $table->json('options')->nullable();

            // Menentukan apakah pertanyaan wajib diisi.
            $table->boolean('is_required')->default(false);

            // Mengatur urutan pertanyaan pada formulir.
            $table->unsignedSmallInteger('sort_order')->default(0);

            // Mengaktifkan atau menonaktifkan pertanyaan.
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Memastikan nama field tidak duplikat dalam satu kompetisi.
            $table->unique(
                ['competition_id', 'name'],
                'unique_competition_form_field'
            );
        });
    }

    /**
     * Menghapus tabel konfigurasi pertanyaan.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_form_fields');
    }
};
