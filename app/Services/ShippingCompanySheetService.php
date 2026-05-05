<?php

namespace App\Services;

use App\Models\ShippingCompany;
use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\BatchUpdateSpreadsheetRequest;
use Google\Service\Sheets\Request;
use Google\Service\Sheets\ValueRange;
use Illuminate\Support\Facades\Log;

class ShippingCompanySheetService
{
    protected $client;

    protected $sheetsService;

    protected $company;

    // Configuration constants
    const IMAGE_COLUMN_INDEX = 9; // J column (0-indexed)

    const ROW_HEIGHT = 100;

    const IMAGE_COLUMN_WIDTH = 100;

    public function __construct(ShippingCompany $company)
    {
        $this->company = $company;

        if (! $this->company->google_sheet_id) {
            throw new \Exception('Shipping company does not have a Google Sheet ID configured.');
        }

        $this->client = new Client;
        $this->client->setApplicationName('Sourcing App - Shipping Company Integration');
        $this->client->setScopes([Sheets::SPREADSHEETS]);
        $this->client->setAccessType('offline');

        $credentialsPath = storage_path(config('services.google.credentials_path'));
        if (! file_exists($credentialsPath)) {
            throw new \Exception('Google Sheets API credentials file not found.');
        }
        $this->client->setAuthConfig($credentialsPath);

        $this->sheetsService = new Sheets($this->client);
    }

