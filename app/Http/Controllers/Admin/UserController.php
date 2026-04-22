<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user')->withCount('orders')->withSum('orders', 'total');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        if ($sort === 'orders_high') $query->orderBy('orders_count', 'desc');
        elseif ($sort === 'orders_low') $query->orderBy('orders_count', 'asc');
        elseif ($sort === 'spend_high') $query->orderBy('orders_sum_total', 'desc');
        elseif ($sort === 'spend_low') $query->orderBy('orders_sum_total', 'asc');
        else $query->latest();

        $users = $query->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['orders' => function($q) {
            $q->latest();
        }]);

        // Analytics
        $stats = [
            'total_orders' => $user->orders->count(),
            'delivered_orders' => $user->orders->where('order_status', 'delivered')->count(),
            'cancelled_orders' => $user->orders->where('order_status', 'cancelled')->count(),
            'total_spent' => $user->orders->sum('total'),
            'avg_order_value' => $user->orders->count() > 0 ? $user->orders->avg('total') : 0,
            'cod_success_rate' => $user->orders->where('payment_method', 'cod')->count() > 0 
                ? ($user->orders->where('payment_method', 'cod')->where('order_status', 'delivered')->count() / $user->orders->where('payment_method', 'cod')->count()) * 100 
                : 0,
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'country' => 'nullable|string|max:100',
            'status' => 'required|in:active,blocked,vip',
            'admin_notes' => 'nullable|string',
        ]);

        $user->update($request->all());

        return redirect()->route('admin.users.show', $user)->with('success', 'Customer profile updated successfully.');
    }

    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:active,blocked,vip',
        ]);

        $oldStatus = $user->status;
        $user->update(['status' => $request->status]);

        \App\Models\ActivityLog::log('customer', 'updated', "Customer '{$user->name}' status changed from {$oldStatus} to {$request->status}");

        return back()->with('success', 'Account status updated to ' . strtoupper($request->status));
    }

    public function destroy(User $user)
    {
        if ($user->orders()->count() > 0) {
            return back()->with('error', 'Cannot delete a customer with existing orders. Consider blocking them instead.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        User::whereIn('id', $request->user_ids)->where('role', 'user')->delete();

        return response()->json(['success' => true, 'message' => 'Selected users deleted.']);
    }
}
