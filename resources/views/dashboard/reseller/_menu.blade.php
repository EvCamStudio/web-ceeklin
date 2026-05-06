@php
    $currentPage = request()->segment(3) ?? 'overview';
    $type = request()->query('type');

    $menuGroups = [
        [
            'label' => 'Utama',
            'items' => [
                [
                    'key'   => 'overview',
                    'name'  => 'Beranda',
                    'route' => '/dashboard/reseller',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />',
                ],
            ],
        ],
        [
            'label' => 'Transaksi',
            'items' => [
                [
                    'key'   => 'order',
                    'name'  => 'Buat Pesanan',
                    'route' => '/dashboard/reseller/order',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />',
                ],
                [
                    'key'   => 'history',
                    'name'  => 'Pelacakan Pesanan',
                    'route' => '/dashboard/reseller/history',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />',
                ],
            ],
        ],
        [
            'label' => 'Program & Keuangan',
            'items' => [
                [
                    'key'   => 'target',
                    'name'  => 'Bonus Target',
                    'route' => '/dashboard/reseller/referrals?type=target',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />',
                ],
                [
                    'key'   => 'referral',
                    'name'  => 'Kode Referral',
                    'route' => '/dashboard/reseller/referrals?type=referral',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />',
                ],
            ],
        ],
    ];

    $bottomMenu = [
        'key'   => 'settings',
        'name'  => 'Pengaturan',
        'route' => '/dashboard/reseller/settings',
        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />',
    ];
@endphp

{{-- Grouped Navigation --}}
<div class="flex flex-col flex-1 py-2">
    @foreach($menuGroups as $group)
        {{-- Group Label --}}
        @if($group['label'])
            <div class="px-5 pt-4 pb-1">
                <p class="text-[9px] font-black uppercase tracking-[0.2em] text-white/30">{{ $group['label'] }}</p>
            </div>
        @endif

        {{-- Group Items --}}
        @foreach($group['items'] as $menu)
            @php
                // Logic to check active state based on route and query param
                $isActive = false;
                if ($menu['key'] === 'overview') {
                    $isActive = ($currentPage === 'overview' || request()->is('dashboard/reseller'));
                } elseif ($menu['key'] === 'target') {
                    $isActive = ($currentPage === 'referrals' && request()->query('type') === 'target');
                } elseif ($menu['key'] === 'referral') {
                    $isActive = ($currentPage === 'referrals' && request()->query('type') === 'referral');
                } else {
                    $isActive = ($currentPage === $menu['key']);
                }

                $activeClass = $isActive
                    ? 'bg-white/10 text-secondary border-l-[5px] border-secondary'
                    : 'text-white/50 hover:bg-white/5 hover:text-white border-l-[5px] border-transparent';
            @endphp
            <a href="{{ $menu['route'] }}"
               aria-label="Menu {{ $menu['name'] }}"
               aria-current="{{ $isActive ? 'page' : 'false' }}"
               class="flex items-center gap-3 px-5 py-2.5 text-[10px] font-headline font-bold uppercase tracking-widest transition-colors duration-150 {{ $activeClass }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {!! $menu['icon'] !!}
                </svg>
                {{ $menu['name'] }}
            </a>
        @endforeach
    @endforeach

    {{-- Bottom Pinned --}}
    <div class="mt-auto border-t-2 border-white/10 pt-2">
        @php
            $isActive = $currentPage === $bottomMenu['key'];
            $activeClass = $isActive
                ? 'bg-white/10 text-secondary border-l-[5px] border-secondary'
                : 'text-white/50 hover:bg-white/5 hover:text-white border-l-[5px] border-transparent';
        @endphp
        <a href="{{ $bottomMenu['route'] }}"
           aria-label="Menu {{ $bottomMenu['name'] }}"
           class="flex items-center gap-3 px-5 py-2.5 text-[10px] font-headline font-bold uppercase tracking-widest transition-colors duration-150 {{ $activeClass }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $bottomMenu['icon'] !!}
            </svg>
            {{ $bottomMenu['name'] }}
        </a>

        <form id="logout-form-reseller" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <a href="#"
           onclick="event.preventDefault(); document.getElementById('logout-form-reseller').submit();"
           aria-label="Keluar dari Portal Reseller"
           class="flex items-center gap-3 px-5 py-2.5 text-[10px] font-headline font-bold uppercase tracking-widest text-white/50 hover:text-red-400 border-l-[5px] border-transparent transition-colors duration-150">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Keluar
        </a>
    </div>
</div>
