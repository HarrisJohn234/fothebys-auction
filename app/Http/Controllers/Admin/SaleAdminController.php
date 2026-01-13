<?php

namespace App\Http\Controllers\Admin;

use App\Application\Sales\Services\SaleService;
use App\Domain\Lots\Models\Lot;
use App\Http\Requests\Admin\SaleStoreRequest;
use App\Models\User;

class SaleAdminController
{
    public function __construct(private SaleService $saleService) {}

    public function create(Lot $lot)
    {
        $buyers = User::query()->where('role', 'client')->orderBy('name')->get();
        return view('admin.sales.create', compact('lot', 'buyers'));
    }

    public function store(SaleStoreRequest $request, Lot $lot)
    {
        $data = $request->validated();
        $sale = $this->saleService->recordSale($lot, (int) $data['buyer_id'], (int) $data['hammer_price']);

        return redirect()->route('admin.lots.show', $lot)
            ->with('status', "Sale recorded. Total due: {$sale->total_due}");
    }
}
