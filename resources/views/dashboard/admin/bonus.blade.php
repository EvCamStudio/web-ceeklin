<x-layouts.dashboard bgTheme="light">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">MASTER ADMIN</span>
    </x-slot:subtitleSlot>

    <x-slot:topbarTitle>PELACAK BONUS</x-slot:topbarTitle>

    <x-slot:menuSlot>
        @include('dashboard.admin._menu')
    </x-slot:menuSlot>

    {{-- Tabel Alokasi Bonus -- fullwidth, memanfaatkan lebar layar desktop --}}
    <div class="bg-white border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)]">

        {{-- Header Tabel --}}
        <div class="bg-primary px-8 py-4 flex items-center justify-between">
            <span class="font-headline font-black text-white text-base uppercase tracking-widest">Alokasi Komisi Mitra</span>
            {{-- BACKEND-TODO: tambahkan filter kuartal --}}
            <span class="text-[10px] font-bold uppercase tracking-widest text-secondary">Q3 2024</span>
        </div>

        {{-- Kolom Header --}}
        <div class="grid grid-cols-12 gap-4 px-8 py-3 border-b-2 border-neutral-border bg-neutral-light">
            <div class="col-span-4 text-[10px] font-headline font-bold text-primary uppercase tracking-widest">Mitra / Perusahaan</div>
            <div class="col-span-2 text-[10px] font-headline font-bold text-primary uppercase tracking-widest">Wilayah</div>
            <div class="col-span-2 text-[10px] font-headline font-bold text-primary uppercase tracking-widest">Level</div>
            <div class="col-span-2 text-[10px] font-headline font-bold text-primary uppercase tracking-widest text-right">Jumlah (IDR)</div>
            <div class="col-span-2 text-[10px] font-headline font-bold text-primary uppercase tracking-widest text-right">Status</div>
        </div>

        {{-- BACKEND-TODO: Loop dari BonusAllocation::currentQuarter()->get() --}}
        <div class="divide-y-2 divide-neutral-border">
            {{-- Baris: Cair --}}
            <div class="grid grid-cols-12 gap-4 px-8 py-4 items-center border-l-[5px] border-secondary hover:bg-neutral-light transition-colors duration-150">
                <div class="col-span-4 font-headline font-bold text-sm text-gray-900 uppercase">PT Tirta Makmur</div>
                <div class="col-span-2 text-xs text-slate-500 font-bold uppercase tracking-widest">Jawa Barat</div>
                <div class="col-span-2">
                    <span class="text-xs font-bold text-secondary uppercase tracking-widest">Platinum</span>
                </div>
                <div class="col-span-2 text-right font-headline font-black text-xl text-primary tracking-tighter">12.5M</div>
                <div class="col-span-2 text-right">
                    <span class="px-2 py-1 border-2 border-green-700 text-green-700 font-bold text-[10px] uppercase tracking-widest">Cair</span>
                </div>
            </div>

            {{-- Baris: Tertunda --}}
            <div class="grid grid-cols-12 gap-4 px-8 py-4 items-center border-l-[5px] border-transparent hover:bg-neutral-light transition-colors duration-150">
                <div class="col-span-4 font-headline font-bold text-sm text-gray-900 uppercase">CV Bintang Selatan</div>
                <div class="col-span-2 text-xs text-slate-500 font-bold uppercase tracking-widest">Jawa Timur</div>
                <div class="col-span-2">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Emas</span>
                </div>
                <div class="col-span-2 text-right font-headline font-black text-xl text-primary tracking-tighter opacity-60">8.2M</div>
                <div class="col-span-2 text-right">
                    <span class="px-2 py-1 border-2 border-secondary text-secondary font-bold text-[10px] uppercase tracking-widest">Tertunda</span>
                </div>
            </div>

            {{-- Baris: Tertunda --}}
            <div class="grid grid-cols-12 gap-4 px-8 py-4 items-center border-l-[5px] border-transparent hover:bg-neutral-light transition-colors duration-150">
                <div class="col-span-4 font-headline font-bold text-sm text-gray-900 uppercase">Distributor Abadi</div>
                <div class="col-span-2 text-xs text-slate-500 font-bold uppercase tracking-widest">Jawa Tengah</div>
                <div class="col-span-2">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Emas</span>
                </div>
                <div class="col-span-2 text-right font-headline font-black text-xl text-primary tracking-tighter opacity-60">6.4M</div>
                <div class="col-span-2 text-right">
                    <span class="px-2 py-1 border-2 border-secondary text-secondary font-bold text-[10px] uppercase tracking-widest">Tertunda</span>
                </div>
            </div>
        </div>

        {{-- Aksi Massal --}}
        <div class="px-8 py-4 border-t-4 border-gray-900 bg-neutral-light flex items-center justify-between">
            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500">2 dari 3 bonus menunggu pencairan</span>
            {{-- BACKEND-TODO: action ke BonusController@batchRelease --}}
            <button type="button" aria-label="Cairkan semua bonus yang tertunda"
                class="bg-secondary text-white px-8 py-3 font-headline font-bold text-xs uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-primary active:translate-y-0.5 active:shadow-none transition-all">
                Cairkan Semua yang Tertunda
            </button>
        </div>
    </div>
</x-layouts.dashboard>
