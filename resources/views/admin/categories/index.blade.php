@extends('layouts.admin')

@section('title', 'Collections & Navigation')

@section('content')
<div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
    <div class="space-y-2">
        <h2 class="text-4xl font-black text-black uppercase tracking-tighter leading-none">Catalog Structure</h2>
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.3em]">Manage categories, menus, and hierarchy</p>
    </div>
    
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.categories.create') }}" class="px-8 py-4 bg-black text-white text-[10px] font-bold uppercase tracking-[0.2em] shadow-2xl hover:bg-gold transition-all duration-500 flex items-center gap-3 group">
            <i class="fas fa-plus group-hover:rotate-90 transition-transform"></i>
            New Collection
        </a>
    </div>
</div>

<div class="grid grid-cols-1 gap-8">
    <!-- Stats / Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
        <div class="bg-white p-6 border border-gray-100 shadow-sm">
            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Categories</p>
            <p class="text-2xl font-black text-black">{{ $categories->count() }}</p>
        </div>
        <div class="bg-white p-6 border border-gray-100 shadow-sm border-l-4 border-l-gold">
            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Main Menu Items</p>
            <p class="text-2xl font-black text-black">{{ $categories->where('show_in_menu', true)->count() }}</p>
        </div>
        <div class="bg-white p-6 border border-gray-100 shadow-sm">
            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Featured Sections</p>
            <p class="text-2xl font-black text-black">{{ $categories->where('show_in_featured', true)->count() }}</p>
        </div>
        <div class="bg-white p-6 border border-gray-100 shadow-sm">
            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Active Status</p>
            <p class="text-2xl font-black text-green-600">{{ $categories->where('is_active', true)->count() }}</p>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="bg-white border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-8 py-6 text-[10px] font-black text-black uppercase tracking-widest">Collection Name</th>
                        <th class="px-8 py-6 text-[10px] font-black text-black uppercase tracking-widest text-center">Visibility & Placement</th>
                        <th class="px-8 py-6 text-[10px] font-black text-black uppercase tracking-widest text-center">Hierarchy</th>
                        <th class="px-8 py-6 text-[10px] font-black text-black uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($categories->whereNull('parent_id') as $parent)
                        <!-- Parent Row -->
                        <tr class="hover:bg-gray-50/50 transition-all group border-l-4 border-l-transparent hover:border-l-gold">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-gray-50 flex items-center justify-center border border-gray-100 group-hover:border-gold transition-colors">
                                        <i class="fas fa-folder text-gray-400 group-hover:text-gold"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-black uppercase tracking-tight">{{ $parent->name }}</p>
                                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">/{{ $parent->slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center justify-center gap-3">
                                    <div class="flex flex-col gap-1 items-center">
                                        <span class="flex items-center gap-1.5 px-3 py-1 {{ $parent->show_in_menu ? 'bg-black text-white' : 'bg-gray-50 text-gray-300' }} text-[8px] font-black uppercase tracking-[0.15em] border border-transparent shadow-sm">
                                            <i class="fas fa-bars text-[7px] {{ $parent->show_in_menu ? 'text-gold' : '' }}"></i> Nav Menu
                                        </span>
                                        <span class="flex items-center gap-1.5 px-3 py-1 {{ $parent->show_in_featured ? 'bg-gold/10 text-gold' : 'bg-gray-50 text-gray-300' }} text-[8px] font-black uppercase tracking-[0.15em] border {{ $parent->show_in_featured ? 'border-gold/20' : 'border-transparent' }}">
                                            <i class="fas fa-star text-[7px]"></i> Shop by Cat
                                        </span>
                                    </div>
                                    <div class="h-8 w-[1px] bg-gray-100 mx-2"></div>
                                    <span class="px-3 py-1 {{ $parent->is_active ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }} text-[8px] font-black uppercase tracking-[0.2em] border {{ $parent->is_active ? 'border-green-100' : 'border-red-100' }}">
                                        {{ $parent->is_active ? 'Online' : 'Hidden' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50 px-3 py-1">Root Collection</span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.categories.edit', $parent->id) }}" class="w-9 h-9 flex items-center justify-center bg-gray-50 text-gray-400 hover:bg-black hover:text-white transition-all">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $parent->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this collection and all its subcategories?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="w-9 h-9 flex items-center justify-center bg-gray-50 text-gray-400 hover:bg-red-500 hover:text-white transition-all">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Children Rows -->
                        @foreach($parent->children as $child)
                        <tr class="hover:bg-gray-50/30 transition-all group bg-gray-50/5">
                            <td class="px-8 py-4">
                                <div class="flex items-center gap-4 pl-8">
                                    <div class="w-8 h-8 flex items-center justify-center text-gray-300">
                                        <i class="fas fa-level-up-alt rotate-90"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-700 uppercase tracking-tight">{{ $child->name }}</p>
                                        <p class="text-[8px] text-gray-400 font-bold uppercase tracking-widest italic font-serif">Secondary Header</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-4">
                                <div class="flex items-center justify-center gap-3 opacity-60 group-hover:opacity-100 transition-opacity">
                                    <div class="flex items-center gap-4">
                                        <span class="text-[8px] font-black uppercase tracking-widest {{ $child->show_in_menu ? 'text-black' : 'text-gray-300' }}">
                                            <i class="fas fa-circle text-[5px] mr-1 {{ $child->show_in_menu ? 'text-green-500' : '' }}"></i> Menu
                                        </span>
                                        <span class="text-[8px] font-black uppercase tracking-widest {{ $child->show_in_featured ? 'text-gold' : 'text-gray-300' }}">
                                            <i class="fas fa-circle text-[5px] mr-1 {{ $child->show_in_featured ? 'text-gold' : '' }}"></i> Shop
                                        </span>
                                    </div>
                                    <div class="h-4 w-[1px] bg-gray-200"></div>
                                    <span class="text-[8px] font-black uppercase tracking-widest {{ $child->is_active ? 'text-green-600' : 'text-red-400' }}">
                                        {{ $child->is_active ? 'Online' : 'Offline' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-4 text-center">
                                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest px-3 py-1 border border-gray-100">L2: {{ $parent->name }}</span>
                            </td>
                            <td class="px-8 py-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.categories.edit', $child->id) }}" class="w-8 h-8 flex items-center justify-center bg-transparent text-gray-300 hover:text-black transition-all">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $child->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="w-8 h-8 flex items-center justify-center bg-transparent text-gray-300 hover:text-red-500 transition-all">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                            <!-- Level 3 Grandchildren Rows -->
                            @foreach($child->children as $grandChild)
                            <tr class="hover:bg-gray-100/50 transition-all group bg-white">
                                <td class="px-8 py-3">
                                    <div class="flex items-center gap-4 pl-20">
                                        <div class="w-6 h-1 bg-gold/30 rounded-full"></div>
                                        <div>
                                            <p class="text-[11px] font-medium text-gray-500 uppercase tracking-widest">{{ $grandChild->name }}</p>
                                            <p class="text-[7px] text-gray-300 font-bold uppercase tracking-wider">Product Filter Link</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-3 text-center">
                                    <span class="px-2 py-0.5 {{ $grandChild->is_active ? 'text-green-400' : 'text-red-300' }} text-[7px] font-black uppercase tracking-widest border border-gray-50">
                                        {{ $grandChild->is_active ? 'Active' : 'Hidden' }}
                                    </span>
                                </td>
                                <td class="px-8 py-3 text-center">
                                    <span class="text-[8px] font-bold text-gray-300 uppercase tracking-[0.2em]">Group: {{ $child->name }}</span>
                                </td>
                                <td class="px-8 py-3">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.categories.edit', $grandChild->id) }}" class="text-gray-200 hover:text-black transition-all">
                                            <i class="fas fa-edit text-[10px]"></i>
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $grandChild->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-gray-200 hover:text-red-400 transition-all">
                                                <i class="fas fa-trash-alt text-[10px]"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-32 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <i class="fas fa-folder-open text-5xl text-gray-100"></i>
                                    <p class="text-xs font-black text-gray-300 uppercase tracking-[0.3em]">No collections discovered yet</p>
                                    <a href="{{ route('admin.categories.create') }}" class="mt-2 text-[10px] font-bold text-gold border-b border-gold uppercase tracking-widest">Create first category</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

