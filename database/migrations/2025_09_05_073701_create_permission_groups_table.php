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
        Schema::create('permission_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('set null');
            $table->foreignId('creator_id')->nullable()->constrained('users')->onDelete('set null');
            $table->longText('name')->nullable();
            $table->longText('permissions')->nullable();
            $table->string('type')->nullable();
            $table->decimal('price', 10, 2)->nullable();   // subscription price
            $table->integer('duration')->nullable(); // subscription type
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_groups');
    }
};
