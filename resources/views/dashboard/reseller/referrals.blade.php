<x-layouts.dashboard bgTheme="dark">
    @php
        $user = Auth::user();
        $viewType = request()->query('type', 'target'); // default to target
        
        // Fetch bonus logs manually within the view to avoid changing controller logic
        $allLogs = \App\Models\BonusAllocation::where('user_id', $user->id)
            ->latest()
            ->get()
            ->map(function($log) {
                return [
                    'nama' => $log->description,
                    'ket' => $log->type === 'target' ? 'Pencapaian Target Bulanan' : 'Bonus Ajakan Member Baru',
                    'nominal' => '+Rp ' . number_format($log->amount, 0, ',', '.'),
                    'tgl' => $log->created_at->translatedFormat('d M Y'),
                    'type' => $log->type
                ];
            });
            
        $targetLogs = $allLogs->where('type', 'target');
        $referralLogs = $allLogs->where('type', 'referral');
            
        // Producer targets
        $targetQty = \App\Models\Setting::where('key', 'monthly_target_qty')->value('value') ?? 1000;
        $progressPercent = min(($personalSales / $targetQty) * 100, 100);

        // Determine balance and titles based on viewType
        $currentBalance = ($viewType === 'target') ? $personalBonus : $referralBonus;
        $pageTitle = ($viewType === 'target') ? 'Bonus Target Bulanan' : 'Kode Referral Saya';
        $pageSubtitle = ($viewType === 'target') ? 'Pantau pencapaian target produsen dan klaim bonus Anda' : 'Bagikan kode referral Anda dan pantau member yang telah bergabung';
        $withdrawLabel = ($viewType === 'target') ? 'CAIRKAN BONUS TARGET' : 'CAIRKAN BONUS REFERRAL';
    @endphp

    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">PORTAL RESELLER</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>{{ $pageTitle }}</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.reseller._menu')</x-slot:menuSlot>

    <div x-data="{ 
        subTab: 'logs',
        showWithdrawModal: false,
        requestSent: false,
        isLoading: false,
        submitRequest() {
            this.isLoading = true;
            setTimeout(() => {
                this.isLoading = false;
                this.requestSent = true;
                this.showWithdrawModal = false;
            }, 1500);
        }
    }" class="flex flex-col gap-8">
        
        {{-- Success Notification --}}
        <div x-show="requestSent" x-transition x-cloak
             class="fixed top-24 right-8 z-[10002] bg-green-600 text-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-gray-900)] px-6 py-4 flex items-center gap-4">
            <div class="w-8 h-8 bg-white text-green-600 flex items-center justify-center border-2 border-gray-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <p class="font-headline font-black text-sm uppercase tracking-tight italic">Permintaan Terkirim!</p>
                <p class="text-[10px] font-bold uppercase opacity-90 italic">Admin akan memproses pencairan {{ $viewType === 'target' ? 'Target' : 'Referral' }} Anda segera.</p>
            </div>
            <button @click="requestSent = false" class="text-white hover:opacity-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="w-10 h-1 bg-primary"></span>
                    <h2 class="font-headline font-black text-2xl text-primary uppercase tracking-tight italic">{{ $pageTitle }}</h2>
                </div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">{{ $pageSubtitle }}</p>
            </div>
        </div>

        {{-- BALANCED AREA --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
            {{-- Balance Card --}}
            <div class="lg:col-span-4 bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)] p-8 flex flex-col justify-center text-center">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 italic">Saldo Bonus {{ $viewType === 'target' ? 'Target' : 'Referral' }}</p>
                <h3 class="font-headline font-black text-5xl text-primary tracking-tighter italic mb-6 leading-none">Rp {{ number_format($currentBalance, 0, ',', '.') }}</h3>
                
                <button @click="showWithdrawModal = true" :disabled="requestSent || {{ $currentBalance }} <= 0"
                    :class="(requestSent || {{ $currentBalance }} <= 0) ? 'bg-slate-400 cursor-not-allowed grayscale' : 'bg-primary hover:bg-primary-hover shadow-[4px_4px_0_var(--color-gray-900)] active:translate-y-1 active:shadow-none'"
                    class="w-full text-white px-6 py-4 font-headline font-black text-xs uppercase tracking-widest border-[3px] border-gray-900 transition-all flex items-center justify-center gap-2">
                    <span>{{ $withdrawLabel }}</span>
                    <svg x-show="!requestSent" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </button>
            </div>

            {{-- Info Card --}}
            <div class="lg:col-span-8 bg-neutral-light border-[4px] border-gray-900 p-8 shadow-[8px_8px_0_rgba(0,0,0,0.05)] relative overflow-hidden">
                @if($viewType === 'target')
                    <div class="flex justify-between items-end mb-6 relative z-10">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 italic">Progres Target Bulan Ini</p>
                            <h3 class="font-headline font-black text-3xl text-gray-900 italic tracking-tight">{{ number_format($personalSales) }} / {{ number_format($targetQty) }} PCS</h3>
                        </div>
                        <span class="text-3xl font-headline font-black text-primary italic">{{ round($progressPercent) }}%</span>
                    </div>
                    <div class="h-12 bg-white border-[4px] border-gray-900 relative overflow-hidden z-10">
                        <div class="absolute top-0 left-0 h-full bg-primary transition-all duration-1000 border-r-[4px] border-gray-900" style="width: {{ $progressPercent }}%"></div>
                        <div class="absolute inset-0 flex items-center justify-center mix-blend-difference">
                            <span class="text-[11px] font-black text-white uppercase tracking-[0.5em] italic">GOAL {{ number_format($targetQty) }} PCS</span>
                        </div>
                    </div>
                    <p class="text-[10px] font-bold text-gray-500 mt-4 uppercase italic leading-relaxed">
                        {{ $personalSales >= $targetQty ? '✓ Target tercapai! Bonus akan dihitung secara akumulatif.' : '⚠ Anda butuh '.number_format($targetQty - $personalSales).' PCS lagi untuk memenuhi target produsen.' }}
                    </p>
                @else
                    <div class="flex flex-col h-full justify-between relative z-10">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 italic">Berikan Kode Referral Ini Kepada Calon Member</p>
                            <div class="flex flex-col sm:flex-row gap-3">
                                <div class="flex-1 font-mono font-black text-primary px-6 py-4 bg-white border-[3px] border-gray-900 text-xl tracking-[0.3em] flex items-center justify-center italic shadow-[4px_4px_0_var(--color-gray-900)]">
                                    {{ Auth::user()->username }}
                                </div>
                                <button type="button" @click="navigator.clipboard.writeText('{{ Auth::user()->username }}'); alert('Kode Referral disalin!')"
                                    class="bg-primary text-white px-8 py-4 font-headline font-black text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)] hover:translate-y-1 hover:shadow-none transition-all flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                    SALIN KODE
                                </button>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center gap-6">
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Member Terajak</p>
                                <p class="text-2xl font-headline font-black text-gray-900 italic">{{ count($referrals) }} Orang</p>
                            </div>
                            <div class="h-10 w-[2px] bg-gray-200"></div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Bonus per Member</p>
                                <p class="text-2xl font-headline font-black text-primary italic">Rp 50.000</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- DETAILS AREA --}}
        @if($viewType === 'target')
            <div class="bg-white border-[4px] border-gray-900 shadow-[10px_10px_0_var(--color-primary-darkest)]">
                <div class="bg-gray-900 px-6 py-4 border-b-[4px] border-gray-900">
                    <h4 class="font-headline font-black text-white text-sm uppercase italic">Riwayat Bonus Target Pencapaian</h4>
                </div>
                <div class="divide-y-2 divide-neutral-border">
                    @forelse($targetLogs as $tx)
                        <div class="p-6 flex items-center justify-between hover:bg-neutral-light transition-colors group">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-primary/10 text-primary border-primary border-2 flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                </div>
                                <div>
                                    <p class="font-black text-sm text-gray-900 uppercase italic leading-none group-hover:text-primary transition-colors">{{ $tx['nama'] }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase italic mt-1.5">{{ $tx['tgl'] }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-headline font-black text-primary text-xl italic leading-none">{{ $tx['nominal'] }}</p>
                                <p class="text-[8px] font-black text-slate-300 uppercase tracking-widest mt-1">SUCCESS</p>
                            </div>
                        </div>
                    @empty
                        <div class="py-24 text-center">
                            <p class="text-[10px] font-black text-slate-400 uppercase italic">Belum ada riwayat target tercapai.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @else
            {{-- REFERRAL VIEW WITH TABS --}}
            <div class="flex flex-col gap-0">
                {{-- Internal Tabs (Logs First) --}}
                <div class="flex gap-2 mb-6">
                    <button @click="subTab = 'logs'"
                        :class="subTab === 'logs' ? 'bg-primary text-white shadow-[4px_4px_0_var(--color-gray-900)]' : 'bg-white text-gray-400 hover:bg-neutral-light border-b-4'"
                        class="px-8 py-4 font-headline font-black text-[10px] uppercase tracking-widest border-[4px] border-gray-900 transition-all flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Riwayat Bonus
                    </button>
                    <button @click="subTab = 'members'"
                        :class="subTab === 'members' ? 'bg-primary text-white shadow-[4px_4px_0_var(--color-gray-900)]' : 'bg-white text-gray-400 hover:bg-neutral-light border-b-4'"
                        class="px-8 py-4 font-headline font-black text-[10px] uppercase tracking-widest border-[4px] border-gray-900 transition-all flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Member Terajak
                    </button>
                </div>

                {{-- Tab Content --}}
                <div class="bg-white border-[4px] border-gray-900 shadow-[10px_10px_0_var(--color-primary-darkest)]">
                    
                    {{-- TAB: LOGS (PRIMARY) --}}
                    <div x-show="subTab === 'logs'" x-transition:enter="transition ease-out duration-200">
                        <div class="bg-gray-900 px-6 py-4 border-b-[4px] border-gray-900">
                            <span class="font-headline font-black text-white text-xs uppercase italic">Log Perolehan Bonus Referral</span>
                        </div>
                        <div class="divide-y-2 divide-neutral-border">
                            @forelse($referralLogs as $tx)
                            <div class="p-6 flex items-center justify-between hover:bg-neutral-light transition-colors group">
                                <div class="flex items-center gap-5">
                                    <div class="w-12 h-12 bg-primary/10 text-primary border-primary border-2 flex items-center justify-center shadow-[3px_3px_0_var(--color-gray-900)]">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                    </div>
                                    <div>
                                        <p class="font-black text-sm text-gray-900 uppercase italic leading-none group-hover:text-primary transition-colors">{{ $tx['nama'] }}</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase italic mt-1.5 tracking-widest">BONUS UNDANGAN</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-headline font-black text-primary text-xl italic leading-none">{{ $tx['nominal'] }}</p>
                                    <p class="text-[8px] font-black text-slate-300 uppercase tracking-widest mt-1">DITERIMA</p>
                                </div>
                            </div>
                            @empty
                            <div class="py-24 text-center">
                                <p class="text-[10px] font-black text-slate-400 uppercase italic">Belum ada riwayat bonus referral tercatat.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- TAB: MEMBERS (SECONDARY) --}}
                    <div x-show="subTab === 'members'" x-transition:enter="transition ease-out duration-200" style="display: none;">
                        <div class="bg-gray-900 px-6 py-4 border-b-[4px] border-gray-900">
                            <span class="font-headline font-black text-white text-xs uppercase italic">Daftar Member Yang Bergabung</span>
                        </div>
                        <div class="divide-y-2 divide-neutral-border">
                            @forelse($referrals as $ref)
                            <div class="px-8 py-5 hover:bg-neutral-light transition-colors flex justify-between items-center">
                                <div class="flex items-center gap-5">
                                    <div class="w-12 h-12 bg-primary/10 border-2 border-primary flex items-center justify-center font-black text-primary text-lg uppercase italic shadow-[3px_3px_0_var(--color-gray-900)]">
                                        {{ substr($ref['nama'], 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-black text-sm text-gray-900 uppercase italic leading-none">{{ $ref['nama'] }}</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase italic mt-1.5 tracking-wider">Mulai Aktif: {{ $ref['bergabung'] }}</p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 border-2 {{ $ref['aktif'] ? 'border-primary text-primary bg-primary/5' : 'border-slate-300 text-slate-400' }} text-[9px] font-black uppercase italic tracking-widest shadow-[3px_3px_0_currentColor]">
                                    {{ $ref['status'] }}
                                </span>
                            </div>
                            @empty
                            <div class="py-24 text-center">
                                <p class="text-[10px] font-black text-slate-400 uppercase italic">Belum ada member yang diajak menggunakan kode Anda.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Withdrawal Modal --}}
    <template x-teleport="body">
        <div x-show="showWithdrawModal" x-cloak class="fixed inset-0 z-[10005] flex items-center justify-center p-4">
            <div x-show="showWithdrawModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showWithdrawModal = false"></div>
            
            <div x-show="showWithdrawModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 class="relative bg-white border-[6px] border-gray-900 shadow-[16px_16px_0_var(--color-primary-darkest)] w-full max-w-md overflow-hidden">
                
                <div class="bg-primary p-4 border-b-[4px] border-gray-900 flex justify-between items-center text-white">
                    <h3 class="font-headline font-black uppercase tracking-tight italic">Pencairan Bonus {{ $viewType === 'target' ? 'Target' : 'Referral' }}</h3>
                    <button @click="showWithdrawModal = false" class="hover:rotate-90 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-8 text-center">
                    <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6 border-4 border-dashed border-primary">
                        <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1 italic">Total Dana Yang Dicairkan</p>
                    <h2 class="font-headline font-black text-4xl text-primary tracking-tighter italic mb-8">Rp {{ number_format($currentBalance, 0, ',', '.') }}</h2>

                    <div class="bg-neutral-light border-2 border-gray-900 p-4 mb-8 text-left">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Tujuan Transfer:</p>
                        <p class="font-bold text-gray-900 text-sm uppercase italic">{{ $user->bank_name ?? 'N/A' }} — {{ $user->bank_account_number ?? 'N/A' }}</p>
                        <p class="text-[10px] font-bold text-primary uppercase italic mt-1">A.N. {{ $user->bank_account_name ?? $user->name }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <button @click="showWithdrawModal = false" class="bg-white text-gray-400 border-[3px] border-gray-300 py-4 font-headline font-black text-[10px] uppercase tracking-widest hover:text-gray-600 transition-colors italic">BATAL</button>
                        <button @click="submitRequest()" :disabled="isLoading"
                                class="bg-primary text-white border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] py-4 font-headline font-black text-[10px] uppercase tracking-widest hover:bg-primary-hover transition-all flex items-center justify-center gap-2 italic">
                            <template x-if="!isLoading"><span>KONFIRMASI</span></template>
                            <template x-if="isLoading"><svg class="animate-spin h-5 w-5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></template>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

</x-layouts.dashboard>
