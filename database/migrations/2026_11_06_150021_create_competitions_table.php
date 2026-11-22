
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();

            // Informasi utama kompetisi
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();

            // Jenis dan status kompetisi
            $table->string('type', 20);
            $table->string('status', 30)->default('upcoming');
            $table->boolean('is_active')->default(true);

            // Jadwal pendaftaran dan pelaksanaan
            $table->dateTime('registration_start')->nullable();
            $table->dateTime('registration_end')->nullable();
            $table->dateTime('competition_start')->nullable();
            $table->dateTime('competition_end')->nullable();

            // Pengaturan anggota tim
            $table->unsignedSmallInteger('min_team_members')->nullable();
            $table->unsignedSmallInteger('max_team_members')->nullable();

            // Kuota dan gambar kompetisi
            $table->unsignedInteger('quota')->nullable();
            $table->string('thumbnail')->nullable();

            // Timestamp dan soft delete
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competitions');
    }
};
