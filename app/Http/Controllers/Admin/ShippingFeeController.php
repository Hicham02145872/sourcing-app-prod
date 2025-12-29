<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShippingFeeController extends Controller
{
    public function index()
    {
        return view('admin.shipping-fees.index');
    }

    public function edit(\App\Models\Country $country)
    {
        $country->load('shippingFee');
        return view('admin.shipping-fees.edit', compact('country'));
    }

    public function update(Request $request, \App\Models\Country $country)
    {
        $request->validate([
            'sea_fee' => 'nullable|numeric|min:0',
            'train_fee' => 'nullable|numeric|min:0',
            'air_normal_fee' => 'nullable|numeric|min:0',
            'air_brand_fee' => 'nullable|numeric|min:0',
            'air_battery_fee' => 'nullable|numeric|min:0',
            'air_liquid_fee' => 'nullable|numeric|min:0',
        ]);

        $country->shippingFee()->updateOrCreate(
            ['country_id' => $country->id],
            $request->only([
                'sea_fee',
                'train_fee',
                'air_normal_fee',
                'air_brand_fee',
                'air_battery_fee',
                'air_liquid_fee',
            ])
        );

        return redirect()->route('admin.shipping-fees.index')
            ->with('success', 'Shipping fees updated successfully.');
    }
}
