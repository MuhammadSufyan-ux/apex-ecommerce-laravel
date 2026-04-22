@extends('layouts.admin')

@section('title', 'Create Category')

@section('content')
<div class="mb-10">
    <a href="{{ route('admin.categories.index') }}" class="text-[10px] font-bold text-gray-400 hover:text-black uppercase tracking-widest flex items-center gap-2 mb-4 transition-all">
        <i class="fas fa-arrow-left"></i> Back to Categories
    </a>
    <h2 class="text-2xl font-black text-black uppercase tracking-tighter">New Collection</h2>
</div>

<form action="{{ route('admin.categories.store') }}" method="POST" class="max-w-4xl">
    @csrf
    <div class="bg-white p-10 border border-gray-100 shadow-sm space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Category Name</label>
                <input type="text" name="name" required class="w-full bg-gray-50 border border-gray-200 px-4 py-3 text-sm font-bold tracking-tight focus:border-gold focus:ring-0">
            </div>
            
            <div class="space-y-2">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Parent Category (For Dropdowns)</label>
                <select name="parent_id" class="w-full bg-gray-50 border border-gray-200 px-4 py-3 text-sm font-bold uppercase tracking-widest focus:border-gold focus:ring-0">
                    <option value="">None (Top Level)</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                    @endforeach
                </select>
                <p class="text-[9px] text-gray-400 font-bold uppercase mt-2 italic">Select a parent to make this a submenu item</p>
            </div>
        </div>

        <div class="space-y-4 pt-6 border-t border-gray-50">
            <h3 class="text-xs font-bold uppercase tracking-widest text-black mb-4">Display Placement</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="show_in_menu" class="w-5 h-5 text-gold border-gray-300 focus:ring-gold">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-600 group-hover:text-black">Show in Main Menu</span>
                </label>
                
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="show_in_featured" class="w-5 h-5 text-gold border-gray-300 focus:ring-gold">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-600 group-hover:text-black">Shop by Category</span>
                </label>

                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="is_active" checked class="w-5 h-5 text-gold border-gray-300 focus:ring-gold">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-600 group-hover:text-black">Active Status</span>
                </label>
            </div>
        </div>

        <div class="pt-10 flex gap-4">
            <button type="submit" class="px-10 py-4 bg-black text-white text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-gold transition-all duration-300 shadow-xl">
                Create Category
            </button>
            <a href="{{ route('admin.categories.index') }}" class="px-10 py-4 border border-gray-200 text-gray-400 text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-gray-50 transition-all">
                Cancel
            </a>
        </div>
    </div>
</form>
@endsection
