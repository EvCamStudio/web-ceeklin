<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Secure Checkout - CEEKLIN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral font-body text-gray-900 antialiased min-h-screen py-12 px-6 lg:px-24">

    <!-- Header / Nav Minimalis Checkout -->
    <div class="max-w-7xl mx-auto mb-12 flex justify-between items-end border-b-2 border-neutral-border-light pb-6">
        <div>
            <h1 class="font-headline font-bold text-4xl uppercase text-primary tracking-tighter mb-2">SECURE CHECKOUT</h1>
            <p class="text-[10px] uppercase font-bold tracking-widest text-slate-500 max-w-md leading-relaxed">
                AUTHORIZED DISTRIBUTOR PORTAL. VERIFY ORDER DETAILS BEFORE SECURING ALLOCATION.
            </p>
        </div>
        <a href="/" class="hidden md:inline-block text-[10px] uppercase font-bold tracking-widest text-secondary-dark hover:text-primary transition-colors border-b border-secondary-dark pb-1">
            KEMBALI KE PORTAL UTAMA
        </a>
    </div>

    <!-- Konten Utama Checkout -->
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-12 items-start">
        
        <!-- KOLOM KIRI (Detail Produk & Manifest) -->
        <div class="w-full lg:w-3/5 flex flex-col gap-6">
            
            <!-- Produk Utama Card -->
            <x-ui.card noPadding="true" class="flex flex-col md:flex-row overflow-hidden border border-neutral-border-light">
                <!-- Kiri: Detail Pengiriman/Produk -->
                <div class="w-full md:w-[40%] bg-gray-darkest flex items-center justify-center p-8 aspect-[3/4] md:aspect-auto">
                    {{-- BACKEND-TODO: Tembak URL gambar dinamis dari database --}}
                    <img src="/images/hero-bottle.png" alt="Ceeklin Grade" class="w-full h-auto object-contain drop-shadow-2xl opacity-90 opacity-80 mix-blend-screen brightness-125">
                </div>
                
                <!-- Spesifikasi Produk -->
                <div class="w-full md:w-[60%] p-8 bg-white flex flex-col justify-center">
                    <div class="flex items-center gap-2 mb-4">
                        <svg aria-hidden="true" class="w-4 h-4 text-secondary-dark" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-1.998A11.954 11.954 0 0110 1.944zM9.003 13.355l-3.32-3.32a.75.75 0 011.06-1.06l2.26 2.26 5.02-5.02a.75.75 0 111.06 1.06l-5.55 5.55a.75.75 0 01-1.06 0z" clip-rule="evenodd" /></svg>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-secondary-dark">RESELLER ALLOCATION</span>
                    </div>
                    
                    <h2 class="font-headline font-bold text-2xl uppercase text-primary leading-tight mb-4">CEEKLIN JAGONYA<br>PEMBASMI NODA</h2>
                    
                    <p class="text-xs text-slate-600 mb-8 font-medium">450ml Super Concentrate.<br>Diformulasikan khusus untuk kerak porselen menahun, keramik, dan noda mesin kendaraan.</p>
                    
                    <!-- Harga Box (Brutalist style) -->
                    <div class="bg-neutral-dark border-l-[6px] border-secondary-hover p-4 flex flex-col gap-1 mb-8">
                        <div class="flex justify-between items-end">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-800">UNIT PRICE (RESELLER)</span>
                            <span class="font-headline font-bold text-xl text-primary">Rp 15.000</span>
                        </div>
                        <span class="text-[9px] text-slate-500 font-bold tracking-widest">Standard MSRP: Rp 25.000 (40% Margin)</span>
                    </div>
                    
                    <!-- Kuantitas Input Khusus Reseller -->
                    <div class="flex flex-col gap-2">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-slate-800">MINIMUM ORDER QUANTITY</label>
                        <div class="flex bg-neutral-dark border-x border-neutral-border mb-2 font-headline items-center">
                            <div class="px-4 text-primary w-12 flex justify-center border-r border-neutral-border">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd" /></svg>
                            </div>
                            <input type="text" value="50" disabled class="w-full bg-transparent text-center font-bold text-2xl text-slate-800 focus:outline-none placeholder-slate-700">
                            <div class="px-4 text-slate-500 text-xs font-bold uppercase tracking-widest border-l border-neutral-border">PCS</div>
                        </div>
                        <p class="text-[9px] text-secondary-dark font-bold tracking-widest flex items-center gap-1">
                            <span class="w-3 h-3 bg-secondary-dark text-white rounded-full flex items-center justify-center font-serif leading-none italic select-none" aria-hidden="true">i</span>
                            Quantity locked at minimum 50pcs for reseller tier.
                        </p>
                    </div>
                </div>
            </x-ui.card>
            
            <!-- Shipping Manifest Box -->
            <x-ui.card class="bg-neutral-dark/50 border-0 shadow-none">
                <h3 class="font-headline font-bold text-lg uppercase text-primary mb-6">SHIPPING MANIFEST DETAILS</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-[9px] uppercase font-bold text-secondary-dark tracking-widest mb-1">TOTAL WEIGHT</p>
                        <p class="font-bold text-sm text-gray-900 border-b border-primary pb-1">125 Kgs</p>
                    </div>
                    <div>
                        <p class="text-[9px] uppercase font-bold text-secondary-dark tracking-widest mb-1">DIMENSIONS</p>
                        <p class="font-bold text-sm text-gray-900 border-b border-primary pb-1">110x80x60 cm</p>
                    </div>
                    <div>
                        <p class="text-[9px] uppercase font-bold text-secondary-dark tracking-widest mb-1">HAZMAT CLASS</p>
                        <p class="font-bold text-sm text-gray-900 border-b border-primary pb-1">Corrosive / 8 - Handled Material</p>
                    </div>
                </div>
            </x-ui.card>
            
        </div>
        
        <!-- KOLOM KANAN (Payment Gateway Form) -->
        <div class="w-full lg:w-2/5">
            <x-ui.card class="border-t-[6px] border-primary sticky top-12 p-8 lg:p-10">
                <h2 class="font-headline font-bold text-xl uppercase text-primary mb-6">PAYMENT GATEWAY</h2>
                
                <!-- Total Authorization Box -->
                <div class="bg-neutral-light border-y border-neutral-border py-8 flex flex-col items-center justify-center mb-8">
                    <span class="text-[10px] font-bold text-secondary-dark uppercase tracking-widest mb-2">TOTAL AUTHORIZATION</span>
                    {{-- BACKEND-TODO: Kalkulasi Total Harga (misal 50 pcs * Rp 15.000) --}}
                    <span class="font-headline font-bold text-4xl md:text-5xl text-primary tracking-tighter">Rp 750.000</span>
                </div>
                
                {{-- BACKEND-TODO: Gunakan form method POST untuk submit payment ke controller backend/Midtrans Snap --}}
                <form action="#" method="POST" class="flex flex-col gap-4">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-gray-900 mb-2 border-b border-neutral-border-light pb-2">SELECT MIDTRANS METHOD</label>
                    <div class="flex flex-col gap-3 mt-4" role="radiogroup" aria-required="true">
                        
                        <!-- VA -->
                        <label class="cursor-pointer relative z-10 hover:shadow-lg transition-shadow">
                            <input type="radio" name="payment_method" value="va" class="peer sr-only" required>
                            <div class="border-[3px] border-transparent peer-checked:border-primary bg-neutral-light p-5 flex items-center justify-between transition-all" aria-hidden="true">
                            <div class="flex items-center gap-4">
                                <!-- Ikon VA -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-slate-600 peer-checked:text-primary"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" /></svg>
                                <div>
                                    <h3 class="font-bold text-sm text-gray-900 uppercase tracking-widest">VIRTUAL ACCOUNT</h3>
                                    <p class="text-[10px] font-bold text-slate-500 mt-1">BCA, Mandiri, BNI, BRI</p>
                                </div>
                            </div>
                            <!-- Styling Checkbox Karet Indikator -->
                            <div class="w-5 h-5 border-2 border-gray-300 rounded-none peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center transition-colors">
                                <svg class="w-3 h-3 text-white hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </div>
                    </label>
                    
                    <!-- Radio Button Style UI (QRIS) -->
                    <label class="cursor-pointer">
                        <input type="radio" name="payment_method" value="qris" class="peer hidden">
                        <div class="border-[3px] border-transparent peer-checked:border-primary bg-[#Fdfcf7] p-5 flex items-center justify-between transition-all">
                            <div class="flex items-center gap-4">
                                <!-- Ikon QRIS -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-slate-600 peer-checked:text-primary"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z" /></svg>
                                <div>
                                    <h3 class="font-bold text-sm text-gray-900 uppercase tracking-widest">QRIS</h3>
                                    <p class="text-[10px] font-bold text-slate-500 mt-1">Scan from any banking app</p>
                                </div>
                            </div>
                            <div class="w-5 h-5 border-2 border-gray-300 rounded-none peer-checked:border-primary flex items-center justify-center transition-colors"></div>
                        </div>
                    </label>
                    
                    <!-- Radio Button Style UI (E-WALLET) -->
                    <label class="cursor-pointer mb-6">
                        <input type="radio" name="payment_method" value="ewallet" class="peer hidden">
                        <div class="border-[3px] border-transparent peer-checked:border-primary bg-[#Fdfcf7] p-5 flex items-center justify-between transition-all">
                            <div class="flex items-center gap-4">
                                <!-- Ikon dompet -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-slate-600 peer-checked:text-primary"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" /></svg>
                                <div>
                                    <h3 class="font-bold text-sm text-gray-900 uppercase tracking-widest">E-WALLET</h3>
                                    <p class="text-[10px] font-bold text-slate-500 mt-1">GoPay, ShopeePay, OVO</p>
                                </div>
                            </div>
                            <div class="w-5 h-5 border-2 border-gray-300 rounded-none peer-checked:border-primary flex items-center justify-center transition-colors"></div>
                        </div>
                    </label>
                    
                    <x-ui.button type="submit" class="w-full text-base py-5 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-secondary"><path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd" /></svg>
                        BAYAR SEKARANG
                    </x-ui.button>
                </form>
                
                <div class="mt-6 flex justify-center items-center gap-2 text-[9px] uppercase font-bold tracking-widest text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3"><path fill-rule="evenodd" d="M12.516 2.17a.75.75 0 00-1.032 0 11.209 11.209 0 01-7.877 3.08.75.75 0 00-.722.515A12.74 12.74 0 002.25 9.75c0 5.942 4.064 10.933 9.563 12.348a.749.749 0 00.374 0c5.499-1.415 9.563-6.406 9.563-12.348 0-1.39-.223-2.73-.635-3.985a.75.75 0 00-.722-.516l-.143.001c-2.996 0-5.717-1.17-7.734-3.08zm3.094 8.016a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" /></svg>
                    SECURED BY MIDTRANS
                </div>
            </x-ui.card>
        </div>
        
    </div>

    <!-- Script CSS kustom untuk memberikan checkmark saat radio dicentang menggunakan sibling selector peer -->
    <style>
        input[type="radio"]:checked ~ div > div:last-child > svg {
            display: block !important;
        }
    </style>
</body>
</html>
