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
}
