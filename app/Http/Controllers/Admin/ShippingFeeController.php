<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\ShippingFeesFromAirFreightDDPImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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

    public function importForm()
    {
        return view('admin.shipping-fees.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
            'currency' => 'nullable|string|size:3',
        ]);

        $import = new ShippingFeesFromAirFreightDDPImport;
        $import->setCurrency($request->input('currency', 'USD'));
        Excel::import($import, $request->file('file'));

        return redirect()
            ->route('admin.shipping-fees.import')
            ->with('import_result', [
                'imported' => $import->getImportedCount(),
                'skipped' => $import->getSkippedCount(),
                'errors' => $import->getErrors(),
            ]);
    }
}
