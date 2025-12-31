<?php

namespace App\Livewire\Client;

use App\Models\Country;
use Livewire\Component;
use Livewire\WithPagination;

class ShippingFeesList extends Component
{
    use WithPagination;

    public $search = '';

    public $selectedCategory = null;

    public $selectedCountry = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedCategory' => ['except' => null],
    ];

    public function selectCategory($category)
    {
        if ($this->selectedCategory === $category) {
            $this->selectedCategory = null;
        } else {
            $this->selectedCategory = $category;
        }
    }

    public function selectCountry($countryId)
    {
        $this->selectedCountry = Country::with(['shippingFee.items'])->find($countryId);
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
        $query = Country::with(['shippingFee.items'])
            ->whereHas('shippingFee');

        if (! empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('code', 'like', '%'.$this->search.'%');
            });
        }

        $countries = $query->paginate(12);

        return view('livewire.client.shipping-fees-list', [
            'countries' => $countries,
        ]);
    }
}
