<?php

namespace App\Http\Controllers\Client;

use App\Events\ProofOfPaymentUploadedEvent;
use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\SourcingOrder;
use App\Models\User;
use App\Notifications\ProofOfPaymentUploaded;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use PDF;

class SourcingOrderController extends Controller
{
    public function __construct(
        protected \App\Services\ImageProcessingService $imageService
    ) {}

    public function index(Request $request): View
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

    public function show(SourcingOrder $sourcingOrder): View
    {
        $this->authorize('view', $sourcingOrder);
        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        return view('client.sourcing-orders.show', compact('sourcingOrder', 'paymentMethods'));
    }

    public function uploadProofOfPayment(Request $request, SourcingOrder $sourcingOrder): RedirectResponse
    {
        $this->authorize('uploadProofOfPayment', $sourcingOrder);
        Log::debug('uploadProofOfPayment method called', ['method' => $request->method(), 'request' => $request->all()]);

        if ($request->hasFile('proof_of_payment') && $request->file('proof_of_payment')->isValid()) {
            $path = $this->imageService->compressAndStore(
                $request->file('proof_of_payment'),
                'proofs_of_payment',
                'local'
            );

            Log::debug('File stored', ['path' => $path]);
            $sourcingOrder->update([
                'proof_of_payment_path' => $path,
                'status' => 'paid',
            ]);

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

    public function showReceipt(SourcingOrder $sourcingOrder): View
    {
        $this->authorize('view', $sourcingOrder);

        return view('client.sourcing-orders.receipt', compact('sourcingOrder'));
    }

    public function downloadProofOfPayment(SourcingOrder $sourcingOrder)
    {
        $this->authorize('view', $sourcingOrder);
        if (! $sourcingOrder->proof_of_payment_path) {
            abort(404);
        }

        return Storage::disk('local')->download($sourcingOrder->proof_of_payment_path);
    }

    public function export(Request $request)
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

        $pdf = PDF::loadView('client.sourcing-orders.pdf', compact('sourcingOrders'));

        return $pdf->stream('sourcing-orders.pdf');
    }
}
