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
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5)->unique(); // e.g., 'en', 'es', 'fr'
            $table->string('name'); // e.g., 'English', 'Spanish', 'French'
            $table->string('native_name')->nullable(); // e.g., 'English', 'Español', 'Français'
            $table->string('flag')->nullable(); // Flag emoji or icon path
            $table->boolean('is_rtl')->default(false); // Right-to-left languages
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            // Indexes
            $table->index(['is_active', 'sort_order']);
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
