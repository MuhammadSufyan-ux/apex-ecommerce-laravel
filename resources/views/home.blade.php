@extends('layout')

@section('title', 'S4 Luxury Store - Premium Fashion')
@section('meta_description', 'Discover the future of fashion at 4S Luxury Store. Premium clothing, accessories, and exclusive collections.')

@section('content')

<!-- Hero Section with Slider -->
<section class="relative h-[450px] lg:h-[250px] overflow-hidden group w-full mx-auto">
    <!-- Swiper Hero -->
    <div class="swiper hero-swiper h-full w-full">
        <div class="swiper-wrapper">
            @php 
                $heroImages = json_decode($siteSettings['hero_images'] ?? '[]', true) ?: [];
            @endphp
            
            @if(count($heroImages) > 0)
                @foreach($heroImages as $img)
                <div class="swiper-slide relative">
                    <img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 flex items-center justify-center text-center p-4">
                        <div class="p-6 md:p-10 transform transition-all duration-1000">
                            <h2 class="text-3xl md:text-5xl font-serif font-bold mb-4 text-white uppercase tracking-tighter leading-none">Elevated <br> Elegance</h2>
                            <a href="{{ route('products.index') }}" class="inline-block px-10 py-3 bg-white text-black text-[10px] md:text-[12px] font-bold tracking-[0.4em] uppercase hover:bg-black hover:text-white transition-all">Discover More</a>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <!-- Fallback Slide 1 -->
                <div class="swiper-slide relative">
                    <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover">
                    <div class="absolute inset-0 flex items-center justify-center text-center p-4">
                        <div class="p-6 md:p-10 transform transition-all duration-1000">
                            <h2 class="text-3xl md:text-5xl font-['Playfair_Display'] font-bold mb-4 text-white uppercase tracking-tighter leading-none">Summer <br> Collection</h2>
                            <a href="{{ route('products.index') }}" class="inline-block px-10 py-3 bg-white text-black text-[10px] md:text-[12px] font-bold tracking-[0.4em] uppercase hover:bg-black hover:text-white transition-all hover:scale-105 transform active:scale-95">Discover More</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <!-- Navigation -->
        <div class="swiper-button-next !text-white opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <div class="swiper-button-prev !text-white opacity-0 group-hover:opacity-100 transition-opacity"></div>
        <!-- Pagination -->
        <div class="swiper-pagination"></div>
    </div>
</section>

<!-- Black Scrolling Marquee -->
<div class="bg-black py-4 border-y border-white/10 overflow-hidden group/marquee relative z-20">
    <div class="flex whitespace-nowrap animate-marquee group-hover/marquee:[animation-play-state:paused]">
        @php
            $marqueeText = "TIME: " . now()->format('h:i A') . " &nbsp;|&nbsp; DATE: " . now()->format('D, M d, Y') . " &nbsp;|&nbsp; STORE: S4 LUXURY STORE &nbsp;|&nbsp; PROPRIETOR: SAFDAR ALI &nbsp;|&nbsp; LOCATION: ZIYARAT ROAD TORDHER, SWABI &nbsp;|&nbsp; CONTACTS: MUHAMMAD SUFYAN (03429748731), MUHAMMAD SALMAN (03369480148) &nbsp;&bull;&nbsp; ";
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


