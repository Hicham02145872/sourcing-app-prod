<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ShippingFeeController extends Controller
{
    public function index()
    {
        return view('admin.shipping-fees.index');
    }
}
