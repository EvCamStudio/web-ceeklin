@props(['variant' => 'complex'])

@if($variant === 'complex')
    <footer class="bg-gray-900 text-gray-400 border-t-8 border-secondary-hover font-body py-16 px-12">
        <div class="max-w-[1400px] mx-auto flex flex-col md:flex-row justify-between items-start gap-8">
            <div class="flex flex-col">
                <h2 class="font-headline font-bold text-secondary-hover text-2xl tracking-tighter mb-2">CEEKLIN</h2>
                <p class="text-[10px] uppercase font-bold tracking-widest leading-relaxed text-slate-400">
                    &copy; 2024 CEEKLIN INDUSTRIAL. KEMENKES RI PKL 20303120614.<br>
                    DIRANCANG UNTUK KEKUATAN.
                </p>
            </div>
            
            <div class="flex flex-col items-start md:items-end gap-3 text-[10px] uppercase font-bold tracking-widest text-slate-400 md:pr-16">
                <a href="#" class="hover:text-white transition-colors">LEMBAR DATA KEAMANAN BAHAN</a>
                <a href="#" class="hover:text-white transition-colors">SPESIFIKASI TEKNIS</a>
                <a href="#" class="hover:text-white transition-colors">MITRA DISTRIBUSI</a>
                <a href="#" class="hover:text-white transition-colors">PROTOKOL PRIVASI</a>
            </div>
        </div>
    </footer>
@elseif($variant === 'simple')
    <footer class="w-full bg-gray-900 border-t-2 border-primary py-4 px-8 md:px-12 flex justify-between items-center text-secondary-dark text-[9px] font-bold uppercase tracking-[0.2em] z-40">
        <div>CEEKLIN INDUSTRIAL</div>
        <div>&copy; 2024 CEEKLIN</div>
    </footer>
@endif
