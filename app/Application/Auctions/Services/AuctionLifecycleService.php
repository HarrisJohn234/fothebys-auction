<?php

namespace App\Application\Auctions\Services;

use App\Domain\Auctions\Models\Auction;
use App\Domain\Lots\Models\Lot;
use Illuminate\Support\Facades\DB;

class AuctionLifecycleService
{
    /**
     * Close any LIVE auctions that have ended.
     * When an auction closes, mark all its lots SOLD (demo-friendly default).
     */
    public function closeEndedAuctions(): int
    {
        $ended = Auction::query()
            ->where('status', 'LIVE')
            ->whereNotNull('ends_at')
            ->where('ends_at', '<=', now())
            ->get();

        if ($ended->isEmpty()) {
            return 0;
        }

        $count = 0;

        DB::transaction(function () use ($ended, &$count) {
            foreach ($ended as $auction) {
                $auction->update(['status' => 'CLOSED']);

                Lot::query()
                    ->where('auction_id', $auction->id)
                    ->whereNotIn('status', ['ARCHIVED', 'WITHDRAWN'])
                    ->update(['status' => 'SOLD']);

                $count++;
            }
        });

        return $count;
    }
}
