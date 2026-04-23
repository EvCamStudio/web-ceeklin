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
            <div class="col-span-4 text-[10px] font-headline font-bold text-primary uppercase tracking-widest">Mitra / Perusahaan</div>
            <div class="col-span-2 text-[10px] font-headline font-bold text-primary uppercase tracking-widest">Wilayah</div>
            <div class="col-span-2 text-[10px] font-headline font-bold text-primary uppercase tracking-widest">Level</div>
            <div class="col-span-2 text-[10px] font-headline font-bold text-primary uppercase tracking-widest text-right">Jumlah (IDR)</div>
            <div class="col-span-2 text-[10px] font-headline font-bold text-primary uppercase tracking-widest text-right">Status</div>
        </div>

        {{-- BACKEND-TODO: Loop dari BonusAllocation::currentQuarter()->get() --}}
        <div class="divide-y-2 divide-neutral-border">
            {{-- Baris: Cair --}}
            <div class="flex flex-col md:grid md:grid-cols-12 gap-4 px-6 md:px-8 py-6 md:py-4 items-start md:items-center border-l-[5px] border-secondary hover:bg-neutral-light transition-colors duration-150">
                <div class="md:col-span-4 w-full min-w-0">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Perusahaan</p>
                    <div class="font-headline font-bold text-base md:text-sm text-gray-900 uppercase truncate">PT Tirta Makmur</div>
                </div>
                <div class="md:col-span-2 w-full flex justify-between md:block">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Wilayah</p>
                    <div class="text-xs text-slate-500 font-bold uppercase tracking-widest">Jawa Barat</div>
                </div>
                <div class="md:col-span-2 w-full flex justify-between md:block">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Level Mitra</p>
                    <span class="text-xs font-bold text-secondary uppercase tracking-widest">Platinum</span>
                </div>
                <div class="md:col-span-2 w-full flex justify-between items-center md:block md:text-right">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Total Bonus</p>
                    <div class="font-headline font-black text-xl text-primary tracking-tighter">12.5M</div>
                </div>
                <div class="md:col-span-2 w-full flex justify-between md:block md:text-right">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Status</p>
                    <span class="px-2 py-0.5 border-2 border-green-700 text-green-700 font-bold text-[10px] uppercase tracking-widest whitespace-nowrap">Cair</span>
                </div>
            </div>

            {{-- Baris: Tertunda --}}
            <div class="flex flex-col md:grid md:grid-cols-12 gap-4 px-6 md:px-8 py-6 md:py-4 items-start md:items-center border-l-[5px] border-transparent hover:bg-neutral-light transition-colors duration-150">
                <div class="md:col-span-4 w-full min-w-0">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Perusahaan</p>
                    <div class="font-headline font-bold text-base md:text-sm text-gray-900 uppercase truncate">CV Bintang Selatan</div>
                </div>
                <div class="md:col-span-2 w-full flex justify-between md:block">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Wilayah</p>
                    <div class="text-xs text-slate-500 font-bold uppercase tracking-widest">Jawa Timur</div>
                </div>
                <div class="md:col-span-2 w-full flex justify-between md:block">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Level Mitra</p>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Emas</span>
                </div>
                <div class="md:col-span-2 w-full flex justify-between items-center md:block md:text-right">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Total Bonus</p>
                    <div class="font-headline font-black text-xl text-primary tracking-tighter opacity-60">8.2M</div>
                </div>
                <div class="md:col-span-2 w-full flex justify-between md:block md:text-right">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Status</p>
                    <span class="px-2 py-0.5 border-2 border-secondary text-secondary font-bold text-[10px] uppercase tracking-widest whitespace-nowrap">Tertunda</span>
                </div>
            </div>

            {{-- Baris: Tertunda (2) --}}
            <div class="flex flex-col md:grid md:grid-cols-12 gap-4 px-6 md:px-8 py-6 md:py-4 items-start md:items-center border-l-[5px] border-transparent hover:bg-neutral-light transition-colors duration-150">
                <div class="md:col-span-4 w-full min-w-0">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-1">Perusahaan</p>
                    <div class="font-headline font-bold text-base md:text-sm text-gray-900 uppercase truncate">Distributor Abadi</div>
                </div>
                <div class="md:col-span-2 w-full flex justify-between md:block">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Wilayah</p>
                    <div class="text-xs text-slate-500 font-bold uppercase tracking-widest">Jawa Tengah</div>
                </div>
                <div class="md:col-span-2 w-full flex justify-between md:block">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Level Mitra</p>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Emas</span>
                </div>
                <div class="md:col-span-2 w-full flex justify-between items-center md:block md:text-right">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Total Bonus</p>
                    <div class="font-headline font-black text-xl text-primary tracking-tighter opacity-60">6.4M</div>
                </div>
                <div class="md:col-span-2 w-full flex justify-between md:block md:text-right">
                    <p class="md:hidden text-[8px] font-bold text-slate-400 uppercase tracking-widest">Status</p>
                    <span class="px-2 py-0.5 border-2 border-secondary text-secondary font-bold text-[10px] uppercase tracking-widest whitespace-nowrap">Tertunda</span>
                </div>
            </div>
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
