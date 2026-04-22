<section>
    <div class="mb-8">
        <h3 class="text-xs font-bold uppercase tracking-widest text-black mb-1">Security Update</h3>
        <p class="text-[11px] text-gray-500 uppercase tracking-wider">Change your password to keep your account safe.</p>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <x-input-label for="update_password_current_password" :value="__('Current Password')" class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2" />
                <x-text-input id="update_password_current_password" name="current_password" type="password" class="w-full border-gray-200 focus:border-black focus:ring-0 rounded-none py-3" autocomplete="current-password" />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-[10px]" />
            </div>

            <div>
                <x-input-label for="update_password_password" :value="__('New Password')" class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2" />
                <x-text-input id="update_password_password" name="password" type="password" class="w-full border-gray-200 focus:border-black focus:ring-0 rounded-none py-3" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-[10px]" />
            </div>

            <div>
                <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2" />
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full border-gray-200 focus:border-black focus:ring-0 rounded-none py-3" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-[10px]" />
            </div>
        </div>

        <div class="pt-4 flex items-center gap-4">
            <button type="submit" class="px-10 py-4 bg-black text-white text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-gray-800 transition-all shadow-sm">
                Update Security
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-[10px] font-bold uppercase tracking-widest text-green-600">Password Updated</p>
            @endif
        </div>
    </form>
</section>
