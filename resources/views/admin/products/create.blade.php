@extends('layouts.admin')

@section('title', 'Add New Product')

@section('content')
<div class="mb-10">
    <a href="{{ route('admin.products.index') }}" class="text-[10px] font-bold text-gray-400 hover:text-black uppercase tracking-widest flex items-center gap-2 mb-4 transition-all">
        <i class="fas fa-arrow-left"></i> Back to Products
    </a>
    <h2 class="text-2xl font-black text-black uppercase tracking-tighter">Create Masterpiece</h2>
</div>

@if ($errors->any())
    <div class="mb-8 p-6 bg-red-50 border border-red-100 text-red-700 space-y-2">
        <p class="text-xs font-black uppercase tracking-widest">There were errors with your submission:</p>
        <ul class="list-disc list-inside text-xs font-bold uppercase tracking-tight">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Basic Information -->
            <div class="bg-white p-8 border border-gray-100 shadow-sm rounded-none space-y-6">
                <h3 class="text-xs font-bold uppercase tracking-widest text-black border-b border-gray-50 pb-4 mb-6">General Information</h3>
                
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Product Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                           class="w-full bg-gray-50 border border-gray-200 px-4 py-3 text-sm focus:border-gold focus:ring-0 placeholder:text-gray-300 font-bold tracking-tight">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4 md:col-span-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Select Category</label>
                        
                        <select name="category_id" required 
                                class="w-full bg-gray-50 border border-gray-200 px-4 py-3 text-sm focus:border-gold focus:ring-0 font-bold uppercase tracking-widest">
                            <option value="">Choose Category</option>
                            @foreach($allCategories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2 md:col-span-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">SKU (Optional)</label>
                        <input type="text" name="sku" value="{{ old('sku') }}" 
                               class="w-full bg-gray-50 border border-gray-200 px-4 py-3 text-sm focus:border-gold focus:ring-0 placeholder:text-gray-300 font-bold">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Full Description</label>
                    <textarea name="description" rows="8" class="w-full bg-gray-50 border border-gray-200 px-4 py-3 text-sm focus:border-gold focus:ring-0 font-light leading-relaxed">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Pricing & Inventory -->
            <div class="bg-white p-8 border border-gray-100 shadow-sm rounded-none space-y-6">
                <h3 class="text-xs font-bold uppercase tracking-widest text-black border-b border-gray-50 pb-4 mb-6">Inventory & Pricing</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Regular Price (Rs.)</label>
                        <input type="number" name="price" value="{{ old('price') }}" required 
                               class="w-full bg-gray-50 border border-gray-200 px-4 py-3 text-sm focus:border-gold focus:ring-0 font-bold">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Sale Price (Optional)</label>
                        <input type="number" name="sale_price" value="{{ old('sale_price') }}" 
                               class="w-full bg-gray-50 border border-gray-200 px-4 py-3 text-sm focus:border-gold focus:ring-0 font-bold text-red-600">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Stock Quantity</label>
                        <input type="number" name="stock_quantity" value="{{ old('stock_quantity', 1) }}" required 
                               class="w-full bg-gray-50 border border-gray-200 px-4 py-3 text-sm focus:border-gold focus:ring-0 font-bold">
                    </div>
                </div>
            </div>

            <!-- Attributes -->
            <div class="bg-white p-8 border border-gray-100 shadow-sm rounded-none space-y-6">
                <h3 class="text-xs font-bold uppercase tracking-widest text-black border-b border-gray-50 pb-4 mb-6">Cloth Attributes</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Sizes (Comma separated)</label>
                        <input type="text" name="sizes" placeholder="S, M, L, XL" 
                               class="w-full bg-gray-50 border border-gray-200 px-4 py-3 text-sm focus:border-gold focus:ring-0 font-bold">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Colors (Comma separated)</label>
                        <input type="text" name="colors" placeholder="Black, White, Cream" 
                               class="w-full bg-gray-50 border border-gray-200 px-4 py-3 text-sm focus:border-gold focus:ring-0 font-bold">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Material Type</label>
                        <input type="text" name="material" placeholder="Lawn, Silk, Cotton" 
                               class="w-full bg-gray-50 border border-gray-200 px-4 py-3 text-sm focus:border-gold focus:ring-0 font-bold uppercase tracking-widest">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Brand</label>
                        <input type="text" name="brand" value="S4 Luxury" 
                               class="w-full bg-gray-50 border border-gray-200 px-4 py-3 text-sm focus:border-gold focus:ring-0 font-bold uppercase tracking-widest">
                    </div>
                </div>
            </div>
            
            <!-- Gallery -->
            <div class="bg-white p-8 border border-gray-100 shadow-sm rounded-none space-y-6">
                <h3 class="text-xs font-bold uppercase tracking-widest text-black border-b border-gray-50 pb-4 mb-6">Product Gallery</h3>
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Upload Multiple Images</label>
                    <input type="file" name="images[]" multiple class="w-full bg-gray-50 border border-dashed border-gray-300 px-4 py-10 text-sm focus:ring-0">
                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-1">First image will be the primary thumbnail.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Duppata Detail</label>
                        <input type="file" name="duppata_image" class="w-full text-xs">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Shalwar Detail</label>
                        <input type="file" name="shalwar_image" class="w-full text-xs">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Bazoo Detail</label>
                        <input type="file" name="bazoo_image" class="w-full text-xs">
                    </div>
                </div>
            </div>

            <!-- Fixed Accordion Details -->
            <div class="bg-white p-8 border border-gray-100 shadow-sm rounded-none space-y-6">
                <h3 class="text-xs font-bold uppercase tracking-widest text-black border-b border-gray-50 pb-4 mb-6">Standard Details (Accordions)</h3>
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Product & Fabric Details (Description)</label>
                        <textarea name="fabric_details" rows="3" placeholder="Enter fabric composition, material quality, and other technical details..." 
                                  class="w-full bg-gray-50 border border-gray-200 px-4 py-3 text-sm focus:border-gold focus:ring-0 font-light"></textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Delivery & Returns (Description)</label>
                        <textarea name="return_policy" rows="3" placeholder="Enter shipping times, return conditions, and exchange policy..." 
                                  class="w-full bg-gray-50 border border-gray-200 px-4 py-3 text-sm focus:border-gold focus:ring-0 font-light"></textarea>
                    </div>
                </div>
            </div>

            <!-- Dynamic Accordion Details -->
            <div class="bg-white p-8 border border-gray-100 shadow-sm rounded-none space-y-6" x-data="{ details: [] }">
                <div class="flex items-center justify-between border-b border-gray-50 pb-4 mb-6">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-black">Interactive Details (Accordions)</h3>
                    <button type="button" @click="details.push({title: '', content: ''})" class="text-[10px] font-black text-gold uppercase tracking-widest hover:text-black transition-all">+ Add New Accordion</button>
                </div>
                
                <template x-for="(detail, index) in details" :key="index">
                    <div class="p-6 bg-gray-50 border border-gray-100 space-y-4 relative group">
                        <button type="button" @click="details.splice(index, 1)" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                        
                        <div class="space-y-2">
                            <label class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.2em]">Accordion Title</label>
                            <input type="text" :name="'detail_titles['+index+']'" x-model="detail.title" placeholder="e.g. Care Instructions, Shipping Info" 
                                   class="w-full bg-white border border-gray-200 px-4 py-2 text-xs font-bold focus:border-gold focus:ring-0">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.2em]">Accordion Content</label>
                            <textarea :name="'detail_contents['+index+']'" x-model="detail.content" rows="3" placeholder="Enter the detailed information here..." 
                                      class="w-full bg-white border border-gray-200 px-4 py-2 text-xs font-light focus:border-gold focus:ring-0"></textarea>
                        </div>
                    </div>
                </template>

                <div x-show="details.length === 0" class="py-12 border-2 border-dashed border-gray-100 flex flex-col items-center justify-center text-gray-300">
                    <i class="fas fa-list-ul text-2xl mb-2"></i>
                    <p class="text-[9px] font-bold uppercase tracking-widest">No interactive details added</p>
                    <button type="button" @click="details.push({title: '', content: ''})" class="mt-4 text-[9px] font-black text-gold border-b border-gold pb-0.5 uppercase tracking-[0.2em] hover:text-black hover:border-black transition-all">Create first accordion</button>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions (Fixed at the top when scrolling) -->
        <div class="lg:col-span-1">
            <div class="sticky top-32 space-y-8">
                <!-- Save Controls -->
                <div class="bg-white p-8 border border-gray-100 shadow-sm rounded-none space-y-6">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-black border-b border-gray-50 pb-4 mb-6">Publish & Sections</h3>
                    
                    <div class="space-y-4">
                            <input type="checkbox" name="is_active" checked class="w-5 h-5 border-gray-300 text-gold focus:ring-gold rounded-none">
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-900">Publish to Store</span>
                        </label>

                        <div x-data="{ showPopupDates: false }">
                            <label class="flex items-center gap-3 cursor-pointer group bg-gray-50 p-4 border border-gray-100 hover:border-gold transition-all">
                                <input type="checkbox" name="is_popup" x-model="showPopupDates" class="w-5 h-5 border-gray-300 text-purple-600 focus:ring-purple-600 rounded-none">
                                <span class="text-xs font-bold uppercase tracking-widest text-gray-900">Show in Homepage Popup</span>
                            </label>

                            <div x-show="showPopupDates" x-transition class="mt-4 p-4 bg-purple-50 border border-purple-100 space-y-3">
                                <div class="space-y-1">
                                    <label class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">Offer Start Date</label>
                                    <input type="datetime-local" name="popup_start_date" class="w-full bg-white border border-gray-200 px-3 py-2 text-xs font-bold focus:border-purple-600 focus:ring-0">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">Offer End Date</label>
                                    <input type="datetime-local" name="popup_end_date" class="w-full bg-white border border-gray-200 px-3 py-2 text-xs font-bold focus:border-purple-600 focus:ring-0">
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-3 pt-2">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Homepage Sections</h4>
                            
                            @php
                                $allSections = \App\Models\Section::where('is_active', true)->orderBy('sort_order')->get();
                            @endphp
                            
                            @forelse($allSections as $sec)
                                <label class="flex items-center gap-3 cursor-pointer group bg-white p-3 border border-gray-200 hover:border-black transition-all">
                                    <input type="checkbox" name="sections[]" value="{{ $sec->id }}" class="w-5 h-5 border-gray-300 text-black focus:ring-black rounded-none">
                                    <div>
                                        <span class="block text-xs font-bold uppercase tracking-widest text-black">{{ $sec->name }}</span>
                                        <span class="block text-[9px] text-gray-400 uppercase tracking-wider">
                                            {{ $sec->scroll_type === 'horizontal' ? 'Horizontal Slider' : 'Vertical Grid' }}
                                        </span>
                                    </div>
                                </label>
                            @empty
                                <div class="bg-yellow-50 border border-yellow-200 p-4 text-center">
                                    <p class="text-[9px] font-bold text-yellow-800 uppercase tracking-wider">
                                        No sections created yet. <a href="{{ route('admin.sections.create') }}" class="underline">Create one</a>
                                    </p>
                                </div>
                            @endforelse
                        </div>
                        
                        <div class="pt-4 border-t border-gray-50 mt-4">
                            <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Product Badges</h4>
                            
                            <label class="flex items-center gap-3 cursor-pointer group mb-2">
                                <input type="checkbox" name="is_coming_soon" class="w-5 h-5 border-gray-300 text-gold focus:ring-gold rounded-none">
                                <span class="text-xs font-bold uppercase tracking-widest text-gray-600 group-hover:text-black transition-colors">"Coming Soon" Badge</span>
                            </label>
                            
                            <div class="space-y-2 mt-3">
                                <p class="text-[9px] font-black text-black uppercase tracking-widest">Discount Label</p>
                                <div class="grid grid-cols-2 gap-2">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="discount_badge" value="" checked class="text-gold focus:ring-gold">
                                        <span class="text-[10px] font-bold text-gray-500 uppercase">None</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="discount_badge" value="SALE" class="text-gold focus:ring-gold">
                                        <span class="text-[10px] font-bold text-gray-500 uppercase">SALE</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="discount_badge" value="10%" class="text-gold focus:ring-gold">
                                        <span class="text-[10px] font-bold text-gray-500 uppercase">10% OFF</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="discount_badge" value="20%" class="text-gold focus:ring-gold">
                                        <span class="text-[10px] font-bold text-gray-500 uppercase">20% OFF</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="discount_badge" value="30%" class="text-gold focus:ring-gold">
                                        <span class="text-[10px] font-bold text-gray-500 uppercase">30% OFF</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="discount_badge" value="40%" class="text-gold focus:ring-gold">
                                        <span class="text-[10px] font-bold text-gray-500 uppercase">40% OFF</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                    <div class="pt-6 border-t border-gray-50 space-y-3">
                        <button type="submit" class="w-full py-4 bg-black text-white text-[10px] font-bold uppercase tracking-[0.2em] shadow-xl hover:bg-gold transition-all duration-300">
                            Create Product
                        </button>
                    </div>
                </div>

                <!-- SEO Settings -->
                <div class="bg-white p-8 border border-gray-100 shadow-sm rounded-none space-y-6">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-black border-b border-gray-50 pb-4 mb-6">Search Engine (SEO)</h3>
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Meta Title</label>
                            <input type="text" name="meta_title" class="w-full bg-gray-50 border border-gray-200 px-4 py-2 text-xs font-bold">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Meta Description</label>
                            <textarea name="meta_description" rows="3" class="w-full bg-gray-50 border border-gray-200 px-4 py-2 text-xs font-light"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
