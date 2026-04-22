@extends('layouts.admin')

@section('title', 'Order Details #' . $order->order_number)

@section('content')
<div x-data="itemManager({ 
    order_status: '{{ $order->order_status }}', 
    payment_status: '{{ $order->payment_status }}',
    items: {{ $order->items->mapWithKeys(fn($i) => [$i->id => ['order_status' => $i->order_status, 'payment_status' => $i->payment_status]])->toJson() }}
})" class="relative">
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-6">
            <a href="{{ route('admin.orders.index') }}" class="w-10 h-10 border border-gray-100 flex items-center justify-center text-gray-400 hover:text-black hover:border-black transition-all">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-3xl font-black text-black uppercase tracking-tighter">Order Detail</h1>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.3em] mt-2">Managing Order #{{ $order->order_number }}</p>
            </div>
        </div>
        <div class="flex gap-3">
            <template x-if="hasChanges()">
                <button @click="saveAllChanges" 
                        class="px-8 py-3 bg-black text-[#D4AF37] text-[10px] font-black uppercase tracking-[0.2em] hover:bg-gold hover:text-black transition-all flex items-center gap-3 animate-pulse border border-[#D4AF37]">
                    <i class="fas fa-save"></i> Save Protocol Changes
                </button>
            </template>
            <a href="{{ route('orders.show', $order) }}" target="_blank" class="px-6 py-3 border border-gray-100 bg-white text-[10px] font-black uppercase tracking-widest text-[#5C5C5C] hover:text-black hover:border-black transition-all flex items-center gap-3">
                <i class="fas fa-print"></i> View Receipt
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Global Status & Update -->
            <div class="bg-white border border-gray-100 shadow-sm p-8">
                <h3 class="text-[11px] font-black text-black uppercase tracking-[0.3em] mb-8 pb-4 border-b border-gray-50 flex items-center gap-3">
                    <i class="fas fa-cog text-[#D4AF37]"></i> Global Matrix
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-3">Master Order Status</label>
                        <div class="flex items-center gap-2">
                             @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                                <button @click="applyDraftGlobalStatus('{{ $status }}')" 
                                        title="{{ ucfirst($status) }}"
                                        class="w-10 h-10 flex items-center justify-center rounded-sm transition-all border"
                                        :class="draftOrder.order_status == '{{ $status }}' ? 'bg-black text-white border-black' : 'bg-white text-gray-400 border-gray-100 hover:border-black'">
                                    <i class="fas fa-{{ $status == 'pending' ? 'clock' : ($status == 'processing' ? 'sync' : ($status == 'shipped' ? 'truck' : ($status == 'delivered' ? 'check-circle' : 'times-circle'))) }}"></i>
                                </button>
                             @endforeach
                        </div>
                    </div>

                    <div class="text-right">
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-3">Global Payment Status</label>
                        <div class="flex items-center gap-2 justify-end">
                             @foreach(['pending', 'paid', 'failed', 'refunded'] as $pstatus)
                                <button @click="applyDraftGlobalPayment('{{ $pstatus }}')" 
                                        title="{{ ucfirst($pstatus) }}"
                                        class="w-10 h-10 flex items-center justify-center rounded-sm transition-all border"
                                        :class="draftOrder.payment_status == '{{ $pstatus }}' ? 'bg-black text-white border-black' : 'bg-white text-gray-400 border-gray-100 hover:border-black'">
                                    <i class="fas fa-{{ $pstatus == 'pending' ? 'hourglass-half' : ($pstatus == 'paid' ? 'check-double' : ($pstatus == 'failed' ? 'exclamation-triangle' : 'undo')) }}"></i>
                                </button>
                             @endforeach
                        </div>
                    </div>
                </div>
                <p class="text-[8px] text-gray-300 font-bold uppercase tracking-widest mt-6 text-center italic">Tip: Global updates will automatically sync with any selected items below.</p>
            </div>

            <!-- Order Items -->
            <div class="bg-white border border-gray-100 shadow-sm overflow-hidden">
                <!-- Selection Header (Actions for Selected Items) -->


                <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                    <h3 class="text-[11px] font-black text-black uppercase tracking-[0.3em]">Itemized Inventory</h3>
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $order->items->count() }} Total Shipments</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-6 py-5 w-10">
                                    <input type="checkbox" @change="toggleAll()" :checked="allSelected" class="w-4 h-4 rounded border-gray-300 text-black focus:ring-black">
                                </th>
                                <th class="px-4 py-5 text-[10px] font-black text-black uppercase tracking-widest">Masterpiece</th>
                                <th class="px-4 py-5 text-[10px] font-black text-black uppercase tracking-widest text-center">Spec</th>
                                <th class="px-6 py-5 text-[10px] font-black text-black uppercase tracking-widest text-center">Qty</th>
                                <th class="px-8 py-5 text-[10px] font-black text-black uppercase tracking-widest text-center">Status Control</th>
                                <th class="px-8 py-5 text-[10px] font-black text-black uppercase tracking-widest text-right">Evaluation</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-[11px]">
                            @foreach($order->items as $item)
                            <tr class="hover:bg-gray-50/50 transition-all" :class="selectedItems.includes({{ $item->id }}) ? 'bg-gold/5' : ''">
                                <td class="px-6 py-4">
                                    <input type="checkbox" value="{{ $item->id }}" x-model="selectedItems" class="w-4 h-4 rounded border-gray-300 text-black focus:ring-black item-checkbox">
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-16 bg-gray-50 shrink-0 border border-gray-100 shadow-sm">
                                            @if($item->product && $item->product->images->isNotEmpty())
                                                <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" class="w-full h-full object-cover">
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-black text-black uppercase tracking-tight">{{ $item->product_name }}</p>
                                            <p class="text-[9px] text-gray-400 font-bold tracking-widest mt-0.5">SKU: {{ $item->product_sku }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="font-black text-black uppercase">{{ $item->size != 'N/A' ? $item->size : 'STD' }}</span>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">{{ $item->color != 'N/A' ? $item->color : '-' }}</p>
                                </td>
                                <td class="px-6 py-4 text-center font-black">{{ $item->quantity }}</td>
                                <td class="px-8 py-4">
                                    <div class="flex flex-col gap-3">
                                        <!-- Item Order Status -->
                                        <div class="flex items-center justify-center gap-1.5">
                                            @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                                                <button @click="draftItems[{{ $item->id }}].order_status = '{{ $status }}'" 
                                                        title="{{ ucfirst($status) }}"
                                                        class="w-7 h-7 flex items-center justify-center rounded-sm transition-all border"
                                                        :class="draftItems[{{ $item->id }}].order_status == '{{ $status }}' ? 'bg-black text-white border-black shadow-lg' : 'bg-white text-gray-300 border-gray-100 hover:border-black'">
                                                    <i class="fas fa-{{ $status == 'pending' ? 'clock' : ($status == 'processing' ? 'sync' : ($status == 'shipped' ? 'truck' : ($status == 'delivered' ? 'check-circle' : 'times-circle'))) }} text-[9px]"></i>
                                                </button>
                                            @endforeach
                                        </div>
                                        <!-- Item Payment Status -->
                                        <div class="flex items-center justify-center gap-1.5 opacity-60">
                                            @foreach(['pending', 'paid', 'failed', 'refunded'] as $pstatus)
                                                <button @click="draftItems[{{ $item->id }}].payment_status = '{{ $pstatus }}'" 
                                                        title="Payment: {{ ucfirst($pstatus) }}"
                                                        class="w-7 h-7 flex items-center justify-center rounded-sm transition-all border"
                                                        :class="draftItems[{{ $item->id }}].payment_status == '{{ $pstatus }}' ? 'bg-black text-white border-black' : 'bg-white text-gray-300 border-gray-100 hover:border-black'">
                                                    <i class="fas fa-{{ $pstatus == 'pending' ? 'hourglass-half' : ($pstatus == 'paid' ? 'check-double' : ($pstatus == 'failed' ? 'exclamation-triangle' : 'undo')) }} text-[9px]"></i>
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-4 text-right font-black">Rs. {{ number_format($item->subtotal) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50/50">
                                <td colspan="5" class="px-8 py-6 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Inventory Subtotal</td>
                                <td class="px-8 py-6 text-right font-black text-lg">Rs. {{ number_format($order->subtotal) }}</td>
                            </tr>
                            @if($order->shipping_cost > 0)
                            <tr class="bg-gray-50/50">
                                <td colspan="5" class="px-8 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Global Logistics Fee</td>
                                <td class="px-8 py-4 text-right font-black text-black">Rs. {{ number_format($order->shipping_cost) }}</td>
                            </tr>
                            @endif
                            <tr class="bg-black text-white">
                                <td colspan="5" class="px-8 py-8 text-right text-[12px] font-black uppercase tracking-[0.4em]">Final Settlement Sum</td>
                                <td class="px-8 py-8 text-right text-2xl font-black">Rs. {{ number_format($order->total) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-8">
            <!-- Customer Profile -->
            <div class="bg-white border border-gray-100 shadow-sm p-8">
                <h3 class="text-[11px] font-black text-black uppercase tracking-[0.3em] mb-8 pb-4 border-b border-gray-50">Customer Dossier</h3>
                
                @if($order->user)
                    <div class="flex items-center gap-5 mb-10">
                        <div class="w-16 h-16 rounded-full bg-black border-4 border-gray-50 overflow-hidden shrink-0 shadow-xl">
                            @if($order->user->profile_image)
                                <img src="{{ asset('storage/' . $order->user->profile_image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-white font-black text-xl">
                                    {{ substr($order->user->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-black text-black uppercase tracking-tighter">{{ $order->user->name }}</p>
                            <p class="text-[9px] text-gold font-bold uppercase tracking-widest">Verified Member</p>
                        </div>
                    </div>
                @endif

                <div class="space-y-6">
                    <div>
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Display Identity</label>
                        <p class="text-sm font-black text-black uppercase">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Email Coordinates</label>
                        <p class="text-xs font-bold text-black lowercase">{{ $order->customer_email }}</p>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Direct Communication</label>
                        <p class="text-xs font-bold text-black tracking-widest">{{ $order->customer_phone }}</p>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Submission Date</label>
                        <p class="text-xs font-bold text-black uppercase">{{ $order->created_at->format('M d, Y - H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Logistics Hub -->
            <div class="bg-white border border-gray-100 shadow-sm p-8">
                <h3 class="text-[11px] font-black text-black uppercase tracking-[0.3em] mb-8 pb-4 border-b border-gray-50">Logistics Hub</h3>
                <div class="space-y-6">
                    <div>
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-3">Delivery Destination</label>
                        <div class="bg-gray-50 p-6 rounded-sm border border-gray-100 relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-2 opacity-5"><i class="fas fa-map-marker-alt text-4xl"></i></div>
                            <p class="text-[11px] font-bold text-gray-600 leading-loose uppercase relative z-10">
                                {{ $order->shipping_address }}<br>
                                {{ $order->shipping_city }}, {{ $order->shipping_postal_code }}<br>
                                {{ $order->shipping_country }}
                            </p>
                        </div>
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-3">Protocol Verified</label>
                        @php
                            $gateway = \App\Models\PaymentGateway::where('slug', $order->payment_method)->first();
                            $displayName = $gateway ? $gateway->name : str_replace('_', ' ', $order->payment_method);
                            $icon = $gateway ? $gateway->icon : 'fas fa-shield-alt';
                            $color = $gateway ? $gateway->icon_color : '#000000';
                        @endphp
                        <div class="flex items-center gap-3 px-4 py-3 bg-black border border-gold/20 shadow-xl group">
                            <i class="{{ $icon }} text-lg" style="color: {{ $color }};"></i>
                            <span class="text-[10px] font-black text-white uppercase tracking-[0.2em] group-hover:text-gold transition-colors">{{ $displayName }}</span>
                        </div>
                    </div>
                    @if($order->notes)
                    <div>
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-2">Instructions</label>
                        <div class="p-4 bg-yellow-50/40 border border-yellow-200/40 rounded-sm">
                            <p class="text-[10px] text-gray-500 font-bold italic leading-relaxed">"{{ $order->notes }}"</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function itemManager(initialData) {
        return {
            selectedItems: [],
            allSelected: false,
            
            // Draft states
            draftOrder: {
                order_status: initialData.order_status,
                payment_status: initialData.payment_status
            },
            draftItems: initialData.items,

            // Original states for comparison
            originalOrder: {
                order_status: initialData.order_status,
                payment_status: initialData.payment_status
            },
            originalItems: JSON.parse(JSON.stringify(initialData.items)),

            toggleAll() {
                this.allSelected = !this.allSelected;
                const checkboxes = Array.from(document.querySelectorAll('.item-checkbox'));
                if (this.allSelected) {
                    this.selectedItems = checkboxes.map(el => parseInt(el.value));
                } else {
                    this.selectedItems = [];
                }
            },

            applyDraftGlobalStatus(status) {
                this.draftOrder.order_status = status;
                if (this.selectedItems.length > 0) {
                    this.selectedItems.forEach(id => {
                        this.draftItems[id].order_status = status;
                    });
                }
            },

            applyDraftGlobalPayment(status) {
                this.draftOrder.payment_status = status;
                if (this.selectedItems.length > 0) {
                    this.selectedItems.forEach(id => {
                        this.draftItems[id].payment_status = status;
                    });
                }
            },

            hasChanges() {
                const orderChanged = JSON.stringify(this.draftOrder) !== JSON.stringify(this.originalOrder);
                const itemsChanged = JSON.stringify(this.draftItems) !== JSON.stringify(this.originalItems);
                return orderChanged || itemsChanged;
            },

            async saveAllChanges() {
                if (!confirm('COMMIT ALL PENDING CHANGES TO MASTER PROTOCOL?')) return;

                const payload = {
                    order: {},
                    items: []
                };

                // Add order changes
                if (JSON.stringify(this.draftOrder) !== JSON.stringify(this.originalOrder)) {
                    payload.order = this.draftOrder;
                }

                // Add item changes
                Object.keys(this.draftItems).forEach(id => {
                    if (JSON.stringify(this.draftItems[id]) !== JSON.stringify(this.originalItems[id])) {
                        payload.items.push({
                            id: parseInt(id),
                            ...this.draftItems[id]
                        });
                    }
                });

                try {
                    const response = await fetch('{{ route('admin.orders.batch-update', $order) }}', {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                    const data = await response.json();
                    if (data.success) {
                        location.reload();
                    }
                } catch (error) {
                    console.error('Batch save failed:', error);
                    alert('System Error: Could not commit changes.');
                }
            }
        }
    }
</script>
@endsection
