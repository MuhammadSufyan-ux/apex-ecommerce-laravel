<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function storeLocator()
    {
        return view('pages.store-locator');
    }

    public function shippingPolicy()
    {
        return view('pages.shipping-policy');
    }

    public function returnsPolicy()
    {
        return view('pages.returns');
    }

    public function privacyPolicy()
    {
        return view('pages.privacy');
    }
}
