<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domain\Lots\Models\Lot;
use App\Domain\Categories\Models\Category;
use App\Domain\Auctions\Models\Auction;
use Illuminate\Support\Facades\Storage;

class LotSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        $auction = Auction::where('status', 'LIVE')->first();

        if ($categories->isEmpty()) {
            $this->command->error('No categories found. Run CategorySeeder first.');
            return;
        }

        Storage::disk('public')->makeDirectory('lots');

        for ($i = 1; $i <= 20; $i++) {
            $category = $categories->random();
            $lotNumber = str_pad((string) $i, 8, '0', STR_PAD_LEFT);

            $lot = Lot::create([
                'lot_number' => $lotNumber,
                'artist_name' => $this->randomArtist(),
                'year_produced' => rand(1850, 2020),
                'subject_classification' => $this->randomSubject(),
                'description' => 'An original work demonstrating period style and technique.',
                'estimate_low' => rand(500, 5000),
                'estimate_high' => rand(6000, 15000),
                'category_id' => $category->id,
                'category_metadata' => $this->metadataForCategory($category->slug),
                'auction_id' => $auction?->id,
                'status' => 'IN_AUCTION',
            ]);

            // Give most lots an image, leave some blank to test “no image” layout
            if ($i % 6 !== 0) {
                $file = "lots/{$lotNumber}/lot-{$lotNumber}.svg";
                Storage::disk('public')->put($file, $this->svgCard("Lot #{$lotNumber}", $lot->artist_name));
                $lot->update(['image_path' => $file]);
            }
        }

        $this->command->info('20 lots seeded successfully (with demo images).');
    }

    private function randomArtist(): string
    {
        return collect([
            'J. Turner',
            'Claude Monet',
            'Vincent van Gogh',
            'Georgia O’Keeffe',
            'Pablo Picasso',
            'Henry Moore',
            'Barbara Hepworth',
            'Paul Cézanne',
            'Edgar Degas',
            'Amedeo Modigliani',
        ])->random();
    }

    private function randomSubject(): string
    {
        return collect([
            'Landscape',
            'Portrait',
            'Still Life',
            'Abstract',
            'Figure Study',
            'Urban Scene',
        ])->random();
    }

    private function metadataForCategory(string $slug): array
    {
        return match ($slug) {
            'paintings' => [
                'medium' => 'Oil on canvas',
                'framed' => (bool) rand(0, 1),
            ],
            'drawings' => [
                'medium' => 'Charcoal on paper',
                'signed' => (bool) rand(0, 1),
            ],
            'photographic-images' => [
                'print_type' => 'Gelatin silver print',
                'edition' => rand(1, 50),
            ],
            'sculptures', 'carvings' => [
                'material' => 'Bronze',
                'weight_kg' => rand(5, 150),
            ],
            default => [],
        };
    }

    private function svgCard(string $title, string $subtitle): string
    {
        $title = htmlspecialchars($title, ENT_QUOTES);
        $subtitle = htmlspecialchars($subtitle, ENT_QUOTES);

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="800" height="800">
  <defs>
    <linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="#0f172a"/>
      <stop offset="100%" stop-color="#334155"/>
    </linearGradient>
  </defs>
  <rect width="800" height="800" fill="url(#g)"/>
  <rect x="60" y="60" width="680" height="680" rx="32" fill="#ffffff" opacity="0.08"/>
  <text x="90" y="160" font-family="Arial, sans-serif" font-size="52" fill="#ffffff">{$title}</text>
  <text x="90" y="230" font-family="Arial, sans-serif" font-size="30" fill="#e5e7eb">{$subtitle}</text>
  <text x="90" y="710" font-family="Arial, sans-serif" font-size="20" fill="#d1d5db">Fothebys Demo Image</text>
</svg>
SVG;
    }
}
