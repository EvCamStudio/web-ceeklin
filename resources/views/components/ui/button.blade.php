@props([
    'variant' => 'primary', // primary, secondary, neutral, danger
    'type' => 'button',
    'icon' => null,
    'fullWidth' => false,
])

@php
    // Brutalist Base Button
    $baseClasses = 'font-headline font-bold text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] active:translate-y-0.5 active:shadow-none transition-all flex items-center justify-center gap-2';

    // Padding & Layout
    $layoutClasses = $fullWidth ? 'w-full py-4' : 'px-8 py-3';

    // Variants
    $variants = [
        'primary'   => 'bg-primary text-white hover:bg-primary-hover',
        'secondary' => 'bg-secondary text-white hover:bg-secondary-dark',
        'neutral'   => 'bg-gray-900 text-white hover:bg-gray-700',
        'outline'   => 'bg-white text-gray-900 hover:bg-neutral-light',
        'danger'    => 'bg-red-600 text-white hover:bg-red-700',
    ];

    $variantClasses = $variants[$variant] ?? $variants['primary'];
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "{$baseClasses} {$layoutClasses} {$variantClasses}"]) }}>
    @if($icon)
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            {!! $icon !!}
        </svg>
    @endif
    {{ $slot }}
</button>
