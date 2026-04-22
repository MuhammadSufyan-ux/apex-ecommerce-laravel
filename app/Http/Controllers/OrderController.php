<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items', 'items.product'])
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        // Ensure user can only view their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items', 'items.product']);
        
        return view('orders.show', compact('order'));
    }
}
