<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PaymentMethodController extends Controller
{
    public function __construct(
        protected \App\Services\ImageProcessingService $imageService
    ) {}

    public function index(Request $request): View
    {
        $query = PaymentMethod::query();

        if ($search = $request->query('search')) {
            $query->where('name', 'like', '%'.$search.'%');
        }

        $paymentMethods = $query->paginate(10)->withQueryString();

        return view('admin.payment-methods.index', compact('paymentMethods'));
    }

    public function create(): View
    {
        return view('admin.payment-methods.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
            'account_type' => 'nullable|string|max:255',
            'account_holder' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'routing_number' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'swift_code' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        $details = [
            'Account Type' => $validated['account_type'] ?? null,
            'Account Holder' => $validated['account_holder'] ?? null,
            'Account Number' => $validated['account_number'] ?? null,
            'Routing Number' => $validated['routing_number'] ?? null,
            'Bank Name' => $validated['bank_name'] ?? null,
            'SWIFT Code' => $validated['swift_code'] ?? null,
            'IBAN' => $validated['iban'] ?? null,
            'Country' => $validated['country'] ?? null,
            'Email' => $validated['email'] ?? null,
            'Address' => $validated['address'] ?? null,
        ];

        // Remove null values from details array
        $details = array_filter($details, fn ($value) => ! is_null($value) && $value !== '');

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $result = $this->imageService->compressAndStore(
                $request->file('logo'),
                'payment_method_logos',
                'public'
            );
            $logoPath = $result->path;
        }

        PaymentMethod::create([
            'name' => $validated['name'],
            'logo_path' => $logoPath,
            'details' => $details,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.payment-methods.index')->with('status', 'Payment method created successfully!');
    }

    public function edit(PaymentMethod $paymentMethod): View
    {
        return view('admin.payment-methods.edit', compact('paymentMethod'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
            'account_type' => 'nullable|string|max:255',
            'account_holder' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'routing_number' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'swift_code' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        $details = [
            'Account Type' => $validated['account_type'] ?? null,
            'Account Holder' => $validated['account_holder'] ?? null,
            'Account Number' => $validated['account_number'] ?? null,
            'Routing Number' => $validated['routing_number'] ?? null,
            'Bank Name' => $validated['bank_name'] ?? null,
            'SWIFT Code' => $validated['swift_code'] ?? null,
            'IBAN' => $validated['iban'] ?? null,
            'Country' => $validated['country'] ?? null,
            'Email' => $validated['email'] ?? null,
            'Address' => $validated['address'] ?? null,
        ];

        // Remove null values from details array
        $details = array_filter($details, fn ($value) => ! is_null($value) && $value !== '');

        $logoPath = $paymentMethod->logo_path;
        if ($request->hasFile('logo')) {
            if ($logoPath) {
                Storage::disk('public')->delete($logoPath);
                if ($paymentMethod->cloudinary_public_id) {
                    \App\Jobs\DeleteCloudinaryAsset::dispatch($paymentMethod->cloudinary_public_id);
                }
            }
            $result = $this->imageService->compressAndStore(
                $request->file('logo'),
                'payment_method_logos',
                'public'
            );
            $logoPath = $result->path;
        }

        $paymentMethod->update([
            'name' => $validated['name'],
            'logo_path' => $logoPath,
            'details' => $details,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.payment-methods.index')->with('status', 'Payment method updated successfully!');
    }

    public function destroy(PaymentMethod $paymentMethod): RedirectResponse
    {
        $paymentMethod->delete();

        return redirect()->route('admin.payment-methods.index')->with('status', 'Payment method deleted successfully!');
    }
}
