<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SocialMediaLinkController;
use App\Http\Controllers\Client\ClientDashboardController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SourcingRequestController; // Add this line
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

Route::get('/', function () {
    return view('welcome');
});

// Public static pages
Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy-policy');
Route::get('/terms-of-service', [PageController::class, 'terms'])->name('terms-of-service');
Route::get('/support', [PageController::class, 'support'])->name('support');

Route::get('/dashboard', function () {
    if (auth()->user()->isDeveloper()) {
        return redirect()->route('admin.dev-dashboard');
    }
    if (auth()->user()->isAdmin()) {
        return redirect('/admin/dashboard');
    } else {
        return redirect('/client/dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:admin', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('countries', CountryController::class);
    Route::resource('payment-methods', App\Http\Controllers\Admin\PaymentMethodController::class);
    Route::get('sourcing-orders/export-pdf', [App\Http\Controllers\Admin\SourcingOrderController::class, 'exportPdf'])->name('sourcing-orders.export-pdf');
    Route::post('sourcing-orders/duplicate-last', [App\Http\Controllers\Admin\SourcingOrderController::class, 'duplicateLast'])->name('sourcing-orders.duplicate-last');
    Route::get('sourcing-orders', [App\Http\Controllers\Admin\SourcingOrderController::class, 'index'])->name('sourcing-orders.index');
    Route::get('sourcing-orders/{sourcingOrder}', [App\Http\Controllers\Admin\SourcingOrderController::class, 'show'])->name('sourcing-orders.show');
    Route::get('sourcing-orders/{sourcingOrder}/download-proof-of-payment', [App\Http\Controllers\Admin\SourcingOrderController::class, 'downloadProofOfPayment'])->name('sourcing-orders.download-proof-of-payment');
    Route::post('sourcing-orders/{sourcingOrder}/reject-proof', [App\Http\Controllers\Admin\SourcingOrderController::class, 'rejectProof'])->name('sourcing-orders.reject-proof');
    Route::patch('sourcing-orders/{sourcingOrder}/update-status', [App\Http\Controllers\Admin\SourcingOrderController::class, 'updateStatus'])->name('sourcing-orders.update-status');
    Route::put('sourcing-orders/{sourcingOrder}/update-financials', [App\Http\Controllers\Admin\SourcingOrderController::class, 'updateFinancials'])->name('sourcing-orders.update-financials');
    Route::post('sourcing-orders/{sourcingOrder}/sync-to-sheet', [App\Http\Controllers\Admin\SourcingOrderController::class, 'syncToGoogleSheet'])->name('sourcing-orders.sync-to-sheet');
    Route::post('sourcing-orders/{sourcingOrder}/sync-shipping-sheet', [App\Http\Controllers\Admin\SourcingOrderController::class, 'manualSyncToShippingCompanySheet'])->name('sourcing-orders.sync-shipping-sheet');
    Route::get('sourcing-orders/{sourcingOrder}/shipping-label', [App\Http\Controllers\Admin\SourcingOrderController::class, 'showShippingLabel'])->name('sourcing-orders.shipping-label');
    Route::patch('sourcing-orders/{sourcingOrder}/tracking', [App\Http\Controllers\Admin\SourcingOrderController::class, 'updateTracking'])->name('sourcing-orders.update-tracking'); // Added tracking route
    Route::post('sourcing-orders/{sourcingOrder}/media', [App\Http\Controllers\Admin\SourcingOrderController::class, 'uploadMedia'])->name('sourcing-orders.media.store');
    Route::delete('sourcing-orders/{sourcingOrder}', [App\Http\Controllers\Admin\SourcingOrderController::class, 'destroy'])->name('sourcing-orders.destroy');
    Route::delete('media/{media}', [App\Http\Controllers\Admin\SourcingOrderController::class, 'deleteMedia'])->name('media.destroy');
    // Sourcing Requests Management
    Route::resource('sourcing-requests', App\Http\Controllers\Admin\AdminSourcingRequestController::class);
    Route::patch('/sourcing-requests/{sourcingRequest}/status', [App\Http\Controllers\Admin\AdminSourcingRequestController::class, 'updateStatus'])->name('sourcing-requests.update-status');
    Route::post('/sourcing-requests/{sourcingRequest}/assign', [App\Http\Controllers\Admin\AdminSourcingRequestController::class, 'assign'])->name('sourcing-requests.assign');
    Route::post('/sourcing-requests/{sourcingRequest}/unassign', [App\Http\Controllers\Admin\AdminSourcingRequestController::class, 'unassign'])->name('sourcing-requests.unassign');
    Route::post('/sourcing-requests/{sourcingRequest}/release', [App\Http\Controllers\Admin\AdminSourcingRequestController::class, 'release'])->name('sourcing-requests.release');

    // Refund Requests Management
    Route::middleware('feature:refunds')->group(function () {
        Route::get('refund-requests', [App\Http\Controllers\Admin\RefundRequestController::class, 'index'])->name('refund-requests.index');
        Route::get('refund-requests/{refundRequest}', [App\Http\Controllers\Admin\RefundRequestController::class, 'show'])->name('refund-requests.show');
        Route::patch('refund-requests/{refundRequest}/update-status', [App\Http\Controllers\Admin\RefundRequestController::class, 'updateStatus'])->name('refund-requests.update-status');
        Route::patch('refund-requests/{refundRequest}/assign-to-me', [App\Http\Controllers\Admin\RefundRequestController::class, 'assignToMe'])->name('refund-requests.assign-to-me');
    });
    Route::get('quotations/select-request', [App\Http\Controllers\Admin\QuotationController::class, 'selectRequest'])->name('quotations.select-request');
    Route::get('quotations/create/{sourcingRequest}', [App\Http\Controllers\Admin\QuotationController::class, 'create'])->name('quotations.create');
    Route::post('quotations', [App\Http\Controllers\Admin\QuotationController::class, 'store'])->name('quotations.store');
    Route::get('quotations', [App\Http\Controllers\Admin\QuotationController::class, 'index'])->name('quotations.index');
    Route::get('quotations/{quotation}', [App\Http\Controllers\Admin\QuotationController::class, 'show'])->name('quotations.show');
    Route::get('quotations/{quotation}/edit', [App\Http\Controllers\Admin\QuotationController::class, 'edit'])->name('quotations.edit');
    Route::put('quotations/{quotation}', [App\Http\Controllers\Admin\QuotationController::class, 'update'])->name('quotations.update');
    Route::put('quotations/{quotation}/approve', [App\Http\Controllers\Admin\QuotationController::class, 'approve'])->name('quotations.approve');
    Route::put('quotations/{quotation}/reject', [App\Http\Controllers\Admin\QuotationController::class, 'reject'])->name('quotations.reject');

    Route::get('/social-media-links', [SocialMediaLinkController::class, 'edit'])->name('social-media-links.edit');
    Route::put('/social-media-links', [SocialMediaLinkController::class, 'update'])->name('social-media-links.update');

    // Google Sheets Management (Super Admin & Admin)
    Route::prefix('google-sheets')->name('google-sheets.')->group(function () {
        Route::get('/settings', [App\Http\Controllers\Admin\GoogleSheetController::class, 'index'])->name('settings');
        Route::post('/upload-credentials', [App\Http\Controllers\Admin\GoogleSheetController::class, 'uploadCredentials'])->name('upload-credentials');
        Route::post('/test-connection', [App\Http\Controllers\Admin\GoogleSheetController::class, 'testConnection'])->name('test-connection');
        Route::post('/create-sheet', [App\Http\Controllers\Admin\GoogleSheetController::class, 'createSheet'])->name('create-sheet');
        Route::post('/sync-all', [App\Http\Controllers\Admin\GoogleSheetController::class, 'syncAll'])->name('sync-all');
        Route::post('/install-headers', [App\Http\Controllers\Admin\GoogleSheetController::class, 'installHeaders'])->name('install-headers');
        Route::post('/update-settings', [App\Http\Controllers\Admin\GoogleSheetController::class, 'updateSettings'])->name('update-settings');
        Route::post('/clear-logs', [App\Http\Controllers\Admin\GoogleSheetController::class, 'clearLogs'])->name('clear-logs');
        Route::get('/logs', [App\Http\Controllers\Admin\GoogleSheetController::class, 'logs'])->name('logs');
    });

    // Sales Margin Report
    Route::get('/reports/sales-margin', [ReportController::class, 'salesMarginReport'])->name('reports.sales-margin');
    Route::get('/reports/sales-margin/export', [ReportController::class, 'export'])->name('reports.sales-margin.export');
    Route::get('/reports/sales-margin/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.sales-margin.export-pdf');

    // Financial Reports
    Route::get('/reports/financial', [App\Http\Controllers\Admin\FinancialReportController::class, 'index'])->name('reports.financial.index');
    Route::get('/reports/financial/refunds', [App\Http\Controllers\Admin\FinancialReportController::class, 'refunds'])->name('reports.financial.refunds');

    Route::resource('users', App\Http\Controllers\Admin\UserController::class);

    // Super Admin Routes
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/super-admin/create-admin', [App\Http\Controllers\Admin\SuperAdminController::class, 'createAdmin'])->name('super-admin.create-admin');
        Route::post('/super-admin/store-admin', [App\Http\Controllers\Admin\SuperAdminController::class, 'storeAdmin'])->name('super-admin.store-admin');
        Route::get('/super-admin/list-admins', [App\Http\Controllers\Admin\SuperAdminController::class, 'listAdmins'])->name('super-admin.list-admins');
        Route::get('/admins/{id}', [App\Http\Controllers\Admin\SuperAdminController::class, 'showAdmin'])->name('show-admin');
        Route::get('/super-admin/admins/{admin}/edit', [App\Http\Controllers\Admin\SuperAdminController::class, 'editAdmin'])->name('super-admin.edit-admin');
        Route::put('/super-admin/admins/{admin}', [App\Http\Controllers\Admin\SuperAdminController::class, 'updateAdmin'])->name('super-admin.update-admin');
        Route::delete('/super-admin/admins/{admin}', [App\Http\Controllers\Admin\SuperAdminController::class, 'destroyAdmin'])->name('super-admin.destroy-admin');

        // Shipment Timeline Calendar (Super Admin Only)
        Route::get('/shipment-calendar', [App\Http\Controllers\Admin\ShipmentCalendarController::class, 'index'])->name('shipment-calendar.index');
        Route::get('/shipment-calendar/events', [App\Http\Controllers\Admin\ShipmentCalendarController::class, 'getEvents'])->name('shipment-calendar.events');

        // Shipping Fees (Super Admin Only)
        Route::resource('shipping-fees', App\Http\Controllers\Admin\ShippingFeeController::class)
            ->only(['index', 'edit']);

        // Tracking Test
        Route::middleware('feature:tracking')->group(function () {
            Route::get('/tracking/test', [App\Http\Controllers\Admin\TrackingTestController::class, 'index'])->name('tracking.test');
            Route::post('/tracking/test', [App\Http\Controllers\Admin\TrackingTestController::class, 'test'])->name('tracking.test.run');
        });

        // Shipping Companies Management (Super Admin Only)
        Route::get('/shipping-companies', [App\Http\Controllers\Admin\ShippingCompanyController::class, 'index'])->name('shipping-companies.index');

        // Tracking Logs
        Route::middleware('feature:tracking')->get('/tracking-logs', [App\Http\Controllers\Admin\TrackingLogController::class, 'index'])->name('tracking-logs.index');
    });
});

