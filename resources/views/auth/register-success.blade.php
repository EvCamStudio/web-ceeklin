<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrasi Berhasil - CEEKLIN Portal</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral font-body text-gray-900 min-h-screen flex flex-col antialiased overflow-x-hidden">
    {{-- Grain Texture Overlay --}}
    <div class="grain-overlay"></div>

    <x-layouts.navbar>
        <x-slot:links>
            {{-- Kosong untuk fokus pada sukses --}}
        </x-slot:links>
    </x-layouts.navbar>

    {{-- Main Content --}}
    <main class="flex-grow flex items-center justify-center py-8 px-4">
        <div class="w-full max-w-lg text-center">
            
            {{-- Success Icon (Brutalist Style) --}}
            <div class="mx-auto mb-6 w-20 h-20 bg-primary flex items-center justify-center border-[4px] border-gray-900 shadow-[8px_8px_0_var(--color-secondary)]">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            {{-- Success Card --}}
            <div class="bg-white border-4 border-gray-900 shadow-[12px_12px_0_var(--color-primary)] text-left">
                <div class="bg-primary p-4 border-b-4 border-gray-900">
                    <h1 class="font-headline font-black text-xl text-white uppercase tracking-tighter text-center">
                        Pendaftaran Berhasil!
                    </h1>
                </div>

                <div class="p-8">
                    <p class="text-sm text-slate-600 font-bold leading-relaxed mb-8 text-center">
                        Akun Anda telah tersimpan dan saat ini sedang <strong class="text-secondary uppercase tracking-widest pl-1">Menunggu Verifikasi</strong>. Hubungi Admin via WhatsApp untuk mempercepat proses.
                    </p>

                    {{-- Status Timeline --}}
                    <div class="bg-neutral border-2 border-neutral-border p-5 mb-8 flex flex-col gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 flex items-center justify-center bg-secondary border-2 border-gray-900 text-gray-900 flex-shrink-0">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="font-headline font-bold text-[10px] uppercase tracking-widest text-secondary">TAHAP 1: Menunggu Verifikasi Admin</p>
                                <p class="text-[8px] font-bold text-slate-400 uppercase mt-0.5">Sedang dalam antrean peninjauan</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 opacity-30">
                            <div class="w-6 h-6 flex items-center justify-center bg-transparent border-2 border-slate-400 text-slate-400 flex-shrink-0">
                                <span class="text-[10px] font-black">2</span>
                            </div>
                            <div>
                                <p class="font-headline font-bold text-[10px] uppercase tracking-widest text-slate-500">TAHAP 2: Masuk & Aktivasi Perdana</p>
                            </div>
                        </div>
                    </div>

                    {{-- Warning Box Brutalist --}}
                    <div class="mb-8 bg-secondary/10 border-l-[6px] border-secondary p-5">
                        <p class="text-secondary font-headline font-bold text-[11px] tracking-widest uppercase mb-1">
                            ⚠️ Perhatian
                        </p>
                        <p class="text-gray-900 text-xs font-bold leading-relaxed">
                            Mohon sertakan <span class="text-primary">Nama Lengkap & NIK</span> Anda saat menghubungi Admin agar proses peninjauan dapat dilakukan lebih cepat.
                        </p>
                    </div>

                    {{-- WhatsApp Link --}}
                    @php
                        $waNumber = '6283856823983';
                        $waMessage = "Halo Admin CeeKlin, saya baru saja mendaftar sebagai Reseller.\n\nMohon bantu verifikasi akun saya.\nNama: " . (auth()->user()->name ?? '...') . "\nNIK: " . (auth()->user()->nik ?? '...') . "\n\nTerima kasih!";
                    @endphp
                    <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($waMessage) }}" target="_blank" aria-label="Hubungi Admin via WhatsApp"
                        class="w-full bg-[#25D366] text-white px-4 py-4 font-headline font-black text-base uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-gray-900)] hover:bg-[#1ebd59] active:translate-y-0.5 active:shadow-none transition-all flex items-center justify-center gap-3">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.348-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        HUBUNGI ADMIN VIA WA
                    </a>
                    
                    <div class="mt-4 text-center">
                        @auth
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-[10px] font-bold text-primary hover:text-secondary uppercase tracking-widest transition-colors">
                                    KELUAR
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-[10px] font-bold text-primary hover:text-secondary uppercase tracking-widest transition-colors">
                                Ke Halaman Masuk
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </main>

    <x-layouts.footer variant="simple" />
</body>
</html>
