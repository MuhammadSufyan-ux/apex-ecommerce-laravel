@extends('layouts.admin')

@section('title', 'Payment Gateway Control')

@section('content')
<div x-data="{ 
    showAddModal: false,
    activeGateway: null,
    showCredentials: {},
    toggleCredential(id, key) {
        const k = id + '_' + key;
        this.showCredentials[k] = !this.showCredentials[k];
    },
    isVisible(id, key) {
        return this.showCredentials[id + '_' + key] || false;
    }
}" class="relative">

    <!-- Page Header -->
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-black uppercase tracking-tighter">Payment Gateways</h1>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.3em] mt-2">Configure your payment infrastructure & API credentials</p>
        </div>
        <div class="flex gap-2">
            <button @click="showAddModal = true" class="px-8 py-3 bg-black text-white text-[10px] font-black uppercase tracking-[0.2em] shadow-xl hover:bg-gold transition-all duration-300 flex items-center gap-2">
                <i class="fas fa-plus"></i>
                Deploy Gateway
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 p-6 bg-green-50 border-l-4 border-gold text-gold flex items-center gap-4">
            <i class="fas fa-check-circle"></i>
            <p class="text-[10px] font-black uppercase tracking-widest">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-10">
        <div class="bg-white border border-gray-100 p-6 flex items-center gap-4">
            <div class="w-12 h-12 bg-black/5 flex items-center justify-center">
                <i class="fas fa-plug text-black text-lg"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-black">{{ $gateways->count() }}</p>
                <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Total Gateways</p>
            </div>
        </div>
        <div class="bg-white border border-gray-100 p-6 flex items-center gap-4">
            <div class="w-12 h-12 bg-green-50 flex items-center justify-center">
                <i class="fas fa-check-circle text-green-500 text-lg"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-green-600">{{ $gateways->where('is_active', true)->count() }}</p>
                <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Active</p>
            </div>
        </div>
        <div class="bg-white border border-gray-100 p-6 flex items-center gap-4">
            <div class="w-12 h-12 bg-yellow-50 flex items-center justify-center">
                <i class="fas fa-flask text-yellow-500 text-lg"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-yellow-600">{{ $gateways->where('is_sandbox', true)->count() }}</p>
                <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Sandbox Mode</p>
            </div>
        </div>
        <div class="bg-white border border-gray-100 p-6 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 flex items-center justify-center">
                <i class="fas fa-key text-blue-500 text-lg"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-blue-600">{{ $gateways->filter(function($g) { return $g->hasCredentials(); })->count() }}</p>
                <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Configured</p>
            </div>
        </div>
    </div>

    <!-- Gateway Cards Grid -->
    @if($gateways->count() > 0)
    <div class="grid grid-cols-1 gap-6">
        @foreach($gateways as $gateway)
        <div x-data="{ expanded: false }" class="bg-white border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-lg">
            <!-- Gateway Header -->
            <div class="p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 cursor-pointer" @click="expanded = !expanded">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 flex items-center justify-center border border-gray-100 shrink-0" style="background: {{ $gateway->icon_color }}10;">
                        <i class="{{ $gateway->icon }} text-2xl" style="color: {{ $gateway->icon_color }};"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <h3 class="text-sm font-black uppercase tracking-widest text-black">{{ $gateway->name }}</h3>
                            @if($gateway->is_active)
                                <span class="px-3 py-1 bg-green-50 text-green-600 text-[8px] font-black uppercase tracking-widest border border-green-100">Active</span>
                            @else
                                <span class="px-3 py-1 bg-gray-50 text-gray-400 text-[8px] font-black uppercase tracking-widest border border-gray-100">Inactive</span>
                            @endif
                            @if($gateway->is_sandbox)
                                <span class="px-3 py-1 bg-yellow-50 text-yellow-600 text-[8px] font-black uppercase tracking-widest border border-yellow-100">Sandbox</span>
                            @else
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[8px] font-black uppercase tracking-widest border border-blue-100">Live</span>
                            @endif
                        </div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">
                            {{ $gateway->description ?? 'No description configured' }}
                            &middot; Slug: <span class="font-mono text-black">{{ $gateway->slug }}</span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <!-- Quick Toggle -->
                    <form action="{{ route('admin.payments.toggle', $gateway) }}" method="POST" onclick="event.stopPropagation();">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-4 py-2 text-[9px] font-black uppercase tracking-widest border transition-all {{ $gateway->is_active ? 'bg-green-50 text-green-600 border-green-100 hover:bg-red-50 hover:text-red-600 hover:border-red-100' : 'bg-gray-50 text-gray-400 border-gray-100 hover:bg-green-50 hover:text-green-600 hover:border-green-100' }}">
                            <i class="fas {{ $gateway->is_active ? 'fa-power-off' : 'fa-bolt' }} mr-1"></i>
                            {{ $gateway->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.payments.destroy', $gateway) }}" method="POST" onsubmit="return confirm('Permanently remove {{ $gateway->name }}?');" onclick="event.stopPropagation();">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-10 h-10 flex items-center justify-center border border-gray-100 text-gray-400 hover:text-red-500 hover:border-red-500 hover:bg-red-50 transition-all" title="Remove Gateway">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </form>

                    <button class="w-10 h-10 flex items-center justify-center border border-gray-100 text-gray-400 hover:text-black hover:border-black transition-all">
                        <i class="fas fa-chevron-down text-xs transition-transform" :class="expanded && 'rotate-180'"></i>
                    </button>
                </div>
            </div>

            <!-- Expanded Credential Configuration -->
            <div x-show="expanded" x-collapse x-cloak>
                <div class="border-t border-gray-50">
                    <form action="{{ route('admin.payments.update', $gateway) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="p-8">
                            <!-- Mode Toggle Section -->
                            <div class="flex flex-col md:flex-row gap-8 mb-10">
                                <div class="flex-1">
                                    <h4 class="text-[11px] font-black uppercase tracking-[0.2em] text-black mb-6 flex items-center gap-2">
                                        <i class="fas fa-shield-alt text-gold"></i> Environment Configuration
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Active Status -->
                                        <div class="flex items-center justify-between p-5 bg-gray-50 border border-gray-100">
                                            <div>
                                                <p class="text-[10px] font-black uppercase tracking-widest text-black">Payment Active</p>
                                                <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mt-1">Enable on checkout page</p>
                                            </div>
                                            <div x-data="{ enabled: {{ $gateway->is_active ? 'true' : 'false' }} }">
                                                <input type="hidden" name="is_active" :value="enabled ? 1 : 0">
                                                <button type="button" @click="enabled = !enabled" :class="enabled ? 'bg-black' : 'bg-gray-200'" class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none">
                                                    <span :class="enabled ? 'translate-x-5' : 'translate-x-0'" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Sandbox/Live Toggle -->
                                        <div class="flex items-center justify-between p-5 bg-gray-50 border border-gray-100">
                                            <div>
                                                <p class="text-[10px] font-black uppercase tracking-widest text-black">Sandbox Mode</p>
                                                <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mt-1">Use test environment</p>
                                            </div>
                                            <div x-data="{ sandbox: {{ $gateway->is_sandbox ? 'true' : 'false' }} }">
                                                <input type="hidden" name="is_sandbox" :value="sandbox ? 1 : 0">
                                                <button type="button" @click="sandbox = !sandbox" :class="sandbox ? 'bg-yellow-500' : 'bg-green-500'" class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none">
                                                    <span :class="sandbox ? 'translate-x-5' : 'translate-x-0'" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Gateway Configuration -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                                <div class="space-y-4">
                                    <div>
                                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-2">Display Name</label>
                                        <input type="text" name="name" value="{{ $gateway->name }}" 
                                            placeholder="e.g. Google Pay" required
                                            class="w-full bg-gray-50 border-0 px-4 py-4 text-xs font-bold text-black focus:ring-1 focus:ring-black">
                                    </div>
                                    <div>
                                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-2">Gateway Description</label>
                                        <input type="text" name="description" value="{{ $gateway->description }}" 
                                            placeholder="Brief description of this payment method"
                                            class="w-full bg-gray-50 border-0 px-4 py-4 text-xs font-bold text-black focus:ring-1 focus:ring-black">
                                    </div>
                                </div>
                            </div>

                            <!-- API Credentials Section -->
                            <h4 class="text-[11px] font-black uppercase tracking-[0.2em] text-black mb-6 flex items-center gap-2">
                                <i class="fas fa-key text-gold"></i> API Credentials
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                @php $fields = \App\Models\PaymentGateway::getDefaultFields($gateway->slug); @endphp
                                @foreach($fields as $field)
                                <div class="space-y-2">
                                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">{{ $field['label'] }}</label>
                                    <div class="relative">
                                        @if($field['type'] === 'password')
                                            <input 
                                                :type="isVisible({{ $gateway->id }}, '{{ $field['key'] }}') ? 'text' : 'password'"
                                                name="credentials[{{ $field['key'] }}]" 
                                                value="{{ $gateway->getCredential($field['key'], '') }}"
                                                placeholder="{{ $field['placeholder'] }}"
                                                class="w-full bg-gray-50 border-0 px-4 py-4 pr-12 text-xs font-mono font-bold text-black focus:ring-1 focus:ring-black">
                                            <button type="button" 
                                                @click="toggleCredential({{ $gateway->id }}, '{{ $field['key'] }}')"
                                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-black transition-colors">
                                                <i class="fas" :class="isVisible({{ $gateway->id }}, '{{ $field['key'] }}') ? 'fa-eye-slash' : 'fa-eye'"></i>
                                            </button>
                                        @else
                                            <input 
                                                type="text"
                                                name="credentials[{{ $field['key'] }}]" 
                                                value="{{ $gateway->getCredential($field['key'], '') }}"
                                                placeholder="{{ $field['placeholder'] }}"
                                                class="w-full bg-gray-50 border-0 px-4 py-4 text-xs font-mono font-bold text-black focus:ring-1 focus:ring-black">
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Info Block -->
                            <div class="p-6 border-l-2 border-gold bg-gold/5 mb-8">
                                <p class="text-[10px] font-black uppercase tracking-widest text-gold mb-2">Security Protocol</p>
                                <p class="text-[10px] font-medium text-gray-500 leading-relaxed uppercase">
                                    All API credentials are securely stored in the database. Ensure you are using sandbox/test keys during development. Switch to live keys only when you are ready to accept real payments.
                                </p>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center justify-between pt-6 border-t border-gray-50">
                                <button type="button" 
                                    @click="if(confirm('Are you sure you want to permanently delete this payment gateway?')) { document.getElementById('delete-form-{{ $gateway->id }}').submit(); }"
                                    class="px-6 py-3 text-[9px] font-black uppercase tracking-widest text-red-400 hover:text-red-600 hover:bg-red-50 border border-transparent hover:border-red-100 transition-all">
                                    <i class="fas fa-trash-alt mr-1"></i> Remove Gateway
                                </button>
                                <button type="submit" class="px-10 py-3 bg-black text-white text-[10px] font-black uppercase tracking-[0.2em] shadow-xl hover:bg-gold transition-all duration-300">
                                    <i class="fas fa-save mr-1"></i> Save Credentials
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Hidden Delete Form (Outside main form to prevent nesting) -->
                    <form id="delete-form-{{ $gateway->id }}" action="{{ route('admin.payments.destroy', $gateway) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white border border-gray-100 p-16 text-center">
        <div class="w-20 h-20 mx-auto bg-gray-50 flex items-center justify-center mb-6">
            <i class="fas fa-credit-card text-3xl text-gray-200"></i>
        </div>
        <h3 class="text-sm font-black uppercase tracking-widest text-black mb-2">No Payment Gateways Deployed</h3>
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-8">Configure your first payment method to start accepting payments</p>
        <button @click="showAddModal = true" class="px-10 py-4 bg-black text-white text-[10px] font-black uppercase tracking-[0.2em] shadow-xl hover:bg-gold transition-all">
            <i class="fas fa-plus mr-2"></i> Deploy First Gateway
        </button>
    </div>
    @endif

    <!-- Preset Quick Deploy Section -->
    <div class="mt-10 bg-white border border-gray-100 p-8">
        <h3 class="text-[11px] font-black uppercase tracking-[0.3em] text-black mb-6 flex items-center gap-3">
            <i class="fas fa-rocket text-gold"></i> Quick Deploy Presets
        </h3>
        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-6">Click any preset to instantly deploy a pre-configured gateway template</p>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @php
                $presets = [
                    ['name' => 'Visa', 'icon' => 'fab fa-cc-visa', 'color' => '#1A1F71', 'desc' => 'Visa card processing'],
                    ['name' => 'Mastercard', 'icon' => 'fab fa-cc-mastercard', 'color' => '#EB001B', 'desc' => 'Mastercard processing'],
                    ['name' => 'Discover', 'icon' => 'fab fa-cc-discover', 'color' => '#FF6000', 'desc' => 'Discover card processing'],
                    ['name' => 'Amex', 'icon' => 'fab fa-cc-amex', 'color' => '#007BC1', 'desc' => 'American Express'],
                    ['name' => 'Stripe', 'icon' => 'fab fa-stripe', 'color' => '#635BFF', 'desc' => 'Global card payments'],
                    ['name' => 'Google Pay', 'icon' => 'fab fa-google-pay', 'color' => '#4285F4', 'desc' => 'Google mobile wallet'],
                    ['name' => 'JazzCash', 'icon' => 'fas fa-wallet', 'color' => '#E2232A', 'desc' => 'Pakistan mobile wallet'],
                    ['name' => 'EasyPaisa', 'icon' => 'fas fa-mobile-alt', 'color' => '#00A650', 'desc' => 'Pakistan mobile wallet'],
                    ['name' => 'HBL', 'icon' => 'fas fa-university', 'color' => '#006747', 'desc' => 'HBL Bank gateway'],
                    ['name' => 'UBL', 'icon' => 'fas fa-university', 'color' => '#003366', 'desc' => 'UBL Bank gateway'],
                    ['name' => 'Debit Card', 'icon' => 'far fa-credit-card', 'color' => '#6366F1', 'desc' => 'Direct debit entry'],
                    ['name' => 'COD', 'icon' => 'fas fa-hand-holding-usd', 'color' => '#059669', 'desc' => 'Cash on delivery'],
                ];
            @endphp
            @foreach($presets as $preset)
                @php
                    $slug = \Illuminate\Support\Str::slug($preset['name'], '_');
                    $deployedGateway = $gateways->firstWhere('slug', $slug);
                @endphp
                @if(!$deployedGateway)
                <form action="{{ route('admin.payments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="name" value="{{ $preset['name'] }}">
                    <input type="hidden" name="icon" value="{{ $preset['icon'] }}">
                    <input type="hidden" name="icon_color" value="{{ $preset['color'] }}">
                    <input type="hidden" name="description" value="{{ $preset['desc'] }}">
                    <button type="submit" class="w-full h-full p-5 border border-gray-100 hover:border-gold text-center group transition-all duration-300 hover:shadow-lg bg-white relative">
                        <div class="w-12 h-12 mx-auto flex items-center justify-center border border-gray-50 mb-3 group-hover:border-gold/30 transition-all rounded-full" style="background: {{ $preset['color'] }}08;">
                            <i class="{{ $preset['icon'] }} text-xl group-hover:scale-110 transition-transform" style="color: {{ $preset['color'] }};"></i>
                        </div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-black mb-1">{{ $preset['name'] }}</p>
                        <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest group-hover:text-gold transition-colors">Deploy Now</p>
                    </button>
                </form>
                @else
                <div class="relative group p-5 border border-green-100 bg-white text-center hover:border-red-200 transition-all h-full">
                    <div class="absolute inset-0 bg-red-50/95 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity z-10 backdrop-blur-sm cursor-pointer">
                        <form action="{{ route('admin.payments.destroy', $deployedGateway) }}" method="POST" onsubmit="return confirm('Undeploy {{ $preset['name'] }}? All settings for this gateway will be lost.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-5 py-2.5 bg-red-500 text-white text-[9px] font-black uppercase tracking-[0.2em] shadow-xl hover:bg-red-600 hover:scale-105 transition-all w-full mb-2">
                                <i class="fas fa-trash-alt mr-1"></i> Undeploy
                            </button>
                        </form>
                        <p class="text-[8px] font-bold text-red-400 uppercase tracking-widest">Irreversible Action</p>
                    </div>

                    <div class="w-12 h-12 mx-auto flex items-center justify-center border border-green-100 mb-3 bg-green-50 rounded-full">
                        <i class="{{ $preset['icon'] }} text-xl text-green-600"></i>
                    </div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-black mb-1">{{ $preset['name'] }}</p>
                    <p class="text-[8px] font-bold text-green-500 uppercase tracking-widest">
                        <i class="fas fa-check-circle mr-1"></i> Deployed
                    </p>
                </div>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Add Custom Gateway Modal -->
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="showAddModal = false">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showAddModal = false"></div>
        <div class="relative bg-white w-full max-w-lg shadow-2xl" @click.stop>
            <div class="p-8 border-b border-gray-100">
                <h2 class="text-lg font-black uppercase tracking-tighter text-black">Deploy Custom Gateway</h2>
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">Add a custom payment integration</p>
            </div>
            <form action="{{ route('admin.payments.store') }}" method="POST" class="p-8 space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Gateway Name *</label>
                    <input type="text" name="name" required placeholder="e.g. My Payment Provider"
                        class="w-full bg-gray-50 border-0 px-4 py-4 text-xs font-bold text-black focus:ring-1 focus:ring-black">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Icon Class</label>
                        <input type="text" name="icon" placeholder="fas fa-credit-card"
                            class="w-full bg-gray-50 border-0 px-4 py-4 text-xs font-mono font-bold text-black focus:ring-1 focus:ring-black">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Icon Color</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="icon_color" value="#000000" class="w-12 h-12 border-0 p-0 cursor-pointer bg-gray-50">
                        </div>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Description</label>
                    <input type="text" name="description" placeholder="Brief description of this gateway"
                        class="w-full bg-gray-50 border-0 px-4 py-4 text-xs font-bold text-black focus:ring-1 focus:ring-black">
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" @click="showAddModal = false" class="px-8 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-black transition-all border border-gray-100">
                        Cancel
                    </button>
                    <button type="submit" class="px-8 py-3 bg-black text-white text-[10px] font-black uppercase tracking-[0.2em] shadow-xl hover:bg-gold transition-all duration-300">
                        <i class="fas fa-rocket mr-1"></i> Deploy
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
