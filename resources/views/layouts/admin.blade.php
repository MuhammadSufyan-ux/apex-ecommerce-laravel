<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Panel - S4 Luxury Store</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">

    <!-- Tailwind/Vite -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                    colors: {
                        gold: '#D4AF37',
                    }
                }
            }
        }
    </script>
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('s4_store_logo.png') }}">
    
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-link.active {
            background-color: #f3f4f6;
            color: black;
            border-right: 4px solid #D4AF37;
        }
        @keyframes bellShake {
            0%,100% { transform: rotate(0); }
            15% { transform: rotate(15deg); }
            30% { transform: rotate(-10deg); }
            45% { transform: rotate(10deg); }
            60% { transform: rotate(-5deg); }
            75% { transform: rotate(5deg); }
        }
        .bell-shake { animation: bellShake 0.6s ease-in-out; }
        @keyframes notifSlideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .notif-slide-in { animation: notifSlideIn 0.3s ease-out; }
    </style>
    
    <script src="//unpkg.com/alpinejs" defer></script>
    @stack('styles')
</head>
<body class="font-sans text-gray-900 antialiased bg-white overflow-x-hidden" 
    x-data="{ 
        sidebarOpen: window.innerWidth > 1024 
    }" 
    x-init="
        window.addEventListener('resize', () => {
            if (window.innerWidth <= 1024) sidebarOpen = false;
            else sidebarOpen = true;
        })
    ">
    <!-- Sidebar -->
    <aside class="fixed top-0 left-0 z-40 h-screen transition-all duration-300 bg-white border-r border-gray-100"
           :class="sidebarOpen ? 'w-64' : 'w-20'">
        
        <!-- Sidebar Brand -->
        <div class="h-20 flex items-center px-6 border-b border-gray-50 overflow-hidden">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <img src="{{ asset('s4_store_logo.png') }}" alt="Logo" class="h-10 w-auto object-contain shrink-0">
                <div class="flex flex-col leading-none" x-show="sidebarOpen">
                    <span class="text-xs font-black tracking-tighter text-gold">S4</span>
                    <span class="text-[7px] font-bold uppercase tracking-[0.1em] text-black">Admin Panel</span>
                </div>
            </a>
        </div>

        <div class="flex flex-col justify-between h-[calc(100%-80px)] overflow-y-auto">
            <nav class="p-4 space-y-2">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-4 px-2" x-show="sidebarOpen">Overview</p>
                
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-sm text-gray-500 hover:bg-gray-50 hover:text-black transition-all {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large w-5 text-center"></i>
                    <span class="text-sm font-bold uppercase tracking-widest" x-show="sidebarOpen">Dashboard</span>
                </a>

                <a href="{{ route('admin.reports.index') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-sm text-gray-400 hover:bg-gray-50 hover:text-gold transition-all {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line w-5 text-center"></i>
                    <span class="text-sm font-bold uppercase tracking-widest" x-show="sidebarOpen">Intelligence</span>
                </a>

                <a href="{{ route('admin.notifications.index') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-sm text-gray-400 hover:bg-gray-50 hover:text-gold transition-all {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                    <i class="fas fa-satellite-dish w-5 text-center"></i>
                    <span class="text-sm font-bold uppercase tracking-widest" x-show="sidebarOpen">Communication</span>
                </a>

                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mt-8 mb-4 px-2" x-show="sidebarOpen">Inventory</p>

                <a href="{{ route('admin.products.index') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-sm text-gray-500 hover:bg-gray-50 hover:text-black transition-all {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="fas fa-box w-5 text-center"></i>
                    <span class="text-sm font-bold uppercase tracking-widest" x-show="sidebarOpen">Products</span>
                </a>

                <a href="{{ route('admin.sections.index') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-sm text-gray-500 hover:bg-gray-50 hover:text-black transition-all {{ request()->routeIs('admin.sections.*') ? 'active' : '' }}">
                    <i class="fas fa-layer-group w-5 text-center"></i>
                    <span class="text-sm font-bold uppercase tracking-widest" x-show="sidebarOpen">Sections</span>
                </a>

                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mt-8 mb-4 px-2" x-show="sidebarOpen">Sales</p>

                <a href="{{ route('admin.orders.index') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-sm text-gray-500 hover:bg-gray-50 hover:text-black transition-all {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart w-5 text-center"></i>
                    <span class="text-sm font-bold uppercase tracking-widest" x-show="sidebarOpen">Orders</span>
                </a>

                <a href="{{ route('admin.users.index') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-sm text-gray-500 hover:bg-gray-50 hover:text-black transition-all {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users w-5 text-center"></i>
                    <span class="text-sm font-bold uppercase tracking-widest" x-show="sidebarOpen">Customers</span>
                </a>

                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mt-8 mb-4 px-2" x-show="sidebarOpen">System</p>

                <a href="{{ route('admin.payments.index') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-sm text-gray-500 hover:bg-gray-50 hover:text-black transition-all {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <i class="fas fa-credit-card w-5 text-center"></i>
                    <span class="text-sm font-bold uppercase tracking-widest" x-show="sidebarOpen">Payments</span>
                </a>

                <a href="{{ route('admin.settings.index') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-sm text-gray-500 hover:bg-gray-50 hover:text-black transition-all {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="fas fa-cog w-5 text-center"></i>
                    <span class="text-sm font-bold uppercase tracking-widest" x-show="sidebarOpen">Configuration</span>
                </a>

                <a href="{{ route('admin.faqs.index') }}" class="sidebar-link flex items-center gap-4 px-4 py-3 rounded-sm text-gray-500 hover:bg-gray-50 hover:text-black transition-all {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                    <i class="fas fa-question-circle w-5 text-center"></i>
                    <span class="text-sm font-bold uppercase tracking-widest" x-show="sidebarOpen">FAQs</span>
                </a>
            </nav>

            <div class="p-4 border-t border-gray-50">
                <a href="{{ route('home') }}" class="flex items-center gap-4 px-4 py-3 text-gray-400 hover:text-black transition-all">
                    <i class="fas fa-external-link-alt w-5 text-center"></i>
                    <span class="text-[10px] font-bold uppercase tracking-widest" x-show="sidebarOpen">View Store</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-4 px-4 py-3 text-red-400 hover:text-red-600 transition-all">
                        <i class="fas fa-sign-out-alt w-5 text-center"></i>
                        <span class="text-[10px] font-bold uppercase tracking-widest" x-show="sidebarOpen">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="transition-all duration-300 min-h-screen" 
          :class="sidebarOpen ? 'ml-64' : 'ml-20'">
        
        <!-- Header -->
        <header class="h-20 bg-white border-b border-gray-100 flex items-center justify-between px-8 sticky top-0 z-30">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-400 hover:text-black transition-all">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div class="h-8 w-[1px] bg-gray-100 mx-2 hidden md:block"></div>
                <h1 class="text-[11px] font-black text-black uppercase tracking-[0.3em] hidden lg:block">
                    @yield('title', 'Admin Dashboard')
                </h1>
            </div>

            <div class="flex items-center gap-6">
                <!-- Notifications (Intelligence Bell) with Mark-as-Read -->
                <div class="relative" x-data="notificationBell()" x-init="init()">
                    <button @click="toggleDropdown()" class="text-gray-400 hover:text-black relative transition-all" :class="shaking ? 'bell-shake' : ''">
                        <i class="far fa-bell text-xl"></i>
                        <span x-show="notifications.length > 0" x-text="notifications.length"
                              class="absolute -top-1 -right-1 min-w-[16px] h-4 bg-gold text-white text-[8px] font-black flex items-center justify-center rounded-full border-2 border-white px-0.5"></span>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false" x-cloak
                         class="absolute right-0 mt-4 w-80 bg-white border border-gray-100 shadow-2xl z-50 overflow-hidden notif-slide-in">
                        <div class="p-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                            <span class="text-[10px] font-black uppercase tracking-widest text-black">Intelligence Feed</span>
                            <span class="text-[8px] font-bold text-gold uppercase tracking-widest" x-text="notifications.length + ' New Alerts'"></span>
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            <template x-for="notif in notifications" :key="notif.id">
                                <a :href="notif.link || '#'" 
                                   @click.prevent="markAsRead(notif)"
                                   class="block p-4 hover:bg-gray-50 transition-all border-b border-gray-50 last:border-0 cursor-pointer">
                                    <div class="flex items-start gap-4">
                                        <div class="w-2 h-2 mt-1.5 rounded-full bg-gold shrink-0"></div>
                                        <div class="flex-1">
                                            <p class="text-[10px] font-black uppercase tracking-widest text-black leading-tight mb-1" x-text="notif.title"></p>
                                            <p class="text-[10px] font-medium text-gray-500 leading-none" x-text="notif.message"></p>
                                            <p class="text-[8px] font-bold text-gray-300 uppercase tracking-widest mt-2" x-text="notif.time"></p>
                                        </div>
                                        <button @click.stop.prevent="dismissNotif(notif)" class="text-gray-300 hover:text-red-500 transition-colors shrink-0">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </div>
                                </a>
                            </template>
                            <div x-show="notifications.length === 0" class="p-12 text-center">
                                <div class="opacity-10 mb-4 text-3xl"><i class="fas fa-check-circle"></i></div>
                                <p class="text-[10px] font-black text-gray-300 uppercase tracking-[0.4em]">Clear Signals</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.notifications.index') }}" class="block p-4 text-center bg-black text-white text-[9px] font-black uppercase tracking-[0.3em] hover:bg-gold transition-all">
                            View Full Ledger
                        </a>
                    </div>
                </div>

                <!-- Admin Profile -->
                <div class="flex items-center gap-3 pl-6 border-l border-gray-100">
                    <div class="text-right hidden sm:block">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Administrator</p>
                        <p class="text-sm font-bold text-black leading-none">{{ Auth::user()->name }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-gray-100 border border-gray-200 overflow-hidden">
                        @if(Auth::user()->profile_image)
                            <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center font-bold text-xs text-gray-400">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="p-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-700 text-sm font-bold uppercase tracking-widest">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-700 text-sm font-bold uppercase tracking-widest">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Alert Sound System for Admin -->
    <script>
        // Web Audio API Sound Generator - No external files needed
        const AudioCtx = window.AudioContext || window.webkitAudioContext;
        
        function playAdminSound(type) {
            const ctx = new AudioCtx();
            
            if (type === 'new_order') {
                // Ascending chime - professional "new order" tone
                [523.25, 659.25, 783.99].forEach((freq, i) => {
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(freq, ctx.currentTime + i * 0.15);
                    gain.gain.setValueAtTime(0.3, ctx.currentTime + i * 0.15);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + i * 0.15 + 0.4);
                    osc.connect(gain).connect(ctx.destination);
                    osc.start(ctx.currentTime + i * 0.15);
                    osc.stop(ctx.currentTime + i * 0.15 + 0.4);
                });
            } else if (type === 'processing') {
                // Double beep - "processing" acknowledgment
                [880, 880].forEach((freq, i) => {
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'triangle';
                    osc.frequency.setValueAtTime(freq, ctx.currentTime + i * 0.2);
                    gain.gain.setValueAtTime(0.25, ctx.currentTime + i * 0.2);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + i * 0.2 + 0.15);
                    osc.connect(gain).connect(ctx.destination);
                    osc.start(ctx.currentTime + i * 0.2);
                    osc.stop(ctx.currentTime + i * 0.2 + 0.15);
                });
            } else if (type === 'delivered') {
                // Victory fanfare - "delivered" success
                [523.25, 659.25, 783.99, 1046.50].forEach((freq, i) => {
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(freq, ctx.currentTime + i * 0.12);
                    gain.gain.setValueAtTime(0.2, ctx.currentTime + i * 0.12);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + i * 0.12 + 0.5);
                    osc.connect(gain).connect(ctx.destination);
                    osc.start(ctx.currentTime + i * 0.12);
                    osc.stop(ctx.currentTime + i * 0.12 + 0.5);
                });
            } else if (type === 'cancelled') {
                // Descending tone - "cancelled" alert
                [440, 349.23, 261.63].forEach((freq, i) => {
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'sawtooth';
                    osc.frequency.setValueAtTime(freq, ctx.currentTime + i * 0.2);
                    gain.gain.setValueAtTime(0.15, ctx.currentTime + i * 0.2);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + i * 0.2 + 0.25);
                    osc.connect(gain).connect(ctx.destination);
                    osc.start(ctx.currentTime + i * 0.2);
                    osc.stop(ctx.currentTime + i * 0.2 + 0.25);
                });
            }
        }

        @php
            $notificationsData = collect([]);
            if(Auth::check()) {
                $notificationsData = Auth::user()->unreadNotifications->map(function($n) {
                    return [
                        'id' => $n->id,
                        'title' => $n->data['title'] ?? 'Alert',
                        'message' => $n->data['message'] ?? '',
                        'link' => $n->data['link'] ?? '#',
                        'type' => $n->data['type'] ?? 'general',
                        'time' => $n->created_at->diffForHumans(),
                    ];
                });
            }
        @endphp

        // Notification Bell Component
        function notificationBell() {
            return {
                open: false,
                shaking: false,
                notifications: @json($notificationsData),

                init() {
                    // Play sound for latest notification on page load if there are new ones
                    if (this.notifications.length > 0) {
                        this.playSoundFor(this.notifications[0]);
                    }

                    // Polling for Google/YouTube style live updates
                    setInterval(() => this.fetchUpdates(), 10000); // Check every 10 seconds
                },

                async fetchUpdates() {
                    try {
                        const res = await fetch('{{ route("admin.notifications.check") }}');
                        const data = await res.json();
                        
                        const currentIds = this.notifications.map(n => n.id);
                        const newOnes = (data.unread || []).filter(n => !currentIds.includes(n.id));
                        
                        if (newOnes.length > 0) {
                            this.notifications = [...newOnes, ...this.notifications];
                            this.shaking = true;
                            setTimeout(() => this.shaking = false, 600);
                            this.playSoundFor(newOnes[0]);
                        }
                    } catch (e) {}
                },

                playSoundFor(notif) {
                    this.shaking = true;
                    setTimeout(() => this.shaking = false, 600);
                    
                    const msg = (notif.message || '').toLowerCase();
                    if (msg.includes('cancelled')) {
                        playAdminSound('cancelled');
                    } else if (msg.includes('delivered')) {
                        playAdminSound('delivered');
                    } else if (msg.includes('processing')) {
                        playAdminSound('processing');
                    } else {
                        playAdminSound('new_order');
                    }
                },

                toggleDropdown() {
                    this.open = !this.open;
                },

                markAsRead(notif) {
                    // Mark as read via AJAX then navigate
                    // Use Laravel route helper. Since we are in Blade, we can construct the base URL
                    const url = `{{ url('/admin/notifications') }}/${notif.id}/read`;
                    
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    }).then(() => {
                        this.notifications = this.notifications.filter(n => n.id !== notif.id);
                        if (notif.link && notif.link !== '#') {
                            window.location.href = notif.link;
                        }
                    });
                },

                dismissNotif(notif) {
                    const url = `{{ url('/admin/notifications') }}/${notif.id}/read`;
                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    }).then(() => {
                        this.notifications = this.notifications.filter(n => n.id !== notif.id);
                    });
                }
            };
        }
    </script>

    @stack('scripts')
</body>
</html>
