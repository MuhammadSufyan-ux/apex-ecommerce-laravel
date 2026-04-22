<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = $this->getCartItems();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $subtotal = $cartItems->sum(function($item) {
            return $item->getSubtotal();
        });

        $shippingCost = 200; // Fixed shipping cost
        $tax = $subtotal * 0.0; // No tax for now
        $total = $subtotal + $shippingCost + $tax;

        // Get active payment gateways configured by admin
        $paymentGateways = PaymentGateway::getActiveGateways();

        return view('checkout.index', compact('cartItems', 'subtotal', 'shippingCost', 'tax', 'total', 'paymentGateways'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'payment_method' => 'required|string',
        ]);

        if (Auth::check() && Auth::user()->status === 'blocked') {
            return redirect()->route('cart.index')->with('error', 'OPERATION REJECTED: Your account is protocol-blocked from placing new orders.');
        }

        $cartItems = $this->getCartItems();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        // Calculate totals
        $subtotal = $cartItems->sum(function($item) {
            return $item->getSubtotal();
        });

        $shippingCost = $subtotal >= 5000 ? 0 : 200;
        $tax = 0;
        $total = $subtotal + $shippingCost + $tax;

        DB::beginTransaction();
        
        try {
            // Create order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::id(),
                'customer_name' => $request->first_name . ' ' . $request->last_name,
                'customer_email' => $request->email,
                'customer_phone' => $request->phone,
                'shipping_address' => $request->address,
                'shipping_city' => $request->city,
                'shipping_postal_code' => $request->postal_code,
                'shipping_country' => 'Pakistan',
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_cost' => $shippingCost,
                'discount' => 0,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'notes' => $request->notes,
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'product_sku' => $item->product->sku ?? 'N/A',
                    'size' => $item->size ?? 'N/A',
                    'color' => $item->color ?? 'N/A',
                    'quantity' => $item->quantity,
                    'price' => $item->product->getCurrentPrice(),
                    'subtotal' => $item->getSubtotal(),
                ]);
            }

            // Clear cart from DB
            if (Auth::check()) {
                CartItem::where('user_id', Auth::id())->delete();
            } else {
                CartItem::where('session_id', session()->getId())->delete();
            }

            // Clear session cart too if any
            session()->forget('cart');

            DB::commit();

            // Trigger Notifications
            try {
                // To Customer
                \App\Services\NotificationService::send([
                    'type' => 'email',
                    'event' => 'order_confirmation',
                    'recipient' => $order->customer_email,
                    'recipient_type' => 'customer',
                    'message' => "Order #{$order->order_number} confirmed. Your luxury items are being processed."
                ]);

                // To Admin
                \App\Services\NotificationService::send([
                    'type' => 'email',
                    'event' => 'new_order_alert',
                    'recipient' => \App\Models\Setting::getValue('admin_notify_email'),
                    'recipient_type' => 'admin',
                    'message' => "NEW TACTICAL ORDER: #{$order->order_number} received from {$order->customer_name}."
                ]);

                // Internal Database Notification for Admin Panel Bell
                $admins = \App\Models\User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new \App\Notifications\OrderNotification(
                        $order,
                        'new_order',
                        "New tactical order #{$order->order_number} received from {$order->customer_name}."
                    ));
                }
            } catch (\Exception $ne) {
                \Illuminate\Support\Facades\Log::warning('Post-checkout notification failed: ' . $ne->getMessage());
            }

            return redirect()->route('checkout.success', $order->id)
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to place order: ' . $e->getMessage());
        }
    }

    public function success($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);
        
        // Verify ownership
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('checkout.success', compact('order'));
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
}
