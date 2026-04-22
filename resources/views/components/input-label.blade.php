@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-bold text-xs uppercase tracking-widest text-[#5C5C5C] mb-2']) }}>
    {{ $value ?? $slot }}
</label>
