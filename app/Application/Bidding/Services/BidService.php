<?php

namespace App\Application\Bidding\Services;

use App\Domain\Bidding\Enums\BidStatus;
use App\Domain\Bidding\Models\CommissionBid;
use App\Domain\Lots\Models\Lot;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BidService
{
    /**
     * Places (or updates) a commission bid for (user, lot).
     * DB schema enforces uniqueness, so we update existing.
     */
    public function placeCommissionBid(User $user, Lot $lot, int $maxBidAmount): CommissionBid
    {
        return DB::transaction(function () use ($user, $lot, $maxBidAmount) {
            return CommissionBid::updateOrCreate(
                [
                    'client_id' => $user->id,
                    'lot_id' => $lot->id,
                ],
                [
                    'max_bid_amount' => $maxBidAmount,
                    'status' => BidStatus::PENDING->value,
                ]
            );
        });
    }

    public function accept(CommissionBid $bid): CommissionBid
    {
        $bid->update(['status' => BidStatus::ACCEPTED->value]);
        return $bid;
    }

    public function reject(CommissionBid $bid): CommissionBid
    {
        $bid->update(['status' => BidStatus::REJECTED->value]);
        return $bid;
    }
}
