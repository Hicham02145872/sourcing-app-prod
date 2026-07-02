<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class ShippingFeeController extends Controller
{
    public function index(string $locale)
    {
        return view('client.shipping-fees.index');
    }

    public function getShippingFee(string $locale, Country $country, Request $request)
    {
        $transportType = $request->query('transport', 'air');
        $sourcing = $request->query('sourcing', 'china');

        $country->load(['shippingFee.items']);

        if (!$country->shippingFee) {
            return response()->json(['fee' => null]);
        }

        $fee = $country->shippingFee;

        $items = $fee->items
            ->where('transport_type', $transportType)
            ->values()
            ->map(fn($item) => [
                'id' => $item->id,
                'item_style' => $item->item_style,
                'price_per_kg' => $item->price_per_kg,
                'price_per_kg_dubai' => $item->price_per_kg_dubai,
                'price_per_kg_china_to_dubai' => $item->price_per_kg_china_to_dubai,
                'price_per_kg_dubai_to_africa' => $item->price_per_kg_dubai_to_africa,
                'currency' => $fee->currency ?? 'USD',
                'unit' => $fee->getUnitForTransport($transportType),
                'estimation_days' => $item->estimation_days,
                'estimation_unit' => $item->estimation_unit ?? 'days',
            ]);

        $arrivalTime = match ($transportType) {
            'air' => $fee->air_arrival_time,
            'sea' => $fee->sea_arrival_time,
            'train' => $fee->train_arrival_time,
            default => null,
        };

        return response()->json([
            'country_id' => $country->id,
            'country_name' => $country->name,
            'country_code' => $country->code,
            'currency' => $fee->currency ?? 'USD',
            'unit' => $fee->getUnitForTransport($transportType),
            'arrival_time' => $arrivalTime,
            'sourcing' => $sourcing,
            'transport' => $transportType,
            'items' => $items,
        ]);
    }

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

    protected function calculateIndirectPrice($item): ?float
    {
        $chinaToDubai = $item->price_per_kg_china_to_dubai;
        $dubaiToAfrica = $item->price_per_kg_dubai_to_africa;

        if (!is_null($chinaToDubai) && !is_null($dubaiToAfrica)) {
            return (float) $chinaToDubai + (float) $dubaiToAfrica;
        }

        return $item->price_per_kg;
    }

    public function getRatesForPopup(string $locale, Country $country, Request $request)
    {
        $transportType = $request->query('transport', 'air');

        $country->load(['shippingFee.items']);

        if (!$country->shippingFee) {
            return response()->json([
                'success' => false,
                'message' => 'No shipping data available for this country.',
            ]);
        }

        $fee = $country->shippingFee;

        // Direct Shipping
        $directItems = $fee->items
            ->filter(fn ($i) => strtolower((string) $i->transport_type) === $transportType)
            ->filter(fn ($i) => !$this->isUaeHubItemStyle($i->item_style))
            ->values();

        // Indirect Shipping (UAE/Dubai)
        $indirectItems = $fee->items->filter(
            fn ($i) => strtolower((string) $i->transport_type) === 'train'
        );
        $uaeMisplaced = $fee->items->filter(function ($i) use ($transportType) {
            $t = strtolower((string) $i->transport_type);
            return ($t === $transportType) && $this->isUaeHubItemStyle($i->item_style);
        });
        $indirectItems = $indirectItems->merge($uaeMisplaced)->unique('id')->values();

        $directFormatted = $directItems->map(fn($item) => [
            'id' => $item->id,
            'item_style' => $item->item_style,
            'price_per_kg' => $item->price_per_kg,
            'price_per_kg_dubai' => $item->price_per_kg_dubai,
            'price_per_kg_china_to_dubai' => $item->price_per_kg_china_to_dubai,
            'price_per_kg_dubai_to_africa' => $item->price_per_kg_dubai_to_africa,
            'estimation_days' => $item->estimation_days,
            'estimation_unit' => $item->estimation_unit ?? 'days',
        ]);

        $indirectFormatted = $indirectItems->map(fn($item) => [
            'id' => $item->id,
            'item_style' => $item->item_style,
            'price_per_kg' => $this->calculateIndirectPrice($item),
            'price_per_kg_dubai' => $item->price_per_kg_dubai,
            'price_per_kg_china_to_dubai' => $item->price_per_kg_china_to_dubai,
            'price_per_kg_dubai_to_africa' => $item->price_per_kg_dubai_to_africa,
            'estimation_days' => $item->estimation_days,
            'estimation_unit' => $item->estimation_unit ?? 'days',
        ]);

        $indirectArrivalTime = $fee->train_arrival_time ?: ($transportType === 'air' ? $fee->air_arrival_time : $fee->sea_arrival_time);
        $indirectUnit = ($transportType === 'sea') ? $fee->getUnitForTransport('sea') : ($fee->getUnitForTransport('train') ?: $fee->getUnitForTransport('air'));

        return response()->json([
            'success' => true,
            'country_id' => $country->id,
            'country_name' => $country->name,
            'country_code' => $country->code,
            'currency' => $fee->currency ?? 'USD',
            'direct' => [
                'transport' => $transportType,
                'unit' => $fee->getUnitForTransport($transportType),
                'arrival_time' => $transportType === 'air' ? $fee->air_arrival_time : $fee->sea_arrival_time,
                'items' => $directFormatted,
            ],
            'indirect' => [
                'transport' => $transportType,
                'unit' => $indirectUnit,
                'arrival_time' => $indirectArrivalTime,
                'items' => $indirectFormatted,
            ],
        ]);
    }
}
