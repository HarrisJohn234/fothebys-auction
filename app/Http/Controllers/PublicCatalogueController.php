<?php

namespace App\Http\Controllers;

use App\Application\Auctions\Services\AuctionLifecycleService;
use App\Domain\Lots\Models\Lot;
use App\Domain\Categories\Models\Category;
use Illuminate\Http\Request;

class PublicCatalogueController
{
    public function index(Request $request, AuctionLifecycleService $lifecycle)
    {
        $lifecycle->closeEndedAuctions();

        $q = Lot::query()
            ->with(['category', 'auction'])
            ->whereHas('auction', function ($a) {
                $a->where('status', 'LIVE');
            })
            ->whereNotIn('status', ['ARCHIVED', 'WITHDRAWN']);

        // "complex search" v1: multi-field + estimate range + partial match
        if ($request->filled('q')) {
            $term = $request->string('q')->toString();

            $q->where(function ($sub) use ($term) {
                $sub->where('artist_name', 'like', "%{$term}%")
                    ->orWhere('subject_classification', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%")
                    ->orWhere('lot_number', 'like', "%{$term}%");
            });
        }

        if ($request->filled('category')) {
            $slug = $request->string('category')->toString();

            $q->whereHas('category', function ($c) use ($slug) {
                $c->where('slug', $slug);
            });
        }

        if ($request->filled('min')) {
            $q->where('estimate_low', '>=', $request->integer('min'));
        }

        if ($request->filled('max')) {
            
            $q->where('estimate_high', '<=', $request->integer('max'));
        }

        $lots = $q->latest()->paginate(18)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('public.catalogue.index', compact('lots', 'categories'));
    }

    public function show(Lot $lot)
    {
       
        abort_if(
            in_array($lot->status, ['ARCHIVED', 'WITHDRAWN']) ||
            !$lot->auction ||
            $lot->auction->status !== 'LIVE',
            404
        );

        $lot->load(['category', 'auction']);

        return view('public.catalogue.show', compact('lot'));
    }
}
