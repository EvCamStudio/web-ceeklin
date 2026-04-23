<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">RESELLER TEROTORISASI</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>STATUS TIER</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.reseller._menu')</x-slot:menuSlot>

    <div class="flex flex-col gap-6">

        {{-- Tier Aktif --}}
        <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-secondary)] flex flex-col sm:flex-row gap-0">
            <div class="bg-secondary flex items-center justify-center p-6 sm:p-8 sm:w-48 flex-shrink-0">
                <div class="text-center">
                    <svg class="w-12 h-12 sm:w-16 sm:h-16 text-white mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                    <p class="font-headline font-black text-white text-[10px] uppercase tracking-widest mt-2">AKTIF</p>
                </div>
            </div>
            <div class="p-6 flex-1 text-center sm:text-left">
                <h3 class="font-headline font-black text-3xl md:text-4xl text-secondary tracking-tighter uppercase leading-none">Gold Tier</h3>
                <p class="text-[10px] sm:text-xs text-slate-500 font-bold uppercase tracking-widest mt-1.5">Anggota sejak Agustus 2024</p>
                <div class="mt-4">
                    <div class="flex justify-between items-end mb-1.5">
                        <span class="text-sm font-bold text-gray-900">Progres ke Platinum</span>
                        <span class="font-headline font-black text-primary text-lg">75%</span>
                    </div>
                    <div class="w-full bg-neutral-border-light border-2 border-neutral-border h-4">
                        <div class="bg-secondary h-full" style="width:75%"></div>
                    </div>
                    {{-- BACKEND-TODO: Hitung dari total_unit_sold vs threshold Platinum --}}
                    <p class="text-xs text-slate-500 mt-1.5 font-bold uppercase tracking-widest">Butuh 125 unit lagi untuk naik ke Platinum</p>
                </div>
            </div>
        </div>

        {{-- Perbandingan Tier --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Bronze --}}
            <div class="bg-white border-[3px] border-neutral-border shadow-[4px_4px_0_var(--color-neutral-border)] p-6 text-center opacity-60">
                <p class="font-headline font-black text-2xl text-slate-400 uppercase mb-1">Bronze</p>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">0 – 200 unit</p>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Komisi: 3%</p>
            </div>
            {{-- Gold (AKTIF) --}}
            <div class="bg-white border-[3px] border-secondary shadow-[4px_4px_0_var(--color-gray-900)] p-6 text-center relative">
                <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                    <span class="bg-secondary text-white text-[9px] font-black uppercase tracking-widest px-3 py-0.5 border-[2px] border-white">SAAT INI</span>
                </div>
                <p class="font-headline font-black text-2xl text-secondary uppercase mb-1 mt-2">Gold</p>
                <p class="text-[10px] text-secondary font-bold uppercase tracking-widest">200 – 500 unit</p>
                <p class="text-[10px] text-secondary font-bold uppercase tracking-widest">Komisi: 5%</p>
            </div>
            {{-- Platinum --}}
            <div class="bg-white border-[3px] border-primary shadow-[4px_4px_0_var(--color-primary-darkest)] p-6 text-center">
                <p class="font-headline font-black text-2xl text-primary uppercase mb-1">Platinum</p>
                <p class="text-[10px] text-primary font-bold uppercase tracking-widest">500+ unit</p>
                <p class="text-[10px] text-primary font-bold uppercase tracking-widest">Komisi: 8%</p>
            </div>
        </div>

        {{-- Benefit Gold Tier --}}
        <div class="bg-white border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-primary-darkest)]">
            <div class="bg-primary px-6 py-3">
                <span class="font-headline font-black text-white text-base uppercase tracking-tight">Benefit Gold Tier</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6">
                @foreach([
                    ['judul'=>'Komisi Langsung 5%','ket'=>'Dari semua penjualan personal'],
                    ['judul'=>'Override Referral 5%','ket'=>'3 pesanan pertama dari referral Anda'],
                    ['judul'=>'Dukungan Prioritas','ket'=>'Channel WhatsApp dedicated distributor'],
                    ['judul'=>'Pool Bonus Bulanan','ket'=>'Eligible untuk distribusi bonus bulanan'],
                ] as $benefit)
                <div class="flex items-start gap-3 p-4 bg-neutral-light border-[2px] border-neutral-border">
                    <div class="w-5 h-5 bg-secondary flex-shrink-0 mt-0.5 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <div>
                        <p class="font-headline font-bold text-primary text-sm uppercase tracking-tight">{{ $benefit['judul'] }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $benefit['ket'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.dashboard>
