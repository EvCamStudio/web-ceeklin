@props([
    'shadow' => 'primary', // primary, secondary, gray
    'headerColor' => 'primary', // primary, secondary, gray
    'headerIcon' => null,
])

@php
    // Brutalist Frame Base
    $baseCardClasses = 'bg-white border-[4px] border-gray-900';

    // Shadow Variants
    $shadowVariants = [
        'primary'   => 'shadow-[8px_8px_0_var(--color-primary-darkest)]',
        'secondary' => 'shadow-[8px_8px_0_var(--color-secondary)]',
        'gray'      => 'shadow-[8px_8px_0_var(--color-gray-900)]',
        'none'      => '',
    ];

    // Header Background Variants
    $headerBgVariants = [
        'primary'   => 'bg-primary text-white',
        'secondary' => 'bg-secondary text-white',
        'gray'      => 'bg-gray-900 text-white',
        'light'     => 'bg-neutral-light text-primary border-b-[3px] border-neutral-border',
    ];

    $shadowClass = $shadowVariants[$shadow] ?? $shadowVariants['primary'];
    $headerClass = $headerBgVariants[$headerColor] ?? $headerBgVariants['primary'];
@endphp

<div {{ $attributes->merge(['class' => "{$baseCardClasses} {$shadowClass} flex flex-col"]) }}>
    {{-- Header Slot (Opsional jika titleSlot disertakan) --}}
    @if(isset($titleSlot) || isset($headerAction))
        <div class="{{ $headerClass }} px-6 py-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
                @if($headerIcon)
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        {!! $headerIcon !!}
                    </svg>
                @endif
                <span class="font-headline font-black text-base uppercase tracking-tight">
                    {{ $titleSlot ?? '' }}
                </span>
            </div>
            
            {{-- Slot Opsional untuk Tombol di Header --}}
            @if(isset($headerAction))
                <div>
                    {{ $headerAction }}
                </div>
            @endif
        </div>
    @endif

    {{-- Bodi Card Utama --}}
    <div class="p-6">
        {{ $slot }}
    </div>
</div>
