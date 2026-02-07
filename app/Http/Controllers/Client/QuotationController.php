<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\ProformaInvoiceMail;
use App\Models\Quotation;
use App\Models\SourcingOrder;
use App\Models\SourcingRequest;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class QuotationController extends Controller
{
    public function index(): View
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

    public function accept(Request $request, Quotation $quotation): RedirectResponse
    {
        $this->authorize('update', $quotation);

        $sourcingOrder = null; // Declare outside transaction

        try {
            $sourcingOrder = DB::transaction(function () use ($quotation) {
                // Lock the quotation row to prevent race conditions
                $q = Quotation::where('id', $quotation->id)->lockForUpdate()->firstOrFail();

                // Check if the quotation has already been processed
                if ($q->status !== 'pending') { // Assuming 'pending' is the initial state before acceptance
                    throw new \Exception('This quotation has already been processed.');
                }

                // Create a sourcing order
                $newSourcingOrder = SourcingOrder::create([
                    'user_id' => auth()->user()->id,
                    'quotation_id' => $q->id,
                    'total_amount' => $q->amount,
                    'status' => 'pending_payment',
                    // Auto-assign to the admin who handled the request
                    'assigned_to_admin_id' => $q->sourcingRequest->assigned_to_admin_id,
                ]);
                $newSourcingOrder->load('user'); // Eager load the user relationship

                // Update the quotation status
                $q->update(['status' => 'accepted']);

                // Update the sourcing request status
                $q->sourcingRequest->transitionTo('accepted', auth()->user());

                return $newSourcingOrder; // Return the created order
            });
        } catch (\Exception $e) {
            Log::error('Error accepting quotation: '.$e->getMessage());

            return redirect()->back()->withErrors(['generic' => $e->getMessage()]);
        }

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

    public function reject(Request $request, Quotation $quotation): RedirectResponse
    {
        $this->authorize('update', $quotation);

        DB::transaction(function () use ($quotation) {
            // Update the quotation status
            $quotation->update(['status' => 'rejected']);

            // Also reject the sourcing request
            $quotation->sourcingRequest->transitionTo('rejected', auth()->user());
        });

        event(new \App\Events\QuotationRejected($quotation));

        return redirect()->route('client.sourcing-requests.show', $quotation->sourcingRequest)->with('status', 'Quotation rejected successfully.');
    }

    public function negotiate(Request $request, Quotation $quotation): RedirectResponse
    {
        $this->authorize('update', $quotation);

        $request->validate([
            'negotiation_notes' => 'required|string|max:1000',
        ]);

        DB::transaction(function () use ($request, $quotation) {
            // Update the quotation status and notes
            $quotation->update([
                'status' => 'negotiating',
                'negotiation_notes' => $request->negotiation_notes,
            ]);

            // Also update the sourcing request status to negotiating
            $quotation->sourcingRequest->transitionTo('negotiating', auth()->user());
        });

        // We can dispatch an event if needed, for now let's just log or notify admin
        Log::info('Client requested negotiation for quotation #'.$quotation->id);

        return redirect()->route('client.sourcing-requests.show', $quotation->sourcingRequest)->with('status', 'Negotiation request sent successfully. We will review your request and get back to you.');
    }
}
