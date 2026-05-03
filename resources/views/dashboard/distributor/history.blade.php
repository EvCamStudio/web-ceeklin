<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">PORTAL DISTRIBUTOR</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>RIWAYAT PESANAN</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.distributor._menu')</x-slot:menuSlot>

    <div class="max-w-[1400px] mx-auto w-full">

        {{-- Header + Filter --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h2 class="font-headline font-black text-2xl text-primary tracking-tighter uppercase">Riwayat Pesanan</h2>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-1">Histori Restock ke Pabrik & Status Pengiriman</p>
            </div>
            <div class="flex gap-2">
                <div class="relative">
                    <select aria-label="Filter status pesanan" class="appearance-none bg-white border-[3px] border-gray-900 px-4 py-2 text-xs font-bold uppercase tracking-widest text-primary focus:outline-none focus:border-secondary cursor-pointer pr-8">
                        <option>Semua Status</option>
                        <option>Menunggu Proses</option>
                        <option>Diproses</option>
                        <option>Dikirim</option>
                        <option>Selesai</option>
                    </select>
                    <div class="absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Riwayat --}}
        <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">

            {{-- Header Kolom (Desktop) --}}
            <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-4 bg-gray-900">
                <div class="col-span-2 text-[10px] font-headline font-bold text-white uppercase tracking-widest">No. Order</div>
                <div class="col-span-2 text-[10px] font-headline font-bold text-white uppercase tracking-widest text-center">Volume (PCS)</div>
                <div class="col-span-2 text-[10px] font-headline font-bold text-white uppercase tracking-widest text-right">Total Bayar</div>
                <div class="col-span-3 text-[10px] font-headline font-bold text-white uppercase tracking-widest">Status</div>
                <div class="col-span-3 text-[10px] font-headline font-bold text-white uppercase tracking-widest text-right">Tanggal Pesan</div>
            </div>

            {{-- BACKEND-TODO: Loop dari DistributorOrder::where('distributor_id', Auth::id())->orderBy('created_at', 'desc')->get() --}}
            <div class="divide-y-2 divide-neutral-border">
                @forelse($history as $item)
                <div class="flex flex-col md:grid md:grid-cols-12 gap-4 px-6 py-5 items-start md:items-center border-l-[5px] {{ $item->leftBorder }} hover:bg-neutral-light transition-colors duration-150">
                    {{-- No. Order --}}
                    <div class="md:col-span-2 w-full">
                        <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">No. Order</p>
                        <p class="font-headline font-black text-sm text-gray-900 tracking-tight">{{ $item->id }}</p>
                        <span class="text-[8px] font-bold uppercase text-slate-400">{{ $item->type }}</span>
                    </div>
                    {{-- Volume --}}
                    <div class="md:col-span-2 w-full flex justify-between md:block md:text-center">
                        <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest">Volume</p>
                        <p class="font-headline font-black text-xl text-primary tracking-tighter">{{ number_format($item->qty, 0, ',', '.') }}</p>
                    </div>
                    {{-- Total --}}
                    <div class="md:col-span-2 w-full flex justify-between md:block md:text-right">
                        <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest">Total Bayar</p>
                        <p class="font-bold text-sm text-gray-900">Rp {{ number_format($item->total, 0, ',', '.') }}</p>
                    </div>
                    {{-- Status --}}
                    <div class="md:col-span-3 w-full flex justify-between md:block">
                        <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest">Status</p>
                        <span class="px-2 py-1 border-2 {{ $item->color }} text-[9px] font-bold uppercase tracking-widest block w-max">{{ $item->status }}</span>
                    </div>
                    {{-- Tanggal --}}
                    <div class="md:col-span-3 w-full flex justify-between md:block md:text-right">
                        <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest">Tanggal</p>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">{{ $item->date->format('d M Y') }}</p>
                    </div>
                </div>
                @empty
                <div class="py-20 text-center">
                    <div class="w-16 h-16 bg-neutral-border mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <p class="font-headline font-bold text-sm text-slate-500 uppercase tracking-widest">Belum Ada Riwayat Pesanan</p>
                </div>
                @endforelse
            </div>
 
            {{-- Footer Summary --}}
            <div class="px-6 py-4 border-t-[4px] border-gray-900 bg-neutral-light flex flex-col sm:flex-row items-center justify-between gap-3">
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Menampilkan {{ $history->count() }} transaksi terakhir</span>
                <a href="#" class="text-[10px] font-bold uppercase tracking-widest text-primary hover:text-secondary transition-colors flex items-center gap-1">
                    Lihat Semua Transaksi
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>

        {{-- Info Stok Update --}}
        <div class="mt-6 bg-secondary/10 border-l-[6px] border-secondary p-5">
            <p class="text-secondary font-headline font-bold text-[11px] tracking-widest uppercase mb-1">ℹ️ Informasi</p>
            <p class="text-gray-900 text-xs font-bold leading-relaxed">
                Stok gudang Anda akan diperbarui secara otomatis saat status pesanan diubah ke <strong>Selesai</strong> oleh Admin. Jika ada ketidaksesuaian stok, gunakan fitur Sinkronisasi Stok di halaman <a href="/dashboard/distributor/inventory" class="text-primary underline">Inventori Gudang</a>.
            </p>
        </div>
    </div>
</x-layouts.dashboard>
