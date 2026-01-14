<?php

namespace App\Http\Controllers\Admin;

use App\Application\Bidding\Services\BidService;
use App\Domain\Bidding\Models\CommissionBid;
use Illuminate\Http\RedirectResponse;

class AdminBidController
{
    public function __construct(private BidService $bidService) {}

    public function index()
    {
        $bids = CommissionBid::query()
            ->with(['lot', 'client'])
            ->latest()
            ->paginate(20);

        return view('admin.bids.index', compact('bids'));
    }

    public function accept(CommissionBid $bid): RedirectResponse
    {
        $this->bidService->accept($bid);
        return back()->with('success', 'Bid accepted.');
    }

    public function reject(CommissionBid $bid): RedirectResponse
    {
        $this->bidService->reject($bid);
        return back()->with('failure', 'Bid rejected.');
    }
}
