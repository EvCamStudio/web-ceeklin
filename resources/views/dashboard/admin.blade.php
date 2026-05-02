<x-layouts.dashboard bgTheme="light">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">MASTER ADMIN</span>
    </x-slot:subtitleSlot>

    <x-slot:topbarTitle>BERANDA</x-slot:topbarTitle>

    <x-slot:menuSlot>
        @include('dashboard.admin._menu')
    </x-slot:menuSlot>

    <div class="max-w-[1400px] mx-auto w-full">
        <!-- Pusat Aksi Segera Widget -->
        <div class="animate-in bg-white border-[6px] border-gray-900 shadow-[12px_12px_0_var(--color-primary-darkest)] p-8 mb-12 flex flex-col gap-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b-4 border-gray-900 pb-6">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-primary flex items-center justify-center border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-secondary)]">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-headline font-black text-gray-900 text-2xl uppercase tracking-tighter leading-none">Pusat Aksi Segera</h4>
                        <p class="text-[11px] text-secondary font-black uppercase tracking-[0.2em] mt-2 italic">Dashboard Operasional Harian</p>
                    </div>
                </div>
                <div class="hidden md:block">
                    <span class="bg-red-600 text-white px-4 py-1.5 font-headline font-bold text-xs uppercase tracking-widest shadow-[4px_4px_0_rgba(0,0,0,0.2)]">TOTAL: 8 ANTREAN</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Verifikasi Reseller -->
                <a href="/dashboard/admin/verify" class="group relative bg-neutral-light border-[3px] border-gray-900 p-5 hover:bg-primary hover:text-white transition-all duration-300 shadow-[6px_6px_0_rgba(0,0,0,0.1)] hover:shadow-none hover:translate-x-1 hover:translate-y-1">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-[10px] font-black uppercase tracking-widest opacity-60 group-hover:opacity-100">Pendaftaran</span>
                        <span class="bg-red-600 text-white px-2 py-0.5 text-[10px] font-black group-hover:bg-white group-hover:text-red-600 transition-colors">3 BARU</span>
                    </div>
                    <h5 class="font-headline font-black text-lg uppercase tracking-tight mb-1">Verifikasi Reseller</h5>
                    <p class="text-[9px] font-bold uppercase tracking-widest opacity-50 group-hover:opacity-80 leading-tight">Review NIK & Wilayah</p>
                    <div class="mt-4 flex items-center gap-2 text-primary font-black text-[10px] uppercase tracking-widest group-hover:text-white">
                        <span>Tinjau</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </div>
                </a>

                <!-- Pesanan Distributor -->
                <a href="/dashboard/admin/distributors" class="group relative bg-neutral-light border-[3px] border-gray-900 p-5 hover:bg-secondary hover:text-white transition-all duration-300 shadow-[6px_6px_0_rgba(0,0,0,0.1)] hover:shadow-none hover:translate-x-1 hover:translate-y-1">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-[10px] font-black uppercase tracking-widest opacity-60 group-hover:opacity-100">Logistik</span>
                        <span class="bg-gray-900 text-white px-2 py-0.5 text-[10px] font-black group-hover:bg-white group-hover:text-gray-900 transition-colors">5 ORDER</span>
                    </div>
                    <h5 class="font-headline font-black text-lg uppercase tracking-tight mb-1">Pesanan Distributor</h5>
                    <p class="text-[9px] font-bold uppercase tracking-widest opacity-50 group-hover:opacity-80 leading-tight">Kirim & Input Resi</p>
                    <div class="mt-4 flex items-center gap-2 text-secondary font-black text-[10px] uppercase tracking-widest group-hover:text-white">
                        <span>Proses</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </div>
                </a>

                <!-- Pencairan Bonus -->
                <a href="/dashboard/admin/bonus" class="group relative bg-neutral-light border-[3px] border-gray-900 p-5 hover:bg-primary-hover hover:text-white transition-all duration-300 shadow-[6px_6px_0_rgba(0,0,0,0.1)] hover:shadow-none hover:translate-x-1 hover:translate-y-1">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-[10px] font-black uppercase tracking-widest opacity-60 group-hover:opacity-100">Keuangan</span>
                        <span class="bg-primary text-white px-2 py-0.5 text-[10px] font-black group-hover:bg-white group-hover:text-primary transition-colors">3 PENGAJUAN</span>
                    </div>
                    <h5 class="font-headline font-black text-lg uppercase tracking-tight mb-1">Pencairan Bonus</h5>
                    <p class="text-[9px] font-bold uppercase tracking-widest opacity-50 group-hover:opacity-80 leading-tight">Validasi Komisi Mitra</p>
                    <div class="mt-4 flex items-center gap-2 text-primary-hover font-black text-[10px] uppercase tracking-widest group-hover:text-white">
                        <span>Transfer</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </div>
                </a>

                <!-- Sinkronisasi Stok -->
                <a href="/dashboard/admin/requests" class="group relative bg-neutral-light border-[3px] border-gray-900 p-5 hover:bg-gray-900 hover:text-white transition-all duration-300 shadow-[6px_6px_0_rgba(0,0,0,0.1)] hover:shadow-none hover:translate-x-1 hover:translate-y-1">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-[10px] font-black uppercase tracking-widest opacity-60 group-hover:opacity-100">Inventori</span>
                        <span class="bg-yellow-400 text-gray-900 px-2 py-0.5 text-[10px] font-black group-hover:bg-white group-hover:text-gray-900 transition-colors">2 SELISIH</span>
                    </div>
                    <h5 class="font-headline font-black text-lg uppercase tracking-tight mb-1">Sinkronisasi Stok</h5>
                    <p class="text-[9px] font-bold uppercase tracking-widest opacity-50 group-hover:opacity-80 leading-tight">Koreksi Stok Gudang</p>
                    <div class="mt-4 flex items-center gap-2 text-gray-900 font-black text-[10px] uppercase tracking-widest group-hover:text-white">
                        <span>Tinjau</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </div>
                </a>
            </div>
        </div>

        <!-- NEW: Performance Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start mb-20">
            <!-- Monitoring Transaksi Terkini (Col Span 2) -->
            <div class="lg:col-span-2 space-y-6">
                <div class="flex justify-between items-end">
                    <div>
                        <h3 class="font-headline font-black text-3xl text-gray-900 uppercase tracking-tighter leading-none">Aliran Transaksi</h3>
                        <p class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mt-3">Live Updates — Seluruh Indonesia</p>
                    </div>
                    <a href="/dashboard/admin/sales" class="text-[10px] font-black text-slate-400 hover:text-primary uppercase tracking-widest border-b-2 border-slate-200 hover:border-primary transition-all pb-1">Lihat Laporan Lengkap</a>
                </div>

                <div class="bg-white border-[4px] border-gray-900 shadow-[10px_10px_0_var(--color-primary-darkest)] overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-gray-900 text-white">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-headline font-bold uppercase tracking-widest">Waktu</th>
                                <th class="px-6 py-4 text-[10px] font-headline font-bold uppercase tracking-widest">Mitra / Buyer</th>
                                <th class="px-6 py-4 text-[10px] font-headline font-bold uppercase tracking-widest text-center">Volume</th>
                                <th class="px-6 py-4 text-[10px] font-headline font-bold uppercase tracking-widest text-right">Total</th>
                                <th class="px-6 py-4 text-[10px] font-headline font-bold uppercase tracking-widest text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-neutral-border">
                            @foreach([
                                ['time' => '10 Menit Lalu', 'name' => 'PT Tirta Makmur', 'type' => 'Distributor', 'qty' => 500, 'total' => '6.5M', 'status' => 'LUNAS'],
                                ['time' => '45 Menit Lalu', 'name' => 'Andi Reseller', 'type' => 'Reseller', 'qty' => 50, 'total' => '750rb', 'status' => 'PENDING'],
                                ['time' => '2 Jam Lalu', 'name' => 'Budi Santoso', 'type' => 'Distributor', 'qty' => 200, 'total' => '2.6M', 'status' => 'LUNAS'],
                                ['time' => '5 Jam Lalu', 'name' => 'Sari Wangi', 'type' => 'Reseller', 'qty' => 20, 'total' => '300rb', 'status' => 'LUNAS']
                            ] as $trx)
                            <tr class="hover:bg-neutral-light transition-colors group">
                                <td class="px-6 py-5 text-[10px] font-bold text-slate-500 uppercase italic">{{ $trx['time'] }}</td>
                                <td class="px-6 py-5">
                                    <p class="font-headline font-black text-sm uppercase text-gray-900 leading-none">{{ $trx['name'] }}</p>
                                    <span class="text-[8px] font-black uppercase tracking-widest text-primary">{{ $trx['type'] }}</span>
                                </td>
                                <td class="px-6 py-5 text-center font-headline font-black text-lg">{{ $trx['qty'] }} <span class="text-[9px] text-slate-400">PCS</span></td>
                                <td class="px-6 py-5 text-right font-headline font-black text-lg text-primary">{{ $trx['total'] }}</td>
                                <td class="px-6 py-5 text-center">
                                    <span class="px-3 py-1 text-[9px] font-black uppercase tracking-widest {{ $trx['status'] === 'LUNAS' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }} border-2 {{ $trx['status'] === 'LUNAS' ? 'border-green-700/20' : 'border-orange-700/20' }}">
                                        {{ $trx['status'] }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Leaderboard & System Health (Col Span 1) -->
            <div class="space-y-8">
                <div>
                    <h3 class="font-headline font-black text-3xl text-gray-900 uppercase tracking-tighter leading-none">Top Performa</h3>
                    <p class="text-[10px] font-black text-secondary uppercase tracking-[0.2em] mt-3">Mitra Teraktif Hari Ini</p>
                </div>

                <div class="bg-gray-900 border-[4px] border-gray-900 p-8 shadow-[10px_10px_0_var(--color-secondary)]">
                    <div class="space-y-6">
                        @foreach([
                            ['rank' => '01', 'name' => 'PT TIRTA MAKMUR', 'val' => '1.2K PCS', 'color' => 'bg-primary'],
                            ['rank' => '02', 'name' => 'CV SINAR JAYA', 'val' => '850 PCS', 'color' => 'bg-secondary'],
                            ['rank' => '03', 'name' => 'DISTRIBUTOR BALI', 'val' => '620 PCS', 'color' => 'bg-white/20']
                        ] as $top)
                        <div class="flex items-center gap-6">
                            <span class="font-headline font-black text-3xl text-white/10 italic leading-none">{{ $top['rank'] }}</span>
                            <div class="flex-1">
                                <div class="flex justify-between items-end mb-2">
                                    <p class="text-[9px] font-black text-white uppercase tracking-widest">{{ $top['name'] }}</p>
                                    <span class="text-[10px] font-headline font-black text-secondary italic">{{ $top['val'] }}</span>
                                </div>
                                <div class="h-1.5 w-full bg-white/10 overflow-hidden">
                                    <div class="h-full {{ $top['color'] }} transition-all duration-1000" style="width: {{ 100 - (int)$top['rank']*20 }}%"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- NEW: Central Inventory Tracker -->
                <div class="bg-white border-[4px] border-gray-900 p-6 shadow-[8px_8px_0_var(--color-primary-darkest)]">
                    <div class="flex justify-between items-center mb-4">
                        <h5 class="text-[10px] font-black text-gray-900 uppercase tracking-widest">Stok Gudang Pusat</h5>
                        <span class="bg-green-100 text-green-700 px-2 py-0.5 text-[8px] font-black border border-green-700/20 uppercase">Aman</span>
                    </div>
                    <div class="flex items-end gap-3 mb-4">
                        <h4 class="font-headline font-black text-4xl text-primary leading-none">25,402</h4>
                        <span class="text-[10px] font-bold text-slate-400 uppercase italic pb-1">Botol Tersedia</span>
                    </div>
                    <div class="w-full h-3 bg-neutral-light border-2 border-gray-900 overflow-hidden">
                        <div class="h-full bg-primary w-[75%] border-r-2 border-gray-900"></div>
                    </div>
                    <p class="text-[9px] font-bold text-slate-500 mt-3 uppercase italic">*Produksi selanjutnya dijadwalkan: 05 Mei 2026</p>
                </div>

                <!-- NEW: Quick System Log -->
                <div class="bg-neutral-light border-[4px] border-gray-900 p-6 shadow-[8px_8px_0_var(--color-gray-900)]">
                    <h5 class="text-[10px] font-black text-primary uppercase tracking-widest mb-4">Aktivitas Sistem</h5>
                    <div class="space-y-4">
                        @foreach([
                            ['icon' => '🔑', 'msg' => 'Admin mengubah harga regional Papua', 'time' => '12m'],
                            ['icon' => '📦', 'msg' => 'Invoice #INV-9022 dikirim ke logistik', 'time' => '45m'],
                            ['icon' => '🛡️', 'msg' => 'Login terdeteksi dari IP baru (Surabaya)', 'time' => '2h']
                        ] as $log)
                        <div class="flex items-start gap-3">
                            <span class="text-sm shrink-0">{{ $log['icon'] }}</span>
                            <div class="flex-1">
                                <p class="text-[10px] font-bold text-gray-800 leading-tight">{{ $log['msg'] }}</p>
                                <p class="text-[8px] font-black text-slate-400 uppercase mt-1">{{ $log['time'] }} lalu</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>
</x-layouts.dashboard>
