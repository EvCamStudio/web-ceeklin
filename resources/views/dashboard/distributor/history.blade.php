<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">PORTAL DISTRIBUTOR</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>RIWAYAT PESANAN</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.distributor._menu')</x-slot:menuSlot>

    <div class="max-w-[1400px] mx-auto w-full relative" x-data="{ 
        viewMode: 'list', 
        selectedOrder: null,
        showAll: false,
        confirmMode: false,
        checklist: { qty: false, quality: false, original: false },
        openDetail(order) {
            this.selectedOrder = order;
            this.viewMode = 'detail';
            this.confirmMode = false;
            this.checklist = { qty: false, quality: false, original: false };
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        confirmReceived() {
            // BACKEND-TODO: Kirim request API di sini untuk update status ke Selesai dan update stok gudang
            this.selectedOrder.status = 'Selesai';
            this.confirmMode = false;
        },
        canConfirm() {
            return this.checklist.qty && this.checklist.quality && this.checklist.original;
        }
    }">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4" x-show="viewMode === 'list'">
            <div>
                <h2 class="font-headline font-black text-2xl text-primary tracking-tighter uppercase leading-none italic">Status & Riwayat Restock</h2>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-2 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                    Lacak Pengiriman & Konfirmasi Barang Diterima
                </p>
            </div>
        </div>

        {{-- Tabel Riwayat --}}
        <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]" x-show="viewMode === 'list'">
            {{-- Header Kolom (Desktop) --}}
            <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-4 bg-gray-900">
                <div class="col-span-2 text-[10px] font-headline font-bold text-white uppercase tracking-widest">No. Order</div>
                <div class="col-span-2 text-[10px] font-headline font-bold text-white uppercase tracking-widest text-right">Volume (PCS)</div>
                <div class="col-span-2 text-[10px] font-headline font-bold text-white uppercase tracking-widest text-right">Total Bayar</div>
                <div class="col-span-3 text-[10px] font-headline font-bold text-white uppercase tracking-widest text-center">Status & Tanggal</div>
                <div class="col-span-3 text-[10px] font-headline font-bold text-white uppercase tracking-widest text-right">Aksi</div>
            </div>

            <div class="divide-y-2 divide-neutral-border">
                @forelse($history as $item)
                <div class="flex flex-col md:grid md:grid-cols-12 gap-4 px-6 py-5 items-start md:items-center border-l-[5px] {{ $item->leftBorder }} hover:bg-neutral-light/50 transition-colors duration-150 group">
                    {{-- No. Order --}}
                    <div class="md:col-span-2 w-full">
                        <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">No. Order</p>
                        <p class="font-headline font-black text-sm text-gray-900 tracking-tight">{{ $item->id }}</p>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $item->date->format('d M Y') }}</p>
                    </div>
                    {{-- Volume --}}
                    <div class="md:col-span-2 w-full flex justify-between md:block md:text-right">
                        <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Volume</p>
                        <p class="font-headline font-black text-xl text-primary tracking-tighter italic">{{ number_format($item->qty, 0, ',', '.') }} <span class="text-[10px] font-body font-bold text-slate-400">PCS</span></p>
                    </div>
                    {{-- Total --}}
                    <div class="md:col-span-2 w-full flex justify-between md:block md:text-right">
                        <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Total Bayar</p>
                        <p class="font-bold text-sm text-gray-900 italic">Rp {{ number_format($item->total, 0, ',', '.') }}</p>
                    </div>
                    {{-- Status --}}
                    <div class="md:col-span-3 w-full flex justify-between md:block md:text-center">
                        <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Status</p>
                        <div class="flex flex-col md:items-center">
                            <span class="px-2 py-1 border-2 {{ $item->color }} text-[9px] font-black uppercase tracking-widest block w-max shadow-[2px_2px_0_rgba(0,0,0,0.05)]">{{ $item->status }}</span>
                        </div>
                    </div>
                    {{-- Aksi --}}
                    <div class="md:col-span-3 w-full flex justify-start md:justify-end">
                        <button @click="openDetail({ id: '{{ $item->id }}', qty: {{ $item->qty }}, total: 'Rp {{ number_format($item->total, 0, ',', '.') }}', status: '{{ $item->status }}', date: '{{ $item->date->format('d M Y') }}', tracking: '{{ $item->tracking_number ?? '' }}', courier: '{{ $item->courier ?? '' }}', notes: '{{ $item->admin_note ?? '' }}' })"
                            class="bg-primary text-white px-5 py-2.5 text-[10px] font-black uppercase tracking-widest border-2 border-gray-900 shadow-[3px_3px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-0.5 active:shadow-none transition-all">
                            Lihat Detail
                        </button>
                    </div>
                </div>
                @empty
                <div class="py-20 text-center">
                    <p class="font-headline font-bold text-sm text-slate-500 uppercase tracking-widest">Belum Ada Riwayat Pesanan</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- VIEW: DETAIL PESANAN --}}
        <div x-show="viewMode === 'detail'" class="py-10 space-y-10" style="display: none;" x-transition>
            {{-- Header Detail --}}
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-6">
                <div class="flex items-center gap-6">
                    <x-ui.back-button @click="viewMode = 'list'" />
                    <div>
                        <h3 class="font-headline font-black text-3xl text-primary tracking-tighter uppercase leading-none italic" x-text="'Order ' + selectedOrder?.id"></h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2" x-text="'Tanggal Pesan: ' + selectedOrder?.date"></p>
                    </div>
                </div>
                
                <template x-if="selectedOrder?.status === 'Selesai'">
                    <div class="w-full lg:max-w-md bg-green-50 border-[3px] border-green-600 p-4 shadow-[4px_4px_0_var(--color-green-600)] flex items-center gap-4">
                        <div class="w-10 h-10 bg-green-600 text-white flex items-center justify-center font-bold text-xl">✓</div>
                        <div>
                            <p class="text-[10px] font-black text-green-700 uppercase tracking-widest">Pesanan Selesai</p>
                            <p class="text-[9px] font-bold text-green-600 uppercase mt-1 leading-relaxed">Barang telah diterima & stok gudang telah diperbarui.</p>
                        </div>
                    </div>
                </template>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Status & Tracking --}}
                <div class="space-y-6">
                    <div class="bg-white border-[4px] border-gray-900 p-8 shadow-[10px_10px_0_rgba(0,0,0,0.05)]">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-6 italic">Status Pengiriman</span>
                        
                        <div class="flex items-center w-full mb-10">
                            <template x-for="(step, i) in ['Menunggu', 'Dikemas', 'Dikirim', 'Selesai']">
                                <div class="flex-1 flex flex-col items-center relative">
                                    <div x-show="i > 0" class="absolute right-[50%] top-2.5 w-full h-[4px] -z-10"
                                         :class="i <= ['Menunggu', 'Dikemas', 'Dikirim', 'Selesai'].indexOf(selectedOrder?.status) ? 'bg-primary' : 'bg-neutral-light'"></div>
                                    <div class="w-6 h-6 border-[3px] flex items-center justify-center transition-all duration-500"
                                         :class="i <= ['Menunggu', 'Dikemas', 'Dikirim', 'Selesai'].indexOf(selectedOrder?.status) ? 'bg-primary border-gray-900 text-white shadow-[2px_2px_0_rgba(0,0,0,0.2)]' : 'bg-white border-neutral-light text-slate-300'">
                                        <svg x-show="i <= ['Menunggu', 'Dikemas', 'Dikirim', 'Selesai'].indexOf(selectedOrder?.status)" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <span class="text-[8px] font-black uppercase tracking-widest mt-3"
                                          :class="i <= ['Menunggu', 'Dikemas', 'Dikirim', 'Selesai'].indexOf(selectedOrder?.status) ? 'text-gray-900' : 'text-slate-300'" x-text="step"></span>
                                </div>
                            </template>
                        </div>

                        <div class="space-y-4 border-t-2 border-dashed border-gray-200 pt-6">
                            <div class="flex justify-between">
                                <span class="text-[9px] font-black text-slate-400 uppercase">Kurir / Logistik</span>
                                <span class="text-xs font-bold text-gray-900 uppercase italic" x-text="selectedOrder?.courier || '—'"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[9px] font-black text-slate-400 uppercase">Nomor Resi</span>
                                <span class="text-xs font-black text-primary font-mono tracking-wider" x-text="selectedOrder?.tracking || 'Belum Tersedia'"></span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-neutral-light border-[4px] border-gray-900 p-8 shadow-[8px_8px_0_var(--color-primary-darkest)]">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-3 italic">Catatan Pabrik / Admin:</span>
                        <div class="bg-white p-4 border-2 border-gray-200">
                            <p class="text-xs font-bold text-gray-600 italic leading-relaxed" x-text="'“' + (selectedOrder?.notes || 'Tidak ada catatan tambahan untuk pesanan ini.') + '”'"></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border-[4px] border-gray-900 p-8 shadow-[10px_10px_0_var(--color-secondary)]">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-6 italic">Rincian Stok Dipesan</span>
                    <div class="flex items-center gap-8 mb-8 pb-8 border-b-2 border-dashed border-gray-200">
                        <div class="w-20 h-20 bg-neutral-light flex items-center justify-center border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-primary)]">
                            <img src="/images/hero-bottle.jpeg" class="w-12 h-auto opacity-80 mix-blend-multiply">
                        </div>
                        <div class="flex-1">
                            <h4 class="font-headline font-black text-primary uppercase text-xl italic leading-none">CeeKlin 450ml</h4>
                            <div class="mt-4 flex items-end justify-between">
                                <p class="text-3xl font-headline font-black text-gray-900 italic tracking-tighter" x-text="new Intl.NumberFormat('id-ID').format(selectedOrder?.qty) + ' PCS'"></p>
                                <div class="text-right">
                                    <p class="text-[8px] font-black text-slate-400 uppercase">Subtotal</p>
                                    <p class="text-sm font-bold text-gray-900" x-text="selectedOrder?.total"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-black text-primary uppercase tracking-widest italic">Total Tagihan</span>
                        <span class="text-3xl font-headline font-black text-primary italic tracking-tighter" x-text="selectedOrder?.total"></span>
                    </div>
                </div>
            </div>

            {{-- Konfirmasi Barang Diterima (Checklist) --}}
            <template x-if="selectedOrder?.status === 'Dikirim'">
                <div class="bg-white border-[4px] border-gray-900 shadow-[12px_12px_0_var(--color-primary)] overflow-hidden">
                    <div x-show="!confirmMode" class="p-10 flex flex-col md:flex-row items-center justify-between gap-8">
                        <div class="flex items-center gap-8">
                            <div class="w-20 h-20 bg-primary/10 border-[4px] border-primary flex items-center justify-center text-4xl shadow-[6px_6px_0_var(--color-gray-900)]">
                                🚚
                            </div>
                            <div>
                                <h4 class="font-headline font-black text-2xl text-primary uppercase italic leading-tight">Verifikasi & Konfirmasi</h4>
                                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mt-2">Mohon verifikasi kondisi barang sebelum melakukan konfirmasi selesai.</p>
                            </div>
                        </div>
                        <button @click="confirmMode = true"
                            class="w-full md:w-auto bg-primary text-white px-10 py-5 font-headline font-black text-sm uppercase tracking-widest shadow-[8px_8px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-1 active:shadow-none transition-all flex items-center justify-center gap-3">
                            <span>Mulai Konfirmasi</span>
                        </button>
                    </div>

                    <div x-show="confirmMode" x-collapse class="bg-neutral-light p-10 border-t-[4px] border-gray-900">
                        <div class="flex justify-between items-center mb-8">
                            <h4 class="font-headline font-black text-xl text-primary uppercase italic">Checklist Verifikasi Fisik Barang</h4>
                            <button @click="confirmMode = false" class="text-[10px] font-black uppercase text-slate-400 hover:text-gray-900 transition-colors">← Batal</button>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
                            <label class="bg-white border-[4px] border-gray-900 p-6 flex flex-col items-center gap-4 cursor-pointer hover:border-primary transition-all text-center group" :class="checklist.qty ? 'shadow-[8px_8px_0_var(--color-primary)] -translate-y-1' : 'shadow-none'">
                                <input type="checkbox" x-model="checklist.qty" class="w-8 h-8 border-[3px] border-gray-900 text-primary focus:ring-0">
                                <div>
                                    <p class="text-[11px] font-black uppercase tracking-widest text-gray-900">Volume PCS Sesuai</p>
                                    <p class="text-[8px] font-bold text-slate-400 uppercase mt-1">Cek jumlah botol/dus</p>
                                </div>
                            </label>
                            <label class="bg-white border-[4px] border-gray-900 p-6 flex flex-col items-center gap-4 cursor-pointer hover:border-primary transition-all text-center group" :class="checklist.quality ? 'shadow-[8px_8px_0_var(--color-primary)] -translate-y-1' : 'shadow-none'">
                                <input type="checkbox" x-model="checklist.quality" class="w-8 h-8 border-[3px] border-gray-900 text-primary focus:ring-0">
                                <div>
                                    <p class="text-[11px] font-black uppercase tracking-widest text-gray-900">Kemasan Utuh</p>
                                    <p class="text-[8px] font-bold text-slate-400 uppercase mt-1">Tidak bocor / segel OK</p>
                                </div>
                            </label>
                            <label class="bg-white border-[4px] border-gray-900 p-6 flex flex-col items-center gap-4 cursor-pointer hover:border-primary transition-all text-center group" :class="checklist.original ? 'shadow-[8px_8px_0_var(--color-primary)] -translate-y-1' : 'shadow-none'">
                                <input type="checkbox" x-model="checklist.original" class="w-8 h-8 border-[3px] border-gray-900 text-primary focus:ring-0">
                                <div>
                                    <p class="text-[11px] font-black uppercase tracking-widest text-gray-900">Barang Original</p>
                                    <p class="text-[8px] font-bold text-slate-400 uppercase mt-1">Sesuai standar pabrik</p>
                                </div>
                            </label>
                        </div>

                        <form x-ref="confirmForm" action="{{ route('distributor.history.confirm') }}" method="POST">
                            @csrf
                            <input type="hidden" name="order_id" :value="selectedOrder?.id">
                            <button @click.prevent="confirmReceived(); $refs.confirmForm.submit()"
                                :disabled="!canConfirm()"
                                :class="canConfirm() ? 'bg-primary shadow-[8px_8px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-1 active:shadow-none' : 'bg-slate-300 shadow-none opacity-50 cursor-not-allowed'"
                                class="w-full bg-gray-900 text-white py-6 font-headline font-black text-base uppercase tracking-widest transition-all flex items-center justify-center gap-3">
                                <span x-text="canConfirm() ? 'Kirim Konfirmasi Selesai' : 'Harap Centang Semua Checklist Di Atas'"></span>
                                <svg x-show="canConfirm()" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </template>
        </div>

        {{-- Info Stok Update --}}
        <div class="mt-10 bg-secondary/5 border-[3px] border-secondary border-dashed p-6">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 bg-secondary text-white flex items-center justify-center font-bold">!</div>
                <div>
                    <p class="text-secondary font-headline font-black text-[11px] tracking-widest uppercase mb-1 italic">Penting: Alur Stok</p>
                    <p class="text-gray-600 text-[10px] font-bold leading-relaxed uppercase">
                        Stok gudang Anda akan diperbarui secara otomatis setelah Anda mengonfirmasi <strong class="text-gray-900">Selesai</strong> di halaman ini. Jika barang sudah sampai namun Anda lupa konfirmasi, Admin dapat menyelesaikan pesanan secara manual untuk memastikan integritas data distribusi.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
