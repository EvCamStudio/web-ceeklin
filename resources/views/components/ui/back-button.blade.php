@props([
    'label' => 'Kembali'
])

<button {{ $attributes->merge(['class' => 'group flex items-center gap-3 bg-white border-[3px] border-gray-900 px-4 py-2.5 shadow-[4px_4px_0_var(--color-gray-900)] hover:shadow-none hover:translate-x-[2px] hover:translate-y-[2px] transition-all']) }}>
    <div class="w-6 h-6 bg-gray-900 flex items-center justify-center text-white group-hover:bg-primary transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
    </div>
    <span class="text-[11px] font-black uppercase tracking-widest text-gray-900">{{ $label }}</span>
</button>
