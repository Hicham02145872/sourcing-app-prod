<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\SourcingOrder;
use App\Models\User;
use App\Notifications\QuotationAccepted;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\SourcingRequest;

class QuotationController extends Controller
{
    public function index(): View
    {
        $sourcingRequests = SourcingRequest::where('user_id', auth()->id())
            ->where('status', 'quoted')
            ->with('category', 'destinations.country', 'destinations.service', 'quotation')
            ->get();

        return view('client.quotations.index', compact('sourcingRequests'));
    }


    public function accept(Request $request, Quotation $quotation): RedirectResponse
    {
        // Ensure the authenticated user owns this quotation through the sourcing request
        if (auth()->user()->id !== $quotation->sourcingRequest->user_id) {
            abort(403);
        }

        // Create a sourcing order
        $sourcingOrder = SourcingOrder::create([
            'user_id' => auth()->user()->id,
            'quotation_id' => $quotation->id,
            'total_amount' => $quotation->amount,
            'status' => 'pending_payment',
        ]);

        // Update the quotation status
        $quotation->update(['status' => 'approved']);

        // Notify admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new QuotationAccepted($quotation));
        }

        return redirect()->route('client.sourcing-orders.show', $sourcingOrder)->with('status', 'Quotation accepted successfully! Please upload your proof of payment.');
    }
}
