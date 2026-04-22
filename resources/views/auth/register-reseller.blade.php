<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrasi Reseller - CEEKLIN Portal</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral font-body text-gray-900 min-h-screen flex flex-col antialiased">
    {{-- Grain Texture Overlay --}}
    <div class="grain-overlay"></div>

    <x-layouts.navbar>
        <x-slot:links>
            <a href="{{ route('home') }}" class="text-[10px] font-bold uppercase tracking-widest text-slate-600 hover:text-primary transition-all duration-300 hover:-translate-y-0.5 pb-1">BERANDA</a>
            <a href="{{ route('specs') }}" class="text-[10px] font-bold uppercase tracking-widest text-slate-600 hover:text-primary transition-all duration-300 hover:-translate-y-0.5 pb-1">SPEK PRODUK</a>
            <a href="{{ route('gallery') }}" class="text-[10px] font-bold uppercase tracking-widest text-slate-600 hover:text-primary transition-all duration-300 hover:-translate-y-0.5 pb-1">GALERI</a>
            <a href="{{ route('contact') }}" class="text-[10px] font-bold uppercase tracking-widest text-slate-600 hover:text-primary transition-all duration-300 hover:-translate-y-0.5 pb-1">KONTAK</a>
        </x-slot:links>

        <a href="/login" class="font-headline font-bold text-[10px] uppercase tracking-widest bg-primary text-white border-[3px] border-primary px-8 py-2.5 hover:bg-transparent hover:text-primary transition-all duration-300">
            KEMBALI KE LOGIN
        </a>
    </x-layouts.navbar>

    {{-- Main Content --}}
    <main class="flex-grow flex items-center justify-center py-8 px-4" x-data="{ step: 1 }">
        <div class="w-full max-w-4xl" x-cloak>
            
            {{-- Stepper UI Brutalist --}}
            <div class="flex items-center justify-center gap-4 mb-6 w-full px-4 overflow-x-auto">
                {{-- STEP 1 --}}
                <div class="flex flex-col items-center gap-1.5 min-w-[70px]">
                    <div class="w-10 h-10 flex items-center justify-center font-headline font-black text-base border-[3px] border-gray-900 transition-all duration-300"
                        :class="step >= 1 ? 'bg-primary text-white shadow-[4px_4px_0_var(--color-secondary)]' : 'bg-neutral-light text-slate-400 opacity-60'">
                        <template x-if="step > 1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                        </template>
                        <template x-if="step === 1">
                            <span>1</span>
                        </template>
                    </div>
                    <span class="font-headline font-bold text-[9px] uppercase tracking-[0.2em]"
                        :class="step >= 1 ? 'text-gray-900' : 'text-slate-400'">Profil</span>
                </div>

                <div class="flex-1 h-[3px] max-w-[80px] border-y border-gray-900" :class="step >= 2 ? 'bg-primary' : 'bg-neutral-border'"></div>

                {{-- STEP 2 --}}
                <div class="flex flex-col items-center gap-1.5 min-w-[70px]">
                    <div class="w-10 h-10 flex items-center justify-center font-headline font-black text-base border-[3px] border-gray-900 transition-all duration-300"
                        :class="step >= 2 ? 'bg-primary text-white shadow-[4px_4px_0_var(--color-secondary)]' : 'bg-neutral-light text-slate-400'">
                        <template x-if="step > 2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                        </template>
                        <template x-if="step <= 2">
                            <span>2</span>
                        </template>
                    </div>
                    <span class="font-headline font-bold text-[9px] uppercase tracking-[0.2em]"
                        :class="step >= 2 ? 'text-gray-900' : 'text-slate-400'">Rekening</span>
                </div>

                <div class="flex-1 h-[3px] max-w-[80px] border-y border-gray-900" :class="step >= 3 ? 'bg-primary' : 'bg-neutral-border'"></div>

                {{-- STEP 3 --}}
                <div class="flex flex-col items-center gap-1.5 min-w-[70px]">
                    <div class="w-10 h-10 flex items-center justify-center font-headline font-black text-base border-[3px] border-gray-900 transition-all duration-300"
                        :class="step === 3 ? 'bg-primary text-white shadow-[4px_4px_0_var(--color-secondary)]' : 'bg-neutral-light text-slate-400'">
                        <span>3</span>
                    </div>
                    <span class="font-headline font-bold text-[9px] uppercase tracking-[0.2em]"
                        :class="step === 3 ? 'text-gray-900' : 'text-slate-400'">Selesai</span>
                </div>
            </div>

            {{-- Form Container --}}
            <div class="bg-white border-4 border-gray-900 shadow-[12px_12px_0_var(--color-primary)]">
                <div class="bg-primary p-4 border-b-4 border-gray-900 flex justify-between items-center">
                    <h2 class="font-headline font-black text-lg text-white uppercase tracking-tighter" 
                        x-text="step === 1 ? 'Langkah 1: Identitas Diri' : (step === 2 ? 'Langkah 2: Data Pembayaran' : 'Langkah 3: Keamanan Akses')"></h2>
                    <span class="bg-secondary text-gray-900 font-headline font-black px-2 py-0.5 text-xs border-2 border-gray-900" x-text="step + '/3'"></span>
                </div>

                <div class="p-6 lg:p-8">
                    <form method="POST" action="/register" novalidate>
                        @csrf
                        
                        {{-- [ STEP 1: DATA DIRI & ALAMAT ] --}}
                        <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-3">
                                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1">Informasi Utama</p>
                                    <div class="h-1 w-12 bg-secondary"></div>
                                </div>
                                
                                <x-ui.input id="nik" name="nik" label="NO. NIK KTP" placeholder="16 digit NIK" required />
                                <x-ui.input id="name" name="name" label="NAMA LENGKAP" placeholder="Sesuai KTP" required />
                                <x-ui.input id="phone" name="phone" type="tel" label="NO. WHATSAPP" placeholder="08xx..." required />
                                
                                <div class="md:col-span-3">
                                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1">Domisili Pengiriman</p>
                                    <div class="h-1 w-12 bg-secondary"></div>
                                </div>

                                <div class="flex flex-col gap-1.5">
                                    <label class="text-[9px] font-bold text-primary uppercase tracking-widest" for="province_id">PROVINSI</label>
                                    <select id="province_id" name="province_id" class="bg-neutral border-2 border-neutral-border px-3 py-2.5 font-body text-xs font-bold text-gray-900 focus:outline-none focus:border-primary transition-colors appearance-none" required>
                                        <option value="">PILIH PROVINSI</option>
                                        <option value="1">Jawa Barat</option>
                                    </select>
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-[9px] font-bold text-primary uppercase tracking-widest" for="city_id">KOTA / KABUPATEN</label>
                                    <select id="city_id" name="city_id" class="bg-neutral border-2 border-neutral-border px-3 py-2.5 font-body text-xs font-bold text-gray-900 focus:outline-none focus:border-primary transition-colors appearance-none" required>
                                        <option value="">PILIH KOTA</option>
                                        <option value="1">Bandung</option>
                                    </select>
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <label class="text-[9px] font-bold text-primary uppercase tracking-widest" for="district_id">KECAMATAN</label>
                                    <select id="district_id" name="district_id" class="bg-neutral border-2 border-neutral-border px-3 py-2.5 font-body text-xs font-bold text-gray-900 focus:outline-none focus:border-primary transition-colors appearance-none" required>
                                        <option value="">PILIH KECAMATAN</option>
                                    </select>
                                </div>
                                
                                <div class="md:col-span-3">
                                    <div class="flex flex-col gap-1.5">
                                        <label class="text-[9px] font-bold text-primary uppercase tracking-widest" for="address">ALAMAT DETAIL</label>
                                        <textarea id="address" name="address" rows="2" required placeholder="Nama jalan, nomor rumah, RT/RW..." 
                                            class="bg-neutral border-2 border-neutral-border px-3 py-2.5 font-body text-xs font-bold text-gray-900 focus:outline-none focus:border-primary transition-colors resize-none"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <x-ui.button type="button" @click="step = 2" class="px-12 py-3 text-sm">
                                    LANJUTKAN
                                </x-ui.button>
                            </div>
                        </div>

                        {{-- [ STEP 2: REKENING BANK ] --}}
                        <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                            <div class="bg-neutral-light p-6 border-l-4 border-primary mb-8">
                                <p class="text-xs font-bold text-slate-600 leading-relaxed">PENTING: Pastikan rekening atas nama Anda sendiri sesuai KTP. Data ini mutlak digunakan untuk pencairan bagi hasil penjualan.</p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="md:col-span-2">
                                    <x-ui.input id="bank_account_name" name="bank_account_name" label="ATAS NAMA REKENING" placeholder="Contoh: BUDI SANTOSO" class="uppercase" required />
                                </div>
                                <x-ui.input id="bank_name" name="bank_name" label="NAMA BANK" placeholder="BCA / MANDIRI / BNI / BRI" required />
                                <x-ui.input id="bank_account_number" name="bank_account_number" type="number" label="NOMOR REKENING" placeholder="Masukkan angka saja" required />
                            </div>

                            <div class="mt-12 flex flex-col sm:flex-row justify-between gap-4">
                                <button type="button" @click="step = 1" class="font-headline font-bold text-[10px] uppercase tracking-widest text-slate-400 hover:text-primary transition-colors">
                                    &larr; Kembali ke Data Diri
                                </button>
                                <x-ui.button type="button" @click="step = 3" class="px-16 py-4 text-base">
                                    LANJUTKAN
                                </x-ui.button>
                            </div>
                        </div>

                        {{-- [ STEP 3: VERIFIKASI AKUN ] --}}
                        <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="md:col-span-2">
                                    <x-ui.input id="username" name="username" label="USERNAME AKSES" placeholder="Gunakan huruf kecil & angka" required />
                                </div>
                                <x-ui.input id="password" name="password" type="password" label="PASSWORD" placeholder="Minimal 8 karakter" required />
                                <x-ui.input id="password_confirmation" name="password_confirmation" type="password" label="KONFIRMASI PASSWORD" placeholder="Ulangi password Anda" required />
                                
                                <div class="md:col-span-2">
                                    <x-ui.input id="referral_code" name="referral_code" label="KODE REFERRAL (OPSIONAL)" placeholder="Kode dari Distributor Wilayah" class="!border-secondary" />
                                </div>
                            </div>

                            <div class="mt-10 bg-gray-900 text-white p-6 border-l-4 border-secondary shadow-lg">
                                <p class="text-secondary font-headline font-black text-xs tracking-widest uppercase mb-2">⚠️ ATURAN AKTIVASI</p>
                                <p class="text-[11px] font-bold leading-relaxed opacity-90">
                                    Setelah menekan tombol daftar, akun Anda akan masuk status 'TUNGGU'. Anda wajib melakukan order komitmen awal (Activation Order) di halaman berikutnya dalam waktu <span class="text-secondary">48 JAM</span> atau pendaftaran Anda akan dibatalkan otomatis oleh sistem.
                                </p>
                            </div>

                            <div class="mt-12 flex flex-col sm:flex-row justify-between gap-4">
                                <button type="button" @click="step = 2" class="font-headline font-bold text-[10px] uppercase tracking-widest text-slate-400 hover:text-primary transition-colors">
                                    &larr; Kembali ke Data Rekening
                                </button>
                                <x-ui.button type="submit" class="px-10 py-4 text-base">
                                    DAFTAR SEKARANG
                                </x-ui.button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <x-layouts.footer variant="simple" />
</body>
</html>
