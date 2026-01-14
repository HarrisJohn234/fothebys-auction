<?php

namespace App\Http\Controllers;

use App\Application\Auctions\Services\AuctionLifecycleService;
use App\Domain\Auctions\Models\Auction;
use App\Domain\Lots\Models\Lot;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController
{
    public function index(Request $request, AuctionLifecycleService $lifecycle): View
    {
        // Keep auction statuses consistent (your catalogue already does this)
        $lifecycle->closeEndedAuctions();

        // Live lot highlights (from LIVE auctions only)
        $liveLots = Lot::query()
            ->with(['category', 'auction'])
            ->whereHas('auction', fn ($a) => $a->where('status', 'LIVE'))
            ->whereNotIn('status', ['ARCHIVED', 'WITHDRAWN'])
            ->orderByDesc('id')
            ->take(8)
            ->get();

        // Upcoming auctions (scheduled and in the future)
        $upcomingAuctions = Auction::query()
            ->where('status', 'SCHEDULED')
            ->whereNotNull('starts_at')
            ->where('starts_at', '>', now())
            ->orderBy('starts_at')
            ->take(6)
            ->get();

        // Live auctions
        $liveAuctions = Auction::query()
            ->where('status', 'LIVE')
            ->orderByDesc('starts_at')
            ->take(6)
            ->get();

        return view('public.home', compact('liveLots', 'upcomingAuctions', 'liveAuctions'));
    }
}
