<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Auctions\Models\Auction;
use App\Domain\Lots\Models\Lot;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AuctionStoreRequest;
use App\Http\Requests\Admin\AuctionUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Application\Auctions\Services\AuctionLifecycleService;

class AuctionAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request): View
    {
        $query = Auction::query()->orderByDesc('id');

        if ($request->filled('q')) {
            $q = $request->string('q')->toString();
            $query->where('title', 'like', "%{$q}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        $auctions = $query->paginate(15)->withQueryString();

        return view('admin.auctions.index', compact('auctions'));
    }

    public function create(): View
    {
        // Only assign lots that are not archived and not already assigned (or allow reassign in edit)
        $lots = Lot::query()
            ->with('category')
            ->whereNotIn('status', ['ARCHIVED', 'WITHDRAWN'])
            ->whereNull('auction_id')
            ->orderByDesc('id')
            ->get();

        return view('admin.auctions.create', compact('lots'));
    }

    public function store(AuctionStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, &$auction) {
            $auction = Auction::create([
                'title' => $data['title'],
                'starts_at' => $data['starts_at'],
                'ends_at' => $data['ends_at'],
                'status' => $data['status'],
            ]);

            $lotIds = $data['lots'] ?? [];
            if (!empty($lotIds)) {
                Lot::whereIn('id', $lotIds)->update(['auction_id' => $auction->id]);
            }
        });

        return redirect()
            ->route('admin.auctions.index')
            ->with('success', 'Auction created successfully.');
    }

    public function show(Auction $auction): View
    {
        $auction->load(['lots.category']);

        // Attach sale + winning client info per lot
        $sales = \Illuminate\Support\Facades\DB::table('sales')
            ->leftJoin('users', 'sales.client_id', '=', 'users.id')
            ->select(
                'sales.lot_id',
                'sales.hammer_price',
                'sales.status as sale_status',
                'users.email as winning_client'
            )
            ->get()
            ->keyBy('lot_id');

        return view('admin.auctions.show', compact('auction', 'sales'));
    }


    public function edit(Auction $auction): View
    {
        $auction->load('lots');

        // Allow selecting:
        // - currently assigned to this auction
        // - unassigned lots
        $lots = Lot::query()
            ->with('category')
            ->whereNotIn('status', ['ARCHIVED', 'WITHDRAWN'])
            ->where(function ($q) use ($auction) {
                $q->whereNull('auction_id')
                  ->orWhere('auction_id', $auction->id);
            })
            ->orderByDesc('id')
            ->get();

        $selectedLotIds = $auction->lots->pluck('id')->all();

        return view('admin.auctions.edit', compact('auction', 'lots', 'selectedLotIds'));
    }

    public function update(AuctionUpdateRequest $request, Auction $auction): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($auction, $data) {
            $auction->update([
                'title' => $data['title'],
                'starts_at' => $data['starts_at'],
                'ends_at' => $data['ends_at'],
                'status' => $data['status'],
            ]);

            $newLotIds = $data['lots'] ?? [];

            // Unassign lots no longer selected
            Lot::where('auction_id', $auction->id)
                ->whereNotIn('id', $newLotIds)
                ->update(['auction_id' => null]);

            // Assign selected lots to this auction
            if (!empty($newLotIds)) {
                Lot::whereIn('id', $newLotIds)->update(['auction_id' => $auction->id]);
            }
        });

        return redirect()
            ->route('admin.auctions.index')
            ->with('success', 'Auction updated successfully.');
    }
    public function close(Auction $auction, AuctionLifecycleService $lifecycle): RedirectResponse
    {
        if ($auction->status !== 'LIVE') {
            return back()->with('success', 'Auction is not LIVE.');
        }

        // Manual close ALWAYS closes this auction + generates sales
        $lifecycle->closeAuction($auction);

        return redirect()
            ->route('admin.auctions.show', $auction)
            ->with('success', 'Auction closed and sales generated.');
    }


    public function destroy(Auction $auction): RedirectResponse
    {
        // Sprint-friendly: “delete” means unassign lots then remove auction
        DB::transaction(function () use ($auction) {
            Lot::where('auction_id', $auction->id)->update(['auction_id' => null]);
            $auction->delete();
        });

        return redirect()
            ->route('admin.auctions.index')
            ->with('success', 'Auction deleted successfully.');
    }
}
