<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">PORTAL DISTRIBUTOR</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>JARINGAN RESELLER</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.distributor._menu')</x-slot:menuSlot>

    <div x-data="{ 
        viewMode: 'list', 
        selectedReseller: null,
        openDetail(reseller) {
            this.selectedReseller = reseller;
            this.viewMode = 'detail';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }" class="max-w-[1400px] mx-auto w-full">
        
        {{-- VIEW: LIST RESELLER --}}
        <div x-show="viewMode === 'list'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            {{-- Header & Title Section --}}
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-8 gap-6">
                <div>
                    <h2 class="font-headline font-black text-3xl text-primary tracking-tighter uppercase leading-none">Jaringan Reseller</h2>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-3 italic">Pantau & Kelola Aktivitas Reseller Anda</p>
                </div>
                
                {{-- Total Counter Widget --}}
                <div class="bg-white border-[3px] border-gray-900 px-6 py-4 shadow-[4px_4px_0_var(--color-primary-darkest)] flex flex-col items-center justify-center min-w-[120px]">
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Reseller</span>
                    <span class="font-headline font-black text-2xl text-primary leading-none">{{ $resellers->count() }}</span>
                </div>
            </div>

            {{-- Filter & Search Section --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div class="flex gap-4 flex-wrap w-full md:w-auto">
                    <div class="relative w-full md:w-80">
                        <input type="text" placeholder="Cari Reseller / Wilayah..." 
                            class="w-full bg-white border-[3px] border-gray-900 px-4 py-2 text-xs font-bold uppercase tracking-widest text-primary focus:outline-none focus:border-secondary shadow-[3px_3px_0_rgba(0,0,0,0.05)] placeholder:text-slate-300">
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                    </div>
                </div>
                <p class="hidden sm:block text-[9px] font-bold text-slate-400 uppercase tracking-widest italic">
                    ℹ️ Klik baris untuk melihat detail performa
                </p>
            </div>

            <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">
                {{-- Kolom Header --}}
                <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-4 bg-gray-900">
                    <div class="col-span-4 text-[10px] font-headline font-bold text-white uppercase tracking-widest">Nama Reseller</div>
                    <div class="col-span-3 text-[10px] font-headline font-bold text-white uppercase tracking-widest">Lokasi</div>
                    <div class="col-span-2 text-[10px] font-headline font-bold text-white uppercase tracking-widest">Kontak</div>
                    <div class="col-span-2 text-[10px] font-headline font-bold text-white uppercase tracking-widest text-center">Status</div>
                    <div class="col-span-1 text-[10px] font-headline font-bold text-white uppercase tracking-widest text-right">Ket.</div>
                </div>

                <div class="divide-y-2 divide-neutral-border">
                    @forelse($resellers as $reseller)
                    <div @click="openDetail({ name: '{{ $reseller->name }}', city: '{{ $reseller->city_name }}', phone: '{{ $reseller->phone }}', address: '{{ $reseller->address }}', status: '{{ $reseller->status }}' })"
                         class="flex flex-col md:grid md:grid-cols-12 gap-4 px-6 py-5 items-start md:items-center border-l-[5px] border-secondary hover:bg-neutral-light cursor-pointer transition-colors duration-150 group">
                        <div class="md:col-span-4 w-full">
                            <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nama Reseller</p>
                            <div class="font-bold text-sm text-gray-900 uppercase group-hover:text-primary transition-colors">{{ $reseller->name }}</div>
                        </div>
                        <div class="md:col-span-3 w-full flex justify-between md:block">
                            <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest">Lokasi</p>
                            <div class="text-xs text-slate-500 font-bold uppercase tracking-widest">{{ $reseller->city_name }}</div>
                        </div>
                        <div class="md:col-span-2 w-full flex justify-between md:block">
                            <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest">Kontak</p>
                            <div class="text-xs text-slate-500 font-bold">{{ $reseller->phone }}</div>
                        </div>
                        <div class="md:col-span-2 w-full flex justify-between md:block md:text-center">
                            <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest">Status</p>
                            <span class="px-2 py-1 border-2 {{ $reseller->status === 'active' ? 'border-secondary text-secondary' : 'border-slate-300 text-slate-400' }} text-[9px] font-bold uppercase tracking-widest">
                                {{ $reseller->status === 'active' ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </div>
                        <div class="md:col-span-1 w-full flex justify-between md:block md:text-right">
                            <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest">Ket.</p>
                            <span class="text-[9px] font-bold text-slate-400">—</span>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-10 text-center">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Belum ada reseller terdaftar di jaringan Anda</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- VIEW: DETAIL PERFORMA RESELLER --}}
        <div x-show="viewMode === 'detail'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            {{-- Back Button & Header --}}
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-10 gap-6">
                <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                    <x-ui.back-button @click="viewMode = 'list'" />
                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="font-headline font-black text-3xl md:text-4xl text-primary tracking-tighter uppercase leading-none" x-text="selectedReseller?.name"></h2>
                        </div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-2">
                            <span x-text="selectedReseller?.city"></span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 w-full lg:w-auto">
                    <a :href="'https://wa.me/' + (selectedReseller?.phone ? selectedReseller.phone.replace(/[^0-9]/g, '') : '')" target="_blank" class="flex-1 lg:flex-none bg-[#25D366] text-white border-[3px] border-gray-900 px-6 py-3 text-[10px] font-black uppercase tracking-widest hover:bg-[#1DA851] shadow-[4px_4px_0_var(--color-gray-900)] active:shadow-none active:translate-y-1 transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.347-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        Hubungi WA
                    </a>
                </div>
            </div>

            {{-- Stats Grid (Static Placeholder for now) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white border-[4px] border-gray-900 p-6 shadow-[8px_8px_0_var(--color-primary)] flex flex-col justify-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Belanja</p>
                    <p class="font-headline font-black text-3xl text-gray-900">Rp 0</p>
                </div>
                <div class="bg-white border-[4px] border-gray-900 p-6 shadow-[8px_8px_0_var(--color-secondary)] flex flex-col justify-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Volume (PCS)</p>
                    <p class="font-headline font-black text-3xl text-gray-900 text-secondary">0</p>
                </div>
                <div class="bg-white border-[4px] border-gray-900 p-6 shadow-[8px_8px_0_var(--color-gray-900)] flex flex-col justify-center">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Frekuensi Order</p>
                    <p class="font-headline font-black text-3xl text-gray-900">0 <span class="text-sm">Kali</span></p>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-8">
                {{-- Info Detail & Alamat --}}
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">
                        <div class="bg-primary px-6 py-3 border-b-[4px] border-gray-900">
                            <h3 class="font-headline font-black text-white text-sm uppercase tracking-tight">Detail Profil Reseller</h3>
                        </div>
                        <div class="p-6 space-y-6 bg-white">
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Nomor WhatsApp</p>
                                <p class="text-sm font-bold text-gray-900" x-text="selectedReseller?.phone"></p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Alamat Lengkap</p>
                                <p class="text-sm font-bold text-gray-900 leading-relaxed uppercase" x-text="selectedReseller?.address"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
