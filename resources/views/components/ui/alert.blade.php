@props(['type' => 'info', 'title' => null, 'message' => null])

@php
    $baseClasses = 'relative border-[4px] border-gray-900 p-6 shadow-[8px_8px_0_rgba(0,0,0,0.05)] overflow-hidden';
    
    $types = [
        'success' => [
            'bg' => 'bg-green-50',
            'border' => 'border-green-600',
            'text' => 'text-green-800',
            'accent' => 'bg-green-600',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'
        ],
        'error' => [
            'bg' => 'bg-red-50',
            'border' => 'border-red-600',
            'text' => 'text-red-800',
            'accent' => 'bg-red-600',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />'
        ],
        'warning' => [
            'bg' => 'bg-yellow-50',
            'border' => 'border-yellow-600',
            'text' => 'text-yellow-800',
            'accent' => 'bg-yellow-600',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />'
        ],
        'info' => [
            'bg' => 'bg-blue-50',
            'border' => 'border-blue-600',
            'text' => 'text-blue-800',
            'accent' => 'bg-blue-600',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />'
        ],
    ];

    $style = $types[$type] ?? $types['info'];
@endphp

<div {{ $attributes->merge(['class' => $baseClasses . ' ' . $style['bg']]) }}>
    {{-- Side Accent Line --}}
    <div class="absolute left-0 top-0 bottom-0 w-2 {{ $style['accent'] }}"></div>
    
    <div class="flex items-start gap-5">
        <div class="flex-shrink-0 mt-1">
            <div class="w-10 h-10 border-2 border-gray-900 flex items-center justify-center {{ $style['accent'] }} text-white shadow-[3px_3px_0_var(--color-gray-900)]">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                    {!! $style['icon'] !!}
                </svg>
            </div>
        </div>
        
        <div class="flex-1">
            @if($title)
                <h4 class="font-headline font-black text-sm uppercase tracking-widest leading-none mb-2 {{ $style['text'] }}">
                    {{ $title }}
                </h4>
            @endif
            <div class="text-[11px] font-bold uppercase tracking-widest leading-relaxed {{ $style['text'] }} opacity-80">
                {{ $message ?: $slot }}
            </div>
        </div>
    </div>
</div>
