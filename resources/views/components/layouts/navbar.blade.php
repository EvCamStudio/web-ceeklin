<nav x-data="{ open: false }" class="w-full h-20 bg-neutral border-b-2 border-neutral-border flex justify-between items-center px-6 md:px-12 z-50 sticky top-0">
    {{-- Logo Area --}}
    <div class="flex items-center min-w-[200px]">
        <a href="/" class="font-headline font-black text-2xl md:text-3xl text-primary tracking-tighter hover:opacity-80 transition-opacity">
            CEEKLIN
        </a>
    </div>
    
    {{-- Desktop Links --}}
    @if(isset($links))
        <div class="hidden md:flex items-center gap-10 mt-1">
            {{ $links }}
        </div>
    @endif
    
    {{-- Right Actions (Desktop) --}}
    <div class="hidden md:flex items-center justify-end min-w-[200px]">
        {{ $slot }}
    </div>

    {{-- Mobile Hamburger Button --}}
    <div class="flex md:hidden items-center gap-4">
        <button @click="open = !open" class="flex items-center gap-2 bg-primary text-white border-2 border-gray-900 px-3 py-1.5 shadow-[4px_4px_0_var(--color-gray-900)] active:shadow-none active:translate-x-[2px] active:translate-y-[2px] transition-all">
            <span class="font-headline font-bold text-[10px] uppercase tracking-widest" x-text="open ? 'TUTUP' : 'MENU'">MENU</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
                <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Mobile Menu Overlay --}}
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-full"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-full"
         class="fixed inset-0 bg-neutral z-[60] flex flex-col p-6"
         style="display: none;">
        
        {{-- Header Menu Mobile --}}
        <div class="flex justify-between items-center mb-12 border-b-4 border-gray-900 pb-6">
            <span class="font-headline font-black text-3xl text-primary tracking-tighter">CEEKLIN</span>
            <button @click="open = false" class="bg-gray-900 text-white p-2 border-2 border-gray-900 shadow-[4px_4px_0_var(--color-primary)]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Mobile Links --}}
        <div class="flex flex-col gap-6">
            @if(isset($links))
                <div class="flex flex-col gap-8 text-center" @click="open = false">
                    {{ $links }}
                </div>
            @endif
            
            <div class="pt-8 border-t-2 border-dashed border-gray-400 flex flex-col gap-4">
                {{ $slot }}
            </div>
        </div>

        {{-- Mobile Footer --}}
        <div class="mt-auto border-t-4 border-gray-900 pt-6">
            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-[0.3em]">Industrial Cleansing Expert</p>
            <p class="text-[8px] font-bold text-primary uppercase tracking-widest mt-1">© 2024 CEEKLIN OFFICIAL</p>
        </div>
    </div>
</nav>
