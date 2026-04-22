@extends('layout')

@section('title', 'Checkout - 4S Luxury Store')

@section('content')
<!-- Breadcrumb -->
<div class="bg-gray-100 py-4">
    <div class="container mx-auto px-4">
        <div class="breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-black transition-colors">
                <i class="fas fa-home"></i> Home
            </a>
            <span class="text-gray-400">/</span>
            <a href="{{ route('cart.index') }}" class="hover:text-black transition-colors">
                Cart
            </a>
            <span class="text-gray-400">/</span>
            <span class="text-black font-black uppercase tracking-widest text-[10px]">Checkout</span>
        </div>
    </div>
</div>

<div class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-5xl font-['Playfair_Display'] font-black text-black mb-4 uppercase tracking-tighter">Secure Checkout</h1>
            <p class="text-gray-400 text-sm uppercase tracking-widest">Complete your military-grade transaction</p>
        </div>
        
        <form action="{{ route('checkout.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            @csrf
            
            <!-- Left Column - Billing & Shipping -->
            <div class="lg:col-span-2 space-y-12">
                <!-- Billing Information -->
                <div class="bg-white border border-gray-100 p-8 md:p-12">
                    <h2 class="text-xl font-black text-black mb-10 flex items-center gap-4 uppercase tracking-tighter">
                        <span class="w-8 h-8 bg-black text-white flex items-center justify-center text-xs">01</span>
                        Billing Information
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">
                                First Name
                            </label>
                            <input type="text" name="first_name" required 
                                   class="w-full px-5 py-4 bg-white border-0 focus:ring-1 focus:ring-black transition-all outline-none text-sm font-bold">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">
                                Last Name
                            </label>
                            <input type="text" name="last_name" required 
                                   class="w-full px-5 py-4 bg-white border-0 focus:ring-1 focus:ring-black transition-all outline-none text-sm font-bold">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">
                                Email Address
                            </label>
                            <input type="email" name="email" required 
                                   class="w-full px-5 py-4 bg-white border-0 focus:ring-1 focus:ring-black transition-all outline-none text-sm font-bold">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">
                                Phone Number
                            </label>
                            <input type="tel" name="phone" required 
                                   class="w-full px-5 py-4 bg-white border-0 focus:ring-1 focus:ring-black transition-all outline-none text-sm font-bold"
                                   placeholder="03XX-XXXXXXX">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">
                                Street Address
                            </label>
                            <input type="text" name="address" required 
                                   class="w-full px-5 py-4 bg-white border-0 focus:ring-1 focus:ring-black transition-all outline-none text-sm font-bold">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">
                                City
                            </label>
                            <input type="text" name="city" required 
                                   class="w-full px-5 py-4 bg-white border-0 focus:ring-1 focus:ring-black transition-all outline-none text-sm font-bold">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">
                                Postal Code
                            </label>
                            <input type="text" name="postal_code" required 
                                   class="w-full px-5 py-4 bg-white border-0 focus:ring-1 focus:ring-black transition-all outline-none text-sm font-bold">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">
                                Order Notes (Optional)
                            </label>
                            <textarea name="notes" rows="3" 
                                      class="w-full px-5 py-4 bg-white border-0 focus:ring-1 focus:ring-black transition-all outline-none text-sm font-bold"
                                      placeholder="Special instructions..."></textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Method -->
                <div class="bg-white border border-gray-100 p-8 md:p-12">
                    <h2 class="text-xl font-black text-black mb-10 flex items-center gap-4 uppercase tracking-tighter">
                        <span class="w-8 h-8 bg-black text-white flex items-center justify-center text-xs">02</span>
                        Payment Method
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($paymentGateways as $gateway)
                        <label class="payment-card cursor-pointer group relative">
                            <input type="radio" name="payment_method" value="{{ $gateway->slug }}" required class="hidden peer payment-radio">
                            <div class="p-6 border-2 border-gray-100 bg-white text-center transition-all peer-checked:bg-white peer-checked:border-black group-hover:border-black relative overflow-hidden h-full flex flex-col items-center justify-center min-h-[140px] shadow-sm hover:shadow-md">
                                <!-- Branded Accent Line -->
                                <div class="absolute top-0 left-0 w-full h-1 opacity-0 peer-checked:opacity-100 transition-opacity" style="background-color: {{ $gateway->icon_color ?? '#000' }}"></div>
                                
                                <!-- Green High-Contrast Tick -->
                                <div class="absolute top-3 right-3 opacity-0 peer-checked:opacity-100 transition-all transform scale-50 peer-checked:scale-100 z-20">
                                    <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center border-2 border-white shadow-sm">
                                        <i class="fas fa-check text-[10px] text-white"></i>
                                    </div>
                                </div>

                                <!-- Tactical Selection Glow -->
                                <div class="absolute inset-0 transition-opacity opacity-0 peer-checked:opacity-5" style="background-color: {{ $gateway->icon_color ?? '#000' }}"></div>

                                @if($gateway->slug === 'google_pay')
                                    <div id="google-pay-button" class="w-full relative z-10 scale-95 origin-center"></div>
                                @else
                                    <div class="flex flex-col items-center justify-center transition-transform group-hover:scale-110 duration-300">
                                        <div class="w-16 h-16 flex items-center justify-center mb-3">
                                            <i class="{{ $gateway->icon }} text-4xl" style="color: {{ $gateway->icon_color ?? '#000' }};"></i>
                                        </div>
                                        <p class="text-[11px] font-black uppercase tracking-[0.2em] text-black">{{ $gateway->name }}</p>
                                        <div class="mt-2 text-[8px] font-bold text-gray-400 uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">Select Protocol</div>
                                    </div>
                                @endif
                            </div>
                        </label>
                        @empty
                        <div class="col-span-3 text-center py-12 border border-dashed border-gray-200">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">No protocol-active gateways</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Credit Card Details (Compact) -->
                    <div id="card-details" class="hidden mt-8 p-8 bg-black text-white">
                        <h3 class="text-xs font-black uppercase tracking-widest mb-6">Card Information</h3>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2">Card Number</label>
                                <input type="text" placeholder="XXXX XXXX XXXX XXXX" 
                                       class="w-full bg-white/10 border-0 px-4 py-3 outline-none focus:ring-1 focus:ring-gold text-sm font-bold">
                            </div>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2">Expiry Date</label>
                                    <input type="text" placeholder="MM/YY" 
                                           class="w-full bg-white/10 border-0 px-4 py-3 outline-none focus:ring-1 focus:ring-gold text-sm font-bold">
                                </div>
                                <div>
                                    <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-2">Security Code</label>
                                    <input type="text" placeholder="CVV" 
                                           class="w-full bg-white/10 border-0 px-4 py-3 outline-none focus:ring-1 focus:ring-gold text-sm font-bold">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="standard-submit-btn">
                        <button type="submit" class="w-full py-5 bg-black text-white text-[11px] font-black uppercase tracking-[0.4em] hover:bg-gold transition-all active:scale-95 flex items-center justify-center gap-3">
                            <i class="fas fa-lock text-[9px]"></i>
                            Authorize Order
                        </button>
                    </div>
                    
                    <p class="mt-8 text-center text-[8px] font-bold text-gray-400 uppercase tracking-[0.2em]">
                        <i class="fas fa-shield-alt mr-1"></i> End-to-End Encrypted Authentication
                    </p>
                </div>
            </div>

            <!-- Right Column - Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white text-black p-8 md:p-10 sticky top-32 border border-gray-100">
                    <h2 class="text-xl font-black mb-10 uppercase tracking-tighter flex items-center justify-between border-b border-gray-50 pb-4">
                        Order Summary
                        <span class="text-[10px] text-gray-400 font-bold tracking-widest">{{ count(session('cart', [])) }} Items</span>
                    </h2>
                    
                    <div class="space-y-6 mb-10 max-h-64 overflow-y-auto custom-scrollbar pr-4">
                        @php
                            $cartItems = session('cart', []);
                            $subtotal = 0;
                        @endphp
                        
                        @forelse($cartItems as $item)
                            @php
                                $itemTotal = $item['price'] * $item['quantity'];
                                $subtotal += $itemTotal;
                            @endphp
                            <div class="flex gap-4 pb-6 border-b border-gray-50 items-center">
                                <div class="w-14 h-14 bg-white shrink-0 overflow-hidden border border-gray-100">
                                     <img src="{{ $item['image'] ?? '' }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-[10px] font-black text-black uppercase tracking-tight truncate">{{ $item['name'] }}</h4>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase mt-1">Qty {{ $item['quantity'] }} &bull; {{ $item['size'] ?? 'Standard' }}</p>
                                </div>
                                <p class="text-[10px] font-black text-black">Rs. {{ number_format($itemTotal, 0) }}</p>
                            </div>
                        @empty
                            <p class="text-[10px] font-bold text-gray-300 text-center py-8 uppercase tracking-widest">Tactical bag is empty</p>
                        @endforelse
                    </div>
                    
                    <!-- Totals -->
                    <div class="space-y-4 mb-10">
                        <div class="flex justify-between text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            <span>Subtotal Load</span>
                            <span>Rs. {{ number_format($subtotal, 0) }}</span>
                        </div>
                        <div class="flex justify-between text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            <span>Logistic Cost</span>
                            <span class="{{ $subtotal >= 5000 ? 'text-green-600' : 'text-black' }}">
                                {{ $subtotal >= 5000 ? 'COMPLIMENTARY' : 'Rs. 200' }}
                            </span>
                        </div>
                        <div class="pt-6 border-t border-gray-50 flex justify-between items-center mt-6">
                            <span class="text-xs font-black text-black uppercase tracking-[0.2em]">Full Amount</span>
                            @php $finalTotal = $subtotal >= 5000 ? $subtotal : $subtotal + 200; @endphp
                            <span class="text-2xl font-black text-black tracking-tighter">Rs. {{ number_format($finalTotal, 0) }}</span>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full py-5 bg-black text-white text-[11px] font-black uppercase tracking-[0.4em] hover:bg-gold transition-all active:scale-95 flex items-center justify-center gap-3">
                        <i class="fas fa-lock text-[9px]"></i>
                        Authorize Order
                    </button>
                    
                    <p class="mt-8 text-center text-[8px] font-bold text-gray-400 uppercase tracking-[0.2em]">
                        <i class="fas fa-shield-alt mr-1"></i> End-to-End Encrypted Authentication
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>

