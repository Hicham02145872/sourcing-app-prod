<?php

namespace App\Livewire\Client;

use App\Models\Country;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class ShippingFeesList extends Component
{
    use WithPagination;

    public $search = '';

    public $selectedCountry = null;

    /** @var 'air_direct'|'sea'|'air_indirect' */
    public string $detailTab = 'air_direct';

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
        $this->detailTab = 'air_direct';
    }

    public function setDetailTab(string $tab): void
    {
        if (! in_array($tab, ['air_direct', 'sea', 'air_indirect'], true)) {
            return;
        }
        $fee = $this->selectedCountry?->shippingFee;
        if ($tab === 'air_direct' && !($fee->is_air_direct_visible ?? true)) {
            return;
        }
        if ($tab === 'sea' && !($fee->is_sea_visible ?? true)) {
            return;
        }
        if ($tab === 'air_indirect' && !($fee->is_air_indirect_visible ?? true)) {
            return;
        }
        $this->detailTab = $tab;
    }

    /**
     * Colonnes libellées Dubaï / ÉAU : affichées uniquement sous l'onglet UAE (train),
     * même si elles sont enregistrées par erreur sous air/sea.
     */
    protected function isUaeHubItemStyle(?string $itemStyle): bool
    {
        $s = strtolower(trim((string) $itemStyle));
        if ($s === '') {
            return false;
        }

        foreach (['dubai', 'uae', 'emirates', 'united arab'] as $needle) {
            if (str_contains($s, $needle)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  \Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Collection  $items
     */
    protected function filterItemsForTransportTab($items, string $transportTab): Collection
    {
        $transportTab = strtolower($transportTab);

        if ($transportTab === 'air_indirect') {
            $trainRows = $items->filter(
                fn ($i) => strtolower((string) $i->transport_type) === 'air_indirect'
            );
            $uaeMisplaced = $items->filter(function ($i) {
                $t = strtolower((string) $i->transport_type);

                return ($t === 'air_direct' || $t === 'sea') && $this->isUaeHubItemStyle($i->item_style);
            });

            return $trainRows->merge($uaeMisplaced)->unique('id')->values();
        }

        return $items
            ->filter(fn ($i) => strtolower((string) $i->transport_type) === $transportTab)
            ->filter(fn ($i) => ! $this->isUaeHubItemStyle($i->item_style))
            ->values();
    }

    protected function itemsForSelectedCountryTab(): Collection
    {
        $fee = $this->selectedCountry?->shippingFee;
        if (! $fee) {
            return collect();
        }

        if ($this->detailTab === 'air_direct' && !($fee->is_air_direct_visible ?? true)) {
            return collect();
        }
        if ($this->detailTab === 'sea' && !($fee->is_sea_visible ?? true)) {
            return collect();
        }
        if ($this->detailTab === 'air_indirect' && !($fee->is_air_indirect_visible ?? true)) {
            return collect();
        }

        return $this->filterItemsForTransportTab($fee->items, $this->detailTab);
    }

    /**
     * @return 'air_direct'|'sea'|'air_indirect'
     */
    protected function firstAvailableDetailTab(): string
    {
        $fee = $this->selectedCountry?->shippingFee;
        if (! $fee) {
            return 'air_direct';
        }
        
        $availableTypes = [];
        if ($fee->is_air_direct_visible ?? true) {
            $availableTypes[] = 'air_direct';
        }
        if ($fee->is_sea_visible ?? true) {
            $availableTypes[] = 'sea';
        }
        if ($fee->is_air_indirect_visible ?? true) {
            $availableTypes[] = 'air_indirect';
        }

        foreach ($availableTypes as $type) {
            if ($this->filterItemsForTransportTab($fee->items, $type)->isNotEmpty()) {
                return $type;
            }
        }

        return $availableTypes[0] ?? 'air_direct';
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
            'feeItemsForTab' => $this->selectedCountry
                ? $this->itemsForSelectedCountryTab()
                : collect(),
        ]);
    }
}
