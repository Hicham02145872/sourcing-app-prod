<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoogleSheetSetting;
use App\Models\GoogleSheetSyncLog;
use App\Services\GoogleSheetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GoogleSheetSettingsController extends Controller
{
    public function index()
    {
        $setting = GoogleSheetSetting::first();
        $syncStats = GoogleSheetSyncLog::getStats();

        return view('admin.google-sheet-settings.index', compact('setting', 'syncStats'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'sheet_id' => 'nullable|string',
            'sheet_name' => 'nullable|string',
        ]);

        $setting = GoogleSheetSetting::first();
        $setting->update($request->only('sheet_id', 'sheet_name'));

        return redirect()->back()->with('success', 'Google Sheet settings updated successfully.');
    }

    /**
     * Test the connection to Google Sheets API.
     */
    public function testConnection(): JsonResponse
    {
        $result = GoogleSheetService::testConnection();

        return response()->json($result);
    }

    /**
     * Clear all sync logs.
     */
    public function clearLogs(): JsonResponse
    {
        GoogleSheetSyncLog::truncate();

        return response()->json([
            'success' => true,
            'message' => 'Logs effacés avec succès.',
        ]);
    }

    /**
     * Install headers in Google Sheet with formatting.
     */
    public function installHeaders(): JsonResponse
    {
        try {
            $service = new GoogleSheetService;
            $service->ensureHeaders();

            return response()->json([
                'success' => true,
                'message' => 'En-têtes installés et formatés avec succès !',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: '.$e->getMessage(),
            ]);
        }
    }

    /**
     * Create the configured sheet (tab) if it doesn't exist.
     */
    public function createSheet(): JsonResponse
    {
        try {
            $setting = GoogleSheetSetting::first();
            if (! $setting || ! $setting->sheet_name) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sheet name is not configured.',
                ]);
            }

            $service = new GoogleSheetService;
            $service->createSheet($setting->sheet_name);

            // Auto install headers after creation using the SAME instance
            $service->ensureHeaders();

            return response()->json([
                'success' => true,
                'message' => "Sheet '{$setting->sheet_name}' created successfully with headers.",
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create sheet: '.$e->getMessage(),
            ]);
        }
    }

    /**
     * Dispatch job to sync all orders to Google Sheet.
     */
    public function syncAll(): JsonResponse
    {
        try {
            \App\Jobs\SyncAllOrdersToGoogleSheet::dispatch();

            return response()->json([
                'success' => true,
                'message' => 'Sync job dispatched successfully. This may take a few minutes.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to dispatch sync job: '.$e->getMessage(),
            ]);
        }
    }
}
