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
            $has = $fee->items->contains(fn ($i) => $i->transport_type === $type && $i->price_per_kg !== null);
            if ($has) {
                return $type;
            }
        }

        return 'air';
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
