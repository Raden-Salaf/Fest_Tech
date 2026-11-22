<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();

            // Relasi dengan admin penulis berita
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            // Informasi utama berita
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');

            // Media dan kategori
            $table->string('thumbnail')->nullable();
            $table->string('category')->nullable();

            // Status publikasi
            $table->enum('status', ['draft', 'published'])
                ->default('draft');
            $table->timestamp('published_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index untuk pencarian dan filter
            $table->index(['status', 'published_at']);
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
