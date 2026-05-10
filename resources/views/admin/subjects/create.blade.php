@extends('layouts.app')

@section('content')
<div class="p-6 max-w-2xl mx-auto space-y-6">
    <header>
        <flux:heading size="xl">Tambah Mata Kuliah Baru</flux:heading>
        <flux:subheading>Masukkan informasi mata kuliah dan tentukan dosen pengampunya.</flux:subheading>
    </header>

    <flux:card>
        <form action="{{ route('admin.subjects.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Input Kode Matkul --}}
            <flux:input name="subject_code" label="Kode Mata Kuliah" placeholder="Contoh: MK001" required />

            {{-- Input Nama Matkul --}}
            <flux:input name="subject_name" label="Nama Mata Kuliah" placeholder="Contoh: Pemrograman Web" required />

            {{-- Pilih Dosen (Opsional) --}}
            <flux:select name="user_id" label="Tugaskan Dosen (Opsional)" placeholder="Pilih nama dosen...">
                <flux:select.option value="">-- Tanpa Pengampu --</flux:select.option>
                @foreach($dosens as $dosen)
                    <flux:select.option value="{{ $dosen->id }}">{{ $dosen->name }}</flux:select.option>
                @endforeach
            </flux:select>

            <div class="flex gap-2 pt-4">
                <flux:button type="submit" variant="primary">Simpan Mata Kuliah</flux:button>
                <flux:button :href="route('admin.subjects.index')" variant="ghost">Batal</flux:button>
            </div>
        </form>
    </flux:card>
</div>
@endsection