<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSourcingRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_name' => 'required|string|max:255',
            'product_url' => 'required|url|max:255',
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'category_id' => 'required|exists:categories,id',
            'note' => 'nullable|string',
            'shipping_method' => 'required|in:air,sea',
            'sourcing_location' => 'required|in:china,dubai',
            'destinations' => 'required|array|min:1',
            'destinations.*.country_id' => 'required|exists:countries,id',
            'destinations.*.service_id' => 'required|exists:services,id',
            'destinations.*.quantity' => 'required|integer|min:1',
            'destinations.*.address' => 'required|string|max:255',
        ];
    }
}
