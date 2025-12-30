<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ShippingCompanyController extends Controller
{
    public function index()
    {
        return view('admin.shipping-companies.index');
    }
}
