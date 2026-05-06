<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">PORTAL RESELLER</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>BUAT PESANAN</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.reseller._menu')</x-slot:menuSlot>

    <div class="max-w-6xl mx-auto pb-24" x-data="{ 
        currentView: 'checkout',
        qty: {{ $upline->stock >= 50 ? 50 : 0 }}, 
        uplineStock: {{ $upline->stock }},
        price: {{ $price }},
        paymentMethod: 'qris',
        selectedSubMethod: '',
        address: '{{ addslashes(Auth::user()->address ?? 'Alamat belum diatur') }}',
        pic: '{{ addslashes(Auth::user()->name) }} ({{ Auth::user()->phone ?? 'No. Telp belum diatur' }})',
        note: '',
        paymentError: false,
        isProcessing: false,
        orderSuccess: false,

        formatRupiah(number) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number); },

        get total() { return (this.qtyInvalid ? 0 : this.qty) * this.price; },
        get qtyInvalid() { return !this.qty || this.qty < 50; },

        get displayPayment() {
            if (this.paymentMethod === 'qris') return 'QRIS (Otomatis)';
            if (this.selectedSubMethod) return this.selectedSubMethod.replace('va_', 'VA ').replace('wallet_', '').toUpperCase();
            return 'Belum Dipilih';
        },

        getWaDistributorLink() {
            const distributorPhone = '{{ preg_replace('/[^0-9]/', '', $upline->phone ?? '') }}';
            const msg = `Halo {{ $upline->name ?? 'Distributor' }}, saya Reseller baru saja melakukan pemesanan produk sebanyak *${this.qty} PCS*. 

Metode Pembayaran: *${this.displayPayment}*
Total Tagihan: *${this.formatRupiah(this.total)}*

Mohon segera divalidasi dan diproses pengirimannya. Terima kasih!`;
            return 'https://wa.me/' + distributorPhone + '?text=' + encodeURIComponent(msg);
        },

        toggleMethod(method) {
            if (this.paymentMethod === method) {
                this.paymentMethod = '';
                this.selectedSubMethod = '';
            } else {
                this.paymentMethod = method;
                this.selectedSubMethod = '';
                this.paymentError = false;
            }
        },

        goToConfirm() {
            if (this.qtyInvalid) return;
            if (this.paymentMethod !== 'qris' && !this.selectedSubMethod) {
                this.paymentError = true;
                document.getElementById('payment-section').scrollIntoView({ behavior: 'smooth' });
                return;
            }
            this.paymentError = false;
            this.currentView = 'confirm';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        submitOrder() {
            if (this.qtyInvalid) return;
            this.isProcessing = true;
            this.$refs.orderForm.submit();
        }
    }">
        <form x-ref="orderForm" action="{{ route('reseller.order.store') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="quantity" :value="qty">
            <input type="hidden" name="payment_method" :value="paymentMethod">
            <input type="hidden" name="payment_sub_method" :value="selectedSubMethod">
            <input type="hidden" name="shipping_address" :value="address">
            <input type="hidden" name="shipping_note" :value="note">
        </form>

        {{-- VIEW: SUCCESS PAGE --}}
        @if(session('order_success'))
            <div class="flex flex-col items-center justify-center py-20 text-center animate-in">
                <div
                    class="w-32 h-32 bg-primary rounded-full flex items-center justify-center border-[6px] border-gray-900 shadow-[10px_10px_0_var(--color-gray-900)] mb-10 animate-bounce">
                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h2
                    class="font-headline font-black text-6xl text-primary uppercase italic tracking-tighter leading-none mb-4">
                    PESANAN TERKIRIM!</h2>
                <p class="text-xl font-bold text-gray-900 max-w-md mx-auto mb-10 leading-relaxed">Pemesanan Anda sedang
                    diproses oleh Distributor. Silakan selesaikan pembayaran sesuai metode yang dipilih.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-xl">
                    <a href="{{ $upline ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $upline->phone) : '#' }}"
                        target="_blank"
                        class="md:col-span-2 bg-[#25D366] text-white py-5 px-8 font-headline font-black text-lg uppercase tracking-widest border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)] hover:bg-[#1DA851] hover:-translate-y-1 hover:shadow-[10px_10px_0_var(--color-gray-900)] transition-all flex items-center justify-center gap-3 group">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.347-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                        </svg>
                        INFOKAN DISTRIBUTOR KE WA
                    </a>
                    <a href="/dashboard/reseller/history"
                        class="bg-primary text-white py-5 px-8 font-headline font-black text-lg uppercase tracking-widest border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)] hover:bg-primary-hover hover:-translate-y-1 hover:shadow-[10px_10px_0_var(--color-gray-900)] transition-all text-center">LIHAT
                        RIWAYAT</a>
                    <button @click="window.location.reload()"
                        class="bg-white text-gray-900 py-5 px-8 font-headline font-black text-lg uppercase tracking-widest border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-primary)] hover:bg-neutral-light transition-all text-center">BELANJA
                        LAGI</button>
                </div>
            </div>
        @else

            {{-- VIEW: CHECKOUT MAIN --}}
            <div x-show="currentView === 'checkout'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                {{-- Header Checkout --}}
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10">
                    <div class="flex items-center gap-5">
                        <div
                            class="w-14 h-14 bg-primary flex items-center justify-center border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)]">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <div>
                            <h1
                                class="font-headline font-black text-4xl text-primary uppercase tracking-tighter italic leading-none">
                                Checkout</h1>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em] mt-1.5">Portal Reseller
                                &bull; Stok Terjamin</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

                    {{-- KOLOM UTAMA (KIRI) --}}
                    <div class="lg:col-span-8 space-y-8">

                        {{-- SECTION 1: ALAMAT --}}
                        <div class="bg-white border-[4px] border-gray-900 shadow-[10px_10px_0_var(--color-gray-900)]">
                            <div class="bg-gray-900 px-6 py-3.5 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-secondary" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-[11px] font-black text-white uppercase tracking-[0.2em]">Alamat
                                        Pengiriman</span>
                                </div>
                                <button @click="currentView = 'address'"
                                    class="bg-white/10 hover:bg-white/20 text-white text-[9px] font-black uppercase px-3 py-1 border border-white/30 transition-all">Ubah
                                    Alamat</button>
                            </div>
                            <div class="p-8">
                                <div class="flex flex-col md:flex-row md:items-start gap-6">
                                    <div
                                        class="flex-shrink-0 w-12 h-12 bg-neutral-light border-2 border-gray-900 flex items-center justify-center shadow-[4px_4px_0_var(--color-primary)]">
                                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h4 class="font-black text-primary uppercase text-base tracking-tight">
                                                {{ Auth::user()->name }}</h4>
                                            <span
                                                class="px-2 py-0.5 bg-secondary text-white text-[9px] font-black uppercase tracking-widest shadow-[2px_2px_0_var(--color-gray-900)]">RESELLER</span>
                                        </div>
                                        <p class="font-bold text-gray-900 text-sm italic">Tujuan Pengiriman</p>
                                        <p class="text-sm text-slate-500 mt-2 leading-relaxed" x-text="address"></p>
                                        <p class="text-xs font-bold text-gray-900 mt-3 font-mono" x-text="'PIC: ' + pic">
                                        </p>

                                        <template x-if="note">
                                            <div class="mt-4 p-3 bg-secondary/5 border-l-4 border-secondary">
                                                <p
                                                    class="text-[9px] font-black text-secondary uppercase tracking-widest mb-1">
                                                    Catatan Pesanan:</p>
                                                <p class="text-xs italic text-gray-700" x-text="note"></p>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- SECTION 2: ITEM & PENGIRIMAN --}}
                        <div
                            class="bg-white border-[4px] border-gray-900 shadow-[10px_10px_0_var(--color-primary-darkest)]">
                            <div class="bg-primary px-6 py-3.5 flex items-center gap-3">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                                </svg>
                                <span class="text-[11px] font-black text-white uppercase tracking-[0.2em]">Rincian Item &
                                    Stok</span>
                            </div>
                            <div class="p-8 space-y-8">
                                {{-- Row Produk --}}
                                <div class="flex flex-col md:flex-row gap-8 items-center">
                                    <div
                                        class="w-32 h-32 bg-gray-900 border-[4px] border-gray-900 flex-shrink-0 flex items-center justify-center shadow-[6px_6px_0_var(--color-primary)]">
                                        <img src="/images/hero-bottle.jpeg" alt="Product"
                                            class="w-full h-auto mix-blend-screen opacity-90 scale-90">
                                    </div>
                                    <div class="flex-1 flex flex-col md:flex-row justify-between items-center w-full gap-6">
                                        <div class="text-center md:text-left">
                                            <h4
                                                class="font-headline font-black text-primary uppercase text-2xl tracking-tight leading-none">
                                                CeeKlin 450ml</h4>
                                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-2">
                                                Jagonya Pembasmih Noda</p>
                                            <p class="mt-4 font-headline font-black text-gray-900 text-lg"
                                                x-text="formatRupiah(price) + ' / pcs'"></p>
                                        </div>

                                        <div class="flex flex-col items-center md:items-end gap-3">
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Jumlah
                                                    (PCS)</span>
                                                <template x-if="qty >= uplineStock && uplineStock > 0">
                                                    <span
                                                        class="px-1.5 py-0.5 bg-secondary text-white text-[8px] font-black uppercase animate-pulse">MAX</span>
                                                </template>
                                                <template x-if="uplineStock <= 0">
                                                    <span
                                                        class="px-1.5 py-0.5 bg-red-600 text-white text-[8px] font-black uppercase">STOK
                                                        HABIS</span>
                                                </template>
                                            </div>

                                            <div :class="{
                                                'border-secondary bg-red-50': qtyInvalid || qty >= uplineStock,
                                                'border-gray-900 bg-neutral-light': !qtyInvalid && qty < uplineStock
                                            }"
                                                class="flex items-center gap-3 p-1.5 border-[3px] transition-all duration-300">

                                                <button type="button" @click="if(qty > 50) qty--"
                                                    :class="qty <= 50 ? 'opacity-20 cursor-not-allowed' : 'hover:bg-primary hover:text-white'"
                                                    class="w-10 h-10 bg-white border-2 border-gray-900 flex items-center justify-center font-black transition-all text-xl shadow-[3px_3px_0_var(--color-gray-900)] active:shadow-none active:translate-y-1">−</button>

                                                <div class="relative">
                                                    <input type="number" x-model.number="qty"
                                                        @input="if(qty > uplineStock) qty = uplineStock"
                                                        :readonly="uplineStock <= 0"
                                                        class="w-24 text-center font-headline font-black text-2xl text-primary focus:outline-none bg-transparent">
                                                </div>

                                                <button type="button" @click="if(qty < uplineStock) qty++"
                                                    :class="qty >= uplineStock ? 'opacity-20 cursor-not-allowed' : 'hover:bg-primary hover:text-white'"
                                                    class="w-10 h-10 bg-white border-2 border-gray-900 flex items-center justify-center font-black transition-all text-xl shadow-[3px_3px_0_var(--color-gray-900)] active:shadow-none active:translate-y-1">+</button>
                                            </div>

                                            <p :class="qtyInvalid || qty >= uplineStock ? 'text-secondary font-black' : 'text-slate-400'"
                                                class="text-[9px] uppercase tracking-widest mt-1 transition-all">
                                                <template x-if="uplineStock <= 0">
                                                    <span class="text-red-600">STOK DISTRIBUTOR SEDANG KOSONG</span>
                                                </template>
                                                <template x-if="uplineStock > 0 && qtyInvalid">
                                                    <span>MINIMAL PEMESANAN 50 PCS</span>
                                                </template>
                                                <template x-if="uplineStock > 0 && !qtyInvalid && qty >= uplineStock">
                                                    <span>BATAS MAKSIMAL STOK TERCAPAI</span>
                                                </template>
                                                <template x-if="uplineStock > 0 && !qtyInvalid && qty < uplineStock">
                                                    <span>TERSEDIA: <span x-text="uplineStock"></span> PCS</span>
                                                </template>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Opsi Pengiriman --}}
                                <div
                                    class="bg-neutral-light border-[3px] border-dashed border-gray-300 p-6 flex flex-col md:flex-row justify-between items-center gap-4 relative overflow-hidden">
                                    <div class="absolute -left-10 -top-10 w-24 h-24 bg-secondary/5 rotate-45"></div>
                                    <div class="relative z-10 flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 bg-secondary text-white flex items-center justify-center shadow-[4px_4px_0_var(--color-gray-900)]">
                                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-gray-900 uppercase leading-none">Opsi
                                                Pengiriman Khusus</p>
                                            <p class="text-xs font-bold text-primary mt-1.5 italic">Regular &bull; Estimasi
                                                2-4 Hari Kerja</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="line-through text-slate-300 text-xs font-bold font-mono">Rp 0
                                            (Included)</span>
                                        <span
                                            class="bg-green-100 text-green-700 px-3 py-1.5 border-2 border-green-600 font-black text-[10px] uppercase shadow-[3px_3px_0_var(--color-green-700)]">Gratis
                                            Ongkir</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- SECTION 3: METODE PEMBAYARAN --}}
                        <div id="payment-section" :class="paymentError ? 'ring-4 ring-secondary/50 animate-pulse' : ''"
                            class="bg-white border-[4px] border-gray-900 shadow-[10px_10px_0_var(--color-secondary)] transition-all">
                            <div class="bg-secondary px-6 py-3.5 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" />
                                    </svg>
                                    <span class="text-[11px] font-black text-white uppercase tracking-[0.2em]">Pilih Metode
                                        Pembayaran</span>
                                </div>
                            </div>
                            <div class="p-8 space-y-6">
                                {{-- QRIS --}}
                                <div @click="toggleMethod('qris')" class="cursor-pointer group">
                                    <div :class="paymentMethod === 'qris' ? 'border-primary bg-primary/5 shadow-[4px_4px_0_var(--color-primary-darkest)]' : 'border-gray-200 bg-white'"
                                        class="flex items-center justify-between p-5 border-[3px] transition-all group-hover:border-primary">
                                        <div class="flex items-center gap-5">
                                            <div
                                                class="w-12 h-12 flex items-center justify-center bg-white border-2 border-gray-900">
                                                <svg class="w-7 h-7 text-primary font-black" fill="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M3 3h8v8H3V3zm2 2v4h4V5H5zm8-2h8v8h-8V3zm2 2v4h4V5h-4zM3 13h8v8H3v-8zm2 2v4h4v-4H5zm13-2h3v2h-3v-2zm-3 0h2v2h-2v-2zm3 3h3v2h-3v-2zm-3 0h2v2h-2v-2zm3 3h3v2h-3v-2zm-3 0h2v2h-2v-2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-base font-black text-gray-900 uppercase">QRIS (Otomatis)</p>
                                                <p
                                                    class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">
                                                    Verifikasi Instan via Midtrans</p>
                                            </div>
                                        </div>
                                        <div :class="paymentMethod === 'qris' ? 'bg-primary scale-125' : 'bg-transparent'"
                                            class="w-5 h-5 rounded-full border-[3px] border-gray-900 transition-transform">
                                        </div>
                                    </div>
                                </div>

                                {{-- Virtual Account --}}
                                <div class="space-y-0">
                                    <div @click="toggleMethod('va')" class="cursor-pointer group">
                                        <div :class="paymentMethod === 'va' ? 'border-primary bg-primary/5 shadow-[4px_4px_0_var(--color-primary-darkest)]' : 'border-gray-200 bg-white'"
                                            class="flex items-center justify-between p-5 border-[3px] transition-all group-hover:border-primary">
                                            <div class="flex items-center gap-5">
                                                <div
                                                    class="w-12 h-12 flex items-center justify-center bg-white border-2 border-gray-900 text-xs font-black text-primary italic">
                                                    VA</div>
                                                <div>
                                                    <p class="text-base font-black text-gray-900 uppercase">Virtual Account
                                                        (Transfer Bank)</p>
                                                    <p
                                                        class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">
                                                        BCA, Mandiri, BNI, BRI</p>
                                                </div>
                                            </div>
                                            <svg :class="paymentMethod === 'va' ? 'rotate-180' : ''"
                                                class="w-6 h-6 text-gray-900 transition-transform" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div x-show="paymentMethod === 'va'" x-collapse
                                        class="bg-neutral-light border-x-[3px] border-b-[3px] border-gray-900 p-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <template x-for="bank in ['BCA', 'Mandiri', 'BNI', 'BRI']">
                                            <button
                                                @click.stop="selectedSubMethod = 'va_' + bank.toLowerCase(); paymentError = false"
                                                :class="selectedSubMethod === 'va_' + bank.toLowerCase() ? 'bg-primary text-white border-primary shadow-[4px_4px_0_var(--color-primary-darkest)]' : 'bg-white text-gray-900 border-gray-200 hover:border-primary'"
                                                class="flex flex-col items-center justify-center p-4 border-[3px] transition-all">
                                                <span class="text-xs font-black uppercase tracking-widest"
                                                    x-text="bank"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>

                                {{-- E-Wallet --}}
                                <div class="space-y-0">
                                    <div @click="toggleMethod('wallet')" class="cursor-pointer group">
                                        <div :class="paymentMethod === 'wallet' ? 'border-primary bg-primary/5 shadow-[4px_4px_0_var(--color-primary-darkest)]' : 'border-gray-200 bg-white'"
                                            class="flex items-center justify-between p-5 border-[3px] transition-all group-hover:border-primary">
                                            <div class="flex items-center gap-5">
                                                <div
                                                    class="w-12 h-12 flex items-center justify-center bg-white border-2 border-gray-900 text-[9px] font-black text-primary leading-tight text-center px-1 uppercase italic">
                                                    Wallet</div>
                                                <div>
                                                    <p class="text-base font-black text-gray-900 uppercase">E-Wallet Direct
                                                    </p>
                                                    <p
                                                        class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">
                                                        DANA, OVO, ShopeePay</p>
                                                </div>
                                            </div>
                                            <svg :class="paymentMethod === 'wallet' ? 'rotate-180' : ''"
                                                class="w-6 h-6 text-gray-900 transition-transform" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div x-show="paymentMethod === 'wallet'" x-collapse
                                        class="bg-neutral-light border-x-[3px] border-b-[3px] border-gray-900 p-6 grid grid-cols-2 md:grid-cols-3 gap-4">
                                        <template x-for="w in ['DANA', 'OVO', 'ShopeePay']">
                                            <button
                                                @click.stop="selectedSubMethod = 'wallet_' + w.toLowerCase(); paymentError = false"
                                                :class="selectedSubMethod === 'wallet_' + w.toLowerCase() ? 'bg-primary text-white border-primary shadow-[4px_4px_0_var(--color-primary-darkest)]' : 'bg-white text-gray-900 border-gray-200 hover:border-primary'"
                                                class="flex flex-col items-center justify-center p-3 border-[3px] transition-all">
                                                <span class="text-[10px] font-black uppercase tracking-tight"
                                                    x-text="w"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- KOLOM RINGKASAN --}}
                    <div class="lg:col-span-4">
                        <div
                            class="bg-white border-[4px] border-gray-900 shadow-[12px_12px_0_var(--color-primary-darkest)] sticky top-10">
                            <div class="p-8 space-y-6">
                                <div class="flex flex-col gap-1">
                                    <h3
                                        class="font-headline font-black text-2xl text-primary uppercase tracking-tighter italic leading-none">
                                        Rincian Tagihan</h3>
                                    <div class="h-1 w-16 bg-secondary"></div>
                                </div>

                                <div class="space-y-4">
                                    <div
                                        class="bg-neutral-light p-4 border-2 border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)]">
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="text-[10px] font-black text-gray-900 uppercase">CeeKlin
                                                450ml</span>
                                            <span class="text-xs font-headline font-black text-primary"
                                                x-text="qty + ' PCS'"></span>
                                        </div>
                                        <div
                                            class="flex justify-between text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                                            <span>Harga Satuan</span>
                                            <span x-text="formatRupiah(price)"></span>
                                        </div>
                                    </div>

                                    <div class="space-y-3">
                                        <div
                                            class="flex justify-between items-center text-xs font-bold text-slate-500 uppercase tracking-widest">
                                            <span>Subtotal Produk</span>
                                            <span class="text-gray-900 font-mono" x-text="formatRupiah(total)"></span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center text-xs font-bold text-slate-500 uppercase tracking-widest">
                                            <span>Ongkos Kirim</span>
                                            <span class="text-green-600 font-black">Rp 0 (FREE)</span>
                                        </div>
                                        <div
                                            class="flex justify-between items-center pt-3 border-t-2 border-dashed border-gray-200">
                                            <span class="text-[9px] font-black text-slate-400 uppercase">Metode</span>
                                            <span
                                                :class="paymentMethod === 'qris' || selectedSubMethod ? 'text-primary' : 'text-secondary animate-pulse'"
                                                class="text-[10px] font-black uppercase" x-text="displayPayment"></span>
                                        </div>
                                    </div>

                                    <div class="border-t-4 border-double border-gray-900 pt-6 mt-6">
                                        <div class="flex flex-col gap-1.5">
                                            <span
                                                class="text-[10px] font-black text-primary uppercase tracking-[0.3em]">Total
                                                Tagihan Akhir</span>
                                            <span
                                                class="text-primary font-headline font-black text-4xl tracking-tighter italic leading-none"
                                                x-text="formatRupiah(total)"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-6">
                                    <button type="button" @click="goToConfirm()" :disabled="qtyInvalid || qty > uplineStock"
                                        :class="qtyInvalid || qty > uplineStock ? 'opacity-50 grayscale cursor-not-allowed shadow-none translate-y-1' : 'hover:bg-primary-hover hover:-translate-x-1 hover:-translate-y-1 hover:shadow-[12px_12px_0_var(--color-gray-900)]'"
                                        class="w-full bg-primary text-white py-6 font-headline font-black text-xl uppercase tracking-widest border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-gray-900)] active:translate-x-0 active:translate-y-0 active:shadow-none transition-all flex flex-col items-center justify-center group relative overflow-hidden">
                                        <span class="relative z-10 flex items-center gap-3">
                                            BUAT PESANAN
                                            <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- VIEW: CONFIRMATION PAGE --}}
            <div x-show="currentView === 'confirm'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
                class="space-y-10" x-cloak>
                <div class="flex items-center gap-6 mb-10">
                    <x-ui.back-button @click="currentView = 'checkout'" />
                    <div>
                        <h2
                            class="font-headline font-black text-4xl text-primary uppercase italic tracking-tighter leading-none">
                            Konfirmasi Pesanan</h2>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2">Pastikan semua data
                            sudah sesuai</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-8">
                        <div class="bg-white border-[4px] border-gray-900 p-8 shadow-[8px_8px_0_var(--color-gray-900)]">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-4">Tujuan
                                Pengiriman</span>
                            <p class="font-bold text-gray-900 text-sm leading-relaxed mb-3 italic" x-text="address"></p>
                            <p class="text-xs font-black text-primary font-mono" x-text="'PIC: ' + pic"></p>
                        </div>
                        <div
                            class="bg-white border-[4px] border-gray-900 p-8 shadow-[8px_8px_0_var(--color-primary-darkest)] flex items-center gap-6">
                            <div
                                class="w-20 h-20 bg-gray-900 flex items-center justify-center border-2 border-gray-900 shadow-[4px_4px_0_var(--color-primary)]">
                                <img src="/images/hero-bottle.jpeg" class="w-full opacity-70 scale-90 mix-blend-screen">
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Item Pesanan
                                </p>
                                <p class="font-black text-gray-900 uppercase text-lg">CeeKlin 450ml</p>
                                <p class="text-2xl font-headline font-black text-primary tracking-tight leading-none mt-1"
                                    x-text="qty + ' PCS'"></p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <div class="bg-white border-[4px] border-gray-900 p-8 shadow-[8px_8px_0_var(--color-secondary)]">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-4">Metode
                                Pembayaran</span>
                            <p class="text-3xl font-headline font-black text-gray-900 uppercase tracking-tighter"
                                x-text="displayPayment"></p>
                            <p
                                class="text-[9px] font-bold text-secondary mt-3 uppercase tracking-widest flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                                Status: Menunggu Pembayaran
                            </p>
                        </div>
                        <div class="bg-gray-900 p-8 shadow-[8px_8px_0_var(--color-primary)] relative overflow-hidden">
                            <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-primary/10 rotate-12"></div>
                            <span
                                class="text-[10px] font-black text-primary uppercase tracking-widest block mb-2 relative z-10">Total
                                Tagihan Akhir</span>
                            <p class="text-5xl font-headline font-black text-white italic leading-none relative z-10 tracking-tighter"
                                x-text="formatRupiah(total)"></p>
                            <div class="pt-10 relative z-10">
                                <button @click="submitOrder()" :disabled="isProcessing"
                                    class="w-full bg-primary text-white py-6 font-headline font-black text-xl uppercase tracking-widest border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)] hover:bg-primary-hover active:translate-y-1 transition-all flex items-center justify-center gap-4 group">
                                    <span x-show="!isProcessing">KONFIRMASI & BAYAR</span>
                                    <span x-show="isProcessing" class="flex items-center gap-3">
                                        <svg class="animate-spin h-6 w-6 text-white" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4" fill="none"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        MEMPROSES...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- VIEW: ADDRESS EDIT --}}
            <div x-show="currentView === 'address'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0"
                class="space-y-10" x-cloak>
                <div class="flex items-center gap-6 mb-10">
                    <x-ui.back-button @click="currentView = 'checkout'" />
                    <h2 class="font-headline font-black text-3xl text-primary uppercase leading-none">Ubah Alamat Pengiriman
                    </h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="bg-white border-[4px] border-gray-900 p-8 shadow-[8px_8px_0_var(--color-gray-900)]">
                        <label class="text-[10px] font-black text-primary uppercase tracking-widest block mb-4">Detail
                            Alamat Lengkap</label>
                        <textarea x-model="address" rows="6"
                            class="w-full bg-neutral-light border-[3px] border-gray-900 p-5 font-bold text-gray-900 focus:outline-none focus:border-primary resize-none"></textarea>
                        <div class="mt-6">
                            <label class="text-[10px] font-black text-primary uppercase tracking-widest block mb-3">Nama
                                Penerima & WhatsApp</label>
                            <input type="text" x-model="pic"
                                class="w-full bg-neutral-light border-[3px] border-gray-900 p-5 font-bold text-gray-900 focus:outline-none focus:border-primary">
                        </div>
                    </div>
                    <div class="bg-white border-[4px] border-gray-900 p-8 shadow-[8px_8px_0_var(--color-secondary)]">
                        <label class="text-[10px] font-black text-secondary uppercase tracking-widest block mb-4">Catatan
                            Tambahan (Opsional)</label>
                        <textarea x-model="note" rows="6" placeholder="Misal: Warna tutup botol biru..."
                            class="w-full bg-neutral-light border-[3px] border-dashed border-gray-400 p-5 font-bold text-primary focus:outline-none focus:border-secondary resize-none italic"></textarea>
                        <button @click="currentView = 'checkout'"
                            class="mt-10 w-full bg-primary text-white py-6 font-headline font-black text-xl uppercase tracking-widest border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)] hover:bg-primary-hover active:translate-y-1 transition-all">SIMPAN
                            & KEMBALI</button>
                    </div>
                </div>
            </div>

            {{-- Toast Error --}}
            <div x-show="paymentError" class="fixed bottom-24 left-1/2 -translate-x-1/2 z-[300]" x-cloak x-transition>
                <div
                    class="bg-secondary text-white px-8 py-5 border-[4px] border-gray-900 shadow-[10px_10px_0_var(--color-gray-900)] font-black uppercase text-xs tracking-[0.2em] animate-bounce">
                    Wajib Pilih Metode Pembayaran!
                </div>
            </div>

        @endif
    </div>
</x-layouts.dashboard>