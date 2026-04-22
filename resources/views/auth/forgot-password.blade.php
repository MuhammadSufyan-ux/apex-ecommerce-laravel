<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-2xl font-['Playfair_Display'] font-bold text-black tracking-wide">Reset Password</h2>
        <p class="text-xs text-[#5C5C5C] mt-2 uppercase tracking-widest font-semibold">We've got you covered</p>
    </div>

    <div class="mb-4 text-sm text-[#5C5C5C] text-center leading-relaxed">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="your@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="pt-2 flex flex-col gap-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
            
             <div class="text-center">
                <a href="{{ route('login') }}" class="text-xs font-bold text-[#5C5C5C] hover:text-black transition-colors uppercase tracking-wider">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Login
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>
