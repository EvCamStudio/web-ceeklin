@php
    // Deteksi halaman aktif dari URL segment ketiga (/dashboard/admin/{page})
    $currentPage = request()->segment(3) ?? 'overview';

    $menus = [
        [
            'key'   => 'overview',
            'name'  => 'Beranda',
            'route' => '/dashboard/admin',
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />',
        ],
        [
            'key'   => 'pricing',
            'name'  => 'Kontrol Harga',
            'route' => '/dashboard/admin/pricing',
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z" />',
        ],
        [
            'key'   => 'bonus',
            'name'  => 'Pelacak Bonus',
            'route' => '/dashboard/admin/bonus',
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />',
        ],
        [
            'key'   => 'distributors',
            'name'  => 'Distributor',
            'route' => '/dashboard/admin/distributors',
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />',
        ],
        [
            'key'   => 'sales',
            'name'  => 'Penjualan Nasional',
            'route' => '/dashboard/admin/sales',
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />',
        ],
    ];

    $bottomMenus = [
        [
            'key'   => 'settings',
            'name'  => 'Pengaturan',
            'route' => '/dashboard/admin/settings',
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />',
        ],
    ];
@endphp

{{-- Main Nav Items --}}
<div class="flex flex-col flex-1">
@foreach($menus as $menu)
    @php
        $isActive = ($menu['key'] === 'overview')
            ? ($currentPage === 'overview' || request()->is('dashboard/admin'))
            : ($currentPage === $menu['key']);

        $activeClass = $isActive
            ? 'bg-primary text-white border-l-[6px] border-secondary'
            : 'text-gray-600 hover:bg-neutral-border-light border-l-[6px] border-transparent';
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
<div class="mt-auto pb-4 border-t-2 border-neutral-border">
    @foreach($bottomMenus as $menu)
        @php
            $isActive = $currentPage === $menu['key'];
            $activeClass = $isActive
                ? 'bg-primary text-white border-l-[6px] border-secondary'
                : 'text-gray-600 hover:bg-neutral-border-light border-l-[6px] border-transparent';
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
       aria-label="Keluar dari Dashboard"
       class="flex items-center gap-3 px-5 py-3 text-[10px] font-headline font-bold uppercase tracking-widest text-gray-600 hover:text-primary border-l-[6px] border-transparent transition-colors duration-150">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
        Keluar
    </a>
</div>
</div>{{-- end flex-1 wrapper --}}
