<?php

namespace App\Livewire\Admin;

use App\Models\Country;
use App\Models\ShippingFee;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ShippingFeeEdit extends Component
{
    public Country $country;

    public ?ShippingFee $shippingFee = null;

    public $currency = 'USD';

    public array $transportUnits = [
        'air_direct' => 'kg',
        'sea' => 'CBM',
        'air_indirect' => 'kg',
    ];

    public $air_direct_arrival_time = '7-9';

    public $sea_arrival_time = '30-45';

    public $air_indirect_arrival_time = '15-20';

    public $china_to_dubai_duration = '';

    public $dubai_to_destination_duration = '';

    public $china_to_dubai_currency = 'CNY';

    public $dubai_to_destination_currency = 'USD';

    public array $itemsData = [
        'air_direct' => [],
        'sea' => [],
        'air_indirect' => [],
    ];

    public $isDirect = false;

    public $is_air_direct_visible = true;
    public $is_air_indirect_visible = true;
    public $is_sea_visible = true;

    public array $transportTypes = ['air_direct', 'sea', 'air_indirect'];

    public function getCurrenciesProperty(): array
    {
        return config('currencies', []);
    }

    public function mount(Country $country)
    {
        $this->country = rescue(
            fn () => $country->load(['shippingFee.items']),
            $country
        );
        $this->isDirect = (bool) ($this->country->is_direct ?? false);
        $this->initItemsData();

        if ($this->country->shippingFee) {
            $fee = $this->country->shippingFee;
            $this->shippingFee = $fee;
            $allowed = array_keys($this->currencies);
            $cur = strtoupper((string) ($fee->currency ?? 'USD'));
            $this->currency = in_array($cur, $allowed, true) ? $cur : 'USD';
            $legacyUnit = $fee->unit ?? 'kg';
            $this->transportUnits = [
                'air_direct' => $fee->air_direct_unit ?? $fee->air_unit ?? $legacyUnit,
                'sea' => $fee->sea_unit ?? 'CBM',
                'air_indirect' => $fee->air_indirect_unit ?? $fee->train_unit ?? $legacyUnit,
            ];

            $this->air_direct_arrival_time = $fee->air_direct_arrival_time ?? $fee->air_arrival_time ?? '7-9';
            $this->sea_arrival_time = $fee->sea_arrival_time ?? '30-45';
            $this->air_indirect_arrival_time = $fee->air_indirect_arrival_time ?? $fee->train_arrival_time ?? '15-20';
            $this->china_to_dubai_duration = $fee->china_to_dubai_duration ?? '';
            $this->dubai_to_destination_duration = $fee->dubai_to_destination_duration ?? '';
            $this->china_to_dubai_currency = $fee->china_to_dubai_currency ?? 'CNY';
            $this->dubai_to_destination_currency = $fee->dubai_to_destination_currency ?? 'USD';
            
            $this->is_air_direct_visible = $fee->is_air_direct_visible ?? true;
            $this->is_air_indirect_visible = $fee->is_air_indirect_visible ?? true;
            $this->is_sea_visible = $fee->is_sea_visible ?? true;

            foreach ($fee->items as $item) {
                $transportType = match ($item->transport_type) {
                    'air' => 'air_direct',
                    'train' => 'air_indirect',
                    default => $item->transport_type,
                };
                $found = false;
                foreach ($this->itemsData[$transportType] as $key => $existing) {
                    if ($existing['item_style'] === $item->item_style) {
                        $this->itemsData[$transportType][$key] = [
                            'id' => $item->id,
                            'item_style' => $item->item_style,
                            'price_per_kg' => $item->price_per_kg,
                            'price_per_kg_dubai' => $item->price_per_kg_dubai,
                            'price_per_kg_china_to_dubai' => $item->price_per_kg_china_to_dubai,
                            'price_per_kg_dubai_to_africa' => $item->price_per_kg_dubai_to_africa,
                            'estimation_days' => $item->estimation_days,
                            'estimation_unit' => $item->estimation_unit ?? 'days',
                            '_deleted' => false,
                        ];
                        $found = true;
                        break;
                    }
                }
                if (! $found) {
                    $this->itemsData[$transportType][] = [
                        'id' => $item->id,
                        'item_style' => $item->item_style,
                        'price_per_kg' => $item->price_per_kg,
                        'price_per_kg_dubai' => $item->price_per_kg_dubai,
                        'price_per_kg_china_to_dubai' => $item->price_per_kg_china_to_dubai,
                        'price_per_kg_dubai_to_africa' => $item->price_per_kg_dubai_to_africa,
                        'estimation_days' => $item->estimation_days,
                        'estimation_unit' => $item->estimation_unit ?? 'days',
                        '_deleted' => false,
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
                    'id' => null,
                    'item_style' => $defaultStyles[$i] ?? 'Style '.($i + 1),
                    'price_per_kg' => null,
                    'price_per_kg_dubai' => null,
                    'price_per_kg_china_to_dubai' => null,
                    'price_per_kg_dubai_to_africa' => null,
                    'estimation_days' => null,
                    'estimation_unit' => 'days',
                    '_deleted' => false,
                ];
            }
        }
    }

    public function addCategory(string $transportType): void
    {
        if (! in_array($transportType, $this->transportTypes, true)) {
            return;
        }

        $this->itemsData[$transportType][] = [
            'id' => null,
            'item_style' => '',
            'price_per_kg' => null,
            'price_per_kg_dubai' => null,
            'price_per_kg_china_to_dubai' => null,
            'price_per_kg_dubai_to_africa' => null,
            'estimation_days' => null,
            'estimation_unit' => 'days',
            '_deleted' => false,
        ];
    }

    public function removeCategory(string $transportType, int $index): void
    {
        if (! in_array($transportType, $this->transportTypes, true)) {
            return;
        }

        if (! isset($this->itemsData[$transportType][$index])) {
            return;
        }

        if (! empty($this->itemsData[$transportType][$index]['id'])) {
            $this->itemsData[$transportType][$index]['_deleted'] = true;

            return;
        }

        unset($this->itemsData[$transportType][$index]);
        $this->itemsData[$transportType] = array_values($this->itemsData[$transportType]);

        if (empty($this->itemsData[$transportType])) {
            $this->itemsData[$transportType][] = [
                'id' => null,
                'item_style' => '',
                'price_per_kg' => null,
                'price_per_kg_dubai' => null,
                'price_per_kg_china_to_dubai' => null,
                'price_per_kg_dubai_to_africa' => null,
                'estimation_days' => null,
                'estimation_unit' => 'days',
                '_deleted' => false,
            ];
        }
    }

    public function restoreCategory(string $transportType, int $index): void
    {
        if (! in_array($transportType, $this->transportTypes, true)) {
            return;
        }

        if (! isset($this->itemsData[$transportType][$index])) {
            return;
        }

        $this->itemsData[$transportType][$index]['_deleted'] = false;
    }

    private function hasDuplicateCategories(): bool
    {
        foreach ($this->transportTypes as $type) {
            $seenStyles = [];

            foreach ($this->itemsData[$type] as $itemRow) {
                if (! empty($itemRow['_deleted'])) {
                    continue;
                }

                $style = trim((string) ($itemRow['item_style'] ?? ''));

                if ($style === '') {
                    continue;
                }

                $normalizedStyle = strtolower($style);

                if (isset($seenStyles[$normalizedStyle])) {
                    return true;
                }

                $seenStyles[$normalizedStyle] = true;
            }
        }

        return false;
    }

    public function save(): void
    {
        $this->resetErrorBag();

        $this->validate([
            'currency' => ['required', 'string', 'size:3', Rule::in(array_keys($this->currencies))],
        ]);

        if ($this->hasDuplicateCategories()) {
            $this->addError('duplicate_categories', __('Each transport type must have unique category names for this country.'));
            $this->dispatch('show-error-toast', message: __('Please remove duplicate categories before saving.'));

            return;
        }

        $data = [
            'country_id' => $this->country->id,
            'currency' => $this->currency,
            // Legacy unit kept for backward compatibility with existing integrations.
            'unit' => $this->transportUnits['air_direct'] ?? 'kg',
            'air_direct_unit' => $this->transportUnits['air_direct'] ?? 'kg',
            'sea_unit' => $this->transportUnits['sea'] ?? 'CBM',
            'air_indirect_unit' => $this->transportUnits['air_indirect'] ?? 'kg',
            'air_direct_arrival_time' => $this->air_direct_arrival_time,
            'sea_arrival_time' => $this->sea_arrival_time,
            'air_indirect_arrival_time' => $this->air_indirect_arrival_time,
            'china_to_dubai_duration' => $this->china_to_dubai_duration ?: null,
            'dubai_to_destination_duration' => $this->dubai_to_destination_duration ?: null,
            'china_to_dubai_currency' => $this->china_to_dubai_currency ?: 'CNY',
            'dubai_to_destination_currency' => $this->dubai_to_destination_currency ?: 'USD',
            'is_air_direct_visible' => $this->is_air_direct_visible,
            'is_air_indirect_visible' => $this->is_air_indirect_visible,
            'is_sea_visible' => $this->is_sea_visible,
        ];

        $fee = \App\Models\ShippingFee::updateOrCreate(
            ['country_id' => $this->country->id],
            $data
        );

        // Sync items by id and remove deleted rows from the form.
        foreach ($this->transportTypes as $type) {
            $keptItemIds = [];

            foreach ($this->itemsData[$type] as $itemRow) {
                if (! empty($itemRow['_deleted'])) {
                    continue;
                }

                $itemStyle = trim((string) ($itemRow['item_style'] ?? ''));

                if ($itemStyle === '') {
                    continue;
                }

                $payload = [
                    'transport_type' => $type,
                    'item_style' => $itemStyle,
                    'price_per_kg' => ($itemRow['price_per_kg'] ?? '') === '' ? null : $itemRow['price_per_kg'],
                    'price_per_kg_dubai' => ($itemRow['price_per_kg_dubai'] ?? '') === '' ? null : $itemRow['price_per_kg_dubai'],
                    'price_per_kg_china_to_dubai' => ($itemRow['price_per_kg_china_to_dubai'] ?? '') === '' ? null : $itemRow['price_per_kg_china_to_dubai'],
                    'price_per_kg_dubai_to_africa' => ($itemRow['price_per_kg_dubai_to_africa'] ?? '') === '' ? null : $itemRow['price_per_kg_dubai_to_africa'],
                    'estimation_days' => ($itemRow['estimation_days'] ?? '') === '' ? null : $itemRow['estimation_days'],
                    'estimation_unit' => $itemRow['estimation_unit'] ?? 'days',
                ];

                if (! empty($itemRow['id'])) {
                    $existingItem = $fee->items()
                        ->where('id', $itemRow['id'])
                        ->where('transport_type', $type)
                        ->first();

                    if ($existingItem) {
                        $existingItem->update($payload);
                        $keptItemIds[] = $existingItem->id;

                        continue;
                    }
                }

                $createdItem = $fee->items()->create($payload);
                $keptItemIds[] = $createdItem->id;
            }

            if (empty($keptItemIds)) {
                $fee->items()->where('transport_type', $type)->delete();
            } else {
                $fee->items()
                    ->where('transport_type', $type)
                    ->whereNotIn('id', $keptItemIds)
                    ->delete();
            }
        }

        $this->country->update(['is_direct' => $this->isDirect]);

        $this->dispatch('show-success-toast', message: __('Shipping fees updated successfully.'));
        session()->flash('status', __('Shipping fees for :country updated successfully.', ['country' => $this->country->name]));

        $this->redirect(route('admin.shipping-fees.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.shipping-fee-edit');
    }
}
