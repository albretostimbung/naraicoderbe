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
    // title VARCHAR(255) NOT NULL,
    // slug VARCHAR(255) UNIQUE NOT NULL,
    // description TEXT NULL,
    // content LONGTEXT NULL,
    // featured_image VARCHAR(255) NULL,
    // event_type ENUM('bootcamp', 'workshop', 'seminar', 'mentoring', 'meetup') NOT NULL,
    // status ENUM('draft', 'published', 'cancelled', 'completed') DEFAULT 'draft',
    // start_date DATETIME NOT NULL,
    // end_date DATETIME NOT NULL,
    // location VARCHAR(255) NULL,
    // is_online BOOLEAN DEFAULT FALSE,
    // meeting_link VARCHAR(255) NULL,
    // max_participants INT NULL,
    // registration_fee DECIMAL(10,2) DEFAULT 0,
    // registration_deadline DATETIME NULL,
    // organizer_id BIGINT UNSIGNED NOT NULL,
    // created_by BIGINT UNSIGNED NOT NULL,
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('featured_image')->nullable();
            $table->enum('event_type', ['bootcamp', 'workshop', 'seminar', 'mentoring', 'meetup']);
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->string('location')->nullable();
            $table->boolean('is_online')->default(false);
            $table->string('meeting_link')->nullable();
            $table->datetime('registration_deadline')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
