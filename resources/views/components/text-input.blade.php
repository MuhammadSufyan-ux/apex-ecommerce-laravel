@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border border-[#5C5C5C] focus:border-black focus:ring-0 rounded-none shadow-sm px-4 py-3 w-full transition-colors bg-white/50 placeholder:text-gray-400 font-sans tracking-wide text-sm']) !!}>
