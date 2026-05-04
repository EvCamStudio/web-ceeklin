<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">RESELLER TEROTORISASI</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>REFERRAL & BONUS</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.reseller._menu')</x-slot:menuSlot>

    <div x-data="{ 
        period: '{{ now()->translatedFormat('F Y') }}',
        showWithdrawModal: false,
        requestSent: false,
        isLoading: false,
        submitRequest() {
            this.isLoading = true;
            // Real AJAX submission would go here
            setTimeout(() => {
                this.isLoading = false;
                this.requestSent = true;
                this.showWithdrawModal = false;
            }, 1500);
        }
    }" class="flex flex-col gap-6">
        
        {{-- Success Notification --}}
        <div x-show="requestSent" x-transition x-cloak
             class="fixed top-24 right-8 z-[10002] bg-green-600 text-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-gray-900)] px-6 py-4 flex items-center gap-4">
            <div class="w-8 h-8 bg-white text-green-600 flex items-center justify-center border-2 border-gray-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <p class="font-headline font-black text-sm uppercase tracking-tight italic">Permintaan Terkirim!</p>
                <p class="text-[10px] font-bold uppercase opacity-90 italic">Admin akan memproses pencairan Anda segera.</p>
            </div>
            <button @click="requestSent = false" class="text-white hover:opacity-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Periode Selector --}}
        <div class="flex justify-end">
            <div class="relative w-full sm:w-48">
                <select x-model="period" aria-label="Pilih Periode Bonus"
                    class="appearance-none w-full bg-white border-[3px] border-gray-900 px-4 py-2 text-[10px] font-headline font-black uppercase tracking-widest text-primary focus:outline-none focus:border-secondary cursor-pointer pr-10 italic">
                    <option>{{ now()->translatedFormat('F Y') }}</option>
                    <option>{{ now()->subMonth()->translatedFormat('F Y') }}</option>
                    <option>{{ now()->subMonths(2)->translatedFormat('F Y') }}</option>
                </select>
                <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                </div>
            </div>
        </div>

        {{-- Panel Kiri: Link Referral + Statistik --}}
        <div class="xl:col-span-2 flex flex-col gap-6">

            {{-- Referral Link --}}
            <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">
                <div class="bg-primary px-6 py-3 flex items-center justify-between">
                    <span class="font-headline font-black text-white text-base uppercase tracking-tight italic">Rincian Bonus — <span x-text="period"></span></span>
                    <span class="text-[9px] font-black bg-white text-primary px-2 py-0.5 border border-gray-900 italic">VERIFIED ✓</span>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                        <div class="space-y-4">
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1 italic">Volume Penjualan Pribadi</p>
                                <p class="font-headline font-black text-2xl text-gray-900 tracking-tighter italic">{{ number_format($personalSales) }} <span class="text-sm font-normal text-slate-400">PCS</span></p>
                                <p class="text-[10px] font-bold text-secondary uppercase mt-1 italic">Estimasi Bonus: Rp {{ number_format($personalBonus, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1 italic">Total Referral Order</p>
                                <p class="font-headline font-black text-2xl text-gray-900 tracking-tighter italic">{{ number_format($referralSales) }} <span class="text-sm font-normal text-slate-400">PCS</span></p>
                                <p class="text-[10px] font-bold text-primary uppercase mt-1 italic">Estimasi Override (5%): Rp {{ number_format($referralBonus, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="bg-neutral-light border-[3px] border-gray-900 p-5 flex flex-col items-center justify-center text-center shadow-[4px_4px_0_rgba(0,0,0,0.1)]">
                            <p class="text-[10px] font-black text-primary uppercase tracking-widest mb-2 italic">Total Cair Periode Ini</p>
                            <h3 class="font-headline font-black text-4xl text-primary tracking-tighter italic mb-3">Rp {{ number_format($totalToWithdraw, 0, ',', '.') }}</h3>
                            
                            <button @click="showWithdrawModal = true" :disabled="requestSent || {{ $totalToWithdraw }} <= 0"
                                :class="(requestSent || {{ $totalToWithdraw }} <= 0) ? 'bg-slate-400 cursor-not-allowed grayscale' : 'bg-primary hover:bg-primary-hover shadow-[4px_4px_0_var(--color-gray-900)]'"
                                class="w-full text-white px-4 py-3 font-headline font-black text-xs uppercase tracking-widest border-[3px] border-gray-900 transition-all flex items-center justify-center gap-2">
                                <span x-text="requestSent ? 'MENUNGGU PERSETUJUAN' : 'CAIRKAN BONUS SEKARANG'"></span>
                                <svg x-show="!requestSent" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </button>
                            <p class="text-[8px] font-bold text-slate-400 uppercase mt-3 leading-relaxed italic">Pastikan nomor rekening di pengaturan sudah benar sebelum melakukan pencairan.</p>
                        </div>
                    </div>

                    {{-- Referral Link --}}
                    <div class="pt-6 border-t-2 border-dashed border-neutral-border">
                        <p class="text-[10px] text-gray-900 font-black mb-3 uppercase tracking-widest flex items-center gap-2 italic">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.826a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                            Link Referral Anda
                        </p>
                        <div class="flex flex-col sm:flex-row items-stretch border-[3px] border-primary shadow-[4px_4px_0_rgba(0,0,0,0.1)] group">
                            <div class="flex-1 font-mono font-bold text-primary px-4 py-3 bg-white text-xs sm:text-sm tracking-widest break-all flex items-center italic">
                                ceeklin.id/ref/{{ Auth::user()->username }}
                            </div>
                            <button type="button" @click="navigator.clipboard.writeText('ceeklin.id/ref/{{ Auth::user()->username }}'); alert('Link disalin!')"
                                class="bg-primary text-white px-6 py-3 font-headline font-black text-xs uppercase tracking-widest hover:bg-primary-hover transition-colors flex items-center justify-center gap-2 border-t-[3px] sm:border-t-0 sm:border-l-[3px] border-primary group-active:translate-y-1">
                                SALIN LINK
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Anggota Referral --}}
            <div class="bg-white border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-secondary)]">
                <div class="bg-secondary px-6 py-3">
                    <span class="font-headline font-black text-white text-sm uppercase tracking-tight italic">Anggota Referral</span>
                </div>
                <div class="divide-y-2 divide-neutral-border">
                    @forelse($referrals as $ref)
                    <div class="flex items-center justify-between px-6 py-4 group hover:bg-neutral-light transition-colors">
                        <div class="min-w-0 pr-4">
                            <p class="font-bold text-xs sm:text-sm {{ $ref['aktif'] ? 'text-gray-900' : 'text-slate-400' }} truncate uppercase italic group-hover:text-primary transition-colors">{{ $ref['nama'] }}</p>
                            <p class="text-[9px] sm:text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5 italic">Mulai {{ $ref['bergabung'] }}</p>
                        </div>
                        <span class="px-2 py-0.5 border-2 {{ $ref['aktif'] ? 'border-secondary text-secondary' : 'border-slate-300 text-slate-400' }} text-[9px] font-black uppercase tracking-widest whitespace-nowrap italic">
                            {{ $ref['status'] }}
                        </span>
                    </div>
                    @empty
                    <div class="py-10 text-center">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Belum memiliki referral</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Panel Kanan: Statistik --}}
        <div class="flex flex-col gap-4">
            <div class="bg-white border-[3px] border-primary shadow-[6px_6px_0_var(--color-primary-darkest)] p-6 text-center">
                <p class="text-[10px] text-primary font-bold uppercase tracking-widest mb-2 italic">Total Referral</p>
                <h3 class="font-headline font-black text-3xl md:text-4xl text-primary tracking-tighter italic leading-none">{{ $stats['total_referral'] }}</h3>
            </div>
            <div class="bg-white border-[3px] border-secondary shadow-[6px_6px_0_var(--color-gray-900)] p-6 text-center">
                <p class="text-[10px] text-primary font-bold uppercase tracking-widest mb-2 italic">Referral Aktif</p>
                <h3 class="font-headline font-black text-3xl md:text-4xl text-primary tracking-tighter italic leading-none">{{ $stats['aktif_referral'] }}</h3>
            </div>
            <div class="bg-white border-[3px] border-primary shadow-[6px_6px_0_var(--color-secondary)] p-6 text-center">
                <p class="text-[10px] text-primary font-bold uppercase tracking-widest mb-2 italic">Komisi Referral</p>
                <h3 class="font-headline font-black text-3xl md:text-4xl text-primary tracking-tighter italic leading-none">{{ $stats['total_komisi'] }}</h3>
            </div>
        </div>
    </div>
    </div>

    {{-- Withdrawal Modal --}}
    <template x-teleport="body">
        <div x-show="showWithdrawModal" x-cloak class="fixed inset-0 z-[10005] flex items-center justify-center p-4">
            <div x-show="showWithdrawModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showWithdrawModal = false"></div>
            
            <div x-show="showWithdrawModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 class="relative bg-white border-[6px] border-gray-900 shadow-[16px_16px_0_var(--color-primary-darkest)] w-full max-w-md overflow-hidden">
                
                <div class="bg-primary p-4 border-b-[4px] border-gray-900 flex justify-between items-center">
                    <h3 class="font-headline font-black text-white uppercase tracking-tight">Konfirmasi Pencairan</h3>
                    <button @click="showWithdrawModal = false" class="text-white hover:rotate-90 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-8">
                    <div class="flex items-center gap-4 mb-6 p-4 bg-neutral-light border-[3px] border-gray-900">
                        <div class="w-12 h-12 bg-primary flex items-center justify-center text-white shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Nominal Pencairan</p>
                            <p class="font-headline font-black text-2xl text-primary tracking-tight italic">Rp 1.050.000</p>
                        </div>
                    </div>

                    <div class="space-y-4 mb-8">
                        <p class="text-xs font-bold text-gray-900 leading-relaxed uppercase">Permintaan Anda akan dikirim ke Admin untuk diverifikasi. Dana akan dikirim ke rekening terdaftar Anda:</p>
                        <div class="border-l-4 border-secondary pl-4 py-1">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Bank BCA — 123456789</p>
                            <p class="text-sm font-black text-gray-900 uppercase">A.N. {{ Auth::user()->name }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <button @click="showWithdrawModal = false" class="bg-white text-gray-400 border-[3px] border-gray-300 py-4 font-headline font-black text-xs uppercase tracking-widest hover:text-gray-600 transition-colors">
                            BATAL
                        </button>
                        <button @click="submitRequest()" :disabled="isLoading"
                                class="bg-primary text-white border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] py-4 font-headline font-black text-xs uppercase tracking-widest hover:bg-primary-hover active:translate-y-1 active:shadow-none transition-all flex items-center justify-center gap-2">
                            <template x-if="!isLoading">
                                <span>KIRIM SEKARANG</span>
                            </template>
                            <template x-if="isLoading">
                                <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </template>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

</x-layouts.dashboard>
