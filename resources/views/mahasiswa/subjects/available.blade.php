@extends('layouts.app')

@section('content')
<div class="p-6 space-y-8 max-w-7xl mx-auto">
    <header>
        <flux:heading size="xl">Cari Mata Kuliah</flux:heading>
        <flux:subheading>Cari dan bergabunglah ke kelas menggunakan kode yang diberikan dosen.</flux:subheading>
    </header>

    {{-- ALERT ERROR --}}
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm animate-fade-in-down">
            <flux:icon name="exclamation-circle" variant="solid" class="w-5 h-5 text-red-500" />
            <span class="font-medium text-sm">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Form Pencarian --}}
    <form action="{{ route('mahasiswa.subjects.available') }}" method="GET" class="flex gap-2 w-full md:w-1/2">
        <flux:input name="search" placeholder="Masukkan nama/kode matkul..." class="flex-1" value="{{ request('search') }}" />
        <flux:button type="submit" variant="primary" icon="magnifying-glass">Cari</flux:button>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($subjects as $subject)
            @php
                // Cek apakah ID subject ini ada di dalam array $enrolledSubjectIds
                $isEnrolled = in_array($subject->id, $enrolledSubjectIds);
            @endphp

            <flux:card class="flex flex-col justify-between hover:shadow-md transition-shadow">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <flux:badge color="indigo" variant="subtle">{{ $subject->subject_code }}</flux:badge>
                        
                        {{-- Label Tambahan di Pojok Kanan Atas --}}
                        @if($isEnrolled)
                            <flux:badge color="emerald" icon="check-circle" size="sm">Terdaftar</flux:badge>
                        @endif
                    </div>
                    
                    <flux:heading size="lg" class="font-bold text-slate-800">{{ $subject->subject_name }}</flux:heading>
                    <flux:text class="mt-1 text-sm text-slate-500 flex items-center gap-1">
                        <flux:icon name="user" variant="micro" /> Dosen: {{ $subject->user->name }}
                    </flux:text>
                </div>

                <div class="mt-6">
                    @if($isEnrolled)
                        {{-- Tombol jika sudah bergabung (Disabled & berubah fungsi menjadi link ke kelas) --}}
                        <flux:button class="w-full bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100" icon="arrow-right" :href="route('mahasiswa.subjects.exams', $subject->id)" wire:navigate>
                            Masuk ke Kelas
                        </flux:button>
                    @else
                        {{-- Tombol pemicu Modal Kode Kelas jika belum bergabung --}}
                        <flux:modal.trigger name="join-class-{{ $subject->id }}">
                            <flux:button variant="primary" class="w-full bg-blue-600 hover:bg-blue-700" icon="plus">
                                Gabung Kelas
                            </flux:button>
                        </flux:modal.trigger>

                        {{-- Modal Input Kode Akses --}}
                        <flux:modal name="join-class-{{ $subject->id }}" class="md:w-[400px]">
                            <form action="{{ route('mahasiswa.subjects.join', $subject->id) }}" method="POST" class="space-y-6">
                                @csrf
                                
                                <div class="text-center space-y-2">
                                    <div class="mx-auto w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-4">
                                        <flux:icon name="key" variant="solid" class="w-6 h-6" />
                                    </div>
                                    <flux:heading size="lg">Masukkan Kode Kelas</flux:heading>
                                    <flux:text class="text-sm text-slate-500">
                                        Mata Kuliah: <strong>{{ $subject->subject_name }}</strong>. <br>
                                        Mintalah kode akses kelas kepada dosen yang bersangkutan.
                                    </flux:text>
                                </div>

                                <flux:input 
                                    name="join_code" 
                                    placeholder="Contoh: X7B9WQ" 
                                    required 
                                    class="text-center font-mono tracking-widest uppercase text-lg"
                                />

                                <div class="flex gap-3 w-full pt-2">
                                    <flux:modal.close class="w-full">
                                        <flux:button variant="subtle" class="w-full">Batal</flux:button>
                                    </flux:modal.close>
                                    <flux:button type="submit" variant="primary" class="w-full bg-blue-600 hover:bg-blue-700">Verifikasi & Gabung</flux:button>
                                </div>
                            </form>
                        </flux:modal>
                    @endif
                </div>
            </flux:card>
        @empty
            <div class="col-span-full py-16 text-center border-2 border-dashed border-slate-200 rounded-2xl">
                <flux:icon name="magnifying-glass" class="mx-auto h-12 w-12 text-slate-300 mb-4" />
                <flux:heading size="md" class="text-slate-500">Mata kuliah tidak ditemukan.</flux:heading>
                <flux:text class="text-sm text-slate-400">Coba gunakan kata kunci pencarian yang lain.</flux:text>
            </div>
        @endforelse
    </div>
    
    <div class="pt-4">
        <flux:button href="{{ route('mahasiswa.subjects.index') }}" variant="ghost" icon="arrow-left" wire:navigate>Kembali ke Mata Kuliah Saya</flux:button>
    </div>
</div>
@endsection