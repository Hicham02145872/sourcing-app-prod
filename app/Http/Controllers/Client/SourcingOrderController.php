<?php

namespace App\Http\Controllers\Client;

use App\Events\ProofOfPaymentUploadedEvent;
use App\Events\SourcingOrderStatusChanged;
use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\SourcingOrder;
use App\Models\User;
use App\Notifications\ProofOfPaymentUploaded;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SourcingOrderController extends Controller
{
    public function __construct(
        protected \App\Services\ImageProcessingService $imageService
    ) {}

    public function index(Request $request, string $locale): View
    {
        $this->authorize('viewAny', SourcingOrder::class);

        $query = Auth::user()->sourcingOrders()
            ->with(['quotation.sourcingRequest'])
            ->latest();

        if ($request->has('status') && $request->status != 'all') {
            $status = $request->status;
            if ($status === 'in_transit') {
                $query->whereIn('status', ['in_transit_china', 'arrival_uae', 'customs_clearance_uae', 'in_transit_uae', 'arrival_destination_country', 'customs_clearance_destination_country', 'out_for_delivery', 'shipment_delayed']);
            } elseif ($status === 'preparing') {
                $query->where('status', 'shipment_preparing');
            } else {
                $query->where('status', $status);
            }
        }

        $sourcingOrders = $query->paginate(10);

        return view('client.sourcing-orders.index', compact('sourcingOrders'));
    }

    public function show(string $locale, SourcingOrder $sourcingOrder): View
    {
        $this->authorize('view', $sourcingOrder);

        $sourcingOrder->load([
            'quotation.sourcingRequest.destinations.country',
            'destinationShipments.shippingCompany',
            'shippingCompany',
        ]);

        // Backfill FSB tracking when paid and not assigned to shipping company or real tracking (same rule as listener)
        $eligibleForFsb = $sourcingOrder->status === 'paid'
            && is_null($sourcingOrder->fsb_tracking_created_at)
            && (! $sourcingOrder->shipping_company_id || empty(trim((string) $sourcingOrder->tracking_number)));
        if ($eligibleForFsb) {
            $sourcingOrder->update(['fsb_tracking_created_at' => now()]);
            $sourcingOrder->refresh();
        }

        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        return view('client.sourcing-orders.show', compact('sourcingOrder', 'paymentMethods'));
    }

    public function uploadProofOfPayment(Request $request, string $locale, SourcingOrder $sourcingOrder): RedirectResponse
    {
        $this->authorize('uploadProofOfPayment', $sourcingOrder);

        $request->validate([
            'proof_of_payment' => 'required|file|mimes:jpg,jpeg,png,pdf|max:15360',
        ]);

        Log::debug('uploadProofOfPayment method called', ['method' => $request->method(), 'request' => $request->all()]);

        if ($request->hasFile('proof_of_payment') && $request->file('proof_of_payment')->isValid()) {
            $result = $this->imageService->compressAndStore(
                $request->file('proof_of_payment'),
                'proofs_of_payment',
                'local'
            );

            Log::debug('File stored', ['path' => $result->path]);
            $sourcingOrder->update([
                'proof_of_payment_path' => $result->path,
                'status' => 'paid',
            ]);

            // Trigger status change so FSB tracking is initialized and client gets notification with tracking number
            event(new SourcingOrderStatusChanged($sourcingOrder));

            // Notify only the assigned admin and all super admins
            $assignedAdminId = $sourcingOrder->assigned_to_admin_id;

            $admins = User::where('role', 'super_admin')
                ->when($assignedAdminId, function ($query) use ($assignedAdminId) {
                    $query->orWhere(function ($q) use ($assignedAdminId) {
                        $q->where('role', 'admin')
                            ->where('id', $assignedAdminId);
                    });
                })
                ->get();

            foreach ($admins as $admin) {
                $admin->notify(new ProofOfPaymentUploaded($sourcingOrder));
            }

            event(new ProofOfPaymentUploadedEvent($sourcingOrder));
        } else {
            Log::error('Request has no file or file is not valid');
        }

        return redirect()->route('client.sourcing-orders.show', $sourcingOrder)->with('status', 'Proof of payment uploaded successfully. It will be reviewed by an admin.');
    }

    public function showReceipt(string $locale, SourcingOrder $sourcingOrder): View
    {
        $this->authorize('view', $sourcingOrder);

        return view('client.sourcing-orders.receipt', compact('sourcingOrder'));
    }

    public function downloadProofOfPayment(string $locale, SourcingOrder $sourcingOrder)
    {
        $this->authorize('view', $sourcingOrder);
        if (! $sourcingOrder->proof_of_payment_path) {
            abort(404);
        }

        return Storage::disk('local')->download($sourcingOrder->proof_of_payment_path);
    }

    public function export(Request $request, string $locale)
    {
        $this->authorize('viewAny', SourcingOrder::class);

        $query = Auth::user()->sourcingOrders()
            ->with(['quotation.sourcingRequest'])
            ->latest();

        if ($request->has('status') && $request->status != 'all') {
            $status = $request->status;
            if ($status === 'in_transit') {
                $query->whereIn('status', ['in_transit_china', 'arrival_uae', 'customs_clearance_uae', 'in_transit_uae', 'arrival_destination_country', 'customs_clearance_destination_country', 'out_for_delivery', 'shipment_delayed']);
            } elseif ($status === 'preparing') {
                $query->where('status', 'shipment_preparing');
            } else {
                $query->where('status', $status);
            }
        }

        $sourcingOrders = $query->get();

        $pdf = Pdf::loadView('client.sourcing-orders.pdf', compact('sourcingOrders'));

        return $pdf->stream('sourcing-orders.pdf');
    }

    public function showShippingLabel(string $locale, SourcingOrder $sourcingOrder)
    {
        $this->authorize('view', $sourcingOrder);

        if (request()->query('format') === 'html') {
            return view('client.sourcing-orders.shipping-label', compact('sourcingOrder'));
        }

        if ($this->resolveLabelFormat() === 'png') {
            $png = $this->shippingLabelDownloadResponse($sourcingOrder);
            if ($png) {
                return $png;
            }
        }

        $pdf = Pdf::loadView('client.sourcing-orders.shipping-label', compact('sourcingOrder'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('shipping-label-'.$sourcingOrder->id.'.pdf');
    }

    /**
     * Resolve the label output format. PNG downloads are the default once the
     * label_image_output flag is enabled; format=png / format=pdf / format=html
     * can force an explicit format.
     */
    private function resolveLabelFormat(): string
    {
        $forced = request()->query('format');

        if (in_array($forced, ['png', 'pdf'], true)) {
            return $forced;
        }

        return app(\App\Services\FeatureFlagService::class)->isEnabled('label_image_output', auth()->user()) ? 'png' : 'pdf';
    }

    /**
     * Return a direct PNG download (single destination) or a ZIP archive
     * containing one PNG per destination when the order has several
     * destinations, provided the label_image_output flag is enabled.
     * Returns null otherwise.
     */
    protected function shippingLabelDownloadResponse(SourcingOrder $sourcingOrder)
    {
        if (! app(\App\Services\FeatureFlagService::class)->isEnabled('label_image_output', auth()->user())) {
            return null;
        }

        $service = app(\App\Services\ShippingLabelImageService::class);
        $destinations = $sourcingOrder->quotation->sourcingRequest->destinations;

        if ($destinations->count() < 2) {
            $blob = $service->pngBlob($service->render($sourcingOrder, $destinations->first()));

            return response($blob, 200)
                ->header('Content-Type', 'image/png')
                ->header('Content-Disposition', 'attachment; filename="shipping-label-'.$sourcingOrder->id.'.png"');
        }

        $zipPath = tempnam(sys_get_temp_dir(), 'labels-').'.zip';
        $zip = new \ZipArchive;

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return null;
        }

        foreach ($destinations as $index => $destination) {
            $blob = $service->pngBlob($service->render($sourcingOrder, $destination));

            $country = Str::slug($destination?->country?->name ?? 'destination-'.($index + 1), '-');

            $zip->addFromString(
                'shipping-label-'.$sourcingOrder->id.'/'.($index + 1).'-'.$country.'.png',
                $blob
            );
        }

        $zip->close();

        $response = response((string) file_get_contents($zipPath), 200)
            ->header('Content-Type', 'application/zip')
            ->header('Content-Disposition', 'attachment; filename="shipping-labels-'.$sourcingOrder->id.'.zip"');

        @unlink($zipPath);

        return $response;
    }

    /**
     * Return a direct PNG download of the shipping label when the
     * label_image_output flag is enabled, null otherwise.
     */
    protected function shippingLabelPngResponse(SourcingOrder $sourcingOrder)
    {
        if (! app(\App\Services\FeatureFlagService::class)->isEnabled('label_image_output', auth()->user())) {
            return null;
        }

        $service = app(\App\Services\ShippingLabelImageService::class);
        $blob = $service->pngBlob($service->render($sourcingOrder));

        return response($blob, 200)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="shipping-label-'.$sourcingOrder->id.'.png"');
    }
}
