@props(['bgTheme' => 'light'])

@php
    $isDarkSidebar = $bgTheme === 'dark';

    $sidebarClasses = $isDarkSidebar
        ? 'bg-primary text-neutral border-r-[3px] border-primary-darkest'
        : 'bg-neutral border-r-[3px] border-neutral-border text-gray-900';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - CEEKLIN Portal</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* App Shell: kunci viewport agar sidebar selalu diam */
        html, body { height: 100%; overflow: hidden; }

        /* Animasi fade-in untuk konten halaman */
        @keyframes dashContentIn {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .dash-content-enter {
            animation: dashContentIn 220ms cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
        }

        /* Custom scrollbar tipis untuk main content */
        .dash-main::-webkit-scrollbar { width: 4px; }
        .dash-main::-webkit-scrollbar-track { background: transparent; }
        .dash-main::-webkit-scrollbar-thumb {
            background: var(--color-neutral-border);
            border-radius: 0;
        }
        .dash-main::-webkit-scrollbar-thumb:hover {
            background: var(--color-primary);
        }
    </style>
</head>
{{-- h-full + overflow-hidden mengunci frame agar tidak ada page scroll --}}
<body class="bg-neutral antialiased h-full overflow-hidden" x-data="{ sidebarOpen: false }">
<div class="h-full flex font-body text-gray-900">

    {{-- ===== SIDEBAR (Desktop: Visible lg+) ===== --}}
    <aside class="hidden lg:flex w-64 flex-shrink-0 flex-col h-full {{ $sidebarClasses }}">
        {{-- Logo Area --}}
        <div class="h-20 flex-shrink-0 flex items-center px-6 border-b-2 border-neutral-border">
            <div class="flex flex-col">
                <span class="font-headline font-black text-2xl tracking-tight {{ $isDarkSidebar ? 'text-white' : 'text-primary' }}">
                    CEEKLIN
                </span>
                {{ $subtitleSlot ?? '' }}
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto py-2 flex flex-col" aria-label="Navigasi Dashboard">
            {{ $menuSlot ?? '' }}
        </nav>
    </aside>

    {{-- ===== SIDEBAR (Mobile: Drawer) ===== --}}
    <div x-show="sidebarOpen" 
         class="fixed inset-0 z-[100] lg:hidden" 
         style="display: none;"
         @keydown.escape.window="sidebarOpen = false">
        {{-- Backdrop --}}
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm"
             @click="sidebarOpen = false"></div>

        {{-- Drawer Content --}}
        <div x-show="sidebarOpen"
             x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="relative flex w-full max-w-xs flex-1 flex-col h-full overflow-hidden {{ $sidebarClasses }} {{ $isDarkSidebar ? 'shadow-[10px_0_0_var(--color-primary-darkest)]' : 'shadow-[10px_0_0_var(--color-primary)]' }}">
            
            <div class="h-20 flex-shrink-0 flex items-center justify-between px-6 border-b-2 border-neutral-border">
                <div class="flex flex-col">
                    <span class="font-headline font-black text-2xl tracking-tight {{ $isDarkSidebar ? 'text-white' : 'text-primary' }}">
                        CEEKLIN
                    </span>
                </div>
                <button @click="sidebarOpen = false" class="text-white bg-gray-900 p-1.5 border-2 border-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto py-4" @click="sidebarOpen = false">
                {{ $menuSlot ?? '' }}
            </nav>
        </div>
    </div>

    {{-- ===== MAIN CONTENT WRAPPER ===== --}}
    <div class="flex-1 flex flex-col min-w-0 h-full">

        {{-- Topbar --}}
        <header class="h-20 flex-shrink-0 flex items-center justify-between px-4 md:px-8 border-b-2 border-neutral-border bg-neutral">
            <div class="flex items-center gap-4">
                {{-- Hamburger Button (Mobile) --}}
                <button @click="sidebarOpen = true" class="lg:hidden bg-primary text-white p-2 border-2 border-gray-900 shadow-[3px_3px_0_var(--color-gray-900)]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>

                <h2 class="font-headline text-sm md:text-xl font-black text-primary uppercase tracking-widest leading-none">
                    {{ $topbarTitle ?? 'DASHBOARD' }}
                </h2>
            </div>

            <div class="flex items-center gap-3 md:gap-6">
                {{-- Search (Linked to page search if available) --}}
                <div class="relative hidden sm:block border-[3px] border-primary shadow-[4px_4px_0_var(--color-primary-darkest)]">
                    <input type="text" placeholder="Cari..." 
                        @keydown.enter="$dispatch('global-search', $el.value)"
                        class="w-32 md:w-56 bg-white border-none px-4 py-2 font-body text-xs md:text-sm font-bold text-primary focus:ring-0 focus:outline-none">
                </div>

                {{-- Notif + Avatar --}}
                <div class="flex items-center gap-3 md:gap-5 text-primary">
                    <button class="hover:text-secondary transition-colors" aria-label="Notifikasi" @click="$dispatch('notify', { message: 'Belum ada notifikasi baru', type: 'info' })">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </button>
                    
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="w-8 h-8 md:w-10 md:h-10 bg-primary border-[2px] md:border-[3px] border-gray-900 text-white flex items-center justify-center font-headline font-black text-xs md:text-base shadow-[3px_3px_0_var(--color-gray-900)] hover:bg-primary-hover transition-all active:translate-y-0.5 active:shadow-none">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </button>
                        
                        {{-- Profile Dropdown --}}
                        <div x-show="open" @click.away="open = false" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="absolute right-0 mt-3 w-48 bg-white border-[3px] border-gray-900 shadow-[6px_6px_0_var(--color-primary)] z-[150] overflow-hidden">
                            <div class="px-4 py-3 border-b-2 border-neutral-light bg-neutral-light">
                                <p class="text-[10px] font-black uppercase text-slate-400">Login Sebagai</p>
                                <p class="text-xs font-bold text-primary truncate">{{ Auth::user()->name }}</p>
                            </div>
                            <a href="{{ route('dashboard') }}" class="block px-4 py-3 text-[10px] font-black uppercase hover:bg-neutral-light border-b-2 border-neutral-light transition-colors tracking-widest">Pengaturan Akun</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-3 text-[10px] font-black uppercase text-red-600 hover:bg-red-50 transition-colors tracking-widest italic">
                                    Keluar Sesi
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- SCROLLABLE CONTENT AREA --}}
        <main class="dash-main flex-1 overflow-y-auto p-4 md:px-8 md:pt-5 md:pb-8" style="scrollbar-gutter: stable;">
            <div class="dash-content-enter">
                {{ $slot }}
            </div>
        </main>
    </div>

    {{-- Global Toast Notifications & Confirmation --}}
    <div class="fixed top-6 right-6 z-[250] flex flex-col gap-4 pointer-events-none" 
         x-data="{ 
            toasts: [],
            addToast(message, type = 'success') {
                const id = Date.now();
                this.toasts.push({ id, message, type });
                setTimeout(() => {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }, 5000);
            }
         }"
         @notify.window="addToast($event.detail.message, $event.detail.type || 'success')"
         @toast.window="addToast($event.detail.message, $event.detail.type || 'success')">
        
        <template x-for="toast in toasts" :key="toast.id">
            <div x-data="{ show: true }" x-show="show" x-init="$nextTick(() => { })" x-transition>
                <x-ui.toast x-bind:type="toast.type" x-bind:message="toast.message" />
            </div>
        </template>

        @if(session('success'))
            <x-ui.toast type="success" :message="session('success')" />
        @endif

        @if(session('error'))
            <x-ui.toast type="error" :message="session('error')" />
        @endif

        @if(session('warning'))
            <x-ui.toast type="warning" :message="session('warning')" />
        @endif

        @if($errors->any())
            <x-ui.toast type="error" message="Ada kesalahan pada input Anda. Silakan periksa kembali." />
        @endif
    </div>
