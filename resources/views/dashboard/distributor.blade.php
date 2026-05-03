<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">PORTAL DISTRIBUTOR</span>
    </x-slot:subtitleSlot>

    <x-slot:topbarTitle>BERANDA</x-slot:topbarTitle>

    <x-slot:menuSlot>
        @include('dashboard.distributor._menu')
    </x-slot:menuSlot>


    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        {{-- Volume Bulanan --}}
        <div class="bg-white border-[3px] border-primary shadow-[6px_6px_0_var(--color-primary-darkest)] p-6">
            <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-1">Stok Saat Ini</p>
            <h3 class="font-headline font-black text-4xl text-primary tracking-tighter">{{ number_format($user->stock, 0, ',', '.') }}</h3>
            <p class="text-xs text-slate-500 font-bold mt-2 uppercase tracking-widest flex items-center gap-1">
                PCS Tersedia di Gudang
            </p>
        </div>
        {{-- Reseller Aktif --}}
        <div class="bg-white border-[3px] border-secondary shadow-[6px_6px_0_var(--color-gray-900)] p-6">
            <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-1">Total Reseller</p>
            <h3 class="font-headline font-black text-4xl text-primary tracking-tighter">{{ $resellersCount }}</h3>
            <p class="text-xs text-green-600 font-bold mt-2 uppercase tracking-widest flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                Jaringan Terdaftar
            </p>
        </div>
        {{-- Pesanan Menunggu --}}
        <div class="bg-white border-[3px] border-primary shadow-[6px_6px_0_var(--color-primary-hover)] p-6">
            <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-1">Pesanan Menunggu</p>
            <h3 class="font-headline font-black text-4xl text-primary tracking-tighter">{{ $pendingOrdersCount }}</h3>
            <p class="text-xs text-slate-500 font-bold mt-2 uppercase tracking-widest flex items-center gap-1">
                Butuh Konfirmasi Segera
            </p>
        </div>
    </div>

    {{-- Aksi Cepat + Pesanan Terakhir --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Aksi Cepat --}}
        <div class="flex flex-col gap-4">
            <a href="{{ route('distributor.inventory') }}"
               class="bg-white border-[3px] border-primary shadow-[4px_4px_0_var(--color-primary-darkest)] p-5 flex items-center gap-4 hover:bg-neutral-light transition-colors duration-150 group">
                <div class="w-12 h-12 bg-primary/10 flex items-center justify-center flex-shrink-0 group-hover:bg-primary/20 transition-colors">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                </div>
                <div>
                    <h4 class="font-headline font-black text-primary uppercase text-sm">Cek Stok Gudang</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Pantau level stok & minta restock</p>
                </div>
            </a>
            <a href="{{ route('distributor.resellers') }}"
               class="bg-white border-[3px] border-secondary shadow-[4px_4px_0_var(--color-gray-900)] p-5 flex items-center gap-4 hover:bg-neutral-light transition-colors duration-150 group">
                <div class="w-12 h-12 bg-secondary/10 flex items-center justify-center flex-shrink-0 group-hover:bg-secondary/20 transition-colors">
                    <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
                <div>
                    <h4 class="font-headline font-black text-primary uppercase text-sm">Kelola Reseller</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Lihat & kelola jaringan reseller</p>
                </div>
            </a>
            <a href="{{ route('distributor.order') }}"
               class="bg-primary border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] p-5 flex items-center gap-4 hover:bg-primary-hover transition-colors duration-150 group">
                <div class="w-12 h-12 bg-white/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <h4 class="font-headline font-black text-white uppercase text-sm">Buat Pesanan Baru</h4>
                    <p class="text-xs text-white/60 mt-0.5">Pesan stok langsung ke pabrik</p>
                </div>
            </a>
        </div>

        {{-- Pesanan Terakhir --}}
        <div class="xl:col-span-2 bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">
            <div class="bg-primary px-6 py-3 flex items-center justify-between">
                <span class="font-headline font-black text-white text-base uppercase tracking-tight">Pesanan Terakhir</span>
                <a href="{{ route('distributor.incoming-orders') }}" class="text-[10px] font-bold text-secondary hover:text-white uppercase tracking-widest transition-colors">Lihat Semua →</a>
            </div>
            <div class="divide-y-2 divide-neutral-border">
                @forelse($recentOrders as $order)
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between px-6 py-4 gap-4">
                    <div class="flex-1">
                        <p class="font-bold text-sm text-gray-900 uppercase">{{ $order->reseller->name }}</p>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mt-0.5 leading-tight">{{ $order->quantity }} pcs — CeeKlin 450ml</p>
                    </div>
                    <div class="flex items-center gap-3">
                        @if($order->status === 'Menunggu Konfirmasi')
                            <span class="px-2 py-1 border-2 border-primary text-primary text-[10px] font-bold uppercase tracking-widest">Menunggu Konfirmasi</span>
                        @elseif($order->status === 'Diproses')
                            <span class="px-2 py-1 border-2 border-secondary text-secondary text-[10px] font-bold uppercase tracking-widest">Diproses</span>
                        @elseif($order->status === 'Dikirim')
                            <span class="px-2 py-1 border-2 border-blue-500 text-blue-500 text-[10px] font-bold uppercase tracking-widest">Dikirim</span>
                        @else
                            <span class="px-2 py-1 border-2 border-green-700 text-green-700 text-[10px] font-bold uppercase tracking-widest">Selesai</span>
                        @endif
                        
                        @if($order->status === 'Menunggu Konfirmasi')
                            <a href="{{ route('distributor.incoming-orders') }}" class="bg-primary text-white px-3 py-1 text-[10px] font-bold uppercase tracking-widest hover:bg-primary-hover">Kelola</a>
                        @endif
                    </div>
                </div>
                @empty
                <div class="px-6 py-10 text-center">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Belum ada pesanan masuk</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
 </div>
</x-layouts.dashboard>
