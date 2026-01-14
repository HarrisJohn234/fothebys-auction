<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            // One sale per lot
            $table->foreignId('lot_id')->constrained()->cascadeOnDelete()->unique();

            // Winning client (nullable if UNSOLD)
            $table->foreignId('client_id')->nullable()->constrained('users')->nullOnDelete();

            // Prices/commission
            $table->decimal('hammer_price', 10, 2)->nullable();
            $table->decimal('commission_amount', 10, 2)->default(0);

            // Status: COMPLETED / UNSOLD (simple for sprint)
            $table->string('status')->default('COMPLETED');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
