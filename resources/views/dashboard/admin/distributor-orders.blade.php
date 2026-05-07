<x-layouts.dashboard bgTheme="light">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">MASTER ADMIN</span>
    </x-slot:subtitleSlot>

    <x-slot:topbarTitle>PESANAN DISTRIBUTOR</x-slot:topbarTitle>

    <x-slot:menuSlot>
        @include('dashboard.admin._menu')
    </x-slot:menuSlot>

    <div class="max-w-[1400px] mx-auto w-full pb-20" x-data="{
        viewMode: 'list',
        selectedOrder: null,
        newStatus: '',
        searchQuery: '',
        filterStatus: 'Semua Status',
        
        // UI State
        forceCompleteMode: false,
        cancelMode: false,
        cancelReason: '',
        waSent: false,

        orders: {{ $orders->toJson() }},

        // Pagination State
        currentPage: 1,
        perPage: 5,

        // Validation State
        errors: {},

        get filteredOrders() {
            return this.orders.filter(order => {
                const matchesStatus = this.filterStatus === 'Semua Status' || order.status === this.filterStatus;
                const matchesSearch = order.name.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                                     order.id.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                     order.city.toLowerCase().includes(this.searchQuery.toLowerCase());
                return matchesStatus && matchesSearch;
            });
        },

        get paginatedOrders() {
            let start = (this.currentPage - 1) * this.perPage;
            let end = start + this.perPage;
            return this.filteredOrders.slice(start, end);
        },

        get totalPages() {
            return Math.ceil(this.filteredOrders.length / this.perPage);
        },

        openOrder(order) {
            this.selectedOrder = order;
            
            // Map status database ke label UI agar highlight menyala
            let mappedStatus = order.status;
            if (['Menunggu Proses', 'Menunggu'].includes(order.status)) mappedStatus = 'Menunggu';
            if (['Diproses', 'Dikemas'].includes(order.status)) mappedStatus = 'Dikemas';
            
            this.newStatus = mappedStatus;
            
            this.forceCompleteMode = false;
            this.cancelMode = false;
            this.cancelReason = '';
            this.waSent = false;
            this.viewMode = 'detail';
            this.errors = {};
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        goBack() {
            this.viewMode = 'list';
            this.selectedOrder = null;
            this.forceCompleteMode = false;
            this.cancelMode = false;
            this.cancelReason = '';
            this.errors = {};
        },

        validate(formType) {
            this.errors = {};
            
            if (formType === 'update') {
                if (this.newStatus === 'Dikirim') {
                    const resi = document.querySelector('input[name=\'tracking_number\']').value;
                    if (!resi) this.errors.tracking_number = 'Nomor resi wajib diisi';
                }
            } else if (formType === 'cancel') {
                if (!this.cancelReason) this.errors.cancel_reason = 'Alasan pembatalan wajib diisi';
            }

            return Object.keys(this.errors).length === 0;
        },

        getWaLink(mode = 'normal') {
            const phone = (this.selectedOrder?.phone ?? '').replace(/\D/g, '');
            const id = this.selectedOrder?.id ?? '';
            const status = this.newStatus;
            
            let msg = `Halo, Admin CeeKlin di sini.\n\nUpdate pesanan ${id}:\nStatus saat ini: *${status.toUpperCase()}*\n\nTerima kasih atas kerja samanya!`;
            
            if(mode === 'manual_complete') {
                msg = `Halo ${this.selectedOrder?.name}, pesanan ${id} telah kami selesaikan secara manual di sistem karena barang sudah terkirim/diterima kurir.\n\nMohon cek stok di panel Distributor Anda. Jika ada kendala, hubungi kami segera. Terima kasih!`;
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
                    <h2 class="font-headline font-black text-2xl text-primary tracking-tighter uppercase leading-none italic">Antrean Restock</h2>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-3 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-secondary animate-ping"></span>
                        Kelola Distribusi Stok ke Gudang Distributor Wilayah
                    </p>
                </div>
                
                <div class="lg:col-span-5 grid grid-cols-3 gap-4">
                    <div class="bg-white border-[3px] border-gray-900 p-4 shadow-[4px_4px_0_var(--color-primary-darkest)]">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1 italic">Menunggu</p>
                        <p class="font-headline font-black text-2xl text-primary leading-none">{{ sprintf('%02d', $stats['Menunggu']) }}</p>
                    </div>
                    <div class="bg-white border-[3px] border-gray-900 p-4 shadow-[4px_4px_0_var(--color-secondary)]">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1 italic">Dikemas</p>
                        <p class="font-headline font-black text-2xl text-secondary leading-none">{{ sprintf('%02d', $stats['Dikemas']) }}</p>
                    </div>
                    <div class="bg-primary border-[3px] border-gray-900 p-4 shadow-[4px_4px_0_var(--color-gray-900)]">
                        <p class="text-[8px] font-black text-white/50 uppercase tracking-widest mb-1 italic">Dikirim</p>
                        <p class="font-headline font-black text-2xl text-white leading-none">{{ sprintf('%02d', $stats['Dikirim']) }}</p>
                    </div>
                </div>
            </div>

            {{-- Toolbar: Search, Filter (Original Position) --}}
            <div class="bg-white border-[4px] border-gray-900 p-6 mb-8 shadow-[8px_8px_0_rgba(0,0,0,0.05)] flex flex-col xl:flex-row justify-between gap-6">
                <div class="flex flex-col md:flex-row gap-4 flex-1">
                    <div class="relative flex-1 max-w-md">
                        <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Cari Distributor / Order..." 
                            class="w-full bg-white border-[3px] border-gray-900 px-5 py-3 text-xs font-bold uppercase tracking-widest text-primary focus:outline-none focus:border-secondary transition-all shadow-[4px_4px_0_rgba(0,0,0,0.05)]">
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="relative">
                            <select aria-label="Filter Status" x-model="filterStatus" @change="currentPage = 1" class="appearance-none bg-white border-[3px] border-gray-900 px-6 py-3 text-xs font-bold uppercase tracking-widest text-primary focus:outline-none focus:border-secondary cursor-pointer min-w-[180px] shadow-[4px_4px_0_rgba(0,0,0,0.05)] pr-10">
                                <option value="Semua Status">Semua Status</option>
                                <option value="Menunggu Proses">Menunggu</option>
                                <option value="Diproses">Dikemas</option>
                                <option value="Dikirim">Dikirim</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Dibatalkan">Dibatalkan</option>
                            </select>
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest hidden sm:block">Update Terakhir: {{ now()->format('H:i') }}</span>
                    <button class="bg-primary text-white border-[3px] border-gray-900 px-6 py-3 text-[10px] font-black uppercase tracking-widest shadow-[4px_4px_0_var(--color-primary-darkest)] hover:bg-primary-hover active:translate-y-1 active:shadow-none transition-all">Export Laporan</button>
                </div>
            </div>

            {{-- Order Cards --}}
            <div class="space-y-6">
                <template x-for="order in paginatedOrders" :key="order.id">
                    <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)] group hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                        <div class="flex flex-col lg:flex-row">
                            {{-- ID & Info Column --}}
                            <div class="lg:w-80 p-6 bg-neutral-light border-b-[4px] lg:border-b-0 lg:border-r-[4px] border-gray-900 relative">
                                <div class="flex justify-between items-start mb-4">
                                    <span class="bg-gray-900 text-white text-[9px] font-black px-2 py-1 uppercase tracking-widest" x-text="order.id"></span>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest" x-text="order.date"></span>
                                </div>
                                <h4 class="font-headline font-black text-xl text-primary uppercase leading-tight italic" x-text="order.name"></h4>
                                <div class="mt-2 flex items-center gap-2">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase" x-text="order.city"></span>
                                </div>
                            </div>

                            {{-- Order Content --}}
                            <div class="flex-1 p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 sm:gap-8">
                                <div class="w-full sm:w-auto">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 italic">Volume Produk</p>
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-gray-100 border-2 border-gray-900 flex items-center justify-center">
                                            <img src="/images/hero-bottle.jpeg" class="w-8 opacity-50 grayscale mix-blend-multiply">
                                        </div>
                                        <div>
                                            <p class="font-bold text-sm text-gray-900" x-text="order.items"></p>
                                            <p class="text-[9px] font-bold text-green-600 uppercase tracking-widest">Pembayaran Terverifikasi ✓</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-wrap sm:flex-nowrap gap-6 sm:gap-10 w-full sm:w-auto justify-between items-center pt-6 sm:pt-0 border-t-2 sm:border-t-0 border-dashed border-gray-200">
                                    <div class="text-left sm:text-right">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total</p>
                                        <p class="font-headline font-black text-xl text-primary tracking-tighter italic" x-text="order.total"></p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Status</p>
                                        <div class="flex flex-col items-center gap-1.5">
                                            <span class="min-w-[130px] px-3 py-1.5 border-2 text-[9px] font-black uppercase tracking-widest block whitespace-nowrap italic shadow-[2px_2px_0_rgba(0,0,0,0.05)]"
                                                :class="{
                                                    'border-red-400 text-red-700 bg-red-50': ['Menunggu Proses', 'Menunggu'].includes(order.status),
                                                    'border-yellow-500 text-yellow-800 bg-yellow-50': ['Diproses', 'Dikemas'].includes(order.status),
                                                    'border-blue-500 text-blue-700 bg-blue-50': order.status === 'Dikirim',
                                                    'border-green-600 text-green-700 bg-green-50': order.status === 'Selesai',
                                                    'border-slate-400 text-slate-500 bg-slate-50': ['Dibatalkan', 'Ditolak'].includes(order.status)
                                                }" x-text="['Menunggu Proses', 'Menunggu'].includes(order.status) ? 'Menunggu' : (['Diproses', 'Dikemas'].includes(order.status) ? 'Dikemas' : (['Dibatalkan', 'Ditolak'].includes(order.status) ? 'Dibatalkan' : order.status))"></span>
                                            
                                            {{-- Problem Indicator (Mockup) --}}
                                            <template x-if="order.status === 'Dikirim' && order.id.includes('C819C2')">
                                                <span class="text-[7px] font-black text-red-600 bg-red-50 border border-red-200 px-1.5 py-0.5 uppercase tracking-tighter animate-pulse">⚠️ Laporan Kendala</span>
                                            </template>
                                        </div>
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
                <div x-show="filteredOrders.length === 0" class="py-20 text-center bg-white border-[4px] border-dashed border-gray-200 shadow-[8px_8px_0_rgba(0,0,0,0.02)]">
                    <div class="w-20 h-20 bg-neutral-light border-2 border-gray-900 flex items-center justify-center mx-auto mb-4 grayscale">
                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tidak ada pesanan distributor yang sesuai filter.</p>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="mt-12 flex justify-center">
                <div class="flex gap-2">
                    <button @click="if(currentPage > 1) currentPage--" :disabled="currentPage === 1"
                        class="w-10 h-10 border-[3px] border-gray-900 flex items-center justify-center font-black bg-white shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-neutral-light transition-colors disabled:opacity-50 disabled:grayscale">&lt;</button>
                    
                    <template x-for="p in totalPages" :key="p">
                        <button @click="currentPage = p"
                            :class="currentPage === p ? 'bg-primary text-white shadow-[4px_4px_0_var(--color-primary-darkest)]' : 'bg-white text-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-neutral-light'"
                            class="w-10 h-10 border-[3px] border-gray-900 flex items-center justify-center font-black transition-all" x-text="p"></button>
                    </template>

                    <button @click="if(currentPage < totalPages) currentPage++" :disabled="currentPage === totalPages"
                        class="w-10 h-10 border-[3px] border-gray-900 flex items-center justify-center font-black bg-white shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-neutral-light transition-colors disabled:opacity-50 disabled:grayscale">&gt;</button>
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
                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mt-2" x-text="'Distributor: ' + selectedOrder?.name + ' • ' + selectedOrder?.date"></p>
                    </div>
                </div>

                <div class="flex gap-4 w-full lg:w-auto">
                    <button class="flex-1 lg:flex-none bg-white border-[3px] border-gray-900 px-6 py-4 text-[10px] font-black uppercase tracking-widest hover:bg-neutral-light transition-all shadow-[4px_4px_0_var(--color-gray-900)]">Cetak Surat Jalan</button>
                    <a :href="getWaLink()" target="_blank" class="flex-1 lg:flex-none bg-[#25D366] text-white border-[3px] border-gray-900 px-6 py-4 text-[10px] font-black uppercase tracking-widest hover:bg-[#1DA851] shadow-[4px_4px_0_var(--color-gray-900)] transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        <span>WhatsApp Distributor</span>
                    </a>
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
                                     :style="'width: ' + (['Menunggu', 'Dikemas', 'Dikirim', 'Selesai'].indexOf(
                                         ['Menunggu Proses', 'Menunggu'].includes(newStatus) ? 'Menunggu' : 
                                         (['Diproses', 'Dikemas'].includes(newStatus) ? 'Dikemas' : newStatus)
                                     ) * 33.33) + '%'"></div>
                            </div>
                            
                            @foreach(['Menunggu', 'Dikemas', 'Dikirim', 'Selesai'] as $i => $step)
                            @php
                                $statusMap = [
                                    'Menunggu Proses' => 'Menunggu',
                                    'Diproses' => 'Dikemas',
                                    'Dikemas' => 'Dikemas',
                                    'Dikirim' => 'Dikirim',
                                    'Selesai' => 'Selesai'
                                ];
                            @endphp
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-12 h-12 flex items-center justify-center border-[4px] transition-all duration-500"
                                     :class="['Menunggu', 'Dikemas', 'Dikirim', 'Selesai'].indexOf(
                                         ['Menunggu Proses', 'Menunggu'].includes(newStatus) ? 'Menunggu' : 
                                         (['Diproses', 'Dikemas'].includes(newStatus) ? 'Dikemas' : newStatus)
                                     ) >= {{ $i }} ? 'bg-primary border-gray-900 text-white shadow-[4px_4px_0_rgba(0,0,0,0.2)]' : 'bg-white border-neutral-light text-slate-300'">
                                    @if($i == 0) <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @elseif($i == 1) <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    @elseif($i == 2) <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                                    @else <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @endif
                                </div>
                                <span class="text-[9px] font-black uppercase tracking-widest text-center"
                                      :class="['Menunggu', 'Dikemas', 'Dikirim', 'Selesai'].indexOf(
                                         ['Menunggu Proses', 'Menunggu'].includes(newStatus) ? 'Menunggu' : 
                                         (['Diproses', 'Dikemas'].includes(newStatus) ? 'Dikemas' : newStatus)
                                     ) >= {{ $i }} ? 'text-gray-900' : 'text-slate-300'">{{ $step }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- 1.5 Receipt Verification Report (Enhanced Visibility) --}}
                    <div class="bg-white border-[4px] border-gray-900 shadow-[10px_10px_0_rgba(0,0,0,0.05)] overflow-hidden">
                        <div class="bg-gray-900 px-6 py-3 flex items-center justify-between">
                            <span class="text-[10px] font-black text-white uppercase tracking-widest italic">Laporan Penerimaan Distributor</span>
                            <template x-if="selectedOrder?.id.includes('C819C2') && selectedOrder?.status === 'Dikirim'">
                                <span class="bg-red-600 text-white text-[8px] font-black px-2 py-1 uppercase italic border border-white/20">Ada Kendala Dilaporkan</span>
                            </template>
                            <template x-if="selectedOrder?.status === 'Selesai'">
                                <span class="bg-green-600 text-white text-[8px] font-black px-2 py-1 uppercase italic border border-white/20">Diterima Dengan Baik</span>
                            </template>
                        </div>
                        
                        <div class="p-8">
                            <template x-if="selectedOrder?.status !== 'Selesai' && !selectedOrder?.id.includes('C819C2')">
                                <div class="text-center py-6 border-2 border-dashed border-gray-200">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">Belum ada laporan penerimaan dari distributor.</p>
                                </div>
                            </template>

                            {{-- Mockup Report: Problem Case --}}
                            <template x-if="selectedOrder?.id.includes('C819C2') && selectedOrder?.status === 'Dikirim'">
                                <div class="space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                        <div class="space-y-5">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 bg-red-600 text-white flex items-center justify-center font-black italic border-2 border-gray-900 text-[10px]">!</div>
                                                <p class="text-[10px] font-black text-red-600 uppercase italic">Detail Kendala Dilaporkan:</p>
                                            </div>
                                            <div class="bg-red-50 border-l-[6px] border-red-600 p-5 shadow-[4px_4px_0_rgba(220,38,38,0.1)]">
                                                <p class="text-xs font-bold text-red-700 italic leading-relaxed">"Barang pecah 2 botol saat unboxing, dus penyok di bagian samping. Mohon tindak lanjutnya."</p>
                                            </div>
                                            <div class="flex flex-wrap gap-3">
                                                <a :href="'https://wa.me/6281234567890?text=Halo%20Distributor%20' + selectedOrder?.name + ',%20kami%20menerima%20laporan%20kendala%20pada%20order%20' + selectedOrder?.id" target="_blank"
                                                    class="bg-[#25D366] text-white px-5 py-3 text-[10px] font-black uppercase shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-[#1DA851] transition-all italic flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.347-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                                    Diskusi via WA
                                                </a>
                                                <button class="bg-gray-900 text-white px-5 py-3 text-[10px] font-black uppercase shadow-[4px_4px_0_var(--color-red-600)] hover:bg-red-600 transition-all italic">Proses Retur</button>
                                            </div>
                                        </div>
                                        <div class="space-y-4">
                                            <div class="flex items-center justify-between">
                                                <p class="text-[9px] font-black text-slate-400 uppercase italic tracking-widest">Foto Bukti Kerusakan (Unboxing)</p>
                                                <span class="text-[8px] font-bold text-red-600 uppercase">2 Foto Terlampir</span>
                                            </div>
                                            <div class="flex flex-wrap gap-5">
                                                <div class="group relative w-36 h-36 bg-gray-900 border-4 border-gray-900 shadow-[6px_6px_0_var(--color-red-600)] cursor-zoom-in overflow-hidden">
                                                    <img src="/images/hero-bottle.jpeg" class="w-full h-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-110 transition-all duration-500 opacity-80 group-hover:opacity-100">
                                                    <div class="absolute inset-0 bg-red-600/10 group-hover:bg-transparent transition-colors"></div>
                                                    <div class="absolute bottom-0 left-0 right-0 bg-gray-900/80 p-2 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                                        <p class="text-[8px] font-black text-white text-center uppercase tracking-widest">Lihat Foto 1</p>
                                                    </div>
                                                </div>
                                                <div class="group relative w-36 h-36 bg-gray-900 border-4 border-gray-900 shadow-[6px_6px_0_var(--color-slate-200)] cursor-zoom-in overflow-hidden">
                                                    <img src="/images/hero-bottle.jpeg" class="w-full h-full object-cover grayscale opacity-30 group-hover:opacity-100 transition-all duration-500">
                                                    <div class="absolute inset-0 flex items-center justify-center p-4 text-center">
                                                        <p class="text-[9px] font-black text-white/40 group-hover:hidden uppercase leading-tight italic">Tampak<br>Samping Dus</p>
                                                    </div>
                                                    <div class="absolute bottom-0 left-0 right-0 bg-gray-900/80 p-2 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                                        <p class="text-[8px] font-black text-white text-center uppercase tracking-widest">Lihat Foto 2</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            {{-- Mockup Report: Success Case --}}
                            <template x-if="selectedOrder?.status === 'Selesai'">
                                <div class="space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                        <div class="space-y-5">
                                            <p class="text-[9px] font-black text-green-600 uppercase italic tracking-widest">Hasil Verifikasi Fisik:</p>
                                            <div class="grid grid-cols-1 gap-3">
                                                <template x-for="check in ['VOLUME PCS SESUAI', 'KEMASAN UTUH & SEGEL', 'BARANG ORIGINAL PABRIK']">
                                                    <div class="flex items-center gap-3 bg-green-50 border-l-[4px] border-green-600 p-3 shadow-[2px_2px_0_rgba(0,0,0,0.05)]">
                                                        <div class="w-5 h-5 bg-green-600 text-white flex items-center justify-center border border-gray-900 text-[10px] italic">✓</div>
                                                        <span class="text-[10px] font-black text-gray-900 uppercase italic" x-text="check"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                        <div class="space-y-4">
                                            <p class="text-[9px] font-black text-slate-400 uppercase italic tracking-widest">Foto Bukti Penerimaan:</p>
                                            <div class="group relative w-40 h-40 bg-gray-900 border-4 border-gray-900 shadow-[8px_8px_0_var(--color-green-600)] cursor-zoom-in overflow-hidden">
                                                <img src="/images/hero-bottle.jpeg" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-110 transition-all duration-700">
                                                <div class="absolute bottom-0 left-0 right-0 bg-gray-900/90 p-3 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                                    <p class="text-[9px] font-black text-white text-center uppercase tracking-widest italic">Dokumentasi Distributor</p>
                                                </div>
                                                <div class="absolute top-3 right-3">
                                                    <span class="bg-green-600 text-white text-[7px] font-black px-2 py-1 uppercase shadow-[2px_2px_0_rgba(0,0,0,0.3)]">Verified</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- 2. Shipping & Payment Details --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="bg-white border-[4px] border-gray-900 shadow-[10px_10px_0_var(--color-primary-darkest)]">
                            <div class="bg-gray-900 px-6 py-3">
                                <span class="text-[10px] font-black text-white uppercase tracking-widest italic">Informasi Distributor</span>
                            </div>
                            <div class="p-6">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="w-14 h-14 bg-primary text-white flex items-center justify-center font-headline font-black text-2xl border-4 border-gray-900 shadow-[4px_4px_0_var(--color-secondary)]">
                                        <span x-text="selectedOrder?.name.charAt(0)"></span>
                                    </div>
                                    <div>
                                        <h4 class="font-headline font-black text-xl text-primary uppercase italic" x-text="selectedOrder?.name"></h4>
                                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest" x-text="selectedOrder?.phone"></p>
                                    </div>
                                </div>
                                <div class="space-y-4 pt-4 border-t-2 border-dashed border-gray-200">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">Alamat Gudang:</p>
                                    <p class="text-sm font-bold text-gray-900 leading-relaxed uppercase" x-text="selectedOrder?.city + ', INDONESIA. JL. DISTRIBUSI NO. 12, PUSAT PERGUDANGAN BLOK B.'"></p>
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
                                        <span class="text-xs font-black text-primary uppercase" x-text="selectedOrder?.method"></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-[9px] font-black text-slate-400 uppercase">Status Bayar</span>
                                        <span class="px-2 py-0.5 bg-green-100 text-green-700 text-[8px] font-black uppercase border border-green-600">Lunas / Terverifikasi ✓</span>
                                    </div>
                                    <div class="pt-5 border-t-2 border-dashed border-gray-200">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Subtotal</span>
                                            <span class="text-xs font-bold text-gray-900" x-text="selectedOrder?.total"></span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Biaya Layanan</span>
                                            <span class="text-xs font-bold text-gray-900">Rp 0</span>
                                        </div>
                                        <div class="flex justify-between items-center pt-4 mt-2 border-t-2 border-gray-900">
                                            <span class="text-[10px] font-black text-primary uppercase italic tracking-widest">Total Tagihan</span>
                                            <span class="text-3xl font-headline font-black text-primary tracking-tighter" x-text="selectedOrder?.total"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Item List --}}
                    <div class="bg-white border-[4px] border-gray-900 shadow-[10px_10px_0_rgba(0,0,0,0.05)]">
                        <div class="bg-gray-900 px-6 py-4">
                            <h3 class="font-headline font-black text-white text-base uppercase tracking-tight italic">Produk Pesanan</h3>
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
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2 italic">SKU: CK-450-DIST</p>
                                        </div>
                                        <div class="flex gap-10 text-right">
                                            <div>
                                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Harga Unit (Dist)</p>
                                                <p class="font-bold text-sm text-gray-900 uppercase">Rp 13.000</p>
                                            </div>
                                            <div>
                                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Jumlah (Restock)</p>
                                                <p class="font-headline font-black text-xl text-primary tracking-tighter italic" x-text="new Intl.NumberFormat('id-ID').format(selectedOrder?.qty) + ' PCS'"></p>
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
                        {{-- Catatan Distributor --}}
                        <div class="bg-neutral-light p-6">
                            <div class="flex items-start gap-4">
                                <svg class="w-5 h-5 text-secondary mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <div>
                                    <p class="text-[9px] font-black text-secondary uppercase tracking-widest mb-1 italic">Catatan Distributor:</p>
                                    <p class="text-xs font-bold text-gray-600 italic leading-relaxed" x-text="selectedOrder?.note ? '“' + selectedOrder?.note + '”' : '“Tidak ada catatan tambahan.”'"></p>
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
                            <h3 class="font-headline font-black text-white text-xl uppercase tracking-tighter italic leading-none">Kontrol Progres</h3>
                        </div>
                        
                        <div class="p-8">
                            {{-- Action State: Normal --}}
                            <div x-show="!forceCompleteMode" class="space-y-8">
                                <form action="{{ route('admin.distributor-orders.update-status') }}" method="POST" class="space-y-8" novalidate @submit.prevent="if(validate('update')) $el.submit()">
                                    @csrf
                                    <input type="hidden" name="order_id" :value="selectedOrder?.db_id">
                                    <input type="hidden" name="status" :value="newStatus === 'Menunggu' ? 'Menunggu Proses' : (newStatus === 'Dikemas' ? 'Diproses' : newStatus)">

                                    <div class="space-y-4">
                                        <label class="text-[10px] font-black text-gray-900 uppercase tracking-widest italic block mb-2">Update Tahapan Pengiriman:</label>
                                        
                                        {{-- Custom Radio Group for Status --}}
                                        <div class="flex flex-col gap-3">
                                            <template x-for="status in ['Menunggu', 'Dikemas', 'Dikirim']">
                                                <button type="button" @click="newStatus = status; errors = {}"
                                                        :disabled="selectedOrder?.status === 'Selesai'"
                                                        :class="newStatus === status ? 'bg-primary text-white border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)]' : 'bg-white text-gray-900 border-gray-200 hover:border-gray-900'"
                                                        class="w-full px-5 py-4 border-[3px] font-headline font-black text-[11px] uppercase tracking-widest text-left transition-all flex items-center justify-between group disabled:opacity-50 disabled:cursor-not-allowed">
                                                    <span x-text="status"></span>
                                                    <div :class="newStatus === status ? 'bg-white border-gray-900 text-primary' : 'bg-gray-50 border-gray-300 text-transparent'" class="w-6 h-6 border-[2px] flex items-center justify-center transition-all duration-300">
                                                        <svg class="w-4 h-4 transition-all duration-300" :class="newStatus === status ? 'scale-100 opacity-100' : 'scale-50 opacity-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                                                    </div>
                                                </button>
                                            </template>
                                        </div>
                                    </div>

                                    {{-- Input Resi & Kurir (Hanya jika Dikirim) --}}
                                    <div x-show="newStatus === 'Dikirim'" x-transition class="space-y-4 p-5 bg-blue-50 border-2 border-blue-200 border-dashed">
                                        <p class="text-[9px] font-black text-blue-700 uppercase tracking-widest mb-2 italic">Informasi Logistik (Wajib Diisi):</p>
                                        
                                        <div class="space-y-3">
                                            <div class="space-y-1 relative">
                                                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">No. Resi / Surat Jalan</label>
                                                <input type="text" name="tracking_number" :value="selectedOrder?.tracking_number" @input="delete errors.tracking_number" placeholder="Masukkan Nomor Resi Valid"
                                                    class="w-full bg-white border-2 border-gray-900 px-3 py-2 text-[11px] font-bold uppercase tracking-widest font-mono focus:border-primary focus:outline-none transition-all placeholder:text-slate-300">
                                                <template x-if="errors.tracking_number">
                                                    <p class="text-[9px] font-black text-red-500 uppercase tracking-widest mt-1" x-text="errors.tracking_number"></p>
                                                </template>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pt-8 mt-8 border-t-[4px] border-gray-100 space-y-6">
                                        <button type="submit" :disabled="selectedOrder?.status === 'Selesai'"
                                            class="w-full bg-primary text-white py-6 font-headline font-black text-xl uppercase tracking-widest border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)] hover:bg-primary-hover active:translate-y-1 active:shadow-none transition-all flex items-center justify-center gap-4 disabled:opacity-50 disabled:grayscale disabled:cursor-not-allowed group">
                                            <span>SIMPAN PERUBAHAN</span>
                                            <svg class="w-6 h-6 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </button>

                                        <div class="flex flex-col gap-4">
                                            <template x-if="selectedOrder?.status === 'Dikirim' && newStatus === 'Dikirim'">
                                                <button type="button" @click="forceCompleteMode = true"
                                                    class="w-full bg-white text-secondary border-[3px] border-secondary py-4 font-headline font-black text-[11px] uppercase tracking-widest hover:bg-secondary hover:text-white transition-all shadow-[4px_4px_0_rgba(0,0,0,0.1)] active:translate-y-1 active:shadow-none flex items-center justify-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    Selesaikan Manual
                                                </button>
                                            </template>

                                            <button type="button" @click="cancelMode = true"
                                                x-show="!['Selesai', 'Dibatalkan', 'Dikirim'].includes(selectedOrder?.status)"
                                                class="w-full py-2 text-[10px] font-black text-red-500 uppercase tracking-[0.2em] hover:text-red-700 transition-all flex items-center justify-center gap-3 group">
                                                <span class="h-[2px] w-4 bg-red-100 group-hover:bg-red-400 transition-all"></span>
                                                BATALKAN PESANAN INI
                                                <span class="h-[2px] w-4 bg-red-100 group-hover:bg-red-400 transition-all"></span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            {{-- Action State: Force Complete --}}
                            <div x-show="forceCompleteMode" style="display: none;" x-transition class="space-y-6">
                                <form action="{{ route('admin.distributor-orders.update-status') }}" method="POST" class="space-y-6" novalidate @submit.prevent="$el.submit()">
                                    @csrf
                                    <input type="hidden" name="order_id" :value="selectedOrder?.db_id">
                                    <input type="hidden" name="status" value="Selesai">

                                    <div class="p-5 bg-secondary/10 border-2 border-secondary/30">
                                        <h4 class="text-secondary font-headline font-black text-sm uppercase mb-2">Penyelesaian Manual</h4>
                                        <p class="text-[10px] text-gray-600 font-bold uppercase leading-relaxed">Peringatan: Gunakan fitur ini hanya jika Distributor lupa mengonfirmasi barang yang sudah sampai. Stok Distributor akan bertambah secara otomatis.</p>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[9px] font-black text-slate-400 uppercase italic">Alasan Penyelesaian:</label>
                                        <textarea name="note" rows="3" class="w-full bg-neutral-light border-[3px] border-gray-300 p-4 font-bold text-gray-900 text-xs focus:outline-none focus:border-secondary transition-all resize-none" placeholder="Contoh: Barang sudah sampai di gudang menurut kurir internal..."></textarea>
                                    </div>
                                    <div class="flex flex-col gap-3">
                                        <button type="submit" class="w-full bg-secondary text-white py-4 font-headline font-black text-sm uppercase tracking-widest border-[4px] border-gray-900 shadow-[6px_6px_0_rgba(0,0,0,0.2)] hover:bg-secondary-dark transition-all">KONFIRMASI SELESAI</button>
                                        
                                        {{-- WA Notification after Manual Complete --}}
                                        <a :href="getWaLink('manual_complete')" target="_blank" @click="waSent = true"
                                            class="w-full bg-[#25D366] text-white py-3 font-headline font-bold text-[10px] uppercase tracking-widest border-[3px] border-gray-900 flex items-center justify-center gap-2 hover:bg-[#1DA851] transition-all">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                        <span>INFO DISTRIBUTOR VIA WA</span>
                                    </a>
                                    
                                    <button type="button" @click="forceCompleteMode = false" class="w-full text-[9px] font-black text-slate-400 uppercase hover:underline">Kembali</button>
                                </div>
                            </form>
                        </div>

                        {{-- Action State: Cancel Order --}}
                        <div x-show="cancelMode" style="display: none;" x-transition class="space-y-6">
                            <form action="{{ route('admin.distributor-orders.update-status') }}" method="POST" class="space-y-6" novalidate @submit.prevent="if(validate('cancel')) $el.submit()">
                                @csrf
                                <input type="hidden" name="order_id" :value="selectedOrder?.db_id">
                                <input type="hidden" name="status" value="Dibatalkan">

                                <div class="p-5 bg-red-50 border-2 border-red-200">
                                    <h4 class="text-red-600 font-headline font-black text-sm uppercase mb-2">Batalkan Pesanan</h4>
                                    <p class="text-[10px] text-gray-600 font-bold uppercase leading-relaxed">Peringatan: Tindakan ini tidak dapat dibatalkan. Pastikan Anda telah menghubungi Distributor terkait.</p>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[9px] font-black text-slate-400 uppercase italic">Alasan Pembatalan:</label>
                                    <textarea name="note" x-model="cancelReason" rows="3" class="w-full bg-neutral-light border-[3px] border-gray-300 p-4 font-bold text-gray-900 text-xs focus:outline-none focus:border-red-500 transition-all resize-none" placeholder="Masukkan alasan pembatalan..."></textarea>
                                    <template x-if="errors.cancel_reason">
                                        <p class="text-[9px] font-black text-red-500 uppercase tracking-widest mt-1" x-text="errors.cancel_reason"></p>
                                    </template>
                                </div>
                                <div class="flex flex-col gap-3">
                                    <button type="submit" class="w-full bg-red-600 text-white py-4 font-headline font-black text-sm uppercase tracking-widest border-[4px] border-gray-900 shadow-[6px_6px_0_rgba(0,0,0,0.2)] hover:bg-red-700 transition-all">YA, BATALKAN PESANAN</button>
                                    <button type="button" @click="cancelMode = false" class="w-full text-[9px] font-black text-slate-400 uppercase hover:underline">Kembali</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Admin Guidelines --}}
                <div class="bg-gray-900 p-8 border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-secondary)]">
                    <h4 class="font-headline font-black text-secondary text-lg uppercase tracking-tight mb-4 italic">SOP Pengiriman</h4>
                    <ul class="space-y-4">
                        <li class="flex gap-3 items-start">
                            <span class="text-secondary font-black">1.</span>
                            <p class="text-[10px] font-bold text-white/70 uppercase leading-relaxed tracking-widest">Verifikasi pembayaran sebelum mengubah status ke <span class="text-white">Dikemas</span>.</p>
                        </li>
                        <li class="flex gap-3 items-start">
                            <span class="text-secondary font-black">2.</span>
                            <p class="text-[10px] font-bold text-white/70 uppercase leading-relaxed tracking-widest">Wajib sertakan No. Resi/Surat Jalan saat status <span class="text-white">Dikirim</span>.</p>
                        </li>
                        <li class="flex gap-3 items-start">
                            <span class="text-secondary font-black">3.</span>
                            <p class="text-[10px] font-bold text-white/70 uppercase leading-relaxed tracking-widest">Pastikan Distributor melakukan verifikasi barang di panel mereka.</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
