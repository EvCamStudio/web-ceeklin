<x-layouts.dashboard bgTheme="light">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">MASTER ADMIN</span>
    </x-slot:subtitleSlot>

    <x-slot:topbarTitle>SINKRONISASI STOK</x-slot:topbarTitle>

    <x-slot:menuSlot>
        @include('dashboard.admin._menu')
    </x-slot:menuSlot>

    <div class="max-w-[1400px] mx-auto w-full" x-data="{
        searchQuery: '',
        filterRegion: 'Semua Wilayah',
        showModal: false,
        selectedRequest: null,
        showSuccess: false,
        successMsg: '',
        syncRequests: [
            { id: 'SYNC-102', requester: 'PT Tirta Makmur', type: 'Distributor', city: 'Bandung', province: 'Jawa Barat', current: 2450, actual: 2500, diff: '+50', reason: 'Kelebihan kirim dari pabrik setelah audit internal.', status: 'Menunggu', date: 'Hari ini, 09:15' },
            { id: 'SYNC-101', requester: 'CV Bintang Selatan', type: 'Distributor', city: 'Surabaya', province: 'Jawa Timur', current: 1500, actual: 1300, diff: '-200', reason: 'Kebocoran atap gudang mengakibatkan stok rusak.', status: 'Menunggu', date: 'Kemarin, 16:45' }
        ],
        openModal(req) {
            this.selectedRequest = req;
            this.showModal = true;
        },
        handleConfirm() {
            this.showModal = false;
            this.syncRequests = this.syncRequests.filter(r => r.id !== this.selectedRequest.id);
            this.successMsg = 'Sinkronisasi Stok Berhasil Disetujui!';
            this.showSuccess = true;
            setTimeout(() => { this.showSuccess = false; }, 3000);
        },
        getWaLink(req) {
            const msg = `Halo ${req.requester}, saya Admin CeeKlin ingin mendiskusikan pengajuan sinkronisasi stok Anda (${req.id}) dengan selisih ${req.diff} pcs. Mohon informasinya lebih lanjut.`;
            return 'https://wa.me/62xxxxxxxxxx?text=' + encodeURIComponent(msg);
        },
        get filteredSync() {
            let res = this.syncRequests;
            if (this.filterRegion !== 'Semua Wilayah') {
                res = res.filter(r => r.province === this.filterRegion);
            }
            if (this.searchQuery) {
                res = res.filter(r => 
                    r.requester.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                    r.id.toLowerCase().includes(this.searchQuery.toLowerCase())
                );
            }
            return res;
        }
    }">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-6">
            <div>
                <h2 class="font-headline font-black text-3xl text-primary tracking-tighter uppercase leading-none">Persetujuan Stok</h2>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-3 italic">Validasi audit stok gudang Distributor</p>
            </div>

            {{-- Filters --}}
            <div class="w-full md:w-auto flex flex-col sm:flex-row gap-3">
                <div class="relative w-full sm:w-48">
                    <select x-model="filterRegion" aria-label="Filter Wilayah"
                        class="appearance-none w-full bg-white border-[3px] border-gray-900 px-4 py-3 text-[10px] font-bold uppercase tracking-widest text-primary focus:outline-none focus:border-secondary cursor-pointer pr-10 shadow-[4px_4px_0_rgba(0,0,0,0.05)]">
                        <option>Semua Wilayah</option>
                        <option>Jawa Barat</option>
                        <option>Jawa Timur</option>
                    </select>
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
                <div class="relative w-full sm:w-64">
                    <input type="text" x-model="searchQuery" placeholder="Cari Distributor..." 
                        class="w-full bg-white border-[3px] border-gray-900 px-4 py-3 text-[10px] font-bold uppercase tracking-widest text-primary focus:outline-none focus:border-secondary pr-10 shadow-[4px_4px_0_rgba(0,0,0,0.05)]">
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Success Alert --}}
        <div x-show="showSuccess" x-transition x-cloak
             class="fixed top-24 right-8 z-[10002] bg-green-600 text-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-gray-900)] px-6 py-4 flex items-center gap-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
            <span class="font-headline font-black text-xs uppercase tracking-widest" x-text="successMsg"></span>
        </div>

        {{-- CONTENT: TAB SINKRONISASI STOK --}}
        <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">
            <div class="hidden md:grid grid-cols-12 gap-4 px-8 py-4 bg-gray-900 text-white">
                <div class="col-span-3 text-[10px] font-headline font-bold uppercase tracking-widest">Distributor</div>
                <div class="col-span-2 text-center text-[10px] font-headline font-bold uppercase tracking-widest">Sistem vs Fisik</div>
                <div class="col-span-1 text-center text-[10px] font-headline font-bold uppercase tracking-widest">Selisih</div>
                <div class="col-span-4 text-[10px] font-headline font-bold uppercase tracking-widest">Alasan Pengajuan</div>
                <div class="col-span-2 text-right text-[10px] font-headline font-bold uppercase tracking-widest">Aksi</div>
            </div>

            <div class="divide-y-2 divide-neutral-border">
                <template x-for="(req, index) in filteredSync" :key="req.id">
                    <div class="flex flex-col md:grid md:grid-cols-12 gap-4 px-8 py-6 items-start md:items-center hover:bg-neutral-light transition-colors group">
                        <div class="md:col-span-3 w-full">
                            <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pengaju</p>
                            <div class="font-headline font-black text-sm text-gray-900 uppercase tracking-tight" x-text="req.requester"></div>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-[9px] font-bold text-slate-500 uppercase" x-text="req.city"></span>
                                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase" x-text="req.date"></span>
                            </div>
                        </div>
                        <div class="md:col-span-2 w-full md:text-center">
                            <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Perbandingan</p>
                            <div class="flex items-center justify-center gap-2 font-bold">
                                <span class="text-xs text-slate-400" x-text="req.current"></span>
                                <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                <span class="text-sm font-headline font-black text-gray-900" x-text="req.actual"></span>
                            </div>
                        </div>
                        <div class="md:col-span-1 w-full md:text-center font-headline font-black italic">
                            <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Selisih</p>
                            <span :class="req.diff.startsWith('+') ? 'text-green-600' : 'text-red-600'" x-text="req.diff"></span>
                        </div>
                        <div class="md:col-span-4 w-full">
                            <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Alasan</p>
                            <p class="text-[11px] text-slate-600 font-bold leading-relaxed line-clamp-2" x-text="req.reason"></p>
                        </div>
                        <div class="md:col-span-2 w-full flex justify-end gap-2 text-right">
                            {{-- WA Button --}}
                            <a :href="getWaLink(req)" target="_blank"
                                class="p-2 border-[3px] border-gray-900 bg-white text-[#25D366] hover:bg-[#25D366] hover:text-white transition-all shadow-[3px_3px_0_var(--color-gray-900)] active:translate-y-0.5 active:shadow-none">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.347-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            </a>
                            <button @click="openModal(req)" class="bg-primary text-white px-4 py-2 text-[9px] font-headline font-black uppercase tracking-widest border-[3px] border-gray-900 shadow-[3px_3px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-0.5 active:shadow-none transition-all">
                                SETUJUI
                            </button>
                        </div>
                    </div>
                </template>
                <template x-if="filteredSync.length === 0">
                    <div class="px-8 py-20 text-center">
                        <p class="font-headline font-bold text-slate-300 text-xl uppercase italic tracking-widest">Tidak ada pengajuan sinkronisasi</p>
                    </div>
                </template>
            </div>
        </div>

        {{-- Modal Konfirmasi --}}
        <div x-show="showModal" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" x-transition>
            <div class="bg-white border-[6px] border-gray-900 shadow-[15px_15px_0_var(--color-primary)] w-full max-w-md p-8" @click.away="showModal = false">
                <h3 class="font-headline font-black text-2xl text-gray-900 uppercase tracking-tighter mb-4">Setujui Sinkronisasi?</h3>
                <p class="text-sm font-bold text-slate-600 mb-8 leading-relaxed">
                    Menyetujui permintaan dari <span class="text-primary" x-text="selectedRequest?.requester"></span> akan mengubah data stok di sistem secara permanen.
                </p>
                <div class="flex gap-4">
                    <button @click="showModal = false" class="flex-1 py-4 border-[3px] border-gray-900 font-headline font-bold text-xs uppercase tracking-widest hover:bg-neutral-light transition-colors uppercase">BATAL</button>
                    <button @click="handleConfirm()" class="flex-1 py-4 bg-primary text-white font-headline font-black text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-1 active:shadow-none transition-all uppercase">YA, PROSES</button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
