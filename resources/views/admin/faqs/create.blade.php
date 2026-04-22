@extends('layouts.admin')

@section('title', 'Draft New Intel')

@section('content')
<div class="max-w-4xl">
    <div class="mb-8">
        <h2 class="text-2xl font-black text-black uppercase tracking-tighter">Deploy New FAQ</h2>
        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Configure automated response parameters</p>
    </div>

    <form action="{{ route('admin.faqs.store') }}" method="POST" class="bg-white border border-gray-100 p-10 shadow-sm space-y-8">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="col-span-2">
                <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-[#5C5C5C] mb-3">Inquiry Title (Question)</label>
                <input type="text" name="question" required 
                       class="w-full bg-gray-50 border-0 p-4 text-xs font-bold uppercase tracking-widest focus:ring-2 focus:ring-black transition-all">
            </div>

            <div class="col-span-2">
                <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-[#5C5C5C] mb-3">Response Payload (Answer)</label>
                <textarea name="answer" rows="6" required 
                          class="w-full bg-gray-50 border-0 p-4 text-xs font-medium focus:ring-2 focus:ring-black transition-all"></textarea>
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-[#5C5C5C] mb-3">Priority Ranking (Sort Order)</label>
                <input type="number" name="sort_order" value="0" 
                       class="w-full bg-gray-50 border-0 p-4 text-xs font-black focus:ring-2 focus:ring-black transition-all">
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-[#5C5C5C] mb-3">Operational Status</label>
                <select name="is_active" class="w-full bg-gray-50 border-0 p-4 text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-black transition-all">
                    <option value="1">ACTIVE / BROADCAST</option>
                    <option value="0">INACTIVE / ENCRYPTED</option>
                </select>
            </div>
        </div>

        <div class="pt-8 border-t border-gray-50 flex gap-4">
            <button type="submit" class="bg-black text-white px-12 py-5 text-[11px] font-black uppercase tracking-[0.4em] hover:bg-gold transition-all shadow-2xl active:scale-95">
                Broadcast Intel
            </button>
            <a href="{{ route('admin.faqs.index') }}" class="px-12 py-5 border border-black text-black text-[11px] font-black uppercase tracking-[0.4em] hover:bg-black hover:text-white transition-all">
                Abort Mission
            </a>
        </div>
    </form>
</div>
@endsection
