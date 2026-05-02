<x-layouts.dashboard bgTheme="light">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">MASTER ADMIN</span>
    </x-slot:subtitleSlot>

    <x-slot:topbarTitle>MANAJEMEN & PENCAIRAN BONUS</x-slot:topbarTitle>

    <x-slot:menuSlot>
        @include('dashboard.admin._menu')
    </x-slot:menuSlot>

    <div class="max-w-[1400px] mx-auto w-full" x-data="{
        activeTab: 'pencairan',
        showModal: false,
        selectedRequest: null,
        showSuccess: false,
        successMsg: '',
        bonusRequests: [
            { id: 'WD-201', requester: 'Ahmad Fauzi', city: 'Bandung', distributor: 'Pusat Distribusi Bandung', amount: 'Rp 1.050.000', period: 'Mei 2026', date: 'Hari ini, 11:20' },
            { id: 'WD-199', requester: 'Siti Aminah', city: 'Bekasi', distributor: 'Teknik Karya Supply', amount: 'Rp 450.000', period: 'Mei 2026', date: '2 Jam Lalu' }
        ],
        leaderboard: [
            { id: 1, name: 'Ahmad Fauzi', city: 'Bandung', distributor: 'Pusat Distribusi Bandung', sales: 850, progress: 85, potential: 'Rp 2.125.000' },
            { id: 2, name: 'Siti Aminah', city: 'Bekasi', distributor: 'Teknik Karya Supply', sales: 420, progress: 42, potential: 'Rp 1.050.000' },
            { id: 3, name: 'Budi Hermawan', city: 'Cimahi', distributor: 'Pusat Distribusi Bandung', sales: 120, progress: 12, potential: 'Rp 300.000' }
        ],
        openModal(req) {
            this.selectedRequest = req;
            this.showModal = true;
        },
        handleConfirm() {
            this.showModal = false;
            this.bonusRequests = this.bonusRequests.filter(r => r.id !== this.selectedRequest.id);
            this.successMsg = 'Pencairan Bonus Berhasil Diproses!';
            this.showSuccess = true;
            setTimeout(() => { this.showSuccess = false; }, 3000);
        }
    }">
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

        {{-- Success Alert --}}
        <div x-show="showSuccess" x-transition x-cloak class="mb-6 bg-green-500 text-white p-4 border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-green-700)] flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            <span class="font-headline font-bold text-sm uppercase tracking-widest" x-text="successMsg"></span>
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
                                    <button @click="openModal(req)"
                                        class="bg-gray-900 text-white border-[3px] border-gray-900 px-4 py-2 text-[10px] font-headline font-black uppercase tracking-widest shadow-[3px_3px_0_var(--color-primary)] hover:bg-primary transition-all active:translate-y-0.5 active:shadow-none">
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
                        <div>
                            <label class="text-[10px] font-bold text-gray-900 uppercase tracking-widest block mb-2">Minimal Order (PCS)</label>
                            <input type="number" value="1000" class="w-full bg-neutral-light border-[3px] border-gray-900 px-4 py-3 font-headline font-black text-xl text-primary focus:outline-none focus:border-secondary">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-900 uppercase tracking-widest block mb-2">Reward Bonus (IDR)</label>
                            <input type="number" value="2500000" class="w-full bg-neutral-light border-[3px] border-gray-900 px-4 py-3 font-headline font-black text-xl text-primary focus:outline-none focus:border-secondary">
                        </div>
                        <button type="button" @click="successMsg = 'Parameter Target Diupdate!'; showSuccess = true; setTimeout(() => showSuccess = false, 3000)" 
                            class="w-full bg-gray-900 text-white py-4 font-headline font-black text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[6px_6px_0_var(--color-primary)] hover:bg-primary transition-all active:translate-y-1 active:shadow-none">
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

        {{-- Modal Konfirmasi --}}
        <div x-show="showModal" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" x-transition>
            <div class="bg-white border-[6px] border-gray-900 shadow-[15px_15px_0_var(--color-primary)] w-full max-w-md p-8" @click.away="showModal = false">
                <h3 class="font-headline font-black text-2xl text-gray-900 uppercase tracking-tighter mb-4">Konfirmasi Pencairan</h3>
                <p class="text-sm font-bold text-slate-600 mb-8 leading-relaxed">
                    Apakah Anda yakin ingin mencairkan bonus sebesar <span class="text-primary font-black" x-text="selectedRequest?.amount"></span> untuk <span class="text-primary" x-text="selectedRequest?.requester"></span>?
                </p>
                <div class="flex gap-4">
                    <button @click="showModal = false" class="flex-1 py-4 border-[3px] border-gray-900 font-headline font-bold text-xs uppercase tracking-widest hover:bg-neutral-light transition-colors uppercase">BATAL</button>
                    <button @click="handleConfirm()" class="flex-1 py-4 bg-primary text-white font-headline font-black text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-1 active:shadow-none transition-all uppercase">YA, CAIRKAN</button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
