<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('auctions', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->dateTime('starts_at')->nullable();
        $table->dateTime('ends_at')->nullable();   
        $table->string('status')->default('DRAFT');
        $table->timestamps();
    });
    }

    public function down(): void {
        Schema::dropIfExists('auctions');
    }
};
