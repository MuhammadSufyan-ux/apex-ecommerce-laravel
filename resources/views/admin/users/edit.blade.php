@extends('layouts.admin')

@section('title', 'Modify Identity: ' . $user->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-10 flex items-center justify-between">
        <div class="flex items-center gap-6">
            <a href="{{ route('admin.users.show', $user) }}" class="w-10 h-10 border border-gray-100 flex items-center justify-center text-gray-400 hover:text-black hover:border-black transition-all">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-black text-black uppercase tracking-tighter">Modify Identity</h2>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Updating core credentials for #{{ $user->id }}</p>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="mb-8 p-6 bg-red-50 border-l-4 border-red-500 text-red-700">
            <ul class="list-disc list-inside text-xs font-bold uppercase tracking-widest space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Primary Credentials -->
        <div class="bg-white border border-gray-100 p-8 shadow-sm">
            <h3 class="text-[11px] font-black uppercase tracking-[0.3em] text-black mb-8 pb-4 border-b border-gray-50 flex items-center gap-3">
                <i class="fas fa-user-circle text-gold"></i> Primary Identity
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-1.5">
                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Legal Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full bg-gray-50 border-0 px-4 py-3.5 text-xs font-bold text-black focus:ring-1 focus:ring-black">
                </div>

                <div class="space-y-1.5">
                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Electronic Mail Address</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full bg-gray-50 border-0 px-4 py-3.5 text-xs font-bold text-black focus:ring-1 focus:ring-black">
                </div>

                <div class="space-y-1.5">
                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Mobile Contact Protocol</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="w-full bg-gray-50 border-0 px-4 py-3.5 text-xs font-bold text-black focus:ring-1 focus:ring-black" placeholder="e.g. +92 300 1234567">
                </div>

                <div class="space-y-1.5">
                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Account Status Matrix</label>
                    <select name="status" required class="w-full bg-gray-50 border-0 px-4 py-3.5 text-xs font-bold text-black uppercase tracking-widest focus:ring-1 focus:ring-black">
                        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Verified Active</option>
                        <option value="blocked" {{ old('status', $user->status) == 'blocked' ? 'selected' : '' }}>Protocol Blocked</option>
                        <option value="vip" {{ old('status', $user->status) == 'vip' ? 'selected' : '' }}>VIP Elite Tier</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Location Intelligence -->
        <div class="bg-white border border-gray-100 p-8 shadow-sm">
            <h3 class="text-[11px] font-black uppercase tracking-[0.3em] text-black mb-8 pb-4 border-b border-gray-50 flex items-center gap-3">
                <i class="fas fa-map-marker-alt text-red-500"></i> Location Intelligence
            </h3>
            
            <div class="space-y-8">
                <div class="space-y-1.5">
                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Physical Residence Address</label>
                    <textarea name="address" rows="3" class="w-full bg-gray-50 border-0 px-4 py-3.5 text-xs font-bold text-black focus:ring-1 focus:ring-black">{{ old('address', $user->address) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">City Focus</label>
                        <input type="text" name="city" value="{{ old('city', $user->city) }}"
                            class="w-full bg-gray-50 border-0 px-4 py-3.5 text-xs font-bold text-black focus:ring-1 focus:ring-black">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block">Sovereign State/Country</label>
                        <input type="text" name="country" value="{{ old('country', $user->country ?: 'Pakistan') }}"
                            class="w-full bg-gray-50 border-0 px-4 py-3.5 text-xs font-bold text-black focus:ring-1 focus:ring-black">
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Observations -->
        <div class="bg-black text-white p-8">
            <h3 class="text-[11px] font-black uppercase tracking-[0.3em] text-gold mb-8 pb-4 border-b border-white/10 flex items-center gap-3">
                <i class="fas fa-user-shield"></i> Tactical Observations (Internal Only)
            </h3>
            
            <div class="space-y-1.5">
                <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest block">Entity Behavioral Notes</label>
                <textarea name="admin_notes" rows="5" class="w-full bg-white/5 border border-white/10 p-4 text-xs font-bold text-white placeholder:text-gray-700 focus:ring-1 focus:ring-gold focus:border-gold">{{ old('admin_notes', $user->admin_notes) }}</textarea>
                <p class="text-[8px] text-gray-600 font-bold uppercase tracking-widest mt-2">These notes are strictly confidential and visible only to system administrators.</p>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="flex-1 py-4 bg-black text-white text-[10px] font-black uppercase tracking-[0.2em] shadow-xl hover:bg-gold transition-all duration-300">
                Commit Identity Changes
            </button>
            <a href="{{ route('admin.users.show', $user) }}" class="px-10 py-4 border border-gray-100 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 hover:text-black hover:border-black transition-all">
                Cancel Update
            </a>
        </div>
    </form>
</div>
@endsection
