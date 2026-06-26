<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\ProformaInvoiceMail;
use App\Models\Quotation;
use App\Models\SourcingOrder;
use App\Models\SourcingRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class QuotationController extends Controller
{
    protected function quotationAlreadyAcceptedMessage(): string
    {
        return __('This quotation has already been accepted.');
    }

    protected function redirectAlreadyAccepted(Quotation $quotation, ?SourcingOrder $order = null): RedirectResponse
    {
        $order ??= $quotation->order ?? SourcingOrder::query()->where('quotation_id', $quotation->id)->first();
        $message = $this->quotationAlreadyAcceptedMessage();

        if ($order !== null) {
            return redirect()->route('client.sourcing-orders.show', $order)->with('status', $message);
        }

        return redirect()->route('client.sourcing-requests.show', $quotation->sourcingRequest)->with('status', $message);
    }

    protected function isAlreadyAcceptedClientState(Quotation $quotation): bool
    {
        if ($quotation->status === 'accepted') {
            return true;
        }

        if ($quotation->order !== null) {
            return true;
        }

        $sourcingRequest = $quotation->sourcingRequest;
        if ($sourcingRequest && $sourcingRequest->status === 'accepted') {
            return true;
        }

        return false;
    }

    public function index(string $locale): View
    {
        $this->authorize('viewAny', Quotation::class);
        $sourcingRequests = SourcingRequest::where('user_id', auth()->id())
            ->whereHas('quotation', function ($query) {
                $query->where('status', '!=', 'rejected');
            })
            ->whereDoesntHave('quotation.order') // But SourcingOrder must NOT exist
            ->with(['category', 'destinations.country', 'destinations.service', 'quotation'])
            ->latest()
            ->get();

        return view('client.quotations.index', compact('sourcingRequests'));
    }

    public function accept(Request $request, string $locale, Quotation $quotation, \App\Services\ImageProcessingService $imageService): RedirectResponse
    {
        $this->authorize('update', $quotation);

        $request->validate([
            'proof_of_payment' => 'required|file|mimes:jpg,jpeg,png,pdf|max:15360',
        ]);

        $quotation->loadMissing(['order', 'sourcingRequest']);

        if ($this->isAlreadyAcceptedClientState($quotation)) {
            return $this->redirectAlreadyAccepted($quotation);
        }

        $storedPath = null;
        if ($request->hasFile('proof_of_payment') && $request->file('proof_of_payment')->isValid()) {
            $result = $imageService->compressAndStore(
                $request->file('proof_of_payment'),
                'proofs_of_payment',
                'local'
            );
            $storedPath = $result->path;
        }

        if (!$storedPath) {
            return redirect()->back()
                ->withErrors(['proof_of_payment' => __('The proof of payment is invalid or failed to upload.')])
                ->withInput();
        }

        try {
            $result = DB::transaction(function () use ($quotation, $request, $storedPath) {
                $q = Quotation::with(['sourcingRequest'])
                    ->where('id', $quotation->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $existingOrder = $q->order()->first();

                if ($q->status === 'accepted' || $existingOrder !== null) {
                    return ['created' => false, 'order' => $existingOrder];
                }

                if ($q->sourcingRequest->status === 'accepted') {
                    return [
                        'created' => false,
                        'order' => SourcingOrder::query()->where('quotation_id', $q->id)->first(),
                    ];
                }

                if (! in_array($q->status, ['pending', 'approved', 'sent', 'negotiating'], true)) {
                    throw new \DomainException(__('This quotation can no longer be accepted.'));
                }

                $unitPrice = $q->unit_price;
                $amount = $q->amount;
                $realProductImage = $q->real_product_image;

                $selectedQuality = $request->input('selected_quality');
                if ($selectedQuality && isset($q->quality_options[$selectedQuality])) {
                    $unitPrice = $q->quality_options[$selectedQuality]['price'];
                    if (!empty($q->quality_options[$selectedQuality]['image_path'])) {
                        $realProductImage = $q->quality_options[$selectedQuality]['image_path'];
                    }
                    $totalQuantity = $q->sourcingRequest->destinations->sum('quantity');
                    $subtotal = $unitPrice * $totalQuantity;
                    $amount = $subtotal + $q->commission_service + $q->delivery_cost_china;
                }

                $newSourcingOrder = SourcingOrder::create([
                    'user_id' => auth()->user()->id,
                    'quotation_id' => $q->id,
                    'sourcing_request_id' => $q->sourcing_request_id,
                    'total_amount' => $amount,
                    'status' => 'paid',
                    'proof_of_payment_path' => $storedPath,
                    'assigned_to_admin_id' => $q->sourcingRequest->assigned_to_admin_id,
                ]);
                $newSourcingOrder->load('user');

                $q->update([
                    'status' => 'accepted',
                    'unit_price' => $unitPrice,
                    'amount' => $amount,
                    'real_product_image' => $realProductImage,
                ]);
                $q->sourcingRequest->transitionTo('accepted', auth()->user());

                return ['created' => true, 'order' => $newSourcingOrder];
            });
        } catch (\DomainException $e) {
            return redirect()->back()
                ->withErrors(['generic' => $e->getMessage()])
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error accepting quotation: '.$e->getMessage());

            return redirect()->back()
                ->withErrors(['generic' => $e->getMessage()])
                ->with('error', __('Could not accept the quotation.'));
        }

        if (! $result['created']) {
            $quotation->refresh();

            return $this->redirectAlreadyAccepted($quotation, $result['order']);
        }

        $sourcingOrder = $result['order'];

        event(new \App\Events\QuotationAccepted($quotation));
        event(new \App\Events\SourcingOrderStatusChanged($sourcingOrder));
        event(new \App\Events\ProofOfPaymentUploadedEvent($sourcingOrder));

        $assignedAdminId = $sourcingOrder->assigned_to_admin_id;
        $admins = \App\Models\User::where('role', 'super_admin')
            ->when($assignedAdminId, function ($query) use ($assignedAdminId) {
                $query->orWhere(function ($q) use ($assignedAdminId) {
                    $q->where('role', 'admin')->where('id', $assignedAdminId);
                });
            })
            ->get();

        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\ProofOfPaymentUploaded($sourcingOrder));
        }

        // Generate and send pro-forma invoice
        try {
            Log::debug('Attempting to generate PDF for Sourcing Order #'.$sourcingOrder->id);
            $pdf = Pdf::loadView('pdf.proforma-invoice', compact('sourcingOrder'));
            Log::debug('PDF generated successfully for Sourcing Order #'.$sourcingOrder->id);

            Log::debug('Attempting to send pro-forma invoice email to '.$sourcingOrder->user->email.' for Sourcing Order #'.$sourcingOrder->id);
            Mail::to($sourcingOrder->user->email)->queue(new ProformaInvoiceMail($sourcingOrder));
            Log::debug('Pro-forma invoice email sent successfully for Sourcing Order #'.$sourcingOrder->id);
        } catch (\Exception $e) {
            Log::error('Failed to send pro-forma invoice for order #'.$sourcingOrder->id.'. Error: '.$e->getMessage(), ['exception' => $e]);
            // Do not block the user flow if email fails
        }

        return redirect()->route('client.sourcing-orders.show', $sourcingOrder)->with('status', 'Quotation accepted successfully! A pro-forma invoice has been sent to your email.');
    }

    public function reject(Request $request, string $locale, Quotation $quotation): RedirectResponse
    {
        $this->authorize('update', $quotation);

        try {
            DB::transaction(function () use ($quotation) {
                // Update the quotation status
                $quotation->update(['status' => 'rejected']);

                // Also reject the sourcing request
                $quotation->sourcingRequest->transitionTo('rejected', auth()->user());
            });
        } catch (\Throwable $e) {
            Log::error('Error rejecting quotation: '.$e->getMessage());

            return redirect()->back()
                ->withErrors(['generic' => $e->getMessage()])
                ->with('error', __('Could not reject the quotation.'));
        }

        event(new \App\Events\QuotationRejected($quotation));

        return redirect()->route('client.sourcing-requests.show', $quotation->sourcingRequest)->with('status', 'Quotation rejected successfully.');
    }

    public function negotiate(Request $request, string $locale, Quotation $quotation): RedirectResponse
    {
        $this->authorize('update', $quotation);

        $request->validate([
            'negotiation_notes' => 'required|string|max:1000',
        ]);

        try {
            DB::transaction(function () use ($request, $quotation) {
                // Update the quotation status and notes
                $quotation->update([
                    'status' => 'negotiating',
                    'negotiation_notes' => $request->negotiation_notes,
                ]);

                // Also update the sourcing request status to negotiating
                $quotation->sourcingRequest->transitionTo('negotiating', auth()->user());
            });
        } catch (\Throwable $e) {
            Log::error('Error negotiating quotation: '.$e->getMessage());

            return redirect()->back()
                ->withErrors(['generic' => $e->getMessage()])
                ->with('error', __('Could not send the negotiation request.'));
        }

        // We can dispatch an event if needed, for now let's just log or notify admin
        Log::info('Client requested negotiation for quotation #'.$quotation->id);

        return redirect()->route('client.sourcing-requests.show', $quotation->sourcingRequest)->with('status', 'Negotiation request sent successfully. We will review your request and get back to you.');
    }

    public function bulkPaymentShow(Request $request, string $locale): View|RedirectResponse
    {
        $idsStr = $request->query('ids');
        if (is_array($idsStr)) {
            $ids = array_map('intval', $idsStr);
        } elseif ($idsStr) {
            $ids = array_map('intval', explode(',', $idsStr));
        } else {
            $ids = [];
        }

        $quotations = Quotation::whereIn('id', $ids)
            ->whereHas('sourcingRequest', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->with(['sourcingRequest.category', 'sourcingRequest.destinations.country'])
            ->get();

        $validQuotations = $quotations->filter(function ($q) {
            return $q->status !== 'accepted' && $q->status !== 'rejected' && !$q->order()->exists();
        });

        if ($validQuotations->isEmpty()) {
            return redirect()->route('client.quotations.index', $locale)
                ->with('error', __('Please select at least one valid quotation.'));
        }

        $paymentMethods = \App\Models\PaymentMethod::where('is_active', true)->get();

        return view('client.quotations.bulk-payment', [
            'quotations' => $validQuotations,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    public function bulkPay(
        Request $request,
        string $locale,
        \App\Services\ImageProcessingService $imageService
    ): RedirectResponse {
        $request->validate([
            'quotation_ids' => 'required|array',
            'quotation_ids.*' => 'integer',
            'proof_of_payment' => 'required|file|mimes:jpg,jpeg,png,pdf|max:15360',
            'qualities' => 'nullable|array',
        ]);

        $ids = array_map('intval', $request->input('quotation_ids'));
        $qualities = $request->input('qualities', []);

        $quotations = Quotation::whereIn('id', $ids)
            ->whereHas('sourcingRequest', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->with('sourcingRequest')
            ->get();

        $validQuotations = $quotations->filter(function ($q) {
            return $q->status !== 'accepted' && $q->status !== 'rejected' && !$q->order()->exists();
        });

        if ($validQuotations->isEmpty()) {
            return redirect()->route('client.quotations.index', $locale)
                ->with('error', __('No valid quotations found to process.'));
        }

        $storedPath = null;
        if ($request->hasFile('proof_of_payment') && $request->file('proof_of_payment')->isValid()) {
            $result = $imageService->compressAndStore(
                $request->file('proof_of_payment'),
                'proofs_of_payment',
                'local'
            );
            $storedPath = $result->path;
        }

        if (!$storedPath) {
            return redirect()->back()
                ->withErrors(['proof_of_payment' => __('The proof of payment is invalid or failed to upload.')])
                ->withInput();
        }

        try {
            $orders = DB::transaction(function () use ($validQuotations, $storedPath, $qualities) {
                $createdOrders = [];
                
                foreach ($validQuotations as $q) {
                    $quotation = Quotation::with('sourcingRequest')
                        ->where('id', $q->id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($quotation->status === 'accepted' || $quotation->order()->exists()) {
                        continue;
                    }

                    $unitPrice = $quotation->unit_price;
                    $amount = $quotation->amount;
                    $realProductImage = $quotation->real_product_image;

                    $selectedQuality = $qualities[$quotation->id] ?? null;
                    if ($selectedQuality && isset($quotation->quality_options[$selectedQuality])) {
                        $unitPrice = $quotation->quality_options[$selectedQuality]['price'];
                        if (!empty($quotation->quality_options[$selectedQuality]['image_path'])) {
                            $realProductImage = $quotation->quality_options[$selectedQuality]['image_path'];
                        }
                        $totalQuantity = $quotation->sourcingRequest->destinations->sum('quantity');
                        $subtotal = $unitPrice * $totalQuantity;
                        $amount = $subtotal + $quotation->commission_service + $quotation->delivery_cost_china;
                    }

                    $order = SourcingOrder::create([
                        'user_id' => auth()->id(),
                        'quotation_id' => $quotation->id,
                        'sourcing_request_id' => $quotation->sourcing_request_id,
                        'total_amount' => $amount,
                        'status' => 'paid',
                        'proof_of_payment_path' => $storedPath,
                        'assigned_to_admin_id' => $quotation->sourcingRequest->assigned_to_admin_id,
                    ]);

                    $quotation->update([
                        'status' => 'accepted',
                        'unit_price' => $unitPrice,
                        'amount' => $amount,
                        'real_product_image' => $realProductImage,
                    ]);
                    $quotation->sourcingRequest->transitionTo('accepted', auth()->user());

                    $createdOrders[] = $order;
                }

                return $createdOrders;
            });

            if (empty($orders)) {
                return redirect()->route('client.quotations.index', $locale)
                    ->with('error', __('Quotations were already accepted or processed.'));
            }

            foreach ($orders as $order) {
                event(new \App\Events\QuotationAccepted($order->quotation));
                event(new \App\Events\SourcingOrderStatusChanged($order));
                event(new \App\Events\ProofOfPaymentUploadedEvent($order));

                try {
                    $pdf = Pdf::loadView('pdf.proforma-invoice', compact('order'));
                    Mail::to(auth()->user()->email)->queue(new ProformaInvoiceMail($order));
                } catch (\Exception $e) {
                    Log::error('Failed to send pro-forma invoice for order #'.$order->id.'. Error: '.$e->getMessage());
                }

                $assignedAdminId = $order->assigned_to_admin_id;
                $admins = \App\Models\User::where('role', 'super_admin')
                    ->when($assignedAdminId, function ($query) use ($assignedAdminId) {
                        $query->orWhere(function ($q) use ($assignedAdminId) {
                            $q->where('role', 'admin')->where('id', $assignedAdminId);
                        });
                    })
                    ->get();

                foreach ($admins as $admin) {
                    $admin->notify(new \App\Notifications\ProofOfPaymentUploaded($order));
                }
            }

        } catch (\Exception $e) {
            Log::error('Error processing bulk payment: '.$e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->with('error', __('An error occurred while processing the bulk payment.'))
                ->withInput();
        }

        return redirect()->route('client.quotations.index', $locale)
            ->with('success', __('Bulk payment submitted successfully! Your orders are now paid and under review.'));
    }
}
