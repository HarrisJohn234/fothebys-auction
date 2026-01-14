<?php

namespace App\Application\Auctions\Services;

use App\Domain\Auctions\Models\Auction;
use App\Domain\Lots\Models\Lot;
use Illuminate\Support\Facades\DB;

class AuctionLifecycleService
{
    /**
     * Close any LIVE auctions that have ended.
     * For each lot in the auction:
     * - If bids exist: create a sale (highest bid wins) and mark lot SOLD
     * - If no bids: create UNSOLD sale and mark lot UNSOLD
     */
    public function closeEndedAuctions(): int
    {
        $auctions = Auction::query()
            ->where('status', 'LIVE')
            ->whereNotNull('ends_at')
            ->where('ends_at', '<=', now())
            ->get();

        if ($auctions->isEmpty()) {
            return 0;
        }

        $closedCount = 0;

        DB::transaction(function () use ($auctions, &$closedCount) {
            foreach ($auctions as $auction) {
                $auction->update(['status' => 'CLOSED']);

                $lots = Lot::query()
                    ->where('auction_id', $auction->id)
                    ->whereNotIn('status', ['ARCHIVED', 'WITHDRAWN'])
                    ->get();

                foreach ($lots as $lot) {
                    $topBid = DB::table('bids')
                        ->where('lot_id', $lot->id)
                        ->orderByDesc('max_bid_amount')
                        ->first();

                    if (!$topBid) {
                        // Create UNSOLD sale record (one per lot)
                        DB::table('sales')->updateOrInsert(
                            ['lot_id' => $lot->id],
                            [
                                'client_id' => null,
                                'hammer_price' => null,
                                'commission_amount' => 0,
                                'status' => 'UNSOLD',
                                'updated_at' => now(),
                                'created_at' => now(),
                            ]
                        );

                        $lot->update(['status' => 'UNSOLD']);
                        continue;
                    }

                    $hammer = (float) $topBid->max_bid_amount;
                    $commission = round($hammer * 0.10, 2); // simple sprint rule: 10%

                    DB::table('sales')->updateOrInsert(
                        ['lot_id' => $lot->id],
                        [
                            'client_id' => $topBid->client_id,
                            'hammer_price' => $hammer,
                            'commission_amount' => $commission,
                            'status' => 'COMPLETED',
                            'updated_at' => now(),
                            'created_at' => now(),
                        ]
                    );

                    $lot->update(['status' => 'SOLD']);

                    // Mark bids
                    DB::table('bids')->where('lot_id', $lot->id)->update(['status' => 'LOST']);
                    DB::table('bids')->where('id', $topBid->id)->update(['status' => 'WON']);
                }

                $closedCount++;
            }
        });

        return $closedCount;
    }
}
