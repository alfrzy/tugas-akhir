@extends('layouts.app')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-8">
    <header>
        <flux:heading size="xl" class="font-bold">Daftar Mahasiswa</flux:heading>
        <flux:subheading>Mahasiswa yang mengambil mata kuliah Anda.</flux:subheading>
    </header>

    {{-- ALERT PESAN SUKSES --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm mb-6">
            <flux:icon name="check-circle" variant="solid" class="w-5 h-5 text-emerald-500" />
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
    @endif

    {{-- ALERT PESAN ERROR --}}
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-start gap-3 shadow-sm mb-6">
            <flux:icon name="exclamation-circle" variant="solid" class="w-5 h-5 text-red-500 shrink-0 mt-0.5" />
            <span class="font-medium text-sm">{{ session('error') }}</span>
        </div>
    @endif

    @forelse($subjects as $subject)
        <div class="space-y-4">
            <div class="flex items-center gap-3 border-b-2 border-indigo-500 pb-2">
                <flux:heading size="lg" class="text-indigo-600 dark:text-indigo-400 font-black uppercase">{{ $subject->subject_name }}</flux:heading>
                <flux:badge color="zinc" size="sm" variant="subtle">{{ $subject->subject_code }}</flux:badge>
            </div>

            <flux:card class="p-0 overflow-hidden shadow-sm">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 dark:bg-zinc-900">
                        <tr>
                            <th class="py-3 px-6 text-xs font-black uppercase text-slate-500">No</th>
                            <th class="py-3 px-6 text-xs font-black uppercase text-slate-500">Mahasiswa</th>
                            <th class="py-3 px-6 text-xs font-black uppercase text-slate-500 text-center">NIM</th>
                            <th class="py-3 px-6 text-xs font-black uppercase text-slate-500 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                        @forelse($subject->students as $index => $student)
                            <tr class="hover:bg-slate-50 dark:hover:bg-zinc-800/50 transition-colors">
                                <td class="py-4 px-6 text-sm text-slate-400">
                                    {{ $index + 1 }}
                                </td>
                                <td class="py-4 px-6 font-medium text-slate-800 dark:text-slate-200">
                                    {{ $student->name }}
                                </td>
                                <td class="py-4 px-6 text-center text-slate-600 dark:text-slate-400">
                                    {{ $student->nim }}
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex justify-end gap-2">
                                        <flux:button href="{{ route('dosen.students.progress', ['subject' => $subject->id, 'student' => $student->id]) }}" variant="subtle" size="sm" icon="chart-bar" color="indigo">
                                            Lihat Progress
                                        </flux:button>
                                        <form action="{{ route('dosen.students.remove', ['subject' => $subject->id, 'student' => $student->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengeluarkan mahasiswa ini dari kelas?');">
                                            @csrf
                                            @method('DELETE')
                                            <flux:button type="submit" variant="subtle" size="sm" icon="trash" color="red" class="!text-red-500 hover:!bg-red-50">
                                                Keluarkan
                                            </flux:button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-400 italic">
                                    Belum ada mahasiswa yang tergabung di kelas ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </flux:card>
        </div>
    @empty
        <flux:card class="py-20 text-center">
            <flux:text>Anda belum memiliki data mata kuliah.</flux:text>
        </flux:card>
    @endforelse
</div>
@endsection