<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UI Component Showcase - Portal CEEKLIN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral font-body text-gray-900 antialiased min-h-screen p-10 relative">
    {{-- Grain Texture Overlay --}}
    <div class="grain-overlay"></div>

    <div class="max-w-4xl mx-auto space-y-12">
        <header class="border-b-[4px] border-primary pb-4 mb-10">
            <h1 class="font-headline font-black text-4xl text-primary uppercase tracking-tighter">
                UI COMPONENT SHOWCASE
            </h1>
            <p class="text-xs uppercase font-bold text-secondary tracking-[0.2em] mt-1">
                JAGONYA PEMBASMI NODA - DESIGN SYSTEM
            </p>
        </header>

        <!-- PESAN UNTUK BACKEND TEAM -->
        <div class="bg-primary-darkest border-l-4 border-secondary p-4 text-left shadow-md">
            <p class="text-secondary font-black text-xs uppercase tracking-widest mb-1">
                [INFORMASI / TODO UNTUK BACKEND DEVELOPER]
            </p>
            <p class="text-white text-[11px] leading-relaxed uppercase">
                <!-- kita udh nyiapin komponen nya, coba di cek dlu komponen komponen nya bia tau gitu -->
                Halo Tim Backend! Kami sudah menyiapkan berbagai komponen error handling terstandarisasi untuk Frontend. Anda bisa langsung memanggil komponen-komponen ini ketika melakukan integrasi backend. Silakan baca panduan di bawah untuk pemakaiannya.
            </p>
        </div>

        <!-- 1. BUTTONS -->
        <section class="space-y-4">
            <h2 class="font-headline font-bold text-xl text-primary uppercase tracking-tight border-b-2 border-neutral-border pb-1">
                1. BUTTONS (<code class="lowercase">&lt;x-ui.button&gt;</code>)
            </h2>
            <div class="flex flex-wrap gap-4 p-4 bg-white border-2 border-primary/20">
                <x-ui.button>PRIMARY BUTTON</x-ui.button>
                <x-ui.button variant="secondary">SECONDARY BUTTON</x-ui.button>
            </div>
        </section>

        <!-- 2. CARDS -->
        <section class="space-y-4">
            <h2 class="font-headline font-bold text-xl text-primary uppercase tracking-tight border-b-2 border-neutral-border pb-1">
                2. CARDS (<code class="lowercase">&lt;x-ui.card&gt;</code>)
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-white border-2 border-primary/20">
                <x-ui.card titleSlot="PRIMARY CARD" headerColor="primary">
                    <p class="text-xs font-bold uppercase text-slate-600">Konten dari card beraliran desain industrial brutalist.</p>
                </x-ui.card>

                <x-ui.card titleSlot="SECONDARY CARD" headerColor="secondary">
                    <p class="text-xs font-bold uppercase text-slate-600">Cocok untuk penekanan informasi/peringatan.</p>
                </x-ui.card>
            </div>
        </section>

        <!-- 3. INPUT FIELDS -->
        <section class="space-y-4">
            <h2 class="font-headline font-bold text-xl text-primary uppercase tracking-tight border-b-2 border-neutral-border pb-1">
                3. INPUTS (<code class="lowercase">&lt;x-ui.input&gt;</code>)
            </h2>
            <div class="space-y-6 p-4 bg-white border-2 border-primary/20">
                <div>
                    <label class="block text-[10px] font-bold text-primary uppercase tracking-widest mb-1.5">TEXT INPUT</label>
                    <x-ui.input type="text" placeholder="Masukkan teks di sini..." id="example-text" name="example_text" />
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-primary uppercase tracking-widest mb-1.5">PASSWORD INPUT (INTERACTIVE SHOW/HIDE)</label>
                    <x-ui.input type="password" placeholder="********" id="example-pass" name="example_pass" />
                </div>
            </div>
        </section>

        <!-- 4. TOAST NOTIFICATIONS -->
        <section class="space-y-4">
            <h2 class="font-headline font-bold text-xl text-primary uppercase tracking-tight border-b-2 border-neutral-border pb-1">
                4. TOASTS (<code class="lowercase">&lt;x-ui.toast&gt;</code>)
            </h2>
            <p class="text-[10px] uppercase font-bold text-slate-500">Gunakan untuk notifikasi melayang tanpa merubah layout utama.</p>
            <div x-data="{ toasts: { success: true, warning: true, error: true }, trigger(type) { this.toasts[type] = false; setTimeout(() => this.toasts[type] = true, 50); } }" class="space-y-4 p-4 bg-white border-2 border-primary/20">
                <div class="flex flex-wrap gap-4 border-b-2 border-neutral-border pb-4">
                    <x-ui.button type="button" @click="trigger('success')" class="text-[10px] py-2">TRIGGER SUCCESS</x-ui.button>
                    <x-ui.button type="button" variant="secondary" @click="trigger('warning')" class="text-[10px] py-2">TRIGGER WARNING</x-ui.button>
                    <x-ui.button type="button" @click="trigger('error')" class="bg-red-600 border-red-700 text-[10px] py-2 hover:bg-red-700">TRIGGER ERROR</x-ui.button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 relative min-h-[150px]">
                    <!-- SUCCESS -->
                    <template x-if="toasts.success">
                        <x-ui.toast type="success" message="OPERASI BERHASIL DISIMPAN!" />
                    </template>
                    <!-- WARNING -->
                    <template x-if="toasts.warning">
                        <x-ui.toast type="warning" message="PERINGATAN: CEK KEMBALI DATA ANDA!" />
                    </template>
                    <!-- ERROR -->
                    <template x-if="toasts.error">
                        <x-ui.toast type="error" message="MAAF, IDENTITAS TIDAK COCOK!" />
                    </template>
                </div>
            </div>
        </section>

        <!-- 5. ERROR HANDLING PILLS -->
        <section class="space-y-4" x-data="{ errors: { example_error: 'Kolom ini wajib diisi dengan benar' } }">
            <h2 class="font-headline font-bold text-xl text-primary uppercase tracking-tight border-b-2 border-neutral-border pb-1">
                5. ERROR PILLS (<code class="lowercase">&lt;x-ui.error&gt;</code>)
            </h2>
            <p class="text-[10px] uppercase font-bold text-slate-500">Pill melayang tanpa pergeseran layout. Otomatis mendukung Alpine.js & Laravel Backend Error.</p>
            
            <div class="space-y-12 p-4 bg-white border-2 border-primary/20 relative min-h-[100px]">
                <!-- Contoh Penempatan Langsung -->
                <div class="relative w-full max-w-xs">
                    <label class="block text-[10px] font-bold text-primary uppercase tracking-widest mb-1.5">CONTOH FIELD DENGAN ERROR</label>
                    <input type="text" class="w-full bg-neutral-light border-red-600 bg-red-50 border-[3px] px-4 py-2.5 font-bold text-sm text-primary focus:outline-none focus:border-secondary transition-colors" value="Data salah..." disabled />
                    
                    <!-- Cara Pemanggilan di Backend (Contoh Manual) -->
                    <x-ui.error message="FORMAT DATA TIDAK VALID!" />
                </div>

                <!-- Contoh Terintegrasi Alpine -->
                <div class="relative w-full max-w-xs mt-10">
                    <label class="block text-[10px] font-bold text-primary uppercase tracking-widest mb-1.5">SIMULASI ALPINE ERROR</label>
                    <input type="text" class="w-full bg-neutral-light border-[3px] border-primary px-4 py-2.5 font-bold text-sm text-primary" placeholder="Tiru state error..." disabled />
                    
                    <!-- Panggil berdasarkan nama input -->
                    <x-ui.error name="example_error" />
                </div>
            </div>
        </section>
    </div>
</body>
</html>
