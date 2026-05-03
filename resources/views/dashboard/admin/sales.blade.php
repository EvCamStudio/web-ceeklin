<x-layouts.dashboard bgTheme="light">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">MASTER ADMIN</span>
    </x-slot:subtitleSlot>

    <x-slot:topbarTitle>LAPORAN PENJUALAN NASIONAL</x-slot:topbarTitle>

    <x-slot:menuSlot>
        @include('dashboard.admin._menu')
    </x-slot:menuSlot>

    <div class="max-w-[1400px] mx-auto w-full" x-data="{
        reportPeriod: 'Bulan Ini',
        transactions: [
            { id: 'INV-8821', buyer: 'PT Tirta Makmur', type: 'Distributor', city: 'Bandung', amount: 'Rp 45.000.000', items: '3.000 PCS', date: '02 Mei 2026', status: 'Lunas', method: 'Transfer BCA' },
            { id: 'INV-8819', buyer: 'CV Bintang Selatan', type: 'Distributor', city: 'Surabaya', amount: 'Rp 28.500.000', items: '1.900 PCS', date: '01 Mei 2026', status: 'Lunas', method: 'Transfer Mandiri' },
            { id: 'INV-8815', buyer: 'Ahmad Fauzi', type: 'Reseller', city: 'Bandung', amount: 'Rp 1.050.000', items: '70 PCS', date: '01 Mei 2026', status: 'Menunggu', method: 'QRIS' }
        ]
    }">
        {{-- KPI Cards Highlight --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-primary p-6 border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-secondary)]">
                <p class="text-[10px] font-black text-white/60 uppercase tracking-[0.2em] mb-1">Total Omzet (MTD)</p>
                <h3 class="font-headline font-black text-3xl text-white tracking-tighter">Rp 1.240.500.000</h3>
                <div class="flex items-center gap-2 mt-4">
                    <span class="bg-secondary text-gray-900 text-[9px] font-black px-1.5 py-0.5">+14.2%</span>
                    <span class="text-[9px] font-bold text-white/50 uppercase">vs Bulan Lalu</span>
                </div>
            </div>
            <div class="bg-white p-6 border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Volume Penjualan</p>
                <h3 class="font-headline font-black text-3xl text-primary tracking-tighter italic">82.941 <span class="text-sm">PCS</span></h3>
                <div class="flex items-center gap-2 mt-4">
                    <span class="bg-primary text-white text-[9px] font-black px-1.5 py-0.5">+5.8%</span>
                    <span class="text-[9px] font-bold text-slate-400 uppercase">Trend Positif</span>
                </div>
            </div>
            <div class="bg-white p-6 border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-gray-900)]">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Rata-rata Order (AOV)</p>
                <h3 class="font-headline font-black text-3xl text-gray-900 tracking-tighter">Rp 12.450.000</h3>
                <div class="w-full bg-neutral-border-light h-1.5 mt-5 border border-gray-900">
                    <div class="bg-secondary h-full" style="width: 75%"></div>
                </div>
            </div>
        </div>

        {{-- Toolbar: Filter & Export --}}
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-8">
            <div class="flex flex-wrap gap-3">
                <div class="relative min-w-[200px]">
                    <select x-model="reportPeriod" class="appearance-none w-full bg-white border-[3px] border-gray-900 px-4 py-3 text-[10px] font-black uppercase tracking-widest text-primary focus:outline-none focus:border-secondary cursor-pointer pr-10 shadow-[4px_4px_0_rgba(0,0,0,0.05)]">
                        <option>Hari Ini</option>
                        <option>Minggu Ini</option>
                        <option>Bulan Ini</option>
                        <option>Kuartal Ini</option>
                        <option>Kustom Tanggal...</option>
                    </select>
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
                <button class="bg-neutral-light border-[3px] border-gray-900 px-5 py-3 text-[10px] font-headline font-black uppercase tracking-widest hover:bg-white transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Filter Lanjut
                </button>
            </div>

            <div class="flex gap-4">
                <button class="bg-primary text-white px-6 py-3 text-[10px] font-headline font-black uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-1 active:shadow-none transition-all flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    TARIK KE EXCEL
                </button>
                <button class="bg-white text-primary px-6 py-3 text-[10px] font-headline font-black uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-primary-darkest)] hover:bg-neutral-light active:translate-y-1 active:shadow-none transition-all flex items-center gap-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    CETAK PDF
                </button>
            </div>
        </div>

        {{-- Main Table: Transaction Detail --}}
        <div class="bg-white border-[4px] border-gray-900 shadow-[10px_10px_0_var(--color-primary-darkest)] overflow-hidden mb-12">
            <div class="bg-gray-900 px-8 py-4 flex justify-between items-center border-b-2 border-gray-900">
                <h4 class="font-headline font-black text-white text-sm uppercase tracking-widest">Rincian Transaksi Masuk</h4>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-secondary rounded-full animate-pulse"></span>
                    <span class="text-[9px] font-black text-secondary uppercase tracking-widest">Live Updates</span>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[1000px]">
                    <thead class="bg-neutral-light border-b-2 border-neutral-border">
                        <tr>
                            <th class="px-8 py-4 text-[10px] font-headline font-bold text-primary uppercase tracking-widest">Invoice / Tgl</th>
                            <th class="px-6 py-4 text-[10px] font-headline font-bold text-primary uppercase tracking-widest">Mitra / Wilayah</th>
                            <th class="px-6 py-4 text-[10px] font-headline font-bold text-primary uppercase tracking-widest text-center">Volume</th>
                            <th class="px-6 py-4 text-[10px] font-headline font-bold text-primary uppercase tracking-widest text-right">Total Bayar</th>
                            <th class="px-6 py-4 text-[10px] font-headline font-bold text-primary uppercase tracking-widest text-center">Status</th>
                            <th class="px-8 py-4 text-[10px] font-headline font-bold text-primary uppercase tracking-widest text-right">Metode</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 divide-neutral-border">
                        <template x-for="txn in transactions" :key="txn.id">
                            <tr class="hover:bg-neutral-light transition-colors group">
                                <td class="px-8 py-5">
                                    <p class="font-headline font-black text-sm text-gray-900 leading-none" x-text="txn.id"></p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase mt-1.5" x-text="txn.date"></p>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 flex items-center justify-center border-2 border-gray-900 font-headline font-black text-[10px]" :class="txn.type === 'Distributor' ? 'bg-primary text-white' : 'bg-secondary text-gray-900'">
                                            <span x-text="txn.type === 'Distributor' ? 'D' : 'R'"></span>
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-black text-gray-900 uppercase leading-none" x-text="txn.buyer"></p>
                                            <p class="text-[9px] font-bold text-slate-500 uppercase mt-1" x-text="txn.city"></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <p class="font-headline font-black text-base text-primary tracking-tighter" x-text="txn.items"></p>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <p class="font-headline font-black text-lg text-gray-900 tracking-tighter" x-text="txn.amount"></p>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="px-3 py-1 border-2 text-[9px] font-black uppercase tracking-widest" 
                                        :class="txn.status === 'Lunas' ? 'border-secondary text-secondary bg-secondary/5' : 'border-orange-400 text-orange-500 bg-orange-50'"
                                        x-text="txn.status"></span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <p class="text-[10px] font-bold text-slate-600 uppercase tracking-widest" x-text="txn.method"></p>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Regional Breakdown (Moved to Bottom) --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <div class="bg-white border-[4px] border-gray-900 p-8 shadow-[8px_8px_0_var(--color-primary-darkest)]">
                <h4 class="font-headline font-black text-xl text-primary uppercase tracking-tight mb-6">Peringkat Penjualan Wilayah</h4>
                <div class="space-y-6">
                    @foreach([
                        ['label' => 'Jawa Barat',  'nilai' => 1845, 'pct' => 72, 'warna' => 'bg-primary'],
                        ['label' => 'Jawa Timur',  'nilai' => 1380, 'pct' => 54, 'warna' => 'bg-secondary'],
                        ['label' => 'DKI Jakarta', 'nilai' => 976,  'pct' => 38, 'warna' => 'bg-secondary'],
                    ] as $wilayah)
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="font-bold text-xs text-gray-900 uppercase tracking-widest">{{ $wilayah['label'] }}</span>
                                <span class="font-headline font-black text-base text-primary tracking-tighter">{{ number_format($wilayah['nilai']) }} PCS</span>
                            </div>
                            <div class="bg-neutral-border-light border-2 border-gray-900 h-4 w-full overflow-hidden">
                                <div class="{{ $wilayah['warna'] }} h-full border-r-2 border-gray-900 transition-all duration-1000" style="width:{{ $wilayah['pct'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-neutral-light border-[4px] border-gray-900 p-8 shadow-[8px_8px_0_var(--color-gray-900)] flex flex-col justify-center">
                <h4 class="font-headline font-black text-xl text-primary uppercase tracking-tight mb-4">Catatan Analitik</h4>
                <p class="text-sm font-bold text-slate-700 leading-relaxed mb-4">
                    Penjualan bulan ini didominasi oleh segmen <span class="text-primary">Distributor (85%)</span> dengan pertumbuhan signifikan di wilayah <span class="text-secondary">Jawa Barat</span>.
                </p>
                <div class="p-4 bg-white border-2 border-gray-900 italic text-[10px] font-bold text-slate-500">
                    *Data diperbarui secara real-time berdasarkan invoice yang statusnya telah "Lunas".
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
