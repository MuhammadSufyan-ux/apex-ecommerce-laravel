@extends('layouts.admin')

@section('title', 'Recalibrate Intel')

@section('content')
<div class="max-w-4xl">
    <div class="mb-8">
        <h2 class="text-2xl font-black text-black uppercase tracking-tighter">Edit FAQ Parameters</h2>
        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Modify existing intelligence response</p>
    </div>

    <form action="{{ route('admin.faqs.update', $faq) }}" method="POST" class="bg-white border border-gray-100 p-10 shadow-sm space-y-8">
        @csrf @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="col-span-2">
                <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-[#5C5C5C] mb-3">Inquiry Title (Question)</label>
                <input type="text" name="question" value="{{ $faq->question }}" required 
                       class="w-full bg-gray-50 border-0 p-4 text-xs font-bold uppercase tracking-widest focus:ring-2 focus:ring-black transition-all">
            </div>

            <div class="col-span-2">
                <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-[#5C5C5C] mb-3">Response Payload (Answer)</label>
                <textarea name="answer" rows="6" required 
                          class="w-full bg-gray-50 border-0 p-4 text-xs font-medium focus:ring-2 focus:ring-black transition-all">{{ $faq->answer }}</textarea>
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-[#5C5C5C] mb-3">Priority Ranking (Sort Order)</label>
                <input type="number" name="sort_order" value="{{ $faq->sort_order }}" 
                       class="w-full bg-gray-50 border-0 p-4 text-xs font-black focus:ring-2 focus:ring-black transition-all">
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-[#5C5C5C] mb-3">Operational Status</label>
                <select name="is_active" class="w-full bg-gray-50 border-0 p-4 text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-black transition-all">
                    <option value="1" {{ $faq->is_active ? 'selected' : '' }}>ACTIVE / BROADCAST</option>
                    <option value="0" {{ !$faq->is_active ? 'selected' : '' }}>INACTIVE / ENCRYPTED</option>
                </select>
            </div>
        </div>

        <div class="pt-8 border-t border-gray-50 flex gap-4">
            <button type="submit" class="bg-black text-white px-12 py-5 text-[11px] font-black uppercase tracking-[0.4em] hover:bg-gold transition-all shadow-2xl active:scale-95">
                Update Intel
            </button>
            <a href="{{ route('admin.faqs.index') }}" class="px-12 py-5 border border-black text-black text-[11px] font-black uppercase tracking-[0.4em] hover:bg-black hover:text-white transition-all">
                Abort Mission
            </a>
        </div>
    </form>
</div>
@endsection
