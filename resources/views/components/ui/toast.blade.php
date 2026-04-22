@props(['type' => 'success', 'message' => ''])

@php
    $baseClasses = 'pointer-events-auto w-full max-w-sm overflow-hidden border-[3px] rounded-none shadow-[8px_8px_0_var(--color-gray-900)] transform transition-all duration-300';
    
    if ($type === 'error') {
        $typeClasses = 'bg-primary-darkest border-primary text-white';
        $icon = '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />';
        $iconClasses = 'text-secondary';
    } elseif ($type === 'warning') {
        $typeClasses = 'bg-secondary border-secondary-dark text-gray-900';
        $icon = '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />'; 
        $iconClasses = 'text-primary-darkest';
    } else {
        // Success Base
        $typeClasses = 'bg-neutral-light border-primary text-primary';
        $icon = '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />';
        $iconClasses = 'text-primary';
    }
@endphp

{{-- 
  Flash Alert Container.
  Gunakan AlpineJS (x-data) untuk mengontrol visibilitas dan timeout otomatis 5 detik.
--}}
<div 
    role="alert" 
    aria-live="assertive" 
    aria-atomic="true" 
    x-data="{ show: true }"
    x-show="show"
    x-init="setTimeout(() => show = false, 5000)"
    x-transition:enter="transition ease-out duration-300 transform"
    x-transition:enter-start="translate-y-2 opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100"
    x-transition:leave="transition ease-in duration-200 transform"
    x-transition:leave-start="translate-y-0 opacity-100"
    x-transition:leave-end="translate-y-2 opacity-0"
    {{ $attributes->merge(['class' => $baseClasses . ' ' . $typeClasses]) }}
>
    <div class="p-4 relative">
        <!-- Akses Garis Brutalist -->
        <div class="absolute inset-y-0 left-0 w-2 bg-current opacity-20" aria-hidden="true"></div>
        
        <div class="flex items-start pl-3">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 {{ $iconClasses }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    {!! $icon !!}
                </svg>
            </div>
            <div class="ml-3 w-0 flex-1 pt-0.5">
                <p class="text-sm font-bold uppercase tracking-wider font-headline">
                    {{ $message ?: $slot }}
                </p>
            </div>
            <div class="ml-4 flex flex-shrink-0">
                <!-- Tombol Dismiss Alpine -->
                <button type="button" @click="show = false" class="inline-flex rounded-none focus:outline-none focus:ring-2 focus:ring-secondary focus:ring-offset-2 hover:opacity-70 transition-opacity" aria-label="Tutup notifikasi">
                    <span class="sr-only">Tutup</span>
                    <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
