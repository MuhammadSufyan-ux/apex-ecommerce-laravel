@extends('layout')

@section('content')
<div class="bg-[#F9F9F9] min-h-screen py-12">
    <div class="container mx-auto px-4 max-w-6xl">
        <!-- Page Header -->
        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-black tracking-tight uppercase mb-2">My Order History</h1>
                <p class="text-gray-500 font-medium tracking-wide">Detailed view of your past luxury purchases.</p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('home') }}" class="text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:text-black transition-all">Back to Store</a>
            </div>
        </div>

        <!-- 30-Day Auto-Removal Notice -->
        <div class="mb-8 p-6 bg-orange-50 border border-orange-100 rounded-sm flex items-start gap-5">
            <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center shrink-0">
                <i class="fas fa-history text-orange-600"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold text-orange-800 uppercase tracking-widest mb-1">Important: Auto-Archive Policy</h4>
                <p class="text-xs text-orange-700/80 leading-relaxed">
                    Please note that for performance optimization, order history older than <span class="font-bold underline">30 days</span> is automatically removed from this panel. 
                    We recommend taking <span class="font-bold uppercase tracking-tighter italic">screenshots</span> or noting down important order numbers for your future reference.
                </p>
            </div>
        </div>

        @if($orders->isEmpty())
            <div class="bg-white border border-gray-100 p-16 text-center rounded-sm">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-shopping-bag text-3xl text-gray-300"></i>
                </div>
                <h2 class="text-xl font-bold text-black mb-2">No Orders Yet</h2>
                <p class="text-gray-500 mb-8 max-w-sm mx-auto">You haven't placed any orders with us yet. Start exploring our collection to find your perfect style.</p>
                <a href="{{ route('products.index') }}" class="inline-block bg-black text-white px-10 py-4 font-bold uppercase tracking-widest text-xs hover:bg-gray-900 transition-all">
                    Shop Now
                </a>
            </div>
        @else
            <!-- Order History Table -->
            <div class="bg-white border border-gray-100 rounded-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400">Order #</th>
                                <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400">Date & Time</th>
                                <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400">Customer Details</th>
                                <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400">Items</th>
                                <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400">Total Amount</th>
                                <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400">Payment</th>
                                <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400">Order Status</th>
                                <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($orders as $order)
                                <tr class="hover:bg-gray-50/50 transition-colors group">
                                    <td class="px-6 py-6">
                                        <span class="text-sm font-bold text-black tracking-tighter">#{{ $order->order_number }}</span>
                                    </td>
                                    <td class="px-6 py-6">
                                        <div class="text-sm font-bold text-black">{{ $order->created_at->format('M d, Y') }}</div>
                                        <div class="text-[10px] text-gray-400 font-medium tracking-wide uppercase">{{ $order->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-6">
                                        <div class="text-sm font-bold text-black">{{ $order->customer_name }}</div>
                                        <div class="text-[10px] text-gray-500 truncate max-w-[150px]">{{ $order->shipping_city }}, {{ $order->shipping_address }}</div>
                                    </td>
                                    <td class="px-6 py-6">
                                        <div class="flex -space-x-3 overflow-hidden">
                                            @foreach($order->items->take(3) as $item)
                                                <div class="inline-block h-8 w-8 rounded-full ring-2 ring-white bg-gray-100 overflow-hidden" title="{{ $item->product_name }}">
                                                    @if($item->product && $item->product->images->isNotEmpty())
                                                        <img src="{{ $item->product->images->first()->image_path }}" class="h-full w-full object-cover">
                                                    @else
                                                        <div class="h-full w-full flex items-center justify-center bg-gray-200 text-[10px] font-bold">{{ substr($item->product_name ?? '', 0, 1) }}</div>
                                                    @endif
                                                </div>
                                            @endforeach
                                            @if($order->items->count() > 3)
                                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-900 text-[10px] font-bold text-white ring-2 ring-white">
                                                    +{{ $order->items->count() - 3 }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-6">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-black">Rs. {{ number_format($order->total, 0) }}</span>
                                            <span class="text-[10px] text-gray-400 uppercase tracking-widest">{{ $order->billing_method }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-6">
                                        <span class="inline-flex px-3 py-1 text-[9px] font-black uppercase tracking-widest rounded-sm border 
                                            @if($order->payment_status === 'paid') bg-green-50 text-green-700 border-green-200
                                            @elseif($order->payment_status === 'refunded') bg-red-50 text-red-700 border-red-200
                                            @else bg-yellow-50 text-yellow-700 border-yellow-200 @endif">
                                            {{ $order->payment_status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-6">
                                        <span class="inline-flex px-3 py-1 text-[10px] font-bold uppercase tracking-widest rounded-full 
                                            @if($order->order_status === 'delivered') bg-green-50 text-green-600 
                                            @elseif($order->order_status === 'cancelled') bg-red-50 text-red-600 
                                            @elseif($order->order_status === 'shipped') bg-purple-50 text-purple-600
                                            @else bg-orange-50 text-orange-600 @endif">
                                            {{ $order->order_status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-6 text-right">
                                        <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-[#5C5C5C] hover:text-black transition-all">
                                            View Details
                                            <i class="fas fa-chevron-right text-[8px]"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
