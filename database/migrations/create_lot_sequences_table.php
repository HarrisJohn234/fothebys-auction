<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('lot_sequences', function (Blueprint $table) {
            $table->id(); // auto-increment => serial source for lot_number
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('lot_sequences');
    }
};
