<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">PORTAL DISTRIBUTOR</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>PESANAN MASUK</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.distributor._menu')</x-slot:menuSlot>

    <div class="max-w-[1400px] mx-auto w-full" x-data="{
        viewMode: 'list',
        selectedOrder: null,
        cancelMode: false,
        cancelReason: '',
        waSent: false,
        openOrder(order) {
            this.selectedOrder = order;
            this.cancelMode = false;
            this.cancelReason = '';
            this.waSent = false;
            this.viewMode = 'detail';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        goBack() {
            this.viewMode = 'list';
            this.selectedOrder = null;
            this.cancelMode = false;
            this.waSent = false;
        },
        getWaLink() {
            const phone = (this.selectedOrder?.phone ?? '').replace(/\D/g, '');
            const id = this.selectedOrder?.id ?? '';
            const status = this.selectedOrder?.status;
            const msg = `Halo, Distributor CeeKlin di sini.\n\nUpdate pesanan ${id}:\nStatus saat ini: *${status.toUpperCase()}*\n\nTerima kasih!`;
            return 'https://wa.me/' + (phone.startsWith('0') ? '62' + phone.substring(1) : phone) + '?text=' + encodeURIComponent(msg);
        }
    }">

        {{-- ========================= --}}
        {{-- VIEW: LIST PESANAN MASUK --}}
        {{-- ========================= --}}
        <div x-show="viewMode === 'list'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <div>
                    <h2 class="font-headline font-black text-2xl text-primary tracking-tighter uppercase">Pesanan Masuk</h2>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-1">Kelola & Proses Pesanan dari Reseller</p>
                </div>
                {{-- Filter Status --}}
                <div class="flex gap-2 flex-wrap">
                    <div class="relative">
                        <select aria-label="Filter status pesanan" class="appearance-none bg-white border-[3px] border-gray-900 px-4 py-2 text-xs font-bold uppercase tracking-widest text-primary focus:outline-none focus:border-secondary cursor-pointer pr-8">
                            <option>Semua Status</option>
                            <option>Menunggu Proses</option>
                            <option>Dikemas</option>
                            <option>Dikirim</option>
                            <option>Selesai</option>
                            <option>Dibatalkan</option>
                        </select>
                        <div class="absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel --}}
            <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">
                {{-- Header Kolom (Desktop) --}}
                <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-4 bg-gray-900">
                    <div class="col-span-3 text-[10px] font-headline font-bold text-white uppercase tracking-widest">Reseller</div>
                    <div class="col-span-2 text-[10px] font-headline font-bold text-white uppercase tracking-widest text-center">Vol (PCS)</div>
                    <div class="col-span-2 text-[10px] font-headline font-bold text-white uppercase tracking-widest text-right">Total</div>
                    <div class="col-span-3 text-[10px] font-headline font-bold text-white uppercase tracking-widest">Status & Waktu</div>
                    <div class="col-span-2 text-[10px] font-headline font-bold text-white uppercase tracking-widest text-right">Aksi</div>
                </div>

                {{-- BACKEND-TODO: Loop dari Order::where('distributor_id', Auth::id())->orderBy('created_at', 'desc')->get() --}}
                <div class="divide-y-2 divide-neutral-border">
                    @php
                    $dummies = [
                        ['id' => 'ORD-201', 'reseller' => 'Ahmad Fauzi', 'phone' => '08123456789', 'city' => 'Bandung', 'qty' => 100, 'total' => 'Rp 1.500.000', 'status' => 'Menunggu Proses', 'date' => 'Hari ini, 10:30', 'statusClass' => 'border-red-400 text-red-700 bg-red-50'],
                        ['id' => 'ORD-198', 'reseller' => 'Budi Santoso', 'phone' => '08987654321', 'city' => 'Cimahi', 'qty' => 50, 'total' => 'Rp 750.000', 'status' => 'Dikemas', 'date' => 'Kemarin, 14:00', 'statusClass' => 'border-yellow-500 text-yellow-800 bg-yellow-50'],
                        ['id' => 'ORD-195', 'reseller' => 'Siti Aminah', 'phone' => '08111222333', 'city' => 'Bekasi', 'qty' => 75, 'total' => 'Rp 1.125.000', 'status' => 'Dikirim', 'date' => '2 Hari Lalu', 'statusClass' => 'border-blue-500 text-blue-700 bg-blue-50'],
                        ['id' => 'ORD-190', 'reseller' => 'Toko Jaya Abadi', 'phone' => '08222333444', 'city' => 'Depok', 'qty' => 50, 'total' => 'Rp 750.000', 'status' => 'Selesai', 'date' => '5 Hari Lalu', 'statusClass' => 'border-green-600 text-green-700 bg-green-50'],
                    ];
                    @endphp

                    @foreach($dummies as $order)
                    <div class="flex flex-col md:grid md:grid-cols-12 gap-4 px-6 py-5 items-start md:items-center hover:bg-neutral-light transition-colors duration-150">
                        {{-- Reseller --}}
                        <div class="md:col-span-3 w-full">
                            <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Reseller</p>
                            <p class="font-bold text-sm text-gray-900 uppercase leading-tight">{{ $order['reseller'] }}</p>
                            <p class="text-[9px] font-bold text-slate-500 mt-0.5 uppercase">{{ $order['city'] }}</p>
                        </div>
                        {{-- Volume --}}
                        <div class="md:col-span-2 w-full flex justify-between md:block md:text-center">
                            <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest">Volume</p>
                            <p class="font-headline font-black text-lg text-primary tracking-tighter">{{ $order['qty'] }}</p>
                        </div>
                        {{-- Total --}}
                        <div class="md:col-span-2 w-full flex justify-between md:block md:text-right">
                            <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest">Total</p>
                            <p class="font-bold text-sm text-gray-900">{{ $order['total'] }}</p>
                        </div>
                        {{-- Status --}}
                        <div class="md:col-span-3 w-full">
                            <p class="md:hidden text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Status</p>
                            <span class="px-2 py-1 border-2 {{ $order['statusClass'] }} text-[9px] font-bold uppercase tracking-widest block w-max mb-1">{{ $order['status'] }}</span>
                            <p class="text-[9px] font-bold text-slate-400 uppercase">{{ $order['date'] }}</p>
                        </div>
                        {{-- Aksi --}}
                        <div class="md:col-span-2 w-full flex justify-start md:justify-end">
                            @if($order['status'] !== 'Selesai' && $order['status'] !== 'Dibatalkan')
                                <button @click="openOrder({{ json_encode($order) }})"
                                    class="bg-primary text-white px-4 py-2 text-[10px] font-bold uppercase tracking-widest border-2 border-gray-900 hover:bg-primary-hover shadow-[3px_3px_0_var(--color-gray-900)] active:translate-y-0.5 active:shadow-none transition-all">
                                    Proses
                                </button>
                            @else
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $order['status'] === 'Selesai' ? 'Tuntas ✓' : 'Dibatalkan' }}</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ======================== --}}
        {{-- VIEW: DETAIL / PROSES   --}}
        {{-- ======================== --}}
        <div x-show="viewMode === 'detail'" style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">

            {{-- Header + Tombol Kembali --}}
            <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
                <div>
                    <h2 class="font-headline font-black text-2xl text-primary tracking-tighter uppercase">Kelola Pesanan</h2>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1" x-text="'No. Order: ' + selectedOrder?.id"></p>
                </div>
                <button @click="goBack()"
                    class="flex items-center gap-2 bg-white text-gray-900 px-4 py-2 text-[10px] font-bold uppercase tracking-widest border-[3px] border-gray-900 hover:bg-neutral-light transition-colors shadow-[4px_4px_0_var(--color-gray-900)] active:translate-y-0.5 active:shadow-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali
                </button>
            </div>

            <div class="bg-white border-[6px] border-gray-900 shadow-[12px_12px_0_var(--color-secondary)] w-full max-w-3xl flex flex-col">

                {{-- Info Ringkas --}}
                <div class="p-6 md:p-8 flex flex-col gap-6 bg-neutral border-b-[4px] border-gray-900">
                    <div class="bg-white border-[3px] border-gray-900 p-5 shadow-[4px_4px_0_var(--color-gray-900)] grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div>
                            <p class="text-[9px] font-bold text-secondary uppercase tracking-widest mb-1">Reseller</p>
                            <h4 class="font-headline font-black text-lg text-primary uppercase" x-text="selectedOrder?.reseller"></h4>
                            <p class="text-sm font-bold text-slate-500 uppercase" x-text="selectedOrder?.city"></p>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-secondary uppercase tracking-widest mb-1">Volume Pesanan</p>
                            <h4 class="font-headline font-black text-3xl text-gray-900 tracking-tighter">
                                <span x-text="selectedOrder?.qty"></span>
                                <span class="text-base text-slate-400 font-normal">PCS</span>
                            </h4>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-secondary uppercase tracking-widest mb-1">Total Pembayaran</p>
                            <h4 class="font-headline font-black text-xl text-primary" x-text="selectedOrder?.total"></h4>
                            <span class="text-[9px] font-bold text-green-600 uppercase tracking-widest">Sudah Dibayar ✓</span>
                        </div>
                    </div>
                </div>

                {{-- Form Update Status --}}
                <div x-show="!cancelMode" x-transition>
                    {{-- BACKEND-TODO: action ke DistributorController@updateOrderStatus + @csrf --}}
                    <form action="/dashboard/distributor/incoming-orders/update-status" method="POST">
                        @csrf
                        <input type="hidden" name="order_id" :value="selectedOrder?.id">

                        <div class="p-6 md:p-8 flex flex-col gap-4 bg-white border-b-[4px] border-gray-900">
                            <label class="text-[10px] font-bold text-gray-900 uppercase tracking-widest">Update Status Pesanan</label>

                            {{-- Status Options --}}
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4" x-data="{ newStatus: selectedOrder?.status ?? 'Menunggu Proses' }">
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="status" value="Menunggu Proses" x-model="newStatus" class="peer sr-only">
                                    <div class="w-full bg-neutral-light border-[3px] border-gray-300 p-4 peer-checked:border-red-500 peer-checked:bg-red-50 transition-colors text-center">
                                        <div class="w-8 h-8 rounded-full border-2 border-gray-300 peer-checked:border-red-500 mx-auto mb-2 flex items-center justify-center">
                                            <span class="text-[10px] font-black text-gray-400">1</span>
                                        </div>
                                        <p class="font-headline font-bold text-[10px] uppercase text-gray-500">Menunggu</p>
                                    </div>
                                </label>

                                <label class="relative cursor-pointer">
                                    <input type="radio" name="status" value="Dikemas" x-model="newStatus" class="peer sr-only">
                                    <div class="w-full bg-neutral-light border-[3px] border-gray-300 p-4 peer-checked:border-yellow-500 peer-checked:bg-yellow-50 transition-colors text-center">
                                        <div class="w-8 h-8 rounded-full border-2 border-gray-300 peer-checked:border-yellow-500 mx-auto mb-2 flex items-center justify-center">
                                            <span class="text-[10px] font-black text-gray-400">2</span>
                                        </div>
                                        <p class="font-headline font-bold text-[10px] uppercase text-gray-500">Dikemas</p>
                                    </div>
                                </label>

                                <label class="relative cursor-pointer">
                                    <input type="radio" name="status" value="Dikirim" x-model="newStatus" class="peer sr-only">
                                    <div class="w-full bg-neutral-light border-[3px] border-gray-300 p-4 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-colors text-center">
                                        <div class="w-8 h-8 rounded-full border-2 border-gray-300 peer-checked:border-blue-500 mx-auto mb-2 flex items-center justify-center">
                                            <span class="text-[10px] font-black text-gray-400">3</span>
                                        </div>
                                        <p class="font-headline font-bold text-[10px] uppercase text-gray-500">Dikirim</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Aksi Simpan + Tombol Batalkan --}}
                        <div class="p-6 md:p-8 bg-neutral border-t-0 flex flex-col gap-4">
                            <div class="flex flex-col sm:flex-row gap-4">
                                <button type="button" @click="cancelMode = true"
                                    class="w-full sm:w-auto border-[3px] border-red-400 text-red-600 px-6 py-3 font-headline font-bold text-xs uppercase tracking-widest hover:bg-red-50 transition-colors">
                                    Batalkan Pesanan
                                </button>
                                <button type="submit"
                                    class="flex-1 bg-primary text-white border-[3px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)] px-6 py-4 font-headline font-black text-sm uppercase tracking-widest hover:bg-primary-hover active:translate-y-1 active:shadow-none transition-all flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    SIMPAN PERUBAHAN
                                </button>
                            </div>

                            <div class="flex flex-col gap-2 border-t-2 border-dashed border-gray-300 pt-4 mt-2">
                                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Komunikasi:</p>
                                <a x-show="!waSent" :href="getWaLink()" target="_blank" @click="waSent = true"
                                    class="w-full bg-[#25D366] text-white px-6 py-3 font-headline font-bold text-[10px] uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-[#1DA851] active:translate-y-0.5 active:shadow-none transition-all flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.347-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                    NOTIFIKASI STATUS VIA WA
                                </a>
                                <div x-show="waSent" class="w-full flex items-center justify-center gap-3 bg-green-50 border-4 border-green-600 p-3" x-transition>
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                                    <span class="font-headline font-black text-[10px] text-green-700 uppercase tracking-widest">Notifikasi Terkirim ✓</span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Form Batalkan Pesanan --}}
                <div x-show="cancelMode" style="display: none;" x-transition
                     class="p-6 md:p-8 bg-red-50 border-t-[4px] border-red-600">
                    <p class="text-[10px] font-bold text-red-700 uppercase tracking-widest mb-3">⚠️ Alasan Pembatalan (Wajib Diisi)</p>
                    <p class="text-xs font-bold text-red-600 mb-4 leading-relaxed">Stok akan dikembalikan otomatis. Reseller akan mendapat notifikasi WA. Refund uang diproses manual oleh Admin.</p>

                    {{-- BACKEND-TODO: action ke DistributorController@cancelOrder + @csrf --}}
                    <form action="/dashboard/distributor/incoming-orders/cancel" method="POST">
                        @csrf
                        <input type="hidden" name="order_id" :value="selectedOrder?.id">
                        <textarea name="cancel_reason" x-model="cancelReason" rows="3" required
                            placeholder="Contoh: Stok tidak mencukupi untuk saat ini..."
                            class="w-full bg-white border-2 border-red-300 px-4 py-3 text-sm font-bold text-gray-900 focus:outline-none focus:border-red-600 resize-none mb-4 placeholder:text-slate-400"></textarea>

                        <div class="flex gap-4">
                            <button type="button" @click="cancelMode = false"
                                class="w-1/3 bg-white text-gray-600 border-[3px] border-gray-400 px-4 py-3 font-headline font-bold text-xs uppercase tracking-widest hover:bg-gray-50 transition-colors">
                                Urungkan
                            </button>
                            <button type="submit" :disabled="cancelReason.trim() === ''"
                                class="w-2/3 bg-red-600 text-white border-[3px] border-red-800 shadow-[4px_4px_0_rgba(0,0,0,0.2)] px-4 py-3 font-headline font-black text-xs uppercase tracking-widest hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                                KONFIRMASI BATALKAN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
