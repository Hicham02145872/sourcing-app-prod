<?php

namespace App\Livewire\Admin;

use App\Models\Country;
use App\Models\ShippingFee;
use Livewire\Component;
use Livewire\WithPagination;

class ShippingFeesTable extends Component
{
    use WithPagination;

    public $search = '';

    public bool $showEditModal = false;

    public ?Country $selectedCountry = null;

    // Form fields
    public $air_normal_fee;

    public $air_brand_fee;

    public $air_battery_fee;

    public $air_liquid_fee;

    public $sea_fee;

    public $train_fee;

    public $currency = 'USD';

    public $unit = 'kg';

    protected $queryString = ['search' => ['except' => '']];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function editCountry(int $countryId): void
    {
        $this->selectedCountry = Country::with('shippingFee')->find($countryId);

        if ($this->selectedCountry && $this->selectedCountry->shippingFee) {
            $fee = $this->selectedCountry->shippingFee;
            $this->air_normal_fee = $fee->air_normal_fee;
            $this->air_brand_fee = $fee->air_brand_fee;
            $this->air_battery_fee = $fee->air_battery_fee;
            $this->air_liquid_fee = $fee->air_liquid_fee;
            $this->sea_fee = $fee->sea_fee;
            $this->train_fee = $fee->train_fee;
            $this->currency = $fee->currency ?? 'USD';
            $this->unit = $fee->unit ?? 'kg';
        } else {
            $this->reset(['air_normal_fee', 'air_brand_fee', 'air_battery_fee', 'air_liquid_fee', 'sea_fee', 'train_fee']);
            $this->currency = 'USD';
            $this->unit = 'kg';
        }

        $this->showEditModal = true;
    }

    public function save(): void
    {
        if (! $this->selectedCountry) {
            return;
        }

        $data = [
            'country_id' => $this->selectedCountry->id,
            'air_normal_fee' => $this->air_normal_fee,
            'air_brand_fee' => $this->air_brand_fee,
            'air_battery_fee' => $this->air_battery_fee,
            'air_liquid_fee' => $this->air_liquid_fee,
            'sea_fee' => $this->sea_fee,
            'train_fee' => $this->train_fee,
            'currency' => $this->currency,
            'unit' => $this->unit,
        ];

        ShippingFee::updateOrCreate(
            ['country_id' => $this->selectedCountry->id],
            $data
        );

        $this->showEditModal = false;
        $this->dispatch('show-success-toast', message: __('Shipping fees updated successfully.'));
    }

    public function render()
    {
        $query = Country::with('shippingFee');

        if (! empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('code', 'like', '%'.$this->search.'%');
            });
        }

        $countries = $query->paginate(20);

        return view('livewire.admin.shipping-fees-table', [
            'countries' => $countries,
        ]);
    }
}
