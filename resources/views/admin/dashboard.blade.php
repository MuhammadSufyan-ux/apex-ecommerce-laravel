@extends('layouts.admin')

@section('title', 'System Command Center')

@section('content')
<div class="px-8 py-10">
    <!-- Header: Admin & System Status -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-8 mb-12">
        <div>
            <h1 class="text-3xl font-black text-black uppercase tracking-tighter leading-none mb-2">SYSTEM COMMAND CENTER</h1>
            <div class="flex flex-wrap gap-4 items-center">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Core Status: Operational</span>
                </div>
                <div class="h-3 w-px bg-gray-200"></div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-shield-alt text-gold text-[10px]"></i>
                    <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Role: {{ auth()->user()->role }}</span>
                </div>
                <div class="h-3 w-px bg-gray-200"></div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-clock text-gray-400 text-[10px]"></i>
                    <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Last Login: {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->diffForHumans() : 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3 w-full lg:w-auto">
            <a href="{{ route('admin.products.create') }}" class="flex flex-col items-center justify-center p-4 bg-black text-white hover:bg-gold transition-all group">
                <i class="fas fa-plus mb-2 text-xs"></i>
                <span class="text-[8px] font-black uppercase tracking-widest text-center">Add Product</span>
            </a>
            <a href="{{ route('admin.reports.index') }}" class="flex flex-col items-center justify-center p-4 bg-white border border-gray-100 hover:border-black transition-all">
                <i class="fas fa-chart-line mb-2 text-xs text-gold"></i>
                <span class="text-[8px] font-black uppercase tracking-widest text-center">Intelligence</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center justify-center p-4 bg-white border border-gray-100 hover:border-black transition-all">
                <i class="fas fa-users mb-2 text-xs"></i>
                <span class="text-[8px] font-black uppercase tracking-widest text-center">Customers</span>
            </a>
            <a href="{{ route('admin.orders.index') }}" class="flex flex-col items-center justify-center p-4 bg-white border border-gray-100 hover:border-black transition-all">
                <i class="fas fa-shopping-bag mb-2 text-xs"></i>
                <span class="text-[8px] font-black uppercase tracking-widest text-center">Orders</span>
            </a>
            <a href="{{ route('admin.settings.index') }}" class="flex flex-col items-center justify-center p-4 bg-white border border-gray-100 hover:border-black transition-all">
                <i class="fas fa-cog mb-2 text-xs"></i>
                <span class="text-[8px] font-black uppercase tracking-widest text-center">Settings</span>
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <div class="bg-white p-8 border border-gray-100 shadow-sm transition-all hover:border-gold">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.3em] mb-2">Revenue Node</p>
            <h3 class="text-3xl font-black text-black tracking-tighter">Rs. {{ number_format($totalSales ?? 0) }}</h3>
        </div>
        <div class="bg-white p-8 border border-gray-100 shadow-sm transition-all hover:border-gold">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.3em] mb-2">Order Volume</p>
            <h3 class="text-3xl font-black text-black tracking-tighter">{{ $totalOrders ?? 0 }}</h3>
        </div>
        <div class="bg-white p-8 border border-gray-100 shadow-sm transition-all hover:border-gold">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.3em] mb-2">User Registry</p>
            <h3 class="text-3xl font-black text-black tracking-tighter">{{ $totalCustomers ?? 0 }}</h3>
        </div>
        <div class="bg-white p-8 border border-gray-100 shadow-sm transition-all hover:border-gold">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.3em] mb-2">Product Assets</p>
            <h3 class="text-3xl font-black text-black tracking-tighter">{{ $totalProducts ?? 0 }}</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Main: Recent Activity & Orders -->
        <div class="lg:col-span-2 space-y-12">
            
            <!-- Recent Activity Log -->
            <div class="bg-white border border-gray-100 shadow-sm">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30" x-data="{ clearOpen: false }">
                    <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-black">Operational Activity Log</h4>
                    <div class="relative">
                        <button @click="clearOpen = !clearOpen" class="text-[8px] font-black text-gray-400 uppercase tracking-widest hover:text-black transition-all flex items-center gap-1">
                            <i class="fas fa-broom"></i> Clear
                        </button>
                        <div x-show="clearOpen" @click.away="clearOpen = false" x-cloak
                             class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 shadow-xl z-50">
                            <form action="{{ route('admin.activity-logs.clear') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="period" value="all">
                                <button type="submit" onclick="return confirm('Clear ALL activity logs?')"
                                    class="w-full text-left px-4 py-3 text-[10px] font-black uppercase tracking-widest hover:bg-black hover:text-white transition-all border-b border-gray-100">
                                    <i class="fas fa-trash-alt mr-2 text-red-500"></i> Clear All
                                </button>
                            </form>
                            <form action="{{ route('admin.activity-logs.clear') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="period" value="week">
                                <button type="submit" onclick="return confirm('Clear logs older than 1 week?')"
                                    class="w-full text-left px-4 py-3 text-[10px] font-black uppercase tracking-widest hover:bg-black hover:text-white transition-all border-b border-gray-100">
                                    <i class="fas fa-calendar-week mr-2 text-blue-500"></i> Older than 1 Week
                                </button>
                            </form>
                            <form action="{{ route('admin.activity-logs.clear') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="period" value="month">
                                <button type="submit" onclick="return confirm('Clear logs older than 1 month?')"
                                    class="w-full text-left px-4 py-3 text-[10px] font-black uppercase tracking-widest hover:bg-black hover:text-white transition-all border-b border-gray-100">
                                    <i class="fas fa-calendar-alt mr-2 text-green-500"></i> Older than 1 Month
                                </button>
                            </form>
                            <form action="{{ route('admin.activity-logs.clear') }}" method="POST" class="p-4 border-t border-gray-100">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="period" value="date">
                                <label class="text-[8px] font-black uppercase tracking-widest text-gray-400 block mb-2">Before Date</label>
                                <div class="flex gap-2">
                                    <input type="date" name="clear_date" required
                                        class="flex-1 text-[10px] border border-gray-200 px-2 py-1.5 focus:border-black focus:ring-0">
                                    <button type="submit" onclick="return confirm('Clear logs before selected date?')"
                                        class="bg-black text-white px-3 py-1.5 text-[9px] font-black uppercase hover:bg-gold transition-all">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        @forelse($activityLogs ?? [] as $log)
                        <div class="flex items-start gap-4">
                            <div class="mt-1 w-2 h-2 rounded-full @if($log->type == 'auth') bg-blue-500 @elseif($log->type == 'order') bg-gold @elseif($log->type == 'product') bg-green-500 @else bg-gray-300 @endif"></div>
                            <div class="flex-1">
                                <p class="text-[11px] font-medium text-black leading-relaxed">{{ $log->description }}</p>
                                <div class="flex items-center gap-3 mt-2">
                                    <span class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">{{ $log->created_at->diffForHumans() }}</span>
                                    <span class="text-[8px] font-bold text-gray-300 uppercase tracking-widest">By {{ $log->user->name }}</span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="py-12 text-center text-[10px] font-black text-gray-300 uppercase tracking-widest">No activity recorded</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                    <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-black">Recent Orders</h4>
                    <a href="{{ route('admin.orders.index') }}" class="text-[9px] font-black text-gold hover:text-black transition-all uppercase tracking-widest">Full Ledger</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-8 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest">Order ID</th>
                                <th class="px-8 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest">Protocol</th>
                                <th class="px-8 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                                <th class="px-8 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest text-right">Settlement</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($recentOrders ?? [] as $order)
                            <tr class="hover:bg-gray-50/50 transition-all cursor-pointer" onclick="window.location='{{ route('admin.orders.show', $order->id) }}'">
                                <td class="px-8 py-5 text-[11px] font-black text-black">#{{ $order->order_number }}</td>
                                <td class="px-8 py-5">
                                    <p class="text-[11px] font-black text-black uppercase tracking-tight">{{ $order->customer_name }}</p>
                                    <p class="text-[9px] font-medium text-gray-400">{{ $order->customer_email }}</p>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="px-3 py-1 bg-black text-white text-[8px] font-black uppercase tracking-[0.2em]">{{ $order->order_status }}</span>
                                </td>
                                <td class="px-8 py-5 text-right text-[11px] font-black text-black">Rs. {{ number_format($order->total) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar: Admin Profile & Stock Alerts -->
        <div class="space-y-12">
            
            <!-- Administrator Node -->
            <div class="bg-black text-white p-10 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-48 h-48 bg-gold/5 rounded-full blur-3xl"></div>
                <div class="relative z-10">
                    <div class="w-20 h-20 bg-white/5 border border-white/10 rounded-full flex items-center justify-center mb-6 text-2xl font-black text-gold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <p class="text-[11px] font-black text-gold uppercase tracking-[0.4em] mb-1">Authenticated Node</p>
                    <h3 class="text-xl font-black tracking-tight mb-2">{{ auth()->user()->name }}</h3>
                    <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-6">{{ auth()->user()->email }}</p>
                    
                    <div class="space-y-4 border-t border-white/5 pt-6">
                        <div class="flex justify-between items-center">
                            <span class="text-[8px] font-black text-gray-500 uppercase tracking-widest">Protocol Role</span>
                            <span class="text-[10px] font-black text-white uppercase">{{ auth()->user()->role }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[8px] font-black text-gray-500 uppercase tracking-widest">Session Age</span>
                            <span class="text-[10px] font-black text-white uppercase">{{ auth()->user()->last_login_at ? auth()->user()->last_login_at->diffForHumans(null, true) : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventory Depletion Alerts -->
            <div class="bg-white border border-rose-100 p-8">
                <div class="flex items-center justify-between mb-8">
                    <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-black">Depletion Alerts</h4>
                    <span class="px-3 py-1 bg-gold text-white text-[8px] font-black uppercase tracking-widest">Action Required</span>
                </div>
                <div class="space-y-6">
                    @forelse($lowStockProducts ?? [] as $product)
                    <div class="flex items-center gap-4 group cursor-pointer" onclick="window.location='{{ route('admin.products.edit', $product->id) }}'">
                        <div class="w-12 h-16 bg-gray-50 border border-gray-100 shrink-0 overflow-hidden">
                            @if($product->images->isNotEmpty())
                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-[11px] font-black text-black group-hover:text-gold transition-colors leading-tight uppercase">{{ $product->name }}</p>
                            <div class="mt-2 flex items-center gap-2">
                                <span class="text-[9px] font-black text-rose-500 tracking-tighter">{{ $product->stock_quantity }} ONLY</span>
                                <span class="text-[8px] font-bold text-gray-300 uppercase tracking-widest underline decoration-rose-500/30">Restock Protocol</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="py-12 text-center">
                        <i class="fas fa-check-circle text-2xl text-green-500 mb-2"></i>
                        <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest">Inventory Balanced</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
