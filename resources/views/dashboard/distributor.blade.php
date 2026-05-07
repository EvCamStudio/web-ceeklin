<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">PORTAL DISTRIBUTOR</span>
    </x-slot:subtitleSlot>

    <x-slot:topbarTitle>BERANDA</x-slot:topbarTitle>

    <x-slot:menuSlot>
        @include('dashboard.distributor._menu')
    </x-slot:menuSlot>

    {{-- ACTION CENTER (PRIORITY TASKS) --}}
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-4">
            <span class="w-10 h-1 bg-primary"></span>
            <h2 class="font-headline font-black text-xl text-primary uppercase tracking-tight italic">Pusat Aksi (Urgensi)</h2>
            <span class="flex-1 h-[2px] bg-neutral-border"></span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Task: Pesanan Pending --}}
            <a href="/dashboard/distributor/incoming-orders?status=Menunggu" 
               class="bg-red-50 border-[4px] border-red-600 p-6 flex items-center justify-between group hover:bg-red-600 transition-all duration-300 shadow-[8px_8px_0_var(--color-gray-900)]">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-red-600 flex items-center justify-center text-white group-hover:bg-white group-hover:text-red-600 transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-headline font-black text-red-600 group-hover:text-white text-xl uppercase leading-none tracking-tighter">{{ $pendingOrdersCount }} Pesanan Masuk</h4>
                        <p class="text-[10px] font-bold text-red-400 group-hover:text-red-100 uppercase tracking-widest mt-1">Belum diproses & dikemas</p>
                    </div>
                </div>
                <div class="hidden sm:block">
                    <svg class="w-8 h-8 text-red-600 group-hover:text-white transition-transform group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 5l7 7-7 7"/></svg>
                </div>
            </a>

            {{-- Task: Stok Menipis --}}
            <a href="/dashboard/distributor/inventory" 
               class="bg-yellow-50 border-[4px] border-yellow-500 p-6 flex items-center justify-between group hover:bg-yellow-500 transition-all duration-300 shadow-[8px_8px_0_var(--color-gray-900)]">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-yellow-500 flex items-center justify-center text-white group-hover:bg-white group-hover:text-yellow-500 transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-headline font-black text-yellow-600 group-hover:text-white text-xl uppercase leading-none tracking-tighter">Stok {{ $stockStatus }}</h4>
                        <p class="text-[10px] font-bold text-yellow-600/60 group-hover:text-yellow-100 uppercase tracking-widest mt-1">Sisa {{ number_format($user->stock) }} PCS (Tersedia)</p>
                    </div>
                </div>
                <div class="hidden sm:block">
                    <svg class="w-8 h-8 text-yellow-600 group-hover:text-white transition-transform group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 5l7 7-7 7"/></svg>
                </div>
            </a>
        </div>
    </div>

    {{-- 2. KPI CARDS (PERFORMANCE) --}}
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-4">
            <span class="w-10 h-1 bg-primary"></span>
            <h2 class="font-headline font-black text-xl text-primary uppercase tracking-tight italic">Ringkasan Performa</h2>
            <span class="flex-1 h-[2px] bg-neutral-border"></span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Volume Bulanan (Terjual) --}}
        <div class="bg-white border-[3px] border-primary shadow-[6px_6px_0_var(--color-primary-darkest)] p-6">
            <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-1 italic">Volume Terjual (MTD)</p>
            <h3 class="font-headline font-black text-4xl text-primary tracking-tighter italic">{{ number_format($monthlyVolume) }} <span class="text-xs font-body uppercase">PCS</span></h3>
            <p class="text-xs {{ $volumeGrowth >= 0 ? 'text-secondary' : 'text-red-500' }} font-bold mt-2 uppercase tracking-widest flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $volumeGrowth >= 0 ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6' }}" /></svg>
                {{ $volumeGrowth >= 0 ? '+' : '' }}{{ number_format($volumeGrowth, 1) }}% vs bulan lalu
            </p>
        </div>

        {{-- Estimasi Profit --}}
        <div class="bg-primary text-white border-[3px] border-gray-900 shadow-[6px_6px_0_var(--color-secondary)] p-6">
            <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-1 italic">Estimasi Profit (MTD)</p>
            <h3 class="font-headline font-black text-3xl text-white tracking-tighter italic">Rp {{ number_format($estimatedProfit / 1000000, 2) }} Juta</h3>
            <p class="text-[9px] font-bold text-secondary uppercase tracking-widest mt-2 italic leading-tight">
                *Dihitung dari margin Rp 2.000 / PCS
            </p>
        </div>

        {{-- Reseller Aktif --}}
        <div class="bg-white border-[3px] border-secondary shadow-[6px_6px_0_var(--color-gray-900)] p-6">
            <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-1 italic">Reseller Aktif</p>
            <h3 class="font-headline font-black text-4xl text-primary tracking-tighter italic">{{ $resellersCount }} <span class="text-lg text-slate-400">/ 20</span></h3>
            <p class="text-xs {{ $resellersCount >= 20 ? 'text-green-600' : 'text-slate-400' }} font-bold mt-2 uppercase tracking-widest flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                {{ $resellersCount >= 20 ? 'Target Minimum Tercapai' : 'Di bawah target minimum' }}
            </p>
        </div>

        {{-- Stok Tersedia --}}
        <div class="bg-white border-[3px] border-primary shadow-[6px_6px_0_var(--color-primary-hover)] p-6">
            <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-1 italic">Stok Siap Jual</p>
            <h3 class="font-headline font-black text-4xl text-primary tracking-tighter italic">{{ number_format($user->stock) }}</h3>
            <div class="mt-2">
                <div class="w-full bg-slate-100 h-1.5 border border-slate-200">
                    <div class="bg-secondary h-full" style="width: {{ $stockLevel }}%"></div>
                </div>
                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1 italic">Level Stok: {{ $stockStatus }} ({{ round($stockLevel) }}%)</p>
            </div>
        </div>
        </div>
    </div>

    {{-- Aksi Cepat + Pesanan Terakhir --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Aksi Cepat --}}
        <div class="flex flex-col gap-4">
            <div class="flex items-center gap-3 mb-2">
                <span class="w-8 h-1 bg-primary"></span>
                <h2 class="font-headline font-black text-lg text-primary uppercase tracking-tight italic">Navigasi Cepat</h2>
            </div>
            <a href="/dashboard/distributor/order"
               class="bg-primary border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] p-5 flex items-center gap-4 hover:bg-primary-hover transition-colors duration-150 group">
                <div class="w-12 h-12 bg-white/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <h4 class="font-headline font-black text-white uppercase text-sm italic">Buat Pesanan Baru</h4>
                    <p class="text-xs text-white/60 mt-0.5 uppercase tracking-tight font-bold italic">Pesan stok langsung ke pabrik</p>
                </div>
            </a>
            <a href="/dashboard/distributor/inventory"
               class="bg-white border-[3px] border-primary shadow-[4px_4px_0_var(--color-primary-darkest)] p-5 flex items-center gap-4 hover:bg-neutral-light transition-colors duration-150 group">
                <div class="w-12 h-12 bg-primary/10 flex items-center justify-center flex-shrink-0 group-hover:bg-primary/20 transition-colors">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                </div>
                <div>
                    <h4 class="font-headline font-black text-primary uppercase text-sm italic">Cek Stok Gudang</h4>
                    <p class="text-xs text-slate-500 mt-0.5 uppercase tracking-tight font-bold italic">Pantau level stok & minta restock</p>
                </div>
            </a>
            <a href="/dashboard/distributor/resellers"
               class="bg-white border-[3px] border-secondary shadow-[4px_4px_0_var(--color-gray-900)] p-5 flex items-center gap-4 hover:bg-neutral-light transition-colors duration-150 group">
                <div class="w-12 h-12 bg-secondary/10 flex items-center justify-center flex-shrink-0 group-hover:bg-secondary/20 transition-colors">
                    <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
                <div>
                    <h4 class="font-headline font-black text-primary uppercase text-sm italic">Kelola Reseller</h4>
                    <p class="text-xs text-slate-500 mt-0.5 uppercase tracking-tight font-bold italic">Lihat & kelola jaringan reseller</p>
                </div>
            </a>
        </div>

        {{-- Pesanan Terakhir --}}
        <div class="xl:col-span-2 bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">
            <div class="bg-primary px-6 py-3 flex items-center justify-between">
                <span class="font-headline font-black text-white text-base uppercase tracking-tight italic">Pesanan Terakhir</span>
                <a href="/dashboard/distributor/incoming-orders" class="text-[10px] font-bold text-secondary hover:text-white uppercase tracking-widest transition-colors italic">Lihat Semua →</a>
            </div>
            <div class="divide-y-2 divide-neutral-border">
                @forelse($recentOrders as $order)
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between px-6 py-4 gap-4">
                    <div class="flex-1">
                        <p class="font-bold text-sm text-gray-900 uppercase">{{ $order->reseller->name ?? 'N/A' }}</p>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mt-0.5 leading-tight italic">{{ number_format($order->quantity) }} pcs — CeeKlin 450ml</p>
                    </div>
                    <div class="flex items-center gap-3">
                        @php
                            $statusColors = [
                                'Menunggu Konfirmasi' => 'border-red-500 text-red-600 bg-red-50',
                                'Menunggu Proses' => 'border-red-500 text-red-600 bg-red-50',
                                'Menunggu' => 'border-red-500 text-red-600 bg-red-50',
                                'Diproses' => 'border-yellow-500 text-yellow-800 bg-yellow-50',
                                'Dikirim' => 'border-blue-500 text-blue-700 bg-blue-50',
                                'Selesai' => 'border-green-600 text-green-700 bg-green-50',
                                'Dibatalkan' => 'border-gray-400 text-gray-500 bg-gray-50',
                                'Ditolak' => 'border-gray-400 text-gray-500 bg-gray-50',
                            ];
                            
                            $displayStatus = $order->status;
                            if (in_array($order->status, ['Menunggu Konfirmasi', 'Menunggu Proses', 'Menunggu'])) $displayStatus = 'Menunggu';
                            if ($order->status === 'Diproses') $displayStatus = 'Dikemas';
                            if (in_array($order->status, ['Dibatalkan', 'Ditolak'])) $displayStatus = 'Dibatalkan';
                        @endphp
                        <span class="w-[130px] px-2 py-1 border-2 text-[10px] font-black uppercase tracking-widest italic text-center block whitespace-nowrap {{ $statusColors[$order->status] ?? 'border-gray-400 text-gray-500 bg-gray-50' }}">
                            {{ $displayStatus }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="px-8 py-16 text-center bg-neutral-light/50">
                    <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-dashed border-primary/30">
                        <svg class="w-8 h-8 text-primary opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <h3 class="font-headline font-black text-lg text-primary uppercase tracking-tight mb-2">Belum Ada Pesanan</h3>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest max-w-xs mx-auto leading-relaxed">
                        Saat ini belum ada pesanan masuk dari reseller Anda.
                    </p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.dashboard>
