<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">PORTAL DISTRIBUTOR</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>PENGATURAN</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.distributor._menu')</x-slot:menuSlot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-6xl pb-12">

        {{-- Profil Perusahaan --}}
        <div class="lg:col-span-2 bg-white border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-primary-darkest)] h-fit">
            <div class="bg-primary px-6 py-3 border-b-[4px] border-gray-900">
                <span class="font-headline font-black text-white text-base uppercase tracking-tight italic">Profil Distributor</span>
            </div>
            <div class="p-6 italic" x-data="{ 
                errors: {},
                validate(e) {
                    this.errors = {};
                    if (!this.$el.name.value) this.errors.name = 'Nama wajib diisi';
                    if (!this.$el.email.value) this.errors.email = 'Email wajib diisi';
                    if (!this.$el.phone.value) this.errors.phone = 'No. Telepon wajib diisi';
                    if (!this.$el.address.value) this.errors.address = 'Alamat wajib diisi';
                    
                    if (Object.keys(this.errors).length > 0) {
                        e.preventDefault();
                        return false;
                    }
                }
            }">
                <form action="{{ route('distributor.settings.profile') }}" method="POST" @submit="validate" novalidate>
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-ui.input id="name" name="name" label="Nama Distributor / Perusahaan" value="{{ old('name', $user->name) }}" class="italic" required />
                        <x-ui.input id="email" name="email" type="email" label="Email" value="{{ old('email', $user->email) }}" class="italic" required />
                        <x-ui.input id="phone" name="phone" type="tel" label="No. Telepon / WhatsApp" value="{{ old('phone', $user->phone) }}" class="italic" required />
                        
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-bold text-primary uppercase tracking-widest italic" for="wilayah-dist">Wilayah Operasi (Locked)</label>
                            <input id="wilayah-dist" type="text" value="{{ $user->city_name ?? 'N/A' }}" disabled
                                class="bg-neutral-border-light border-[3px] border-neutral-border px-4 py-2.5 font-body text-sm text-slate-400 cursor-not-allowed italic">
                        </div>

                        <div class="md:col-span-2 relative">
                            <label class="text-[10px] font-bold text-primary uppercase tracking-widest italic" for="address">Alamat Gudang Lengkap</label>
                            <textarea id="address" name="address" rows="3"
                                class="w-full bg-neutral-light border-[3px] border-primary px-4 py-2.5 font-body text-sm text-primary focus:outline-none focus:border-secondary transition-colors resize-none italic"
                                :class="errors['address'] ? 'border-red-600 bg-red-50' : ''">{{ old('address', $user->address) }}</textarea>
                            <x-ui.error name="address" />
                        </div>

                        {{-- Geolocation Section --}}
                        <div class="md:col-span-2 mt-6 pt-6 border-t-2 border-dashed border-gray-100">
                            <div class="flex items-center gap-3 mb-4">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <h4 class="font-headline font-black text-gray-900 text-sm uppercase tracking-tight italic">Titik Koordinat Gudang (Maps)</h4>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                                <div class="space-y-4">
                                    <p class="text-[10px] font-bold text-slate-500 uppercase italic leading-relaxed mb-4">Tentukan koordinat gudang Anda untuk membantu kurir dan pemetaan distribusi nasional.</p>
                                    <div class="flex flex-col gap-4">
                                        <x-ui.input id="latitude" name="latitude" label="Latitude" placeholder="-6.xxxxxx" value="{{ old('latitude', $user->latitude ?? '') }}" class="italic" />
                                        <x-ui.input id="longitude" name="longitude" label="Longitude" placeholder="106.xxxxxx" value="{{ old('longitude', $user->longitude ?? '') }}" class="italic" />
                                    </div>
                                    <button type="button" onclick="alert('Mencari lokasi GPS...')" 
                                        class="w-full mt-2 bg-primary text-white px-6 py-4 font-headline font-black text-[10px] uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-all flex items-center justify-center gap-2 italic">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                        Gunakan Lokasi Saat Ini
                                    </button>
                                </div>
                                
                                {{-- Map Mockup --}}
                                <div class="relative group">
                                    <div class="aspect-video bg-neutral-light border-[3px] border-gray-900 shadow-[8px_8px_0_var(--color-gray-900)] flex items-center justify-center overflow-hidden">
                                        <div class="absolute inset-0 opacity-20 bg-[url('https://www.google.com/maps/vt/pb=!1m4!1m3!1i12!2i2368!3i1622!2m3!1e0!2sm!3i633010156!3m8!2sen!3sid!5e1105!12m4!1e68!2m2!1sset!2sRoadmap!4e0!5m1!1f0!2shv!4m2!1sh!100!2sh!23!4m1!1i148!6m1!1e1')] bg-cover"></div>
                                        <div class="relative z-10 text-center px-6">
                                            <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg animate-bounce">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                            </div>
                                            <p class="text-[9px] font-black text-gray-900 uppercase tracking-widest bg-white/80 px-2 py-1 inline-block border border-gray-900 italic">Map Integration Ready</p>
                                        </div>
                                    </div>
                                    <div class="absolute -top-3 -left-3 bg-primary text-white px-3 py-1 font-headline font-black text-[9px] uppercase italic border-2 border-gray-900 shadow-md">
                                        Live Preview
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-12">
                        <x-ui.button class="w-full sm:w-auto px-12 py-5 italic" type="submit">
                            Simpan Perubahan Profil
                        </x-ui.button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Panel Kanan --}}
        <div class="flex flex-col gap-8">

            {{-- Info Rekening --}}
            <div class="bg-white border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-secondary)]">
                <div class="bg-secondary px-6 py-3 border-b-[4px] border-gray-900">
                    <span class="font-headline font-black text-white text-sm uppercase tracking-tight italic">Info Rekening</span>
                </div>
                <div class="p-5 italic" x-data="{
                    errors: {},
                    validate(e) {
                        this.errors = {};
                        if (!this.$el.bank_name.value) this.errors.bank_name = 'Nama bank wajib diisi';
                        if (!this.$el.bank_account_number.value) this.errors.bank_account_number = 'Nomor rekening wajib diisi';
                        if (!this.$el.bank_account_name.value) this.errors.bank_account_name = 'Nama pemilik wajib diisi';
                        
                        if (Object.keys(this.errors).length > 0) {
                            e.preventDefault();
                            return false;
                        }
                    }
                }">
                    <form action="{{ route('distributor.settings.bank') }}" method="POST" class="flex flex-col gap-4" @submit="validate" novalidate>
                        @csrf
                        <div class="space-y-4">
                            <x-ui.input id="bank_name" name="bank_name" label="Nama Bank" value="{{ old('bank_name', $user->bank_name) }}" placeholder="Contoh: BCA / Mandiri" class="italic" required />
                            <x-ui.input id="bank_account_number" name="bank_account_number" label="Nomor Rekening" value="{{ old('bank_account_number', $user->bank_account_number) }}" placeholder="1234567890" class="italic" required />
                            <x-ui.input id="bank_account_name" name="bank_account_name" label="Atas Nama" value="{{ old('bank_account_name', $user->bank_account_name) }}" placeholder="Nama sesuai buku tabungan" class="italic" required />
                            
                            <div class="pt-4 border-t-2 border-dashed border-gray-100">
                                <x-ui.input id="bank_password_confirm" name="current_password" type="password" label="Konfirmasi Sandi Akun" placeholder="Masukkan sandi saat ini" class="italic" required />
                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1 italic leading-relaxed">Dibutuhkan untuk memverifikasi perubahan rekening.</p>
                            </div>
                        </div>
                        <button type="submit"
                            class="w-full bg-secondary text-white py-2.5 font-headline font-bold text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[3px_3px_0_var(--color-gray-900)] hover:bg-secondary-dark active:translate-y-0.5 active:shadow-none transition-all mt-1 italic">
                            Perbarui Info Rekening
                        </button>
                    </form>
                </div>
            </div>

            {{-- Ganti Kata Sandi --}}
            <div class="bg-white border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)]">
                <div class="bg-gray-900 px-6 py-3 border-b-[4px] border-gray-900">
                    <span class="font-headline font-black text-white text-sm uppercase tracking-tight italic">Kata Sandi</span>
                </div>
                <div class="p-5 italic" x-data="{
                    errors: {},
                    validate(e) {
                        this.errors = {};
                        if (!this.$el.current_password.value) this.errors.current_password = 'Sandi lama wajib diisi';
                        if (!this.$el.new_password.value) this.errors.new_password = 'Sandi baru wajib diisi';
                        else if (this.$el.new_password.value.length < 8) this.errors.new_password = 'Minimal 8 karakter';
                        
                        if (this.$el.new_password.value !== this.$el.new_password_confirmation.value) {
                            this.errors.new_password_confirmation = 'Konfirmasi sandi tidak cocok';
                        }
                        
                        if (Object.keys(this.errors).length > 0) {
                            e.preventDefault();
                            return false;
                        }
                    }
                }">
                    <form action="{{ route('distributor.settings.password') }}" method="POST" class="flex flex-col gap-4" @submit="validate" novalidate>
                        @csrf
                        <x-ui.input id="current_password" name="current_password" type="password" label="Sandi Lama" placeholder="••••••••" class="italic" required />
                        <x-ui.input id="new_password" name="new_password" type="password" label="Sandi Baru" placeholder="Min. 8 karakter" class="italic" required />
                        <x-ui.input id="new_password_confirmation" name="new_password_confirmation" type="password" label="Ulangi Sandi Baru" placeholder="Ketik ulang sandi baru" class="italic" required />
                        <button type="submit"
                            class="w-full bg-primary text-white py-2.5 font-headline font-bold text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[3px_3px_0_var(--color-primary-darkest)] hover:bg-primary-hover active:translate-y-0.5 active:shadow-none transition-all mt-1 italic">
                            Perbarui Kata Sandi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
