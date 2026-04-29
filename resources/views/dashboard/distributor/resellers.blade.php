<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">PORTAL DISTRIBUTOR</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>RESELLER SAYA</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.distributor._menu')</x-slot:menuSlot>

    <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">
        <div class="bg-primary px-6 py-3 flex items-center justify-between">
            <span class="font-headline font-black text-white text-base uppercase tracking-tight">Jaringan Aktif</span>
            {{-- BACKEND-TODO: data dari Reseller::where('distributor_id', Auth::id())->count() --}}
            <span class="text-[10px] font-bold text-secondary uppercase tracking-widest">24 Total</span>
        </div>

        {{-- Kolom Header --}}
        <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-3 bg-neutral-light border-b-2 border-neutral-border">
            <div class="col-span-4 text-[10px] font-headline font-bold text-primary uppercase tracking-widest">Nama Reseller</div>
            <div class="col-span-3 text-[10px] font-headline font-bold text-primary uppercase tracking-widest">Lokasi</div>
            <div class="col-span-3 text-[10px] font-headline font-bold text-primary uppercase tracking-widest">Pesanan Terakhir</div>
            <div class="col-span-1 text-[10px] font-headline font-bold text-primary uppercase tracking-widest text-center">Status</div>
            <div class="col-span-1 text-[10px] font-headline font-bold text-primary uppercase tracking-widest text-center">Ket.</div>
        </div>

        {{-- BACKEND-TODO: Loop dari Reseller::where('distributor_id', Auth::id())->paginate(15) --}}
        <div class="divide-y-2 divide-neutral-border">
            {{-- Item 1 --}}
            <div class="flex flex-col md:grid md:grid-cols-12 gap-4 px-6 py-6 md:py-4 items-start md:items-center border-l-[5px] border-secondary hover:bg-neutral-light transition-colors duration-150">
                <div class="md:col-span-4 w-full">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nama Reseller</p>
                    <div class="font-bold text-sm text-gray-900 uppercase">PT. Maju Logistik</div>
                </div>
                <div class="md:col-span-3 w-full flex justify-between md:block">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Lokasi</p>
                    <div class="text-xs text-slate-500 font-bold uppercase tracking-widest">Bandung</div>
                </div>
                <div class="md:col-span-3 w-full flex justify-between md:block">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Pesanan Terakhir</p>
                    <div class="text-xs text-slate-500 font-bold">12 Okt 2024</div>
                </div>
                <div class="md:col-span-1 w-full flex justify-between md:block md:text-center">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Status</p>
                    <span class="px-2 py-0.5 border-2 border-secondary text-secondary text-[10px] font-bold uppercase tracking-widest">Aktif</span>
                </div>
                <div class="md:col-span-1 w-full flex justify-between md:block md:text-center">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Ket.</p>
                    <span class="text-[9px] font-bold text-slate-400">—</span>
                </div>
            </div>

            {{-- Item 2 --}}
            <div class="flex flex-col md:grid md:grid-cols-12 gap-4 px-6 py-6 md:py-4 items-start md:items-center border-l-[5px] border-secondary hover:bg-neutral-light transition-colors duration-150">
                <div class="md:col-span-4 w-full">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nama Reseller</p>
                    <div class="font-bold text-sm text-gray-900 uppercase">Teknik Karya Supply</div>
                </div>
                <div class="md:col-span-3 w-full flex justify-between md:block">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Lokasi</p>
                    <div class="text-xs text-slate-500 font-bold uppercase tracking-widest">Bekasi</div>
                </div>
                <div class="md:col-span-3 w-full flex justify-between md:block">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Pesanan Terakhir</p>
                    <div class="text-xs text-slate-500 font-bold">05 Okt 2024</div>
                </div>
                <div class="md:col-span-2 w-full flex justify-between md:block md:text-center">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Status</p>
                    <span class="px-2 py-0.5 border-2 border-secondary text-secondary text-[10px] font-bold uppercase tracking-widest">Aktif</span>
                </div>
            </div>

            {{-- Item 3: Contoh Reseller Sementara --}}
            <div class="flex flex-col md:grid md:grid-cols-12 gap-4 px-6 py-6 md:py-4 items-start md:items-center border-l-[5px] border-yellow-400 hover:bg-neutral-light transition-colors duration-150">
                <div class="md:col-span-4 w-full">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nama Reseller</p>
                    <div class="font-bold text-sm text-gray-900 uppercase">Indo Cipta Chem</div>
                </div>
                <div class="md:col-span-3 w-full flex justify-between md:block">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Lokasi</p>
                    <div class="text-xs text-slate-500 font-bold uppercase tracking-widest">Cirebon</div>
                </div>
                <div class="md:col-span-3 w-full flex justify-between md:block">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Pesanan Terakhir</p>
                    <div class="text-xs text-slate-500 font-bold">22 Agu 2024</div>
                </div>
                <div class="md:col-span-1 w-full flex justify-between md:block md:text-center">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Status</p>
                    <span class="px-2 py-0.5 border-2 border-secondary text-secondary text-[10px] font-bold uppercase tracking-widest">Aktif</span>
                </div>
                <div class="md:col-span-1 w-full flex justify-between md:block md:text-center">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Ket.</p>
                    <span class="px-1.5 py-0.5 bg-yellow-100 text-yellow-800 border border-yellow-400 text-[8px] font-black uppercase tracking-wider whitespace-nowrap">Sementara</span>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
