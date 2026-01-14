<?php

namespace App\Application\Auctions\Services;

use App\Domain\Auctions\Models\Auction;
use App\Domain\Lots\Models\Lot;
use Illuminate\Support\Facades\DB;

class AuctionLifecycleService
{
    /**
     * Manual close: closes this auction and generates sales.
     * Idempotent: safe to click multiple times; it will not create duplicates.
     */
    public function closeAuction(Auction $auction): void
    {
        DB::transaction(function () use ($auction) {
            $auction->refresh();

            if ($auction->status === 'CLOSED') {
                return;
            }

            $auction->update([
                'status' => 'CLOSED',
                'ends_at' => $auction->ends_at ?? now(),
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
                    $this->upsertSale(
                        lotId: $lot->id,
                        clientId: null,
                        hammerPrice: null,
                        commissionAmount: 0,
                        status: 'UNSOLD'
                    );

                    $lot->update(['status' => 'UNSOLD']);
                    continue;
                }

                $hammer = (float) $topBid->max_bid_amount;
                $commission = round($hammer * 0.10, 2);

                $this->upsertSale(
                    lotId: $lot->id,
                    clientId: $topBid->client_id,
                    hammerPrice: $hammer,
                    commissionAmount: $commission,
                    status: 'COMPLETED'
                );

                $lot->update(['status' => 'SOLD']);

                DB::table('bids')->where('lot_id', $lot->id)->update(['status' => 'LOST']);
                DB::table('bids')->where('id', $topBid->id)->update(['status' => 'WON']);
            }
        });
    }

    /**
     * Scheduled close: closes any LIVE auctions that have ended.
     */
    public function closeEndedAuctions(): int
    {
        $auctions = Auction::query()
            ->where('status', 'LIVE')
            ->whereNotNull('ends_at')
            ->where('ends_at', '<=', now())
            ->get();

        foreach ($auctions as $auction) {
            $this->closeAuction($auction);
        }

        return $auctions->count();
    }

    /**
     * Insert if missing; update if exists. No COALESCE.
     */
    private function upsertSale(
        int $lotId,
        ?int $clientId,
        ?float $hammerPrice,
        float $commissionAmount,
        string $status
    ): void {
        $exists = DB::table('sales')->where('lot_id', $lotId)->exists();

        if ($exists) {
            DB::table('sales')->where('lot_id', $lotId)->update([
                'client_id' => $clientId,
                'hammer_price' => $hammerPrice,
                'commission_amount' => $commissionAmount,
                'status' => $status,
                'updated_at' => now(),
            ]);
            return;
        }

        DB::table('sales')->insert([
            'lot_id' => $lotId,
            'client_id' => $clientId,
            'hammer_price' => $hammerPrice,
            'commission_amount' => $commissionAmount,
            'status' => $status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
