<x-layouts.dashboard bgTheme="light">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">MASTER ADMIN</span>
    </x-slot:subtitleSlot>

    <x-slot:topbarTitle>BERANDA</x-slot:topbarTitle>

    <x-slot:menuSlot>
        @include('dashboard.admin._menu')
    </x-slot:menuSlot>

    {{-- 
        TODO BACKEND:
        Gunakan Toast Component untuk notifikasi success/error (misal: setelah berhasil login atau simpan data).
        Silakan panggil komponen ini jika terdapat flash session.
        Contoh:
        @if(session('success'))
            <div class="fixed top-8 right-8 z-[100]">
                <x-ui.toast type="success" :message="session('success')" />
            </div>
        @endif
    --}}

    <div class="max-w-[1400px] mx-auto w-full">
        <!-- KPI Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 mt-4">
            <!-- Box 1: Sales -->
            <div class="bg-white p-6 border-[3px] border-primary-container shadow-[8px_8px_0_var(--color-primary-darkest)]">
                <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-2">Total Sales (Nasional)</p>
                <h3 class="font-headline font-black text-3xl md:text-4xl text-primary tracking-tighter">4,821</h3>
                <p class="text-xs text-slate-500 font-bold mt-2 uppercase tracking-widest flex items-center gap-1">
                    <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    +12.4% vs Q prev
                </p>
            </div>
            
            <!-- Box 2: Distributors -->
            <div class="bg-white p-6 border-[3px] border-secondary shadow-[8px_8px_0_var(--color-gray-900)]">
                <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-2">Active Distributors</p>
                <h3 class="font-headline font-black text-3xl md:text-4xl text-primary tracking-tighter">38</h3>
                <p class="text-xs text-slate-500 font-bold mt-2 uppercase tracking-widest">Across 12 provinces</p>
            </div>
            
            <!-- Box 3: Revenue -->
            <div class="bg-white p-6 border-[3px] border-primary shadow-[8px_8px_0_var(--color-primary-hover)]">
                <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-2">Revenue MTD</p>
                <h3 class="font-headline font-black text-2xl sm:text-3xl md:text-4xl text-primary tracking-tighter italic">Rp 6.2B</h3>
                <p class="text-xs text-slate-500 font-bold mt-2 uppercase tracking-widest flex items-center gap-1">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    +8.1% M/M
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start mb-10">
            <!-- Main Content Area: Chart (Col Span 2) -->
            <div class="lg:col-span-2 bg-neutral-light border-[4px] border-gray-900 px-6 py-8 sm:px-8 shadow-[12px_12px_0_var(--color-primary-darkest)]">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                    <div>
                        <h3 class="font-headline font-black text-2xl sm:text-3xl text-primary tracking-tighter uppercase relative z-10">National Volume</h3>
                        <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mt-1">Q3 YTD</p>
                    </div>
                    <!-- Brutalist Select -->
                    <div class="relative w-full sm:w-auto">
                        <select aria-label="Pilih Wilayah" class="appearance-none bg-white border-[3px] border-primary text-xs w-full sm:w-auto py-2.5 pl-4 pr-12 font-headline font-bold text-primary focus:outline-none focus:ring-0 focus:border-secondary shadow-[4px_4px_0_var(--color-gray-900)] cursor-pointer">
                            <option>NATIONAL</option>
                            <option>JAWA BARAT</option>
                            <option>JAWA TIMUR</option>
                        </select>
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-primary">
                            <svg class="w-5 h-5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                    </div>
                </div>
                
                <!-- Chart Area -->
                <div class="h-[300px] w-full relative border-l-[3px] border-b-[3px] border-gray-900 pt-4 pr-4 bg-white/50 overflow-hidden">
                    <!-- Helper Grid Layout -->
                    <div class="absolute inset-0 z-0 opacity-20 pointer-events-none bg-[radial-gradient(var(--color-neutral-border)_1px,transparent_1px)] [background-size:24px_24px]"></div>
                    
                    <svg class="w-full h-full relative z-10" preserveAspectRatio="none" viewBox="0 0 100 100">
                        <path d="M0,80 L20,65 L40,70 L60,30 L80,45 L100,10" fill="none" stroke="var(--color-primary-container)"
                            stroke-width="2.5" vector-effect="non-scaling-stroke" />
                        <path d="M0,80 L20,65 L40,70 L60,30 L80,45 L100,10 L100,100 L0,100 Z" fill="var(--color-primary)"
                            opacity="0.1" />
                        <path d="M0,90 L20,85 L40,80 L60,60 L80,65 L100,40" fill="none" stroke="var(--color-secondary)"
                            stroke-dasharray="4 4" stroke-width="1.5" vector-effect="non-scaling-stroke" />
                    </svg>
                </div>
            </div>

            <!-- Side Content Activity (Col Span 1) -->
            <div class="bg-white border-[4px] border-gray-900 p-6 sm:p-8 shadow-[12px_12px_0_var(--color-gray-900)] h-full flex flex-col">
                <h3 class="font-headline font-bold text-xl text-primary uppercase tracking-tight mb-6">Recent Activity</h3>
                <div class="flex-1 flex flex-col gap-1">
                    <div class="flex flex-col sm:flex-row justify-between py-4 border-b-2 border-neutral-border gap-2">
                        <div class="flex-1">
                            <p class="font-bold text-sm text-gray-900 leading-tight">PT Tirta Makmur mendaftar</p>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mt-1">Distributor Baru — Jabar</p>
                        </div>
                        <span class="text-[9px] font-bold text-primary-hover uppercase tracking-[0.2em] bg-neutral-light px-2 py-1 h-fit">2j lalu</span>
                    </div>
                    <div class="flex flex-col sm:flex-row justify-between py-4 border-b-2 border-neutral-border gap-2">
                        <div class="flex-1">
                            <p class="font-bold text-sm text-gray-900 leading-tight">Harga naik ke Rp 1.250.000</p>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mt-1">Tier Distributor — Nasional</p>
                        </div>
                        <span class="text-[9px] font-bold text-primary-hover uppercase tracking-[0.2em] bg-neutral-light px-2 py-1 h-fit">12j lalu</span>
                    </div>
                    <div class="flex flex-col sm:flex-row justify-between py-4 gap-2">
                        <div class="flex-1">
                            <p class="font-bold text-sm text-gray-900 leading-tight">Bonus 12.5M dicairkan</p>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mt-1">PT Tirta Makmur — Platinum</p>
                        </div>
                        <span class="text-[9px] font-bold text-primary-hover uppercase tracking-[0.2em] bg-neutral-light px-2 py-1 h-fit">1h lalu</span>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t-4 border-gray-900">
                    <button class="w-full text-center text-[10px] font-bold uppercase tracking-widest text-primary hover:text-secondary flex justify-center items-center gap-2 group transition-colors">
                        LIHAT LOG LENGKAP
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
