<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">PORTAL DISTRIBUTOR</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>BUAT PESANAN</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.distributor._menu')</x-slot:menuSlot>

    {{-- BACKEND-TODO: Bungkus dengan <form method="POST" action="{{ route('distributor.order.store') }}"> + @csrf --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        {{-- Panel Kiri: Pilih Produk + Alamat --}}
        <div class="lg:col-span-2 flex flex-col gap-6">

            {{-- Pilih Produk --}}
            <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">
                <div class="bg-primary px-6 py-3">
                    <span class="font-headline font-black text-white text-base uppercase tracking-tight">Pilih Produk</span>
                </div>
                <div class="p-6 flex flex-col gap-4">
                    {{-- BACKEND-TODO: Loop dari Product::all() --}}

                    @foreach([
                        ['nama' => 'Industrial Degreaser', 'desc' => 'Pembersih industri heavy-duty, format 5L', 'harga' => 'Rp 1.250.000', 'qty' => 100],
                        ['nama' => 'Heavy Duty Solvent',   'desc' => 'Pelarut kuat untuk komponen mesin, format 5L', 'harga' => 'Rp 1.450.000', 'qty' => 50],
                        ['nama' => 'Surface Prep',          'desc' => 'Solusi persiapan permukaan, format 5L', 'harga' => 'Rp 980.000', 'qty' => 0],
                    ] as $produk)
                    <div class="border-[3px] {{ $produk['qty'] > 0 ? 'border-primary' : 'border-neutral-border' }} p-4 md:p-5 flex flex-col md:flex-row items-start md:items-center gap-4 md:gap-5 hover:border-secondary transition-colors duration-150">
                        <div class="flex items-center gap-4 w-full md:w-auto">
                            {{-- Slot Gambar Produk --}}
                            <div class="w-14 h-14 md:w-16 md:h-16 bg-neutral-light border-2 border-neutral-border flex items-center justify-center flex-shrink-0">
                                <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-headline font-black text-primary uppercase text-sm leading-tight">{{ $produk['nama'] }}</h4>
                                <p class="text-[10px] md:text-xs text-slate-500 mt-0.5">{{ $produk['desc'] }}</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between w-full md:contents">
                            <div class="md:flex-1">
                                <p class="font-headline font-black text-base md:text-lg text-primary">{{ $produk['harga'] }} <span class="hidden md:inline text-xs text-slate-400 font-normal">/ unit</span></p>
                            </div>
                            {{-- Kontrol Qty --}}
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <button type="button" aria-label="Kurangi jumlah {{ $produk['nama'] }}"
                                    class="w-10 h-10 md:w-8 md:h-8 border-[2px] border-primary text-primary flex items-center justify-center font-bold hover:bg-primary hover:text-white transition-colors text-xl md:text-base">−</button>
                                <input type="number" value="{{ $produk['qty'] }}" min="0" aria-label="Jumlah {{ $produk['nama'] }}"
                                    class="w-14 md:w-16 text-center bg-neutral-light border-b-2 border-primary py-1 font-headline font-bold text-base md:text-lg focus:outline-none">
                                <button type="button" aria-label="Tambah jumlah {{ $produk['nama'] }}"
                                    class="w-10 h-10 md:w-8 md:h-8 border-[2px] border-primary text-primary flex items-center justify-center font-bold hover:bg-primary hover:text-white transition-colors text-xl md:text-base">+</button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Alamat Pengiriman --}}
            <div class="bg-white border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)]">
                <div class="bg-gray-900 px-6 py-3">
                    <span class="font-headline font-black text-white text-base uppercase tracking-tight">Alamat Pengiriman</span>
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
                {{-- BACKEND-TODO: Hitung total dari input qty × harga produk via Alpine.js atau server-side --}}
                <div class="flex flex-col gap-2 border-b-2 border-neutral-border pb-4 mb-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Industrial Degreaser × 100</span>
                        <span class="font-bold text-gray-900">Rp 125.000.000</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Heavy Duty Solvent × 50</span>
                        <span class="font-bold text-gray-900">Rp 72.500.000</span>
                    </div>
                </div>
                <div class="flex flex-col gap-2 border-b-2 border-neutral-border pb-4 mb-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Subtotal</span>
                        <span class="font-bold text-gray-900">Rp 197.500.000</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Ongkos Kirim</span>
                        <span class="font-bold text-secondary">Gratis</span>
                    </div>
                </div>
                <div class="flex justify-between items-center mb-6">
                    <span class="font-headline font-black text-primary uppercase text-sm tracking-tight">Total</span>
                    <span class="font-headline font-black text-primary text-2xl tracking-tighter">Rp 197,5M</span>
                </div>
                <button type="button" aria-label="Kirim pesanan ke pabrik"
                    class="w-full bg-primary text-white py-4 font-headline font-black text-sm uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-0.5 active:shadow-none transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Kirim Pesanan
                </button>
                <p class="text-[10px] text-slate-400 text-center mt-3 uppercase tracking-widest">Pesanan dikonfirmasi admin dalam 1×24 jam</p>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
