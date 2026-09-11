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
use Illuminate\Support\Facades\Session;

Route::get('language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Root: always default to /eng unless a locale exists in session
Route::get('/', function (Request $request) {
    $supported = ['eng', 'fr', 'ar'];
    $locale = Session::get('locale')
        ?? 'eng';
    if (! in_array($locale, $supported)) {
        $locale = 'eng';
    }

    return redirect('/'.$locale, 302);
});

// Locale-prefixed welcome page: /eng  /fr  /ar
Route::get('/{locale}', function ($locale) {
    return view('welcome');
})->where('locale', 'eng|fr|ar')->name('welcome');

// Public static pages with locale in path (same style as welcome)
Route::prefix('{locale}')
    ->where(['locale' => 'eng|fr|ar'])
    ->group(function () {
        Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy-policy');
        Route::get('/refund-policy', [PageController::class, 'refund'])->name('refund-policy');
        Route::get('/shipping-policy', [PageController::class, 'shipping'])->name('shipping-policy');
        Route::get('/terms-of-service', [PageController::class, 'terms'])->name('terms-of-service');
        Route::get('/support', [PageController::class, 'support'])->name('support');
    });

// Backward-compatible redirects for old non-localized legal/support URLs
Route::get('/privacy-policy', function (Request $request) {
    $locale = Session::get('locale', 'eng');
    $locale = $locale === 'en' ? 'eng' : $locale;

    return redirect()->route('privacy-policy', ['locale' => in_array($locale, ['eng', 'fr', 'ar']) ? $locale : 'eng']);
});
Route::get('/refund-policy', function (Request $request) {
    $locale = Session::get('locale', 'eng');
    $locale = $locale === 'en' ? 'eng' : $locale;

    return redirect()->route('refund-policy', ['locale' => in_array($locale, ['eng', 'fr', 'ar']) ? $locale : 'eng']);
});
Route::get('/shipping-policy', function (Request $request) {
    $locale = Session::get('locale', 'eng');
    $locale = $locale === 'en' ? 'eng' : $locale;

    return redirect()->route('shipping-policy', ['locale' => in_array($locale, ['eng', 'fr', 'ar']) ? $locale : 'eng']);
});
Route::get('/terms-of-service', function (Request $request) {
    $locale = Session::get('locale', 'eng');
    $locale = $locale === 'en' ? 'eng' : $locale;

    return redirect()->route('terms-of-service', ['locale' => in_array($locale, ['eng', 'fr', 'ar']) ? $locale : 'eng']);
});
Route::get('/support', function (Request $request) {
    $locale = Session::get('locale', 'eng');
    $locale = $locale === 'en' ? 'eng' : $locale;

    return redirect()->route('support', ['locale' => in_array($locale, ['eng', 'fr', 'ar']) ? $locale : 'eng']);
});

Route::get('/dashboard', function () {
    if (auth()->user()->isDeveloper()) {
        return redirect()->route('admin.dev-dashboard');
    }
    if (auth()->user()->isAdmin()) {
        return redirect('/admin/dashboard');
    }

    $locale = \App\Models\User::normalizeUrlLocale(session('locale') ?: auth()->user()->preferred_locale);

    return redirect()->route('client.dashboard', ['locale' => $locale]);
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
    Route::get('sourcing-orders/{sourcingOrder}/edit-label', [App\Http\Controllers\Admin\SourcingOrderController::class, 'editLabel'])->name('sourcing-orders.edit-label');
    Route::put('sourcing-orders/{sourcingOrder}/label', [App\Http\Controllers\Admin\SourcingOrderController::class, 'updateLabel'])->name('sourcing-orders.update-label');
    Route::get('sourcing-orders/{sourcingOrder}/shipping-label/{destination}', [App\Http\Controllers\Admin\SourcingOrderController::class, 'showShippingLabelForDestination'])->name('sourcing-orders.shipping-label.destination');
    Route::patch('sourcing-orders/{sourcingOrder}/tracking', [App\Http\Controllers\Admin\SourcingOrderController::class, 'updateTracking'])->name('sourcing-orders.update-tracking'); // Added tracking route
    Route::post('sourcing-orders/{sourcingOrder}/media', [App\Http\Controllers\Admin\SourcingOrderController::class, 'uploadMedia'])->name('sourcing-orders.media.store');
    Route::post('sourcing-orders/{sourcingOrder}/parcel', [App\Http\Controllers\Admin\SourcingOrderController::class, 'uploadParcel'])->name('sourcing-orders.parcel.store');
    Route::delete('sourcing-orders/{sourcingOrder}', [App\Http\Controllers\Admin\SourcingOrderController::class, 'destroy'])->name('sourcing-orders.destroy');
    Route::delete('media/{media}', [App\Http\Controllers\Admin\SourcingOrderController::class, 'deleteMedia'])->name('media.destroy');
    // Sourcing Requests Management
    Route::resource('sourcing-requests', App\Http\Controllers\Admin\AdminSourcingRequestController::class);
    Route::patch('/sourcing-requests/{sourcingRequest}/status', [App\Http\Controllers\Admin\AdminSourcingRequestController::class, 'updateStatus'])->name('sourcing-requests.update-status');
    Route::post('/sourcing-requests/{sourcingRequest}/assign', [App\Http\Controllers\Admin\AdminSourcingRequestController::class, 'assign'])->name('sourcing-requests.assign');
    Route::post('/sourcing-requests/{sourcingRequest}/unassign', [App\Http\Controllers\Admin\AdminSourcingRequestController::class, 'unassign'])->name('sourcing-requests.unassign');
    Route::post('/sourcing-requests/{sourcingRequest}/release', [App\Http\Controllers\Admin\AdminSourcingRequestController::class, 'release'])->name('sourcing-requests.release');

    // Refund Requests Management (toujours visible)
    Route::get('refund-requests', [App\Http\Controllers\Admin\RefundRequestController::class, 'index'])->name('refund-requests.index');
    Route::get('refund-requests/{refundRequest}', [App\Http\Controllers\Admin\RefundRequestController::class, 'show'])->name('refund-requests.show');
    Route::patch('refund-requests/{refundRequest}/update-status', [App\Http\Controllers\Admin\RefundRequestController::class, 'updateStatus'])->name('refund-requests.update-status');
    Route::patch('refund-requests/{refundRequest}/assign-to-me', [App\Http\Controllers\Admin\RefundRequestController::class, 'assignToMe'])->name('refund-requests.assign-to-me');
    Route::get('quotations/select-request', [App\Http\Controllers\Admin\QuotationController::class, 'selectRequest'])->name('quotations.select-request');
    Route::get('quotations/create/{sourcingRequest}', [App\Http\Controllers\Admin\QuotationController::class, 'create'])->name('quotations.create');
    Route::post('quotations', [App\Http\Controllers\Admin\QuotationController::class, 'store'])->name('quotations.store');
    Route::get('quotations', [App\Http\Controllers\Admin\QuotationController::class, 'index'])->name('quotations.index');
    Route::get('quotations/{quotation}', [App\Http\Controllers\Admin\QuotationController::class, 'show'])->name('quotations.show');
    Route::get('quotations/{quotation}/edit', [App\Http\Controllers\Admin\QuotationController::class, 'edit'])->name('quotations.edit');
    Route::put('quotations/{quotation}', [App\Http\Controllers\Admin\QuotationController::class, 'update'])->name('quotations.update');
    Route::put('quotations/{quotation}/approve', [App\Http\Controllers\Admin\QuotationController::class, 'approve'])->name('quotations.approve');
    Route::put('quotations/{quotation}/reject', [App\Http\Controllers\Admin\QuotationController::class, 'reject'])->name('quotations.reject');

    // Quotation Media Management
    Route::delete('quotation-media/{medium}', [App\Http\Controllers\Admin\QuotationMediaController::class, 'destroy'])->name('quotation-media.destroy');
    Route::delete('quotations/{quotation}/featured-photo', [App\Http\Controllers\Admin\QuotationMediaController::class, 'destroyFeatured'])->name('quotations.featured-photo.destroy');

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
        Route::get('/super-admin/mail-notification-preferences', [App\Http\Controllers\Admin\SuperAdminController::class, 'mailNotificationPreferences'])->name('super-admin.mail-notification-preferences');
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
        Route::get('/shipping-fees/import/excel', [App\Http\Controllers\Admin\ShippingFeeController::class, 'importForm'])->name('shipping-fees.import');
        Route::post('/shipping-fees/import/excel', [App\Http\Controllers\Admin\ShippingFeeController::class, 'import'])->name('shipping-fees.import.run');

        // Tracking Test (toujours visible)
        Route::get('/tracking/test', [App\Http\Controllers\Admin\TrackingTestController::class, 'index'])->name('tracking.test');
        Route::post('/tracking/test', [App\Http\Controllers\Admin\TrackingTestController::class, 'test'])->name('tracking.test.run');

        // Shipping Companies Management (Super Admin Only)
        Route::get('/shipping-companies', [App\Http\Controllers\Admin\ShippingCompanyController::class, 'index'])->name('shipping-companies.index');

        // Delivery Content Management (Super Admin Only)
        Route::get('/delivery-content', \App\Livewire\Admin\DeliveryContentManager::class)->name('delivery-content.index');

        // Tracking Logs (toujours visible)
        Route::get('/tracking-logs', [App\Http\Controllers\Admin\TrackingLogController::class, 'index'])->name('tracking-logs.index');

        // Admin Performance Analytics (Super Admin Only)
        Route::get('/super-admin/analytics/admin-performance', \App\Livewire\Admin\AdminPerformanceAnalytics::class)
            ->middleware('feature:admin_performance_analytics')
            ->name('super-admin.analytics.admin-performance');
    });
});

