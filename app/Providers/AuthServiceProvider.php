<?php

namespace App\Providers;

use App\Models\SourcingRequest;
use App\Policies\SourcingRequestPolicy;
use App\Models\Quotation;
use App\Policies\QuotationPolicy;
use App\Models\SourcingOrder;
use App\Policies\SourcingOrderPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        SourcingRequest::class => SourcingRequestPolicy::class,
        Quotation::class => QuotationPolicy::class,
        SourcingOrder::class => SourcingOrderPolicy::class,
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
