@props([
    'type' => 'text',
    'label' => '',
    'disabled' => false,
    'id' => null,
    'fullWidth' => true,
    'icon' => null,
    'hideError' => false,
    'hideServerError' => false,
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

<div class="flex flex-col gap-1.5 {{ $widthClass }} relative"
     @if($type === 'password') x-data="{ showPassword: false }" @endif>
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
            :type="@if($type === 'password') showPassword ? 'text' : 'password' @else '{{ $type }}' @endif"
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->merge(['class' => "{$baseClasses} {$textClasses} {$cursorClass} {$paddingClasses} {$widthClass}"]) }}
            :class="errors['{{ $attributes->get('name') }}'] ? 'border-red-600 bg-red-50' : ''"
            @blur="if (typeof validateField === 'function') validateField($el)"
            @input="if (typeof validateField === 'function' && errors['{{ $attributes->get('name') }}']) validateField($el)"
        >
        
        @if($type === 'password')
            <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer text-primary/70 hover:text-primary transition-colors" @click="showPassword = !showPassword">
                <!-- Eye Icon -->
                <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;" x-cloak>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <!-- Eye Off Icon -->
                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0112 4.5c4.756 0 8.774 3.162 10.065 7.498a10.522 10.522 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                </svg>
            </button>
        @endif
        
        {{-- Slot belakang opsional --}}
        @if(isset($trailingSlot))
            <div class="absolute right-3 top-1/2 -translate-y-1/2 text-primary/70">
                {{ $trailingSlot }}
            </div>
        @endif
    </div>

    {{-- Error Message --}}
    @if(!$hideError && $attributes->has('name'))
        <x-ui.error :name="$attributes->get('name')" :hideServerError="$hideServerError" />
    @endif
</div>
