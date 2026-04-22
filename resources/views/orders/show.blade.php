@extends('layout')

@section('content')
<div class="bg-white min-h-screen py-10 print:py-0 print:bg-white">
    <div class="container mx-auto px-4 max-w-4xl print:max-w-none print:px-0">
        <!-- Receipt Card -->
        <div id="invoice-card" class="bg-white p-8 md:p-12 border border-gray-100 print:shadow-none print:border-0 print:p-0 mx-auto" style="width: 210mm; min-height: 297mm; box-sizing: border-box;">
            
            <div class="print-padding" style="padding: 10mm;">
                <!-- Header -->
                <div class="mb-10 flex border-b-4 border-black pb-8">
                    <!-- Brand -->
                    <div class="w-1/2">
                        <div class="flex items-center gap-4 mb-6">
                            <img src="{{ asset('s4_store_logo.png') }}" alt="S4 Luxury Store" class="h-16 w-auto object-contain">
                            <div>
                                <h1 class="text-2xl font-black text-black uppercase tracking-tighter leading-none">S4 LUXURY</h1>
                                <p class="text-[8px] text-gray-400 font-bold uppercase tracking-[0.4em] mt-1">Premium Fashion House</p>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-black uppercase tracking-widest">Invoice: #{{ substr($order->order_number, -8) }}</p>
                            <p class="text-[9px] text-gray-500 font-bold uppercase tracking-widest">Issue Date: {{ $order->created_at->format('d M Y') }}</p>
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-4">Order Ref: {{ $order->id }}</p>
                        </div>
                    </div>

                    <!-- Customer -->
                    <div class="w-1/2 text-right">
                        <h3 class="text-[9px] text-gray-300 font-black uppercase tracking-[0.4em] mb-4">Elite Customer</h3>
                        <div class="space-y-1">
                            <p class="text-lg font-black text-black uppercase tracking-tight">{{ $order->customer_name }}</p>
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest leading-loose">
                                {{ $order->shipping_address }}<br>
                                {{ $order->shipping_city }}, {{ $order->shipping_postal_code }}<br>
                                {{ $order->shipping_country }}
                            </p>
                            <p class="text-[11px] font-black text-black mt-3 tracking-[0.1em]">{{ $order->customer_phone }}</p>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="mb-10">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-black text-white">
                                <th class="py-3 px-4 text-[10px] font-black uppercase tracking-[0.2em] text-left">Article</th>
                                <th class="py-3 px-2 text-[10px] font-black uppercase tracking-[0.2em] text-center">Spec</th>
                                <th class="py-3 px-2 text-[10px] font-black uppercase tracking-[0.2em] text-center">Qty</th>
                                <th class="py-3 px-2 text-[10px] font-black uppercase tracking-[0.2em] text-center">Status</th>
                                <th class="py-3 px-4 text-[10px] font-black uppercase tracking-[0.2em] text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($order->items as $item)
                            <tr class="print:break-inside-avoid">
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-16 bg-gray-50 border border-gray-100 overflow-hidden shrink-0">
                                            @if($item->product && $item->product->images->isNotEmpty())
                                                <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" class="w-full h-full object-cover">
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-[10px] font-black text-black uppercase tracking-tight truncate">{{ $item->product_name }}</p>
                                            <p class="text-[8px] text-gray-400 font-bold uppercase tracking-[0.1em]">SKU: {{ $item->product_sku }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-2 text-center">
                                    <p class="text-[10px] font-black text-black uppercase">{{ $item->size != 'N/A' ? $item->size : 'STD' }}</p>
                                    <p class="text-[8px] text-gray-400 font-bold uppercase">{{ $item->color != 'N/A' ? $item->color : '-' }}</p>
                                </td>
                                <td class="py-4 px-2 text-center text-[10px] font-black text-black">{{ $item->quantity }}</td>
                                <td class="py-4 px-2 text-center">
                                    <span class="text-[8px] font-black uppercase px-2 py-1 bg-gray-50 text-black border border-gray-200 tracking-tighter">
                                        {{ $item->order_status == 'pending' ? 'CONFIRMED' : strtoupper($item->order_status) }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-right text-[10px] font-black text-black">Rs. {{ number_format($item->subtotal) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Financials -->
                <div class="flex justify-between items-start border-t-2 border-gray-100 pt-8 mt-10">
                    <div class="w-1/2">
                        <div class="mb-4">
                            <p class="text-[8px] text-gray-300 font-black uppercase tracking-[0.3em] mb-2">Payment Info</p>
                            <p class="text-[10px] font-black text-black uppercase tracking-widest bg-gray-50 px-3 py-2 inline-block border border-gray-100">{{ $order->payment_method }}</p>
                        </div>
                        @if($order->notes)
                        <div class="mt-4 pr-10">
                            <p class="text-[8px] text-gray-300 font-black uppercase tracking-[0.3em] mb-2">Merchant Notes</p>
                            <p class="text-[9px] text-gray-500 font-bold leading-relaxed italic border-l-2 border-gray-100 pl-3">"{{ $order->notes }}"</p>
                        </div>
                        @endif
                    </div>

                    <div class="w-1/3 space-y-3">
                        <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest text-gray-400">
                            <span>Subtotal</span>
                            <span class="text-black">Rs. {{ number_format($order->subtotal) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[10px] font-black uppercase tracking-widest text-gray-400">
                            <span>Shipping</span>
                            <span class="text-black">Rs. {{ number_format($order->shipping_cost) }}</span>
                        </div>
                        <div class="h-[1px] bg-gray-100 w-full my-4"></div>
                        <div class="flex justify-between items-center bg-black text-white px-5 py-4">
                            <span class="text-[9px] font-black uppercase tracking-[0.3em]">Total</span>
                            <span class="text-xl font-black">Rs. {{ number_format($order->total) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Bottom Brand -->
                <div class="mt-20 pt-10 border-t border-gray-50">
                    <div class="text-center">
                        <p class="text-[9px] text-black font-black uppercase tracking-[0.6em] mb-3 leading-none underline decoration-gold underline-offset-8">AUTHENTIC LUXURY BY S4</p>
                        <p class="text-[8px] text-gray-400 font-bold uppercase tracking-widest leading-loose mt-4">
                            S4 Luxury Store Swabi, Khyber Pakhtunkhwa, Pakistan<br>
                            Contact: +92 313 0000000 | Web: www.s4luxury.com
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Section (Hidden in PDF) -->
        <div class="flex gap-4 mt-10 print:hidden max-w-4xl mx-auto" id="action-buttons-container">
            <button onclick="window.print()" class="flex-1 py-4 border-2 border-black text-black text-[11px] font-black uppercase tracking-[0.3em] hover:bg-black hover:text-white transition-all flex items-center justify-center gap-4">
                <i class="fas fa-print"></i> Print Receipt
            </button>
            <button onclick="downloadPDF()" id="download-btn" class="flex-1 py-4 bg-black text-white text-[11px] font-black uppercase tracking-[0.3em] shadow-xl hover:bg-gray-800 transition-all flex items-center justify-center gap-4">
                <i class="fas fa-file-pdf"></i> Download PDF
            </button>
        </div>
    </div>
</div>

<style>
@media print {
    @page {
        size: A4;
        margin: 0;
    }
    header, footer, nav, .WhatsApp-Floating-Button, .WhatsApp, .floating-btn, #action-buttons-container { display: none !important; }
    body { background: white !important; padding: 0 !important; margin: 0 !important; }
    .bg-gray-50 { background: white !important; }
    .container { width: 100% !important; max-width: none !important; margin: 0 !important; padding: 0 !important; }
    #invoice-card { 
        box-shadow: none !important; 
        border: none !important; 
        width: 210mm !important; 
        height: 297mm !important; 
        margin: 0 !important; 
        padding: 0 !important;
        float: none !important;
    }
}
* { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function downloadPDF() {
    const orderNumber = '{{ $order->order_number }}';
    const btn = document.getElementById('download-btn');
    
    btn.innerHTML = '<i class="fas fa-sync fa-spin"></i> GENERATING...';
    btn.disabled = true;

    // Create a clean version for PDF
    const printArea = document.createElement('div');
    printArea.style.padding = '20px';
    printArea.style.background = 'white';
    printArea.innerHTML = `
        <div style="border-bottom: 2px solid #000; padding-bottom: 20px; margin-bottom: 20px; display: flex; justify-content: space-between;">
            <div>
                <h1 style="margin:0; font-size: 24px; text-transform: uppercase;">S4 LUXURY STORE</h1>
                <p style="margin:5px 0; font-size: 14px; font-weight: bold;">Invoice: #${orderNumber}</p>
                <p style="margin:5px 0; font-size: 12px; color: #888;">Date: {{ $order->created_at->format('M d, Y') }}</p>
            </div>
            <div style="text-align: right;">
                <p style="margin:0; font-size: 14px; font-weight: bold;">{{ $order->customer_name }}</p>
                <p style="margin:5px 0; font-size: 12px;">{{ $order->shipping_address }}</p>
                <p style="margin:5px 0; font-size: 12px;">{{ $order->shipping_city }}</p>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
            <thead>
                <tr style="background: #000; color: #fff;">
                    <th style="padding: 10px; text-align: left; font-size: 10px;">PRODUCT</th>
                    <th style="padding: 10px; text-align: center; font-size: 10px;">SPEC</th>
                    <th style="padding: 10px; text-align: center; font-size: 10px;">QTY</th>
                    <th style="padding: 10px; text-align: right; font-size: 10px;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px 10px; font-size: 11px;">
                        <strong>{{ $item->product_name }}</strong><br>
                        <span style="color: #888;">SKU: {{ $item->product_sku }}</span>
                    </td>
                    <td style="padding: 12px 10px; text-align: center; font-size: 11px;">{{ $item->size }} / {{ $item->color }}</td>
                    <td style="padding: 12px 10px; text-align: center; font-size: 11px;">{{ $item->quantity }}</td>
                    <td style="padding: 12px 10px; text-align: right; font-size: 11px;">Rs. {{ number_format($item->subtotal) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="display: flex; justify-content: flex-end;">
            <div style="width: 200px;">
                <div style="display: flex; justify-content: space-between; padding: 5px 0; font-size: 12px;">
                    <span>Subtotal:</span>
                    <span>Rs. {{ number_format($order->subtotal) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 5px 0; font-size: 12px;">
                    <span>Logistics:</span>
                    <span>Rs. {{ number_format($order->shipping_cost) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; border-top: 1px solid #000; padding: 10px 0; margin-top: 5px; font-size: 14px; font-weight: bold;">
                    <span>Total:</span>
                    <span>Rs. {{ number_format($order->total) }}</span>
                </div>
            </div>
        </div>

        <div style="margin-top: 40px; text-align: center; font-size: 10px; color: #888;">
            OFFICIAL DIGITAL RECEIPT - S4 LUXURY STORE
        </div>
    `;

    const opt = {
        margin: [10, 10],
        filename: 'S4-Invoice-' + orderNumber + '.pdf',
        image: { type: 'jpeg', quality: 1.0 },
        html2canvas: { scale: 3, useCORS: true },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };

    html2pdf().set(opt).from(printArea).save().then(() => {
        btn.innerHTML = '<i class="fas fa-file-pdf"></i> Download PDF';
        btn.disabled = false;
    });
}
</script>
@endsection
