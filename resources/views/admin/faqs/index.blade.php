@extends('layouts.admin')

@section('title', 'FAQ Intelligence')

@section('content')
<div class="space-y-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-black text-black uppercase tracking-tighter">Support Knowledge Base</h2>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Manage public automated responses</p>
        </div>
        <a href="{{ route('admin.faqs.create') }}" class="bg-black text-white px-8 py-4 text-[10px] font-black uppercase tracking-[0.3em] hover:bg-gold transition-all shadow-xl active:scale-95">
            Add New Intel
        </a>
    </div>

    <div class="bg-white border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-[#5C5C5C]">Order</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-[#5C5C5C]">Inquiry Position</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-[#5C5C5C]">Status</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-[#5C5C5C]">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($faqs as $faq)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <span class="text-xs font-black text-gray-300">#{{ $faq->sort_order }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs font-black text-black uppercase tracking-tight mb-1">{{ $faq->question }}</p>
                            <p class="text-[10px] text-gray-400 font-medium line-clamp-1">{{ Str::limit($faq->answer, 100) }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($faq->is_active)
                                <span class="px-3 py-1 bg-green-50 text-green-600 text-[8px] font-black uppercase tracking-widest border border-green-100">ACTIVE</span>
                            @else
                                <span class="px-3 py-1 bg-red-50 text-red-600 text-[8px] font-black uppercase tracking-widest border border-red-100">SILENCED</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <a href="{{ route('admin.faqs.edit', $faq) }}" class="text-gray-300 hover:text-black transition-colors">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" onsubmit="return confirm('Erase this intel permanentally?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="text-gray-300 hover:text-red-500 transition-colors">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-20 text-center">
                            <div class="opacity-10 mb-4 text-4xl"><i class="fas fa-database"></i></div>
                            <p class="text-[10px] font-black text-gray-300 uppercase tracking-[0.4em]">Zero records detected</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
