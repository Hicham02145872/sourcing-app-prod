<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SourcingRequestController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;
use Monolog\Handler\AmqpHandler;

Route::get('language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect('/admin/dashboard');
    } else {
        return redirect('/client/dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('countries', CountryController::class);
    Route::resource('payment-methods', App\Http\Controllers\Admin\PaymentMethodController::class);
    Route::get('sourcing-orders', [App\Http\Controllers\Admin\SourcingOrderController::class, 'index'])->name('sourcing-orders.index');
    Route::get('sourcing-orders/{sourcingOrder}', [App\Http\Controllers\Admin\SourcingOrderController::class, 'show'])->name('sourcing-orders.show');
    Route::get('sourcing-orders/{sourcingOrder}/download-proof-of-payment', [App\Http\Controllers\Admin\SourcingOrderController::class, 'downloadProofOfPayment'])->name('sourcing-orders.download-proof-of-payment');
    Route::get('/sourcing-requests', [App\Http\Controllers\Admin\AdminSourcingRequestController::class, 'index'])->name('sourcing-requests.index');
    Route::get('/sourcing-requests/{sourcingRequest}', [App\Http\Controllers\Admin\AdminSourcingRequestController::class, 'show'])->name('sourcing-requests.show');
    Route::patch('/sourcing-requests/{sourcingRequest}/update-status', [App\Http\Controllers\Admin\AdminSourcingRequestController::class, 'updateStatus'])->name('sourcing-requests.update-status');
    Route::get('quotations/create', [App\Http\Controllers\Admin\QuotationController::class, 'create'])->name('quotations.create');
    Route::post('quotations', [App\Http\Controllers\Admin\QuotationController::class, 'store'])->name('quotations.store');
    Route::get('quotations', [App\Http\Controllers\Admin\QuotationController::class, 'index'])->name('quotations.index');
    Route::patch('sourcing-orders/{sourcingOrder}/update-status', [App\Http\Controllers\Admin\SourcingOrderController::class, 'updateStatus'])->name('sourcing-orders.update-status');

    Route::resource('users', App\Http\Controllers\Admin\UserController::class)->only(['index', 'edit', 'update', 'destroy']);
});


Route::middleware(['auth', 'role:client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', function () {
        $sourcingRequests = auth()->user()->sourcingRequests()->with('category', 'destinations.country', 'destinations.service')->get();
        return view('client.dashboard', compact('sourcingRequests'));
    })->name('dashboard');

    Route::get('/sourcing-requests/create', [SourcingRequestController::class, 'create'])->name('sourcing-requests.create');
    Route::post('/sourcing-requests', [SourcingRequestController::class, 'store'])->name('sourcing-requests.store');
    Route::get('/sourcing-requests', [SourcingRequestController::class, 'index'])->name('sourcing-requests.index');
    Route::get('/sourcing-requests/handling', [SourcingRequestController::class, 'handling'])->name('sourcing-requests.handling');
    Route::get('/sourcing-requests/{sourcingRequest}', [SourcingRequestController::class, 'show'])->name('sourcing-requests.show');
    Route::get('/sourcing-requests/{sourcingRequest}/edit', [SourcingRequestController::class, 'edit'])->name('sourcing-requests.edit');
    Route::put('/sourcing-requests/{sourcingRequest}', [SourcingRequestController::class, 'update'])->name('sourcing-requests.update');
    Route::delete('/sourcing-requests/{sourcingRequest}', [SourcingRequestController::class, 'destroy'])->name('sourcing-requests.destroy');
    Route::match(['get', 'post'], '/sourcing-orders/{sourcingOrder}/upload-proof-of-payment', [App\Http\Controllers\Client\SourcingOrderController::class, 'uploadProofOfPayment'])->name('sourcing-orders.upload-proof-of-payment');
    Route::post('/quotations/{quotation}/accept', [App\Http\Controllers\Client\QuotationController::class, 'accept'])->name('quotations.accept');
    Route::get('/quotations', [App\Http\Controllers\Client\QuotationController::class, 'index'])->name('quotations.index');

    Route::get('/sourcing-orders', [App\Http\Controllers\Client\SourcingOrderController::class, 'index'])->name('sourcing-orders.index');
    Route::get('/sourcing-orders/{sourcingOrder}', [App\Http\Controllers\Client\SourcingOrderController::class, 'show'])->name('sourcing-orders.show');
    Route::get('/history', [SourcingRequestController::class, 'history'])->name('history');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/fcm-token', [App\Http\Controllers\NotificationController::class, 'updateToken'])->name('fcm.token.update');
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/clear-all', [App\Http\Controllers\NotificationController::class, 'clearAll'])->name('notifications.clear-all');
});

require __DIR__.'/auth.php';
