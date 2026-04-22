<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $siteSettings['seo_description'] ?? 'S4 Luxury Store - Premium Fashion Collection' }}">
    <meta name="keywords" content="{{ $siteSettings['seo_keywords'] ?? 'fashion, luxury, clothing' }}">
    <title>@yield('title', $siteSettings['seo_title'] ?? ($siteSettings['store_name'] ?? 'S4 Luxury Store'))</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('s4_store_logo.png') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair_Display:wght@700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @php
        $primaryColor = $siteSettings['primary_color'] ?? '#D4AF37';
        $fontFamily = $siteSettings['font_family'] ?? "'Outfit', sans-serif";
        $fontSize = $siteSettings['base_font_size'] ?? '16px';
    @endphp

    <style>
        :root {
            --primary-color: {{ $primaryColor }};
        }
        body {
            font-family: {!! $fontFamily !!} !important;
            font-size: {{ $fontSize }};
        }
        .text-gold, .text-\[\#D4AF37\] { color: var(--primary-color) !important; }
        .bg-gold, .bg-\[\#D4AF37\] { background-color: var(--primary-color) !important; }
        .border-gold, .border-\[\#D4AF37\] { border-color: var(--primary-color) !important; }
        
        [x-cloak] { display: none !important; }
        
        /* Layout overrides */
        header { border-bottom-color: var(--primary-color) !important; }
    </style>
    
    @stack('styles')
</head>
<body class="bg-white font-['Inter']">

    <!-- Header -->
    <header class="sticky top-0 z-50 bg-white border-b border-[#5C5C5C]">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                
                <!-- Left Section: Mobile Menu & Logo -->
                <div class="flex items-center gap-4">
                    <button onclick="toggleMobileMenu()" class="lg:hidden text-[rgb(92,92,92)] hover:text-black transition-colors focus:outline-none">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                    
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                        @if(isset($siteSettings['store_logo']) && $siteSettings['store_logo'])
                            <img src="{{ asset('storage/' . $siteSettings['store_logo']) }}" alt="{{ $siteSettings['store_name'] ?? 'Store' }}" class="h-14 w-auto object-contain transition-transform group-hover:scale-105">
                        @else
                            <img src="{{ asset('s4_store_logo.png') }}" alt="Logo" class="h-14 w-auto object-contain transition-transform group-hover:scale-105">
                        @endif
                        <div class="flex flex-col leading-none ml-1">
                            <span class="text-sm font-black tracking-tighter text-gold">S4</span>
                            <span class="text-[7px] font-bold uppercase tracking-[0.15em] text-black">Luxury Store</span>
                        </div>
                    </a>
                </div>

                <nav class="hidden lg:flex items-center gap-8">
                    <a href="{{ route('products.index') }}" class="text-[12px] font-bold uppercase tracking-[0.2em] text-black hover:text-[#808080] transition-colors border-b-2 border-transparent hover:border-black py-1">ALL</a>
                    <a href="{{ route('products.index', ['category' => 'sale']) }}" class="text-[12px] font-bold uppercase tracking-[0.2em] text-red-600 hover:text-red-700 transition-colors border-b-2 border-transparent hover:border-red-600 py-1">SALE</a>
                    <a href="{{ route('products.index', ['category' => 'man']) }}" class="text-[12px] font-bold uppercase tracking-[0.2em] text-black hover:text-[#808080] transition-colors">MAN</a>
                    <a href="{{ route('products.index', ['category' => 'women']) }}" class="text-[12px] font-bold uppercase tracking-[0.2em] text-black hover:text-[#808080] transition-colors">WOMEN</a>
                    <a href="{{ route('products.index', ['category' => 'kids']) }}" class="text-[12px] font-bold uppercase tracking-[0.2em] text-black hover:text-[#808080] transition-colors">KIDS</a>
                    <a href="{{ route('products.index', ['category' => 'accessories']) }}" class="text-[12px] font-bold uppercase tracking-[0.2em] text-black hover:text-[#808080] transition-colors">ACCESSORIES</a>
                    <a href="{{ route('products.index', ['category' => 'new']) }}" class="text-[12px] font-bold uppercase tracking-[0.2em] text-black hover:text-[#808080] transition-colors">NEW</a>
                    <a href="{{ route('products.index', ['category' => '2-piece']) }}" class="text-[12px] font-bold uppercase tracking-[0.2em] text-black hover:text-[#808080] transition-colors">2 PIECE</a>
                    <a href="{{ route('products.index', ['category' => '3-piece']) }}" class="text-[12px] font-bold uppercase tracking-[0.2em] text-black hover:text-[#808080] transition-colors">3 PIECE</a>
                </nav>

                <!-- Right Section: Icons -->
                <div class="flex items-center gap-5 text-[rgb(92,92,92)]">
                    <button onclick="toggleSearch()" class="hover:text-black transition-colors p-1">
                        <i class="fas fa-search text-xl"></i>
                    </button>
                    
                    @if (Route::has('login'))
                        @auth
                            <!-- User Dropdown (Desktop & Tablet) -->
                            <div class="relative group flex items-center">
                                <button class="w-10 h-10 rounded-full bg-black text-white flex items-center justify-center font-bold text-sm tracking-tight hover:bg-gray-800 transition-all border border-black uppercase shrink-0 overflow-hidden">
                                    @if(Auth::user()->profile_image)
                                        <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" class="w-full h-full object-cover">
                                    @else
                                        @php
                                            $names = explode(' ', Auth::user()->name);
                                            $initials = count($names) >= 2 
                                                ? substr($names[0], 0, 1) . substr(end($names), 0, 1)
                                                : substr($names[0], 0, 2);
                                        @endphp
                                        {{ $initials }}
                                    @endif
                                </button>
                                <!-- Dropdown Menu -->
                                <div class="absolute top-full right-0 mt-3 w-64 bg-white border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-[60] py-2 rounded-sm transform origin-top scale-95 group-hover:scale-100">
                                    <div class="px-5 py-4 border-b border-gray-50 mb-1 flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden shrink-0">
                                            @if(Auth::user()->profile_image)
                                                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-xs font-bold">{{ $initials }}</span>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-0.5">Welcome back,</p>
                                            <p class="text-sm font-bold text-black truncate">{{ Auth::user()->name }}</p>
                                        </div>
                                    </div>
                                    <div class="py-1">
                                        <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-5 py-3 text-xs text-gray-600 hover:text-black hover:bg-white transition-all duration-200">
                                            <div class="w-5 h-5 flex items-center justify-center">
                                                <i class="fas fa-shopping-bag text-sm opacity-40"></i>
                                            </div>
                                            <span class="font-medium tracking-wide uppercase">Order History</span>
                                        </a>
                                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-5 py-3 text-xs text-gray-600 hover:text-black hover:bg-white transition-all duration-200">
                                            <div class="w-5 h-5 flex items-center justify-center">
                                                <i class="fas fa-user-circle text-sm opacity-40"></i>
                                            </div>
                                            <span class="font-medium tracking-wide uppercase">My Profile</span>
                                        </a>
                                        
                                        @if(Auth::user()->role === 'admin')
                                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-5 py-3 text-xs text-red-600 hover:text-black hover:bg-white transition-all duration-200 border-t border-gray-50 mt-1 pt-3">
                                                <div class="w-5 h-5 flex items-center justify-center">
                                                    <i class="fas fa-user-shield text-sm opacity-40"></i>
                                                </div>
                                                <span class="font-medium tracking-wide uppercase">Admin Panel</span>
                                            </a>
                                        @endif
                                    </div>
                                    
                                    <div class="mt-1 pt-1 border-t border-gray-50">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="w-full flex items-center gap-3 px-5 py-4 text-xs text-red-600 hover:bg-red-50 transition-all duration-200">
                                                <div class="w-5 h-5 flex items-center justify-center">
                                                    <i class="fas fa-sign-out-alt text-sm opacity-60"></i>
                                                </div>
                                                <span class="font-bold tracking-[0.1em] uppercase">Log Out</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="hover:text-black transition-colors p-1" title="Login">
                                <i class="far fa-user text-xl"></i>
                            </a>
                        @endauth
                    @endif

                    <button onclick="toggleFavorites()" class="relative hover:text-black transition-colors p-1 group">
                        <i class="far fa-heart text-xl group-hover:font-solid"></i>
                        <span id="favorites-count" class="absolute -top-1 -right-2 bg-black text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">0</span>
                    </button>

                    <button onclick="toggleCart()" class="relative hover:text-black transition-colors p-1">
                        <i class="fas fa-shopping-bag text-xl"></i>
                        <span id="cart-count" class="absolute -top-1 -right-2 bg-black text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">0</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Menu Sidebar -->
    <div id="mobile-menu" class="fixed top-0 left-0 h-full w-80 bg-white border-r border-gray-100 z-50 transform -translate-x-full transition-transform duration-300 overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('s4_store_logo.png') }}" alt="S4 Luxury Store" class="h-10 w-auto object-contain">
                    <div class="flex flex-col leading-none">
                        <span class="text-sm font-black tracking-tighter text-gold">S4</span>
                        <span class="text-[7px] font-bold uppercase tracking-[0.1em] text-black">Luxury Store</span>
                    </div>
                </div>
                <button onclick="toggleMobileMenu()" class="text-gray-500 hover:text-[#808080]">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <nav class="space-y-2 mt-4">
                <a href="{{ route('products.index') }}" class="block py-5 px-4 text-black font-black text-[11px] tracking-[0.2em] uppercase border-b border-gray-50 last:border-0 hover:bg-white transition-colors">ALL</a>
                <a href="{{ route('products.index', ['category' => 'sale']) }}" class="block py-5 px-4 text-red-600 font-black text-[11px] tracking-[0.2em] uppercase border-b border-gray-50 last:border-0 hover:bg-white transition-colors">SALE</a>
                <a href="{{ route('products.index', ['category' => 'man']) }}" class="block py-5 px-4 text-black font-black text-[11px] tracking-[0.2em] uppercase border-b border-gray-50 last:border-0 hover:bg-white transition-colors">MAN</a>
                <a href="{{ route('products.index', ['category' => 'women']) }}" class="block py-5 px-4 text-black font-black text-[11px] tracking-[0.2em] uppercase border-b border-gray-50 last:border-0 hover:bg-white transition-colors">WOMEN</a>
                <a href="{{ route('products.index', ['category' => 'kids']) }}" class="block py-5 px-4 text-black font-black text-[11px] tracking-[0.2em] uppercase border-b border-gray-50 last:border-0 hover:bg-white transition-colors">KIDS</a>
                <a href="{{ route('products.index', ['category' => 'accessories']) }}" class="block py-5 px-4 text-black font-black text-[11px] tracking-[0.2em] uppercase border-b border-gray-50 last:border-0 hover:bg-white transition-colors">ACCESSORIES</a>
                <a href="{{ route('products.index', ['category' => 'new']) }}" class="block py-5 px-4 text-black font-black text-[11px] tracking-[0.2em] uppercase border-b border-gray-50 last:border-0 hover:bg-white transition-colors">NEW</a>
                <a href="{{ route('products.index', ['category' => '2-piece']) }}" class="block py-5 px-4 text-black font-black text-[11px] tracking-[0.2em] uppercase border-b border-gray-50 last:border-0 hover:bg-white transition-colors">2 PIECE</a>
                <a href="{{ route('products.index', ['category' => '3-piece']) }}" class="block py-5 px-4 text-black font-black text-[11px] tracking-[0.2em] uppercase border-b border-gray-50 last:border-0 hover:bg-white transition-colors">3 PIECE</a>
            </nav>
            <!-- Mobile Auth Buttons -->
                <div class="mt-8 border-t border-gray-200 pt-6 space-y-3 px-4">
                    @auth
                        <div class="flex items-center gap-4 mb-6">
                             <div class="w-12 h-12 bg-black text-white rounded-full flex items-center justify-center border border-black font-bold text-base uppercase shadow-lg overflow-hidden">
                                 @if(Auth::user()->profile_image)
                                     <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" class="w-full h-full object-cover">
                                 @else
                                     @php
                                         $names = explode(' ', Auth::user()->name);
                                         $initials = count($names) >= 2 
                                             ? substr($names[0], 0, 1) . substr(end($names), 0, 1)
                                             : substr($names[0], 0, 2);
                                     @endphp
                                     {{ $initials }}
                                 @endif
                             </div>
                             <div class="flex-1">
                                 <p class="font-bold text-base text-black tracking-tight leading-tight">{{ Auth::user()->name }}</p>
                                 <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">{{ Auth::user()->role }} Account</p>
                             </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 mb-4">
                            <a href="{{ route('orders.index') }}" class="flex items-center justify-between py-3 px-4 bg-white hover:bg-black hover:text-white transition-all rounded-sm group">
                                <span class="text-xs font-bold uppercase tracking-widest">Order History</span>
                                <i class="fas fa-shopping-bag text-[10px] opacity-30 group-hover:opacity-100"></i>
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center justify-between py-3 px-4 bg-white hover:bg-black hover:text-white transition-all rounded-sm group">
                                <span class="text-xs font-bold uppercase tracking-widest">Account Settings</span>
                                <i class="fas fa-cog text-[10px] opacity-30 group-hover:opacity-100"></i>
                            </a>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full py-4 border-2 border-red-500 text-red-500 text-center font-bold uppercase tracking-[0.2em] text-[10px] hover:bg-red-500 hover:text-white transition-all">
                                Log Out
                            </button>
                        </form>
                    @else
                        <p class="text-xs uppercase tracking-widest text-[#5C5C5C] mb-2 font-bold">My Account</p>
                        <a href="{{ route('login') }}" class="block w-full py-3 border border-[#5C5C5C] text-[#5C5C5C] text-center font-bold uppercase tracking-widest text-xs hover:bg-[#5C5C5C] hover:text-white transition-colors">
                            Log In
                        </a>
                        <a href="{{ route('register') }}" class="block w-full py-3 bg-[#5C5C5C] text-white text-center font-bold uppercase tracking-widest text-xs hover:bg-black transition-colors">
                            Sign Up
                        </a>
                    @endauth
                </div>
            </nav>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div id="mobile-menu-overlay" class="fixed inset-0 bg-black/50 z-40 hidden" onclick="toggleMobileMenu()"></div>

    <!-- Search Sidebar (Now a Sidebar like Favorites) -->
    <div id="search-overlay" class="fixed top-0 right-0 h-full w-full md:w-96 bg-white z-50 transform translate-x-full transition-transform duration-500 border-l border-gray-100 overflow-hidden" onclick="event.stopPropagation()">
        <div class="flex flex-col h-full">
            <!-- Header -->
            <div class="flex items-center justify-between p-8 border-b border-gray-50 bg-white/50">
                <div>
                    <h3 class="text-xl font-black text-black uppercase tracking-tighter">Search Store</h3>
                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-[0.2em] mt-1">Find your masterpiece</p>
                </div>
                <button onclick="toggleSearch()" class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-black hover:rotate-90 transition-all duration-300">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                <!-- Search Input Area -->
                <div class="mb-10">
                    <div class="relative group">
                        <input 
                            type="text" 
                            name="search" 
                            id="search-input"
                            placeholder="WHAT ARE YOU LOOKING FOR?" 
                            class="w-full bg-white border-b-2 border-gray-100 py-4 text-xs font-black uppercase tracking-[0.2em] focus:border-black focus:ring-0 placeholder:text-gray-200 transition-all"
                            autocomplete="off"
                            oninput="performLiveSearch(this.value)"
                        >
                        <i class="fas fa-search absolute right-0 top-1/2 -translate-y-1/2 text-gray-100 group-focus-within:text-black transition-colors"></i>
                    </div>
                </div>

                <!-- Live Search Results (Small Boxes Grid) -->
                <div id="live-search-results" class="hidden mb-12">
                    <h4 class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">Discovery Found</h4>
                    <div id="search-results-grid" class="grid grid-cols-2 gap-4">
                        <!-- Results injected here -->
                    </div>
                    <a href="#" id="view-all-search" class="block mt-8 py-4 border border-black text-center text-[10px] font-black uppercase tracking-[0.3em] hover:bg-black hover:text-white transition-all">View All Pieces</a>
                </div>
                
                <!-- History & Popular -->
                <div id="search-initial-state">
                    <!-- Recent Searches -->
                    <div id="recent-searches" class="mb-12">
                        <h4 class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">Recent Inquiries</h4>
                        <div id="recent-searches-list" class="space-y-3">
                            <!-- Recent searches -->
                        </div>
                    </div>
                    
                    <!-- Popular Searches -->
                    <div id="popular-searches">
                        <h4 class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">Trending Now</h4>
                        <div class="flex flex-wrap gap-2 text-center">
                            @foreach(['Lawn', 'Silk', 'Unstitched', 'Formal', 'Accessories'] as $tag)
                                <a href="{{ route('products.index', ['search' => strtolower($tag)]) }}" class="px-5 py-2.5 bg-white border border-gray-100 text-[9px] font-black uppercase tracking-widest text-black hover:bg-black hover:text-white transition-all">
                                    {{ $tag }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Favorites Sidebar -->
    <div id="favorites-sidebar" class="fixed top-0 right-0 h-full w-full md:w-96 bg-white z-50 transform translate-x-full transition-transform duration-300 border-l border-[#5C5C5C]">
        <div class="flex flex-col h-full">
            <div class="flex items-center justify-between p-6 border-b border-[#5C5C5C]">
                <h3 class="text-xl font-semibold">My Favorites</h3>
                <button onclick="toggleFavorites()" class="text-gray-500 hover:text-[#808080]">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <div id="favorites-list" class="flex-1 overflow-y-auto p-6">
                <p class="text-gray-500 text-center py-12">No favorites yet</p>
            </div>
        </div>
    </div>

    <!-- Cart Sidebar -->
    <div id="cart-sidebar" class="fixed top-0 right-0 h-full w-full md:w-96 bg-white z-50 transform translate-x-full transition-transform duration-500 border-l border-gray-100">
        <div class="flex flex-col h-full">
            <div class="flex items-center justify-between p-8 border-b border-gray-50">
                <div>
                    <h3 class="text-xl font-black text-black uppercase tracking-tighter">Your Bag</h3>
                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-[0.2em] mt-1">Ready for checkout</p>
                </div>
                <button onclick="toggleCart()" class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-black hover:rotate-90 transition-all duration-300">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <div id="cart-list" class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                <div class="flex flex-col items-center justify-center h-full text-center">
                    <i class="fas fa-shopping-bag text-5xl text-gray-100 mb-6"></i>
                    <p class="text-gray-400 font-bold text-[10px] uppercase tracking-widest">Your bag is empty</p>
                </div>
            </div>
            
            <div class="bg-white p-8 border-t border-gray-100">
                <div class="flex items-center justify-between mb-8">
                    <span class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">Estimated Total</span>
                    <span id="cart-subtotal" class="text-xl font-black text-black">Rs. 0</span>
                </div>
                <div class="space-y-4">
                    <a href="{{ route('checkout.index') }}" id="checkout-btn" class="block w-full py-5 bg-black text-white text-[11px] font-black tracking-[0.4em] text-center uppercase hover:bg-gray-800 transition-all">
                        Secure Checkout
                    </a>
                    <a href="{{ route('cart.index') }}" class="block w-full py-5 border border-black text-black text-[11px] font-black tracking-[0.4em] text-center uppercase hover:bg-black hover:text-white transition-all">
                        View Full Bag
                    </a>
                </div>
                <p class="text-[8px] text-gray-400 font-bold uppercase tracking-widest text-center mt-6">
                    Complimentary Shipping on orders over Rs. 5,000
                </p>
            </div>
        </div>
    </div>

    <!-- Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden" onclick="closeSidebars()"></div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-green-500/10 border border-green-500 text-green-500 px-6 py-4 rounded-lg animate-slide-in">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-red-500/10 border border-red-500 text-red-500 px-6 py-4 rounded-lg animate-slide-in">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Redesigned Footer -->
    <footer class="bg-black text-white pt-16 pb-10 mt-20 relative border-t border-white/10">
        <div class="container mx-auto px-4 relative z-10">
            <!-- Main Footer Grid: 2 columns on mobile, 4 on desktop -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8">
                <!-- Brand Identity -->
                <div class="col-span-2 lg:col-span-1 space-y-6">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        @if(isset($siteSettings['store_logo']) && $siteSettings['store_logo'])
                            <img src="{{ asset('storage/' . $siteSettings['store_logo']) }}" alt="Logo" class="h-16 w-auto object-contain">
                        @else
                            <img src="{{ asset('s4_store_logo.png') }}" alt="Logo" class="h-16 w-auto object-contain">
                        @endif
                        <div class="flex flex-col leading-none">
                            <span class="text-2xl font-black tracking-tighter text-gold">{{ $siteSettings['store_name'] ?? 'S4' }}</span>
                            <span class="text-[10px] font-bold uppercase tracking-[0.3em] text-white/70">Luxury Store</span>
                        </div>
                    </a>
                    <p class="text-sm text-gray-400 leading-relaxed font-light">
                        Premium craftsmanship and timeless elegance. Bringing you the best of high-end Pakistani fashion.
                    </p>
                    <div class="flex gap-4">
                        @php $socials = json_decode($siteSettings['social_links'] ?? '{}', true); @endphp
                        @if($socials['facebook'] ?? false)
                            <a href="{{ $socials['facebook'] }}" class="text-gray-400 hover:text-gold transition-colors"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if($socials['instagram'] ?? false)
                            <a href="{{ $socials['instagram'] }}" class="text-gray-400 hover:text-gold transition-colors"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if($socials['whatsapp'] ?? false)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $socials['whatsapp']) }}" class="text-gray-400 hover:text-green-500 transition-colors"><i class="fab fa-whatsapp"></i></a>
                        @endif
                        <a href="#" class="text-gray-400 hover:text-gold transition-colors"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>

                <!-- Navigation Column 1 -->
                <div class="col-span-1">
                    <h4 class="text-[11px] font-bold uppercase tracking-[0.3em] text-[#D4AF37] mb-8">Collections</h4>
                    <ul class="space-y-4">
                        @foreach(['sale' => 'Sale', 'man' => 'Man', 'women' => 'Women', 'kids' => 'Kids', 'new' => 'New'] as $slug => $label)
                        <li><a href="{{ route('products.index', ['category' => $slug]) }}" class="text-sm text-gray-400 hover:text-white transition-colors">{{ $label }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <!-- Navigation Column 2 -->
                <div class="col-span-1">
                    <h4 class="text-[11px] font-bold uppercase tracking-[0.3em] text-[#D4AF37] mb-8">Useful Links</h4>
                    <ul class="space-y-4">
                        <li><a href="{{ route('pages.about') }}" class="text-sm text-gray-400 hover:text-white transition-colors">About Us</a></li>
                        <li><a href="{{ route('pages.store-locator') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Store Locator</a></li>
                        <li><a href="{{ route('pages.shipping-policy') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Shipping Policy</a></li>
                        <li><a href="{{ route('pages.returns') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Returns</a></li>
                        <li><a href="{{ route('pages.privacy') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>

                <!-- Contact Column -->
                <div class="col-span-2 lg:col-span-1">
                    <h4 class="text-[11px] font-bold uppercase tracking-[0.3em] text-gold mb-8">Contact Info</h4>
                    <div class="space-y-6">
                        <a href="#" class="flex items-start gap-4 group">
                            <i class="fas fa-map-marker-alt text-gold mt-1 shrink-0 group-hover:scale-110 transition-transform"></i>
                            <p class="text-sm text-gray-400 leading-relaxed font-light group-hover:text-white transition-colors uppercase">
                                {{ $siteSettings['contact_address'] ?? 'Pakistan' }}
                            </p>
                        </a>
                        <div class="flex items-center gap-4">
                            <i class="fas fa-phone-alt text-gold shrink-0"></i>
                            <div class="flex flex-col">
                                <a href="tel:{{ $siteSettings['contact_phone'] ?? '' }}" class="text-sm text-gray-300 hover:text-gold transition-colors">{{ $siteSettings['contact_phone'] ?? '' }}</a>
                                <a href="mailto:{{ $siteSettings['contact_email'] ?? '' }}" class="text-sm text-gray-300 hover:text-gold transition-colors">{{ $siteSettings['contact_email'] ?? '' }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="mt-20 pt-8 border-t border-white/5">
                <div class="flex flex-col lg:flex-row justify-between items-center gap-8">
                    <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-gray-600">
                        &copy; 2026 S4 Luxury Store. All rights reserved.
                    </p>
                    
                    <div class="flex items-center gap-2">
                         <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-gray-600">Architected By</span>
                         <a href="https://lavenderblush-monkey-546500.hostingersite.com/" target="_blank" class="text-[10px] font-bold text-[#D4AF37] hover:text-white transition-all duration-300 uppercase tracking-widest">
                            M. Sufyan
                         </a>
                    </div>
                </div>
            </div>
    </footer>
    
    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/923369480148" target="_blank" class="fixed bottom-6 right-6 w-14 h-14 bg-green-500 rounded-full flex items-center justify-center text-white shadow-lg hover:bg-green-600 hover:scale-110 transition-all z-40">
        <i class="fab fa-whatsapp text-2xl"></i>
    </a>

    <script>
        // Update cart count
        function updateCartCount() {
            fetch('{{ route('cart.count') }}')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('cart-count').textContent = data.count;
                })
                .catch(error => console.error('Error:', error));
        }
        
        // Update on page load
        updateCartCount();
        
        // Toggle Mobile Menu
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const overlay = document.getElementById('mobile-menu-overlay');
            
            menu.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
        
        // Toggle Mobile Dropdown
        function toggleMobileDropdown(category) {
            const dropdown = document.getElementById(category + '-dropdown');
            const icon = document.getElementById(category + '-icon');
            
            dropdown.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }
        
        // Toggle Search Sidebar
        function toggleSearch() {
            const overlay = document.getElementById('search-overlay');
            const input = document.getElementById('search-input');
            const resContainer = document.getElementById('live-search-results');
            const initialState = document.getElementById('search-initial-state');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            
            overlay.classList.toggle('translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
            
            if (!overlay.classList.contains('translate-x-full')) {
                input.focus();
                loadRecentSearches();
                // If input is empty, ensure results are hidden
                if (input.value.length < 2) {
                    resContainer.classList.add('hidden');
                    initialState.classList.remove('hidden');
                }
            }
        }
        
        // Toggle Favorites Sidebar
        function toggleFavorites() {
            const sidebar = document.getElementById('favorites-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            sidebar.classList.toggle('translate-x-full');
            overlay.classList.toggle('hidden');
            
            if (!sidebar.classList.contains('translate-x-full')) {
                loadFavorites();
            }
        }
        
        // Add to Cart Functionality (Global)
        async function addToCart(id, name, price, image) {
            try {
                const response = await fetch('{{ route("cart.add") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ product_id: id, quantity: 1 })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    updateCartCount();
                    
                    // Show success message (simple toast)
                    const toast = document.createElement('div');
                    toast.className = 'fixed bottom-4 right-4 bg-[#5C5C5C] text-white px-6 py-3 rounded shadow-lg z-50 animate-slide-in-up';
                    toast.innerHTML = `<i class="fas fa-check-circle mr-2"></i> Added to cart`;
                    document.body.appendChild(toast);
                    
                    setTimeout(() => {
                        toast.remove();
                    }, 3000);
                    
                    // Open cart sidebar if closed, otherwise just reload
                    const sidebar = document.getElementById('cart-sidebar');
                    if (sidebar.classList.contains('translate-x-full')) {
                        toggleCart();
                    } else {
                        loadCart();
                    }
                } else {
                     // Check for specific errors like stock
                     const toast = document.createElement('div');
                     toast.className = 'fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded shadow-lg z-50 animate-slide-in-up';
                     toast.innerHTML = `<i class="fas fa-exclamation-circle mr-2"></i> ${data.message || 'Error adding to cart'}`;
                     document.body.appendChild(toast);
                     setTimeout(() => toast.remove(), 3000);
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
            }
        }
        
        // Toggle Cart Sidebar
        function toggleCart() {
            const sidebar = document.getElementById('cart-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            sidebar.classList.toggle('translate-x-full');
            overlay.classList.toggle('hidden');
            
            if (!sidebar.classList.contains('translate-x-full')) {
                loadCart();
            }
        }
        
        // Close all sidebars
        function closeSidebars() {
            document.getElementById('favorites-sidebar').classList.add('translate-x-full');
            document.getElementById('cart-sidebar').classList.add('translate-x-full');
            document.getElementById('search-overlay').classList.add('translate-x-full');
            document.getElementById('sidebar-overlay').classList.add('hidden');
        }
        
        // Local Storage Functions
        function saveToLocalStorage(key, value) {
            try {
                localStorage.setItem(key, JSON.stringify(value));
            } catch (e) {
                console.error('Error saving to localStorage:', e);
            }
        }
        
        function getFromLocalStorage(key) {
            try {
                const item = localStorage.getItem(key);
                return item ? JSON.parse(item) : null;
            } catch (e) {
                console.error('Error reading from localStorage:', e);
                return null;
            }
        }
        
        // Live Search Function
        let searchTimeout;
        async function performLiveSearch(query) {
            clearTimeout(searchTimeout);
            
            const resultsContainer = document.getElementById('live-search-results');
            const resultsGrid = document.getElementById('search-results-grid');
            const initialState = document.getElementById('search-initial-state');
            const viewAllLink = document.getElementById('view-all-search');
            
            if (!query || query.length < 2) {
                resultsContainer.classList.add('hidden');
                initialState.classList.remove('hidden');
                return;
            }

            initialState.classList.add('hidden');
            resultsContainer.classList.remove('hidden');

            searchTimeout = setTimeout(async () => {
                try {
                    resultsGrid.innerHTML = '<div class="col-span-2 py-10 text-center"><i class="fas fa-spinner fa-spin text-gray-200 text-2xl"></i></div>';

                    const response = await fetch(`{{ route('products.search') }}?q=${encodeURIComponent(query)}`);
                    const products = await response.json();

                    if (products.length === 0) {
                        resultsGrid.innerHTML = `
                            <div class="col-span-2 py-10 text-center">
                                <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest leading-loose">No masterpieces match <br> "${query}"</p>
                            </div>
                        `;
                        viewAllLink.classList.add('hidden');
                    } else {
                        viewAllLink.href = `{{ route('products.index') }}?search=${encodeURIComponent(query)}`;
                        viewAllLink.classList.remove('hidden');
                        
                        resultsGrid.innerHTML = products.slice(0, 6).map(product => `
                            <a href="/product/${product.slug}" class="group block bg-white p-3 border border-gray-100 hover:border-black transition-all">
                                <div class="aspect-[3/4] mb-3 overflow-hidden">
                                    <img src="${product.image}" alt="${product.name}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                </div>
                                <h4 class="text-[9px] font-black text-black uppercase tracking-tight truncate">${product.name}</h4>
                                <p class="text-[9px] font-bold text-gray-400 mt-1">Rs. ${product.price}</p>
                            </a>
                        `).join('');
                    }
                } catch (error) {
                    console.error('Search error:', error);
                    resultsGrid.innerHTML = '<div class="col-span-2 py-4 text-center text-red-500">System Error</div>';
                }
            }, 300);
        }

        // Recent Searches
        function addRecentSearch(query) {
            if (!query || query.trim() === '') return;
            
            let searches = getFromLocalStorage('recentSearches') || [];
            searches = searches.filter(s => s !== query);
            searches.unshift(query);
            searches = searches.slice(0, 5); // Keep only last 5 searches
            
            saveToLocalStorage('recentSearches', searches);
        }
        
        function loadRecentSearches() {
            const searches = getFromLocalStorage('recentSearches') || [];
            const container = document.getElementById('recent-searches-list');
            
            if (searches.length === 0) {
                container.innerHTML = '<p class="text-gray-300 font-bold text-[9px] uppercase tracking-widest italic">No search history</p>';
                return;
            }
            
            container.innerHTML = searches.map(search => `
                <a href="{{ route('products.index') }}?search=${encodeURIComponent(search)}" 
                   class="flex items-center justify-between p-3 bg-white/50 hover:bg-black hover:text-white group border border-gray-100 transition-all">
                    <span class="text-[10px] font-black uppercase tracking-widest">${search}</span>
                    <i class="fas fa-history text-[8px] opacity-20 group-hover:opacity-100"></i>
                </a>
            `).join('');
        }
        
        // Favorites Management
        async function addToFavorites(productId, productName, productPrice, productImage) {
            try {
                const response = await fetch('{{ route("favorites.toggle") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ product_id: productId })
                });
                
                const data = await response.json();
                
                // Show notification
                const toast = document.createElement('div');
                toast.className = `fixed bottom-4 right-4 ${data.status === 'added' ? 'bg-[#5C5C5C]' : 'bg-red-500'} text-white px-6 py-3 rounded shadow-lg z-50 animate-slide-in-up`;
                toast.innerHTML = `<i class="${data.status === 'added' ? 'fas fa-heart' : 'far fa-heart'} mr-2"></i> ${data.message}`;
                document.body.appendChild(toast);
                
                setTimeout(() => toast.remove(), 3000);
                
                updateFavoritesCount();
                
                // If sidebar is open, reload list
                const sidebar = document.getElementById('favorites-sidebar');
                if (!sidebar.classList.contains('translate-x-full')) {
                    loadFavorites();
                }
            } catch (error) {
                console.error('Error toggling favorite:', error);
            }
        }
        
        async function removeFromFavorites(productId) {
            try {
                // Since this is usually called from the list, we might want to call toggle or destroy
                // Let's use destroy if we have an endpoint, or toggle if we just want to remove
                // For direct remove button, destroy is better
                
                // Assuming we have toggle endpoint which handles removal if exists
                await addToFavorites(productId);
            } catch (error) {
                console.error('Error removing favorite:', error);
            }
        }
        
        async function loadFavorites() {
            const container = document.getElementById('favorites-list');
            container.innerHTML = '<div class="flex justify-center p-8"><i class="fas fa-spinner fa-spin text-2xl text-[#5C5C5C]"></i></div>';
            
            try {
                const response = await fetch('{{ route("favorites.list") }}');
                const favorites = await response.json();
                
                if (favorites.length === 0) {
                    container.innerHTML = '<p class="text-gray-500 text-center py-12">No favorites yet</p>';
                    return;
                }
                
                container.innerHTML = favorites.map(item => `
                    <div class="flex gap-4 pb-4 mb-4 border-b border-[#5C5C5C]">
                        <img src="${item.image}" alt="${item.name}" class="w-20 h-20 object-cover border border-[#5C5C5C]">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 text-sm mb-1 truncate">
                                <a href="/product/${item.slug}" class="hover:text-[#808080] transition-colors">${item.name}</a>
                            </h4>
                            <p class="text-[#5C5C5C] font-semibold text-sm">Rs. ${item.price}</p>
                            <button onclick="addToCart(${item.id}, '${item.name}', 0, '${item.image}')" class="text-xs text-gray-500 hover:text-black underline mt-2">
                                Add to Cart
                            </button>
                        </div>
                        <button onclick="removeFromFavorites(${item.id})" class="text-red-500 hover:text-red-700 transition-colors self-start">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `).join('');
            } catch (error) {
                console.error('Error loading favorites:', error);
                container.innerHTML = '<p class="text-red-500 text-center py-4">Failed to load favorites</p>';
            }
        }
        
        function updateFavoritesCount() {
            fetch('{{ route("favorites.count") }}')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('favorites-count').textContent = data.count;
                    document.getElementById('favorites-count-mobile').textContent = data.count;
                })
                .catch(error => console.error('Error updating favorites count:', error));
        }
        
        // Cart Management
        async function loadCart() {
            const container = document.getElementById('cart-list');
            const subtotalEl = document.getElementById('cart-subtotal');
            const checkoutBtn = document.getElementById('checkout-btn');
            
            container.innerHTML = '<div class="flex justify-center p-8"><i class="fas fa-spinner fa-spin text-2xl text-[#5C5C5C]"></i></div>';
            
            try {
                const response = await fetch('{{ route("cart.list") }}');
                const data = await response.json();
                
                if (!data.items || data.items.length === 0) {
                    container.innerHTML = '<p class="text-gray-500 text-center py-12">Your cart is empty</p>';
                    if(subtotalEl) subtotalEl.textContent = 'Rs. 0';
                    if(checkoutBtn) checkoutBtn.classList.add('hidden');
                    return;
                }
                
                if(subtotalEl) subtotalEl.textContent = 'Rs. ' + data.subtotal;
                if(checkoutBtn) checkoutBtn.classList.remove('hidden');

                container.innerHTML = data.items.map(item => `
                    <div class="flex gap-6 pb-6 mb-6 border-b border-gray-100 last:border-0 group">
                        <div class="w-20 h-28 shrink-0 overflow-hidden bg-gray-50 border border-gray-100">
                            <img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        </div>
                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start">
                                    <h4 class="text-[11px] font-black text-black uppercase tracking-tight mb-1 truncate max-w-[150px]">
                                        <a href="/product/${item.slug}" class="hover:text-gray-400 transition-colors">${item.name}</a>
                                    </h4>
                                    <button onclick="removeFromCart(${item.id})" class="text-gray-300 hover:text-red-500 transition-colors">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </div>
                                <p class="text-[10px] font-black text-black">Rs. ${item.price}</p>
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-1">${item.size || 'STD'} / ${item.color || 'ORIG'}</p>
                            </div>
                            
                            <div class="flex items-center gap-3 mt-4">
                                <div class="flex items-center border border-gray-100 bg-gray-50">
                                    <button onclick="updateCartQuantity(${item.id}, ${Math.max(1, item.quantity - 1)})" class="w-7 h-7 flex items-center justify-center text-[10px] hover:bg-black hover:text-white transition-all">-</button>
                                    <span class="text-[10px] font-black w-6 text-center">${item.quantity}</span>
                                    <button onclick="updateCartQuantity(${item.id}, ${item.quantity + 1})" class="w-7 h-7 flex items-center justify-center text-[10px] hover:bg-black hover:text-white transition-all">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('');
            } catch (error) {
                console.error('Error loading cart:', error);
                container.innerHTML = '<p class="text-red-500 text-center py-4">Failed to load cart</p>';
            }
        }

        async function updateCartQuantity(itemId, quantity) {
            try {
                const response = await fetch(`/cart/${itemId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ quantity: quantity })
                });

                if (response.ok) {
                    loadCart();
                    updateCartCount();
                }
            } catch (error) {
                console.error('Error updating cart:', error);
            }
        }

        async function removeFromCart(itemId) {
            if(!confirm('Are you sure you want to remove this item?')) return;
            
            try {
                const response = await fetch(`/cart/${itemId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    loadCart();
                    updateCartCount();
                }
            } catch (error) {
                console.error('Error removing from cart:', error);
            }
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateFavoritesCount();
            
            // Add search to recent searches on form submit
            const searchForm = document.querySelector('#search-overlay form');
            if (searchForm) {
                searchForm.addEventListener('submit', function(e) {
                    const searchInput = document.getElementById('search-input');
                    if (searchInput.value.trim()) {
                        addRecentSearch(searchInput.value.trim());
                    }
                });
            }
            
            // Close sidebars on ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeSidebars();
                }
            });
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.animate-slide-in');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.3s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
        // Toggle Mobile Dropdown
        function toggleMobileDropdown(id) {
            const dropdown = document.getElementById(id + '-dropdown');
            const icon = document.getElementById(id + '-icon');
            
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                icon.classList.remove('fa-plus');
                icon.classList.add('fa-minus');
            } else {
                dropdown.classList.add('hidden');
                icon.classList.remove('fa-minus');
                icon.classList.add('fa-plus');
            }
        }
    </script>

    @stack('scripts')
    <script>
        // Update cart count
        function updateCartCount() {
            // ... (existing code, keeping it implicit or assuming user has it)
            // Wait, I am appending this NEW script, so I should be careful not to overwrite the long existing one unless I match it.
            // I'll just replace the closing body tag with my script + body tag?
            // Actually replace_file_content works by replacing line numbers.
            // I will target the end of the existing script block or add a new one after it.
            // The existing file ends at line 1014 presumably or around there.
            // I'll target the very end of the file.
        }
    </script>

    <!-- Client Sound Alert System -->
    @auth
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @php
                $notificationsToPlay = [];
                try {
                    $unread = Auth::user()->unreadNotifications->where('type', 'App\Notifications\OrderNotification');
                    foreach($unread as $n) {
                        $notificationsToPlay[] = [
                            'id' => $n->id,
                            'type' => $n->data['type'] ?? ''
                        ];
                    }
                } catch(\Exception $e) {}
            @endphp
            
            const initialNotifs = @json($notificationsToPlay);
            if (initialNotifs.length > 0) playNotifications(initialNotifs);

            // Google/YouTube style polling
            setInterval(async () => {
                try {
                    const res = await fetch('{{ route("user.notifications.check") }}');
                    const data = await res.json();
                    if (data.unread && data.unread.length > 0) {
                        playNotifications(data.unread);
                    }
                } catch (e) {}
            }, 10000); // Check every 10s

            function playNotifications(notifs) {
                try {
                    const AudioContextClass = window.AudioContext || window.webkitAudioContext;
                    if (!AudioContextClass) return;
                    const ctx = new AudioContextClass();
                    
                    notifs.forEach((n, index) => {
                        setTimeout(() => {
                            if (n.type.includes('delivered') || n.type.includes('paid')) playClientSound(ctx, 'delivered');
                            else if (n.type.includes('cancelled') || n.type.includes('refunded')) playClientSound(ctx, 'cancelled');
                            else if (n.type.includes('shipped') || n.type.includes('processing') || n.type.includes('approved')) playClientSound(ctx, 'processing');
                        }, index * 1000);
                    });
                    
                    // Mark as read
                    fetch('{{ route("notifications.read") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ ids: notifs.map(n => n.id) })
                    });
                } catch (e) {}
            }

            function playClientSound(ctx, type) {
                if (type === 'processing') {
                    [880, 880].forEach((freq, i) => {
                        const osc = ctx.createOscillator();
                        const gain = ctx.createGain();
                        osc.type = 'triangle';
                        osc.frequency.setValueAtTime(freq, ctx.currentTime + i * 0.2);
                        gain.gain.setValueAtTime(0.1, ctx.currentTime + i * 0.2);
                        gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + i * 0.2 + 0.15);
                        osc.connect(gain).connect(ctx.destination);
                        osc.start(ctx.currentTime + i * 0.2);
                        osc.stop(ctx.currentTime + i * 0.2 + 0.15);
                    });
                } else if (type === 'delivered') {
                    [523.25, 659.25, 783.99, 1046.50].forEach((freq, i) => {
                        const osc = ctx.createOscillator();
                        const gain = ctx.createGain();
                        osc.type = 'sine';
                        osc.frequency.setValueAtTime(freq, ctx.currentTime + i * 0.12);
                        gain.gain.setValueAtTime(0.1, ctx.currentTime + i * 0.12);
                        gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + i * 0.12 + 0.5);
                        osc.connect(gain).connect(ctx.destination);
                        osc.start(ctx.currentTime + i * 0.12);
                        osc.stop(ctx.currentTime + i * 0.12 + 0.5);
                    });
                } else if (type === 'cancelled') {
                    [440, 349.23, 261.63].forEach((freq, i) => {
                        const osc = ctx.createOscillator();
                        const gain = ctx.createGain();
                        osc.type = 'sawtooth';
                        osc.frequency.setValueAtTime(freq, ctx.currentTime + i * 0.2);
                        gain.gain.setValueAtTime(0.1, ctx.currentTime + i * 0.2);
                        gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + i * 0.2 + 0.25);
                        osc.connect(gain).connect(ctx.destination);
                        osc.start(ctx.currentTime + i * 0.2);
                        osc.stop(ctx.currentTime + i * 0.2 + 0.25);
                    });
                }
            }
        });
    </script>
    @endauth
</body>
</html>
