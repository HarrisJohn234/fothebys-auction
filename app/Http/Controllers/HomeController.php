<?php

namespace App\Http\Controllers;

use App\Application\Auctions\Services\AuctionLifecycleService;
use App\Domain\Auctions\Enums\AuctionStatus;
use App\Domain\Auctions\Models\Auction;
use App\Domain\Lots\Models\Lot;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController
{
    public function index(Request $request, AuctionLifecycleService $lifecycle): View
    {
        // Keep auction statuses consistent (auto-close ended LIVE auctions)
        $lifecycle->closeEndedAuctions();

        $now = now();

        /**
         * Define which auction statuses are "discoverable" on the public homepage.
         * We purposely do NOT include DRAFT/CLOSED/ARCHIVED here.
         *
         * NOTE: Homepage sections (Upcoming/Ongoing) are time-driven, not status-driven.
         * This prevents a future-dated auction marked LIVE from showing in the "ongoing" row.
         */
        $discoverableStatuses = [
            AuctionStatus::SCHEDULED->value,
            AuctionStatus::LIVE->value,
        ];

        // Ongoing auctions (time window: started and not ended)
        $ongoingAuctionsQuery = Auction::query()
            ->whereIn('status', $discoverableStatuses)
            ->whereNotNull('starts_at')
            ->where('starts_at', '<=', $now)
            ->where(function ($q) use ($now) {
                $q->whereNull('ends_at')
                    ->orWhere('ends_at', '>', $now);
            });

        // Live lot highlights (from ongoing auctions only)
        $liveLots = Lot::query()
            ->with(['category', 'auction'])
            ->whereHas('auction', function ($a) use ($discoverableStatuses, $now) {
                $a->whereIn('status', $discoverableStatuses)
                    ->whereNotNull('starts_at')
                    ->where('starts_at', '<=', $now)
                    ->where(function ($q) use ($now) {
                        $q->whereNull('ends_at')
                            ->orWhere('ends_at', '>', $now);
                    });
            })
            ->whereNotIn('status', ['ARCHIVED', 'WITHDRAWN'])
            ->orderByDesc('id')
            ->take(8)
            ->get();

        // Upcoming auctions (time window: starts in the future)
        // This will include auctions even if an admin mistakenly set status=LIVE while starts_at is future.
        $upcomingAuctions = Auction::query()
            ->whereIn('status', $discoverableStatuses)
            ->whereNotNull('starts_at')
            ->where('starts_at', '>', $now)
            ->orderBy('starts_at')
            ->take(6)
            ->get();

        // Ongoing (live) auctions row
        $liveAuctions = $ongoingAuctionsQuery
            ->orderBy('starts_at')
            ->take(6)
            ->get();

        return view('public.home', compact('liveLots', 'upcomingAuctions', 'liveAuctions'));
    }
}
