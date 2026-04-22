@props(['name', 'title' => 'DIALOG BOX'])

{{-- 
  Modal Wrapper Logic menggunakan Alpine.js
  di komponen induk untuk toggle mount dan unmount modal ini secara absolut.
  Dapat dipanggil dengan event: $dispatch('open-modal', 'nama-modal')
--}}
<div 
    x-data="{ show: false }"
    x-show="show"
    x-cloak
    @open-modal.window="if ($event.detail === '{{ $name }}') show = true"
    @close-modal.window="if ($event.detail === '{{ $name }}') show = false"
    @keydown.escape.window="show = false"
    class="relative z-50 pointer-events-auto" 
    aria-labelledby="modal-title" 
    role="dialog" 
    aria-modal="true" 
    style="display: none;"
>
    
    <!-- Backdrop Gelap Brutalist (Opasitas Solid Tinggi) -->
    <div class="fixed inset-0 bg-gray-900/80 transition-opacity backdrop-blur-sm" aria-hidden="true"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            
            <!-- Panel Modal Brutalist -->
            <div class="relative transform overflow-hidden border-4 border-primary bg-neutral-light text-left shadow-[15px_15px_0_var(--color-primary-darkest)] transition-all sm:my-8 sm:w-full sm:max-w-lg rounded-none">
                
                <!-- Aksen Pita Atas -->
                <div class="h-2 w-full bg-secondary" aria-hidden="true"></div>
                
                <!-- Header Modal -->
                <div class="border-b-2 border-neutral-border bg-white px-6 py-4 flex justify-between items-center relative overflow-hidden">
                    <div class="absolute inset-y-0 left-0 w-2 bg-primary" aria-hidden="true"></div>
                    <h3 class="font-headline text-xl font-bold uppercase tracking-widest text-primary pl-2" id="modal-title">
                        {{ $title }}
                    </h3>
                    
                    {{-- Tombol Tutup --}}
                    <button type="button" @click="show = false" class="text-slate-400 hover:text-primary transition-colors focus:outline-none focus:ring-2 focus:ring-secondary focus:ring-offset-2 bg-neutral-border-light p-1" aria-label="Tutup Dialog">
                        <span class="sr-only">Tutup</span>
                        <svg class="h-6 w-6" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Tubuh / Konten Modal -->
                <div class="bg-neutral px-6 py-8 relative">
                    <div class="absolute inset-0 pointer-events-none opacity-5" style="background-image: radial-gradient(var(--color-gray-900) 1px, transparent 1px); background-size: 16px 16px;" aria-hidden="true"></div>
                    <div class="text-sm font-body text-gray-900 leading-relaxed font-bold relative z-10">
                        {{ $slot }}
                    </div>
                </div>

                <!-- Kaki / Aksi Tombol -->
                @if (isset($footer))
                    <div class="border-t-[3px] border-neutral-border bg-neutral-dark px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        {{ $footer }}
                    </div>
                @else 
                    <div class="border-t-[3px] border-neutral-border bg-neutral-dark px-6 py-5 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <x-ui.button variant="outline" type="button" class="w-full sm:w-auto bg-white hover:bg-neutral-hover hover:text-primary">BATAL</x-ui.button>
                        <x-ui.button type="button" class="w-full sm:w-auto border-2 border-primary hover:bg-primary-hover shadow-none">KONFIRMASI</x-ui.button>
                    </div>
                @endif
                
            </div>
        </div>
    </div>
</div>
