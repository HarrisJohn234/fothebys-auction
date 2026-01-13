<?php

namespace App\Http\Controllers;

use App\Domain\Categories\Models\Category;
use App\Domain\Lots\Models\Lot;
use Illuminate\Http\Request;

class PublicCatalogueController
{
    public function index(Request $request)
    {
        $q = Lot::query()
            ->with('category')
            ->whereNotIn('status', ['ARCHIVED', 'WITHDRAWN']);

        // "complex search" v1: multi-field + estimate range + partial match
        if ($request->filled('q')) {
            $term = $request->string('q');
            $q->where(function ($sub) use ($term) {
                $sub->where('artist_name', 'like', "%{$term}%")
                    ->orWhere('subject_classification', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%")
                    ->orWhere('lot_number', 'like', "%{$term}%");
            });
        }

        if ($request->filled('category')) {
            $q->whereHas('category', fn ($c) => $c->where('slug', $request->string('category')));
        }

        if ($request->filled('min')) $q->where('estimate_low', '>=', $request->integer('min'));
        if ($request->filled('max')) $q->where('estimate_low', '<=', $request->integer('max'));

        $lots = $q->latest()->paginate(18)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('public.catalogue.index', compact('lots', 'categories'));
    }

    public function show(Lot $lot)
    {
        abort_if(in_array($lot->status, ['ARCHIVED', 'WITHDRAWN']), 404);

        $lot->load('category');
        return view('public.catalogue.show', compact('lot'));
    }
}
