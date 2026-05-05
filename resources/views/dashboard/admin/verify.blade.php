<x-layouts.dashboard bgTheme="light">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">MASTER ADMIN</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>VERIFIKASI RESELLER</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.admin._menu')</x-slot:menuSlot>

    <div class="max-w-[1400px] mx-auto w-full" x-data="{
        viewMode: 'list',
        selectedReseller: null,
        step: 1,
        rejectMode: false,
        rejectSuccess: false,
        waSent: false,
        notified: {
            reseller: false,
            distributor: false
        },
        rejectReason: '',
        filterRegion: 'Semua Wilayah',
        searchQuery: '',
        distFilter: 'same',
        distSearch: '',
        selectedDistributorId: '',
        distributors: {{ json_encode($distributors) }},
        get distributorMap() {
            let map = {};
            this.distributors.forEach(d => {
                map[d.id] = d.name;
            });
            return map;
        },
        get sameRegionCount() {
            if (!this.selectedReseller) return 0;
            return this.distributors.filter(d => d.province_id == this.selectedReseller.province_id).length;
        },
        openVerification(reseller) {
            this.selectedReseller = reseller;
            this.step = 1;
            this.rejectMode = false;
            this.waSent = false;
            this.rejectReason = '';
            
            // Auto-suggest distributor in the same region
            const suggested = this.distributors.find(d => 
                (d.province_id && d.province_id == reseller.province_id)
            );
            this.selectedDistributorId = suggested ? suggested.id : '';
            
            this.viewMode = 'detail';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        goBack() {
            if (this.step > 1 && this.step < 3) {
                this.step--;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                this.viewMode = 'list';
                this.selectedReseller = null;
                this.step = 1;
                this.rejectMode = false;
                this.waSent = false;
            }
        },
        async submitApprove(e) {
            const form = e.target;
            const formData = new FormData(form);
            
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                if (data.success) {
                    this.step = 3;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    alert(data.message || 'Gagal menyetujui reseller.');
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan jaringan.');
            }
        },
        async submitReject(e) {
            const form = e.target;
            const formData = new FormData(form);
            
            try {
                // BACKEND-TODO: Real AJAX call here
                this.rejectSuccess = true;
                this.step = 4; // Use step 4 for rejection success
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan jaringan.');
            }
        },
        getRejectWaLink() {
            const phone = (this.selectedReseller?.phone ?? '').replace(/\D/g, '');
            const name = this.selectedReseller?.name ?? '';
            const msg = `Halo ${name}, mohon maaf, pendaftaran reseller CeeKlin Anda BELUM DAPAT KAMI SETUJUI saat ini.\n\nAlasan: ${this.rejectReason}\n\nSilakan lakukan pendaftaran ulang dengan data yang benar atau hubungi kami jika ada pertanyaan. Terima kasih.`;
            return 'https://wa.me/62' + (phone.startsWith('0') ? phone.substring(1) : phone) + '?text=' + encodeURIComponent(msg);
        },
        getWaLink(type) {
            const phone = (this.selectedReseller?.phone ?? '').replace(/\D/g, '');
            const name = this.selectedReseller?.name ?? '';
            const dist = this.distributorMap[this.selectedDistributorId] ?? '-';
            
            let msg = '';
            if (type === 'reseller') {
                msg = `Halo ${name}, akun reseller CeeKlin Anda telah DISETUJUI dan sudah aktif!\n\nDistributor Anda: ${dist}\n\nSilakan login menggunakan username yang Anda daftarkan di https://ceeklin.id/login\n\nJika ada pertanyaan, balas pesan ini. Terima kasih!`;
            } else if (type === 'distributor') {
                msg = `Halo ${dist}, diinfokan bahwa ada Reseller baru bernama ${name} yang telah kami alokasikan ke wilayah Anda. Mohon dibantu untuk koordinasi pesanan pertamanya. Terima kasih!`;
            }
            
            return 'https://wa.me/62' + (phone.startsWith('0') ? phone.substring(1) : phone) + '?text=' + encodeURIComponent(msg);
        }
    }">

        {{-- =================== --}}
        {{-- VIEW: LIST ANTREAN  --}}
        {{-- =================== --}}
        <div x-show="viewMode === 'list'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <div>
                    <h2 class="font-headline font-black text-2xl text-primary tracking-tighter uppercase">Menunggu Peninjauan</h2>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-1">{{ count($pendingResellers) }} Pendaftar Baru</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <div class="relative flex-1 sm:w-64">
                        <input type="text" x-model="searchQuery" placeholder="Cari Nama / NIK..." 
                            class="w-full bg-white border-[3px] border-gray-900 px-4 py-2 text-xs font-bold uppercase tracking-widest text-primary focus:outline-none focus:border-secondary pr-10 shadow-[4px_4px_0_rgba(0,0,0,0.05)]">
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                    </div>
                    <div class="relative">
                        <select x-model="filterRegion" aria-label="Filter wilayah" class="appearance-none bg-white border-[3px] border-gray-900 px-4 py-2 text-xs font-bold uppercase tracking-widest text-primary focus:outline-none focus:border-secondary cursor-pointer pr-8 shadow-[4px_4px_0_rgba(0,0,0,0.05)]">
                            <option>Semua Wilayah</option>
                            <option>Jawa Barat</option>
                            <option>Jawa Tengah</option>
                            <option>Jawa Timur</option>
                        </select>
                        <div class="absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">
                <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-4 bg-gray-900 border-b-2 border-gray-900">
                    <div class="col-span-3 text-[10px] font-headline font-bold text-white uppercase tracking-widest">Pendaftar</div>
                    <div class="col-span-2 text-[10px] font-headline font-bold text-white uppercase tracking-widest">NIK</div>
                    <div class="col-span-3 text-[10px] font-headline font-bold text-white uppercase tracking-widest">Domisili</div>
                    <div class="col-span-2 text-[10px] font-headline font-bold text-white uppercase tracking-widest">Tanggal Masuk</div>
                    <div class="col-span-2 text-[10px] font-headline font-bold text-white uppercase tracking-widest text-right">Aksi</div>
                </div>

                <div class="divide-y-2 divide-neutral-border">
                    @foreach($pendingResellers as $reseller)
                    <div x-show="(filterRegion === 'Semua Wilayah' || '{{ $reseller->province_name }}' === filterRegion) && 
                                 (!searchQuery || '{{ strtolower($reseller->name) }}'.includes(searchQuery.toLowerCase()) || '{{ $reseller->nik }}'.includes(searchQuery))"
                         class="animate-in stagger-{{ ($loop->iteration % 5) + 1 }} flex flex-col md:grid md:grid-cols-12 gap-4 px-6 py-5 items-start md:items-center hover:bg-neutral-light transition-colors">
                        <div class="md:col-span-3 w-full">
                            <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pendaftar</p>
                            <p class="font-bold text-sm text-gray-900 uppercase leading-tight">{{ $reseller->name }}</p>
                            <p class="text-[10px] font-bold text-slate-500 mt-1">{{ $reseller->phone }}</p>
                        </div>
                        <div class="md:col-span-2 w-full">
                            <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">NIK</p>
                            <p class="text-xs font-bold text-gray-900 tracking-widest">{{ $reseller->nik }}</p>
                        </div>
                        <div class="md:col-span-3 w-full">
                            <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Domisili</p>
                            <p class="text-xs font-bold text-gray-900 uppercase">{{ $reseller->city_name }}</p>
                            <p class="text-[10px] font-bold text-slate-500 uppercase mt-0.5">{{ $reseller->province_name }}</p>
                        </div>
                        <div class="md:col-span-2 w-full">
                            <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tanggal Masuk</p>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-[9px] font-bold uppercase tracking-widest border border-yellow-300">{{ $reseller->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="md:col-span-2 w-full flex justify-start md:justify-end">
                            <button @click="openVerification({ 
                                id: {{ $reseller->id }}, 
                                name: '{{ $reseller->name }}', 
                                nik: '{{ $reseller->nik }}', 
                                phone: '{{ $reseller->phone }}', 
                                province: '{{ $reseller->province_name }}', 
                                province_id: '{{ $reseller->province_id }}',
                                city: '{{ $reseller->city_name }}', 
                                city_id: '{{ $reseller->city_id }}',
                                address: '{{ str_replace(["\r", "\n"], ' ', $reseller->address) }}',
                                bank_name: '{{ $reseller->bank_name }}',
                                bank_account_name: '{{ $reseller->bank_account_name }}',
                                bank_account_number: '{{ $reseller->bank_account_number }}',
                                ktp: '{{ Storage::url($reseller->ktp_photo) }}'
                            })"
                                class="bg-primary text-white px-4 py-2 text-[10px] font-bold uppercase tracking-widest border-2 border-gray-900 hover:bg-primary-hover shadow-[3px_3px_0_var(--color-gray-900)] active:translate-y-0.5 active:shadow-none transition-all">
                                Tinjau
                            </button>
                        </div>
                    </div>
                    @endforeach

                    <!-- Empty State for Filter/Search -->
                    <div x-show="[...$el.parentElement.children].filter(el => el.hasAttribute('x-show') && el.style.display !== 'none').length === 0"
                         x-cloak class="px-8 py-16 text-center animate-in">
                        <svg class="w-12 h-12 text-slate-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Tidak ada data yang cocok dengan pencarian Anda</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================== --}}
        {{-- VIEW: DETAIL VERIFIKASI --}}
        {{-- ====================== --}}
        <div x-show="viewMode === 'detail'" x-cloak style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">

            <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
                <div>
                    <h2 class="font-headline font-black text-2xl text-primary tracking-tighter uppercase">Tinjau Reseller</h2>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1" x-text="'ID PENDAFTARAN: #' + selectedReseller?.id"></p>
                </div>
                <button @click="goBack()"
                    class="flex items-center gap-2 bg-white text-gray-900 px-4 py-2 text-[10px] font-bold uppercase tracking-widest border-[3px] border-gray-900 hover:bg-neutral-light transition-colors shadow-[4px_4px_0_var(--color-gray-900)] active:translate-y-0.5 active:shadow-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span x-text="step > 1 && step < 3 ? 'Kembali' : (step === 3 || step === 4 ? 'Tutup' : 'Kembali ke Antrean')"></span>
                </button>
            </div>

            {{-- STEP INDICATOR BAR --}}
            <div class="flex items-center gap-0 mb-8 bg-white border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-primary-darkest)] overflow-hidden">
                <template x-for="(label, i) in ['Tinjau Data', 'Tetapkan Distributor', 'Selesai']" :key="i">
                    <div class="flex-1 flex items-center justify-center gap-2 py-3 px-2 border-r border-gray-200 last:border-r-0 transition-colors duration-300"
                         :class="step > i+1 ? 'bg-primary text-white' : step === i+1 ? 'bg-secondary text-white' : 'bg-neutral-light text-slate-400'">
                        <span class="w-6 h-6 flex items-center justify-center text-[10px] font-black border-2 flex-shrink-0"
                              :class="step > i+1 ? 'border-white bg-white text-primary' : step === i+1 ? 'border-white' : 'border-slate-300'"
                              x-text="step > i+1 ? '✓' : i+1"></span>
                        <span class="text-[9px] font-bold uppercase tracking-widest hidden sm:inline" x-text="label"></span>
                    </div>
                </template>
            </div>

            {{-- ========================= --}}
            {{-- STEP 1: TINJAU DATA       --}}
            {{-- ========================= --}}
            <div x-show="step === 1"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
            <div class="bg-white border-[6px] border-gray-900 shadow-[12px_12px_0_var(--color-secondary)] w-full flex flex-col">

                {{-- Petunjuk Scroll (Affordance) --}}
                <div class="bg-yellow-50 border-b-[4px] border-gray-900 p-4 md:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h4 class="font-headline font-black text-yellow-800 text-sm uppercase">Tinjau Kesesuaian Data</h4>
                        <p class="text-[10px] font-bold text-yellow-700 uppercase tracking-widest mt-1">Mohon teliti kelengkapan data. Gulir ke bagian paling bawah form untuk menolak atau menyetujui pendaftar.</p>
                    </div>
                    <button type="button" @click="document.getElementById('action-buttons-step1').scrollIntoView({behavior: 'smooth'})"
                        class="bg-white text-yellow-800 border-[3px] border-yellow-600 px-4 py-2 font-headline font-bold text-[10px] uppercase tracking-widest hover:bg-yellow-50 transition-colors shadow-[3px_3px_0_var(--color-yellow-600)] active:translate-y-1 active:shadow-none whitespace-nowrap">
                        Lompat ke Tombol Aksi ↓
                    </button>
                </div>

                {{-- Data Reseller (Spacious Layout) --}}
                <div class="p-6 md:p-8 flex-1 flex flex-col md:flex-row gap-8 bg-neutral border-b-[4px] border-gray-900">
                    <div class="w-full md:w-1/2 flex flex-col gap-6">
                        <div class="bg-white border-[3px] border-gray-900 p-5 shadow-[4px_4px_0_var(--color-gray-900)]">
                            <p class="text-[9px] font-bold text-secondary uppercase tracking-widest mb-1">Informasi Personal</p>
                            <h4 class="font-headline font-black text-xl text-primary uppercase mb-4" x-text="selectedReseller?.name"></h4>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-0.5">NIK KTP</p>
                                    <p class="font-bold text-gray-900 text-sm tracking-widest" x-text="selectedReseller?.nik"></p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-0.5">Nomor WhatsApp</p>
                                    <p class="font-bold text-gray-900 text-sm tracking-widest" x-text="selectedReseller?.phone"></p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white border-[3px] border-gray-900 p-5 shadow-[4px_4px_0_var(--color-gray-900)]">
                            <p class="text-[9px] font-bold text-secondary uppercase tracking-widest mb-3">Informasi Domisili</p>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-0.5">Provinsi & Kota</p>
                                    <p class="font-bold text-gray-900 text-sm uppercase" x-text="selectedReseller?.city + ', ' + selectedReseller?.province"></p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-0.5">Alamat Detail</p>
                                    <p class="font-bold text-gray-900 text-sm leading-relaxed" x-text="selectedReseller?.address"></p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white border-[3px] border-gray-900 p-5 shadow-[4px_4px_0_var(--color-gray-900)]">
                            <p class="text-[9px] font-bold text-secondary uppercase tracking-widest mb-3">Informasi Perbankan</p>
                            <div class="space-y-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-0.5">Bank & No. Rekening</p>
                                        <p class="font-bold text-gray-900 text-sm uppercase" x-text="selectedReseller?.bank_name + ' — ' + selectedReseller?.bank_account_number"></p>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-0.5">Atas Nama</p>
                                    <p class="font-bold text-gray-900 text-sm uppercase" x-text="selectedReseller?.bank_account_name"></p>
                                    <template x-if="selectedReseller?.bank_account_name && selectedReseller?.bank_account_name.toUpperCase() !== selectedReseller?.name.toUpperCase()">
                                        <p class="text-[8px] font-bold text-red-600 uppercase mt-1">⚠️ Nama pemilik rekening berbeda dengan nama pendaftar</p>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-1/2 flex flex-col">
                        <p class="text-[10px] font-bold text-primary uppercase tracking-widest mb-2">Lampiran Foto KTP</p>
                        <div class="bg-gray-900 p-2 border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-primary)] flex-1 min-h-[300px] flex items-center justify-center relative group">
                            <img :src="selectedReseller?.ktp" alt="Foto KTP" class="w-full h-full object-contain mix-blend-screen opacity-90 group-hover:opacity-100 transition-opacity">
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-gray-900/40 backdrop-blur-sm">
                                <a :href="selectedReseller?.ktp" target="_blank"
                                    class="bg-white text-gray-900 px-4 py-2 text-[10px] font-bold uppercase tracking-widest border-2 border-gray-900 flex items-center gap-2 hover:bg-secondary hover:text-white transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    Perbesar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============================== --}}
                {{-- AKSI: STEP 1 — Tolak / Setujui --}}
                {{-- ============================== --}}
                <div id="action-buttons-step1" x-show="!rejectMode" x-transition class="p-6 md:p-8 bg-white border-t-[4px] border-gray-900">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button @click="rejectMode = true"
                            class="w-full sm:w-1/3 bg-neutral-light text-red-600 border-[3px] border-red-600 px-6 py-4 font-headline font-black text-sm uppercase tracking-widest hover:bg-red-50 transition-colors shadow-[4px_4px_0_var(--color-red-600)] active:translate-y-1 active:shadow-none">
                            TOLAK DATA
                        </button>
                        <button @click="step = 2; window.scrollTo({ top: 0, behavior: 'smooth' })"
                            class="w-full sm:w-2/3 bg-primary text-white border-[3px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)] px-6 py-4 font-headline font-black text-sm uppercase tracking-widest hover:bg-primary-hover active:translate-y-1 active:shadow-none transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            SETUJUI — LANJUT TETAPKAN DISTRIBUTOR
                        </button>
                    </div>
                </div>

                {{-- ============================== --}}
                {{-- AKSI: TOLAK — Form Alasan      --}}
                {{-- ============================== --}}
                <div x-show="rejectMode" style="display: none;" x-transition
                     class="p-6 md:p-8 bg-red-50 border-t-[4px] border-red-600">
                    <p class="text-[10px] font-bold text-red-700 uppercase tracking-widest mb-3">Alasan Penolakan (Wajib Diisi)</p>
                    <form action="{{ route('admin.verify.reject') }}" method="POST" @submit.prevent="submitReject">
                        @csrf
                        <input type="hidden" name="reseller_id" :value="selectedReseller?.id">
                        <textarea name="reason" x-model="rejectReason" rows="3" required
                            placeholder="Contoh: Foto KTP blur dan NIK tidak sesuai..."
                            class="w-full bg-white border-2 border-red-300 px-4 py-3 text-sm font-bold text-gray-900 focus:outline-none focus:border-red-600 resize-none mb-4 placeholder:text-slate-400"></textarea>
                        <div class="flex gap-4">
                            <button type="button" @click="rejectMode = false"
                                class="w-1/3 bg-white text-gray-600 border-[3px] border-gray-400 px-4 py-3 font-headline font-bold text-xs uppercase tracking-widest hover:bg-gray-50 transition-colors">
                                Batal
                            </button>
                            <button type="submit" :disabled="rejectReason.trim() === ''"
                                class="w-2/3 bg-red-600 text-white border-[3px] border-red-800 shadow-[4px_4px_0_rgba(0,0,0,0.15)] px-4 py-3 font-headline font-black text-xs uppercase tracking-widest hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                                KIRIM PENOLAKAN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            </div>

            {{-- ====================================== --}}
            {{-- STEP 2: TETAPKAN DISTRIBUTOR           --}}
            {{-- ====================================== --}}
            <div x-show="step === 2" style="display: none;"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
            <div class="bg-white border-[6px] border-gray-900 shadow-[12px_12px_0_var(--color-secondary)] w-full flex flex-col">

                <div>
                    <div class="px-6 md:px-8 py-4 bg-primary flex items-center gap-3 border-t-[4px] border-gray-900">
                        <span class="w-8 h-8 bg-white text-primary font-black text-sm flex items-center justify-center flex-shrink-0 border-2 border-gray-900">2</span>
                        <div>
                            <h3 class="font-headline font-bold text-white text-sm uppercase tracking-tight">Tetapkan Distributor untuk Reseller Ini</h3>
                            <p class="text-[9px] text-white/60 uppercase tracking-widest">Pilih berdasarkan wilayah reseller</p>
                        </div>
                    </div>

                    <div class="p-6 md:p-8 bg-neutral-light">

                        {{-- Info Wilayah Reseller --}}
                        <div class="bg-white border-[3px] border-primary p-4 mb-6 flex items-center gap-4">
                            <div class="w-10 h-10 bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Wilayah Reseller</p>
                                <p class="font-headline font-bold text-base text-primary uppercase" x-text="selectedReseller?.city + ', ' + selectedReseller?.province"></p>
                            </div>
                        </div>

                        {{-- BACKEND-TODO: Form approve + assign distributor --}}
                        <form action="{{ route('admin.verify.approve') }}" method="POST"
                              class="flex flex-col gap-6"
                              @submit.prevent="submitApprove">
                            @csrf
                            <input type="hidden" name="reseller_id" :value="selectedReseller?.id">

                            {{-- Filter Distributor yang Lebih User Friendly --}}
                            <div class="flex flex-col gap-4 mb-6">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div class="flex p-1 bg-neutral-light border-2 border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)]">
                                        <button type="button" @click="distFilter = 'same'"
                                            :class="distFilter === 'same' ? 'bg-primary text-white shadow-[2px_2px_0_rgba(0,0,0,0.2)]' : 'text-slate-400 hover:text-primary'"
                                            class="px-4 py-2 font-headline font-black text-[10px] uppercase tracking-widest transition-all flex items-center gap-2">
                                            <span>Wilayah Sama</span>
                                            <span class="px-1.5 py-0.5 bg-black/10 text-[9px] rounded-sm" x-text="sameRegionCount"></span>
                                        </button>
                                        <button type="button" @click="distFilter = 'all'"
                                            :class="distFilter === 'all' ? 'bg-primary text-white shadow-[2px_2px_0_rgba(0,0,0,0.2)]' : 'text-slate-400 hover:text-primary'"
                                            class="px-4 py-2 font-headline font-black text-[10px] uppercase tracking-widest transition-all flex items-center gap-2">
                                            <span>Semua Wilayah</span>
                                            <span class="px-1.5 py-0.5 bg-black/10 text-[9px] rounded-sm" x-text="distributors.length"></span>
                                        </button>
                                    </div>

                                    <div class="relative flex-grow max-w-md">
                                        <input type="text" x-model="distSearch" placeholder="Cari nama distributor..."
                                            class="w-full bg-white border-[3px] border-primary px-4 py-2 text-xs font-bold uppercase tracking-widest text-primary focus:outline-none focus:border-secondary pr-10 shadow-[4px_4px_0_rgba(0,0,0,0.03)]">
                                        <div class="absolute right-3 top-1/2 -translate-y-1/2 text-primary opacity-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Instruksi Cerdas Jika Kosong --}}
                                <div x-show="distFilter === 'same' && sameRegionCount === 0" x-transition
                                     class="bg-yellow-50 border-l-[6px] border-yellow-400 p-5 shadow-[4px_4px_0_rgba(0,0,0,0.05)]">
                                    <div class="flex items-start gap-4">
                                        <div class="w-10 h-10 bg-yellow-400 flex items-center justify-center flex-shrink-0 border-2 border-yellow-600">
                                            <svg class="w-6 h-6 text-yellow-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-headline font-black text-yellow-900 text-xs uppercase tracking-widest mb-1">Wilayah Ini Belum Tercover!</p>
                                            <p class="text-[11px] font-bold text-yellow-800 leading-tight mb-3">
                                                Belum ada distributor yang terdaftar di <span class="underline" x-text="selectedReseller?.province"></span>. 
                                                Silakan gunakan tombol di bawah untuk mencari distributor di provinsi lain.
                                            </p>
                                            <button type="button" @click="distFilter = 'all'"
                                                class="bg-yellow-400 text-yellow-900 px-4 py-2 text-[9px] font-black uppercase tracking-widest border-2 border-yellow-600 hover:bg-yellow-500 transition-all shadow-[2px_2px_0_var(--color-yellow-600)] active:translate-y-0.5 active:shadow-none">
                                                Pindah ke Semua Wilayah &rarr;
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest italic" x-show="distFilter === 'same' && sameRegionCount > 0">
                                    * Menampilkan <span class="text-secondary" x-text="sameRegionCount"></span> distributor di <span class="text-secondary" x-text="selectedReseller?.province"></span>
                                </p>
                            </div>

                            {{-- Pilih Distributor dalam Bentuk Grid Kartu --}}
                            <div class="flex flex-col gap-3">
                                <label class="text-[10px] font-bold text-primary uppercase tracking-widest block mb-1">
                                    Pilih Distributor Yang Akan Menangani <span class="text-red-500">*</span>
                                </label>
                                
                                {{-- Hidden Input untuk Form --}}
                                <input type="hidden" name="distributor_id" :value="selectedDistributorId" required>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[400px] overflow-y-auto p-2 border-2 border-dashed border-gray-200 bg-neutral/30 scrollbar-thin scrollbar-thumb-primary">
                                    <template x-for="dist in distributors.filter(d => 
                                        (distFilter === 'all' || d.province_id == selectedReseller?.province_id) &&
                                        (!distSearch || d.name.toLowerCase().includes(distSearch.toLowerCase()))
                                    )" :key="dist.id">
                                        <div @click="selectedDistributorId = dist.id"
                                             :class="selectedDistributorId == dist.id ? 'border-primary bg-primary/5 shadow-[6px_6px_0_var(--color-primary)] -translate-x-1 -translate-y-1' : 'border-gray-300 bg-white hover:border-primary hover:shadow-[4px_4px_0_rgba(0,0,0,0.1)]'"
                                             class="cursor-pointer border-[3px] p-4 transition-all duration-200 flex flex-col justify-between gap-4 relative overflow-hidden group">
                                            
                                            {{-- Selected Badge --}}
                                            <div x-show="selectedDistributorId == dist.id" class="absolute top-0 right-0 bg-primary text-white p-1 shadow-[-2px_2px_0_var(--color-gray-900)] border-b-2 border-l-2 border-gray-900">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                                            </div>

                                            <div>
                                                <div class="flex justify-between items-start mb-2">
                                                    <h5 class="font-headline font-black text-sm text-primary uppercase leading-tight pr-6" x-text="dist.name"></h5>
                                                </div>
                                                <div class="flex items-center gap-2 text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-3">
                                                    <svg class="w-3 h-3 text-secondary" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                                                    <span x-text="dist.province_name || 'Nasional'"></span>
                                                </div>
                                            </div>

                                            <div class="flex items-center justify-between border-t border-dashed border-gray-200 pt-3">
                                                <div class="flex flex-col">
                                                    <span class="text-[8px] font-bold text-slate-400 uppercase tracking-tighter">Stok Tersedia</span>
                                                    <span :class="(dist.current_stock ?? 0) < 100 ? 'text-red-600' : 'text-green-600'" 
                                                          class="font-headline font-black text-base" 
                                                          x-text="(dist.current_stock ?? 0).toLocaleString('id-ID') + ' PCS'"></span>
                                                </div>
                                                <div :class="dist.province_id == selectedReseller?.province_id ? 'bg-green-100 text-green-700 border-green-600' : 'bg-yellow-100 text-yellow-800 border-yellow-600'"
                                                     class="px-2 py-0.5 border text-[8px] font-black uppercase tracking-widest"
                                                     x-text="dist.province_id == selectedReseller?.province_id ? 'Wilayah Sama' : 'Beda Wilayah'">
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    {{-- Empty Grid State --}}
                                    <template x-if="distributors.filter(d => (distFilter === 'all' || d.province_id == selectedReseller?.province_id) && (!distSearch || d.name.toLowerCase().includes(distSearch.toLowerCase()))).length === 0">
                                        <div class="col-span-full py-12 flex flex-col items-center justify-center opacity-50 italic">
                                            <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <p class="text-[10px] font-bold uppercase tracking-widest">Tidak ada distributor yang ditemukan</p>
                                        </div>
                                    </template>
                                </div>

                                {{-- Warning Info jika Beda Wilayah --}}
                                <div x-show="selectedDistributorId && distributors.find(d => d.id == selectedDistributorId)?.province_id != selectedReseller?.province_id" x-transition style="display:none;"
                                     class="mt-2 bg-yellow-400 border-[3px] border-yellow-600 p-4 shadow-[4px_4px_0_var(--color-yellow-600)] flex gap-3 items-start">
                                    <svg class="w-6 h-6 text-yellow-900 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    <div>
                                        <p class="text-[10px] font-black text-yellow-900 uppercase tracking-widest mb-1">PERHATIAN: PENGALIHAN WILAYAH</p>
                                        <p class="text-[9px] font-bold text-yellow-800 leading-tight">Distributor terpilih berada di luar wilayah pendaftar. Pastikan ini adalah keputusan yang disengaja.</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Tombol Final --}}
                            <div class="mt-8 border-t border-dashed border-gray-300 pt-6">
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <button type="button" @click="step = 1; window.scrollTo({ top: 0, behavior: 'smooth' })"
                                        class="w-full sm:w-1/3 bg-white text-gray-600 border-[3px] border-gray-400 px-6 py-4 font-headline font-bold text-sm uppercase tracking-widest hover:bg-gray-50 transition-colors shadow-[4px_4px_0_var(--color-gray-400)] active:translate-y-1 active:shadow-none">
                                        Kembali
                                    </button>
                                    <button type="submit" :disabled="selectedDistributorId === ''"
                                        class="w-full sm:w-2/3 bg-primary text-white border-[3px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)] px-6 py-4 font-headline font-black text-sm uppercase tracking-widest hover:bg-primary-hover active:translate-y-1 active:shadow-none transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        SETUJUI & TETAPKAN DISTRIBUTOR
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            </div>

            {{-- ====================================== --}}
            {{-- STEP 3: BERHASIL DIVERIFIKASI          --}}
            {{-- ====================================== --}}
            <div x-show="step === 3" style="display: none;"
                 x-transition:enter="transition ease-out duration-400"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
            <div class="bg-white border-[6px] border-gray-900 shadow-[12px_12px_0_var(--color-secondary)] w-full flex flex-col">

                    {{-- Konfirmasi Berhasil --}}
                    <div class="p-8 md:p-12 bg-white flex flex-col items-center text-center">

                        {{-- Icon Sukses --}}
                        <div class="relative mb-8">
                            <div class="absolute inset-0 bg-secondary translate-x-2 translate-y-2 border-[4px] border-gray-900"></div>
                            <div class="w-24 h-24 bg-primary flex items-center justify-center border-[4px] border-gray-900 relative z-10">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>

                        <h3 class="font-headline font-black text-2xl text-primary uppercase tracking-tighter mb-2">
                            Reseller Disetujui!
                        </h3>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-8">Informasikan pihak terkait agar aktivasi berjalan lancar:</p>

                        <div class="w-full max-w-sm flex flex-col gap-4">
                            <!-- Notify Reseller -->
                            <div class="flex items-center gap-4 bg-neutral-light border-[3px] border-gray-900 p-4 shadow-[4px_4px_0_rgba(0,0,0,0.05)] transition-all"
                                 :class="notified.reseller ? 'bg-green-50 border-green-600 shadow-none translate-x-1 translate-y-1' : ''">
                                <div class="flex-1 text-left">
                                    <p class="text-[9px] font-black uppercase tracking-widest mb-0.5" :class="notified.reseller ? 'text-green-700' : 'text-slate-400'">Kepada Reseller</p>
                                    <p class="font-bold text-xs" x-text="selectedReseller?.name"></p>
                                </div>
                                <a :href="getWaLink('reseller')" target="_blank" @click="notified.reseller = true"
                                   class="bg-[#25D366] text-white p-2 border-2 border-gray-900 shadow-[2px_2px_0_var(--color-gray-900)] hover:bg-[#1DA851] active:translate-y-0.5 active:shadow-none transition-all">
                                    <template x-if="!notified.reseller">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.347-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                    </template>
                                    <template x-if="notified.reseller">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                                    </template>
                                </a>
                            </div>

                            <!-- Notify Distributor -->
                            <div class="flex items-center gap-4 bg-neutral-light border-[3px] border-gray-900 p-4 shadow-[4px_4px_0_rgba(0,0,0,0.05)] transition-all"
                                 :class="notified.distributor ? 'bg-green-50 border-green-600 shadow-none translate-x-1 translate-y-1' : ''">
                                <div class="flex-1 text-left">
                                    <p class="text-[9px] font-black uppercase tracking-widest mb-0.5" :class="notified.distributor ? 'text-green-700' : 'text-slate-400'">Kepada Distributor</p>
                                    <p class="font-bold text-xs" x-text="distributorMap[selectedDistributorId] ?? '-'"></p>
                                </div>
                                <a :href="getWaLink('distributor')" target="_blank" @click="notified.distributor = true"
                                   class="bg-[#25D366] text-white p-2 border-2 border-gray-900 shadow-[2px_2px_0_var(--color-gray-900)] hover:bg-[#1DA851] active:translate-y-0.5 active:shadow-none transition-all">
                                    <template x-if="!notified.distributor">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.347-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                    </template>
                                    <template x-if="notified.distributor">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                                    </template>
                                </a>
                            </div>

                            <button @click="window.location.reload()"
                                class="mt-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest hover:text-primary transition-colors underline underline-offset-2">
                                Selesai & Kembali ke Antrean →
                            </button>
                        </div>
               </div>
                    </div>
                </div>
            </div>

            {{-- ====================================== --}}
            {{-- STEP 4: PENOLAKAN BERHASIL             --}}
            {{-- ====================================== --}}
            <div x-show="step === 4" style="display: none;"
                 x-transition:enter="transition ease-out duration-400"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                <div class="bg-white border-[6px] border-gray-900 shadow-[12px_12px_0_var(--color-red-600)] w-full flex flex-col">
                    <div class="p-8 md:p-12 bg-white flex flex-col items-center text-center">
                        <div class="w-20 h-20 bg-red-600 flex items-center justify-center border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)] mb-8">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        
                        <h3 class="font-headline font-black text-2xl text-red-600 uppercase mb-2">Reseller Ditolak</h3>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2" x-text="selectedReseller?.name"></p>
                        <div class="bg-red-50 border-2 border-red-200 p-4 mb-8 w-full max-w-md">
                            <p class="text-[9px] font-black text-red-700 uppercase mb-1">Alasan Penolakan:</p>
                            <p class="text-xs font-bold text-gray-900 italic" x-text="rejectReason"></p>
                        </div>

                        <div class="w-full max-w-sm flex flex-col gap-3">
                            <a :href="getRejectWaLink()" target="_blank" @click="waSent = true"
                               class="w-full flex items-center justify-center gap-3 bg-[#25D366] text-white px-6 py-5 font-headline font-black text-sm uppercase tracking-widest border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)] hover:bg-[#1DA851] active:translate-y-1 active:shadow-none transition-all group">
                                <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.347-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                <span>Kirim Alasan via WA</span>
                            </a>
                            <button @click="window.location.reload()"
                                class="mt-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest hover:text-primary transition-colors underline underline-offset-2">
                                Kembali ke Antrean →
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            </div>

        </div>
    </div>

</x-layouts.dashboard>
