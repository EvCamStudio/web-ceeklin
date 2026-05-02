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
        <div class="flex flex-wrap gap-4 mb-10 border-b-4 border-gray-900 pb-6">
            <button @click="tab = 'account'" :class="tab === 'account' ? 'bg-primary text-white shadow-[4px_4px_0_var(--color-secondary)]' : 'bg-white text-gray-400 hover:text-primary'" class="px-6 py-3 border-[3px] border-gray-900 font-headline font-black text-xs uppercase tracking-widest transition-all">
                🔐 Akun & Keamanan
            </button>
            <button @click="tab = 'landing'" :class="tab === 'landing' ? 'bg-primary text-white shadow-[4px_4px_0_var(--color-secondary)]' : 'bg-white text-gray-400 hover:text-primary'" class="px-6 py-3 border-[3px] border-gray-900 font-headline font-black text-xs uppercase tracking-widest transition-all">
                🖼️ Landing Page
            </button>
            <button @click="tab = 'gallery'" :class="tab === 'gallery' ? 'bg-primary text-white shadow-[4px_4px_0_var(--color-secondary)]' : 'bg-white text-gray-400 hover:text-primary'" class="px-6 py-3 border-[3px] border-gray-900 font-headline font-black text-xs uppercase tracking-widest transition-all">
                📸 Galeri Foto
            </button>
            <button @click="tab = 'system'" :class="tab === 'system' ? 'bg-primary text-white shadow-[4px_4px_0_var(--color-secondary)]' : 'bg-white text-gray-400 hover:text-primary'" class="px-6 py-3 border-[3px] border-gray-900 font-headline font-black text-xs uppercase tracking-widest transition-all">
                📱 Sosmed & Sistem
            </button>
        </div>

        {{-- Alert Success --}}
        <div x-show="showSuccess" x-transition x-cloak class="fixed top-8 left-1/2 -translate-x-1/2 z-[1000] bg-green-600 text-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-gray-900)] px-10 py-4 flex items-center gap-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
            <span class="font-headline font-black text-xs uppercase tracking-widest">Pembaruan Berhasil Disimpan!</span>
        </div>

        {{-- TAB 1: ACCOUNT & SECURITY --}}
        <div x-show="tab === 'account'" x-transition class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white border-[4px] border-gray-900 p-8 shadow-[10px_10px_0_var(--color-primary-darkest)]">
                <h3 class="font-headline font-black text-xl text-primary uppercase tracking-tight mb-6">Profil Administrator</h3>
                <div class="space-y-6">
                    <div class="flex items-center gap-6 mb-8">
                        <div class="relative group">
                            <div class="w-24 h-24 bg-neutral-light border-4 border-gray-900 shadow-[4px_4px_0_var(--color-secondary)] overflow-hidden">
                                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=admin" alt="Avatar" class="w-full h-full object-cover">
                            </div>
                            <button class="absolute -bottom-2 -right-2 bg-primary text-white p-2 border-2 border-gray-900 shadow-[2px_2px_0_var(--color-gray-900)] hover:scale-110 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </button>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Level Akses</p>
                            <span class="bg-primary text-white px-2 py-0.5 text-[10px] font-black uppercase tracking-widest">Master Admin</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[10px] font-black text-primary uppercase tracking-widest">Nama Lengkap</label>
                        <input type="text" value="Super Administrator" class="bg-neutral-light border-[3px] border-gray-900 px-4 py-3 font-bold text-sm text-gray-900 focus:outline-none focus:border-secondary">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[10px] font-black text-primary uppercase tracking-widest">Email Terdaftar</label>
                        <input type="email" value="admin@ceeklin.id" class="bg-neutral-light border-[3px] border-gray-900 px-4 py-3 font-bold text-sm text-gray-900 focus:outline-none focus:border-secondary">
                    </div>
                </div>
            </div>

            <div class="bg-neutral-light border-[4px] border-gray-900 p-8 shadow-[10px_10px_0_var(--color-gray-900)]">
                <h3 class="font-headline font-black text-xl text-primary uppercase tracking-tight mb-6">Kredensial Keamanan</h3>
                <div class="space-y-6">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[10px] font-black text-primary uppercase tracking-widest">Username Baru</label>
                        <input type="text" placeholder="Biarkan kosong jika tidak ganti" class="bg-white border-[3px] border-gray-900 px-4 py-3 font-bold text-sm text-gray-900 focus:outline-none focus:border-secondary">
                    </div>
                    <div class="h-[1px] bg-gray-300 w-full my-4"></div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[10px] font-black text-primary uppercase tracking-widest">Kata Sandi Lama</label>
                        <input type="password" placeholder="••••••••" class="bg-white border-[3px] border-gray-900 px-4 py-3 font-bold text-sm text-gray-900 focus:outline-none focus:border-secondary">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-black text-primary uppercase tracking-widest">Sandi Baru</label>
                            <input type="password" placeholder="Min. 8 Karakter" class="bg-white border-[3px] border-gray-900 px-4 py-3 font-bold text-sm text-gray-900 focus:outline-none focus:border-secondary">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-black text-primary uppercase tracking-widest">Konfirmasi Sandi</label>
                            <input type="password" placeholder="Ulangi Sandi" class="bg-white border-[3px] border-gray-900 px-4 py-3 font-bold text-sm text-gray-900 focus:outline-none focus:border-secondary">
                        </div>
                    </div>
                    <button @click="saveChanges()" class="w-full bg-gray-900 text-white py-4 font-headline font-black text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-primary)] hover:bg-primary transition-all">
                        PERBARUI AKSES AKUN
                    </button>
                </div>
            </div>
        </div>

        {{-- TAB 2: LANDING PAGE CONTENT --}}
        <div x-show="tab === 'landing'" x-transition style="display: none;">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left: Visual Content --}}
                <div class="lg:col-span-2 space-y-8">
                    {{-- Hero & Video --}}
                    <div class="bg-white border-[4px] border-gray-900 p-8 shadow-[8px_8px_0_var(--color-primary-darkest)]">
                        <h4 class="font-headline font-black text-lg text-primary uppercase tracking-tight mb-6">Hero & Media Utama</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-3">Gambar Background Hero</label>
                                <div class="aspect-video bg-neutral-light border-4 border-dashed border-gray-300 flex flex-col items-center justify-center p-4 relative group overflow-hidden">
                                    <svg class="w-10 h-10 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <input type="file" class="absolute inset-0 opacity-0 cursor-pointer">
                                </div>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-3">Video Profil Beranda (URL)</label>
                                <input type="text" placeholder="https://youtube.com/..." class="w-full bg-neutral-light border-[3px] border-gray-900 px-4 py-3 font-bold text-xs text-gray-900 focus:outline-none focus:border-secondary mb-4">
                                <div class="p-3 bg-primary/5 border border-primary/20 text-[9px] font-bold text-primary uppercase leading-tight italic">
                                    *Video akan muncul di section 'Bukti Visual'
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Marquee & FAQ --}}
                    <div class="bg-white border-[4px] border-gray-900 p-8 shadow-[8px_8px_0_var(--color-primary-darkest)]">
                        <h4 class="font-headline font-black text-lg text-primary uppercase tracking-tight mb-6">Informasi & Edukasi</h4>
                        <div class="space-y-6">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-3">Teks Berjalan (Sertifikasi/Pita Hitam)</label>
                                <textarea rows="2" class="w-full bg-neutral-light border-[3px] border-gray-900 px-4 py-3 font-bold text-xs text-gray-900 focus:outline-none focus:border-secondary">Tersertifikasi KEMENKES RI PKL 20303120614 | ISO 9001:2015 Compliant | Dipercaya 50+ Manufaktur Nasional</textarea>
                            </div>
                            <div class="h-[1px] bg-neutral-border w-full"></div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-4">FAQ (Pertanyaan Umum)</label>
                                <div class="space-y-4">
                                    <template x-for="i in 3" :key="i">
                                        <div class="p-4 bg-neutral-light border-2 border-gray-900">
                                            <input type="text" :placeholder="'Pertanyaan ' + i" class="w-full bg-transparent border-b border-gray-300 pb-2 mb-2 font-headline font-black text-[10px] uppercase tracking-widest text-primary focus:outline-none">
                                            <textarea rows="2" :placeholder="'Jawaban ' + i" class="w-full bg-transparent text-[10px] font-bold text-slate-600 focus:outline-none"></textarea>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right: Content Editor --}}
                <div class="space-y-8">
                    {{-- Bento Grid / Categories --}}
                    <div class="bg-white border-[4px] border-gray-900 p-8 shadow-[8px_8px_0_var(--color-secondary)]">
                        <h4 class="font-headline font-black text-sm text-primary uppercase tracking-tight mb-6">Daya Hancur (Bento Grid)</h4>
                        <div class="space-y-6">
                            @foreach(['Automotive', 'Kitchen', 'Fabric', 'Ceramic'] as $cat)
                                <div class="p-4 border-2 border-dashed border-gray-300">
                                    <p class="text-[8px] font-black text-slate-400 uppercase mb-2">Slot {{ $cat }}</p>
                                    <input type="text" value="{{ $cat === 'Automotive' ? 'BLOK MESIN & KENDARAAN' : ($cat === 'Kitchen' ? 'GELAS & ALAT DAPUR' : ($cat === 'Fabric' ? 'NODA PADA PAKAIAN' : 'KLOSET & WASTAFEL')) }}" class="w-full bg-neutral-light border-2 border-gray-900 px-3 py-2 text-[10px] font-black uppercase text-primary mb-2">
                                    <textarea class="w-full bg-neutral-light border-2 border-gray-900 px-3 py-2 text-[9px] font-bold text-slate-600 leading-tight">Deskripsi singkat mengenai keunggulan pembersihan di kategori ini...</textarea>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <button @click="saveChanges()" class="w-full bg-gray-900 text-white py-5 font-headline font-black text-sm uppercase tracking-widest border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary)] hover:bg-primary transition-all active:translate-y-1 active:shadow-none">
                        SIMPAN KONTEN BERANDA
                    </button>
                </div>
            </div>
        </div>

        {{-- TAB 3: PRODUCT GALLERY --}}
        <div x-show="tab === 'gallery'" x-transition style="display: none;">
            <div class="bg-white border-[4px] border-gray-900 p-8 shadow-[12px_12px_0_var(--color-primary-darkest)]">
                <div class="flex justify-between items-center mb-10 border-b-2 border-neutral-border pb-6">
                    <div>
                        <h4 class="font-headline font-black text-xl text-primary uppercase tracking-tight">Pusat Bukti Visual (Before & After)</h4>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Upload foto asli kondisi kotor dan kondisi bersih untuk hasil maksimal</p>
                    </div>
                    <button class="bg-secondary text-gray-900 px-6 py-3 font-headline font-black text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-yellow-400 active:translate-y-1 active:shadow-none transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                        TAMBAH SLOT BARU
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    @for($i=1; $i<=3; $i++)
                        <div class="bg-neutral-light border-2 border-gray-900 p-4 relative group">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-[9px] font-black text-primary uppercase">Slot Bukti #{{$i}}</span>
                                <button class="text-red-600 hover:scale-110 transition-transform">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                {{-- BEFORE UPLOAD --}}
                                <div class="space-y-2">
                                    <div class="aspect-square bg-white border-2 border-dashed border-gray-300 flex items-center justify-center relative overflow-hidden group/img">
                                        <p class="text-[7px] font-black text-slate-300 uppercase text-center px-2">Upload Foto<br>Kotor (Before)</p>
                                        <input type="file" class="absolute inset-0 opacity-0 cursor-pointer">
                                        {{-- Dummy Image --}}
                                        <img src="https://picsum.photos/seed/dirty{{$i}}/200/200" class="absolute inset-0 w-full h-full object-cover opacity-50 grayscale">
                                    </div>
                                    <p class="text-[8px] font-black text-gray-900 text-center uppercase bg-gray-200 py-1">KOTOR</p>
                                </div>
                                {{-- AFTER UPLOAD --}}
                                <div class="space-y-2">
                                    <div class="aspect-square bg-white border-2 border-dashed border-gray-300 flex items-center justify-center relative overflow-hidden group/img border-primary/30">
                                        <p class="text-[7px] font-black text-slate-300 uppercase text-center px-2">Upload Foto<br>Bersih (After)</p>
                                        <input type="file" class="absolute inset-0 opacity-0 cursor-pointer">
                                        {{-- Dummy Image --}}
                                        <img src="https://picsum.photos/seed/clean{{$i}}/200/200" class="absolute inset-0 w-full h-full object-cover">
                                    </div>
                                    <p class="text-[8px] font-black text-white text-center uppercase bg-primary py-1">BERSIH</p>
                                </div>
                            </div>
                            <input type="text" value="{{ $i === 1 ? 'Kerak Kloset Menahun' : ($i === 2 ? 'Karat Oli Blok Mesin' : 'Noda Gelas Kusam') }}" placeholder="Judul Bukti (Contoh: Kerak Kloset)" class="w-full bg-white border-2 border-gray-900 px-3 py-2 text-[10px] font-black uppercase text-primary focus:outline-none focus:border-secondary">
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        {{-- TAB 4: SYSTEM & SOCIAL --}}
        <div x-show="tab === 'system'" x-transition style="display: none;">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white border-[4px] border-gray-900 p-8 shadow-[10px_10px_0_var(--color-primary-darkest)]">
                    <h3 class="font-headline font-black text-xl text-primary uppercase tracking-tight mb-6">Informasi Kontak & Sosmed</h3>
                    <div class="space-y-6">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-black text-primary uppercase tracking-widest">WhatsApp CS (Format: 628xxx)</label>
                            <input type="text" value="6281234567890" class="bg-neutral-light border-[3px] border-gray-900 px-4 py-3 font-bold text-sm text-gray-900 focus:outline-none focus:border-secondary">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-black text-primary uppercase tracking-widest">Link Instagram</label>
                            <input type="text" value="https://instagram.com/ceeklin.id" class="bg-neutral-light border-[3px] border-gray-900 px-4 py-3 font-bold text-sm text-gray-900 focus:outline-none focus:border-secondary">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-black text-primary uppercase tracking-widest">Link Facebook</label>
                            <input type="text" value="https://facebook.com/ceeklin.official" class="bg-neutral-light border-[3px] border-gray-900 px-4 py-3 font-bold text-sm text-gray-900 focus:outline-none focus:border-secondary">
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-black text-primary uppercase tracking-widest">Alamat Kantor (Footer)</label>
                            <textarea rows="3" class="bg-neutral-light border-[3px] border-gray-900 px-4 py-3 font-bold text-sm text-gray-900 focus:outline-none focus:border-secondary">Jl. Soekarno Hatta No. 123, Kota Bandung, Jawa Barat</textarea>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-8">
                    <div class="bg-gray-900 p-8 shadow-[10px_10px_0_var(--color-secondary)]">
                        <h3 class="font-headline font-black text-xl text-secondary uppercase tracking-tight mb-6 italic">Mode Pemeliharaan</h3>
                        <div class="flex items-center justify-between p-6 bg-white/10 border-2 border-dashed border-white/20">
                            <div>
                                <p class="text-xs font-black text-white uppercase mb-1">Maintenance Mode</p>
                                <p class="text-[9px] font-bold text-white/50 uppercase leading-none" x-text="maintenanceMode ? 'Situs Sedang Ditutup' : 'Situs Sedang Aktif'"></p>
                            </div>
                            <button @click="maintenanceMode = !maintenanceMode" class="w-14 h-8 flex items-center bg-gray-600 rounded-full p-1 transition-colors duration-300" :class="maintenanceMode ? 'bg-secondary' : 'bg-gray-600'">
                                <div class="bg-white w-6 h-6 rounded-full shadow-md transform transition-transform duration-300" :class="maintenanceMode ? 'translate-x-6' : ''"></div>
                            </button>
                        </div>
                        <p class="text-[9px] font-bold text-white/40 uppercase mt-4 leading-relaxed">
                            *Jika diaktifkan, pengunjung landing page akan melihat halaman pemberitahuan maintenance. Dashboard admin tetap dapat diakses.
                        </p>
                    </div>

                    <div class="bg-white border-[4px] border-gray-900 p-8 shadow-[10px_10px_0_var(--color-primary-darkest)]">
                        <h3 class="font-headline font-black text-sm text-primary uppercase tracking-tight mb-4">Informasi Sistem</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between border-b border-neutral-border pb-2">
                                <span class="text-[9px] font-bold text-slate-400 uppercase">Versi Aplikasi</span>
                                <span class="text-[10px] font-black text-gray-900 uppercase">v1.2.0-MVP</span>
                            </div>
                            <div class="flex justify-between border-b border-neutral-border pb-2">
                                <span class="text-[9px] font-bold text-slate-400 uppercase">Framework</span>
                                <span class="text-[10px] font-black text-gray-900 uppercase">Laravel 11 / Tailwind</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[9px] font-bold text-slate-400 uppercase">Server Status</span>
                                <span class="text-[10px] font-black text-green-600 uppercase flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 bg-green-600 rounded-full animate-pulse"></span>
                                    Operational
                                </span>
                            </div>
                        </div>
                    </div>

                    <button @click="saveChanges()" class="w-full bg-primary text-white py-5 font-headline font-black text-sm uppercase tracking-widest border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-secondary)] hover:bg-primary-hover active:translate-y-1 active:shadow-none transition-all">
                        SIMPAN SEMUA PERUBAHAN
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
