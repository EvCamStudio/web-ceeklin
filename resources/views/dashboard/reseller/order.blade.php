<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">RESELLER TEROTORISASI</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>BUAT PESANAN</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.reseller._menu')</x-slot:menuSlot>

    {{-- Banner Countdown --}}
    <div class="w-full bg-secondary border-b-[3px] border-gray-900 px-6 py-2 flex items-center justify-between mb-6 -mt-6 -mx-6" style="width: calc(100% + 3rem);">
        <span class="font-headline font-bold text-white text-xs uppercase tracking-tight flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            Order Minimal 50pcs — Sisa Waktu
        </span>
        <span class="font-headline font-black text-white text-lg tabular-nums">47:59:59</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        {{-- Panel Kiri: Produk + Distributor --}}
        <div class="lg:col-span-2 flex flex-col gap-6">

            {{-- Pilih Produk --}}
            <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">
                <div class="bg-primary px-6 py-3">
                    <span class="font-headline font-black text-white text-base uppercase tracking-tight">Pilih Produk</span>
                </div>
                <div class="p-6 flex flex-col gap-4">
                    {{-- BACKEND-TODO: Loop dari Product::where('is_active', true)->get() dengan harga reseller --}}
                    @foreach([
                        ['nama'=>'Industrial Degreaser','desc'=>'Pembersih industri heavy-duty, format 5L','harga'=>'Rp 1.450.000','ket'=>'Harga Reseller','qty'=>50],
                        ['nama'=>'Heavy Duty Solvent','desc'=>'Pelarut kuat untuk komponen mesin, format 5L','harga'=>'Rp 1.680.000','ket'=>'Harga Reseller','qty'=>0],
                    ] as $produk)
                    <div class="border-[3px] {{ $produk['qty'] > 0 ? 'border-primary' : 'border-neutral-border' }} p-5 flex items-center gap-5 hover:border-secondary transition-colors duration-150">
                        <div class="w-14 h-14 bg-neutral-light border-2 border-neutral-border flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-headline font-black text-primary uppercase text-sm">{{ $produk['nama'] }}</h4>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $produk['desc'] }}</p>
                            <p class="font-headline font-black text-lg text-primary mt-1">{{ $produk['harga'] }} <span class="text-xs text-slate-400 font-normal">/ unit ({{ $produk['ket'] }})</span></p>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <button type="button" aria-label="Kurangi jumlah"
                                class="w-8 h-8 border-[2px] border-primary text-primary flex items-center justify-center font-bold hover:bg-primary hover:text-white transition-colors">−</button>
                            <input type="number" value="{{ $produk['qty'] }}" min="0" aria-label="Jumlah {{ $produk['nama'] }}"
                                class="w-14 text-center bg-neutral-light border-b-2 border-primary py-1 font-headline font-bold text-lg focus:outline-none">
                            <button type="button" aria-label="Tambah jumlah"
                                class="w-8 h-8 border-[2px] border-primary text-primary flex items-center justify-center font-bold hover:bg-primary hover:text-white transition-colors">+</button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Info Distributor --}}
            <div class="bg-white border-[4px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)]">
                <div class="bg-gray-900 px-6 py-3">
                    <span class="font-headline font-black text-white text-sm uppercase tracking-tight">Distributor Anda</span>
                </div>
                <div class="p-5 flex items-center justify-between gap-4">
                    <div class="border-l-[5px] border-secondary pl-4">
                        <p class="font-bold text-sm text-gray-900">PT. Industrial Mandiri</p>
                        <p class="text-xs text-slate-500 mt-0.5">Jawa Barat — Bandung Hub</p>
                    </div>
                    <button type="button" aria-label="Hubungi distributor via WhatsApp"
                        class="text-secondary flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest hover:text-primary transition-colors flex-shrink-0">
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
                        <span class="text-slate-500">Industrial Degreaser × 50</span>
                        <span class="font-bold text-gray-900">Rp 72.500.000</span>
                    </div>
                </div>
                <div class="flex flex-col gap-2 border-b-2 border-neutral-border pb-4 mb-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Subtotal</span>
                        <span class="font-bold text-gray-900">Rp 72.500.000</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Ongkos Kirim</span>
                        <span class="font-bold text-secondary">Gratis</span>
                    </div>
                </div>
                <div class="flex justify-between items-center mb-5">
                    <span class="font-headline font-black text-primary uppercase text-sm tracking-tight">Total</span>
                    <span class="font-headline font-black text-primary text-2xl tracking-tighter">Rp 72,5M</span>
                </div>
                {{-- Indikator Minimum Order --}}
                <div class="bg-secondary/10 border-[2px] border-secondary p-3 mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-secondary flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p class="text-xs text-secondary font-bold uppercase tracking-widest">Minimum order 50pcs terpenuhi ✓</p>
                </div>
                <button type="button" aria-label="Kirim pesanan ke distributor"
                    class="w-full bg-primary text-white py-4 font-headline font-black text-sm uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-0.5 active:shadow-none transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Kirim Pesanan
                </button>
                <p class="text-[10px] text-slate-400 text-center mt-2 uppercase tracking-widest">Order dikirim ke distributor untuk dikonfirmasi</p>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
