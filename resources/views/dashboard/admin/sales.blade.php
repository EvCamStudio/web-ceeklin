<x-layouts.dashboard bgTheme="light">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">MASTER ADMIN</span>
    </x-slot:subtitleSlot>

    <x-slot:topbarTitle>PENJUALAN NASIONAL</x-slot:topbarTitle>

    <x-slot:menuSlot>
        @include('dashboard.admin._menu')
    </x-slot:menuSlot>

    {{-- Layout: grafik besar kiri + breakdown wilayah kanan --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">

        {{-- Grafik Tren Volume --}}
        <div class="xl:col-span-2 bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">
            <div class="bg-primary px-6 py-3 flex items-center justify-between">
                <div>
                    <span class="font-headline font-black text-white text-base uppercase tracking-tight">Tren Volume Penjualan</span>
                    <p class="text-[10px] text-white/50 font-bold uppercase tracking-widest mt-0.5">Seluruh wilayah nasional</p>
                </div>
                {{-- Filter Kuartal --}}
                <div class="relative">
                    <select aria-label="Filter Kuartal" class="appearance-none bg-white/10 text-white border-2 border-white/20 text-xs py-1.5 pl-3 pr-8 font-headline font-bold focus:outline-none focus:border-secondary cursor-pointer">
                        {{-- BACKEND-TODO: populate dari kuartal tersedia --}}
                        <option>Q3 2024</option>
                        <option>Q2 2024</option>
                        <option>Q1 2024</option>
                    </select>
                    <div class="absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-white">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                    </div>
                </div>
            </div>

            <div class="p-6">
                {{-- Area Grafik --}}
                <div class="h-[240px] w-full relative border-l-[3px] border-b-[3px] border-gray-900 bg-neutral-light">
                    <div class="absolute inset-0 opacity-20 pointer-events-none bg-[radial-gradient(var(--color-neutral-border)_1px,transparent_1px)] [background-size:24px_24px]"></div>
                    {{-- BACKEND-TODO: data dari SalesReport::monthlyTrend($quarter) --}}
                    <svg class="w-full h-full relative z-10" preserveAspectRatio="none" viewBox="0 0 100 100" aria-label="Grafik Tren Volume Penjualan Nasional">
                        <path d="M0,80 L17,65 L33,70 L50,30 L67,45 L83,25 L100,10"
                            fill="none" stroke="var(--color-primary-container)" stroke-width="2.5" vector-effect="non-scaling-stroke" />
                        <path d="M0,80 L17,65 L33,70 L50,30 L67,45 L83,25 L100,10 L100,100 L0,100 Z"
                            fill="var(--color-primary)" opacity="0.08" />
                    </svg>
                </div>
                {{-- Label Bulan --}}
                <div class="flex justify-between mt-2 pr-1 gap-1">
                    @foreach(['Jan','Feb','Mar','Apr','Mei','Jun','Jul'] as $bulan)
                        <span class="text-[7px] sm:text-[9px] font-bold text-slate-400 uppercase tracking-tight sm:tracking-widest">{{ $bulan }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Breakdown Wilayah --}}
        <div class="bg-white border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)]">
            <div class="bg-gray-900 px-6 py-3">
                <span class="font-headline font-black text-white text-base uppercase tracking-tight">Rincian per Wilayah</span>
            </div>
            <div class="p-6 flex flex-col gap-5">
                {{-- BACKEND-TODO: data dari SalesReport::regionalBreakdown($quarter) --}}
                @foreach([
                    ['label' => 'Jawa Barat',  'nilai' => 1845, 'pct' => 72, 'warna' => 'bg-primary'],
                    ['label' => 'Jawa Timur',  'nilai' => 1380, 'pct' => 54, 'warna' => 'bg-secondary'],
                    ['label' => 'DKI Jakarta', 'nilai' => 976,  'pct' => 38, 'warna' => 'bg-secondary'],
                    ['label' => 'Jawa Tengah', 'nilai' => 620,  'pct' => 24, 'warna' => 'bg-slate-400'],
                ] as $wilayah)
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="font-bold text-xs text-gray-900">{{ $wilayah['label'] }}</span>
                            <span class="font-headline font-black text-base text-primary tracking-tighter">{{ number_format($wilayah['nilai']) }}</span>
                        </div>
                        <div class="bg-neutral-border-light border-2 border-neutral-border h-3 w-full">
                            <div class="{{ $wilayah['warna'] }} h-full transition-all" style="width:{{ $wilayah['pct'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</x-layouts.dashboard>
