<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = $this->getCartItems();
        $subtotal = $cartItems->sum(function($item) {
            return $item->getSubtotal();
        });

        return view('cart.index', compact('cartItems', 'subtotal'));
    }

    public function list()
    {
        $cartItems = $this->getCartItems();
        $subtotal = $cartItems->sum(function($item) {
            return $item->getSubtotal();
        });

        $items = $cartItems->map(function($item) {
            // Robust image retrieval
            $product = $item->product;
            $imageUrl = 'https://placehold.co/100x120?text=No+Image'; // Default placeholder

            if ($product && $product->images->count() > 0) {
                $image = $product->images->sortBy('sort_order')->first();
                if ($image && !empty($image->image_path)) {
                     // Check if path already contains http (external) or needs storage prefix
                    $imageUrl = str_starts_with($image->image_path, 'http') 
                        ? $image->image_path 
                        : asset('storage/' . $image->image_path);
                }
            }

            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'name' => $product->name,
                'price' => number_format($product->getCurrentPrice()),
                'quantity' => $item->quantity,
                'image' => $imageUrl,
                'subtotal' => number_format($item->getSubtotal()),
                'slug' => $product->slug,
                'size' => $item->size,
                'color' => $item->color
            ];
        });

        return response()->json([
            'items' => $items,
            'subtotal' => number_format($subtotal),
            'count' => $cartItems->sum('quantity'),
            'status' => 'success'
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'size' => 'nullable|string',
            'color' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check stock
        if ($product->stock_quantity < $request->quantity) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Insufficient stock available'], 400);
            }
            return back()->with('error', 'Insufficient stock available');
        }

        $size = $request->size ?: null;
        $color = $request->color ?: null;

        $cartData = [
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'size' => $size,
            'color' => $color,
        ];

        $userId = Auth::id();
        $sessionId = session()->getId();
        $query = CartItem::query();

        if (Auth::check()) {
            $query->where('user_id', $userId);
            $cartData['user_id'] = $userId;
        } else {
            $query->where('session_id', $sessionId);
            $cartData['session_id'] = $sessionId;
        }

        $existingItem = $query->where('product_id', $request->product_id)
            ->where('size', $size)
            ->where('color', $color)
            ->first();

        if ($existingItem) {
            $existingItem->quantity += $request->quantity;
            $existingItem->save();
        } else {
            CartItem::create($cartData);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true, 
                'message' => 'Product added to cart!',
                'count' => $this->getCartItems()->count()
            ]);
        }
        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'size' => 'nullable|string',
            'color' => 'nullable|string',
        ]);

        $cartItem = CartItem::findOrFail($id);
        
        // Verify ownership
        if (Auth::check() && $cartItem->user_id !== Auth::id()) {
            abort(403);
        } elseif (!Auth::check() && $cartItem->session_id !== session()->getId()) {
            abort(403);
        }

        // Check stock
        if ($cartItem->product->stock_quantity < $request->quantity) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Insufficient stock'], 400);
            }
            return back()->with('error', 'Insufficient stock available');
        }

        $cartItem->quantity = $request->quantity;
        if($request->has('size')) $cartItem->size = $request->size;
        if($request->has('color')) $cartItem->color = $request->color;
        $cartItem->save();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Cart updated']);
        }
        return back()->with('success', 'Cart updated!');
    }

    public function remove(Request $request, $id)
    {
        $cartItem = CartItem::findOrFail($id);
        
        // Verify ownership
        if (Auth::check() && $cartItem->user_id !== Auth::id()) {
            abort(403);
        } elseif (!Auth::check() && $cartItem->session_id !== session()->getId()) {
            abort(403);
        }

        $cartItem->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Item removed']);
        }
        return back()->with('success', 'Item removed from cart!');
    }

    private function getCartItems()
    {
        if (Auth::check()) {
            return CartItem::where('user_id', Auth::id())
                ->with('product.images')
                ->get();
        } else {
            return CartItem::where('session_id', session()->getId())
                ->with('product.images')
                ->get();
        }
    }

    public function count()
    {
        $count = $this->getCartItems()->sum('quantity'); // returning sum of quantities is better than rows
        return response()->json(['count' => $count]);
    }
}
