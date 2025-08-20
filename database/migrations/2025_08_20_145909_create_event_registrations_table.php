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
    // event_id BIGINT UNSIGNED NOT NULL,
    // user_id BIGINT UNSIGNED NOT NULL,
    // status ENUM('pending', 'confirmed', 'cancelled', 'attended') DEFAULT 'pending',
    // payment_status ENUM('unpaid', 'paid', 'refunded') DEFAULT 'unpaid',
    // payment_method VARCHAR(50) NULL,
    // payment_proof VARCHAR(255) NULL,
    // notes TEXT NULL,
    // registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamp('registered_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
    }
};
