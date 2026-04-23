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
        <div class="bg-white border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-secondary)]">
            <div class="bg-secondary px-6 py-3">
                <span class="font-headline font-black text-white text-base uppercase tracking-tight">Tambah Distributor</span>
            </div>
            <div class="p-6">
                {{-- BACKEND-TODO: action ke DistributorController@store + @csrf --}}
                <form class="flex flex-col gap-5">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[10px] font-bold text-primary uppercase tracking-widest" for="nama-entitas">Nama Perusahaan</label>
                        <input id="nama-entitas" type="text" placeholder="CV / PT ..."
                            class="bg-neutral-light border-[3px] border-primary px-4 py-2.5 font-body text-sm font-bold text-primary focus:outline-none focus:border-secondary placeholder:text-primary/30 transition-colors">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[10px] font-bold text-primary uppercase tracking-widest" for="wilayah">Wilayah</label>
                        <div class="relative">
                            <select id="wilayah" aria-label="Pilih Wilayah"
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
                        <label class="text-[10px] font-bold text-primary uppercase tracking-widest" for="kontak">Kontak (PIC)</label>
                        <input id="kontak" type="text" placeholder="Nama & No. HP"
                            class="bg-neutral-light border-[3px] border-primary px-4 py-2.5 font-body text-sm font-bold text-primary focus:outline-none focus:border-secondary placeholder:text-primary/30 transition-colors">
                    </div>
                    <button type="button" aria-label="Inisialisasi akun distributor baru"
                        class="w-full bg-primary text-white py-3 font-headline font-bold text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-0.5 active:shadow-none transition-all mt-2">
                        Buat Akun
                    </button>
                </form>
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
