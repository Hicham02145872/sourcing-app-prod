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
        $transportType = $request->query('transport', 'air_direct');
        $sourcing = $request->query('sourcing', 'china');

        $country->load(['shippingFee.items']);

        if (!$country->shippingFee) {
            return response()->json(['fee' => null]);
        }

        $fee = $country->shippingFee;
        
        $dbTransportType = $transportType;
        if ($transportType === 'air' && $sourcing === 'china') {
            $dbTransportType = 'air_direct';
        } elseif ($transportType === 'air' && $sourcing === 'dubai') {
            $dbTransportType = 'air_indirect';
        }

        if ($dbTransportType === 'air_direct' && !($fee->is_air_direct_visible ?? true)) {
            return response()->json(['fee' => null]);
        }
        if ($dbTransportType === 'air_indirect' && !($fee->is_air_indirect_visible ?? true)) {
            return response()->json(['fee' => null]);
        }
        if ($dbTransportType === 'sea' && !($fee->is_sea_visible ?? true)) {
            return response()->json(['fee' => null]);
        }

        $legacyTransportTypes = match ($dbTransportType) {
            'air_direct' => ['air_direct', 'air'],
            'air_indirect' => ['air_indirect', 'train'],
            default => [$dbTransportType],
        };

        $items = $fee->items
            ->whereIn('transport_type', $legacyTransportTypes)
            ->values()
            ->map(fn($item) => [
                'id' => $item->id,
                'item_style' => $item->item_style,
                'price_per_kg' => $item->price_per_kg,
                'price_per_kg_dubai' => $item->price_per_kg_dubai,
                'price_per_kg_china_to_dubai' => $item->price_per_kg_china_to_dubai,
                'price_per_kg_dubai_to_africa' => $item->price_per_kg_dubai_to_africa,
                'currency' => $fee->currency ?? 'USD',
                'unit' => $fee->getUnitForTransport($dbTransportType),
                'estimation_days' => $item->estimation_days,
                'estimation_unit' => $item->estimation_unit ?? 'days',
            ]);

        $arrivalTime = match ($dbTransportType) {
            'air_direct' => $fee->air_direct_arrival_time ?? $fee->air_arrival_time,
            'sea' => $fee->sea_arrival_time,
            'air_indirect' => $fee->air_indirect_arrival_time ?? $fee->train_arrival_time,
            default => null,
        };

        return response()->json([
            'country_id' => $country->id,
            'country_name' => $country->name,
            'country_code' => $country->code,
            'currency' => $fee->currency ?? 'USD',
            'unit' => $fee->getUnitForTransport($dbTransportType),
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

        $isDirect = (bool) ($country->is_direct ?? false);

        // Direct Shipping
        $directTransportType = $transportType === 'air' ? 'air_direct' : 'sea';
        $directTransportTypes = $directTransportType === 'air_direct' ? ['air_direct', 'air'] : ['sea'];
        $directItems = collect();
        if (($directTransportType === 'air_direct' && ($fee->is_air_direct_visible ?? true)) || 
            ($directTransportType === 'sea' && ($fee->is_sea_visible ?? true))) {
            
            $directItems = $fee->items
                ->filter(fn ($i) => in_array(strtolower((string) $i->transport_type), $directTransportTypes))
                ->values();
        }

        // Indirect Shipping (UAE/Dubai) — only available for air transport
        $indirectItems = collect();
        if ($transportType === 'air' && ($fee->is_air_indirect_visible ?? true)) {
            $indirectItems = $fee->items->filter(
                fn ($i) => in_array(strtolower((string) $i->transport_type), ['air_indirect', 'train'])
            )->values();
        }

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

        $indirectArrivalTime = $fee->air_indirect_arrival_time ?? $fee->train_arrival_time;
        $indirectUnit = $fee->getUnitForTransport('air_indirect');

        return response()->json([
            'success' => true,
            'country_id' => $country->id,
            'country_name' => $country->name,
            'country_code' => $country->code,
            'currency' => $fee->currency ?? 'USD',
            'direct' => [
                'transport' => $transportType,
                'unit' => $fee->getUnitForTransport($directTransportType),
                'arrival_time' => $directTransportType === 'air_direct' ? ($fee->air_direct_arrival_time ?? $fee->air_arrival_time) : $fee->sea_arrival_time,
                'items' => $directFormatted,
            ],
            'indirect' => [
                'transport' => $transportType,
                'unit' => $indirectUnit,
                'arrival_time' => $isDirect ? null : $indirectArrivalTime,
                'items' => $isDirect ? [] : $indirectFormatted,
            ],
        ]);
    }
}
