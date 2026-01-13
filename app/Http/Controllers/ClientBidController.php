<?php

namespace App\Http\Controllers;

use App\Application\Bidding\Services\BidService;
use App\Domain\Lots\Models\Lot;
use App\Http\Requests\BidStoreRequest;
use Illuminate\Http\RedirectResponse;

class ClientBidController extends Controller
{
    public function __construct(private readonly BidService $bidService)
    {
        $this->middleware('auth');
    }

    public function store(BidStoreRequest $request, Lot $lot): RedirectResponse
    {
        $this->bidService->placeCommissionBid(
            $request->user(),
            $lot,
            (int) $request->validated('max_bid_amount')
        );

        return back()->with('success', 'Commission bid submitted.');
    }
}
