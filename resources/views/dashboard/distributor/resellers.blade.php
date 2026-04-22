<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">PORTAL DISTRIBUTOR</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>JARINGAN RESELLER</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.distributor._menu')</x-slot:menuSlot>

    <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">
        <div class="bg-primary px-6 py-3 flex items-center justify-between">
            <span class="font-headline font-black text-white text-base uppercase tracking-tight">Jaringan Aktif</span>
            {{-- BACKEND-TODO: data dari Reseller::where('distributor_id', Auth::id())->count() --}}
            <span class="text-[10px] font-bold text-secondary uppercase tracking-widest">24 Total</span>
        </div>

        {{-- Kolom Header --}}
        <div class="grid grid-cols-12 gap-4 px-6 py-3 bg-neutral-light border-b-2 border-neutral-border">
            <div class="col-span-4 text-[10px] font-headline font-bold text-primary uppercase tracking-widest">Nama Reseller</div>
            <div class="col-span-3 text-[10px] font-headline font-bold text-primary uppercase tracking-widest">Lokasi</div>
            <div class="col-span-3 text-[10px] font-headline font-bold text-primary uppercase tracking-widest">Pesanan Terakhir</div>
            <div class="col-span-2 text-[10px] font-headline font-bold text-primary uppercase tracking-widest text-center">Status</div>
        </div>

        {{-- BACKEND-TODO: Loop dari Reseller::where('distributor_id', Auth::id())->paginate(15) --}}
        <div class="divide-y-2 divide-neutral-border">
            <div class="grid grid-cols-12 gap-4 px-6 py-4 items-center border-l-[5px] border-secondary hover:bg-neutral-light transition-colors duration-150">
                <div class="col-span-4 font-bold text-sm text-gray-900">PT. Maju Logistik</div>
                <div class="col-span-3 text-xs text-slate-500 font-bold uppercase tracking-widest">Bandung</div>
                <div class="col-span-3 text-xs text-slate-500 font-bold">12 Okt 2024</div>
                <div class="col-span-2 text-center">
                    <span class="px-2 py-1 border-2 border-secondary text-secondary text-[10px] font-bold uppercase tracking-widest">Aktif</span>
                </div>
            </div>
            <div class="grid grid-cols-12 gap-4 px-6 py-4 items-center border-l-[5px] border-secondary hover:bg-neutral-light transition-colors duration-150">
                <div class="col-span-4 font-bold text-sm text-gray-900">Teknik Karya Supply</div>
                <div class="col-span-3 text-xs text-slate-500 font-bold uppercase tracking-widest">Bekasi</div>
                <div class="col-span-3 text-xs text-slate-500 font-bold">05 Okt 2024</div>
                <div class="col-span-2 text-center">
                    <span class="px-2 py-1 border-2 border-secondary text-secondary text-[10px] font-bold uppercase tracking-widest">Aktif</span>
                </div>
            </div>
            <div class="grid grid-cols-12 gap-4 px-6 py-4 items-center border-l-[5px] border-transparent hover:bg-neutral-light transition-colors duration-150">
                <div class="col-span-4 font-bold text-sm text-slate-400">Indo Cipta Chem</div>
                <div class="col-span-3 text-xs text-slate-400 font-bold uppercase tracking-widest">Cirebon</div>
                <div class="col-span-3 text-xs text-slate-400 font-bold">22 Agu 2024</div>
                <div class="col-span-2 text-center">
                    <span class="px-2 py-1 border-2 border-slate-300 text-slate-400 text-[10px] font-bold uppercase tracking-widest">Nonaktif</span>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
