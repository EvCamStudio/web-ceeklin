<x-layouts.dashboard bgTheme="light">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">MASTER ADMIN</span>
    </x-slot:subtitleSlot>

    <x-slot:topbarTitle>PENGATURAN SISTEM & KONTEN</x-slot:topbarTitle>

    <x-slot:menuSlot>
        @include('dashboard.admin._menu')
    </x-slot:menuSlot>

    <div class="max-w-6xl mx-auto" x-data="{ 
        tab: 'account',
        maintenanceMode: false,
        showSuccess: false,
        saveChanges() {
            this.showSuccess = true;
            setTimeout(() => this.showSuccess = false, 3000);
        }
    }">
        {{-- Navigation Tabs --}}
        <div class="flex flex-wrap gap-4 mb-10 border-b-4 border-gray-900 pb-6 italic">
            <button @click="tab = 'account'" :class="tab === 'account' ? 'bg-primary text-white shadow-[4px_4px_0_var(--color-secondary)]' : 'bg-white text-gray-400 hover:text-primary'" class="px-6 py-3 border-[3px] border-gray-900 font-headline font-black text-xs uppercase tracking-widest transition-all italic">
                🔐 Akun & Keamanan
            </button>
            <button @click="tab = 'landing'" :class="tab === 'landing' ? 'bg-primary text-white shadow-[4px_4px_0_var(--color-secondary)]' : 'bg-white text-gray-400 hover:text-primary'" class="px-6 py-3 border-[3px] border-gray-900 font-headline font-black text-xs uppercase tracking-widest transition-all italic">
                🖼️ Landing Page
            </button>
            <button @click="tab = 'system'" :class="tab === 'system' ? 'bg-primary text-white shadow-[4px_4px_0_var(--color-secondary)]' : 'bg-white text-gray-400 hover:text-primary'" class="px-6 py-3 border-[3px] border-gray-900 font-headline font-black text-xs uppercase tracking-widest transition-all italic">
                📱 Sosmed & Sistem
            </button>
        </div>

        {{-- TAB 1: ACCOUNT & SECURITY (Real Data) --}}
        <div x-show="tab === 'account'" x-transition class="grid grid-cols-1 lg:grid-cols-2 gap-8 italic">
            <div class="bg-white border-[4px] border-gray-900 p-8 shadow-[10px_10px_0_var(--color-primary-darkest)] italic">
                <h3 class="font-headline font-black text-xl text-primary uppercase tracking-tight mb-6 italic">Profil Administrator</h3>
                <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6 italic">
                    @csrf
                    <div class="flex flex-col gap-1.5 italic">
                        <label class="text-[10px] font-black text-primary uppercase tracking-widest italic">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ Auth::user()->name }}" class="bg-neutral-light border-[3px] border-gray-900 px-4 py-3 font-bold text-sm text-gray-900 focus:outline-none focus:border-secondary italic">
                    </div>
                    <div class="flex flex-col gap-1.5 italic">
                        <label class="text-[10px] font-black text-primary uppercase tracking-widest italic">Username</label>
                        <input type="text" name="username" value="{{ Auth::user()->username }}" class="bg-neutral-light border-[3px] border-gray-900 px-4 py-3 font-bold text-sm text-gray-900 focus:outline-none focus:border-secondary italic">
                    </div>
                    <div class="flex flex-col gap-1.5 italic">
                        <label class="text-[10px] font-black text-primary uppercase tracking-widest italic">Password Baru (Opsional)</label>
                        <input type="password" name="password" placeholder="••••••••" class="bg-neutral-light border-[3px] border-gray-900 px-4 py-3 font-bold text-sm text-gray-900 focus:outline-none focus:border-secondary italic">
                    </div>
                    <button type="submit" class="w-full bg-primary text-white py-4 font-headline font-black text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-primary-hover transition-all active:translate-y-1 active:shadow-none italic">
                        SIMPAN PERUBAHAN PROFIL
                    </button>
                </form>
            </div>

            <div class="bg-neutral-light border-[4px] border-gray-900 p-8 shadow-[10px_10px_0_var(--color-gray-900)] italic">
                <h3 class="font-headline font-black text-xl text-primary uppercase tracking-tight mb-6 italic">Informasi Sistem</h3>
                <div class="space-y-4 italic">
                    <div class="flex justify-between border-b border-gray-300 pb-2 italic">
                        <span class="text-[10px] font-bold text-slate-400 uppercase italic">Level Akses</span>
                        <span class="bg-primary text-white px-2 py-0.5 text-[9px] font-black uppercase italic">Master Admin</span>
                    </div>
                    <div class="flex justify-between border-b border-gray-300 pb-2 italic">
                        <span class="text-[10px] font-bold text-slate-400 uppercase italic">Terakhir Login</span>
                        <span class="text-[10px] font-black text-gray-900 italic">{{ now()->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB 2: LANDING PAGE (Dummy/WIP) --}}
        <div x-show="tab === 'landing'" x-transition style="display: none;" class="italic">
            <div class="bg-white border-[4px] border-gray-900 p-8 shadow-[10px_10px_0_var(--color-primary-darkest)] italic">
                <h3 class="font-headline font-black text-xl text-primary uppercase tracking-tight mb-6 italic">Konten Landing Page</h3>
                <p class="text-sm font-bold text-slate-500 italic uppercase">Fitur manajemen konten visual sedang dikembangkan.</p>
            </div>
        </div>

        {{-- TAB 3: SYSTEM --}}
        <div x-show="tab === 'system'" x-transition style="display: none;" class="italic">
            <div class="bg-gray-900 p-8 shadow-[10px_10px_0_var(--color-secondary)] italic">
                <h3 class="font-headline font-black text-xl text-secondary uppercase tracking-tight mb-6 italic">Mode Pemeliharaan</h3>
                <div class="flex items-center justify-between p-6 bg-white/10 border-2 border-dashed border-white/20 italic">
                    <div>
                        <p class="text-xs font-black text-white uppercase mb-1 italic">Maintenance Mode</p>
                        <p class="text-[9px] font-bold text-white/50 uppercase leading-none italic" x-text="maintenanceMode ? 'Situs Sedang Ditutup' : 'Situs Sedang Aktif'"></p>
                    </div>
                    <button @click="maintenanceMode = !maintenanceMode" class="w-14 h-8 flex items-center bg-gray-600 rounded-full p-1 transition-colors duration-300 italic" :class="maintenanceMode ? 'bg-secondary' : 'bg-gray-600'">
                        <div class="bg-white w-6 h-6 rounded-full shadow-md transform transition-transform duration-300 italic" :class="maintenanceMode ? 'translate-x-6' : ''"></div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
