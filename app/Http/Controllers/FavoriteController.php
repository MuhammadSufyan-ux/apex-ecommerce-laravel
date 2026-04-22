<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $favorites = $this->getFavorites();
        return view('favorites.index', compact('favorites'));
    }

    /**
     * Get favorites as JSON for sidebar.
     */
    public function list()
    {
        $favorites = $this->getFavorites();
        // Transform for JS consumption
        $data = $favorites->map(function ($fav) {
            $product = $fav->product;
            $imageUrl = 'https://placehold.co/100x120?text=No+Image'; // Default placeholder

            if ($product && $product->images->count() > 0) {
                $image = $product->images->sortBy('sort_order')->first();
                if ($image && !empty($image->image_path)) {
                    $imageUrl = str_starts_with($image->image_path, 'http') 
                        ? $image->image_path 
                        : asset('storage/' . $image->image_path);
                }
            }
            
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => number_format($product->getCurrentPrice()),
                'image' => $imageUrl,
                'slug' => $product->slug,
            ];
        });
        
        return response()->json($data);
    }

    /**
     * Store a newly created favorite in storage.
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $productId = $request->product_id;
        $sessionId = Session::getId();
        $userId = Auth::id();

        if ($userId) {
            $existing = Favorite::where('user_id', $userId)
                ->where('product_id', $productId)
                ->first();
        } else {
            $existing = Favorite::where('session_id', $sessionId)
                ->where('product_id', $productId)
                ->first();
        }

        if ($existing) {
            $existing->delete();
            return response()->json(['status' => 'removed', 'message' => 'Removed from favorites']);
        } else {
            Favorite::create([
                'user_id' => $userId,
                'session_id' => $userId ? null : $sessionId,
                'product_id' => $productId,
            ]);
            return response()->json(['status' => 'added', 'message' => 'Added to favorites']);
        }
    }

    /**
     * Remove the specified favorite from storage.
     */
    public function destroy($id)
    {
        $favorite = Favorite::where('product_id', $id)->first();
         // Verify ownership logic would be needed here strictly speaking but for toggle it's handled.
         // Below I handle finding by product_id relevant to current user session
        
        $sessionId = Session::getId();
        $userId = Auth::id();

        if ($userId) {
            $favorite = Favorite::where('user_id', $userId)->where('product_id', $id)->first();
        } else {
            $favorite = Favorite::where('session_id', $sessionId)->where('product_id', $id)->first();
        }

        if ($favorite) {
            $favorite->delete();
            return response()->json(['status' => 'success', 'message' => 'Removed from favorites']);
        }

        return response()->json(['status' => 'error', 'message' => 'Item not found'], 404);
    }

    /**
     * Get count of favorites.
     */
    public function count()
    {
        $count = $this->getFavorites()->count();
        return response()->json(['count' => $count]);
    }

    /**
     * Helper to get favorites based on auth/session.
     */
    private function getFavorites()
    {
        if (Auth::check()) {
            return Favorite::where('user_id', Auth::id())
                ->with('product.images')
                ->latest()
                ->get();
        } else {
            return Favorite::where('session_id', Session::getId())
                ->with('product.images')
                ->latest()
                ->get();
        }
    }
}
