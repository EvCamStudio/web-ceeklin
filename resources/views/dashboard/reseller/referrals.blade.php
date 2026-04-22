<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">RESELLER TEROTORISASI</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>PROGRAM REFERRAL</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.reseller._menu')</x-slot:menuSlot>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Panel Kiri: Link Referral + Statistik --}}
        <div class="xl:col-span-2 flex flex-col gap-6">

            {{-- Referral Link --}}
            <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">
                <div class="bg-primary px-6 py-3">
                    <span class="font-headline font-black text-white text-base uppercase tracking-tight">Link Referral Anda</span>
                </div>
                <div class="p-6">
                    <p class="text-xs text-slate-500 font-bold mb-4 uppercase tracking-widest">Ajak reseller lain & dapatkan 5% override dari 3 pesanan pertama mereka.</p>
                    {{-- BACKEND-TODO: Ambil dari Auth::user()->referral_code --}}
                    <div class="flex items-stretch border-[3px] border-primary shadow-[4px_4px_0_var(--color-gray-900)]">
                        <div class="flex-1 font-mono font-bold text-primary px-4 py-3 bg-neutral-light text-sm tracking-widest truncate">
                            ceeklin.com/ref/IM-778XQ
                        </div>
                        <button type="button" aria-label="Salin link referral"
                            class="bg-primary text-white px-6 font-headline font-bold text-xs uppercase tracking-widest hover:bg-primary-hover transition-colors flex items-center gap-1.5 whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            Salin
                        </button>
                    </div>
                </div>
            </div>

            {{-- Anggota Referral --}}
            <div class="bg-white border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-secondary)]">
                <div class="bg-secondary px-6 py-3">
                    <span class="font-headline font-black text-white text-sm uppercase tracking-tight">Anggota Referral</span>
                </div>
                <div class="divide-y-2 divide-neutral-border">
                    {{-- BACKEND-TODO: Loop dari Referral::where('referrer_id', Auth::id())->get() --}}
                    @foreach([
                        ['nama'=>'Andi Pratama','bergabung'=>'Okt 2024','status'=>'Aktif','aktif'=>true],
                        ['nama'=>'Sari Wulandari','bergabung'=>'Sep 2024','status'=>'Aktif','aktif'=>true],
                        ['nama'=>'Budi Santoso','bergabung'=>'Agu 2024','status'=>'Nonaktif','aktif'=>false],
                    ] as $ref)
                    <div class="flex items-center justify-between px-6 py-4">
                        <div>
                            <p class="font-bold text-sm {{ $ref['aktif'] ? 'text-gray-900' : 'text-slate-400' }}">{{ $ref['nama'] }}</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Bergabung {{ $ref['bergabung'] }}</p>
                        </div>
                        <span class="px-2 py-1 border-2 {{ $ref['aktif'] ? 'border-secondary text-secondary' : 'border-slate-300 text-slate-400' }} text-[10px] font-bold uppercase tracking-widest">
                            {{ $ref['status'] }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Panel Kanan: Statistik --}}
        <div class="flex flex-col gap-4">
            @foreach([
                ['label'=>'Total Referral','nilai'=>'7','border'=>'border-primary','shadow'=>'var(--color-primary-darkest)'],
                ['label'=>'Referral Aktif','nilai'=>'5','border'=>'border-secondary','shadow'=>'var(--color-gray-900)'],
                ['label'=>'Pendapatan dari Referral','nilai'=>'Rp 2.1M','border'=>'border-primary','shadow'=>'var(--color-secondary)'],
            ] as $stat)
            <div class="bg-white border-[3px] {{ $stat['border'] }} shadow-[6px_6px_0_{{ $stat['shadow'] }}] p-6 text-center">
                <p class="text-[10px] text-secondary font-bold uppercase tracking-widest mb-2">{{ $stat['label'] }}</p>
                <h3 class="font-headline font-black text-4xl text-primary tracking-tighter">{{ $stat['nilai'] }}</h3>
            </div>
            @endforeach
        </div>
    </div>
</x-layouts.dashboard>
