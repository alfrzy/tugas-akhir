@extends('layouts.app')

@section('content')
<div class="p-6 space-y-6">
    <header class="flex items-center justify-between">
        <div>
            <flux:heading size="xl" level="1">Kelola Mata Kuliah</flux:heading>
            <flux:subheading>Daftar semua mata kuliah dan dosen pengampunya.</flux:subheading>
        </div>
        {{-- Tombol Tambah Matkul Baru --}}
        <flux:button 
            variant="primary" 
            icon="plus" 
            :href="route('admin.subjects.create')" 
            wire:navigate
        >
            Tambah Mata Kuliah
        </flux:button>
    </header>

    <flux:card class="overflow-hidden">
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Kode</flux:table.column>
                <flux:table.column>Nama Mata Kuliah</flux:table.column>
                <flux:table.column>Dosen Pengampu</flux:table.column>
                <flux:table.column align="end">Aksi</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach($subjects as $subject)
                    <flux:table.row>
                        <flux:table.cell class="font-mono text-xs">{{ $subject->subject_code }}</flux:table.cell>
                        <flux:table.cell class="font-medium">{{ $subject->subject_name }}</flux:table.cell>
                        <flux:table.cell>
                            @if($subject->user)
                                <flux:badge color="purple" variant="subtle">{{ $subject->user->name }}</flux:badge>
                            @else
                                <flux:badge color="red" variant="flat">Belum Ada Dosen</flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            {{-- DISINI BARU BOLEH PAKE $subject --}}
                            <flux:button 
                                icon="user-plus" 
                                variant="ghost" 
                                size="sm" 
                                tooltip="Tugaskan Dosen"
                                :href="route('admin.subjects.assign', $subject->id)" 
                                wire:navigate 
                            />
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </flux:card>
</div>
@endsection