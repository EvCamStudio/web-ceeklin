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
                        <label class="text-[10px] font-bold text-secondary uppercase tracking-widest" for="nama-entitas">Nama Perusahaan</label>
                        <input id="nama-entitas" type="text" placeholder="CV / PT ..."
                            class="bg-neutral-light border-[3px] border-primary px-4 py-2.5 font-body text-sm font-bold text-primary focus:outline-none focus:border-secondary placeholder:text-primary/30 transition-colors">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[10px] font-bold text-secondary uppercase tracking-widest" for="wilayah">Wilayah</label>
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
                        <label class="text-[10px] font-bold text-secondary uppercase tracking-widest" for="kontak">Kontak (PIC)</label>
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
            <div class="bg-primary px-6 py-3 flex items-center justify-between">
                <span class="font-headline font-black text-white text-base uppercase tracking-tight">Distributor Aktif</span>
                {{-- BACKEND-TODO: hitung dari Distributor::active()->count() --}}
                <span class="text-[10px] font-bold uppercase tracking-widest text-secondary">38 Total</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left" aria-label="Daftar Distributor Aktif">
                    <thead>
                        <tr class="bg-neutral-light border-b-2 border-neutral-border">
                            <th class="py-3 px-6 text-[10px] font-headline font-bold text-primary uppercase tracking-widest">Nama Perusahaan</th>
                            <th class="py-3 px-4 text-[10px] font-headline font-bold text-primary uppercase tracking-widest">Wilayah</th>
                            <th class="py-3 px-4 text-[10px] font-headline font-bold text-primary uppercase tracking-widest text-center">Reseller</th>
                            <th class="py-3 px-4 text-[10px] font-headline font-bold text-primary uppercase tracking-widest text-center">Status</th>
                        </tr>
                    </thead>
                    {{-- BACKEND-TODO: Loop dari Distributor::with('resellers')->paginate(15) --}}
                    <tbody class="text-sm divide-y-2 divide-neutral-border">
                        <tr class="hover:bg-neutral-light transition-colors duration-150">
                            <td class="py-3 px-6 font-bold text-gray-900">PT Tirta Makmur</td>
                            <td class="py-3 px-4 text-slate-500 font-bold text-xs uppercase tracking-widest">Jawa Barat</td>
                            <td class="py-3 px-4 font-headline font-black text-xl text-primary text-center">24</td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2 py-1 border-2 border-secondary text-secondary text-[10px] font-bold uppercase tracking-widest">Aktif</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-neutral-light transition-colors duration-150">
                            <td class="py-3 px-6 font-bold text-gray-900">CV Bintang Selatan</td>
                            <td class="py-3 px-4 text-slate-500 font-bold text-xs uppercase tracking-widest">Jawa Timur</td>
                            <td class="py-3 px-4 font-headline font-black text-xl text-primary text-center">18</td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2 py-1 border-2 border-secondary text-secondary text-[10px] font-bold uppercase tracking-widest">Aktif</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-neutral-light transition-colors duration-150">
                            <td class="py-3 px-6 font-bold text-slate-400">Distributor Abadi</td>
                            <td class="py-3 px-4 text-slate-400 font-bold text-xs uppercase tracking-widest">Jawa Tengah</td>
                            <td class="py-3 px-4 font-headline font-black text-xl text-slate-400 text-center">9</td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2 py-1 border-2 border-slate-300 text-slate-400 text-[10px] font-bold uppercase tracking-widest">Nonaktif</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
