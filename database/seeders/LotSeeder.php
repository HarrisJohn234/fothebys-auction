<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domain\Lots\Models\Lot;
use App\Domain\Categories\Models\Category;
use App\Domain\Auctions\Models\Auction;
use Illuminate\Support\Str;

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

        for ($i = 1; $i <= 20; $i++) {
            $category = $categories->random();

            Lot::create([
                'lot_number' => str_pad((string) $i, 8, '0', STR_PAD_LEFT),
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
        }

        $this->command->info('20 lots seeded successfully.');
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
}
