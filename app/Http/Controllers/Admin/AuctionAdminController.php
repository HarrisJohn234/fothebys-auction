<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Auctions\Models\Auction;
use Illuminate\Http\Request;

class AuctionAdminController
{
    public function index()
    {
        $auctions = Auction::query()->latest()->paginate(15);
        return view('admin.auctions.index', compact('auctions'));
    }

    public function create()
    {
        return view('admin.auctions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'theme' => ['nullable','string','max:255'],
            'auction_type' => ['required','in:PHYSICAL,ONLINE_ONLY'],
            'starts_at' => ['nullable','date'],
            'duration_minutes' => ['nullable','integer','min:1'],
            'status' => ['required','in:DRAFT,SCHEDULED,LIVE,CLOSED,ARCHIVED'],
        ]);

        $data['created_by'] = auth()->id();

        $auction = Auction::create($data);
        return redirect()->route('admin.auctions.show', $auction);
    }

    public function show(Auction $auction)
    {
        $auction->load('lots');
        return view('admin.auctions.show', compact('auction'));
    }

    public function edit(Auction $auction)
    {
        return view('admin.auctions.edit', compact('auction'));
    }

    public function update(Request $request, Auction $auction)
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'theme' => ['nullable','string','max:255'],
            'auction_type' => ['required','in:PHYSICAL,ONLINE_ONLY'],
            'starts_at' => ['nullable','date'],
            'duration_minutes' => ['nullable','integer','min:1'],
            'status' => ['required','in:DRAFT,SCHEDULED,LIVE,CLOSED,ARCHIVED'],
        ]);

        $auction->update($data);
        return redirect()->route('admin.auctions.show', $auction);
    }

    public function destroy(Auction $auction)
    {
        $auction->update(['status' => 'ARCHIVED']);
        return redirect()->route('admin.auctions.index');
    }
}
