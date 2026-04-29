<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">PORTAL DISTRIBUTOR</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>INVENTORI GUDANG</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.distributor._menu')</x-slot:menuSlot>

    <div class="max-w-[1400px] mx-auto w-full flex flex-col gap-6">

        {{-- ======================== --}}
        {{-- SECTION 1: Level Stok    --}}
        {{-- ======================== --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">

            {{-- Level Stok --}}
            <div class="xl:col-span-2 bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">
                <div class="bg-primary px-6 py-3 flex items-center justify-between">
                    <span class="font-headline font-black text-white text-base uppercase tracking-tight">Level Stok Saat Ini</span>
                    {{-- BACKEND-TODO: tampilkan waktu terakhir update dari Inventory::updated_at --}}
                    <span class="text-[9px] font-bold text-white/50 uppercase tracking-widest">Update: Hari ini 08:30</span>
                </div>
                <div class="p-6 flex flex-col gap-6">
                    {{-- BACKEND-TODO: Loop dari Inventory::where('distributor_id', Auth::id())->get() --}}

                    {{-- Produk: CeeKlin 450ml --}}
                    <div>
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-2 gap-1">
                            <div>
                                <span class="font-headline font-bold text-sm text-gray-900 uppercase tracking-tight">CeeKlin 450ml</span>
                                <span class="ml-2 px-1.5 py-0.5 bg-yellow-100 text-yellow-800 text-[8px] font-bold uppercase tracking-wider border border-yellow-300">Stok Menengah</span>
                            </div>
                            <span class="font-headline font-black text-primary text-base sm:text-lg italic">2.450 / 5.000 PCS</span>
                        </div>
                        <div class="w-full bg-neutral-border-light border-2 border-neutral-border h-4 relative overflow-hidden">
                            <div class="bg-secondary h-full transition-all duration-500" style="width:49%"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-[8px] font-black text-gray-900/60 uppercase tracking-widest">49%</span>
                            </div>
                        </div>
                        <div class="flex justify-between mt-1">
                            <p class="text-[9px] text-slate-400 font-bold uppercase">Stok Aman: ≥ 1.000 PCS</p>
                            <p class="text-[9px] text-yellow-600 font-bold uppercase">⚠ Segera restock</p>
                        </div>
                    </div>
                </div>

                {{-- Footer Link ke Restock --}}
                <div class="px-6 pb-5">
                    <a href="/dashboard/distributor/order"
                       class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 font-headline font-bold text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-0.5 active:shadow-none transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Restock ke Pabrik
                    </a>
                </div>
            </div>

            {{-- Form Sinkronisasi Stok --}}
            <div class="bg-white border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-secondary)]"
                 x-data="{ stokNyata: '', alasan: '', submitted: false }">
                <div class="bg-secondary px-6 py-3">
                    <span class="font-headline font-black text-white text-base uppercase tracking-tight">Ajukan Sinkronisasi Stok</span>
                </div>
                <div class="p-6">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest leading-relaxed mb-5">
                        Jika ada selisih antara stok di sistem dan stok fisik di gudang (mis: rusak, hilang, tumpah), ajukan koreksi stok ke Admin.
                    </p>

                    {{-- BACKEND-TODO: action ke InventoryController@requestSync + @csrf --}}
                    <form action="/dashboard/distributor/inventory/sync" method="POST"
                          class="flex flex-col gap-4"
                          @submit.prevent="if(stokNyata !== '' && alasan.trim() !== '') submitted = true">
                        @csrf

                        {{-- Stok Sistem (readonly info) --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-bold text-primary uppercase tracking-widest">Stok di Sistem (Saat Ini)</label>
                            <div class="bg-neutral-light border-[3px] border-neutral-border px-4 py-2.5 font-headline font-black text-lg text-slate-400 tracking-tighter">
                                2.450 <span class="text-xs font-body font-normal text-slate-400">PCS</span>
                            </div>
                        </div>

                        {{-- Stok Nyata --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-bold text-secondary uppercase tracking-widest" for="stok-nyata">
                                Stok Fisik Aktual <span class="text-red-500">*</span>
                            </label>
                            <input id="stok-nyata" name="actual_stock" type="number" min="0"
                                   placeholder="Masukkan jumlah nyata..." x-model="stokNyata" required
                                   class="bg-neutral-light border-[3px] border-secondary px-4 py-2.5 font-headline font-black text-lg text-primary focus:outline-none focus:border-primary transition-colors placeholder:text-slate-300 placeholder:font-body placeholder:font-normal placeholder:text-sm tracking-tighter">
                            {{-- Preview selisih --}}
                            <p class="text-[9px] font-bold uppercase tracking-widest"
                               x-show="stokNyata !== ''"
                               :class="parseInt(stokNyata) < 2450 ? 'text-red-600' : 'text-green-600'"
                               x-text="parseInt(stokNyata) < 2450 ? `Selisih: -${2450 - parseInt(stokNyata)} PCS (kurang)` : `Selisih: +${parseInt(stokNyata) - 2450} PCS (lebih)`">
                            </p>
                        </div>

                        {{-- Alasan --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-bold text-primary uppercase tracking-widest" for="alasan-sync">
                                Alasan / Keterangan <span class="text-red-500">*</span>
                            </label>
                            <textarea id="alasan-sync" name="reason" rows="3" required x-model="alasan"
                                placeholder="Contoh: 200 pcs rusak karena kebocoran gudang saat hujan deras tanggal 15 April..."
                                class="bg-neutral-light border-[3px] border-primary px-4 py-2.5 font-body text-sm font-bold text-primary focus:outline-none focus:border-secondary resize-none transition-colors placeholder:text-slate-400 placeholder:font-normal"></textarea>
                        </div>

                        {{-- Alert info admin approval --}}
                        <div class="bg-yellow-50 border-l-[4px] border-yellow-500 p-3">
                            <p class="text-[9px] font-bold text-yellow-800 uppercase tracking-widest leading-relaxed">
                                ⚠️ Pengajuan ini perlu disetujui Admin sebelum stok di sistem diperbarui.
                            </p>
                        </div>

                        {{-- Tombol Submit --}}
                        <div x-show="!submitted">
                            <button type="submit"
                                :disabled="stokNyata === '' || alasan.trim() === ''"
                                class="w-full bg-secondary text-white py-3 font-headline font-bold text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-secondary-dark active:translate-y-0.5 active:shadow-none transition-all disabled:opacity-50 disabled:cursor-not-allowed mt-1">
                                Kirim Pengajuan Sinkronisasi
                            </button>
                        </div>

                        {{-- Sukses Feedback --}}
                        <div x-show="submitted" x-transition style="display:none;"
                             class="bg-green-50 border-[3px] border-green-600 p-4 text-center">
                            <svg class="w-8 h-8 text-green-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="font-bold text-xs text-green-800 uppercase tracking-widest">Pengajuan terkirim!</p>
                            <p class="text-[9px] text-green-600 mt-1 font-bold uppercase">Menunggu persetujuan Admin</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ======================== --}}
        {{-- SECTION 2: Riwayat Stok  --}}
        {{-- ======================== --}}
        <div class="bg-white border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)]">
            <div class="bg-gray-900 px-6 py-3 flex items-center justify-between">
                <span class="font-headline font-black text-white text-sm uppercase tracking-tight">Riwayat Perubahan Stok</span>
                <span class="text-[9px] font-bold text-white/50 uppercase tracking-widest">30 hari terakhir</span>
            </div>
            <div class="divide-y-2 divide-neutral-border">
                {{-- BACKEND-TODO: Loop dari StockLog::where('distributor_id', Auth::id())->latest()->take(10)->get() --}}
                @php
                $logs = [
                    ['type' => 'in',  'desc' => 'Restock dari Pabrik — ORD-1088',           'qty' => '+2.000', 'date' => '5 Apr 2026',  'color' => 'text-green-600'],
                    ['type' => 'out', 'desc' => 'Pesanan Reseller — Ahmad Fauzi (ORD-201)',  'qty' => '-100',   'date' => 'Hari Ini',    'color' => 'text-red-600'],
                    ['type' => 'out', 'desc' => 'Pesanan Reseller — Budi Santoso (ORD-195)', 'qty' => '-50',    'date' => 'Kemarin',     'color' => 'text-red-600'],
                    ['type' => 'adj', 'desc' => 'Koreksi Stok — disetujui Admin',            'qty' => '-200',   'date' => '20 Mar 2026', 'color' => 'text-orange-600'],
                    ['type' => 'in',  'desc' => 'Restock dari Pabrik — ORD-1081',            'qty' => '+3.000', 'date' => '22 Mar 2026', 'color' => 'text-green-600'],
                ];
                @endphp
                @foreach($logs as $log)
                <div class="flex items-center justify-between px-6 py-3 hover:bg-neutral-light transition-colors gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 flex items-center justify-center flex-shrink-0">
                            @if($log['type'] === 'in')
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            @elseif($log['type'] === 'out')
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/></svg>
                            @else
                            <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            @endif
                        </div>
                        <div>
                            <p class="font-bold text-xs text-gray-900 uppercase leading-tight">{{ $log['desc'] }}</p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $log['date'] }}</p>
                        </div>
                    </div>
                    <span class="font-headline font-black text-base {{ $log['color'] }} flex-shrink-0">{{ $log['qty'] }} PCS</span>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</x-layouts.dashboard>
