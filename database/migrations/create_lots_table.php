<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('lots', function (Blueprint $table) {
            $table->id();

            $table->string('lot_number', 8)->unique(); // 8-digit serial, immutable

            // Mandatory across all categories (assignment brief)
            $table->string('artist_name');
            $table->unsignedSmallInteger('year_produced');
            $table->string('subject_classification'); // landscape/portrait/etc.
            $table->text('description');

            // Estimated price (we support low/high for better UX; high nullable)
            $table->unsignedInteger('estimate_low');
            $table->unsignedInteger('estimate_high')->nullable();

            // When known (assignment brief)
            $table->date('auction_date')->nullable();

            $table->foreignId('category_id')->constrained('categories');

            // Category-specific attributes stored as JSON
            $table->json('category_metadata');

            // Optional link to an auction
            $table->foreignId('auction_id')->nullable()->constrained('auctions');

            $table->string('status')->default('PENDING'); // PENDING | IN_AUCTION | SOLD | WITHDRAWN | ARCHIVED
            $table->timestamps();

            $table->index(['artist_name', 'subject_classification']);
            $table->index(['auction_id', 'status']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('lots');
    }
};
