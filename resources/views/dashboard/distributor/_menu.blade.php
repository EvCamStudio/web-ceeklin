@php
    // Deteksi halaman aktif dari URL segment ketiga (/dashboard/distributor/{page})
    $currentPage = request()->segment(3) ?? 'overview';

    $menus = [
        [
            'key'   => 'overview',
            'name'  => 'Beranda',
            'route' => '/dashboard/distributor',
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />',
        ],
        [
            'key'   => 'sales-map',
            'name'  => 'Peta Wilayah',
            'route' => '/dashboard/distributor/sales-map',
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />',
        ],
        [
            'key'   => 'resellers',
            'name'  => 'Jaringan Reseller',
            'route' => '/dashboard/distributor/resellers',
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />',
        ],
        [
            'key'   => 'inventory',
            'name'  => 'Stok Gudang',
            'route' => '/dashboard/distributor/inventory',
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />',
        ],
        [
            'key'   => 'order',
            'name'  => 'Buat Pesanan',
            'route' => '/dashboard/distributor/order',
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />',
        ],
    ];

    $bottomMenus = [
        [
            'key'   => 'settings',
            'name'  => 'Pengaturan',
            'route' => '/dashboard/distributor/settings',
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />',
        ],
    ];
@endphp

{{-- Main Nav Items --}}
<div class="flex flex-col flex-1">
@foreach($menus as $menu)
    @php
        $isActive = ($menu['key'] === 'overview')
            ? ($currentPage === 'overview' || request()->is('dashboard/distributor'))
            : ($currentPage === $menu['key']);

        $activeClass = $isActive
            ? 'bg-white/10 text-secondary border-l-[6px] border-secondary'
            : 'text-white/60 hover:bg-white/5 hover:text-white border-l-[6px] border-transparent';
    @endphp
    <a href="{{ $menu['route'] }}"
       aria-label="Menu {{ $menu['name'] }}"
       aria-current="{{ $isActive ? 'page' : 'false' }}"
       class="flex items-center gap-3 px-5 py-3 text-[10px] font-headline font-bold uppercase tracking-widest transition-colors duration-150 {{ $activeClass }}">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            {!! $menu['icon'] !!}
        </svg>
        {{ $menu['name'] }}
    </a>
@endforeach

{{-- Bottom Pinned Nav --}}
<div class="mt-auto pb-4 border-t-2 border-white/10">
    @foreach($bottomMenus as $menu)
        @php
            $isActive = $currentPage === $menu['key'];
            $activeClass = $isActive
                ? 'bg-white/10 text-secondary border-l-[6px] border-secondary'
                : 'text-white/60 hover:bg-white/5 hover:text-white border-l-[6px] border-transparent';
        @endphp
        <a href="{{ $menu['route'] }}"
           aria-label="Menu {{ $menu['name'] }}"
           aria-current="{{ $isActive ? 'page' : 'false' }}"
           class="flex items-center gap-3 px-5 py-3 text-[10px] font-headline font-bold uppercase tracking-widest transition-colors duration-150 {{ $activeClass }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $menu['icon'] !!}
            </svg>
            {{ $menu['name'] }}
        </a>
    @endforeach
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <a href="#"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
       aria-label="Keluar dari Portal Distributor"
       class="flex items-center gap-3 px-5 py-3 text-[10px] font-headline font-bold uppercase tracking-widest text-white/60 hover:text-white border-l-[6px] border-transparent transition-colors duration-150">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
        Keluar
    </a>
</div>
</div>{{-- end flex-1 wrapper --}}
