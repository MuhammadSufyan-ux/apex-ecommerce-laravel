<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category', 'images');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%")
                  ->orWhereHas('category', function($cq) use ($search) {
                      $cq->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $products = $query->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')->with('children.children')->get();
        // Fetch ALL categories sorted by name to avoid missing any
        // The previous filtering might have hidden the actual categories used by frontend
        $allCategories = Category::orderBy('name', 'asc')->get();
        
        return view('admin.products.create', compact('categories', 'allCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'nullable|string|max:50|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'duppata_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'shalwar_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'bazoo_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'popup_start_date' => 'nullable|date',
            'popup_end_date' => 'nullable|date|after:popup_start_date',
        ]);

        $data = $request->all();

        $data['sku'] = $request->sku ?: 'S4-' . strtoupper(Str::random(8));
        $data['slug'] = Str::slug($request->name) . '-' . time();
        $data['is_featured'] = $request->has('is_featured');
        $data['is_new'] = $request->has('is_new');
        $data['is_active'] = $request->has('is_active');
        $data['is_on_sale'] = $request->has('is_on_sale');
        $data['is_coming_soon'] = $request->has('is_coming_soon');
        $data['is_popup'] = $request->has('is_popup');
        // discount_badge comes directly from request as string
        
        // Handle JSON fields
        $data['sizes'] = $request->sizes ? explode(',', $request->sizes) : [];
        $data['colors'] = $request->colors ? explode(',', $request->colors) : [];

        // Handle specific cloth component images
        if ($request->hasFile('duppata_image')) {
            $data['duppata_image'] = $request->file('duppata_image')->store('products/details', 'public');
        }
        if ($request->hasFile('shalwar_image')) {
            $data['shalwar_image'] = $request->file('shalwar_image')->store('products/details', 'public');
        }
        if ($request->hasFile('bazoo_image')) {
            $data['bazoo_image'] = $request->file('bazoo_image')->store('products/details', 'public');
        }

        // Handle Dynamic Accordion Details
        if ($request->has('detail_titles') && is_array($request->detail_titles)) {
            $dynamicDetails = [];
            foreach ($request->detail_titles as $index => $title) {
                if (!empty($title) && !empty($request->detail_contents[$index])) {
                    $dynamicDetails[] = [
                        'title' => $title,
                        'content' => $request->detail_contents[$index]
                    ];
                }
            }
            $data['dynamic_details'] = $dynamicDetails;
        }

        $product = Product::create($data);

        // Sync sections (many-to-many relationship)
        if ($request->has('sections')) {
            $product->sections()->sync($request->sections);
        }

        // Handle Multiple Gallery Images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $index === 0,
                    'sort_order' => $index
                ]);
            }
        }

        \App\Models\ActivityLog::log('product', 'created', "Product '{$product->name}' (SKU: {$product->sku}) added to inventory.");

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    public function edit(Product $product)
    {
        $categories = Category::whereNull('parent_id')->with('children.children')->get();
        // Fetch ALL categories sorted by name
        $allCategories = Category::orderBy('name', 'asc')->get();
        
        return view('admin.products.edit', compact('product', 'categories', 'allCategories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'nullable|string|max:50|unique:products,sku,' . $product->id,
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'discount_badge' => 'nullable|string',
            'popup_start_date' => 'nullable|date',
            'popup_end_date' => 'nullable|date|after:popup_start_date',
        ]);

        $data = $request->all();

        $data['sku'] = $request->sku ?: ($product->sku ?: 'S4-' . strtoupper(Str::random(8)));
        $data['is_featured'] = $request->has('is_featured');
        $data['is_new'] = $request->has('is_new');
        $data['is_active'] = $request->has('is_active');
        $data['is_on_sale'] = $request->has('is_on_sale');
        $data['is_coming_soon'] = $request->has('is_coming_soon');
        $data['is_popup'] = $request->has('is_popup');
        // discount_badge comes directly from request as string

        // Handle JSON fields
        $data['sizes'] = $request->sizes ? (is_array($request->sizes) ? $request->sizes : explode(',', $request->sizes)) : [];
        $data['colors'] = $request->colors ? (is_array($request->colors) ? $request->colors : explode(',', $request->colors)) : [];

        // Handle specific cloth component images
        if ($request->hasFile('duppata_image')) {
            if ($product->duppata_image) Storage::disk('public')->delete($product->duppata_image);
            $data['duppata_image'] = $request->file('duppata_image')->store('products/details', 'public');
        }
        if ($request->hasFile('shalwar_image')) {
            if ($product->shalwar_image) Storage::disk('public')->delete($product->shalwar_image);
            $data['shalwar_image'] = $request->file('shalwar_image')->store('products/details', 'public');
        }
        if ($request->hasFile('bazoo_image')) {
            if ($product->bazoo_image) Storage::disk('public')->delete($product->bazoo_image);
            $data['bazoo_image'] = $request->file('bazoo_image')->store('products/details', 'public');
        }

        // Handle Dynamic Accordion Details
        if ($request->has('detail_titles') && is_array($request->detail_titles)) {
            $dynamicDetails = [];
            foreach ($request->detail_titles as $index => $title) {
                if (!empty($title) && !empty($request->detail_contents[$index])) {
                    $dynamicDetails[] = [
                        'title' => $title,
                        'content' => $request->detail_contents[$index]
                    ];
                }
            }
            $data['dynamic_details'] = $dynamicDetails;
        } else {
            $data['dynamic_details'] = [];
        }

        $product->update($data);

        // Low Stock Alert Check
        try {
            $threshold = \App\Models\Setting::getValue('low_stock_threshold', 5);
            if ($product->stock_quantity <= $threshold) {
                \App\Services\NotificationService::send([
                    'type' => 'email',
                    'event' => 'low_stock_alert',
                    'recipient' => \App\Models\Setting::getValue('admin_notify_email'),
                    'recipient_type' => 'admin',
                    'message' => "INVENTORY ALERT: Product '{$product->name}' is running low (Current: {$product->stock_quantity})."
                ]);
            }
        } catch (\Exception $ne) {
            \Illuminate\Support\Facades\Log::warning('Stock alert failed: ' . $ne->getMessage());
        }

        // Sync sections (many-to-many relationship)
        if ($request->has('sections')) {
            $product->sections()->sync($request->sections);
        } else {
            $product->sections()->detach(); // Remove all if none selected
        }

        // Add new images to gallery if uploaded
        if ($request->hasFile('images')) {
            $lastOrder = $product->images()->max('sort_order') ?? -1;
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => false,
                    'sort_order' => $lastOrder + $index + 1
                ]);
            }
        }

        \App\Models\ActivityLog::log('product', 'updated', "Product '{$product->name}' details were modified.");

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        // Delete gallery images
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        // Delete component images
        if ($product->duppata_image) Storage::disk('public')->delete($product->duppata_image);
        if ($product->shalwar_image) Storage::disk('public')->delete($product->shalwar_image);
        if ($product->bazoo_image) Storage::disk('public')->delete($product->bazoo_image);

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }
    public function duplicate(Product $product)
    {
        $newProduct = $product->replicate();
        $newProduct->name = $product->name . ' (Copy)';
        $newProduct->sku = 'S4-' . strtoupper(Str::random(8));
        $newProduct->slug = Str::slug($newProduct->name) . '-' . time();
        $newProduct->is_active = false; // Set as draft initially
        $newProduct->save();

        // Duplicate images
        foreach ($product->images as $image) {
            ProductImage::create([
                'product_id' => $newProduct->id,
                'image_path' => $image->image_path,
                'is_primary' => $image->is_primary,
                'sort_order' => $image->sort_order
            ]);
        }

        // Duplicate sections
        $newProduct->sections()->sync($product->sections->pluck('id'));

        return redirect()->route('admin.products.index')->with('success', 'Product duplicated successfully as draft!');
    }
}
