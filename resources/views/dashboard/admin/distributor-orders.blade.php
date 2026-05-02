<x-layouts.dashboard bgTheme="light">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">MASTER ADMIN</span>
    </x-slot:subtitleSlot>

    <x-slot:topbarTitle>PESANAN DISTRIBUTOR</x-slot:topbarTitle>

    <x-slot:menuSlot>
        @include('dashboard.admin._menu')
    </x-slot:menuSlot>

    <div class="max-w-[1400px] mx-auto w-full" x-data="{
        viewMode: 'list',
        selectedOrder: null,
        newStatus: '',
        searchQuery: '',
        dummies: [
            {id: 'ORD-1092', name: 'PT Tirta Makmur', phone: '081234567890', city: 'Bandung, Jawa Barat', qty: 5000, status: 'Menunggu Proses', statusColor: 'bg-red-100 text-red-800 border-red-300', date: 'Hari Ini, 08:15'},
            {id: 'ORD-1091', name: 'CV Bintang Selatan', phone: '08987654321', city: 'Surabaya, Jawa Timur', qty: 2500, status: 'Diproses', statusColor: 'bg-yellow-100 text-yellow-800 border-yellow-300', date: 'Kemarin, 14:30'},
            {id: 'ORD-1088', name: 'Distributor Abadi', phone: '08111222333', city: 'Semarang, Jawa Tengah', qty: 1000, status: 'Dikirim', statusColor: 'bg-blue-100 text-blue-800 border-blue-300', date: '2 Hari Lalu'},
        ],
        openOrder(order) {
            this.selectedOrder = order;
            this.newStatus = order.status;
            this.viewMode = 'detail';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        goBack() {
            this.viewMode = 'list';
            this.selectedOrder = null;
        },
        get filteredOrders() {
            if (!this.searchQuery) return this.dummies;
            return this.dummies.filter(o => 
                o.id.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                o.name.toLowerCase().includes(this.searchQuery.toLowerCase())
            );
        },
        getWaLink() {
            const phone = (this.selectedOrder?.phone ?? '').replace(/\D/g, '');
            const id = this.selectedOrder?.id ?? '';
            const status = this.newStatus;
            const msg = `Halo, Admin CeeKlin di sini.\n\nUpdate pesanan ${id}:\nStatus saat ini: *${status.toUpperCase()}*\n\nTerima kasih atas kerja samanya!`;
            return 'https://wa.me/' + (phone.startsWith('0') ? '62' + phone.substring(1) : phone) + '?text=' + encodeURIComponent(msg);
        }
    }">
        {{-- VIEW LIST: Tabel Pesanan --}}
        <div x-show="viewMode === 'list'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            {{-- Header Data --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <div>
                    <h2 class="font-headline font-black text-2xl text-primary tracking-tighter uppercase">Antrean Restock</h2>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-1">Kelola Pengiriman ke Gudang Distributor</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <div class="relative flex-1 sm:min-w-[250px]">
                        <input type="text" x-model="searchQuery" placeholder="Cari No. Order / Distributor..." 
                            class="w-full bg-white border-[3px] border-gray-900 px-4 py-2 text-xs font-bold uppercase tracking-widest text-primary focus:outline-none focus:border-secondary pr-10">
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                    </div>
                    <select class="appearance-none bg-white border-[3px] border-gray-900 px-4 py-2 text-xs font-bold uppercase tracking-widest text-primary focus:outline-none focus:border-secondary cursor-pointer">
                        <option>Semua Status</option>
                        <option>Menunggu Proses</option>
                        <option>Sedang Diproses</option>
                        <option>Dikirim</option>
                    </select>
                </div>
            </div>

            <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">
                <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-4 bg-gray-900 border-b-2 border-gray-900">
                    <div class="col-span-2 text-[10px] font-headline font-bold text-white uppercase tracking-widest">No. Order</div>
                    <div class="col-span-3 text-[10px] font-headline font-bold text-white uppercase tracking-widest">Distributor</div>
                    <div class="col-span-2 text-[10px] font-headline font-bold text-white uppercase tracking-widest text-center">Volume (PCS)</div>
                    <div class="col-span-3 text-[10px] font-headline font-bold text-white uppercase tracking-widest">Status & Waktu</div>
                    <div class="col-span-2 text-[10px] font-headline font-bold text-white uppercase tracking-widest text-right">Aksi</div>
                </div>

                <div class="divide-y-2 divide-neutral-border">
                    <template x-for="(order, index) in filteredOrders" :key="order.id">
                        <div :class="'animate-in stagger-' + (index % 5 + 1)"
                             class="flex flex-col md:grid md:grid-cols-12 gap-4 px-6 py-5 items-start md:items-center hover:bg-neutral-light transition-colors">
                            <div class="md:col-span-2 w-full">
                                <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">No. Order</p>
                                <p class="font-headline font-black text-sm text-gray-900 tracking-tight" x-text="order.id"></p>
                            </div>
                            <div class="md:col-span-3 w-full">
                                <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Distributor</p>
                                <p class="font-bold text-xs text-gray-900 uppercase leading-tight" x-text="order.name"></p>
                                <p class="text-[9px] font-bold text-slate-500 mt-1 uppercase" x-text="order.city"></p>
                            </div>
                            <div class="md:col-span-2 w-full md:text-center">
                                <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Volume</p>
                                <p class="text-sm font-headline font-black text-primary" x-text="new Intl.NumberFormat('id-ID').format(order.qty)"></p>
                            </div>
                            <div class="md:col-span-3 w-full">
                                <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Status & Waktu</p>
                                <span class="px-2 py-1 text-[9px] font-bold uppercase tracking-widest border block w-max mb-1" :class="order.statusColor" x-text="order.status"></span>
                                <p class="text-[9px] font-bold text-slate-400 uppercase" x-text="order.date"></p>
                            </div>
                            <div class="md:col-span-2 w-full flex justify-start md:justify-end">
                                <button @click="openOrder(order)" class="bg-white text-gray-900 px-4 py-2 text-[10px] font-bold uppercase tracking-widest border-2 border-gray-900 hover:bg-neutral-light shadow-[3px_3px_0_var(--color-gray-900)] active:translate-y-0.5 active:shadow-none transition-all">
                                    Kelola
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- VIEW DETAIL: Form Kelola Order (Inline) --}}
        <div x-show="viewMode === 'detail'" x-cloak style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            
            {{-- Tombol Kembali & Header --}}
            <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
                <div>
                    <h2 class="font-headline font-black text-2xl text-primary tracking-tighter uppercase">Kelola Pengiriman</h2>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1" x-text="'NO. ORDER: ' + selectedOrder?.id"></p>
                </div>
                <button @click="goBack()" class="flex items-center gap-2 bg-white text-gray-900 px-4 py-2 text-[10px] font-bold uppercase tracking-widest border-[3px] border-gray-900 hover:bg-neutral-light transition-colors shadow-[4px_4px_0_var(--color-gray-900)] active:translate-y-0.5 active:shadow-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Antrean
                </button>
            </div>

            <div class="bg-white border-[6px] border-gray-900 shadow-[12px_12px_0_var(--color-secondary)] w-full max-w-3xl flex flex-col mx-auto">
                
                {{-- Info Ringkas Order --}}
                <div class="p-6 md:p-8 flex-1 flex flex-col gap-6 bg-neutral border-b-[4px] border-gray-900">
                    <div class="bg-white border-[3px] border-gray-900 p-5 shadow-[4px_4px_0_var(--color-gray-900)] grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-[9px] font-bold text-secondary uppercase tracking-widest mb-1">Informasi Pemesan</p>
                            <h4 class="font-headline font-black text-xl text-primary uppercase mb-2" x-text="selectedOrder?.name"></h4>
                            <p class="font-bold text-gray-900 text-sm uppercase" x-text="selectedOrder?.city"></p>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-secondary uppercase tracking-widest mb-1">Pesanan Restock</p>
                            <h4 class="font-headline font-black text-3xl text-gray-900 tracking-tighter uppercase"><span x-text="new Intl.NumberFormat('id-ID').format(selectedOrder?.qty)"></span> <span class="text-lg text-slate-400">PCS</span></h4>
                            <p class="text-[10px] font-bold text-slate-500 uppercase mt-1">CeeKlin 450ml</p>
                        </div>
                    </div>
                </div>

                {{-- Form Update Status --}}
                <form action="/dashboard/admin/distributor-orders/update-status" method="POST">
                    @csrf
                    <input type="hidden" name="order_id" :value="selectedOrder?.id">
                    
                    <div class="p-6 md:p-8 flex flex-col gap-4 bg-white">
                        <label class="text-[10px] font-bold text-gray-900 uppercase tracking-widest">Update Progres Pengiriman</label>
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                            
                            {{-- Radio: Menunggu --}}
                            <label class="relative cursor-pointer">
                                <input type="radio" name="status" value="Menunggu Proses" x-model="newStatus" class="peer sr-only">
                                <div class="w-full bg-neutral-light border-[3px] border-gray-300 p-4 peer-checked:border-red-600 peer-checked:bg-red-50 transition-colors text-center">
                                    <p class="font-headline font-bold text-xs uppercase text-gray-500 peer-checked:text-red-700">Menunggu</p>
                                </div>
                            </label>

                            {{-- Radio: Diproses --}}
                            <label class="relative cursor-pointer">
                                <input type="radio" name="status" value="Diproses" x-model="newStatus" class="peer sr-only">
                                <div class="w-full bg-neutral-light border-[3px] border-gray-300 p-4 peer-checked:border-yellow-500 peer-checked:bg-yellow-50 transition-colors text-center">
                                    <p class="font-headline font-bold text-xs uppercase text-gray-500 peer-checked:text-yellow-700">Diproses</p>
                                </div>
                            </label>

                            {{-- Radio: Dikirim --}}
                            <label class="relative cursor-pointer">
                                <input type="radio" name="status" value="Dikirim" x-model="newStatus" class="peer sr-only">
                                <div class="w-full bg-neutral-light border-[3px] border-gray-300 p-4 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-colors text-center">
                                    <p class="font-headline font-bold text-xs uppercase text-gray-500 peer-checked:text-blue-700">Dikirim</p>
                                </div>
                            </label>

                            {{-- Radio: Selesai --}}
                            <label class="relative cursor-pointer">
                                <input type="radio" name="status" value="Selesai" x-model="newStatus" class="peer sr-only">
                                <div class="w-full bg-neutral-light border-[3px] border-gray-300 p-4 peer-checked:border-green-600 peer-checked:bg-green-50 transition-colors text-center">
                                    <p class="font-headline font-bold text-xs uppercase text-gray-500 peer-checked:text-green-700">Selesai</p>
                                </div>
                            </label>

                        </div>

                        {{-- Input Resi (hanya muncul jika dikirim/selesai) --}}
                        <div x-show="newStatus === 'Dikirim' || newStatus === 'Selesai'" x-cloak x-transition class="mt-4 flex flex-col gap-1.5" style="display: none;">
                            <label class="text-[10px] font-bold text-primary uppercase tracking-widest" for="resi">Nomor Resi / Bukti Jalan</label>
                            <input id="resi" name="tracking_number" type="text" placeholder="Masukkan nomor resi..."
                                class="bg-neutral-light border-[3px] border-primary px-4 py-3 font-body text-sm font-bold text-primary focus:outline-none focus:border-secondary transition-colors">
                        </div>

                        {{-- Alert Info --}}
                        <div x-show="newStatus === 'Selesai'" x-cloak x-transition class="mt-2 bg-green-100 border-l-[4px] border-green-600 p-3" style="display: none;">
                            <p class="text-[10px] font-bold text-green-800 uppercase tracking-widest">Peringatan: Jika diset Selesai, stok otomatis ditambahkan ke sistem Distributor tersebut.</p>
                        </div>
                    </div>

                    {{-- Aksi --}}
                    <div class="p-6 md:p-8 bg-neutral border-t-[4px] border-gray-900 flex flex-col gap-4">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <button type="button" @click="goBack()" class="w-full sm:w-1/3 bg-white text-gray-600 border-[3px] border-gray-400 px-6 py-4 font-headline font-bold text-sm uppercase tracking-widest hover:bg-gray-50 transition-colors">
                                BATAL
                            </button>
                            
                            <button type="submit" class="w-full sm:col-span-2 bg-primary text-white border-[3px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)] px-6 py-4 font-headline font-black text-sm uppercase tracking-widest hover:bg-primary-hover active:translate-y-1 active:shadow-none transition-all flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                SIMPAN PERUBAHAN
                            </button>
                        </div>

                        <div class="flex flex-col gap-2 border-t-2 border-dashed border-gray-300 pt-4 mt-2">
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Aksi Tambahan:</p>
                            <a :href="getWaLink()" target="_blank"
                                class="w-full bg-[#25D366] text-white px-6 py-3 font-headline font-bold text-[10px] uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-[#1DA851] active:translate-y-0.5 active:shadow-none transition-all flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.347-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                KIRIM NOTIFIKASI WA KE DISTRIBUTOR
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