// Quitter l'impersonation (tout utilisateur authentifié ayant une session dev)
Route::middleware(['auth', 'verified'])->post('/admin/dev/stop-impersonation', function () {
    $orig = session()->pull('dev_impersonator_id');
    if ($orig) {
        \App\Services\Dev\AuditLogger::log('impersonate_stop', \App\Models\User::class, (int) $orig);
        auth()->loginUsingId((int) $orig);
    }

    return redirect()->route('admin.dev-dashboard');
})->name('admin.dev-stop-impersonation');

// Dev Dashboard & Impersonation (Strictly for Developer role)
Route::middleware(['auth', 'role:developer', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dev-dashboard', \App\Livewire\Admin\DevDashboard::class)->name('dev-dashboard');

    // UI/UX Inspector (Gemini)
    Route::get('/dev/uiux-inspector', \App\Livewire\Admin\UiUxInspector::class)->name('dev.uiux-inspector');
    Route::get('/dev/uiux/screenshot/{run}/{file}', function (string $run, string $file) {
        $file = basename($file);
        $path = storage_path('app/uiux'.DIRECTORY_SEPARATOR.$run.DIRECTORY_SEPARATOR.$file);
        if (! is_file($path)) {
            abort(404);
        }

        return response()->file($path);
    })->name('dev.uiux-screenshot');

    Route::get('/impersonate/{user}', function (\App\Models\User $user) {
        // Double security check for impersonation
        if (auth()->user()->email !== 'hichamaltit@gmail.com') {
            abort(403);
        }
        auth()->login($user);

        return redirect()->route('dashboard')->with('status', 'Impersonating '.$user->name);
    })->name('impersonate');
});

