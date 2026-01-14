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
            $this->command->warn('CommissionBidSeeder: No clients or lots found; skipping.');
            return;
        }

        // Create ~30 bids across the available lots
        $target = min(30, $clients->count() * $lots->count());
        $created = 0;
        $attempts = 0;
        $maxAttempts = 2000;

        while ($created < $target && $attempts < $maxAttempts) {
            $attempts++;

            $client = $clients->random();
            $lot = $lots->random();

            // Ensure unique (client_id, lot_id)
            $exists = CommissionBid::query()
                ->where('client_id', $client->id)
                ->where('lot_id', $lot->id)
                ->exists();

            if ($exists) {
                continue;
            }

            $low = (float) $lot->estimate_low;
            $high = (float) ($lot->estimate_high ?? ($low * 1.5));
            $max = rand((int) $low, (int) round($high * 1.25));

            CommissionBid::create([
                'client_id' => $client->id,
                'lot_id' => $lot->id,
                'max_bid_amount' => $max,
                'status' => 'PENDING',
            ]);

            $created++;
        }

        $this->command->info("CommissionBidSeeder: seeded {$created} bids (attempts: {$attempts}).");
    }
}
