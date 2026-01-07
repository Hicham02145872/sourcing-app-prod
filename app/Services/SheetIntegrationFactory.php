<?php

namespace App\Services;

use App\Contracts\SheetIntegrationInterface;
use App\Models\ShippingCompany;
use Illuminate\Support\Facades\App;

class SheetIntegrationFactory
{
    /**
     * Get the appropriate sheet integration service for a shipping company.
     */
    public function getService(ShippingCompany $company): ?SheetIntegrationInterface
    {
        if ($company->google_sheet_id) {
            return App::make(GoogleSheetService::class);
        }

        if ($company->lark_app_id && $company->lark_base_token) {
            return App::make(LarkSheetService::class);
        }

        return null;
    }
}
