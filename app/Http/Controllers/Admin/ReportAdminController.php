<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Sales\Models\Sale;
use Illuminate\View\View;

class ReportAdminController
{
    public function salesSummary(): View
    {
        $rows = Sale::query()
            ->with('lot.auction', 'client')
            ->latest()
            ->paginate(25);

        $totalHammer = (float) Sale::sum('hammer_price');
        $totalCommission = (float) Sale::sum('commission_amount');

        return view('admin.reports.sales', compact('rows', 'totalHammer', 'totalCommission'));
    }
}
