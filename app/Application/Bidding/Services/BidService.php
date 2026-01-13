<?php

namespace App\Application\Bidding\Services;

use App\Domain\Bidding\Models\CommissionBid;
use App\Domain\Bidding\Enums\BidStatus;
use App\Domain\Lots\Models\Lot;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BidService
{
    /**
     * Create a commission bid for a lot.
     * Business rule: Only ONE ACTIVE bid per (user, lot).
     */
    public function placeCommissionBid(User $user, Lot $lot, float $maxBidAmount): CommissionBid
    {
        return DB::transaction(function () use ($user, $lot, $maxBidAmount) {
            $existingActive = CommissionBid::query()
                ->where('user_id', $user->id)
                ->where('lot_id', $lot->id)
                ->where('status', BidStatus::ACTIVE->value)
                ->first();

            if ($existingActive) {
                throw ValidationException::withMessages([
                    'max_bid_amount' => 'You already have an active commission bid for this lot.',
                ]);
            }

            return CommissionBid::create([
                'user_id' => $user->id,
                'lot_id' => $lot->id,
                'max_bid_amount' => $maxBidAmount,
                'status' => BidStatus::ACTIVE->value,
                'placed_at' => now(),
            ]);
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
