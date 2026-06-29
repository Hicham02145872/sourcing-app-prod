<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuotationMedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuotationMediaController extends Controller
{
    public function destroy(Request $request, QuotationMedia $medium): JsonResponse
    {
        $this->authorize('update', $medium->quotation);

        Storage::disk('public')->delete($medium->file_path);

        $medium->delete();

        return response()->json(['success' => true, 'message' => __('Media deleted successfully.')]);
    }

    public function destroyFeatured(Request $request, $quotationId): JsonResponse
    {
        $quotation = \App\Models\Quotation::findOrFail($quotationId);
        $this->authorize('update', $quotation);

        if ($quotation->real_product_image) {
            Storage::disk('public')->delete($quotation->real_product_image);
            $quotation->update(['real_product_image' => null]);
        }

        return response()->json(['success' => true, 'message' => __('Featured photo deleted successfully.')]);
    }
}
