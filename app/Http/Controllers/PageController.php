<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Display the privacy policy page.
     */
    public function privacy(): View
    {
        return view('pages.privacy');
    }

    /**
     * Display the refund policy page.
     */
    public function refund(): View
    {
        return view('pages.refund-policy');
    }

    /**
     * Display the shipping policy page.
     */
    public function shipping(): View
    {
        return view('pages.shipping-policy');
    }

    /**
     * Display the terms of service page.
     */
    public function terms(): View
    {
        return view('pages.terms');
    }

    /**
     * Display the support page.
     */
    public function support(): View
    {
        return view('pages.support');
    }
}
