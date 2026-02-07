<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

class ShippingFeeController extends Controller
{
    public function index()
    {
        return view('client.shipping-fees.index');
    }
}