@php
    $googlePayGateway = $paymentGateways->where('slug', 'google_pay')->first();
    $gpayConfig = [
        'merchantId' => $googlePayGateway ? $googlePayGateway->getCredential('merchant_id', '') : '',
        'merchantName' => $googlePayGateway ? $googlePayGateway->getCredential('merchant_name', 'S4 Luxury Store') : 'S4 Luxury Store',
        'env' => ($googlePayGateway && !$googlePayGateway->is_sandbox) ? 'PRODUCTION' : 'TEST',
        'total' => $finalTotal
    ];
@endphp

@push('scripts')
<script src="https://pay.google.com/gp/p/js/pay.js"></script>
<script>
const gpayConfig = @json($gpayConfig);

// Initialize GPay immediately
document.addEventListener('DOMContentLoaded', function() {
    onGooglePayLoaded();
});

// Show/hide card details based on payment method
document.querySelectorAll('.payment-radio').forEach(radio => {
    radio.addEventListener('change', function() {
        const cardDetails = document.getElementById('card-details');
        const standardBtn = document.getElementById('standard-submit-btn');

        // Reset
        cardDetails.classList.add('hidden');
        standardBtn.classList.remove('hidden');

        if (this.value === 'credit_card' || this.value === 'debit_card') {
            cardDetails.classList.remove('hidden');
        } else if (this.value === 'google_pay') {
            standardBtn.classList.add('hidden');
        }
    });
});

