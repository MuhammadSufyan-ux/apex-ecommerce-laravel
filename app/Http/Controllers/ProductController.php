<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)->with(['category', 'images']);

        // 1. HARD CATEGORY FILTERING (Support Slug, ID, or Name)
        if ($request->filled('category') || $request->filled('subcategory')) {
            $categoryIdentifier = $request->category ?: $request->subcategory;
            
            $query->whereHas('category', function($q) use ($categoryIdentifier) {
                if (is_numeric($categoryIdentifier)) {
                    $q->where('id', $categoryIdentifier);
                } else {
                    $q->where(function($subQ) use ($categoryIdentifier) {
                        $subQ->where('slug', $categoryIdentifier)
                             ->orWhere('name', 'LIKE', $categoryIdentifier)
                             ->orWhere('name', 'LIKE', str_replace('-', ' ', $categoryIdentifier));
                    });
                }
            });
        }

        // 2. SMART PRICE RANGE (Prioritizes Sale Price if available)
        if ($request->filled('min_price')) {
            $query->whereRaw('COALESCE(sale_price, price) >= ?', [(float)$request->min_price]);
        }
        if ($request->filled('max_price')) {
            $query->whereRaw('COALESCE(sale_price, price) <= ?', [(float)$request->max_price]);
        }

        // 3. ROBUST SIZE FILTERING (JSON Column Support)
        if ($request->filled('size')) {
            $sizes = (array)$request->size;
            $query->where(function($q) use ($sizes) {
                foreach ($sizes as $size) {
                    $q->orWhereJsonContains('sizes', $size);
                }
            });
        }

        // 4. ROBUST COLOR FILTERING (JSON Column Support)
        if ($request->filled('color')) {
            $colors = (array)$request->color;
            $query->where(function($q) use ($colors) {
                foreach ($colors as $color) {
                    $q->orWhereJsonContains('colors', $color);
                }
            });
        }

        // 5. DEEP KEYWORD SEARCH (Name, SKU, Description, Category)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('category', function($catQ) use ($search) {
                      $catQ->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // 6. ADVANCED SORTING
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderByRaw('COALESCE(sale_price, price) ASC');
                break;
            case 'price_high':
                $query->orderByRaw('COALESCE(sale_price, price) DESC');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'images'])
            ->firstOrFail();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with(['category', 'images'])
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return response()->json([]);
        }

        $products = Product::where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhereHas('category', function($catQ) use ($query) {
                      $catQ->where('name', 'like', "%{$query}%");
                  });
            })
            ->with(['images', 'category'])
            ->take(6)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug, // Ensure slug is returned for linking
                    'price' => number_format($product->price, 2),
                    'image' => $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : 'https://placehold.co/100x120?text=No+Image',
                    'category' => $product->category->name ?? ''
                ];
            });

        return response()->json($products);
    }
}
