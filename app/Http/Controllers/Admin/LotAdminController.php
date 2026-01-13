<?php

namespace App\Http\Controllers\Admin;

use App\Application\Lots\Services\LotService;
use App\Domain\Bidding\Models\CommissionBid;
use App\Domain\Categories\Models\Category;
use App\Domain\Lots\Models\Lot;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LotAdminController extends Controller
{
    public function __construct(private readonly LotService $lotService)
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request): View
    {
        $query = Lot::query()
            ->with(['category', 'auction'])
            ->orderByDesc('id');

        if ($request->filled('q')) {
            $q = $request->string('q')->toString();
            $query->where(function ($sub) use ($q) {
                $sub->where('artist_name', 'like', "%{$q}%")
                    ->orWhere('subject_classification', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('lot_number', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('min_estimate')) {
            $query->where('estimate_low', '>=', (float) $request->input('min_estimate'));
        }

        if ($request->filled('max_estimate')) {
            $query->where('estimate_high', '<=', (float) $request->input('max_estimate'));
        }

        $lots = $query->paginate(15)->withQueryString();

        $categories = Category::query()->orderBy('name')->get();

        return view('admin.lots.index', compact('lots', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::query()->orderBy('name')->get();
        return view('admin.lots.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'artist_name' => ['required', 'string', 'max:255'],
            'year_produced' => ['required', 'integer', 'min:1000', 'max:' . (date('Y') + 1)],
            'subject_classification' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'estimate_low' => ['required', 'numeric', 'min:0'],
            'estimate_high' => ['required', 'numeric', 'gte:estimate_low'],
            'category_id' => ['required', 'exists:categories,id'],
            'status' => ['required', 'string', 'max:50'],
            'category_metadata' => ['nullable', 'array'], // later: per-category rules
        ]);

        $lot = $this->lotService->create($data);

        return redirect()
            ->route('admin.lots.show', $lot)
            ->with('success', 'Lot created successfully.');
    }

    public function show(Lot $lot): View
    {
        $lot->load(['category', 'auction']);

        $bids = CommissionBid::query()
            ->with('user')
            ->where('lot_id', $lot->id)
            ->latest('placed_at')
            ->get();

        return view('admin.lots.show', compact('lot', 'bids'));
    }

    public function edit(Lot $lot): View
    {
        $lot->load('category');
        $categories = Category::query()->orderBy('name')->get();

        return view('admin.lots.edit', compact('lot', 'categories'));
    }

    public function update(Request $request, Lot $lot): RedirectResponse
    {
        $data = $request->validate([
            'artist_name' => ['required', 'string', 'max:255'],
            'year_produced' => ['required', 'integer', 'min:1000', 'max:' . (date('Y') + 1)],
            'subject_classification' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'estimate_low' => ['required', 'numeric', 'min:0'],
            'estimate_high' => ['required', 'numeric', 'gte:estimate_low'],
            'category_id' => ['required', 'exists:categories,id'],
            'status' => ['required', 'string', 'max:50'],
            'category_metadata' => ['nullable', 'array'],
        ]);

        $this->lotService->update($lot, $data);

        return redirect()
            ->route('admin.lots.show', $lot)
            ->with('success', 'Lot updated successfully.');
    }

    public function destroy(Lot $lot): RedirectResponse
    {
        $this->lotService->archive($lot);

        return redirect()
            ->route('admin.lots.index')
            ->with('success', 'Lot archived successfully.');
    }
}
