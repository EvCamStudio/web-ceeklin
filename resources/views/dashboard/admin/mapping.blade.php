<x-layouts.dashboard bgTheme="light">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">MASTER ADMIN</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>PEMETAAN WILAYAH</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.admin._menu')</x-slot:menuSlot>

    <div class="max-w-[1400px] mx-auto w-full" x-data="{
        viewMode: 'list',
        activeTab: 'priority',
        selectedReseller: null,
        targetDistributorId: '',
        targetDistributorName: '',
        oldDistributorName: '',
        migrationSuccess: false,
        distributors: {{ $distributors->toJson() }},
        notified: {
            reseller: false,
            newDist: false,
            oldDist: false
        },
        openMigration(reseller, currentDistName) {
            this.selectedReseller = reseller;
            this.oldDistributorName = currentDistName;
            this.targetDistributorId = '';
            this.targetDistributorName = '';
            this.migrationSuccess = false;
            this.notified = { reseller: false, newDist: false, oldDist: false };
            this.viewMode = 'detail';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        goBack() {
            if (this.migrationSuccess) {
                window.location.reload();
            } else {
                this.viewMode = 'list';
                this.selectedReseller = null;
            }
        },
        submitMigration() {
            this.$refs.migrateForm.submit();
        },
        getWaLink(type) {
            const resName = this.selectedReseller?.name ?? '';
            const newDist = this.targetDistributorName;
            const oldDist = this.oldDistributorName;
            let msg = '';

            if (type === 'reseller') {
                msg = `Halo ${resName}, pengalihan wilayah Anda ke ${newDist} telah berhasil diproses. Pesanan Anda selanjutnya akan ditangani oleh distributor tersebut. Terima kasih!`;
            } else if (type === 'newDist') {
                msg = `Halo ${newDist}, ada reseller baru (${resName}) yang dialihkan ke wilayah Anda. Mohon dibantu untuk koordinasi stok dan pengirimannya.`;
            } else if (type === 'oldDist') {
                msg = `Halo ${oldDist}, diinfokan bahwa Reseller ${resName} telah kami alihkan ke ${newDist} (Penyesuaian Alokasi Wilayah). Terima kasih atas kerjasamanya selama ini.`;
            }
            
            return 'https://wa.me/6281234567890?text=' + encodeURIComponent(msg);
        }
    }">
        {{-- VIEW LIST: Tabel Peta --}}
        <div x-show="viewMode === 'list'" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">

            {{-- Header & Tab Switcher --}}
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-10 gap-6">
                <div>
                    <h2 class="font-headline font-black text-3xl text-primary tracking-tighter uppercase leading-none">
                        Manajemen Alokasi</h2>
                    <div class="flex flex-wrap gap-4 mt-6">
                        <button @click="activeTab = 'priority'"
                            class="px-5 py-2.5 text-[10px] font-headline font-black uppercase tracking-widest border-[3px] transition-all"
                            :class="activeTab === 'priority' ? 'bg-red-600 text-white border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)]' : 'bg-white text-gray-400 border-gray-200 hover:border-gray-900 hover:text-gray-900'">
                            Darurat (Stok 0)
                            <span
                                class="ml-2 px-1.5 py-0.5 bg-white text-red-600 text-[8px] font-black border border-red-600">{{ $priorityResellers->count() }}</span>
                        </button>
                        <button @click="activeTab = 'optimize'"
                            class="px-5 py-2.5 text-[10px] font-headline font-black uppercase tracking-widest border-[3px] transition-all"
                            :class="activeTab === 'optimize' ? 'bg-amber-500 text-white border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)]' : 'bg-white text-gray-400 border-gray-200 hover:border-gray-900 hover:text-gray-900'">
                            Optimalisasi Wilayah
                            <span
                                class="ml-2 px-1.5 py-0.5 bg-white text-amber-600 text-[8px] font-black border border-amber-600">{{ $optimizeResellers->count() }}</span>
                        </button>
                        <button @click="activeTab = 'all'"
                            class="px-5 py-2.5 text-[10px] font-headline font-black uppercase tracking-widest border-[3px] transition-all"
                            :class="activeTab === 'all' ? 'bg-primary text-white border-gray-900 shadow-[4px_4px_0_var(--color-secondary)]' : 'bg-white text-gray-400 border-gray-200 hover:border-gray-900 hover:text-gray-900'">
                            Semua Mitra
                        </button>
                    </div>
                </div>
            </div>

            {{-- Content: Priority Tab (Emergency) --}}
            <div x-show="activeTab === 'priority'" x-transition class="space-y-6">
                <div class="bg-red-50 border-[3px] border-red-200 p-4 flex items-center gap-4 mb-6">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="text-[10px] font-bold text-red-800 uppercase tracking-widest">Reseller di bawah memiliki
                        Distributor yang stoknya HABIS. Alihkan ke Distributor lain segera!</p>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    @forelse($priorityResellers as $reseller)
                        <div
                            class="bg-white border-[3px] border-gray-900 shadow-[6px_6px_0_var(--color-red-600)] p-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 hover:bg-red-50 transition-colors">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 bg-red-600 flex items-center justify-center border-2 border-gray-900 text-white font-headline font-black">
                                    !</div>
                                <div>
                                    <h4 class="font-headline font-black text-lg text-gray-900 uppercase tracking-tight">
                                        {{ $reseller['name'] }}</h4>
                                    <div class="flex gap-2 items-center">
                                        <p class="text-[10px] font-bold text-red-600 uppercase tracking-widest">Gudang:
                                            {{ $reseller['old_dist'] }}</p>
                                        <span
                                            class="text-[8px] bg-red-100 text-red-700 px-1.5 py-0.5 font-black uppercase">{{ $reseller['dist_city'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-6 w-full md:w-auto">
                                <div class="text-right hidden lg:block">
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Wilayah
                                        Reseller</p>
                                    <p class="text-xs font-bold text-gray-900 uppercase">{{ $reseller['city'] }}</p>
                                </div>
                                <button
                                    @click.prevent="openMigration({{ json_encode($reseller) }}, '{{ $reseller['old_dist'] }}')"
                                    class="w-full md:w-auto bg-red-600 text-white px-6 py-2.5 text-[10px] font-headline font-black uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-gray-900 transition-all active:translate-y-1 active:shadow-none">
                                    CARI PENGGANTI
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="py-20 text-center">
                            <p class="font-headline font-bold text-slate-300 text-xl uppercase italic">Tidak ada darurat
                                stok saat ini</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Content: Optimize Tab (Repatriation) --}}
            <div x-show="activeTab === 'optimize'" x-transition style="display: none;">
                <div class="bg-amber-50 border-[3px] border-amber-200 p-4 flex items-center gap-4 mb-6">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <p class="text-[10px] font-bold text-amber-800 uppercase tracking-widest">Reseller ini dilayani oleh
                        Distributor LUAR KOTA. Sekarang sudah ada Distributor LOKAL di wilayah mereka.</p>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    @forelse($optimizeResellers as $reseller)
                        <div
                            class="bg-white border-[3px] border-gray-900 shadow-[6px_6px_0_var(--color-amber-500)] p-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 hover:bg-amber-50 transition-colors">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 bg-amber-500 flex items-center justify-center border-2 border-gray-900 text-white font-headline font-black">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-headline font-black text-lg text-gray-900 uppercase tracking-tight">
                                        {{ $reseller['name'] }}</h4>
                                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                                        Wilayah: <span class="text-gray-900">{{ $reseller['city'] }}</span>
                                        <span class="mx-2 text-slate-300">|</span>
                                        Saat ini di: <span class="text-red-500">{{ $reseller['dist_city'] }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 w-full md:w-auto">
                                <span
                                    class="hidden lg:block bg-green-100 text-green-700 px-2 py-1 text-[9px] font-black uppercase">Distributor
                                    Lokal Tersedia ✓</span>
                                <button
                                    @click.prevent="openMigration({{ json_encode($reseller) }}, '{{ $reseller['old_dist'] }}')"
                                    class="w-full md:w-auto bg-primary text-white px-6 py-2.5 text-[10px] font-headline font-black uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-primary-hover transition-all active:translate-y-1 active:shadow-none">
                                    PULANGKAN KE LOKAL
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="py-20 text-center">
                            <p class="font-headline font-bold text-slate-300 text-xl uppercase italic">Tidak ada saran
                                optimalisasi wilayah</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Content: All Tab --}}
            <div x-show="activeTab === 'all'" x-transition style="display: none;">
                <div
                    class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)] overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-900 text-white">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-headline font-bold uppercase tracking-widest">
                                    Mitra / Reseller</th>
                                <th
                                    class="px-6 py-4 text-[10px] font-headline font-bold uppercase tracking-widest text-center">
                                    Wilayah Mitra</th>
                                <th class="px-6 py-4 text-[10px] font-headline font-bold uppercase tracking-widest">
                                    Distributor Aktif</th>
                                <th
                                    class="px-6 py-4 text-[10px] font-headline font-bold uppercase tracking-widest text-right">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-neutral-border">
                            @foreach($allResellers as $reseller)
                                <tr class="hover:bg-neutral-light transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-sm text-gray-900 uppercase">{{ $reseller['name'] }}</p>
                                        <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">ID:
                                            #{{ $reseller['id'] }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="bg-neutral-light border-2 border-gray-900 px-2 py-1 text-[9px] font-bold text-gray-900 uppercase">{{ $reseller['city'] }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-xs font-bold text-primary uppercase">{{ $reseller['old_dist'] }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span
                                                class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Lokasi:
                                                {{ $reseller['dist_city'] }}</span>
                                            <span class="text-[9px] text-slate-300">|</span>
                                            <span
                                                class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Stok:
                                                {{ number_format($reseller['stock']) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button
                                            @click.prevent="openMigration({{ json_encode($reseller) }}, '{{ $reseller['old_dist'] }}')"
                                            class="bg-primary text-white px-4 py-2 text-[10px] font-bold uppercase tracking-widest border-[3px] border-gray-900 shadow-[3px_3px_0_var(--color-gray-900)] hover:bg-primary-hover transition-all">
                                            PINDAHKAN
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- VIEW DETAIL: Form Migrasi (Inline) --}}
        <div x-show="viewMode === 'detail'" style="display: none;" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">

            {{-- Tombol Kembali & Header --}}
            <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
                <div>
                    <h2 class="font-headline font-black text-2xl text-primary tracking-tighter uppercase">Eksekusi
                        Perpindahan</h2>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1">Sistem Pemetaan
                        Wilayah CeeKlin</p>
                </div>
                <button @click="goBack()"
                    class="flex items-center gap-2 bg-white text-gray-900 px-4 py-2 text-[10px] font-bold uppercase tracking-widest border-[3px] border-gray-900 hover:bg-neutral-light transition-colors shadow-[4px_4px_0_var(--color-gray-900)] active:translate-y-0.5 active:shadow-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span x-text="migrationSuccess ? 'Tutup' : 'Batal & Kembali'"></span>
                </button>
            </div>

            <div
                class="bg-white border-[6px] border-gray-900 shadow-[12px_12px_0_var(--color-secondary)] w-full max-w-2xl flex flex-col mx-auto overflow-hidden">

                {{-- Form Migrasi --}}
                <div x-show="!migrationSuccess" x-transition>
                    <form x-ref="migrateForm" action="{{ route('admin.mapping.migrate') }}" method="POST"
                        @submit.prevent="submitMigration" novalidate>
                        @csrf
                        <input type="hidden" name="reseller_id" :value="selectedReseller?.id">

                        <div class="p-6 md:p-8 bg-neutral flex flex-col gap-6">

                            {{-- Info Reseller & Source --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div
                                    class="bg-white border-[3px] border-gray-900 p-4 shadow-[4px_4px_0_rgba(0,0,0,0.1)]">
                                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Pihak
                                        Reseller</p>
                                    <p class="font-headline font-black text-base text-gray-900 uppercase"
                                        x-text="selectedReseller?.name"></p>
                                    <p class="text-[10px] font-bold text-primary uppercase"
                                        x-text="selectedReseller?.city"></p>
                                </div>
                                <div
                                    class="bg-white border-[3px] border-gray-900 p-4 shadow-[4px_4px_0_rgba(0,0,0,0.1)]">
                                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">
                                        Distributor Saat Ini</p>
                                    <p class="font-headline font-black text-base text-red-600 uppercase"
                                        x-text="oldDistributorName"></p>
                                    <p class="text-[10px] font-bold text-slate-500 uppercase"
                                        x-text="selectedReseller?.dist_city"></p>
                                </div>
                            </div>

                            <div class="flex flex-col gap-2">
                                <label
                                    class="text-[10px] font-bold text-gray-900 uppercase tracking-widest flex items-center gap-2">
                                    Pilih Distributor Tujuan
                                    <span class="bg-primary text-white px-1.5 py-0.5 text-[8px] font-black">WAJIB</span>
                                </label>

                                {{-- Dropdown Berdasarkan Tab --}}
                                <div class="relative">
                                    <select name="distributor_id" x-model="targetDistributorId" required
                                        @change="targetDistributorName = $el.options[$el.selectedIndex].text.split(' [')[0]"
                                        class="w-full appearance-none bg-white border-[3px] border-gray-900 px-4 py-4 font-bold text-sm text-gray-900 focus:outline-none focus:border-primary cursor-pointer shadow-[6px_6px_0_var(--color-gray-900)]">

                                        <option value="" disabled selected>-- Pilih Distributor Baru --</option>

                                        {{-- Skenario 1 & 3: Emergency & Semua --}}
                                        <template x-if="activeTab === 'priority' || activeTab === 'all'">
                                            <optgroup label="REKOMENDASI TERDEKAT & STOK TERBANYAK">
                                                <template x-for="d in distributors" :key="d.id">
                                                    <option :value="d.id"
                                                        x-text="d.name + ' [' + d.city + '] — Stok: ' + d.stock">
                                                    </option>
                                                </template>
                                            </optgroup>
                                        </template>

                                        {{-- Skenario 2: Optimalisasi (ONLY same city/region) --}}
                                        <template x-if="activeTab === 'optimize'">
                                            <optgroup :label="'DISTRIBUTOR LOKAL DI ' + (selectedReseller?.city || '')">
                                                <template
                                                    x-for="d in distributors.filter(dist => dist.city_id === selectedReseller?.city_id)"
                                                    :key="d.id">
                                                    <option :value="d.id"
                                                        x-text="d.name + ' [' + d.city + '] — Stok: ' + d.stock">
                                                    </option>
                                                </template>
                                            </optgroup>
                                        </template>

                                    </select>
                                    <div
                                        class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-900">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                    <x-ui.error name="distributor_id" />
                                </div>

                                {{-- Hint Berdasarkan Tab --}}
                                <p x-show="activeTab === 'priority'"
                                    class="text-[9px] font-bold text-red-600 uppercase mt-1 tracking-tight">
                                    * Menampilkan rekomendasi distributor terdekat dengan stok mencukupi.
                                </p>
                                <p x-show="activeTab === 'optimize'"
                                    class="text-[9px] font-bold text-amber-600 uppercase mt-1 tracking-tight">
                                    * Hanya menampilkan distributor yang berada di kota yang sama dengan Reseller.
                                </p>
                            </div>

                        </div>

                        {{-- Footer Form --}}
                        <div class="p-6 md:p-8 bg-white border-t-[4px] border-gray-900 flex gap-4">
                            <button type="button" @click="goBack()"
                                class="w-1/3 bg-white text-gray-900 border-[3px] border-gray-900 px-4 py-4 font-headline font-bold text-xs uppercase tracking-widest hover:bg-neutral-light transition-colors">
                                BATAL
                            </button>
                            <button type="submit" :disabled="!targetDistributorId"
                                class="w-2/3 bg-primary text-white border-[3px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)] px-4 py-4 font-headline font-black text-xs uppercase tracking-widest hover:bg-primary-hover disabled:opacity-50 disabled:cursor-not-allowed transition-all flex justify-center items-center gap-2">
                                KONFIRMASI PINDAH
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Success Screen: Communication Hub --}}
                <div x-show="migrationSuccess" x-transition style="display:none;"
                    class="p-6 md:p-10 flex flex-col items-center text-center">
                    <div
                        class="w-16 h-16 bg-secondary flex items-center justify-center border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-primary-darkest)] mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>

                    <h3 class="font-headline font-black text-xl text-primary uppercase mb-2">Migrasi Selesai!</h3>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-8">Informasikan semua
                        pihak agar jalur distribusi kembali normal:</p>

                    <div class="w-full max-w-md flex flex-col gap-4">
                        <!-- Notify Reseller -->
                        <div class="flex items-center gap-4 bg-neutral-light border-[3px] border-gray-900 p-4 shadow-[4px_4px_0_rgba(0,0,0,0.05)] transition-all"
                            :class="notified.reseller ? 'bg-green-50 border-green-600 shadow-none translate-x-1 translate-y-1' : ''">
                            <div class="flex-1 text-left">
                                <p class="text-[9px] font-black uppercase tracking-widest mb-0.5"
                                    :class="notified.reseller ? 'text-green-700' : 'text-slate-400'">Kepada Reseller</p>
                                <p class="font-bold text-xs" x-text="selectedReseller?.name"></p>
                            </div>
                            <a :href="getWaLink('reseller')" target="_blank" @click="notified.reseller = true"
                                class="bg-[#25D366] text-white p-2 border-2 border-gray-900 shadow-[2px_2px_0_var(--color-gray-900)] hover:bg-[#1DA851] active:translate-y-0.5 active:shadow-none transition-all">
                                <template x-if="!notified.reseller">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.347-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                                    </svg>
                                </template>
                                <template x-if="notified.reseller">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </template>
                            </a>
                        </div>

                        <!-- Notify New Distributor -->
                        <div class="flex items-center gap-4 bg-neutral-light border-[3px] border-gray-900 p-4 shadow-[4px_4px_0_rgba(0,0,0,0.05)] transition-all"
                            :class="notified.newDist ? 'bg-green-50 border-green-600 shadow-none translate-x-1 translate-y-1' : ''">
                            <div class="flex-1 text-left">
                                <p class="text-[9px] font-black uppercase tracking-widest mb-0.5"
                                    :class="notified.newDist ? 'text-green-700' : 'text-slate-400'">Distributor Baru</p>
                                <p class="font-bold text-xs" x-text="targetDistributorName"></p>
                            </div>
                            <a :href="getWaLink('newDist')" target="_blank" @click="notified.newDist = true"
                                class="bg-[#25D366] text-white p-2 border-2 border-gray-900 shadow-[2px_2px_0_var(--color-gray-900)] hover:bg-[#1DA851] active:translate-y-0.5 active:shadow-none transition-all">
                                <template x-if="!notified.newDist">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.347-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                                    </svg>
                                </template>
                                <template x-if="notified.newDist">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </template>
                            </a>
                        </div>

                        <!-- Notify Old Distributor -->
                        <div class="flex items-center gap-4 bg-neutral-light border-[3px] border-gray-900 p-4 shadow-[4px_4px_0_rgba(0,0,0,0.05)] transition-all"
                            :class="notified.oldDist ? 'bg-green-50 border-green-600 shadow-none translate-x-1 translate-y-1' : ''">
                            <div class="flex-1 text-left">
                                <p class="text-[9px] font-black uppercase tracking-widest mb-0.5"
                                    :class="notified.oldDist ? 'text-green-700' : 'text-slate-400'">Distributor Lama</p>
                                <p class="font-bold text-xs" x-text="oldDistributorName"></p>
                            </div>
                            <a :href="getWaLink('oldDist')" target="_blank" @click="notified.oldDist = true"
                                class="bg-[#25D366] text-white p-2 border-2 border-gray-900 shadow-[2px_2px_0_var(--color-gray-900)] hover:bg-[#1DA851] active:translate-y-0.5 active:shadow-none transition-all">
                                <template x-if="!notified.oldDist">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.347-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                                    </svg>
                                </template>
                                <template x-if="notified.oldDist">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </template>
                            </a>
                        </div>

                        <button @click="goBack()"
                            class="mt-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest hover:text-primary transition-colors underline underline-offset-4">
                            Selesai & Kembali ke Daftar
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layouts.dashboard>