// Espace client localisé : /eng/client/..., /fr/client/..., /ar/client/...
Route::middleware(['auth', 'role:client', 'verified'])->prefix('{locale}/client')->where(['locale' => 'eng|fr|ar'])->name('client.')->group(function () {
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');

    Route::get('/sourcing-requests/handling', [SourcingRequestController::class, 'handling'])->name('sourcing-requests.handling');
    Route::get('/sourcing-requests/history-requests', [SourcingRequestController::class, 'archived'])->name('sourcing-requests.archived');
    Route::patch('/sourcing-requests/{sourcing_request}/destination-quantities', [SourcingRequestController::class, 'updateDestinationQuantities'])->name('sourcing-requests.update-destination-quantities');
    Route::resource('sourcing-requests', SourcingRequestController::class);
    Route::post('/sourcing-requests/{sourcingRequest}/duplicate', [SourcingRequestController::class, 'duplicate'])->name('sourcing-requests.duplicate');
    Route::post('/sourcing-requests/{sourcingRequest}/cancel', [SourcingRequestController::class, 'cancel'])->name('sourcing-requests.cancel');
    Route::match(['get', 'post'], '/sourcing-orders/{sourcingOrder}/upload-proof-of-payment', [App\Http\Controllers\Client\SourcingOrderController::class, 'uploadProofOfPayment'])->name('sourcing-orders.upload-proof-of-payment');
    Route::post('/quotations/{quotation}/accept', [App\Http\Controllers\Client\QuotationController::class, 'accept'])->name('quotations.accept');
    Route::post('/quotations/{quotation}/reject', [App\Http\Controllers\Client\QuotationController::class, 'reject'])->name('quotations.reject');
    Route::post('/quotations/{quotation}/negotiate', [App\Http\Controllers\Client\QuotationController::class, 'negotiate'])->name('quotations.negotiate');
    Route::get('/quotations/bulk-payment', [App\Http\Controllers\Client\QuotationController::class, 'bulkPaymentShow'])->name('quotations.bulk-payment');
    Route::post('/quotations/bulk-pay', [App\Http\Controllers\Client\QuotationController::class, 'bulkPay'])->name('quotations.bulk-pay');
    Route::get('/quotations', [App\Http\Controllers\Client\QuotationController::class, 'index'])->name('quotations.index');

    Route::get('/sourcing-orders', [App\Http\Controllers\Client\SourcingOrderController::class, 'index'])->name('sourcing-orders.index');
    Route::get('/sourcing-orders/export', [App\Http\Controllers\Client\SourcingOrderController::class, 'export'])->name('sourcing-orders.export');
    Route::get('/sourcing-orders/{sourcingOrder}', [App\Http\Controllers\Client\SourcingOrderController::class, 'show'])->name('sourcing-orders.show');
    Route::get('/sourcing-orders/{sourcingOrder}/receipt', [App\Http\Controllers\Client\SourcingOrderController::class, 'showReceipt'])->name('sourcing-orders.receipt');
    Route::get('/sourcing-orders/{sourcingOrder}/shipping-label', [App\Http\Controllers\Client\SourcingOrderController::class, 'showShippingLabel'])->name('sourcing-orders.shipping-label');
    Route::get('/sourcing-orders/{sourcingOrder}/download-proof-of-payment', [App\Http\Controllers\Client\SourcingOrderController::class, 'downloadProofOfPayment'])->name('sourcing-orders.download-proof-of-payment');
    // Refunds (toujours visible)
    Route::get('/refunds', [App\Http\Controllers\Client\RefundRequestController::class, 'index'])->name('refund-requests.index');
    Route::get('/refund-requests/create/{sourcingOrder}', [App\Http\Controllers\Client\RefundRequestController::class, 'create'])->name('refund-requests.create');
    Route::post('/sourcing-orders/{sourcingOrder}/refund-request', [App\Http\Controllers\Client\RefundRequestController::class, 'store'])->name('sourcing-orders.refund-request');
    Route::get('/refund-requests/{refundRequest}', [App\Http\Controllers\Client\RefundRequestController::class, 'show'])->name('refund-requests.show');
    Route::get('/history', [SourcingRequestController::class, 'history'])->name('history');
    Route::get('/history/export', [SourcingRequestController::class, 'exportHistory'])->name('history.export');

    // Notification API routes for client sidebar
    Route::get('/notifications-api', [App\Http\Controllers\Client\NotificationController::class, 'index'])->name('notifications.api.index');
    Route::post('/notifications-api/{notification}/mark-as-read', [App\Http\Controllers\Client\NotificationController::class, 'markAsRead'])->name('notifications.api.mark-as-read');

    // Tracking (toujours accessible pour le client)
    Route::get('/tracking', [App\Http\Controllers\Client\TrackingController::class, 'index'])->name('tracking.index');
    Route::middleware('throttle:10,1')->get('/tracking/data', [App\Http\Controllers\Client\TrackingController::class, 'data'])->name('tracking.data');
    Route::get('/tracking/17track', [App\Http\Controllers\Client\TrackingController::class, 'seventeenTrackIndex'])->name('tracking.17track.index');
    Route::middleware('throttle:10,1')->get('/tracking/1track/data', [App\Http\Controllers\Client\TrackingController::class, 'seventeenTrackData'])->name('tracking.17track.data');
    Route::get('/tracking/logs', [App\Http\Controllers\Client\TrackingLogController::class, 'index'])->name('tracking.logs');

    // Shipping Fees
    Route::get('/shipping-fees', [App\Http\Controllers\Client\ShippingFeeController::class, 'index'])->name('shipping-fees.index');
    Route::get('/shipping-fees/{country}', [App\Http\Controllers\Client\ShippingFeeController::class, 'getShippingFee'])->name('shipping-fees.get');
    Route::get('/shipping-popup-rates/{country}', [App\Http\Controllers\Client\ShippingFeeController::class, 'getRatesForPopup'])->name('shipping-fees.popup-rates');
});

