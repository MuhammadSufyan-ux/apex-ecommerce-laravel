@extends('layout')

@section('title', 'My Favorites - S4 Luxury Store')

@section('content')
<div class="container mx-auto px-4 py-8 md:py-16">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-['Playfair_Display'] font-bold text-gray-900 mb-4">My Favorites</h1>
        <p class="text-gray-600">Your curated list of favorite items</p>
    </div>

    @if($favorites->count() > 0)
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 transition-all duration-500">
            @foreach($favorites as $favorite)
                @php
                    $product = $favorite->product;
                    $imageUrl = $product->images->first() 
                        ? asset('storage/' . $product->images->first()->image_path) 
                        : 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=400&h=600&fit=crop';
                @endphp
                
                <div class="group bg-white product-card rounded-none overflow-hidden relative border border-[#5C5C5C]">
                    <!-- Image -->
                    <div class="relative aspect-[3/4] overflow-hidden bg-gray-100">
                        <img src="{{ $imageUrl }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        
                        <!-- Quick Add Button Overlay -->
                        <div class="absolute inset-x-0 bottom-0 p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                            <button onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, '{{ $imageUrl }}')" 
                                    class="w-full bg-[#5C5C5C] text-white py-3 font-semibold hover:bg-black transition-colors uppercase text-xs tracking-widest">
                                Add to Cart
                            </button>
                        </div>
                        
                        <!-- Remove Button -->
                        <button onclick="removeFromFavorites({{ $product->id }})" 
                                class="absolute top-3 right-3 w-8 h-8 flex items-center justify-center bg-white rounded-full text-red-500 hover:text-red-700 hover:bg-gray-50 transition-colors z-10 border border-gray-100">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-4 text-center">
                        <span class="text-[#5C5C5C] text-xs font-semibold uppercase tracking-wider block mb-1">{{ $product->category->name }}</span>
                        <h3 class="font-medium text-gray-900 mb-1 truncate">
                            <a href="{{ route('products.show', $product->slug) }}" class="hover:text-black transition-colors">{{ $product->name }}</a>
                        </h3>
                        <p class="text-[#5C5C5C] font-semibold">Rs. {{ number_format($product->price) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-20 bg-gray-50 border border-dashed border-[#5C5C5C]">
            <i class="far fa-heart text-6xl text-gray-300 mb-4"></i>
            <h2 class="text-2xl font-semibold text-gray-900 mb-2">No Favorites Yet</h2>
            <p class="text-gray-500 mb-8">Start exploring our collection and save your favorite items here.</p>
            <a href="{{ route('products.index') }}" class="inline-block px-8 py-3 bg-[#5C5C5C] text-white font-semibold hover:bg-[#808080] transition-colors uppercase tracking-widest">
                Browse Products
            </a>
        </div>
    @endif
</div>
@endsection
