<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">PORTAL DISTRIBUTOR</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>RIWAYAT PESANAN</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.distributor._menu')</x-slot:menuSlot>

    <div class="max-w-[1400px] mx-auto w-full relative" x-data="distributorHistory">
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('distributorHistory', () => ({
                    viewMode: 'list',
                    selectedOrder: null,
                    showAll: false,
                    confirmMode: false,
                    history: @json($history),
                    statusFilter: 'all',
                    searchQuery: '',
                    problemNote: '',
                    problemImage: null,
                    problemImageUrl: null,
                    get filteredHistory() {
                        let filtered = this.history || [];
                        if (this.statusFilter !== 'all') {
                            filtered = filtered.filter(o => o.status === this.statusFilter);
                        }
                        if (this.searchQuery.trim() !== '') {
                            const q = this.searchQuery.toLowerCase();
                            filtered = filtered.filter(o => o.id.toLowerCase().includes(q));
                        }
                        return filtered;
                    },
                    checklist: { qty: false, quality: false, original: false },
                    openDetail(order) {
                        this.selectedOrder = order;
                        this.viewMode = 'detail';
                        this.confirmMode = false;
                        this.checklist = { qty: false, quality: false, original: false };
                        this.problemNote = '';
                        this.problemImage = null;
                        this.problemImageUrl = null;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    },
                    handleFileSelect(event) {
                        const file = event.target.files[0];
                        if (file) {
                            this.problemImage = file;
                            this.problemImageUrl = URL.createObjectURL(file);
                        }
                    },
                    confirmReceived(customNote = null) {
                        if (customNote) {
                            window.dispatchEvent(new CustomEvent('ask-confirm', {
                                detail: {
                                    title: 'Kirim Laporan Masalah',
                                    message: 'Apakah Anda yakin ingin mengirim laporan masalah ini ke Admin? Pesanan akan ditandai memiliki kendala.',
                                    confirmText: 'YA, KIRIM LAPORAN',
                                    onConfirm: () => {
                                        this.$refs.confirmType.value = 'problem';
                                        this.$refs.confirmNote.value = this.problemNote;
                                        this.$refs.confirmForm.submit();
                                    }
                                }
                            }));
                            return;
                        }

                        if(this.canConfirm()) {
                            window.dispatchEvent(new CustomEvent('ask-confirm', {
                                detail: {
                                    title: 'Konfirmasi Selesai',
                                    message: 'Pastikan barang sudah dicek sesuai checklist. Setelah konfirmasi, stok Anda akan bertambah secara permanen.',
                                    confirmText: 'YA, BARANG DITERIMA',
                                    onConfirm: () => {
                                        this.$refs.confirmType.value = 'success';
                                        this.$refs.confirmForm.submit();
                                    }
                                }
                            }));
                        } else {
                            window.dispatchEvent(new CustomEvent('toast', {
                                detail: { message: 'Mohon lengkapi semua checklist verifikasi!', type: 'warning' }
                            }));
                        }
                    },
                    canConfirm() {
                        return this.checklist.qty && this.checklist.quality && this.checklist.original;
                    },
                    get shippedOrdersCount() {
                        return this.history.filter(o => o.status === 'Dikirim' && o.type === 'Pembelian').length;
                    }
                }));
            });
        </script>
        <form x-ref="confirmForm" action="{{ route('distributor.history.confirm') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="order_id" :value="selectedOrder?.id">
            <input type="hidden" name="type" x-ref="confirmType" value="success">
            <input type="hidden" name="note" x-ref="confirmNote" value="">
            
            {{-- Hidden file input that will be triggered by the UI --}}
            <input type="file" name="evidence_photo" x-ref="problemFileInput" @change="handleFileSelect" class="hidden" accept="image/*">
        </form>

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4" x-show="viewMode === 'list'">
            <div>
                <h2 class="font-headline font-black text-2xl text-primary tracking-tighter uppercase leading-none italic">Status & Riwayat Restock</h2>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-2 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                    Lacak Pengiriman & Konfirmasi Barang Diterima
                </p>
            </div>
            <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto mt-4 sm:mt-0">
                <div class="relative w-full sm:w-64">
                    <input type="text" x-model="searchQuery" placeholder="Cari No. Order..." 
                        class="w-full bg-white border-[3px] border-gray-900 px-4 py-3 text-xs font-bold uppercase tracking-widest text-primary focus:outline-none focus:border-secondary shadow-[3px_3px_0_rgba(0,0,0,0.05)] placeholder:text-primary/40 italic">
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>
                <div class="relative w-full sm:w-auto">
                    <select x-model="statusFilter" aria-label="Filter status pesanan" class="w-full appearance-none bg-white border-[3px] border-gray-900 px-6 py-3 text-xs font-bold uppercase tracking-widest text-primary focus:outline-none focus:border-secondary cursor-pointer pr-10 shadow-[3px_3px_0_rgba(0,0,0,0.05)]">
                        <option value="all">Semua Status</option>
                        <option value="Menunggu">Menunggu</option>
                        <option value="Dikemas">Dikemas</option>
                        <option value="Dikirim">Dikirim</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Dibatalkan">Dibatalkan</option>
                    </select>
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- UX Reminder: Shipped Orders (Simplified) --}}
        <template x-if="shippedOrdersCount > 0 && viewMode === 'list'">
            <div class="mb-8 bg-white border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-secondary)] p-5 relative overflow-hidden">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-secondary text-white flex items-center justify-center font-bold border-2 border-gray-900">!</div>
                        <div>
                            <h4 class="font-headline font-black text-gray-900 text-sm uppercase italic leading-none">Konfirmasi Penerimaan Barang</h4>
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mt-1.5">
                                Ada <span class="text-primary" x-text="shippedOrdersCount"></span> pesanan sedang dikirim. Konfirmasi "Selesai" jika barang sudah sampai untuk menambah stok.
                            </p>
                        </div>
                    </div>
                    <button @click="statusFilter = 'Dikirim'" class="bg-gray-900 text-white px-5 py-2 text-[9px] font-headline font-bold uppercase tracking-widest border-2 border-gray-900 shadow-[3px_3px_0_var(--color-secondary)] hover:translate-y-0.5 hover:shadow-none transition-all italic">
                        Filter Pesanan
                    </button>
                </div>
            </div>
        </template>

        {{-- Tabel Riwayat --}}
        <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]" x-show="viewMode === 'list'">
            {{-- Header Kolom (Desktop) --}}
            <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-4 bg-gray-900">
                <div class="col-span-2 text-[10px] font-headline font-bold text-white uppercase tracking-widest">No. Order</div>
                <div class="col-span-2 text-[10px] font-headline font-bold text-white uppercase tracking-widest text-right">Volume (PCS)</div>
                <div class="col-span-2 text-[10px] font-headline font-bold text-white uppercase tracking-widest text-right">Total Bayar</div>
                <div class="col-span-3 text-[10px] font-headline font-bold text-white uppercase tracking-widest text-center">Status & Tanggal</div>
                <div class="col-span-3 text-[10px] font-headline font-bold text-white uppercase tracking-widest text-right">Aksi</div>
            </div>

            {{-- Data Loop --}}
            <div class="divide-y-2 divide-neutral-border">
                <template x-for="order in (showAll ? (filteredHistory || []) : (filteredHistory || []).slice(0, 5))" :key="order.db_id + '-' + order.type">
                    <div class="flex flex-col md:grid md:grid-cols-12 gap-4 px-6 py-5 items-start md:items-center border-l-[5px] hover:bg-neutral-light/50 transition-colors duration-150 group"
                         :class="order.leftBorder">
                        {{-- No. Order --}}
                        <div class="md:col-span-2 w-full">
                            <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">No. Order</p>
                            <p class="font-headline font-black text-sm text-gray-900 tracking-tight" x-text="order.id"></p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest" x-text="order.date"></p>
                        </div>
                        {{-- Volume --}}
                        <div class="md:col-span-2 w-full flex justify-between md:block md:text-right">
                            <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Volume</p>
                            <p class="font-headline font-black text-xl text-primary tracking-tighter italic">
                                <span x-text="new Intl.NumberFormat('id-ID').format(order.qty)"></span> 
                                <span class="text-[10px] font-body font-bold text-slate-400">PCS</span>
                            </p>
                        </div>
                        {{-- Total --}}
                        <div class="md:col-span-2 w-full flex justify-between md:block md:text-right">
                            <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Total Bayar</p>
                            <p class="font-bold text-sm text-gray-900 italic" x-text="order.total"></p>
                        </div>
                        {{-- Status --}}
                        <div class="md:col-span-3 w-full flex justify-between md:block md:text-center">
                            <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Status</p>
                            <div class="flex flex-col md:items-center">
                                <span class="px-2 py-1 border-2 text-[9px] font-black uppercase tracking-widest block w-max shadow-[2px_2px_0_rgba(0,0,0,0.05)] italic"
                                      :class="order.statusClass" x-text="order.status"></span>
                            </div>
                        </div>
                        {{-- Aksi --}}
                        <div class="md:col-span-3 w-full flex justify-start md:justify-end">
                            <button @click="openDetail(order)"
                                class="bg-primary text-white px-5 py-2.5 text-[10px] font-black uppercase tracking-widest border-2 border-gray-900 shadow-[3px_3px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-0.5 active:shadow-none transition-all">
                                Lihat Detail
                            </button>
                        </div>
                    </div>
                </template>

                <template x-if="!filteredHistory || filteredHistory.length === 0">
                    <div class="px-8 py-20 text-center bg-neutral-light/50">
                        <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6 border-2 border-dashed border-primary/30">
                            <svg class="w-10 h-10 text-primary opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <h3 class="font-headline font-black text-xl text-primary uppercase tracking-tight mb-2">Data Tidak Ditemukan</h3>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest max-w-xs mx-auto leading-relaxed">
                            Belum ada riwayat transaksi atau pencarian tidak cocok.
                        </p>
                    </div>
                </template>
            </div>

            {{-- Footer Summary --}}
            <div class="px-6 py-4 border-t-[4px] border-gray-900 bg-neutral-light flex flex-col sm:flex-row items-center justify-between gap-3">
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500 italic" x-text="showAll ? 'Menampilkan semua transaksi histori' : 'Menampilkan transaksi terbaru'"></span>
                <button @click="showAll = !showAll" class="text-[10px] font-black uppercase tracking-widest text-primary hover:text-secondary transition-colors flex items-center gap-2 group">
                    <span x-text="showAll ? 'Sembunyikan' : 'Muat Semua Riwayat'"></span>
                    <svg class="w-4 h-4 transition-transform duration-300" :class="showAll ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                </button>
            </div>
        </div>

        {{-- VIEW: DETAIL PESANAN --}}
        <div x-show="viewMode === 'detail'" class="pt-2 pb-10 space-y-10" style="display: none;" x-transition>
            {{-- Header Detail --}}
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-6">
                <div class="flex items-center gap-6">
                    <x-ui.back-button @click="viewMode = 'list'" />
                    <div>
                        <h3 class="font-headline font-black text-3xl text-primary tracking-tighter uppercase leading-none italic" x-text="'Order ' + selectedOrder?.id"></h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2" x-text="'Tanggal Pesan: ' + selectedOrder?.date"></p>
                    </div>
                </div>
                
                {{-- Success Alert (Inline) --}}
                <template x-if="selectedOrder?.status === 'Selesai'">
                    <div class="w-full lg:max-w-md bg-green-50 border-[3px] border-green-600 p-4 shadow-[4px_4px_0_var(--color-green-600)] flex items-center gap-4">
                        <div class="w-10 h-10 bg-green-600 text-white flex items-center justify-center font-bold text-xl">✓</div>
                        <div>
                            <p class="text-[10px] font-black text-green-700 uppercase tracking-widest">Pesanan Selesai</p>
                            <p class="text-[9px] font-bold text-green-600 uppercase mt-1 leading-relaxed">Barang telah diterima & stok gudang telah diperbarui.</p>
                        </div>
                    </div>
                </template>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Status & Tracking --}}
                <div class="space-y-6">
                    <div class="bg-white border-[4px] border-gray-900 p-8 shadow-[10px_10px_0_rgba(0,0,0,0.05)]">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-6 italic">Status Pengiriman</span>
                        
                        <div class="flex items-center w-full mb-10">
                            <template x-for="(step, i) in ['Menunggu', 'Dikemas', 'Dikirim', 'Selesai']">
                                <div class="flex-1 flex flex-col items-center relative">
                                    <div x-show="i > 0" class="absolute right-[50%] top-2.5 w-full h-[4px] -z-10"
                                         :class="i <= ['Menunggu', 'Dikemas', 'Dikirim', 'Selesai'].indexOf(selectedOrder?.status) ? 'bg-primary' : 'bg-neutral-light'"></div>
                                    <div class="w-6 h-6 border-[3px] flex items-center justify-center transition-all duration-500"
                                         :class="i <= ['Menunggu', 'Dikemas', 'Dikirim', 'Selesai'].indexOf(selectedOrder?.status) ? 'bg-primary border-gray-900 text-white shadow-[2px_2px_0_rgba(0,0,0,0.2)]' : 'bg-white border-neutral-light text-slate-300'">
                                        <svg x-show="i <= ['Menunggu', 'Dikemas', 'Dikirim', 'Selesai'].indexOf(selectedOrder?.status)" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <span class="text-[8px] font-black uppercase tracking-widest mt-3"
                                          :class="i <= ['Menunggu', 'Dikemas', 'Dikirim', 'Selesai'].indexOf(selectedOrder?.status) ? 'text-gray-900' : 'text-slate-300'" x-text="step"></span>
                                </div>
                            </template>
                        </div>

                        <div class="space-y-4 border-t-2 border-dashed border-gray-200 pt-6">
                            <div class="flex justify-between">
                                <span class="text-[9px] font-black text-slate-400 uppercase">Kurir / Logistik</span>
                                <span class="text-xs font-bold text-gray-900 uppercase italic" x-text="selectedOrder?.courier || '—'"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[9px] font-black text-slate-400 uppercase">Nomor Resi</span>
                                <span class="text-xs font-black text-primary font-mono tracking-wider" x-text="selectedOrder?.tracking || 'Belum Tersedia'"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Admin Note --}}
                    <div class="bg-neutral-light border-[4px] border-gray-900 p-8 shadow-[8px_8px_0_var(--color-primary-darkest)]">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-3 italic">Catatan Pabrik / Admin:</span>
                        <div class="bg-white p-4 border-2 border-gray-200">
                            <p class="text-xs font-bold text-gray-600 italic leading-relaxed" x-text="'“' + (selectedOrder?.notes || 'Tidak ada catatan tambahan untuk pesanan ini.') + '”'"></p>
                        </div>
                    </div>
                </div>

                {{-- Ringkasan Item --}}
                <div class="bg-white border-[4px] border-gray-900 p-8 shadow-[10px_10px_0_var(--color-secondary)]">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-6 italic">Rincian Stok Dipesan</span>
                    <div class="flex items-center gap-8 mb-8 pb-8 border-b-2 border-dashed border-gray-200">
                        <div class="w-20 h-20 bg-neutral-light flex items-center justify-center border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-primary)]">
                            <img src="/images/hero-bottle.jpeg" class="w-12 h-auto opacity-80 mix-blend-multiply">
                        </div>
                        <div class="flex-1">
                            <h4 class="font-headline font-black text-primary uppercase text-xl italic leading-none">CeeKlin 450ml</h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2 italic">SKU: CK-450-DIST</p>
                            <div class="mt-4 flex items-end justify-between">
                                <p class="text-3xl font-headline font-black text-gray-900 italic tracking-tighter" x-text="new Intl.NumberFormat('id-ID').format(selectedOrder?.qty || 0) + ' PCS'"></p>
                                <div class="text-right">
                                    <p class="text-[8px] font-black text-slate-400 uppercase">Subtotal</p>
                                    <p class="text-sm font-bold text-gray-900" x-text="selectedOrder?.total"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-black text-primary uppercase tracking-widest italic">Total Tagihan</span>
                        <span class="text-3xl font-headline font-black text-primary italic tracking-tighter" x-text="selectedOrder?.total"></span>
                    </div>
                </div>
            </div>

            {{-- Konfirmasi Barang Diterima (Enhanced UX) --}}
            <template x-if="selectedOrder?.status === 'Dikirim'">
                <div class="bg-white border-[4px] border-gray-900 shadow-[12px_12px_0_var(--color-primary)] overflow-hidden" x-data="{ reportMode: 'initial' }">
                    {{-- Initial Choice --}}
                    <div x-show="reportMode === 'initial'" class="p-10 flex flex-col lg:flex-row items-center justify-between gap-8">
                        <div class="flex items-center gap-6">
                            <div class="w-20 h-20 bg-primary/10 border-[4px] border-primary flex items-center justify-center text-4xl shadow-[6px_6px_0_var(--color-gray-900)]">
                                📦
                            </div>
                            <div>
                                <h4 class="font-headline font-black text-2xl text-primary uppercase italic leading-tight">Terima & Verifikasi Barang</h4>
                                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mt-2">Apakah barang sudah sampai dan sesuai dengan pesanan Anda?</p>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                            <button @click="reportMode = 'problem'"
                                class="flex-1 sm:flex-none bg-white text-red-600 px-8 py-4 font-headline font-black text-[11px] uppercase tracking-widest border-[3px] border-red-600 shadow-[5px_5px_0_var(--color-red-600)] hover:translate-y-1 hover:shadow-none transition-all flex items-center justify-center gap-2 italic">
                                ⚠️ Ada Masalah
                            </button>
                            <button @click="reportMode = 'success'"
                                class="flex-1 sm:flex-none bg-primary text-white px-10 py-5 font-headline font-black text-sm uppercase tracking-widest shadow-[8px_8px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-1 active:shadow-none transition-all flex items-center justify-center gap-3">
                                <span>Ya, Barang Sesuai</span>
                            </button>
                        </div>
                    </div>

                    {{-- Success Path: Checklist & Photo Proof --}}
                    <div x-show="reportMode === 'success'" x-collapse class="bg-neutral-light p-8 md:p-12 border-t-[4px] border-gray-900">
                        <div class="flex justify-between items-center mb-8">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-green-600 text-white flex items-center justify-center font-bold italic border-2 border-gray-900 text-xs">OK</div>
                                <h4 class="font-headline font-black text-xl text-primary uppercase italic">Verifikasi Kualitas Barang</h4>
                            </div>
                            <button @click="reportMode = 'initial'" class="text-[10px] font-black uppercase text-slate-400 hover:text-gray-900 transition-colors">← Kembali</button>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                            {{-- Checklists --}}
                            <div class="space-y-4">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 italic">Lakukan pengecekan fisik:</p>
                                <template x-for="(label, key) in {qty: 'Volume PCS Sesuai', quality: 'Kemasan Utuh & Segel', original: 'Barang Original Pabrik'}">
                                    <label class="bg-white border-[3px] border-gray-900 p-5 flex items-center gap-4 cursor-pointer hover:border-primary transition-all group" :class="checklist[key] ? 'shadow-[4px_4px_0_var(--color-primary)]' : 'shadow-none'">
                                        <input type="checkbox" x-model="checklist[key]" class="w-6 h-6 border-[3px] border-gray-900 text-primary focus:ring-0">
                                        <span class="text-xs font-black uppercase tracking-widest text-gray-900 italic" x-text="label"></span>
                                    </label>
                                </template>
                            </div>

                            {{-- Photo Upload --}}
                            <div class="space-y-4">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 italic">Unggah Bukti Penerimaan (Opsional):</p>
                                <div @click="$refs.problemFileInput.click()" 
                                    class="bg-white border-[3px] border-dashed border-gray-300 p-8 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-neutral-light transition-colors group relative overflow-hidden h-48">
                                    
                                    <template x-if="!problemImageUrl">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-10 h-10 text-slate-300 mb-3 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Klik untuk ambil foto / upload bukti</p>
                                        </div>
                                    </template>
                                    
                                    <template x-if="problemImageUrl">
                                        <div class="absolute inset-0 p-2">
                                            <img :src="problemImageUrl" class="w-full h-full object-cover border-2 border-primary shadow-sm">
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                <span class="text-white text-[10px] font-black uppercase tracking-widest">Ganti Foto</span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <p class="text-[8px] font-bold text-slate-400 uppercase leading-relaxed">Maksimal 2MB. Format: JPG, PNG.</p>
                            </div>
                        </div>

                        <div class="mt-12 pt-8 border-t-2 border-dashed border-gray-200">
                            <button @click="confirmReceived()"
                                :disabled="!canConfirm()"
                                :class="canConfirm() ? 'bg-primary shadow-[8px_8px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-1 active:shadow-none' : 'bg-slate-300 shadow-none opacity-50 cursor-not-allowed'"
                                class="w-full text-white py-6 font-headline font-black text-base uppercase tracking-widest transition-all flex items-center justify-center gap-3">
                                <span x-text="canConfirm() ? 'Kirim Konfirmasi Selesai' : 'Lengkapi Checklist Di Atas'"></span>
                                <svg x-show="canConfirm()" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Problem Path: Report Issue --}}
                    <div x-show="reportMode === 'problem'" x-collapse class="bg-red-50 p-8 md:p-12 border-t-[4px] border-gray-900">
                        <div class="flex justify-between items-center mb-8">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-red-600 text-white flex items-center justify-center font-bold italic border-2 border-gray-900 text-xs">!</div>
                                <h4 class="font-headline font-black text-xl text-red-600 uppercase italic">Laporan Masalah Pesanan</h4>
                            </div>
                            <button @click="reportMode = 'initial'" class="text-[10px] font-black uppercase text-slate-400 hover:text-gray-900 transition-colors">← Kembali</button>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                            <div class="space-y-6">
                                <div class="flex flex-col gap-2">
                                    <label class="text-[10px] font-black text-red-600 uppercase tracking-widest italic">Detail Masalah:</label>
                                    <textarea rows="4" x-model="problemNote" placeholder="Jelaskan masalah (Barang pecah, kurang qty, salah kirim, dll)..." 
                                        class="w-full bg-white border-[3px] border-red-600 p-4 font-body text-xs text-red-600 focus:outline-none focus:border-gray-900 transition-colors resize-none italic"></textarea>
                                </div>
                                <div class="bg-red-100 border-l-[6px] border-red-600 p-4">
                                    <p class="text-[10px] font-bold text-red-700 leading-relaxed uppercase italic">
                                        Laporan Anda akan diteruskan ke Admin Pabrik. Mohon jangan mengonfirmasi "Selesai" jika masalah belum terselesaikan.
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mb-4 italic">Wajib Unggah Bukti Foto (Unboxing):</p>
                                <div @click="$refs.problemFileInput.click()" 
                                    class="bg-white border-[3px] border-dashed border-red-300 p-8 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-red-50 transition-colors group relative overflow-hidden h-48">
                                    
                                    <template x-if="!problemImageUrl">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-10 h-10 text-red-200 mb-3 group-hover:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            <p class="text-[10px] font-bold text-red-300 uppercase tracking-widest">Klik untuk unggah bukti masalah</p>
                                        </div>
                                    </template>
                                    
                                    <template x-if="problemImageUrl">
                                        <div class="absolute inset-0 p-2">
                                            <img :src="problemImageUrl" class="w-full h-full object-cover border-2 border-red-600 shadow-sm">
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                <span class="text-white text-[10px] font-black uppercase tracking-widest">Ganti Foto</span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="mt-12 pt-8 border-t-2 border-dashed border-red-200 flex flex-col sm:flex-row gap-4">
                            <button @click="confirmReceived('Laporan Masalah Dikirim')"
                                class="flex-1 bg-primary text-white py-6 font-headline font-black text-base uppercase tracking-widest shadow-[8px_8px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-1 active:shadow-none transition-all flex items-center justify-center gap-3">
                                <span>Kirim Laporan Masalah</span>
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                            <a :href="'https://wa.me/6282215433606?text=Halo%20Admin%20CeeKlin,%20saya%20ingin%20melaporkan%20masalah%20pada%20order%20' + selectedOrder?.id" target="_blank"
                                class="flex-1 bg-[#25D366] text-white py-6 font-headline font-black text-base uppercase tracking-widest shadow-[8px_8px_0_var(--color-gray-900)] hover:bg-[#1DA851] transition-all flex items-center justify-center gap-3">
                                <span>Hubungi Admin via WA</span>
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.347-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </template>
        </div>



        {{-- Info Stok Update --}}
        <div class="mt-10 bg-secondary/5 border-[3px] border-secondary border-dashed p-6">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 bg-secondary text-white flex items-center justify-center font-bold">!</div>
                <div>
                    <p class="text-secondary font-headline font-black text-[11px] tracking-widest uppercase mb-1 italic">Penting: Alur Stok</p>
                    <p class="text-gray-600 text-[10px] font-bold leading-relaxed uppercase">
                        Stok gudang Anda akan diperbarui secara otomatis setelah Anda mengonfirmasi <strong class="text-gray-900">Selesai</strong> di halaman ini. Jika barang sudah sampai namun Anda lupa konfirmasi, Admin dapat menyelesaikan pesanan secara manual untuk memastikan integritas data distribusi.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
