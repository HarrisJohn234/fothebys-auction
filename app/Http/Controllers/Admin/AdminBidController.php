<?php

namespace App\Http\Controllers\Admin;

use App\Application\Bids\Services\BidService;
use App\Domain\Bids\Models\Bid;

class AdminBidController
{
    public function __construct(private BidService $bidService) {}

    public function index()
    {
        $bids = Bid::query()->with(['lot','client'])->latest()->paginate(20);
        return view('admin.bids.index', compact('bids'));
    }

    public function accept(Bid $bid)
    {
        $this->bidService->accept($bid);
        return redirect()->route('admin.bids.index');
    }

    public function reject(Bid $bid)
    {
        $this->bidService->reject($bid);
        return redirect()->route('admin.bids.index');
    }
}
