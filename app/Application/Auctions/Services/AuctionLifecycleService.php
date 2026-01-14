<?php

namespace App\Application\Auctions\Services;

use App\Domain\Auctions\Models\Auction;
use App\Domain\Lots\Models\Lot;
use Illuminate\Support\Facades\DB;

class AuctionLifecycleService
{
    /**
     * Close one specific auction and generate sales.
     * Idempotent: safe to call multiple times (won't duplicate sales).
     */
    public function closeAuction(Auction $auction): void
    {
        DB::transaction(function () use ($auction) {
            // Always re-fetch inside transaction (fresh data)
            $auction->refresh();

            // If already closed, do nothing
            if ($auction->status === 'CLOSED') {
                return;
            }

            // Force status to CLOSED (manual close)
            $auction->update([
                'status' => 'CLOSED',
                'ends_at' => $auction->ends_at ?? now(), // ensure ends_at exists for reporting
            ]);

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
                    // UNSOLD sale (one per lot)
                    DB::table('sales')->updateOrInsert(
                        ['lot_id' => $lot->id],
                        [
                            'client_id' => null,
                            'hammer_price' => null,
                            'commission_amount' => 0,
                            'status' => 'UNSOLD',
                            'updated_at' => now(),
                            // only set created_at if it doesn't exist yet
                            'created_at' => DB::raw('COALESCE(created_at, CURRENT_TIMESTAMP)'),
                        ]
                    );

                    $lot->update(['status' => 'UNSOLD']);
                    continue;
                }

                $hammer = (float) $topBid->max_bid_amount;
                $commission = round($hammer * 0.10, 2);

                DB::table('sales')->updateOrInsert(
                    ['lot_id' => $lot->id],
                    [
                        'client_id' => $topBid->client_id,
                        'hammer_price' => $hammer,
                        'commission_amount' => $commission,
                        'status' => 'COMPLETED',
                        'updated_at' => now(),
                        'created_at' => DB::raw('COALESCE(created_at, CURRENT_TIMESTAMP)'),
                    ]
                );

                $lot->update(['status' => 'SOLD']);

                // Mark bids
                DB::table('bids')->where('lot_id', $lot->id)->update(['status' => 'LOST']);
                DB::table('bids')->where('id', $topBid->id)->update(['status' => 'WON']);
            }
        });
    }

    /**
     * Close any LIVE auctions that have ended (scheduled behaviour).
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

        foreach ($auctions as $auction) {
            $this->closeAuction($auction);
        }

        return $auctions->count();
    }
}
