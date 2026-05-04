<x-layouts.dashboard bgTheme="light">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">MASTER ADMIN</span>
    </x-slot:subtitleSlot>

    <x-slot:topbarTitle>SINKRONISASI STOK</x-slot:topbarTitle>

    <x-slot:menuSlot>
        @include('dashboard.admin._menu')
    </x-slot:menuSlot>

    <div class="max-w-[1400px] mx-auto w-full" x-data="{
        viewMode: 'list',
        searchQuery: '',
        filterRegion: 'Semua Wilayah',
        selectedRequest: null,
        showSuccess: false,
        successMsg: '',
        syncRequests: [
            @foreach($adjustments as $adj)
            { 
                db_id: {{ $adj->id }},
                id: 'SYNC-{{ 1000 + $adj->id }}', 
                requester: '{{ addslashes($adj->user->name) }}', 
                type: 'Distributor', 
                city: '{{ $adj->user->city_name ?? "N/A" }}', 
                province: '{{ $adj->user->province_name ?? "N/A" }}', 
                phone: '{{ $adj->user->phone }}', 
                current: {{ $adj->system_stock }}, 
                actual: {{ $adj->physical_stock }}, 
                diff: '{{ ($adj->physical_stock - $adj->system_stock) > 0 ? "+" . ($adj->physical_stock - $adj->system_stock) : ($adj->physical_stock - $adj->system_stock) }}', 
                reason: '{{ addslashes($adj->reason) }}', 
                status: 'Menunggu', 
                date: '{{ $adj->created_at->diffForHumans() }}' 
            },
            @endforeach
        ],
        openDetail(req) {
            this.selectedRequest = req;
            this.viewMode = 'detail';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        goBack() {
            this.viewMode = 'list';
            this.selectedRequest = null;
        },
        handleConfirm() {
            this.$refs.approveForm.submit();
        },
        handleReject() {
            this.$refs.rejectForm.submit();
        },
        getWaLink(req) {
            const phone = (req.phone ?? '').replace(/\D/g, '');
            const msg = `Halo ${req.requester}, saya Admin CeeKlin ingin mendiskusikan pengajuan sinkronisasi stok Anda (${req.id}) dengan selisih ${req.diff} pcs. Mohon informasinya lebih lanjut.`;
            return 'https://wa.me/62' + (phone.startsWith('0') ? phone.substring(1) : phone) + '?text=' + encodeURIComponent(msg);
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
        },
        init() {
            @if(session('success'))
                this.successMsg = '{{ session('success') }}';
                this.showSuccess = true;
                setTimeout(() => { this.showSuccess = false; }, 4000);
            @endif
        }
    }">
        <div x-show="viewMode === 'list'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
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

        <div class="fixed top-8 left-0 right-0 z-[10002] flex justify-center pointer-events-none px-4">
            <div x-show="showSuccess" 
                 x-transition:enter="transition ease-out duration-500" 
                 x-transition:enter-start="opacity-0 -translate-y-12 scale-90" 
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-12"
                 x-cloak
                 class="pointer-events-auto bg-green-600 text-white border-[4px] border-gray-900 shadow-[12px_12px_0_rgba(0,0,0,0.15)] px-8 py-5 flex items-center gap-4 max-w-md w-full">
                <div class="w-10 h-10 bg-white/20 border-2 border-white/30 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                </div>
                <span class="font-headline font-black text-[11px] uppercase tracking-widest leading-tight" x-text="successMsg"></span>
            </div>
        </div>

        <form x-ref="approveForm" action="{{ route('admin.requests.approve') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="request_id" :value="selectedRequest?.db_id">
        </form>

        <form x-ref="rejectForm" action="{{ route('admin.requests.reject') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="request_id" :value="selectedRequest?.db_id">
        </form>

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
                            <button @click="openDetail(req)" class="bg-primary text-white px-4 py-2 text-[9px] font-headline font-black uppercase tracking-widest border-[3px] border-gray-900 shadow-[3px_3px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-0.5 active:shadow-none transition-all">
                                TINJAU
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

        </div> {{-- End List View --}}

        {{-- ====================== --}}
        {{-- VIEW: DETAIL SINKRON   --}}
        {{-- ====================== --}}
        <div x-show="viewMode === 'detail'" x-cloak style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">
            
            <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="font-headline font-black text-3xl text-primary tracking-tighter uppercase leading-none">Tinjau Sinkronisasi</h2>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-3 italic" x-text="'ID PENGAJUAN: ' + selectedRequest?.id"></p>
                </div>
                <button @click="goBack()" class="flex items-center gap-2 bg-white text-gray-900 px-6 py-3 text-[10px] font-bold uppercase tracking-widest border-[3px] border-gray-900 hover:bg-neutral-light transition-colors shadow-[6px_6px_0_var(--color-gray-900)] active:translate-y-1 active:shadow-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    KEMBALI KE LIST
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {{-- Kiri: Info Distributor & Detail --}}
                <div class="lg:col-span-7 flex flex-col gap-6">
                    
                    {{-- Card Distributor --}}
                    <div class="bg-white border-[4px] border-gray-900 shadow-[10px_10px_0_var(--color-primary)] p-8">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <p class="text-[10px] font-bold text-secondary uppercase tracking-widest mb-1">Informasi Distributor</p>
                                <h3 class="font-headline font-black text-2xl text-gray-900 uppercase tracking-tight" x-text="selectedRequest?.requester"></h3>
                            </div>
                            <span class="px-3 py-1 bg-gray-900 text-white text-[10px] font-bold uppercase tracking-widest border-2 border-gray-900 shadow-[4px_4px_0_var(--color-secondary)]" x-text="selectedRequest?.type"></span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-8 border-t-2 border-neutral-border pt-6 mt-6">
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Wilayah / Lokasi</p>
                                <p class="font-bold text-gray-900 text-sm uppercase" x-text="selectedRequest?.city + ', ' + selectedRequest?.province"></p>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Waktu Pengajuan</p>
                                <p class="font-bold text-gray-900 text-sm" x-text="selectedRequest?.date"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Card Alasan --}}
                    <div class="bg-neutral-light border-[4px] border-gray-900 p-8 relative overflow-hidden">
                        <div class="absolute -right-4 -top-4 opacity-5 pointer-events-none">
                            <svg class="w-32 h-32 text-gray-900" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21L14.017 18C14.017 16.8954 13.1216 16 12.017 16H9.01705C7.91248 16 7.01705 16.8954 7.01705 18V21H14.017ZM14.017 21H17.017C18.1216 21 19.017 20.1046 19.017 19V10H21.017V8H19.017V5C19.017 3.89543 18.1216 3 17.017 3H7.01705C5.91248 3 5.01705 3.89543 5.01705 5V8H3.01705V10H5.01705V19C5.01705 20.1046 5.91248 21 7.01705 21H10.017L10.017 18C10.017 17.4477 10.4648 17 11.017 17H12.017C12.5693 17 13.017 17.4477 13.017 18V21H14.017Z"/></svg>
                        </div>
                        <h4 class="font-headline font-black text-lg text-primary uppercase mb-4 tracking-tighter italic">Alasan Sinkronisasi</h4>
                        <div class="bg-white border-2 border-gray-900 p-6 shadow-[5px_5px_0_var(--color-gray-900)]">
                            <p class="text-sm text-gray-900 font-bold leading-relaxed italic" x-text="'“' + selectedRequest?.reason + '”'"></p>
                        </div>
                        <div class="mt-6 flex items-center gap-2">
                            <span class="w-2 h-2 bg-secondary rounded-full animate-pulse"></span>
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Membutuhkan persetujuan segera untuk sinkronisasi inventori</p>
                        </div>
                    </div>
                </div>

                {{-- Kanan: Stock Compare & Actions --}}
                <div class="lg:col-span-5 flex flex-col gap-6">
                    
                    {{-- Visual Stock Comparison --}}
                    <div class="bg-gray-900 border-[4px] border-gray-900 shadow-[10px_10px_0_var(--color-secondary)] p-8 text-white">
                        <h4 class="text-[10px] font-bold text-secondary-dark uppercase tracking-widest mb-8 text-center border-b border-white/10 pb-4">KOMPARASI AUDIT STOK</h4>
                        
                        <div class="flex items-center justify-between gap-4 mb-10">
                            <div class="text-center flex-1">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-3">STOK SISTEM</p>
                                <div class="font-headline font-black text-4xl tracking-tighter" x-text="selectedRequest?.current"></div>
                                <p class="text-[9px] font-bold text-slate-500 uppercase mt-1">BOTOL</p>
                            </div>
                            
                            <div class="flex flex-col items-center">
                                <svg class="w-6 h-6 text-secondary animate-bounce-horizontal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                <span class="text-[10px] font-black italic mt-2 uppercase tracking-tighter" :class="selectedRequest?.diff.startsWith('+') ? 'text-green-400' : 'text-red-400'" x-text="selectedRequest?.diff"></span>
                            </div>

                            <div class="text-center flex-1">
                                <p class="text-[9px] font-bold text-primary-light uppercase tracking-widest mb-3">STOK FISIK</p>
                                <div class="font-headline font-black text-4xl text-secondary tracking-tighter" x-text="selectedRequest?.actual"></div>
                                <p class="text-[9px] font-bold text-secondary-dark uppercase mt-1 italic">ACTUAL</p>
                            </div>
                        </div>

                        <div class="bg-white/5 border border-white/10 p-4 flex items-start gap-3">
                            <svg class="w-5 h-5 text-secondary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-[10px] text-slate-300 font-bold leading-relaxed uppercase tracking-widest">Stok sistem akan otomatis diupdate menjadi <span class="text-white" x-text="selectedRequest?.actual"></span> botol setelah disetujui.</p>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="bg-white border-[4px] border-gray-900 p-8 shadow-[10px_10px_0_var(--color-gray-900)] flex flex-col gap-4">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 border-b-2 border-neutral-border pb-2">Opsi Keputusan</p>
                        
                        <button @click="handleConfirm()" class="w-full bg-primary text-white py-5 font-headline font-black text-base uppercase tracking-widest border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-1 active:shadow-none transition-all flex items-center justify-center gap-3">
                            <span>SETUJUI SINKRONISASI</span>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </button>

                        <div class="grid grid-cols-2 gap-4">
                            <button @click="handleReject()" class="w-full bg-white text-red-600 py-3 font-headline font-bold text-[10px] uppercase tracking-widest border-[3px] border-red-600 hover:bg-red-50 transition-colors shadow-[4px_4px_0_var(--color-red-600)] active:translate-y-0.5 active:shadow-none">
                                TOLAK PENGAJUAN
                            </button>
                            <a :href="getWaLink(selectedRequest)" target="_blank" class="w-full bg-[#25D366] text-white py-3 font-headline font-bold text-[10px] uppercase tracking-widest border-[3px] border-gray-900 flex items-center justify-center gap-2 hover:bg-[#1DA851] transition-all shadow-[4px_4px_0_var(--color-gray-900)] active:translate-y-0.5 active:shadow-none">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.347-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                HUBUNGI DISTRIBUTOR
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
