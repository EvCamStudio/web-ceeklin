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
                        <h4 class="font-headline font-black text-red-600 group-hover:text-white text-xl uppercase leading-none tracking-tighter">3 Pesanan Masuk</h4>
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
                        <h4 class="font-headline font-black text-yellow-600 group-hover:text-white text-xl uppercase leading-none tracking-tighter">Stok Menengah</h4>
                        <p class="text-[10px] font-bold text-yellow-600/60 group-hover:text-yellow-100 uppercase tracking-widest mt-1">Sisa 2.125 PCS (Tersedia)</p>
                    </div>
                </div>
                <div class="hidden sm:block">
                    <svg class="w-8 h-8 text-yellow-600 group-hover:text-white transition-transform group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 5l7 7-7 7"/></svg>
                </div>
            </a>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        {{-- Volume Bulanan (Terjual) --}}
        <div class="bg-white border-[3px] border-primary shadow-[6px_6px_0_var(--color-primary-darkest)] p-6">
            <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-1">Volume Terjual (MTD)</p>
            {{-- BACKEND-TODO: Sum qty dari ResellerOrder yang status 'Paid' atau 'Selesai' bulan ini --}}
            <h3 class="font-headline font-black text-4xl text-primary tracking-tighter">1.245 <span class="text-xs font-body">PCS</span></h3>
            <p class="text-xs text-slate-500 font-bold mt-2 uppercase tracking-widest flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                +8.3% vs bulan lalu
            </p>
        </div>

        {{-- Estimasi Profit --}}
        <div class="bg-primary text-white border-[3px] border-gray-900 shadow-[6px_6px_0_var(--color-secondary)] p-6">
            <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-1">Estimasi Profit (MTD)</p>
            {{-- BACKEND-TODO: (Total Volume Terjual x 2.000) --}}
            <h3 class="font-headline font-black text-3xl text-white tracking-tighter italic">Rp 2,49 Juta</h3>
            <p class="text-[9px] font-bold text-secondary uppercase tracking-widest mt-2 italic leading-tight">
                *Dihitung dari margin Rp 2.000 / PCS
            </p>
        </div>

        {{-- Reseller Aktif --}}
        <div class="bg-white border-[3px] border-secondary shadow-[6px_6px_0_var(--color-gray-900)] p-6">
            <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-1">Reseller Aktif</p>
            <h3 class="font-headline font-black text-4xl text-primary tracking-tighter">24 <span class="text-lg text-slate-400">/ 20</span></h3>
            <p class="text-xs text-green-600 font-bold mt-2 uppercase tracking-widest flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                Target Minimum Tercapai
            </p>
        </div>

        {{-- Stok Tersedia --}}
        <div class="bg-white border-[3px] border-primary shadow-[6px_6px_0_var(--color-primary-hover)] p-6">
            <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-1">Stok Siap Jual</p>
            {{-- BACKEND-TODO: Ambil dari inventory (stok fisik - terpesan) --}}
            <h3 class="font-headline font-black text-4xl text-primary tracking-tighter italic">2.125</h3>
            <div class="mt-2">
                <div class="w-full bg-slate-100 h-1.5 border border-slate-200">
                    <div class="bg-secondary h-full" style="width: 65%"></div>
                </div>
                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1">Level Stok: Aman (65%)</p>
            </div>
        </div>
    </div>

    {{-- Aksi Cepat + Pesanan Terakhir --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Aksi Cepat --}}
        <div class="flex flex-col gap-4">
            <a href="/dashboard/distributor/inventory"
               class="bg-white border-[3px] border-primary shadow-[4px_4px_0_var(--color-primary-darkest)] p-5 flex items-center gap-4 hover:bg-neutral-light transition-colors duration-150 group">
                <div class="w-12 h-12 bg-primary/10 flex items-center justify-center flex-shrink-0 group-hover:bg-primary/20 transition-colors">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                </div>
                <div>
                    <h4 class="font-headline font-black text-primary uppercase text-sm">Cek Stok Gudang</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Pantau level stok & minta restock</p>
                </div>
            </a>
            <a href="/dashboard/distributor/resellers"
               class="bg-white border-[3px] border-secondary shadow-[4px_4px_0_var(--color-gray-900)] p-5 flex items-center gap-4 hover:bg-neutral-light transition-colors duration-150 group">
                <div class="w-12 h-12 bg-secondary/10 flex items-center justify-center flex-shrink-0 group-hover:bg-secondary/20 transition-colors">
                    <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
                <div>
                    <h4 class="font-headline font-black text-primary uppercase text-sm">Kelola Reseller</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Lihat & kelola jaringan reseller</p>
                </div>
            </a>
            <a href="/dashboard/distributor/order"
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
                {{-- BACKEND-TODO: link ke halaman riwayat pesanan --}}
                <a href="/dashboard/distributor/order" class="text-[10px] font-bold text-secondary hover:text-white uppercase tracking-widest transition-colors">Lihat Semua →</a>
            </div>
            {{-- BACKEND-TODO: Loop dari Order::latest()->take(5)->get() --}}
            {{-- BACKEND-TODO: Gunakan Enum Status yang Konsisten (Menunggu Proses, Dikemas, Dikirim, Selesai, Dibatalkan) --}}
            <div class="divide-y-2 divide-neutral-border">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between px-6 py-4 gap-4">
                    <div class="flex-1">
                        <p class="font-bold text-sm text-gray-900">Ahmad Reseller</p>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mt-0.5 leading-tight">50 pcs — CeeKlin 450ml</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-2 py-1 border-2 border-primary text-primary text-[10px] font-bold uppercase tracking-widest">Menunggu Proses</span>
                        <button class="bg-primary text-white px-3 py-1 text-[10px] font-bold uppercase tracking-widest hover:bg-primary-hover">Proses</button>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between px-6 py-4 gap-4">
                    <div class="flex-1">
                        <p class="font-bold text-sm text-gray-900">Budi Subroto</p>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mt-0.5 leading-tight">100 pcs — CeeKlin 450ml</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-2 py-1 border-2 border-secondary text-secondary text-[10px] font-bold uppercase tracking-widest">Dikirim</span>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between px-6 py-4 gap-4">
                    <div class="flex-1">
                        <p class="font-bold text-sm text-gray-900">Toko Sinar Jaya</p>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mt-0.5 leading-tight">50 pcs — CeeKlin 450ml</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-2 py-1 border-2 border-green-700 text-green-700 text-[10px] font-bold uppercase tracking-widest">Selesai</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
