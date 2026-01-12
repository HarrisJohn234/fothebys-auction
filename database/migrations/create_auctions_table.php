<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('theme')->nullable();
            $table->string('auction_type')->default('PHYSICAL'); // PHYSICAL | ONLINE_ONLY
            $table->timestamp('starts_at')->nullable();
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->string('status')->default('DRAFT'); // DRAFT | SCHEDULED | LIVE | CLOSED | ARCHIVED
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('auctions');
    }
};
