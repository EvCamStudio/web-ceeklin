<x-layouts.dashboard bgTheme="light">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">MASTER ADMIN</span>
    </x-slot:subtitleSlot>

    <x-slot:topbarTitle>LAPORAN PENJUALAN NASIONAL</x-slot:topbarTitle>

    <x-slot:menuSlot>
        @include('dashboard.admin._menu')
    </x-slot:menuSlot>

    <div class="max-w-[1400px] mx-auto w-full" x-data="{
        reportPeriod: 'Bulan Ini'
    }">
        {{-- KPI Cards Highlight --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 italic">
            <div class="bg-primary p-6 border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-secondary)] italic">
                <p class="text-[10px] font-black text-white/60 uppercase tracking-[0.2em] mb-1 italic">Total Omzet (MTD)</p>
                <h3 class="font-headline font-black text-3xl text-white tracking-tighter italic">Rp {{ number_format($totalOmzet ?? 0, 0, ',', '.') }}</h3>
                <div class="flex items-center gap-2 mt-4 italic">
                    <span class="bg-secondary text-gray-900 text-[9px] font-black px-1.5 py-0.5 italic">+{{ $growth ?? 0 }}%</span>
                    <span class="text-[9px] font-bold text-white/50 uppercase italic">vs Bulan Lalu</span>
                </div>
            </div>
            <div class="bg-white p-6 border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)] italic">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1 italic">Volume Penjualan</p>
                <h3 class="font-headline font-black text-3xl text-primary tracking-tighter italic">{{ number_format($totalVolume ?? 0, 0, ',', '.') }} <span class="text-sm italic">PCS</span></h3>
                <div class="flex items-center gap-2 mt-4 italic">
                    @foreach($monthlyTrend ?? [] as $trend)
                        <span class="text-[7px] sm:text-[9px] font-bold text-slate-400 uppercase tracking-tight sm:tracking-widest italic">{{ date('M', strtotime($trend->period . '-01')) }}</span>
                    @endforeach
                </div>
            </div>
            <div class="bg-white p-6 border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-gray-900)] italic">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1 italic">Aktivitas Terbaru</p>
                <h3 class="font-headline font-black text-3xl text-gray-900 tracking-tighter italic">{{ $totalTransactions ?? 0 }} <span class="text-sm italic">INV</span></h3>
                <div class="w-full bg-neutral-border-light h-1.5 mt-5 border border-gray-900 italic">
                    <div class="bg-secondary h-full italic" style="width: 75%"></div>
                </div>
            </div>
        </div>

        {{-- Main Table: Transaction Detail --}}
        <div class="bg-white border-[4px] border-gray-900 shadow-[10px_10px_0_var(--color-primary-darkest)] overflow-hidden mb-12 italic">
            <div class="bg-gray-900 px-8 py-4 flex justify-between items-center border-b-2 border-gray-900 italic">
                <h4 class="font-headline font-black text-white text-sm uppercase tracking-widest italic">Rincian Transaksi Masuk</h4>
            </div>
            
            <div class="overflow-x-auto italic">
                <table class="w-full text-left border-collapse min-w-[1000px] italic">
                    <thead class="bg-neutral-light border-b-2 border-neutral-border italic">
                        <tr>
                            <th class="px-8 py-4 text-[10px] font-headline font-bold text-primary uppercase tracking-widest italic">Invoice / Tgl</th>
                            <th class="px-6 py-4 text-[10px] font-headline font-bold text-primary uppercase tracking-widest italic">Mitra / Wilayah</th>
                            <th class="px-6 py-4 text-[10px] font-headline font-bold text-primary uppercase tracking-widest text-center italic">Volume</th>
                            <th class="px-6 py-4 text-[10px] font-headline font-bold text-primary uppercase tracking-widest text-right italic">Total Bayar</th>
                            <th class="px-6 py-4 text-[10px] font-headline font-bold text-primary uppercase tracking-widest text-center italic">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 divide-neutral-border italic">
                        @forelse($recentTransactions ?? [] as $txn)
                        <tr class="hover:bg-neutral-light transition-colors group italic">
                            <td class="px-8 py-5 italic">
                                <p class="font-headline font-black text-sm text-gray-900 leading-none italic">{{ $txn->invoice_number }}</p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase mt-1.5 italic">{{ $txn->created_at->translatedFormat('d M Y') }}</p>
                            </td>
                            <td class="px-6 py-5 italic">
                                <p class="text-[11px] font-black text-gray-900 uppercase leading-none italic">{{ $txn->user->name }}</p>
                                <p class="text-[9px] font-bold text-slate-500 uppercase mt-1 italic">{{ $txn->user->city_name }}</p>
                            </td>
                            <td class="px-6 py-5 text-center italic">
                                <p class="font-headline font-black text-base text-primary tracking-tighter italic">{{ number_format($txn->quantity) }} PCS</p>
                            </td>
                            <td class="px-6 py-5 text-right italic">
                                <p class="font-headline font-black text-lg text-gray-900 tracking-tighter italic">Rp {{ number_format($txn->total_price) }}</p>
                            </td>
                            <td class="px-6 py-5 text-center italic">
                                <span class="px-3 py-1 border-2 text-[9px] font-black uppercase tracking-widest italic border-secondary text-secondary bg-secondary/5">
                                    {{ $txn->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center italic">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">Belum ada transaksi</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Regional Breakdown --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12 italic">
            <div class="bg-white border-[4px] border-gray-900 p-8 shadow-[8px_8px_0_var(--color-primary-darkest)] italic">
                <h4 class="font-headline font-black text-xl text-primary uppercase tracking-tight mb-6 italic">Peringkat Penjualan Wilayah</h4>
                <div class="space-y-6 italic">
                    @forelse($regionalBreakdown ?? [] as $wilayah)
                        @php
                            $maxVolume = $regionalBreakdown->max('volume') ?: 1;
                            $pct = ($wilayah->volume / $maxVolume) * 100;
                        @endphp
                        <div class="italic">
                            <div class="flex items-center justify-between mb-1.5 italic">
                                <span class="font-bold text-xs text-gray-900 uppercase tracking-widest italic">{{ $wilayah->region }}</span>
                                <span class="font-headline font-black text-base text-primary tracking-tighter italic">{{ number_format($wilayah->volume) }} PCS</span>
                            </div>
                            <div class="bg-neutral-border-light border-2 border-gray-900 h-4 w-full overflow-hidden italic">
                                <div class="bg-primary h-full border-r-2 border-gray-900 transition-all duration-1000 italic" style="width:{{ $pct }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center italic">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">Belum ada data wilayah</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-neutral-light border-[4px] border-gray-900 p-8 shadow-[8px_8px_0_var(--color-gray-900)] flex flex-col justify-center italic">
                <h4 class="font-headline font-black text-xl text-primary uppercase tracking-tight mb-4 italic">Catatan Analitik</h4>
                <p class="text-sm font-bold text-slate-700 leading-relaxed mb-4 italic">
                    Data diperbarui secara real-time berdasarkan invoice yang statusnya telah terverifikasi.
                </p>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
