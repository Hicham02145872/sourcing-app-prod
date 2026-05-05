# ITDIDA Shipping Tracking Integration

## Overview
This document outlines how to integrate with the ITDIDA shipping company tracking system (`ydl.itdida.com`).

## Challenge
The ITDIDA website uses anti-bot protection that prevents direct API calls. The response includes JavaScript challenges that must be solved before accessing the actual tracking data.

## Tracking Data Structure

Based on the XML response analysis, here's the structure of tracking information:

### Response Format
- **Format**: XML (partial-response)
- **Content**: HTML table embedded in CDATA

### Tracking Fields

| Field | Arabic Name | Description | Example |
|-------|-------------|-------------|---------|
| `customer_number` | رقم العميل | Customer/Tracking Number | K0121112B |
| `time` | الوقت | Timestamp | 2025-11-22 23:00:00 |
| `status` | الوصف | Status Description | 已签收 (Signed/Delivered) |
| `location` | الموقع | Location | 客服部 (Customer Service Dept) |
| `pieces` | عدد القطع | Number of Pieces | 1 |
| `receive_info` | تلقي المعلومات | Receiving Information | 迪拜 (Dubai) |
| `transfer_number` | رقم التحويل | Transfer Number | (optional) |

### Status Translations

Common status values (Chinese to English):

| Chinese | English | Arabic |
|---------|---------|--------|
| 已签收 | Delivered/Signed | تم التسليم |
| 运输中 | In Transit | قيد النقل |
| 已发货 | Shipped | تم الشحن |
| 待发货 | Pending Shipment | في انتظار الشحن |

## Integration Solutions

### Option 1: Browser Automation (Recommended)
Use browser automation tools to bypass anti-bot protection:

**Tools:**
- Selenium WebDriver
- Puppeteer (Node.js)
- Laravel Dusk (for Laravel integration)

**Advantages:**
- Handles JavaScript challenges automatically
- More reliable for protected websites
- Can screenshot for debugging

**Implementation:**
```php
// Using Laravel Dusk or Selenium
$browser->visit('https://ydl.itdida.com/query.xhtml?danHao=' . $trackingNumber)
    ->type('searchForm:danHaoInput', $trackingNumber)
    ->press('Search')
    ->waitFor('#resultDataTable')
    ->with('#resultDataTable tbody tr', function ($row) {
        // Extract tracking data
    });
```

### Option 2: Manual Webhook/Scraping Service
Use a third-party service or manual process:

**Options:**
- ScrapingBee
- Bright Data
- Manual entry by staff

### Option 3: Direct Contact with ITDIDA
Request API access from ITDIDA for automated tracking.

## Recommended Implementation for Laravel App

### 1. Create Tracking Service

```php
<?php

namespace App\Services\Shipping;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ItdidaTrackingService
{
    protected string $baseUrl = 'https://ydl.itdida.com';
    
    public function getTrackingInfo(string $trackingNumber): ?array
    {
        try {
            // Note: This will require browser automation
            // For now, return null and log for manual processing
            Log::info('ITDIDA tracking requested', [
                'tracking_number' => $trackingNumber
            ]);
            
            return null;
        } catch (\Exception $e) {
            Log::error('ITDIDA tracking failed', [
                'tracking_number' => $trackingNumber,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }
    
    public function parseStatus(string $chineseStatus): string
    {
        $statusMap = [
            '已签收' => 'delivered',
            '运输中' => 'in_transit',
            '已发货' => 'shipped',
            '待发货' => 'pending_shipment',
        ];
        
        return $statusMap[$chineseStatus] ?? 'unknown';
    }
}
```

### 2. Add to Shipping Company Model

```php
// In ShippingCompany model
public function getItdidaTracking(string $trackingNumber): ?array
{
    $service = new \App\Services\Shipping\ItdidaTrackingService();
    return $service->getTrackingInfo($trackingNumber);
}
```

### 3. Create Artisan Command for Manual Sync

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SourcingOrder;
use App\Services\Shipping\ItdidaTrackingService;

class SyncItdidaTracking extends Command
{
    protected $signature = 'shipping:sync-itdida {tracking_number?}';
    protected $description = 'Sync tracking information from ITDIDA';
    
    public function handle()
    {
        $trackingNumber = $this->argument('tracking_number');
        
        if ($trackingNumber) {
            $this->syncSingle($trackingNumber);
        } else {
            $this->syncAll();
        }
    }
    
    protected function syncSingle(string $trackingNumber)
    {
        $service = new ItdidaTrackingService();
        $info = $service->getTrackingInfo($trackingNumber);
        
        if ($info) {
            $this->info("Tracking info retrieved for: {$trackingNumber}");
            $this->table(
                ['Field', 'Value'],
                collect($info)->map(fn($v, $k) => [$k, $v])
            );
        } else {
            $this->error("Failed to retrieve tracking info for: {$trackingNumber}");
        }
    }
    
    protected function syncAll()
    {
        // Sync all orders with ITDIDA tracking numbers
        $orders = SourcingOrder::whereHas('shippingCompany', function ($q) {
                $q->where('name', 'LIKE', '%ITDIDA%');
            })
            ->whereNotNull('tracking_number')
            ->get();
            
        $this->info("Found {$orders->count()} orders to sync");
        
        foreach ($orders as $order) {
            $this->syncSingle($order->tracking_number);
        }
    }
}
```

## Test Script Usage

The provided `test_itdida_tracking.php` script demonstrates the API structure but will fail due to anti-bot protection.

**To test manually:**

1. Visit: `https://ydl.itdida.com/query.xhtml?danHao=K0121112B`
2. Enter tracking number in the form
3. Inspect the network tab to see the AJAX request
4. Copy the response for analysis

## Next Steps

1. **Decide on integration approach:**
   - Browser automation (most reliable)
   - Third-party scraping service
   - Manual entry
   - Contact ITDIDA for API access

2. **If using browser automation:**
   - Install Laravel Dusk: `composer require laravel/dusk --dev`
   - Create Dusk test for tracking
   - Convert to service class

3. **Update database schema:**
   - Add `tracking_status` field to orders
   - Add `tracking_last_updated` timestamp
   - Add `tracking_history` JSON field for full history

4. **Create admin interface:**
   - Button to manually refresh tracking
   - Display tracking history timeline
   - Automatic status updates

## Example: Expected Data Structure

```json
{
  "success": true,
  "tracking_number": "K0121112B",
  "data": [
    {
      "customer_number": "K0121112B",
      "time": "2025-11-22 23:00:00",
      "status": "已签收",
      "status_en": "delivered",
      "location": "客服部",
      "location_en": "Customer Service Dept",
      "pieces": "1",
      "receive_info": "迪拜",
      "receive_info_en": "Dubai",
      "transfer_number": ""
    }
  ],
  "latest_status": "delivered",
  "latest_location": "Customer Service Dept",
  "latest_time": "2025-11-22 23:00:00"
}
```

## Security Considerations

- Store ITDIDA credentials securely in `.env`
- Rate limit tracking requests
- Cache tracking results (15-30 minutes)
- Log all tracking attempts for debugging
- Handle failures gracefully

## Conclusion

Due to anti-bot protection, direct API integration is not feasible. Browser automation or manual processes are recommended for reliable tracking data retrieval.
