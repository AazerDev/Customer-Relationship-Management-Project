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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('set null');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->unique();
            $table->string('company_name')->nullable();
            $table->string('website')->nullable();
            $table->string('industry')->nullable();
            $table->string('employees')->nullable();
            $table->text('address')->nullable();
            $table->string('billing_email')->nullable();
            $table->json('notification_preferences')->nullable();
            $table->json('notification_channels')->nullable();
            $table->string('digest_frequency')->default('Weekly');
            $table->string('quiet_hours_start')->default('21:00');
            $table->string('quiet_hours_end')->default('07:00');
            $table->json('custom_fields')->nullable();
            $table->json('api_keys')->nullable();
            $table->timestamps();

            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
