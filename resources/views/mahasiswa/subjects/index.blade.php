@extends('layouts.app')

@section('content')
<div class="p-6 space-y-8">
    
    {{-- ALERT PESAN SUKSES --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm animate-fade-in-down">
            <flux:icon name="check-circle" variant="solid" class="w-5 h-5 text-emerald-500" />
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <header class="flex justify-between items-center">
        <div>
            <flux:heading size="xl">Mata Kuliah Saya</flux:heading>
            <flux:subheading>Daftar kelas yang sedang Anda ikuti.</flux:subheading>
        </div>
        
        <flux:button href="{{ route('mahasiswa.subjects.available') }}" variant="primary" icon="magnifying-glass" wire:navigate>
            Cari & Gabung Kelas
        </flux:button>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($mySubjects as $subject)
            <flux:card class="flex flex-col justify-between">
                <div>
                    <flux:badge color="green" variant="subtle">{{ $subject->subject_code }}</flux:badge>
                    <flux:heading size="lg" class="mt-2">{{ $subject->subject_name }}</flux:heading>
                    <flux:text class="mt-1 text-sm">Dosen: {{ $subject->user->name }}</flux:text>
                </div>
                <div class="mt-6">
                <flux:button 
                    href="{{ route('mahasiswa.subjects.exams', $subject->id) }}" 
                    variant="subtle" 
                    class="w-full" 
                    icon="document-text"
                    wire:navigate
                >
                    Lihat Ujian
                </flux:button>
            </div>
            </flux:card>
        @empty
            <flux:card class="col-span-full p-12 text-center">
                <flux:text>Anda belum bergabung di kelas manapun.</flux:text>
                <flux:button href="{{ route('mahasiswa.subjects.available') }}" variant="subtle">Cari Mata Kuliah Sekarang</flux:button>
            </flux:card>
        @endforelse
    </div>
</div>
@endsection