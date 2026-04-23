<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Portal CEEKLIN</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral font-body text-gray-900 antialiased min-h-screen flex flex-col justify-between">
    {{-- Grain Texture Overlay --}}
    <div class="grain-overlay"></div>

    <x-layouts.navbar>
        <x-slot:links>
            <a href="{{ route('home') }}" class="px-3 py-2 text-[10px] font-bold uppercase tracking-widest text-slate-600 hover:text-primary transition-all duration-300 hover:-translate-y-0.5">BERANDA</a>
            <a href="{{ route('specs') }}" class="px-3 py-2 text-[10px] font-bold uppercase tracking-widest text-slate-600 hover:text-primary transition-all duration-300 hover:-translate-y-0.5">SPEK PRODUK</a>
            <a href="{{ route('gallery') }}" class="px-3 py-2 text-[10px] font-bold uppercase tracking-widest text-slate-600 hover:text-primary transition-all duration-300 hover:-translate-y-0.5">GALERI</a>
            <a href="{{ route('contact') }}" class="px-3 py-2 text-[10px] font-bold uppercase tracking-widest text-slate-600 hover:text-primary transition-all duration-300 hover:-translate-y-0.5">KONTAK</a>
        </x-slot:links>
        
        <a href="/register" class="w-full md:w-auto font-headline font-bold text-[10px] uppercase tracking-widest bg-primary text-white border-[3px] border-primary px-8 py-2.5 hover:bg-transparent hover:text-primary transition-all duration-300 text-center">
            DAFTAR MITRA
        </a>
    </x-layouts.navbar>

    <!-- AREA KONTEN LOGIN -->
    <main class="flex-grow flex items-center justify-center p-6 py-12 md:py-20 relative">
        <x-ui.card class="w-full max-w-md bg-white border-0 border-t-[8px] border-primary shadow-2xl p-10 md:p-14 rounded-none">
            
            <!-- Logo & Subtitle Form -->
            <div class="text-center mb-10">
                <h1 class="font-headline font-bold text-4xl text-primary uppercase tracking-tighter mb-1 relative inline-block">
                    CEEKLIN
                </h1>
                <p class="text-[9px] uppercase font-bold text-secondary tracking-[0.2em]">INDUSTRIAL POWER PORTAL</p>
                
                @if($errors->any())
                    <div class="mt-6 bg-primary-darkest border-l-4 border-secondary p-3 text-left">
                        <p class="text-neutral font-bold text-[10px] uppercase tracking-widest">{{ $errors->first() }}</p>
                    </div>
                @endif
            </div>

            {{-- BACKEND-TODO: Route aksi form ini sementara diarahakan ke /login biasa. Ganti dengan route('login') Laravel --}}
            <form class="flex flex-col gap-6" method="POST" action="/login">
                @csrf
                
                <!-- Field Username -->
                <div>
                    <label for="username" class="block text-[10px] font-bold text-primary uppercase tracking-widest mb-1.5">USERNAME</label>
                    <x-ui.input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="admin, distributor, reseller, atau reseller2" 
                        required 
                        autofocus 
                        variant="industrial" 
                    />
                </div>

                <!-- Field Password -->
                <div>
                    <label for="password" class="block text-[10px] font-bold text-primary uppercase tracking-widest mb-1.5">PASSWORD</label>
                    <x-ui.input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="********" 
                        required 
                        variant="industrial" 
                    />
                    
                    <div class="flex justify-end mt-2">
                        <a href="#" class="text-[9px] uppercase font-bold text-primary tracking-widest hover:text-secondary transition-colors underline-offset-4 hover:underline">
                            LUPA PASSWORD?
                        </a>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="mt-4">
                    <x-ui.button type="submit" class="w-full text-base py-4 font-bold tracking-widest flex items-center justify-center gap-2 hover:bg-primary-hover">
                        MASUK &rarr;
                    </x-ui.button>
                </div>
            </form>
            
            <div class="mt-8 pt-8 border-t border-neutral-border-light text-center">
                <a href="/register" class="text-[10px] uppercase font-bold text-primary tracking-widest hover:text-secondary transition-colors inline-flex items-center gap-1">
                    Belum punya akun? Daftar di sini &rarr;
                </a>
            </div>
            
        </x-ui.card>
    </main>

    <!-- FOOTER MINIMALIS -->
    <x-layouts.footer variant="simple" />

</body>
</html>
