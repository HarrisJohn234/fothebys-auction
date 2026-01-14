<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
}
