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

    /** @var 'air'|'sea'|'train' */
    public string $detailTab = 'air';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function selectCountry($countryId): void
    {
        $this->selectedCountry = rescue(
            fn () => Country::with(['shippingFee.items'])->findOrFail($countryId),
            null
        );
        $this->detailTab = $this->firstAvailableDetailTab();
    }

    public function closeCountryDetails(): void
    {
        $this->selectedCountry = null;
        $this->detailTab = 'air';
    }

    public function setDetailTab(string $tab): void
    {
        if (! in_array($tab, ['air', 'sea', 'train'], true)) {
            return;
        }
        $this->detailTab = $tab;
    }

    /**
     * @return 'air'|'sea'|'train'
     */
    protected function firstAvailableDetailTab(): string
    {
        $fee = $this->selectedCountry?->shippingFee;
        if (! $fee) {
            return 'air';
        }
        foreach (['air', 'sea', 'train'] as $type) {
            $has = $fee->items->contains(
                fn ($i) => strtolower((string) $i->transport_type) === $type
            );
            if ($has) {
                return $type;
            }
        }

        return 'air';
    }

    /**
     * @return list<string>
     */
    protected function orderedItemStylesForCurrentTab(): array
    {
        $fee = $this->selectedCountry?->shippingFee;
        if (! $fee) {
            return [];
        }

        $type = $this->detailTab;
        $fetched = $fee->items
            ->filter(fn ($i) => strtolower((string) $i->transport_type) === strtolower($type))
            ->pluck('item_style')
            ->map(fn ($s) => trim((string) $s))
            ->filter(fn ($s) => $s !== '')
            ->unique()
            ->values()
            ->all();

        $defaultOrder = [
            'Electr & Magnet (No Brand)',
            'Electr & Magnet (With Brand)',
            'General Cargo (No Brand)',
            'General Cargo (With Brand)',
            'General goods',
            'Power Bank, Battery, Cosmetic',
            'Screens, Electr & Mag (No Brand)',
            'Screens, Electr & Mag (With Brand)',
            'Health Care Products',
        ];

        return collect($fetched)->sortBy(function ($style) use ($defaultOrder) {
            $idx = array_search($style, $defaultOrder, true);

            return $idx === false ? 999 : $idx;
        })->values()->all();
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
            'shippingItemStyles' => $this->selectedCountry
                ? $this->orderedItemStylesForCurrentTab()
                : [],
        ]);
    }
}
