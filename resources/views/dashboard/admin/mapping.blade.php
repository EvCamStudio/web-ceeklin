<x-layouts.dashboard bgTheme="light">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">MASTER ADMIN</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>PEMETAAN WILAYAH</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.admin._menu')</x-slot:menuSlot>

    <div class="max-w-[1400px] mx-auto w-full" x-data="{
        viewMode: 'list',
        activeTab: 'all',
        selectedReseller: null,
        targetDistributorId: '',
        targetDistributorName: '',
        oldDistributorName: '',
        migrationSuccess: false,
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
            // BACKEND-TODO: Real AJAX call here
            this.migrationSuccess = true;
            window.scrollTo({ top: 0, behavior: 'smooth' });
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
            
            return 'https://wa.me/62' + (this.selectedReseller?.phone || 'xxxxxxxxxx') + '?text=' + encodeURIComponent(msg);
        }
    }">
        {{-- VIEW LIST: Tabel Peta --}}
        <div x-show="viewMode === 'list'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            
            {{-- Header & Tab Switcher --}}
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-10 gap-6">
                <div>
                    <h2 class="font-headline font-black text-3xl text-primary tracking-tighter uppercase leading-none italic">Manajemen Alokasi Wilayah</h2>
                    <div class="flex flex-wrap gap-4 mt-6">
                        <button @click="activeTab = 'all'" 
                            class="px-5 py-2.5 text-[10px] font-headline font-black uppercase tracking-widest border-[3px] transition-all"
                            :class="activeTab === 'all' ? 'bg-primary text-white border-gray-900 shadow-[4px_4px_0_var(--color-secondary)]' : 'bg-white text-gray-400 border-gray-200 hover:border-gray-900 hover:text-gray-900'">
                            👥 Semua Mitra
                        </button>
                    </div>
                </div>
            </div>

            {{-- Content: All Tab (Real Data) --}}
            <div x-show="activeTab === 'all'" x-transition>
                <div class="grid grid-cols-1 gap-8">
                    @forelse($distributors as $distributor)
                    {{-- Distributor Card --}}
                    <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">
                        <div class="bg-primary px-6 py-3 flex justify-between items-center italic">
                            <span class="font-headline font-black text-white text-base uppercase tracking-tight italic">{{ $distributor->name }}</span>
                            <span class="bg-secondary text-gray-900 px-2 py-0.5 text-[9px] font-bold uppercase tracking-widest italic">{{ $distributor->status === 'active' ? 'AKTIF' : 'NONAKTIF' }}</span>
                        </div>
                        <div class="p-6 bg-neutral-light border-b-2 border-neutral-border italic">
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1 italic">Informasi Wilayah</p>
                            <div class="flex items-center gap-4 italic">
                                <h3 class="font-headline font-black text-xl text-primary tracking-tighter italic">{{ $distributor->province_name ?? 'N/A' }}</h3>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest italic">{{ count($distributor->resellers) }} Reseller Terikat</p>
                            </div>
                        </div>
                        
                        <div class="p-0 bg-white">
                            <div class="divide-y-2 divide-neutral-border">
                                @foreach($distributor->resellers as $reseller)
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-4 gap-3 hover:bg-neutral-light transition-colors px-6 italic">
                                    <div>
                                        <p class="font-bold text-sm text-gray-900 uppercase italic">{{ $reseller->name }}</p>
                                        <p class="text-[10px] font-bold text-slate-500 uppercase mt-0.5 italic">{{ $reseller->city_name }} — {{ $reseller->status === 'active' ? 'Aktif' : 'Menunggu' }}</p>
                                    </div>
                                    <button @click.prevent="openMigration({{ json_encode(['id' => $reseller->id, 'name' => $reseller->name, 'city' => $reseller->city_name, 'phone' => $reseller->phone]) }}, '{{ $distributor->name }}')"
                                        class="bg-transparent text-gray-900 px-4 py-2 text-[10px] font-bold uppercase tracking-widest border-2 border-gray-900 hover:bg-gray-900 hover:text-white transition-colors shrink-0 italic">
                                        Alihkan
                                    </button>
                                </div>
                                @endforeach

                                @if(count($distributor->resellers) === 0)
                                <div class="py-10 text-center italic">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">Belum ada reseller terikat</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="py-20 text-center bg-white border-[4px] border-dashed border-slate-300 italic">
                        <p class="font-bold text-slate-400 uppercase tracking-widest italic">Belum ada distributor untuk dipetakan</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- VIEW DETAIL: Form Migrasi (Inline) --}}
        <div x-show="viewMode === 'detail'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            
            <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center italic">
                <div>
                    <h2 class="font-headline font-black text-2xl text-primary tracking-tighter uppercase italic">Eksekusi Perpindahan</h2>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1 italic">Sistem Pemetaan Wilayah CeeKlin</p>
                </div>
                <button @click="goBack()" class="flex items-center gap-2 bg-white text-gray-900 px-4 py-2 text-[10px] font-bold uppercase tracking-widest border-[3px] border-gray-900 hover:bg-neutral-light transition-colors shadow-[4px_4px_0_var(--color-gray-900)] active:translate-y-0.5 active:shadow-none italic">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span x-text="migrationSuccess ? 'Tutup' : 'Batal & Kembali'"></span>
                </button>
            </div>

            <div class="bg-white border-[6px] border-gray-900 shadow-[12px_12px_0_var(--color-secondary)] w-full max-w-2xl flex flex-col mx-auto overflow-hidden italic">
                
                {{-- Form Migrasi --}}
                <div x-show="!migrationSuccess" x-transition italic">
                    <form action="{{ route('admin.mapping.migrate') }}" method="POST">
                        @csrf
                        <input type="hidden" name="reseller_id" :value="selectedReseller?.id">
                        
                        <div class="p-6 md:p-8 bg-neutral flex flex-col gap-6 italic">
                            
                            {{-- Info Reseller & Source --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 italic">
                                <div class="bg-white border-[3px] border-gray-900 p-4 shadow-[4px_4px_0_rgba(0,0,0,0.1)] italic">
                                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1 italic">Pihak Reseller</p>
                                    <p class="font-headline font-black text-base text-gray-900 uppercase italic" x-text="selectedReseller?.name"></p>
                                    <p class="text-[10px] font-bold text-primary uppercase italic" x-text="selectedReseller?.city"></p>
                                </div>
                                <div class="bg-white border-[3px] border-gray-900 p-4 shadow-[4px_4px_0_rgba(0,0,0,0.1)] italic">
                                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1 italic">Distributor Saat Ini</p>
                                    <p class="font-headline font-black text-base text-red-600 uppercase italic" x-text="oldDistributorName"></p>
                                </div>
                            </div>

                            <div class="flex flex-col gap-2 italic">
                                <label class="text-[10px] font-bold text-gray-900 uppercase tracking-widest flex items-center gap-2 italic">
                                    Pilih Distributor Tujuan
                                    <span class="bg-primary text-white px-1.5 py-0.5 text-[8px] font-black italic">WAJIB</span>
                                </label>
                                
                                <div class="relative italic">
                                    <select name="distributor_id" x-model="targetDistributorId" required
                                        @change="targetDistributorName = $el.options[$el.selectedIndex].text"
                                        class="w-full appearance-none bg-white border-[3px] border-gray-900 px-4 py-4 font-bold text-sm text-gray-900 focus:outline-none focus:border-primary cursor-pointer shadow-[6px_6px_0_var(--color-gray-900)] italic">
                                        
                                        <option value="" disabled selected>-- Pilih Distributor Baru --</option>
                                        @foreach($distributors as $dist)
                                            <option value="{{ $dist->id }}">{{ $dist->name }} ({{ $dist->province_name }})</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-900">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" /></svg>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="p-6 md:p-8 bg-white border-t-[4px] border-gray-900 flex gap-4 italic">
                            <button type="button" @click="goBack()" class="w-1/3 bg-white text-gray-900 border-[3px] border-gray-900 px-4 py-4 font-headline font-bold text-xs uppercase tracking-widest hover:bg-neutral-light transition-colors italic">
                                BATAL
                            </button>
                            <button type="submit" :disabled="!targetDistributorId" 
                                class="w-2/3 bg-primary text-white border-[3px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)] px-4 py-4 font-headline font-black text-xs uppercase tracking-widest hover:bg-primary-hover disabled:opacity-50 disabled:cursor-not-allowed transition-all flex justify-center items-center gap-2 italic">
                                KONFIRMASI PINDAH
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Success Screen: Communication Hub --}}
                <div x-show="migrationSuccess" x-transition style="display:none;" class="p-6 md:p-10 flex flex-col items-center text-center italic">
                    <div class="w-16 h-16 bg-secondary flex items-center justify-center border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-primary-darkest)] mb-6 italic">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    
                    <h3 class="font-headline font-black text-xl text-primary uppercase mb-2 italic">Migrasi Selesai!</h3>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-8 italic">Informasikan semua pihak agar jalur distribusi kembali normal:</p>

                    <div class="w-full max-w-md flex flex-col gap-4 italic">
                        <div class="flex items-center gap-4 bg-neutral-light border-[3px] border-gray-900 p-4 shadow-[4px_4px_0_rgba(0,0,0,0.05)] transition-all italic">
                            <div class="flex-1 text-left italic">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5 italic">Kepada Reseller</p>
                                <p class="font-bold text-xs italic" x-text="selectedReseller?.name"></p>
                            </div>
                            <a :href="getWaLink('reseller')" target="_blank"
                               class="bg-[#25D366] text-white p-2 border-2 border-gray-900 shadow-[2px_2px_0_var(--color-gray-900)] hover:bg-[#1DA851] active:translate-y-0.5 active:shadow-none transition-all italic">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.347-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            </a>
                        </div>
                    </div>

                    <button @click="goBack()" class="mt-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest hover:text-primary transition-colors underline underline-offset-4 italic">
                        Selesai & Kembali ke Daftar
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
