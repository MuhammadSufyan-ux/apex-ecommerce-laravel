@extends('layout')

@section('title', 'Order Confirmed - Cloth Shop')

@section('content')
<div class="bg-white min-h-screen py-16">
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Success Banner -->
        <div class="mb-8 text-center">
            <div class="w-16 h-16 bg-green-500 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-2xl animate-bounce">
                <i class="fas fa-check"></i>
            </div>
            <h1 class="text-3xl font-black text-black uppercase tracking-tighter">Order Confirmed!</h1>
            <p class="text-gray-500 mt-2">Thank you for your purchase. Your order #{{ $order->order_number }} has been placed.</p>
        </div>

        <!-- Receipt Card -->
        <div id="invoice-card" class="bg-white border border-gray-100 rounded-sm overflow-hidden">
            <!-- Header -->
            <div class="p-8 md:p-12 border-b border-gray-100 flex flex-wrap justify-between items-start gap-8">
                <div>
                    <div class="flex items-center gap-2 mb-6">
                        <img src="{{ asset('s4_store_logo.png') }}" alt="S4 Luxury Store" class="h-16 w-auto object-contain">
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Order Details</p>
                        <p class="text-lg font-bold text-black">#{{ $order->order_number }}</p>
                        <p class="text-sm text-gray-400 font-medium">{{ $order->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
                <div class="text-left md:text-right">
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-2">Shipping Information</p>
                    <p class="text-sm font-bold text-black">{{ $order->customer_name }}</p>
                    <p class="text-sm text-gray-600 leading-relaxed max-w-xs md:ml-auto">
                        {{ $order->shipping_address }}<br>
                        {{ $order->shipping_city }}, {{ $order->shipping_postal_code }}<br>
                        {{ $order->shipping_country }}
                    </p>
                    <p class="text-sm text-gray-600 mt-2">{{ $order->customer_phone }}</p>
                </div>
            </div>

            <!-- Items Table -->
            <div class="p-8 md:p-12">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="pb-6 text-[10px] text-gray-400 font-bold uppercase tracking-widest">Product</th>
                            <th class="pb-6 text-[10px] text-gray-400 font-bold uppercase tracking-widest text-center">Attributes</th>
                            <th class="pb-6 text-[10px] text-gray-400 font-bold uppercase tracking-widest text-center">Qty</th>
                            <th class="pb-6 text-[10px] text-gray-400 font-bold uppercase tracking-widest text-right">Price</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($order->items as $item)
                            <tr>
                                <td class="py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-16 bg-gray-50 shrink-0 overflow-hidden border border-gray-100 print:hidden">
                                             @if($item->product && $item->product->images->isNotEmpty())
                                                <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" class="w-full h-full object-cover">
                                             @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-black uppercase tracking-tight">{{ $item->product_name }}</p>
                                            <p class="text-[9px] text-gray-400 mt-1 font-bold">SKU: {{ $item->product_sku }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 text-center">
                                    <div class="space-y-1">
                                        <span class="text-[10px] font-bold text-black uppercase">{{ $item->size && $item->size != 'N/A' ? $item->size : 'Standard' }}</span>
                                        <div class="flex items-center justify-center gap-2">
                                            <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest capitalize">{{ $item->color && $item->color != 'N/A' ? $item->color : 'Original' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 text-center">
                                    <p class="text-sm font-bold text-black">{{ $item->quantity }}</p>
                                </td>
                                <td class="py-6 text-right">
                                    <p class="text-sm font-bold text-black">Rs. {{ number_format($item->subtotal, 0) }}</p>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Footer Totals -->
            <div class="bg-gray-50/50 p-8 md:p-12 flex justify-end">
                <div class="w-full md:w-80 space-y-4">
                    <div class="flex justify-between items-center text-sm font-medium">
                        <span class="text-gray-500">Subtotal</span>
                        <span class="text-black font-bold">Rs. {{ number_format($order->subtotal, 0) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm font-medium">
                        <span class="text-gray-500">Shipping Cost</span>
                        <span class="text-black font-bold">Rs. {{ number_format($order->shipping_cost, 0) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm font-medium pt-2">
                        <span class="text-gray-500 uppercase text-[10px] font-black tracking-widest">Payment Protocol</span>
                        <span class="text-black font-bold uppercase text-[10px] tracking-widest">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                    </div>
                    <div class="flex justify-between items-center border-t border-gray-200 pt-6">
                        <span class="text-sm font-bold text-black uppercase tracking-[0.2em]">Total</span>
                        <span class="text-2xl font-bold text-black">Rs. {{ number_format($order->total, 0) }}</span>
                    </div>
                    
                    <div class="pt-8 flex flex-col sm:flex-row gap-3 print:hidden">
                        <button onclick="window.print()" class="flex-1 py-4 bg-black text-white text-[10px] font-black uppercase tracking-[0.3em] hover:bg-gold transition-all flex items-center justify-center gap-3 active:scale-95">
                            <i class="fas fa-print text-[9px]"></i> Print Receipt
                        </button>
                        <button onclick="downloadPDF()" id="download-btn" class="flex-1 py-4 border border-black text-black text-[10px] font-black uppercase tracking-[0.3em] hover:bg-black hover:text-white transition-all flex items-center justify-center gap-3 active:scale-95">
                            <i class="fas fa-file-pdf text-[9px]"></i> Download PDF
                        </button>
                    </div>
                    <div class="mt-4 flex flex-col sm:flex-row gap-3 print:hidden">
                         <a href="{{ route('home') }}" class="flex-1 py-4 border border-gray-100 text-gray-400 text-[9px] font-black uppercase tracking-[0.3em] hover:bg-black hover:text-white hover:border-black transition-all text-center">
                            Marketplace
                        </a>
                         <a href="{{ route('orders.index') }}" class="flex-1 py-4 bg-gray-50 text-gray-400 text-[9px] font-black uppercase tracking-[0.3em] hover:bg-black hover:text-white transition-all text-center">
                            Deployment Log
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    header, footer, nav, .WhatsApp-Floating-Button, .WhatsApp, .floating-btn, .print\:hidden { display: none !important; }
    body { background: white; padding: 0; margin: 0; }
    .bg-gray-50 { background: white !important; py: 0 !important; }
    .container { max-width: 100% !important; width: 100% !important; padding: 0 !important; margin: 0 !important; }
    #invoice-card { box-shadow: none !important; border: none !important; width: 100% !important; margin: 0 !important; }
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function downloadPDF() {
    const orderNumber = '{{ $order->order_number }}';
    const btn = document.getElementById('download-btn');
    const originalBtnContent = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>...';
    btn.disabled = true;

    // Create a clean, table-only version for PDF
    const printArea = document.createElement('div');
    printArea.style.padding = '20px';
    printArea.style.background = 'white';
    printArea.style.fontFamily = 'serif';
    printArea.innerHTML = `
        <div style="border-bottom: 2px solid #000; padding-bottom: 20px; margin-bottom: 20px; display: flex; justify-content: space-between;">
            <div>
                <h1 style="margin:0; font-size: 24px; text-transform: uppercase;">S4 LUXURY STORE</h1>
                <p style="margin:5px 0; font-size: 12px; color: #666;">Premium Fashion & Elegance</p>
                <p style="margin:5px 0; font-size: 14px; font-weight: bold;">Order: #${orderNumber}</p>
                <p style="margin:5px 0; font-size: 12px; color: #888;">Date: {{ $order->created_at->format('M d, Y') }}</p>
            </div>
            <div style="text-align: right;">
                <h3 style="margin:0 0 10px 0; font-size: 12px; color: #888; text-transform: uppercase;">Customer</h3>
                <p style="margin:0; font-size: 14px; font-weight: bold;">{{ $order->customer_name }}</p>
                <p style="margin:5px 0; font-size: 12px; color: #444;">{{ $order->shipping_address }}</p>
                <p style="margin:5px 0; font-size: 12px; color: #444;">{{ $order->shipping_city }}, {{ $order->shipping_postal_code }}</p>
                <p style="margin:5px 0; font-size: 12px; color: #444;">{{ $order->shipping_phone }}</p>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
            <thead>
                <tr style="background: #000; color: #fff;">
                    <th style="padding: 12px; text-align: left; font-size: 10px; text-transform: uppercase;">Product</th>
                    <th style="padding: 12px; text-align: center; font-size: 10px; text-transform: uppercase;">Details</th>
                    <th style="padding: 12px; text-align: center; font-size: 10px; text-transform: uppercase;">Qty</th>
                    <th style="padding: 12px; text-align: right; font-size: 10px; text-transform: uppercase;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 15px 12px;">
                        <span style="font-weight: bold; font-size: 12px; text-transform: uppercase;">{{ $item->product_name }}</span><br>
                        <span style="font-size: 10px; color: #888;">SKU: {{ $item->product_sku }}</span>
                    </td>
                    <td style="padding: 15px 12px; text-align: center; font-size: 10px;">
                        {{ $item->size != 'N/A' ? $item->size : 'STD' }} / {{ $item->color != 'N/A' ? $item->color : 'Original' }}
                    </td>
                    <td style="padding: 15px 12px; text-align: center; font-size: 12px;">{{ $item->quantity }}</td>
                    <td style="padding: 15px 12px; text-align: right; font-weight: bold; font-size: 12px;">Rs. {{ number_format($item->subtotal) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="display: flex; justify-content: flex-end;">
            <div style="width: 250px;">
                <div style="display: flex; justify-content: space-between; padding: 5px 0; font-size: 12px;">
                    <span style="color: #888;">Subtotal:</span>
                    <span style="font-weight: bold;">Rs. {{ number_format($order->subtotal) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 5px 0; font-size: 12px;">
                    <span style="color: #888;">Shipping:</span>
                    <span style="font-weight: bold;">Rs. {{ number_format($order->shipping_cost) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; border-top: 1px solid #000; padding: 15px 0; margin-top: 10px; font-size: 16px;">
                    <span style="font-weight: bold;">Total Sum:</span>
                    <span style="font-weight: bold;">Rs. {{ number_format($order->total) }}</span>
                </div>
                <div style="margin-top: 20px; font-size: 9px; text-align: right; color: #888; text-transform: uppercase; letter-spacing: 2px;">
                    Authorized Payment: {{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}
                </div>
            </div>
        </div>

        <div style="margin-top: 50px; text-align: center; border-top: 1px solid #eee; padding-top: 20px;">
            <p style="font-size: 10px; color: #888; text-transform: uppercase; letter-spacing: 3px;">Thank you for choosing S4 Luxury Store</p>
        </div>
    `;

    const opt = {
        margin: [15, 15],
        filename: 'S4-Luxury-Invoice-' + orderNumber + '.pdf',
        image: { type: 'jpeg', quality: 1.0 },
        html2canvas: { scale: 3, useCORS: true },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };

    html2pdf().set(opt).from(printArea).save().then(() => {
        btn.innerHTML = originalBtnContent;
        btn.disabled = false;
    }).catch(err => {
        console.error('PDF Error:', err);
        btn.innerHTML = originalBtnContent;
        btn.disabled = false;
    });
}
</script>
@endsection
