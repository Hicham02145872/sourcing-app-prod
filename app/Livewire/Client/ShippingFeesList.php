<?php

namespace App\Livewire\Client;

use App\Models\Country;
use App\Models\ShippingFeeItem;
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

    public function mount(): void
    {
        if (! empty($this->selectedCategory)) {
            return;
        }

        // Show detailed table on first page load without requiring a click.
        foreach (['sea', 'air', 'train'] as $transportType) {
            $hasRates = ShippingFeeItem::query()
                ->where('transport_type', $transportType)
                ->whereNotNull('price_per_kg')
                ->exists();

            if ($hasRates) {
                $this->selectedCategory = $transportType;

                return;
            }
        }

        $this->selectedCategory = 'sea';
    }

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

        $itemStyles = [];
        if ($this->selectedCategory) {
            $defaultOrder = [
                'Electr & Magnet (No Brand)',
                'Electr & Magnet (With Brand)',
                'General Cargo (No Brand)',
                'General Cargo (With Brand)',
                'Power Bank, Battery, Cosmetic',
                'Screens, Electr & Mag (No Brand)',
                'Screens, Electr & Mag (With Brand)',
                'Health Care Products',
            ];

            $fetchedStyles = $countries->getCollection()
                ->flatMap(function ($country) {
                    return $country->shippingFee?->items ?? collect();
                })
                ->filter(function ($item) {
                    return $item->transport_type === $this->selectedCategory
                        && ! is_null($item->price_per_kg)
                        && trim((string) $item->item_style) !== '';
                })
                ->pluck('item_style')
                ->unique()
                ->values()
                ->toArray();

            // Sort fetched styles based on defaultOrder, keep custom styles at the end.
            $itemStyles = collect($fetchedStyles)->sortBy(function ($style) use ($defaultOrder) {
                $index = array_search($style, $defaultOrder);

                return $index === false ? 999 : $index;
            })->values()->toArray();
        }

        return view('livewire.client.shipping-fees-list', [
            'countries' => $countries,
            'itemStyles' => $itemStyles,
        ]);
    }
}