// Dev Dashboard & Impersonation (Strictly for Developer role)
Route::middleware(['auth', 'role:developer', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dev-dashboard', \App\Livewire\Admin\DevDashboard::class)->name('dev-dashboard');
    Route::get('/impersonate/{user}', function (\App\Models\User $user) {
        // Double security check for impersonation
        if (auth()->user()->email !== 'hichamaltit@gmail.com') {
            abort(403);
        }
        auth()->login($user);

        return redirect()->route('dashboard')->with('status', 'Impersonating '.$user->name);
    })->name('impersonate');
});

Route::middleware(['auth', 'role:client', 'verified'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');

    Route::get('/sourcing-requests/handling', [SourcingRequestController::class, 'handling'])->name('sourcing-requests.handling');
    Route::get('/sourcing-requests/history-requests', [SourcingRequestController::class, 'archived'])->name('sourcing-requests.archived');
    Route::resource('sourcing-requests', SourcingRequestController::class);
    Route::post('/sourcing-requests/{sourcingRequest}/duplicate', [SourcingRequestController::class, 'duplicate'])->name('sourcing-requests.duplicate');
    Route::post('/sourcing-requests/{sourcingRequest}/cancel', [SourcingRequestController::class, 'cancel'])->name('sourcing-requests.cancel');
    Route::match(['get', 'post'], '/sourcing-orders/{sourcingOrder}/upload-proof-of-payment', [App\Http\Controllers\Client\SourcingOrderController::class, 'uploadProofOfPayment'])->name('sourcing-orders.upload-proof-of-payment');
    Route::post('/quotations/{quotation}/accept', [App\Http\Controllers\Client\QuotationController::class, 'accept'])->name('quotations.accept');
    Route::post('/quotations/{quotation}/reject', [App\Http\Controllers\Client\QuotationController::class, 'reject'])->name('quotations.reject');
    Route::post('/quotations/{quotation}/negotiate', [App\Http\Controllers\Client\QuotationController::class, 'negotiate'])->name('quotations.negotiate');
    Route::get('/quotations', [App\Http\Controllers\Client\QuotationController::class, 'index'])->name('quotations.index');

    Route::get('/sourcing-orders', [App\Http\Controllers\Client\SourcingOrderController::class, 'index'])->name('sourcing-orders.index');
    Route::get('/sourcing-orders/export', [App\Http\Controllers\Client\SourcingOrderController::class, 'export'])->name('sourcing-orders.export');
    Route::get('/sourcing-orders/{sourcingOrder}', [App\Http\Controllers\Client\SourcingOrderController::class, 'show'])->name('sourcing-orders.show');
    Route::get('/sourcing-orders/{sourcingOrder}/receipt', [App\Http\Controllers\Client\SourcingOrderController::class, 'showReceipt'])->name('sourcing-orders.receipt');
    Route::get('/sourcing-orders/{sourcingOrder}/shipping-label', [App\Http\Controllers\Client\SourcingOrderController::class, 'showShippingLabel'])->name('sourcing-orders.shipping-label');
    Route::get('/sourcing-orders/{sourcingOrder}/download-proof-of-payment', [App\Http\Controllers\Client\SourcingOrderController::class, 'downloadProofOfPayment'])->name('sourcing-orders.download-proof-of-payment');
    Route::middleware('feature:refunds')->group(function () {
        Route::get('/refunds', [App\Http\Controllers\Client\RefundRequestController::class, 'index'])->name('refund-requests.index');
        Route::get('/refund-requests/create/{sourcingOrder}', [App\Http\Controllers\Client\RefundRequestController::class, 'create'])->name('refund-requests.create');
        Route::post('/sourcing-orders/{sourcingOrder}/refund-request', [App\Http\Controllers\Client\RefundRequestController::class, 'store'])->name('sourcing-orders.refund-request');
        Route::get('/refund-requests/{refundRequest}', [App\Http\Controllers\Client\RefundRequestController::class, 'show'])->name('refund-requests.show');
    });
    Route::get('/history', [SourcingRequestController::class, 'history'])->name('history');
    Route::get('/history/export', [SourcingRequestController::class, 'exportHistory'])->name('history.export');

    // Notification API routes for client sidebar
    Route::get('/notifications-api', [App\Http\Controllers\Client\NotificationController::class, 'index'])->name('notifications.api.index');
    Route::post('/notifications-api/{notification}/mark-as-read', [App\Http\Controllers\Client\NotificationController::class, 'markAsRead'])->name('notifications.api.mark-as-read');

    // Tracking
    Route::middleware('feature:tracking')->group(function () {
        Route::get('/tracking', [App\Http\Controllers\Client\TrackingController::class, 'index'])->name('tracking.index');
        Route::middleware('throttle:10,1')->get('/tracking/data', [App\Http\Controllers\Client\TrackingController::class, 'data'])->name('tracking.data');
        Route::get('/tracking/17track', [App\Http\Controllers\Client\TrackingController::class, 'seventeenTrackIndex'])->name('tracking.17track.index');
        Route::middleware('throttle:10,1')->get('/tracking/1track/data', [App\Http\Controllers\Client\TrackingController::class, 'seventeenTrackData'])->name('tracking.17track.data');
        Route::get('/tracking/logs', [App\Http\Controllers\Client\TrackingLogController::class, 'index'])->name('tracking.logs');
    });

    // Shipping Fees
    Route::get('/shipping-fees', [App\Http\Controllers\Client\ShippingFeeController::class, 'index'])->name('shipping-fees.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route::post('/fcm-token', [App\Http\Controllers\NotificationController::class, 'updateToken'])->name('fcm.token.update');
    Route::middleware(['auth'])->post('/fcm/token/update', function (Request $request) {
        $request->user()->update([
            'fcm_token' => $request->input('fcm_token'),
        ]);

        return response()->json(['success' => true]);
    })->name('fcm.token.update');
    Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [App\Http\Controllers\NotificationController::class, 'index'])->name('index');
        Route::post('/{notification}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/clear-all', [App\Http\Controllers\NotificationController::class, 'clearAll'])->name('clear-all');
    });
});

require __DIR__.'/auth.php';
