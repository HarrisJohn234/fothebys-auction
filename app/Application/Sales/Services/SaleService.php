<?php

namespace App\Application\Sales\Services;

use App\Domain\Lots\Models\Lot;
use App\Domain\Sales\Models\Sale;
use Illuminate\Support\Facades\DB;

class SaleService
{
    public function recordSale(Lot $lot, int $buyerId, int $hammerPrice): Sale
    {
        return DB::transaction(function () use ($lot, $buyerId, $hammerPrice) {
            $rate = (float) config('fees.buyer_premium_rate', 0.15);

            $buyerPremiumAmount = (int) round($hammerPrice * $rate);
            $totalDue = $hammerPrice + $buyerPremiumAmount;

            $sale = Sale::updateOrCreate(
                ['lot_id' => $lot->id],
                [
                    'buyer_id' => $buyerId,
                    'hammer_price' => $hammerPrice,
                    'buyer_premium_rate' => $rate,
                    'buyer_premium_amount' => $buyerPremiumAmount,
                    'total_due' => $totalDue,
                    'sold_at' => now(),
                ]
            );

            $lot->update(['status' => 'SOLD']);

            return $sale->refresh();
        });
    }
}
