<?php

namespace App\Http\Controllers\Client;

use App\Application\Bids\Services\BidService;
use App\Domain\Lots\Models\Lot;
use App\Http\Requests\Client\BidStoreRequest;

class ClientBidController
{
    public function __construct(private BidService $bidService) {}

    public function store(BidStoreRequest $request, Lot $lot)
    {
        $this->bidService->submitCommissionBid(
            $lot,
            auth()->id(),
            (int) $request->validated()['max_bid_amount']
        );

        return redirect()->route('public.lots.show', $lot)
            ->with('status', 'Commission bid submitted.');
    }
}
