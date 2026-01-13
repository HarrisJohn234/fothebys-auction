<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Sales\Models\Sale;

class ReportAdminController
{
    public function salesSummary()
    {
        $rows = Sale::query()
            ->with('lot.auction')
            ->latest()
            ->paginate(25);

        $totalHammer = (int) Sale::sum('hammer_price');
        $totalPremium = (int) Sale::sum('buyer_premium_amount');
        $totalDue = (int) Sale::sum('total_due');

        return view('admin.reports.sales', compact('rows', 'totalHammer', 'totalPremium', 'totalDue'));
    }
}
