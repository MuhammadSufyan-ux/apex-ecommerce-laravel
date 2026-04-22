<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function export(Request $request)
    {
        $type = $request->get('type', 'daily_sales');
        $fileName = "report_{$type}_" . date('Y-m-d') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($type) {
            $file = fopen('php://output', 'w');

            if ($type === 'daily_sales') {
                fputcsv($file, ['Date', 'Orders', 'Revenue', 'Status']);
                $data = Order::whereDate('created_at', '>=', now()->subDays(30))
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as revenue'))
                    ->groupBy('date')->orderBy('date', 'desc')->get();
                foreach($data as $row) fputcsv($file, [$row->date, $row->count, $row->revenue, 'Active']);
            } 
            elseif ($type === 'product_sales') {
                fputcsv($file, ['Product', 'SKU', 'Sold Quantity', 'Revenue']);
                $data = OrderItem::select('product_name', 'product_sku', DB::raw('SUM(quantity) as qty'), DB::raw('SUM(subtotal) as revenue'))
                    ->groupBy('product_name', 'product_sku')->orderBy('qty', 'desc')->get();
                foreach($data as $row) fputcsv($file, [$row->product_name, $row->product_sku, $row->qty, $row->revenue]);
            }
            elseif ($type === 'customers') {
                fputcsv($file, ['Name', 'Email', 'Phone', 'Orders', 'Total Spend', 'Status']);
                $data = User::where('role', 'customer')->withCount('orders')->withSum('orders', 'total')->get();
                foreach($data as $row) fputcsv($file, [$row->name, $row->email, $row->phone, $row->orders_count, $row->orders_sum_total, $row->status]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function index()
    {
        // General Stats
        $totalRevenue = Order::where('order_status', 'completed')->sum('total');
        $totalOrders = Order::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalProducts = Product::count();

        // 1. Orders Trajectory (Monthly Counts & Revenue)
        $orderStats = Order::select(
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total) as revenue'),
                DB::raw("DATE_FORMAT(created_at, '%M') as month"),
                DB::raw('MONTH(created_at) as month_num')
            )
            ->where('order_status', '!=', 'cancelled')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month', 'month_num')
            ->orderBy('month_num')
            ->get();

        // 2. Customer Acquisition Growth
        $customerGrowth = User::where('role', 'customer')
            ->select(
                DB::raw('COUNT(*) as count'),
                DB::raw("DATE_FORMAT(created_at, '%M') as month"),
                DB::raw('MONTH(created_at) as month_num')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month', 'month_num')
            ->orderBy('month_num')
            ->get();

        // 3. Stock Intelligence (Inventory Health)
        $stockLevels = [
            'in_stock' => Product::where('stock_quantity', '>', 5)->count(),
            'low_stock' => Product::whereBetween('stock_quantity', [1, 5])->count(),
            'out_of_stock' => Product::where('stock_quantity', '<=', 0)->count(),
        ];

        // 4. Quantity Sold Trajectory (Last 30 Days)
        $soldTrajectory = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.order_status', 'completed')
            ->select(
                DB::raw('SUM(quantity) as quantity'),
                DB::raw('DATE(order_items.created_at) as date')
            )
            ->where('order_items.created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 5. Product Asset Value per Category
        $categoryValue = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(products.price * products.stock_quantity) as total_value'))
            ->groupBy('categories.name')
            ->get();

        // 6. Realistic Customer Insights
        $customerInsights = [
            'today' => User::where('role', 'customer')->whereDate('created_at', Carbon::today())->count(),
            'blocked' => User::where('role', 'customer')->where('status', 'blocked')->count(),
            'returning' => User::where('role', 'customer')
                ->has('orders', '>', 1)
                ->count(),
            'vip' => User::where('role', 'customer')
                ->withSum(['orders' => function($q) {
                    $q->where('order_status', 'completed');
                }], 'total')
                ->having('orders_sum_total', '>=', 10000) // VIP threshold: 10,000 revenue
                ->orderByDesc('orders_sum_total')
                ->limit(5)
                ->get()
        ];

        // 7. Profit/Loss & Product Excellence
        $profitLoss = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.order_status', 'completed')
            ->select(
                DB::raw('SUM(order_items.price * order_items.quantity) as revenue'),
                DB::raw('SUM(products.cost_price * order_items.quantity) as cost')
            )
            ->first();

        $grossProfit = ($profitLoss->revenue ?? 0) - ($profitLoss->cost ?? 0);

        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->with(['product:id,name,price,sale_price,sku', 'product.images'])
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        return view('admin.reports.index', compact(
            'totalRevenue',
            'totalOrders',
            'totalCustomers',
            'totalProducts',
            'orderStats',
            'customerGrowth',
            'stockLevels',
            'soldTrajectory',
            'categoryValue',
            'customerInsights',
            'grossProfit',
            'topProducts'
        ));
    }

    public function sales(Request $request)
    {
        $period = $request->get('period', 'monthly');
        
        if ($period == 'daily') {
            $data = Order::where('order_status', 'completed')
                ->select(DB::raw('SUM(total) as total'), DB::raw('DATE(created_at) as date'))
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get();
        } else {
            $data = Order::where('order_status', 'completed')
                ->select(DB::raw('SUM(total) as total'), DB::raw("DATE_FORMAT(created_at, '%Y-%m') as date"))
                ->groupBy('date')
                ->orderBy('date')
                ->get();
        }

        return response()->json($data);
    }

    public function productSales()
    {
        $data = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(quantity * price) as revenue'))
            ->with(['product:id,name'])
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->get();

        return response()->json($data);
    }
}
