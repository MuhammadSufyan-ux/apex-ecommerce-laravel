<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">

        <!-- Tailwind/Vite (using CDN if vite not building correctly locally for user, but keeping blade directive if it works) -->
        <!-- Fallback to Tailwind CDN if needed, but assuming user has correct setup since previous pages work -->
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
                            primary: '#5C5C5C',
                        }
                    }
                }
            }
        </script>
        
        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('s4_store_logo.png') }}">

        <!-- Alpine.js -->
        <script src="//unpkg.com/alpinejs" defer></script>

        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="font-sans text-[#5C5C5C] antialiased bg-white flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md px-6 py-8">
            <!-- Logo -->
            <div class="flex justify-center mb-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img src="{{ asset('s4_store_logo.png') }}" alt="S4 Luxury Store" class="h-16 w-auto object-contain">
                    <div class="flex flex-col leading-none">
                        <span class="text-3xl font-black tracking-tighter text-[#D4AF37]">S4</span>
                        <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-black">Luxury Store</span>
                    </div>
                </a>
            </div>

            <!-- Card -->
            <div class="bg-white p-8 border border-[#5C5C5C] shadow-none rounded-none relative">
                <!-- Decorative Corner -->
                <div class="absolute top-0 left-0 w-2 h-2 bg-black"></div>
                <div class="absolute bottom-0 right-0 w-2 h-2 bg-black"></div>
                
                {{ $slot }}
            </div>
            
            <div class="text-center mt-6 text-xs text-[#808080]">
                &copy; {{ date('Y') }} S4 Luxury Store. All rights reserved.
                <div class="mt-4">
                    <a href="/" class="text-[#5C5C5C] hover:text-black transition-colors font-bold uppercase tracking-widest border-b border-transparent hover:border-black pb-1">Back to Home</a>
                </div>
            </div>
        </div>
    </body>
</html>
