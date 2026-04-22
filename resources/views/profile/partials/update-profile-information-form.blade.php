<section>
    <form method="post" action="{{ route('profile.update') }}" class="space-y-8" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Profile Image Section -->
        <div class="p-6 bg-gray-50/50 border border-dashed border-gray-200 rounded-sm">
            <div class="flex flex-col sm:flex-row items-center gap-8">
                <div class="relative">
                    <div class="w-32 h-32 rounded-full overflow-hidden bg-white border border-gray-100 shadow-sm flex items-center justify-center">
                        @if($user->profile_image)
                            <img id="profile-preview" src="{{ asset('storage/' . $user->profile_image) }}" class="w-full h-full object-cover">
                        @else
                            <div id="profile-placeholder" class="w-full h-full bg-black flex items-center justify-center text-white text-3xl font-bold uppercase">
                                @php
                                    $names = explode(' ', $user->name);
                                    $initials = count($names) >= 2 
                                        ? substr($names[0], 0, 1) . substr(end($names), 0, 1)
                                        : substr($names[0], 0, 2);
                                @endphp
                                {{ $initials }}
                            </div>
                            <img id="profile-preview" src="#" class="w-full h-full object-cover hidden">
                        @endif
                    </div>
                </div>

                <div class="flex-1 text-center sm:text-left">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-black mb-1">Profile Photo</h3>
                    <p class="text-[11px] text-gray-500 mb-4 uppercase tracking-wider">JPG or PNG. Max size 2MB.</p>
                    <label class="cursor-pointer inline-flex items-center gap-2 px-6 py-2.5 bg-black text-white text-[10px] font-bold uppercase tracking-widest hover:bg-gray-800 transition-all">
                        <i class="fas fa-camera text-[10px]"></i>
                        <span>Change Photo</span>
                        <input type="file" name="profile_image" id="profile_image_input" class="hidden" accept="image/*" onchange="previewImage(this)">
                    </label>
                    <x-input-error class="mt-2" :messages="$errors->get('profile_image')" />
                </div>
            </div>
        </div>

        <!-- Name & Email -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="name" :value="__('Full Name')" class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2" />
                <x-text-input id="name" name="name" type="text" class="w-full border-gray-200 focus:border-black focus:ring-0 rounded-none py-3" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2 text-[10px]" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email Address')" class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2" />
                <x-text-input id="email" name="email" type="email" class="w-full border-gray-200 focus:border-black focus:ring-0 rounded-none py-3" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2 text-[10px]" :messages="$errors->get('email')" />
            </div>
        </div>

        <div class="pt-4 flex items-center gap-4">
            <button type="submit" class="px-10 py-4 bg-black text-white text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-gray-800 transition-all shadow-sm">
                Save Changes
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-[10px] font-bold uppercase tracking-widest text-green-600">Updated Successfully</p>
            @endif
        </div>
    </form>
</section>
</section>
