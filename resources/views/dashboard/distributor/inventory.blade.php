<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">PORTAL DISTRIBUTOR</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>STOK GUDANG</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.distributor._menu')</x-slot:menuSlot>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">

        {{-- Level Stok --}}
        <div class="xl:col-span-2 bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">
            <div class="bg-primary px-6 py-3">
                <span class="font-headline font-black text-white text-base uppercase tracking-tight">Level Stok Saat Ini</span>
            </div>
            <div class="p-6 flex flex-col gap-6">
                {{-- BACKEND-TODO: Loop dari Inventory::where('distributor_id', Auth::id())->get() --}}

                {{-- Produk: Stok Normal --}}
                <div>
                    <div class="flex justify-between items-end mb-2">
                        <span class="font-headline font-bold text-sm text-gray-900 uppercase tracking-tight">Industrial Degreaser</span>
                        <span class="font-headline font-black text-primary text-lg">2.450 / 5.000</span>
                    </div>
                    <div class="w-full bg-neutral-border-light border-2 border-neutral-border h-3">
                        <div class="bg-secondary h-full" style="width:49%"></div>
                    </div>
                </div>

                {{-- Produk: Stok Normal --}}
                <div>
                    <div class="flex justify-between items-end mb-2">
                        <span class="font-headline font-bold text-sm text-gray-900 uppercase tracking-tight">Heavy Duty Solvent</span>
                        <span class="font-headline font-black text-primary text-lg">1.120 / 2.000</span>
                    </div>
                    <div class="w-full bg-neutral-border-light border-2 border-neutral-border h-3">
                        <div class="bg-secondary h-full" style="width:56%"></div>
                    </div>
                </div>

                {{-- Produk: Stok Kritis --}}
                <div>
                    <div class="flex justify-between items-end mb-2">
                        <span class="font-headline font-bold text-sm text-gray-900 uppercase tracking-tight">Surface Prep</span>
                        <span class="font-headline font-black text-primary text-lg">450 / 3.000</span>
                    </div>
                    <div class="w-full bg-neutral-border-light border-2 border-neutral-border h-3">
                        <div class="bg-primary h-full" style="width:15%"></div>
                    </div>
                    <p class="text-[10px] font-bold text-primary uppercase tracking-widest mt-1.5 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        Stok Kritis — Segera Restock
                    </p>
                </div>

                {{-- Produk: Stok Normal --}}
                <div>
                    <div class="flex justify-between items-end mb-2">
                        <span class="font-headline font-bold text-sm text-gray-900 uppercase tracking-tight">Multi-Purpose Cleaner</span>
                        <span class="font-headline font-black text-primary text-lg">3.200 / 4.000</span>
                    </div>
                    <div class="w-full bg-neutral-border-light border-2 border-neutral-border h-3">
                        <div class="bg-secondary h-full" style="width:80%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Permintaan Restock --}}
        <div class="bg-white border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-secondary)]">
            <div class="bg-secondary px-6 py-3">
                <span class="font-headline font-black text-white text-base uppercase tracking-tight">Ajukan Restock</span>
            </div>
            <div class="p-6">
                {{-- BACKEND-TODO: action ke InventoryController@requestRestock + @csrf --}}
                <form class="flex flex-col gap-5">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[10px] font-bold text-secondary uppercase tracking-widest" for="produk-restock">Produk</label>
                        <div class="relative">
                            <select id="produk-restock" aria-label="Pilih produk untuk restock"
                                class="appearance-none w-full bg-neutral-light border-[3px] border-secondary px-4 py-2.5 font-body text-sm font-bold text-primary focus:outline-none focus:border-primary transition-colors cursor-pointer">
                                <option>Surface Prep</option>
                                <option>Industrial Degreaser</option>
                                <option>Heavy Duty Solvent</option>
                                <option>Multi-Purpose Cleaner</option>
                            </select>
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[10px] font-bold text-secondary uppercase tracking-widest" for="jumlah-restock">Jumlah (unit)</label>
                        <input id="jumlah-restock" type="number" placeholder="500" min="1"
                            class="bg-neutral-light border-[3px] border-secondary px-4 py-2.5 font-body text-sm font-bold text-primary focus:outline-none focus:border-primary transition-colors placeholder:text-primary/30">
                    </div>
                    <button type="button" aria-label="Kirim permintaan restock"
                        class="w-full bg-primary text-white py-3 font-headline font-bold text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-0.5 active:shadow-none transition-all mt-2">
                        Kirim Permintaan
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
