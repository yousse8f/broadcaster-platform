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
            
            // General Settings
            $table->string('company_name')->default('Broadcast Platform');
            $table->string('support_email')->nullable();
            $table->string('support_url')->nullable();
            $table->string('timezone')->default('UTC');
            $table->string('logo')->nullable();
            
            // License Settings
            $table->integer('default_expiration')->default(365)->comment('Default license expiration in days');
            $table->integer('default_devices_limit')->default(1)->comment('Default allowed devices per license');
            $table->integer('heartbeat_timeout')->default(300)->comment('Heartbeat timeout in seconds');
            $table->integer('validation_timeout')->default(60)->comment('Validation timeout in seconds');
            
            // Security Settings
            $table->integer('rate_limit')->default(60)->comment('Rate limit per minute');
            $table->integer('max_activations_per_day')->default(10)->comment('Maximum activations per day per IP');
            $table->string('api_secret')->nullable()->comment('API secret for external integrations');
            $table->text('allowed_origins')->nullable()->comment('JSON array of allowed CORS origins');
            
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('company_name');
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
