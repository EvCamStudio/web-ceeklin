@props(['count' => 0, 'type' => 'secondary'])

@php
    $bgClass = match($type) {
        'primary' => 'bg-primary text-white',
        'secondary' => 'bg-secondary text-white',
        'warning' => 'bg-yellow-400 text-yellow-900',
        'danger' => 'bg-red-600 text-white border-red-900',
        default => 'bg-secondary text-white'
    };
@endphp

@if($count > 0)
<span {{ $attributes->merge(['class' => "inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-[9px] font-black uppercase tracking-tighter border-2 border-gray-900 shadow-[2px_2px_0_var(--color-gray-900)] $bgClass"]) }}>
    {{ $count > 99 ? '99+' : $count }}
</span>
@endif
