<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">PORTAL DISTRIBUTOR</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>KELOLA PESANAN RESELLER</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.distributor._menu')</x-slot:menuSlot>

    <div class="max-w-[1400px] mx-auto w-full pb-20" x-data="{
        viewMode: 'list',
        selectedOrder: null,
        cancelMode: false,
        cancelReason: '',
        waSent: false,
        
        // UI State
        forceCompleteMode: false,
        showResiInput: false,
        filterStatus: 'Semua Status',
        searchQuery: '',
        orders: [
            {id: 'ORD-201', reseller: 'Ahmad Fauzi', is_peralihan: false, phone: '08123456789', city: 'Bandung', qty: 100, total: 'Rp 1.500.000', status: 'Menunggu', date: 'Hari ini, 10:30', items: 'CeeKlin 450ml (x100)'},
            {id: 'ORD-198', reseller: 'Indo Cipta Chem', is_peralihan: true, phone: '08987654321', city: 'Cirebon', qty: 50, total: 'Rp 750.000', status: 'Dikemas', date: 'Kemarin, 14:00', items: 'CeeKlin 450ml (x50)'},
            {id: 'ORD-195', reseller: 'Siti Aminah', is_peralihan: false, phone: '08111222333', city: 'Bekasi', qty: 75, total: 'Rp 1.125.000', status: 'Dikirim', date: '2 Hari Lalu', items: 'CeeKlin 450ml (x75)'},
            {id: 'ORD-190', reseller: 'Toko Jaya Abadi', is_peralihan: false, phone: '08222333444', city: 'Depok', qty: 50, total: 'Rp 750.000', status: 'Selesai', date: '5 Hari Lalu', items: 'CeeKlin 450ml (x50)'},
        ],

        init() {
            const urlParams = new URLSearchParams(window.location.search);
            const statusParam = urlParams.get('status');
            if (statusParam === 'Menunggu') {
                this.filterStatus = 'Menunggu';
            }
        },

        filteredOrders() {
            return this.orders.filter(order => {
                const matchesStatus = this.filterStatus === 'Semua Status' || order.status === this.filterStatus;
                const matchesSearch = order.reseller.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                                     order.id.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                     order.city.toLowerCase().includes(this.searchQuery.toLowerCase());
                return matchesStatus && matchesSearch;
            });
        },
        
        openOrder(order) {
            this.selectedOrder = order;
            this.cancelMode = false;
            this.forceCompleteMode = false;
            this.cancelReason = '';
            this.waSent = false;
            this.showResiInput = order.status === 'Dikirim';
            this.viewMode = 'detail';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        goBack() {
            this.viewMode = 'list';
            this.selectedOrder = null;
            this.cancelMode = false;
            this.forceCompleteMode = false;
            this.waSent = false;
        },

        getWaLink(mode = 'normal') {
            const phone = (this.selectedOrder?.phone ?? '').replace(/\D/g, '');
            const id = this.selectedOrder?.id ?? '';
            const status = this.selectedOrder?.status;
            
            let msg = `Halo, Distributor CeeKlin di sini.\n\nUpdate pesanan ${id}:\nStatus saat ini: *${status.toUpperCase()}*\n\nTerima kasih!`;
            
            if(mode === 'manual_complete') {
                msg = `Halo ${this.selectedOrder?.reseller}, pesanan ${id} telah kami konfirmasi Selesai secara manual karena barang sudah sampai/diterima.\n\nSilakan cek riwayat transaksi Anda. Terima kasih!`;
            }
            
            return 'https://wa.me/' + (phone.startsWith('0') ? '62' + phone.substring(1) : phone) + '?text=' + encodeURIComponent(msg);
        }
    }">

        {{-- ========================= --}}
        {{-- VIEW: LIST PESANAN MASUK --}}
        {{-- ========================= --}}
        <div x-show="viewMode === 'list'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">

            {{-- Header & Stats Summary --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-10 items-end">
                <div class="lg:col-span-7">
                    <h2 class="font-headline font-black text-4xl text-primary tracking-tighter uppercase leading-none italic">Manajemen Pesanan</h2>
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-[0.2em] mt-4 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-secondary animate-ping"></span>
                        Pantau & Proses Distribusi ke Jaringan Reseller
                    </p>
                </div>
                
                <div class="lg:col-span-5 grid grid-cols-3 gap-4">
                    <div class="bg-white border-[3px] border-gray-900 p-4 shadow-[4px_4px_0_var(--color-primary-darkest)]">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Menunggu</p>
                        <p class="font-headline font-black text-2xl text-primary leading-none">08</p>
                    </div>
                    <div class="bg-white border-[3px] border-gray-900 p-4 shadow-[4px_4px_0_var(--color-secondary)]">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Dikemas</p>
                        <p class="font-headline font-black text-2xl text-secondary leading-none">12</p>
                    </div>
                    <div class="bg-primary border-[3px] border-gray-900 p-4 shadow-[4px_4px_0_var(--color-gray-900)]">
                        <p class="text-[8px] font-black text-white/50 uppercase tracking-widest mb-1">Dikirim</p>
                        <p class="font-headline font-black text-2xl text-white leading-none">05</p>
                    </div>
                </div>
            </div>

            {{-- Toolbar: Search, Filter, Sort --}}
            <div class="bg-white border-[4px] border-gray-900 p-6 mb-8 shadow-[8px_8px_0_rgba(0,0,0,0.05)] flex flex-col xl:flex-row justify-between gap-6">
                <div class="flex flex-col md:flex-row gap-4 flex-1">
                    <div class="relative flex-1 max-w-md">
                        <input type="text" x-model="searchQuery" placeholder="Cari Reseller, Wilayah, atau No. Order..." 
                            class="w-full bg-neutral-light border-[3px] border-gray-900 px-5 py-3 text-xs font-bold uppercase tracking-widest text-primary focus:outline-none focus:border-secondary transition-all">
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <select aria-label="Filter Status" x-model="filterStatus" class="bg-white border-[3px] border-gray-900 px-6 py-3 text-[10px] font-black uppercase tracking-widest text-gray-900 focus:outline-none focus:border-secondary cursor-pointer min-w-[160px]">
                            <option value="Semua Status">Semua Status</option>
                            <option value="Menunggu">Menunggu</option>
                            <option value="Dikemas">Dikemas</option>
                            <option value="Dikirim">Dikirim</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest hidden sm:block">Update Terakhir: {{ now()->format('H:i') }}</span>
                    <button class="bg-primary text-white border-[3px] border-gray-900 px-6 py-3 text-[10px] font-black uppercase tracking-widest shadow-[4px_4px_0_var(--color-primary-darkest)] hover:bg-primary-hover active:translate-y-1 active:shadow-none transition-all">Export Laporan</button>
                </div>
            </div>

            {{-- Professional Order Cards --}}
            <div class="space-y-6">
                <template x-for="order in filteredOrders()" :key="order.id">
                    <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)] group hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                        <div class="flex flex-col lg:flex-row">
                            {{-- ID & Info Column --}}
                            <div class="lg:w-72 p-6 bg-neutral-light border-b-[4px] lg:border-b-0 lg:border-r-[4px] border-gray-900 relative">
                                <div class="flex justify-between items-start mb-4">
                                    <span class="bg-gray-900 text-white text-[9px] font-black px-2 py-1 uppercase tracking-widest" x-text="order.id"></span>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest" x-text="order.date"></span>
                                </div>
                                <h4 class="font-headline font-black text-xl text-primary uppercase leading-tight italic" x-text="order.reseller"></h4>
                                <div class="mt-2 flex items-center gap-2">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase" x-text="order.city"></span>
                                    <template x-if="order.is_peralihan">
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                                            <span class="text-[8px] font-black text-secondary uppercase tracking-widest">Peralihan</span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- Order Content --}}
                            <div class="flex-1 p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 sm:gap-8">
                                <div class="w-full sm:w-auto">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 italic">Ringkasan Item</p>
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-gray-100 border-2 border-gray-900 flex items-center justify-center">
                                            <img src="/images/hero-bottle.jpeg" class="w-8 opacity-50 grayscale mix-blend-multiply">
                                        </div>
                                        <div>
                                            <p class="font-bold text-sm text-gray-900" x-text="order.items"></p>
                                            <p class="text-[9px] font-bold text-green-600 uppercase tracking-widest">Sudah Dibayar ✓</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-wrap sm:flex-nowrap gap-6 sm:gap-10 w-full sm:w-auto justify-between items-center pt-6 sm:pt-0 border-t-2 sm:border-t-0 border-dashed border-gray-200">
                                    <div class="text-left sm:text-right">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total</p>
                                        <p class="font-headline font-black text-xl text-primary tracking-tighter italic" x-text="order.total"></p>
                                    </div>
                                    <div class="text-center min-w-[100px]">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Status</p>
                                        <span class="px-2 py-1 border-2 text-[9px] font-black uppercase tracking-widest block"
                                            :class="{
                                                'border-red-500 text-red-600 bg-red-50': order.status === 'Menunggu',
                                                'border-yellow-500 text-yellow-700 bg-yellow-50': order.status === 'Dikemas',
                                                'border-blue-500 text-blue-600 bg-blue-50': order.status === 'Dikirim',
                                                'border-green-600 text-green-700 bg-green-50': order.status === 'Selesai'
                                            }" x-text="order.status"></span>
                                    </div>
                                    
                                    <button @click="openOrder(order)" 
                                        class="w-full sm:w-auto bg-primary text-white px-6 py-4 font-headline font-black text-[10px] uppercase tracking-widest shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-1 active:shadow-none transition-all flex items-center justify-center gap-3">
                                        <span>Kelola Pesanan</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7-7 7M3 12h18"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                {{-- No Results State --}}
                <div x-show="filteredOrders().length === 0" class="py-20 text-center bg-white border-[4px] border-dashed border-gray-200 shadow-[8px_8px_0_rgba(0,0,0,0.02)]">
                    <div class="w-20 h-20 bg-neutral-light border-2 border-gray-900 flex items-center justify-center mx-auto mb-4 grayscale">
                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tidak ada pesanan yang sesuai dengan filter.</p>
                    <button @click="filterStatus = 'Semua Status'; searchQuery = ''" class="mt-4 text-[10px] font-black text-primary uppercase underline tracking-widest">Reset Filter</button>
                </div>
            </div>

            {{-- Pagination Placeholder --}}
            <div class="mt-12 flex justify-center">
                <div class="flex gap-2">
                    <button class="w-10 h-10 border-[3px] border-gray-900 flex items-center justify-center font-black bg-white shadow-[4px_4px_0_var(--color-gray-900)] grayscale opacity-50">&lt;</button>
                    <button class="w-10 h-10 border-[3px] border-gray-900 flex items-center justify-center font-black bg-primary text-white shadow-[4px_4px_0_var(--color-gray-900)]">1</button>
                    <button class="w-10 h-10 border-[3px] border-gray-900 flex items-center justify-center font-black bg-white shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-neutral-light transition-colors">2</button>
                    <button class="w-10 h-10 border-[3px] border-gray-900 flex items-center justify-center font-black bg-white shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-neutral-light transition-colors">&gt;</button>
                </div>
            </div>
        </div>

        {{-- ======================== --}}
        {{-- VIEW: DETAIL / PROSES   --}}
        {{-- ======================== --}}
        <div x-show="viewMode === 'detail'" style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-10"
             x-transition:enter-end="opacity-100 translate-x-0">

            {{-- Sticky Action Header --}}
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-10 gap-6">
                <div class="flex items-center gap-6">
                    <x-ui.back-button @click="goBack()" />
                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="font-headline font-black text-4xl text-primary tracking-tighter uppercase italic leading-none" x-text="selectedOrder?.id"></h2>
                        </div>
                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mt-2" x-text="'Pesanan Oleh: ' + selectedOrder?.reseller + ' • ' + selectedOrder?.date"></p>
                    </div>
                </div>

                <div class="flex gap-4 w-full lg:w-auto">
                    <button class="flex-1 lg:flex-none bg-white border-[3px] border-gray-900 px-6 py-4 text-[10px] font-black uppercase tracking-widest hover:bg-neutral-light transition-all shadow-[4px_4px_0_var(--color-gray-900)]">Cetak Invoice</button>
                    <a :href="getWaLink()" target="_blank" class="flex-1 lg:flex-none bg-[#25D366] text-white border-[3px] border-gray-900 px-6 py-4 text-[10px] font-black uppercase tracking-widest hover:bg-[#1DA851] shadow-[4px_4px_0_var(--color-gray-900)] transition-all flex items-center justify-center gap-2">WhatsApp Reseller</a>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
                
                {{-- LEFT COLUMN: ORDER INFO --}}
                <div class="xl:col-span-8 space-y-8">
                    
                    {{-- 1. Status Stepper --}}
                    <div class="bg-white border-[4px] border-gray-900 p-8 shadow-[10px_10px_0_rgba(0,0,0,0.05)]">
                        <div class="flex flex-col md:flex-row justify-between items-center gap-4 relative">
                            {{-- Connector Line --}}
                            <div class="hidden md:block absolute top-6 left-10 right-10 h-[4px] bg-neutral-light -z-10">
                                <div class="h-full bg-primary transition-all duration-700" 
                                     :style="'width: ' + (['Menunggu', 'Dikemas', 'Dikirim', 'Selesai'].indexOf(selectedOrder?.status) * 33.33) + '%'"></div>
                            </div>
                            
                            @foreach(['Menunggu', 'Dikemas', 'Dikirim', 'Selesai'] as $i => $step)
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-12 h-12 flex items-center justify-center border-[4px] transition-all duration-500"
                                     :class="['Menunggu', 'Dikemas', 'Dikirim', 'Selesai'].indexOf(selectedOrder?.status) >= {{ $i }} ? 'bg-primary border-gray-900 text-white shadow-[4px_4px_0_rgba(0,0,0,0.2)]' : 'bg-white border-neutral-light text-slate-300'">
                                    @if($i == 0) <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    @elseif($i == 1) <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    @elseif($i == 2) <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                                    @else <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @endif
                                </div>
                                <span class="text-[9px] font-black uppercase tracking-widest text-center"
                                      :class="['Menunggu', 'Dikemas', 'Dikirim', 'Selesai'].indexOf(selectedOrder?.status) >= {{ $i }} ? 'text-gray-900' : 'text-slate-300'">{{ $step }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- 2. Customer & Shipping Details --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="bg-white border-[4px] border-gray-900 shadow-[10px_10px_0_var(--color-primary-darkest)]">
                            <div class="bg-gray-900 px-6 py-3 flex justify-between items-center">
                                <span class="text-[10px] font-black text-white uppercase tracking-widest italic">Informasi Reseller</span>
                                <template x-if="selectedOrder?.is_peralihan">
                                    <span class="px-2 py-0.5 bg-secondary text-white text-[8px] font-black uppercase">Reseller Peralihan</span>
                                </template>
                            </div>
                            <div class="p-6">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="w-14 h-14 bg-primary text-white flex items-center justify-center font-headline font-black text-2xl border-4 border-gray-900 shadow-[4px_4px_0_var(--color-secondary)]">
                                        <span x-text="selectedOrder?.reseller.charAt(0)"></span>
                                    </div>
                                    <div>
                                        <h4 class="font-headline font-black text-xl text-primary uppercase italic" x-text="selectedOrder?.reseller"></h4>
                                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest" x-text="selectedOrder?.phone"></p>
                                    </div>
                                </div>
                                <div class="space-y-4 pt-4 border-t-2 border-dashed border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">Alamat Pengiriman:</p>
                                        <template x-if="selectedOrder?.is_peralihan">
                                            <span class="text-[8px] font-bold text-secondary uppercase italic">Peralihan Aktif</span>
                                        </template>
                                    </div>
                                    <p class="text-sm font-bold text-gray-900 leading-relaxed uppercase" x-text="selectedOrder?.city + ', Jawa Barat - 40132. JL. RAYA UTAMA NO. 45, RT 02 RW 05.'"></p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white border-[4px] border-gray-900 shadow-[10px_10px_0_var(--color-secondary)]">
                            <div class="bg-secondary px-6 py-3">
                                <span class="text-[10px] font-black text-white uppercase tracking-widest italic">Rincian Pembayaran</span>
                            </div>
                            <div class="p-6">
                                <div class="space-y-5">
                                    <div class="flex justify-between items-center">
                                        <span class="text-[9px] font-black text-slate-400 uppercase">Metode Bayar</span>
                                        <span class="text-xs font-black text-primary uppercase">QRIS (Otomatis)</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-[9px] font-black text-slate-400 uppercase">Status Bayar</span>
                                        <span class="px-2 py-0.5 bg-green-100 text-green-700 text-[8px] font-black uppercase border border-green-600">Lunas ✓</span>
                                    </div>
                                    <div class="pt-5 border-t-2 border-dashed border-gray-200">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-[9px] font-black text-slate-400 uppercase">Subtotal</span>
                                            <span class="text-xs font-bold text-gray-900" x-text="selectedOrder?.total"></span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-[9px] font-black text-slate-400 uppercase">Biaya Layanan</span>
                                            <span class="text-xs font-bold text-gray-900">Rp 0</span>
                                        </div>
                                        <div class="flex justify-between items-center pt-4 mt-2 border-t-2 border-gray-900">
                                            <span class="text-[10px] font-black text-primary uppercase italic">Total Akhir</span>
                                            <span class="text-2xl font-headline font-black text-primary tracking-tighter" x-text="selectedOrder?.total"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Item List --}}
                    <div class="bg-white border-[4px] border-gray-900 shadow-[10px_10px_0_rgba(0,0,0,0.05)]">
                        <div class="bg-gray-900 px-6 py-4">
                            <h3 class="font-headline font-black text-white text-base uppercase tracking-tight italic">Produk Yang Dipesan</h3>
                        </div>
                        <div class="p-0">
                            <div class="flex items-center gap-8 p-8 border-b-2 border-neutral-light">
                                <div class="w-24 h-24 bg-gray-100 border-[3px] border-gray-900 flex items-center justify-center p-2 shadow-[4px_4px_0_var(--color-primary)]">
                                    <img src="/images/hero-bottle.jpeg" class="w-full h-auto">
                                </div>
                                <div class="flex-1">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                        <div>
                                            <h4 class="font-headline font-black text-2xl text-primary uppercase italic leading-none">CeeKlin 450ml</h4>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2 italic">SKU: CK-450-REG</p>
                                        </div>
                                        <div class="flex gap-10 text-right">
                                            <div>
                                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Harga Unit</p>
                                                <p class="font-bold text-sm text-gray-900 uppercase">Rp 15.000</p>
                                            </div>
                                            <div>
                                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Jumlah</p>
                                                <p class="font-headline font-black text-xl text-primary tracking-tighter italic" x-text="selectedOrder?.qty + ' PCS'"></p>
                                            </div>
                                            <div>
                                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total</p>
                                                <p class="font-bold text-sm text-gray-900" x-text="selectedOrder?.total"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-neutral-light p-6">
                            <div class="flex items-start gap-4">
                                <svg class="w-5 h-5 text-secondary mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <div>
                                    <p class="text-[9px] font-black text-secondary uppercase tracking-widest mb-1 italic">Catatan Reseller:</p>
                                    <p class="text-xs font-bold text-gray-600 italic leading-relaxed">"Tolong diproses cepat ya min, stok di toko saya sudah mau habis. Terima kasih banyak!"</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: ACTION PANEL --}}
                <div class="xl:col-span-4 space-y-8 sticky top-10">
                    
                    {{-- Status Control Panel --}}
                    <div class="bg-white border-[4px] border-gray-900 shadow-[12px_12px_0_var(--color-primary-darkest)] overflow-hidden">
                        <div class="bg-primary p-6 border-b-[4px] border-gray-900">
                            <h3 class="font-headline font-black text-white text-xl uppercase tracking-tighter italic leading-none">Kontrol Status</h3>
                        </div>
                        
                        <div class="p-8">
                            {{-- Action State: Normal --}}
                            <div x-show="!cancelMode && !forceCompleteMode" class="space-y-8">
                                <div class="space-y-4">
                                    <label class="text-[10px] font-black text-gray-900 uppercase tracking-widest italic block mb-2">Pilih Tahapan Berikutnya:</label>
                                    
                                    {{-- Custom Radio Group for Status --}}
                                    <div class="flex flex-col gap-3">
                                        <template x-for="status in ['Menunggu', 'Dikemas', 'Dikirim']">
                                            <button @click="selectedOrder.status = status; showResiInput = (status === 'Dikirim')"
                                                    :disabled="selectedOrder?.status === 'Selesai'"
                                                    :class="selectedOrder?.status === status ? 'bg-primary text-white border-primary shadow-[4px_4px_0_var(--color-primary-darkest)]' : 'bg-white text-gray-900 border-gray-200 hover:border-gray-900'"
                                                    class="w-full px-5 py-4 border-[3px] font-headline font-black text-[11px] uppercase tracking-widest text-left transition-all flex items-center justify-between group disabled:opacity-50 disabled:cursor-not-allowed">
                                                <span x-text="status"></span>
                                                <div :class="selectedOrder?.status === status ? 'bg-white' : 'bg-gray-100'" class="w-4 h-4 rounded-full border-2 border-gray-900"></div>
                                            </button>
                                        </template>
                                    </div>
                                </div>

                                {{-- Input Resi & Kurir --}}
                                <div x-show="showResiInput" x-transition class="space-y-4 p-5 bg-blue-50 border-2 border-blue-200 border-dashed">
                                    <p class="text-[9px] font-black text-blue-700 uppercase tracking-widest mb-2 italic">Pengiriman (Wajib Diisi):</p>
                                    
                                    <div class="space-y-3">
                                        <div class="space-y-1">
                                            <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Nama Kurir / Logistik</label>
                                            <input type="text" name="courier_name" placeholder="Contoh: JNE, J&T, atau Kurir Internal"
                                                class="w-full bg-white border-2 border-gray-900 px-3 py-2 text-[11px] font-bold uppercase tracking-widest focus:border-primary focus:outline-none transition-all placeholder:text-slate-300">
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Nomor Resi</label>
                                            <input type="text" name="tracking_number" placeholder="Masukkan No. Resi Valid"
                                                class="w-full bg-white border-2 border-gray-900 px-3 py-2 text-[11px] font-bold uppercase tracking-widest font-mono focus:border-primary focus:outline-none transition-all placeholder:text-slate-300">
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-4 border-t-2 border-neutral-light">
                                    <button :disabled="selectedOrder?.status === 'Selesai'"
                                        class="w-full bg-primary text-white py-6 font-headline font-black text-lg uppercase tracking-widest border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-1 active:shadow-none transition-all flex items-center justify-center gap-3 disabled:opacity-50 disabled:grayscale disabled:cursor-not-allowed">
                                        SIMPAN PERUBAHAN
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                    
                                    {{-- Aksi Cadangan: Selesaikan Manual --}}
                                    <template x-if="selectedOrder?.status === 'Dikirim'">
                                        <button @click="forceCompleteMode = true" class="w-full mt-6 bg-white text-secondary border-[3px] border-secondary py-3 font-headline font-black text-[10px] uppercase tracking-widest hover:bg-secondary/5 transition-all">Selesaikan Pesanan Secara Manual</button>
                                    </template>

                                    <button @click="cancelMode = true" :disabled="selectedOrder?.status === 'Selesai'" class="w-full mt-4 text-[10px] font-black text-red-500 uppercase tracking-widest hover:underline transition-all disabled:hidden">Batalkan Pesanan Ini</button>
                                </div>
                            </div>

                            {{-- Action State: Force Complete --}}
                            <div x-show="forceCompleteMode" style="display: none;" x-transition class="space-y-6">
                                <div class="p-5 bg-secondary/10 border-2 border-secondary/30">
                                    <h4 class="text-secondary font-headline font-black text-sm uppercase mb-2">Penyelesaian Manual</h4>
                                    <p class="text-[10px] text-gray-600 font-bold uppercase leading-relaxed">Konfirmasi pesanan ini selesai karena barang sudah sampai. Tindakan ini akan tercatat di sistem.</p>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[9px] font-black text-slate-400 uppercase italic">Alasan (Opsional):</label>
                                    <textarea rows="3" class="w-full bg-neutral-light border-[3px] border-gray-300 p-4 font-bold text-gray-900 text-xs focus:outline-none focus:border-secondary transition-all resize-none" placeholder="Contoh: Reseller sudah konfirmasi via WA..."></textarea>
                                </div>
                                <div class="flex flex-col gap-3">
                                    <button @click="selectedOrder.status = 'Selesai'; forceCompleteMode = false" class="w-full bg-secondary text-white py-4 font-headline font-black text-sm uppercase tracking-widest border-[4px] border-gray-900 shadow-[6px_6px_0_rgba(0,0,0,0.2)] hover:bg-secondary-dark transition-all">YA, SELESAIKAN PESANAN</button>
                                    
                                    <a :href="getWaLink('manual_complete')" target="_blank"
                                        class="w-full bg-[#25D366] text-white py-3 font-headline font-bold text-[10px] uppercase tracking-widest border-[3px] border-gray-900 flex items-center justify-center gap-2">
                                        KIRIM INFO WA KE RESELLER
                                    </a>
                                    
                                    <button @click="forceCompleteMode = false" class="w-full text-[9px] font-black text-slate-400 uppercase hover:underline">Kembali</button>
                                </div>
                            </div>

                            {{-- Action State: Cancel --}}
                            <div x-show="cancelMode" style="display: none;" x-transition class="space-y-6">
                                <div class="p-5 bg-red-50 border-2 border-red-200">
                                    <h4 class="text-red-700 font-headline font-black text-sm uppercase mb-2">Konfirmasi Pembatalan</h4>
                                    <p class="text-[10px] text-red-600 font-bold uppercase leading-relaxed">Peringatan: Dana akan dikembalikan ke reseller dan stok Anda akan dipulihkan.</p>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[9px] font-black text-slate-400 uppercase italic">Alasan Pembatalan:</label>
                                    <textarea rows="4" class="w-full bg-neutral-light border-[3px] border-red-400 p-4 font-bold text-gray-900 text-xs focus:outline-none focus:border-red-600 transition-all resize-none" placeholder="Contoh: Stok sedang kosong..."></textarea>
                                </div>
                                <div class="flex flex-col gap-3">
                                    <button class="w-full bg-red-600 text-white py-4 font-headline font-black text-sm uppercase tracking-widest border-[4px] border-gray-900 shadow-[6px_6px_0_rgba(0,0,0,0.2)] hover:bg-red-700 transition-all">YA, BATALKAN SEKARANG</button>
                                    <button @click="cancelMode = false" class="w-full bg-white text-gray-900 py-3 font-headline font-bold text-[10px] uppercase tracking-widest border-[3px] border-gray-900 hover:bg-neutral-light transition-all">KEMBALI</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Help / Guideline --}}
                    <div class="bg-gray-900 p-8 border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-secondary)]">
                        <h4 class="font-headline font-black text-secondary text-lg uppercase tracking-tight mb-4 italic">Panduan Pengiriman</h4>
                        <ul class="space-y-4">
                            <li class="flex gap-3 items-start">
                                <span class="text-secondary font-black">1.</span>
                                <p class="text-[10px] font-bold text-white/70 uppercase leading-relaxed tracking-widest">Pastikan barang dikemas dengan aman menggunakan bubble wrap.</p>
                            </li>
                            <li class="flex gap-3 items-start">
                                <span class="text-secondary font-black">2.</span>
                                <p class="text-[10px] font-bold text-white/70 uppercase leading-relaxed tracking-widest">Wajib memasukkan Nomor Resi setelah status diubah ke <span class="text-white">Dikirim</span>.</p>
                            </li>
                            <li class="flex gap-3 items-start">
                                <span class="text-secondary font-black">3.</span>
                                <p class="text-[10px] font-bold text-white/70 uppercase leading-relaxed tracking-widest">Ingatkan reseller untuk klik <span class="text-white text-secondary-light">Selesai</span> agar alur transaksi tuntas.</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
