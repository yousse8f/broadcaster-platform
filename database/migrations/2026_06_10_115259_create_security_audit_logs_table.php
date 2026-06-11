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
        Schema::create('security_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('event_type', ['failed_login', 'failed_activation', 'api_abuse', 'blocked_request', 'rate_limit_exceeded']);
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('license_key')->nullable();
            $table->string('device_id')->nullable();
            $table->text('details')->nullable();
            $table->string('severity')->default('low'); // low, medium, high, critical
            $table->timestamp('occurred_at');
            $table->timestamps();

            $table->index('event_type');
            $table->index('ip_address');
            $table->index('occurred_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_audit_logs');
    }
};
