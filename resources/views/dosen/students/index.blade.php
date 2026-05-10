@extends('layouts.app')

@section('content')
<div class="p-6 space-y-6">
    <header>
        <flux:heading size="xl">Daftar Mahasiswa</flux:heading>
        <flux:subheading>Mahasiswa yang mengambil mata kuliah Anda.</flux:subheading>
    </header>

    <flux:card p-0>
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Nama Mahasiswa</flux:table.column>
                <flux:table.column>NIM</flux:table.column>
                <flux:table.column>Mata Kuliah yang Diikuti</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach($students as $student)
                    <flux:table.row>
                        <flux:table.cell class="font-medium">{{ $student->name }}</flux:table.cell>
                        <flux:table.cell>{{ $student->nim }}</flux:table.cell>
                        <flux:table.cell>
                            @foreach($student->subjects as $sub)
                                <flux:badge size="sm" variant="subtle" color="blue" class="mr-1">
                                    {{ $sub->subject_name }}
                                </flux:badge>
                            @endforeach
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>

        @if($students->isEmpty())
            <div class="p-10 text-center text-zinc-500">Belum ada mahasiswa yang bergabung di kelas Anda.</div>
        @endif
    </flux:card>
</div>
@endsection