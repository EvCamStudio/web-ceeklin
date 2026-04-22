<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">PORTAL DISTRIBUTOR</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>PETA WILAYAH</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.distributor._menu')</x-slot:menuSlot>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">

        {{-- Area Peta Utama --}}
        <div class="xl:col-span-2 bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">
            <div class="bg-primary px-6 py-3 flex items-center justify-between">
                <div>
                    <span class="font-headline font-black text-white text-base uppercase tracking-tight">Wilayah Jawa Barat</span>
                    <p class="text-[10px] text-white/50 font-bold uppercase tracking-widest mt-0.5">Titik distribusi aktif</p>
                </div>
                {{-- BACKEND-TODO: Filter zona dari wilayah distributor --}}
                <div class="relative">
                    <select aria-label="Filter Zona" class="appearance-none bg-white/10 text-white border-2 border-white/20 text-xs py-1.5 pl-3 pr-8 font-headline font-bold focus:outline-none focus:border-secondary cursor-pointer">
                        <option>Semua Zona</option>
                        <option>Zona A - Bandung</option>
                        <option>Zona B - Bekasi</option>
                    </select>
                    <div class="absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-white">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                    </div>
                </div>
            </div>
            {{-- Area Peta Placeholder --}}
            {{-- BACKEND-TODO: Integrasikan dengan Google Maps / Leaflet.js menggunakan koordinat reseller --}}
            <div class="h-[360px] bg-neutral-light relative flex items-center justify-center border-b-2 border-neutral-border">
                <div class="absolute inset-0 opacity-10 bg-[linear-gradient(var(--color-neutral-border)_1px,transparent_1px),linear-gradient(90deg,var(--color-neutral-border)_1px,transparent_1px)] [background-size:32px_32px]"></div>
                {{-- Pin Bandung --}}
                <div class="absolute top-[30%] left-[35%] flex flex-col items-center">
                    <div class="w-4 h-4 bg-primary border-[3px] border-white shadow-[2px_2px_0_var(--color-primary-darkest)]"></div>
                    <span class="text-[9px] font-black bg-white border-2 border-primary text-primary px-1.5 py-0.5 mt-1 tracking-widest uppercase">BGD Hub</span>
                </div>
                {{-- Pin Bekasi --}}
                <div class="absolute top-[50%] left-[65%] flex flex-col items-center">
                    <div class="w-4 h-4 bg-secondary border-[3px] border-white shadow-[2px_2px_0_var(--color-gray-900)]"></div>
                    <span class="text-[9px] font-black bg-white border-2 border-secondary text-secondary px-1.5 py-0.5 mt-1 tracking-widest uppercase">BKS Point</span>
                </div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 absolute bottom-4">Peta Interaktif — Integrasi Backend Diperlukan</p>
            </div>
        </div>

        {{-- Statistik Lokasi --}}
        <div class="flex flex-col gap-4">
            <div class="bg-white border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-primary-darkest)]">
                <div class="bg-primary px-5 py-3">
                    <span class="font-headline font-black text-white text-sm uppercase tracking-tight">Bandung Hub</span>
                </div>
                <div class="p-5 flex flex-col gap-3">
                    {{-- BACKEND-TODO: data dari Region::find('bandung')->stats() --}}
                    @foreach([['label'=>'Reseller Aktif','nilai'=>'14'],['label'=>'Volume Bulanan','nilai'=>'680 unit'],['label'=>'Cakupan Area','nilai'=>'85%']] as $stat)
                    <div class="flex justify-between items-center border-b border-neutral-border pb-2 last:border-0 last:pb-0">
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $stat['label'] }}</span>
                        <span class="font-headline font-black text-primary text-base">{{ $stat['nilai'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="bg-white border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-secondary)]">
                <div class="bg-secondary px-5 py-3">
                    <span class="font-headline font-black text-white text-sm uppercase tracking-tight">Bekasi Point</span>
                </div>
                <div class="p-5 flex flex-col gap-3">
                    @foreach([['label'=>'Reseller Aktif','nilai'=>'10'],['label'=>'Volume Bulanan','nilai'=>'565 unit'],['label'=>'Cakupan Area','nilai'=>'72%']] as $stat)
                    <div class="flex justify-between items-center border-b border-neutral-border pb-2 last:border-0 last:pb-0">
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $stat['label'] }}</span>
                        <span class="font-headline font-black text-primary text-base">{{ $stat['nilai'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
