<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">PORTAL DISTRIBUTOR</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>INVENTORI GUDANG</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.distributor._menu')</x-slot:menuSlot>

    <div class="max-w-[1400px] mx-auto w-full flex flex-col gap-6" x-data="{ activeTab: 'summary' }">

        {{-- NAVIGATION TABS --}}
        <div class="flex flex-wrap gap-2 md:gap-4 border-b-[4px] border-gray-900 pb-2">
            <button @click="activeTab = 'summary'"
                :class="activeTab === 'summary' ? 'bg-primary text-white' : 'bg-white text-gray-600 hover:bg-neutral-light'"
                class="flex items-center gap-2 px-6 py-3 border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] font-headline font-black text-xs uppercase tracking-widest transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Ringkasan Stok
            </button>
            <button @click="activeTab = 'sync'"
                :class="activeTab === 'sync' ? 'bg-secondary text-white' : 'bg-white text-gray-600 hover:bg-neutral-light'"
                class="flex items-center gap-2 px-6 py-3 border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] font-headline font-black text-xs uppercase tracking-widest transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Ajukan Sinkronisasi
            </button>
            <button @click="activeTab = 'logs'"
                :class="activeTab === 'logs' ? 'bg-gray-900 text-white' : 'bg-white text-gray-600 hover:bg-neutral-light'"
                class="flex items-center gap-2 px-6 py-3 border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] font-headline font-black text-xs uppercase tracking-widest transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Riwayat Perubahan
            </button>
        </div>

        {{-- TAB CONTENT 1: SUMMARY --}}
        <div x-show="activeTab === 'summary'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">
                {{-- Level Stok --}}
                <div class="xl:col-span-2 bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">
                    <div class="bg-primary px-6 py-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                        <div>
                            <span class="font-headline font-black text-white text-lg uppercase tracking-tight">Status Inventori Gudang</span>
                            <p class="text-[9px] font-bold text-white/60 uppercase tracking-[0.2em] mt-0.5">Pantau Pergerakan & Kapasitas Jual</p>
                        </div>
                        <span class="text-[9px] font-bold text-secondary uppercase tracking-widest bg-gray-900 px-3 py-1.5 border border-white/20">Update: {{ now()->format('d M H:i') }}</span>
                    </div>
                    
                    <div class="p-6 flex flex-col gap-8">
                        <div class="flex flex-col gap-4">
                            <div class="flex justify-between items-center">
                                <h3 class="font-headline font-black text-xl text-primary uppercase tracking-tighter">CeeKlin 450ml</h3>
                                @if($user->stock < 1000)
                                    <span class="px-2 py-1 bg-red-500 text-white text-[10px] font-black uppercase border-2 border-gray-900 shadow-[2px_2px_0_var(--color-gray-900)]">Stok Rendah</span>
                                @elseif($user->stock < 2500)
                                    <span class="px-2 py-1 bg-yellow-400 text-gray-900 text-[10px] font-black uppercase border-2 border-gray-900 shadow-[2px_2px_0_var(--color-gray-900)]">Stok Menengah</span>
                                @else
                                    <span class="px-2 py-1 bg-green-500 text-white text-[10px] font-black uppercase border-2 border-gray-900 shadow-[2px_2px_0_var(--color-gray-900)]">Stok Aman</span>
                                @endif
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-neutral-light border-[3px] border-gray-900 p-4 flex flex-col">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Stok Fisik (Gudang)</span>
                                    <p class="font-headline font-black text-3xl text-gray-900 tracking-tighter">{{ number_format($user->stock, 0, ',', '.') }} <span class="text-xs font-body font-bold text-slate-400">PCS</span></p>
                                </div>
                                <div class="bg-white border-[3px] border-secondary p-4 flex flex-col">
                                    <span class="text-[9px] font-black text-secondary uppercase tracking-widest mb-1">Terpesan (Hold)</span>
                                    {{-- Placeholder: logic belum ada di controller --}}
                                    <p class="font-headline font-black text-3xl text-primary tracking-tighter">0 <span class="text-xs font-body font-bold text-slate-400">PCS</span></p>
                                </div>
                                <div class="bg-primary border-[3px] border-gray-900 p-4 flex flex-col shadow-[4px_4px_0_var(--color-gray-900)]">
                                    <span class="text-[9px] font-black text-white/50 uppercase tracking-widest mb-1">Stok Siap Jual</span>
                                    <p class="font-headline font-black text-3xl text-white tracking-tighter">{{ number_format($user->stock, 0, ',', '.') }} <span class="text-xs font-body font-bold text-white/40">PCS</span></p>
                                </div>
                            </div>

                            <div class="w-full bg-neutral-border-light border-[3px] border-gray-900 h-6 relative overflow-hidden">
                                <div class="bg-secondary h-full transition-all duration-500" style="width:{{ $stockPercentage }}%"></div>
                                <div class="absolute inset-0 flex items-center px-4">
                                    <span class="text-[9px] font-black text-gray-900 uppercase">Kapasitas: {{ round($stockPercentage) }}% @if($user->stock < 1000) (Segera Restock) @endif</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 pb-6">
                        <a href="{{ route('distributor.order') }}" class="inline-flex items-center gap-2 bg-primary text-white px-5 py-3 font-headline font-bold text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-0.5 active:shadow-none transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Restock ke Pabrik
                        </a>
                    </div>
                </div>

                <div class="bg-gray-900 border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-secondary)] p-6 text-white">
                    <h4 class="font-headline font-black text-lg uppercase tracking-tight mb-4 text-secondary">Panduan Inventori</h4>
                    <ul class="flex flex-col gap-4 text-xs font-bold uppercase tracking-widest leading-relaxed">
                        <li class="flex gap-3">
                            <span class="text-secondary">●</span>
                            <p><span class="text-white">Stok Fisik</span> adalah jumlah asli botol yang ada di gudang Anda. *Bertambah otomatis saat pesanan restock ke pabrik dinyatakan SELESAI/TERKIRIM.*</p>
                        </li>
                        <li class="flex gap-3">
                            <span class="text-secondary">●</span>
                            <p><span class="text-white">Terpesan (Hold)</span> adalah barang yang sudah dibayar reseller. *Stok distributor langsung berkurang dari sistem saat pembayaran reseller berhasil.*</p>
                        </li>
                        <li class="flex gap-3">
                            <span class="text-secondary">●</span>
                            <p><span class="text-white">Siap Jual</span> adalah sisa stok aman yang tersedia untuk dipesan oleh reseller lain.</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- TAB CONTENT 2: SYNC --}}
        <div x-show="activeTab === 'sync'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;">
            <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-secondary)] max-w-2xl mx-auto" x-data="{ stokNyata: '', alasan: '', submitted: false }">
                <div class="bg-secondary px-6 py-4 text-center">
                    <span class="font-headline font-black text-white text-lg uppercase tracking-tight">Ajukan Sinkronisasi Stok</span>
                </div>
                <div class="p-8">
                    <form action="{{ route('distributor.inventory') }}" method="POST" class="flex flex-col gap-6" @submit.prevent="submitted = true">
                        @csrf
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-bold text-primary uppercase tracking-widest">Stok di Sistem (Saat Ini)</label>
                            <div class="bg-neutral-light border-[3px] border-neutral-border px-4 py-3 font-headline font-black text-xl text-slate-400 tracking-tighter">{{ number_format($user->stock, 0, ',', '.') }} PCS</div>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-bold text-secondary uppercase tracking-widest">Stok Fisik Aktual</label>
                            <input type="number" name="actual_stock" x-model="stokNyata" required class="bg-neutral-light border-[3px] border-secondary px-4 py-3 font-headline font-black text-xl text-primary focus:outline-none focus:border-primary transition-colors tracking-tighter" placeholder="0">
                            
                            <p class="text-[9px] font-bold uppercase tracking-widest mt-1"
                               x-show="stokNyata !== ''"
                               :class="parseInt(stokNyata) < {{ $user->stock }} ? 'text-red-600' : 'text-green-600'"
                               x-text="parseInt(stokNyata) < {{ $user->stock }} ? `Selisih: -${ {{ $user->stock }} - parseInt(stokNyata)} PCS (kurang)` : `Selisih: +${parseInt(stokNyata) - {{ $user->stock }} } PCS (lebih)`">
                            </p>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-bold text-primary uppercase tracking-widest">Alasan / Keterangan</label>
                            <textarea name="reason" rows="3" required x-model="alasan" class="bg-neutral-light border-[3px] border-primary px-4 py-3 font-body text-sm font-bold text-primary focus:outline-none focus:border-secondary resize-none transition-colors" placeholder="Contoh: Barang rusak karena bocor..."></textarea>
                        </div>

                        <div class="bg-yellow-50 border-l-[4px] border-yellow-500 p-3">
                            <p class="text-[9px] font-bold text-yellow-800 uppercase tracking-widest leading-relaxed italic">
                                ⚠️ Pengajuan ini perlu disetujui Admin sebelum stok diperbarui.
                            </p>
                        </div>
                        <div x-show="!submitted">
                            <button type="submit" class="w-full bg-secondary text-white py-4 font-headline font-black text-sm uppercase tracking-widest border-[3px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)] hover:bg-secondary-dark active:translate-y-1 active:shadow-none transition-all">Kirim Pengajuan</button>
                        </div>
                        <div x-show="submitted" class="p-6 bg-green-50 border-[3px] border-green-600 text-center">
                            <p class="font-headline font-black text-green-800 uppercase tracking-tighter">Pengajuan Berhasil Dikirim!</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- TAB CONTENT 3: LOGS --}}
        <div x-show="activeTab === 'logs'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display:none;">
            <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-gray-900)]">
                <div class="bg-gray-900 px-6 py-4 flex items-center justify-between">
                    <span class="font-headline font-black text-white text-lg uppercase tracking-tight">Riwayat Perubahan Stok</span>
                </div>
                <div class="divide-y-2 divide-neutral-border">
                    @forelse($logs as $log)
                    <div class="flex flex-col px-6 py-5 hover:bg-neutral-light transition-colors group">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 flex items-center justify-center bg-neutral border-2 border-neutral-border">
                                    @if($log['type'] === 'in') <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                                    @elseif($log['type'] === 'out') <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"/></svg>
                                    @else <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    @endif
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <p class="font-headline font-black text-sm text-gray-900 uppercase tracking-tight">{{ $log['desc'] }}</p>
                                    </div>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">{{ $log['date'] }}</p>
                                </div>
                            </div>
                            <span class="font-headline font-black text-2xl {{ $log['color'] }} tracking-tighter">{{ $log['qty'] }} PCS</span>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-10 text-center">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Belum ada riwayat stok</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</x-layouts.dashboard>
