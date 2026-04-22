@extends('layout')

@section('title', 'Store Locator - S4 Luxury Store')

@section('content')
<div class="py-20 bg-white">
    <div class="container mx-auto px-4 max-w-4xl">
        <h1 class="text-4xl font-black text-black uppercase tracking-tighter mb-8 text-center">Store Locator</h1>
        <div class="h-1 w-20 bg-[#D4AF37] mx-auto mb-12"></div>
        <div class="bg-gray-50 p-12 rounded-lg border border-gray-100 flex flex-col md:flex-row items-center gap-12">
            <div class="flex-1">
                <h3 class="text-2xl font-black text-black uppercase mb-4">Swabi Flagship Store</h3>
                <p class="text-gray-600 mb-6">
                    Ziyarat road Tordher, Swabi,<br>
                    KPK, Pakistan
                </p>
                <div class="space-y-4">
                    <p class="flex items-center gap-3 text-black font-bold">
                        <i class="fas fa-phone-alt text-[#D4AF37]"></i>
                        0342 9748731
                    </p>
                    <p class="flex items-center gap-3 text-black font-bold">
                        <i class="far fa-clock text-[#D4AF37]"></i>
                        10:00 AM - 10:00 PM
                    </p>
                </div>
                <a href="https://www.google.com/maps/search/?api=1&query=Ziyarat+road+Tordher+Swabi+KPK+Pakistan" target="_blank" class="inline-block mt-8 px-8 py-3 bg-black text-white text-xs font-bold uppercase tracking-widest hover:bg-gray-800 transition-colors">
                    Get Directions
                </a>
            </div>
            <div class="flex-1 w-full h-[300px] bg-gray-200 rounded-lg overflow-hidden flex items-center justify-center">
                 <!-- Placeholder for Map -->
                 <i class="fas fa-map-marked-alt text-5xl text-gray-400"></i>
            </div>
        </div>
    </div>
</div>
@endsection