/** Google Pay Setup **/
const baseRequest = {
  apiVersion: 2,
  apiVersionMinor: 0
};

const tokenizationSpecification = {
  type: 'PAYMENT_GATEWAY',
  parameters: {
    'gateway': 'example', // Replace with your gateway (e.g., 'stripe')
    'gatewayMerchantId': 'exampleGatewayMerchantId'
  }
};

const allowedCardNetworks = ["AMEX", "DISCOVER", "INTERAC", "JCB", "MASTERCARD", "VISA"];
const allowedCardAuthMethods = ["PAN_ONLY", "CRYPTOGRAM_3DS"];

const baseCardPaymentMethod = {
  type: 'CARD',
  parameters: {
    allowedAuthMethods: allowedCardAuthMethods,
    allowedCardNetworks: allowedCardNetworks
  }
};

const cardPaymentMethod = Object.assign(
  {},
  baseCardPaymentMethod,
  {
    tokenizationSpecification: tokenizationSpecification
  }
);

let paymentsClient = null;

function getGoogleIsReadyToPayRequest() {
  return Object.assign(
      {},
      baseRequest,
      {
        allowedPaymentMethods: [baseCardPaymentMethod]
      }
  );
}

function getGooglePaymentDataRequest() {
  const paymentDataRequest = Object.assign({}, baseRequest);
  paymentDataRequest.allowedPaymentMethods = [cardPaymentMethod];
  paymentDataRequest.transactionInfo = getGoogleTransactionInfo();
  paymentDataRequest.merchantInfo = {
    merchantName: gpayConfig.merchantName
  };
  
  if (gpayConfig.merchantId) {
      paymentDataRequest.merchantInfo.merchantId = gpayConfig.merchantId;
  }

  return paymentDataRequest;
}

