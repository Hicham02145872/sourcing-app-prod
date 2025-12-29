<?php

namespace App\Livewire\Client;

use App\Models\Country;
use Livewire\Component;
use Livewire\WithPagination;

class ShippingFeesList extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Country::with('shippingFee')
            ->whereHas('shippingFee', function ($q) {
                $q->whereNotNull('air_normal_fee')
                    ->orWhereNotNull('air_brand_fee')
                    ->orWhereNotNull('air_battery_fee')
                    ->orWhereNotNull('air_liquid_fee')
                    ->orWhereNotNull('sea_fee')
                    ->orWhereNotNull('train_fee');
            });

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%');
            });
        }

        $countries = $query->get(); // Using get() for now as the previous view didn't seem to have pagination, but I'll check.
        // Wait, the previous view iterated over $countries. The controller used ->get().
        
        return view('livewire.client.shipping-fees-list', [
            'countries' => $countries,
        ]);
    }
}
