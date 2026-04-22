@props(['product'])

@php
    $images = $product->images;
    $frontImage = $images->count() > 0 
        ? asset('storage/' . $images->first()->image_path) 
        : 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=500&h=700&fit=crop';
        
    $backImage = $images->count() > 1 
        ? asset('storage/' . $images->get(1)->image_path) 
        : null;
@endphp

<div class="group bg-white product-card rounded-none overflow-hidden relative border border-dashed border-[#5C5C5C]/50 transition-all duration-300 hover:bg-white flex flex-col h-full" style="perspective: 1000px;">
    <!-- Image -->
    <div class="relative aspect-[3/4] overflow-hidden bg-gray-100 transition-transform duration-700 md:group-hover:rotate-y-12 transform-gpu">
        <a href="{{ route('products.show', $product->slug) }}" class="block w-full h-full relative">
            <!-- Front Image -->
            <img src="{{ $frontImage }}" 
                 alt="{{ $product->name }}" 
                 class="w-full h-full object-cover transition-opacity duration-700 {{ $backImage ? 'group-hover:opacity-0' : '' }}">
            
            <!-- Back Image (Hover) -->
            @if($backImage)
                <img src="{{ $backImage }}" 
                     alt="{{ $product->name }}" 
                     class="absolute inset-0 w-full h-full object-cover opacity-0 group-hover:opacity-100 transition-all duration-700 scale-105 group-hover:scale-100">
            @endif
        </a>
        
        <!-- Badges -->
        <div class="absolute top-3 left-3 flex flex-col gap-2 z-10 pointer-events-none">
            @if($product->is_coming_soon)
                <span class="px-3 py-1 bg-black text-white text-[10px] font-bold uppercase tracking-widest">NEW ARRIVAL</span>
            @endif
            
            @if($product->is_new)
                <span class="px-3 py-1 bg-black text-white text-[10px] font-bold uppercase tracking-widest">NEW</span>
            @endif

            @if(!empty($product->discount_badge))
                <span class="px-3 py-1 bg-red-600 text-white text-[10px] font-bold uppercase tracking-widest">
                    {{ str_contains($product->discount_badge, '%') ? $product->discount_badge . ' OFF' : $product->discount_badge }}
                </span>
            @elseif($product->is_on_sale)
                <span class="px-3 py-1 bg-red-600 text-white text-[10px] font-bold uppercase tracking-widest">SALE</span>
            @elseif($product->hasDiscount())
                <span class="px-3 py-1 bg-red-600 text-white text-[10px] font-bold uppercase tracking-widest">-{{ $product->getDiscountPercentage() }}%</span>
            @endif
        </div>
        
        <!-- Quick Add Button Overlay -->
        <div class="absolute inset-x-0 bottom-0 p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300 z-20">
            <button onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->getCurrentPrice() }}, '{{ $frontImage }}')" 
                    class="w-full bg-[#5C5C5C] text-white py-3 font-semibold hover:bg-black transition-colors uppercase text-xs tracking-widest">
                Add to Cart
            </button>
        </div>
        
        <!-- Wishlist Button -->
        <button onclick="addToFavorites({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->getCurrentPrice() }}, '{{ $frontImage }}')" 
                class="absolute top-3 right-3 w-8 h-8 flex items-center justify-center bg-white rounded-full text-gray-400 hover:text-red-600 hover:bg-white transition-colors z-10 border border-transparent hover:border-gray-200">
            <i class="far fa-heart"></i>
        </button>
    </div>
    
    <!-- Content -->
    <div class="p-4 text-center">
        @if($product->category)
            <span class="text-[#5C5C5C] text-[10px] font-bold uppercase tracking-widest block mb-1">{{ $product->category->name }}</span>
        @endif
        <h3 class="font-medium text-gray-900 mb-1 truncate text-sm tracking-wide">
            <a href="{{ route('products.show', $product->slug) }}" class="hover:text-black transition-colors">{{ $product->name }}</a>
        </h3>

        <!-- Reviews -->
        <div class="flex items-center justify-center gap-0.5 mb-1">
             <i class="fas fa-star text-[10px] text-black"></i>
             <i class="fas fa-star text-[10px] text-black"></i>
             <i class="fas fa-star text-[10px] text-black"></i>
             <i class="fas fa-star text-[10px] text-black"></i>
             <i class="fas fa-star-half-alt text-[10px] text-black"></i>
             <span class="text-[10px] text-[#5C5C5C] ml-1">(4.5)</span>
        </div>
        
        <div class="flex justify-center items-center gap-3 mt-2">
            <span class="text-[#5C5C5C] font-semibold">Rs. {{ number_format($product->getCurrentPrice()) }}</span>
            @if($product->hasDiscount())
                <span class="text-xs text-gray-400 line-through decoration-red-500">Rs. {{ number_format($product->price) }}</span>
            @endif
        </div>
    </div>
</div>
