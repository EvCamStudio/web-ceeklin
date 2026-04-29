<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - Portal CeeKlin</title>
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
                
                @if(session('status') === 'pending')
                    <div class="fixed top-12 left-1/2 -translate-x-1/2 z-[100] w-full max-w-sm px-4">
                        <x-ui.toast type="warning" message="Akun Anda sedang dalam proses peninjauan oleh Admin." />
                    </div>
                @elseif(session('status') === 'rejected')
                    <div class="fixed top-12 left-1/2 -translate-x-1/2 z-[100] w-full max-w-sm px-4">
                        <x-ui.toast type="error" :message="'Akun Ditolak: ' . session('reason')" />
                    </div>
                @elseif($errors->any())
                    <div class="fixed top-12 left-1/2 -translate-x-1/2 z-[100] w-full max-w-sm px-4">
                        <x-ui.toast type="error" :message="$errors->first()" />
                    </div>
                @endif
            </div>

            {{-- BACKEND-TODO: Route aksi form ini sementara diarahakan ke /login biasa. Ganti dengan route('login') Laravel --}}
            <form class="flex flex-col gap-6" method="POST" action="/login" novalidate x-data="{
                errors: { username: '', password: '' },
                validateForm(e) {
                    this.errors.username = '';
                    this.errors.password = '';
                    const u = document.getElementById('username').value;
                    const p = document.getElementById('password').value;
                    let hasError = false;
                    
                    if (!u) {
                        this.errors.username = 'Username wajib diisi';
                        hasError = true;
                    }
                    if (!p) {
                        this.errors.password = 'Password wajib diisi';
                        hasError = true;
                    }
                    
                    if (hasError) {
                        e.preventDefault();
                    }
                }
            }" @submit="validateForm($event)">
                @csrf
                
                <!-- Field Username -->
                <div>
                    <label for="username" class="block text-[10px] font-bold text-primary uppercase tracking-widest mb-1.5">USERNAME</label>
                    {{-- 
                        TODO BACKEND:
                        hideServerError="true" ditambahkan agar pesan error bawaan Laravel (seperti "Identitas tidak cocok")
                        TIDAK diduplikasi di bawah field ini, karena sudah ditampilkan di komponen x-ui.toast di bagian atas form.
                        Error pill di bawah field ini murni difokuskan untuk interaksi validasi client-side (Alpine.js).
                    --}}
                    <x-ui.input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="Masukkan username Anda" 
                        required 
                        autofocus 
                        variant="industrial" 
                        hideServerError="true"
                        @input="if(errors['username']) errors['username'] = null"
                    />
                </div>

                <!-- Field Password -->
                <div class="relative">
                    <label for="password" class="block text-[10px] font-bold text-primary uppercase tracking-widest mb-1.5">PASSWORD</label>
                    <x-ui.input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Masukkan password Anda" 
                        required 
                        variant="industrial" 
                        hideServerError="true"
                        @input="if(errors['password']) errors['password'] = null"
                    />
                    
                    <div class="flex justify-end mt-2" x-data="{ showReset: false }">
                        <button type="button" @click="showReset = !showReset"
                            class="text-[9px] uppercase font-bold text-primary tracking-widest hover:text-secondary transition-colors">
                            LUPA PASSWORD?
                        </button>
                        <div x-show="showReset" x-transition
                             class="absolute mt-6 right-0 bg-white border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-secondary)] p-4 max-w-[220px] z-10"
                             style="display:none;">
                            <p class="text-[9px] font-bold text-gray-900 uppercase tracking-widest leading-relaxed mb-2">
                                Hubungi Admin untuk reset password:
                            </p>
                            <a href="https://wa.me/628xxxxxxxxxx?text=Halo%20Admin%2C%20saya%20lupa%20password%20akun%20CeeKlin%20saya." target="_blank"
                               class="inline-flex items-center gap-1.5 bg-secondary text-white px-3 py-2 font-bold text-[9px] uppercase tracking-widest w-full justify-center hover:bg-secondary-dark transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                Hubungi Admin via WA
                            </a>
                        </div>
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
