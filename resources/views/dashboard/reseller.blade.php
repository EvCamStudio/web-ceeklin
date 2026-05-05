<x-layouts.dashboard bgTheme="dark">
    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-primary tracking-widest mt-1">RESELLER TEROTORISASI</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>BERANDA</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.reseller._menu')</x-slot:menuSlot>

    @php
        // Urgency data
        $urgentShippingCount = \App\Models\ResellerOrder::where('reseller_id', Auth::id())
            ->where('status', 'Dikirim')
            ->count();
            
        $totalBonus = ($personalBonus ?? 0) + ($referralBonus ?? 0);
    @endphp

    {{-- 1. ACTION CENTER (HIGHEST PRIORITY) --}}
    @if($urgentShippingCount > 0)
    <div class="mb-10">
        <div class="flex items-center gap-3 mb-4">
            <span class="w-10 h-1 bg-primary"></span>
            <h2 class="font-headline font-black text-xl text-primary uppercase tracking-tight italic">Pusat Aksi (Urgensi)</h2>
            <span class="flex-1 h-[2px] bg-neutral-border"></span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <a href="/dashboard/reseller/history" 
               class="bg-blue-50 border-[4px] border-blue-600 p-6 flex items-center justify-between group hover:bg-blue-600 transition-all duration-300 shadow-[8px_8px_0_var(--color-gray-900)]">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-blue-600 flex items-center justify-center text-white group-hover:bg-white group-hover:text-blue-600 transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1-1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                    </div>
                    <div>
                        <h4 class="font-headline font-black text-blue-600 group-hover:text-white text-xl uppercase leading-none tracking-tighter">{{ $urgentShippingCount }} Pesanan Dikirim</h4>
                        <p class="text-[10px] font-bold text-blue-400 group-hover:text-blue-100 uppercase tracking-widest mt-1 italic">Pantau status perjalanan kurir</p>
                    </div>
                </div>
                <svg class="w-8 h-8 text-blue-600 group-hover:text-white transition-transform group-hover:translate-x-2 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
    @endif

    {{-- 2. KPI CARDS (PERFORMANCE) --}}
    <div class="mb-10">
        <div class="flex items-center gap-3 mb-4">
            <span class="w-10 h-1 bg-primary"></span>
            <h2 class="font-headline font-black text-xl text-primary uppercase tracking-tight italic">Ringkasan Performa</h2>
            <span class="flex-1 h-[2px] bg-neutral-border"></span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Saldo Bonus --}}
            <div class="bg-white border-[3px] border-primary shadow-[8px_8px_0_var(--color-primary-darkest)] p-6">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1 italic">Total Saldo Bonus</p>
                <h3 class="font-headline font-black text-4xl text-primary tracking-tighter italic">Rp {{ number_format($totalBonus, 0, ',', '.') }}</h3>
                <div class="mt-6 flex gap-4">
                    <a href="/dashboard/reseller/referrals?type=target" class="text-[9px] font-black text-primary uppercase border-b-2 border-primary hover:opacity-70 transition-all italic">TARGET</a>
                    <a href="/dashboard/reseller/referrals?type=referral" class="text-[9px] font-black text-primary uppercase border-b-2 border-primary hover:opacity-70 transition-all italic">REFERRAL</a>
                </div>
            </div>

            {{-- Progress Target --}}
            <div class="bg-primary text-white border-[3px] border-gray-900 shadow-[8px_8px_0_var(--color-gray-900)] p-6 relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-16 h-16 bg-white/5 rounded-full blur-xl"></div>
                <div class="flex justify-between items-start mb-1">
                    <p class="text-[10px] text-white/70 font-bold uppercase tracking-widest italic">Target Bulanan</p>
                    <span class="text-[9px] font-black uppercase tracking-widest">GOAL: {{ number_format($targetQty) }}</span>
                </div>
                <h3 class="font-headline font-black text-4xl text-white tracking-tighter italic mb-3">{{ number_format($currentMonthOrders) }} <span class="text-xs font-body opacity-60">PCS</span></h3>
                <div class="w-full bg-white/20 h-1.5 mb-2 border border-black/10">
                    <div class="bg-white h-full" style="width: {{ $targetProgress }}%"></div>
                </div>
                <p class="text-[9px] font-bold text-white/80 uppercase tracking-widest italic">
                    {{ $neededForTarget > 0 ? number_format($neededForTarget) . ' pcs lagi ke bonus target' : 'Target bulan ini tercapai! 🔥' }}
                </p>
            </div>

            {{-- Total Pesanan --}}
            <div class="bg-white border-[3px] border-gray-900 shadow-[8px_8px_0_var(--color-primary-darkest)] p-6">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1 italic">Total Pesanan Selesai</p>
                <h3 class="font-headline font-black text-4xl text-primary tracking-tighter italic">{{ number_format($completedOrders) }} <span class="text-xs font-body text-slate-400">ORDER</span></h3>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-3 italic">Dari total {{ number_format($totalOrders) }} transaksi</p>
            </div>
        </div>
    </div>

    {{-- 3. QUICK ACTIONS & DISTRIBUTOR INFO --}}
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
        {{-- Quick Actions (Kiri) --}}
        <div class="xl:col-span-4 flex flex-col gap-4">
            <div class="flex items-center gap-3 mb-2">
                <span class="w-8 h-1 bg-gray-900"></span>
                <h2 class="font-headline font-black text-lg text-gray-900 uppercase tracking-tight italic">Navigasi Cepat</h2>
            </div>
            <a href="/dashboard/reseller/order"
               class="bg-primary border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] p-5 flex items-center gap-4 hover:bg-primary-hover transition-all group hover:-translate-y-1">
                <div class="w-12 h-12 bg-white/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <h4 class="font-headline font-black text-white uppercase text-sm italic">Buat Pesanan Baru</h4>
                    <p class="text-xs text-white/60 mt-0.5 uppercase tracking-tight font-bold italic">Restock produk ke distributor</p>
                </div>
            </a>
            <a href="/dashboard/reseller/referrals?type=target"
               class="bg-white border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-primary-darkest)] p-5 flex items-center gap-4 hover:bg-neutral-light transition-all group hover:-translate-y-1">
                <div class="w-12 h-12 bg-primary/10 flex items-center justify-center flex-shrink-0 group-hover:bg-primary/20 transition-colors">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                </div>
                <div>
                    <h4 class="font-headline font-black text-primary uppercase text-sm italic">Bonus Target</h4>
                    <p class="text-xs text-slate-500 mt-0.5 uppercase tracking-tight font-bold italic">Lihat progres pencapaian</p>
                </div>
            </a>
            <a href="/dashboard/reseller/referrals?type=referral"
               class="bg-white border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] p-5 flex items-center gap-4 hover:bg-neutral-light transition-all group hover:-translate-y-1">
                <div class="w-12 h-12 bg-gray-100 flex items-center justify-center flex-shrink-0 group-hover:bg-gray-200 transition-colors">
                    <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                </div>
                <div>
                    <h4 class="font-headline font-black text-gray-900 uppercase text-sm italic">Kode Referral</h4>
                    <p class="text-xs text-slate-500 mt-0.5 uppercase tracking-tight font-bold italic">Bagikan kode & lihat member</p>
                </div>
            </a>
        </div>

        {{-- Distributor Info Card (RAPI & DETAIL) --}}
        <div class="xl:col-span-8 bg-white border-[4px] border-gray-900 shadow-[12px_12px_0_var(--color-primary-darkest)] relative overflow-hidden">
            {{-- Decorative Accents --}}
            <div class="absolute -right-16 -top-16 w-48 h-48 bg-primary/5 rounded-full blur-3xl"></div>
            
            <div class="bg-primary px-8 py-4 flex items-center justify-between relative z-10 border-b-[4px] border-gray-900">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span class="font-headline font-black text-white text-lg uppercase tracking-tight italic">Profil Distributor Anda</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                    <span class="text-[10px] font-black text-white uppercase tracking-widest italic">TERVERIFIKASI</span>
                </div>
            </div>

            <div class="p-8 relative z-10">
                <div class="flex flex-col lg:flex-row gap-10">
                    {{-- Avatar & Basic Info --}}
                    <div class="flex flex-col items-center lg:items-start text-center lg:text-left lg:w-1/3">
                        <div class="w-24 h-24 bg-neutral-light border-[4px] border-gray-900 flex items-center justify-center shadow-[6px_6px_0_var(--color-primary)] mb-6 overflow-hidden">
                            @if($upline && $upline->avatar)
                                <img src="{{ asset('storage/' . $upline->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <span class="text-4xl font-headline font-black text-primary italic uppercase">{{ substr($upline->name ?? 'P', 0, 1) }}</span>
                            @endif
                        </div>
                        <h4 class="font-headline font-black text-gray-900 text-2xl uppercase italic leading-none tracking-tight mb-2">{{ $upline->name ?? 'CeeKlin Pusat' }}</h4>
                        <p class="text-[11px] text-primary font-bold uppercase tracking-[0.2em] italic mb-6">ID DISTRIBUTOR: #{{ str_pad($upline->id ?? 0, 4, '0', STR_PAD_LEFT) }}</p>
                        
                        <div class="space-y-3 w-full">
                            <a href="https://wa.me/62{{ ltrim($upline->phone ?? '', '0') }}" target="_blank" 
                               class="w-full bg-[#25D366] text-white px-6 py-4 font-headline font-black text-[11px] uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all flex items-center justify-center gap-3">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.347-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                CHAT WHATSAPP
                            </a>
                        </div>
                    </div>

                    {{-- Detailed Info --}}
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-8 border-t-2 lg:border-t-0 lg:border-l-2 border-dashed border-gray-200 pt-8 lg:pt-0 lg:pl-10">
                        <div class="space-y-6">
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Wilayah Operasional</p>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-neutral-light border-2 border-gray-900 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <p class="font-bold text-gray-900 uppercase text-sm tracking-tight leading-tight">{{ $upline->city_name ?? 'Pusat' }}, {{ $upline->province_name ?? 'Indonesia' }}</p>
                                </div>
                            </div>

                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Informasi Kontak</p>
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        <span class="text-xs font-bold text-gray-700 italic">{{ $upline->email ?? 'email@distributor.com' }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        <span class="text-xs font-bold text-gray-700 italic">{{ $upline->phone ?? '08xxxxxxxxxx' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Alamat Gudang / Kantor</p>
                                <div class="bg-neutral-light p-4 border-2 border-dashed border-gray-200">
                                    <p class="text-xs text-gray-600 font-bold leading-relaxed italic">{{ $upline->address ?? 'Alamat distributor belum diatur secara lengkap di sistem. Silakan hubungi via WhatsApp untuk detail lokasi.' }}</p>
                                </div>
                            </div>
                            
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Bergabung Sejak</p>
                                <p class="font-bold text-gray-900 uppercase text-xs italic tracking-widest">{{ $upline->created_at ? $upline->created_at->translatedFormat('d F Y') : 'Member Senior' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
