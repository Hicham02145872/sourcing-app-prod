<?php

namespace App\Services;

use App\Models\ShippingCompany;
use App\Models\SourcingOrder;
use App\Services\ShippingLabelImageService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LarkSheetService implements \App\Contracts\SheetIntegrationInterface
{
    protected string $baseUrl = 'https://open.larksuite.com/open-apis';

    public const STATUS_COLORS = [
        'PENDING PAYMENT' => '#FFF2CC', // Yellow
        'PAID' => '#E2EFDA', // Light Green
        'SHIPMENT PREPARING' => '#DDEBF7', // Light Blue
        'IN TRANSIT CHINA' => '#DDEBF7',
        'DELIVERED' => '#C6E0B4', // Green
        'SHIPMENT CANCELED' => '#FBE5D6', // Light Red
        'DEFAULT' => '#FFFFFF',
    ];

    /**
     * Test the connection to Lark Suite.
     */
    public function testConnection(array $config): array
    {
        $appId = $config['lark_app_id'] ?? '';
        $appSecret = $config['lark_app_secret'] ?? '';
        $baseToken = $config['lark_base_token'] ?? '';

        $token = $this->getAccessToken($appId, $appSecret);

        if (! $token) {
            return ['success' => false, 'message' => 'Authentication failed.'];
        }

        $realToken = $this->resolveRealToken($baseToken, $token);

        $url = "{$this->baseUrl}/sheets/v2/spreadsheets/{$realToken}/metainfo";
        $response = Http::withToken($token)->get($url);

        if ($response->successful()) {
            return ['success' => true, 'message' => 'Connection successful!'];
        }

        return ['success' => false, 'message' => 'Failed to access Spreadsheet.'];
    }

    /**
     * Ensure headers and formatting.
     */
    public function ensureHeaders(ShippingCompany $company): array
    {
        $token = $this->getAccessToken($company->lark_app_id, $company->lark_app_secret);
        if (! $token) {
            return ['success' => false, 'message' => 'Auth failed'];
        }

        $realToken = $this->resolveRealToken($company->lark_base_token, $token);
        $targetSheetTitle = $company->lark_table_id ?: 'Sheet1';
        $sheetId = $this->resolveSheetId($realToken, $token, $targetSheetTitle);

        if (! $sheetId) {
            return ['success' => false, 'message' => 'Sheet not found'];
        }

        $this->checkAndInstallHeaders($sheetId, $realToken, $token);

        // Add Advanced V2 Features
        $this->addStatusDataValidation($realToken, $token, $sheetId);
        $this->addStatusConditionalFormatting($realToken, $token, $sheetId);
        $this->styleHeaders($realToken, $token, $sheetId);

        return ['success' => true, 'message' => 'Headers and formatting installed.'];
    }

    /**
     * Add data validation (dropdown) for Status column (C).
     */
    protected function addStatusDataValidation(string $spreadsheetToken, string $accessToken, string $sheetId): void
    {
        // Status is at index 2 (Column C)
        $url = "{$this->baseUrl}/sheets/v2/spreadsheets/{$spreadsheetToken}/values_dropdown";

        $payload = [
            'range' => "{$sheetId}!C2:C1000",
            'dropdown' => [
                'options' => array_values(array_unique(SourcingOrder::STATUSES)),
                'colors' => array_fill(0, count(SourcingOrder::STATUSES), '#FFFFFF'),
            ],
        ];

        Http::withToken($accessToken)->post($url, $payload);
    }

    /**
     * Add conditional formatting for Status column (C).
     */
    protected function addStatusConditionalFormatting(string $spreadsheetToken, string $accessToken, string $sheetId): void
    {
        $url = "{$this->baseUrl}/sheets/v2/spreadsheets/{$spreadsheetToken}/condition_formats";

        // We need to fetch existing if we want to be clean, but for now we just overwrite/append
        $formats = [];
        foreach (self::STATUS_COLORS as $status => $color) {
            if ($status === 'DEFAULT') {
                continue;
            }

            $formats[] = [
                'ranges' => ["{$sheetId}!C2:C1000"],
                'rule_type' => 'containsText',
                'attrs' => ['text' => $status],
                'style' => [
                    'back_color' => $color,
                    'font' => ['bold' => true],
                ],
            ];
        }

        $payload = ['sheet_condition_formats' => [['sheet_id' => $sheetId, 'condition_format' => $formats[0]]]];
        // Lark API for multiple conditional formats is usually one by one or a batch update if supported.
        // Actually the documentation says we can send an array.

        foreach ($formats as $format) {
            Http::withToken($accessToken)->post($url, [
                'sheet_id' => $sheetId,
                'condition_format' => $format,
            ]);
        }
    }

    /**
     * Style the header row (Orange background, Bold White text).
     */
    protected function styleHeaders(string $spreadsheetToken, string $accessToken, string $sheetId): void
    {
        $url = "{$this->baseUrl}/sheets/v2/spreadsheets/{$spreadsheetToken}/styles";

        $payload = [
            'range' => "{$sheetId}!A1:L1",
            'style' => [
                'font' => [
                    'bold' => true,
                    'font_size' => '11',
                    'fore_color' => '#FFFFFF',
                ],
                'back_color' => '#FF9900', // Strong Orange
                'h_align' => 1, // Center
                'v_align' => 1, // Center
            ],
        ];

        Http::withToken($accessToken)->put($url, $payload);
    }

    /**
     * Update order status in Lark.
     */
    public function updateOrderStatus(SourcingOrder $order, string $newStatus): bool
    {
        $company = $order->shippingCompany;
        if (! $company) {
            return false;
        }

        $token = $this->getAccessToken($company->lark_app_id, $company->lark_app_secret);
        if (! $token) {
            return false;
        }

        $realToken = $this->resolveRealToken($company->lark_base_token, $token);
        $targetSheetTitle = $company->lark_table_id ?: 'Sheet1';
        $sheetId = $this->resolveSheetId($realToken, $token, $targetSheetTitle);

        // Find the row. We use Order ID (Column A)
        // Order ID in sheet is (int) ($order->id * 5)
        $displayId = (string) ($order->id * 5);

        $urlFetch = "{$this->baseUrl}/sheets/v2/spreadsheets/{$realToken}/values/".urlencode("{$sheetId}!A:A");
        $response = Http::withToken($token)->get($urlFetch);

        if ($response->successful()) {
            $values = $response->json()['data']['valueRange']['values'] ?? [];
            foreach ($values as $index => $row) {
                if (isset($row[0]) && (string) $row[0] === $displayId) {
                    $rowIndex = $index + 1;
                    $urlUpdate = "{$this->baseUrl}/sheets/v2/spreadsheets/{$realToken}/values";
                    $payload = [
                        'valueRange' => [
                            'range' => "{$sheetId}!C{$rowIndex}",
                            'values' => [[strtoupper(str_replace('_', ' ', $newStatus))]],
                        ],
                    ];
                    Http::withToken($token)->put($urlUpdate, $payload);

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Batch sync multiple orders.
     */
    public function batchSync(array $orders, ShippingCompany $company): array
    {
        $token = $this->getAccessToken($company->lark_app_id, $company->lark_app_secret);
        if (! $token) {
            return ['success' => false, 'message' => 'Auth failed'];
        }

        $realToken = $this->resolveRealToken($company->lark_base_token, $token);
        $targetSheetTitle = $company->lark_table_id ?: 'Sheet1';
        $sheetId = $this->resolveSheetId($realToken, $token, $targetSheetTitle);

        $rows = [];
        foreach ($orders as $order) {
            $order->load(['user', 'quotation.sourcingRequest.destinations.country', 'destinationShipments', 'shippingCompany']);
            $destinations = $order->quotation->sourcingRequest->destinations;

            if ($destinations->isEmpty()) {
                $rows[] = $this->mapOrderToRow($order, null);
            } else {
                foreach ($destinations as $destination) {
                    $rows[] = $this->mapOrderToRow($order, $destination);
                }
            }
        }

        $url = "{$this->baseUrl}/sheets/v2/spreadsheets/{$realToken}/values_append";
        $payload = [
            'valueRange' => [
                'range' => $sheetId,
                'values' => $rows,
            ],
        ];

        $response = Http::withToken($token)->post($url, $payload);

        if ($response->successful()) {
            return ['success' => true, 'synced' => count($rows)];
        }

        return ['success' => false, 'message' => 'Sync failed'];
    }

    /**
     * Resolve the real Spreadsheet Token from a Wiki Token if necessary.
     */
    /**
     * Resolve the real Spreadsheet Token from a Wiki Token or URL.
     */
    public function resolveRealToken(string $tokenOrUrl, string $accessToken): string
    {
        // 1. Extract token if it's a full URL
        if (filter_var($tokenOrUrl, FILTER_VALIDATE_URL)) {
            // Pattern: sequences of alphanumeric chars, at least 15 chars long
            if (preg_match('/(sht[a-zA-Z0-9]{15,})/', $tokenOrUrl, $matches)) {
                return $matches[1];
            }
            if (preg_match('/(wik[a-zA-Z0-9]{15,})/', $tokenOrUrl, $matches)) {
                $tokenOrUrl = $matches[1];
            }
        }

        // 2. If it's already a sheet token, return it
        if (str_starts_with($tokenOrUrl, 'sht')) {
            return $tokenOrUrl;
        }

        // 3. Assume it's a Wiki token (or unknown), try to resolve via Wiki API
        Log::info("Attempting to resolve potential Wiki token: {$tokenOrUrl}");

        // GET /open-apis/wiki/v2/spaces/get_node?token={token}
        $url = "{$this->baseUrl}/wiki/v2/spaces/get_node?token={$tokenOrUrl}";

        $response = Http::withToken($accessToken)->get($url);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['code']) && $data['code'] === 0 && isset($data['data']['node']['obj_type']) && $data['data']['node']['obj_type'] === 'sheet') {
                $sheetToken = $data['data']['node']['obj_token'];
                Log::info("Resolved Wiki token {$tokenOrUrl} to Sheet token {$sheetToken}");

                return $sheetToken;
            }
            Log::warning('Wiki resolution success but content not a sheet or error: '.json_encode($data));
        } else {
            Log::warning('Wiki resolution failed HTTP status: '.$response->status().' Body: '.$response->body());
        }

        // Fallback: return the original string, maybe it's a raw token we didn't recognize pattern for
        return $tokenOrUrl;
    }

    /**
     * Get the tenant access token for the specific app.
     */
    public function getAccessToken(string $appId, string $appSecret): ?string
    {
        $cacheKey = "lark_access_token_{$appId}";

        return Cache::remember($cacheKey, 7000, function () use ($appId, $appSecret) {
            $response = Http::post("{$this->baseUrl}/auth/v3/tenant_access_token/internal", [
                'app_id' => $appId,
                'app_secret' => $appSecret,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                return $data['tenant_access_token'] ?? null;
            }

            Log::error('Failed to get Lark access token: '.$response->body());

            return null;
        });
    }

    /**
     * Sync the order to the shipping company's Lark sheet (Spreadsheet).
     */
    public function syncOrder(SourcingOrder $order, ShippingCompany $company): bool
    {
        if (! $company->lark_app_id || ! $company->lark_app_secret || ! $company->lark_base_token) {
            Log::warning("Lark configuration missing for shipping company: {$company->name}");

            return false;
        }

        $token = $this->getAccessToken($company->lark_app_id, $company->lark_app_secret);

        if (! $token) {
            Log::error("Could not obtain Lark access token for company: {$company->name}");

            return false;
        }

        $realToken = $this->resolveRealToken($company->lark_base_token, $token);

        // Resolve the actual Sheet ID because Lark V2 requires sheetId (e.g. "19557a") not title ("Sheet1")
        $targetSheetTitle = $company->lark_table_id ?: 'Sheet1';
        $sheetId = $this->resolveSheetId($realToken, $token, $targetSheetTitle);

        if (! $sheetId) {
            Log::error("Could not find sheet with title '{$targetSheetTitle}' for company {$company->name}");

            return false;
        }

        // Auto-install headers if missing
        $this->ensureHeaders($company);

        $order->load(['user', 'quotation.sourcingRequest.destinations.country', 'destinationShipments', 'shippingCompany']);
        $destinations = $order->quotation->sourcingRequest->destinations;

        if ($destinations->isEmpty()) {
            Log::warning("Order #{$order->id} has no destinations defined. Syncing basic data.");
            $rows = [$this->mapOrderToRow($order, null)];
        } else {
            $rows = [];
            foreach ($destinations as $destination) {
                $rows[] = $this->mapOrderToRow($order, $destination);
            }
        }

        // Sheets API v2: Append data
        $url = "{$this->baseUrl}/sheets/v2/spreadsheets/{$realToken}/values_append";

        $payload = [
            'valueRange' => [
                'range' => $sheetId,
                'values' => $rows,
            ],
        ];

        Log::info("Syncing Order #{$order->id} to Lark (Multiple rows: ".count($rows)."). Range used (SheetID): {$sheetId}.");

        $response = Http::withToken($token)->post($url, $payload);
        $responseData = $response->json();

        if ($response->successful() && isset($responseData['code']) && $responseData['code'] === 0) {
            Log::info("Successfully synced order #{$order->id} to Lark Sheet.");

            // Adjust row height for the newly added rows to make images visible
            if (isset($responseData['data']['updates']['updatedRange'])) {
                $range = $responseData['data']['updates']['updatedRange']; // e.g., "sheetId!A2:K2"
                if (preg_match('/!([A-Z]+)(\d+):([A-Z]+)(\d+)/', $range, $matches)) {
                    $startRow = (int) $matches[2] - 1; // 0-indexed
                    $endRow = (int) $matches[4]; // exclusive
                    $this->updateDimension($realToken, $token, $sheetId, 'ROWS', $startRow, $endRow, 140);
                }
            }

            return true;
        }

        Log::error("Failed to sync order #{$order->id} to Lark Sheet. Response: ".$response->body());

        return false;
    }

    /**
     * Resolve the internal Sheet ID (e.g. "19557a") from a Title (e.g. "Sheet1")
     * defaults to the first sheet if title not found or null.
     */
    protected function resolveSheetId(string $spreadsheetToken, string $accessToken, string $targetTitle = 'Sheet1'): ?string
    {
        $url = "{$this->baseUrl}/sheets/v2/spreadsheets/{$spreadsheetToken}/metainfo";
        $response = Http::withToken($accessToken)->get($url);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['code']) && $data['code'] === 0 && isset($data['data']['sheets'])) {
                foreach ($data['data']['sheets'] as $sheet) {
                    if ($sheet['title'] === $targetTitle) {
                        return $sheet['sheetId'];
                    }
                }
                if (! empty($data['data']['sheets'])) {
                    return $data['data']['sheets'][0]['sheetId'];
                }
            }
        }

        return null;
    }

    /**
     * Check if the sheet has headers, if not, install them.
     */
    protected function checkAndInstallHeaders(string $sheetId, string $spreadsheetToken, string $accessToken): void
    {
        $url = "{$this->baseUrl}/sheets/v2/spreadsheets/{$spreadsheetToken}/values/".urlencode("{$sheetId}!A1:Z1");
        $response = Http::withToken($accessToken)->get($url);

        $isEmpty = true;
        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['code']) && $data['code'] === 0) {
                $values = $data['data']['valueRange']['values'] ?? [];
                if (! empty($values) && isset($values[0])) {
                    // Check if the first row actually has any non-null, non-empty content
                    foreach ($values[0] as $cell) {
                        if ($cell !== null && $cell !== '') {
                            $isEmpty = false;
                            break;
                        }
                    }
                }
            }
        }

        if ($isEmpty) {
            Log::info("Sheet {$sheetId} is empty. Installing headers using values_update (A1).");

            $headers = ['PICTURE', 'SELLING DATE', 'PRODUCT NAME', 'QUANTITY', 'PRODUCT PRICE', 'TOTAL PRICE', 'TRACKING NUMBER FROM CHINA', 'Shipping address', 'LABEL SHIPPING'];

            $urlUpdate = "{$this->baseUrl}/sheets/v2/spreadsheets/{$spreadsheetToken}/values";

            $payload = [
                'valueRange' => [
                    'range' => "{$sheetId}!A1:I1",
                    'values' => [$headers],
                ],
            ];

            $postResponse = Http::withToken($accessToken)->put($urlUpdate, $payload);

            if (! $postResponse->successful() || ($postResponse->json()['code'] ?? -1) !== 0) {
                Log::error('Failed to install headers. Response: '.$postResponse->body());
            }
        }

        $this->updateDimension($spreadsheetToken, $accessToken, $sheetId, 'COLUMNS', 1, 9, 160);
        $this->updateDimension($spreadsheetToken, $accessToken, $sheetId, 'COLUMNS', 1, 1, 250);
        $this->updateDimension($spreadsheetToken, $accessToken, $sheetId, 'COLUMNS', 9, 9, 300);
        $this->updateDimension($spreadsheetToken, $accessToken, $sheetId, 'ROWS', 1, 100, 150);
    }

    /**
     * Update row height or column width in the spreadsheet.
     */
    public function updateDimension(string $spreadsheetToken, string $accessToken, string $sheetId, string $majorDimension, int $startIndex, int $endIndex, int $size): bool
    {
        $url = "{$this->baseUrl}/sheets/v2/spreadsheets/{$spreadsheetToken}/dimension_range";

        $payload = [
            'dimension' => [
                'sheetId' => $sheetId,
                'majorDimension' => $majorDimension,
                'startIndex' => $startIndex,
                'endIndex' => $endIndex,
            ],
            'dimensionProperties' => [
                'fixedSize' => $size,
            ],
        ];

        $response = Http::withToken($accessToken)->put($url, $payload);

        if (! $response->successful() || ($response->json()['code'] ?? -1) !== 0) {
            Log::error("Failed to update dimension ({$majorDimension}) for sheet {$sheetId}. Status: ".$response->status().' Body: '.$response->body());

            return false;
        }

        return true;
    }

    /**
     * Map order data to a simple indexed array for Spreadsheet.
     */
    protected function mapOrderToRow(SourcingOrder $order, ?\App\Models\SourcingRequestDestination $destination): array
    {
        $sr = $order->quotation->sourcingRequest;
        $quotation = $order->quotation;

        $quantity = $destination ? $destination->quantity : $sr->destinations->sum('quantity');
        $imageUrl = $sr->product_image ? '=IMAGE("'.asset('storage/'.$sr->product_image).'")' : '';

        $labelImageUrl = ShippingLabelImageService::getImageUrl($order, $destination);
        $shippingLabelCell = $labelImageUrl ? '=IMAGE("'.$labelImageUrl.'")' : '';

        $unitPrice = (float) ($quotation->unit_price ?? 0);
        $totalPrice = $unitPrice * (int) $quantity;

        return [
            $imageUrl,
            $order->created_at->format('Y-m-d H:i:s'),
            $sr->product_name,
            (int) $quantity,
            number_format($unitPrice, 2, '.', ''),
            number_format($totalPrice, 2, '.', ''),
            '',
            '',
            $shippingLabelCell,
        ];
    }
}
