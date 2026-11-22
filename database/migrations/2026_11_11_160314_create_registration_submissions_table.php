<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registration_submissions', function (Blueprint $table) {
            $table->id();

            // Menghubungkan pengajuan dengan pendaftaran kompetisi.
            $table->unsignedBigInteger('registration_id');

            // Nomor pengajuan untuk mempertahankan seluruh riwayat.
            $table->unsignedSmallInteger('submission_number');

            // Lokasi bukti transfer yang disimpan secara privat.
            $table->string('payment_proof_path')->nullable();

            // Keterangan pembayaran dari peserta.
            $table->text('payment_description')->nullable();

            // Pesan sanggahan peserta jika pengajuan sebelumnya ditolak.
            $table->text('appeal_message')->nullable();

            // Status pemeriksaan setiap pengajuan.
            $table->string('status', 20)->default('pending');

            // Admin yang melakukan pemeriksaan.
            $table->unsignedBigInteger('reviewed_by')->nullable();

            // Waktu admin menyelesaikan pemeriksaan.
            $table->timestamp('reviewed_at')->nullable();

            // Alasan penolakan dari admin.
            $table->text('rejection_reason')->nullable();

            $table->timestamps();

            // Foreign key ke tabel pendaftaran kompetisi.
            $table->foreign('registration_id', 'fk_submissions_registration')
                ->references('id')
                ->on('competition_registrations')
                ->cascadeOnDelete();

            // Foreign key ke tabel users untuk identitas admin pemeriksa.
            $table->foreign('reviewed_by', 'fk_submissions_reviewer')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            // Satu nomor pengajuan hanya boleh digunakan sekali per pendaftaran.
            $table->unique(
                ['registration_id', 'submission_number'],
                'uq_submission_registration_number'
            );

            // Mempercepat pencarian pengajuan berdasarkan status.
            $table->index('status', 'idx_submissions_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_submissions');
    }
};
