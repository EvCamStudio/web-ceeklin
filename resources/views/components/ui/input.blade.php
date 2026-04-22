@props([
    'type' => 'text',
    'label' => '',
    'disabled' => false,
    'id' => null,
    'fullWidth' => true,
    'icon' => null,
])

@php
    $inputId = $id ?? uniqid('input-');
    $widthClass = $fullWidth ? 'w-full' : '';

    // Base Brutalist Input Classes
    $baseClasses = 'bg-neutral-light border-[3px] border-primary px-4 py-2.5 font-body text-sm font-bold focus:outline-none focus:border-secondary transition-colors placeholder:text-primary/30';
    $textClasses = $disabled ? 'text-slate-400' : 'text-primary';
    $cursorClass = $disabled ? 'cursor-not-allowed bg-neutral-border-light border-neutral-border' : '';
    
    // Icon Padding Adjustment
    $paddingClasses = $icon ? 'pl-10' : '';
@endphp

<div class="flex flex-col gap-1.5 {{ $widthClass }}">
    @if($label)
        <label class="text-[10px] font-bold text-secondary uppercase tracking-widest" for="{{ $inputId }}">
            {{ $label }}
        </label>
    @endif
    
    <div class="relative w-full">
        @if($icon)
            <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none text-primary/50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {!! $icon !!}
                </svg>
            </div>
        @endif

        <input 
            id="{{ $inputId }}"
            type="{{ $type }}"
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->merge(['class' => "{$baseClasses} {$textClasses} {$cursorClass} {$paddingClasses} {$widthClass}"]) }}
        >
        
        {{-- Slot belakang opsional (seperti icon toggle password/satuan unit) --}}
        @if(isset($trailingSlot))
            <div class="absolute right-3 top-1/2 -translate-y-1/2 text-primary/70">
                {{ $trailingSlot }}
            </div>
        @endif
    </div>
</div>
