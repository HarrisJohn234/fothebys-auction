<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lot_id')->constrained('lots')->unique();
            $table->foreignId('buyer_id')->constrained('users');

            $table->unsignedInteger('hammer_price');

            // store applied rate for audit/traceability
            $table->decimal('buyer_premium_rate', 5, 4); // e.g. 0.1500
            $table->unsignedInteger('buyer_premium_amount');
            $table->unsignedInteger('total_due');

            $table->timestamp('sold_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('sales');
    }
};
