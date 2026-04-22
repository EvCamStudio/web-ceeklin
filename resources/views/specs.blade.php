<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Spesifikasi Produk - CEEKLIN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral font-body text-gray-900 antialiased min-h-screen flex flex-col overflow-x-hidden">
    {{-- Grain Texture Overlay --}}
    <div class="grain-overlay"></div>

    <x-layouts.navbar>
        <x-slot:links>
            <a href="{{ route('home') }}" class="text-[10px] font-bold uppercase tracking-widest text-slate-600 hover:text-primary transition-all duration-300 hover:-translate-y-0.5 pb-1">BERANDA</a>
            <a href="{{ route('specs') }}" class="text-[10px] font-bold uppercase tracking-widest text-primary border-b-2 border-primary pb-1 transition-all duration-300 hover:-translate-y-0.5">SPEK PRODUK</a>
            <a href="{{ route('gallery') }}" class="text-[10px] font-bold uppercase tracking-widest text-slate-600 hover:text-primary transition-all duration-300 hover:-translate-y-0.5 pb-1">GALERI</a>
            <a href="{{ route('contact') }}" class="text-[10px] font-bold uppercase tracking-widest text-slate-600 hover:text-primary transition-all duration-300 hover:-translate-y-0.5 pb-1">KONTAK</a>
        </x-slot:links>
        <a href="/login" class="hidden md:inline-flex font-headline font-bold text-[10px] uppercase tracking-widest bg-primary text-white border-[3px] border-primary px-8 py-2.5 hover:bg-transparent hover:text-primary transition-all duration-300">PORTAL LOGIN</a>
    </x-layouts.navbar>

    <main class="flex-grow">
        {{-- Hero Specs --}}
        <section class="max-w-7xl mx-auto px-8 py-16 border-b-4 border-gray-900">
            <div class="flex flex-col md:flex-row gap-16 items-center">
                <div class="w-full md:w-1/2">
                    <span class="inline-block bg-secondary text-gray-900 font-headline font-bold text-[10px] px-4 py-1.5 uppercase tracking-[0.2em] mb-6">Technical Datasheet</span>
                    <h1 class="font-headline font-black text-5xl lg:text-7xl text-primary uppercase leading-none mb-8 tracking-tighter">
                        KOMPOSISI &<br>KEKUATAN.
                    </h1>
                    <p class="text-lg font-bold text-slate-700 leading-relaxed max-w-lg">
                        CeeKlin bukan sabun pembersih biasa. Ini adalah konsentrat agresif yang dirancang untuk menghancurkan ikatan mineral dan oksidasi pada permukaan porselen dan logam.
                    </p>
                </div>
                <div class="w-full md:w-1/2 relative">
                    <div class="absolute inset-0 bg-primary/10 -rotate-3 scale-110"></div>
                    <img src="/images/hero-bottle.jpeg" alt="CeeKlin Bottle" class="relative z-10 w-full h-auto border-4 border-gray-900 shadow-[15px_15px_0_var(--color-primary)]">
                </div>
            </div>
        </section>

        {{-- Specs Grid --}}
        <section class="max-w-7xl mx-auto px-8 py-20">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Bahan Aktif --}}
                <x-ui.card class="border-t-[8px] border-primary">
                    <h3 class="font-headline font-bold text-xl text-primary uppercase mb-4">Bahan Aktif</h3>
                    <ul class="flex flex-col gap-4">
                        <li class="flex justify-between border-b border-neutral-border pb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Kandungan Utama</span>
                            <span class="text-sm font-bold">Asam Klorida (HCl)</span>
                        </li>
                        <li class="flex justify-between border-b border-neutral-border pb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Konsentrasi</span>
                            <span class="text-sm font-bold">Super High-Active</span>
                        </li>
                        <li class="flex justify-between border-b border-neutral-border pb-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Surfactant</span>
                            <span class="text-sm font-bold">Industrial Grade</span>
                        </li>
                    </ul>
                </x-ui.card>

                {{-- Cara Pakai --}}
                <x-ui.card class="border-t-[8px] border-secondary">
                    <h3 class="font-headline font-bold text-xl text-gray-900 uppercase mb-4">Cara Pemakaian</h3>
                    <div class="flex flex-col gap-4">
                        <div class="flex gap-4">
                            <span class="font-headline font-black text-2xl text-secondary">01</span>
                            <p class="text-xs font-bold text-slate-600 uppercase leading-relaxed">Oleskan cairan pada permukaan yang berkerak (Gunakan kuas).</p>
                        </div>
                        <div class="flex gap-4">
                            <span class="font-headline font-black text-2xl text-secondary">02</span>
                            <p class="text-xs font-bold text-slate-600 uppercase leading-relaxed">Diamkan selama 2-5 menit agar formula bereaksi.</p>
                        </div>
                        <div class="flex gap-4">
                            <span class="font-headline font-black text-2xl text-secondary">03</span>
                            <p class="text-xs font-bold text-slate-600 uppercase leading-relaxed">Bilas dengan air bersih hingga tuntas (Tanpa gosok keras).</p>
                        </div>
                    </div>
                </x-ui.card>

                {{-- Peringatan Keamanan --}}
                <x-ui.card class="border-t-[8px] border-gray-900 bg-gray-darkest text-white">
                    <h3 class="font-headline font-bold text-xl text-primary uppercase mb-4">Keamanan & APD</h3>
                    <div class="flex flex-col gap-4 opacity-90">
                        <p class="text-xs font-bold uppercase tracking-widest text-secondary mb-2">⚠️ WAJIB DIPERHATIKAN:</p>
                        <ul class="text-[10px] font-bold list-disc pl-4 flex flex-col gap-2">
                            <li>Wajib menggunakan sarung tangan karet saat pemakaian.</li>
                            <li>Hindari kontak langsung dengan mata dan kulit.</li>
                            <li>Jauhkan dari jangkauan anak-anak.</li>
                            <li>Jangan dicampur dengan bahan kimia lain.</li>
                        </ul>
                    </div>
                </x-ui.card>
            </div>
        </section>
    </main>

    <x-layouts.footer variant="simple" />
</body>
</html>
