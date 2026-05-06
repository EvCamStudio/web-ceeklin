@php
    $currentPage = request()->segment(3) ?? 'overview';

    $menuGroups = [
        [
            'label' => 'Utama',
            'items' => [
                [
                    'key'   => 'overview',
                    'name'  => 'Beranda',
                    'route' => '/dashboard/admin',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />',
                ],
            ],
        ],
        [
            'label' => 'Operasional',
            'items' => [
                [
                    'key'   => 'verify',
                    'name'  => 'Verifikasi Reseller',
                    'route' => '/dashboard/admin/verify',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />',
                    'badge' => \App\Models\User::where('role', 'reseller')->where('status', 'pending')->count()
                ],
                [
                    'key'   => 'distributor-orders',
                    'name'  => 'Pesanan Distributor',
                    'route' => '/dashboard/admin/distributor-orders',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />',
                    'badge' => \App\Models\DistributorOrder::where('status', 'Menunggu Proses')->count()
                ],
                [
                    'key'   => 'requests',
                    'name'  => 'Sinkronisasi Stok',
                    'route' => '/dashboard/admin/requests',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />',
                ],
            ],
        ],
        [
            'label' => 'Manajemen',
            'items' => [
                [
                    'key'   => 'distributors',
                    'name'  => 'Manajemen Distributor',
                    'route' => '/dashboard/admin/distributors',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />',
                ],
                [
                    'key'   => 'bonus',
                    'name'  => 'Bonus Reseller',
                    'route' => '/dashboard/admin/bonus',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />',
                ],
            ],
        ],
        [
            'label' => 'Analitik',
            'items' => [
                [
                    'key'   => 'sales',
                    'name'  => 'Laporan Penjualan',
                    'route' => '/dashboard/admin/sales',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />',
                ],
                [
                    'key'   => 'mapping',
                    'name'  => 'Pemetaan Wilayah',
                    'route' => '/dashboard/admin/mapping',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />',
                ],
            ],
        ],
        [
            'label' => 'Konfigurasi',
            'items' => [
                [
                    'key'   => 'pricing',
                    'name'  => 'Kontrol Harga',
                    'route' => '/dashboard/admin/pricing',
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z" />',
                ],
            ],
        ],
    ];

    $bottomMenu = [
        'key'   => 'settings',
        'name'  => 'Pengaturan',
        'route' => '/dashboard/admin/settings',
        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />',
    ];
@endphp

{{-- Grouped Navigation --}}
<div class="flex flex-col flex-1 py-2">
    @foreach($menuGroups as $group)
        {{-- Group Label --}}
        @if($group['label'])
            <div class="px-5 pt-4 pb-1">
                <p class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">{{ $group['label'] }}</p>
            </div>
        @endif

        {{-- Group Items --}}
        @foreach($group['items'] as $menu)
            @php
                // Cek apakah route saat ini cocok dengan route menu (menghapus domain)
                $path = ltrim(parse_url($menu['route'], PHP_URL_PATH), '/');
                $isActive = request()->is($path . '*') || ($menu['key'] === 'overview' && request()->is('dashboard/admin'));

                $activeClass = $isActive
                    ? 'bg-primary text-white border-l-[5px] border-secondary'
                    : 'text-gray-600 hover:bg-neutral-border-light hover:text-gray-900 border-l-[5px] border-transparent';
            @endphp
            <a href="{{ $menu['route'] }}"
               aria-label="Menu {{ $menu['name'] }}"
               aria-current="{{ $isActive ? 'page' : 'false' }}"
               class="flex items-center gap-3 px-5 py-2.5 text-[10px] font-headline font-bold uppercase tracking-widest transition-colors duration-150 {{ $activeClass }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {!! $menu['icon'] !!}
                </svg>
                <div class="flex-1 flex items-center justify-between">
                    <span>{{ $menu['name'] }}</span>
                    @if(isset($menu['badge']) && $menu['badge'] > 0)
                        <x-ui.badge-count :count="$menu['badge']" type="danger" />
                    @endif
                </div>
            </a>
        @endforeach
    @endforeach

    {{-- Bottom Pinned --}}
    <div class="mt-auto border-t-2 border-neutral-border pt-2">
        @php
            $bottomPath = ltrim(parse_url($bottomMenu['route'], PHP_URL_PATH), '/');
            $isActive = request()->is($bottomPath . '*');
            $activeClass = $isActive
                ? 'bg-primary text-white border-l-[5px] border-secondary'
                : 'text-gray-600 hover:bg-neutral-border-light hover:text-gray-900 border-l-[5px] border-transparent';
        @endphp
        <a href="{{ $bottomMenu['route'] }}"
           aria-label="Menu {{ $bottomMenu['name'] }}"
           class="flex items-center gap-3 px-5 py-2.5 text-[10px] font-headline font-bold uppercase tracking-widest transition-colors duration-150 {{ $activeClass }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $bottomMenu['icon'] !!}
            </svg>
            {{ $bottomMenu['name'] }}
        </a>

        <form id="logout-form-admin" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <a href="#"
           onclick="event.preventDefault(); document.getElementById('logout-form-admin').submit();"
           aria-label="Keluar dari Dashboard Admin"
           class="flex items-center gap-3 px-5 py-2.5 text-[10px] font-headline font-bold uppercase tracking-widest text-gray-500 hover:text-red-600 border-l-[5px] border-transparent transition-colors duration-150">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Keluar
        </a>
    </div>
</div>
