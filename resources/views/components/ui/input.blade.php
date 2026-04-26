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
        <label class="text-[9px] font-bold text-primary uppercase tracking-widest" for="{{ $inputId }}">
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
            :class="errors['{{ $attributes->get('name') }}'] ? 'border-red-600 bg-red-50' : ''"
            @blur="if (typeof validateField === 'function') validateField($el)"
            @input="if (typeof validateField === 'function' && errors['{{ $attributes->get('name') }}']) validateField($el)"
        >
        
        {{-- Slot belakang opsional --}}
        @if(isset($trailingSlot))
            <div class="absolute right-3 top-1/2 -translate-y-1/2 text-primary/70">
                {{ $trailingSlot }}
            </div>
        @endif
    </div>

    {{-- Error Message (Google Forms Style) --}}
    <div x-show="errors['{{ $attributes->get('name') }}']" 
         x-transition
         class="text-[10px] font-black text-red-600 uppercase tracking-tight flex items-center gap-1 mt-1" 
         style="display: none;">
        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
        <span x-text="errors['{{ $attributes->get('name') }}']"></span>
    </div>

    @error($attributes->get('name'))
        <div class="text-[10px] font-black text-red-600 uppercase tracking-tight flex items-center gap-1 mt-1">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
            {{ $message }}
        </div>
    @enderror
</div>
