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
        Schema::table('devices', function (Blueprint $table) {
            // Add status column before dropping is_active
            $table->enum('status', ['active', 'suspended', 'revoked'])->default('active')->after('ip_address');

            // Add first_activated_at
            $table->timestamp('first_activated_at')->nullable()->after('status');

            // Add operating_system
            $table->string('operating_system')->nullable()->after('first_activated_at');

            // Add index for status
            $table->index('status');
        });

        // Drop the index on is_active column first
        Schema::table('devices', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });

        // Drop the old is_active column (we'll use status now)
        Schema::table('devices', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back is_active with index
        Schema::table('devices', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('last_seen');
            $table->index('is_active');
        });

        // Drop the new columns and status index
        Schema::table('devices', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropColumn(['status', 'first_activated_at', 'operating_system']);
        });
    }
};
