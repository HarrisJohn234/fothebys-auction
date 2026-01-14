<?php

use App\Http\Controllers\Admin\AdminBidController;
use App\Http\Controllers\Admin\AuctionAdminController;
use App\Http\Controllers\Admin\CategoryAdminController;
use App\Http\Controllers\Admin\LotAdminController;
use App\Http\Controllers\Admin\ReportAdminController;
use App\Http\Controllers\Admin\SaleAdminController;
use App\Http\Controllers\Client\ClientDashboardController;
use App\Http\Controllers\Client\ClientPreferenceController;
use App\Http\Controllers\ClientBidController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicCatalogueController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user && method_exists($user, 'isAdmin') && $user->isAdmin()) {
        return redirect()->route('admin.auctions.index');
    }

    // Clients go to their dashboard (Gap C)
    return redirect()->route('client.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/catalogue', [PublicCatalogueController::class, 'index'])->name('public.catalogue');
Route::get('/lots/{lot}', [PublicCatalogueController::class, 'show'])->name('public.lots.show');

Route::post('/lots/{lot}/commission-bid', [ClientBidController::class, 'store'])
    ->middleware('auth')
    ->name('lots.commission-bid');

// Client area (Gap C)
Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');

    Route::get('/preferences', [ClientPreferenceController::class, 'edit'])->name('preferences.edit');
    Route::put('/preferences', [ClientPreferenceController::class, 'update'])->name('preferences.update');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('auctions', AuctionAdminController::class)->names('auctions');
    Route::post('auctions/{auction}/close', [AuctionAdminController::class, 'close'])->name('auctions.close');

    Route::resource('lots', LotAdminController::class);

    // Gap B: Categories CRUD
    Route::resource('categories', CategoryAdminController::class)->except(['show']);

    Route::get('sales', [SaleAdminController::class, 'index'])->name('sales.index');
    Route::get('lots/{lot}/sale', [SaleAdminController::class, 'create'])->name('sales.create');
    Route::post('lots/{lot}/sale', [SaleAdminController::class, 'store'])->name('sales.store');

    Route::get('bids', [AdminBidController::class, 'index'])->name('bids.index');
    Route::post('bids/{bid}/accept', [AdminBidController::class, 'accept'])->name('bids.accept');
    Route::post('bids/{bid}/reject', [AdminBidController::class, 'reject'])->name('bids.reject');

    Route::get('reports/sales', [ReportAdminController::class, 'salesSummary'])->name('reports.sales');
});

require __DIR__.'/auth.php';
