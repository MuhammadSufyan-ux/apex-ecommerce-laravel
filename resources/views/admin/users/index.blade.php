@extends('layouts.admin')

@section('title', 'Customer Management')

@section('content')
<div x-data="userManager()" class="relative">
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-black uppercase tracking-tighter">Verified Customers</h1>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.3em] mt-2">Manage and view your elite clientele</p>
        </div>
    </div>

    <!-- Contextual Search & Advanced Filters -->
    <div class="bg-white border border-gray-100 shadow-sm p-6 mb-4">
        <form action="{{ route('admin.users.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div class="md:col-span-2">
                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Search Identifier</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="NAME, EMAIL, PHONE..." 
                        class="w-full bg-gray-50 border-0 text-[11px] font-bold text-black px-4 py-2.5 placeholder:text-gray-300 focus:ring-1 focus:ring-black">
                    <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <div>
                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Status Protocol</label>
                <select name="status" onchange="this.form.submit()" class="w-full bg-gray-50 border-0 text-[11px] font-bold text-black uppercase tracking-widest px-4 py-2.5 focus:ring-1 focus:ring-black">
                    <option value="all">All Statuses</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                    <option value="vip" {{ request('status') == 'vip' ? 'selected' : '' }}>VIP Elite</option>
                </select>
            </div>

            <div>
                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Sorting Logic</label>
                <select name="sort" onchange="this.form.submit()" class="w-full bg-gray-50 border-0 text-[11px] font-bold text-black uppercase tracking-widest px-4 py-2.5 focus:ring-1 focus:ring-black">
                    <option value="latest">Newest First</option>
                    <option value="spend_high">Spend: High to Low</option>
                    <option value="spend_low">Spend: Low to High</option>
                    <option value="orders_high">Orders: High to Low</option>
                </select>
            </div>

            <div class="flex items-end">
                <a href="{{ route('admin.users.index') }}" class="w-full py-2.5 border border-gray-100 text-center text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:bg-black hover:text-white transition-all">Reset Matrix</a>
            </div>
        </form>
    </div>

    <!-- Selection Actions (Below Search) -->
    <div x-show="selectedUsers.length > 0" x-cloak class="bg-black text-white p-4 mb-6 flex items-center justify-between transition-all duration-300 border-l-4 border-gold">
        <div class="flex items-center gap-6">
            <span class="text-[11px] font-black uppercase tracking-widest text-gold" x-text="selectedUsers.length + ' Profiles Intercepted'"></span>
            <div class="h-4 w-[1px] bg-gray-800"></div>
            
            <div class="flex items-center gap-2">
                <button @click="bulkDelete()" title="Data Wipe" class="w-8 h-8 flex items-center justify-center bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all rounded-sm border border-red-500/20">
                    <i class="fas fa-trash-alt text-[10px]"></i>
                </button>
            </div>
        </div>
        <button @click="selectedUsers = []" class="text-[10px] font-black uppercase tracking-widest text-gray-500 hover:text-white">Abort Selection</button>
    </div>

    <!-- Users Intelligence Table -->
    <div class="bg-white border border-gray-100 shadow-sm overflow-x-auto">
        <table class="w-full text-left min-w-[1000px]">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-5 w-10">
                        <input type="checkbox" @change="toggleAll()" :checked="allSelected" class="w-4 h-4 rounded border-gray-300 text-black focus:ring-black">
                    </th>
                    <th class="px-6 py-5 text-[10px] font-black text-black uppercase tracking-widest">Customer Identity</th>
                    <th class="px-6 py-5 text-[10px] font-black text-black uppercase tracking-widest">Location</th>
                    <th class="px-6 py-5 text-[10px] font-black text-black uppercase tracking-widest text-center">Status</th>
                    <th class="px-6 py-5 text-[10px] font-black text-black uppercase tracking-widest text-center">Activity</th>
                    <th class="px-6 py-5 text-[10px] font-black text-black uppercase tracking-widest text-center">Value</th>
                    <th class="px-6 py-5 text-[10px] font-black text-black uppercase tracking-widest text-right">Protocol</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-[11px]">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50/50 transition-all group" :class="selectedUsers.includes({{ $user->id }}) ? 'bg-black/5' : ''">
                    <td class="px-6 py-5">
                        <input type="checkbox" value="{{ $user->id }}" x-model="selectedUsers" class="w-4 h-4 rounded border-gray-300 text-black focus:ring-black user-checkbox">
                    </td>
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-none bg-black border border-gray-200 overflow-hidden flex items-center justify-center shrink-0">
                                @if($user->profile_image)
                                    <img src="{{ asset('storage/' . $user->profile_image) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-white font-black text-[16px]">{{ substr($user->name, 0, 1) }}</span>
                                @endif
                            </div>
                            <div>
                                <p class="font-black text-black uppercase tracking-tight">{{ $user->name }}</p>
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">{{ $user->email }}</p>
                                <p class="text-[9px] text-gray-300 font-bold uppercase tracking-widest">{{ $user->phone ?? 'NO MOBILE' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        <p class="font-bold text-black uppercase">{{ $user->city ?? '-' }}</p>
                        <p class="text-[9px] text-gray-300 font-bold uppercase tracking-widest mt-0.5">{{ $user->country ?? 'Pakistan' }}</p>
                    </td>
                    <td class="px-6 py-5 text-center">
                        @php
                            $statusMap = [
                                'active' => 'bg-green-50 text-green-600 border-green-100',
                                'blocked' => 'bg-red-50 text-red-600 border-red-100',
                                'vip' => 'bg-gold/10 text-gold border-gold/20 font-black',
                            ];
                        @endphp
                        <span class="px-3 py-1.5 {{ $statusMap[$user->status] ?? 'bg-gray-50 text-gray-500 border-gray-100' }} text-[9px] font-black uppercase tracking-widest border">
                            {{ $user->status }}
                        </span>
                    </td>
                    <td class="px-6 py-5 text-center">
                        <div class="flex flex-col items-center">
                            <span class="text-sm font-black text-black">{{ $user->orders_count }}</span>
                            <span class="text-[8px] text-gray-300 font-bold uppercase tracking-widest">Orders</span>
                        </div>
                    </td>
                    <td class="px-6 py-5 text-center">
                        <div class="flex flex-col items-center">
                            <span class="text-sm font-black text-gold">Rs. {{ number_format($user->orders_sum_total ?? 0) }}</span>
                            <span class="text-[8px] text-gray-300 font-bold uppercase tracking-widest">Lifetime value</span>
                        </div>
                    </td>
                    <td class="px-6 py-5 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.users.show', $user) }}" title="Deep Intelligence" class="p-2.5 bg-gray-50 text-black hover:bg-black hover:text-white transition-all border border-gray-100">
                                <i class="fas fa-eye text-[10px]"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}" title="Edit Identity" class="p-2.5 bg-gray-50 text-black hover:bg-black hover:text-white transition-all border border-gray-100">
                                <i class="fas fa-edit text-[10px]"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-20 text-center">
                        <i class="fas fa-users-slash text-4xl text-gray-100 mb-4"></i>
                        <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.3em]">No entities found in this sector</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-6">
        {{ $users->links() }}
    </div>
</div>

<script>
    function userManager() {
        return {
            selectedUsers: [],
            allSelected: false,
            
            toggleAll() {
                this.allSelected = !this.allSelected;
                const checkboxes = Array.from(document.querySelectorAll('.user-checkbox'));
                if (this.allSelected) {
                    this.selectedUsers = checkboxes.map(el => parseInt(el.value));
                } else {
                    this.selectedUsers = [];
                }
            },

            async bulkDelete() {
                if (!confirm(`Permanently remove ${this.selectedUsers.length} selected customers?`)) return;

                const response = await fetch('{{ route('admin.users.bulk-delete') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ user_ids: this.selectedUsers })
                });

                if ((await response.json()).success) {
                    location.reload();
                }
            }
        }
    }
</script>
@endsection
