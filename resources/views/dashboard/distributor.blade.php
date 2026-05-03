<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1 italic">PORTAL DISTRIBUTOR</span>
    </x-slot:subtitleSlot>

    <x-slot:topbarTitle>BERANDA</x-slot:topbarTitle>

    <x-slot:menuSlot>
        @include('dashboard.distributor._menu')
    </x-slot:menuSlot>

    {{-- ACTION CENTER (PRIORITY TASKS) --}}
    <div class="mb-8 italic">
        <div class="flex items-center gap-3 mb-4 italic">
            <span class="w-10 h-1 bg-primary italic"></span>
            <h2 class="font-headline font-black text-xl text-primary uppercase tracking-tight italic">Pusat Aksi (Urgensi)</h2>
            <span class="flex-1 h-[2px] bg-neutral-border italic"></span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 italic">
            {{-- Task: Pesanan Pending --}}
            <a href="{{ route('distributor.incoming-orders') }}" 
               class="bg-red-50 border-[4px] border-red-600 p-6 flex items-center justify-between group hover:bg-red-600 transition-all duration-300 shadow-[8px_8px_0_var(--color-gray-900)] italic">
                <div class="flex items-center gap-5 italic">
                    <div class="w-14 h-14 bg-red-600 flex items-center justify-center text-white group-hover:bg-white group-hover:text-red-600 transition-colors italic">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div class="italic">
                        <h4 class="font-headline font-black text-red-600 group-hover:text-white text-xl uppercase leading-none tracking-tighter italic">{{ $pendingOrdersCount }} Pesanan Masuk</h4>
                        <p class="text-[10px] font-bold text-red-400 group-hover:text-red-100 uppercase tracking-widest mt-1 italic">Butuh Konfirmasi Segera</p>
                    </div>
                </div>
                <div class="hidden sm:block italic">
                    <svg class="w-8 h-8 text-red-600 group-hover:text-white transition-transform group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 5l7 7-7 7"/></svg>
                </div>
            </a>

            {{-- Task: Stok --}}
            <a href="{{ route('distributor.inventory') }}" 
               class="bg-yellow-50 border-[4px] border-yellow-500 p-6 flex items-center justify-between group hover:bg-yellow-500 transition-all duration-300 shadow-[8px_8px_0_var(--color-gray-900)] italic">
                <div class="flex items-center gap-5 italic">
                    <div class="w-14 h-14 bg-yellow-500 flex items-center justify-center text-white group-hover:bg-white group-hover:text-yellow-500 transition-colors italic">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <div class="italic">
                        <h4 class="font-headline font-black text-yellow-600 group-hover:text-white text-xl uppercase leading-none tracking-tighter italic">Status Stok</h4>
                        <p class="text-[10px] font-bold text-yellow-600/60 group-hover:text-yellow-100 uppercase tracking-widest mt-1 italic">{{ number_format($user->stock) }} PCS Tersedia</p>
                    </div>
                </div>
                <div class="hidden sm:block italic">
                    <svg class="w-8 h-8 text-yellow-600 group-hover:text-white transition-transform group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 5l7 7-7 7"/></svg>
                </div>
            </a>
        </div>
    </div>

    {{-- KPI Cards (Real Data) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6 italic">
        {{-- Stok Saat Ini --}}
        <div class="bg-white border-[3px] border-primary shadow-[6px_6px_0_var(--color-primary-darkest)] p-6 italic">
            <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-1 italic">Stok Saat Ini</p>
            <h3 class="font-headline font-black text-4xl text-primary tracking-tighter italic">{{ number_format($user->stock, 0, ',', '.') }}</h3>
            <p class="text-xs text-slate-500 font-bold mt-2 uppercase tracking-widest flex items-center gap-1 italic">
                PCS Tersedia di Gudang
            </p>
        </div>

        {{-- Profit --}}
        <div class="bg-primary text-white border-[3px] border-gray-900 shadow-[6px_6px_0_var(--color-secondary)] p-6 italic">
            <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-1 italic">Estimasi Profit</p>
            <h3 class="font-headline font-black text-3xl text-white tracking-tighter italic italic">Rp {{ number_format($totalProfit ?? 0, 0, ',', '.') }}</h3>
            <p class="text-[9px] font-bold text-secondary uppercase tracking-widest mt-2 italic leading-tight italic">
                *Total Keuntungan Berjalan
            </p>
        </div>

        {{-- Reseller Aktif --}}
        <div class="bg-white border-[3px] border-secondary shadow-[6px_6px_0_var(--color-gray-900)] p-6 italic">
            <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-1 italic">Total Reseller</p>
            <h3 class="font-headline font-black text-4xl text-primary tracking-tighter italic">{{ $resellersCount }}</h3>
            <p class="text-xs text-green-600 font-bold mt-2 uppercase tracking-widest flex items-center gap-1 italic">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                Jaringan Terdaftar
            </p>
        </div>

        {{-- Pesanan Menunggu --}}
        <div class="bg-white border-[3px] border-primary shadow-[6px_6px_0_var(--color-primary-hover)] p-6 italic">
            <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-1 italic">Pesanan Masuk</p>
            <h3 class="font-headline font-black text-4xl text-primary tracking-tighter italic">{{ $pendingOrdersCount }}</h3>
            <p class="text-xs text-slate-500 font-bold mt-2 uppercase tracking-widest flex items-center gap-1 italic">
                Perlu Diproses Segera
            </p>
        </div>
    </div>

    {{-- Aksi Cepat + Pesanan Terakhir --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 italic">

        {{-- Aksi Cepat --}}
        <div class="flex flex-col gap-4 italic">
            <a href="{{ route('distributor.inventory') }}"
               class="bg-white border-[3px] border-primary shadow-[4px_4px_0_var(--color-primary-darkest)] p-5 flex items-center gap-4 hover:bg-neutral-light transition-colors duration-150 group italic">
                <div class="w-12 h-12 bg-primary/10 flex items-center justify-center flex-shrink-0 group-hover:bg-primary/20 transition-colors italic">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                </div>
                <div class="italic">
                    <h4 class="font-headline font-black text-primary uppercase text-sm italic">Cek Stok Gudang</h4>
                    <p class="text-xs text-slate-500 mt-0.5 italic">Pantau level stok & minta restock</p>
                </div>
            </a>
            <a href="{{ route('distributor.resellers') }}"
               class="bg-white border-[3px] border-secondary shadow-[4px_4px_0_var(--color-gray-900)] p-5 flex items-center gap-4 hover:bg-neutral-light transition-colors duration-150 group italic">
                <div class="w-12 h-12 bg-secondary/10 flex items-center justify-center flex-shrink-0 group-hover:bg-secondary/20 transition-colors italic">
                    <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
                <div class="italic">
                    <h4 class="font-headline font-black text-primary uppercase text-sm italic">Kelola Reseller</h4>
                    <p class="text-xs text-slate-500 mt-0.5 italic">Lihat & kelola jaringan reseller</p>
                </div>
            </a>
            <a href="{{ route('distributor.order') }}"
               class="bg-primary border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] p-5 flex items-center gap-4 hover:bg-primary-hover transition-colors duration-150 group italic">
                <div class="w-12 h-12 bg-white/10 flex items-center justify-center flex-shrink-0 italic">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div class="italic">
                    <h4 class="font-headline font-black text-white uppercase text-sm italic">Buat Pesanan Baru</h4>
                    <p class="text-xs text-white/60 mt-0.5 italic">Pesan stok langsung ke pabrik</p>
                </div>
            </a>
        </div>

        {{-- Pesanan Terakhir (Real Data) --}}
        <div class="xl:col-span-2 bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)] italic">
            <div class="bg-primary px-6 py-3 flex items-center justify-between italic">
                <span class="font-headline font-black text-white text-base uppercase tracking-tight italic">Pesanan Terakhir</span>
                <a href="{{ route('distributor.incoming-orders') }}" class="text-[10px] font-bold text-secondary hover:text-white uppercase tracking-widest transition-colors italic">Lihat Semua →</a>
            </div>
            <div class="divide-y-2 divide-neutral-border italic">
                @forelse($recentOrders as $order)
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between px-6 py-4 gap-4 italic">
                    <div class="flex-1 italic">
                        <p class="font-bold text-sm text-gray-900 uppercase italic">{{ $order->reseller->name }}</p>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mt-0.5 leading-tight italic">{{ number_format($order->quantity) }} pcs — CeeKlin 450ml</p>
                    </div>
                    <div class="flex items-center gap-3 italic">
                        <span class="px-2 py-1 border-2 text-[10px] font-bold uppercase tracking-widest italic {{ $order->status === 'Selesai' ? 'border-green-700 text-green-700' : 'border-primary text-primary' }}">
                            {{ $order->status }}
                        </span>
                        @if($order->status === 'Menunggu Konfirmasi' || $order->status === 'Menunggu')
                            <a href="{{ route('distributor.incoming-orders') }}" class="bg-primary text-white px-3 py-1 text-[10px] font-bold uppercase tracking-widest hover:bg-primary-hover italic">Kelola</a>
                        @endif
                    </div>
                </div>
                @empty
                <div class="px-6 py-10 text-center italic">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest italic">Belum ada pesanan masuk</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.dashboard>
