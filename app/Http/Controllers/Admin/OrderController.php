<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        // Search
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'LIKE', "%{$search}%")
                  ->orWhere('customer_name', 'LIKE', "%{$search}%")
                  ->orWhere('customer_email', 'LIKE', "%{$search}%")
                  ->orWhere('customer_phone', 'LIKE', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('order_status', $request->status);
        }

        $orders = $query->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'user']);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => 'nullable|in:pending,processing,shipped,delivered,cancelled,returned',
            'payment_status' => 'nullable|in:pending,paid,failed,refunded',
        ]);

        $updateData = [];
        if ($request->has('order_status')) $updateData['order_status'] = $request->order_status;
        if ($request->has('payment_status')) $updateData['payment_status'] = $request->payment_status;

        if (!empty($updateData)) {
            $oldStatus = $order->order_status;
            $order->update($updateData);

            // Trigger Notifications if status changed
            if (isset($updateData['order_status']) && $oldStatus !== $updateData['order_status']) {
                \App\Models\ActivityLog::log('order', 'updated', "Order #{$order->order_number} status changed from {$oldStatus} to {$updateData['order_status']}");
                try {
                    $status = $updateData['order_status'];
                    $message = "Your Order #{$order->order_number} status has been updated to: " . strtoupper($status);
                    
                    if ($status === 'shipped') $message = "Good news! Your Order #{$order->order_number} has been SHIPPED and is on its way.";
                    if ($status === 'delivered') $message = "Tactical delivery confirmed! Order #{$order->order_number} has been DELIVERED.";
                    if ($status === 'cancelled') $message = "Order #{$order->order_number} has been CANCELLED as per protocol.";
                    if ($status === 'processing') $message = "Order #{$order->order_number} is now PROCESSING. We are packing your gear.";
                    if ($status === 'approved') $message = "Order #{$order->order_number} has been APPROVED. Preparing for deployment.";
                    if ($status === 'refunded') $message = "Refund initiated for Order #{$order->order_number}. Amount will be credited shortly.";

                    \App\Services\NotificationService::send([
                        'type' => 'email',
                        'event' => 'order_status_updated',
                        'recipient' => $order->customer_email,
                        'recipient_type' => 'customer',
                        'message' => $message
                    ]);

                    // Also WhatsApp if it was enabled (NotificationService handles the check)
                    \App\Services\NotificationService::send([
                        'type' => 'whatsapp',
                        'event' => 'order_status_updated',
                        'recipient' => $order->customer_phone,
                        'recipient_type' => 'customer',
                        'message' => $message
                    ]);

                    // Trigger Client Database Notification (For Sound Alert)
                    if ($order->user) {
                        $order->user->notify(new \App\Notifications\OrderNotification(
                            $order, 
                            'status_' . $status, 
                            $message
                        ));
                    }

                } catch (\Exception $ne) {
                    \Illuminate\Support\Facades\Log::warning('Status update notification failed: ' . $ne->getMessage());
                }
            }

            // Trigger Notification if payment status changed
            if (isset($updateData['payment_status']) && $oldStatus !== 'paid' && $updateData['payment_status'] === 'paid') {
                try {
                    $msg = "Payment confirmed for Order #{$order->order_number}. Thank you for your purchase!";
                    \App\Services\NotificationService::send([
                        'type' => 'email',
                        'event' => 'payment_received',
                        'recipient' => $order->customer_email,
                        'recipient_type' => 'customer',
                        'message' => $msg
                    ]);
                    
                    if ($order->user) {
                        $order->user->notify(new \App\Notifications\OrderNotification(
                            $order, 
                            'payment_paid', 
                            $msg
                        ));
                    }
                } catch (\Exception $ne) {
                    \Illuminate\Support\Facades\Log::warning('Payment notification failed: ' . $ne->getMessage());
                }
            }
            
            if (isset($updateData['payment_status']) && $updateData['payment_status'] === 'refunded') {
                try {
                    $msg = "Refund processed for Order #{$order->order_number}.";
                    if ($order->user) {
                        $order->user->notify(new \App\Notifications\OrderNotification(
                            $order, 
                            'payment_refunded', 
                            $msg
                        ));
                    }
                } catch (\Exception $e) {}
            }

        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Order updated successfully.']);
        }

        return back()->with('success', 'Order updated successfully.');
    }

    public function updateItem(Request $request, Order $order, \App\Models\OrderItem $item)
    {
        $request->validate([
            'order_status' => 'nullable|in:pending,processing,shipped,delivered,cancelled,returned',
            'payment_status' => 'nullable|in:pending,paid,failed,refunded',
        ]);

        $updateData = [];
        if ($request->has('order_status')) $updateData['order_status'] = $request->order_status;
        if ($request->has('payment_status')) $updateData['payment_status'] = $request->payment_status;

        if (!empty($updateData)) {
            $item->update($updateData);
        }

        return response()->json(['success' => true, 'message' => 'Item status updated.']);
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'order_status' => 'nullable|in:pending,processing,shipped,delivered,cancelled,returned',
            'payment_status' => 'nullable|in:pending,paid,failed,refunded',
        ]);

        $updateData = [];
        if ($request->has('order_status') && $request->order_status) $updateData['order_status'] = $request->order_status;
        if ($request->has('payment_status') && $request->payment_status) $updateData['payment_status'] = $request->payment_status;

        if (!empty($updateData)) {
            Order::whereIn('id', $request->order_ids)->update($updateData);
        }

        return response()->json(['success' => true, 'message' => 'Bulk update successful.']);
    }

    public function bulkUpdateItems(Request $request, Order $order)
    {
        $request->validate([
            'item_ids' => 'required|array',
            'item_ids.*' => 'exists:order_items,id',
            'order_status' => 'nullable|in:pending,processing,shipped,delivered,cancelled,returned',
            'payment_status' => 'nullable|in:pending,paid,failed,refunded',
        ]);

        $updateData = [];
        if ($request->has('order_status') && $request->order_status) $updateData['order_status'] = $request->order_status;
        if ($request->has('payment_status') && $request->payment_status) $updateData['payment_status'] = $request->payment_status;

        if (!empty($updateData)) {
            \App\Models\OrderItem::whereIn('id', $request->item_ids)
                ->where('order_id', $order->id)
                ->update($updateData);
        }

        return response()->json(['success' => true, 'message' => 'Items updated successfully.']);
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
        ]);

        Order::whereIn('id', $request->order_ids)->delete();

        return response()->json(['success' => true, 'message' => 'Orders deleted successfully.']);
    }

    public function batchUpdate(Request $request, Order $order)
    {
        $request->validate([
            'order' => 'nullable|array',
            'order.order_status' => 'nullable|in:pending,processing,shipped,delivered,cancelled,returned',
            'order.payment_status' => 'nullable|in:pending,paid,failed,refunded',
            'items' => 'nullable|array',
            'items.*.id' => 'required|exists:order_items,id',
            'items.*.order_status' => 'nullable|in:pending,processing,shipped,delivered,cancelled,returned',
            'items.*.payment_status' => 'nullable|in:pending,paid,failed,refunded',
        ]);

        \DB::transaction(function() use ($request, $order) {
            // Update Order
            if ($request->has('order')) {
                $order->update($request->order);
            }

            // Update Items
            if ($request->has('items')) {
                foreach ($request->items as $itemData) {
                    $item = \App\Models\OrderItem::where('id', $itemData['id'])
                        ->where('order_id', $order->id)
                        ->first();
                    
                    if ($item) {
                        $item->update(\Arr::only($itemData, ['order_status', 'payment_status']));
                    }
                }
            }
        });

        return response()->json(['success' => true, 'message' => 'Batch update applied successfully.']);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }
}
