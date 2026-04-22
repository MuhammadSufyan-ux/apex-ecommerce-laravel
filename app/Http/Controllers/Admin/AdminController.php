<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalSales = Order::where('payment_status', 'completed')->orWhere('payment_method', 'cod')->sum('total');
        $totalOrders = Order::count();
        $totalCustomers = User::where('role', 'user')->count();
        $totalProducts = Product::count();

        $recentOrders = Order::latest()->take(5)->get();
        
        $lowStockProducts = Product::where('stock_quantity', '<=', 5)
            ->with('images')
            ->take(5)
            ->get();

        $activityLogs = ActivityLog::with('user')->latest()->take(10)->get();
        $adminInfo = auth()->user();

        return view('admin.dashboard', compact(
            'totalSales',
            'totalOrders',
            'totalCustomers',
            'totalProducts',
            'recentOrders',
            'lowStockProducts',
            'activityLogs',
            'adminInfo'
        ));
    }

    public function clearActivityLogs(Request $request)
    {
        $period = $request->input('period', 'all');

        switch ($period) {
            case 'week':
                ActivityLog::where('created_at', '<', Carbon::now()->subWeek())->delete();
                $msg = 'Activity logs older than 1 week cleared.';
                break;
            case 'month':
                ActivityLog::where('created_at', '<', Carbon::now()->subMonth())->delete();
                $msg = 'Activity logs older than 1 month cleared.';
                break;
            case 'date':
                $request->validate(['clear_date' => 'required|date']);
                ActivityLog::where('created_at', '<', Carbon::parse($request->clear_date)->endOfDay())->delete();
                $msg = 'Activity logs before ' . $request->clear_date . ' cleared.';
                break;
            default:
                ActivityLog::truncate();
                $msg = 'All activity logs cleared.';
        }

        return back()->with('success', $msg);
    }
}
