<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">RESELLER RESMI</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>PESANAN SAYA</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.reseller._menu')</x-slot:menuSlot>

    <div class="max-w-[1400px] mx-auto w-full" x-data="{ tab: 'orders' }">

        {{-- Tab Navigation --}}
        <div class="flex border-b-[3px] border-gray-900 mb-6">
            <button @click="tab = 'orders'"
                :class="tab === 'orders' ? 'bg-primary text-white border-b-[3px] border-secondary -mb-[3px]' : 'bg-white text-gray-600 hover:bg-neutral-light'"
                class="px-6 py-3 font-headline font-bold text-xs uppercase tracking-widest border-[3px] border-gray-900 border-b-0 transition-colors">
                Pesanan Aktif
            </button>
            <button @click="tab = 'history'"
                :class="tab === 'history' ? 'bg-primary text-white border-b-[3px] border-secondary -mb-[3px]' : 'bg-white text-gray-600 hover:bg-neutral-light'"
                class="px-6 py-3 font-headline font-bold text-xs uppercase tracking-widest border-[3px] border-l-0 border-gray-900 border-b-0 transition-colors">
                Riwayat & Bonus
            </button>
        </div>

        {{-- ========================= --}}
        {{-- TAB: PESANAN AKTIF        --}}
        {{-- ========================= --}}
        <div x-show="tab === 'orders'"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0">

            {{-- BACKEND-TODO: Loop dari Order::where('reseller_id', Auth::id())->orderBy('created_at', 'desc')->get() --}}
            @php
            $orders = [
                ['id' => 'ORD-201', 'qty' => 100, 'total' => 'Rp 1.500.000', 'distributor' => 'PT Tirta Makmur', 'status' => 'Dikirim',          'date' => 'Hari ini',    'statusClass' => 'border-blue-500 text-blue-700 bg-blue-50'],
                ['id' => 'ORD-195', 'qty' => 50,  'total' => 'Rp 750.000',   'distributor' => 'PT Tirta Makmur', 'status' => 'Dikemas',          'date' => 'Kemarin',     'statusClass' => 'border-yellow-500 text-yellow-800 bg-yellow-50'],
                ['id' => 'ORD-188', 'qty' => 75,  'total' => 'Rp 1.125.000', 'distributor' => 'PT Tirta Makmur', 'status' => 'Menunggu Proses',  'date' => '3 Hari Lalu', 'statusClass' => 'border-red-400 text-red-700 bg-red-50'],
                ['id' => 'ORD-171', 'qty' => 50,  'total' => 'Rp 750.000',   'distributor' => 'PT Tirta Makmur', 'status' => 'Selesai',          'date' => '2 Apr 2026',  'statusClass' => 'border-green-600 text-green-700 bg-green-50'],
            ];
            @endphp

            <div class="flex flex-col gap-4">
                @foreach($orders as $order)
                <div class="bg-white border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-primary-darkest)] overflow-hidden">

                    {{-- Header Row --}}
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between px-5 py-4 bg-neutral-light border-b-2 border-neutral-border gap-3">
                        <div class="flex items-center gap-4 flex-wrap">
                            <span class="font-headline font-black text-sm text-gray-900">{{ $order['id'] }}</span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $order['date'] }}</span>
                            <span class="text-[9px] font-bold text-slate-500 uppercase">via {{ $order['distributor'] }}</span>
                        </div>
                        <span class="px-3 py-1 border-2 {{ $order['statusClass'] }} text-[9px] font-bold uppercase tracking-widest">
                            {{ $order['status'] }}
                        </span>
                    </div>

                    {{-- Order Detail --}}
                    <div class="px-5 py-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gray-900 flex items-center justify-center flex-shrink-0 border-2 border-gray-900">
                                <img src="/images/hero-bottle.jpeg" alt="CeeKlin" class="w-full h-auto mix-blend-screen opacity-80">
                            </div>
                            <div>
                                <p class="font-bold text-sm text-gray-900 uppercase leading-tight">CeeKlin 450ml</p>
                                <p class="text-[10px] font-bold text-slate-500 mt-0.5">{{ $order['qty'] }} PCS × Rp 15.000</p>
                            </div>
                        </div>
                        <div class="flex flex-col sm:items-end gap-2">
                            <p class="font-headline font-black text-lg text-primary tracking-tighter">{{ $order['total'] }}</p>

                            {{-- ===== KONFIRMASI TERIMA (hanya untuk status Dikirim) ===== --}}
                            @if($order['status'] === 'Dikirim')
                            <div x-data="{ confirmed: false }" class="w-full">
                                <div x-show="!confirmed">
                                    {{-- BACKEND-TODO: form POST ke OrderController@confirmReceived --}}
                                    <form action="/dashboard/reseller/orders/confirm" method="POST"
                                          @submit.prevent="confirmed = true">
                                        @csrf
                                        <input type="hidden" name="order_id" value="{{ $order['id'] }}">
                                        <button type="submit"
                                            class="w-full sm:w-auto bg-secondary text-white px-5 py-2.5 font-headline font-black text-[10px] uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-secondary-dark active:translate-y-0.5 active:shadow-none transition-all flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span>Konfirmasi Terima</span>
                                        </button>
                                    </form>
                                </div>
                                <div x-show="confirmed" x-transition class="flex items-center gap-2 text-green-600 font-bold text-xs uppercase tracking-widest" style="display:none;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    Dikonfirmasi ✓
                                </div>
                            </div>

                            @elseif($order['status'] === 'Selesai')
                            <span class="text-[10px] font-bold text-green-600 uppercase tracking-widest flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                Diterima & Selesai
                            </span>

                            @else
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Menunggu diproses distributor...</span>
                            @endif
                        </div>
                    </div>

                    {{-- Progress Bar Status --}}
                    <div class="px-5 pb-4">
                        @php
                        $steps = ['Menunggu', 'Dikemas', 'Dikirim', 'Selesai'];
                        $stepsFull = ['Menunggu Proses', 'Dikemas', 'Dikirim', 'Selesai'];
                        $currentIdx = array_search($order['status'], $stepsFull);
                        if ($currentIdx === false) $currentIdx = 0;
                        @endphp
                        <div class="flex items-center gap-0">
                            @foreach($steps as $i => $step)
                            <div class="flex-1 flex flex-col items-center">
                                <div class="w-full flex items-center">
                                    @if($i > 0)
                                    <div class="flex-1 h-[2px] {{ $i <= $currentIdx ? 'bg-primary' : 'bg-neutral-border' }}"></div>
                                    @endif
                                    <div class="w-4 h-4 rounded-full border-2 flex-shrink-0 flex items-center justify-center
                                        {{ $i <= $currentIdx ? 'bg-primary border-primary' : 'bg-white border-neutral-border' }}">
                                        @if($i <= $currentIdx)
                                        <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        @endif
                                    </div>
                                    @if($i < count($steps) - 1)
                                    <div class="flex-1 h-[2px] {{ $i < $currentIdx ? 'bg-primary' : 'bg-neutral-border' }}"></div>
                                    @endif
                                </div>
                                <p class="text-[7px] font-bold uppercase tracking-tight mt-1 text-center leading-tight
                                    {{ $i <= $currentIdx ? 'text-primary' : 'text-slate-400' }}">
                                    {{ $step }}
                                </p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ========================= --}}
        {{-- TAB: RIWAYAT & BONUS      --}}
        {{-- ========================= --}}
        <div x-show="tab === 'history'" style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0">

            {{-- KPI Mini --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white border-[3px] border-primary shadow-[6px_6px_0_var(--color-primary-darkest)] p-6">
                    <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-1">Total Transaksi</p>
                    <h3 class="font-headline font-black text-2xl sm:text-3xl text-primary tracking-tighter italic">Rp 12.500.000</h3>
                </div>
                <div class="bg-white border-[3px] border-secondary shadow-[6px_6px_0_var(--color-gray-900)] p-6">
                    <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-1">Bulan Ini</p>
                    <h3 class="font-headline font-black text-2xl sm:text-3xl text-primary tracking-tighter italic">Rp 3.200.000</h3>
                </div>
                <div class="bg-white border-[3px] border-primary shadow-[6px_6px_0_var(--color-primary-hover)] p-6">
                    <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-1">Bonus Menunggu Cair</p>
                    <h3 class="font-headline font-black text-2xl sm:text-3xl text-primary tracking-tighter italic">Rp 800.000</h3>
                </div>
            </div>

            {{-- Riwayat Bonus --}}
            <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-secondary)]">
                <div class="bg-secondary px-6 py-3">
                    <span class="font-headline font-black text-white text-sm uppercase tracking-tight">Riwayat Bonus & Komisi</span>
                </div>
                <div class="divide-y-2 divide-neutral-border">
                    {{-- BACKEND-TODO: Loop dari Earning::where('reseller_id', Auth::id())->latest()->get() --}}
                    @foreach([
                        ['nama'=>'Bonus Penjualan — Oktober','ket'=>'Komisi penjualan langsung','nominal'=>'+Rp 3.200.000','tgl'=>'15 Okt 2024','type'=>'target'],
                        ['nama'=>'Bonus Referral — CV Maju Jaya','ket'=>'Referral 3 pesanan pertama','nominal'=>'+Rp 750.000','tgl'=>'08 Okt 2024','type'=>'referral'],
                        ['nama'=>'Bonus Penjualan — September','ket'=>'Komisi penjualan langsung','nominal'=>'+Rp 2.800.000','tgl'=>'15 Sep 2024','type'=>'target'],
                    ] as $tx)
                    <div class="flex items-start justify-between px-5 py-4 gap-4 hover:bg-neutral-light transition-colors">
                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 px-1.5 py-0.5 text-[7px] font-black uppercase tracking-wider {{ $tx['type'] === 'referral' ? 'bg-secondary/10 text-secondary border border-secondary/30' : 'bg-primary/10 text-primary border border-primary/30' }}">
                                {{ $tx['type'] === 'referral' ? 'REF' : 'TGT' }}
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-xs text-gray-900 leading-tight uppercase">{{ $tx['nama'] }}</p>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ $tx['ket'] }}</p>
                                <p class="text-[9px] text-slate-400 mt-0.5">{{ $tx['tgl'] }}</p>
                            </div>
                        </div>
                        <span class="font-headline font-black text-primary text-sm flex-shrink-0">{{ $tx['nominal'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
