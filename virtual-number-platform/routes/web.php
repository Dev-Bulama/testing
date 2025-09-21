<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CommandConsoleController;
use App\Http\Controllers\Admin\PhoneNumberController as AdminPhoneNumberController;
use App\Http\Controllers\Admin\ProviderController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\Customer\PhoneNumberController as CustomerPhoneNumberController;
use App\Http\Controllers\Customer\TransactionController as CustomerTransactionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'))->name('welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])
        ->name('dashboard');

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('numbers', [CustomerPhoneNumberController::class, 'index'])->name('numbers.index');
        Route::post('numbers/{phoneNumber}/rent', [CustomerPhoneNumberController::class, 'rent'])->name('numbers.rent');
        Route::post('numbers/{phoneNumber}/extend', [CustomerPhoneNumberController::class, 'extend'])->name('numbers.extend');
        Route::post('numbers/{phoneNumber}/release', [CustomerPhoneNumberController::class, 'release'])->name('numbers.release');
        Route::get('wallet', [CustomerTransactionController::class, 'wallet'])->name('wallet');
        Route::post('wallet/fund', [CustomerTransactionController::class, 'fundWallet'])->name('wallet.fund');
        Route::get('invoices', [CustomerTransactionController::class, 'invoices'])->name('invoices');
    });

    Route::prefix('admin')->middleware('role:admin')->name('admin.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('providers', ProviderController::class)->except(['show']);
        Route::post('numbers/fetch', [AdminPhoneNumberController::class, 'fetch'])->name('numbers.fetch');
        Route::resource('numbers', AdminPhoneNumberController::class);
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::post('console', [CommandConsoleController::class, 'execute'])->name('console.execute');
    });
});

require __DIR__.'/auth.php';
