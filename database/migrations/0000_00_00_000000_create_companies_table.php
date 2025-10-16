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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id')->nullable()->constrained('roles')->onDelete('set null');
            $table->string('admin_email');
            $table->mediumText('logo')->nullable();
            $table->string('company_name');
            $table->string('subdomain')->unique();
            $table->timestamp('package_start_at')->nullable();
            $table->timestamp('package_end_at')->nullable();
            $table->json('ai_models')->nullable();
            $table->json('feature_modules')->nullable();
            $table->string('status')->default('pending'); // pending, active, suspended, expired
            $table->timestamp('onboarded_at')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
