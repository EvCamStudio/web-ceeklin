<x-layouts.dashboard bgTheme="light">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">MASTER ADMIN</span>
    </x-slot:subtitleSlot>

    <x-slot:topbarTitle>MANAJEMEN DISTRIBUTOR</x-slot:topbarTitle>

    <x-slot:menuSlot>
        @include('dashboard.admin._menu')
    </x-slot:menuSlot>

    {{-- Layout: form tambah (kiri compact) + tabel daftar (kanan lebar) --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">

        {{-- Form Tambah Distributor Baru --}}
        <div class="bg-white border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-secondary)]" x-data="{
            companyName: '',
            phone: '',
            username: '',
            password: '',
            successMode: false,
            waSent: false,
            resetForm() {
                this.companyName = '';
                this.phone = '';
                this.username = '';
                this.password = '';
                this.successMode = false;
                this.waSent = false;
            },
            getWaLink() {
                const p = this.phone.replace(/\D/g, '');
                const msg = `Halo ${this.companyName}, akun distributor CeeKlin Anda telah dibuat.\n\nUsername: ${this.username}\nPassword: ${this.password}\n\nSilakan login di: https://ceeklin.id/login\nUbah password Anda setelah login pertama.\n\nTerima kasih!`;
                return 'https://wa.me/62' + p + '?text=' + encodeURIComponent(msg);
            }
        }">
            <div class="bg-secondary px-6 py-3">
                <span class="font-headline font-black text-white text-base uppercase tracking-tight">Tambah Distributor</span>
            </div>
            <div class="p-6">
                {{-- BACKEND-TODO: action ke DistributorController@store --}}
                <div x-show="!successMode">
                <form action="/dashboard/admin/distributors/store" method="POST"
                      class="flex flex-col gap-5"
                      @submit.prevent="successMode = true">
                    @csrf
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[10px] font-bold text-primary uppercase tracking-widest" for="nama-entitas">Nama Perusahaan</label>
                        <input id="nama-entitas" name="company_name" type="text" placeholder="CV / PT ..." x-model="companyName" required
                            class="bg-neutral-light border-[3px] border-primary px-4 py-2.5 font-body text-sm font-bold text-primary focus:outline-none focus:border-secondary placeholder:text-primary/30 transition-colors">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[10px] font-bold text-primary uppercase tracking-widest" for="wilayah">Wilayah</label>
                        <div class="relative">
                            <select id="wilayah" name="region" aria-label="Pilih Wilayah" required
                                class="appearance-none w-full bg-neutral-light border-[3px] border-primary px-4 py-2.5 font-body text-sm font-bold text-primary focus:outline-none focus:border-secondary transition-colors cursor-pointer">
                                <option value="">Pilih wilayah...</option>
                                <option value="jabar">JAWA BARAT</option>
                                <option value="jateng">JAWA TENGAH</option>
                                <option value="jatim">JAWA TIMUR</option>
                                <option value="jakarta">DKI JAKARTA</option>
                            </select>
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[10px] font-bold text-primary uppercase tracking-widest" for="kontak">No. WA / HP (PIC)</label>
                        <input id="kontak" name="phone" type="text" placeholder="08xxxxxxxxxx" x-model="phone" required
                            class="bg-neutral-light border-[3px] border-primary px-4 py-2.5 font-body text-sm font-bold text-primary focus:outline-none focus:border-secondary placeholder:text-primary/30 transition-colors">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[10px] font-bold text-primary uppercase tracking-widest" for="dist-username">Username Akun</label>
                        <input id="dist-username" name="username" type="text" placeholder="tanpa spasi" x-model="username" required
                            class="bg-neutral-light border-[3px] border-primary px-4 py-2.5 font-body text-sm font-bold text-primary focus:outline-none focus:border-secondary placeholder:text-primary/30 transition-colors">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[10px] font-bold text-primary uppercase tracking-widest" for="dist-password">Kata Sandi Awal</label>
                        <input id="dist-password" name="password" type="text" placeholder="Min. 8 karakter" x-model="password" required
                            class="bg-neutral-light border-[3px] border-primary px-4 py-2.5 font-body text-sm font-bold text-primary focus:outline-none focus:border-secondary placeholder:text-primary/30 transition-colors">
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Tampilkan teks agar bisa dicopy ke WA</p>
                    </div>
                    <button type="submit" aria-label="Buat akun distributor baru"
                        class="w-full bg-primary text-white py-3 font-headline font-bold text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-0.5 active:shadow-none transition-all mt-2">
                        Buat Akun
                    </button>
                </form>
                </div>

                {{-- SUCCESS SCREEN --}}
                <div x-show="successMode" style="display:none;"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="py-6 flex flex-col items-center text-center gap-4">

                    <div class="w-16 h-16 bg-primary flex items-center justify-center border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)]">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>

                    <div>
                        <h4 class="font-headline font-black text-lg text-primary uppercase tracking-tight">Akun Dibuat!</h4>
                        <p class="font-bold text-sm text-gray-900 uppercase mt-0.5" x-text="companyName"></p>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">Username: <span x-text="username" class="text-primary"></span></p>
                    </div>

                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Langkah berikutnya:</p>

                    <div x-show="!waSent" class="w-full" x-transition>
                        <a :href="getWaLink()" target="_blank" @click="waSent = true"
                           class="w-full flex items-center justify-center gap-2 bg-secondary text-white px-4 py-4 font-headline font-black text-sm uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-secondary-dark active:translate-y-0.5 active:shadow-none transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            Kirim Info Akun via WA
                        </a>
                        <p class="text-[9px] text-slate-400 text-center mt-1.5 font-bold uppercase">Pesan sudah disiapkan otomatis</p>
                    </div>

                    <div x-show="waSent" style="display:none;" x-transition
                         class="w-full flex items-center justify-center gap-2 bg-green-50 border-[3px] border-green-600 px-4 py-3">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        <span class="font-bold text-sm text-green-700 uppercase tracking-widest">Info Akun Terkirim ✓</span>
                    </div>

                    <button @click="resetForm()"
                        class="text-[10px] font-bold text-slate-400 uppercase tracking-widest hover:text-primary transition-colors underline underline-offset-2 mt-1">
                        + Tambah Distributor Lain
                    </button>
                </div>
            </div>
        </div>

        {{-- Tabel Daftar Distributor --}}
        <div class="xl:col-span-2 bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">
            <div class="bg-primary px-6 py-3 flex items-center justify-between gap-4">
                <span class="font-headline font-black text-white text-base uppercase tracking-tight leading-tight">Distributor Aktif</span>
                {{-- BACKEND-TODO: hitung dari Distributor::active()->count() --}}
                <span class="text-[10px] font-bold uppercase tracking-widest text-secondary bg-white/10 px-2 py-0.5 border border-white/20 whitespace-nowrap">38 Total</span>
            </div>
            
            {{-- Header (Desktop Only) --}}
            <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-3 bg-neutral-light border-b-2 border-neutral-border">
                <div class="col-span-5 text-[10px] font-headline font-bold text-primary uppercase tracking-widest">Nama Perusahaan</div>
                <div class="col-span-3 text-[10px] font-headline font-bold text-primary uppercase tracking-widest">Wilayah</div>
                <div class="col-span-2 text-[10px] font-headline font-bold text-primary uppercase tracking-widest text-center">Reseller</div>
                <div class="col-span-2 text-[10px] font-headline font-bold text-primary uppercase tracking-widest text-right">Status</div>
            </div>

            <div class="divide-y-2 divide-neutral-border">
                {{-- Item 1 --}}
                <div class="flex flex-col md:grid md:grid-cols-12 gap-4 px-6 py-6 md:py-3 items-start md:items-center hover:bg-neutral-light transition-colors duration-150">
                    <div class="md:col-span-5 w-full min-w-0">
                        <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Perusahaan</p>
                        <div class="font-bold text-gray-900 text-base md:text-sm uppercase truncate">PT Tirta Makmur</div>
                    </div>
                    <div class="md:col-span-3 w-full flex justify-between md:block">
                        <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Wilayah</p>
                        <div class="text-xs text-slate-500 font-bold uppercase tracking-widest">Jawa Barat</div>
                    </div>
                    <div class="md:col-span-2 w-full flex justify-between items-center md:block md:text-center">
                        <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Reseller</p>
                        <div class="font-headline font-black text-xl text-primary tracking-tighter">24</div>
                    </div>
                    <div class="md:col-span-2 w-full flex justify-between md:block md:text-right">
                        <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Status</p>
                        <span class="px-2 py-0.5 border-2 border-secondary text-secondary text-[10px] font-bold uppercase tracking-widest whitespace-nowrap">Aktif</span>
                    </div>
                </div>

                {{-- Item 2 --}}
                <div class="flex flex-col md:grid md:grid-cols-12 gap-4 px-6 py-6 md:py-3 items-start md:items-center hover:bg-neutral-light transition-colors duration-150">
                    <div class="md:col-span-5 w-full min-w-0">
                        <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Perusahaan</p>
                        <div class="font-bold text-gray-900 text-base md:text-sm uppercase truncate">CV Bintang Selatan</div>
                    </div>
                    <div class="md:col-span-3 w-full flex justify-between md:block">
                        <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Wilayah</p>
                        <div class="text-xs text-slate-500 font-bold uppercase tracking-widest">Jawa Timur</div>
                    </div>
                    <div class="md:col-span-2 w-full flex justify-between items-center md:block md:text-center">
                        <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Reseller</p>
                        <div class="font-headline font-black text-xl text-primary tracking-tighter">18</div>
                    </div>
                    <div class="md:col-span-2 w-full flex justify-between md:block md:text-right">
                        <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Status</p>
                        <span class="px-2 py-0.5 border-2 border-secondary text-secondary text-[10px] font-bold uppercase tracking-widest whitespace-nowrap">Aktif</span>
                    </div>
                </div>

                {{-- Item 3 --}}
                <div class="flex flex-col md:grid md:grid-cols-12 gap-4 px-6 py-6 md:py-3 items-start md:items-center hover:bg-neutral-light transition-colors duration-150">
                    <div class="md:col-span-5 w-full min-w-0">
                        <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Perusahaan</p>
                        <div class="font-bold text-slate-400 text-base md:text-sm uppercase truncate">Distributor Abadi</div>
                    </div>
                    <div class="md:col-span-3 w-full flex justify-between md:block">
                        <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Wilayah</p>
                        <div class="text-xs text-slate-400 font-bold uppercase tracking-widest">Jawa Tengah</div>
                    </div>
                    <div class="md:col-span-2 w-full flex justify-between items-center md:block md:text-center">
                        <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Reseller</p>
                        <div class="font-headline font-black text-xl text-slate-400 tracking-tighter">9</div>
                    </div>
                    <div class="md:col-span-2 w-full flex justify-between md:block md:text-right">
                        <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Status</p>
                        <span class="px-2 py-0.5 border-2 border-slate-300 text-slate-400 text-[10px] font-bold uppercase tracking-widest whitespace-nowrap">Nonaktif</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
