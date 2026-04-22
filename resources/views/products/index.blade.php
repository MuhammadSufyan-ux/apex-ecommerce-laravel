@extends('layout')

@section('title', 'Shop - S4 Luxury Store')

@section('content')

@if(Route::is('home') && !request()->hasAny(['category', 'search', 'size', 'color', 'min_price', 'max_price']))
<!-- Hero Section with Slider (Only on main All/Home view) -->
<section class="relative h-[450px] lg:h-[400px] overflow-hidden group w-full mx-auto">
    <div class="swiper hero-swiper h-full w-full">
        <div class="swiper-wrapper">
             @php 
                $siteSettings = \App\Models\Setting::pluck('value', 'key')->toArray();
                $heroImages = json_decode($siteSettings['hero_images'] ?? '[]', true) ?: [];
            @endphp
            
            @forelse($heroImages as $img)
            <div class="swiper-slide relative">
                <img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 flex items-center justify-center text-center p-4">
                    <div class="p-8 md:p-12 transform transition-all duration-1000">
                        <h2 class="text-4xl md:text-6xl font-['Playfair_Display'] font-black mb-6 text-white uppercase tracking-tighter leading-none italic">New <br> Collection</h2>
                        <button class="inline-block px-12 py-4 bg-white text-black text-[12px] font-black tracking-[0.4em] uppercase hover:bg-black hover:text-white transition-all hover:scale-105 transform active:scale-95">Discover More</button>
                    </div>
                </div>
            </div>
            @empty
            <div class="swiper-slide relative">
                <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover">
                <div class="absolute inset-0 flex items-center justify-center text-center p-4">
                    <div class="p-6 md:p-10 transform transition-all duration-1000">
                        <h2 class="text-3xl md:text-5xl font-['Playfair_Display'] font-bold mb-4 text-white uppercase tracking-tighter leading-none">Summer <br> Collection</h2>
                        <a href="{{ route('products.index') }}" class="inline-block px-10 py-3 bg-white text-black text-[10px] md:text-[12px] font-bold tracking-[0.4em] uppercase hover:bg-black hover:text-white transition-all hover:scale-105 transform active:scale-95">Discover More</a>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
        <div class="swiper-button-next !text-white opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="swiper-button-prev !text-white opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="swiper-pagination"></div>
    </div>
</section>

<!-- Black Scrolling Marquee -->
<div class="bg-black py-4 border-y border-white/10 overflow-hidden group/marquee relative z-20">
    <div class="flex whitespace-nowrap animate-marquee group-hover/marquee:[animation-play-state:paused]">
        @php
            $marqueeText = "NEW ARRIVALS NOW LIVE &nbsp;|&nbsp; COMPLIMENTARY SHIPPING ON ALL MASTERPIECES OVER RS. 5,000 &nbsp;|&nbsp; PREMIUM QUALITY GUARANTEED &nbsp;|&nbsp; EXCLUSIVE LUXURY COLLECTION &nbsp;&bull;&nbsp; ";
        @endphp
        <div class="flex items-center px-4">
            @for($i = 0; $i < 4; $i++)
                <span class="text-[11px] md:text-sm font-bold text-white uppercase tracking-[0.15em] whitespace-nowrap px-4 font-mono">
                    {!! $marqueeText !!}
                </span>
            @endfor
        </div>
    </div>
</div>
@endif
<!-- Minimal Breadcrumb -->
<div class="bg-white py-8">
    <div class="container mx-auto px-4 max-w-screen-xl flex flex-col md:flex-row items-center justify-between">
        <div class="text-xs uppercase tracking-widest text-[#5C5C5C] flex items-center gap-2 mb-4 md:mb-0">
            <a href="{{ route('home') }}" class="hover:text-black transition-colors">Home</a>
             <span>/</span>
             <span class="text-black font-bold">
                @if(request('category'))
                    @php 
                        $cat = $categories->where('slug', request('category'))->first() ?? $categories->where('id', request('category'))->first();
                    @endphp
                    {{ $cat ? $cat->name : ucfirst(request('category')) }}
                @elseif(request('search'))
                    Search: "{{ request('search') }}"
                @else
                    All
                @endif
             </span>
        </div>
        
    </div>
</div>

