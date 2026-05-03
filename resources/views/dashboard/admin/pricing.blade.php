<x-layouts.dashboard bgTheme="light">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">MASTER ADMIN</span>
    </x-slot:subtitleSlot>

    <x-slot:topbarTitle>KONTROL HARGA NASIONAL</x-slot:topbarTitle>

    <x-slot:menuSlot>
        @include('dashboard.admin._menu')
    </x-slot:menuSlot>

    <div class="max-w-[1400px] mx-auto w-full" x-data="{
        viewMode: 'standard',
        showSuccess: false,
        successMsg: '',
        saveChanges() {
            this.showSuccess = true;
            this.successMsg = 'Harga Berhasil Diperbarui!';
            setTimeout(() => this.showSuccess = false, 3000);
        }
    }">
        {{-- Header & Info --}}
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-10 gap-6 italic">
            <div>
                <h2 class="font-headline font-black text-3xl text-primary tracking-tighter uppercase leading-none italic">Manajemen Harga Produk</h2>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-3 italic">Atur harga jual standar dan penyesuaian khusus per wilayah</p>
            </div>
            <div class="flex gap-4 italic">
                <button @click="viewMode = 'standard'" 
                    class="px-5 py-2.5 text-[10px] font-headline font-black uppercase tracking-widest border-[3px] transition-all italic"
                    :class="viewMode === 'standard' ? 'bg-primary text-white border-gray-900 shadow-[4px_4px_0_var(--color-secondary)]' : 'bg-white text-gray-400 border-gray-200 hover:border-gray-900 hover:text-gray-900'">
                    🏁 Standar Nasional
                </button>
            </div>
        </div>

        {{-- VIEW 1: STANDARD NATIONAL PRICE --}}
        <div x-show="viewMode === 'standard'" x-transition italic">
            <form action="{{ route('admin.pricing.update') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10 italic">
                    {{-- Harga Distributor Card --}}
                    <div class="bg-white border-[4px] border-gray-900 shadow-[10px_10px_0_var(--color-primary-darkest)] italic">
                        <div class="bg-primary px-8 py-4 border-b-4 border-gray-900 italic">
                            <h3 class="font-headline font-black text-white text-xl uppercase tracking-tight italic">Harga Distributor (Nasional)</h3>
                        </div>
                        <div class="p-8 italic">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-3 italic">Nominal Per Unit (Standard)</label>
                            <div class="relative group italic">
                                <span class="absolute left-5 top-1/2 -translate-y-1/2 font-headline font-black text-2xl text-primary/30 italic">Rp</span>
                                <input type="number" name="distributor_price" value="{{ $distributorPrice->price ?? 13000 }}" class="w-full bg-neutral-light border-[4px] border-gray-900 py-6 pl-16 pr-8 font-headline font-black text-5xl text-primary focus:outline-none focus:border-secondary transition-colors italic">
                            </div>
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-4 italic">Terhitung sebagai harga dasar di seluruh Indonesia</p>
                        </div>
                    </div>

                    {{-- Harga Reseller Card --}}
                    <div class="bg-white border-[4px] border-gray-900 shadow-[10px_10px_0_var(--color-secondary)] italic">
                        <div class="bg-secondary px-8 py-4 border-b-4 border-gray-900 italic">
                            <h3 class="font-headline font-black text-gray-900 text-xl uppercase tracking-tight italic">Harga Reseller (Nasional)</h3>
                        </div>
                        <div class="p-8 italic">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-3 italic">Nominal Per Unit (Standard)</label>
                            <div class="relative group italic">
                                <span class="absolute left-5 top-1/2 -translate-y-1/2 font-headline font-black text-2xl text-secondary/40 italic">Rp</span>
                                <input type="number" name="reseller_price" value="{{ $resellerPrice->price ?? 15000 }}" class="w-full bg-neutral-light border-[4px] border-gray-900 py-6 pl-16 pr-8 font-headline font-black text-5xl text-primary focus:outline-none focus:border-primary transition-colors italic">
                            </div>
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-4 italic">Harga jual standar ke pelanggan akhir via reseller</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center mt-12 italic">
                    <button type="submit" class="w-full lg:max-w-md bg-primary text-white py-5 font-headline font-black text-sm uppercase tracking-widest border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-gray-900)] hover:bg-primary-hover transition-all active:translate-y-1 active:shadow-none italic">
                        SIMPAN HARGA NASIONAL
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.dashboard>
