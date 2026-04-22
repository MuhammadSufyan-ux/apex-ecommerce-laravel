@extends('layout')

@section('title', 'Shopping Bag - S4 Luxury Store')

@section('content')
<div class="bg-gray-50 min-h-screen py-20 pb-40" x-data="cartManager()">
    <div class="container mx-auto px-4 max-w-6xl">
        <!-- Header -->
        <div class="mb-16">
            <h1 class="text-4xl font-black text-black uppercase tracking-tighter mb-2">Shopping Bag</h1>
            <div class="h-[3px] w-12 bg-black mb-4"></div>
            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-[0.3em]">Review your selections</p>
        </div>

        <template x-if="items.length > 0">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                <!-- Left Column: Products List (Spans 8 cols) -->
                <div class="lg:col-span-8 space-y-6">
                    <template x-for="item in items" :key="item.id">
                        <div class="bg-white border border-gray-100 p-6 flex items-center gap-8 group transition-all duration-500 relative">
                            <!-- Product Image & Qty Control -->
                            <div class="w-32 shrink-0 flex flex-col items-center">
                                <div class="aspect-[3/4] w-full bg-gray-50 overflow-hidden border border-gray-100 mb-4 relative">
                                    <img :src="item.image" :alt="item.name" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700">
                                </div>
                                <!-- Quantity Control Below Image -->
                                <div class="flex items-center bg-black rounded-full overflow-hidden w-full">
                                    <button @click="updateQty(item.id, item.quantity - 1)" class="w-8 h-8 flex items-center justify-center text-white hover:bg-gold transition-all">
                                        <i class="fas fa-minus text-[8px]"></i>
                                    </button>
                                    <input type="text" :value="item.quantity" readonly class="w-8 bg-black text-center text-[10px] font-black text-white border-0 p-0 focus:ring-0">
                                    <button @click="updateQty(item.id, item.quantity + 1)" class="w-8 h-8 flex items-center justify-center text-white hover:bg-gold transition-all">
                                        <i class="fas fa-plus text-[8px]"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Product Info -->
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-sm font-black text-black uppercase tracking-tight mb-1" x-text="item.name"></h3>
                                        <div class="flex gap-4">
                                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest" x-text="'Unit: Rs. ' + item.price"></p>
                                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest" x-text="'Size: ' + (item.size || 'N/A')"></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Impact Value</p>
                                        <p class="text-lg font-black text-black tracking-tighter" x-text="'Rs. ' + item.subtotal"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Remove Button -->
                            <button @click="removeItem(item.id)" class="absolute top-4 right-4 text-gray-200 hover:text-red-500 transition-colors">
                                <i class="fas fa-times text-sm"></i>
                            </button>
                        </div>
                    </template>
                </div>

                <!-- Right Column: Summary (Spans 4 cols) -->
                <div class="lg:col-span-4 sticky top-32">
                    <div class="bg-white p-10 border border-gray-100 relative overflow-hidden">
                        <!-- Tactical Decoration -->
                        <div class="absolute top-0 right-0 w-24 h-24 bg-gray-50 -mr-12 -mt-12 rotate-45 pointer-events-none"></div>

                        <h2 class="text-xl font-black text-black uppercase tracking-tighter mb-10 flex items-center gap-3">
                            <span class="w-2 h-2 bg-black rotate-45"></span>
                            Cart Intelligence
                        </h2>
                        
                        <div class="space-y-6 mb-12">
                            <div class="flex justify-between items-center text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] px-1">
                                <span>Base Load</span>
                                <span class="text-black" x-text="'Rs. ' + subtotal"></span>
                            </div>
                            
                            <div class="flex justify-between items-center text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] px-1">
                                <span>Logistic Cost</span>
                                <span :class="parseFloat(subtotal.replace(/,/g, '')) >= 5000 ? 'text-green-600' : 'text-black'" 
                                      x-text="parseFloat(subtotal.replace(/,/g, '')) >= 5000 ? 'COMPLIMENTARY' : 'Rs. 200'">
                                </span>
                            </div>
                        </div>

                        <div class="pt-8 border-t border-gray-50 flex justify-between items-center mb-10 px-1">
                            <span class="text-[11px] font-black text-black uppercase tracking-[0.3em]">Final Value</span>
                            <span class="text-3xl font-black text-black tracking-tighter" x-text="'Rs. ' + finalTotal"></span>
                        </div>

                        <div class="space-y-4">
                            <a href="{{ route('checkout.index') }}" class="group block w-full py-5 bg-black text-white text-[11px] font-black uppercase tracking-[0.4em] text-center hover:bg-gold transition-all active:scale-95 flex items-center justify-center gap-2">
                                Initiate Checkout
                                <i class="fas fa-chevron-right text-[8px] transition-transform group-hover:translate-x-1"></i>
                            </a>
                            <a href="{{ route('products.index') }}" class="block w-full py-5 border border-black text-black text-[11px] font-black uppercase tracking-[0.4em] text-center hover:bg-black hover:text-white transition-all">
                                Return to Market
                            </a>
                        </div>
                        
                        <p class="mt-8 text-center text-[8px] font-black text-gray-300 uppercase tracking-widest">
                            <i class="fas fa-shield-alt mr-1"></i> End-to-End Encrypted Deployment
                        </p>
                    </div>
                </div>
            </div>
        </template>

        <!-- Empty State -->
        <template x-if="items.length === 0 && !loading">
            <div class="py-40 text-center bg-white border border-gray-100 animate-pulse">
                <i class="fas fa-shopping-bag text-8xl text-black mb-8 opacity-5"></i>
                <h2 class="text-3xl font-black text-black uppercase tracking-tighter mb-4">Inventory Empty</h2>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.4em] mb-12">No gear currently assigned for deployment</p>
                <a href="{{ route('products.index') }}" class="inline-block bg-black text-white px-12 py-5 text-[10px] font-black uppercase tracking-[0.4em] hover:bg-gold transition-all active:scale-95">
                    Deploy to Marketplace
                </a>
            </div>
        </template>

        <!-- Loading Overlay -->
        <div x-show="loading" class="fixed inset-0 bg-white/50 backdrop-blur-[1px] z-[100] flex items-center justify-center" style="display: none;">
            <div class="w-8 h-8 border-4 border-black border-t-gold animate-spin"></div>
        </div>
    </div>
