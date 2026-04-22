<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-['Playfair_Display'] font-bold text-black tracking-wide">Create Account</h2>
        <p class="text-xs text-[#5C5C5C] mt-2 uppercase tracking-widest font-semibold">Join S4 Luxury Store</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div class="space-y-2">
            <x-input-label for="name" :value="__('Full Name')" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="far fa-user text-gray-400"></i>
                </div>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe"
                    class="block w-full border border-[#5C5C5C] focus:border-black focus:ring-0 rounded-none shadow-sm pl-11 pr-4 py-3 transition-colors bg-white/50 placeholder:text-gray-400 font-sans tracking-wide text-sm">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="space-y-2">
            <x-input-label for="email" :value="__('Email Address')" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="far fa-envelope text-gray-400"></i>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="your@email.com"
                    class="block w-full border border-[#5C5C5C] focus:border-black focus:ring-0 rounded-none shadow-sm pl-11 pr-4 py-3 transition-colors bg-white/50 placeholder:text-gray-400 font-sans tracking-wide text-sm">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div x-data="{ 
            show: false, 
            password: '',
            get strength() {
                let score = 0;
                if (this.password.length > 0) score++;
                if (this.password.length >= 8) score++;
                if (/[a-z]/.test(this.password) && /[A-Z]/.test(this.password)) score++;
                if (/[0-9]/.test(this.password) || /[^A-Za-z0-9]/.test(this.password)) score++;
                return score;
            },
            get strengthColor() {
                if (this.strength === 0) return 'bg-gray-100';
                if (this.strength === 1) return 'bg-red-500';
                if (this.strength === 2) return 'bg-orange-500';
                if (this.strength === 3) return 'bg-yellow-500';
                return 'bg-green-600';
            },
            get strengthText() {
                if (this.strength === 0) return '';
                if (this.strength === 1) return 'Very Weak';
                if (this.strength === 2) return 'Weak';
                if (this.strength === 3) return 'Fair';
                return 'Strong';
            }
        }" class="space-y-2">
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input id="password" 
                    :type="show ? 'text' : 'password'" 
                    name="password" 
                    x-model="password"
                    required 
                    autocomplete="new-password" 
                    placeholder="••••••••"
                    class="block w-full border border-[#5C5C5C] focus:border-black focus:ring-0 rounded-none shadow-sm pl-11 pr-12 py-3 transition-colors bg-white/50 placeholder:text-gray-400 font-sans tracking-wide text-sm">
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-black transition-colors">
                    <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
            
            <!-- Password Strength Bar -->
            <div class="mt-3" x-show="password.length > 0" x-cloak>
                <div class="flex justify-between items-center mb-1.5">
                    <span class="text-[9px] uppercase tracking-widest font-black" :class="strength > 2 ? 'text-green-600' : 'text-red-500'" x-text="strengthText"></span>
                </div>
                <div class="h-1 w-full bg-gray-100">
                    <div class="h-1 transition-all duration-700" :class="strengthColor" :style="`width: ${strength * 25}%`"></div>
                </div>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div x-data="{ show: false }" class="space-y-2">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-shield-alt text-gray-400"></i>
                </div>
                <input id="password_confirmation" 
                    :type="show ? 'text' : 'password'" 
                    name="password_confirmation" 
                    required 
                    autocomplete="new-password" 
                    placeholder="••••••••"
                    class="block w-full border border-[#5C5C5C] focus:border-black focus:ring-0 rounded-none shadow-sm pl-11 pr-12 py-3 transition-colors bg-white/50 placeholder:text-gray-400 font-sans tracking-wide text-sm">
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-black transition-colors">
                    <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-4 flex flex-col gap-4">
            <x-primary-button>
                {{ __('Create Account') }}
            </x-primary-button>

            <div class="text-center">
                <span class="text-xs text-[#808080]">Already have an account?</span>
                <a href="{{ route('login') }}" class="text-xs font-bold text-black border-b border-black hover:border-transparent transition-colors ml-1 uppercase tracking-wider">
                    Sign In
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>
