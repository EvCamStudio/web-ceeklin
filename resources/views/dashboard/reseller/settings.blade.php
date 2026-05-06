<x-layouts.dashboard bgTheme="dark">
    @php
        // Frontend-only fix to avoid touching Backend Controller
        $user = Auth::user();
    @endphp

    <x-slot:subtitleSlot>
        <span class="text-[10px] uppercase font-bold text-secondary tracking-widest mt-1">PORTAL RESELLER</span>
    </x-slot:subtitleSlot>
    <x-slot:topbarTitle>PENGATURAN</x-slot:topbarTitle>
    <x-slot:menuSlot>@include('dashboard.reseller._menu')</x-slot:menuSlot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-6xl pb-12">

        {{-- Profil Reseller --}}
        <div class="lg:col-span-2 flex flex-col gap-8">
            <x-ui.card shadow="primary" headerColor="primary">
                <x-slot:titleSlot><span class="italic">Identitas & Profil</span></x-slot:titleSlot>
                
                <form action="{{ route('reseller.settings.profile') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    {{-- Avatar Picker --}}
                    <div class="mb-10 flex flex-col sm:flex-row items-center gap-8 border-b-2 border-dashed border-gray-100 pb-8">
                        <div class="relative group">
                            <div class="w-28 h-28 bg-neutral-light border-[4px] border-gray-900 shadow-[6px_6px_0_var(--color-primary)] overflow-hidden">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-3xl font-headline font-black text-primary italic uppercase">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <label for="avatar" class="absolute -bottom-2 -right-2 bg-gray-900 text-white p-2 border-2 border-white cursor-pointer hover:bg-primary transition-colors shadow-[2px_2px_0_rgba(0,0,0,0.2)]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </label>
                            <input type="file" id="avatar" name="avatar" class="hidden" accept="image/*">
                        </div>
                        <div class="text-center sm:text-left">
                            <h4 class="font-headline font-black text-gray-900 text-lg uppercase italic leading-none mb-1">Foto Profil</h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic leading-relaxed">Gunakan foto wajah yang jelas.<br>Format: JPG, PNG (Maks. 2MB)</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 italic">
                        <x-ui.input id="name" name="name" label="Nama Lengkap" value="{{ old('name', $user->name) }}" />
                        <x-ui.input id="nik" label="No. NIK (Sesuai KTP)" value="{{ $user->nik ?? 'N/A' }}" disabled />
                        <x-ui.input id="phone" name="phone" type="tel" label="No. HP (WhatsApp Aktif)" value="{{ old('phone', $user->phone) }}" />
                        <x-ui.input id="ahli_waris" name="ahli_waris" label="Nama Ahli Waris" value="{{ old('ahli_waris', $user->ahli_waris ?? 'N/A') }}" />

                        <div class="flex flex-col gap-2 md:col-span-2">
                            <label class="text-[10px] font-bold text-primary uppercase tracking-widest italic" for="address">Alamat Pengiriman Lengkap</label>
                            <textarea id="address" name="address" rows="3"
                                class="bg-neutral-light border-[3px] border-primary px-4 py-3 font-body text-sm text-primary focus:outline-none focus:border-secondary transition-colors resize-none italic shadow-[4px_4px_0_rgba(0,0,0,0.03)]">{{ old('address', $user->address) }}</textarea>
                        </div>
                    </div>

                    {{-- Geolocation Section --}}
                    <div class="mt-10 pt-8 border-t-2 border-dashed border-gray-100 italic">
                        <div class="flex items-center gap-3 mb-6">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <h4 class="font-headline font-black text-gray-900 text-sm uppercase tracking-tight italic">Titik Koordinat Lokasi (Maps)</h4>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                            <div class="space-y-4">
                                <p class="text-[10px] font-bold text-slate-500 uppercase italic leading-relaxed mb-4">Pastikan titik koordinat sesuai dengan lokasi outlet/rumah Anda untuk keperluan pemetaan nasional.</p>
                                <div class="flex flex-col gap-4">
                                    <x-ui.input id="latitude" name="latitude" label="Latitude" placeholder="-6.xxxxxx" value="{{ old('latitude', $user->latitude ?? '') }}" class="!py-3" />
                                    <x-ui.input id="longitude" name="longitude" label="Longitude" placeholder="106.xxxxxx" value="{{ old('longitude', $user->longitude ?? '') }}" class="!py-3" />
                                </div>
                                <button type="button" onclick="alert('Mencari lokasi GPS...')" 
                                    class="w-full mt-2 bg-gray-900 text-white px-6 py-4 font-headline font-black text-[10px] uppercase tracking-widest border-[3px] border-gray-900 shadow-[4px_4px_0_var(--color-primary)] hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-all flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    GUNAKAN LOKASI SAAT INI
                                </button>
                            </div>
                            
                            {{-- Map Mockup --}}
                            <div class="relative group">
                                <div class="aspect-video bg-neutral-light border-[3px] border-gray-900 shadow-[8px_8px_0_var(--color-gray-900)] flex items-center justify-center overflow-hidden">
                                    {{-- Placeholder for actual Map Integration --}}
                                    <div class="absolute inset-0 opacity-20 bg-[url('https://www.google.com/maps/vt/pb=!1m4!1m3!1i12!2i2368!3i1622!2m3!1e0!2sm!3i633010156!3m8!2sen!3sid!5e1105!12m4!1e68!2m2!1sset!2sRoadmap!4e0!5m1!1f0!2shv!4m2!1sh!100!2sh!23!4m1!1i148!6m1!1e1')] bg-cover"></div>
                                    <div class="relative z-10 text-center px-6">
                                        <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg animate-bounce">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                        </div>
                                        <p class="text-[9px] font-black text-gray-900 uppercase tracking-widest bg-white/80 px-2 py-1 inline-block border border-gray-900 italic">MAP INTEGRATION READY</p>
                                    </div>
                                </div>
                                <div class="absolute -top-3 -left-3 bg-primary text-white px-3 py-1 font-headline font-black text-[9px] uppercase italic border-2 border-gray-900 shadow-md">
                                    LIVE PREVIEW
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-12">
                        <x-ui.button class="w-full sm:w-auto px-12 py-5 italic text-sm" type="submit">
                            Simpan Seluruh Perubahan Profil
                        </x-ui.button>
                    </div>
                </form>
            </x-ui.card>
        </div>

        {{-- Panel Kanan --}}
        <div class="flex flex-col gap-8">
            {{-- Info Rekening --}}
            <x-ui.card shadow="secondary" headerColor="secondary">
                <x-slot:titleSlot><span class="italic">Info Rekening Bank</span></x-slot:titleSlot>
                
                <form action="{{ route('reseller.settings.bank') }}" method="POST" class="flex flex-col gap-5 italic">
                    @csrf
                    <x-ui.input id="bank_name" name="bank_name" label="Nama Bank" value="{{ old('bank_name', $user->bank_name) }}" class="!border-secondary" />
                    <x-ui.input id="bank_account_number" name="bank_account_number" label="Nomor Rekening" value="{{ old('bank_account_number', $user->bank_account_number) }}" class="!border-secondary" />
                    <x-ui.input id="bank_account_name" name="bank_account_name" label="Atas Nama" value="{{ old('bank_account_name', $user->bank_account_name) }}" class="!border-secondary" />
                    
                    <x-ui.button variant="secondary" fullWidth="true" class="mt-2 shadow-[4px_4px_0_var(--color-gray-900)] italic" type="submit">
                        Perbarui Rekening
                    </x-ui.button>
                </form>
            </x-ui.card>

            {{-- Ganti Kata Sandi --}}
            <x-ui.card shadow="gray" headerColor="gray">
                <x-slot:titleSlot><span class="italic">Keamanan Akun</span></x-slot:titleSlot>
                
                <form action="{{ route('reseller.settings.password') }}" method="POST" class="flex flex-col gap-5 italic">
                    @csrf
                    <x-ui.input id="current_password" name="current_password" type="password" label="Sandi Lama" placeholder="••••••••" class="!border-gray-900" />
                    <x-ui.input id="new_password" name="new_password" type="password" label="Sandi Baru" placeholder="Min. 8 karakter" class="!border-gray-900" />
                    
                    <x-ui.button variant="primary" fullWidth="true" class="mt-2 shadow-[4px_4px_0_var(--color-gray-900)] italic" type="submit">
                        Perbarui Kata Sandi
                    </x-ui.button>
                </form>
            </x-ui.card>
        </div>
    </div>
</x-layouts.dashboard>
