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
        Schema::create('partners', function (Blueprint $table) {
    // name VARCHAR(255) NOT NULL,
    // slug VARCHAR(255) UNIQUE NOT NULL,
    // description TEXT NULL,
    // logo VARCHAR(255) NULL,
    // website_url VARCHAR(255) NULL,
    // contact_email VARCHAR(255) NULL,
    // contact_phone VARCHAR(20) NULL,
    // partnership_type ENUM('corporate', 'educational', 'government', 'startup') NOT NULL,
    // is_active BOOLEAN DEFAULT TRUE,
    // is_featured BOOLEAN DEFAULT FALSE,
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('website_url')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone', 20)->nullable();
            $table->enum('partnership_type', ['corporate', 'educational', 'government', 'startup']);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
