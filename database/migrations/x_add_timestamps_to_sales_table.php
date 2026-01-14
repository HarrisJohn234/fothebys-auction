<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * This project originally created the sales table without timestamps.
     *
     * The AuctionLifecycleService performs idempotent upserts and explicitly
     * writes created_at / updated_at, so those columns must exist.
     *
     * This migration is safe to run multiple times.
     */
    public function up(): void
    {
        if (!Schema::hasTable('sales')) {
            return;
        }

        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }
            if (!Schema::hasColumn('sales', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Intentionally no-op.
        // Dropping columns is not supported consistently across SQLite versions,
        // and timestamps are required for this module.
    }
};
