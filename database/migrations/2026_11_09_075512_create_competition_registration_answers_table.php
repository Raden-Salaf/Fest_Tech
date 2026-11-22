<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competition_registration_answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('registration_id')
                ->constrained('competition_registrations')
                ->cascadeOnDelete();

            $table->foreignId('form_field_id')
                ->constrained('competition_form_fields');

            $table->longText('answer')->nullable();

            $table->timestamps();

            $table->unique(
                ['registration_id', 'form_field_id'],
                'unique_registration_form_answer'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competition_registration_answers');
    }
};
