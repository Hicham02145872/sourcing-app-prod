<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientDestinationQuantitiesRequest extends FormRequest
{
    public function authorize(): bool
    {
        $sourcingRequest = $this->route('sourcing_request');

        return $sourcingRequest && $this->user()->can('updateDestinationQuantities', $sourcingRequest);
    }

    public function rules(): array
    {
        return [
            'destinations' => ['required', 'array', 'min:1'],
            'destinations.*.id' => [
                'required',
                'integer',
                Rule::exists('sourcing_request_destinations', 'id')->where(
                    'sourcing_request_id',
                    $this->route('sourcing_request')->id
                ),
            ],
            'destinations.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $sr = $this->route('sourcing_request');
            $expected = $sr->destinations()->pluck('id')->sort()->values();
            $got = collect($this->input('destinations', []))->pluck('id')->sort()->values();

            if ($expected->count() !== $got->count() || $expected->diff($got)->isNotEmpty()) {
                $validator->errors()->add('destinations', __('All shipping destinations must be included.'));
            }
        });
    }
}
