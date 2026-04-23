<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CEEKLIN - Jagonya Pembasmi Noda & Kerak Super</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-neutral font-body text-gray-900 antialiased min-h-screen flex flex-col overflow-x-hidden">
    {{-- 1. Grain Texture Overlay --}}
    <div class="grain-overlay"></div>

    <!-- NAVBAR (Light) -->
    <x-layouts.navbar>
        <x-slot:links>
            <a href="{{ route('home') }}"
                class="px-3 py-2 text-[10px] font-bold uppercase tracking-widest {{ Request::routeIs('home') ? 'text-primary border-b-2 border-primary' : 'text-slate-600' }} hover:text-primary transition-all duration-300 hover:-translate-y-0.5">BERANDA</a>
            <a href="{{ route('specs') }}"
                class="px-3 py-2 text-[10px] font-bold uppercase tracking-widest {{ Request::routeIs('specs') ? 'text-primary border-b-2 border-primary' : 'text-slate-600' }} hover:text-primary transition-all duration-300 hover:-translate-y-0.5">SPEK PRODUK</a>
            <a href="{{ route('gallery') }}"
                class="px-3 py-2 text-[10px] font-bold uppercase tracking-widest {{ Request::routeIs('gallery') ? 'text-primary border-b-2 border-primary' : 'text-slate-600' }} hover:text-primary transition-all duration-300 hover:-translate-y-0.5">GALERI</a>
            <a href="{{ route('contact') }}"
                class="px-3 py-2 text-[10px] font-bold uppercase tracking-widest {{ Request::routeIs('contact') ? 'text-primary border-b-2 border-primary' : 'text-slate-600' }} hover:text-primary transition-all duration-300 hover:-translate-y-0.5">KONTAK</a>
        </x-slot:links>

        <a href="/login"
            class="w-full md:w-auto font-headline font-bold text-[10px] uppercase tracking-widest bg-primary text-white border-[3px] border-primary px-8 py-2.5 hover:bg-transparent hover:text-primary transition-all duration-300 text-center">PORTAL
            LOGIN</a>
    </x-layouts.navbar>

    <main class="flex-grow">
        <!-- 1. HERO SECTION (Extreme Contrast Text) -->
        <section
            class="max-w-[1400px] mx-auto px-8 lg:px-12 py-10 lg:py-14 flex flex-col lg:flex-row items-center gap-10 lg:gap-14 relative">
            <div class="w-full lg:w-[55%] flex flex-col items-start z-10">
                <h1
                    class="font-headline font-black leading-[0.85] text-primary uppercase text-4xl md:text-6xl lg:text-[5rem] tracking-tighter">
                    KERAK<br>
                    BERAKHIR<br>
                    <span class="text-gray-900">DI SINI.</span>
                </h1>

                <div class="mt-8 border-l-[4px] border-secondary pl-6 bg-neutral-dark/40 py-2">
                    <p class="text-lg text-slate-800 font-bold max-w-lg leading-relaxed">
                        Formulasi cairan super yang meluluhkan kerak porselen, noda keramik menahun, hingga kerak mesin dalam hitungan detik. Tanpa sikat keras. Tanpa ampun.
                    </p>
                </div>

                <div class="mt-10 flex flex-col sm:flex-row items-center font-bold gap-6 w-full sm:w-auto">
                    <x-ui.button
                        onclick="window.location.href='/register'"
                        class="w-full sm:w-auto px-10 py-5 text-base border-[3px] border-primary shadow-[6px_6px_0_var(--color-primary-hover)]">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            fill="currentColor" class="w-5 h-5 mr-3">
                            <path fill-rule="evenodd"
                                d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z"
                                clip-rule="evenodd" />
                        </svg>
                        BERMITRA SEKARANG
                    </x-ui.button>

                    <a href="#bukti"
                        class="w-full sm:w-auto flex justify-center items-center text-gray-900 hover:text-primary bg-neutral px-8 py-5 border-[3px] border-gray-900 hover:border-primary transition-colors uppercase tracking-widest text-sm shadow-[6px_6px_0_var(--color-gray-900)]">
                        LIHAT BUKTI VISUAL
                    </a>
                </div>
            </div>

            <div class="w-full lg:w-[45%] relative mt-10 lg:mt-0">
                <!-- Aksen Pita Latar Belakang & Base Frame -->
                <div class="absolute inset-0 bg-primary translate-x-6 translate-y-6 z-0" aria-hidden="true"></div>



                <!-- Gambar Hero Adaptive (Slider Ready) -->
                <div
                    class="relative z-20 bg-neutral-hover aspect-[4/5] overflow-hidden border-4 border-gray-900 group w-full">

                    <!-- Background Pattern -->
                    <div
                        class="absolute inset-0 bg-[radial-gradient(var(--color-neutral-border)_1px,transparent_1px)] [background-size:16px_16px] opacity-40 z-0">
                    </div>

                    <!-- SLIDER CONTAINER TRACK -->
                    <div 
                        x-data="{ 
                            currentSlide: 0, 
                            totalSlides: 2,
                            touchStartX: 0,
                            touchEndX: 0,
                            next() { this.currentSlide = (this.currentSlide + 1) % this.totalSlides },
                            prev() { this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides },
                            handleSwipe() {
                                if (this.touchStartX - this.touchEndX > 50) this.next();
                                if (this.touchEndX - this.touchStartX > 50) this.prev();
                            }
                        }" 
                        @touchstart="touchStartX = $event.touches[0].clientX"
                        @touchend="touchEndX = $event.changedTouches[0].clientX; handleSwipe()"
                        class="absolute inset-0 z-10 w-full h-full group/slider"
                    >
                        <div class="absolute inset-0 flex transition-transform duration-700 ease-out"
                            :style="`transform: translateX(-${currentSlide * 100}%);`">
                            <!-- Slide 1 -->
                            <img src="/images/hero-bottle.jpeg" alt="Botol Ceeklin 1"
                                class="w-full h-full object-cover mix-blend-multiply flex-shrink-0">
                            <!-- Slide 2 (Dummy test slider) -->
                            <img src="/images/hero-bottle.jpeg" alt="Botol Ceeklin 2"
                                class="w-full h-full object-cover mix-blend-multiply flex-shrink-0 blur-[2px] hue-rotate-90">
                        </div>
                        
                        <!-- Large Interaction Zones (Super Clean Mode) -->
                        <div @click="prev()" 
                            class="absolute inset-y-0 left-0 w-1/4 z-20 cursor-pointer group/left flex items-center pl-4">
                            <svg class="w-8 h-8 text-white drop-shadow-[0_2px_4px_rgba(0,0,0,0.5)] opacity-40 group-hover/left:opacity-100 group-hover/left:-translate-x-1 transition-all duration-300" 
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </div>

                        <div @click="next()" 
                            class="absolute inset-y-0 right-0 w-1/4 z-20 cursor-pointer group/right flex items-center justify-end pr-4">
                            <svg class="w-8 h-8 text-white drop-shadow-[0_2px_4px_rgba(0,0,0,0.5)] opacity-40 group-hover/right:opacity-100 group-hover/right:translate-x-1 transition-all duration-300" 
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>

                        <!-- Minimalist Pagination Dots -->
                        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-20">
                            <template x-for="i in totalSlides">
                                <button @click="currentSlide = i-1" 
                                    class="h-1 rounded-full transition-all duration-500"
                                    :class="currentSlide === i-1 ? 'w-8 bg-white' : 'w-2 bg-white/30 hover:bg-white/60'"></button>
                            </template>
                        </div>

                        <!-- Overlay Shadow Gradient for better contrast -->
                        <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-gray-900/20 to-transparent pointer-events-none"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. TRUST BADGES TAPE (Ribbon Berjalan) -->
        <section
            class="w-[110%] -ml-[5%] bg-gray-900 text-secondary border-y-8 border-primary overflow-hidden py-3 -rotate-[0.8deg] my-8 relative z-20"
            style="backface-visibility: hidden; transform: translateZ(0); outline: 1px solid transparent; -webkit-font-smoothing: antialiased;">
            <div class="flex whitespace-nowrap animate-marquee">
                <div class="flex items-center gap-12 font-headline font-bold uppercase tracking-[0.2em] text-[11px] opacity-90 px-6">
                    <span class="text-primary-hover">&#9646;</span>
                    <span>Tersertifikasi KEMENKES RI PKL 20303120614</span>
                    <span class="text-primary-hover">&#9646;</span>
                    <span>ISO 9001:2015 Compliant</span>
                    <span class="text-primary-hover">&#9646;</span>
                    <span>Dipercaya 50+ Manufaktur Nasional</span>
                    <span class="text-primary-hover">&#9646;</span>
                </div>
                {{-- Duplicate for seamless loop --}}
                <div class="flex items-center gap-12 font-headline font-bold uppercase tracking-[0.2em] text-[11px] opacity-90 px-6">
                    <span class="text-primary-hover">&#9646;</span>
                    <span>Tersertifikasi KEMENKES RI PKL 20303120614</span>
                    <span class="text-primary-hover">&#9646;</span>
                    <span>ISO 9001:2015 Compliant</span>
                    <span class="text-primary-hover">&#9646;</span>
                    <span>Dipercaya 50+ Manufaktur Nasional</span>
                    <span class="text-primary-hover">&#9646;</span>
                </div>
            </div>
        </section>

        <!-- 3. BEFORE & AFTER BLOCK (Dark Theme) -->
        <section id="bukti"
            class="w-full bg-gray-darkest text-neutral-light py-16 mb-12 border-t-[8px] border-secondary-dark relative">
            <div class="max-w-[1400px] mx-auto px-8 lg:px-12">
                <div class="flex flex-col md:flex-row justify-between items-end mb-12 border-b border-gray-800 pb-6">
                    <div class="max-w-2xl">
                        <h2
                            class="font-headline font-bold text-4xl lg:text-5xl text-secondary uppercase tracking-tighter mb-4">
                            BUKTIMU DI SINI</h2>
                        <p class="text-slate-400 font-body text-base max-w-lg leading-relaxed font-bold">
                            Jangan telan janji manis. Perhatikan bagaimana 2-5 menit kontak cairan CEEKLIN meluluhkan formasi kerak menahun pada porselen dan noda membandel secara instan.
                        </p>
                    </div>
                    <div class="mt-8 md:mt-0">
                        <div
                            class="flex items-center gap-4 bg-gray-900 border border-gray-800 p-4 shadow-[10px_10px_0_var(--color-primary-darkest)]">
                            <div class="text-5xl text-primary font-headline font-bold">15s</div>
                            <div
                                class="text-[10px] text-slate-500 uppercase font-bold tracking-widest pl-4 border-l border-gray-800">
                                Kontak<br>Waktu Reaksi</div>
                        </div>
                    </div>
                </div>

                <!-- Simulasi Slider Before After (Placeholder) -->
                <div
                    class="w-full aspect-video md:aspect-[21/9] bg-gray-900 relative border-4 border-gray-800 flex items-center justify-center overflow-hidden group shadow-2xl">
                    <!-- Placeholder Abstrak -->
                    <div class="absolute inset-0 flex">
                        <!-- Before (Karat) -->
                        <div
                            class="w-1/2 bg-[#3a2015] border-r-4 border-primary flex items-center justify-center relative inner-shadow opacity-90 relative overflow-hidden">
                            <div
                                class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_center,transparent_0%,black_100%)]">
                            </div>
                             <span
                                class="absolute top-6 left-6 bg-black/60 text-white text-[10px] font-bold px-4 py-2 uppercase tracking-widest backdrop-blur-sm border border-white/10 z-10">BEFORE:
                                KERAK MENAHUN</span>
                            <p
                                class="text-white/10 font-headline text-5xl md:text-8xl font-bold -rotate-90 absolute left-[-2rem] origin-center hidden md:block">
                                DIRTY</p>
                        </div>

                        <!-- After (Baja Bersih) -->
                        <div
                            class="w-1/2 bg-[#8ca3b5] flex items-center justify-center relative inner-shadow relative overflow-hidden">
                            <div
                                class="absolute inset-0 opacity-30 bg-[linear-gradient(45deg,transparent_25%,rgba(255,255,255,0.2)_50%,transparent_75%)] bg-[length:250px_250px] animate-[pulse_3s_infinite]">
                            </div>
                            <span
                                class="absolute top-6 right-6 bg-primary text-white text-[10px] font-bold px-4 py-2 uppercase tracking-widest shadow-md border border-primary-hover z-10">AFTER
                                (15s): CEEKLIN</span>
                            <p
                                class="text-gray-900/10 font-headline text-5xl md:text-8xl font-bold -rotate-90 absolute right-[-2rem] origin-center hidden md:block">
                                RESTORED</p>
                        </div>
                    </div>

                    <!-- Play Button Overlay -->
                    <button
                        class="z-20 bg-primary/95 text-white w-24 h-24 rounded-none flex items-center justify-center hover:bg-white hover:text-primary transition-all duration-300 border-4 border-white/20 hover:border-primary shadow-[0_0_60px_rgba(128,0,0,0.6)] group-hover:scale-110">
                        <svg aria-hidden="true" class="w-10 h-10 ml-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z" />
                        </svg>
                    </button>

                    <!-- Pembelah Garis Tengah Play -->
                    <div class="absolute top-0 bottom-0 left-1/2 w-[4px] bg-primary group-hover:bg-white transition-colors"
                        aria-hidden="true"></div>
                </div>
            </div>
        </section>

        <!-- 4. PERMUKAAN APLIKASI (Bento Grid Brutalist) -->
        <section class="max-w-[1400px] mx-auto px-8 lg:px-12 py-8 mb-20 relative">
            <div class="absolute top-0 right-12 w-64 h-64 bg-secondary opacity-5 blur-3xl z-[-1]" aria-hidden="true">
            </div>

            <div class="border-l-[10px] border-primary pl-6 py-1 mb-10">
                <h2
                    class="font-headline font-bold text-4xl lg:text-5xl text-gray-900 uppercase tracking-tighter leading-none">
                    DAYA HANCUR<br>SPESIFIK</h2>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-2">KATEGORISASI TARGET
                    PENGHAPUSAN KERAK</p>
            </div>

            <!-- Grid Dinamis (Bento Grid 2.0) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:auto-rows-[250px] lg:auto-rows-[280px]">

                <!-- Boks 01: Automotive (Wide) -->
                <div class="md:col-span-2 bg-neutral-dark border-4 border-neutral-border p-8 lg:p-10 relative overflow-hidden group hover:border-primary transition-colors cursor-pointer">
                    <div class="absolute right-0 bottom-0 text-[10rem] lg:text-[12rem] text-primary opacity-5 font-headline leading-none translate-y-16 translate-x-8 group-hover:scale-110 transition-transform duration-700">01</div>
                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div>
                            <span class="inline-block bg-gray-900 text-white text-[9px] uppercase tracking-widest font-bold px-3 py-1.5 mb-4 lg:mb-6">AUTOMOTIVE & GEAR</span>
                            <h3 class="font-headline font-bold text-primary text-2xl md:text-4xl uppercase tracking-tight">BLOK MESIN & KENDARAAN</h3>
                        </div>
                        <p class="text-sm lg:text-base font-bold text-slate-700 max-w-md leading-relaxed">Melarutkan kerak oli, gemuk mati, dan kotoran pada blok mesin tanpa merusak cat atau material logam kendaraan Anda.</p>
                    </div>
                </div>

                <!-- Boks 02: Kitchenware (Standard) -->
                <div class="bg-white border-4 border-neutral-border p-8 lg:p-10 relative overflow-hidden group hover:border-gray-900 transition-colors cursor-pointer shadow-sm">
                    <div class="absolute right-0 bottom-0 text-[8rem] text-gray-900 opacity-5 font-headline leading-none translate-y-12 translate-x-6 group-hover:scale-110 transition-transform duration-700">02</div>
                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div>
                            <span class="inline-block bg-secondary-dark text-white text-[9px] uppercase tracking-widest font-bold px-3 py-1.5 mb-4 lg:mb-6">KITCHENWARE & GLASS</span>
                            <h3 class="font-headline font-bold text-gray-900 text-xl lg:text-2xl uppercase leading-tight">GELAS & ALAT DAPUR</h3>
                        </div>
                        <p class="text-xs lg:text-sm font-bold text-slate-600 leading-relaxed">Mencerahkan kembali peralatan dapur, gelas kusam, hingga noda minyak pada stainless steel secara instan.</p>
                    </div>
                </div>

                <!-- Boks 03: Fabric (Standard) -->
                <div class="bg-gray-darkest border-4 border-gray-900 p-8 lg:p-10 relative overflow-hidden group text-neutral-light hover:border-primary transition-colors cursor-pointer">
                    <div class="absolute right-0 bottom-0 text-[8rem] text-primary opacity-20 font-headline leading-none translate-y-12 translate-x-6 group-hover:scale-110 transition-transform duration-700">03</div>
                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div>
                            <span class="inline-block border border-primary text-primary-hover bg-primary/10 text-[9px] uppercase tracking-widest font-bold px-3 py-1.5 mb-4 lg:mb-6">CLOTHING & FABRIC</span>
                            <h3 class="font-headline font-bold text-white text-xl lg:text-2xl uppercase leading-tight">NODA PADA PAKAIAN</h3>
                        </div>
                        <p class="text-xs lg:text-sm font-bold text-slate-400 leading-relaxed">Penghilang noda jamur, tinta, dan kotoran membandel pada kain putih maupun berwarna dengan pengerjaan cepat.</p>
                    </div>
                </div>

                <!-- Boks 04: Ceramic (Wide) -->
                <div class="md:col-span-2 bg-primary text-neutral border-4 border-primary-darkest p-8 lg:p-10 relative overflow-hidden group hover:border-secondary transition-colors cursor-pointer">
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiPgo8cmVjdCB3aWR0aD0iNCIgaGVpZ2h0PSI0IiBmaWxsPSIjOEIwMDAwIj48L3JlY3Q+CjxyZWN0IHdpZHRoPSIxIiBoZWlnaHQ9IjEiIGZpbGw9IiM1YTAwMDAiPjwvcmVjdD4KPC9zdmc+')] opacity-40 mix-blend-multiply"></div>
                    <div class="absolute right-0 bottom-0 text-[10rem] lg:text-[12rem] text-secondary opacity-10 font-headline leading-none translate-y-16 translate-x-8 group-hover:scale-110 transition-transform duration-700">04</div>
                    <div class="relative z-10 flex flex-col h-full justify-between">
                        <div>
                            <span class="inline-block border border-neutral/30 text-neutral-light bg-primary-darkest text-[9px] uppercase tracking-widest font-bold px-3 py-1.5 mb-4 lg:mb-6">CERAMIC & PORCELAIN</span>
                            <h3 class="font-headline font-bold text-white text-2xl md:text-4xl uppercase tracking-tight">KLOSET & WASTAFEL</h3>
                        </div>
                        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                            <p class="text-sm lg:text-base font-bold text-neutral-hover opacity-90 max-w-xl leading-relaxed">Solusi ekstrem untuk kamar mandi. Menghancurkan kerak air, jamur, dan lumut pada keramik, kloset, wastafel, hingga gayung secara instan.</p>
                            <div class="flex items-center gap-2 text-secondary-hover font-bold text-xs uppercase tracking-widest group-hover:text-secondary group-hover:translate-x-2 transition-all whitespace-nowrap">
                                Lihat Galeri <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <!-- 5. FAQ SECTION (Brutalist Accordion) -->
        <section class="max-w-[1000px] mx-auto px-8 py-20 mb-10">
            <div class="text-center mb-16">
                <h2 class="font-headline font-black text-4xl uppercase tracking-tighter text-gray-900">Pertanyaan Umum</h2>
                <div class="w-16 h-1 bg-primary mx-auto mt-4"></div>
            </div>

            <div class="space-y-4" x-data="{ active: null }">
                {{-- FAQ 1 --}}
                <div class="border-4 border-gray-900 bg-white shadow-[8px_8px_0_var(--color-gray-900)] transition-all duration-300">
                    <button @click="active = (active === 1 ? null : 1)" class="w-full p-6 flex justify-between items-center hover:bg-neutral-light transition-colors text-left group">
                        <span class="font-headline font-bold text-lg uppercase group-hover:text-primary transition-colors">Apakah aman jika terkena kulit langsung?</span>
                        <span class="text-2xl font-black text-primary transition-transform duration-300" :class="active === 1 ? 'rotate-45' : ''">+</span>
                    </button>
                    <div x-show="active === 1" x-collapse class="p-6 pt-0 border-t-2 border-neutral-border text-sm font-bold text-slate-600 leading-relaxed">
                        CeeKlin adalah bahan kimia agresif (HCl). Kami sangat menyarankan penggunaan sarung tangan karet saat pemakaian. Jika terkena kulit, segera bilas dengan air mengalir.
                    </div>
                </div>

                {{-- FAQ 2 --}}
                <div class="border-4 border-gray-900 bg-white shadow-[8px_8px_0_var(--color-gray-900)] transition-all duration-300">
                    <button @click="active = (active === 2 ? null : 2)" class="w-full p-6 flex justify-between items-center hover:bg-neutral-light transition-colors text-left group">
                        <span class="font-headline font-bold text-lg uppercase group-hover:text-primary transition-colors">Apakah merusak cat atau material logam?</span>
                        <span class="text-2xl font-black text-primary transition-transform duration-300" :class="active === 2 ? 'rotate-45' : ''">+</span>
                    </button>
                    <div x-show="active === 2" x-collapse class="p-6 pt-0 border-t-2 border-neutral-border text-sm font-bold text-slate-600 leading-relaxed">
                        Formulasi kami aman untuk permukaan logam dan cat kendaraan asalkan dibilas tuntas setelah waktu reaksi (2-5 menit). Jangan didiamkan hingga mengering.
                    </div>
                </div>

                {{-- FAQ 3 --}}
                <div class="border-4 border-gray-900 bg-white shadow-[8px_8px_0_var(--color-gray-900)] transition-all duration-300">
                    <button @click="active = (active === 3 ? null : 3)" class="w-full p-6 flex justify-between items-center hover:bg-neutral-light transition-colors text-left group">
                        <span class="font-headline font-bold text-lg uppercase group-hover:text-primary transition-colors">Bagaimana cara menjadi distributor resmi?</span>
                        <span class="text-2xl font-black text-primary transition-transform duration-300" :class="active === 3 ? 'rotate-45' : ''">+</span>
                    </button>
                    <div x-show="active === 3" x-collapse class="p-6 pt-0 border-t-2 border-neutral-border text-sm font-bold text-slate-600 leading-relaxed">
                        Pendaftaran distributor hanya dilakukan melalui Admin Pusat. Anda dapat menghubungi kami melalui menu KONTAK untuk verifikasi wilayah dan kuota stok.
                    </div>
                </div>
            </div>
        </section>

        <!-- 6. CALL TO ACTION RAKSASA (Footer Pre-block) -->
        <section id="registrasi"
            class="border-t-[8px] border-primary bg-primary text-white py-20 lg:py-28 px-8 lg:px-12 relative overflow-hidden">
            <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_center,white_0%,transparent_100%)] blur-2xl pointer-events-none"
                aria-hidden="true"></div>
            <!-- Strip Dekoratif di Background -->
            <div class="absolute -top-32 -right-32 w-80 h-80 bg-primary-darkest rounded-full mix-blend-multiply opacity-50 pointer-events-none"
                aria-hidden="true"></div>

            <div
                class="relative z-10 max-w-[1400px] mx-auto flex flex-col md:flex-row items-center justify-between gap-12">
                <div class="text-left w-full md:w-3/5">
                    <p
                        class="text-secondary font-bold text-sm uppercase tracking-[0.3em] border-l-4 border-secondary pl-4 mb-4">
                        AKSES EKSKLUSIF</p>
                    <h2
                        class="font-headline font-bold text-2xl sm:text-3xl md:text-4xl lg:text-6xl uppercase tracking-tighter mb-6 leading-[0.9]">
                        BERHENTI MENGGOSOK.<br>MULAI MENGHANCURKAN.
                    </h2>
                    <p class="font-body text-neutral/80 max-w-xl text-lg font-bold leading-relaxed">Portal CEEKLIN hanya
                        terbuka untuk mitra terotorisasi. Jadilah bagian dari rantai distribusi agen pembersih
                        industrial paling dominan saat ini.</p>
                </div>

                 <div class="w-full md:w-2/5 flex flex-col gap-4">
                    <a href="/register"
                        class="inline-flex items-center justify-center w-full text-center bg-white text-primary hover:bg-neutral border-4 border-white px-8 md:px-10 py-6 md:py-8 font-headline font-bold text-xl md:text-[1.35rem] uppercase tracking-widest transition-all shadow-[8px_8px_0_var(--color-primary-darkest)] md:shadow-[15px_15px_0_var(--color-primary-darkest)] hover:shadow-none hover:translate-x-[8px] md:hover:translate-x-[15px] hover:translate-y-[8px] md:hover:translate-y-[15px] duration-200 group">
                        DAFTAR SEBAGAI MITRA
                        <svg class="w-6 h-6 ml-4 group-hover:translate-x-2 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                    <a href="/login"
                        class="inline-flex items-center justify-center w-full text-center border-4 border-primary-hover text-white hover:bg-primary-hover px-10 py-6 font-headline font-bold text-lg uppercase tracking-widest transition-all mt-4">
                        SUDAH PUNYA AKSES? LOGIN
                    </a>
                </div>
            </div>
        </section>

    </main>

    <!-- FOOTER (Complex Variant) -->
    <x-layouts.footer variant="complex" />

    {{-- 4. Floating WhatsApp Button --}}
    <a href="https://wa.me/628xxxxxxxxxx" target="_blank" class="fixed bottom-8 right-8 z-[10000] bg-primary text-white p-4 shadow-[8px_8px_0_var(--color-secondary)] hover:shadow-[2px_2px_0_var(--color-secondary)] hover:translate-x-[6px] hover:translate-y-[6px] transition-all duration-300 border-2 border-gray-900 group overflow-hidden flex items-center justify-center">
        <div class="flex items-center gap-0 group-hover:gap-3 transition-all duration-300">
            <span class="text-[10px] font-black uppercase tracking-widest max-w-0 opacity-0 group-hover:max-w-[150px] group-hover:opacity-100 transition-all duration-500 overflow-hidden whitespace-nowrap">
                Konsultasi Admin
            </span>
            <svg class="w-6 h-6 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.414 0 .004 5.412 0 12.048c0 2.12.554 4.189 1.605 6.04L0 24l6.117-1.605a11.82 11.82 0 005.928 1.603h.005c6.635 0 12.046-5.412 12.05-12.048a11.825 11.825 0 00-3.576-8.487" />
            </svg>
        </div>
    </a>

</body>

</html>