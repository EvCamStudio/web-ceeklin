<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">PORTAL RESELLER</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>PESANAN SAYA</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.reseller._menu')</x-slot:menuSlot>

    <div class="max-w-[1400px] mx-auto w-full" x-data="{ 
        statusFilter: 'all',
        
        // Memasukkan data PHP ke Alpine untuk filter yang lebih akurat
        orders: {{ $orders->map(fn($o) => [
            'id' => $o->id,
            'order_number' => $o->order_number,
            'date' => $o->formatted_date,
            'distributor' => $o->distributor->name ?? 'Pusat',
            'status' => $o->status,
            'statusClass' => $o->statusClass,
            'quantity' => $o->quantity,
            'total_price' => $o->total_price,
            'total_price_fmt' => 'Rp ' . number_format($o->total_price, 0, ',', '.'),
            'unit_price_fmt' => 'Rp ' . number_format($o->total_price / ($o->quantity ?: 1), 0, ',', '.'),
        ])->toJson() }},

        mapStatus(rawStatus) {
            if (['Menunggu Proses', 'Menunggu Konfirmasi', 'Menunggu'].includes(rawStatus)) return 'Menunggu';
            if (['Diproses', 'Dikemas'].includes(rawStatus)) return 'Dikemas';
            if (['Ditolak', 'Dibatalkan'].includes(rawStatus)) return 'Dibatalkan';
            return rawStatus;
        },

        get filteredOrders() {
            if (this.statusFilter === 'all') return this.orders;
            return this.orders.filter(o => this.mapStatus(o.status) === this.statusFilter);
        },

        getStepIndex(rawStatus) {
            const status = this.mapStatus(rawStatus);
            const map = { 'Menunggu': 0, 'Dikemas': 1, 'Dikirim': 2, 'Selesai': 3 };
            return map[status] ?? 0;
        }
    }">

        {{-- Header & Filters --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="w-10 h-1 bg-primary"></span>
                    <h2 class="font-headline font-black text-2xl text-primary uppercase tracking-tight italic">Pelacakan Pesanan</h2>
                </div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">Pantau status pengiriman barang Anda secara real-time</p>
            </div>

            {{-- Status Filters --}}
            <div class="w-full md:w-auto">
                <div class="md:hidden w-full relative">
                    <select x-model="statusFilter" class="appearance-none w-full bg-white border-[3px] border-gray-900 px-5 py-4 text-xs font-headline font-black uppercase tracking-widest text-primary focus:outline-none focus:border-primary shadow-[6px_6px_0_var(--color-primary-darkest)] pr-12">
                        <option value="all">SEMUA STATUS</option>
                        <option value="Menunggu">MENUNGGU</option>
                        <option value="Dikemas">DIKEMAS</option>
                        <option value="Dikirim">DIKIRIM</option>
                        <option value="Selesai">SELESAI</option>
                        <option value="Dibatalkan">DIBATALKAN</option>
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                <div class="hidden md:flex flex-wrap gap-2">
                    <template x-for="f in ['all', 'Menunggu', 'Dikemas', 'Dikirim', 'Selesai', 'Dibatalkan']">
                        <button @click="statusFilter = f"
                            :class="statusFilter === f ? 'bg-primary text-white border-primary shadow-[4px_4px_0_var(--color-primary-darkest)]' : 'bg-white text-gray-400 border-gray-200 hover:border-primary hover:text-primary'"
                            class="px-4 py-2 border-[3px] font-headline font-black text-[10px] uppercase tracking-widest transition-all">
                            <span x-text="f === 'all' ? 'SEMUA' : f"></span>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        {{-- Order List --}}
        <div class="flex flex-col gap-6">
            <template x-for="order in filteredOrders" :key="order.id">
                <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)] overflow-hidden">
                    {{-- Header Row --}}
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between px-6 py-4 bg-neutral-light border-b-2 border-neutral-border gap-3">
                        <div class="flex items-center gap-4 flex-wrap">
                            <span class="font-headline font-black text-base text-gray-900 uppercase italic" x-text="order.order_number"></span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic" x-text="order.date"></span>
                            <span class="text-[10px] font-bold text-slate-500 uppercase italic" x-text="'via ' + order.distributor"></span>
                        </div>
                        <span class="min-w-[130px] text-center px-3 py-1 border-2 text-[9px] font-black uppercase tracking-widest italic"
                              :class="order.statusClass"
                              x-text="mapStatus(order.status)">
                        </span>
                    </div>

                    {{-- Order Detail --}}
                    <div class="px-6 py-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                        <div class="flex items-center gap-5">
                            <div class="w-14 h-14 bg-gray-900 flex items-center justify-center flex-shrink-0 border-2 border-gray-900 shadow-[3px_3px_0_var(--color-primary)]">
                                <img src="/images/hero-bottle.jpeg" alt="CeeKlin" class="w-full h-auto mix-blend-screen opacity-80 scale-90">
                            </div>
                            <div>
                                <p class="font-headline font-black text-primary uppercase text-lg leading-tight italic">CeeKlin 450ml</p>
                                <p class="text-[11px] font-bold text-slate-500 mt-1 italic" x-text="new Intl.NumberFormat('id-ID').format(order.quantity) + ' PCS × ' + order.unit_price_fmt"></p>
                            </div>
                        </div>
                        <div class="flex flex-col sm:items-end gap-3">
                            <p class="font-headline font-black text-2xl text-primary tracking-tighter italic leading-none" x-text="order.total_price_fmt"></p>

                            {{-- KONFIRMASI TERIMA --}}
                            <template x-if="order.status === 'Dikirim'">
                                <form action="{{ route('reseller.history.confirm') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="order_id" :value="order.id">
                                    <button type="submit"
                                        class="w-full sm:w-auto bg-primary text-white px-6 py-3 font-headline font-black text-[11px] uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-0.5 active:shadow-none transition-all flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        <span>Konfirmasi Terima</span>
                                    </button>
                                </form>
                            </template>
                            
                            <template x-if="order.status === 'Selesai'">
                                <span class="text-[11px] font-black text-green-600 uppercase tracking-widest flex items-center gap-2 italic">
                                    <div class="w-6 h-6 bg-green-100 border-2 border-green-600 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    Pesanan Selesai
                                </span>
                            </template>
                        </div>
                    </div>

                    {{-- Progress Bar Status --}}
                    <div class="px-6 pb-8 pt-4">
                        <div class="relative w-full">
                            <div class="absolute top-1/2 left-0 w-full h-[6px] bg-neutral-border -translate-y-1/2 rounded-full"></div>
                            <div class="absolute top-1/2 left-0 h-[6px] bg-primary -translate-y-1/2 rounded-full transition-all duration-700" 
                                 :style="'width: ' + (getStepIndex(order.status) / 3 * 100) + '%'"></div>
                            
                            <div class="relative flex justify-between items-center w-full">
                                <template x-for="(step, i) in [
                                    {label: 'Menunggu', icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'},
                                    {label: 'Dikemas', icon: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'},
                                    {label: 'Dikirim', icon: 'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0'},
                                    {label: 'Selesai', icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'}
                                ]" :key="i">
                                    <div class="flex flex-col items-center">
                                        <div class="w-10 h-10 border-[3px] flex items-center justify-center transition-all duration-300 z-10 relative"
                                            :class="getStepIndex(order.status) >= i 
                                                ? 'bg-primary border-gray-900 text-white shadow-[4px_4px_0_var(--color-gray-900)] scale-110' 
                                                : 'bg-white border-neutral-border text-slate-300'">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" :d="step.icon"/></svg>
                                        </div>
                                        <p class="text-[9px] font-black uppercase tracking-widest mt-4"
                                            :class="getStepIndex(order.status) >= i ? 'text-primary italic' : 'text-slate-400'" x-text="step.label">
                                        </p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        {{-- No Results State (Ketika sudah belanja tapi filter tidak ada yang cocok) --}}
        <div x-show="orders.length > 0 && filteredOrders.length === 0" style="display: none;"
             class="py-20 text-center bg-white border-[4px] border-dashed border-gray-200 shadow-[8px_8px_0_rgba(0,0,0,0.02)]">
            <div class="w-16 h-16 bg-neutral-light border-2 border-gray-900 flex items-center justify-center mx-auto mb-4 grayscale">
                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Tidak ada pesanan dengan status ini</p>
            <button @click="statusFilter = 'all'" class="mt-4 text-[10px] font-black text-primary uppercase underline tracking-widest italic">Tampilkan Semua</button>
        </div>

        {{-- Empty State (Ketika Benar-benar belum pernah belanja) --}}
        <div x-show="orders.length === 0" style="display: none;"
             class="py-24 text-center bg-white border-[4px] border-dashed border-gray-200 shadow-[10px_10px_0_rgba(0,0,0,0.03)] flex flex-col items-center">
            <div class="w-20 h-20 bg-neutral-light border-[3px] border-gray-900 flex items-center justify-center mb-6 shadow-[4px_4px_0_var(--color-primary-darkest)]">
                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <h3 class="font-headline font-black text-xl text-primary uppercase italic mb-2">Keranjang Anda Masih Kosong</h3>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic mb-8">Anda belum memiliki riwayat transaksi apapun di sistem CeeKlin</p>
            <a href="/dashboard/reseller/order" class="bg-primary text-white border-[3px] border-gray-900 px-8 py-4 font-headline font-black text-xs uppercase tracking-widest shadow-[6px_6px_0_var(--color-primary-darkest)] hover:bg-primary-hover active:translate-y-1 active:shadow-none transition-all italic">
                Buat Pesanan Pertama
            </a>
        </div>
    </div>
</x-layouts.dashboard>
