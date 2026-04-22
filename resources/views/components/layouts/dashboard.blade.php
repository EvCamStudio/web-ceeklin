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
<body class="bg-neutral antialiased h-full overflow-hidden">
<div class="h-full flex font-body text-gray-900">

    {{-- ===== SIDEBAR (diam, tidak pernah bergerak) ===== --}}
    <aside class="w-64 flex-shrink-0 flex flex-col h-full {{ $sidebarClasses }}">
        {{-- Logo Area --}}
        <div class="h-20 flex-shrink-0 flex items-center px-6 border-b-2 border-neutral-border">
            <div class="flex flex-col">
                <span class="font-headline font-black text-2xl tracking-tight {{ $isDarkSidebar ? 'text-white' : 'text-primary' }}">
                    CEEKLIN
                </span>
                {{ $subtitleSlot ?? '' }}
            </div>
        </div>

        {{-- Navigation: flex-1 + overflow-y-auto agar menu bisa scroll jika banyak --}}
        <nav class="flex-1 overflow-y-auto py-2 flex flex-col" aria-label="Navigasi Dashboard">
            {{ $menuSlot ?? '' }}
        </nav>
    </aside>

    {{-- ===== MAIN CONTENT WRAPPER (topbar diam, hanya <main> yang scroll) ===== --}}
    <div class="flex-1 flex flex-col min-w-0 h-full">

        {{-- Alert Banner (Optional) --}}
        {{ $alertBannerSlot ?? '' }}

        {{-- TOPBAR — diam, flex-shrink-0 agar tidak tertekan --}}
        <header class="h-20 flex-shrink-0 flex items-center justify-between px-8 border-b-2 border-neutral-border bg-neutral">
            <h2 class="font-headline text-xl font-black text-primary uppercase tracking-widest">
                {{ $topbarTitle ?? 'DASHBOARD OVERVIEW' }}
            </h2>

            <div class="flex items-center gap-6">
                {{-- Search --}}
                <div class="relative hidden md:block border-[3px] border-primary shadow-[4px_4px_0_var(--color-primary-darkest)]">
                    <input type="text" placeholder="Search..." aria-label="Search dashboard"
                        class="w-56 bg-white border-none px-4 py-2 font-body text-sm font-bold text-primary focus:ring-0 focus:outline-none placeholder:text-primary/40">
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-primary pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                {{-- Notif + Avatar --}}
                <div class="flex items-center gap-5 text-primary">
                    <button class="hover:text-secondary transition-colors duration-150" aria-label="Notifikasi">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </button>
                    {{-- BACKEND-TODO: Ganti 'A' dengan initial dari Auth::user()->name --}}
                    <div class="w-10 h-10 bg-primary border-[3px] border-gray-900 text-white flex items-center justify-center font-headline font-black text-base shadow-[4px_4px_0_var(--color-gray-900)] cursor-pointer hover:bg-primary-hover transition-colors duration-150" aria-label="Profil pengguna">
                        A
                    </div>
                </div>
            </div>
        </header>

        {{-- SCROLLABLE CONTENT AREA — hanya bagian ini yang bergerak --}}
        <main class="dash-main flex-1 overflow-y-auto p-8">
            {{-- Wrapper dengan animasi masuk (fade + slide subtle) setiap navigasi --}}
            <div class="dash-content-enter">
                {{ $slot }}
            </div>
        </main>
    </div>

</div>
</body>
</html>

