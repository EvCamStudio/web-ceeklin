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
            <a href="{{ route('home') }}"
                class="px-3 py-2 text-[10px] font-bold uppercase tracking-widest text-slate-600 hover:text-primary transition-all duration-300 hover:-translate-y-0.5">BERANDA</a>
            <a href="{{ route('specs') }}"
                class="px-3 py-2 text-[10px] font-bold uppercase tracking-widest text-slate-600 hover:text-primary transition-all duration-300 hover:-translate-y-0.5">SPEK
                PRODUK</a>
            <a href="{{ route('gallery') }}"
                class="px-3 py-2 text-[10px] font-bold uppercase tracking-widest text-slate-600 hover:text-primary transition-all duration-300 hover:-translate-y-0.5">GALERI</a>
            <a href="{{ route('contact') }}"
                class="px-3 py-2 text-[10px] font-bold uppercase tracking-widest text-slate-600 hover:text-primary transition-all duration-300 hover:-translate-y-0.5">KONTAK</a>
        </x-slot:links>

        <a href="/login"
            class="font-headline font-bold text-[10px] uppercase tracking-widest bg-primary text-white border-[3px] border-primary px-8 py-2.5 hover:bg-transparent hover:text-primary transition-all duration-300">
            SUDAH PUNYA AKUN? MASUK
        </a>
    </x-layouts.navbar>

    {{-- Main Content --}}
    @php
        $initialStep = 1;
        if ($errors->any()) {
            if ($errors->hasAny(['nik', 'name', 'phone', 'ktp_photo', 'province_id', 'city_id', 'district_id', 'address'])) {
                $initialStep = 1;
            } elseif ($errors->hasAny(['bank_account_name', 'bank_name', 'bank_account_number'])) {
                $initialStep = 2;
            } elseif ($errors->hasAny(['username', 'password'])) {
                $initialStep = 3;
            }
        }
    @endphp
    <main class="flex-grow flex items-center justify-center py-8 px-4" x-data="{ 
        step: {{ $initialStep }}, 
        errors: {},
        async validateField(input) {
            const name = input.name;
            const value = input.value;
            
            // Hapus error lama untuk field ini agar reaktif
            this.errors = { ...this.errors, [name]: null };
            
            if (input.hasAttribute('required') && !value && input.type !== 'file') {
                this.errors = { ...this.errors, [name]: 'Bidang ini wajib diisi' };
            } else if (input.type === 'file') {
                if (input.hasAttribute('required') && !input.files.length) {
                    this.errors = { ...this.errors, [name]: 'Foto KTP wajib diunggah' };
                } else if (input.files.length) {
                    const file = input.files[0];
                    const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                    if (!validTypes.includes(file.type)) {
                        this.errors = { ...this.errors, [name]: 'Format foto harus JPG atau PNG' };
                    } else if (file.size > 2 * 1024 * 1024) {
                        this.errors = { ...this.errors, [name]: 'Ukuran foto maksimal 2MB' };
                    }
                }
            } else if (name === 'nik' && value) {
                if (!/^[0-9]+$/.test(value)) {
                    this.errors = { ...this.errors, [name]: 'NIK harus berupa angka' };
                } else if (value.length !== 16) {
                    this.errors = { ...this.errors, [name]: 'NIK harus tepat 16 digit' };
                } else {
                    try {
                        const response = await fetch(`/api/check-nik?nik=${value}`);
                        if (response.ok) {
                            const data = await response.json();
                            if (!data.available) {
                                this.errors = { ...this.errors, [name]: 'NIK ini sudah terdaftar di sistem kami' };
                            }
                        }
                    } catch (e) { console.error('Gagal cek NIK:', e); }
                }
            } else if (name === 'phone' && value) {
                if (!/^[0-9]+$/.test(value)) {
                    this.errors = { ...this.errors, [name]: 'Nomor WA harus berupa angka' };
                } else if (value.length < 9 || value.length > 15) {
                    this.errors = { ...this.errors, [name]: 'Nomor WA tidak valid' };
                }
            } else if (name === 'username' && value) {
                if (!/^[a-z0-9_]+$/.test(value)) {
                    this.errors = { ...this.errors, [name]: 'Hanya huruf kecil, angka & underscore' };
                } else if (value.length < 4) {
                    this.errors = { ...this.errors, [name]: 'Username minimal 4 karakter' };
                } else {
                    try {
                        const response = await fetch(`/api/check-username?username=${value}`);
                        if (response.ok) {
                            const data = await response.json();
                            if (!data.available) {
                                this.errors = { ...this.errors, [name]: 'Username ini sudah digunakan orang lain' };
                            }
                        }
                    } catch (e) { console.error('Gagal cek Username:', e); }
                }
            } else if (name === 'bank_account_number' && value) {
                if (!/^[0-9]+$/.test(value)) {
                    this.errors = { ...this.errors, [name]: 'Nomor rekening harus berupa angka' };
                } else {
                    try {
                        const response = await fetch(`/api/check-bank?account=${value}`);
                        if (response.ok) {
                            const data = await response.json();
                            if (!data.available) {
                                this.errors = { ...this.errors, [name]: 'Nomor rekening ini sudah terdaftar' };
                            }
                        }
                    } catch (e) { console.error('Gagal cek Rekening:', e); }
                }
            } else if (name === 'password' && value) {
                if (value.length < 8) {
                    this.errors = { ...this.errors, [name]: 'Password minimal 8 karakter' };
                }
                const confirmInput = document.getElementById('password_confirmation');
                if (confirmInput && confirmInput.value && value !== confirmInput.value) {
                    this.errors = { ...this.errors, ['password_confirmation']: 'Konfirmasi password tidak cocok' };
                } else if (confirmInput && confirmInput.value && value === confirmInput.value) {
                    this.errors = { ...this.errors, ['password_confirmation']: null };
                }
            } else if (name === 'password_confirmation' && value) {
                const passwordInput = document.getElementById('password');
                if (passwordInput && value !== passwordInput.value) {
                    this.errors = { ...this.errors, [name]: 'Konfirmasi password tidak cocok' };
                }
            }
        },
        async validateStep(currentStep) {
            let isValid = true;
            const stepEl = document.querySelector(`[x-show='step === ${currentStep}']`);
            if (stepEl) {
                const inputs = stepEl.querySelectorAll('input, select, textarea');
                for (let input of inputs) {
                    await this.validateField(input);
                    if (this.errors[input.name]) isValid = false;
                }
            }

            if (isValid && currentStep < 3) this.step++;
            return isValid;
        }
    }">
        <div class="w-full max-w-4xl" x-cloak>

            {{-- Stepper UI Brutalist --}}
            <div class="flex items-center justify-center gap-4 mb-6 w-full px-4 overflow-x-auto">
                {{-- STEP 1 --}}
                <div class="flex flex-col items-center gap-1.5 min-w-[70px]">
                    <div class="w-10 h-10 flex items-center justify-center font-headline font-black text-base border-[3px] border-gray-900 transition-all duration-300"
                        :class="step >= 1 ? 'bg-primary text-white shadow-[4px_4px_0_var(--color-secondary)]' : 'bg-neutral-light text-slate-400 opacity-60'">
                        <template x-if="step > 1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </template>
                        <template x-if="step === 1">
                            <span>1</span>
                        </template>
                    </div>
                    <span class="font-headline font-bold text-[9px] uppercase tracking-[0.2em]"
                        :class="step >= 1 ? 'text-gray-900' : 'text-slate-400'">Profil</span>
                </div>

                <div class="flex-1 h-[3px] max-w-[80px] border-y border-gray-900"
                    :class="step >= 2 ? 'bg-primary' : 'bg-neutral-border'"></div>

                {{-- STEP 2 --}}
                <div class="flex flex-col items-center gap-1.5 min-w-[70px]">
                    <div class="w-10 h-10 flex items-center justify-center font-headline font-black text-base border-[3px] border-gray-900 transition-all duration-300"
                        :class="step >= 2 ? 'bg-primary text-white shadow-[4px_4px_0_var(--color-secondary)]' : 'bg-neutral-light text-slate-400'">
                        <template x-if="step > 2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </template>
                        <template x-if="step <= 2">
                            <span>2</span>
                        </template>
                    </div>
                    <span class="font-headline font-bold text-[9px] uppercase tracking-[0.2em]"
                        :class="step >= 2 ? 'text-gray-900' : 'text-slate-400'">Rekening</span>
                </div>

                <div class="flex-1 h-[3px] max-w-[80px] border-y border-gray-900"
                    :class="step >= 3 ? 'bg-primary' : 'bg-neutral-border'"></div>

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
                        x-text="step === 1 ? 'Langkah 1: Identitas Diri' : (step === 2 ? 'Langkah 2: Data Pembayaran' : 'Langkah 3: Keamanan Akses')">
                    </h2>
                    <span
                        class="bg-secondary text-gray-900 font-headline font-black px-2 py-0.5 text-xs border-2 border-gray-900"
                        x-text="step + '/3'"></span>
                </div>

                <div class="p-6 lg:p-8">
                    <form id="registerForm" method="POST" action="/register" enctype="multipart/form-data" novalidate 
                          @submit.prevent="validateStep(step).then(valid => { if(valid && step === 3) document.getElementById('registerForm').submit(); })">
                        @csrf
                        
                        {{-- Honeypot field (hidden from users, bot prevention) --}}
                        <div class="hidden" aria-hidden="true">
                            <input type="text" name="website_url" tabindex="-1" autocomplete="off">
                        </div>

                        {{-- [ STEP 1: DATA DIRI & ALAMAT ] --}}
                        <div x-show="step === 1" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-4"
                            x-transition:enter-end="opacity-100 translate-y-0">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-3">
                                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1">
                                        Informasi Utama</p>
                                    <div class="h-1 w-12 bg-secondary"></div>
                                </div>

                                <x-ui.input id="nik" name="nik" label="NO. NIK KTP" placeholder="16 digit NIK" required
                                    minlength="16" maxlength="16" inputmode="numeric" value="{{ old('nik') }}" />
                                <x-ui.input id="name" name="name" label="NAMA LENGKAP" placeholder="Sesuai KTP"
                                    class="uppercase" required value="{{ old('name') }}"
                                    @input="$el.value = $el.value.toUpperCase(); if (errors['name']) validateField($el)" />
                                <x-ui.input id="phone" name="phone" type="text" inputmode="numeric" label="NO. WHATSAPP"
                                    placeholder="08xx..." required value="{{ old('phone') }}" />

                                {{-- Upload KTP --}}
                                <div class="md:col-span-3 mt-2 relative">
                                    <label
                                        class="text-[9px] font-bold text-primary uppercase tracking-widest block mb-1.5">UNGGAH
                                        FOTO KTP</label>
                                    <div class="relative group" id="ktp_preview_container">
                                        <input type="file" name="ktp_photo" id="ktp_photo" accept="image/*"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                            required @change="validateField($el)">
                                        <div id="ktp_placeholder"
                                            class="border-2 border-dashed border-primary bg-neutral-light p-6 flex flex-col items-center justify-center gap-3 group-hover:bg-neutral-border-light transition-all">
                                            <svg class="w-8 h-8 text-primary opacity-50 group-hover:opacity-100 transition-opacity"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <div class="text-center">
                                                <p
                                                    class="text-[10px] font-black text-primary uppercase tracking-widest">
                                                    Pilih File Foto KTP</p>
                                                <p class="text-[8px] font-bold text-slate-500 mt-1 uppercase">Format:
                                                    JPG, PNG (Maks. 2MB)</p>
                                            </div>
                                        </div>
                                        <div id="ktp_preview"
                                            class="hidden border-4 border-gray-900 overflow-hidden bg-white">
                                            <img src="" alt="Preview KTP"
                                                class="w-full h-auto max-h-[300px] object-contain">
                                            <div
                                                class="bg-gray-900 text-white p-2 text-center text-[8px] font-black uppercase tracking-[0.2em]">
                                                Klik untuk mengganti foto</div>
                                        </div>
                                    </div>
                                    <x-ui.error name="ktp_photo" />
                                </div>

                                <div class="md:col-span-3">
                                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1">
                                        Domisili Pengiriman</p>
                                    <div class="h-1 w-12 bg-secondary"></div>
                                </div>

                                <div class="flex flex-col gap-1.5 relative">
                                    <label class="text-[9px] font-bold text-primary uppercase tracking-widest"
                                        for="province_id">PROVINSI</label>
                                    <div class="relative">
                                        <select id="province_id" name="province_id"
                                            class="appearance-none w-full bg-neutral-light border-[3px] border-primary px-3 py-2.5 font-body text-xs font-bold text-primary focus:outline-none focus:border-secondary transition-colors cursor-pointer"
                                            :class="errors['province_id'] ? 'border-red-600 bg-red-50' : ''" required
                                            @change="validateField($el)">
                                            <option value="">PILIH PROVINSI</option>
                                            @foreach ([
                                                '11' => 'ACEH',
                                                '12' => 'SUMATERA UTARA',
                                                '13' => 'SUMATERA BARAT',
                                                '14' => 'RIAU',
                                                '15' => 'JAMBI',
                                                '16' => 'SUMATERA SELATAN',
                                                '17' => 'BENGKULU',
                                                '18' => 'LAMPUNG',
                                                '19' => 'KEPULAUAN BANGKA BELITUNG',
                                                '21' => 'KEPULAUAN RIAU',
                                                '31' => 'DKI JAKARTA',
                                                '32' => 'JAWA BARAT',
                                                '33' => 'JAWA TENGAH',
                                                '34' => 'DI YOGYAKARTA',
                                                '35' => 'JAWA TIMUR',
                                                '36' => 'BANTEN',
                                                '51' => 'BALI',
                                                '52' => 'NUSA TENGGARA BARAT',
                                                '53' => 'NUSA TENGGARA TIMUR',
                                                '61' => 'KALIMANTAN BARAT',
                                                '62' => 'KALIMANTAN TENGAH',
                                                '63' => 'KALIMANTAN SELATAN',
                                                '64' => 'KALIMANTAN TIMUR',
                                                '65' => 'KALIMANTAN UTARA',
                                                '71' => 'SULAWESI UTARA',
                                                '72' => 'SULAWESI TENGAH',
                                                '73' => 'SULAWESI SELATAN',
                                                '74' => 'SULAWESI TENGGARA',
                                                '75' => 'GORONTALO',
                                                '76' => 'SULAWESI BARAT',
                                                '81' => 'MALUKU',
                                                '82' => 'MALUKU UTARA',
                                                '91' => 'PAPUA BARAT',
                                                '94' => 'PAPUA'
                                            ] as $id => $prov)
                                                <option value="{{ $id }}" {{ old('province_id') == $id ? 'selected' : '' }}>{{ $prov }}</option>
                                            @endforeach
                                        </select>
                                        <div
                                            class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-primary">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                    <x-ui.error name="province_id" />
                                </div>
                                <div class="flex flex-col gap-1.5 relative">
                                    <label class="text-[9px] font-bold text-primary uppercase tracking-widest"
                                        for="city_id">KOTA / KABUPATEN</label>
                                    <div class="relative">
                                        <select id="city_id" name="city_id"
                                            class="appearance-none w-full bg-neutral-light border-[3px] border-primary px-3 py-2.5 font-body text-xs font-bold text-primary focus:outline-none focus:border-secondary transition-colors cursor-pointer"
                                            :class="errors['city_id'] ? 'border-red-600 bg-red-50' : ''" required
                                            @change="validateField($el)">
                                            <option value="">PILIH KOTA</option>
                                        </select>
                                        <div
                                            class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-primary">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                    <x-ui.error name="city_id" />
                                </div>
                                <div class="flex flex-col gap-1.5 relative">
                                    <label class="text-[9px] font-bold text-primary uppercase tracking-widest"
                                        for="district_id">KECAMATAN</label>
                                    <div class="relative">
                                        <select id="district_id" name="district_id"
                                            class="appearance-none w-full bg-neutral-light border-[3px] border-primary px-3 py-2.5 font-body text-xs font-bold text-primary focus:outline-none focus:border-secondary transition-colors cursor-pointer"
                                            :class="errors['district_id'] ? 'border-red-600 bg-red-50' : ''" required
                                            @change="validateField($el)">
                                            <option value="">PILIH KECAMATAN</option>
                                            @if(old('district_id'))
                                                <option value="{{ old('district_id') }}" selected>KECAMATAN TERPILIH</option>
                                            @endif
                                        </select>
                                        <div
                                            class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-primary">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                    <x-ui.error name="district_id" />
                                </div>

                                <div class="md:col-span-3">
                                    <div class="flex flex-col gap-1.5 relative">
                                        <label class="text-[9px] font-bold text-primary uppercase tracking-widest"
                                            for="address">ALAMAT DETAIL</label>
                                        <textarea id="address" name="address" rows="2" required
                                            placeholder="Nama jalan, nomor rumah, RT/RW..."
                                            class="bg-neutral-light border-[3px] border-primary px-3 py-2.5 font-body text-xs font-bold text-primary focus:outline-none focus:border-secondary transition-colors resize-none"
                                            :class="errors['address'] ? 'border-red-600 bg-red-50' : ''"
                                            @blur="validateField($el)"
                                            @input="if (errors['address']) validateField($el)">{{ old('address') }}</textarea>
                                        <x-ui.error name="address" />
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <x-ui.button type="button" @click="validateStep(1)" class="px-12 py-3 text-sm">
                                    LANJUTKAN
                                </x-ui.button>
                            </div>
                        </div>

                        {{-- [ STEP 2: REKENING BANK ] --}}
                        <div x-show="step === 2" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-4"
                            x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                            <div class="bg-neutral-light p-6 border-l-4 border-primary mb-8">
                                <p class="text-xs font-bold text-slate-600 leading-relaxed">PENTING: Pastikan rekening
                                    atas nama Anda sendiri sesuai KTP. Data ini mutlak digunakan untuk pencairan bagi
                                    hasil penjualan.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="md:col-span-2">
                                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1">
                                        Informasi Rekening</p>
                                    <div class="h-1 w-12 bg-secondary"></div>
                                </div>
                                <div class="md:col-span-2">
                                    <x-ui.input id="bank_account_name" name="bank_account_name"
                                        label="ATAS NAMA REKENING" placeholder="Contoh: BUDI SANTOSO" class="uppercase"
                                        required value="{{ old('bank_account_name') }}" />
                                </div>
                                {{-- BACKEND-TODO: Ganti options ini dengan data dari API bank (misal: /api/banks) agar bisa load dinamis --}}
                                <div class="flex flex-col gap-1.5 relative">
                                    <label class="text-[9px] font-bold text-primary uppercase tracking-widest" for="bank_name">NAMA BANK</label>
                                    <div class="relative">
                                        <select id="bank_name" name="bank_name" required
                                            class="appearance-none w-full bg-neutral-light border-[3px] border-primary px-3 py-2.5 font-body text-xs font-bold text-primary focus:outline-none focus:border-secondary transition-colors cursor-pointer"
                                            :class="errors['bank_name'] ? 'border-red-600 bg-red-50' : ''"
                                            @change="validateField($el)">
                                            <option value="">PILIH BANK</option>
                                            @if(old('bank_name'))
                                                <option value="{{ old('bank_name') }}" selected>{{ old('bank_name') }}</option>
                                            @endif
                                            <optgroup label="Bank BUMN">
                                                <option value="BRI">BRI — Bank Rakyat Indonesia</option>
                                                <option value="BNI">BNI — Bank Negara Indonesia</option>
                                                <option value="Mandiri">Bank Mandiri</option>
                                                <option value="BTN">BTN — Bank Tabungan Negara</option>
                                                <option value="BSI">BSI — Bank Syariah Indonesia</option>
                                            </optgroup>
                                            <optgroup label="Bank Swasta Nasional">
                                                <option value="BCA">BCA — Bank Central Asia</option>
                                                <option value="CIMB Niaga">CIMB Niaga</option>
                                                <option value="Danamon">Bank Danamon</option>
                                                <option value="Permata">Bank Permata</option>
                                                <option value="Maybank">Maybank Indonesia</option>
                                                <option value="OCBC NISP">OCBC NISP</option>
                                                <option value="Panin">Panin Bank</option>
                                                <option value="Commonwealth">Commonwealth Bank</option>
                                                <option value="Sinarmas">Bank Sinarmas</option>
                                                <option value="Mega">Bank Mega</option>
                                            </optgroup>
                                            <optgroup label="Bank Digital">
                                                <option value="SeaBank">SeaBank</option>
                                                <option value="Jago">Bank Jago</option>
                                                <option value="Jenius">Jenius (BTPN)</option>
                                                <option value="Blu BCA">Blu by BCA</option>
                                                <option value="Neobank">Neo Commerce</option>
                                                <option value="Allo Bank">Allo Bank</option>
                                            </optgroup>
                                            <optgroup label="Bank Daerah">
                                                <option value="BPD Jabar">BPD Jawa Barat (BJB)</option>
                                                <option value="BPD Jateng">BPD Jawa Tengah</option>
                                                <option value="BPD Jatim">BPD Jawa Timur</option>
                                                <option value="BPD DIY">BPD DIY</option>
                                                <option value="BPD Bali">BPD Bali</option>
                                            </optgroup>
                                        </select>
                                        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-primary">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                    <x-ui.error name="bank_name" />
                                </div>
                                <x-ui.input id="bank_account_number" name="bank_account_number" type="text"
                                    inputmode="numeric" label="NOMOR REKENING" placeholder="Masukkan angka saja"
                                    required value="{{ old('bank_account_number') }}" />
                            </div>

                            <div class="mt-12 flex flex-col sm:flex-row justify-between gap-4">
                                <button type="button" @click="step = 1"
                                    class="font-headline font-bold text-[10px] uppercase tracking-widest text-slate-400 hover:text-primary transition-colors">
                                    &larr; Kembali ke Data Diri
                                </button>
                                <x-ui.button type="button" @click="validateStep(2)" class="px-16 py-4 text-base">
                                    LANJUTKAN
                                </x-ui.button>
                            </div>
                        </div>

                        {{-- [ STEP 3: VERIFIKASI AKUN ] --}}
                        <div x-show="step === 3" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-4"
                            x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="md:col-span-2">
                                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1">
                                        Keamanan Akses</p>
                                    <div class="h-1 w-12 bg-secondary"></div>
                                </div>
                                <div class="md:col-span-2">
                                    <x-ui.input id="username" name="username" label="USERNAME AKSES"
                                        placeholder="Gunakan huruf kecil & angka" required value="{{ old('username') }}" />
                                </div>
                                <x-ui.input id="password" name="password" type="password" label="PASSWORD"
                                    placeholder="Minimal 8 karakter" required minlength="8" />
                                <x-ui.input id="password_confirmation" name="password_confirmation" type="password"
                                    label="KONFIRMASI PASSWORD" placeholder="Ulangi password Anda" required
                                    minlength="8" />

                                <div class="md:col-span-2">
                                    <x-ui.input id="referral_code" name="referral_code" label="KODE REFERRAL (OPSIONAL)"
                                        placeholder="Kode dari Distributor Wilayah" class="!border-secondary" value="{{ old('referral_code') }}" />
                                </div>
                            </div>

                            <div class="mt-10 bg-gray-900 text-white p-6 border-l-4 border-secondary shadow-lg">
                                <p
                                    class="text-secondary font-headline font-black text-xs tracking-widest uppercase mb-2">
                                    ⚠️ ATURAN AKTIVASI</p>
                                <p class="text-[11px] font-bold leading-relaxed opacity-90">
                                    Setelah menekan tombol daftar, akun Anda akan masuk status 'TUNGGU'. Anda wajib
                                    melakukan order komitmen awal (Activation Order) di halaman berikutnya dalam waktu
                                    <span class="text-secondary">48 JAM</span> atau pendaftaran Anda akan dibatalkan
                                    otomatis oleh sistem.
                                </p>
                            </div>

                            <div class="mt-12 flex flex-col sm:flex-row justify-between gap-4">
                                <button type="button" @click="step = 2"
                                    class="font-headline font-bold text-[10px] uppercase tracking-widest text-slate-400 hover:text-primary transition-colors">
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const provinceSelect = document.getElementById('province_id');
            const citySelect = document.getElementById('city_id');
            const districtSelect = document.getElementById('district_id');

            // Load Provinces
            fetch('/api/provinces')
                .then(response => response.json())
                .then(data => {
                    provinceSelect.innerHTML = '<option value="">PILIH PROVINSI</option>';
                    data.forEach(province => {
                        provinceSelect.innerHTML += `<option value="${province.id}">${province.name}</option>`;
                    });
                })
                .catch(error => console.error('Error loading provinces:', error));

            // Province Change -> Load Cities
            provinceSelect.addEventListener('change', function () {
                const provinceId = this.value;
                citySelect.innerHTML = '<option value="">LOADING...</option>';
                districtSelect.innerHTML = '<option value="">PILIH KECAMATAN</option>';

                if (!provinceId) {
                    citySelect.innerHTML = '<option value="">PILIH KOTA</option>';
                    return;
                }

                fetch(`/api/cities?province_id=${provinceId}`)
                    .then(response => response.json())
                    .then(data => {
                        citySelect.innerHTML = '<option value="">PILIH KOTA</option>';
                        data.forEach(city => {
                            citySelect.innerHTML += `<option value="${city.id}">${city.name}</option>`;
                        });
                    })
                    .catch(error => console.error('Error loading cities:', error));
            });

            // City Change -> Load Districts
            citySelect.addEventListener('change', function () {
                const cityId = this.value;
                districtSelect.innerHTML = '<option value="">LOADING...</option>';

                if (!cityId) {
                    districtSelect.innerHTML = '<option value="">PILIH KECAMATAN</option>';
                    return;
                }

                fetch(`/api/districts?city_id=${cityId}`)
                    .then(response => response.json())
                    .then(data => {
                        districtSelect.innerHTML = '<option value="">PILIH KECAMATAN</option>';
                        data.forEach(district => {
                            districtSelect.innerHTML += `<option value="${district.id}">${district.name}</option>`;
                        });
                    })
                    .catch(error => console.error('Error loading districts:', error));
            });

            // KTP Preview
            const ktpInput = document.getElementById('ktp_photo');
            const ktpPlaceholder = document.getElementById('ktp_placeholder');
            const ktpPreview = document.getElementById('ktp_preview');
            const ktpPreviewImg = ktpPreview.querySelector('img');

            ktpInput.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        ktpPreviewImg.src = e.target.result;
                        ktpPreview.classList.remove('hidden');
                        ktpPlaceholder.classList.add('hidden');
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
</body>

</html>