<?php

namespace App\Services;

use App\Models\GoogleSheetSetting;
use App\Models\GoogleSheetSyncLog;
use App\Models\SourcingOrder;
use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\BatchUpdateSpreadsheetRequest;
use Google\Service\Sheets\Request;
use Google\Service\Sheets\ValueRange;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GoogleSheetService implements \App\Contracts\SheetIntegrationInterface
{
    protected $client;

    protected $sheetsService;

    protected $spreadsheetId;

    protected $sheetName;

    protected $sheetId;

    public function __construct(?string $spreadsheetId = null, ?string $sheetName = null)
    {
        // Use provided values or fallback to global settings
        if ($spreadsheetId === null) {
            $setting = \App\Models\GoogleSheetSetting::first();
            if (! $setting || ! $setting->sheet_id) {
                \Log::error('Google Sheet settings are not configured in the database.');
                throw new \Exception('Google Sheet settings are not configured.');
            }
            $this->spreadsheetId = $setting->sheet_id;
            $this->sheetName = $sheetName ?? $setting->sheet_name ?? 'sourcing';
        } else {
            $this->spreadsheetId = $spreadsheetId;
            $this->sheetName = $sheetName ?? 'sourcing';
        }

        $this->client = new \Google\Client;
        $this->client->setApplicationName('Sourcing App Google Sheets Integration');
        $this->client->setScopes([\Google\Service\Sheets::SPREADSHEETS]);
        $this->client->setAccessType('offline');

        $credentialsPath = storage_path(config('services.google.credentials_path'));
        if (! file_exists($credentialsPath)) {
            \Log::error('Google Sheets API credentials file not found at: '.$credentialsPath);
            throw new \Exception('Google Sheets API credentials file not found.');
        }
        $this->client->setAuthConfig($credentialsPath);

        $this->sheetsService = new \Google\Service\Sheets($this->client);
        $this->sheetId = $this->getSheetIdByName($this->sheetName);
    }

    /**
     * Test the connection to the integration.
     */
    public function testConnection(array $config): array
    {
        return self::staticTestConnection($config['google_sheet_id'] ?? null);
    }

    /**
     * Sync a single order to the sheet.
     */
    public function syncOrder(\App\Models\SourcingOrder $order, \App\Models\ShippingCompany $company): bool
    {
        $this->spreadsheetId = $company->google_sheet_id;
        $this->sheetName = $company->sheet_name ?: 'sourcing';
        $this->sheetId = $this->getSheetIdByName($this->sheetName);

        if (! $this->spreadsheetId) {
            return false;
        }

        $order->load(['user', 'quotation.sourcingRequest.destinations.country', 'destinationShipments', 'shippingCompany']);
        $destinations = $order->quotation->sourcingRequest->destinations;
        $syncedFields = array_keys(self::SHIPPING_SHEET_HEADERS);

        try {
            if ($destinations->count() > 1) {
                foreach ($destinations as $index => $destination) {
                    $data = $this->mapOrderToDataForShippingSheet($order, $destination);
                    $displayId = ($order->id * 5) + $index;
                    $this->upsertRow($data, $displayId, $order->id, $syncedFields);
                }
            } else {
                $dest = $destinations->first();
                $data = $this->mapOrderToDataForShippingSheet($order, $dest);
                $this->upsertRow($data, $order->id * 5, $order->id, $syncedFields);
            }

            return true;
        } catch (\Exception $e) {
            \Log::error("Google Sheet Sync Failed for Order #{$order->id}: ".$e->getMessage());

            return false;
        }
    }

    /**
     * Update the status of an existing order row.
     */
    public function updateOrderStatus(\App\Models\SourcingOrder $order, string $newStatus): bool
    {
        $company = $order->shippingCompany;
        if (! $company || ! $company->google_sheet_id) {
            return false;
        }

        $this->spreadsheetId = $company->google_sheet_id;
        $this->sheetName = $company->sheet_name ?: 'sourcing';

        try {
            // Internal logic uses displayId for matching
            return $this->originalUpdateOrderStatus($order->id * 5, $newStatus);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Batch Sync (shipping company sheet: same columns as Lark).
     */
    public function batchSync(array $orders, \App\Models\ShippingCompany $company): array
    {
        $this->spreadsheetId = $company->google_sheet_id;
        $this->sheetName = $company->sheet_name ?: 'sourcing';

        $allData = [];
        foreach ($orders as $order) {
            $order->load(['user', 'quotation.sourcingRequest.destinations.country', 'destinationShipments', 'shippingCompany']);
            $destinations = $order->quotation->sourcingRequest->destinations;

            if ($destinations->count() > 1) {
                foreach ($destinations as $index => $destination) {
                    $data = $this->mapOrderToDataForShippingSheet($order, $destination);
                    $data['id'] = ($order->id * 5) + $index;
                    $allData[] = $data;
                }
            } else {
                $allData[] = $this->mapOrderToDataForShippingSheet($order, $destinations->first());
            }
        }

        try {
            $syncedFields = array_keys(self::SHIPPING_SHEET_HEADERS);
            $stats = $this->batchUpsertRows($allData, $syncedFields);

            return ['success' => true, 'synced' => ($stats['updated'] + $stats['appended'])];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Helper to map order to data array (main app sheet).
     */
    protected function mapOrderToData(\App\Models\SourcingOrder $order): array
    {
        $carrier = $order->tracking_carrier
            ?? $order->shippingCompany?->name
            ?? '';

        return [
            'id' => $order->id * 5,
            'created_at' => $order->created_at->format('Y-m-d H:i:s'),
            'status' => $order->status,
            'client_name' => $order->user->name,
            'client_email' => $order->user->email,
            'product_name' => $order->quotation->sourcingRequest->product_name ?? 'N/A',
            'quantity' => $order->quotation->sourcingRequest->quantity ?? 0,
            'total_amount' => $order->total_amount,
            'tracking_number' => $order->tracking_number ?? '',
            'carrier' => $carrier,
        ];
    }

    protected function mapOrderToDataForShippingSheet(\App\Models\SourcingOrder $order, ?\App\Models\SourcingRequestDestination $destination = null): array
    {
        $sr = $order->quotation->sourcingRequest;
        $quotation = $order->quotation;

        $quantity = $destination ? $destination->quantity : $sr->destinations->sum('quantity');
        $imageUrl = $sr->product_image ? '=IMAGE("'.asset('storage/'.$sr->product_image).'", 1)' : '';

        $labelImageUrl = \App\Services\ShippingLabelImageService::getImageUrl($order, $destination);
        $shippingLabelCell = '=IMAGE("'.$labelImageUrl.'", 1)';

        $unitPrice = $quotation->unit_price ?? 0;
        $totalPrice = $order->total_amount ?? 0;

        return [
            'product_image' => $imageUrl,
            'created_at' => $order->created_at->format('Y-m-d H:i:s'),
            'product_name' => $sr->product_name ?? 'N/A',
            'quantity' => (int) $quantity,
            'product_price' => (string) $unitPrice,
            'total_price' => (string) $totalPrice,
            'tracking_number' => '',
            'address' => '',
            'shipping_label' => $shippingLabelCell,
        ];
    }

    /**
     * Ensure headers and formatting.
     * For shipping company sheets, uses the same headers as Lark (Order ID, Date, Status, Client Name, etc.).
     */
    public function ensureHeaders(\App\Models\ShippingCompany $company): array
    {
        $this->spreadsheetId = $company->google_sheet_id;
        $this->sheetName = $company->sheet_name ?: 'sourcing';
        $this->sheetId = $this->getSheetIdByName($this->sheetName);

        if (! $this->spreadsheetId || ! $this->sheetId) {
            return ['success' => false, 'message' => 'Config missing'];
        }

        return $this->runEnsureHeaders($company);
    }

    protected function runEnsureHeaders(?\App\Models\ShippingCompany $company = null): array
    {
        return $this->originalEnsureHeaders($company);
    }

    protected function getSheetIdByName(string $sheetName): ?int
    {
        $cacheKey = $this->getSheetCacheKey($sheetName);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $sheetId = $this->fetchSheetIdFromApi($sheetName);

        if ($sheetId !== null) {
            Cache::put($cacheKey, $sheetId, now()->addHour());
        }

        return $sheetId;
    }

    protected function getSheetCacheKey(string $sheetName): string
    {
        return "google_sheet_id:{$this->spreadsheetId}:{$sheetName}";
    }

    protected function fetchSheetIdFromApi(string $sheetName): ?int
    {
        try {
            $spreadsheet = $this->sheetsService->spreadsheets->get($this->spreadsheetId);
            foreach ($spreadsheet->getSheets() as $sheet) {
                if ($sheet->getProperties()->getTitle() === $sheetName) {
                    return $sheet->getProperties()->getSheetId();
                }
            }
        } catch (\Exception $e) {
            Log::error('Could not fetch sheet ID from API: '.$e->getMessage());
        }

        return null;
    }

    /**
     * Create a new sheet (tab) in the spreadsheet.
     */
    public function createSheet(string $sheetName)
    {
        try {
            $requests = [
                new Request([
                    'addSheet' => [
                        'properties' => [
                            'title' => $sheetName,
                        ],
                    ],
                ]),
            ];

            $batchUpdateRequest = new BatchUpdateSpreadsheetRequest(['requests' => $requests]);
            $response = $this->sheetsService->spreadsheets->batchUpdate($this->spreadsheetId, $batchUpdateRequest);

            // Update local property
            $this->sheetName = $sheetName;
            $this->sheetId = $response->getReplies()[0]->getAddSheet()->getProperties()->getSheetId();

            Log::info("Created new sheet '{$sheetName}' in spreadsheet {$this->spreadsheetId}");

            return true;

        } catch (\Google\Service\Exception $e) {
            Log::error("Failed to create sheet '{$sheetName}': ".$e->getMessage());
            throw new \Exception("Failed to create sheet '{$sheetName}': ".$e->getMessage());
        }
    }

    protected function originalEnsureHeaders(?\App\Models\ShippingCompany $company = null): array
    {
        if ($this->sheetId === null) {
            Log::error("Could not find sheet ID for sheet name: {$this->sheetName}. Cannot style headers.");

            return [
                'success' => false,
                'message' => __('Could not find sheet ID for sheet name: :name. Please check your sheet name configuration.', ['name' => $this->sheetName]),
            ];
        }

        try {
            // Check if headers exist (check first row)
            $range = $this->sheetName.'!A1:Z1';
            $response = $this->sheetsService->spreadsheets_values->get($this->spreadsheetId, $range);
            $values = $response->getValues();

            if (empty($values)) {
                if ($company !== null) {
                    // Shipping company sheet: same headers as Lark
                    $availableFields = self::SHIPPING_SHEET_HEADERS;
                    $syncedFields = array_keys($availableFields);
                    $headers = array_values($availableFields);
                } else {
                    $setting = GoogleSheetSetting::first();
                    $availableFields = self::AVAILABLE_FIELDS;
                    $syncedFields = $setting->synced_fields ?? array_keys($availableFields);

                    // Build headers
                    $headers = [];
                    foreach ($syncedFields as $fieldKey) {
                        if (isset($availableFields[$fieldKey])) {
                            $headers[] = $availableFields[$fieldKey];
                        }
                    }
                    if (empty($headers)) {
                        $headers = array_values($availableFields);
                    }
                }

                // Set header values
                $lastColumnLetter = self::indexToLetter(count($headers));
                $range = $this->sheetName.'!A1:'.$lastColumnLetter.'1';

                $body = new ValueRange(['values' => [$headers]]);
                $params = ['valueInputOption' => 'RAW'];
                $this->sheetsService->spreadsheets_values->update($this->spreadsheetId, $range, $body, $params);

                // Prepare batch requests for styling
                $requests = [];

                // 1. Style headers: Dark blue background, white text, bold, centered
                $requests[] = new Request([
                    'repeatCell' => [
                        'range' => [
                            'sheetId' => $this->sheetId,
                            'startRowIndex' => 0,
                            'endRowIndex' => 1,
                            'startColumnIndex' => 0,
                            'endColumnIndex' => count($headers),
                        ],
                        'cell' => [
                            'userEnteredFormat' => [
                                'backgroundColor' => ['red' => 0.16, 'green' => 0.20, 'blue' => 0.31], // Dark slate
                                'horizontalAlignment' => 'CENTER',
                                'verticalAlignment' => 'MIDDLE',
                                'textFormat' => [
                                    'bold' => true,
                                    'fontSize' => 11,
                                    'foregroundColor' => ['red' => 1, 'green' => 1, 'blue' => 1],
                                ],
                                'borders' => [
                                    'top' => ['style' => 'SOLID', 'width' => 2, 'color' => ['red' => 0.1, 'green' => 0.1, 'blue' => 0.1]],
                                    'bottom' => ['style' => 'SOLID', 'width' => 2, 'color' => ['red' => 0.1, 'green' => 0.1, 'blue' => 0.1]],
                                    'left' => ['style' => 'SOLID', 'color' => ['red' => 0.1, 'green' => 0.1, 'blue' => 0.1]],
                                    'right' => ['style' => 'SOLID', 'color' => ['red' => 0.1, 'green' => 0.1, 'blue' => 0.1]],
                                ],
                            ],
                        ],
                        'fields' => 'userEnteredFormat(backgroundColor,textFormat,horizontalAlignment,verticalAlignment,borders)',
                    ],
                ]);

                // 2. Add Data Validation for Status
                $statusIndex = array_search('status', $syncedFields);
                if ($statusIndex !== false) {
                    $requests[] = new Request([
                        'setDataValidation' => [
                            'range' => [
                                'sheetId' => $this->sheetId,
                                'startRowIndex' => 1,
                                'endRowIndex' => 1000,
                                'startColumnIndex' => $statusIndex,
                                'endColumnIndex' => $statusIndex + 1,
                            ],
                            'rule' => [
                                'condition' => [
                                    'type' => 'ONE_OF_LIST',
                                    'values' => array_values(array_map(function ($status) {
                                        return ['userEnteredValue' => $status];
                                    }, array_unique(SourcingOrder::STATUSES))),
                                ],
                                'showCustomUi' => true,
                                'strict' => false,
                            ],
                        ],
                    ]);

                    // Add Conditional Formatting for Status Colors
                    $statusColors = [
                        // Yellow/Orange (Pending/Waiting)
                        'pending_payment' => ['red' => 1, 'green' => 0.9, 'blue' => 0.6],
                        'waiting_for_refund' => ['red' => 1, 'green' => 0.9, 'blue' => 0.6],
                        'shipment_delayed' => ['red' => 1, 'green' => 0.8, 'blue' => 0.4],

                        // Blue/Cyan (Processing/Transit)
                        'paid' => ['red' => 0.8, 'green' => 0.9, 'blue' => 1],
                        'shipment_preparing' => ['red' => 0.8, 'green' => 0.9, 'blue' => 1],
                        'in_transit_china' => ['red' => 0.7, 'green' => 0.95, 'blue' => 1],
                        'arrival_uae' => ['red' => 0.7, 'green' => 0.95, 'blue' => 1],
                        'customs_clearance_uae' => ['red' => 0.7, 'green' => 0.95, 'blue' => 1],
                        'in_transit_uae' => ['red' => 0.7, 'green' => 0.95, 'blue' => 1],
                        'arrival_destination_country' => ['red' => 0.7, 'green' => 0.95, 'blue' => 1],
                        'customs_clearance_destination_country' => ['red' => 0.7, 'green' => 0.95, 'blue' => 1],
                        'out_for_delivery' => ['red' => 0.6, 'green' => 0.9, 'blue' => 1],

                        // Green (Success/Completed)
                        'delivered' => ['red' => 0.7, 'green' => 1, 'blue' => 0.7],
                        'order_completed' => ['red' => 0.6, 'green' => 0.9, 'blue' => 0.6],
                        'refund_approved' => ['red' => 0.7, 'green' => 1, 'blue' => 0.7],

                        // Red (Failed/Rejected/Canceled)
                        'shipment_canceled' => ['red' => 1, 'green' => 0.7, 'blue' => 0.7],
                        'delivery_failed' => ['red' => 1, 'green' => 0.7, 'blue' => 0.7],
                        'shipment_returned' => ['red' => 1, 'green' => 0.7, 'blue' => 0.7],
                        'refund_rejected' => ['red' => 1, 'green' => 0.7, 'blue' => 0.7],

                        // Gray (Refunded)
                        'refunded' => ['red' => 0.9, 'green' => 0.9, 'blue' => 0.9],
                    ];

                    foreach ($statusColors as $status => $color) {
                        $requests[] = new Request([
                            'addConditionalFormatRule' => [
                                'rule' => [
                                    'ranges' => [[
                                        'sheetId' => $this->sheetId,
                                        'startRowIndex' => 1,
                                        'endRowIndex' => 1000,
                                        'startColumnIndex' => $statusIndex,
                                        'endColumnIndex' => $statusIndex + 1,
                                    ]],
                                    'booleanRule' => [
                                        'condition' => [
                                            'type' => 'TEXT_EQ',
                                            'values' => [['userEnteredValue' => $status]],
                                        ],
                                        'format' => [
                                            'backgroundColor' => $color,
                                            'textFormat' => ['foregroundColor' => ['red' => 0, 'green' => 0, 'blue' => 0]],
                                        ],
                                    ],
                                ],
                                'index' => 0, // Add to top priority
                            ],
                        ]);
                    }
                }

                // 3. Freeze first row
                $requests[] = new Request([
                    'updateSheetProperties' => [
                        'properties' => [
                            'sheetId' => $this->sheetId,
                            'gridProperties' => [
                                'frozenRowCount' => 1,
                            ],
                        ],
                        'fields' => 'gridProperties.frozenRowCount',
                    ],
                ]);

                // 4. Auto-resize columns
                $requests[] = new Request([
                    'autoResizeDimensions' => [
                        'dimensions' => [
                            'sheetId' => $this->sheetId,
                            'dimension' => 'COLUMNS',
                            'startIndex' => 0,
                            'endIndex' => count($headers),
                        ],
                    ],
                ]);

                $batchUpdateRequest = new BatchUpdateSpreadsheetRequest(['requests' => $requests]);
                $this->sheetsService->spreadsheets->batchUpdate($this->spreadsheetId, $batchUpdateRequest);

                Log::info('Google Sheet headers installed and formatted professionally.', [
                    'spreadsheetId' => $this->spreadsheetId,
                    'sheetName' => $this->sheetName,
                ]);

                return [
                    'success' => true,
                    'message' => __('Headers installed and styled successfully.'),
                ];
            }

            return [
                'success' => true,
                'message' => __('Headers already exist. No changes were made.'),
            ];

        } catch (\Exception $e) {
            Log::error('Failed to ensure and style Google Sheet headers: '.$e->getMessage());

            return [
                'success' => false,
                'message' => __('Failed to ensure and style Google Sheet headers: :error', ['error' => $e->getMessage()]),
            ];
        }
    }

    const AVAILABLE_FIELDS = [
        'id' => 'Order ID',
        'created_at' => 'Date Création',
        'status' => 'Statut',
        'client_email' => 'Email Client',
        'product_name' => 'Nom Produit',
        'quantity' => 'Quantité',
        'total_amount' => 'Montant Total',
        'currency' => 'Devise',
        'shipping_method' => 'Méthode Livraison',
        'tracking_number' => 'Numéro Suivi',
        'carrier' => 'Transporteur',
        'product_image' => 'Image Produit',
    ];

    /**
     * Headers for Shipping Company Sheet – same as Lark for consistency.
     */
    const SHIPPING_SHEET_HEADERS = [
        'product_image' => 'PICTURE',
        'created_at' => 'SELLING DATE',
        'product_name' => 'PRODUCT NAME',
        'quantity' => 'QUANTITY',
        'product_price' => 'PRODUCT PRICE',
        'total_price' => 'TOTAL PRICE',
        'tracking_number' => 'TRACKING NUMBER FROM CHINA',
        'address' => 'Shipping address',
        'shipping_label' => 'LABEL SHIPPING',
    ];

    /**
     * Upsert multiple rows in a batch (optimized for performance).
     *
     * @param  array  $allOrdersData  Array of order data arrays (associative per row)
     * @param  array|null  $syncedFields  When provided (e.g. shipping company sheet), use this column order
     * @return array statistics on operations
     */
    public function batchUpsertRows(array $allOrdersData, ?array $syncedFields = null)
    {
        if (empty($allOrdersData)) {
            return ['updated' => 0, 'appended' => 0];
        }

        try {
            if ($syncedFields === null) {
                $setting = GoogleSheetSetting::first();
                $syncedFields = $setting->synced_fields ?? array_keys(self::AVAILABLE_FIELDS);
            }

            // 1. Fetch existing data to determine which rows to update vs append
            // We assume column A (or the ID column) is used to identifying rows.

            $idIndex = array_search('id', $syncedFields);
            $canUpdate = ($idIndex !== false);

            $existingIdsMap = []; // Order ID => Row Number
            $nextRowIndex = 2; // Start after header (row 1)

            if ($canUpdate) {
                // Fetch valid range of IDs
                $idColumn = self::indexToLetter($idIndex + 1);
                $range = $this->sheetName."!{$idColumn}:{$idColumn}";
                $response = $this->sheetsService->spreadsheets_values->get($this->spreadsheetId, $range);
                $values = $response->getValues();

                if (! empty($values)) {
                    foreach ($values as $index => $row) {
                        // Skip header or empty rows
                        if ($index === 0) {
                            continue;
                        }

                        if (isset($row[0]) && ! empty($row[0])) {
                            $existingIdsMap[(string) $row[0]] = $index + 1;
                        }
                    }
                    $nextRowIndex = count($values) + 1;
                }
            } else {
                // Even if we can't update, we need to know where to start appending
                $sheet = $this->sheetsService->spreadsheets->get($this->spreadsheetId);
                // Fallback method to get last row if we can't query by ID column
                // ... simplified for now, assuming append works automatically
            }

            // 2. Prepare ValueRanges for BatchUpdate
            $dataToUpdate = []; // range => values
            $dataToAppend = []; // List of rows

            foreach ($allOrdersData as $orderData) {
                $rowValues = [];
                // Build row based on synced fields
                foreach ($syncedFields as $field) {
                    $rowValues[] = $orderData[$field] ?? '';
                }

                $orderId = (string) ($orderData['id'] ?? '');

                if ($canUpdate && isset($existingIdsMap[$orderId])) {
                    // Update existing row
                    $rowIndex = $existingIdsMap[$orderId];
                    $range = $this->sheetName."!A{$rowIndex}";
                    $dataToUpdate[] = [
                        'range' => $range,
                        'values' => [$rowValues],
                    ];
                } else {
                    // Append new row
                    $dataToAppend[] = $rowValues;
                }
            }

            // 3. Execute Updates (Batch)
            if (! empty($dataToUpdate)) {
                $data = [];
                foreach ($dataToUpdate as $item) {
                    $data[] = new ValueRange([
                        'range' => $item['range'],
                        'values' => $item['values'],
                    ]);
                }

                $batchUpdateBody = new \Google\Service\Sheets\BatchUpdateValuesRequest([
                    'data' => $data,
                    'valueInputOption' => 'USER_ENTERED',
                ]);

                $this->sheetsService->spreadsheets_values->batchUpdate($this->spreadsheetId, $batchUpdateBody);
            }

            if (! empty($dataToAppend)) {
                $range = $this->sheetName.'!A:ZZ';
                $body = new ValueRange(['values' => $dataToAppend]);
                $params = ['valueInputOption' => 'USER_ENTERED'];

                $this->sheetsService->spreadsheets_values->append(
                    $this->spreadsheetId,
                    $range,
                    $body,
                    $params
                );

                // Note: We might want to style these new rows here, but for batch performance
                // we might skip individual row styling or do it in a huge batch if really needed.
                // For now, let's skip complex styling on batch sync to ensure speed.
            }

            return [
                'updated' => count($dataToUpdate),
                'appended' => count($dataToAppend),
            ];

        } catch (\Exception $e) {
            Log::error('Batch upsert failed: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Upsert a row in the Google Sheet (Update if exists, Append if not).
     *
     * @param  array  $data  Associative array of field => value
     * @param  array|null  $syncedFields  When provided (e.g. for shipping company sheet), use this column order instead of GoogleSheetSetting
     */
    public function upsertRow(array $data, int $sheetDisplayId, ?int $internalId = null, ?array $syncedFields = null)
    {
        try {
            if ($syncedFields === null) {
                $setting = GoogleSheetSetting::first();
                $syncedFields = $setting->synced_fields ?? array_keys(self::AVAILABLE_FIELDS);
            }

            // Find index of 'id' field to match Row in Sheet
            $idIndex = array_search('id', $syncedFields);
            if ($idIndex === false) {
                // If ID is not synced, we can't find the row to update, so just append
                return $this->appendRow($data, $internalId, $syncedFields);
            }

            // Convert idIndex to column letter
            $idColumn = self::indexToLetter($idIndex + 1);

            // Fetch ID column to find the row
            $range = $this->sheetName."!{$idColumn}:{$idColumn}";
            $response = $this->sheetsService->spreadsheets_values->get($this->spreadsheetId, $range);
            $values = $response->getValues();

            $rowIndex = -1;
            if (! empty($values)) {
                foreach ($values as $index => $row) {
                    // Match based on Display ID (e.g. 55)
                    if (isset($row[0]) && (string) $row[0] === (string) $sheetDisplayId) {
                        $rowIndex = $index + 1; // 1-based index
                        break;
                    }
                }
            }

            if ($rowIndex !== -1) {
                // UPDATE EXISTING ROW
                $finalData = [];
                foreach ($syncedFields as $field) {
                    $finalData[] = $data[$field] ?? '';
                }

                $updateRange = $this->sheetName."!A{$rowIndex}:".self::indexToLetter(count($finalData)).$rowIndex;
                $body = new ValueRange(['values' => [$finalData]]);
                $params = ['valueInputOption' => 'USER_ENTERED'];

                $this->sheetsService->spreadsheets_values->update(
                    $this->spreadsheetId,
                    $updateRange,
                    $body,
                    $params
                );

                Log::info("Order #{$sheetDisplayId} (Internal #{$internalId}) updated in Google Sheet at row {$rowIndex}.");
                if ($internalId) {
                    GoogleSheetSyncLog::logSuccess($internalId, $data);
                }

                return true;
            } else {
                // APPEND NEW ROW
                return $this->appendRow($data, $internalId, $syncedFields);
            }

        } catch (\Exception $e) {
            Log::error("Failed to upsert row for Order Display #{$sheetDisplayId}: ".$e->getMessage());
            throw $e;
        }
    }

    public function appendRow($data, ?int $orderId = null, ?array $syncedFields = null)
    {
        try {
            if ($syncedFields === null) {
                $setting = GoogleSheetSetting::first();
                $syncedFields = $setting->synced_fields ?? [];
            }

            // Helper to get selected fields data
            // If dynamic data mapping is needed, the caller should pass an associative array

            $finalData = [];

            if (count(array_filter(array_keys($data), 'is_string')) > 0) {
                // Associative array passed

                // If synced_fields is empty/null (legacy or not set), default to ALL available fields
                if (empty($syncedFields)) {
                    $syncedFields = array_keys(self::AVAILABLE_FIELDS);
                }

                foreach ($syncedFields as $field) {
                    $finalData[] = $data[$field] ?? '';
                }
            } else {
                // Indexed array passed - use as is (legacy support for now, or fallback)
                $finalData = $data;
            }

            // Define range for appending (wide range to support any number of columns)
            $range = $this->sheetName.'!A:ZZ';

            $body = new ValueRange(['values' => [$finalData]]);
            $params = ['valueInputOption' => 'USER_ENTERED'];
            $response = $this->sheetsService->spreadsheets_values->append(
                $this->spreadsheetId,
                $range,
                $body,
                $params
            );

            // Get last row index (0-based)
            $sheet = $this->sheetsService->spreadsheets->get($this->spreadsheetId);
            $rowsCount = $sheet->getSheets()[0]->getProperties()->getGridProperties()->getRowCount();
            $rowIndex = $rowsCount - 1;

            // Count columns safely
            $columnsCount = count($finalData);

            // Style the new row
            $requests = [
                new Request([
                    'repeatCell' => [
                        'range' => [
                            'sheetId' => $this->sheetId,
                            'startRowIndex' => $rowIndex,
                            'endRowIndex' => $rowIndex + 1,
                            'startColumnIndex' => 0,
                            'endColumnIndex' => $columnsCount,
                        ],
                        'cell' => [
                            'userEnteredFormat' => [
                                'backgroundColor' => ['red' => 1, 'green' => 0.98, 'blue' => 0.93],
                                'horizontalAlignment' => 'CENTER',
                                'verticalAlignment' => 'MIDDLE',
                                'borders' => [
                                    'top' => ['style' => 'SOLID', 'color' => ['red' => 0, 'green' => 0, 'blue' => 0]],
                                    'bottom' => ['style' => 'SOLID', 'color' => ['red' => 0, 'green' => 0, 'blue' => 0]],
                                    'left' => ['style' => 'SOLID', 'color' => ['red' => 0, 'green' => 0, 'blue' => 0]],
                                    'right' => ['style' => 'SOLID', 'color' => ['red' => 0, 'green' => 0, 'blue' => 0]],
                                ],
                            ],
                        ],
                        'fields' => 'userEnteredFormat(backgroundColor,horizontalAlignment,verticalAlignment,borders)',
                    ],
                ]),
            ];

            // Add Data Validation (Dropdown) for Status column if it exists
            $statusIndex = array_search('status', $syncedFields);
            if ($statusIndex !== false) {
                $requests[] = new Request([
                    'setDataValidation' => [
                        'range' => [
                            'sheetId' => $this->sheetId,
                            'startRowIndex' => $rowIndex,
                            'endRowIndex' => $rowIndex + 1,
                            'startColumnIndex' => $statusIndex,
                            'endColumnIndex' => $statusIndex + 1,
                        ],
                        'rule' => [
                            'condition' => [
                                'type' => 'ONE_OF_LIST',
                                'values' => array_values(array_map(function ($status) {
                                    return ['userEnteredValue' => $status];
                                }, array_unique(SourcingOrder::STATUSES))),
                            ],
                            'showCustomUi' => true,
                            'strict' => false, // Set to false to allow manual entry if needed, but show dropdown
                        ],
                    ],
                ]);
            }

            $batchUpdateRequest = new BatchUpdateSpreadsheetRequest(['requests' => $requests]);
            $this->sheetsService->spreadsheets->batchUpdate($this->spreadsheetId, $batchUpdateRequest);

            Log::info('Row successfully appended and styled to Google Sheet.', ['data' => $data]);

            // Log successful sync
            if ($orderId) {
                GoogleSheetSyncLog::logSuccess($orderId, $data);
            }

            return $response;

        } catch (\Google\Service\Exception $e) {
            $errorMessage = $e->getMessage();
            $errorCode = (string) $e->getCode();

            Log::error('Failed to append and style row to Google Sheet: '.$errorMessage, ['data' => $data]);

            // Log failed sync
            if ($orderId) {
                GoogleSheetSyncLog::logError($orderId, $errorMessage, $errorCode, $data);
            }

            throw new \Exception('Failed to append and style row to Google Sheet: '.$errorMessage);
        } catch (\Exception $e) {
            Log::error('Failed to append and style row to Google Sheet: '.$e->getMessage(), ['data' => $data]);

            // Log failed sync
            if ($orderId) {
                GoogleSheetSyncLog::logError($orderId, $e->getMessage(), null, $data);
            }

            throw new \Exception('Failed to append and style row to Google Sheet: '.$e->getMessage());
        }
    }

    /**
     * Internal logic: Update order status in Google Sheet.
     */
    protected function executeUpdateOrderStatus(int $orderId, string $newStatus)
    {
        try {
            $setting = GoogleSheetSetting::first();
            $syncedFields = $setting->synced_fields ?? [];

            // Find index of 'id' and 'status' fields
            $idIndex = array_search('id', $syncedFields);
            $statusIndex = array_search('status', $syncedFields);

            if ($idIndex === false) {
                Log::warning("Cannot update status: 'id' field is not synced to Google Sheet.");

                return false;
            }

            if ($statusIndex === false) {
                Log::warning("Cannot update status: 'status' field is not synced to Google Sheet.");

                return false;
            }

            // Convert indices to column letters
            $idColumn = self::indexToLetter($idIndex + 1);
            $statusColumn = self::indexToLetter($statusIndex + 1);

            // Fetch ID column to find the row
            $range = $this->sheetName."!{$idColumn}:{$idColumn}";
            $response = $this->sheetsService->spreadsheets_values->get($this->spreadsheetId, $range);
            $values = $response->getValues();

            if (empty($values)) {
                Log::info("Google Sheet is empty, cannot update order #{$orderId}.");

                return false;
            }

            // Find row index (1-based for A1 notation)
            $rowIndex = -1;
            foreach ($values as $index => $row) {
                if (isset($row[0]) && (string) $row[0] === (string) $orderId) {
                    $rowIndex = $index + 1; // 1-based index
                    break;
                }
            }

            if ($rowIndex === -1) {
                Log::info("Order #{$orderId} not found in Google Sheet.");

                return false;
            }

            // Update status cell
            $cellRange = $this->sheetName."!{$statusColumn}{$rowIndex}";
            $body = new ValueRange(['values' => [[$newStatus]]]);
            $params = ['valueInputOption' => 'RAW'];

            $this->sheetsService->spreadsheets_values->update(
                $this->spreadsheetId,
                $cellRange,
                $body,
                $params
            );

            Log::info("Updated status for Order #{$orderId} in Google Sheet to: {$newStatus} at row {$rowIndex}");

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to update status in Google Sheet: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Test the connection to Google Sheets API.
     * Returns an array with 'success', 'message', and optionally 'details'.
     */
    public static function staticTestConnection(?string $spreadsheetId = null): array
    {
        try {
            if ($spreadsheetId === null) {
                $setting = GoogleSheetSetting::first();
                // Check if settings exist
                if (! $setting || ! $setting->sheet_id) {
                    return [
                        'success' => false,
                        'message' => 'Les paramètres Google Sheet ne sont pas configurés dans la base de données.',
                        'details' => null,
                    ];
                }
                $spreadsheetId = $setting->sheet_id;
            }

            // Check if credentials file exists
            $credentialsPath = storage_path(config('services.google.credentials_path'));
            if (! file_exists($credentialsPath)) {
                return [
                    'success' => false,
                    'message' => 'Le fichier d\'identifiants Google API est introuvable.',
                    'details' => ['path' => $credentialsPath],
                ];
            }

            // Initialize client
            $client = new Client;
            $client->setApplicationName('Sourcing App Google Sheets Integration');
            $client->setScopes([Sheets::SPREADSHEETS_READONLY]);
            $client->setAccessType('offline');
            $client->setAuthConfig($credentialsPath);

            $sheetsService = new Sheets($client);

            // Try to get spreadsheet info
            $spreadsheet = $sheetsService->spreadsheets->get($spreadsheetId);
            $title = $spreadsheet->getProperties()->getTitle();

            // Get sheet names
            $sheetNames = [];
            foreach ($spreadsheet->getSheets() as $sheet) {
                $sheetNames[] = $sheet->getProperties()->getTitle();
            }

            // Check if the configured sheet name exists (if using settings)
            $setting = GoogleSheetSetting::first();
            $configuredSheetName = $setting ? $setting->sheet_name : 'sourcing';
            $configuredSheetExists = in_array($configuredSheetName, $sheetNames);

            return [
                'success' => true,
                'message' => 'Connexion réussie à Google Sheets !',
                'details' => [
                    'spreadsheet_title' => $title,
                    'spreadsheet_id' => $spreadsheetId,
                    'configured_sheet' => $configuredSheetName,
                    'sheet_exists' => $configuredSheetExists,
                    'available_sheets' => $sheetNames,
                ],
            ];

        } catch (\Google\Service\Exception $e) {
            $errorMessage = 'Erreur API Google: ';
            $errors = json_decode($e->getMessage(), true);
            if (isset($errors['error']['message'])) {
                $errorMessage .= $errors['error']['message'];
            } else {
                $errorMessage .= $e->getMessage();
            }

            return [
                'success' => false,
                'message' => $errorMessage,
                'details' => ['code' => $e->getCode()],
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur de connexion: '.$e->getMessage(),
                'details' => null,
            ];
        }
    }

    /**
     * Helper to convert column index (1-based) to letter (A, B, ..., Z, AA...)
     */
    public static function indexToLetter($number)
    {
        $letter = '';
        while ($number > 0) {
            $temp = ($number - 1) % 26;
            $letter = chr(65 + $temp).$letter;
            $number = (int) (($number - $temp - 1) / 26);
        }

        return $letter;
    }
}
