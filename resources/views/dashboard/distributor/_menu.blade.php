@php
    $currentPage = request()->segment(3) ?? 'overview';

    $menuGroups = [
        [
            'label' => 'Utama',
            'items' => [
                [
                    'key'   => 'overview',
                    'name'  => 'Beranda Dashboard',
                    'route' => '/dashboard/distributor',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />',
                ],
            ],
        ],
        [
            'label' => 'Distribusi (Penjualan)',
            'items' => [
                [
                    'key'   => 'incoming-orders',
                    'name'  => 'Pesanan Reseller',
                    'route' => '/dashboard/distributor/incoming-orders',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />',
                ],
                [
                    'key'   => 'resellers',
                    'name'  => 'Jaringan Reseller',
                    'route' => '/dashboard/distributor/resellers',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />',
                ],
                [
                    'key'   => 'sales-map',
                    'name'  => 'Peta Wilayah',
                    'route' => '/dashboard/distributor/sales-map',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />',
                ],
            ],
        ],
        [
            'label' => 'Inventori (Pembelian)',
            'items' => [
                [
                    'key'   => 'inventory',
                    'name'  => 'Stok Gudang',
                    'route' => '/dashboard/distributor/inventory',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />',
                ],
                [
                    'key'   => 'order',
                    'name'  => 'Restock ke Pabrik',
                    'route' => '/dashboard/distributor/order',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />',
                ],
                [
                    'key'   => 'history',
                    'name'  => 'Status & Riwayat Restock',
                    'route' => '/dashboard/distributor/history',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />',
                ],
            ],
        ],
    ];

    $bottomMenu = [
        'key'   => 'settings',
        'name'  => 'Pengaturan',
        'route' => '/dashboard/distributor/settings',
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
                $isActive = ($menu['key'] === 'overview')
                    ? ($currentPage === 'overview' || request()->is('dashboard/distributor'))
                    : ($currentPage === $menu['key']);

                $activeClass = $isActive
                    ? 'bg-white/10 text-secondary border-l-[5px] border-secondary'
                    : 'text-white/60 hover:bg-white/5 hover:text-white border-l-[5px] border-transparent';
            @endphp
            <a href="{{ $menu['route'] }}"
               aria-label="Menu {{ $menu['name'] }}"
               aria-current="{{ $isActive ? 'page' : 'false' }}"
               class="flex items-center gap-3 px-5 py-2.5 text-[10px] font-headline font-bold uppercase tracking-widest transition-colors duration-150 {{ $activeClass }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {!! $menu['icon'] !!}
                </svg>
                <span class="flex-1">{{ $menu['name'] }}</span>
                {{-- Badge untuk Pesanan Masuk --}}
                @if($menu['key'] === 'incoming-orders')
                    {{-- BACKEND-TODO: ganti 3 dengan count pesanan pending dari DB --}}
                    <span class="bg-secondary text-gray-900 text-[8px] font-black px-1.5 py-0.5 rounded-sm leading-none">3</span>
                @endif
            </a>
        @endforeach
    @endforeach

    {{-- Bottom Pinned --}}
    <div class="mt-auto border-t-2 border-white/10 pt-2">
        @php
            $isActive = $currentPage === $bottomMenu['key'];
            $activeClass = $isActive
                ? 'bg-white/10 text-secondary border-l-[5px] border-secondary'
                : 'text-white/60 hover:bg-white/5 hover:text-white border-l-[5px] border-transparent';
        @endphp
        <a href="{{ $bottomMenu['route'] }}"
           aria-label="Menu {{ $bottomMenu['name'] }}"
           class="flex items-center gap-3 px-5 py-2.5 text-[10px] font-headline font-bold uppercase tracking-widest transition-colors duration-150 {{ $activeClass }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $bottomMenu['icon'] !!}
            </svg>
            {{ $bottomMenu['name'] }}
        </a>

        <form id="logout-form-distributor" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <a href="#"
           onclick="event.preventDefault(); document.getElementById('logout-form-distributor').submit();"
           aria-label="Keluar dari Portal Distributor"
           class="flex items-center gap-3 px-5 py-2.5 text-[10px] font-headline font-bold uppercase tracking-widest text-white/50 hover:text-red-400 border-l-[5px] border-transparent transition-colors duration-150">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Keluar
        </a>
    </div>
</div>
