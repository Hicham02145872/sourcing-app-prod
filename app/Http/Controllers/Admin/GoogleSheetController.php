<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoogleSheetSetting;
use App\Services\GoogleSheetService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GoogleSheetController extends Controller
{
    /**
     * Display Google Sheets settings page.
     */
    public function index(): View
    {
        // Only super admins can access
        if (! auth()->user()->isSuperAdmin()) {
            abort(403, 'Only super administrators can manage Google Sheets settings.');
        }

        $setting = GoogleSheetSetting::first();
        $credentialsExist = Storage::exists('app/secure/credentials.json');

        // Try to get connection status
        $connectionStatus = null;
        if ($credentialsExist && $setting) {
            try {
                $connectionStatus = GoogleSheetService::testConnection();
            } catch (\Exception $e) {
                $connectionStatus = [
                    'success' => false,
                    'message' => 'Error testing connection: '.$e->getMessage(),
                ];
            }
        }

        return view('admin.google-sheets.settings', compact('setting', 'credentialsExist', 'connectionStatus'));
    }

    /**
     * Upload and validate Google Service Account credentials.
     */
    public function uploadCredentials(Request $request): RedirectResponse
    {
        if (! auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'credentials_file' => [
                'required',
                'file',
                'mimes:json',
                'max:10240', // 10MB
            ],
        ]);

        try {
            // Get file contents
            $file = $request->file('credentials_file');
            $contents = file_get_contents($file->getRealPath());

            // Validate JSON structure
            $validation = $this->validateCredentialsJson($contents);
            if (! $validation['valid']) {
                return back()->with('error', 'Invalid credentials file: '.$validation['error']);
            }

            // Ensure secure directory exists
            if (! Storage::exists('app/secure')) {
                Storage::makeDirectory('app/secure');
            }

            // Store the file
            $path = storage_path('app/secure/credentials.json');
            file_put_contents($path, $contents);

            // Set restrictive permissions (Unix systems)
            if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
                chmod($path, 0600);
            }

            // Add .htaccess protection
            $htaccessPath = storage_path('app/secure/.htaccess');
            if (! file_exists($htaccessPath)) {
                file_put_contents($htaccessPath, 'Deny from all');
            }

            // Test connection immediately
            try {
                $testResult = GoogleSheetService::testConnection();

                if ($testResult['success']) {
                    return back()->with('success', 'Credentials uploaded and validated successfully! Connection test passed.');
                } else {
                    return back()->with('warning', 'Credentials uploaded but connection test failed: '.$testResult['message']);
                }
            } catch (\Exception $e) {
                return back()->with('warning', 'Credentials uploaded but connection test failed: '.$e->getMessage());
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Error uploading credentials: '.$e->getMessage());
        }
    }

    /**
     * Test Google Sheets API connection.
     */
    public function testConnection(): RedirectResponse
    {
        if (! auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        try {
            $result = GoogleSheetService::testConnection();

            if ($result['success']) {
                $message = 'Connection successful! Connected to: '.($result['details']['spreadsheet_title'] ?? 'Unknown');

                return back()->with('success', $message);
            } else {
                return back()->with('error', 'Connection failed: '.$result['message']);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Connection test error: '.$e->getMessage());
        }
    }

    /**
     * Install headers in the Google Sheet.
     */
    public function installHeaders(): RedirectResponse
    {
        if (! auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        try {
            $result = GoogleSheetService::installHeaders();

            if ($result['success']) {
                return back()->with('success', $result['message']);
            } else {
                return back()->with('error', $result['message']);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error installing headers: '.$e->getMessage());
        }
    }

    /**
     * Update Google Sheets settings.
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        if (! auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'sheet_id' => 'required|string|max:255',
            'sheet_name' => 'required|string|max:255',
            'synced_fields' => 'nullable|array',
            'synced_fields.*' => 'string|in:id,created_at,status,client_name,client_email,product_name,quantity,total_amount,currency,shipping_method,tracking_number,admin_assigned',
        ]);

        $setting = GoogleSheetSetting::first();

        // Ensure ID is always included even if not sent
        if (isset($validated['synced_fields']) && ! in_array('id', $validated['synced_fields'])) {
            $validated['synced_fields'][] = 'id';
        }

        // Default if checked empty (should contain at least ID)
        if (! isset($validated['synced_fields'])) {
            $validated['synced_fields'] = ['id'];
        }

        if ($setting) {
            $setting->update($validated);
            $message = 'Settings updated successfully!';
        } else {
            GoogleSheetSetting::create($validated);
            $message = 'Settings created successfully!';
        }

        return back()->with('success', $message);
    }

    /**
     * Validate Google Service Account JSON structure.
     */
    private function validateCredentialsJson(string $contents): array
    {
        // Parse JSON
        $data = json_decode($contents, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'valid' => false,
                'error' => 'Invalid JSON format: '.json_last_error_msg(),
            ];
        }

        // Check required fields
        $required = ['type', 'project_id', 'private_key_id', 'private_key', 'client_email'];
        foreach ($required as $field) {
            if (! isset($data[$field]) || empty($data[$field])) {
                return [
                    'valid' => false,
                    'error' => "Missing required field: {$field}",
                ];
            }
        }

        // Verify type
        if ($data['type'] !== 'service_account') {
            return [
                'valid' => false,
                'error' => 'Invalid type. Must be "service_account", got: '.$data['type'],
            ];
        }

        // Validate email format
        if (! filter_var($data['client_email'], FILTER_VALIDATE_EMAIL)) {
            return [
                'valid' => false,
                'error' => 'Invalid client_email format',
            ];
        }

        // Check private key format
        if (! str_contains($data['private_key'], 'BEGIN PRIVATE KEY')) {
            return [
                'valid' => false,
                'error' => 'Invalid private_key format',
            ];
        }

        return [
            'valid' => true,
            'data' => $data,
        ];
    }

    /**
     * Display Google Sheets sync logs.
     */
    public function logs(): View
    {
        if (! auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $stats = \App\Models\GoogleSheetSyncLog::getStats();
        $logs = \App\Models\GoogleSheetSyncLog::with('sourcingOrder')->latest()->paginate(20);

        return view('admin.google-sheets.logs', compact('logs', 'stats'));
    }
}
