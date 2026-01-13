<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domain\Auctions\Models\Auction;
use Carbon\Carbon;

class AuctionSeeder extends Seeder
{
    public function run(): void
    {
        // Create 3 auctions: one LIVE, one DRAFT, one CLOSED
        Auction::create([
            'title' => 'January Weekly Auction (LIVE)',
            'starts_at' => Carbon::now()->subDays(1)->setTime(10, 0),
            'ends_at' => Carbon::now()->addDays(6)->setTime(18, 0),
            'status' => 'LIVE',
        ]);

        Auction::create([
            'title' => 'February Weekly Auction (DRAFT)',
            'starts_at' => Carbon::now()->addDays(14)->setTime(10, 0),
            'ends_at' => Carbon::now()->addDays(20)->setTime(18, 0),
            'status' => 'DRAFT',
        ]);

        Auction::create([
            'title' => 'December Clearance Auction (CLOSED)',
            'starts_at' => Carbon::now()->subDays(40)->setTime(10, 0),
            'ends_at' => Carbon::now()->subDays(33)->setTime(18, 0),
            'status' => 'CLOSED',
        ]);
    }
}
