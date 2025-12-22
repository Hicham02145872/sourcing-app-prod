<?php

namespace App\Providers;

use App\Models\Quotation;
use App\Models\SourcingOrder;
use App\Models\SourcingRequest;
use App\Policies\QuotationPolicy;
use App\Policies\SourcingOrderPolicy;
use App\Policies\SourcingRequestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        SourcingRequest::class => SourcingRequestPolicy::class,
        Quotation::class => QuotationPolicy::class,
        SourcingOrder::class => SourcingOrderPolicy::class,
        \App\Models\RefundRequest::class => \App\Policies\RefundRequestPolicy::class,
    ];

    public function register(): void
    {
        \Illuminate\Support\Facades\Log::info('AuthServiceProvider registered');
    }

    public function boot(): void
    {
        $this->app->booted(function () {
            $this->registerPolicies();
        });
    }
}
