<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-[#5C5C5C] border border-transparent rounded-none font-bold text-xs text-white uppercase tracking-[0.2em] hover:bg-black focus:bg-black active:bg-black focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition ease-in-out duration-300 w-full shadow-lg hover:shadow-xl transform hover:-translate-y-0.5']) }}>
    {{ $slot }}
</button>
