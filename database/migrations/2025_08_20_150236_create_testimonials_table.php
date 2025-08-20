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
    // user_id BIGINT UNSIGNED NOT NULL,
    // content TEXT NOT NULL,
    // rating INT DEFAULT 5 CHECK (rating >= 1 AND rating <= 5),
    // is_featured BOOLEAN DEFAULT FALSE,
    // is_published BOOLEAN DEFAULT FALSE,
    // program_id BIGINT UNSIGNED NULL,
    // event_id BIGINT UNSIGNED NULL,
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('content');
            $table->integer('rating')->default(5);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
