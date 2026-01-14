<?php

namespace App\Http\Controllers\Client;

use App\Domain\Bidding\Models\CommissionBid;
use App\Domain\Sales\Models\Sale;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $bids = CommissionBid::query()
            ->with(['lot.category', 'lot.auction'])
            ->where('client_id', $user->id)
            ->latest()
            ->paginate(10, ['*'], 'bids_page');

        $purchases = Sale::query()
            ->with(['lot.category', 'lot.auction'])
            ->where('client_id', $user->id)
            ->latest()
            ->paginate(10, ['*'], 'sales_page');

        return view('client.dashboard', compact('bids', 'purchases'));
    }
}
