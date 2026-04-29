<?php

namespace App\Livewire\Client;

use App\Models\Country;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class ShippingFeesList extends Component
{
    use WithPagination;

    public $search = '';

    public $selectedCountry = null;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function selectCountry($countryId)
    {
        $this->selectedCountry = rescue(
            fn () => Country::with(['shippingFee.items'])->findOrFail($countryId),
            null
        );
    }

    public function closeCountryDetails()
    {
        $this->selectedCountry = null;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $countries = rescue(function () {
            $query = Country::with(['shippingFee.items'])
                ->whereHas('shippingFee');

            if (! empty($this->search)) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('code', 'like', '%'.$this->search.'%');
                });
            }

            return $query->orderBy('name')->paginate(12);
        }, new LengthAwarePaginator([], 0, 12));

        return view('livewire.client.shipping-fees-list', [
            'countries' => $countries,
        ]);
    }
}
