<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aktivasi Kemitraan - CEEKLIN</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral font-body text-gray-900 antialiased min-h-screen flex flex-col items-center justify-center py-8 px-6">
    {{-- Grain Texture Overlay --}}
    <div class="grain-overlay"></div>

    <div class="w-full max-w-4xl">
        {{-- Header Minimalis --}}
        <div class="min-h-[5rem] py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 border-b-4 border-gray-900 gap-4">
            <div>
                <h1 class="font-headline font-black text-2xl sm:text-3xl text-primary tracking-tighter uppercase leading-none">Aktivasi Akun</h1>
                <p class="text-[8px] sm:text-[9px] font-bold tracking-[0.15em] sm:tracking-[0.2em] text-slate-500 uppercase mt-2 sm:mt-1">Langkah Terakhir Menjadi Mitra Resmi</p>
            </div>
            <form action="/login" method="GET" class="w-full sm:w-auto">
                <button type="submit" class="w-full sm:w-auto font-headline font-bold text-[10px] uppercase tracking-widest bg-primary text-white border-[3px] border-primary px-8 py-2.5 hover:bg-transparent hover:text-primary transition-all duration-300">
                    KELUAR
                </button>
            </form>
        </div>

        <div class="flex flex-col lg:flex-row gap-8 items-stretch">
            {{-- Sisi Kiri: Detail & Countdown --}}
            <div class="w-full lg:w-[45%] flex flex-col gap-6">
                {{-- Countdown Timer --}}
                <div class="bg-white border-4 border-gray-900 p-6 shadow-[10px_10px_0_var(--color-primary)]">
                    <p class="text-[9px] font-bold text-center uppercase tracking-[0.3em] text-slate-500 mb-4">Masa Berlaku Pendaftaran</p>
                    <div class="flex justify-center gap-4" x-data="{ 
                        hours: 47, 
                        minutes: 59, 
                        seconds: 59,
                        init() {
                            setInterval(() => {
                                if(this.seconds > 0) this.seconds--;
                                else {
                                    this.seconds = 59;
                                    if(this.minutes > 0) this.minutes--;
                                    else {
                                        this.minutes = 59;
                                        if(this.hours > 0) this.hours--;
                                    }
                                }
                            }, 1000);
                        }
                    }">
                        <div class="text-center">
                            <span class="font-headline font-black text-3xl sm:text-4xl text-primary" x-text="hours.toString().padStart(2, '0')">47</span>
                            <p class="text-[8px] font-bold uppercase tracking-widest mt-1">Jam</p>
                        </div>
                        <span class="font-headline font-black text-3xl sm:text-4xl text-primary">:</span>
                        <div class="text-center">
                            <span class="font-headline font-black text-3xl sm:text-4xl text-primary" x-text="minutes.toString().padStart(2, '0')">59</span>
                            <p class="text-[8px] font-bold uppercase tracking-widest mt-1">Menit</p>
                        </div>
                        <span class="font-headline font-black text-3xl sm:text-4xl text-primary">:</span>
                        <div class="text-center">
                            <span class="font-headline font-black text-3xl sm:text-4xl text-primary" x-text="seconds.toString().padStart(2, '0')">59</span>
                            <p class="text-[8px] font-bold uppercase tracking-widest mt-1">Detik</p>
                        </div>
                    </div>
                </div>

                {{-- Copywriting Berkelas --}}
                <div class="bg-white border-4 border-gray-900 p-6 flex-grow">
                    <h3 class="font-headline font-bold text-lg text-gray-900 uppercase mb-3">Selamat Datang, Mitra.</h3>
                    <p class="text-xs text-slate-600 leading-relaxed font-bold mb-4">
                        Pendaftaran Anda telah kami terima. Untuk menjaga eksklusivitas jaringan, setiap mitra wajib melakukan aktivasi melalui pembelian stok perdana.
                    </p>
                    <div class="bg-primary/5 p-4 border-l-4 border-secondary">
                        <p class="text-[10px] text-gray-900 font-bold leading-relaxed uppercase tracking-tight">
                            ⚠️ JIKA WAKTU HABIS, DATA PENDAFTARAN AKAN DIHAPUS OTOMATIS OLEH SISTEM.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Sisi Kanan: Order Card --}}
            <div class="w-full lg:w-[55%]">
                <div class="bg-white border-4 border-gray-900 shadow-[8px_8px_0_var(--color-secondary)] sm:shadow-[12px_12px_0_var(--color-secondary)] overflow-hidden h-full flex flex-col">
                    <div class="p-5 border-b-4 border-gray-900 bg-gray-900">
                        <h2 class="font-headline font-bold text-sm text-secondary uppercase tracking-widest text-center">RINCIAN PESANAN AKTIVASI</h2>
                    </div>

                    <div class="p-6 flex gap-6 items-center bg-neutral-light border-b-2 border-neutral-border">
                        <div class="w-16 h-16 bg-gray-900 flex items-center justify-center flex-shrink-0 border-2 border-gray-900">
                            <img src="/images/hero-bottle.jpeg" alt="Ceeklin" class="w-full h-auto mix-blend-screen opacity-80">
                        </div>
                        <div>
                            <h4 class="font-headline font-bold text-xs text-gray-900 uppercase">Ceeklin Jagonya Pembasmi Noda</h4>
                            <p class="text-[9px] font-bold text-slate-500 uppercase mt-1">Paket Komitmen Awal (50 Pcs)</p>
                            <p class="font-headline font-bold text-base text-primary mt-1">Rp 15.000 <span class="text-[9px] text-slate-400">/ PCS</span></p>
                        </div>
                    </div>

                    <div class="p-6 flex-grow flex flex-col justify-between" x-data="{ 
                        qty: 50, 
                        pricePerItem: 15000, 
                        // BACKEND-TODO: passing true/false dari ketersediaan stok distributor lokal
                        hasStock: {{ $hasStock ?? 'true' }}, 
                        get total() { return Math.max(50, this.qty) * this.pricePerItem; },
                        formatRupiah(number) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number); }
                    }">
                        
                        <div class="mb-6 space-y-4">
                            <div class="flex flex-col gap-1.5">
                                <label class="text-[9px] font-bold text-gray-900 uppercase tracking-widest">JUMLAH PEMBELIAN (MIN. 50)</label>
                                <div class="flex items-center gap-2">
                                    <button type="button" @click="if(qty > 50) qty--" class="w-10 h-10 bg-neutral flex items-center justify-center border-2 border-gray-900 text-gray-900 font-bold text-xl hover:bg-neutral-border active:translate-y-px transition-all" :disabled="!hasStock">-</button>
                                    <input type="number" x-model.number="qty" min="50" class="flex-1 w-full bg-white border-2 border-gray-900 px-3 py-2.5 font-headline font-black text-center text-primary text-xl focus:outline-none focus:border-secondary transition-colors" :disabled="!hasStock">
                                    <button type="button" @click="qty++" class="w-10 h-10 bg-neutral flex items-center justify-center border-2 border-gray-900 text-gray-900 font-bold text-xl hover:bg-neutral-border active:translate-y-px transition-all" :disabled="!hasStock">+</button>
                                </div>
                                <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mt-1">Stok Distributor: <span x-text="hasStock ? 'Tersedia' : 'HABIS'" :class="hasStock ? 'text-green-600' : 'text-red-600'"></span></p>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-between items-center mb-8 bg-neutral p-4 border-2 border-dashed border-gray-400 gap-2">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Total Transfer</span>
                            <span class="font-headline font-black text-2xl sm:text-3xl text-primary tracking-tighter" x-text="formatRupiah(total)">Rp 750.000</span>
                        </div>

                        <div class="space-y-4">
                            <!-- Warning Out of Stock -->
                            <div x-show="!hasStock" class="bg-red-50 border-l-[4px] border-red-600 p-3 mb-4" style="display: none;">
                                <p class="text-red-700 text-[10px] font-bold uppercase tracking-widest mb-1">STOK DISTRIBUTOR HABIS</p>
                                <p class="text-xs text-red-600 leading-relaxed font-bold">Admin sedang mengalihkan Anda ke distributor darurat. Mohon tunggu notifikasi selanjutnya.</p>
                            </div>

                            <form action="/dashboard/reseller" method="GET">
                                <input type="hidden" name="qty" :value="Math.max(50, qty)">
                                <x-ui.button type="submit" fullWidth="true" class="py-5 text-base flex items-center justify-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed" x-bind:disabled="!hasStock || qty < 50">
                                    <span x-text="hasStock ? 'BAYAR & AKTIFKAN SEKARANG' : 'MENUNGGU ALOKASI STOK'"></span>
                                    <svg x-show="hasStock" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </x-ui.button>
                            </form>
                            
                            <div class="flex justify-center items-center gap-2 text-[8px] font-bold uppercase tracking-[0.2em] text-slate-400 mt-4">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-1.998A11.954 11.954 0 0110 1.944zM9.003 13.355l-3.32-3.32a.75.75 0 011.06-1.06l2.26 2.26 5.02-5.02a.75.75 0 111.06 1.06l-5.55 5.55a.75.75 0 01-1.06 0z" clip-rule="evenodd" /></svg>
                                Secure Payment with Midtrans Encryption
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
