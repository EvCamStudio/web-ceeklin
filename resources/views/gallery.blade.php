<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Galeri Hasil - CEEKLIN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral font-body text-gray-900 antialiased min-h-screen flex flex-col overflow-x-hidden">
    {{-- Grain Texture Overlay --}}
    <div class="grain-overlay"></div>

    <x-layouts.navbar>
        <x-slot:links>
            <a href="{{ route('home') }}" class="text-[10px] font-bold uppercase tracking-widest text-slate-600 hover:text-primary transition-all duration-300 hover:-translate-y-0.5 pb-1">BERANDA</a>
            <a href="{{ route('specs') }}" class="text-[10px] font-bold uppercase tracking-widest text-slate-600 hover:text-primary transition-all duration-300 hover:-translate-y-0.5 pb-1">SPEK PRODUK</a>
            <a href="{{ route('gallery') }}" class="text-[10px] font-bold uppercase tracking-widest text-primary border-b-2 border-primary pb-1 transition-all duration-300 hover:-translate-y-0.5">GALERI</a>
            <a href="{{ route('contact') }}" class="text-[10px] font-bold uppercase tracking-widest text-slate-600 hover:text-primary transition-all duration-300 hover:-translate-y-0.5 pb-1">KONTAK</a>
        </x-slot:links>
        <a href="/login" class="hidden md:inline-flex font-headline font-bold text-[10px] uppercase tracking-widest bg-primary text-white border-[3px] border-primary px-8 py-2.5 hover:bg-transparent hover:text-primary transition-all duration-300">PORTAL LOGIN</a>
    </x-layouts.navbar>

    <main class="flex-grow">
        {{-- Section Title --}}
        <section class="max-w-7xl mx-auto px-8 pt-16 pb-8">
            <h1 class="font-headline font-black text-6xl text-gray-900 uppercase tracking-tighter leading-none mb-4">
                BUKTI NYATA<br><span class="text-primary">HASIL LAPANGAN.</span>
            </h1>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-[0.3em] border-l-4 border-primary pl-4">Dokumentasi Penggunaan CeeKlin pada Berbagai Permukaan</p>
        </section>

        {{-- Gallery Grid --}}
        <section class="max-w-7xl mx-auto px-8 py-12 mb-20">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-1">
                {{-- Item 1 --}}
                <div class="group relative aspect-square bg-gray-900 border-4 border-gray-900 overflow-hidden">
                    <img src="/images/hero-bottle.jpeg" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500">
                    <div class="absolute inset-0 bg-primary/80 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity p-8 text-center">
                        <p class="font-headline font-bold text-white text-xl uppercase">Kerak Kloset Menahun (2 Menit)</p>
                    </div>
                </div>
                {{-- Item 2 --}}
                <div class="group relative aspect-square bg-gray-900 border-4 border-gray-900 overflow-hidden">
                    <img src="/images/hero-bottle.jpeg" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500">
                    <div class="absolute inset-0 bg-secondary/90 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity p-8 text-center text-gray-900">
                        <p class="font-headline font-bold text-xl uppercase">Blok Mesin Berkarat & Oli</p>
                    </div>
                </div>
                {{-- Item 3 --}}
                <div class="group relative aspect-square bg-gray-900 border-4 border-gray-900 overflow-hidden">
                    <img src="/images/hero-bottle.jpeg" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500">
                    <div class="absolute inset-0 bg-primary/80 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity p-8 text-center">
                        <p class="font-headline font-bold text-white text-xl uppercase">Pembersihan Noda Gelas Kaca</p>
                    </div>
                </div>
                {{-- Item 4 --}}
                <div class="group relative aspect-square bg-gray-900 border-4 border-gray-900 overflow-hidden">
                    <img src="/images/hero-bottle.jpeg" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500">
                    <div class="absolute inset-0 bg-secondary/90 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity p-8 text-center text-gray-900">
                        <p class="font-headline font-bold text-xl uppercase">Wastafel Keramik Putih</p>
                    </div>
                </div>
                {{-- Item 5 --}}
                <div class="group relative aspect-square bg-gray-900 border-4 border-gray-900 overflow-hidden">
                    <img src="/images/hero-bottle.jpeg" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500">
                    <div class="absolute inset-0 bg-primary/80 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity p-8 text-center">
                        <p class="font-headline font-bold text-white text-xl uppercase">Noda Karat pada Logam</p>
                    </div>
                </div>
                {{-- Item 6 --}}
                <div class="group relative aspect-square bg-gray-900 border-4 border-gray-900 overflow-hidden">
                    <img src="/images/hero-bottle.jpeg" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500">
                    <div class="absolute inset-0 bg-secondary/90 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity p-8 text-center text-gray-900">
                        <p class="font-headline font-bold text-xl uppercase">Kerak pada Mesin Cuci</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <x-layouts.footer variant="simple" />
</body>
</html>
