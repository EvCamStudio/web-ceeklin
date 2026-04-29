@props(['name' => null, 'message' => null, 'hideServerError' => false])

@php
    $hasServerError = !$hideServerError && $name && $errors->has($name);
    $serverErrorMessage = $hasServerError ? $errors->first($name) : '';
@endphp

@if($name)
    {{-- Client-side error handling (Alpine.js) & Server-side error handling --}}
    <div x-show="errors['{{ $name }}'] || {{ $hasServerError ? 'true' : 'false' }}" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-1 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-1 scale-95"
         class="absolute top-full left-0 mt-2 z-20 flex flex-col items-start" 
         style="display: none;"
         x-cloak>
        
        {{-- Arrow pointing to the input --}}
        <div class="w-2 h-2 bg-red-600 rotate-45 ml-4 -mb-1 z-0 shadow-sm border-t border-l border-red-700"></div>
        
        {{-- Pill Body --}}
        <div class="bg-red-600 border border-red-700 text-white px-3 py-1.5 text-[9px] font-black uppercase tracking-wider flex items-center gap-1.5 shadow-[4px_4px_0_rgba(0,0,0,0.15)] z-10">
            <svg class="w-3.5 h-3.5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span x-text="errors['{{ $name }}'] || '{{ $serverErrorMessage }}'"></span>
        </div>
    </div>
@elseif($message)
    {{-- Direct message passing (Custom Error) --}}
    <div x-data="{ show: true }" x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-1 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         class="absolute top-full left-0 mt-2 z-20 flex flex-col items-start"
         x-cloak>
        
        {{-- Arrow pointing up --}}
        <div class="w-2 h-2 bg-red-600 rotate-45 ml-4 -mb-1 z-0 shadow-sm border-t border-l border-red-700"></div>
        
        {{-- Pill Body --}}
        <div class="bg-red-600 border border-red-700 text-white px-3 py-1.5 text-[9px] font-black uppercase tracking-wider flex items-center gap-1.5 shadow-[4px_4px_0_rgba(0,0,0,0.15)] z-10">
            <svg class="w-3.5 h-3.5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>{{ $message }}</span>
        </div>
    </div>
@endif
