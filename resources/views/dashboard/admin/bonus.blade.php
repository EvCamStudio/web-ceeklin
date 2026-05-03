<x-layouts.dashboard bgTheme="light">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">MASTER ADMIN</span>
    </x-slot:subtitleSlot>

    <x-slot:topbarTitle>PELACAK BONUS</x-slot:topbarTitle>

    <x-slot:menuSlot>
        @include('dashboard.admin._menu')
    </x-slot:menuSlot>

    <div class="flex flex-col lg:flex-row gap-6 mb-8">
        {{-- Set Target Bulanan --}}
        <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)] p-6 lg:w-1/3 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    <h3 class="font-headline font-black text-xl text-primary tracking-tighter uppercase">Target Bulanan</h3>
                </div>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-6">Atur syarat pencapaian bonus reseller bulan ini.</p>
                
                {{-- BACKEND-TODO: Form submit untuk update setting target bulanan --}}
                <form action="{{ route('admin.bonus.update') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-[9px] font-bold text-gray-900 uppercase tracking-widest block mb-1">Target Penjualan (PCS)</label>
                        <input type="number" name="target_qty" value="{{ $targetQty }}" class="w-full bg-neutral-light border-[3px] border-gray-900 px-4 py-2 font-headline font-black text-lg text-primary focus:outline-none focus:border-secondary">
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-gray-900 uppercase tracking-widest block mb-1">Hadiah Bonus (IDR)</label>
                        <input type="number" name="reward_amount" value="{{ $targetReward }}" class="w-full bg-neutral-light border-[3px] border-gray-900 px-4 py-2 font-headline font-black text-lg text-primary focus:outline-none focus:border-secondary">
                    </div>
                    <button type="submit" class="w-full bg-primary text-white py-3 font-headline font-bold text-xs uppercase tracking-widest border-[3px] border-gray-900 hover:bg-primary-hover active:translate-y-0.5 transition-all mt-2">
                        Simpan Pengaturan
                    </button>
                </form>
            </div>
        </div>

        {{-- Info Box --}}
        <div class="bg-secondary/10 border-[4px] border-secondary p-6 lg:w-2/3 flex flex-col justify-center">
            <h4 class="font-headline font-black text-lg text-gray-900 uppercase mb-2">Sistem Bonus Aktif</h4>
            <ul class="space-y-3 mt-2">
                <li class="flex items-start gap-2 text-sm font-bold text-slate-700">
                    <svg class="w-5 h-5 text-secondary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                    <span><strong>Bonus Referral:</strong> Otomatis masuk saat ada downline yang melakukan order pertama (Aktivasi 50 pcs).</span>
                </li>
                <li class="flex items-start gap-2 text-sm font-bold text-slate-700">
                    <svg class="w-5 h-5 text-secondary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                    <span><strong>Bonus Target Bulanan:</strong> Hanya diberikan jika akumulasi pemesanan reseller (berdasar pesanan dari distributor) mencapai atau melebihi Target Penjualan dalam bulan berjalan.</span>
                </li>
            </ul>
        </div>
    </div>

    {{-- Tabel Alokasi Bonus -- fullwidth, memanfaatkan lebar layar desktop --}}
    <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">

        {{-- Header Tabel --}}
        <div class="bg-primary px-6 md:px-8 py-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
            <div>
                <span class="font-headline font-black text-white text-base uppercase tracking-widest leading-tight">Alokasi Komisi Mitra</span>
                <p class="md:hidden text-[9px] text-white/50 font-bold uppercase tracking-widest mt-0.5">Monitoring Bonus Kuartal</p>
            </div>
            {{-- BACKEND-TODO: tambahkan filter kuartal --}}
            <span class="text-[10px] font-bold uppercase tracking-widest text-secondary bg-white/10 px-2 py-0.5 border border-white/20">Q3 2024</span>
        </div>

        {{-- Kolom Header --}}
        <div class="hidden md:grid grid-cols-12 gap-4 px-8 py-3 border-b-2 border-neutral-border bg-neutral-light">
            <div class="col-span-5 text-[10px] font-headline font-bold text-primary uppercase tracking-widest">Mitra / Perusahaan</div>
            <div class="col-span-3 text-[10px] font-headline font-bold text-primary uppercase tracking-widest">Wilayah</div>
            <div class="col-span-2 text-[10px] font-headline font-bold text-primary uppercase tracking-widest text-right">Jumlah (IDR)</div>
            <div class="col-span-2 text-[10px] font-headline font-bold text-primary uppercase tracking-widest text-right">Status</div>
        </div>

        {{-- BACKEND-TODO: Loop dari BonusAllocation::currentQuarter()->get() --}}
        <div class="divide-y-2 divide-neutral-border">
            @forelse($bonusAllocations as $bonus)
            {{-- Baris Bonus --}}
            <div class="flex flex-col md:grid md:grid-cols-12 gap-4 px-6 md:px-8 py-6 md:py-4 items-start md:items-center border-l-[5px] {{ $bonus->status === 'paid' ? 'border-green-600' : 'border-secondary' }} hover:bg-neutral-light transition-colors duration-150">
                <div class="md:col-span-5 w-full min-w-0">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Mitra</p>
                    <div class="font-headline font-bold text-base md:text-sm text-gray-900 uppercase truncate">{{ $bonus->user->name }}</div>
                </div>
                <div class="md:col-span-3 w-full flex justify-between md:block">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Wilayah</p>
                    <div class="text-xs text-slate-500 font-bold uppercase tracking-widest">{{ $bonus->user->province_name }}</div>
                </div>
                <div class="md:col-span-2 w-full flex justify-between items-center md:block md:text-right">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Total Bonus</p>
                    <div class="font-headline font-black text-xl text-primary tracking-tighter">Rp {{ number_format($bonus->amount, 0, ',', '.') }}</div>
                </div>
                <div class="md:col-span-2 w-full flex justify-between md:block md:text-right">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Status</p>
                    <span class="px-2 py-0.5 border-2 {{ $bonus->status === 'paid' ? 'border-green-700 text-green-700' : 'border-secondary text-secondary' }} font-bold text-[10px] uppercase tracking-widest whitespace-nowrap">
                        {{ $bonus->status === 'paid' ? 'Cair' : 'Tertunda' }}
                    </span>
                </div>
            </div>
            @empty
            <div class="py-10 text-center">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Belum ada alokasi bonus</p>
            </div>
            @endforelse
        </div>

        {{-- Aksi Massal --}}
        <div class="px-6 md:px-8 py-6 md:py-4 border-t-4 border-gray-900 bg-neutral-light flex flex-col md:flex-row items-center justify-between gap-4">
            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500 order-2 md:order-1">2 dari 3 bonus menunggu pencairan</span>
            <button type="button" aria-label="Cairkan semua bonus yang tertunda"
                class="w-full md:w-auto bg-secondary text-white px-8 py-3 font-headline font-bold text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-primary active:translate-y-0.5 active:shadow-none transition-all order-1 md:order-2">
                Cairkan Semua yang Tertunda
            </button>
        </div>
    </div>
</x-layouts.dashboard>
