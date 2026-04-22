<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">RESELLER TEROTORISASI</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>PENDAPATAN</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.reseller._menu')</x-slot:menuSlot>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white border-[3px] border-primary shadow-[6px_6px_0_var(--color-primary-darkest)] p-6">
            <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-1">Total Pendapatan</p>
            <h3 class="font-headline font-black text-3xl text-primary tracking-tighter">Rp 12.500.000</h3>
        </div>
        <div class="bg-white border-[3px] border-secondary shadow-[6px_6px_0_var(--color-gray-900)] p-6">
            <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-1">Bulan Ini</p>
            <h3 class="font-headline font-black text-3xl text-primary tracking-tighter">Rp 3.200.000</h3>
        </div>
        <div class="bg-white border-[3px] border-primary shadow-[6px_6px_0_var(--color-primary-hover)] p-6">
            <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-1">Menunggu Cair</p>
            <h3 class="font-headline font-black text-3xl text-primary tracking-tighter">Rp 800.000</h3>
        </div>
    </div>

    {{-- Grafik Tren + Riwayat Transaksi --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Grafik Tren --}}
        <div class="xl:col-span-2 bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">
            <div class="bg-primary px-6 py-3">
                <span class="font-headline font-black text-white text-base uppercase tracking-tight">Tren Pendapatan Bulanan</span>
            </div>
            <div class="p-6">
                {{-- BACKEND-TODO: Render chart dengan Chart.js dari Earning::groupBy('month')->sum('amount') --}}
                <div class="h-[180px] relative border-l-2 border-b-2 border-neutral-border">
                    <svg class="w-full h-full" preserveAspectRatio="none" viewBox="0 0 100 100">
                        <path d="M0,85 L17,70 L33,75 L50,50 L67,40 L83,35 L100,20" fill="none" stroke="var(--color-primary)" stroke-width="2.5" vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M0,85 L17,70 L33,75 L50,50 L67,40 L83,35 L100,20 L100,100 L0,100 Z" fill="var(--color-primary)" opacity="0.06"/>
                    </svg>
                    <div class="absolute bottom-2 left-0 right-0 flex justify-between px-2">
                        @foreach(['Mei','Jun','Jul','Agu','Sep','Okt','Nov'] as $bln)
                        <span class="text-[9px] font-bold text-slate-400 uppercase">{{ $bln }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Riwayat Transaksi --}}
        <div class="bg-white border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-secondary)]">
            <div class="bg-secondary px-6 py-3">
                <span class="font-headline font-black text-white text-sm uppercase tracking-tight">Riwayat Transaksi</span>
            </div>
            <div class="divide-y-2 divide-neutral-border">
                {{-- BACKEND-TODO: Loop dari Earning::latest()->take(5)->get() --}}
                @foreach([
                    ['nama'=>'Bonus Penjualan — Oktober','ket'=>'Komisi penjualan langsung','nominal'=>'+Rp 3.200.000','tgl'=>'15 Okt 2024'],
                    ['nama'=>'Bonus Referral — CV Maju Jaya','ket'=>'5% override 3 pesanan pertama','nominal'=>'+Rp 750.000','tgl'=>'08 Okt 2024'],
                    ['nama'=>'Bonus Penjualan — September','ket'=>'Komisi penjualan langsung','nominal'=>'+Rp 2.800.000','tgl'=>'15 Sep 2024'],
                ] as $tx)
                <div class="flex items-start justify-between px-5 py-4 gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-xs text-gray-900 truncate">{{ $tx['nama'] }}</p>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ $tx['ket'] }}</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">{{ $tx['tgl'] }}</p>
                    </div>
                    <span class="font-headline font-black text-primary text-sm flex-shrink-0">{{ $tx['nominal'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.dashboard>
