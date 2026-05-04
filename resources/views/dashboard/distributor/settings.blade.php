<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">PORTAL DISTRIBUTOR</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>PENGATURAN</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.distributor._menu')</x-slot:menuSlot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-5xl">

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
                    </div>
                    <button type="submit"
                        class="mt-8 bg-primary text-white px-8 py-3 font-headline font-bold text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-0.5 active:shadow-none transition-all italic">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        {{-- Panel Kanan: Password + Info Rekening --}}
        <div class="flex flex-col gap-6">

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
                        <button type="submit"
                            class="w-full bg-gray-900 text-white py-2.5 font-headline font-bold text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[3px_3px_0_var(--color-primary-darkest)] hover:bg-gray-700 active:translate-y-0.5 active:shadow-none transition-all mt-1 italic">
                            Perbarui Sandi
                        </button>
                    </form>
                </div>
            </div>

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
                        <x-ui.input id="bank_name" name="bank_name" label="Nama Bank" value="{{ old('bank_name', $user->bank_name) }}" placeholder="Contoh: BCA / Mandiri" class="italic" required />
                        <x-ui.input id="bank_account_number" name="bank_account_number" label="Nomor Rekening" value="{{ old('bank_account_number', $user->bank_account_number) }}" placeholder="1234567890" class="italic" required />
                        <x-ui.input id="bank_account_name" name="bank_account_name" label="Atas Nama" value="{{ old('bank_account_name', $user->bank_account_name) }}" placeholder="Nama sesuai buku tabungan" class="italic" required />
                        <button type="submit"
                            class="w-full bg-secondary text-white py-2.5 font-headline font-bold text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[3px_3px_0_var(--color-gray-900)] hover:bg-secondary-dark active:translate-y-0.5 active:shadow-none transition-all mt-1 italic">
                            Perbarui Rekening
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
