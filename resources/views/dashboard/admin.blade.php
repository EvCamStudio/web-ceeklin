<x-layouts.dashboard bgTheme="light">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">MASTER ADMIN</span>
    </x-slot:subtitleSlot>

    <x-slot:topbarTitle>BERANDA</x-slot:topbarTitle>

    <x-slot:menuSlot>
        @include('dashboard.admin._menu')
    </x-slot:menuSlot>

    <div class="max-w-[1400px] mx-auto w-full">
        <!-- KPI Cards Grid (Real Data) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12 mt-4">
            <!-- Box 1: Resellers -->
            <div class="bg-white p-6 border-[3px] border-primary-container shadow-[8px_8px_0_var(--color-primary-darkest)]">
                <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-2">Total Reseller Aktif</p>
                <h3 class="font-headline font-black text-3xl md:text-4xl text-primary tracking-tighter">{{ number_format($resellersCount) }}</h3>
                <p class="text-xs text-slate-500 font-bold mt-2 uppercase tracking-widest flex items-center gap-1">
                    <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    Nasional
                </p>
            </div>
            
            <!-- Box 2: Distributors -->
            <div class="bg-white p-6 border-[3px] border-secondary shadow-[8px_8px_0_var(--color-gray-900)]">
                <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-2">Distributor Aktif</p>
                <h3 class="font-headline font-black text-3xl md:text-4xl text-primary tracking-tighter">{{ number_format($distributorsCount) }}</h3>
                <p class="text-xs text-slate-500 font-bold mt-2 uppercase tracking-widest italic">Tersebar di {{ $provincesCount ?? '—' }} Provinsi</p>
            </div>
            
            <!-- Box 3: Pending Verifications -->
            <div class="bg-white p-6 border-[3px] border-primary shadow-[8px_8px_0_var(--color-primary-hover)]">
                <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-2">Antrean Verifikasi</p>
                <h3 class="font-headline font-black text-3xl md:text-4xl text-primary tracking-tighter">{{ number_format($pendingVerificationsCount) }}</h3>
                <p class="text-xs text-slate-500 font-bold mt-2 uppercase tracking-widest flex items-center gap-1 italic">
                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Butuh Peninjauan Segera
                </p>
            </div>
        </div>

        <!-- Pusat Aksi Segera Widget (Premium UI from Wan) -->
        <div class="bg-white border-[6px] border-gray-900 shadow-[12px_12px_0_var(--color-primary-darkest)] p-8 mb-12 flex flex-col gap-8">
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
                    <span class="bg-red-600 text-white px-4 py-1.5 font-headline font-bold text-xs uppercase tracking-widest shadow-[4px_4px_0_rgba(0,0,0,0.2)] italic">TOTAL: {{ $pendingVerificationsCount }} ANTREAN</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Verifikasi Reseller -->
                <a href="{{ route('admin.verify.index') }}" class="group relative bg-neutral-light border-[3px] border-gray-900 p-5 hover:bg-primary hover:text-white transition-all duration-300 shadow-[6px_6px_0_rgba(0,0,0,0.1)] hover:shadow-none hover:translate-x-1 hover:translate-y-1">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-[10px] font-black uppercase tracking-widest opacity-60 group-hover:opacity-100 italic">Pendaftaran</span>
                        <span class="bg-red-600 text-white px-2 py-0.5 text-[10px] font-black group-hover:bg-white group-hover:text-red-600 transition-colors">{{ $pendingVerificationsCount }} BARU</span>
                    </div>
                    <h5 class="font-headline font-black text-lg uppercase tracking-tight mb-1">Verifikasi Reseller</h5>
                    <p class="text-[9px] font-bold uppercase tracking-widest opacity-50 group-hover:opacity-80 leading-tight italic">Review NIK & Wilayah</p>
                    <div class="mt-4 flex items-center gap-2 text-primary font-black text-[10px] uppercase tracking-widest group-hover:text-white">
                        <span>Tinjau</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </div>
                </a>

                <!-- Pesanan Distributor -->
                <a href="{{ route('admin.distributors.index') }}" class="group relative bg-neutral-light border-[3px] border-gray-900 p-5 hover:bg-secondary hover:text-white transition-all duration-300 shadow-[6px_6px_0_rgba(0,0,0,0.1)] hover:shadow-none hover:translate-x-1 hover:translate-y-1">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-[10px] font-black uppercase tracking-widest opacity-60 group-hover:opacity-100 italic">Logistik</span>
                    </div>
                    <h5 class="font-headline font-black text-lg uppercase tracking-tight mb-1">Pesanan Distributor</h5>
                    <p class="text-[9px] font-bold uppercase tracking-widest opacity-50 group-hover:opacity-80 leading-tight italic">Kirim & Input Resi</p>
                    <div class="mt-4 flex items-center gap-2 text-secondary font-black text-[10px] uppercase tracking-widest group-hover:text-white">
                        <span>Proses</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </div>
                </a>

                <!-- Pencairan Bonus -->
                <a href="{{ route('admin.bonus') }}" class="group relative bg-neutral-light border-[3px] border-gray-900 p-5 hover:bg-primary-hover hover:text-white transition-all duration-300 shadow-[6px_6px_0_rgba(0,0,0,0.1)] hover:shadow-none hover:translate-x-1 hover:translate-y-1">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-[10px] font-black uppercase tracking-widest opacity-60 group-hover:opacity-100 italic">Keuangan</span>
                    </div>
                    <h5 class="font-headline font-black text-lg uppercase tracking-tight mb-1">Pencairan Bonus</h5>
                    <p class="text-[9px] font-bold uppercase tracking-widest opacity-50 group-hover:opacity-80 leading-tight italic">Validasi Komisi Mitra</p>
                    <div class="mt-4 flex items-center gap-2 text-primary-hover font-black text-[10px] uppercase tracking-widest group-hover:text-white">
                        <span>Transfer</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </div>
                </a>

                <!-- Sinkronisasi Stok -->
                <a href="{{ route('admin.requests') }}" class="group relative bg-neutral-light border-[3px] border-gray-900 p-5 hover:bg-gray-900 hover:text-white transition-all duration-300 shadow-[6px_6px_0_rgba(0,0,0,0.1)] hover:shadow-none hover:translate-x-1 hover:translate-y-1">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-[10px] font-black uppercase tracking-widest opacity-60 group-hover:opacity-100 italic">Inventori</span>
                    </div>
                    <h5 class="font-headline font-black text-lg uppercase tracking-tight mb-1">Sinkronisasi Stok</h5>
                    <p class="text-[9px] font-bold uppercase tracking-widest opacity-50 group-hover:opacity-80 leading-tight italic">Koreksi Stok Gudang</p>
                    <div class="mt-4 flex items-center gap-2 text-gray-900 font-black text-[10px] uppercase tracking-widest group-hover:text-white">
                        <span>Tinjau</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </div>
                </a>
            </div>
        </div>

        <!-- Monitoring Transaksi Terkini & Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start mb-20">
            <!-- Monitoring Transaksi Terkini (Col Span 2) -->
            <div class="lg:col-span-2 space-y-6">
                <div class="flex justify-between items-end">
                    <div>
                        <h3 class="font-headline font-black text-3xl text-gray-900 uppercase tracking-tighter leading-none italic">Aliran Transaksi</h3>
                        <p class="text-[10px] font-black text-primary uppercase tracking-[0.2em] mt-3">Live Updates — Seluruh Indonesia</p>
                    </div>
                    <a href="{{ route('admin.sales') }}" class="text-[10px] font-black text-slate-400 hover:text-primary uppercase tracking-widest border-b-2 border-slate-200 hover:border-primary transition-all pb-1">Lihat Laporan Lengkap</a>
                </div>

                <div class="bg-white border-[4px] border-gray-900 shadow-[10px_10px_0_var(--color-primary-darkest)] overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-gray-900 text-white">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-headline font-bold uppercase tracking-widest italic">Waktu</th>
                                <th class="px-6 py-4 text-[10px] font-headline font-bold uppercase tracking-widest italic">Mitra / Buyer</th>
                                <th class="px-6 py-4 text-[10px] font-headline font-bold uppercase tracking-widest text-center italic">Volume</th>
                                <th class="px-6 py-4 text-[10px] font-headline font-bold uppercase tracking-widest text-right italic">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-neutral-border">
                            @forelse($recentTransactions ?? [] as $trx)
                            <tr class="hover:bg-neutral-light transition-colors group italic">
                                <td class="px-6 py-5 text-[10px] font-bold text-slate-500 uppercase italic">{{ $trx['time'] }}</td>
                                <td class="px-6 py-5">
                                    <p class="font-headline font-black text-sm uppercase text-gray-900 leading-none italic">{{ $trx['name'] }}</p>
                                    <span class="text-[8px] font-black uppercase tracking-widest {{ $trx['type'] === 'Distributor' ? 'text-primary' : 'text-secondary' }}">{{ $trx['type'] }}</span>
                                </td>
                                <td class="px-6 py-5 text-center font-headline font-black text-lg">{{ $trx['qty'] }} <span class="text-[9px] text-slate-400">PCS</span></td>
                                <td class="px-6 py-5 text-right font-headline font-black text-lg text-primary italic">{{ $trx['total'] }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-10 text-center italic">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">Belum ada transaksi terkini.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Activity Log (Real Data if available) -->
            <div class="space-y-8">
                <div>
                    <h3 class="font-headline font-black text-3xl text-gray-900 uppercase tracking-tighter leading-none italic">Aktivitas Terbaru</h3>
                    <p class="text-[10px] font-black text-secondary uppercase tracking-[0.2em] mt-3">Log Sistem Real-Time</p>
                </div>

                <div class="bg-white border-[4px] border-gray-900 p-8 shadow-[10px_10px_0_var(--color-secondary)]">
                    <div class="divide-y-2 divide-neutral-border">
                        @forelse($recentActivity ?? [] as $activity)
                        <div class="py-4 first:pt-0">
                            <p class="font-bold text-sm text-gray-900 leading-tight uppercase">{{ $activity['title'] }}</p>
                            <div class="flex justify-between items-center mt-1">
                                <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 italic">{{ $activity['subtitle'] }}</p>
                                <span class="text-[8px] font-black text-primary uppercase tracking-widest italic">{{ $activity['time'] }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="py-10 text-center">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Belum ada aktivitas terbaru</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- System Info Card -->
                <div class="bg-gray-900 p-6 border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">
                    <h5 class="text-[10px] font-black text-white uppercase tracking-widest mb-4 italic">Kesehatan Sistem</h5>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-[9px] font-bold text-white/50 uppercase italic">Server Status</span>
                            <span class="text-[9px] font-black text-green-400 uppercase">Online ✓</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[9px] font-bold text-white/50 uppercase italic">Database Sync</span>
                            <span class="text-[9px] font-black text-green-400 uppercase">Synchronized</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
