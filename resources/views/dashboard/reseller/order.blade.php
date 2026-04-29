<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">RESELLER TEROTORISASI</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>BUAT PESANAN</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.reseller._menu')</x-slot:menuSlot>



    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start" x-data="{ 
        qty: 50, 
        price: 20000, // Harga Modal Reseller
        get total() { return Math.max(50, this.qty) * this.price; },
        formatRupiah(number) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number); }
    }">

        {{-- Panel Kiri: Produk + Distributor --}}
        <div class="lg:col-span-2 flex flex-col gap-6">

            {{-- Pilih Produk --}}
            <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">
                <div class="bg-primary px-6 py-3">
                    <span class="font-headline font-black text-white text-base uppercase tracking-tight">Produk & Kuantitas</span>
                </div>
                <div class="p-6 flex flex-col gap-4">
                    <div class="border-[3px] border-primary p-4 md:p-5 flex flex-col md:flex-row items-start md:items-center gap-4 md:gap-5 hover:border-secondary transition-colors duration-150">
                        <div class="flex items-center gap-4 w-full md:w-auto">
                            <div class="w-14 h-14 md:w-16 md:h-16 bg-gray-900 border-2 border-gray-900 flex items-center justify-center flex-shrink-0">
                                <img src="/images/hero-bottle.jpeg" alt="CeeKlin 450ml" class="w-full h-auto mix-blend-screen opacity-80">
                            </div>
                            <div class="flex-1">
                                <h4 class="font-headline font-black text-primary uppercase text-sm leading-tight">CeeKlin 450ml</h4>
                                <p class="text-[10px] md:text-xs text-slate-500 mt-0.5">Jagonya Pembasmi Noda Pakaian</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between w-full md:contents">
                            <div class="md:flex-1">
                                <p class="font-headline font-black text-base md:text-lg text-primary" x-text="formatRupiah(price)">Rp 20.000 <span class="hidden md:inline text-xs text-slate-400 font-normal">/ pcs</span></p>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <button type="button" @click="if(qty > 50) qty--" aria-label="Kurangi jumlah"
                                    class="w-10 h-10 md:w-8 md:h-8 border-[2px] border-primary text-primary flex items-center justify-center font-bold hover:bg-primary hover:text-white transition-colors text-xl md:text-base">−</button>
                                <input type="number" x-model.number="qty" min="50" aria-label="Jumlah Pesanan"
                                    class="w-20 md:w-24 text-center bg-neutral-light border-b-2 border-primary py-1 font-headline font-bold text-base md:text-lg focus:outline-none">
                                <button type="button" @click="qty++" aria-label="Tambah jumlah"
                                    class="w-10 h-10 md:w-8 md:h-8 border-[2px] border-primary text-primary flex items-center justify-center font-bold hover:bg-primary hover:text-white transition-colors text-xl md:text-base">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info Distributor --}}
            <div class="bg-white border-[4px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)]">
                <div class="bg-gray-900 px-6 py-3">
                    <span class="font-headline font-black text-white text-sm uppercase tracking-tight">Distributor Anda</span>
                </div>
                <div class="p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="border-l-[5px] border-secondary pl-4">
                        <p class="font-bold text-sm text-gray-900 uppercase">PT. Industrial Mandiri</p>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Jawa Barat — Bandung Hub</p>
                    </div>
                    <button type="button" aria-label="Hubungi distributor via WhatsApp"
                        class="w-full sm:w-auto bg-secondary text-white px-4 py-2 font-headline font-bold text-[10px] uppercase tracking-widest border-2 border-gray-900 shadow-[3px_3px_0_var(--color-gray-900)] hover:bg-secondary-dark active:translate-y-0.5 active:shadow-none transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                        WhatsApp
                    </button>
                </div>
            </div>
        </div>

        {{-- Panel Kanan: Ringkasan + Cek Minimum --}}
        <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-secondary)] sticky top-6">
            <div class="bg-secondary px-6 py-3">
                <span class="font-headline font-black text-white text-base uppercase tracking-tight">Ringkasan Pesanan</span>
            </div>
            <div class="p-6">
                <div class="flex flex-col gap-2 border-b-2 border-neutral-border pb-4 mb-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">CeeKlin 450ml × <span x-text="Math.max(50, qty)"></span></span>
                        <span class="font-bold text-gray-900" x-text="formatRupiah(total)"></span>
                    </div>
                </div>
                <div class="flex flex-col gap-2 border-b-2 border-neutral-border pb-4 mb-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Ongkos Kirim</span>
                        <span class="font-bold text-secondary">Ditentukan Distributor</span>
                    </div>
                </div>
                <div class="flex justify-between items-center mb-5">
                    <span class="font-headline font-black text-primary uppercase text-sm tracking-tight">Total</span>
                    <span class="font-headline font-black text-primary text-2xl tracking-tighter" x-text="formatRupiah(total)"></span>
                </div>
                {{-- Indikator Minimum Order --}}
                <div class="bg-secondary/10 border-[2px] border-secondary p-3 mb-5 flex items-center gap-2" :class="qty >= 50 ? 'bg-secondary/10 border-secondary' : 'bg-red-50 border-red-500'">
                    <svg x-show="qty >= 50" class="w-5 h-5 text-secondary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <svg x-show="qty < 50" style="display: none;" class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    <p class="text-[10px] font-bold uppercase tracking-widest" :class="qty >= 50 ? 'text-secondary' : 'text-red-500'" x-text="qty >= 50 ? 'Minimum order 50pcs terpenuhi ✓' : 'Minimum order 50pcs belum terpenuhi!'"></p>
                </div>
                <button type="button" aria-label="Kirim pesanan ke distributor" :disabled="qty < 50"
                    class="w-full bg-primary text-white py-4 font-headline font-black text-sm uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-0.5 active:shadow-none disabled:opacity-50 disabled:cursor-not-allowed transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Kirim Pesanan
                </button>
                <p class="text-[10px] text-slate-400 text-center mt-2 uppercase tracking-widest">Order dikirim ke distributor untuk dikonfirmasi</p>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
