<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lot_id')->constrained('lots');
            $table->foreignId('client_id')->constrained('users');

            // commission bid (max the client authorises)
            $table->unsignedInteger('max_bid_amount');

            $table->string('status')->default('PENDING'); // PENDING | ACCEPTED | REJECTED | CANCELLED

            $table->timestamps();

            $table->index(['lot_id', 'status']);
            $table->unique(['lot_id', 'client_id']); // simplifies v1: 1 bid per client per lot
        });
    }

    public function down(): void {
        Schema::dropIfExists('bids');
    }
};
