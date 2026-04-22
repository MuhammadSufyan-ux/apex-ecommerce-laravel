@extends('layouts.admin')

@section('content')
<div class="px-8 py-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
        <div>
            <h1 class="text-3xl font-black text-black uppercase tracking-tighter leading-none mb-2">COMMUNICATION LEDGER</h1>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.3em]">Auditing global notification traffic</p>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            <!-- Search -->
            <form action="{{ route('admin.notifications.index') }}" method="GET" class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search notifications..."
                    class="bg-white border border-gray-200 text-[11px] font-bold text-black px-4 py-3 pl-10 w-64 focus:border-black focus:ring-0 transition-all uppercase tracking-wider placeholder:normal-case placeholder:tracking-normal">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            </form>

            <form action="{{ route('admin.notifications.clear') }}" method="POST" onsubmit="return confirm('PURGE ALL COMMUNICATION RECORDS?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-6 py-3 text-[10px] font-black uppercase tracking-widest hover:bg-black transition-all">
                    <i class="fas fa-trash-alt mr-2"></i> Purge All
                </button>
            </form>
        </div>
    </div>

    <!-- Bulk Actions Bar -->
    <div x-data="bulkNotifications()" x-cloak>
        <div x-show="selectedIds.length > 0" 
             class="mb-6 p-4 bg-black text-white flex items-center justify-between" x-cloak>
            <span class="text-[10px] font-black uppercase tracking-widest">
                <span x-text="selectedIds.length"></span> notification(s) selected
            </span>
            <button @click="bulkDelete()" class="bg-red-600 text-white px-6 py-2 text-[10px] font-black uppercase tracking-widest hover:bg-red-700 transition-all">
                <i class="fas fa-trash-alt mr-2"></i> Delete Selected
            </button>
        </div>

        <!-- Logs Table -->
        <div class="bg-white border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-4 py-4 w-10">
                                <input type="checkbox" @change="toggleAll($event)" 
                                    class="w-4 h-4 border-gray-300 text-black focus:ring-black rounded-none cursor-pointer"
                                    :checked="allSelected">
                            </th>
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-400">Timestamp</th>
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-400">Channel</th>
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-400">Operational Event</th>
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-400">Recipient Node</th>
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-400">Status</th>
                            <th class="px-6 py-4 text-[9px] font-black uppercase tracking-widest text-gray-400 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($logs as $log)
                        <tr class="hover:bg-gray-50/50 transition-all group">
                            <td class="px-4 py-4">
                                <input type="checkbox" value="{{ $log->id }}" x-model="selectedIds"
                                    class="w-4 h-4 border-gray-300 text-black focus:ring-black rounded-none cursor-pointer">
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-[10px] font-bold text-black uppercase tracking-tight">{{ $log->created_at->format('M d, Y') }}</p>
                                <p class="text-[9px] font-medium text-gray-400 uppercase tracking-widest">{{ $log->created_at->format('H:i:s') }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-2 px-2.5 py-1 rounded-sm text-[8px] font-black uppercase tracking-widest {{ $log->type == 'email' ? 'bg-blue-50 text-blue-600' : ($log->type == 'sms' ? 'bg-green-50 text-green-600' : 'bg-pink-50 text-pink-600') }}">
                                    <i class="fas {{ $log->type == 'email' ? 'fa-envelope' : ($log->type == 'sms' ? 'fa-comment' : 'fa-whatsapp') }}"></i>
                                    {{ $log->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-[10px] font-bold text-black uppercase tracking-widest">{{ str_replace('_', ' ', $log->event) }}</p>
                                <p class="text-[9px] font-medium text-gray-400 truncate max-w-[200px]">{{ $log->message }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 bg-gray-100 border border-gray-200 rounded-sm flex items-center justify-center text-[10px] font-bold text-gray-400">
                                        {{ $log->recipient_type == 'admin' ? 'A' : 'C' }}
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-black">{{ $log->recipient }}</p>
                                        <p class="text-[8px] font-black text-gray-300 uppercase tracking-widest">{{ $log->recipient_type }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-widest {{ $log->status == 'sent' ? 'text-green-500' : 'text-red-500' }}">
                                    <i class="fas {{ $log->status == 'sent' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                    {{ $log->status }}
                                </span>
                                @if($log->error)
                                    <p class="text-[8px] font-bold text-red-300 uppercase tracking-widest mt-1">{{ $log->error }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button @click="viewNotif({{ json_encode($log) }})" class="text-gray-400 hover:text-black transition-colors mr-2" title="View">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                                <form action="{{ route('admin.notifications.bulk-delete') }}" method="POST" class="inline" onsubmit="return confirm('Delete this notification?')">
                                    @csrf
                                    <input type="hidden" name="ids[]" value="{{ $log->id }}">
                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="Delete">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-20 text-center">
                                <div class="opacity-10 mb-4 text-4xl"><i class="fas fa-satellite-dish"></i></div>
                                <p class="text-[10px] font-black text-gray-300 uppercase tracking-[0.4em]">Intelligence baseline: Quiet</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($logs->hasPages())
            <div class="p-6 border-t border-gray-50 bg-gray-50/30">
                {{ $logs->links() }}
            </div>
            @endif
        </div>

        <!-- View Modal -->
        <div x-show="viewModal" x-cloak @click.self="viewModal = false"
             class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
            <div class="bg-white w-full max-w-lg border-2 border-black shadow-2xl" @click.stop>
                <div class="p-6 bg-black text-white flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase tracking-widest">Notification Detail</span>
                    <button @click="viewModal = false" class="text-white hover:text-gold transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-8 space-y-6">
                    <div>
                        <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Event</p>
                        <p class="text-sm font-bold text-black uppercase" x-text="viewData.event ? viewData.event.replace(/_/g, ' ') : ''"></p>
                    </div>
                    <div>
                        <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Channel</p>
                        <p class="text-sm font-bold text-black uppercase" x-text="viewData.type"></p>
                    </div>
                    <div>
                        <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Recipient</p>
                        <p class="text-sm font-bold text-black" x-text="viewData.recipient"></p>
                    </div>
                    <div>
                        <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Message</p>
                        <p class="text-sm font-medium text-gray-600 leading-relaxed" x-text="viewData.message"></p>
                    </div>
                    <div>
                        <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Status</p>
                        <span class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-widest"
                              :class="viewData.status == 'sent' ? 'text-green-500' : 'text-red-500'" x-text="viewData.status"></span>
                    </div>
                    <div x-show="viewData.error">
                        <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Error</p>
                        <p class="text-sm font-medium text-red-500" x-text="viewData.error"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function bulkNotifications() {
    return {
        selectedIds: [],
        viewModal: false,
        viewData: {},
        
        get allSelected() {
            const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
            return checkboxes.length > 0 && this.selectedIds.length === checkboxes.length;
        },

        toggleAll(event) {
            if (event.target.checked) {
                this.selectedIds = Array.from(document.querySelectorAll('tbody input[type="checkbox"]')).map(cb => cb.value);
            } else {
                this.selectedIds = [];
            }
        },

        bulkDelete() {
            if (!confirm('Delete ' + this.selectedIds.length + ' selected notifications?')) return;
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.notifications.bulk-delete") }}';
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = document.querySelector('meta[name="csrf-token"]').content;
            form.appendChild(csrf);

            this.selectedIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        },

        viewNotif(log) {
            this.viewData = log;
            this.viewModal = true;
        }
    };
}
</script>
@endpush
@endsection
