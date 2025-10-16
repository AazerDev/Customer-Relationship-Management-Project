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
        Schema::create('notifies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('set null');
            $table->unsignedBigInteger('sender_id')->nullable()->constrained('users')->onDelete('set null');
            $table->unsignedBigInteger('receiver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('title')->nullable();
            $table->string('message')->nullable();
            $table->enum('is_active', [1, 0])->default(1)->comment('1 or 0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifies');
    }
};
