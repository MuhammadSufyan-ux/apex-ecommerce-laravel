<x-app-layout>
    <div class="py-12 bg-[#F9F9F9] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-black text-black uppercase tracking-tighter">Notifications</h2>
                    <p class="text-gray-500 font-medium tracking-wide text-sm mt-1">Stay updated with your order status and exclusive offers.</p>
                </div>
                
                @if($notifications->count() > 0)
                <form action="{{ route('user.notifications.clear-all') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete ALL notifications?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800 text-[10px] font-bold uppercase tracking-widest border-b border-red-600 hover:border-red-800 transition-all">
                        Clear All History
                    </button>
                </form>
                @endif
            </div>

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded relative text-xs font-bold uppercase tracking-wide" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white border border-gray-100 shadow-[0_2px_15px_rgba(0,0,0,0.03)] rounded-sm overflow-hidden">
                @if($notifications->count() > 0)
                    <form id="bulk-action-form" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="form-method" value="POST">
                        
                        <!-- Toolbar -->
                        <div class="p-4 bg-gray-50 border-b border-gray-100 flex items-center gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="select-all" class="w-4 h-4 border-gray-300 text-black focus:ring-black rounded-sm">
                                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Select All</span>
                            </label>
                            
                            <div class="h-4 w-px bg-gray-300"></div>

                            <button type="button" onclick="submitBulk('{{ route('user.notifications.mark-read') }}', 'POST')" class="text-gray-500 hover:text-black text-[10px] font-bold uppercase tracking-wider flex items-center gap-2 transition-colors">
                                <i class="fas fa-check-double"></i> Mark Read
                            </button>
                            
                            <button type="button" onclick="submitBulk('{{ route('user.notifications.bulk-destroy') }}', 'DELETE')" class="text-gray-500 hover:text-red-600 text-[10px] font-bold uppercase tracking-wider flex items-center gap-2 transition-colors">
                                <i class="fas fa-trash-alt"></i> Delete Selected
                            </button>
                        </div>

                        <!-- List -->
                        <div class="divide-y divide-gray-50">
                            @foreach($notifications as $notification)
                                <div class="p-6 hover:bg-gray-50 transition-colors group flex gap-4 {{ $notification->read_at ? 'opacity-70' : 'bg-blue-50/30' }}">
                                    <div class="pt-1">
                                        <input type="checkbox" name="ids[]" value="{{ $notification->id }}" class="notification-checkbox w-4 h-4 border-gray-300 text-black focus:ring-black rounded-sm">
                                    </div>
                                    
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start mb-1">
                                            <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wide {{ !$notification->read_at ? 'text-black' : 'text-gray-600' }}">
                                                {{ $notification->data['title'] ?? 'Notification' }}
                                                @if(!$notification->read_at)
                                                    <span class="ml-2 w-2 h-2 rounded-full bg-gold inline-block align-middle"></span>
                                                @endif
                                            </h4>
                                            <span class="text-[10px] font-mono text-gray-400 uppercase">{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                        
                                        <p class="text-xs text-gray-600 font-medium leading-relaxed mb-3">
                                            {{ $notification->data['message'] ?? '' }}
                                        </p>

                                        <div class="flex items-center gap-4">
                                            @if(isset($notification->data['link']) && $notification->data['link'] !== '#')
                                                <a href="{{ $notification->data['link'] }}" class="text-[10px] font-bold text-black uppercase tracking-widest border-b border-black hover:text-gold hover:border-gold transition-all">
                                                    View Details
                                                </a>
                                            @endif
                                            
                                            <!-- Single Delete Form -->
                                            <button type="button" onclick="deleteSingle('{{ $notification->id }}')" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-red-600 transition-colors">
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </form>
                    
                    <!-- Pagination -->
                    @if($notifications->hasPages())
                        <div class="p-4 border-t border-gray-100 bg-gray-50">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                @else
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                            <i class="fas fa-bell-slash text-2xl"></i>
                        </div>
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide">No Notifications</h3>
                        <p class="text-xs text-gray-500 mt-2">You're all caught up! Check back later for updates.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Hidden form for single delete to keep logic clean -->
    <form id="single-delete-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    @push('scripts')
    <script>
        // Select All Logic
        document.getElementById('select-all')?.addEventListener('change', function(e) {
            const checkboxes = document.querySelectorAll('.notification-checkbox');
            checkboxes.forEach(cb => cb.checked = e.target.checked);
        });

        // Bulk Action Submission
        function submitBulk(url, method) {
            const form = document.getElementById('bulk-action-form');
            const checkboxes = document.querySelectorAll('.notification-checkbox:checked');
            
            if (checkboxes.length === 0) {
                alert('Please select at least one notification.');
                return;
            }

            if (method === 'DELETE' && !confirm('Are you sure you want to delete selected notifications?')) {
                return;
            }

            form.action = url;
            document.getElementById('form-method').value = method;
            form.submit();
        }

        // Single Delete
        function deleteSingle(id) {
            if(!confirm('Delete this notification?')) return;
            
            const form = document.getElementById('single-delete-form');
            form.action = "{{ route('user.notifications.destroy', ':id') }}".replace(':id', id);
            form.submit();
        }
    </script>
    @endpush
</x-app-layout>
