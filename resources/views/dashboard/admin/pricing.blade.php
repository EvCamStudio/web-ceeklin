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
        distPrice: {{ $distributorPrice->price ?? 13000 }},
        resPrice: {{ $resellerPrice->price ?? 15000 }},
        regionalPrices: [],
        isConfirming: false,
        adminPassword: '',

        saveChanges() {
            if (!this.adminPassword) {
                alert('Silakan masukkan kata sandi Anda untuk konfirmasi.');
                return;
            }
            this.$refs.priceForm.submit();
        }
    }">
        <form x-ref="priceForm" action="{{ route('admin.pricing.update') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="distributor_price" :value="distPrice">
            <input type="hidden" name="reseller_price" :value="resPrice">
            <input type="hidden" name="current_password" :value="adminPassword">
        </form>

        {{-- Header & Info --}}
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-10 gap-6">
            <div>
                <h2 class="font-headline font-black text-3xl text-primary tracking-tighter uppercase leading-none">
                    Manajemen Harga Produk</h2>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-3 italic">Atur harga jual
                    standar dan penyesuaian khusus per wilayah</p>
            </div>
            <div class="flex gap-4">
                <button @click="viewMode = 'standard'"
                    class="px-5 py-2.5 text-[10px] font-headline font-black uppercase tracking-widest border-[3px] transition-all"
                    :class="viewMode === 'standard' ? 'bg-primary text-white border-gray-900 shadow-[4px_4px_0_var(--color-secondary)]' : 'bg-white text-gray-400 border-gray-200 hover:border-gray-900 hover:text-gray-900'">
                    Standar Nasional
                </button>
                <button @click="viewMode = 'regional'"
                    class="relative px-5 py-2.5 text-[10px] font-headline font-black uppercase tracking-widest border-[3px] transition-all"
                    :class="viewMode === 'regional' ? 'bg-primary text-white border-gray-900 shadow-[4px_4px_0_var(--color-secondary)]' : 'bg-white text-gray-400 border-gray-200 hover:border-gray-900 hover:text-gray-900'">
                    Harga Per Wilayah
                    <span class="absolute -top-3 -right-2 bg-secondary text-gray-900 text-[7px] px-1 py-0.5 border border-gray-900 shadow-[2px_2px_0_var(--color-primary)]">SOON</span>
                </button>
            </div>
        </div>
        
        {{-- VIEW 1: STANDARD NATIONAL PRICE --}}
        <div x-show="viewMode === 'standard'" x-transition>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                {{-- Harga Distributor Card --}}
                <div class="bg-white border-[4px] border-gray-900 shadow-[10px_10px_0_var(--color-primary-darkest)]">
                    <div class="bg-primary px-8 py-4 border-b-4 border-gray-900">
                        <h3 class="font-headline font-black text-white text-xl uppercase tracking-tight">Harga
                            Distributor (Nasional)</h3>
                    </div>
                    <div class="p-8">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-3">Nominal
                            Per Unit (Standard)</label>
                        <div class="relative group">
                            <span
                                class="absolute left-5 top-1/2 -translate-y-1/2 font-headline font-black text-2xl text-primary/30">Rp</span>
                            <input type="number" x-model="distPrice"
                                class="w-full bg-neutral-light border-[4px] border-gray-900 py-6 pl-16 pr-8 font-headline font-black text-5xl text-primary focus:outline-none focus:border-secondary transition-colors">
                            <x-ui.error name="distributor_price" />
                        </div>
                        <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-4">Terhitung sebagai
                            harga dasar di seluruh Indonesia</p>
                    </div>
                </div>

                {{-- Harga Reseller Card --}}
                <div class="bg-white border-[4px] border-gray-900 shadow-[10px_10px_0_var(--color-secondary)]">
                    <div class="bg-secondary px-8 py-4 border-b-4 border-gray-900">
                        <h3 class="font-headline font-black text-gray-900 text-xl uppercase tracking-tight">Harga
                            Reseller (Nasional)</h3>
                    </div>
                    <div class="p-8">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-3">Nominal
                            Per Unit (Standard)</label>
                        <div class="relative group">
                            <span
                                class="absolute left-5 top-1/2 -translate-y-1/2 font-headline font-black text-2xl text-secondary/40">Rp</span>
                            <input type="number" x-model="resPrice"
                                class="w-full bg-neutral-light border-[4px] border-gray-900 py-6 pl-16 pr-8 font-headline font-black text-5xl text-primary focus:outline-none focus:border-primary transition-colors">
                            <x-ui.error name="reseller_price" />
                        </div>
                        <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-4">Harga jual standar
                            ke pelanggan akhir via reseller</p>
                    </div>
                </div>
            </div>

            {{-- Confirm Section Inline --}}
            <div class="max-w-2xl mx-auto mt-12 overflow-hidden">
                <div x-show="!isConfirming" class="flex justify-center">
                    <button @click="isConfirming = true"
                        class="w-full bg-primary text-white py-6 font-headline font-black text-base uppercase tracking-widest border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-gray-900)] hover:bg-primary-hover transition-all active:translate-y-1 active:shadow-none">
                        SIMPAN HARGA NASIONAL
                    </button>
                </div>

                <div x-show="isConfirming" style="display: none;" x-transition
                    class="bg-white border-[4px] border-gray-900 p-8 shadow-[10px_10px_0_var(--color-primary-darkest)] relative">
                    <button @click="isConfirming = false; adminPassword = ''" 
                        class="absolute right-6 top-6 text-[10px] font-black text-slate-300 hover:text-red-600 uppercase tracking-widest italic">✕ Batal</button>
                    
                    <div class="flex flex-col items-center text-center">
                        <div class="w-12 h-12 bg-red-50 text-red-600 flex items-center justify-center border-2 border-red-600 mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <h4 class="font-headline font-black text-2xl text-gray-900 uppercase italic mb-2 tracking-tighter">Konfirmasi Keamanan</h4>
                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-8">Masukkan Kata Sandi Admin Untuk Mengubah Harga Nasional</p>
                        
                        <div class="w-full max-w-sm space-y-6">
                            <div class="relative">
                                <input type="password" x-model="adminPassword" placeholder="••••••••"
                                    @keydown.enter="saveChanges()"
                                    class="w-full bg-neutral-light border-[4px] border-gray-900 px-6 py-4 text-center font-black text-xl tracking-[0.5em] focus:outline-none focus:border-primary transition-all">
                                <x-ui.error name="current_password" />
                            </div>
                            
                            <button @click="saveChanges()"
                                class="w-full bg-gray-900 text-white py-6 font-headline font-black text-base uppercase tracking-widest shadow-[8px_8px_0_var(--color-primary)] hover:bg-primary transition-all active:translate-y-1 active:shadow-none flex items-center justify-center gap-4">
                                <span>KONFIRMASI & SIMPAN</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- VIEW 2: REGIONAL PRICE OVERRIDES --}}
        <div x-show="viewMode === 'regional'" x-transition style="display: none;">
            <div
                class="bg-white border-[4px] border-gray-900 shadow-[10px_10px_0_var(--color-primary-darkest)] overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-900 text-white">
                        <tr>
                            <th class="px-8 py-4 text-[10px] font-headline font-bold uppercase tracking-widest">Wilayah
                                / Provinsi</th>
                            <th
                                class="px-6 py-4 text-[10px] font-headline font-bold uppercase tracking-widest text-center">
                                Tipe Harga</th>
                            <th
                                class="px-6 py-4 text-[10px] font-headline font-bold uppercase tracking-widest text-right">
                                Harga Distributor</th>
                            <th
                                class="px-6 py-4 text-[10px] font-headline font-bold uppercase tracking-widest text-right">
                                Harga Reseller</th>
                            <th
                                class="px-8 py-4 text-[10px] font-headline font-bold uppercase tracking-widest text-right">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 divide-neutral-border text-gray-900">
                        <template x-for="item in regionalPrices" :key="item.province">
                            <tr class="hover:bg-neutral-light transition-colors group">
                                <td class="px-8 py-5">
                                    <p class="font-headline font-black text-sm uppercase tracking-tight"
                                        x-text="item.province"></p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase mt-1">Indonesia</p>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="px-2 py-1 text-[9px] font-black uppercase tracking-widest"
                                        :class="item.isStandard ? 'bg-slate-100 text-slate-400' : 'bg-primary/10 text-primary border border-primary/20'"
                                        x-text="item.isStandard ? 'Standard' : 'Custom'"></span>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <span class="text-[10px] font-bold text-slate-400">Rp</span>
                                        <input type="number" x-model="item.distributor"
                                            class="w-24 bg-transparent border-b-2 border-transparent focus:border-primary focus:outline-none text-right font-headline font-black text-lg text-primary">
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <span class="text-[10px] font-bold text-slate-400">Rp</span>
                                        <input type="number" x-model="item.reseller"
                                            class="w-24 bg-transparent border-b-2 border-transparent focus:border-secondary focus:outline-none text-right font-headline font-black text-lg text-primary">
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <button @click="saveChanges()"
                                        class="p-2 bg-white border-2 border-gray-900 shadow-[3px_3px_0_var(--color-primary)] hover:bg-primary hover:text-white transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="mt-8 p-6 bg-secondary/10 border-4 border-dashed border-secondary flex items-start gap-4">
                <div class="w-10 h-10 bg-secondary flex items-center justify-center border-2 border-gray-900 shrink-0">
                    <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h5 class="text-[11px] font-black uppercase tracking-widest text-gray-900 mb-1">Tips Regional
                        Pricing</h5>
                    <p class="text-[11px] font-bold text-slate-600 leading-relaxed italic">
                        Harga regional akan secara otomatis menimpa (override) harga nasional jika nilai di atas tidak
                        sama dengan harga standar.
                        Gunakan ini untuk menyesuaikan margin di wilayah dengan biaya logistik tinggi.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>