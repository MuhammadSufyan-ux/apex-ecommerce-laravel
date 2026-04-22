@extends('layouts.admin')

@section('title', 'Manage Orders')

@section('content')
<div x-data="orderManager()" class="relative">
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-black uppercase tracking-tighter">Orders Management</h1>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.3em] mt-2">Track and manage customer shipments</p>
        </div>
    </div>

    <!-- Contextual Search & Filters -->
    <div class="bg-white border border-gray-100 shadow-sm p-6 mb-4">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Search Dispatch Registry</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Order #, Customer Name, Mobile..." 
                        class="w-full bg-gray-50 border-0 text-[11px] font-bold text-black px-4 py-2.5 placeholder:text-gray-300 focus:ring-1 focus:ring-black">
                    <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gold transition-colors">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <div>
                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Matrix Status Filter</label>
                <select name="status" onchange="this.form.submit()" class="w-full bg-gray-50 border-0 text-[11px] font-bold text-black uppercase tracking-widest px-4 py-2.5 focus:ring-1 focus:ring-black appearance-none cursor-pointer">
                    <option value="all">All Protocols</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="flex items-end">
                <a href="{{ route('admin.orders.index') }}" class="w-full py-2.5 border border-gray-100 text-center text-[10px] font-bold uppercase tracking-widest text-gray-300 hover:bg-black hover:text-white transition-all">Reset Matrix</a>
            </div>
        </form>
    </div>

    <!-- Selection Actions Bar (Below Search) -->
    <div x-show="selectedOrders.length > 0" x-cloak class="bg-black text-white p-4 mb-6 flex flex-wrap items-center justify-between gap-6 transition-all duration-300">
        <div class="flex items-center gap-6">
            <span class="text-[11px] font-black uppercase tracking-widest text-gold" x-text="selectedOrders.length + ' Selected'"></span>
            <div class="h-4 w-[1px] bg-gray-800"></div>
            
            <div class="flex items-center gap-4">
                <!-- Delete Action -->
                <button @click="bulkDelete()" title="Archive Selected" class="w-8 h-8 flex items-center justify-center bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all rounded-sm border border-red-500/20">
                    <i class="fas fa-trash-alt text-[10px]"></i>
                </button>

                <!-- Unified Operation Dropdown -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" class="flex items-center gap-3 bg-white/10 hover:bg-white/20 px-4 py-2 transition-all rounded-sm border border-white/10 group">
                        <span class="text-[9px] font-black uppercase tracking-widest text-white/70 group-hover:text-white">Choose Operation</span>
                        <i class="fas fa-chevron-down text-[8px] text-gold transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute left-0 top-full mt-2 w-56 bg-white shadow-2xl z-50 border border-gray-100 p-2 space-y-1">
                        
                        <p class="px-3 py-2 text-[8px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50 mb-1">Update Master Status</p>
                        @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                        <button @click="applyBulkUpdate('order_status', '{{ $status }}')" 
                                class="w-full text-left px-3 py-2.5 text-[9px] font-black text-black uppercase hover:bg-black hover:text-white transition-all flex items-center justify-between group/item">
                            {{ $status }}
                            <i class="fas fa-{{ $status == 'pending' ? 'clock' : ($status == 'processing' ? 'sync' : ($status == 'shipped' ? 'truck' : ($status == 'delivered' ? 'check-circle' : 'times-circle'))) }} text-[8px] opacity-0 group-hover/item:opacity-100"></i>
                        </button>
                        @endforeach

                        <p class="px-3 py-2 text-[8px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50 mt-3 mb-1">Update Master Payment</p>
                        @foreach(['pending', 'paid', 'failed', 'refunded'] as $pstatus)
                        <button @click="applyBulkUpdate('payment_status', '{{ $pstatus }}')" 
                                class="w-full text-left px-3 py-2.5 text-[9px] font-black text-black uppercase hover:bg-gold hover:text-black transition-all flex items-center justify-between group/item">
                            {{ $pstatus }}
                            <i class="fas fa-{{ $pstatus == 'pending' ? 'hourglass-half' : ($pstatus == 'paid' ? 'check-double' : ($pstatus == 'failed' ? 'exclamation-triangle' : 'undo')) }} text-[8px] opacity-0 group-hover/item:opacity-100"></i>
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <button @click="selectedOrders = []" class="text-[10px] font-black uppercase tracking-widest text-white/40 hover:text-white transition-all">Abort Selection</button>
    </div>

    <!-- Orders Table -->
    <div class="bg-white border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-5 w-10">
                            <input type="checkbox" @change="toggleAll()" :checked="allSelected" class="w-4 h-4 rounded border-gray-300 text-black focus:ring-black">
                        </th>
                        <th class="px-6 py-5 text-[10px] font-black text-black uppercase tracking-widest">Order ID</th>
                        <th class="px-6 py-5 text-[10px] font-black text-black uppercase tracking-widest">Customer</th>
                        <th class="px-6 py-5 text-[10px] font-black text-black uppercase tracking-widest">Status</th>
                        <th class="px-6 py-5 text-[10px] font-black text-black uppercase tracking-widest">Total</th>
                        <th class="px-6 py-5 text-[10px] font-black text-black uppercase tracking-widest">Date</th>
                        <th class="px-6 py-5 text-[10px] font-black text-black uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-[11px]">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50/50 transition-all group" :class="selectedOrders.includes({{ $order->id }}) ? 'bg-black/5' : ''">
                        <td class="px-6 py-5">
                            <input type="checkbox" :value="{{ $order->id }}" x-model="selectedOrders" class="w-4 h-4 rounded border-gray-300 text-black focus:ring-black order-checkbox">
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-black text-white rounded-sm flex items-center justify-center text-[10px] font-black">
                                    #{{ $order->id }}
                                </div>
                                <div>
                                    <p class="font-black text-black uppercase tracking-tight">{{ $order->order_number }}</p>
                                    <p class="text-[8px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">{{ $order->payment_method }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <p class="font-bold text-black uppercase tracking-tight">{{ $order->customer_name }}</p>
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">{{ $order->customer_phone }}</p>
                        </td>
                        <td class="px-6 py-5">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-50 text-yellow-600 border-yellow-100',
                                    'processing' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    'shipped' => 'bg-purple-50 text-purple-600 border-purple-100',
                                    'delivered' => 'bg-green-50 text-green-600 border-green-100',
                                    'cancelled' => 'bg-red-50 text-red-600 border-red-100',
                                ];
                                $color = $statusColors[$order->order_status] ?? 'bg-gray-50 text-gray-600 border-gray-100';
                            @endphp
                            <span class="px-2.5 py-1 {{ $color }} text-[9px] font-black uppercase tracking-widest border">
                                {{ $order->order_status }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <p class="font-black text-black">Rs. {{ number_format($order->total) }}</p>
                            <p class="text-[9px] text-gray-300 font-bold uppercase tracking-widest mt-0.5">{{ $order->items->count() }} items</p>
                        </td>
                        <td class="px-6 py-5">
                            <p class="font-bold text-gray-600 uppercase tracking-tight">{{ $order->created_at->format('d M, Y') }}</p>
                            <p class="text-[9px] text-gray-300 font-bold uppercase tracking-widest mt-0.5">{{ $order->created_at->format('H:i') }}</p>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex justify-end gap-2 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" title="Manage Status" class="p-2.5 bg-gray-50 text-black hover:bg-black hover:text-white transition-all rounded-sm border border-gray-100">
                                    <i class="fas fa-edit text-[10px]"></i>
                                </a>
                                <a href="{{ route('orders.show', $order) }}" target="_blank" title="Digital Invoice" class="p-2.5 bg-gray-50 text-black hover:bg-black hover:text-white transition-all rounded-sm border border-gray-100">
                                    <i class="fas fa-eye text-[10px]"></i>
                                </a>
                                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Archive this order?')">
                                    @csrf
                                    @method('DELETE')
                                    <button title="Archive" class="p-2.5 bg-gray-50 text-red-400 hover:bg-red-500 hover:text-white transition-all rounded-sm border border-gray-100 text-right">
                                        <i class="fas fa-trash text-[10px]"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center">
                            <i class="fas fa-shopping-bag text-4xl text-gray-100 mb-4"></i>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.3em]">No orders found matching criteria</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-6 border-t border-gray-50">
            {{ $orders->links() }}
        </div>
    </div>
</div>

<script>
    function orderManager() {
        return {
            selectedOrders: [],
            allSelected: false,
            
            toggleAll() {
                this.allSelected = !this.allSelected;
                const checkboxes = Array.from(document.querySelectorAll('.order-checkbox'));
                if (this.allSelected) {
                    this.selectedOrders = checkboxes.map(el => parseInt(el.value));
                } else {
                    this.selectedOrders = [];
                }
            },

            async applyBulkUpdate(field, value) {
                if (!confirm(`Apply ${field} update to ${this.selectedOrders.length} orders?`)) return;

                const body = { order_ids: this.selectedOrders };
                body[field] = value;

                const response = await fetch('{{ route('admin.orders.bulk-update') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(body)
                });

                if ((await response.json()).success) {
                    location.reload();
                }
            },

            async bulkDelete() {
                if (!confirm(`Permanently archive ${this.selectedOrders.length} selected orders?`)) return;

                const response = await fetch('{{ route('admin.orders.bulk-delete') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ order_ids: this.selectedOrders })
                });

                if ((await response.json()).success) {
                    location.reload();
                }
            }
        }
    }
</script>
@endsection
