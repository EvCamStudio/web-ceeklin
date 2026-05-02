<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">RESELLER TEROTORISASI</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>PENGATURAN</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.reseller._menu')</x-slot:menuSlot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-5xl">

        {{-- Profil Reseller --}}
        <x-ui.card shadow="primary" headerColor="primary" class="lg:col-span-2">
            <x-slot:titleSlot>Profil Reseller</x-slot:titleSlot>
            
            {{-- BACKEND-TODO: Populate dari Auth::user()->reseller atau Reseller::where('user_id', Auth::id())->first() --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-ui.input id="nama-lengkap" label="Nama Lengkap" value="Ahmad Fauzi" />
                
                {{-- NIK tidak bisa diubah, hanya Admin --}}
                <x-ui.input id="nik-reseller" label="No. NIK" value="3201234567890001" disabled />
                
                <x-ui.input id="hp-reseller" type="tel" label="No. HP (WhatsApp)" value="081234567890" />
                
                <x-ui.input id="ahli-waris" label="Ahli Waris" value="Siti Aminah" />

                <div class="flex flex-col gap-1.5 md:col-span-2">
                    <label class="text-[10px] font-bold text-primary uppercase tracking-widest" for="alamat-reseller">Alamat Lengkap</label>
                    <textarea id="alamat-reseller" rows="2"
                        class="bg-neutral-light border-[3px] border-primary px-4 py-2.5 font-body text-sm text-primary focus:outline-none focus:border-secondary transition-colors resize-none">Jl. Raya Bandung No. 12, Kel. Cimahi, Kec. Cimahi Utara, Kota Cimahi, Jawa Barat 40511</textarea>
                </div>

                {{-- BACKEND-TODO: Map Koordinat UI --}}
                <div class="flex flex-col gap-3 md:col-span-2 mt-2 p-4 bg-gray-50 border-[3px] border-gray-900 border-dashed">
                    <div class="flex justify-between items-center mb-1">
                        <div>
                            <label class="text-[10px] font-bold text-primary uppercase tracking-widest block">Titik Koordinat Lokasi (Maps)</label>
                            <span class="text-[9px] text-slate-500 uppercase tracking-widest">Paskan pin di peta untuk mempermudah navigasi pengiriman</span>
                        </div>
                    </div>
                    
                    {{-- Placeholder Peta --}}
                    <div class="w-full h-[180px] bg-gray-200 border-2 border-gray-900 flex flex-col items-center justify-center relative overflow-hidden group cursor-pointer hover:border-secondary transition-colors">
                        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                        <svg class="w-8 h-8 text-secondary mb-2 relative z-10 group-hover:-translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-900 relative z-10 bg-white/90 px-3 py-1.5 border-[2px] border-gray-900 shadow-[2px_2px_0_var(--color-gray-900)]">Klik untuk Menyesuaikan Pin Peta</span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-2">
                        <x-ui.input id="lat-reseller" label="Latitude" value="-6.8732" placeholder="Cth: -6.123456" />
                        <x-ui.input id="lng-reseller" label="Longitude" value="107.5421" placeholder="Cth: 106.123456" />
                    </div>
                    
                    <button type="button" class="w-full bg-white text-secondary border-[2px] border-secondary py-2.5 font-headline font-bold text-[10px] uppercase tracking-widest hover:bg-secondary hover:text-white transition-colors mt-1 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Ambil Titik Lokasi GPS Saat Ini
                    </button>
                </div>
            </div>
            
            <x-ui.button class="mt-6">
                Simpan Perubahan
            </x-ui.button>
        </x-ui.card>

        {{-- Panel Kanan --}}
        <div class="flex flex-col gap-6">

            {{-- Ganti Kata Sandi --}}
            <x-ui.card shadow="gray" headerColor="gray">
                <x-slot:titleSlot>Kata Sandi</x-slot:titleSlot>
                
                <div class="flex flex-col gap-4">
                    <x-ui.input id="sandi-lama-res" type="password" label="Sandi Lama" placeholder="••••••••" class="!border-gray-900" />
                    <x-ui.input id="sandi-baru-res" type="password" label="Sandi Baru" placeholder="Min. 8 karakter" class="!border-gray-900" />
                    <x-ui.input id="konfirmasi-sandi-res" type="password" label="Konfirmasi" placeholder="Ketik ulang" class="!border-gray-900" />
                    
                    <x-ui.button variant="neutral" fullWidth="true" class="mt-1 shadow-[3px_3px_0_var(--color-primary-darkest)]">
                        Perbarui Sandi
                    </x-ui.button>
                </div>
            </x-ui.card>

            {{-- Info Rekening --}}
            <x-ui.card shadow="secondary" headerColor="secondary">
                <x-slot:titleSlot>Info Rekening</x-slot:titleSlot>
                
                <div class="flex flex-col gap-4">
                    {{-- BACKEND-TODO: dari reseller->bank_account --}}
                    <x-ui.input id="bank-reseller" label="Nama Bank" value="BRI" class="!border-secondary" />
                    <x-ui.input id="norek-reseller" label="Nomor Rekening" value="0987654321" class="!border-secondary" />
                    <x-ui.input id="atasnama-reseller" label="Atas Nama" value="AHMAD FAUZI" class="!border-secondary" />
                    
                    <x-ui.button variant="secondary" fullWidth="true" class="mt-1 shadow-[3px_3px_0_var(--color-gray-900)]">
                        Perbarui Rekening
                    </x-ui.button>
                </div>
            </x-ui.card>
        </div>
    </div>
</x-layouts.dashboard>
