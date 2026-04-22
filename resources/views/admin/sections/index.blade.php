@extends('layouts.admin')

@section('title', 'Homepage Sections')

@section('content')
<div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div>
        <h2 class="text-2xl font-black text-black uppercase tracking-tighter">Homepage Sections</h2>
        <p class="text-xs text-gray-400 uppercase tracking-widest mt-1">Manage dynamic sections on your homepage</p>
    </div>

    <div class="flex flex-col md:flex-row items-stretch md:items-center gap-4 w-full md:w-auto">
        <!-- Local Search -->
        <form action="{{ route('admin.sections.index') }}" method="GET" class="relative group min-w-[300px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="QUERY SECTIONS..." 
                class="w-full bg-white border border-gray-100 text-[10px] font-black uppercase tracking-[0.2em] px-4 py-3 placeholder:text-gray-300 focus:ring-1 focus:ring-gold focus:border-gold transition-all">
            <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-gold transition-colors">
                <i class="fas fa-search"></i>
            </button>
        </form>

        <a href="{{ route('admin.sections.create') }}" class="px-6 py-3 bg-black text-white text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-gold transition-all flex items-center gap-2 whitespace-nowrap">
            <i class="fas fa-plus"></i> New Section
        </a>
    </div>
</div>

@if(request('search'))
    <div class="mb-6 flex items-center gap-3">
        <span class="text-[9px] font-black text-gold uppercase tracking-widest bg-gold/5 px-3 py-1.5 border border-gold/20">Filtering: "{{ request('search') }}"</span>
        <a href="{{ route('admin.sections.index') }}" class="text-[9px] font-black text-red-500 uppercase tracking-widest hover:underline">Clear Filter</a>
    </div>
@endif

@if(session('success'))
    <div class="mb-8 p-6 bg-green-50 border border-green-100 text-green-700">
        <p class="text-xs font-black uppercase tracking-widest">{{ session('success') }}</p>
    </div>
@endif

<div class="bg-white border border-gray-100 shadow-sm overflow-x-auto">
    <table class="w-full min-w-[800px]">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Name</th>
                <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Display Title</th>
                <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Scroll Type</th>
                <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Products</th>
                <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Order</th>
                <th class="px-6 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($sections as $section)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <span class="text-sm font-bold text-black uppercase tracking-wide">{{ $section->name }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-600">{{ $section->title ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($section->scroll_type === 'horizontal')
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-[9px] font-bold uppercase tracking-widest">
                                <i class="fas fa-arrows-alt-h mr-1"></i> Horizontal
                            </span>
                        @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-800 text-[9px] font-bold uppercase tracking-widest">
                                <i class="fas fa-th mr-1"></i> Vertical Grid
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm font-bold text-gray-900">{{ $section->products->count() }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($section->is_active)
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-[9px] font-bold uppercase tracking-widest">Active</span>
                        @else
                            <span class="px-3 py-1 bg-red-100 text-red-800 text-[9px] font-bold uppercase tracking-widest">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-600">{{ $section->sort_order }}</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.sections.edit', $section) }}" class="px-4 py-2 bg-gray-100 text-black text-[9px] font-bold uppercase tracking-widest hover:bg-black hover:text-white transition-all">
                                Edit
                            </a>
                            <form action="{{ route('admin.sections.destroy', $section) }}" method="POST" onsubmit="return confirm('Delete this section?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-100 text-red-800 text-[9px] font-bold uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center text-gray-300">
                            <i class="fas fa-layer-group text-4xl mb-4"></i>
                            <p class="text-xs font-bold uppercase tracking-widest">No sections created yet</p>
                            <a href="{{ route('admin.sections.create') }}" class="mt-4 text-[10px] font-black text-gold border-b border-gold pb-0.5 uppercase tracking-[0.2em] hover:text-black hover:border-black transition-all">
                                Create your first section
                            </a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
