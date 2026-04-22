@extends('layouts.admin')

@section('title', 'Create Section')

@section('content')
<div class="mb-10">
    <a href="{{ route('admin.sections.index') }}" class="text-[10px] font-bold text-gray-400 hover:text-black uppercase tracking-widest flex items-center gap-2 mb-4 transition-all">
        <i class="fas fa-arrow-left"></i> Back to Sections
    </a>
    <h2 class="text-2xl font-black text-black uppercase tracking-tighter">Create New Section</h2>
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

<form action="{{ route('admin.sections.store') }}" method="POST">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white p-8 border border-gray-100 shadow-sm rounded-none space-y-6">
                <h3 class="text-xs font-bold uppercase tracking-widest text-black border-b border-gray-50 pb-4 mb-6">Section Details</h3>
                
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Section Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                           placeholder="e.g., Featured Products, Winter Collection"
                           class="w-full bg-gray-50 border border-gray-200 px-4 py-3 text-sm focus:border-gold focus:ring-0 placeholder:text-gray-300 font-bold tracking-tight">
                    <p class="text-[9px] text-gray-400 uppercase tracking-wider">Used for admin identification</p>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Display Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" 
                           placeholder="e.g., Shop Our Featured Collection"
                           class="w-full bg-gray-50 border border-gray-200 px-4 py-3 text-sm focus:border-gold focus:ring-0 placeholder:text-gray-300 font-bold tracking-tight">
                    <p class="text-[9px] text-gray-400 uppercase tracking-wider">Shown on homepage (leave empty to use section name)</p>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" 
                           class="w-full bg-gray-50 border border-gray-200 px-4 py-3 text-sm focus:border-gold focus:ring-0 font-bold">
                    <p class="text-[9px] text-gray-400 uppercase tracking-wider">Lower numbers appear first</p>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="sticky top-32 space-y-8">
                <div class="bg-white p-8 border border-gray-100 shadow-sm rounded-none space-y-6">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-black border-b border-gray-50 pb-4 mb-6">Display Settings</h3>
                    
                    <div class="space-y-4">
                        <label class="flex items-center gap-3 cursor-pointer group bg-gray-50 p-4 border border-gray-100 hover:border-gold transition-all">
                            <input type="checkbox" name="is_active" checked class="w-5 h-5 border-gray-300 text-gold focus:ring-gold rounded-none">
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-900">Active on Homepage</span>
                        </label>

                        <div class="space-y-3 pt-4 border-t border-gray-50">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Scroll Type *</h4>
                            
                            <label class="flex items-start gap-3 cursor-pointer group bg-white p-4 border-2 border-gray-200 hover:border-black transition-all">
                                <input type="radio" name="scroll_type" value="vertical" checked class="mt-1 w-5 h-5 border-gray-300 text-black focus:ring-black">
                                <div>
                                    <span class="block text-xs font-bold uppercase tracking-widest text-black">
                                        <i class="fas fa-th mr-2"></i>Vertical Grid
                                    </span>
                                    <span class="block text-[9px] text-gray-400 uppercase tracking-wider mt-1">Standard product grid layout</span>
                                </div>
                            </label>
                            
                            <label class="flex items-start gap-3 cursor-pointer group bg-white p-4 border-2 border-gray-200 hover:border-black transition-all">
                                <input type="radio" name="scroll_type" value="horizontal" class="mt-1 w-5 h-5 border-gray-300 text-black focus:ring-black">
                                <div>
                                    <span class="block text-xs font-bold uppercase tracking-widest text-black">
                                        <i class="fas fa-arrows-alt-h mr-2"></i>Horizontal Slider
                                    </span>
                                    <span class="block text-[9px] text-gray-400 uppercase tracking-wider mt-1">Swipeable carousel layout</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-50 space-y-3">
                        <button type="submit" class="w-full py-4 bg-black text-white text-[10px] font-bold uppercase tracking-[0.2em] shadow-xl hover:bg-gold transition-all duration-300">
                            Create Section
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
