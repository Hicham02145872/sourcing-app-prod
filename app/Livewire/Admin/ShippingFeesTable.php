<?php

namespace App\Livewire\Admin;

use App\Models\Country;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class ShippingFeesTable extends Component
{
    use WithPagination;

    public $search = '';

    public bool $showEditModal = false;

    public ?Country $selectedCountry = null;

    protected $queryString = ['search' => ['except' => '']];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function editCountry(int $countryId): void
    {
        $this->redirect(route('admin.shipping-fees.edit', $countryId), navigate: true);
    }

    public function render()
    {
        $countries = rescue(function () {
            $query = Country::with(['shippingFee.items']);

            if (! empty($this->search)) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('code', 'like', '%'.$this->search.'%');
                });
            }

            return $query->paginate(20);
        }, new LengthAwarePaginator([], 0, 20));

        return view('livewire.admin.shipping-fees-table', [
            'countries' => $countries,
        ]);
    }
}
