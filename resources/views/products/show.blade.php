@extends('layout')

@section('title', $product->name . ' - S4 Luxury Store')
@section('meta_description', $product->meta_description ?? Str::limit(strip_tags($product->description), 160))

@section('content')
<div class="container mx-auto px-4 py-8 md:py-12 max-w-screen-xl">
    <!-- Minimal Breadcrumb -->
    <!-- Requested Breadcrumb Format -->
    <div class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400 mb-10 flex items-center gap-2">
        <a href="{{ route('home') }}" class="hover:text-black transition-colors">Home</a>
        <span class="text-gray-200">/</span>
        <a href="{{ route('products.index') }}" class="hover:text-black transition-colors">Shop</a>
        <span class="text-gray-200">/</span>
        <a href="{{ route('products.index', ['category' => $product->category->slug ?? 'all']) }}" class="hover:text-black transition-colors">
            {{ $product->category->name ?? 'Collection' }}
        </a>
        <span class="text-gray-200">/</span>
        <span class="text-black">{{ $product->name }}</span>
    </div>

    @php
        $mainImage = $product->images->count() > 0 
            ? asset('storage/' . $product->images->first()->image_path) 
            : 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=800&h=1200&fit=crop';
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20">
        <!-- Left Column: Images -->
        <div class="space-y-6">
            <!-- Main Image (Smaller as requested) -->
            <div class="relative overflow-hidden bg-white aspect-[3/4] group border border-[#5C5C5C] rounded-none max-w-[500px] mx-auto">
                <img src="{{ $mainImage }}" 
                     alt="{{ $product->name }}" 
                     id="main-product-image"
                     class="w-full h-full object-contain md:object-cover transition-transform duration-700 group-hover:scale-105">
                
                <!-- Badges -->
                <div class="absolute top-4 left-4 flex flex-col gap-2 z-10">
                    @if($product->is_new)
                        <span class="px-3 py-1 bg-black text-white text-[10px] font-bold uppercase tracking-widest">NEW</span>
                    @endif
                    @if($product->hasDiscount())
                        <span class="px-3 py-1 bg-red-600 text-white text-[10px] font-bold uppercase tracking-widest">-{{ $product->getDiscountPercentage() }}%</span>
                    @endif
                </div>
            </div>

            <!-- Premium Thumbnails Gallery -->
            @if($product->images->count() > 0)
                <div class="mt-8">
                    <div class="flex flex-wrap justify-center gap-3">
                        @foreach($product->images as $image)
                            <div class="w-20 h-28 md:w-24 md:h-32 cursor-pointer border border-gray-200 hover:border-black transition-all duration-300 p-0.5 group overflow-hidden relative"
                                 onclick="updateMainImage('{{ asset('storage/' . $image->image_path) }}')">
                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
                                     alt="{{ $product->name }}">
                                <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-4">
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.2em] italic">Click images to zoom material detail</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Product Details (Sticky) -->
        <div class="lg:sticky lg:top-32 h-fit">
            <h1 class="text-4xl md:text-5xl font-['Playfair_Display'] font-bold text-black mb-4 leading-tight">{{ $product->name }}</h1>
            
            <div class="flex items-center gap-4 mb-8">
                 <span class="text-2xl font-medium text-[#5C5C5C]">Rs. {{ number_format($product->getCurrentPrice()) }}</span>
                 @if($product->hasDiscount())
                    <span class="text-lg text-[#808080] line-through decoration-red-500">Rs. {{ number_format($product->price) }}</span>
                 @endif
            </div>

            <!-- Description -->
            <div class="prose prose-sm text-[#5C5C5C] mb-8 leading-relaxed font-light">
                {!! nl2br(e($product->description)) !!}
            </div>

            <!-- Validation/Stock -->
            <div class="mb-8">
                @if($product->stock_quantity > 0)
                    <span class="text-green-600 text-xs font-bold uppercase tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-600 shimmer"></span>
                        In Stock
                    </span>
                @else
                    <span class="text-red-600 text-xs font-bold uppercase tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-red-600"></span>
                        Out of Stock
                    </span>
                @endif
            </div>

            <!-- Actions -->
            <div class="flex flex-col gap-4 mb-10">
                <button onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->getCurrentPrice() }}, '{{ $mainImage }}')" 
                        class="w-full py-4 bg-[#5C5C5C] text-white font-bold uppercase tracking-[0.15em] hover:bg-black transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden group">
                    <span class="relative z-10">Add to Cart</span>
                    <div class="absolute inset-0 bg-black transform translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                </button>
                
                <button onclick="addToFavorites({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->getCurrentPrice() }}, '{{ $mainImage }}')" 
                        class="w-full py-4 border border-[#5C5C5C] text-[#5C5C5C] font-bold uppercase tracking-[0.15em] hover:bg-black hover:text-white hover:border-black transition-all duration-300">
                    Add to Favorites
                </button>
            </div>
            
            <!-- Additional Gallery (Duppata, Shalwar, Bazoo) -->
            @if($product->duppata_image || $product->shalwar_image || $product->bazoo_image)
                <div class="mt-12 pt-12 border-t border-gray-100">
                    <h3 class="text-xs font-bold uppercase tracking-[0.2em] mb-8 text-black">Component Gallery</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @if($product->duppata_image)
                            <div class="group relative overflow-hidden bg-gray-50 border border-gray-100">
                                <img src="{{ asset('storage/' . $product->duppata_image) }}" class="w-full aspect-[3/4] object-cover transition-transform duration-700 group-hover:scale-110">
                                <span class="absolute bottom-4 left-4 bg-white/90 backdrop-blur-sm px-4 py-2 text-[10px] font-bold uppercase tracking-widest text-black">Duppata Detail</span>
                            </div>
                        @endif
                        @if($product->shalwar_image)
                            <div class="group relative overflow-hidden bg-gray-50 border border-gray-100">
                                <img src="{{ asset('storage/' . $product->shalwar_image) }}" class="w-full aspect-[3/4] object-cover transition-transform duration-700 group-hover:scale-110">
                                <span class="absolute bottom-4 left-4 bg-white/90 backdrop-blur-sm px-4 py-2 text-[10px] font-bold uppercase tracking-widest text-black">Shalwar Detail</span>
                            </div>
                        @endif
                        @if($product->bazoo_image)
                            <div class="group relative overflow-hidden bg-gray-50 border border-gray-100">
                                <img src="{{ asset('storage/' . $product->bazoo_image) }}" class="w-full aspect-[3/4] object-cover transition-transform duration-700 group-hover:scale-110">
                                <span class="absolute bottom-4 left-4 bg-white/90 backdrop-blur-sm px-4 py-2 text-[10px] font-bold uppercase tracking-widest text-black">Bazoo Detail</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Additional Info (Accordion Style) -->
            <div class="border-y border-[#5C5C5C] divide-y divide-[#5C5C5C]">
                <!-- Product & Fabric Details -->
                <div x-data="{ open: false }" class="py-4">
                    <button @click="open = !open" class="w-full flex justify-between items-center text-sm font-bold uppercase tracking-widest text-black">
                        <span>Product & Fabric Details</span>
                        <i class="fas transition-transform duration-300" :class="open ? 'fa-minus' : 'fa-plus'"></i>
                    </button>
                    <div x-show="open" x-cloak x-transition class="mt-4 text-sm text-[#5C5C5C] leading-relaxed">
                        <div class="prose prose-stone prose-sm max-w-none">
                            {!! nl2br(e($product->fabric_details ?? 'Premium fabric selected for longevity and comfort. Hand-crafted with attention to every stitch.')) !!}
                        </div>
                    </div>
                </div>

                <!-- Care Instructions -->
                @if($product->care_instructions)
                <div x-data="{ open: false }" class="py-4">
                    <button @click="open = !open" class="w-full flex justify-between items-center text-sm font-bold uppercase tracking-widest text-black">
                        <span>Care Instructions</span>
                        <i class="fas transition-transform duration-300" :class="open ? 'fa-minus' : 'fa-plus'"></i>
                    </button>
                    <div x-show="open" x-cloak x-transition class="mt-4">
                        <ul class="space-y-2 list-none p-0">
                            @foreach(explode("\n", $product->care_instructions) as $instruction)
                                @if(trim($instruction))
                                    <li class="flex items-center gap-3 text-sm text-[#5C5C5C]">
                                        <span class="w-1.5 h-1.5 bg-black rounded-full shrink-0"></span>
                                        {{ trim($instruction) }}
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <!-- Dynamic Details -->
                @if($product->dynamic_details && is_array($product->dynamic_details))
                    @foreach($product->dynamic_details as $detail)
                        <div x-data="{ open: false }" class="py-4">
                            <button @click="open = !open" class="w-full flex justify-between items-center text-sm font-bold uppercase tracking-widest text-black">
                                <span>{{ $detail['title'] }}</span>
                                <i class="fas transition-transform duration-300" :class="open ? 'fa-minus' : 'fa-plus'"></i>
                            </button>
                            <div x-show="open" x-cloak x-transition class="mt-4 text-sm text-[#5C5C5C] leading-relaxed">
                                {!! nl2br(e($detail['content'])) !!}
                            </div>
                        </div>
                    @endforeach
                @endif

                <!-- Delivery & Returns -->
                <div x-data="{ open: false }" class="py-4">
                    <button @click="open = !open" class="w-full flex justify-between items-center text-sm font-bold uppercase tracking-widest text-black">
                        <span>Delivery & Returns</span>
                        <i class="fas transition-transform duration-300" :class="open ? 'fa-minus' : 'fa-plus'"></i>
                    </button>
                    <div x-show="open" x-cloak x-transition class="mt-4 text-sm text-[#5C5C5C] leading-relaxed">
                        <div class="prose prose-stone prose-sm max-w-none">
                            {!! nl2br(e($product->return_policy ?? 'Free standard delivery on orders over Rs. 5000. Returns are accepted within 7 days of purchase in original condition.')) !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Newsletter -->
            <div class="mt-10 pt-8 border-t border-[#5C5C5C]">
                <h4 class="font-bold text-sm uppercase tracking-widest text-black mb-2">Join the Club</h4>
                <p class="text-xs text-[#5C5C5C] mb-4">Subscribe to receive updates, access to exclusive deals, and more.</p>
                <form class="flex gap-0">
                    <input type="email" placeholder="ENTER YOUR EMAIL" class="flex-1 bg-transparent border border-[#5C5C5C] border-r-0 px-4 py-3 text-xs focus:border-black focus:ring-0 placeholder:text-[#808080] tracking-widest">
                    <button type="submit" class="bg-[#5C5C5C] text-white px-6 py-3 text-xs uppercase tracking-widest font-bold hover:bg-black transition-colors">Subscribe</button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
    <div class="mt-24 border-t border-[#5C5C5C] pt-16">
        <h3 class="text-3xl font-['Playfair_Display'] font-bold text-center mb-12">You May Also Like</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            @foreach($relatedProducts as $related)
                @include('partials.product-card', ['product' => $related])
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
function updateMainImage(src) {
    const mainImg = document.getElementById('main-product-image');
    mainImg.style.opacity = '0.5';
    setTimeout(() => {
        mainImg.src = src;
        mainImg.style.opacity = '1';
    }, 200);
}
</script>

<!-- Alpine.js for accordions (if not already loaded in layout, though simple JS toggle can work too) -->
<script src="//unpkg.com/alpinejs" defer></script>
@endsection
