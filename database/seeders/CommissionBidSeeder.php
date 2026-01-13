<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domain\Bidding\Models\CommissionBid;
use App\Domain\Lots\Models\Lot;
use App\Models\User;

class CommissionBidSeeder extends Seeder
{
    public function run(): void
    {
        $clients = User::query()->where('role', 'client')->get();
        $lots = Lot::query()
            ->whereNotIn('status', ['ARCHIVED', 'WITHDRAWN'])
            ->get();

        if ($clients->isEmpty() || $lots->isEmpty()) {
            $this->command->warn('No clients or lots found; skipping bid seeding.');
            return;
        }

        // Create ~30 bids across the 20 lots (some lots will have multiple bidders)
        $target = min(30, $clients->count() * $lots->count());

        $created = 0;

        while ($created < $target) {
            $client = $clients->random();
            $lot = $lots->random();

            // Ensure unique (user_id, lot_id)
            $exists = CommissionBid::query()
                ->where('user_id', $client->id)
                ->where('lot_id', $lot->id)
                ->exists();

            if ($exists) {
                continue;
            }

            $low = (float) $lot->estimate_low;
            $high = (float) ($lot->estimate_high ?? ($low * 1.5));

            // Max bid somewhere between low and high*1.25 for realism
            $max = rand((int) $low, (int) round($high * 1.25));

            CommissionBid::create([
                'user_id' => $client->id,
                'lot_id' => $lot->id,
                'max_bid_amount' => $max,
            ]);

            $created++;
        }

        $this->command->info("Seeded {$created} commission bids.");
    }
}
