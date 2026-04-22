<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrasi Berhasil - CEEKLIN Portal</title>
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
                        Akun Anda berhasil dikonfigurasi. Segera lakukan <strong class="text-primary uppercase tracking-widest pl-1">Login Pertama</strong> untuk memulai proses aktivasi wilayah.
                    </p>

                    {{-- Status Timeline --}}
                    <div class="bg-neutral border-2 border-neutral-border p-5 mb-8 flex flex-col gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 flex items-center justify-center bg-primary text-white border-2 border-gray-900 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <p class="font-headline font-bold text-[10px] uppercase tracking-widest text-gray-900">Data Diri Terverifikasi</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 flex items-center justify-center bg-primary text-white border-2 border-gray-900 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <p class="font-headline font-bold text-[10px] uppercase tracking-widest text-gray-900">Rekening Bank Terdaftar</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 opacity-50">
                            <div class="w-6 h-6 flex items-center justify-center bg-transparent border-2 border-slate-400 text-slate-400 flex-shrink-0">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="font-headline font-bold text-[10px] uppercase tracking-widest text-secondary">Menunggu Aktivasi Stok</p>
                            </div>
                        </div>
                    </div>

                    {{-- Warning Box Brutalist --}}
                    <div class="mb-8 bg-secondary/10 border-l-[6px] border-secondary p-5">
                        <p class="text-secondary font-headline font-bold text-[11px] tracking-widest uppercase mb-1">
                            ⚠️ Perhatian
                        </p>
                        <p class="text-gray-900 text-xs font-bold leading-relaxed">
                            Lakukan pembelian komitmen awal dalam <span class="text-primary">48 JAM</span> setelah login. Sistem akan menghapus data pendaftaran jika syarat ini tidak terpenuhi.
                        </p>
                    </div>

                    <x-ui.button fullWidth="true" onclick="window.location.href='/login'" class="py-4 text-base">
                        KEMBALI KE LOGIN &rarr;
                    </x-ui.button>
                </div>
            </div>
        </div>
    </main>

    <x-layouts.footer variant="simple" />
</body>
</html>
