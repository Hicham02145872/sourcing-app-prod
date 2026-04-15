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
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('product_url')) {
            $url = $this->input('product_url');
            if ($url && ! preg_match('~^(?:f|ht)tps?://~i', $url)) {
                $this->merge([
                    'product_url' => 'https://'.$url,
                ]);
            }
        }
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        \Illuminate\Support\Facades\Log::warning('Sourcing Request Validation Failed:', [
            'user_id' => auth()->id(),
            'errors' => $validator->errors()->toArray(),
            'input' => $this->except(['product_image']),
        ]);

        parent::failedValidation($validator);
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
            'product_url' => 'required|url', // Removed max:255
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:15360', // Increased to 15MB
            'category_id' => 'required|exists:categories,id',
            'note' => 'required|string', // Changed to required
            'shipping_method' => 'required|in:air,sea',
            'sourcing_location' => 'required|in:china,dubai',
            'destinations' => 'required|array|min:1',
            'destinations.*.country_id' => 'required|exists:countries,id',
            'destinations.*.service_id' => 'required|exists:services,id',
            'destinations.*.quantity' => 'required|integer|min:1',
            'destinations.*.address' => 'required|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'note.required' => __('Please provide special requirements or details for your request.'),
            'shipping_method.required' => __('Please select a preferred shipping method.'),
            'product_url.url' => __('The product link must be a valid URL (e.g., https://alibaba.com/...)'),
            'product_image.required' => __('Please upload an image of the product.'),
        ];
    }
}