</div>
    {{-- Global Confirmation Modal --}}
    <div x-data="{ 
            show: false, 
            title: '', 
            message: '', 
            onConfirm: null,
            confirmText: 'YA, LANJUTKAN',
            cancelText: 'BATAL'
         }"
         @ask-confirm.window="
            show = true; 
            title = $event.detail.title || 'Konfirmasi Tindakan';
            message = $event.detail.message || 'Apakah Anda yakin ingin melakukan tindakan ini?';
            onConfirm = $event.detail.onConfirm;
            confirmText = $event.detail.confirmText || 'YA, LANJUTKAN';
            cancelText = $event.detail.cancelText || 'BATAL';
         "
         x-show="show"
         style="display: none;"
         class="fixed inset-0 z-[300] flex items-center justify-center p-4 bg-gray-900/80 backdrop-blur-sm">
        
        <div x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90 translate-y-10"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="bg-white border-[4px] border-gray-900 shadow-[16px_16px_0_rgba(0,0,0,0.2)] max-w-md w-full overflow-hidden">
            
            <div class="bg-primary p-6 border-b-[4px] border-gray-900 flex items-center gap-4">
                <div class="w-10 h-10 bg-white/20 border-2 border-white/30 flex items-center justify-center text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="font-headline font-black text-xl text-white uppercase tracking-tighter italic" x-text="title"></h3>
            </div>
            
            <div class="p-8">
                <p class="text-sm font-bold text-gray-600 leading-relaxed uppercase tracking-wider" x-text="message"></p>
                
                <div class="mt-10 flex flex-col sm:flex-row gap-4">
                    <button @click="show = false; if(onConfirm) onConfirm()" 
                            class="flex-1 bg-primary text-white py-4 font-headline font-black text-xs uppercase tracking-[0.2em] border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-primary-darkest)] hover:bg-primary-hover active:translate-y-1 active:shadow-none transition-all"
                            x-text="confirmText"></button>
                    
                    <button @click="show = false" 
                            class="flex-1 bg-white text-gray-900 py-4 font-headline font-black text-xs uppercase tracking-[0.2em] border-[3px] border-gray-900 shadow-[4px_4px_0_rgba(0,0,0,0.1)] hover:bg-neutral-light active:translate-y-1 active:shadow-none transition-all"
                            x-text="cancelText"></button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

