<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('lots', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('auction_id');
        });

        Schema::table('auctions', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('lots', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });

        Schema::table('auctions', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
    }
};
