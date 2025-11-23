<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\WelcomePageContent;

class WelcomePageContentController extends Controller
{
    public function index()
    {
        $contents = WelcomePageContent::all()->keyBy('key');
        return view('admin.welcome-content.index', compact('contents'));
    }

    public function update(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            WelcomePageContent::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'Content updated successfully.');
    }
}
