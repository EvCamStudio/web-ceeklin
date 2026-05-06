<x-layouts.dashboard bgTheme="light">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">MASTER ADMIN</span>
    </x-slot:subtitleSlot>

    <x-slot:topbarTitle>MANAJEMEN DISTRIBUTOR</x-slot:topbarTitle>

    <x-slot:menuSlot>
        @include('dashboard.admin._menu')
    </x-slot:menuSlot>

    {{-- Main Container dengan View Switcher --}}
    <div x-data="{ 
        view: 'list',
        filterRegion: 'Semua Wilayah',
        companyName: '',
        phone: '',
        username: '',
        password: '',
        selectedDistributor: null,
        successMode: false,
        tableExpanded: false,
        confirmMode: 'none', // 'none', 'deactivate', 'delete'
        confirmPassword: '',
        waSent: false,
        errors: {},
        resetForm() {
            this.companyName = '';
            this.phone = '';
            this.username = '';
            this.password = '';
            this.initialStock = 0;
            this.successMode = false;
            this.waSent = false;
            this.errors = {};
        },
        async submitForm(e) {
            this.errors = {};
            const form = e.target;
            if (!this.companyName) this.errors.company_name = ['Nama perusahaan wajib diisi'];
            if (!form.region.value) this.errors.region = ['Wilayah wajib dipilih'];
            if (!this.phone) this.errors.phone = ['Nomor WA wajib diisi'];
            if (!this.username) this.errors.username = ['Username wajib diisi'];
            if (!this.password) this.errors.password = ['Password wajib diisi'];
            if (Object.keys(this.errors).length > 0) return;
            {{-- 
                BACKEND-TODO: 
                Logika pengiriman form menggunakan AJAX fetch. 
                Pastikan Controller merespon dengan JSON: { success: true } atau { success: false, errors: [...] }
            --}}
            const formData = new FormData(form);
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await response.json();
                if (data.success) {
                    this.successMode = true;
                } else {
                    this.errors = data.errors || { general: [data.message || 'Gagal membuat akun distributor.'] };
                }
            } catch (err) {
                console.error(err);
                this.errors = { general: ['Terjadi kesalahan jaringan. Silakan coba lagi.'] };
            }
        },
        getWaLink() {
            const p = this.phone.replace(/\D/g, '');
            const msg = `Halo ${this.companyName}, akun distributor CeeKlin Anda telah dibuat.\n\nUsername: ${this.username}\nPassword: ${this.password}\n\nSilakan login di: https://ceeklin.id/login\n\nTerima kasih!`;
            return 'https://wa.me/62' + (p.startsWith('0') ? p.substring(1) : p) + '?text=' + encodeURIComponent(msg);
        }
    }" class="max-w-6xl mx-auto">

        {{-- Unified Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-6">
            <div x-show="view === 'list'">
                <div class="flex items-center gap-4">
                    <h2 class="font-headline font-black text-2xl text-primary uppercase tracking-tighter">Database Distributor</h2>
                    <x-ui.badge-count :count="count($distributors)" type="secondary" class="scale-110" />
                </div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-3 leading-tight">Manajemen Mitra & Wilayah Operasional</p>
            </div>
            <div x-show="view === 'create'" x-cloak style="display: none;">
                <h2 class="font-headline font-black text-2xl text-primary uppercase tracking-tighter">Registrasi Mitra</h2>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-3 leading-tight">Pembuatan Akun Distributor Resmi CeeKlin</p>
            </div>

            <div class="w-full md:w-auto flex flex-col sm:flex-row gap-3">
                {{-- Filter Wilayah --}}
                <div x-show="view === 'list'" class="relative w-full sm:w-48">
                    <select x-model="filterRegion" aria-label="Filter wilayah" 
                        class="appearance-none w-full bg-white border-[3px] border-gray-900 px-4 py-3 text-xs font-bold uppercase tracking-widest text-primary focus:outline-none focus:border-secondary cursor-pointer pr-10 shadow-[4px_4px_0_rgba(0,0,0,0.05)]">
                        <option>Semua Wilayah</option>
                        @foreach($provinces as $province)
                            <option>{{ $province->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                <button x-show="view === 'list'" @click="view = 'create'; successMode = false; resetForm()"
                    class="w-full sm:w-auto bg-primary text-white px-6 py-3 font-headline font-black text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-1 active:shadow-none transition-all flex items-center justify-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                    TAMBAH AKUN
                </button>
                <button x-show="view === 'create'" x-cloak style="display: none;" @click="view = 'list'; resetForm()"
                    class="w-full sm:w-auto bg-white text-gray-900 px-6 py-3 font-headline font-black text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-gray-100 active:translate-y-1 active:shadow-none transition-all flex items-center justify-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    BATAL / KEMBALI
                </button>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- VIEW: LIST (FULL WIDTH TABLE)              --}}
        {{-- ========================================== --}}
        <div x-show="view === 'list'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
             class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)] overflow-hidden">
            
            <div class="hidden md:grid grid-cols-12 gap-4 px-8 py-4 bg-gray-900 text-white">
                <div class="col-span-4 text-[10px] font-headline font-bold uppercase tracking-widest">Distributor</div>
                <div class="col-span-3 text-[10px] font-headline font-bold uppercase tracking-widest">Wilayah</div>
                <div class="col-span-2 text-center text-[10px] font-headline font-bold uppercase tracking-widest">Stok (PCS)</div>
                <div class="col-span-1 text-center text-[10px] font-headline font-bold uppercase tracking-widest">Mitra</div>
                <div class="col-span-2 text-right text-[10px] font-headline font-bold uppercase tracking-widest">Aksi</div>
            </div>

            <div class="divide-y-2 divide-neutral-border" x-data="{ expandedId: null }">
                {{-- BACKEND-TODO: Pastikan variabel $distributors membawa resellers_count dan province_name --}}
                @forelse($distributors as $distributor)
                <div x-show="filterRegion === 'Semua Wilayah' || '{{ $distributor->province_name }}' === filterRegion" class="contents">
                    {{-- Row Utama --}}
                    <div class="animate-in flex flex-col md:grid md:grid-cols-12 gap-4 px-8 py-6 md:py-4 items-start md:items-center hover:bg-neutral-light transition-colors group cursor-pointer"
                         @click="view = 'detail'; selectedDistributor = {
                            id: {{ $distributor->id }},
                            name: '{{ $distributor->name }}',
                            province: '{{ $distributor->province_name }}',
                            address: '{{ str_replace(["\r", "\n"], " ", $distributor->address ?? 'Alamat belum diatur') }}',
                            phone: '{{ $distributor->phone }}',
                            stock: {{ $distributor->current_stock ?? 0 }},
                            resellerCount: {{ $distributor->resellers_count }},
                            username: '{{ $distributor->user->username ?? 'N/A' }}'
                        }">
                        <div class="md:col-span-4 w-full">
                            <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Distributor</p>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-primary/10 flex items-center justify-center border-2 border-primary/20 group-hover:border-primary transition-colors">
                                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900 text-sm uppercase group-hover:text-primary transition-colors leading-tight">{{ $distributor->name }}</div>
                                    <p class="text-[9px] font-bold text-slate-400 mt-0.5 italic tracking-widest">{{ $distributor->phone }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="md:col-span-3 w-full text-left md:text-left">
                            <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Wilayah</p>
                            <div class="text-xs font-black text-gray-700 uppercase tracking-widest flex items-center justify-start gap-2">
                                <svg class="w-3 h-3 text-secondary" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                                {{ is_numeric($distributor->province_name) ? 'WILAYAH #' . $distributor->province_name : $distributor->province_name }}
                            </div>
                        </div>
                        <div class="md:col-span-2 w-full flex justify-between items-center md:block md:text-center">
                            <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Stok (PCS)</p>
                            <div class="font-headline font-black text-xl text-primary tracking-tighter">{{ number_format($distributor->current_stock ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="md:col-span-1 w-full flex justify-between items-center md:block md:text-center">
                            <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Mitra</p>
                            <span class="font-headline font-black text-xl text-primary tracking-tighter" x-text="'{{ $distributor->resellers_count }}'"></span>
                        </div>
                        <div class="md:col-span-2 w-full flex justify-between items-center md:flex md:items-center md:justify-end gap-2">
                            <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Manajemen</p>
                            <div class="flex items-center gap-2">
                                <a href="https://wa.me/{{ preg_replace('/\D/', '', $distributor->phone) }}" 
                                   target="_blank"
                                   @click.stop
                                   class="w-8 h-8 flex items-center justify-center bg-[#25D366] text-white border-2 border-gray-900 shadow-[2px_2px_0_var(--color-gray-900)] hover:bg-[#1DA851] transition-all">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.347-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                </a>
                                <button @click.stop="view = 'detail'; selectedDistributor = {
                                    id: {{ $distributor->id }},
                                    name: '{{ $distributor->name }}',
                                    province: '{{ $distributor->province_name }}',
                                    phone: '{{ $distributor->phone }}',
                                    stock: {{ $distributor->current_stock ?? 0 }},
                                    resellerCount: {{ $distributor->resellers_count }},
                                    username: '{{ $distributor->user->username ?? 'N/A' }}'
                                }"
                                    class="bg-primary text-white px-3 py-1.5 text-xs font-black uppercase tracking-widest border-2 border-gray-900 shadow-[3px_3px_0_var(--color-gray-900)] hover:bg-primary-hover transition-all">
                                    DETAIL
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
                @empty
                <div class="px-8 py-20 text-center bg-neutral-light/50">
                    <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6 border-2 border-dashed border-primary/30">
                        <svg class="w-10 h-10 text-primary opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <h3 class="font-headline font-black text-xl text-primary uppercase tracking-tight mb-2">Belum Ada Distributor</h3>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest max-w-xs mx-auto leading-relaxed">
                        Belum ada distributor terdaftar di sistem.
                    </p>
                </div>
                @endforelse

                <!-- Empty State for Filter/Search -->
                @if(count($distributors) > 0)
                <div x-show="![
                        @foreach($distributors as $distributor)
                            '{{ addslashes($distributor->province_name) }}',
                        @endforeach
                    ].some(p => filterRegion === 'Semua Wilayah' || p === filterRegion)"
                     x-cloak class="px-8 py-20 text-center bg-neutral-light/50 animate-in">
                    <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6 border-2 border-dashed border-primary/30">
                        <svg class="w-10 h-10 text-primary opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-headline font-black text-xl text-primary uppercase tracking-tight mb-2">Wilayah Kosong!</h3>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest max-w-xs mx-auto leading-relaxed">
                        Tidak ada distributor yang ditemukan di wilayah ini.
                    </p>
                </div>
                @endif
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- VIEW: CREATE (REGISTER STYLE CARD)          --}}
        {{-- ========================================== --}}
        <div x-show="view === 'create'" x-cloak style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
             class="max-w-3xl mx-auto">
            
            {{-- Stepper Imitation (Style Register) --}}
            <div class="flex items-center justify-center gap-4 mb-8">
                <div class="flex flex-col items-center gap-1.5">
                    <div class="w-10 h-10 flex items-center justify-center font-headline font-black text-base border-[3px] border-gray-900 bg-primary text-white shadow-[4px_4px_0_var(--color-secondary)]">
                        <span x-show="!successMode">1</span>
                        <svg x-show="successMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="font-headline font-bold text-[9px] uppercase tracking-[0.2em] text-gray-900">Registrasi</span>
                </div>
                <div class="w-12 h-[3px] bg-neutral-border border-y border-gray-900" :class="successMode ? 'bg-primary' : ''"></div>
                <div class="flex flex-col items-center gap-1.5">
                    <div class="w-10 h-10 flex items-center justify-center font-headline font-black text-base border-[3px] border-gray-900 transition-colors" :class="successMode ? 'bg-primary text-white shadow-[4px_4px_0_var(--color-secondary)]' : 'bg-neutral-light text-slate-400'">
                        2
                    </div>
                    <span class="font-headline font-bold text-[9px] uppercase tracking-[0.2em]" :class="successMode ? 'text-gray-900' : 'text-slate-400'">Selesai</span>
                </div>
            </div>

            <div class="bg-white border-4 border-gray-900 shadow-[12px_12px_0_var(--color-primary)]">
                <div class="bg-primary p-4 border-b-4 border-gray-900 flex justify-between items-center">
                    <h2 class="font-headline font-black text-lg text-white uppercase tracking-tighter"
                        x-text="successMode ? 'Pendaftaran Berhasil' : 'Pembuatan Akun Distributor'">
                    </h2>
                    <span class="bg-secondary text-gray-900 font-headline font-black px-2 py-0.5 text-xs border-2 border-gray-900"
                        x-text="successMode ? '2/2' : '1/2'"></span>
                </div>

                <div class="p-6 lg:p-10">
                    <div x-show="!successMode">
                        {{-- BACKEND-TODO: Route admin.distributors.store harus menangani input: company_name, region, phone, username, password --}}
                        <form action="{{ route('admin.distributors.store') }}" method="POST" class="flex flex-col gap-6" novalidate @submit.prevent="submitForm">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1">Data Identitas</p>
                                    <div class="h-1 w-12 bg-secondary"></div>
                                </div>

                                <div class="md:col-span-2 flex flex-col gap-1.5">
                                    <label class="text-[10px] font-bold text-gray-900 uppercase tracking-widest" for="nama-entitas">Nama Perusahaan Distributor</label>
                                    <input id="nama-entitas" name="company_name" type="text" placeholder="CONTOH: PT. DISTRIBUSI JAYA" x-model="companyName" required @input="delete errors.company_name"
                                        class="bg-neutral-light border-[3px] border-primary px-4 py-3 font-body text-sm font-bold text-primary focus:outline-none focus:border-secondary placeholder:text-slate-300 transition-colors uppercase">
                                    <x-ui.error name="company_name" />
                                </div>

                                <div class="flex flex-col gap-1.5">
                                    <label class="text-[10px] font-bold text-gray-900 uppercase tracking-widest" for="wilayah">Wilayah Operasional</label>
                                    <div class="relative">
                                        <select id="wilayah" name="region" required @change="delete errors.region"
                                            class="appearance-none w-full bg-neutral-light border-[3px] border-primary px-4 py-3 font-body text-sm font-bold text-primary focus:outline-none focus:border-secondary transition-colors cursor-pointer pr-10">
                                            <option value="">PILIH PROVINSI</option>
                                            @foreach($provinces as $province)
                                                <option value="{{ $province->code }}">{{ $province->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-primary">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" /></svg>
                                        </div>
                                    </div>
                                    <x-ui.error name="region" />
                                </div>

                                <div class="flex flex-col gap-1.5">
                                    <label class="text-[10px] font-bold text-gray-900 uppercase tracking-widest" for="kontak">No. WhatsApp PIC</label>
                                    <input id="kontak" name="phone" type="text" placeholder="08xxxxxxxxxx" x-model="phone" required @input="delete errors.phone"
                                        class="bg-neutral-light border-[3px] border-primary px-4 py-3 font-body text-sm font-bold text-primary focus:outline-none focus:border-secondary transition-colors">
                                    <x-ui.error name="phone" />
                                </div>

                                <div class="flex flex-col gap-1.5">
                                    <label class="text-[10px] font-bold text-gray-900 uppercase tracking-widest" for="stok-awal">Stok Awal (PCS)</label>
                                    <input id="stok-awal" name="initial_stock" type="number" x-model="initialStock" required
                                        class="bg-neutral-light border-[3px] border-secondary px-4 py-3 font-headline font-black text-xl text-primary focus:outline-none focus:border-primary transition-colors">
                                    <p class="text-[8px] font-bold text-slate-400 uppercase mt-1">Gunakan untuk migrasi stok manual ke sistem</p>
                                </div>

                                <div class="md:col-span-2 mt-2">
                                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1">Keamanan Akses</p>
                                    <div class="h-1 w-12 bg-secondary"></div>
                                </div>

                                <x-ui.input id="dist-username" name="username" label="USERNAME" placeholder="Gunakan huruf kecil" x-model="username" required @input="delete errors.username" />
                                <x-ui.input id="dist-password" name="password" type="password" label="KATA SANDI" placeholder="Min. 8 karakter" x-model="password" required @input="delete errors.password" />
                            </div>

                            <div class="relative">
                                <x-ui.error name="general" />
                            </div>

                            <button type="submit"
                                class="w-full bg-primary text-white py-4 font-headline font-black text-sm uppercase tracking-widest border-[3px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)] hover:bg-transparent hover:text-primary transition-all duration-300 mt-4">
                                DAFTARKAN DISTRIBUTOR
                            </button>
                        </form>
                    </div>

                    {{-- SUCCESS SCREEN --}}
                    <div x-show="successMode" style="display:none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                         class="py-4 flex flex-col items-center text-center">

                        <div class="w-20 h-20 bg-primary flex items-center justify-center border-4 border-gray-900 shadow-[6px_6px_0_var(--color-secondary)] mb-6">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>

                        <h4 class="font-headline font-black text-2xl text-primary uppercase tracking-tighter mb-1">Berhasil Terdaftar!</h4>
                        <p class="font-bold text-sm text-gray-900 uppercase" x-text="companyName"></p>
                        
                        <div class="w-full bg-neutral-light border-2 border-dashed border-gray-400 p-6 my-8">
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Akses Login</p>
                            <p class="font-headline font-black text-xl text-primary tracking-tight uppercase" x-text="username"></p>
                        </div>

                        <div x-show="!waSent" class="w-full space-y-4" x-transition>
                            <a :href="getWaLink()" target="_blank" @click="waSent = true"
                               class="w-full flex items-center justify-center gap-3 bg-[#25D366] text-white px-6 py-4 font-headline font-black text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)] hover:bg-[#1DA851] transition-all">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.347-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                KIRIM AKSES VIA WHATSAPP
                            </a>
                        </div>

                        <div x-show="waSent" style="display:none;" x-transition
                             class="w-full flex flex-col items-center gap-4">
                            <div class="w-full flex items-center justify-center gap-3 bg-green-50 border-4 border-green-600 p-4">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                                <span class="font-headline font-black text-sm text-green-700 uppercase tracking-widest">Akses Terkirim ✓</span>
                            </div>
                        </div>

                        <button @click="view = 'list'; resetForm()" class="mt-8 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-primary transition-colors underline underline-offset-4">
                            Selesai & Kembali ke Database
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- VIEW: DETAIL (PROFESSIONAL SUB-PAGE)       --}}
        {{-- ========================================== --}}
        <div x-show="view === 'detail'" x-cloak style="display: none;" x-transition
             class="max-w-[1400px] mx-auto">
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-6">
                <div>
                    <h2 class="font-headline font-black text-2xl text-primary uppercase italic leading-none" x-text="selectedDistributor?.name"></h2>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-3 leading-tight">Manajemen Detail & Daftar Reseller Terdaftar</p>
                </div>

                <div class="w-full md:w-auto flex flex-col sm:flex-row gap-3">
                    <button @click="view = 'list'; selectedDistributor = null" 
                        class="w-full sm:w-auto bg-white text-gray-900 px-6 py-3 text-xs font-headline font-black uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-gray-100 active:translate-y-1 active:shadow-none transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        KEMBALI KE DAFTAR
                    </button>
                    <a :href="'https://wa.me/' + selectedDistributor?.phone" target="_blank"
                        class="w-full sm:w-auto bg-[#25D366] text-white px-6 py-3 text-xs font-headline font-black uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-[#1DA851] transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.347-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        HUBUNGI DISTRIBUTOR
                    </a>
                </div>
            </div>

            <div class="space-y-8">
                {{-- Top Info Section (Address & Stats) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-stretch">
                    {{-- Alamat --}}
                    <div class="bg-white border-[4px] border-gray-900 p-7 shadow-[10px_10px_0_var(--color-primary)] flex flex-col justify-between">
                        <div>
                            <h4 class="font-headline font-black text-[10px] uppercase tracking-[0.2em] text-secondary mb-5 border-b-2 border-neutral-border pb-2 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                ALAMAT OPERASIONAL
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Wilayah Penugasan</p>
                                    <p class="font-headline font-black text-xl text-primary uppercase leading-tight" x-text="!isNaN(selectedDistributor?.province) ? 'ZONA #' + selectedDistributor?.province : selectedDistributor?.province"></p>
                                </div>
                                <div>
                                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Titik Lokasi Lengkap</p>
                                    <p class="font-bold text-gray-900 text-[11px] leading-relaxed uppercase tracking-tight" x-text="selectedDistributor?.address"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Statistik --}}
                    <div class="bg-gray-900 border-[4px] border-gray-900 p-7 shadow-[10px_10px_0_var(--color-secondary)] flex flex-col justify-between">
                        <h4 class="font-headline font-black text-[10px] uppercase tracking-[0.2em] text-white/50 mb-5 border-b border-white/10 pb-2 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            RINGKASAN PERFORMA
                        </h4>
                        <div class="grid grid-cols-2 gap-4 h-full items-center">
                            <div class="p-4 bg-white/5 border border-white/10 relative overflow-hidden group">
                                <div class="relative z-10">
                                    <p class="text-[8px] font-bold text-white/30 uppercase tracking-[0.2em] mb-2">Total Stok</p>
                                    <div class="flex items-baseline gap-1">
                                        <p class="font-headline font-black text-3xl text-white tracking-tighter" x-text="selectedDistributor?.stock.toLocaleString('id-ID')"></p>
                                        <span class="text-[8px] font-black text-white/40 uppercase">PCS</span>
                                    </div>
                                </div>
                                <svg class="absolute -right-2 -bottom-2 w-12 h-12 text-white/5 transform rotate-12 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                            <div class="p-4 bg-white/5 border border-white/10 relative overflow-hidden group">
                                <div class="relative z-10">
                                    <p class="text-[8px] font-bold text-white/30 uppercase tracking-[0.2em] mb-2">Total Reseller</p>
                                    <div class="flex items-baseline gap-1">
                                        <p class="font-headline font-black text-3xl text-secondary tracking-tighter" x-text="selectedDistributor?.resellerCount"></p>
                                        <span class="text-[8px] font-black text-secondary/40 uppercase">RESELLER</span>
                                    </div>
                                </div>
                                <svg class="absolute -right-2 -bottom-2 w-12 h-12 text-white/5 transform rotate-12 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Reseller List (Full Width) --}}
                <div class="bg-white border-[4px] border-gray-900 shadow-[12px_12px_0_var(--color-gray-900)] overflow-hidden transition-all duration-500">
                    <div class="p-4 bg-neutral-light border-b-2 border-gray-900 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-primary text-white flex items-center justify-center font-black text-xs border-2 border-gray-900">R</div>
                            <h4 class="font-headline font-black text-xs uppercase tracking-widest text-primary">Manajemen Reseller Terdaftar</h4>
                        </div>
                        <span class="bg-gray-900 text-white px-3 py-1 text-xs font-black uppercase tracking-widest" x-text="selectedDistributor?.resellerCount + ' Reseller Aktif'"></span>
                    </div>
                    
                    <div :class="tableExpanded ? 'max-h-none' : 'max-h-[450px]'" class="overflow-hidden relative transition-all duration-500">
                        {{-- Desktop Table --}}
                        <div class="hidden md:block overflow-x-auto overflow-y-auto max-h-[600px] custom-scrollbar">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-neutral-border/20 sticky top-0 z-10">
                                    <tr class="bg-neutral-light">
                                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 border-b-2 border-neutral-border">Reseller</th>
                                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 border-b-2 border-neutral-border">Kontak HP</th>
                                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 border-b-2 border-neutral-border text-center">Total Pesanan</th>
                                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 border-b-2 border-neutral-border">Bergabung Sejak</th>
                                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 border-b-2 border-neutral-border text-right">Status Akun</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y-2 divide-neutral-border bg-white">
                                    <template x-for="i in selectedDistributor?.resellerCount" :key="i">
                                        <tr class="hover:bg-neutral-light transition-colors group">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-6 h-6 rounded-full bg-primary flex items-center justify-center text-[8px] font-black text-white border border-gray-900" x-text="i"></div>
                                                    <div>
                                                        <p class="font-black text-xs text-gray-900 uppercase" x-text="'Reseller #' + i"></p>
                                                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest" x-text="selectedDistributor?.province"></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 font-bold text-xs text-primary">08xxxxxxx</td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="font-headline font-black text-sm text-gray-900" x-text="(i * 12) + 'x'"></span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <p class="text-[10px] font-bold text-gray-700 uppercase tracking-tight">12 Mei 2024</p>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <span class="px-2 py-0.5 bg-green-100 text-green-700 border border-green-600 text-[8px] font-black uppercase tracking-widest">Aktif</span>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-if="selectedDistributor?.resellerCount === 0">
                                        <tr>
                                            <td colspan="5" class="px-8 py-20 text-center bg-neutral-light/50">
                                                <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6 border-2 border-dashed border-primary/30">
                                                    <svg class="w-10 h-10 text-primary opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                                </div>
                                                <h3 class="font-headline font-black text-xl text-primary uppercase tracking-tight mb-2">Belum Ada Mitra</h3>
                                                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest max-w-xs mx-auto leading-relaxed">
                                                    Distributor ini belum memiliki reseller yang terdaftar.
                                                </p>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        {{-- Mobile Cards --}}
                        <div class="md:hidden divide-y-2 divide-neutral-border bg-white overflow-y-auto max-h-[600px] custom-scrollbar">
                            <template x-for="i in selectedDistributor?.resellerCount" :key="i">
                                <div class="p-6 space-y-4 hover:bg-neutral-light transition-colors">
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-primary text-white flex items-center justify-center font-black text-xs border border-gray-900 shadow-[2px_2px_0_var(--color-gray-900)]" x-text="i"></div>
                                            <div>
                                                <p class="font-black text-sm text-gray-900 uppercase leading-none" x-text="'Reseller #' + i"></p>
                                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1" x-text="selectedDistributor?.province"></p>
                                            </div>
                                        </div>
                                        <span class="px-2 py-0.5 bg-green-100 text-green-700 border border-green-600 text-[7px] font-black uppercase tracking-widest">Aktif</span>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 pt-2">
                                        <div class="space-y-1">
                                            <p class="text-[7px] font-bold text-slate-400 uppercase tracking-widest">Kontak HP</p>
                                            <p class="font-bold text-xs text-primary">08xxxxxxx</p>
                                        </div>
                                        <div class="space-y-1">
                                            <p class="text-[7px] font-bold text-slate-400 uppercase tracking-widest text-center">Total Pesanan</p>
                                            <p class="font-headline font-black text-sm text-gray-900 text-center" x-text="(i * 12) + 'x'"></p>
                                        </div>
                                        <div class="col-span-2 pt-2 border-t border-neutral-border flex justify-between items-center">
                                            <p class="text-[7px] font-bold text-slate-400 uppercase tracking-widest">Bergabung Sejak</p>
                                            <p class="text-[9px] font-black text-gray-700 uppercase tracking-tight">12 Mei 2024</p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <template x-if="selectedDistributor?.resellerCount === 0">
                                <div class="px-8 py-20 text-center bg-neutral-light/50">
                                    <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6 border-2 border-dashed border-primary/30">
                                        <svg class="w-10 h-10 text-primary opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    </div>
                                    <h3 class="font-headline font-black text-xl text-primary uppercase tracking-tight mb-2">Belum Ada Mitra</h3>
                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest max-w-xs mx-auto leading-relaxed">
                                        Distributor ini belum memiliki reseller yang terdaftar.
                                    </p>
                                </div>
                            </template>
                        </div>

                        {{-- Gradient Overlay for "Show More" --}}
                        <div x-show="!tableExpanded && selectedDistributor?.resellerCount > 5" 
                             class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-white to-transparent pointer-events-none"></div>
                    </div>

                    {{-- Show More/Less Button --}}
                    <div x-show="selectedDistributor?.resellerCount > 5" class="p-4 border-t-2 border-neutral-border bg-neutral-light/50 flex justify-center">
                        <button @click="tableExpanded = !tableExpanded" 
                            class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-primary hover:text-secondary transition-colors group">
                            <span x-text="tableExpanded ? 'Sembunyikan Daftar' : 'Tampilkan Lebih Banyak Reseller'"></span>
                            <svg class="w-4 h-4 transition-transform duration-300" :class="tableExpanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Danger Zone (Inline Confirmation) --}}
            <div class="mt-12 bg-white border-[4px] border-red-600 p-8 shadow-[12px_12px_0_rgba(220,38,38,0.1)] overflow-hidden">
                {{-- Initial State --}}
                <div x-show="confirmMode === 'none'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                        <div class="max-w-xl text-center md:text-left">
                            <h4 class="font-headline font-black text-lg uppercase tracking-widest text-red-600">Zona Bahaya & Manajemen Akun</h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2 leading-relaxed">
                                Gunakan fitur ini untuk menangguhkan akses distributor atau menghapus seluruh data secara permanen. 
                                Pastikan Anda telah melakukan verifikasi stok sebelum melakukan penghapusan.
                            </p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                            <button @click="confirmMode = 'deactivate'; confirmPassword = ''" 
                                class="px-8 py-4 border-[3px] border-red-600 text-red-600 font-headline font-black text-[11px] uppercase tracking-widest hover:bg-red-50 transition-all flex items-center justify-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                NONAKTIFKAN AKSES
                            </button>
                            <button @click="confirmMode = 'delete'; confirmPassword = ''" 
                                class="px-8 py-4 bg-red-600 text-white font-headline font-black text-[11px] uppercase tracking-widest hover:bg-red-700 transition-all shadow-[6px_6px_0_var(--color-gray-900)] active:translate-y-1 active:shadow-none flex items-center justify-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                HAPUS PERMANEN
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Deactivate Confirmation Form --}}
                <div x-show="confirmMode === 'deactivate'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-12" style="display: none;">
                    <div class="flex flex-col lg:flex-row gap-8 items-center">
                        <div class="flex-1 space-y-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-primary/10 border-2 border-primary flex items-center justify-center text-primary font-black italic">!</div>
                                <div>
                                    <h4 class="font-headline font-black text-xl text-gray-900 uppercase italic">Konfirmasi Penangguhan</h4>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Akun <span class="text-primary italic" x-text="selectedDistributor?.name"></span> akan ditangguhkan</p>
                                </div>
                            </div>
                            <p class="text-[10px] font-bold text-gray-500 uppercase leading-relaxed max-w-2xl">
                                Penangguhan berarti distributor tidak dapat login, namun seluruh data stok dan mitra tetap aman dalam database. 
                                Silakan masukkan password Anda untuk memverifikasi tindakan ini.
                            </p>
                        </div>
                        <div class="w-full lg:w-96 space-y-4">
                            <input type="password" x-model="confirmPassword" placeholder="MASUKKAN PASSWORD ADMIN..." 
                                class="w-full bg-slate-50 border-3 border-gray-900 px-4 py-3 font-bold text-xs focus:bg-white outline-none focus:ring-4 focus:ring-primary/20 transition-all uppercase tracking-widest">
                            <div class="grid grid-cols-2 gap-3">
                                <button @click="confirmMode = 'none'" class="py-3 border-3 border-gray-900 font-headline font-black text-xs uppercase tracking-widest hover:bg-slate-100">BATAL</button>
                                <button class="py-3 bg-primary text-white font-headline font-black text-xs uppercase tracking-widest border-3 border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] active:translate-y-1 active:shadow-none">KONFIRMASI</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Delete Confirmation Form --}}
                <div x-show="confirmMode === 'delete'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-x-12" style="display: none;">
                    <div class="flex flex-col lg:flex-row gap-8 items-center">
                        <div class="flex-1 space-y-4">
                            <div class="flex items-center gap-4 text-red-600">
                                <div class="w-12 h-12 bg-red-600 text-white flex items-center justify-center font-black italic shadow-[4px_4px_0_rgba(0,0,0,0.1)] text-xl">X</div>
                                <div>
                                    <h4 class="font-headline font-black text-xl uppercase italic">Hapus Permanen?</h4>
                                    <p class="text-[9px] font-bold text-red-400 uppercase tracking-widest">Seluruh data <span class="underline" x-text="selectedDistributor?.name"></span> akan dilenyapkan</p>
                                </div>
                            </div>
                            <p class="text-[10px] font-bold text-red-800 uppercase leading-relaxed max-w-2xl bg-red-50 p-4 border-l-4 border-red-600">
                                Peringatan: Tindakan ini akan menghapus stok, riwayat, dan memutus semua mitra reseller secara permanen. 
                                Data yang sudah dihapus tidak dapat dipulihkan kembali.
                            </p>
                        </div>
                        <div class="w-full lg:w-96 space-y-4">
                            <input type="password" x-model="confirmPassword" placeholder="PASSWORD KONFIRMASI..." 
                                class="w-full bg-red-50 border-3 border-red-600 px-4 py-3 font-bold text-xs focus:bg-white outline-none focus:ring-4 focus:ring-red-100 transition-all uppercase tracking-widest text-red-600 placeholder:text-red-200">
                            <div class="grid grid-cols-2 gap-3">
                                <button @click="confirmMode = 'none'" class="py-3 border-3 border-gray-900 font-headline font-black text-xs uppercase tracking-widest hover:bg-slate-100">BATAL</button>
                                <button class="py-3 bg-red-600 text-white font-headline font-black text-xs uppercase tracking-widest border-3 border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] active:translate-y-1 active:shadow-none">HAPUS DATA</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
