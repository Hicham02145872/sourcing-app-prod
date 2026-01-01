<?php

namespace App\Livewire\Admin;

use App\Models\Country;
use App\Models\ShippingFee;
use Livewire\Component;

class ShippingFeeEdit extends Component
{
    public Country $country;

    public ?ShippingFee $shippingFee = null;

    public $currency = 'USD';

    public $unit = 'kg';

    public $air_arrival_time = '7-9';

    public $sea_arrival_time = '30-45';

    public $train_arrival_time = '15-20';

    public array $itemsData = [
        'air' => [],
        'sea' => [],
        'train' => [],
    ];

    public array $transportTypes = ['air', 'sea', 'train'];

    public function mount(Country $country)
    {
        $this->country = $country->load(['shippingFee.items']);
        $this->initItemsData();

        if ($this->country->shippingFee) {
            $fee = $this->country->shippingFee;
            $this->shippingFee = $fee;
            $this->currency = $fee->currency ?? 'USD';
            $this->unit = $fee->unit ?? 'kg';

            $this->air_arrival_time = $fee->air_arrival_time ?? '7-9';
            $this->sea_arrival_time = $fee->sea_arrival_time ?? '30-45';
            $this->train_arrival_time = $fee->train_arrival_time ?? '15-20';

            foreach ($fee->items as $item) {
                $found = false;
                foreach ($this->itemsData[$item->transport_type] as $key => $existing) {
                    if ($existing['item_style'] === $item->item_style) {
                        $this->itemsData[$item->transport_type][$key] = [
                            'id' => $item->id,
                            'item_style' => $item->item_style,
                            'price_per_kg' => $item->price_per_kg,
                            'estimation_days' => $item->estimation_days,
                            'estimation_unit' => $item->estimation_unit ?? 'days',
                        ];
                        $found = true;
                        break;
                    }
                }
                if (! $found) {
                    $this->itemsData[$item->transport_type][] = [
                        'id' => $item->id,
                        'item_style' => $item->item_style,
                        'price_per_kg' => $item->price_per_kg,
                        'estimation_days' => $item->estimation_days,
                        'estimation_unit' => $item->estimation_unit ?? 'days',
                    ];
                }
            }
        }
    }

    private function initItemsData()
    {
        $defaultStyles = [
            'Electr & Magnet (No Brand)',
            'Electr & Magnet (With Brand)',
            'General Cargo (No Brand)',
            'General Cargo (With Brand)',
            'Power Bank, Battery, Cosmetic',
            'Screens, Electr & Mag (No Brand)',
            'Screens, Electr & Mag (With Brand)',
            'Health Care Products',
        ];

        foreach ($this->transportTypes as $type) {
            $this->itemsData[$type] = [];
            for ($i = 0; $i < 8; $i++) {
                $this->itemsData[$type][] = [
                    'item_style' => $defaultStyles[$i] ?? 'Style '.($i + 1),
                    'price_per_kg' => null,
                    'estimation_days' => null,
                    'estimation_unit' => 'days',
                ];
            }
        }
    }

    public function save(): void
    {
        $data = [
            'country_id' => $this->country->id,
            'currency' => $this->currency,
            'unit' => $this->unit,
            'air_arrival_time' => $this->air_arrival_time,
            'sea_arrival_time' => $this->sea_arrival_time,
            'train_arrival_time' => $this->train_arrival_time,
        ];

        $fee = \App\Models\ShippingFee::updateOrCreate(
            ['country_id' => $this->country->id],
            $data
        );

        // Sync items
        foreach ($this->transportTypes as $type) {
            foreach ($this->itemsData[$type] as $itemRow) {
                if (! empty($itemRow['item_style'])) {
                    $fee->items()->updateOrCreate(
                        ['transport_type' => $type, 'item_style' => $itemRow['item_style']],
                        [
                            'price_per_kg' => $itemRow['price_per_kg'],
                            'estimation_days' => $itemRow['estimation_days'],
                            'estimation_unit' => $itemRow['estimation_unit'] ?? 'days',
                        ]
                    );
                }
            }
        }

        $this->dispatch('show-success-toast', message: __('Shipping fees updated successfully.'));
        session()->flash('status', __('Shipping fees for :country updated successfully.', ['country' => $this->country->name]));

        $this->redirect(route('admin.shipping-fees.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.shipping-fee-edit');
    }
}
