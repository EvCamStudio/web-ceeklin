<nav class="w-full h-20 bg-neutral border-b-2 border-neutral-border flex justify-between items-center px-8 md:px-12 z-50 sticky top-0">
    <a href="/" class="font-headline font-bold text-3xl text-primary tracking-tighter hover:opacity-80 transition-opacity">CEEKLIN</a>
    
    @if(isset($links))
        <div class="hidden md:flex items-center gap-10 mt-1">
            {{ $links }}
        </div>
    @endif
    
    <div class="flex items-center justify-end">
        {{ $slot }}
    </div>
</nav>
