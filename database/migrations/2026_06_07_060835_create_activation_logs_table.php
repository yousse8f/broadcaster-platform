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
        Schema::create('activation_logs', function (Blueprint $table) {
            $table->id();
            $table->string('license_key', 100);
            $table->string('device_id', 100);
            $table->string('device_name')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->boolean('success')->default(false);
            $table->string('message')->nullable();
            $table->foreignId('license_id')->nullable()->constrained('licenses')->onDelete('set null')->onUpdate('cascade');
            $table->timestamp('created_at')->useCurrent();
            
            // Indexes for better performance
            $table->index('license_key');
            $table->index('device_id');
            $table->index('success');
            $table->index('created_at');
            $table->index('license_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activation_logs');
    }
};
