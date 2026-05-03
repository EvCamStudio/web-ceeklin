@props(['type' => 'success', 'message' => ''])

@php
    $baseClasses = 'pointer-events-auto w-full max-w-[400px] overflow-hidden border-[4px] border-gray-900 shadow-[12px_12px_0_rgba(0,0,0,0.1)] transition-all duration-500 transform';
    
    if ($type === 'error') {
        $typeClasses = 'bg-red-600 text-white';
        $icon = '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />';
        $accentColor = 'bg-red-800';
    } elseif ($type === 'warning') {
        $typeClasses = 'bg-yellow-400 text-gray-900';
        $icon = '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />'; 
        $accentColor = 'bg-yellow-600';
    } else {
        // Success: use a nice green instead of primary (which is red in this project)
        $typeClasses = 'bg-green-600 text-white';
        $icon = '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />';
        $accentColor = 'bg-green-800';
    }
@endphp

<div 
    role="alert" 
    x-data="{ show: true }"
    x-show="show"
    x-init="setTimeout(() => show = false, 5000)"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="translate-x-full opacity-0 scale-95"
    x-transition:enter-end="translate-x-0 opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="translate-x-0 opacity-100"
    x-transition:leave-end="translate-x-full opacity-0"
    {{ $attributes->merge(['class' => $baseClasses . ' ' . $typeClasses]) }}
>
    <div class="relative px-6 py-4 flex items-center gap-4">
        {{-- Side Accent --}}
        <div class="absolute left-0 top-0 bottom-0 w-2 {{ $accentColor }}"></div>
        
        <div class="flex-shrink-0">
            <div class="w-10 h-10 bg-white/20 border-2 border-white/30 flex items-center justify-center shadow-[2px_2px_0_rgba(0,0,0,0.2)]">
                <svg class="h-6 w-6 text-current" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                    {!! $icon !!}
                </svg>
            </div>
        </div>
        
        <div class="flex-1 pr-2">
            <p class="text-[10px] font-headline font-black uppercase tracking-widest leading-snug">
                {{ $message ?: $slot }}
            </p>
        </div>

        <button type="button" @click="show = false" class="flex-shrink-0 text-white/50 hover:text-white transition-colors">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>
