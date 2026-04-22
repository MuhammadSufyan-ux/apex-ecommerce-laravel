@extends('layout')

@section('title', 'Shipping Policy - S4 Luxury Store')

@section('content')
<div class="py-20 bg-white">
    <div class="container mx-auto px-4 max-w-4xl">
        <h1 class="text-4xl font-black text-black uppercase tracking-tighter mb-8 text-center">Shipping Policy</h1>
        <div class="h-1 w-20 bg-[#D4AF37] mx-auto mb-12"></div>
        <div class="prose prose-lg mx-auto text-gray-600">
            <h3 class="text-xl font-bold text-black mb-4 uppercase">Delivery Timeline</h3>
            <p class="mb-6">Standard delivery takes 3-5 working days across Pakistan. International shipping timelines vary by destination.</p>
            
            <h3 class="text-xl font-bold text-black mb-4 uppercase">Shipping Charges</h3>
            <p class="mb-6">We offer <strong>COMPLIMENTARY SHIPPING</strong> on all orders above Rs. 5,000 within Pakistan. For orders below this amount, a standard flat fee of Rs. 200 applies.</p>
            
            <h3 class="text-xl font-bold text-black mb-4 uppercase">Order Tracking</h3>
            <p class="mb-6">Once your order is dispatched, you will receive a tracking number via SMS or Email to monitor your delivery progress.</p>
        </div>
    </div>
</div>
@endsection
