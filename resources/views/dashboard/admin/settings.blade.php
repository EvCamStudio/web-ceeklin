<x-layouts.dashboard bgTheme="light">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">MASTER ADMIN</span>
    </x-slot:subtitleSlot>

    <x-slot:topbarTitle>PENGATURAN</x-slot:topbarTitle>

    <x-slot:menuSlot>
        @include('dashboard.admin._menu')
    </x-slot:menuSlot>

    {{-- Layout: dua kartu pengaturan berdampingan --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 max-w-4xl">

        {{-- Profil Admin --}}
        <div class="bg-white border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-primary-darkest)]">
            <div class="bg-primary px-6 py-3">
                <span class="font-headline font-black text-white text-base uppercase tracking-tight">Profil Admin</span>
            </div>
            <div class="p-6 flex flex-col gap-5">
                {{-- BACKEND-TODO: populate dari Auth::user() --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-bold text-secondary uppercase tracking-widest" for="nama-admin">Nama Lengkap</label>
                    <input id="nama-admin" type="text" value="Super Administrator"
                        class="bg-neutral-light border-[3px] border-primary px-4 py-2.5 font-body text-sm font-bold text-primary focus:outline-none focus:border-secondary transition-colors">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-bold text-secondary uppercase tracking-widest" for="username-admin">Username</label>
                    <input id="username-admin" type="text" value="admin"
                        class="bg-neutral-light border-[3px] border-primary px-4 py-2.5 font-body text-sm font-bold text-primary focus:outline-none focus:border-secondary transition-colors">
                </div>
                <button type="button" aria-label="Simpan perubahan profil"
                    class="w-full bg-primary text-white py-3 font-headline font-bold text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-0.5 active:shadow-none transition-all mt-2">
                    Simpan Perubahan
                </button>
            </div>
        </div>

        {{-- Ganti Password --}}
        <div class="bg-white border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)]">
            <div class="bg-gray-900 px-6 py-3">
                <span class="font-headline font-black text-white text-base uppercase tracking-tight">Ganti Kata Sandi</span>
            </div>
            <div class="p-6 flex flex-col gap-5">
                {{-- BACKEND-TODO: action ke AdminController@updatePassword + validasi --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-bold text-secondary uppercase tracking-widest" for="sandi-lama">Kata Sandi Lama</label>
                    <input id="sandi-lama" type="password" placeholder="••••••••"
                        class="bg-neutral-light border-[3px] border-gray-900 px-4 py-2.5 font-body text-sm font-bold focus:outline-none focus:border-primary transition-colors placeholder:text-gray-400">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-bold text-secondary uppercase tracking-widest" for="sandi-baru">Kata Sandi Baru</label>
                    <input id="sandi-baru" type="password" placeholder="••••••••"
                        class="bg-neutral-light border-[3px] border-gray-900 px-4 py-2.5 font-body text-sm font-bold focus:outline-none focus:border-primary transition-colors placeholder:text-gray-400">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-bold text-secondary uppercase tracking-widest" for="konfirmasi-sandi">Konfirmasi Kata Sandi</label>
                    <input id="konfirmasi-sandi" type="password" placeholder="••••••••"
                        class="bg-neutral-light border-[3px] border-gray-900 px-4 py-2.5 font-body text-sm font-bold focus:outline-none focus:border-primary transition-colors placeholder:text-gray-400">
                </div>
                <button type="button" aria-label="Perbarui kata sandi akun"
                    class="w-full bg-gray-900 text-white py-3 font-headline font-bold text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-primary-darkest)] hover:bg-gray-700 active:translate-y-0.5 active:shadow-none transition-all mt-2">
                    Perbarui Kata Sandi
                </button>
            </div>
        </div>

    </div>
</x-layouts.dashboard>
