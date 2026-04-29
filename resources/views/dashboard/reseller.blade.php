<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">RESELLER TEROTORISASI</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>BERANDA</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.reseller._menu')</x-slot:menuSlot>

    {{-- 
        TODO BACKEND:
        Gunakan Toast Component untuk notifikasi success/error (misal: setelah berhasil login atau simpan data).
        Silakan panggil komponen ini jika terdapat flash session.
        Contoh:
        @if(session('success'))
            <div class="fixed top-8 right-8 z-[100]">
                <x-ui.toast type="success" :message="session('success')" />
            </div>
        @endif
    --}}
    
    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Card 1: Saldo Bonus -->
        <div class="bg-white border-[3px] border-primary shadow-[6px_6px_0_var(--color-primary-darkest)] p-6 flex flex-col justify-between">
            <div>
                <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-1">Total Saldo Bonus Aktif</p>
                <h3 class="font-headline font-black text-3xl md:text-4xl text-primary tracking-tighter italic mb-2">Rp 12.500.000</h3>
                <div class="flex flex-wrap gap-x-4 gap-y-1 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                    <span>Referral: <span class="text-gray-900">Rp 10.000.000</span></span>
                    <span>Target: <span class="text-gray-900">Rp 2.500.000</span></span>
                </div>
            </div>
            <div class="mt-6">
                <!-- BACKEND-TODO: Action form untuk pengajuan pencairan bonus -->
                <form action="/dashboard/reseller/withdraw" method="POST">
                    @csrf
                    <x-ui.button type="submit" class="w-full py-3 text-xs bg-gray-900 border-gray-900 text-white hover:bg-gray-800 shadow-[4px_4px_0_var(--color-secondary)] active:translate-y-1 active:shadow-none transition-all">
                        AJUKAN PENCAIRAN BONUS
                    </x-ui.button>
                </form>
            </div>
        </div>

        <!-- Card 2: Target Bulanan -->
        <div class="bg-white border-[3px] border-secondary shadow-[6px_6px_0_var(--color-gray-900)] p-6 flex flex-col justify-between">
            <div>
                <div class="flex justify-between items-start mb-2">
                    <p class="text-[10px] text-secondary font-bold uppercase tracking-widest">Target Bulanan</p>
                    <span class="bg-primary text-white text-[9px] font-bold px-2 py-0.5 uppercase border border-gray-900">Hadiah: Rp 2.500.000</span>
                </div>
                <h3 class="font-headline font-black text-2xl md:text-3xl text-gray-900 tracking-tighter mb-4">
                    850 <span class="text-lg text-slate-400">/ 1.000 PCS</span>
                </h3>
                
                <div class="w-full bg-neutral-border-light border-2 border-neutral-border h-3 mb-2">
                    <div class="bg-secondary h-full relative" style="width:85%">
                        <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                    </div>
                </div>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                    <span class="text-secondary">150 pcs</span> lagi untuk capai target bulan ini!
                </p>
            </div>
        </div>
    </div>

    {{-- Aksi Cepat + Info Distributor --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Aksi Cepat --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-1 gap-4">
            <a href="/dashboard/reseller/history"
               class="bg-white border-[3px] border-primary shadow-[4px_4px_0_var(--color-primary-darkest)] p-5 flex flex-col xl:flex-row items-center gap-3 hover:bg-neutral-light transition-colors group">
                <div class="w-10 h-10 bg-primary/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                </div>
                <span class="font-headline font-black text-primary uppercase text-[10px] text-center xl:text-left">Pesanan Saya</span>
            </a>
            <a href="/dashboard/reseller/referrals"
               class="bg-white border-[3px] border-secondary shadow-[4px_4px_0_var(--color-gray-900)] p-5 flex flex-col xl:flex-row items-center gap-3 hover:bg-neutral-light transition-colors group">
                <div class="w-10 h-10 bg-secondary/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg>
                </div>
                <span class="font-headline font-black text-primary uppercase text-[10px] text-center xl:text-left">Referral</span>
            </a>
        </div>

        {{-- Info Distributor --}}
        <div class="xl:col-span-2 bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">
            <div class="bg-primary px-6 py-3">
                <span class="font-headline font-black text-white text-base uppercase tracking-tight">Distributor Anda</span>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-6 items-start">
                <div class="flex flex-col gap-1">
                    <p class="text-[10px] text-primary font-bold uppercase tracking-widest">Wilayah</p>
                    <p class="font-headline font-black text-primary text-lg">Jawa Barat</p>
                </div>
                <div class="flex flex-col gap-1">
                    <p class="text-[10px] text-primary font-bold uppercase tracking-widest">Distributor</p>
                    <p class="font-bold text-gray-900 text-sm">PT. Industrial Mandiri</p>
                    <p class="text-[10px] text-slate-400 uppercase tracking-widest">Bandung Hub</p>
                </div>
                <div>
                    {{-- BACKEND-TODO: Link WA dari distributor->whatsapp_number --}}
                    <a href="https://wa.me/628xxxxxxxxxx" target="_blank" aria-label="Hubungi distributor via WhatsApp"
                        class="w-full bg-secondary text-white px-4 py-3 font-headline font-bold text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[3px_3px_0_var(--color-gray-900)] hover:bg-secondary-dark active:translate-y-0.5 active:shadow-none transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                        Hubungi WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
