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
        initialStock: 0,
        successMode: false,
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

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div x-show="view === 'list'">
                <h2 class="font-headline font-black text-2xl text-primary uppercase tracking-tighter">Database Distributor</h2>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Manajemen Mitra & Wilayah Operasional</p>
            </div>
            <div x-show="view === 'create'" x-cloak style="display: none;">
                <h2 class="font-headline font-black text-2xl text-primary uppercase tracking-tighter">Registrasi Mitra</h2>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Pembuatan Akun Distributor Resmi CeeKlin</p>
            </div>

            <div class="w-full sm:w-auto flex flex-wrap gap-3">
                {{-- Filter Wilayah (Hanya tampil di List) --}}
                <div x-show="view === 'list'" class="relative w-full sm:w-auto">
                    <select x-model="filterRegion" aria-label="Filter wilayah" class="appearance-none w-full sm:w-auto bg-white border-[3px] border-gray-900 px-4 py-3 text-[10px] font-bold uppercase tracking-widest text-primary focus:outline-none focus:border-secondary cursor-pointer pr-10">
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
                    class="w-full sm:w-auto bg-primary text-white px-6 py-3 font-headline font-black text-[10px] uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-1 active:shadow-none transition-all flex items-center justify-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                    TAMBAH AKUN
                </button>
                <button x-show="view === 'create'" x-cloak style="display: none;" @click="view = 'list'; resetForm()"
                    class="w-full sm:w-auto bg-white text-gray-900 px-6 py-3 font-headline font-black text-[10px] uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-gray-100 active:translate-y-1 active:shadow-none transition-all flex items-center justify-center gap-3">
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
            
            <div class="bg-gray-900 px-6 py-4 flex items-center justify-between border-b-2 border-gray-900">
                <span class="font-headline font-bold text-white text-[10px] uppercase tracking-widest">Master Data Distributor</span>
                <span class="text-[10px] font-bold text-secondary uppercase tracking-widest">{{ count($distributors) }} Mitra Terdaftar</span>
            </div>

            {{-- Table Header (Desktop) --}}
            <div class="hidden md:grid grid-cols-12 gap-4 px-8 py-4 bg-neutral-light border-b-2 border-neutral-border">
                <div class="col-span-5 text-[10px] font-headline font-bold text-primary uppercase tracking-widest">Informasi Perusahaan</div>
                <div class="col-span-3 text-[10px] font-headline font-bold text-primary uppercase tracking-widest">Wilayah</div>
                <div class="col-span-2 text-center text-[10px] font-headline font-bold text-primary uppercase tracking-widest">Stok Saat Ini</div>
                <div class="col-span-1 text-center text-[10px] font-headline font-bold text-primary uppercase tracking-widest">Reseller</div>
                <div class="col-span-1 text-right text-[10px] font-headline font-bold text-primary uppercase tracking-widest">Status</div>
            </div>

            <div class="divide-y-2 divide-neutral-border" x-data="{ expandedId: null }">
                {{-- BACKEND-TODO: Pastikan variabel $distributors membawa resellers_count dan province_name --}}
                @forelse($distributors as $distributor)
                <div x-show="filterRegion === 'Semua Wilayah' || '{{ $distributor->province_name }}' === filterRegion" class="contents">
                    {{-- Row Utama --}}
                    <div class="animate-in flex flex-col md:grid md:grid-cols-12 gap-4 px-8 py-6 md:py-4 items-start md:items-center hover:bg-neutral-light transition-colors group cursor-pointer"
                         @click="expandedId === {{ $distributor->id }} ? expandedId = null : expandedId = {{ $distributor->id }}">
                        <div class="md:col-span-5 w-full">
                            <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Perusahaan</p>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-primary/10 flex items-center justify-center border-2 border-primary/20 group-hover:border-primary transition-colors">
                                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900 text-sm uppercase group-hover:text-primary transition-colors leading-tight">{{ $distributor->name }}</div>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <p class="text-[9px] font-bold text-slate-500">{{ $distributor->phone }}</p>
                                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $distributor->phone) }}" 
                                           target="_blank"
                                           @click.stop
                                           class="p-1 bg-[#25D366]/10 text-[#25D366] hover:bg-[#25D366] hover:text-white transition-colors border border-[#25D366]/20">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.347-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="md:col-span-3 w-full">
                            <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Wilayah</p>
                            <div class="text-[10px] font-black text-gray-700 uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-3 h-3 text-secondary" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                                {{ $distributor->province_name }}
                            </div>
                        </div>
                        <div class="md:col-span-2 w-full flex justify-between items-center md:block md:text-center">
                            <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Stok</p>
                            <div class="font-headline font-black text-xl text-primary tracking-tighter">{{ number_format($distributor->current_stock ?? 0, 0, ',', '.') }}</div>
                        </div>
                        <div class="md:col-span-1 w-full flex justify-between items-center md:block md:text-center">
                            <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Reseller</p>
                            <button class="flex flex-col items-center mx-auto group/btn">
                                <span class="font-headline font-black text-xl text-primary tracking-tighter group-hover/btn:text-secondary" x-text="'{{ $distributor->resellers_count }}'"></span>
                                <span class="text-[7px] font-black uppercase text-slate-400 group-hover/btn:text-secondary">Lihat List</span>
                            </button>
                        </div>
                        <div class="md:col-span-1 w-full flex justify-between items-center md:block md:text-right">
                            <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Status</p>
                            <span class="px-2 py-0.5 border-2 {{ $distributor->status === 'active' ? 'border-secondary text-secondary' : 'border-slate-300 text-slate-400' }} text-[9px] font-bold uppercase tracking-widest">
                                {{ $distributor->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>

                    {{-- Dropdown: Daftar Reseller --}}
                    <div x-show="expandedId === {{ $distributor->id }}" 
                         x-collapse x-cloak
                         class="bg-neutral-light border-b-2 border-gray-900">
                        <div class="px-8 py-6">
                            <div class="bg-white border-2 border-gray-900 p-4 shadow-[4px_4px_0_var(--color-primary)]">
                                <div class="flex items-center justify-between mb-4 border-b-2 border-neutral-border pb-2">
                                    <h6 class="text-[10px] font-headline font-black text-primary uppercase tracking-widest">Daftar Reseller Terdaftar</h6>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase">Total: {{ $distributor->resellers_count }} Mitra</span>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    {{-- BACKEND-TODO: Loop list reseller asli --}}
                                    <template x-for="i in {{ $distributor->resellers_count }}" :key="i">
                                        <div class="flex items-center gap-3 p-3 bg-neutral-light border border-neutral-border hover:border-primary transition-colors">
                                            <div class="w-8 h-8 rounded-full bg-white border border-gray-900 flex items-center justify-center font-headline font-black text-xs text-primary">R</div>
                                            <div>
                                                <p class="text-[11px] font-black text-gray-900 uppercase leading-none">Reseller #<span x-text="i"></span></p>
                                                <p class="text-[9px] font-bold text-slate-400 uppercase mt-1">Status: Aktif</p>
                                            </div>
                                        </div>
                                    </template>

                                    @if($distributor->resellers_count == 0)
                                        <div class="col-span-full py-4 text-center">
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">Distributor ini belum memiliki reseller</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-8 py-16 text-center">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Belum ada distributor terdaftar</p>
                </div>
                @endforelse

                <!-- Empty State for Filter/Search -->
                <div x-show="[...$el.parentElement.children].filter(el => el.hasAttribute('x-show') && el.style.display !== 'none').length === 0"
                     x-cloak class="px-8 py-16 text-center animate-in">
                    <svg class="w-12 h-12 text-slate-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Tidak ada distributor di wilayah ini</p>
                </div>
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
                                <x-ui.input id="dist-password" name="password" label="KATA SANDI" placeholder="Min. 8 karakter" x-model="password" required @input="delete errors.password" />
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
    </div>
</x-layouts.dashboard>
