<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Galeri Hasil - CEEKLIN</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral font-body text-gray-900 antialiased min-h-screen flex flex-col overflow-x-hidden">
    {{-- Grain Texture Overlay --}}
    <div class="grain-overlay"></div>

    <x-layouts.navbar>
        <x-slot:links>
            <a href="{{ route('home') }}" class="px-3 py-2 text-[10px] font-bold uppercase tracking-widest text-slate-600 hover:text-primary transition-all duration-300 hover:-translate-y-0.5">BERANDA</a>
            <a href="{{ route('specs') }}" class="px-3 py-2 text-[10px] font-bold uppercase tracking-widest text-slate-600 hover:text-primary transition-all duration-300 hover:-translate-y-0.5">SPEK PRODUK</a>
            <a href="{{ route('gallery') }}" class="px-3 py-2 text-[10px] font-bold uppercase tracking-widest text-primary border-b-2 border-primary transition-all duration-300 hover:-translate-y-0.5">GALERI</a>
            <a href="{{ route('contact') }}" class="px-3 py-2 text-[10px] font-bold uppercase tracking-widest text-slate-600 hover:text-primary transition-all duration-300 hover:-translate-y-0.5">KONTAK</a>
        </x-slot:links>
        <a href="/login" class="w-full md:w-auto font-headline font-bold text-[10px] uppercase tracking-widest bg-primary text-white border-[3px] border-primary px-8 py-2.5 hover:bg-transparent hover:text-primary transition-all duration-300 text-center">MASUK</a>
    </x-layouts.navbar>

    <main class="flex-grow">
        {{-- Section Title --}}
        <section class="max-w-7xl mx-auto px-8 pt-16 pb-8">
            <h1 class="font-headline font-black text-5xl md:text-7xl text-gray-900 uppercase tracking-tighter leading-none mb-4">
                BUKTI NYATA<br><span class="text-primary italic">HASIL LAPANGAN.</span>
            </h1>
            <div class="flex flex-col md:flex-row md:items-center gap-4 border-l-4 border-primary pl-4">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-[0.4em]">Dokumentasi Penggunaan CeeKlin</p>
                <span class="hidden md:inline text-slate-300">|</span>
                <p class="text-[10px] md:text-xs font-black text-primary uppercase tracking-widest animate-pulse">💡 Tips: Ketuk gambar untuk melihat keajaiban CeeKlin</p>
            </div>
        </section>

        {{-- Bento Gallery Grid --}}
        <section class="max-w-7xl mx-auto px-8 py-12 mb-20">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 auto-rows-[300px]">
                
                {{-- Item 1: Tall (Kloset) --}}
                <div x-data="{ active: false }" @click="active = !active" 
                    class="md:row-span-2 bg-gray-900 border-4 border-gray-900 overflow-hidden relative group cursor-crosshair">
                    
                    {{-- AFTER IMAGE (Clean) --}}
                    <img src="https://picsum.photos/seed/clean1/400/800" class="w-full h-full object-cover">
                    
                    {{-- BEFORE IMAGE (Dirty - Overlays Clean) --}}
                    <div class="absolute inset-0 transition-opacity duration-700" :class="active ? 'opacity-0' : 'opacity-100 group-hover:opacity-0'">
                        <img src="https://picsum.photos/seed/dirty1/400/800" class="w-full h-full object-cover">
                        {{-- Optional: Darken the dirty one slightly to make it feel 'grimy' --}}
                        <div class="absolute inset-0 bg-black/20"></div>
                    </div>

                    <!-- Interaction Hint -->
                    <div x-show="!active" class="absolute inset-0 flex items-center justify-center z-10 pointer-events-none md:hidden">
                        <span class="bg-white/90 text-gray-900 text-[10px] font-black px-4 py-2 rounded-full shadow-xl border-2 border-gray-900 animate-bounce">TAP TO CLEAN 👆</span>
                    </div>
                    <!-- Labels -->
                    <div class="absolute top-6 left-6 z-20">
                        <span class="bg-black/80 text-white text-[10px] font-bold px-3 py-1 uppercase tracking-widest" :class="active ? 'hidden' : 'group-hover:hidden'">BEFORE</span>
                        <span class="bg-primary text-white text-[10px] font-bold px-3 py-1 uppercase tracking-widest hidden" :class="active ? 'inline-block' : 'group-hover:inline-block'">CLEANED (2 MIN)</span>
                    </div>
                    <div class="absolute bottom-6 left-6 right-6 z-20">
                        <p class="font-headline font-bold text-white text-2xl uppercase leading-tight drop-shadow-lg">Kerak Kloset Menahun</p>
                        <p class="text-[8px] text-white/50 uppercase mt-2 md:hidden">Tap to Clean</p>
                    </div>
                </div>

                {{-- Item 2: Wide (Blok Mesin) --}}
                <div x-data="{ active: false }" @click="active = !active"
                    class="md:col-span-2 bg-gray-900 border-4 border-gray-900 overflow-hidden relative group cursor-crosshair">
                    
                    {{-- AFTER --}}
                    <img src="https://picsum.photos/seed/clean2/800/400" class="w-full h-full object-cover transition-transform duration-700" :class="active ? 'scale-105' : 'scale-100'">
                    
                    {{-- BEFORE --}}
                    <div class="absolute inset-0 transition-opacity duration-700" :class="active ? 'opacity-0' : 'opacity-100 group-hover:opacity-0'">
                        <img src="https://picsum.photos/seed/dirty2/800/400" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/10"></div>
                    </div>

                    <!-- Interaction Hint -->
                    <div x-show="!active" class="absolute inset-0 flex items-center justify-center z-10 pointer-events-none md:hidden">
                        <span class="bg-white/90 text-gray-900 text-[10px] font-black px-4 py-2 rounded-full shadow-xl border-2 border-gray-900 animate-bounce text-center">TAP TO RESTORE 👆</span>
                    </div>
                    <div class="absolute top-6 left-6 z-20">
                        <span class="bg-black/80 text-white text-[10px] font-bold px-3 py-1 uppercase tracking-widest" :class="active ? 'hidden' : 'group-hover:hidden'">DIRTY BLOCK</span>
                        <span class="bg-secondary text-gray-900 text-[10px] font-black px-3 py-1 uppercase tracking-widest hidden" :class="active ? 'inline-block' : 'group-hover:inline-block'">RESTORED</span>
                    </div>
                    <div class="absolute bottom-6 left-6 z-20">
                        <p class="font-headline font-bold text-white text-3xl uppercase tracking-tighter">Blok Mesin & Karat Oli</p>
                    </div>
                </div>

                {{-- Item 3: Standard (Gelas) --}}
                <div x-data="{ active: false }" @click="active = !active"
                    class="bg-white border-4 border-gray-900 overflow-hidden relative group cursor-crosshair">
                    
                    {{-- AFTER --}}
                    <img src="https://picsum.photos/seed/clean3/400/400" class="w-full h-full object-cover">
                    
                    {{-- BEFORE --}}
                    <div class="absolute inset-0 transition-opacity duration-500" :class="active ? 'opacity-0' : 'opacity-100 group-hover:opacity-0'">
                        <img src="https://picsum.photos/seed/dirty3/400/400" class="w-full h-full object-cover grayscale blur-[1px]">
                    </div>

                    <!-- Interaction Hint -->
                    <div x-show="!active" class="absolute inset-0 flex items-center justify-center z-10 pointer-events-none md:hidden">
                        <span class="bg-white/90 text-gray-900 text-[8px] font-black px-3 py-1.5 rounded-full shadow-md border border-gray-900 animate-pulse">TAP 👆</span>
                    </div>
                    <div class="absolute top-4 left-4 z-20">
                        <span class="bg-gray-900 text-white text-[8px] font-bold px-2 py-1 uppercase transition-colors" :class="active ? 'bg-primary' : 'group-hover:bg-primary'">GLASSWARE</span>
                    </div>
                    <div class="absolute bottom-4 left-4 right-4 z-20">
                        <p class="font-headline font-bold text-gray-900 text-lg uppercase leading-none transition-colors" :class="active ? 'text-primary' : 'group-hover:text-primary'">Noda Gelas Kusam</p>
                    </div>
                </div>

                {{-- Item 4: Standard (Wastafel) --}}
                <div x-data="{ active: false }" @click="active = !active"
                    class="bg-gray-900 border-4 border-gray-900 overflow-hidden relative group cursor-crosshair">
                    
                    {{-- AFTER --}}
                    <img src="https://picsum.photos/seed/clean4/400/400" class="w-full h-full object-cover">
                    
                    {{-- BEFORE --}}
                    <div class="absolute inset-0 transition-opacity duration-500" :class="active ? 'opacity-0' : 'opacity-100 group-hover:opacity-0'">
                        <img src="https://picsum.photos/seed/dirty4/400/400" class="w-full h-full object-cover sepia-[0.3]">
                    </div>

                    <!-- Interaction Hint -->
                    <div x-show="!active" class="absolute inset-0 flex items-center justify-center z-10 pointer-events-none md:hidden">
                        <span class="bg-white/90 text-gray-900 text-[8px] font-black px-3 py-1.5 rounded-full shadow-md border border-gray-900 animate-pulse">CLEAN ME 👆</span>
                    </div>
                    <div class="absolute bottom-4 left-4 z-20">
                        <p class="font-headline font-bold text-white text-lg uppercase transition-colors" :class="active ? 'text-secondary' : 'group-hover:text-secondary'">Wastafel Keramik</p>
                    </div>
                </div>

                {{-- Item 5: Wide (Pakaian) --}}
                <div x-data="{ active: false }" @click="active = !active"
                    class="md:col-span-2 bg-gray-darkest border-4 border-gray-900 overflow-hidden relative group cursor-crosshair">
                    
                    {{-- AFTER --}}
                    <img src="https://picsum.photos/seed/clean5/800/400" class="w-full h-full object-cover">
                    
                    {{-- BEFORE --}}
                    <div class="absolute inset-0 transition-opacity duration-700" :class="active ? 'opacity-0' : 'opacity-100 group-hover:opacity-0'">
                        <img src="https://picsum.photos/seed/dirty5/800/400" class="w-full h-full object-cover saturate-[0.8] contrast-[1.1]">
                    </div>

                    <!-- Interaction Hint -->
                    <div x-show="!active" class="absolute inset-0 flex items-center justify-center z-10 pointer-events-none md:hidden">
                        <span class="bg-primary/90 text-white text-[10px] font-black px-4 py-2 rounded-full shadow-xl border-2 border-white animate-bounce">TAP TO ERASE STAIN 👆</span>
                    </div>
                    <div class="absolute top-6 left-6 z-20">
                        <span class="bg-primary text-white text-[10px] font-bold px-3 py-1 uppercase tracking-widest transition-colors" :class="active ? 'bg-white text-primary' : 'group-hover:bg-white group-hover:text-primary'">FABRIC CARE</span>
                    </div>
                    <div class="absolute bottom-6 left-6 z-20">
                        <p class="font-headline font-bold text-white text-3xl uppercase tracking-tighter">Noda Jamur Pakaian</p>
                    </div>
                </div>

                {{-- Item 6: Standard (Logam) --}}
                <div x-data="{ active: false }" @click="active = !active"
                    class="bg-secondary border-4 border-gray-900 overflow-hidden relative group cursor-crosshair">
                    <img src="/images/hero-bottle.jpeg" class="w-full h-full object-cover transition-all duration-500"
                        :class="active ? 'opacity-100 mix-blend-normal' : 'mix-blend-multiply opacity-40 group-hover:opacity-100 group-hover:mix-blend-normal'">
                    <div class="absolute inset-0 flex items-center justify-center p-4 text-center">
                        <p class="font-headline font-black text-gray-900 text-xl uppercase leading-none transition-opacity" :class="active ? 'opacity-0' : 'opacity-100 group-hover:opacity-0'">TAP TO CLEAN LOGAM</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <x-layouts.footer variant="simple" />
</body>
</html>
