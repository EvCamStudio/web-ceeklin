<x-layouts.dashboard bgTheme="light">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">MASTER ADMIN</span>
    </x-slot:subtitleSlot>

    <x-slot:topbarTitle>PESANAN DISTRIBUTOR</x-slot:topbarTitle>

    <x-slot:menuSlot>
        @include('dashboard.admin._menu')
    </x-slot:menuSlot>

    <div class="max-w-[1400px] mx-auto w-full pb-20" x-data="{
        viewMode: 'list',
        selectedOrder: null,
        newStatus: '',
        
        openOrder(order) {
            this.selectedOrder = order;
            this.newStatus = order.status;
            this.viewMode = 'detail';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        goBack() {
            this.viewMode = 'list';
            this.selectedOrder = null;
        }
    }">
        {{-- VIEW: LIST PESANAN --}}
        <div x-show="viewMode === 'list'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">

            {{-- Header --}}
            <div class="mb-10">
                <h2 class="font-headline font-black text-4xl text-primary tracking-tighter uppercase leading-none italic">Antrean Restock</h2>
                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-[0.2em] mt-4 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-secondary animate-ping"></span>
                    Kelola Distribusi Stok ke Gudang Distributor Wilayah
                </p>
            </div>

            {{-- Order Cards (Real Data) --}}
            <div class="space-y-6">
                @forelse($orders as $order)
                <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)] group hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                    <div class="flex flex-col lg:flex-row">
                        {{-- ID & Info Column --}}
                        <div class="lg:w-80 p-6 bg-neutral-light border-b-[4px] lg:border-b-0 lg:border-r-[4px] border-gray-900 relative">
                            <div class="flex justify-between items-start mb-4">
                                <span class="bg-gray-900 text-white text-[9px] font-black px-2 py-1 uppercase tracking-widest">{{ $order->order_number }}</span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</span>
                            </div>
                            <h4 class="font-headline font-black text-xl text-primary uppercase leading-tight italic">{{ $order->user->name }}</h4>
                            <div class="mt-2 flex items-center gap-2">
                                <span class="text-[10px] font-bold text-slate-500 uppercase">{{ $order->user->city_name }}</span>
                            </div>
                        </div>

                        {{-- Order Content --}}
                        <div class="flex-1 p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 sm:gap-8 italic">
                            <div class="w-full sm:w-auto">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 italic">Volume Produk</p>
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-gray-100 border-2 border-gray-900 flex items-center justify-center italic">
                                        <img src="/images/hero-bottle.jpeg" class="w-8 opacity-50 grayscale mix-blend-multiply italic">
                                    </div>
                                    <div>
                                        <p class="font-bold text-sm text-gray-900 uppercase italic">CeeKlin 450ml (x{{ number_format($order->quantity, 0, ',', '.') }})</p>
                                        <p class="text-[9px] font-bold text-green-600 uppercase tracking-widest italic">Pembayaran Terverifikasi ✓</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-wrap sm:flex-nowrap gap-6 sm:gap-10 w-full sm:w-auto justify-between items-center pt-6 sm:pt-0 border-t-2 sm:border-t-0 border-dashed border-gray-200 italic">
                                <div class="text-left sm:text-right">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 italic">Total</p>
                                    <p class="font-headline font-black text-xl text-primary tracking-tighter italic">Rp {{ number_format($order->total_price ?? ($order->quantity * 13000), 0, ',', '.') }}</p>
                                </div>
                                <div class="text-center min-w-[100px] italic">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 italic">Status</p>
                                    <span class="px-2 py-1 border-2 text-[9px] font-black uppercase tracking-widest block italic {{ $order->statusColor }}">
                                        {{ $order->status }}
                                    </span>
                                </div>
                                
                                <button @click="openOrder({ 
                                    id: '{{ $order->order_number }}', 
                                    db_id: {{ $order->id }},
                                    name: '{{ $order->user->name }}', 
                                    city: '{{ $order->user->city_name }}', 
                                    qty: {{ $order->quantity }}, 
                                    total: 'Rp {{ number_format($order->total_price ?? ($order->quantity * 13000), 0, ',', '.') }}',
                                    status: '{{ $order->status }}',
                                    date: '{{ $order->created_at->diffForHumans() }}',
                                    phone: '{{ $order->user->phone }}',
                                    method: '{{ $order->payment_method ?? 'Manual' }}',
                                    note: '{{ $order->note }}'
                                })" 
                                    class="w-full sm:w-auto bg-primary text-white px-6 py-4 font-headline font-black text-[10px] uppercase tracking-widest shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-1 active:shadow-none transition-all flex items-center justify-center gap-3 italic">
                                    <span>Kelola Pengiriman</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7-7 7M3 12h18"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="py-20 text-center bg-white border-[4px] border-dashed border-gray-200 shadow-[8px_8px_0_rgba(0,0,0,0.02)]">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Belum ada pesanan distributor masuk.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- VIEW: DETAIL --}}
        <div x-show="viewMode === 'detail'" style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-10"
             x-transition:enter-end="opacity-100 translate-x-0">

            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-10 gap-6">
                <div class="flex items-center gap-6 italic">
                    <button @click="goBack()" class="w-10 h-10 bg-white border-[3px] border-gray-900 flex items-center justify-center shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-neutral-light transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </button>
                    <div>
                        <div class="flex items-center gap-3 italic">
                            <h2 class="font-headline font-black text-4xl text-primary tracking-tighter uppercase italic leading-none" x-text="selectedOrder?.id"></h2>
                        </div>
                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mt-2 italic" x-text="'Distributor: ' + selectedOrder?.name + ' • ' + selectedOrder?.date"></p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start italic">
                <div class="xl:col-span-8 space-y-8 italic">
                    <div class="bg-white border-[4px] border-gray-900 p-8 shadow-[10px_10px_0_rgba(0,0,0,0.05)] italic">
                        <div class="flex flex-col md:flex-row justify-between items-center gap-4 relative italic">
                            @foreach(['Menunggu', 'Dikemas', 'Dikirim', 'Selesai'] as $i => $step)
                            <div class="flex flex-col items-center gap-3 italic">
                                <div class="w-12 h-12 flex items-center justify-center border-[4px] transition-all duration-500 italic"
                                     :class="['Menunggu', 'Dikemas', 'Dikirim', 'Selesai'].indexOf(newStatus) >= {{ $i }} ? 'bg-primary border-gray-900 text-white shadow-[4px_4px_0_rgba(0,0,0,0.2)]' : 'bg-white border-neutral-light text-slate-300'">
                                    <span class="font-headline font-black text-xs">{{ $i + 1 }}</span>
                                </div>
                                <span class="text-[9px] font-black uppercase tracking-widest text-center italic"
                                      :class="['Menunggu', 'Dikemas', 'Dikirim', 'Selesai'].indexOf(newStatus) >= {{ $i }} ? 'text-gray-900' : 'text-slate-300'">{{ $step }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 italic">
                        <div class="bg-white border-[4px] border-gray-900 shadow-[10px_10px_0_var(--color-primary-darkest)] italic">
                            <div class="bg-gray-900 px-6 py-3 italic">
                                <span class="text-[10px] font-black text-white uppercase tracking-widest italic">Informasi Distributor</span>
                            </div>
                            <div class="p-6 italic">
                                <h4 class="font-headline font-black text-xl text-primary uppercase italic" x-text="selectedOrder?.name"></h4>
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-2 italic" x-text="selectedOrder?.phone"></p>
                                <p class="text-xs font-bold text-gray-900 mt-4 uppercase italic" x-text="selectedOrder?.city"></p>
                            </div>
                        </div>

                        <div class="bg-white border-[4px] border-gray-900 shadow-[10px_10px_0_var(--color-secondary)] italic">
                            <div class="bg-secondary px-6 py-3 italic">
                                <span class="text-[10px] font-black text-white uppercase tracking-widest italic">Tagihan</span>
                            </div>
                            <div class="p-6 italic text-right">
                                <p class="text-[9px] font-black text-slate-400 uppercase italic">Total Tagihan</p>
                                <p class="text-3xl font-headline font-black text-primary tracking-tighter italic" x-text="selectedOrder?.total"></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-neutral-light p-6 border-[4px] border-gray-900 italic">
                        <p class="text-[9px] font-black text-secondary uppercase tracking-widest mb-1 italic">Catatan Distributor:</p>
                        <p class="text-xs font-bold text-gray-600 italic" x-text="selectedOrder?.note || 'Tidak ada catatan.'"></p>
                    </div>
                </div>

                <div class="xl:col-span-4 space-y-8 italic">
                    <div class="bg-white border-[4px] border-gray-900 shadow-[12px_12px_0_var(--color-primary-darkest)] italic">
                        <div class="bg-primary p-6 border-b-[4px] border-gray-900 italic">
                            <h3 class="font-headline font-black text-white text-xl uppercase tracking-tighter italic">Update Status</h3>
                        </div>
                        <div class="p-8 italic">
                            <form action="{{ route('admin.distributor-orders.update-status') }}" method="POST" class="space-y-6 italic">
                                @csrf
                                <input type="hidden" name="order_id" :value="selectedOrder?.db_id">
                                <select name="status" x-model="newStatus" class="w-full bg-neutral-light border-[3px] border-gray-900 px-4 py-3 font-headline font-black text-xs uppercase tracking-widest text-primary focus:outline-none focus:border-secondary italic">
                                    <option value="Menunggu">Menunggu</option>
                                    <option value="Dikemas">Dikemas</option>
                                    <option value="Dikirim">Dikirim</option>
                                    <option value="Selesai">Selesai</option>
                                </select>

                                <div x-show="newStatus === 'Dikirim'" class="space-y-4 italic">
                                    <input type="text" name="courier" placeholder="Nama Kurir / Ekspedisi" class="w-full bg-white border-2 border-gray-900 px-3 py-2 text-[11px] font-bold uppercase tracking-widest italic">
                                    <input type="text" name="tracking_number" placeholder="Nomor Resi" class="w-full bg-white border-2 border-gray-900 px-3 py-2 text-[11px] font-bold uppercase tracking-widest italic">
                                </div>

                                <button type="submit" class="w-full bg-primary text-white py-4 font-headline font-black text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-1 active:shadow-none italic">
                                    Simpan Perubahan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
