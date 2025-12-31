<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ShippingFeeController extends Controller
{
    public function index()
    {
        return view('admin.shipping-fees.index');
    }

    public function edit(\App\Models\Country $shipping_fee)
    {
        // $shipping_fee is actually the Country model because of the resource naming
        return view('admin.shipping-fees.edit', ['country' => $shipping_fee]);
    }
}
