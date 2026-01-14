<?php

namespace App\Http\Controllers;

use App\Application\Auctions\Services\AuctionLifecycleService;
use App\Domain\Auctions\Enums\AuctionStatus;
use App\Domain\Auctions\Models\Auction;
use App\Domain\Lots\Models\Lot;
use App\Domain\Categories\Models\Category;
use Illuminate\Http\Request;

class PublicCatalogueController
{
    public function index(Request $request, AuctionLifecycleService $lifecycle)
    {
        // Keep lifecycle behaviour (auto-close ended auctions)
        $lifecycle->closeEndedAuctions();

        // Only show catalogue content for auctions that are discoverable pre-auction or live
        $visibleAuctionStatuses = [
            AuctionStatus::SCHEDULED->value,
            AuctionStatus::LIVE->value,
        ];

        $q = Lot::query()
            ->with(['category', 'auction'])
            ->whereHas('auction', function ($a) use ($visibleAuctionStatuses) {
                $a->whereIn('status', $visibleAuctionStatuses);
            })
            ->whereNotIn('status', ['ARCHIVED', 'WITHDRAWN']);

        // Text search (multi-field partial)
        if ($request->filled('q')) {
            $term = $request->string('q')->toString();

            $q->where(function ($sub) use ($term) {
                $sub->where('artist_name', 'like', "%{$term}%")
                    ->orWhere('subject_classification', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%")
                    ->orWhere('lot_number', 'like', "%{$term}%");
            });
        }

        // Category filter (slug)
        if ($request->filled('category')) {
            $slug = $request->string('category')->toString();

            $q->whereHas('category', function ($c) use ($slug) {
                $c->where('slug', $slug);
            });
        }

        // Estimate range filters
        if ($request->filled('min')) {
            $q->where('estimate_low', '>=', $request->integer('min'));
        }

        if ($request->filled('max')) {
            $q->where('estimate_high', '<=', $request->integer('max'));
        }

        /**
         * Auction-date filtering (Workshop req: filter by auction date).
         * We filter by the auction starts_at date.
         *
         * Params:
         *  - auction_from (YYYY-MM-DD)
         *  - auction_to (YYYY-MM-DD)
         */
        if ($request->filled('auction_from')) {
            $from = $request->date('auction_from');
            if ($from) {
                $q->whereHas('auction', fn($a) => $a->whereDate('starts_at', '>=', $from->format('Y-m-d')));
            }
        }

        if ($request->filled('auction_to')) {
            $to = $request->date('auction_to');
            if ($to) {
                $q->whereHas('auction', fn($a) => $a->whereDate('starts_at', '<=', $to->format('Y-m-d')));
            }
        }

        // Optional: filter to a specific auction
        if ($request->filled('auction_id')) {
            $auctionId = (int) $request->input('auction_id');
            $q->where('auction_id', $auctionId);
        }

        $lots = $q->latest()->paginate(18)->withQueryString();

        $categories = Category::orderBy('name')->get();

        // Auction list for dropdown (SCHEDULED + LIVE)
        $auctions = Auction::query()
            ->whereIn('status', $visibleAuctionStatuses)
            ->orderBy('starts_at')
            ->get();

        return view('public.catalogue.index', compact('lots', 'categories', 'auctions'));
    }

    public function show(Lot $lot)
    {
        $lot->load(['category', 'auction']);

        $visibleAuctionStatuses = [
            AuctionStatus::SCHEDULED->value,
            AuctionStatus::LIVE->value,
        ];

        abort_if(
            in_array($lot->status, ['ARCHIVED', 'WITHDRAWN']) ||
            !$lot->auction ||
            !in_array($lot->auction->status, $visibleAuctionStatuses, true),
            404
        );

        return view('public.catalogue.show', compact('lot'));
    }
}