    public function ensureHeaders(): array
    {
        try {
            $sheetName = $this->company->sheet_name ?? 'Sheet1';

            // Check if headers exist
            $range = $sheetName.'!A1:Z1';
            $response = $this->sheetsService->spreadsheets_values->get($this->company->google_sheet_id, $range);
            $values = $response->getValues();

            if (empty($values)) {
                $headers = array_values(self::AVAILABLE_FIELDS);

                // Write headers
                $body = new ValueRange(['values' => [$headers]]);
                $params = ['valueInputOption' => 'RAW'];
                $this->sheetsService->spreadsheets_values->update(
                    $this->company->google_sheet_id,
                    $range,
                    $body,
                    $params
                );

                // Style headers
                $sheetId = $this->getSheetIdByName($sheetName);
                if ($sheetId !== null) {
                    $this->styleHeaders($sheetId, count($headers));
                }

                return ['success' => true, 'message' => 'Headers installed successfully.'];
            }

            return ['success' => true, 'message' => 'Headers already exist.'];

        } catch (\Exception $e) {
            Log::error('Failed to ensure headers for shipping company: '.$e->getMessage());

            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    protected function styleHeaders($sheetId, $columnCount)
    {
        $requests = [
            new Request([
                'repeatCell' => [
                    'range' => [
                        'sheetId' => $sheetId,
                        'startRowIndex' => 0,
                        'endRowIndex' => 1,
                        'startColumnIndex' => 0,
                        'endColumnIndex' => $columnCount,
                    ],
                    'cell' => [
                        'userEnteredFormat' => [
                            'backgroundColor' => ['red' => 0.2, 'green' => 0.2, 'blue' => 0.2],
                            'horizontalAlignment' => 'CENTER',
                            'textFormat' => [
                                'bold' => true,
                                'foregroundColor' => ['red' => 1, 'green' => 1, 'blue' => 1],
                            ],
                        ],
                    ],
                    'fields' => 'userEnteredFormat(backgroundColor,textFormat,horizontalAlignment)',
                ],
            ]),
            new Request([
                'updateSheetProperties' => [
                    'properties' => [
                        'sheetId' => $sheetId,
                        'gridProperties' => ['frozenRowCount' => 1],
                    ],
                    'fields' => 'gridProperties.frozenRowCount',
                ],
            ]),
        ];

        $batchUpdateRequest = new BatchUpdateSpreadsheetRequest(['requests' => $requests]);
        $this->sheetsService->spreadsheets->batchUpdate($this->company->google_sheet_id, $batchUpdateRequest);
    }

    public function upsertRow(\App\DTOs\ShippingSheetRowDTO $dto)
    {
        $sheetName = $this->company->sheet_name ?? 'Sheet1';
        $syncedFields = array_keys(self::AVAILABLE_FIELDS);

        // Find ID column index
        $idIndex = array_search('id', $syncedFields);
        $idColumn = $this->indexToLetter($idIndex + 1);

        // Fetch existing IDs
        $range = $sheetName."!{$idColumn}:{$idColumn}";
        try {
            $response = $this->sheetsService->spreadsheets_values->get($this->company->google_sheet_id, $range);
            $values = $response->getValues();
        } catch (\Google\Service\Exception $e) {
            throw new \Exception('Google API Error reading sheet: '.$e->getMessage());
        }

        $rowIndex = -1;
        if (! empty($values)) {
            foreach ($values as $index => $row) {
                if (isset($row[0]) && trim((string) $row[0]) === trim((string) $dto->id)) {
                    $rowIndex = $index + 1;
                    break;
                }
            }
        }

        $rowValues = $dto->toArray();

        // Prepare requests for batch update or single update
        $requests = [];

        if ($rowIndex !== -1) {
            // Update
            $updateRange = $sheetName."!A{$rowIndex}";
            $body = new ValueRange(['values' => [$rowValues]]);
            $this->sheetsService->spreadsheets_values->update(
                $this->company->google_sheet_id,
                $updateRange,
                $body,
                ['valueInputOption' => 'USER_ENTERED']
            );
        } else {
            // Append
            $appendRange = $sheetName.'!A:ZZ';
            $body = new ValueRange(['values' => [$rowValues]]);
            $response = $this->sheetsService->spreadsheets_values->append(
                $this->company->google_sheet_id,
                $appendRange,
                $body,
                ['valueInputOption' => 'USER_ENTERED']
            );

            // Extract new row index from update range like "Sheet1!A10:Z10"
            $updatedRange = $response->getUpdates()->getUpdatedRange();
            if (preg_match('/!A(\d+):/', $updatedRange, $matches)) {
                $rowIndex = intval($matches[1]);
            }
        }

        if ($rowIndex !== -1) {
            $this->applyRowFormatting($rowIndex, $dto->isCanceled);
        }
    }

    protected function applyRowFormatting($rowIndex, $isCanceled)
    {
        $sheetName = $this->company->sheet_name ?? 'Sheet1';
        $sheetId = $this->getSheetIdByName($sheetName);

        if ($sheetId === null) {
            return;
        }

        $requests = [];

        // 1. Resize Row (to show image bigger)
        $requests[] = new Request([
            'updateDimensionProperties' => [
                'range' => [
                    'sheetId' => $sheetId,
                    'dimension' => 'ROWS',
                    'startIndex' => $rowIndex - 1,
                    'endIndex' => $rowIndex,
                ],
                'properties' => [
                    'pixelSize' => self::ROW_HEIGHT,
                ],
                'fields' => 'pixelSize',
            ],
        ]);

        // 2. Resize Image Column (One time check usually, but ensuring it here doesn't hurt or we can move it)
        // Optimally we only do this once during header setup, but let's leave it for now to ensure robustness

        // 3. Apply Cancellation Style (Strikethrough + Red Background) or Reset
        $backgroundColor = $isCanceled
            ? ['red' => 1.0, 'green' => 0.8, 'blue' => 0.8] // Light Red
            : ['red' => 1, 'green' => 1, 'blue' => 1]; // White

        $textDecoration = $isCanceled ? true : false;

        $requests[] = new Request([
            'repeatCell' => [
                'range' => [
                    'sheetId' => $sheetId,
                    'startRowIndex' => $rowIndex - 1,
                    'endRowIndex' => $rowIndex,
                    'startColumnIndex' => 0,
                    'endColumnIndex' => count(self::AVAILABLE_FIELDS),
                ],
                'cell' => [
                    'userEnteredFormat' => [
                        'backgroundColor' => $backgroundColor,
                        'textFormat' => [
                            'strikethrough' => $textDecoration,
                        ],
                    ],
                ],
                'fields' => 'userEnteredFormat(backgroundColor,textFormat.strikethrough)',
            ],
        ]);

        $batchUpdateRequest = new BatchUpdateSpreadsheetRequest(['requests' => $requests]);
        $this->sheetsService->spreadsheets->batchUpdate($this->company->google_sheet_id, $batchUpdateRequest);
    }

    protected function getSheetIdByName($sheetName)
    {
        $spreadsheet = $this->sheetsService->spreadsheets->get($this->company->google_sheet_id);
        foreach ($spreadsheet->getSheets() as $sheet) {
            if ($sheet->getProperties()->getTitle() === $sheetName) {
                return $sheet->getProperties()->getSheetId();
            }
        }

        return null;
    }

    protected function indexToLetter($index)
    {
        $letter = '';
        while ($index > 0) {
            $temp = ($index - 1) % 26;
            $letter = chr(65 + $temp).$letter;
            $index = ($index - $temp - 1) / 26;
        }

        return $letter;
    }

    const AVAILABLE_FIELDS = [
        'id' => 'Order ID',
        'created_at' => 'Date',
        'status' => 'Statut',
        'product_name' => 'Produit',
        'quantity' => 'Quantité',
        'tracking_number' => 'Tracking',
        'client_name' => 'Client',
        'address' => 'Adresse',
        'phone' => 'Téléphone',
        'product_image' => 'Photo',
        'weight' => 'Poids (kg)', // Placeholder for future
        'notes' => 'Notes',
    ];
}
