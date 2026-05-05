<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">RESELLER RESMI</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>PESANAN SAYA</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.reseller._menu')</x-slot:menuSlot>

    <div class="max-w-[1400px] mx-auto w-full" x-data="{ 
        statusFilter: 'all',
        showMockAlert(msg) {
            alert(msg || 'Frontend Ready! Menunggu integrasi rute dari Backend.');
        }
    }">

        {{-- Header & Filters --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="w-10 h-1 bg-primary"></span>
                    <h2 class="font-headline font-black text-2xl text-primary uppercase tracking-tight italic">Pelacakan Pesanan</h2>
                </div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">Pantau status pengiriman barang Anda secara real-time</p>
            </div>

            {{-- Status Pills --}}
            <div class="flex flex-wrap gap-2">
                <template x-for="f in ['all', 'Diproses', 'Dikirim', 'Selesai']">
                    <button @click="statusFilter = f"
                        :class="statusFilter === f ? 'bg-primary text-white border-primary shadow-[4px_4px_0_var(--color-primary-darkest)]' : 'bg-white text-gray-400 border-gray-200 hover:border-primary hover:text-primary'"
                        class="px-4 py-2 border-[3px] font-headline font-black text-[10px] uppercase tracking-widest transition-all">
                        <span x-text="f === 'all' ? 'SEMUA' : f"></span>
                    </button>
                </template>
            </div>
        </div>

        {{-- Order List --}}
        <div class="flex flex-col gap-6">
            
            {{-- MOCKUP: SELESAI (HANYA UNTUK PREVIEW DESAIN) --}}
            <div x-show="statusFilter === 'all' || statusFilter === 'Selesai'"
                 class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_rgba(22,163,74,0.2)] overflow-hidden opacity-90">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between px-6 py-4 bg-green-50 border-b-2 border-green-100 gap-3">
                    <div class="flex items-center gap-4 flex-wrap">
                        <span class="font-headline font-black text-base text-gray-900 uppercase italic">ORD/SIMULASI/001</span>
                        <span class="text-[10px] font-black text-green-600 uppercase italic px-2 py-0.5 bg-green-100 border border-green-600 shadow-[2px_2px_0_var(--color-green-600)]">PREVIEW SELESAI</span>
                    </div>
                    <span class="px-3 py-1 border-2 border-green-600 text-green-600 bg-white text-[9px] font-black uppercase tracking-widest italic">
                        Selesai
                    </span>
                </div>
                <div class="px-6 py-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                    <div class="flex items-center gap-5">
                        {{-- Image box shadow changed to Primary Red as requested --}}
                        <div class="w-14 h-14 bg-gray-900 flex items-center justify-center flex-shrink-0 border-2 border-gray-900 shadow-[3px_3px_0_var(--color-primary)]">
                            <img src="/images/hero-bottle.jpeg" alt="CeeKlin" class="w-full h-auto mix-blend-screen opacity-80 scale-90">
                        </div>
                        <div>
                            <p class="font-headline font-black text-primary uppercase text-lg leading-tight italic">CeeKlin 450ml</p>
                            <p class="text-[11px] font-bold text-slate-500 mt-1 italic">1.000 PCS × Rp 15.000</p>
                        </div>
                    </div>
                    <div class="flex flex-col sm:items-end gap-3 text-right">
                        <p class="font-headline font-black text-2xl text-primary tracking-tighter italic leading-none">Rp 15.000.000</p>
                        <span class="text-[11px] font-black text-green-600 uppercase tracking-widest flex items-center gap-2 italic">
                            <div class="w-6 h-6 bg-green-100 border-2 border-green-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            Pesanan Selesai
                        </span>
                    </div>
                </div>
                <div class="px-6 pb-8 pt-4">
                    <div class="relative w-full">
                        <div class="absolute top-1/2 left-0 w-full h-[6px] bg-neutral-border -translate-y-1/2 rounded-full"></div>
                        <div class="absolute top-1/2 left-0 h-[6px] bg-green-600 -translate-y-1/2 rounded-full transition-all duration-700" style="width: 100%"></div>
                        <div class="relative flex justify-between items-center w-full">
                            @foreach(['Menunggu', 'Dikemas', 'Dikirim', 'Selesai'] as $i => $label)
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 border-[3px] flex items-center justify-center bg-green-600 border-gray-900 text-white shadow-[4px_4px_0_var(--color-gray-900)] scale-110 z-10 relative">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <p class="text-[9px] font-black uppercase tracking-widest mt-4 text-green-600 italic">{{ $label }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            {{-- END MOCKUP --}}

            @foreach($orders as $order)
            <div x-show="statusFilter === 'all' || statusFilter === '{{ $order->status }}'"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)] overflow-hidden">

                {{-- Header Row --}}
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between px-6 py-4 bg-neutral-light border-b-2 border-neutral-border gap-3">
                    <div class="flex items-center gap-4 flex-wrap">
                        <span class="font-headline font-black text-base text-gray-900 uppercase italic">{{ $order->order_number }}</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">{{ $order->formatted_date }}</span>
                        <span class="text-[10px] font-bold text-slate-500 uppercase italic">via {{ $order->distributor->name ?? 'Pusat' }}</span>
                    </div>
                    <span class="px-3 py-1 border-2 {{ $order->statusClass }} text-[9px] font-black uppercase tracking-widest italic">
                        {{ $order->status }}
                    </span>
                </div>

                {{-- Order Detail --}}
                <div class="px-6 py-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 bg-gray-900 flex items-center justify-center flex-shrink-0 border-2 border-gray-900 shadow-[3px_3px_0_var(--color-primary)]">
                            <img src="/images/hero-bottle.jpeg" alt="CeeKlin" class="w-full h-auto mix-blend-screen opacity-80 scale-90">
                        </div>
                        <div>
                            <p class="font-headline font-black text-primary uppercase text-lg leading-tight italic">CeeKlin 450ml</p>
                            <p class="text-[11px] font-bold text-slate-500 mt-1 italic">{{ number_format($order->quantity) }} PCS × Rp {{ number_format($order->total_price / $order->quantity, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col sm:items-end gap-3">
                        <p class="font-headline font-black text-2xl text-primary tracking-tighter italic leading-none">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>

                        {{-- KONFIRMASI TERIMA (MOCKUP VERSION) --}}
                        @if($order->status === 'Dikirim')
                        <div x-data="{ confirmed: false }" class="w-full">
                            <div x-show="!confirmed">
                                <form action="#" method="GET" @submit.prevent="showMockAlert('Frontend UI Siap! Menunggu rute backend [reseller.confirmReceived] untuk fungsi ini.')">
                                    <button type="submit"
                                        class="w-full sm:w-auto bg-primary text-white px-6 py-3 font-headline font-black text-[11px] uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-primary-hover active:translate-y-0.5 active:shadow-none transition-all flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        <span>Konfirmasi Terima</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @elseif($order->status === 'Selesai')
                        <span class="text-[11px] font-black text-green-600 uppercase tracking-widest flex items-center gap-2 italic">
                            <div class="w-6 h-6 bg-green-100 border-2 border-green-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            Pesanan Selesai
                        </span>
                        @endif
                    </div>
                </div>

                {{-- Progress Bar Status --}}
                <div class="px-6 pb-8 pt-4">
                    @php
                        $steps = [
                            ['label' => 'Menunggu', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                            ['label' => 'Dikemas', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                            ['label' => 'Dikirim', 'icon' => 'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0'],
                            ['label' => 'Selesai', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z']
                        ];
                        $stepsMap = ['Menunggu Konfirmasi' => 0, 'Diproses' => 1, 'Dikirim' => 2, 'Selesai' => 3];
                        $currentIdx = $stepsMap[$order->status] ?? 0;
                    @endphp
                    <div class="relative w-full">
                        <div class="absolute top-1/2 left-0 w-full h-[6px] bg-neutral-border -translate-y-1/2 rounded-full"></div>
                        <div class="absolute top-1/2 left-0 h-[6px] bg-primary -translate-y-1/2 rounded-full transition-all duration-700" 
                             style="width: {{ ($currentIdx / (count($steps) - 1)) * 100 }}%"></div>
                        
                        <div class="relative flex justify-between items-center w-full">
                            @foreach($steps as $i => $step)
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 border-[3px] flex items-center justify-center transition-all duration-300 z-10 relative
                                    {{ $i <= $currentIdx 
                                        ? 'bg-primary border-gray-900 text-white shadow-[4px_4px_0_var(--color-gray-900)] scale-110' 
                                        : 'bg-white border-neutral-border text-slate-300' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $step['icon'] }}"/></svg>
                                </div>
                                <p class="text-[9px] font-black uppercase tracking-widest mt-4
                                    {{ $i <= $currentIdx ? 'text-primary italic' : 'text-slate-400' }}">
                                    {{ $step['label'] }}
                                </p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($orders->isEmpty() && !request()->has('mock'))
        <div class="py-24 text-center bg-white border-[4px] border-dashed border-gray-200 shadow-[10px_10px_0_rgba(0,0,0,0.03)]">
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest italic">Belum ada riwayat pesanan</p>
        </div>
        @endif
    </div>
</x-layouts.dashboard>
