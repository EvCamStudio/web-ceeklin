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
        activeTab: 'alokasi',
        selectedRequest: null,
        showSuccess: false,
        successMsg: '',
        
        openDetail(bonus) {
            this.selectedRequest = bonus;
            this.viewMode = 'detail';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        goBack() {
            this.viewMode = 'list';
            this.selectedRequest = null;
        }
    }">
        <div x-show="viewMode === 'list'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            {{-- Header & Tab Switcher --}}
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-10 gap-6">
                <div>
                    <h2 class="font-headline font-black text-3xl text-primary tracking-tighter uppercase leading-none italic">Pusat Bonus & Komisi</h2>
                    <div class="flex flex-wrap gap-4 mt-6">
                        <button @click="activeTab = 'alokasi'" 
                            class="px-5 py-2.5 text-[10px] font-headline font-black uppercase tracking-widest border-[3px] transition-all"
                            :class="activeTab === 'alokasi' ? 'bg-primary text-white border-gray-900 shadow-[4px_4px_0_var(--color-secondary)]' : 'bg-white text-gray-400 border-gray-200 hover:border-gray-900 hover:text-gray-900'">
                            💰 Alokasi Bonus
                        </button>
                        <button @click="activeTab = 'setting'" 
                            class="px-5 py-2.5 text-[10px] font-headline font-black uppercase tracking-widest border-[3px] transition-all"
                            :class="activeTab === 'setting' ? 'bg-gray-900 text-white border-gray-900 shadow-[4px_4px_0_var(--color-secondary)]' : 'bg-white text-gray-400 border-gray-200 hover:border-gray-900 hover:text-gray-900'">
                            ⚙️ Atur Target Bulanan
                        </button>
                    </div>
                </div>
            </div>

            {{-- TAB 1: ALOKASI BONUS --}}
            <div x-show="activeTab === 'alokasi'" x-transition>
                <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)] overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-900 text-white">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-headline font-bold uppercase tracking-widest italic">Mitra / Perusahaan</th>
                                <th class="px-6 py-4 text-[10px] font-headline font-bold uppercase tracking-widest italic">Wilayah</th>
                                <th class="px-6 py-4 text-[10px] font-headline font-bold uppercase tracking-widest text-right italic">Jumlah (IDR)</th>
                                <th class="px-6 py-4 text-[10px] font-headline font-bold uppercase tracking-widest text-center italic">Status</th>
                                <th class="px-6 py-4 text-[10px] font-headline font-bold uppercase tracking-widest text-right italic">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-neutral-border italic">
                            @forelse($bonusAllocations as $bonus)
                            <tr class="hover:bg-neutral-light transition-colors group">
                                <td class="px-6 py-4">
                                    <p class="font-headline font-black text-sm uppercase tracking-tight italic">{{ $bonus->user->name }}</p>
                                </td>
                                <td class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase italic">
                                    {{ $bonus->user->province_name }}
                                </td>
                                <td class="px-6 py-4 text-right font-headline font-black text-lg text-primary tracking-tighter italic">
                                    Rp {{ number_format($bonus->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-0.5 border-2 {{ $bonus->status === 'paid' ? 'border-green-700 text-green-700' : 'border-secondary text-secondary' }} font-bold text-[9px] uppercase tracking-widest italic whitespace-nowrap">
                                        {{ $bonus->status === 'paid' ? 'Cair' : 'Tertunda' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button @click="openDetail({ 
                                        id: '{{ $bonus->id }}', 
                                        requester: '{{ $bonus->user->name }}', 
                                        city: '{{ $bonus->user->province_name }}', 
                                        amount: 'Rp {{ number_format($bonus->amount, 0, ',', '.') }}', 
                                        bank_name: '{{ $bonus->user->bank_name }}', 
                                        bank_account_number: '{{ $bonus->user->bank_account_number }}', 
                                        bank_account_name: '{{ $bonus->user->bank_account_name }}',
                                        phone: '{{ $bonus->user->phone }}'
                                    })"
                                        class="bg-primary text-white border-[3px] border-gray-900 px-4 py-2 text-[10px] font-headline font-black uppercase tracking-widest shadow-[3px_3px_0_var(--color-gray-900)] hover:bg-primary-hover transition-all active:translate-y-0.5 active:shadow-none italic">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-20 text-center font-headline font-bold text-slate-400 uppercase tracking-widest italic">Belum ada alokasi bonus</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TAB 2: ATUR TARGET --}}
            <div x-show="activeTab === 'setting'" x-transition style="display: none;">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)] p-8">
                        <h3 class="font-headline font-black text-xl text-gray-900 uppercase mb-6 italic">Parameter Bonus Bulanan</h3>
                        <form action="{{ route('admin.bonus.update') }}" method="POST" class="space-y-6">
                            @csrf
                            <div>
                                <label class="text-[10px] font-bold text-gray-900 uppercase tracking-widest block mb-2 italic">Target Penjualan (PCS)</label>
                                <input type="number" name="target_qty" value="{{ $targetQty }}" class="w-full bg-neutral-light border-[3px] border-gray-900 px-4 py-3 font-headline font-black text-xl text-primary focus:outline-none focus:border-secondary">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-900 uppercase tracking-widest block mb-2 italic">Hadiah Bonus (IDR)</label>
                                <input type="number" name="reward_amount" value="{{ $targetReward }}" class="w-full bg-neutral-light border-[3px] border-gray-900 px-4 py-3 font-headline font-black text-xl text-primary focus:outline-none focus:border-secondary">
                            </div>
                            <button type="submit" class="w-full bg-primary text-white py-4 font-headline font-black text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)] hover:bg-primary-hover transition-all active:translate-y-1 active:shadow-none italic">
                                SIMPAN PERUBAHAN
                            </button>
                        </form>
                    </div>
                    <div class="bg-secondary/10 border-[4px] border-secondary p-8 flex flex-col justify-center italic">
                        <h4 class="font-headline font-black text-lg text-gray-900 uppercase mb-4 italic">Informasi Sistem Bonus</h4>
                        <p class="text-sm font-bold text-slate-700 leading-relaxed mb-4 italic uppercase">Bonus Referral otomatis masuk ke saldo reseller setelah downline melakukan aktivasi.</p>
                        <p class="text-sm font-bold text-slate-700 leading-relaxed italic uppercase">Bonus Target dihitung berdasarkan total akumulasi order reseller dalam satu bulan kalender.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- VIEW: DETAIL --}}
        <div x-show="viewMode === 'detail'" x-cloak style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">
            
            <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="font-headline font-black text-3xl text-primary tracking-tighter uppercase leading-none italic">Detail Pencairan</h2>
                </div>
                <button @click="goBack()" class="flex items-center gap-2 bg-white text-gray-900 px-6 py-3 text-[10px] font-bold uppercase tracking-widest border-[3px] border-gray-900 hover:bg-neutral-light transition-colors shadow-[6px_6px_0_var(--color-gray-900)] active:translate-y-1 active:shadow-none italic">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    KEMBALI
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <div class="lg:col-span-7 flex flex-col gap-6">
                    <div class="bg-white border-[4px] border-gray-900 shadow-[10px_10px_0_var(--color-primary)] p-8 italic">
                        <p class="text-[10px] font-bold text-secondary uppercase tracking-widest mb-1 italic">Penerima Bonus</p>
                        <h3 class="font-headline font-black text-2xl text-gray-900 uppercase tracking-tight italic" x-text="selectedRequest?.requester"></h3>
                        <p class="text-[10px] font-bold text-slate-500 uppercase mt-1 italic" x-text="selectedRequest?.city"></p>
                    </div>

                    <div class="bg-gray-900 border-[4px] border-gray-900 shadow-[12px_12px_0_var(--color-secondary)] p-8 text-white relative overflow-hidden italic">
                        <h4 class="font-headline font-black text-lg text-secondary uppercase mb-8 tracking-tighter flex items-center gap-3 italic">
                            DETAIL REKENING TUJUAN
                        </h4>

                        <div class="space-y-6 relative z-10">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1 italic">Nama Bank</p>
                                    <p class="font-headline font-black text-xl text-white uppercase italic" x-text="selectedRequest?.bank_name"></p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1 italic">Nomor Rekening</p>
                                    <p class="font-headline font-black text-xl text-secondary tracking-widest italic" x-text="selectedRequest?.bank_account_number"></p>
                                </div>
                            </div>
                            <div class="pt-6 border-t border-white/10">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1 italic">Atas Nama Pemilik Rekening</p>
                                <p class="font-headline font-black text-2xl text-white uppercase tracking-tight italic" x-text="selectedRequest?.bank_account_name"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5 flex flex-col gap-6">
                    <div class="bg-neutral border-[4px] border-gray-900 shadow-[10px_10px_0_var(--color-gray-900)] p-8 italic">
                        <div class="text-center mb-8">
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-2 italic">TOTAL NOMINAL CAIR</p>
                            <div class="font-headline font-black text-5xl text-primary tracking-tighter italic" x-text="selectedRequest?.amount"></div>
                        </div>
                    </div>

                    <div class="bg-white border-[4px] border-gray-900 p-8 shadow-[10px_10px_0_var(--color-gray-900)] flex flex-col gap-4 italic">
                        <button class="w-full bg-primary text-white py-5 font-headline font-black text-base uppercase tracking-widest border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-1 active:shadow-none transition-all flex items-center justify-center gap-3 italic">
                            <span>KONFIRMASI PEMBAYARAN</span>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
