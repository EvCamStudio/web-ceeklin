<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">PORTAL DISTRIBUTOR</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>RESTOCK KE PABRIK</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.distributor._menu')</x-slot:menuSlot>

    {{-- BACKEND-TODO: Bungkus dengan <form method="POST" action="{{ route('distributor.order.store') }}"> + @csrf --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start" x-data="{ 
        qty: 1000, 
        price: 13000, // Harga Modal Distributor (Rp 13.000/pcs)
        get total() { return Math.max(1000, this.qty) * this.price; },
        formatRupiah(number) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number); },
        getWaAdminLink() {
            const msg = `Halo Admin, saya sudah melakukan pesanan restock CeeKlin sebanyak ${Math.max(1000, this.qty)} pcs (Total: ${this.formatRupiah(this.total)}). Mohon diproses. Terima kasih!`;
            return 'https://wa.me/628xxxxxxxxxx?text=' + encodeURIComponent(msg);
        }
    }">

        {{-- Panel Kiri: Pilih Produk + Alamat --}}
        <div class="lg:col-span-2 flex flex-col gap-6">

            {{-- Pilih Produk --}}
            <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">
                <div class="bg-primary px-6 py-3">
                    <span class="font-headline font-black text-white text-base uppercase tracking-tight">Produk & Kuantitas</span>
                </div>
                <div class="p-6 flex flex-col gap-4">
                    
                    <div class="border-[3px] border-primary p-4 md:p-5 flex flex-col md:flex-row items-start md:items-center gap-4 md:gap-5 hover:border-secondary transition-colors duration-150">
                        <div class="flex items-center gap-4 w-full md:w-auto">
                            {{-- Slot Gambar Produk --}}
                            <div class="w-14 h-14 md:w-16 md:h-16 bg-gray-900 border-2 border-gray-900 flex items-center justify-center flex-shrink-0">
                                <img src="/images/hero-bottle.jpeg" alt="Ceeklin 450ml" class="w-full h-auto mix-blend-screen opacity-80">
                            </div>
                            <div class="flex-1">
                                <h4 class="font-headline font-black text-primary uppercase text-sm leading-tight">CeeKlin 450ml</h4>
                                <p class="text-[10px] md:text-xs text-slate-500 mt-0.5">Jagonya Pembasmi Noda Pakaian</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between w-full md:contents">
                            <div class="md:flex-1">
                                <p class="font-headline font-black text-base md:text-lg text-primary" x-text="formatRupiah(price)">Rp 15.000 <span class="hidden md:inline text-xs text-slate-400 font-normal">/ pcs</span></p>
                            </div>
                            {{-- Kontrol Qty --}}
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <button type="button" @click="if(qty > 1000) qty--" aria-label="Kurangi jumlah"
                                    class="w-10 h-10 md:w-8 md:h-8 border-[2px] border-primary text-primary flex items-center justify-center font-bold hover:bg-primary hover:text-white transition-colors text-xl md:text-base">−</button>
                                <input type="number" x-model.number="qty" min="1000" aria-label="Jumlah Pesanan"
                                    class="w-20 md:w-24 text-center bg-neutral-light border-b-2 border-primary py-1 font-headline font-bold text-base md:text-lg focus:outline-none">
                                <button type="button" @click="qty++" aria-label="Tambah jumlah"
                                    class="w-10 h-10 md:w-8 md:h-8 border-[2px] border-primary text-primary flex items-center justify-center font-bold hover:bg-primary hover:text-white transition-colors text-xl md:text-base">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="bg-secondary/10 p-3 border-l-[4px] border-secondary">
                        <p class="text-[10px] text-gray-900 font-bold uppercase tracking-widest">⚠️ Minimal Restock: 1.000 PCS</p>
                    </div>
                </div>
            </div>

            {{-- Alamat Pengiriman --}}
            <div class="bg-white border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)]">
                <div class="bg-gray-900 px-6 py-3">
                    <span class="font-headline font-black text-white text-base uppercase tracking-tight">Alamat Pengiriman (Gudang Anda)</span>
                </div>
                <div class="p-6">
                    {{-- BACKEND-TODO: Isi dari Auth::user()->warehouse_address --}}
                    <div class="p-4 bg-neutral-light border-l-[5px] border-secondary text-sm mb-4">
                        <p class="font-bold text-gray-900">Gudang PT. Industrial Mandiri</p>
                        <p class="text-slate-500 mt-1">Jl. Industri No. 45, Cikarang, Bekasi, Jawa Barat 17530</p>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[10px] font-bold text-primary uppercase tracking-widest" for="catatan-pengiriman">Catatan Pengiriman (Opsional)</label>
                        <textarea id="catatan-pengiriman" rows="2" placeholder="Instruksi khusus untuk pengiriman..."
                            class="bg-neutral-light border-[3px] border-gray-900 px-4 py-2.5 font-body text-sm text-primary focus:outline-none focus:border-primary transition-colors resize-none placeholder:text-slate-400"></textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel Kanan: Ringkasan Pesanan (Sticky) --}}
        <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-secondary)] sticky top-6">
            <div class="bg-secondary px-6 py-3">
                <span class="font-headline font-black text-white text-base uppercase tracking-tight">Ringkasan Pesanan</span>
            </div>
            <div class="p-6">
                <div class="flex flex-col gap-2 border-b-2 border-neutral-border pb-4 mb-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">CeeKlin 450ml × <span x-text="Math.max(1000, qty)"></span></span>
                        <span class="font-bold text-gray-900" x-text="formatRupiah(total)"></span>
                    </div>
                </div>
                <div class="flex flex-col gap-2 border-b-2 border-neutral-border pb-4 mb-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Ongkos Kirim</span>
                        <span class="font-bold text-secondary">Akan disesuaikan</span>
                    </div>
                </div>
                <div class="flex justify-between items-center mb-6">
                    <span class="font-headline font-black text-primary uppercase text-sm tracking-tight">Total</span>
                    <span class="font-headline font-black text-primary text-2xl tracking-tighter" x-text="formatRupiah(total)"></span>
                </div>
                {{-- BACKEND-TODO: Ganti type="button" dengan type="submit" setelah form dibungkus POST --}}
                <form action="/dashboard/distributor/order/store" method="POST">
                    @csrf
                    <input type="hidden" name="qty" :value="Math.max(1000, qty)">
                    <button type="submit" aria-label="Kirim pesanan ke pabrik"
                        class="w-full bg-primary text-white py-4 font-headline font-black text-sm uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-0.5 active:shadow-none transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        KIRIM PESANAN
                    </button>
                </form>

                {{-- Tombol WA ke Admin (setelah bayar / submit) --}}
                <div class="mt-3 pt-3 border-t-2 border-neutral-border">
                    <p class="text-[9px] text-slate-400 text-center uppercase tracking-widest mb-2">Setelah membayar, infokan admin:</p>
                    <a :href="getWaAdminLink()" target="_blank"
                       class="w-full inline-flex items-center justify-center gap-2 bg-secondary/10 text-secondary border-[2px] border-secondary px-4 py-2.5 font-headline font-bold text-[10px] uppercase tracking-widest hover:bg-secondary hover:text-white transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        Infokan Admin via WA
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
