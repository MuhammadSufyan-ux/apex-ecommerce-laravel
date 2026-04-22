@extends('layouts.admin')

@section('title', 'System Configuration')

@section('content')
<div x-data="{ activeTab: 'general' }" class="relative">
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-black uppercase tracking-tighter">System Master Config</h1>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.3em] mt-2">Adjust your digital infrastructure</p>
        </div>
        <div class="flex gap-2">
            <button form="settingsForm" type="submit" class="px-8 py-3 bg-black text-white text-[10px] font-black uppercase tracking-[0.2em] shadow-xl hover:bg-gold transition-all duration-300">
                Commit Changes
            </button>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="flex flex-wrap gap-1 mb-8 bg-gray-50 p-1 border border-gray-100">
        <template x-for="tab in ['general', 'aesthetics', 'homepage', 'contact', 'finance', 'seo', 'notifications']">
            <button @click="activeTab = tab" 
                :class="activeTab === tab ? 'bg-black text-white' : 'text-gray-400 hover:text-black hover:bg-white'"
                class="px-8 py-3 text-[10px] font-black uppercase tracking-widest transition-all">
                <span x-text="tab.replace('_', ' ')"></span>
            </button>
        </template>
    </div>

    @if(session('success'))
        <div class="mb-8 p-6 bg-green-50 border-l-4 border-gold text-gold flex items-center gap-4">
            <i class="fas fa-check-circle"></i>
            <p class="text-[10px] font-black uppercase tracking-widest">{{ session('success') }}</p>
        </div>
    @endif

    <form id="settingsForm" action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- General Settings -->
        <div x-show="activeTab === 'general'" x-cloak class="space-y-8">
            <div class="bg-white border border-gray-100 p-8">
                <h3 class="text-[11px] font-black uppercase tracking-[0.3em] text-black mb-8 flex items-center gap-3">
                    <i class="fas fa-store text-gold"></i> Core Identity
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Store Brand Name</label>
                        <input type="text" name="store_name" value="{{ \App\Models\Setting::getValue('store_name') }}" 
                            class="w-full bg-gray-50 border-0 px-4 py-4 text-xs font-bold text-black focus:ring-1 focus:ring-black">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Master Logo (PNG/SVG)</label>
                        <div class="flex items-center gap-4">
                            @if(\App\Models\Setting::getValue('store_logo'))
                                <img src="{{ asset('storage/' . \App\Models\Setting::getValue('store_logo')) }}" class="h-12 w-auto border border-gray-100 p-2">
                            @endif
                            <input type="file" name="store_logo" class="text-xs font-bold text-gray-400 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-black file:text-white hover:file:bg-gold file:transition-all">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aesthetics Settings -->
        <div x-show="activeTab === 'aesthetics'" x-cloak class="space-y-8">
            <div class="bg-white border border-gray-100 p-8">
                <h3 class="text-[11px] font-black uppercase tracking-[0.3em] text-black mb-8 flex items-center gap-3">
                    <i class="fas fa-palette text-gold"></i> Visual DNA
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Primary Accent Color</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="primary_color" value="{{ \App\Models\Setting::getValue('primary_color', '#D4AF37') }}" 
                                class="w-12 h-12 border-0 p-0 cursor-pointer">
                            <input type="text" value="{{ \App\Models\Setting::getValue('primary_color', '#D4AF37') }}" readonly
                                class="flex-1 bg-gray-50 border-0 px-4 py-3 text-xs font-mono font-bold text-black uppercase tracking-widest">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Typography Core Family</label>
                        <select name="font_family" class="w-full bg-gray-50 border-0 px-4 py-4 text-xs font-bold text-black focus:ring-1 focus:ring-black uppercase">
                            <option value="'Outfit', sans-serif" {{ \App\Models\Setting::getValue('font_family') == "'Outfit', sans-serif" ? 'selected' : '' }}>Outfit (Premium Modern)</option>
                            <option value="'Inter', sans-serif" {{ \App\Models\Setting::getValue('font_family') == "'Inter', sans-serif" ? 'selected' : '' }}>Inter (Clean UI)</option>
                            <option value="'Playfair Display', serif" {{ \App\Models\Setting::getValue('font_family') == "'Playfair Display', serif" ? 'selected' : '' }}>Playfair (Luxury Serif)</option>
                            <option value="'Montserrat', sans-serif" {{ \App\Models\Setting::getValue('font_family') == "'Montserrat', sans-serif" ? 'selected' : '' }}>Montserrat (Bold Fashion)</option>
                            <option value="'Roboto', sans-serif" {{ \App\Models\Setting::getValue('font_family') == "'Roboto', sans-serif" ? 'selected' : '' }}>Roboto (Universal)</option>
                            <option value="'Cinzel', serif" {{ \App\Models\Setting::getValue('font_family') == "'Cinzel', serif" ? 'selected' : '' }}>Cinzel (Classic Royal)</option>
                            <option value="'Poiret One', cursive" {{ \App\Models\Setting::getValue('font_family') == "'Poiret One', cursive" ? 'selected' : '' }}>Poiret One (Art Deco)</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Standard Typography Scale</label>
                        <select name="base_font_size" class="w-full bg-gray-50 border-0 px-4 py-4 text-xs font-bold text-black focus:ring-1 focus:ring-black">
                            <option value="14px" {{ \App\Models\Setting::getValue('base_font_size') == '14px' ? 'selected' : '' }}>14px (Compact)</option>
                            <option value="16px" {{ \App\Models\Setting::getValue('base_font_size') == '16px' ? 'selected' : '' }}>16px (Standard)</option>
                            <option value="18px" {{ \App\Models\Setting::getValue('base_font_size') == '18px' ? 'selected' : '' }}>18px (Large)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Homepage Settings -->
        <div x-show="activeTab === 'homepage'" x-cloak class="space-y-8">
            <div class="bg-white border border-gray-100 p-8">
                <h3 class="text-[11px] font-black uppercase tracking-[0.3em] text-black mb-8 flex items-center gap-3">
                    <i class="fas fa-images text-gold"></i> Hero Section Deployment
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-8">
                    @foreach(\App\Models\Setting::getValue('hero_images', []) as $index => $img)
                        <div class="relative group aspect-[16/6] bg-black overflow-hidden border border-white/5">
                            <img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-cover opacity-80 group-hover:scale-105 transition-transform duration-700">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center gap-4">
                                <label class="w-10 h-10 bg-white text-black rounded-full flex items-center justify-center cursor-pointer hover:bg-gold hover:text-white transition-all shadow-xl">
                                    <i class="fas fa-plus text-xs"></i>
                                    <input type="file" name="hero_images[]" class="hidden">
                                </label>
                                <form action="{{ route('admin.settings.delete-image') }}" method="POST" onsubmit="return confirm('EXTERMINATE THIS FRAME?')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="key" value="hero_images">
                                    <input type="hidden" name="image" value="{{ $img }}">
                                    <button type="submit" class="w-10 h-10 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition-all shadow-xl">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                    <div class="aspect-[16/6] border-2 border-dashed border-gray-100 flex items-center justify-center p-8 text-center group hover:border-gold transition-all cursor-pointer relative overflow-hidden bg-gray-50/50">
                        <input type="file" name="hero_images[]" multiple class="absolute inset-0 opacity-0 cursor-pointer">
                        <div class="transform group-hover:scale-110 transition-transform">
                            <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center mx-auto mb-4 group-hover:bg-gold group-hover:text-white transition-all">
                                <i class="fas fa-cloud-upload-alt text-gray-300 group-hover:text-inherit"></i>
                            </div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em]">Deploy New Frame</p>
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-gray-50">
                    <h3 class="text-[11px] font-black uppercase tracking-[0.3em] text-black mb-8">Collection Grid Thumbnails</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                        @for($i = 1; $i <= 4; $i++)
                        <div class="space-y-4">
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Category Grid #{{$i}}</label>
                            <div class="aspect-[3/4] bg-gray-50 border border-gray-100 relative group overflow-hidden">
                                @if(\App\Models\Setting::getValue('cat_img_'.$i))
                                    <img src="{{ asset('storage/' . \App\Models\Setting::getValue('cat_img_'.$i)) }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 flex items-center justify-center gap-3 transition-all z-20">
                                        <label class="w-8 h-8 bg-white text-black rounded-full flex items-center justify-center cursor-pointer hover:bg-gold hover:text-white transition-all">
                                            <i class="fas fa-plus text-[10px]"></i>
                                            <input type="file" name="cat_img_{{$i}}" class="hidden">
                                        </label>
                                        <form action="{{ route('admin.settings.delete-image') }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="key" value="cat_img_{{$i}}">
                                            <input type="hidden" name="image" value="{{ \App\Models\Setting::getValue('cat_img_'.$i) }}">
                                            <button type="submit" class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition-all">
                                                <i class="fas fa-times text-[10px]"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div class="w-full h-full flex items-center justify-center opacity-20">
                                        <i class="fas fa-image text-4xl"></i>
                                    </div>
                                    <input type="file" name="cat_img_{{$i}}" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                                @endif
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Settings -->
        <div x-show="activeTab === 'contact'" x-cloak class="space-y-8">
            <div class="bg-white border border-gray-100 p-8">
                <h3 class="text-[11px] font-black uppercase tracking-[0.3em] text-black mb-8 flex items-center gap-3">
                    <i class="fas fa-address-book text-gold"></i> Global Contact Network
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">HQ Physical Location</label>
                            <textarea name="contact_address" rows="3" class="w-full bg-gray-50 border-0 px-4 py-4 text-xs font-bold text-black focus:ring-1 focus:ring-black uppercase">{{ \App\Models\Setting::getValue('contact_address') }}</textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Public Support Email</label>
                                <input type="email" name="contact_email" value="{{ \App\Models\Setting::getValue('contact_email') }}" class="w-full bg-gray-50 border-0 px-4 py-4 text-xs font-bold text-black focus:ring-1 focus:ring-black">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Customer Support Phone (Line 1)</label>
                                <input type="text" name="contact_phone" value="{{ \App\Models\Setting::getValue('contact_phone') }}" class="w-full bg-gray-50 border-0 px-4 py-4 text-xs font-bold text-black focus:ring-1 focus:ring-black">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Customer Support Phone (Line 2)</label>
                                <input type="text" name="contact_phone_2" value="{{ \App\Models\Setting::getValue('contact_phone_2') }}" class="w-full bg-gray-50 border-0 px-4 py-4 text-xs font-bold text-black focus:ring-1 focus:ring-black">
                            </div>
                        </div>
                    </div>
                    <div class="space-y-6 bg-gray-50 p-6">
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-4">Social Ecosystem Links</label>
                        @php $socials = \App\Models\Setting::getValue('social_links', []); @endphp
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#1877F2]/10 text-[#1877F2] flex items-center justify-center border border-[#1877F2]/20"><i class="fab fa-facebook-f text-xs"></i></div>
                                <input type="text" name="facebook_link" value="{{ $socials['facebook'] ?? '' }}" placeholder="FACEBOOK URL" class="flex-1 bg-white border-0 px-4 py-2.5 text-[10px] font-bold text-black focus:ring-1 focus:ring-black">
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#E4405F]/10 text-[#E4405F] flex items-center justify-center border border-[#E4405F]/20"><i class="fab fa-instagram text-xs"></i></div>
                                <input type="text" name="instagram_link" value="{{ $socials['instagram'] ?? '' }}" placeholder="INSTAGRAM URL" class="flex-1 bg-white border-0 px-4 py-2.5 text-[10px] font-bold text-black focus:ring-1 focus:ring-black">
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#25D366]/10 text-[#25D366] flex items-center justify-center border border-[#25D366]/20"><i class="fab fa-whatsapp text-xs"></i></div>
                                <input type="text" name="whatsapp_link" value="{{ $socials['whatsapp'] ?? '' }}" placeholder="WHATSAPP NUMBER" class="flex-1 bg-white border-0 px-4 py-2.5 text-[10px] font-bold text-black focus:ring-1 focus:ring-black">
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#FF0050]/10 text-[#FF0050] flex items-center justify-center border border-[#FF0050]/20"><i class="fab fa-tiktok text-xs"></i></div>
                                <input type="text" name="tiktok_link" value="{{ $socials['tiktok'] ?? '' }}" placeholder="TIKTOK URL" class="flex-1 bg-white border-0 px-4 py-2.5 text-[10px] font-bold text-black focus:ring-1 focus:ring-black">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Finance Settings -->
        <div x-show="activeTab === 'finance'" x-cloak class="space-y-8">
            <div class="bg-white border border-gray-100 p-8">
                <h3 class="text-[11px] font-black uppercase tracking-[0.3em] text-black mb-8 flex items-center gap-3">
                    <i class="fas fa-coins text-gold"></i> Finance & Tax Matrix
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Currency Symbol</label>
                                <input type="text" name="currency_symbol" value="{{ \App\Models\Setting::getValue('currency_symbol', 'Rs.') }}" class="w-full bg-gray-50 border-0 px-4 py-4 text-xs font-bold text-black focus:ring-1 focus:ring-black">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Currency Code (ISO)</label>
                                <input type="text" name="currency_code" value="{{ \App\Models\Setting::getValue('currency_code', 'PKR') }}" class="w-full bg-gray-50 border-0 px-4 py-4 text-xs font-bold text-black focus:ring-1 focus:ring-black">
                            </div>
                        </div>
                    </div>
                    <div class="space-y-6 border-l border-gray-50 pl-8">
                        <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-100">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-black">Tax Calculation</p>
                                <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mt-1">Enable global tax protocol</p>
                            </div>
                            <div x-data="{ enabled: {{ \App\Models\Setting::getValue('tax_enabled', 0) }} }">
                                <input type="hidden" name="tax_enabled" :value="enabled ? 1 : 0">
                                <button type="button" @click="enabled = !enabled" :class="enabled ? 'bg-black' : 'bg-gray-200'" class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none">
                                    <span :class="enabled ? 'translate-x-5' : 'translate-x-0'" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                                </button>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Standard Tax Rate (%)</label>
                            <input type="number" step="0.01" name="tax_rate" value="{{ \App\Models\Setting::getValue('tax_rate', 0) }}" class="w-full bg-gray-50 border-0 px-4 py-4 text-xs font-bold text-black focus:ring-1 focus:ring-black">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEO Settings -->
        <div x-show="activeTab === 'seo'" x-cloak class="space-y-8">
            <!-- Existing SEO content -->
            <div class="bg-white border border-gray-100 p-8">
                <h3 class="text-[11px] font-black uppercase tracking-[0.3em] text-black mb-8 flex items-center gap-3">
                    <i class="fas fa-search text-gold"></i> Search Intelligence
                </h3>
                <div class="space-y-6 max-w-3xl">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Global Page Title Meta</label>
                        <input type="text" name="seo_title" value="{{ \App\Models\Setting::getValue('seo_title') }}" class="w-full bg-gray-50 border-0 px-4 py-4 text-xs font-bold text-black focus:ring-1 focus:ring-black">
                        <p class="text-[8px] font-bold text-gray-300 uppercase tracking-widest">Recommended: 50-60 characters</p>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Core Description Narrative</label>
                        <textarea name="seo_description" rows="4" class="w-full bg-gray-50 border-0 px-4 py-4 text-xs font-bold text-black focus:ring-1 focus:ring-black">{{ \App\Models\Setting::getValue('seo_description') }}</textarea>
                        <p class="text-[8px] font-bold text-gray-300 uppercase tracking-widest">Recommended: 150-160 characters</p>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Target Search Keywords (Comma Separated)</label>
                        <textarea name="seo_keywords" rows="2" class="w-full bg-gray-50 border-0 px-4 py-4 text-xs font-bold text-black focus:ring-1 focus:ring-black">{{ \App\Models\Setting::getValue('seo_keywords') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Settings -->
        <div x-show="activeTab === 'notifications'" x-cloak class="space-y-8">
            <div class="bg-white border border-gray-100 p-8">
                <h3 class="text-[11px] font-black uppercase tracking-[0.3em] text-black mb-8 flex items-center gap-3">
                    <i class="fas fa-bell text-gold"></i> Multi-Channel Command & Control
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                    <!-- Email Protocol -->
                    <div class="p-6 bg-gray-50 border border-gray-100 relative group">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fas fa-envelope text-blue-500 text-xl"></i>
                            <div x-data="{ enabled: {{ \App\Models\Setting::getValue('notify_email_enabled', 1) }} }">
                                <input type="hidden" name="notify_email_enabled" :value="enabled ? 1 : 0">
                                <button type="button" @click="enabled = !enabled" :class="enabled ? 'bg-black' : 'bg-gray-200'" class="relative inline-flex h-5 w-10 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200">
                                    <span :class="enabled ? 'translate-x-5' : 'translate-x-0'" class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow transition duration-200"></span>
                                </button>
                            </div>
                        </div>
                        <h4 class="text-[10px] font-black uppercase tracking-widest text-black">Email Gateway</h4>
                        <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mt-1">Order confirmations & tracking</p>
                    </div>

                    <!-- SMS Protocol -->
                    <div class="p-6 bg-gray-50 border border-gray-100 relative group">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fas fa-comment-alt text-green-500 text-xl"></i>
                            <div x-data="{ enabled: {{ \App\Models\Setting::getValue('notify_sms_enabled', 0) }} }">
                                <input type="hidden" name="notify_sms_enabled" :value="enabled ? 1 : 0">
                                <button type="button" @click="enabled = !enabled" :class="enabled ? 'bg-black' : 'bg-gray-200'" class="relative inline-flex h-5 w-10 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200">
                                    <span :class="enabled ? 'translate-x-5' : 'translate-x-0'" class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow transition duration-200"></span>
                                </button>
                            </div>
                        </div>
                        <h4 class="text-[10px] font-black uppercase tracking-widest text-black">SMS Gateway</h4>
                        <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mt-1">Mobile delivery alerts</p>
                    </div>

                    <!-- WhatsApp Protocol -->
                    <div class="p-6 bg-gray-50 border border-gray-100 relative group">
                        <div class="flex items-center justify-between mb-4">
                            <i class="fab fa-whatsapp text-pink-500 text-xl"></i>
                            <div x-data="{ enabled: {{ \App\Models\Setting::getValue('notify_whatsapp_enabled', 0) }} }">
                                <input type="hidden" name="notify_whatsapp_enabled" :value="enabled ? 1 : 0">
                                <button type="button" @click="enabled = !enabled" :class="enabled ? 'bg-gold' : 'bg-gray-200'" class="relative inline-flex h-5 w-10 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200">
                                    <span :class="enabled ? 'translate-x-5' : 'translate-x-0'" class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow transition duration-200"></span>
                                </button>
                            </div>
                        </div>
                        <h4 class="text-[10px] font-black uppercase tracking-widest text-black">WhatsApp API</h4>
                        <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mt-1">Dynamic customer chat nodes</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <div class="space-y-6">
                        <h4 class="text-[11px] font-black uppercase tracking-[0.2em] text-black">Administrative Alert Nodes</h4>
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Master Notification Email</label>
                                <input type="email" name="admin_notify_email" value="{{ \App\Models\Setting::getValue('admin_notify_email') }}" class="w-full bg-gray-50 border-0 px-4 py-4 text-xs font-bold text-black focus:ring-1 focus:ring-black">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Master Alert Phone (SMS/WA)</label>
                                <input type="text" name="admin_notify_phone" value="{{ \App\Models\Setting::getValue('admin_notify_phone') }}" class="w-full bg-gray-50 border-0 px-4 py-4 text-xs font-bold text-black focus:ring-1 focus:ring-black">
                            </div>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <h4 class="text-[11px] font-black uppercase tracking-[0.2em] text-black">Threshold Intelligence</h4>
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Low Stock Alert Trigger (Quantity)</label>
                                <input type="number" name="low_stock_threshold" value="{{ \App\Models\Setting::getValue('low_stock_threshold', 5) }}" class="w-full bg-gray-50 border-0 px-4 py-4 text-xs font-bold text-black focus:ring-1 focus:ring-black">
                            </div>
                        </div>
                        <div class="mt-8 p-6 border-l-2 border-gold bg-gold/5">
                            <p class="text-[10px] font-black uppercase tracking-widest text-gold mb-2">Operational Insight</p>
                            <p class="text-[10px] font-medium text-gray-500 leading-relaxed uppercase">The system will automatically deploy alerts to the admin nodes whenever inventory drops below this threshold.</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-12 pt-8 border-t border-gray-50 flex justify-between items-center">
                    <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest">Active nodes for Order, Stock, and Payment events</p>
                    <a href="{{ route('admin.notifications.index') }}" class="text-[9px] font-black text-black uppercase tracking-widest hover:text-gold transition-colors underline decoration-2 underline-offset-4">View Communication Ledger</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
