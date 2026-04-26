@php
    $currentPage = request()->segment(3) ?? 'overview';

    $menus = [
        [
            'key'   => 'overview',
            'name'  => 'Beranda',
            'route' => '/dashboard/reseller',
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />',
        ],
        [
            'key'   => 'earnings',
            'name'  => 'Pendapatan',
            'route' => '/dashboard/reseller/earnings',
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />',
        ],
        [
            'key'   => 'referrals',
            'name'  => 'Referral',
            'route' => '/dashboard/reseller/referrals',
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />',
        ],
        [
            'key'   => 'tier',
            'name'  => 'Status Tier',
            'route' => '/dashboard/reseller/tier',
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />',
        ],
        [
            'key'   => 'order',
            'name'  => 'Buat Pesanan',
            'route' => '/dashboard/reseller/order',
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />',
        ],
    ];

    $bottomMenus = [
        [
            'key'   => 'settings',
            'name'  => 'Pengaturan',
            'route' => '/dashboard/reseller/settings',
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />',
        ],
    ];
@endphp

<div class="flex flex-col flex-1">
@foreach($menus as $menu)
    @php
        $isActive = ($menu['key'] === 'overview')
            ? ($currentPage === 'overview' || request()->is('dashboard/reseller'))
            : ($currentPage === $menu['key']);

        // Reseller: active = amber/gold, inactive = white/50
        $activeClass = $isActive
            ? 'bg-white/10 text-secondary border-l-[6px] border-secondary'
            : 'text-white/50 hover:bg-white/5 hover:text-white border-l-[6px] border-transparent';
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

<div class="mt-auto pb-4 border-t-2 border-white/10">
    @foreach($bottomMenus as $menu)
        @php
            $isActive = $currentPage === $menu['key'];
            $activeClass = $isActive
                ? 'bg-white/10 text-secondary border-l-[6px] border-secondary'
                : 'text-white/50 hover:bg-white/5 hover:text-white border-l-[6px] border-transparent';
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
       aria-label="Keluar dari Portal Reseller"
       class="flex items-center gap-3 px-5 py-3 text-[10px] font-headline font-bold uppercase tracking-widest text-white/50 hover:text-white border-l-[6px] border-transparent transition-colors duration-150">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
        Keluar
    </a>
</div>
</div>