// Anciennes URLs /client/... → /{locale}/client/... (session ou préférence utilisateur)
Route::middleware(['auth', 'role:client', 'verified'])->get('/client/{extra?}', function (Request $request, ?string $extra = null) {
    $locale = \App\Models\User::normalizeUrlLocale(session('locale') ?: auth()->user()?->preferred_locale);
    $tail = ($extra !== null && $extra !== '') ? '/'.ltrim($extra, '/') : '/dashboard';
    $qs = $request->getQueryString();

    return redirect('/'.$locale.'/client'.$tail.($qs ? '?'.$qs : ''));
})->where('extra', '.*');

Route::middleware(['auth', 'verified.client'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/fcm/token/update', function (Request $request) {
        $request->user()->update([
            'fcm_token' => $request->input('fcm_token'),
        ]);

        return response()->json(['success' => true]);
    })->name('fcm.token.update');

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [App\Http\Controllers\NotificationController::class, 'index'])->name('index');
        Route::post('/{notification}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/clear-all', [App\Http\Controllers\NotificationController::class, 'clearAll'])->name('clear-all');
    });
});

// Dev Login (dedicated route for developers only)
Route::middleware('guest')->group(function () {
    Route::get('/dev/login', [\App\Http\Controllers\Auth\DevLoginController::class, 'showLoginForm'])->name('dev.login');
    Route::post('/dev/login', [\App\Http\Controllers\Auth\DevLoginController::class, 'login'])->middleware('throttle:5,1');
});
Route::middleware('guest')->prefix('{locale}')->where(['locale' => 'eng|fr|ar'])->group(function () {
    Route::get('/dev/login', [\App\Http\Controllers\Auth\DevLoginController::class, 'showLoginForm'])->name('dev.login.locale');
    Route::post('/dev/login', [\App\Http\Controllers\Auth\DevLoginController::class, 'login'])->middleware('throttle:5,1');
});

require __DIR__.'/auth.php';
