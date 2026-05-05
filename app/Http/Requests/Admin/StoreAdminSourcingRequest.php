<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminSourcingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Client selection
            'client_type' => 'required|in:existing,new',
            'user_id' => 'required_if:client_type,existing|nullable|exists:users,id',
            'client_name' => 'required_if:client_type,new|nullable|string|max:255',
            'client_email' => 'required_if:client_type,new|nullable|email|max:255|unique:users,email',
            'client_phone' => 'nullable|string|max:255',

            // Sourcing Request Details
            'product_name' => 'required|string|max:255',
            'product_url' => 'nullable|url|max:255',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'category_id' => 'required|exists:categories,id',
            'note' => 'nullable|string',
            'phone_number' => 'nullable|string|max:255', // Product-specific phone if different
            'address' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'shipping_method' => 'nullable|in:air,sea',
            'sourcing_location' => 'required|in:china,dubai',

            // Destinations
            'destinations' => 'required|array|min:1',
            'destinations.*.country_id' => 'required|exists:countries,id',
            'destinations.*.service_id' => 'required|exists:services,id',
            'destinations.*.quantity' => 'required|integer|min:1',
        ];
    }
}
