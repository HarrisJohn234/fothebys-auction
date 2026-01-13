<?php

use App\Http\Controllers\Admin\AdminBidController;
use App\Http\Controllers\Admin\AuctionAdminController;
use App\Http\Controllers\Admin\LotAdminController;
use App\Http\Controllers\Admin\ReportAdminController;
use App\Http\Controllers\Admin\SaleAdminController;
use App\Http\Controllers\Client\ClientBidController;
use App\Http\Controllers\PublicCatalogueController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientBidController;

Route::post('/lots/{lot}/commission-bid', [ClientBidController::class, 'store'])
    ->middleware('auth')
    ->name('lots.commission-bid');

Route::get('/', fn () => redirect()->route('public.catalogue'));
Route::get('/dashboard', fn () => redirect()->route('public.catalogue'))
    ->middleware(['auth'])
    ->name('dashboard');

Route::get('/catalogue', [PublicCatalogueController::class, 'index'])->name('public.catalogue');
Route::get('/lots/{lot}', [PublicCatalogueController::class, 'show'])->name('public.lots.show');

// Client commission bid (requires login; request ensures role=client)
Route::middleware(['auth'])->group(function () {
    Route::post('/lots/{lot}/commission-bid', [ClientBidController::class, 'store'])
        ->name('client.bids.store');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('auctions', AuctionAdminController::class);
    Route::resource('lots', LotAdminController::class);

    // bids admin
    Route::get('bids', [AdminBidController::class, 'index'])->name('bids.index');
    Route::post('bids/{bid}/accept', [AdminBidController::class, 'accept'])->name('bids.accept');
    Route::post('bids/{bid}/reject', [AdminBidController::class, 'reject'])->name('bids.reject');

    // sales
    Route::get('lots/{lot}/sale', [SaleAdminController::class, 'create'])->name('sales.create');
    Route::post('lots/{lot}/sale', [SaleAdminController::class, 'store'])->name('sales.store');

    // reports
    Route::get('reports/sales', [ReportAdminController::class, 'salesSummary'])->name('reports.sales');
});
require __DIR__.'/auth.php';
