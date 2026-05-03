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
            @forelse($resellers as $reseller)
            <div class="flex flex-col md:grid md:grid-cols-12 gap-4 px-6 py-6 md:py-4 items-start md:items-center border-l-[5px] border-secondary hover:bg-neutral-light transition-colors duration-150">
                <div class="md:col-span-4 w-full">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nama Reseller</p>
                    <div class="font-bold text-sm text-gray-900 uppercase">{{ $reseller->name }}</div>
                </div>
                <div class="md:col-span-3 w-full flex justify-between md:block">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Lokasi</p>
                    <div class="text-xs text-slate-500 font-bold uppercase tracking-widest">{{ $reseller->city_name }}</div>
                </div>
                <div class="md:col-span-3 w-full flex justify-between md:block">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Kontak</p>
                    <div class="text-xs text-slate-500 font-bold">{{ $reseller->phone }}</div>
                </div>
                <div class="md:col-span-1 w-full flex justify-between md:block md:text-center">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Status</p>
                    <span class="px-2 py-0.5 border-2 {{ $reseller->status === 'active' ? 'border-secondary text-secondary' : 'border-slate-300 text-slate-400' }} text-[10px] font-bold uppercase tracking-widest">
                        {{ $reseller->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                <div class="md:col-span-1 w-full flex justify-between md:block md:text-center">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Ket.</p>
                    <span class="text-[9px] font-bold text-slate-400">—</span>
                </div>
            </div>
            @empty
            <div class="px-6 py-10 text-center">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Belum ada reseller terdaftar di jaringan Anda</p>
            </div>
            @endforelse
        </div>
>
    </div>
</x-layouts.dashboard>
