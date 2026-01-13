<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domain\Lots\Models\Lot;
use Illuminate\Support\Facades\DB;

class BidSeeder extends Seeder
{
    public function run(): void
    {
        /** @var class-string<\Illuminate\Database\Eloquent\Model> $User */
        $User = config('auth.providers.users.model');

        $clients = $User::query()->where('role', 'client')->get();
        $lots = Lot::query()
            ->whereNotIn('status', ['ARCHIVED', 'WITHDRAWN'])
            ->get();

        $this->command->info('BidSeeder: clients=' . $clients->count() . ', lots=' . $lots->count());

        if ($clients->isEmpty() || $lots->isEmpty()) {
            $this->command->warn('BidSeeder: No clients or lots found; skipping.');
            return;
        }

        $target = min(30, $clients->count() * $lots->count());
        $created = 0;
        $attempts = 0;
        $maxAttempts = 2000; // prevent infinite loop if uniqueness blocks everything

        while ($created < $target && $attempts < $maxAttempts) {
            $attempts++;

            $client = $clients->random();
            $lot = $lots->random();

            $exists = DB::table('bids')
                ->where('client_id', $client->id)
                ->where('lot_id', $lot->id)
                ->exists();

            if ($exists) {
                continue;
            }

            $low = (float) $lot->estimate_low;
            $high = (float) ($lot->estimate_high ?? ($low * 1.5));
            $max = rand((int) $low, (int) round($high * 1.25));

            DB::table('bids')->insert([
                'lot_id' => $lot->id,
                'client_id' => $client->id,
                'max_bid_amount' => $max,
                'status' => 'PENDING',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $created++;
        }

        $this->command->info("BidSeeder: Seeded {$created} bids in {$attempts} attempts.");
    }
}
