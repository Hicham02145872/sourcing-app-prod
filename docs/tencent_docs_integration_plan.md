# Tencent Docs Integration Plan

This plan outlines the steps required to replace the existing Google Sheets integration with **Tencent Docs (docs.qq.com)**. When a client uploads proof of payment, the order data will be synchronized to a specific Tencent Docs sheet.

## User Review Required

> [!IMPORTANT]
> **Tencent Docs Open API V3** requires an **App ID** and **App Secret**, which must be obtained from the [Tencent Docs Open Platform](https://docs.qq.com/open/).
> The integration also requires implementing an **OAuth 2.0** flow to obtain an `Access-Token` and `Open-Id`.

## Proposed Changes

### 1. Configuration & Credentials

We need to store the Tencent Docs credentials. For simplicity and security, it is recommended to use environment variables.

#### [NEW] [tencent-docs.php](file:///c:/xampp/htdocs/sourcing-app/config/tencent-docs.php)
```php
<?php
return [
    'app_id' => env('TENCENT_DOCS_APP_ID'),
    'app_secret' => env('TENCENT_DOCS_APP_SECRET'),
    'file_id' => env('TENCENT_DOCS_FILE_ID', 'DTUdWZkRCR3RydFhi'),
    'sheet_id' => env('TENCENT_DOCS_SHEET_ID', 'BB08J2'),
];
```

### 2. Service Layer Implementation

Create a dedicated service to handle the Tencent Docs Open API.

#### [NEW] [TencentDocsService.php](file:///c:/xampp/htdocs/sourcing-app/app/Services/TencentDocsService.php)
```php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TencentDocsService
{
    protected string $fileId;
    protected string $sheetId;

    public function __construct()
    {
        $this->fileId = config('tencent-docs.file_id');
        $this->sheetId = config('tencent-docs.sheet_id');
    }

    /**
     * Appends a row by first finding the row count and then updating the range.
     */
    public function appendOrderRow(array $orderData): bool
    {
        $token = $this->getAccessToken();
        $rowCount = $this->getCurrentRowCount($token);

        return $this->updateRange($token, $rowCount, $orderData);
    }

    protected function getAccessToken(): string { /* OAuth2 logic */ }
    protected function getCurrentRowCount(string $token): int { /* GET /sheets logic */ }
    protected function updateRange(string $token, int $startRow, array $data): bool { /* POST /batchUpdate logic */ }
}
```

### 3. Triggering the Sync

Modify the existing listener or job that handles Google Sheets sync.

#### [MODIFY] [SyncOrderToGoogleSheet.php](file:///c:/xampp/htdocs/sourcing-app/app/Listeners/SyncOrderToGoogleSheet.php)
Replace the `GoogleSheetService` call with `TencentDocsService`.

```php
// Before
// $this->googleSheetService->upsertRow($data, $order->id);

// After
$this->tencentDocsService->appendOrderRow($data);
```

## API Usage Details

### Finding the Append Point
Since Tencent Docs V3 lacks a direct "append" endpoint, we must query the sheet metadata first:
**Endpoint:** `GET https://docs.qq.com/openapi/spreadsheet/v3/files/{fileId}/sheets`
**Logic:** Use the `rowCount` from the response to determine the `startRow` for the next write operation.

### Writing Data (Batch Update)
**Endpoint:** `POST https://docs.qq.com/openapi/spreadsheet/v3/files/{fileId}/batchUpdate`
**Payload Example:**
```json
{
  "requests": [
    {
      "updateRangeRequest": {
        "sheetId": "BB08J2",
        "gridData": {
          "startRow": [LAST_ROW_INDEX],
          "startColumn": 0,
          "rows": [
            {
              "values": [
                { "cellValue": { "text": "Order ID" } },
                { "cellValue": { "text": "Customer Name" } },
                { "cellValue": { "text": "Amount" } }
              ]
            }
          ]
        }
      }
    }
  ]
}
```

## Verification Plan

### Automated Tests
- **Unit Test:** `tests/Unit/Services/TencentDocsServiceTest.php` to mock API responses and verify payload construction.
- **Feature Test:** Mock the `TencentDocsService` in an order update test to ensure the service is called when payment proof is uploaded.

### Manual Verification
1.  **Stage 1:** Configure `TENCENT_DOCS_APP_ID` and `TENCENT_DOCS_APP_SECRET` in `.env`.
2.  **Stage 2:** Upload a proof of payment for a test order.
3.  **Stage 3:** Verify that the order data appears in the [Tencent Docs Sheet](https://docs.qq.com/sheet/DTUdWZkRCR3RydFhi?nlc=1&tab=BB08J2).
