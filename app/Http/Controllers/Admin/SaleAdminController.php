<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Lots\Models\Lot;
use App\Domain\Sales\Models\Sale;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaleStoreRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SaleAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(): View
    {
        $sales = DB::table('sales')
            ->join('lots', 'sales.lot_id', '=', 'lots.id')
            ->leftJoin('users', 'sales.client_id', '=', 'users.id')
            ->select(
                'sales.*',
                'lots.lot_number',
                'lots.artist_name',
                'users.email as client_email'
            )
            ->orderByDesc('sales.id')
            ->paginate(20);

        return view('admin.sales.index', compact('sales'));
    }

    public function create(Lot $lot): View
    {
        // Only “client” users should be selectable as buyers in this demo.
        $clients = User::query()
            ->where('role', 'client')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('admin.sales.create', [
            'lot' => $lot,
            'clients' => $clients,
        ]);
    }

    public function store(SaleStoreRequest $request, Lot $lot): RedirectResponse
    {
        $hammer = (float) $request->input('hammer_price');
        $commissionRate = (float) config('fees.commission_rate', 0.10);
        $commission = round($hammer * $commissionRate, 2);

        Sale::updateOrCreate(
            ['lot_id' => $lot->id],
            [
                'client_id' => (int) $request->input('client_id'),
                'hammer_price' => $hammer,
                'commission_amount' => $commission,
                'status' => 'COMPLETED',
            ]
        );

        // Keep lot status consistent with your demo workflow.
        $lot->update(['status' => 'SOLD']);

        return redirect()
            ->route('admin.sales.index')
            ->with('success', 'Sale recorded.');
    }
}
