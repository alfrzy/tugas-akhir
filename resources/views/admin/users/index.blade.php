@extends('layouts.app')

@section('content')
<div class="p-6 space-y-6">
    <header>
        <flux:heading size="xl" level="1">
            Kelola Data {{ $role === 'dosen' ? 'Dosen' : 'Mahasiswa' }}
        </flux:heading>
        <flux:subheading>
            Menampilkan semua pengguna dengan peran {{ $role }}.
        </flux:subheading>
    </header>

    <flux:card class="overflow-hidden">
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Nama</flux:table.column>
                <flux:table.column>Email</flux:table.column>
                {{-- Kita tampilkan NIM hanya jika yang dilihat adalah Mahasiswa --}}
                @if($role === 'mahasiswa')
                    <flux:table.column>NIM</flux:table.column>
                @endif
                <flux:table.column align="end">Aksi</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach($users as $user)
                    <flux:table.row>
                        <flux:table.cell class="font-medium text-zinc-800 dark:text-white">{{ $user->name }}</flux:table.cell>
                        <flux:table.cell class="text-zinc-600 dark:text-zinc-400">{{ $user->email }}</flux:table.cell>
                        
                        @if($role === 'mahasiswa')
                            <flux:table.cell class="text-zinc-600 dark:text-zinc-400">{{ $user->nim ?? '-' }}</flux:table.cell>
                        @endif

                        <flux:table.cell align="end">
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <flux:button type="submit" variant="ghost" icon="trash" color="red" size="sm" />
                            </form>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </flux:card>
</div>
@endsection