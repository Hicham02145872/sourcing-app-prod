<?php

namespace App\Livewire\Admin;

use App\Models\Country;
use Livewire\Component;
use Livewire\WithPagination;

class ShippingFeesTable extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = ['search' => ['except' => '']];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Country::with('shippingFee');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%');
            });
        }

        $countries = $query->paginate(20);

        return view('livewire.admin.shipping-fees-table', [
            'countries' => $countries,
        ]);
    }
}