function getGooglePaymentsClient() {
  if (paymentsClient === null) {
    paymentsClient = new google.payments.api.PaymentsClient({
        environment: gpayConfig.env
    });
  }
  return paymentsClient;
}

function onGooglePayLoaded() {
  const paymentsClient = getGooglePaymentsClient();
  paymentsClient.isReadyToPay(getGoogleIsReadyToPayRequest())
      .then(function(response) {
        if (response.result) {
          addGooglePayButton();
        }
      })
      .catch(function(err) {
        console.error("GPay Ready Error:", err);
      });
}

function addGooglePayButton() {
  const paymentsClient = getGooglePaymentsClient();
  const button = paymentsClient.createButton({
      onClick: onGooglePaymentButtonClicked,
      buttonType: 'buy',
      buttonSizeMode: 'fill'
  });
  const container = document.getElementById('google-pay-button');
  if (container) {
    container.innerHTML = '';
    container.appendChild(button);
  }
}

function getGoogleTransactionInfo() {
  return {
    displayItems: [
      {
        label: "Subtotal",
        type: "SUBTOTAL",
        price: gpayConfig.total.toString(),
      }
    ],
    countryCode: 'PK',
    currencyCode: "PKR",
    totalPriceStatus: "FINAL",
    totalPrice: gpayConfig.total.toString(),
    totalPriceLabel: "Total"
  };
}

function onGooglePaymentButtonClicked() {
  // Check the radio button
  const gpayRadio = document.querySelector('input[value="google_pay"]');
  if (gpayRadio) gpayRadio.checked = true;

  // Validate form first
  const form = document.querySelector('form');
  if (!form.checkValidity()) {
      form.reportValidity();
      return;
  }

  const paymentsClient = getGooglePaymentsClient();
  const paymentDataRequest = getGooglePaymentDataRequest();
  paymentDataRequest.transactionInfo = getGoogleTransactionInfo();

  paymentsClient.loadPaymentData(paymentDataRequest)
      .then(function(paymentData) {
        // Handle the response
        processPayment(paymentData);
      })
      .catch(function(err) {
        console.error("GPay Payment Error:", err);
      });
}

function processPayment(paymentData) {
  const form = document.querySelector('form');
  const gpayInput = document.createElement('input');
  gpayInput.type = 'hidden';
  gpayInput.name = 'gpay_token';
  gpayInput.value = JSON.stringify(paymentData.paymentMethodData);
  form.appendChild(gpayInput);
  form.submit();
}
</script>
