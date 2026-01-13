<?php

namespace App\Http\Controllers\Admin;

use App\Application\Lots\Services\LotService;
use App\Domain\Categories\Models\Category;
use App\Domain\Lots\Models\Lot;
use App\Http\Requests\Admin\LotStoreRequest;
use App\Http\Requests\Admin\LotUpdateRequest;
use Illuminate\Http\Request;

class LotAdminController
{
    public function __construct(private LotService $lotService) {}

    public function index(Request $request)
    {
        $q = Lot::query()->with(['category','auction']);

        // Simple search (assignment minimum) + status filter for admin usability
        if ($request->filled('artist')) {
            $q->where('artist_name', 'like', '%' . $request->string('artist') . '%');
        }
        if ($request->filled('category_id')) {
            $q->where('category_id', $request->integer('category_id'));
        }
        if ($request->filled('subject')) {
            $q->where('subject_classification', $request->string('subject'));
        }
        if ($request->filled('min_estimate')) {
            $q->where('estimate_low', '>=', $request->integer('min_estimate'));
        }
        if ($request->filled('max_estimate')) {
            $q->where('estimate_low', '<=', $request->integer('max_estimate'));
        }
        if ($request->filled('status')) {
            $q->where('status', $request->string('status'));
        }

        $lots = $q->latest()->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.lots.index', compact('lots','categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.lots.create', compact('categories'));
    }

    public function store(LotStoreRequest $request)
    {
        $lot = $this->lotService->create($request->validated());
        return redirect()->route('admin.lots.show', $lot);
    }

    public function show(Lot $lot)
    {
        $lot->load(['category','auction','bids']);
        return view('admin.lots.show', compact('lot'));
    }

    public function edit(Lot $lot)
    {
        $lot->load('category');
        $categories = Category::orderBy('name')->get();
        return view('admin.lots.edit', compact('lot','categories'));
    }

    public function update(LotUpdateRequest $request, Lot $lot)
    {
        $this->lotService->update($lot, $request->validated());
        return redirect()->route('admin.lots.show', $lot);
    }

    public function destroy(Lot $lot)
    {
        $this->lotService->archive($lot);
        return redirect()->route('admin.lots.index');
    }
}
