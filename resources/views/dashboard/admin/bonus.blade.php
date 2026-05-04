<x-layouts.dashboard bgTheme="light">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">MASTER ADMIN</span>
    </x-slot:subtitleSlot>

    <x-slot:topbarTitle>MANAJEMEN & PENCAIRAN BONUS</x-slot:topbarTitle>

    <x-slot:menuSlot>
        @include('dashboard.admin._menu')
    </x-slot:menuSlot>

    <div class="max-w-[1400px] mx-auto w-full" x-data="{
        viewMode: 'list',
        activeTab: 'pencairan',
        selectedRequest: null,
        targetQty: {{ $targetQty }},
        rewardAmount: {{ $targetReward }},
        bonusRequests: {{ $bonusRequests->map(fn($r) => [
            'id' => 'WD-' . $r->id,
            'db_id' => $r->id,
            'requester' => $r->user->name,
            'phone' => $r->user->phone,
            'city' => $r->user->city_name,
            'distributor' => $r->user->upline->name ?? 'Pusat',
            'amount' => 'Rp ' . number_format($r->amount),
            'period' => $r->created_at->translatedFormat('F Y'),
            'date' => $r->created_at->diffForHumans(),
            'bank_name' => $r->user->bank_name ?? 'N/A',
            'bank_account_number' => $r->user->bank_account_number ?? 'N/A',
            'bank_account_name' => $r->user->bank_account_name ?? $r->user->name
        ])->toJson() }},
        leaderboard: {{ $leaderboard->map(fn($item) => [
            'id' => $item->reseller_id,
            'name' => $item->reseller->name ?? 'N/A',
            'city' => $item->reseller->city_name ?? 'N/A',
            'distributor' => $item->reseller->upline->name ?? 'Pusat',
            'sales' => $item->total_sales,
            'progress' => $item->progress,
            'potential' => $item->potential
        ])->toJson() }},
        openDetail(req) {
            this.selectedRequest = req;
            this.viewMode = 'detail';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        goBack() {
            this.viewMode = 'list';
            this.selectedRequest = null;
        },
        handleConfirm() {
            this.$refs.approveForm.submit();
        },
        handleReject() {
            this.$refs.rejectForm.submit();
        },
        saveTarget() {
            this.$refs.targetForm.submit();
        },
        getWaLink(req) {
            const phone = (req.phone ?? '').replace(/\D/g, '');
            const msg = `Halo ${req.requester}, saya Admin CeeKlin ingin mendiskusikan pengajuan pencairan bonus Anda (${req.id}) sebesar ${req.amount}.`;
            return 'https://wa.me/62' + (phone.startsWith('0') ? phone.substring(1) : phone) + '?text=' + encodeURIComponent(msg);
        }
    }">
        {{-- Hidden Action Forms --}}
        <form x-ref="approveForm" action="{{ route('admin.bonus.approve') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="request_id" :value="selectedRequest?.db_id">
        </form>
        <form x-ref="rejectForm" action="{{ route('admin.bonus.reject') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="request_id" :value="selectedRequest?.db_id">
        </form>
        <form x-ref="targetForm" action="{{ route('admin.bonus.update') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="target_qty" :value="targetQty">
            <input type="hidden" name="reward_amount" :value="rewardAmount">
        </form>
        <div x-show="viewMode === 'list'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        {{-- Header & Tab Switcher --}}
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-10 gap-6">
            <div>
                <h2 class="font-headline font-black text-3xl text-primary tracking-tighter uppercase leading-none">Pusat Bonus Reseller</h2>
                <div class="flex flex-wrap gap-4 mt-6">
                    <button @click="activeTab = 'pencairan'" 
                        class="px-5 py-2.5 text-[10px] font-headline font-black uppercase tracking-widest border-[3px] transition-all"
                        :class="activeTab === 'pencairan' ? 'bg-primary text-white border-gray-900 shadow-[4px_4px_0_var(--color-secondary)]' : 'bg-white text-gray-400 border-gray-200 hover:border-gray-900 hover:text-gray-900'">
                        💰 Antrean Pencairan
                        <span class="ml-2 px-1.5 py-0.5 bg-secondary text-gray-900 text-[8px] font-black" x-text="bonusRequests.length"></span>
                    </button>
                    <button @click="activeTab = 'monitoring'" 
                        class="px-5 py-2.5 text-[10px] font-headline font-black uppercase tracking-widest border-[3px] transition-all"
                        :class="activeTab === 'monitoring' ? 'bg-primary text-white border-gray-900 shadow-[4px_4px_0_var(--color-secondary)]' : 'bg-white text-gray-400 border-gray-200 hover:border-gray-900 hover:text-gray-900'">
                        📈 Monitoring Performa
                    </button>
                    <button @click="activeTab = 'setting'" 
                        class="px-5 py-2.5 text-[10px] font-headline font-black uppercase tracking-widest border-[3px] transition-all"
                        :class="activeTab === 'setting' ? 'bg-gray-900 text-white border-gray-900 shadow-[4px_4px_0_var(--color-secondary)]' : 'bg-white text-gray-400 border-gray-200 hover:border-gray-900 hover:text-gray-900'">
                        ⚙️ Atur Target
                    </button>
                </div>
            </div>
        </div>


        {{-- TAB 1: ANTREAN PENCAIRAN --}}
        <div x-show="activeTab === 'pencairan'" x-transition>
            <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)] overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-900 text-white">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-headline font-bold uppercase tracking-widest">Penerima Bonus</th>
                            <th class="px-6 py-4 text-[10px] font-headline font-bold uppercase tracking-widest">Suplai Oleh</th>
                            <th class="px-6 py-4 text-[10px] font-headline font-bold uppercase tracking-widest">ID / Tgl</th>
                            <th class="px-6 py-4 text-[10px] font-headline font-bold uppercase tracking-widest text-right">Nominal</th>
                            <th class="px-6 py-4 text-[10px] font-headline font-bold uppercase tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 divide-neutral-border">
                        <template x-for="req in bonusRequests" :key="req.id">
                            <tr class="hover:bg-neutral-light transition-colors group text-gray-900">
                                <td class="px-6 py-4">
                                    <p class="font-headline font-black text-sm uppercase tracking-tight" x-text="req.requester"></p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase" x-text="req.city"></p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-[8px] font-black text-primary uppercase mb-0.5 tracking-tighter">Distributor</span>
                                        <p class="text-[10px] font-bold text-slate-500 uppercase leading-none italic" x-text="req.distributor"></p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-[10px] font-black text-secondary uppercase leading-none" x-text="req.id"></p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase mt-1" x-text="req.date"></p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <p class="font-headline font-black text-lg text-primary tracking-tighter" x-text="req.amount"></p>
                                    <p class="text-[8px] font-black text-slate-400 uppercase" x-text="req.period"></p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button @click="openDetail(req)"
                                        class="bg-primary text-white border-[3px] border-gray-900 px-4 py-2 text-[10px] font-headline font-black uppercase tracking-widest shadow-[3px_3px_0_var(--color-gray-900)] hover:bg-primary-hover transition-all active:translate-y-0.5 active:shadow-none">
                                        CAIRKAN
                                    </button>
                                </td>
                            </tr>
                        </template>
                        <template x-if="bonusRequests.length === 0">
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <p class="font-headline font-bold text-slate-300 text-xl uppercase italic">Tidak ada antrean pencairan bonus</p>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TAB 2: MONITORING PERFORMA --}}
        <div x-show="activeTab === 'monitoring'" x-transition style="display: none;">
            <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)] overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-neutral-light border-b-2 border-gray-900">
                        <tr>
                            <th class="px-8 py-4 text-[10px] font-headline font-bold text-gray-900 uppercase tracking-widest">Nama Reseller</th>
                            <th class="px-6 py-4 text-[10px] font-headline font-bold text-gray-900 uppercase tracking-widest">Suplai Oleh</th>
                            <th class="px-6 py-4 text-[10px] font-headline font-bold text-gray-900 uppercase tracking-widest text-center">Pencapaian</th>
                            <th class="px-6 py-4 text-[10px] font-headline font-bold text-gray-900 uppercase tracking-widest">Progress Target</th>
                            <th class="px-8 py-4 text-[10px] font-headline font-bold text-gray-900 uppercase tracking-widest text-right">Potensi Bonus</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 divide-neutral-border">
                        <template x-for="item in leaderboard" :key="item.id">
                            <tr class="hover:bg-neutral-light transition-colors">
                                <td class="px-8 py-6">
                                    <p class="font-headline font-bold text-sm text-gray-900 uppercase" x-text="item.name"></p>
                                    <p class="text-[9px] font-bold text-slate-500 uppercase" x-text="item.city"></p>
                                </td>
                                <td class="px-6 py-6">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase italic leading-tight" x-text="item.distributor"></p>
                                </td>
                                <td class="px-6 py-6 text-center">
                                    <p class="font-headline font-black text-xl text-primary leading-none" x-text="item.sales"></p>
                                    <p class="text-[8px] font-black uppercase mt-1 text-slate-400">PCS</p>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="w-full bg-slate-100 border-2 border-gray-900 h-6 relative overflow-hidden">
                                        <div class="absolute inset-0 bg-primary border-r-2 border-gray-900 transition-all duration-500" :style="'width: ' + item.progress + '%'"></div>
                                        <span class="absolute inset-0 flex items-center justify-center text-[9px] font-black text-gray-900 uppercase mix-blend-difference" x-text="item.progress + '%'"></span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <p class="font-headline font-black text-lg text-gray-900 tracking-tighter" x-text="item.potential"></p>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TAB 3: ATUR TARGET --}}
        <div x-show="activeTab === 'setting'" x-transition style="display: none;">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)] p-8">
                    <h3 class="font-headline font-black text-xl text-gray-900 uppercase mb-6">Parameter Bonus Bulanan</h3>
                    <form class="space-y-6">
                        <div class="relative">
                            <label class="text-[10px] font-bold text-gray-900 uppercase tracking-widest block mb-2">Minimal Order (PCS)</label>
                            <input type="number" x-model="targetQty" class="w-full bg-neutral-light border-[3px] border-gray-900 px-4 py-3 font-headline font-black text-xl text-primary focus:outline-none focus:border-secondary">
                            <x-ui.error name="target_qty" />
                        </div>
                        <div class="relative">
                            <label class="text-[10px] font-bold text-gray-900 uppercase tracking-widest block mb-2">Reward Bonus (IDR)</label>
                            <input type="number" x-model="rewardAmount" class="w-full bg-neutral-light border-[3px] border-gray-900 px-4 py-3 font-headline font-black text-xl text-primary focus:outline-none focus:border-secondary">
                            <x-ui.error name="reward_amount" />
                        </div>
                        <button type="button" @click="saveTarget()" 
                            class="w-full bg-primary text-white py-4 font-headline font-black text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)] hover:bg-primary-hover transition-all active:translate-y-1 active:shadow-none">
                            SIMPAN PERUBAHAN
                        </button>
                    </form>
                </div>
                <div class="bg-secondary/10 border-[4px] border-secondary p-8 flex flex-col justify-center">
                    <h4 class="font-headline font-black text-lg text-gray-900 uppercase mb-4">Informasi Sistem Bonus</h4>
                    <p class="text-sm font-bold text-slate-700 leading-relaxed mb-4">Bonus Referral otomatis masuk ke saldo reseller setelah downline melakukan aktivasi.</p>
                    <p class="text-sm font-bold text-slate-700 leading-relaxed">Bonus Target dihitung berdasarkan total akumulasi order reseller dalam satu bulan kalender.</p>
                </div>
            </div>
        </div>

        </div> {{-- End List View --}}

        {{-- ====================== --}}
        {{-- VIEW: DETAIL CAIRKAN   --}}
        {{-- ====================== --}}
        <div x-show="viewMode === 'detail'" x-cloak style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">
            
            <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="font-headline font-black text-3xl text-primary tracking-tighter uppercase leading-none">Verifikasi Pencairan</h2>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-3 italic" x-text="'ID PENGAJUAN: ' + selectedRequest?.id"></p>
                </div>
                <button @click="goBack()" class="flex items-center gap-2 bg-white text-gray-900 px-6 py-3 text-[10px] font-bold uppercase tracking-widest border-[3px] border-gray-900 hover:bg-neutral-light transition-colors shadow-[6px_6px_0_var(--color-gray-900)] active:translate-y-1 active:shadow-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    KEMBALI KE ANTREAN
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {{-- Kiri: Info Reseller & Bank --}}
                <div class="lg:col-span-7 flex flex-col gap-6">
                    
                    {{-- Card Reseller & Distributor --}}
                    <div class="bg-white border-[4px] border-gray-900 shadow-[10px_10px_0_var(--color-primary)] p-8">
                        <div class="flex items-start justify-between mb-8">
                            <div>
                                <p class="text-[10px] font-bold text-secondary uppercase tracking-widest mb-1">Penerima Bonus</p>
                                <h3 class="font-headline font-black text-2xl text-gray-900 uppercase tracking-tight" x-text="selectedRequest?.requester"></h3>
                                <p class="text-[10px] font-bold text-slate-500 uppercase mt-1" x-text="selectedRequest?.city"></p>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Disuplai Oleh</p>
                                <p class="text-[11px] font-black text-primary uppercase italic" x-text="selectedRequest?.distributor"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Card Rekening Bank (HIGHLIGHTED) --}}
                    <div class="bg-gray-900 border-[4px] border-gray-900 shadow-[12px_12px_0_var(--color-secondary)] p-8 text-white relative overflow-hidden">
                        {{-- Background Accent --}}
                        <div class="absolute -right-8 -bottom-8 opacity-10 pointer-events-none rotate-12">
                            <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24"><path d="M4 10V18C4 19.1046 4.89543 20 6 20H18C19.1046 20 20 19.1046 20 18V10H4ZM4 6V8H20V6C20 4.89543 19.1046 4 18 4H6C4.89543 4 4 4.89543 4 6ZM11 12H13V14H11V12ZM11 15H13V17H11V15ZM7 12H9V14H7V12ZM7 15H9V17H7V15ZM15 12H17V14H15V12ZM15 15H17V17H15V15Z"/></svg>
                        </div>

                        <h4 class="font-headline font-black text-lg text-secondary uppercase mb-8 tracking-tighter flex items-center gap-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            DETAIL REKENING TUJUAN
                        </h4>

                        <div class="space-y-6 relative z-10">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nama Bank</p>
                                    <p class="font-headline font-black text-xl text-white uppercase" x-text="selectedRequest?.bank_name"></p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nomor Rekening</p>
                                    <p class="font-headline font-black text-xl text-secondary tracking-widest" x-text="selectedRequest?.bank_account_number"></p>
                                </div>
                            </div>
                            <div class="pt-6 border-t border-white/10">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Atas Nama Pemilik Rekening</p>
                                <p class="font-headline font-black text-2xl text-white uppercase tracking-tight" x-text="selectedRequest?.bank_account_name"></p>
                            </div>
                        </div>

                        <div class="mt-8 bg-white/5 border border-white/10 p-4 flex items-center gap-3">
                            <svg class="w-5 h-5 text-yellow-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <p class="text-[9px] text-slate-300 font-bold leading-relaxed uppercase tracking-widest">Mohon pastikan nama pemilik rekening sesuai dengan pendaftar untuk menghindari kesalahan transfer.</p>
                        </div>
                    </div>
                </div>

                {{-- Kanan: Summary & Actions --}}
                <div class="lg:col-span-5 flex flex-col gap-6">
                    
                    {{-- Withdrawal Summary --}}
                    <div class="bg-neutral border-[4px] border-gray-900 shadow-[10px_10px_0_var(--color-gray-900)] p-8">
                        <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-6 border-b border-gray-200 pb-3 text-center italic">RINGKASAN PENCAIRAN</h4>
                        
                        <div class="text-center mb-8">
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-2">TOTAL NOMINAL CAIR</p>
                            <div class="font-headline font-black text-5xl text-primary tracking-tighter" x-text="selectedRequest?.amount"></div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 border-t-2 border-dashed border-gray-300 pt-6">
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Periode</p>
                                <p class="font-bold text-gray-900 text-sm uppercase" x-text="selectedRequest?.period"></p>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Waktu Request</p>
                                <p class="font-bold text-gray-900 text-sm uppercase" x-text="selectedRequest?.date"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="bg-white border-[4px] border-gray-900 p-8 shadow-[10px_10px_0_var(--color-gray-900)] flex flex-col gap-4">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 border-b-2 border-neutral-border pb-2 text-center uppercase">Konfirmasi Pembayaran</p>
                        
                        <button @click="handleConfirm()" class="w-full bg-primary text-white py-5 font-headline font-black text-base uppercase tracking-widest border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-1 active:shadow-none transition-all flex items-center justify-center gap-3">
                            <span>PROSES PENCAIRAN</span>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </button>

                        <div class="grid grid-cols-2 gap-4">
                            <button @click="handleReject()" class="w-full bg-white text-red-600 py-3 font-headline font-bold text-[10px] uppercase tracking-widest border-[3px] border-red-600 hover:bg-red-50 transition-colors shadow-[4px_4px_0_var(--color-red-600)] active:translate-y-0.5 active:shadow-none">
                                TANGGUHKAN
                            </button>
                            <a :href="getWaLink(selectedRequest)" target="_blank" class="w-full bg-[#25D366] text-white py-3 font-headline font-bold text-[10px] uppercase tracking-widest border-[3px] border-gray-900 flex items-center justify-center gap-2 hover:bg-[#1DA851] transition-all shadow-[4px_4px_0_var(--color-gray-900)] active:translate-y-0.5 active:shadow-none">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.347-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                WA RESELLER
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