<div class="py-12 bg-white">
    <div class="container mx-auto px-4 max-w-screen-xl flex flex-col lg:flex-row gap-12">
        <!-- Sidebar Filters -->
        <aside class="lg:w-1/4 space-y-8">
            <div class="border-b border-[#5C5C5C] pb-4 mb-8">
                <h2 class="text-xl font-['Playfair_Display'] font-bold text-black">Filters</h2>
            </div>
            
            <form method="GET" action="{{ route('products.index') }}" id="filter-form" class="space-y-6">
                <!-- Search -->
                <div>
                     <label class="block text-xs font-bold uppercase tracking-widest text-[#5C5C5C] mb-2">Search</label>
                     <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." 
                            class="w-full px-4 py-3 bg-white border border-transparent focus:border-[#5C5C5C] focus:bg-white transition-all outline-none text-sm">
                </div>

                <!-- Category -->
                <div>
                     <label class="block text-xs font-bold uppercase tracking-widest text-[#5C5C5C] mb-2">Category</label>
                     <select name="category" onchange="this.form.submit()" class="w-full px-4 py-3 bg-white border border-transparent focus:border-[#5C5C5C] focus:bg-white transition-all outline-none text-sm appearance-none">
                         <option value="">All Categories</option>
                         @foreach($categories as $category)
                             <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                 {{ $category->name }}
                             </option>
                         @endforeach
                     </select>
                </div>

                <!-- Sort By -->
                <div>
                     <label class="block text-xs font-bold uppercase tracking-widest text-[#5C5C5C] mb-2">Sort By</label>
                     <select name="sort" onchange="this.form.submit()" class="w-full px-4 py-3 bg-white border border-transparent focus:border-[#5C5C5C] focus:bg-white transition-all outline-none text-sm appearance-none">
                         <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>New Arrivals</option>
                         <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                         <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                         <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Alphabetical (A-Z)</option>
                         <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Classic Collection</option>
                     </select>
                </div>

                <!-- Size (Collapsible) -->
                <div x-data="{ open: true }" class="border-t border-[#5C5C5C] pt-4">
                    <button type="button" @click="open = !open" class="flex justify-between items-center w-full mb-2">
                        <span class="text-xs font-bold uppercase tracking-widest text-[#5C5C5C]">Size</span>
                        <i class="fas fa-chevron-down text-xs transition-transform transform" :class="{ 'rotate-180': !open }"></i>
                    </button>
                    <div x-show="open" class="space-y-2">
                        @foreach(['XS', 'S', 'M', 'L', 'XL'] as $size)
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" name="size[]" value="{{ $size }}" 
                                    {{ in_array($size, (array)request('size')) ? 'checked' : '' }}
                                    class="w-4 h-4 rounded-none border-[#5C5C5C] text-black focus:ring-black">
                                <span class="text-xs text-[#5C5C5C] group-hover:text-black uppercase tracking-wide">{{ $size }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Color (Collapsible) -->
                <div x-data="{ open: true }" class="border-t border-[#5C5C5C] pt-4">
                    <button type="button" @click="open = !open" class="flex justify-between items-center w-full mb-3">
                        <span class="text-xs font-bold uppercase tracking-widest text-[#5C5C5C]">Color</span>
                        <i class="fas fa-chevron-down text-xs transition-transform transform" :class="{ 'rotate-180': !open }"></i>
                    </button>
                    <div x-show="open" class="grid grid-cols-2 gap-2">
                        @foreach(['Black', 'White', 'Red', 'Blue', 'Green', 'Yellow', 'Pink', 'Purple', 'Orange', 'Brown'] as $color)
                            <label class="cursor-pointer group relative">
                                <input type="checkbox" name="color[]" value="{{ $color }}" 
                                    {{ in_array($color, (array)request('color')) ? 'checked' : '' }}
                                    class="peer sr-only">
                                <div class="px-2 py-1.5 border border-gray-200 text-[10px] font-bold uppercase tracking-widest text-center transition-all peer-checked:bg-black peer-checked:text-white peer-checked:border-black hover:border-black">
                                    {{ $color }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
                
                <!-- Price Range -->
                <div class="border-t border-[#5C5C5C] pt-4">
                     <label class="block text-xs font-bold uppercase tracking-widest text-[#5C5C5C] mb-2">Price Range</label>
                     <div class="grid grid-cols-2 gap-4">
                         <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" 
                                class="w-full px-4 py-3 bg-white border border-transparent focus:border-[#5C5C5C] focus:bg-white transition-all outline-none text-sm">
                         <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" 
                                class="w-full px-4 py-3 bg-white border border-transparent focus:border-[#5C5C5C] focus:bg-white transition-all outline-none text-sm">
                     </div>
                </div>

                <button type="submit" class="w-full py-4 bg-[#5C5C5C] text-white font-bold uppercase tracking-widest hover:bg-black transition-colors">
                    Apply Filters
                </button>
            </form>
        </aside>

        <!-- Product Grid -->
        <main class="lg:w-3/4">
              <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-8 transition-all duration-500">
                 @forelse($products as $product)
                     @include('partials.product-card', ['product' => $product])
                 @empty
                     <div class="col-span-full py-20 text-center">
                         <p class="text-[#5C5C5C] text-lg">No products found matching your criteria.</p>
                         <a href="{{ route('products.index') }}" class="inline-block mt-4 text-black border-b border-black hover:text-[#5C5C5C] hover:border-[#5C5C5C] transition-colors">Clear Filters</a>
                     </div>
                 @endforelse
             </div>
             
             <!-- Pagination -->
             <div class="mt-12">
                 {{ $products->withQueryString()->links() }}
             </div>
        </main>
    </div>
</div>

<script src="//unpkg.com/alpinejs" defer></script>
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    @keyframes marquee {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    .animate-marquee {
        animation: marquee 100s linear infinite;
        width: max-content;
        display: flex;
    }
    .hero-swiper .swiper-pagination-bullet { background: white; width: 10px; height: 10px; opacity: 0.5; }
    .hero-swiper .swiper-pagination-bullet-active { opacity: 1; width: 25px; border-radius: 5px; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (document.querySelector('.hero-swiper')) {
            new Swiper('.hero-swiper', {
                loop: true,
                effect: 'fade',
                autoplay: { delay: 5000 },
                pagination: { el: '.swiper-pagination', clickable: true },
                navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
            });
        }
    });
</script>
@endpush
@endsection
