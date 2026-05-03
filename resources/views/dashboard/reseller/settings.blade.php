<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">RESELLER TEROTORISASI</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>PENGATURAN</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.reseller._menu')</x-slot:menuSlot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-5xl">

        {{-- Profil Reseller --}}
        <div class="lg:col-span-2">
            <form action="{{ route('reseller.settings.profile') }}" method="POST">
                @csrf
                <x-ui.card shadow="primary" headerColor="primary">
                    <x-slot:titleSlot>Profil Reseller</x-slot:titleSlot>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-ui.input id="nama-lengkap" name="name" label="Nama Lengkap" value="{{ auth()->user()->name }}" required />
                        
                        {{-- NIK tidak bisa diubah, hanya Admin --}}
                        <x-ui.input id="nik-reseller" label="No. NIK" value="{{ auth()->user()->nik }}" disabled />
                        
                        <x-ui.input id="hp-reseller" name="phone" type="tel" label="No. HP (WhatsApp)" value="{{ auth()->user()->phone }}" required />
                        
                        <x-ui.input id="username-reseller" label="Username" value="{{ auth()->user()->username }}" disabled />

                        <div class="flex flex-col gap-1.5 md:col-span-2">
                            <label class="text-[10px] font-bold text-primary uppercase tracking-widest" for="alamat-reseller">Alamat</label>
                            <textarea id="alamat-reseller" name="address" rows="2" required
                                class="bg-neutral-light border-[3px] border-primary px-4 py-2.5 font-body text-sm text-primary focus:outline-none focus:border-secondary transition-colors resize-none">{{ auth()->user()->address }}</textarea>
                        </div>
                    </div>
                    
                    <x-ui.button type="submit" class="mt-6">
                        Simpan Perubahan
                    </x-ui.button>
                </x-ui.card>
            </form>
        </div>

        {{-- Panel Kanan --}}
        <div class="flex flex-col gap-6">

            {{-- Ganti Kata Sandi --}}
            <form action="{{ route('reseller.settings.password') }}" method="POST">
                @csrf
                <x-ui.card shadow="gray" headerColor="gray">
                    <x-slot:titleSlot>Kata Sandi</x-slot:titleSlot>
                    
                    <div class="flex flex-col gap-4">
                        <x-ui.input id="sandi-lama-res" name="current_password" type="password" label="Sandi Lama" placeholder="••••••••" class="!border-gray-900" required />
                        <x-ui.input id="sandi-baru-res" name="new_password" type="password" label="Sandi Baru" placeholder="Min. 8 karakter" class="!border-gray-900" required />
                        <x-ui.input id="konfirmasi-sandi-res" name="new_password_confirmation" type="password" label="Konfirmasi" placeholder="Ketik ulang" class="!border-gray-900" required />
                        
                        <x-ui.button type="submit" variant="neutral" fullWidth="true" class="mt-1 shadow-[3px_3px_0_var(--color-primary-darkest)]">
                            Perbarui Sandi
                        </x-ui.button>
                    </div>
                </x-ui.card>
            </form>

            {{-- Info Rekening --}}
            <form action="{{ route('reseller.settings.bank') }}" method="POST">
                @csrf
                <x-ui.card shadow="secondary" headerColor="secondary">
                    <x-slot:titleSlot>Info Rekening</x-slot:titleSlot>
                    
                    <div class="flex flex-col gap-4">
                        <x-ui.input id="bank-reseller" name="bank_name" label="Nama Bank" value="{{ auth()->user()->bank_name }}" class="!border-secondary" required />
                        <x-ui.input id="norek-reseller" name="bank_account_number" label="Nomor Rekening" value="{{ auth()->user()->bank_account_number }}" class="!border-secondary" required />
                        <x-ui.input id="atasnama-reseller" name="bank_account_name" label="Atas Nama" value="{{ auth()->user()->bank_account_name }}" class="!border-secondary" required />
                        
                        <x-ui.button type="submit" variant="secondary" fullWidth="true" class="mt-1 shadow-[3px_3px_0_var(--color-gray-900)]">
                            Perbarui Rekening
                        </x-ui.button>
                    </div>
                </x-ui.card>
            </form>
        </div>
    </div>
</x-layouts.dashboard>
