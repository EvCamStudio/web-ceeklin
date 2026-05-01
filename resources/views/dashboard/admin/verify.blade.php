<x-layouts.dashboard bgTheme="light">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">MASTER ADMIN</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>VERIFIKASI RESELLER</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.admin._menu')</x-slot:menuSlot>

    <div class="max-w-[1400px] mx-auto w-full" x-data="{
        viewMode: 'list',
        selectedReseller: null,
        rejectMode: false,
        approveMode: false,
        successMode: false,
        waSent: false,
        rejectReason: '',
        selectedDistributorId: '',
        distributors: {{ json_encode($distributors) }},
        get distributorMap() {
            let map = {};
            this.distributors.forEach(d => {
                map[d.id] = d.name;
            });
            return map;
        },
        openVerification(reseller) {
            this.selectedReseller = reseller;
            this.rejectMode = false;
            this.approveMode = false;
            this.successMode = false;
            this.waSent = false;
            this.rejectReason = '';
            this.selectedDistributorId = '';
            this.viewMode = 'detail';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        goBack() {
            this.viewMode = 'list';
            this.selectedReseller = null;
            this.approveMode = false;
            this.rejectMode = false;
            this.successMode = false;
            this.waSent = false;
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
                    this.successMode = true;
                    this.approveMode = false;
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
                    window.location.reload();
                } else {
                    alert(data.message || 'Gagal menolak reseller.');
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan jaringan.');
            }
        },
        getWaLink() {
            const phone = (this.selectedReseller?.phone ?? '').replace(/\D/g, '');
            const name = this.selectedReseller?.name ?? '';
            const dist = this.distributorMap[this.selectedDistributorId] ?? '-';
            const msg = `Halo ${name}, akun reseller CeeKlin Anda telah DISETUJUI dan sudah aktif!\n\nDistributor Anda: ${dist}\n\nSilakan login menggunakan username yang Anda daftarkan di https://ceeklin.id/login\n\nJika ada pertanyaan, balas pesan ini. Terima kasih!`;
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
                <div class="flex gap-2">
                    <div class="relative">
                        <select aria-label="Filter wilayah" class="appearance-none bg-white border-[3px] border-gray-900 px-4 py-2 text-xs font-bold uppercase tracking-widest text-primary focus:outline-none focus:border-secondary cursor-pointer pr-8">
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
                    <div class="flex flex-col md:grid md:grid-cols-12 gap-4 px-6 py-5 items-start md:items-center hover:bg-neutral-light transition-colors">
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
                                city: '{{ $reseller->city_name }}', 
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
                </div>
            </div>
        </div>

        {{-- ====================== --}}
        {{-- VIEW: DETAIL VERIFIKASI --}}
        {{-- ====================== --}}
        <div x-show="viewMode === 'detail'" style="display: none;"
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
                    Kembali ke Antrean
                </button>
            </div>

            <div class="bg-white border-[6px] border-gray-900 shadow-[12px_12px_0_var(--color-secondary)] w-full flex flex-col">

                {{-- Data Reseller --}}
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
                <div x-show="!rejectMode && !approveMode" x-transition class="p-6 md:p-8 bg-white border-t-[4px] border-gray-900">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button @click="rejectMode = true"
                            class="w-full sm:w-1/3 bg-neutral-light text-red-600 border-[3px] border-red-600 px-6 py-4 font-headline font-black text-sm uppercase tracking-widest hover:bg-red-50 transition-colors">
                            TOLAK DATA
                        </button>
                        <button @click="approveMode = true"
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
                    {{-- BACKEND-TODO: Form action reject --}}
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

                {{-- ============================================= --}}
                {{-- AKSI: SETUJUI — STEP 2 Tetapkan Distributor   --}}
                {{-- ============================================= --}}
                <div x-show="approveMode" style="display: none;" x-transition>

                    {{-- Step Header --}}
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

                            {{-- Pilih Distributor --}}
                            <div>
                                <label class="text-[10px] font-bold text-primary uppercase tracking-widest block mb-2">
                                    Pilih Distributor <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select name="distributor_id" x-model="selectedDistributorId" required
                                        class="appearance-none w-full bg-white border-[3px] border-primary px-4 py-3 font-body text-sm font-bold text-primary focus:outline-none focus:border-secondary transition-colors cursor-pointer pr-8">
                                        <option value="">-- Pilih Distributor --</option>
                                        <template x-for="dist in distributors" :key="dist.id">
                                            <option :value="dist.id" x-text="dist.name"></option>
                                        </template>
                                    </select>
                                    <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-primary">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                                <div x-show="selectedDistributorId && distributors.find(d => d.id == selectedDistributorId)?.province_id != selectedReseller?.province_id" x-transition
                                     class="mt-2 bg-yellow-50 border-l-[4px] border-yellow-500 p-3">
                                    <p class="text-[10px] font-bold text-yellow-800 uppercase tracking-widest">⚠️ Distributor Wilayah Lain — Pastikan alasan pengalihan wilayah sudah sesuai kebijakan</p>
                                </div>
                            </div>

                            {{-- Tombol Final --}}
                            <div class="flex flex-col sm:flex-row gap-4">
                                <button type="button" @click="approveMode = false"
                                    class="w-full sm:w-1/3 bg-white text-gray-600 border-[3px] border-gray-400 px-6 py-4 font-headline font-bold text-sm uppercase tracking-widest hover:bg-gray-50 transition-colors">
                                    Kembali
                                </button>
                                <button type="submit" :disabled="selectedDistributorId === ''"
                                    class="w-full sm:w-2/3 bg-primary text-white border-[3px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)] px-6 py-4 font-headline font-black text-sm uppercase tracking-widest hover:bg-primary-hover active:translate-y-1 active:shadow-none transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    SETUJUI & TETAPKAN DISTRIBUTOR
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- SUCCESS SCREEN: Muncul setelah form submit  --}}
                {{-- ============================================ --}}
                <div x-show="successMode" style="display: none;"
                     x-transition:enter="transition ease-out duration-400"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100">

                    {{-- Konfirmasi Berhasil --}}
                    <div class="p-8 md:p-12 bg-white border-t-[4px] border-gray-900 flex flex-col items-center text-center">

                        {{-- Icon Sukses --}}
                        <div class="w-20 h-20 bg-primary flex items-center justify-center border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)] mb-6">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>

                        <h3 class="font-headline font-black text-2xl text-primary uppercase tracking-tighter mb-2">
                            Reseller Disetujui!
                        </h3>
                        <p class="font-bold text-sm text-gray-900 uppercase mb-1" x-text="selectedReseller?.name"></p>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1"
                           x-text="'Distributor: ' + (distributorMap[selectedDistributorId] ?? '-')"></p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-8">Akun aktif & siap digunakan</p>

                        {{-- CTA: Notifikasi WA — 1 tombol, 1 aksi --}}
                        <div class="w-full max-w-sm flex flex-col items-center gap-3">
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Langkah berikutnya:</p>

                            <div x-show="!waSent" class="w-full" x-transition>
                                <a :href="getWaLink()" target="_blank"
                                   @click="waSent = true"
                                   class="w-full flex items-center justify-center gap-3 bg-secondary text-white px-6 py-5 font-headline font-black text-base uppercase tracking-widest border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)] hover:bg-secondary-dark active:translate-y-1 active:shadow-none transition-all">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    Kirim Konfirmasi ke Reseller via WA
                                </a>
                                <p class="text-[9px] text-slate-400 text-center mt-2 font-bold uppercase tracking-widest">Pesan sudah disiapkan otomatis</p>
                            </div>

                            <div x-show="waSent" style="display:none;" x-transition
                                 class="w-full flex items-center justify-center gap-3 bg-green-50 border-[3px] border-green-600 px-6 py-4">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                <span class="font-headline font-bold text-sm text-green-700 uppercase tracking-widest">Notifikasi Terkirim ✓</span>
                            </div>

                            <button @click="goBack()"
                                class="mt-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest hover:text-primary transition-colors underline underline-offset-2">
                                Kembali ke Antrean →
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

</x-layouts.dashboard>
