@extends('layouts.admin')

@section('title', 'Customer Intelligence: ' . $user->name)

@section('content')
<div class="relative">
    <!-- Header Section -->
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-6">
            <a href="{{ route('admin.users.index') }}" class="w-10 h-10 border border-gray-100 flex items-center justify-center text-gray-400 hover:text-black hover:border-black transition-all">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-3xl font-black text-black uppercase tracking-tighter">Profile Intelligence</h1>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.3em] mt-2">Analyzing Entity #{{ $user->id }}</p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.users.edit', $user) }}" class="px-6 py-3 border border-black text-[10px] font-black uppercase tracking-widest text-black hover:bg-black hover:text-white transition-all flex items-center gap-3">
                <i class="fas fa-edit"></i> Modify Profile
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar: Identity & Controls -->
        <div class="space-y-8">
            <!-- Identity Card -->
            <div class="bg-white border border-gray-100 p-8 text-center">
                <div class="w-32 h-32 mx-auto bg-black border-4 border-gray-50 overflow-hidden mb-6 relative group">
                    @if($user->profile_image)
                        <img src="{{ asset('storage/' . $user->profile_image) }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-white font-black text-5xl flex items-center justify-center h-full">{{ substr($user->name, 0, 1) }}</span>
                    @endif
                    
                    @if($user->status == 'vip')
                        <div class="absolute inset-0 border-4 border-gold z-10 pointer-events-none"></div>
                        <div class="absolute -top-1 -right-1 bg-gold text-white w-8 h-8 flex items-center justify-center rounded-none shadow-lg z-20">
                            <i class="fas fa-crown text-[12px]"></i>
                        </div>
                    @endif
                </div>

                <h3 class="text-xl font-black text-black uppercase tracking-tight">{{ $user->name }}</h3>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">{{ $user->email }}</p>
                
                <div class="mt-6 flex justify-center">
                    @php
                        $statusMap = [
                            'active' => 'bg-green-50 text-green-600 border-green-100',
                            'blocked' => 'bg-red-50 text-red-600 border-red-100',
                            'vip' => 'bg-gold/10 text-gold border-gold/20 font-black',
                        ];
                    @endphp
                    <span class="px-4 py-2 {{ $statusMap[$user->status] ?? 'bg-gray-50 text-gray-500 border-gray-100' }} text-[10px] font-black uppercase tracking-widest border">
                        Account {{ $user->status }}
                    </span>
                </div>

                <div class="mt-10 grid grid-cols-2 gap-4 pt-10 border-t border-gray-50">
                    <div class="text-left">
                        <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Since</p>
                        <p class="text-[10px] font-bold text-black uppercase mt-1">{{ $user->created_at->format('d M, Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Last Access</p>
                        <p class="text-[10px] font-bold text-black uppercase mt-1">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</p>
                    </div>
                </div>
            </div>

            <!-- Protocol Controls -->
            <div class="bg-black text-white p-8">
                <h4 class="text-[11px] font-black uppercase tracking-[0.3em] text-[#D4AF37] mb-6 flex items-center gap-3">
                    <i class="fas fa-shield-alt"></i> Protocol Override
                </h4>
                
                <div class="space-y-3">
                    <form action="{{ route('admin.users.update-status', $user) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="active">
                        <button type="submit" class="w-full flex items-center justify-between px-5 py-3 border border-white/10 hover:border-green-500 hover:bg-green-500/10 transition-all text-[10px] font-black uppercase tracking-widest {{ $user->status == 'active' ? 'bg-green-500/10 border-green-500 text-green-500' : 'text-gray-400' }}">
                            Standard Access
                            <i class="fas fa-check-circle"></i>
                        </button>
                    </form>

                    <form action="{{ route('admin.users.update-status', $user) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="vip">
                        <button type="submit" class="w-full flex items-center justify-between px-5 py-3 border border-white/10 hover:border-gold hover:bg-gold/10 transition-all text-[10px] font-black uppercase tracking-widest {{ $user->status == 'vip' ? 'bg-gold/10 border-gold text-gold' : 'text-gray-400' }}">
                            VIP Elite Privilege
                            <i class="fas fa-crown"></i>
                        </button>
                    </form>

                    <form action="{{ route('admin.users.update-status', $user) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="blocked">
                        <button type="submit" class="w-full flex items-center justify-between px-5 py-3 border border-white/10 hover:border-red-500 hover:bg-red-500/10 transition-all text-[10px] font-black uppercase tracking-widest {{ $user->status == 'blocked' ? 'bg-red-500/10 border-red-500 text-red-500' : 'text-gray-400' }}">
                            Block Protocol
                            <i class="fas fa-ban"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Internal Registry -->
            <div class="bg-white border border-gray-100 p-8">
                <h4 class="text-[11px] font-black uppercase tracking-[0.3em] text-black mb-6 flex items-center gap-3">
                    <i class="fas fa-sticky-note text-gold"></i> Internal Notes
                </h4>
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    {{-- Hidden fields to satisfy validation on simple note update --}}
                    <input type="hidden" name="name" value="{{ $user->name }}">
                    <input type="hidden" name="email" value="{{ $user->email }}">
                    <input type="hidden" name="status" value="{{ $user->status }}">

                    <textarea name="admin_notes" rows="6" placeholder="Document behavioral patterns..." 
                        class="w-full bg-gray-50 border-0 p-4 text-xs font-bold text-black placeholder:text-gray-300 focus:ring-1 focus:ring-black mb-4">{{ $user->admin_notes }}</textarea>
                    
                    <button type="submit" class="w-full py-3 bg-black text-white text-[10px] font-black uppercase tracking-widest hover:bg-gold transition-all">
                        Journal Entry
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content: Stats & Orders -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Analytics Matrix -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white border border-gray-100 p-6">
                    <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Lifetime Spent</p>
                    <p class="text-xl font-black text-black">Rs. {{ number_format($stats['total_spent']) }}</p>
                </div>
                <div class="bg-white border border-gray-100 p-6">
                    <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Orders</p>
                    <p class="text-xl font-black text-black">{{ $stats['total_orders'] }}</p>
                </div>
                <div class="bg-white border border-gray-100 p-6">
                    <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Avg Order Value</p>
                    <p class="text-xl font-black text-black">Rs. {{ number_format($stats['avg_order_value']) }}</p>
                </div>
                <div class="bg-white border border-gray-100 p-6">
                    <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">COD Success</p>
                    <p class="text-xl font-black text-black">{{ number_format($stats['cod_success_rate'], 1) }}%</p>
                </div>
            </div>

            <!-- Behavioral Metrics -->
            <div class="bg-white border border-gray-100 p-8">
                <h4 class="text-[11px] font-black uppercase tracking-[0.3em] text-black mb-8 flex items-center gap-3">
                    <i class="fas fa-chart-line text-blue-500"></i> Behavior Analytics
                </h4>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Deliveries</span>
                            <span class="text-[10px] font-black text-green-600">{{ $stats['delivered_orders'] }}</span>
                        </div>
                        <div class="h-1 w-full bg-gray-50 overflow-hidden">
                            <div class="h-full bg-green-500" style="width: {{ $stats['total_orders'] > 0 ? ($stats['delivered_orders'] / $stats['total_orders'] * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Cancellations</span>
                            <span class="text-[10px] font-black text-red-600">{{ $stats['cancelled_orders'] }}</span>
                        </div>
                        <div class="h-1 w-full bg-gray-50 overflow-hidden">
                            <div class="h-full bg-red-500" style="width: {{ $stats['total_orders'] > 0 ? ($stats['cancelled_orders'] / $stats['total_orders'] * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Physical Location -->
            <div class="bg-white border border-gray-100 p-8">
                <h4 class="text-[11px] font-black uppercase tracking-[0.3em] text-black mb-8 flex items-center gap-3">
                    <i class="fas fa-map-marker-alt text-red-500"></i> Location Intelligence
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-2">Primary Residence</label>
                        <p class="text-xs font-bold text-black uppercase leading-relaxed">{{ $user->address ?: 'NO ADDRESS REGISTERED' }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-2">City Focus</label>
                            <p class="text-xs font-bold text-black uppercase">{{ $user->city ?: '-' }}</p>
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-2">Region</label>
                            <p class="text-xs font-bold text-black uppercase">{{ $user->country ?: 'PAKISTAN' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deployment History (Orders) -->
            <div class="bg-white border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex items-center justify-between">
                    <h4 class="text-[11px] font-black uppercase tracking-[0.3em] text-black flex items-center gap-3">
                        <i class="fas fa-history text-purple-500"></i> Operation History
                    </h4>
                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $user->orders->count() }} DEPLOYMENTS RECORDED</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-4 text-[9px] font-black text-black uppercase tracking-widest">Command ID</th>
                                <th class="px-6 py-4 text-[9px] font-black text-black uppercase tracking-widest">Timeline</th>
                                <th class="px-6 py-4 text-[9px] font-black text-black uppercase tracking-widest">Protocol</th>
                                <th class="px-6 py-4 text-[9px] font-black text-black uppercase tracking-widest">Value</th>
                                <th class="px-6 py-4 text-right text-[9px] font-black text-black uppercase tracking-widest">Details</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-[11px]">
                            @forelse($user->orders as $order)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-black text-black uppercase tracking-tight">#{{ $order->order_number }}</p>
                                    <p class="text-[8px] text-gray-300 font-bold uppercase mt-0.5">{{ $order->payment_method }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-600 uppercase">{{ $order->created_at->format('d M, Y') }}</p>
                                    <p class="text-[8px] text-gray-300 font-bold uppercase mt-0.5">{{ $order->created_at->format('H:i') }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $orderStatusColors = [
                                            'pending' => 'text-yellow-500',
                                            'processing' => 'text-blue-500',
                                            'shipped' => 'text-purple-500',
                                            'delivered' => 'text-green-500',
                                            'cancelled' => 'text-red-500',
                                        ];
                                    @endphp
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full {{ $orderStatusColors[$order->order_status] ?? 'bg-gray-300' }}"></div>
                                        <span class="font-black uppercase tracking-widest text-[9px] {{ $orderStatusColors[$order->order_status] ?? 'text-gray-400' }}">
                                            {{ $order->order_status }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-black font-black">
                                    Rs. {{ number_format($order->total) }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="p-2 bg-gray-50 border border-gray-100 text-black hover:bg-black hover:text-white transition-all">
                                        <i class="fas fa-external-link-alt text-[9px]"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <p class="text-[10px] text-gray-300 font-black uppercase tracking-widest">NO FIELD DEPLOYMENTS RECORDED</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
