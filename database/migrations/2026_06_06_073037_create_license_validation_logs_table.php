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
        Schema::create('license_validation_logs', function (Blueprint $table) {
            $table->id();
            $table->string('license_key', 100);
            $table->string('ip_address', 45)->nullable();
            $table->enum('result', ['success', 'failure'])->default('failure');
            $table->string('message', 255)->nullable();
            $table->foreignId('license_id')->nullable()->constrained('licenses')->onDelete('set null')->onUpdate('cascade');
            $table->timestamp('created_at')->useCurrent();
            
            // Indexes for better performance
            $table->index('license_key');
            $table->index('result');
            $table->index('created_at');
            $table->index('license_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('license_validation_logs');
    }
};
