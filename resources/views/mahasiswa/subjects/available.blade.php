@extends('layouts.app')

@section('content')
<div class="p-6 space-y-8">
    <header>
        <flux:heading size="xl">Cari Mata Kuliah</flux:heading>
        <flux:subheading>Cari berdasarkan nama atau kode mata kuliah.</flux:subheading>
    </header>

    {{-- Form Pencarian --}}
    <form action="{{ route('mahasiswa.subjects.available') }}" method="GET" class="flex gap-2">
        <flux:input name="search" placeholder="Masukkan nama/kode matkul..." class="flex-1" value="{{ request('search') }}" />
        <flux:button type="submit" variant="subtle" icon="magnifying-glass">Cari</flux:button>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($subjects as $subject)
            <flux:card class="flex flex-col justify-between">
                <div>
                    <flux:badge color="blue" variant="subtle">{{ $subject->subject_code }}</flux:badge>
                    <flux:heading size="lg" class="mt-2">{{ $subject->subject_name }}</flux:heading>
                    <flux:text class="mt-1 text-sm">Dosen: {{ $subject->user->name }}</flux:text>
                </div>

                <form action="{{ route('mahasiswa.subjects.join', $subject->id) }}" method="POST" class="mt-6">
                    @csrf
                    <flux:button type="submit" variant="primary" class="w-full">Gabung Kelas</flux:button>
                </form>
            </flux:card>
        @empty
            <flux:text class="col-span-full text-center py-10">Mata kuliah tidak ditemukan atau sudah Anda ambil.</flux:text>
        @endforelse
    </div>
    
    <flux:button href="{{ route('mahasiswa.subjects.index') }}" variant="ghost" icon="arrow-left">Kembali</flux:button>
</div>
@endsection