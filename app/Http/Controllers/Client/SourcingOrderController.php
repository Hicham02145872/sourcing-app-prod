<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\SourcingOrder;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use App\Notifications\ProofOfPaymentUploaded;
use Illuminate\Support\Facades\Log;

class SourcingOrderController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', SourcingOrder::class);
        $sourcingOrders = Auth::user()->sourcingOrders()->with('quotation.sourcingRequest')->get();
        return view('client.sourcing-orders.index', compact('sourcingOrders'));
    }

    public function show(SourcingOrder $sourcingOrder): View
    {
        $this->authorize('view', $sourcingOrder);

        return view('client.sourcing-orders.show', compact('sourcingOrder'));
    }

    public function uploadProofOfPayment(Request $request, SourcingOrder $sourcingOrder): RedirectResponse
    {
        $this->authorize('update', $sourcingOrder);
        Log::debug('uploadProofOfPayment method called', ['method' => $request->method(), 'request' => $request->all()]);

        if ($request->hasFile('proof_of_payment')) {
            Log::debug('Request has file');
            if ($request->file('proof_of_payment')->isValid()) {
                Log::debug('File is valid');
                $path = $request->file('proof_of_payment')->store('proofs_of_payment', 'local');
                Log::debug('File stored', ['path' => $path]);
                $sourcingOrder->update([
                    'proof_of_payment_path' => $path,
                    'status' => 'paid',
                ]);

                // Notify admins
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new ProofOfPaymentUploaded($sourcingOrder));
                }
            } else {
                Log::error('File is not valid');
            }
        } else {
            Log::error('Request has no file');
        }

        return redirect()->route('client.sourcing-orders.show', $sourcingOrder)->with('status', 'Proof of payment uploaded successfully. It will be reviewed by an admin.');
    }

    public function showReceipt(SourcingOrder $sourcingOrder): View
    {
        $this->authorize('view', $sourcingOrder);

        return view('client.sourcing-orders.receipt', compact('sourcingOrder'));
    }
}
