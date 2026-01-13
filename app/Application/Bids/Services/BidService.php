<?php

namespace App\Application\Bids\Services;

use App\Domain\Bids\Enums\BidStatus;
use App\Domain\Bids\Models\Bid;
use App\Domain\Lots\Models\Lot;

class BidService
{
    public function submitCommissionBid(Lot $lot, int $clientId, int $maxBidAmount): Bid
    {
        return Bid::updateOrCreate(
            ['lot_id' => $lot->id, 'client_id' => $clientId],
            ['max_bid_amount' => $maxBidAmount, 'status' => BidStatus::PENDING->value]
        );
    }

    public function accept(Bid $bid): Bid
    {
        $bid->update(['status' => BidStatus::ACCEPTED->value]);
        return $bid->refresh();
    }

    public function reject(Bid $bid): Bid
    {
        $bid->update(['status' => BidStatus::REJECTED->value]);
        return $bid->refresh();
    }

    public function cancel(Bid $bid, int $clientId): Bid
    {
        abort_unless($bid->client_id === $clientId, 403);

        $bid->update(['status' => BidStatus::CANCELLED->value]);
        return $bid->refresh();
    }
}
