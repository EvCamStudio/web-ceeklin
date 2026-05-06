<x-layouts.dashboard bgTheme="light">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">MASTER ADMIN</span>
    </x-slot:subtitleSlot>

    <x-slot:topbarTitle>LAPORAN PENJUALAN NASIONAL</x-slot:topbarTitle>

    <x-slot:menuSlot>
        @include('dashboard.admin._menu')
    </x-slot:menuSlot>

    <div class="max-w-[1400px] mx-auto w-full" x-data="{
        reportPeriod: 'Semua Periode',
        filterByPeriod(dateStr) {
            if (this.reportPeriod === 'Semua Periode') return true;
            const now = new Date();
            const date = new Date(dateStr);
            if (this.reportPeriod === 'Hari Ini') {
                return date.toDateString() === now.toDateString();
            } else if (this.reportPeriod === 'Minggu Ini') {
                const weekAgo = new Date(now);
                weekAgo.setDate(now.getDate() - 7);
                return date >= weekAgo;
            } else if (this.reportPeriod === 'Bulan Ini') {
                return date.getMonth() === now.getMonth() && date.getFullYear() === now.getFullYear();
            } else if (this.reportPeriod === 'Kuartal Ini') {
                const quarter = Math.floor(now.getMonth() / 3);
                const dateQuarter = Math.floor(date.getMonth() / 3);
                return dateQuarter === quarter && date.getFullYear() === now.getFullYear();
            }
            return true;
        }
    }">
        {{-- KPI Cards Highlight --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10 italic">
            <div class="bg-primary p-6 border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-secondary)] italic">
                <p class="text-[10px] font-black text-white/60 uppercase tracking-[0.2em] mb-1 italic">Total Omzet (MTD)</p>
                <h3 class="font-headline font-black text-3xl text-white tracking-tighter italic">Rp {{ number_format($totalOmzet ?? 0, 0, ',', '.') }}</h3>
                <div class="flex items-center gap-2 mt-4 italic">
                    <span class="bg-secondary text-gray-900 text-[9px] font-black px-1.5 py-0.5 italic">{{ $growth >= 0 ? '+' : '' }}{{ number_format($growth, 1) }}%</span>
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
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1 italic">Total Transaksi</p>
                <h3 class="font-headline font-black text-3xl text-gray-900 tracking-tighter italic">{{ $totalTransactions ?? 0 }} <span class="text-sm italic">INV</span></h3>
                <div class="w-full bg-neutral-border-light h-1.5 mt-5 border border-gray-900 italic">
                    <div class="bg-secondary h-full italic" style="width: 75%"></div>
                </div>
            </div>
        </div>

        {{-- Toolbar: Filter & Export --}}
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-8 italic">
            <div class="flex flex-wrap gap-3 italic">
                <div class="relative min-w-[200px] italic">
                    <select x-model="reportPeriod" class="appearance-none w-full bg-white border-[3px] border-gray-900 px-4 py-3 text-[10px] font-black uppercase tracking-widest text-primary focus:outline-none focus:border-secondary cursor-pointer pr-10 shadow-[4px_4px_0_rgba(0,0,0,0.05)] italic">
                        <option>Semua Periode</option>
                        <option>Hari Ini</option>
                        <option>Minggu Ini</option>
                        <option>Bulan Ini</option>
                        <option>Kuartal Ini</option>
                    </select>
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-primary italic">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
            </div>

            <div class="flex gap-4 italic">
                <button class="bg-primary text-white px-6 py-3 text-[10px] font-headline font-black uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-1 active:shadow-none transition-all flex items-center gap-3 italic">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    TARIK KE EXCEL
                </button>
            </div>
        </div>

        {{-- Main Table: Transaction Detail --}}
        <div class="bg-white border-[4px] border-gray-900 shadow-[10px_10px_0_var(--color-primary-darkest)] overflow-hidden mb-12 italic">
            {{-- Table Header (consistent with other tables) --}}
            <div class="hidden md:grid grid-cols-12 gap-4 px-8 py-4 bg-gray-900">
                <div class="col-span-3 text-[10px] font-headline font-bold text-white uppercase tracking-widest">Invoice / Tgl</div>
                <div class="col-span-3 text-[10px] font-headline font-bold text-white uppercase tracking-widest">Mitra / Wilayah</div>
                <div class="col-span-2 text-[10px] font-headline font-bold text-white uppercase tracking-widest text-center">Volume</div>
                <div class="col-span-2 text-[10px] font-headline font-bold text-white uppercase tracking-widest text-right">Total Bayar</div>
                <div class="col-span-2 text-[10px] font-headline font-bold text-white uppercase tracking-widest text-center">Status</div>
            </div>

            {{-- Rows --}}
            <div class="divide-y-2 divide-neutral-border italic">
                @forelse($recentTransactions ?? [] as $txn)
                    <div x-show="filterByPeriod('{{ $txn->created_at->toISOString() }}')" class="flex flex-col md:grid md:grid-cols-12 gap-3 md:gap-4 px-6 md:px-8 py-5 md:py-4 items-start md:items-center hover:bg-neutral-light transition-colors group italic">
                        {{-- Invoice / Tgl --}}
                        <div class="md:col-span-3 w-full">
                            <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1 italic">Invoice / Tgl</p>
                            <p class="font-headline font-black text-sm text-gray-900 leading-none italic">{{ $txn->invoice_number }}</p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase mt-1.5 italic">{{ $txn->created_at->translatedFormat('d M Y') }}</p>
                        </div>
                        {{-- Mitra / Wilayah --}}
                        <div class="md:col-span-3 w-full">
                            <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1 italic">Mitra / Wilayah</p>
                            <div class="flex items-center gap-3 italic">
                                <div class="w-8 h-8 flex items-center justify-center border-2 border-gray-900 font-headline font-black text-[10px] bg-primary text-white italic flex-shrink-0">
                                    <span>{{ substr($txn->user->role ?? 'U', 0, 1) }}</span>
                                </div>
                                <div class="italic">
                                    <p class="text-[11px] font-black text-gray-900 uppercase leading-none italic">{{ $txn->user->name }}</p>
                                    <p class="text-[9px] font-bold text-slate-500 uppercase mt-1 italic">{{ $txn->user->city_name }}</p>
                                </div>
                            </div>
                        </div>
                        {{-- Volume & Total Bayar (side by side on mobile) --}}
                        <div class="md:col-span-2 w-full flex justify-between items-center md:block md:text-center">
                            <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest italic">Volume</p>
                            <p class="font-headline font-black text-base text-primary tracking-tighter italic">{{ number_format($txn->quantity) }} PCS</p>
                        </div>
                        <div class="md:col-span-2 w-full flex justify-between items-center md:block md:text-right">
                            <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest italic">Total Bayar</p>
                            <p class="font-headline font-black text-lg text-gray-900 tracking-tighter italic">Rp {{ number_format($txn->total_price) }}</p>
                        </div>
                        {{-- Status --}}
                        <div class="md:col-span-2 w-full flex justify-between items-center md:flex md:justify-center">
                            <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest italic">Status</p>
                            <span class="px-3 py-1 border-2 text-[9px] font-black uppercase tracking-widest italic {{ $txn->status === 'Selesai' ? 'border-secondary text-secondary bg-secondary/5' : 'border-orange-400 text-orange-500 bg-orange-50' }}">
                                {{ $txn->status }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-8 py-20 text-center bg-neutral-light/50 italic">
                        <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6 border-2 border-dashed border-primary/30">
                            <svg class="w-10 h-10 text-primary opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <h3 class="font-headline font-black text-xl text-primary uppercase tracking-tight mb-2 italic">Belum Ada Transaksi</h3>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest max-w-xs mx-auto leading-relaxed italic">
                            Belum ada transaksi yang tercatat pada periode ini. Data akan muncul saat pesanan masuk.
                        </p>
                    </div>
                @endforelse

                {{-- Empty State for Frontend Filter --}}
                @if(count($recentTransactions ?? []) > 0)
                    <div x-show="![
                            @foreach($recentTransactions as $txn)
                                filterByPeriod('{{ $txn->created_at->toISOString() }}'),
                            @endforeach
                        ].some(v => v)"
                         x-cloak class="px-8 py-20 text-center bg-neutral-light/50 italic animate-in">
                        <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6 border-2 border-dashed border-primary/30">
                            <svg class="w-10 h-10 text-primary opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <h3 class="font-headline font-black text-xl text-primary uppercase tracking-tight mb-2 italic">Tidak Ada Hasil</h3>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest max-w-xs mx-auto leading-relaxed italic">
                            Tidak ditemukan transaksi untuk periode <span class="text-primary font-black" x-text="reportPeriod"></span>.
                        </p>
                    </div>
                @endif
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
                <div class="p-4 bg-white border-2 border-gray-900 italic text-[10px] font-bold text-slate-500 italic">
                    *Data diperbarui secara real-time berdasarkan transaksi yang masuk ke sistem.
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