</div>

<script>
function cartManager() {
    return {
        items: [],
        subtotal: '0',
        finalTotal: '0',
        loading: true,
        
        async init() {
            await this.loadCart();
        },
        
        async loadCart() {
            this.loading = true;
            try {
                const res = await fetch('{{ route("cart.list") }}');
                const data = await res.json();
                this.items = data.items;
                this.subtotal = data.subtotal;
                
                let rawSubtotal = parseFloat(data.subtotal.replace(/,/g, ''));
                let final = rawSubtotal >= 5000 ? rawSubtotal : rawSubtotal + 200;
                this.finalTotal = final.toLocaleString();
            } catch (e) {
                console.error('Failed to load cart', e);
            }
            this.loading = false;
        },
        
        async updateQty(id, qty) {
            if (qty < 1) return;
            this.loading = true;
            try {
                const res = await fetch(`/cart/${id}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ quantity: qty })
                });
                
                if (res.ok) {
                    await this.loadCart();
                    updateCartCount(); // Global function to update header count
                } else {
                    const data = await res.json();
                    alert(data.message || 'Update failed');
                }
            } catch (e) {
                console.error('Update qty error', e);
            }
            this.loading = false;
        },
        
        async removeItem(id) {
            if (!confirm('Abort item deployment?')) return;
            this.loading = true;
            try {
                const res = await fetch(`/cart/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                
                if (res.ok) {
                    await this.loadCart();
                    updateCartCount();
                }
            } catch (e) {
                console.error('Remove error', e);
            }
            this.loading = false;
        }
    }
}
</script>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
