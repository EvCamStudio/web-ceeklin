<x-layouts.dashboard bgTheme="light">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">MASTER ADMIN</span>
    </x-slot:subtitleSlot>

    <x-slot:topbarTitle>KONTROL HARGA</x-slot:topbarTitle>

    <x-slot:menuSlot>
        @include('dashboard.admin._menu')
    </x-slot:menuSlot>

    {{-- Layout dua kolom: dua kartu tier harga berdampingan + tombol aksi di bawah --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

        {{-- Harga Distributor --}}
        <div class="bg-white border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-primary-darkest)]">
            <div class="bg-primary px-6 py-3 flex items-center justify-between">
                <span class="font-headline font-black text-white text-base uppercase tracking-tight">Harga Distributor</span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-secondary bg-black/20 px-2 py-1">Tier 1</span>
            </div>
            <div class="p-6">
                <label class="block text-[10px] font-bold text-secondary uppercase tracking-widest mb-2" for="harga-distributor">Harga per Unit</label>
                <div class="relative border-[3px] border-primary shadow-[4px_4px_0_var(--color-gray-900)] bg-white">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 font-headline font-black text-lg text-primary/40">Rp</span>
                    {{-- BACKEND-TODO: value dari DB (pricings table, tier=distributor) --}}
                    <input id="harga-distributor" type="number" value="1250000"
                        aria-label="Harga per unit untuk Distributor"
                        class="w-full bg-transparent py-3 pl-12 pr-4 font-headline font-black text-2xl text-primary text-right focus:outline-none focus:ring-0">
                </div>
                <div class="flex justify-between mt-2">
                    <span class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">Diperbarui: 12 jam lalu</span>
                    <span class="text-[10px] font-bold text-green-700">↑ +2.5% MoM</span>
                </div>
            </div>
        </div>

        {{-- Harga Reseller --}}
        <div class="bg-white border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-secondary)]">
            <div class="bg-secondary px-6 py-3 flex items-center justify-between">
                <span class="font-headline font-black text-white text-base uppercase tracking-tight">Harga Reseller</span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-white/60 bg-black/20 px-2 py-1">Tier 2</span>
            </div>
            <div class="p-6">
                <label class="block text-[10px] font-bold text-secondary uppercase tracking-widest mb-2" for="harga-reseller">Harga per Unit</label>
                <div class="relative border-[3px] border-secondary shadow-[4px_4px_0_var(--color-gray-900)] bg-white">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 font-headline font-black text-lg text-secondary/40">Rp</span>
                    {{-- BACKEND-TODO: value dari DB (pricings table, tier=reseller) --}}
                    <input id="harga-reseller" type="number" value="1450000"
                        aria-label="Harga per unit untuk Reseller"
                        class="w-full bg-transparent py-3 pl-12 pr-4 font-headline font-black text-2xl text-primary text-right focus:outline-none focus:ring-0">
                </div>
                <div class="flex justify-between mt-2">
                    <span class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">Margin Terkunci: 16%</span>
                    <span class="text-[10px] font-bold text-green-700">↑ +1.2% MoM</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Tombol Deploy --}}
    {{-- BACKEND-TODO: Tambahkan <form method="POST" action="{{ route('admin.pricing.update') }}"> + @csrf --}}
    <button type="button"
        aria-label="Terapkan pembaruan harga ke seluruh sistem"
        class="w-full max-w-xl bg-primary text-white py-4 font-headline font-black text-base uppercase tracking-widest border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-1 active:shadow-none transition-all flex items-center justify-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
        </svg>
        Terapkan Pembaruan Harga
    </button>
</x-layouts.dashboard>
