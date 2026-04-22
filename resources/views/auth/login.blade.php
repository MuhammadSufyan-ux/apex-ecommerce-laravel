<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Success Message (from registration) -->
    @if(session('registration_success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-center" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)">
            <i class="fas fa-check-circle text-green-500 text-xl mb-2"></i>
            <p class="text-sm font-bold text-green-700 uppercase tracking-widest">{{ session('registration_success') }}</p>
        </div>
    @endif

    <div class="mb-8 text-center">
        <h2 class="text-3xl font-['Playfair_Display'] font-bold text-black tracking-wide">Sign In</h2>
        <p class="text-xs text-[#5C5C5C] mt-2 uppercase tracking-widest font-semibold">Welcome Back</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="space-y-2">
            <x-input-label for="email" :value="__('Email')" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="far fa-envelope text-gray-400"></i>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email', session('registered_email')) }}" required autofocus autocomplete="username" placeholder="your@email.com"
                    class="block w-full border border-[#5C5C5C] focus:border-black focus:ring-0 rounded-none shadow-sm pl-11 pr-4 py-3 transition-colors bg-white/50 placeholder:text-gray-400 font-sans tracking-wide text-sm">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div x-data="{ show: false }" class="space-y-2">
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input id="password" 
                    :type="show ? 'text' : 'password'" 
                    name="password" 
                    required 
                    autocomplete="current-password" 
                    placeholder="••••••••"
                    class="block w-full border border-[#5C5C5C] focus:border-black focus:ring-0 rounded-none shadow-sm pl-11 pr-12 py-3 transition-colors bg-white/50 placeholder:text-gray-400 font-sans tracking-wide text-sm">
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-black transition-colors">
                    <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="w-4 h-4 border-[#5C5C5C] text-black focus:ring-black rounded-none shadow-sm cursor-pointer" name="remember">
                <span class="ms-2 text-xs text-[#5C5C5C] group-hover:text-black transition-colors tracking-wide font-medium">{{ __('Remember me') }}</span>
            </label>
            @if (Route::has('password.request'))
                <a class="text-xs text-[#808080] hover:text-black transition-colors tracking-wide font-medium" href="{{ route('password.request') }}">
                    {{ __('Forgot Password?') }}
                </a>
            @endif
        </div>

        <div class="pt-2 flex flex-col gap-4">
            <x-primary-button>
                {{ __('Sign In') }}
            </x-primary-button>

            <div class="text-center">
                <span class="text-xs text-[#808080]">New Customer?</span>
                <a href="{{ route('register') }}" class="text-xs font-bold text-black border-b border-black hover:border-transparent transition-colors ml-1 uppercase tracking-wider">
                    Create Account
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>
