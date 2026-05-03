<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">PORTAL DISTRIBUTOR</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>PENGATURAN</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.distributor._menu')</x-slot:menuSlot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-5xl">

        {{-- Profil Perusahaan --}}
        <div class="lg:col-span-2 bg-white border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-primary-darkest)]">
            <div class="bg-primary px-6 py-3">
                <span class="font-headline font-black text-white text-base uppercase tracking-tight">Profil Distributor</span>
            </div>
            <div class="p-6">
                <form action="/dashboard/distributor/settings/update-profile" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-bold text-primary uppercase tracking-widest" for="nama-perusahaan">Nama Perusahaan</label>
                            <input id="nama-perusahaan" name="name" type="text" value="{{ $user->name }}"
                                class="bg-neutral-light border-[3px] border-primary px-4 py-2.5 font-body text-sm font-bold text-primary focus:outline-none focus:border-secondary transition-colors">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-bold text-primary uppercase tracking-widest" for="email-dist">Email</label>
                            <input id="email-dist" name="email" type="email" value="{{ $user->email }}"
                                class="bg-neutral-light border-[3px] border-primary px-4 py-2.5 font-body text-sm text-primary focus:outline-none focus:border-secondary transition-colors">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-bold text-primary uppercase tracking-widest" for="telp-dist">No. Telepon</label>
                            <input id="telp-dist" name="phone" type="tel" value="{{ $user->phone }}"
                                class="bg-neutral-light border-[3px] border-primary px-4 py-2.5 font-body text-sm text-primary focus:outline-none focus:border-secondary transition-colors">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-bold text-primary uppercase tracking-widest" for="wilayah-dist">Wilayah Operasi</label>
                            <input id="wilayah-dist" type="text" value="{{ $user->province_name ?? 'Nasional' }}" disabled
                                class="bg-neutral-border-light border-[3px] border-neutral-border px-4 py-2.5 font-body text-sm text-slate-400 cursor-not-allowed">
                        </div>
                        <div class="flex flex-col gap-1.5 md:col-span-2">
                            <label class="text-[10px] font-bold text-primary uppercase tracking-widest" for="alamat-gudang">Alamat Gudang Lengkap</label>
                            <textarea id="alamat-gudang" name="address" rows="2"
                                class="bg-neutral-light border-[3px] border-primary px-4 py-2.5 font-body text-sm text-primary focus:outline-none focus:border-secondary transition-colors resize-none">{{ $user->address }}</textarea>
                        </div>

                        {{-- BACKEND-TODO: Map Koordinat UI --}}
                        <div class="flex flex-col gap-3 md:col-span-2 mt-2 p-4 bg-gray-50 border-[3px] border-gray-900 border-dashed">
                            <div class="flex justify-between items-center mb-1">
                                <div>
                                    <label class="text-[10px] font-bold text-primary uppercase tracking-widest block">Titik Koordinat Lokasi Gudang (Maps)</label>
                                    <span class="text-[9px] text-slate-500 uppercase tracking-widest">Paskan pin di peta untuk memudahkan kalkulasi pengiriman & logistik</span>
                                </div>
                            </div>
                            
                            {{-- Placeholder Peta --}}
                            <div class="w-full h-[180px] bg-gray-200 border-2 border-gray-900 flex flex-col items-center justify-center relative overflow-hidden group cursor-pointer hover:border-secondary transition-colors">
                                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                                <svg class="w-8 h-8 text-secondary mb-2 relative z-10 group-hover:-translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span class="text-[10px] font-bold uppercase tracking-widest text-gray-900 relative z-10 bg-white/90 px-3 py-1.5 border-[2px] border-gray-900 shadow-[2px_2px_0_var(--color-gray-900)]">Klik untuk Menyesuaikan Pin Peta</span>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mt-2">
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-[9px] font-bold text-primary uppercase tracking-widest" for="lat-dist">Latitude</label>
                                    <input id="lat-dist" type="text" value="-6.2845" placeholder="Cth: -6.123456"
                                        class="bg-white border-[2px] border-primary px-3 py-2.5 font-body text-sm font-bold focus:outline-none focus:border-secondary transition-colors">
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-[9px] font-bold text-primary uppercase tracking-widest" for="lng-dist">Longitude</label>
                                    <input id="lng-dist" type="text" value="107.1423" placeholder="Cth: 106.123456"
                                        class="bg-white border-[2px] border-primary px-3 py-2.5 font-body text-sm font-bold focus:outline-none focus:border-secondary transition-colors">
                                </div>
                            </div>
                            
                            <button type="button" class="w-full bg-white text-secondary border-[2px] border-secondary py-2.5 font-headline font-bold text-[10px] uppercase tracking-widest hover:bg-secondary hover:text-white transition-colors mt-1 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Ambil Titik Lokasi GPS Saat Ini
                            </button>
                        </div>
                    </div>
                    <button type="submit" aria-label="Simpan perubahan profil perusahaan"
                        class="mt-6 bg-primary text-white px-8 py-3 font-headline font-bold text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-0.5 active:shadow-none transition-all">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        {{-- Panel Kanan: Password + Info Rekening --}}
        <div class="flex flex-col gap-6">

            {{-- Ganti Kata Sandi --}}
            <div class="bg-white border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)]">
                <div class="bg-gray-900 px-6 py-3">
                    <span class="font-headline font-black text-white text-sm uppercase tracking-tight">Kata Sandi</span>
                </div>
                <div class="p-5 flex flex-col gap-4">
                    <form action="/dashboard/distributor/settings/update-password" method="POST">
                        @csrf
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-bold text-primary uppercase tracking-widest" for="sandi-lama-dist">Sandi Lama</label>
                            <input id="sandi-lama-dist" name="current_password" type="password" placeholder="••••••••"
                                class="bg-neutral-light border-[3px] border-gray-900 px-4 py-2 font-body text-sm focus:outline-none focus:border-primary transition-colors placeholder:text-slate-300">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-bold text-primary uppercase tracking-widest" for="sandi-baru-dist">Sandi Baru</label>
                            <input id="sandi-baru-dist" name="new_password" type="password" placeholder="Min. 8 karakter"
                                class="bg-neutral-light border-[3px] border-gray-900 px-4 py-2 font-body text-sm focus:outline-none focus:border-primary transition-colors placeholder:text-slate-300">
                        </div>
                        <button type="submit" aria-label="Perbarui kata sandi"
                            class="w-full bg-gray-900 text-white py-2.5 font-headline font-bold text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[3px_3px_0_var(--color-primary-darkest)] hover:bg-gray-700 active:translate-y-0.5 active:shadow-none transition-all mt-1">
                            Perbarui Sandi
                        </button>
                    </form>
                </div>
            </div>

            {{-- Info Rekening --}}
            <div class="bg-white border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-secondary)]">
                <div class="bg-secondary px-6 py-3">
                    <span class="font-headline font-black text-white text-sm uppercase tracking-tight">Info Rekening</span>
                </div>
                <div class="p-5 flex flex-col gap-4">
                    <form action="/dashboard/distributor/settings/update-bank" method="POST">
                        @csrf
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-bold text-primary uppercase tracking-widest" for="nama-bank">Nama Bank</label>
                            <input id="nama-bank" name="bank_name" type="text" value="{{ $user->bank_name }}"
                                class="bg-neutral-light border-[3px] border-secondary px-4 py-2 font-body text-sm font-bold text-primary focus:outline-none focus:border-primary transition-colors">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-bold text-primary uppercase tracking-widest" for="no-rekening">Nomor Rekening</label>
                            <input id="no-rekening" name="bank_account_number" type="text" value="{{ $user->bank_account_number }}"
                                class="bg-neutral-light border-[3px] border-secondary px-4 py-2 font-body text-sm text-primary focus:outline-none focus:border-primary transition-colors">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-bold text-primary uppercase tracking-widest" for="atas-nama">Atas Nama</label>
                            <input id="atas-nama" name="bank_account_name" type="text" value="{{ $user->bank_account_name }}"
                                class="bg-neutral-light border-[3px] border-secondary px-4 py-2 font-body text-sm font-bold text-primary focus:outline-none focus:border-primary transition-colors">
                        </div>
                        <button type="submit" aria-label="Perbarui informasi rekening bank"
                            class="w-full bg-secondary text-white py-2.5 font-headline font-bold text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[3px_3px_0_var(--color-gray-900)] hover:bg-secondary-dark active:translate-y-0.5 active:shadow-none transition-all mt-1">
                            Perbarui Rekening
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-layouts.dashboard>
