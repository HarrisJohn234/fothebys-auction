<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domain\Auctions\Models\Auction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class AuctionSeeder extends Seeder
{
    public function run(): void
    {
        Storage::disk('public')->makeDirectory('auctions');

        $a1 = Auction::create([
            'title' => 'January Weekly Auction (LIVE)',
            'starts_at' => Carbon::now()->subDays(1)->setTime(10, 0),
            'ends_at' => Carbon::now()->addDays(6)->setTime(18, 0),
            'status' => 'LIVE',
        ]);

        $a2 = Auction::create([
            'title' => 'February Weekly Auction (DRAFT)',
            'starts_at' => Carbon::now()->addDays(14)->setTime(10, 0),
            'ends_at' => Carbon::now()->addDays(20)->setTime(18, 0),
            'status' => 'DRAFT',
        ]);

        $a3 = Auction::create([
            'title' => 'December Clearance Auction (CLOSED)',
            'starts_at' => Carbon::now()->subDays(40)->setTime(10, 0),
            'ends_at' => Carbon::now()->subDays(33)->setTime(18, 0),
            'status' => 'CLOSED',
        ]);

        $this->seedAuctionImage($a1);
        $this->seedAuctionImage($a2);
        $this->seedAuctionImage($a3);
    }

    private function seedAuctionImage(Auction $auction): void
    {
        $safeTitle = preg_replace('/[^a-z0-9\-]+/i', '-', strtolower($auction->title));
        $file = "auctions/auction-{$auction->id}-{$safeTitle}.svg";

        $svg = $this->svgCard(
            title: "Auction #{$auction->id}",
            subtitle: $auction->title
        );

        Storage::disk('public')->put($file, $svg);
        $auction->update(['image_path' => $file]);
    }

    private function svgCard(string $title, string $subtitle): string
    {
        $title = htmlspecialchars($title, ENT_QUOTES);
        $subtitle = htmlspecialchars($subtitle, ENT_QUOTES);

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="800" height="800">
  <defs>
    <linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="#111827"/>
      <stop offset="100%" stop-color="#374151"/>
    </linearGradient>
  </defs>
  <rect width="800" height="800" fill="url(#g)"/>
  <rect x="60" y="60" width="680" height="680" rx="32" fill="#ffffff" opacity="0.08"/>
  <text x="90" y="160" font-family="Arial, sans-serif" font-size="48" fill="#ffffff">{$title}</text>
  <text x="90" y="230" font-family="Arial, sans-serif" font-size="28" fill="#e5e7eb">{$subtitle}</text>
  <text x="90" y="710" font-family="Arial, sans-serif" font-size="20" fill="#d1d5db">Fothebys Demo Image</text>
</svg>
SVG;
    }
}
