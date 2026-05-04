<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">RESELLER TEROTORISASI</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>PENGATURAN</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.reseller._menu')</x-slot:menuSlot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-5xl">

        {{-- Profil Reseller --}}
        <x-ui.card shadow="primary" headerColor="primary" class="lg:col-span-2">
            <x-slot:titleSlot><span class="italic">Profil Reseller</span></x-slot:titleSlot>
            
            <form action="{{ route('reseller.settings.profile') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 italic">
                    <x-ui.input id="name" name="name" label="Nama Lengkap" value="{{ old('name', $user->name) }}" />
                    
                    {{-- NIK tidak bisa diubah, hanya Admin --}}
                    <x-ui.input id="nik" label="No. NIK (Locked)" value="{{ $user->nik ?? 'N/A' }}" disabled />
                    
                    <x-ui.input id="phone" name="phone" type="tel" label="No. HP (WhatsApp)" value="{{ old('phone', $user->phone) }}" />
                    
                    <x-ui.input id="ahli_waris" name="ahli_waris" label="Ahli Waris" value="{{ old('ahli_waris', $user->ahli_waris ?? 'N/A') }}" />

                    <div class="flex flex-col gap-1.5 md:col-span-2">
                        <label class="text-[10px] font-bold text-primary uppercase tracking-widest italic" for="address">Alamat Lengkap</label>
                        <textarea id="address" name="address" rows="3"
                            class="bg-neutral-light border-[3px] border-primary px-4 py-2.5 font-body text-sm text-primary focus:outline-none focus:border-secondary transition-colors resize-none italic">{{ old('address', $user->address) }}</textarea>
                    </div>
                </div>
                
                <x-ui.button class="mt-8 italic" type="submit">
                    Simpan Perubahan
                </x-ui.button>
            </form>
        </x-ui.card>

        {{-- Panel Kanan --}}
        <div class="flex flex-col gap-6">

            {{-- Ganti Kata Sandi --}}
            <x-ui.card shadow="gray" headerColor="gray">
                <x-slot:titleSlot><span class="italic">Kata Sandi</span></x-slot:titleSlot>
                
                <form action="{{ route('reseller.settings.password') }}" method="POST" class="flex flex-col gap-4 italic">
                    @csrf
                    <x-ui.input id="current_password" name="current_password" type="password" label="Sandi Lama" placeholder="••••••••" class="!border-gray-900" />
                    <x-ui.input id="new_password" name="new_password" type="password" label="Sandi Baru" placeholder="Min. 8 karakter" class="!border-gray-900" />
                    
                    <x-ui.button variant="neutral" fullWidth="true" class="mt-1 shadow-[3px_3px_0_var(--color-primary-darkest)] italic" type="submit">
                        Perbarui Sandi
                    </x-ui.button>
                </form>
            </x-ui.card>

            {{-- Info Rekening --}}
            <x-ui.card shadow="secondary" headerColor="secondary">
                <x-slot:titleSlot><span class="italic">Info Rekening</span></x-slot:titleSlot>
                
                <form action="{{ route('reseller.settings.bank') }}" method="POST" class="flex flex-col gap-4 italic">
                    @csrf
                    <x-ui.input id="bank_name" name="bank_name" label="Nama Bank" value="{{ old('bank_name', $user->bank_name) }}" class="!border-secondary" />
                    <x-ui.input id="bank_account_number" name="bank_account_number" label="Nomor Rekening" value="{{ old('bank_account_number', $user->bank_account_number) }}" class="!border-secondary" />
                    <x-ui.input id="bank_account_name" name="bank_account_name" label="Atas Nama" value="{{ old('bank_account_name', $user->bank_account_name) }}" class="!border-secondary" />
                    
                    <x-ui.button variant="secondary" fullWidth="true" class="mt-1 shadow-[3px_3px_0_var(--color-gray-900)] italic" type="submit">
                        Perbarui Rekening
                    </x-ui.button>
                </form>
            </x-ui.card>
        </div>
    </div>
    </div>
</x-layouts.dashboard>