@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    @keyframes marquee {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    .animate-marquee {
        animation: marquee 150s linear infinite;
        width: max-content;
        display: flex;
    }
    @media (max-width: 768px) {
        .animate-marquee {
            animation: marquee 80s linear infinite;
        }
    }
    .pause-marquee {
        animation-play-state: paused;
    }
    .hero-swiper .swiper-pagination-bullet {
        background: white;
        width: 12px;
        height: 12px;
        opacity: 0.5;
    }
    .hero-swiper .swiper-pagination-bullet-active {
        opacity: 1;
        width: 30px;
        border-radius: 6px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const swiper = new Swiper('.hero-swiper', {
            loop: true,
            effect: 'fade',
            fadeEffect: { crossFade: true },
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    });
</script>
@endpush


<!-- Shop by Category (Dotted Design) -->
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-[#808080] uppercase tracking-[0.2em] text-sm">Collections</span>
            <h2 class="text-4xl font-['Playfair_Display'] font-bold mt-2 text-gray-900">Shop by Category</h2>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-8">
            @for($i = 1; $i <= 4; $i++)
                @php
                    $defaultImgs = [
                        1 => 'https://images.unsplash.com/photo-1594136975306-b3c959c99684?w=500&h=700&fit=crop',
                        2 => 'https://images.unsplash.com/photo-1623940250616-2fd1f7d2f939?w=500&h=700&fit=crop',
                        3 => 'https://images.unsplash.com/photo-1566174053879-31528523f8ae?w=500&h=700&fit=crop',
                        4 => 'https://images.unsplash.com/photo-1485968579580-b6d095142e6e?w=500&h=700&fit=crop'
                    ];
                    $defaultLabels = [1 => 'UNSTITCHED', 2 => 'READY TO WEAR', 3 => 'FORMAL', 4 => 'SHAWLS'];
                    $defaultSubs = [1 => 'Premium Fabric', 2 => 'Stitched Perfection', 3 => 'Luxury Events', 4 => 'Winter Elegance'];
                    $customImg = $siteSettings['cat_img_'.$i] ?? null;
                @endphp
                
                <a href="{{ route('products.index', ['category' => $i]) }}" class="group block relative overflow-hidden h-full">
                    <div class="border border-dashed border-[#5C5C5C]/50 h-full transition-all duration-300 group-hover:bg-gray-50 flex flex-col">
                        <div class="aspect-[3/4] overflow-hidden mb-4 relative">
                            <img src="{{ $customImg ? asset('storage/' . $customImg) : $defaultImgs[$i] }}" alt="{{ $defaultLabels[$i] }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        </div>
                        <div class="text-center p-4">
                            <h3 class="text-xl font-bold text-gray-900 group-hover:text-[#808080] transition-colors uppercase tracking-tighter">{{ $defaultLabels[$i] }}</h3>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mt-1">{{ $defaultSubs[$i] }}</p>
                        </div>
                    </div>
                </a>
            @endfor
        </div>
    </div>
</section>


<!-- Dynamic Sections -->
@foreach($sections as $section)
    @if($section->products->count() > 0)
        @if($section->scroll_type === 'horizontal')
            <!-- Horizontal Slider Section -->
            <section class="py-20 bg-{{ $loop->even ? 'white' : 'gray-50' }}">
                <div class="container mx-auto px-4 max-w-7xl">
                    <div class="flex flex-col md:flex-row justify-between items-center md:items-end mb-12 gap-6">
                        <div class="text-center md:text-left">
                            <span class="text-[#808080] uppercase tracking-[0.2em] text-sm">{{ $section->name }}</span>
                            <h2 class="text-4xl font-['Playfair_Display'] font-bold mt-2 text-gray-900">{{ $section->title ?? $section->name }}</h2>
                        </div>
                        <a href="{{ route('products.index') }}" class="text-[#5C5C5C] hover:text-black font-semibold border-b border-[#5C5C5C] pb-1 uppercase text-xs tracking-widest">View All</a>
                    </div>
                    
                    <!-- Horizontal Swiper -->
                    <div class="swiper section-swiper-{{ $section->id }} relative">
                        <div class="swiper-wrapper">
                            @foreach($section->products as $product)
                                <div class="swiper-slide">
                                    @include('partials.product-card', ['product' => $product])
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-button-next !text-black"></div>
                        <div class="swiper-button-prev !text-black"></div>
                    </div>
                </div>
            </section>
            
            @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    new Swiper('.section-swiper-{{ $section->id }}', {
                        slidesPerView: 2,
                        spaceBetween: 10,
                        navigation: {
                            nextEl: '.section-swiper-{{ $section->id }} .swiper-button-next',
                            prevEl: '.section-swiper-{{ $section->id }} .swiper-button-prev',
                        },
                        breakpoints: {
                            768: { slidesPerView: 3, spaceBetween: 30 },
                            1024: { slidesPerView: 4, spaceBetween: 30 },
                        }
                    });
                });
            </script>
            @endpush
        @else
            <!-- Vertical Grid Section -->
            <section class="py-20 bg-{{ $loop->even ? 'white' : 'gray-50' }}" x-data="{ limit: 4 }">
                <div class="container mx-auto px-4 max-w-7xl">
                    <div class="flex flex-col md:flex-row justify-between items-center md:items-end mb-12 gap-6">
                        <div class="text-center md:text-left">
                            <span class="text-[#808080] uppercase tracking-[0.2em] text-sm">{{ $section->name }}</span>
                            <h2 class="text-4xl font-['Playfair_Display'] font-bold mt-2 text-gray-900">{{ $section->title ?? $section->name }}</h2>
                        </div>
                        <div class="flex gap-4">
                            <a href="{{ route('products.index') }}" class="text-[#5C5C5C] hover:text-black font-semibold border-b border-[#5C5C5C] pb-1 uppercase text-xs tracking-widest">View All</a>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-8 transition-all duration-500">
                        @foreach($section->products as $index => $product)
                            <div x-show="{{ $index }} < limit" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                                @include('partials.product-card', ['product' => $product])
                            </div>
                        @endforeach
                    </div>

                    @if($section->products->count() > 4)
                        <div class="mt-16 text-center" x-show="limit < {{ $section->products->count() }}">
                            <button @click="limit += 4" class="px-12 py-4 bg-white border-2 border-black text-black text-[11px] font-black uppercase tracking-[0.4em] hover:bg-black hover:text-white transition-all hover:-translate-y-1">
                                Load More Arrivals
                            </button>
                        </div>
                    @endif
                </div>
            </section>
        @endif
    @endif
@endforeach

<!-- Modern Clean FAQs -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="text-center mb-16">
            <span class="text-[#808080] uppercase tracking-[0.2em] text-sm">Need Help?</span>
            <h2 class="text-4xl font-['Playfair_Display'] font-bold mt-2 text-gray-900">Frequently Asked Questions</h2>
        </div>
        
        <div class="space-y-4">
            @forelse($faqs as $faq)
                <div class="border border-[#5C5C5C] bg-white rounded-none overflow-hidden group">
                    <button class="w-full flex items-center justify-between p-6 text-left focus:outline-none" onclick="toggleFaq('faq{{ $faq->id }}')">
                        <span class="text-lg font-semibold text-gray-900 group-hover:text-[#808080] transition-colors uppercase tracking-tight">{{ $faq->question }}</span>
                        <i class="fas fa-plus text-[#5C5C5C] transition-transform duration-300" id="icon-faq{{ $faq->id }}"></i>
                    </button>
                    <div id="faq{{ $faq->id }}" class="hidden px-6 pb-6 text-gray-600 border-t border-dashed border-[#5C5C5C] pt-4">
                        <p class="text-xs font-medium leading-relaxed">{{ $faq->answer }}</p>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center opacity-30">
                    <p class="text-[10px] font-black uppercase tracking-[0.4em]">Knowledge Base offline</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

@push('scripts')
<script>
    function toggleFaq(id) {
        const content = document.getElementById(id);
        const icon = document.getElementById('icon-' + id);
        
        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            icon.classList.remove('fa-plus');
            icon.classList.add('fa-minus');
        } else {
            content.classList.add('hidden');
            icon.classList.remove('fa-minus');
            icon.classList.add('fa-plus');
        }
    }
</script>
@endpush
@endsection
