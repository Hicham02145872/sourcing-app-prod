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

    public function accept(Request $request, string $locale, Quotation $quotation): RedirectResponse
    {
        $this->authorize('update', $quotation);

        $quotation->loadMissing(['order', 'sourcingRequest']);

        if ($this->isAlreadyAcceptedClientState($quotation)) {
            return $this->redirectAlreadyAccepted($quotation);
        }

        try {
            $result = DB::transaction(function () use ($quotation) {
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

                if (! in_array($q->status, ['pending', 'approved', 'sent'], true)) {
                    throw new \DomainException(__('This quotation can no longer be accepted.'));
                }

                $newSourcingOrder = SourcingOrder::create([
                    'user_id' => auth()->user()->id,
                    'quotation_id' => $q->id,
                    'sourcing_request_id' => $q->sourcing_request_id,
                    'total_amount' => $q->amount,
                    'status' => 'pending_payment',
                    'assigned_to_admin_id' => $q->sourcingRequest->assigned_to_admin_id,
                ]);
                $newSourcingOrder->load('user');

                $q->update(['status' => 'accepted']);
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
}
