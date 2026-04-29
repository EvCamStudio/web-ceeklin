<x-layouts.dashboard bgTheme="light">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">MASTER ADMIN</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>PEMETAAN WILAYAH</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.admin._menu')</x-slot:menuSlot>

    <div class="max-w-[1400px] mx-auto w-full" x-data="{
        viewMode: 'list',
        selectedReseller: null,
        targetDistributor: '',
        openMigration(reseller) {
            this.selectedReseller = reseller;
            this.targetDistributor = '';
            this.viewMode = 'detail';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        goBack() {
            this.viewMode = 'list';
            this.selectedReseller = null;
        }
    }">
        {{-- VIEW LIST: Tabel Peta --}}
        <div x-show="viewMode === 'list'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            {{-- Header Data --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h2 class="font-headline font-black text-2xl text-primary tracking-tighter uppercase">Peta Alokasi Reseller</h2>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-1">Kelola Pengalihan Darurat & Migrasi</p>
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <select class="w-full sm:w-auto appearance-none bg-white border-[3px] border-gray-900 px-4 py-2 text-xs font-bold uppercase tracking-widest text-primary focus:outline-none focus:border-secondary cursor-pointer shadow-[3px_3px_0_var(--color-gray-900)]">
                    <option>Filter: Stok Kritis</option>
                    <option>Filter: Semua Distributor</option>
                </select>
            </div>
        </div>

        {{-- Grid Distributor --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
            
            {{-- Distributor Card 1 (Stok Habis / Kritis) --}}
            <div class="bg-white border-[4px] border-red-600 shadow-[8px_8px_0_var(--color-red-600)]">
                <div class="bg-red-600 px-6 py-3 flex justify-between items-center">
                    <span class="font-headline font-black text-white text-base uppercase tracking-tight">PT. Maju Logistik</span>
                    <span class="bg-white text-red-600 px-2 py-0.5 text-[9px] font-bold uppercase tracking-widest border border-red-800">STOK HABIS</span>
                </div>
                <div class="p-6 bg-red-50/50 border-b-2 border-red-200">
                    <p class="text-[10px] font-bold text-red-700 uppercase tracking-widest mb-1">Status Gudang</p>
                    <div class="flex items-center gap-4">
                        <h3 class="font-headline font-black text-3xl text-red-600 tracking-tighter">0 <span class="text-lg text-red-400">PCS</span></h3>
                        <p class="text-xs font-bold text-red-600">Butuh pengalihan reseller segera!</p>
                    </div>
                </div>
                
                <div class="p-4 bg-white">
                    <p class="text-[10px] font-bold text-gray-900 uppercase tracking-widest mb-3">Daftar Reseller Terikat (3)</p>
                    
                    <div class="divide-y-2 divide-neutral-border">
                        @php
                            $resellersMaju = [
                                ['id' => 101, 'name' => 'Ahmad Fauzi', 'city' => 'Bandung', 'status' => 'Menunggu Aktivasi'],
                                ['id' => 102, 'name' => 'Toko Sinar Jaya', 'city' => 'Cimahi', 'status' => 'Aktif'],
                                ['id' => 103, 'name' => 'Budi Subroto', 'city' => 'Bandung', 'status' => 'Aktif'],
                            ];
                        @endphp

                        @foreach($resellersMaju as $reseller)
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-3 gap-3 hover:bg-neutral-light transition-colors px-2">
                            <div>
                                <p class="font-bold text-sm text-gray-900 uppercase">{{ $reseller['name'] }}</p>
                                <p class="text-[10px] font-bold text-slate-500 uppercase mt-0.5">{{ $reseller['city'] }} — {{ $reseller['status'] }}</p>
                            </div>
                            <button @click.prevent="openMigration({{ json_encode($reseller) }})"
                                class="bg-gray-900 text-white px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest border-2 border-gray-900 hover:bg-secondary transition-colors shrink-0">
                                Alihkan
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Distributor Card 2 (Aman) --}}
            <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">
                <div class="bg-primary px-6 py-3 flex justify-between items-center">
                    <span class="font-headline font-black text-white text-base uppercase tracking-tight">Teknik Karya Supply</span>
                    <span class="bg-secondary text-gray-900 px-2 py-0.5 text-[9px] font-bold uppercase tracking-widest">STOK AMAN</span>
                </div>
                <div class="p-6 bg-neutral-light border-b-2 border-neutral-border">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Status Gudang</p>
                    <div class="flex items-center gap-4">
                        <h3 class="font-headline font-black text-3xl text-primary tracking-tighter">1.250 <span class="text-lg text-slate-400">PCS</span></h3>
                        <p class="text-xs font-bold text-green-600">Siap menerima pelimpahan</p>
                    </div>
                </div>
                
                <div class="p-4 bg-white">
                    <p class="text-[10px] font-bold text-gray-900 uppercase tracking-widest mb-3">Daftar Reseller Terikat (2)</p>
                    
                    <div class="divide-y-2 divide-neutral-border">
                        @php
                            $resellersKarya = [
                                ['id' => 104, 'name' => 'Citra Mandiri', 'city' => 'Bekasi', 'status' => 'Aktif'],
                                ['id' => 105, 'name' => 'Toko Barokah', 'city' => 'Bekasi', 'status' => 'Aktif'],
                            ];
                        @endphp

                        @foreach($resellersKarya as $reseller)
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-3 gap-3 hover:bg-neutral-light transition-colors px-2">
                            <div>
                                <p class="font-bold text-sm text-gray-900 uppercase">{{ $reseller['name'] }}</p>
                                <p class="text-[10px] font-bold text-slate-500 uppercase mt-0.5">{{ $reseller['city'] }} — {{ $reseller['status'] }}</p>
                            </div>
                            <button @click.prevent="openMigration({{ json_encode($reseller) }})"
                                class="bg-transparent text-gray-900 px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest border-2 border-gray-900 hover:bg-gray-900 hover:text-white transition-colors shrink-0">
                                Pindah
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

        </div>

        {{-- VIEW DETAIL: Form Migrasi (Inline) --}}
        <div x-show="viewMode === 'detail'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            
            {{-- Tombol Kembali & Header --}}
            <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
                <div>
                    <h2 class="font-headline font-black text-2xl text-primary tracking-tighter uppercase">Migrasi Reseller</h2>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1">Pengalihan Darurat</p>
                </div>
                <button @click="goBack()" class="flex items-center gap-2 bg-white text-gray-900 px-4 py-2 text-[10px] font-bold uppercase tracking-widest border-[3px] border-gray-900 hover:bg-neutral-light transition-colors shadow-[4px_4px_0_var(--color-gray-900)] active:translate-y-0.5 active:shadow-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Peta
                </button>
            </div>

            <div class="bg-white border-[6px] border-gray-900 shadow-[12px_12px_0_var(--color-secondary)] w-full max-w-2xl flex flex-col mx-auto">

                {{-- Modal Body --}}
                <form action="/dashboard/admin/mapping/migrate" method="POST">
                    @csrf
                    <input type="hidden" name="reseller_id" :value="selectedReseller?.id">
                    
                    <div class="p-6 md:p-8 bg-neutral flex flex-col gap-6">
                        
                        <div class="bg-white border-[3px] border-gray-900 p-4 border-l-[6px] border-l-secondary">
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1">Reseller Terpilih</p>
                            <p class="font-headline font-black text-lg text-gray-900 uppercase" x-text="selectedReseller?.name"></p>
                            <p class="text-xs font-bold text-primary mt-1 uppercase" x-text="selectedReseller?.city"></p>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-[10px] font-bold text-gray-900 uppercase tracking-widest">Pilih Distributor Tujuan</label>
                            <div class="relative">
                                <select name="distributor_id" x-model="targetDistributor" required
                                    class="w-full appearance-none bg-white border-[3px] border-gray-900 px-4 py-3 font-bold text-sm text-gray-900 focus:outline-none focus:border-primary cursor-pointer shadow-[4px_4px_0_var(--color-gray-900)]">
                                    <option value="" disabled selected>-- Pilih Distributor yang Tersedia --</option>
                                    {{-- BACKEND-TODO: Loop from Distributor where stock > 50 --}}
                                    <option value="2">Teknik Karya Supply (Bekasi) — Sisa: 1.250 pcs</option>
                                    <option value="3">Indo Cipta Chem (Cirebon) — Sisa: 800 pcs</option>
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Footer Form --}}
                    <div class="p-6 md:p-8 bg-white border-t-[4px] border-gray-900 flex gap-4">
                        <button type="button" @click="goBack()" class="w-1/3 bg-white text-gray-900 border-[3px] border-gray-900 px-4 py-4 font-headline font-bold text-xs uppercase tracking-widest hover:bg-neutral-light transition-colors">
                            BATAL
                        </button>
                        <button type="submit" :disabled="!targetDistributor" 
                            class="w-2/3 bg-primary text-white border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] px-4 py-4 font-headline font-black text-xs uppercase tracking-widest hover:bg-primary-hover disabled:opacity-50 disabled:cursor-not-allowed transition-all flex justify-center items-center gap-2">
                            PROSES MIGRASI
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
