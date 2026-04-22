@extends('layouts.admin')

@section('title', 'Product Management')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
    <div class="space-y-1">
        <h2 class="text-2xl font-black text-black uppercase tracking-tighter">Your Inventory</h2>
        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Manage your style collection</p>
    </div>
    
    <div class="flex flex-col md:flex-row items-stretch md:items-center gap-4 w-full md:w-auto">
        <!-- Local Search -->
        <form action="{{ route('admin.products.index') }}" method="GET" class="relative group min-w-[300px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="SEARCH INVENTORY (NAME, SKU)..." 
                class="w-full bg-white border border-gray-100 text-[10px] font-black uppercase tracking-[0.2em] px-4 py-4 placeholder:text-gray-300 focus:ring-1 focus:ring-gold focus:border-gold transition-all">
            <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-gold transition-colors">
                <i class="fas fa-search"></i>
            </button>
        </form>

        <a href="{{ route('admin.products.create') }}" class="px-8 py-4 bg-black text-white text-[10px] font-bold uppercase tracking-[0.2em] shadow-xl hover:bg-gold transition-all duration-300 flex items-center gap-2 whitespace-nowrap">
            <i class="fas fa-plus"></i> Add New Product
        </a>
    </div>
</div>

<div class="bg-white border border-gray-100 shadow-sm rounded-none overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Info</th>
                    <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Category</th>
                    <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Price</th>
                    <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Stock</th>
                    <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Status</th>
                    <th class="px-6 py-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50/50 transition-all group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-20 bg-gray-50 border border-gray-100 overflow-hidden shrink-0 shadow-sm transition-transform group-hover:scale-105">
                                @if($product->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-100 text-[10px] text-gray-400 font-bold uppercase">No Img</div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-black uppercase tracking-tight truncate">{{ $product->name }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">SKU: {{ $product->sku }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wide bg-gray-100 px-3 py-1 rounded-sm">{{ $product->category->name ?? 'Uncategorized' }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-black">Rs. {{ number_format($product->getCurrentPrice()) }}</span>
                            @if($product->hasDiscount())
                                <span class="text-[10px] text-gray-400 line-through">Rs. {{ number_format($product->price) }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-sm font-bold {{ $product->stock_quantity <= 5 ? 'text-red-600' : 'text-black' }}">
                            {{ $product->stock_quantity }}
                        </span>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">units</p>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex flex-col gap-1 items-center justify-center">
                            @if($product->is_active)
                                <span class="px-2 py-0.5 bg-green-50 text-green-600 text-[8px] font-black uppercase tracking-widest border border-green-100">Active</span>
                            @else
                                <span class="px-2 py-0.5 bg-red-50 text-red-600 text-[8px] font-black uppercase tracking-widest border border-red-100">Draft</span>
                            @endif
                            
                            @if($product->is_featured)
                                <span class="px-2 py-0.5 bg-gold/10 text-gold text-[8px] font-black uppercase tracking-widest border border-gold/20">Featured</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-3 px-2" x-data="{}">
                            <!-- Duplicate Button -->
                            <form action="{{ route('admin.products.duplicate', $product->id) }}" method="POST" onsubmit="return confirm('Duplicate this product?');">
                                @csrf
                                <button type="submit" class="p-2 text-gray-400 hover:text-green-600 transition-colors" title="Duplicate Product">
                                    <i class="fas fa-clone text-xs"></i>
                                </button>
                            </form>

                            <a href="{{ route('admin.products.edit', $product->id) }}" class="p-2 text-gray-400 hover:text-black transition-colors" title="Edit Product">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 transition-colors" title="Delete Product">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-box-open text-4xl text-gray-200 mb-4"></i>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">No products in your store yet</p>
                            <a href="{{ route('admin.products.create') }}" class="mt-4 text-gold font-bold uppercase text-[10px] tracking-widest hover:text-black transition-colors">Start adding products</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Quick Preview Modal -->
<div x-data="{ 
        open: false, 
        p: {} 
    }" 
    @open-preview.window="open = true; p = $event.detail"
    x-show="open" 
    x-cloak
    class="fixed inset-0 z-[60] flex items-center justify-center px-4 py-6 overflow-hidden">
    
    <!-- Backdrop -->
    <div x-show="open" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="open = false" 
         class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

    <!-- Modal Content -->
    <div x-show="open"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4"
         class="relative bg-white w-full max-w-4xl shadow-2xl border border-gray-100 overflow-hidden">
        
        <div class="flex flex-col md:flex-row h-full max-h-[85vh]">
            <!-- Product Image -->
            <div class="w-full md:w-2/5 aspect-[3/4] bg-gray-50 border-r border-gray-100 relative">
                <img :src="p.image" class="w-full h-full object-cover" x-show="p.image">
                <div x-show="!p.image" class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                    <i class="fas fa-image text-4xl mb-2"></i>
                    <span class="text-[10px] uppercase font-black tracking-widest">No Gallery</span>
                </div>
                <!-- Status Badge Overlay -->
                <div class="absolute top-4 left-4">
                    <span class="px-3 py-1 bg-black text-white text-[9px] font-black uppercase tracking-widest" x-text="p.status"></span>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 p-8 md:p-12 overflow-y-auto">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <p class="text-[10px] text-gold font-black uppercase tracking-widest mb-1" x-text="p.category"></p>
                        <h2 class="text-3xl font-black text-black uppercase tracking-tighter leading-none" x-text="p.name"></h2>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-2" x-text="'SKU: ' + p.sku"></p>
                    </div>
                    <button @click="open = false" class="text-gray-400 hover:text-black p-2">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="flex items-center gap-6 mb-8 py-6 border-y border-gray-50">
                    <div class="space-y-1">
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Current Valuation</p>
                        <p class="text-2xl font-black text-black" x-text="p.sale_price ? 'Rs. ' + p.sale_price : 'Rs. ' + p.price"></p>
                        <p class="text-xs text-gray-400 line-through" x-show="p.sale_price" x-text="'Rs. ' + p.price"></p>
                    </div>
                    <div class="w-[1px] h-10 bg-gray-100"></div>
                    <div class="space-y-1">
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Stock Units</p>
                        <p class="text-2xl font-black" :class="p.stock <= 5 ? 'text-red-600' : 'text-black'" x-text="p.stock"></p>
                        <p class="text-[9px] font-black text-green-600 uppercase tracking-widest" x-show="p.stock > 0">Discovered in Depot</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="text-[10px] font-black text-black uppercase tracking-widest">Collection Description</h4>
                    <p class="text-sm font-light leading-relaxed text-gray-500" x-text="p.description"></p>
                </div>

                <div class="mt-12 flex gap-4">
                    <a :href="'/admin/products/' + p.id + '/edit'" class="px-8 py-4 bg-black text-white text-[10px] font-bold uppercase tracking-[0.2em] shadow-xl hover:bg-gold transition-colors block text-center flex-1">
                        Refine Masterpiece
                    </a>
                    <button @click="open = false" class="px-8 py-4 border border-gray-200 text-gray-400 text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-gray-50 transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-8">
    {{ $products->links() }}
</div>
@endsection

