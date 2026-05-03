<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">PORTAL DISTRIBUTOR</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>JARINGAN RESELLER</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.distributor._menu')</x-slot:menuSlot>

    <div x-data="{ 
        viewMode: 'list', 
        selectedReseller: null,
        openDetail(reseller) {
            this.selectedReseller = reseller;
            this.viewMode = 'detail';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }" class="max-w-[1400px] mx-auto w-full">
        
        {{-- VIEW: LIST RESELLER --}}
        <div x-show="viewMode === 'list'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            {{-- Header & Title Section --}}
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-8 gap-6">
                <div>
                    <h2 class="font-headline font-black text-3xl text-primary tracking-tighter uppercase leading-none">Jaringan Reseller</h2>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-3 italic">Pantau & Kelola Aktivitas Reseller Anda</p>
                </div>
                
                {{-- Total Counter Widget --}}
                <div class="bg-white border-[3px] border-gray-900 px-6 py-4 shadow-[4px_4px_0_var(--color-primary-darkest)] flex flex-col items-center justify-center min-w-[120px]">
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Reseller</span>
                    {{-- BACKEND-TODO: data dari Reseller::where('distributor_id', Auth::id())->count() --}}
                    <span class="font-headline font-black text-2xl text-primary leading-none">24</span>
                </div>
            </div>

            {{-- Filter & Search Section --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div class="flex gap-4 flex-wrap w-full md:w-auto">
                    <div class="relative w-full md:w-80">
                        <input type="text" placeholder="Cari Reseller / Wilayah..." 
                            class="w-full bg-white border-[3px] border-gray-900 px-4 py-2 text-xs font-bold uppercase tracking-widest text-primary focus:outline-none focus:border-secondary shadow-[3px_3px_0_rgba(0,0,0,0.05)] placeholder:text-slate-300">
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                    </div>
                    <div class="relative">
                        <select aria-label="Filter status reseller" class="appearance-none bg-white border-[3px] border-gray-900 px-4 py-2 text-xs font-bold uppercase tracking-widest text-primary focus:outline-none focus:border-secondary cursor-pointer pr-8 shadow-[3px_3px_0_rgba(0,0,0,0.05)]">
                            <option>Semua Status</option>
                            <option>Aktif</option>
                            <option>Non-Aktif</option>
                            <option>Peralihan</option>
                        </select>
                        <div class="absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                    </div>
                </div>
                <p class="hidden sm:block text-[9px] font-bold text-slate-400 uppercase tracking-widest italic">
                    ℹ️ Klik baris untuk melihat detail performa
                </p>
            </div>

            <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">
                {{-- Kolom Header --}}
                <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-4 bg-gray-900">
                    <div class="col-span-4 text-[10px] font-headline font-bold text-white uppercase tracking-widest">Nama Reseller</div>
                    <div class="col-span-3 text-[10px] font-headline font-bold text-white uppercase tracking-widest">Lokasi & Asal</div>
                    <div class="col-span-2 text-[10px] font-headline font-bold text-white uppercase tracking-widest">Pesanan Terakhir</div>
                    <div class="col-span-2 text-[10px] font-headline font-bold text-white uppercase tracking-widest text-center">Status</div>
                    <div class="col-span-1 text-[10px] font-headline font-bold text-white uppercase tracking-widest text-right">Ket.</div>
                </div>

                <div class="divide-y-2 divide-neutral-border">
                    {{-- Item 1 --}}
                    <div @click="openDetail({ name: 'PT. Maju Logistik', city: 'Bandung', isPeralihan: false })"
                         class="flex flex-col md:grid md:grid-cols-12 gap-4 px-6 py-5 items-start md:items-center border-l-[5px] border-secondary hover:bg-neutral-light cursor-pointer transition-colors duration-150 group">
                        <div class="md:col-span-4 w-full">
                            <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nama Reseller</p>
                            <div class="font-bold text-sm text-gray-900 uppercase group-hover:text-primary transition-colors">PT. Maju Logistik</div>
                        </div>
                        <div class="md:col-span-3 w-full flex justify-between md:block">
                            <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest">Lokasi</p>
                            <div class="text-xs text-slate-500 font-bold uppercase tracking-widest">Bandung</div>
                        </div>
                        <div class="md:col-span-2 w-full flex justify-between md:block">
                            <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest">Pesanan Terakhir</p>
                            <div class="text-xs text-slate-500 font-bold">12 Okt 2024</div>
                        </div>
                        <div class="md:col-span-2 w-full flex justify-between md:block md:text-center">
                            <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest">Status</p>
                            <span class="px-2 py-1 border-2 border-secondary text-secondary text-[9px] font-bold uppercase tracking-widest">Aktif</span>
                        </div>
                        <div class="md:col-span-1 w-full flex justify-between md:block md:text-right">
                            <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest">Ket.</p>
                            <span class="text-[9px] font-bold text-slate-400">—</span>
                        </div>
                    </div>

                    {{-- Item 2 --}}
                    <div @click="openDetail({ name: 'Teknik Karya Supply', city: 'Bekasi', isPeralihan: false })"
                         class="flex flex-col md:grid md:grid-cols-12 gap-4 px-6 py-5 items-start md:items-center border-l-[5px] border-secondary hover:bg-neutral-light cursor-pointer transition-colors duration-150 group">
                        <div class="md:col-span-4 w-full">
                            <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nama Reseller</p>
                            <div class="font-bold text-sm text-gray-900 uppercase group-hover:text-primary transition-colors">Teknik Karya Supply</div>
                        </div>
                        <div class="md:col-span-3 w-full flex justify-between md:block">
                            <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest">Lokasi</p>
                            <div class="text-xs text-slate-500 font-bold uppercase tracking-widest">Bekasi</div>
                        </div>
                        <div class="md:col-span-2 w-full flex justify-between md:block">
                            <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest">Pesanan Terakhir</p>
                            <div class="text-xs text-slate-500 font-bold">05 Okt 2024</div>
                        </div>
                        <div class="md:col-span-2 w-full flex justify-between md:block md:text-center">
                            <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest">Status</p>
                            <span class="px-2 py-1 border-2 border-secondary text-secondary text-[9px] font-bold uppercase tracking-widest">Aktif</span>
                        </div>
                        <div class="md:col-span-1 w-full flex justify-between md:block md:text-right">
                            <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest">Ket.</p>
                            <span class="text-[9px] font-bold text-slate-400">—</span>
                        </div>
                    </div>

                    {{-- Item 3: Contoh Reseller Peralihan --}}
                    <div @click="openDetail({ name: 'Indo Cipta Chem', city: 'Cirebon', isPeralihan: true, origin: 'Aceh' })"
                         class="flex flex-col md:grid md:grid-cols-12 gap-4 px-6 py-6 md:py-5 items-start md:items-center border-l-[6px] border-yellow-400 bg-yellow-50/30 hover:bg-yellow-50 cursor-pointer transition-colors duration-150 relative overflow-hidden group">
                        <div class="absolute -right-4 -bottom-2 opacity-[0.03] pointer-events-none select-none">
                            <span class="font-headline font-black text-6xl uppercase italic text-gray-900">PERALIHAN</span>
                        </div>
                        <div class="md:col-span-4 w-full">
                            <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nama Reseller</p>
                            <div class="flex items-center gap-3">
                                <div class="font-bold text-sm text-gray-900 uppercase group-hover:text-primary transition-colors">Indo Cipta Chem</div>
                                <span class="animate-pulse hidden md:inline-block w-2.5 h-2.5 rounded-full bg-yellow-500 shadow-[0_0_8px_rgba(234,179,8,0.6)] border border-yellow-600"></span>
                            </div>
                        </div>
                        <div class="md:col-span-3 w-full flex justify-between md:block">
                            <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest">Lokasi & Asal</p>
                            <div class="text-xs text-slate-500 font-bold uppercase tracking-widest">Cirebon</div>
                            <div class="text-[9px] font-bold text-secondary uppercase tracking-widest mt-1 italic">Asal: Aceh (Peralihan)</div>
                        </div>
                        <div class="md:col-span-2 w-full flex justify-between md:block">
                            <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest">Pesanan Terakhir</p>
                            <div class="text-xs text-slate-500 font-bold">22 Agu 2024</div>
                        </div>
                        <div class="md:col-span-2 w-full flex justify-between md:block md:text-center">
                            <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest">Status</p>
                            <div class="flex flex-col md:items-center">
                                <span class="px-2 py-1 border-2 border-secondary text-secondary text-[9px] font-bold uppercase tracking-widest">Aktif</span>
                            </div>
                        </div>
                        <div class="md:col-span-1 w-full flex justify-between md:block md:text-right">
                            <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest">Ket.</p>
                            <div class="flex flex-col items-end gap-1">
                                <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-yellow-400 text-gray-900 border-[2px] border-gray-900 text-[8px] font-black uppercase tracking-wider whitespace-nowrap shadow-[2px_2px_0_var(--color-gray-900)]">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    Peralihan
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- VIEW: DETAIL PERFORMA RESELLER --}}
        <div x-show="viewMode === 'detail'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            {{-- Back Button & Header --}}
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-10 gap-6">
                <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                    <x-ui.back-button @click="viewMode = 'list'" />
                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="font-headline font-black text-3xl md:text-4xl text-primary tracking-tighter uppercase leading-none" x-text="selectedReseller?.name"></h2>
                            <template x-if="selectedReseller?.isPeralihan">
                                <span class="px-2 py-1 bg-yellow-400 text-gray-900 text-[10px] font-black uppercase border-2 border-gray-900 shadow-[2px_2px_0_var(--color-gray-900)]">PERALIHAN</span>
                            </template>
                        </div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-2">
                            <span class="text-gray-900">ID: RSL-77291</span> • 
                            <span x-text="selectedReseller?.city"></span>
                            <template x-if="selectedReseller?.isPeralihan">
                                <span class="text-secondary italic" x-text="' (Asal: ' + selectedReseller?.origin + ')'"></span>
                            </template>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 w-full lg:w-auto">
                    <button class="flex-1 lg:flex-none bg-[#25D366] text-white border-[3px] border-gray-900 px-6 py-3 text-[10px] font-black uppercase tracking-widest hover:bg-[#1DA851] shadow-[4px_4px_0_var(--color-gray-900)] active:shadow-none active:translate-y-1 transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.347-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        Hubungi WA
                    </button>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white border-[4px] border-gray-900 p-6 shadow-[8px_8px_0_var(--color-primary)] flex flex-col justify-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Belanja</p>
                    <p class="font-headline font-black text-3xl text-gray-900">Rp 12.4M</p>
                    <p class="text-[9px] font-bold text-green-600 uppercase mt-2">↑ 12% Bulan ini</p>
                </div>
                <div class="bg-white border-[4px] border-gray-900 p-6 shadow-[8px_8px_0_var(--color-secondary)] flex flex-col justify-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Volume (PCS)</p>
                    <p class="font-headline font-black text-3xl text-gray-900 text-secondary">8.250</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase mt-2 italic">Target: 10.000</p>
                </div>
                <div class="bg-white border-[4px] border-gray-900 p-6 shadow-[8px_8px_0_var(--color-gray-900)] flex flex-col justify-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Frekuensi Order</p>
                    <p class="font-headline font-black text-3xl text-gray-900">24 <span class="text-sm">Kali</span></p>
                    <p class="text-[9px] font-bold text-primary uppercase mt-2">Rata-rata: 2x / Bulan</p>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-8">
                {{-- Riwayat Transaksi Reseller (Preview) --}}
                <div class="lg:col-span-2">
                    <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_rgba(0,0,0,0.05)]">
                        <div class="bg-gray-900 px-6 py-4 flex justify-between items-center">
                            <h3 class="font-headline font-black text-white text-base uppercase tracking-tight">Riwayat Transaksi</h3>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest italic">Data Real-time</span>
                        </div>
                        <div class="divide-y-2 divide-neutral-border">
                            {{-- Header --}}
                            <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-4 bg-neutral-light border-b-2 border-neutral-border">
                                <div class="col-span-3 text-[9px] font-black uppercase text-slate-400">Order ID</div>
                                <div class="col-span-3 text-[9px] font-black uppercase text-slate-400">Produk</div>
                                <div class="col-span-2 text-[9px] font-black uppercase text-slate-400 text-right">Vol</div>
                                <div class="col-span-4 text-[9px] font-black uppercase text-slate-400 text-right">Status</div>
                            </div>
                            
                            {{-- Rows --}}
                            <div class="divide-y-2 divide-neutral-border">
                                <div class="grid grid-cols-12 gap-4 items-center px-6 py-4 hover:bg-neutral-light transition-colors">
                                    <div class="col-span-3 font-bold text-xs">#ORD-201</div>
                                    <div class="col-span-3 font-bold text-xs text-slate-500 uppercase">CeeKlin 450ml</div>
                                    <div class="col-span-2 font-headline font-black text-sm text-right text-primary">100</div>
                                    <div class="col-span-4 flex justify-end">
                                        <span class="px-2 py-0.5 border-2 border-green-600 text-green-700 text-[8px] font-black uppercase tracking-widest bg-green-50">Selesai</span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-12 gap-4 items-center px-6 py-4 hover:bg-neutral-light transition-colors">
                                    <div class="col-span-3 font-bold text-xs">#ORD-188</div>
                                    <div class="col-span-3 font-bold text-xs text-slate-500 uppercase">CeeKlin 450ml</div>
                                    <div class="col-span-2 font-headline font-black text-sm text-right text-primary">50</div>
                                    <div class="col-span-4 flex justify-end">
                                        <span class="px-2 py-0.5 border-2 border-green-600 text-green-700 text-[8px] font-black uppercase tracking-widest bg-green-50">Selesai</span>
                                    </div>
                                </div>
                            </div>

                            {{-- View More --}}
                            <div class="px-6 py-3 bg-neutral-light">
                                <button @click="viewMode = 'all_transactions'" class="text-[9px] font-black uppercase tracking-widest text-primary hover:text-secondary transition-colors">Lihat Semua Transaksi →</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Info Detail & Alamat --}}
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">
                        <div class="bg-primary px-6 py-3 border-b-[4px] border-gray-900">
                            <h3 class="font-headline font-black text-white text-sm uppercase tracking-tight">Detail Profil Reseller</h3>
                        </div>
                        <div class="p-6 space-y-6 bg-white">
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Nomor WhatsApp</p>
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-bold text-gray-900">0812-3456-7890</p>
                                    <span class="px-1.5 py-0.5 bg-green-100 text-green-700 text-[8px] font-bold uppercase rounded">Verified</span>
                                </div>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Alamat Lengkap</p>
                                <p class="text-sm font-bold text-gray-900 leading-relaxed uppercase">JL. RAYA BANDUNG NO. 123, RT 01 RW 02, KEC. COBLONG, KOTA BANDUNG, JAWA BARAT 40135</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Bergabung Sejak</p>
                                    <p class="text-sm font-bold text-gray-900 uppercase">14 Jan 2024</p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Status Keanggotaan</p>
                                    <p class="text-sm font-bold text-primary uppercase">Reguler</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- VIEW: SEMUA TRANSAKSI RESELLER --}}
        <div x-show="viewMode === 'all_transactions'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0">
            {{-- Header --}}
            <div class="flex flex-col md:flex-row items-start md:items-center mb-8 gap-6">
                <x-ui.back-button @click="viewMode = 'detail'" />
                <div>
                    <h2 class="font-headline font-black text-3xl text-primary tracking-tighter uppercase leading-none">Histori Lengkap Transaksi</h2>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-2" x-text="'Seluruh Rekam Jejak Pesanan ' + selectedReseller?.name"></p>
                </div>
            </div>

            <div class="bg-white border-[4px] border-gray-900 shadow-[10px_10px_0_var(--color-primary-darkest)]">
                {{-- Header Tabel --}}
                <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-4 bg-gray-900">
                    <div class="col-span-2 text-[10px] font-headline font-bold text-white uppercase tracking-widest">Order ID</div>
                    <div class="col-span-3 text-[10px] font-headline font-bold text-white uppercase tracking-widest">Produk</div>
                    <div class="col-span-2 text-[10px] font-headline font-bold text-white uppercase tracking-widest text-right">Volume</div>
                    <div class="col-span-2 text-[10px] font-headline font-bold text-white uppercase tracking-widest text-right">Total</div>
                    <div class="col-span-3 text-[10px] font-headline font-bold text-white uppercase tracking-widest text-center">Status & Tanggal</div>
                </div>

                <div class="divide-y-2 divide-neutral-border">
                    @php
                    $fullHistory = [
                        ['id' => '#ORD-201', 'date' => '12 Okt 2024', 'product' => 'CeeKlin 450ml', 'qty' => '100', 'total' => 'Rp 1.500.000', 'status' => 'Selesai'],
                        ['id' => '#ORD-188', 'date' => '05 Okt 2024', 'product' => 'CeeKlin 450ml', 'qty' => '50', 'total' => 'Rp 750.000', 'status' => 'Selesai'],
                        ['id' => '#ORD-165', 'date' => '20 Sep 2024', 'product' => 'CeeKlin 1L', 'qty' => '200', 'total' => 'Rp 4.000.000', 'status' => 'Selesai'],
                        ['id' => '#ORD-142', 'date' => '12 Sep 2024', 'product' => 'CeeKlin 450ml', 'qty' => '100', 'total' => 'Rp 1.500.000', 'status' => 'Selesai'],
                        ['id' => '#ORD-130', 'date' => '01 Sep 2024', 'product' => 'CeeKlin 450ml', 'qty' => '300', 'total' => 'Rp 4.500.000', 'status' => 'Dibatalkan'],
                    ];
                    @endphp

                    @foreach($fullHistory as $item)
                    <div class="flex flex-col md:grid md:grid-cols-12 gap-4 px-6 py-4 items-start md:items-center hover:bg-neutral-light transition-colors">
                        <div class="md:col-span-2 font-bold text-xs uppercase">{{ $item['id'] }}</div>
                        <div class="md:col-span-3">
                            <p class="text-xs font-bold text-gray-900 uppercase">{{ $item['product'] }}</p>
                        </div>
                        <div class="md:col-span-2 md:text-right font-headline font-black text-sm text-primary tracking-tight">{{ $item['qty'] }} <span class="text-[9px] font-body text-slate-400">PCS</span></div>
                        <div class="md:col-span-2 md:text-right font-bold text-xs text-gray-900">{{ $item['total'] }}</div>
                        <div class="md:col-span-3 flex flex-col md:items-center gap-1">
                            <span class="px-2 py-0.5 border-2 {{ $item['status'] === 'Selesai' ? 'border-green-600 text-green-700 bg-green-50' : 'border-red-500 text-red-700 bg-red-50' }} text-[8px] font-black uppercase tracking-widest">{{ $item['status'] }}</span>
                            <p class="text-[9px] font-bold text-slate-400 uppercase">{{ $item['date'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
