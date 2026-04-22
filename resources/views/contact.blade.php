<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kontak Kami - CEEKLIN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral font-body text-gray-900 antialiased min-h-screen flex flex-col overflow-x-hidden">
    {{-- Grain Texture Overlay --}}
    <div class="grain-overlay"></div>

    <x-layouts.navbar>
        <x-slot:links>
            <a href="{{ route('home') }}" class="text-[10px] font-bold uppercase tracking-widest text-slate-600 hover:text-primary transition-all duration-300 hover:-translate-y-0.5 pb-1">BERANDA</a>
            <a href="{{ route('specs') }}" class="text-[10px] font-bold uppercase tracking-widest text-slate-600 hover:text-primary transition-all duration-300 hover:-translate-y-0.5 pb-1">SPEK PRODUK</a>
            <a href="{{ route('gallery') }}" class="text-[10px] font-bold uppercase tracking-widest text-slate-600 hover:text-primary transition-all duration-300 hover:-translate-y-0.5 pb-1">GALERI</a>
            <a href="{{ route('contact') }}" class="text-[10px] font-bold uppercase tracking-widest text-primary border-b-2 border-primary pb-1 transition-all duration-300 hover:-translate-y-0.5">KONTAK</a>
        </x-slot:links>
        <a href="/login" class="hidden md:inline-flex font-headline font-bold text-[10px] uppercase tracking-widest bg-primary text-white border-[3px] border-primary px-8 py-2.5 hover:bg-transparent hover:text-primary transition-all duration-300">PORTAL LOGIN</a>
    </x-layouts.navbar>

    <main class="flex-grow">
        <section class="max-w-7xl mx-auto px-8 py-20 flex flex-col lg:flex-row gap-20">
            {{-- Info Kontak --}}
            <div class="w-full lg:w-1/2">
                <h1 class="font-headline font-black text-6xl text-primary uppercase leading-none mb-10 tracking-tighter">
                    HUBUNGI<br>ADMIN PUSAT.
                </h1>
                
                <div class="space-y-12">
                    <div>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3">Kantor Pusat / Pabrik</p>
                        <p class="text-xl font-bold text-gray-900 leading-tight">Kawasan Industri CeeKlin, Blok B-4<br>Jakarta, Indonesia</p>
                    </div>

                    <div>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3">Layanan Kemitraan (WhatsApp)</p>
                        <a href="#" class="text-3xl font-headline font-black text-primary hover:text-primary-hover transition-colors tracking-tight">0812-3456-7890</a>
                    </div>

                    <div>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3">Email Support</p>
                        <a href="mailto:support@ceeklin.com" class="text-xl font-bold text-gray-900 border-b-2 border-gray-900 hover:text-primary hover:border-primary transition-all pb-1">support@ceeklin.com</a>
                    </div>
                </div>
            </div>

            {{-- Form Pesan --}}
            <div class="w-full lg:w-1/2">
                <div class="bg-white border-4 border-gray-900 p-10 shadow-[15px_15px_0_var(--color-primary)]">
                    <h3 class="font-headline font-bold text-2xl uppercase text-gray-900 mb-8">Kirim Pesan Langsung</h3>
                    <form action="#" method="POST" class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Nama Lengkap</label>
                            <input type="text" class="w-full bg-neutral border-2 border-neutral-border p-4 focus:outline-none focus:border-primary font-bold text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Subjek Inquiry</label>
                            <select class="w-full bg-neutral border-2 border-neutral-border p-4 focus:outline-none focus:border-primary font-bold text-sm appearance-none">
                                <option>Informasi Kemitraan</option>
                                <option>Keluhan Produk</option>
                                <option>Kerjasama Distribusi</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Detail Pesan</label>
                            <textarea rows="5" class="w-full bg-neutral border-2 border-neutral-border p-4 focus:outline-none focus:border-primary font-bold text-sm"></textarea>
                        </div>
                        <x-ui.button type="button" fullWidth="true" class="py-5 text-base">KIRIM PESAN</x-ui.button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <x-layouts.footer variant="simple" />
</body>
</html>
