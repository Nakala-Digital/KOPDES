<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BumdesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\KopdesController;
use App\Http\Controllers\MbgController;
use App\Http\Controllers\PendataanController;
use App\Http\Controllers\UmkmController;
use App\Http\Controllers\AnalitikController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store']);
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'storeRegister']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard-data', [DashboardController::class, 'data'])->name('dashboard.data');
    Route::post('/dashboard/reports/village', [DashboardController::class, 'villageReport'])->name('dashboard.reports.village');
    Route::get('/dashboard/{role}', [DashboardController::class, 'index'])->name('dashboard.role');
    Route::get('/role-flow', [\App\Http\Controllers\RoleFlowController::class, 'index'])->name('role-flow.index');

    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
    Route::post('/auth/accounts', [AuthController::class, 'createManagedAccount'])->name('auth.accounts.store');

    Route::get('/pendataan', [PendataanController::class, 'index'])->name('pendataan.index');
    Route::get('/pendataan/aset', [PendataanController::class, 'index'])->defaults('feature', 'aset')->name('pendataan.aset.page');
    Route::get('/pendataan/umkm', [PendataanController::class, 'index'])->defaults('feature', 'umkm')->name('pendataan.umkm.page');
    Route::get('/pendataan/export', [PendataanController::class, 'index'])->defaults('feature', 'export')->name('pendataan.export.page');
    Route::post('/pendataan/aset', [PendataanController::class, 'storeAsset'])->name('pendataan.aset.store');
    Route::post('/pendataan/umkm', [PendataanController::class, 'storeUmkm'])->name('pendataan.umkm.store');
    Route::post('/pendataan/export', [PendataanController::class, 'export'])->name('pendataan.export');

    Route::get('/kopdes', [KopdesController::class, 'index'])->name('kopdes.index');
    Route::get('/kopdes/members', [KopdesController::class, 'index'])->defaults('feature', 'members')->name('kopdes.members.page');
    Route::get('/kopdes/savings', [KopdesController::class, 'index'])->defaults('feature', 'savings')->name('kopdes.savings.page');
    Route::get('/kopdes/loans', [KopdesController::class, 'index'])->defaults('feature', 'loans')->name('kopdes.loans.page');
    Route::get('/kopdes/reports', [KopdesController::class, 'index'])->defaults('feature', 'reports')->name('kopdes.reports.page');
    Route::post('/kopdes/members', [KopdesController::class, 'storeMember'])->name('kopdes.members.store');
    Route::post('/kopdes/savings', [KopdesController::class, 'storeSaving'])->name('kopdes.savings.store');
    Route::post('/kopdes/loans/evaluate', [KopdesController::class, 'evaluateLoan'])->name('kopdes.loans.evaluate');
    Route::post('/kopdes/loans', [KopdesController::class, 'storeLoan'])->name('kopdes.loans.store');
    Route::post('/kopdes/installments/pay', [KopdesController::class, 'payInstallment'])->name('kopdes.installments.pay');
    Route::post('/kopdes/reports/finance', [KopdesController::class, 'report'])->name('kopdes.reports.finance');

    Route::get('/bumdes', [BumdesController::class, 'index'])->name('bumdes.index');
    Route::get('/bumdes/units', [BumdesController::class, 'index'])->defaults('feature', 'units')->name('bumdes.units.page');
    Route::get('/bumdes/transactions', [BumdesController::class, 'index'])->defaults('feature', 'transactions')->name('bumdes.transactions.page');
    Route::get('/bumdes/reversal', [BumdesController::class, 'index'])->defaults('feature', 'reversal')->name('bumdes.reversal.page');
    Route::get('/bumdes/consolidation', [BumdesController::class, 'index'])->defaults('feature', 'consolidation')->name('bumdes.consolidation.page');
    Route::get('/bumdes/annual', [BumdesController::class, 'index'])->defaults('feature', 'annual')->name('bumdes.annual.page');
    Route::post('/bumdes/units', [BumdesController::class, 'storeUnit'])->name('bumdes.units.store');
    Route::post('/bumdes/transactions', [BumdesController::class, 'storeTransaction'])->name('bumdes.transactions.store');
    Route::post('/bumdes/reversal', [BumdesController::class, 'reverseTransaction'])->name('bumdes.reversal.store');
    Route::post('/bumdes/reports/consolidation', [BumdesController::class, 'consolidatedReport'])->name('bumdes.reports.consolidation');
    Route::post('/bumdes/reports/annual', [BumdesController::class, 'annualReport'])->name('bumdes.reports.annual');
    Route::post('/bumdes/reports/unit-finance', [BumdesController::class, 'unitFinancialReport'])->name('bumdes.reports.unit-finance');

    Route::get('/umkm', [UmkmController::class, 'index'])->name('umkm.index');
    Route::get('/umkm/products', [UmkmController::class, 'index'])->defaults('feature', 'products')->name('umkm.products.page');
    Route::get('/umkm/orders', [UmkmController::class, 'index'])->defaults('feature', 'orders')->name('umkm.orders.page');
    Route::get('/umkm/reports', [UmkmController::class, 'index'])->defaults('feature', 'reports')->name('umkm.reports.page');
    Route::post('/umkm/products', [UmkmController::class, 'storeProduct'])->name('umkm.products.store');
    Route::post('/umkm/orders', [UmkmController::class, 'incomingOrder'])->name('umkm.orders.store');
    Route::post('/umkm/orders/{order}/confirm', [UmkmController::class, 'confirmOrder'])->name('umkm.orders.confirm');
    Route::post('/umkm/orders/{order}/ship', [UmkmController::class, 'shipOrder'])->name('umkm.orders.ship');
    Route::post('/umkm/orders/overdue-alerts', [UmkmController::class, 'overdueAlerts'])->name('umkm.orders.overdue-alerts');
    Route::post('/umkm/reports/sales', [UmkmController::class, 'salesReport'])->name('umkm.reports.sales');

    Route::get('/mbg', [MbgController::class, 'index'])->name('mbg.index');
    Route::get('/mbg/orders', [MbgController::class, 'index'])->defaults('feature', 'orders')->name('mbg.orders.page');
    Route::get('/mbg/suppliers', [MbgController::class, 'index'])->defaults('feature', 'suppliers')->name('mbg.suppliers.page');
    Route::get('/mbg/distributions', [MbgController::class, 'index'])->defaults('feature', 'distributions')->name('mbg.distributions.page');
    Route::get('/mbg/reports', [MbgController::class, 'index'])->defaults('feature', 'reports')->name('mbg.reports.page');
    Route::post('/mbg/orders', [MbgController::class, 'storeOrder'])->name('mbg.orders.store');
    Route::post('/mbg/orders/{order}/suppliers/notify', [MbgController::class, 'notifySuppliers'])->name('mbg.suppliers.notify');
    Route::post('/mbg/suppliers/{confirmation}/confirm', [MbgController::class, 'confirmSupplier'])->name('mbg.suppliers.confirm');
    Route::post('/mbg/suppliers/{confirmation}/fail', [MbgController::class, 'failSupplier'])->name('mbg.suppliers.fail');
    Route::post('/mbg/suppliers/process-deadlines', [MbgController::class, 'processDeadlines'])->name('mbg.suppliers.process-deadlines');
    Route::post('/mbg/distributions', [MbgController::class, 'recordDistribution'])->name('mbg.distributions.store');
    Route::post('/mbg/reports/monthly', [MbgController::class, 'report'])->name('mbg.reports.monthly');

    Route::get('/keuangan', [KeuanganController::class, 'index'])->name('keuangan.index');

    Route::get('/analitik', [AnalitikController::class, 'index'])->name('analitik.index');
});

Route::post('/auth/pin-reset/request', [AuthController::class, 'requestPinReset'])->name('auth.pin-reset.request');
Route::post('/auth/pin-reset/verify', [AuthController::class, 'verifyPinReset'])->name('auth.pin-reset.verify');
Route::post('/auth/pin-reset/complete', [AuthController::class, 'completePinReset'])->name('auth.pin-reset.complete');

Route::get('/flow', function () {
    return view('flow');
});
