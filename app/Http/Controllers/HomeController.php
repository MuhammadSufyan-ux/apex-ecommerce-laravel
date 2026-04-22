<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $siteSettings = \App\Models\Setting::pluck('value', 'key')->toArray();
        $categories = Category::where('is_active', true)->get();
        $faqs = \App\Models\Faq::where('is_active', true)->orderBy('sort_order')->get();
        $sections = \App\Models\Section::with(['products' => function($q) {
            $q->where('is_active', true)->with('images');
        }])->orderBy('sort_order')->get();

        return view('home', compact('categories', 'sections', 'siteSettings', 'faqs'));
    }
}
