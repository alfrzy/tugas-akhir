@extends('layouts.app')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6">
    <!-- Tombol Kembali & Header -->
    <div class="space-y-4">
        <flux:button href="{{ route('dosen.results.index') }}" variant="subtle" icon="chevron-left" size="sm">
            Kembali ke Daftar
        </flux:button>

        <header class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <flux:heading size="xl" class="font-black text-indigo-600 uppercase tracking-tight">
                    Detail Peserta Ujian
                </flux:heading>
                <flux:subheading class="mt-1">
                    Ujian: <span class="font-bold text-slate-700 dark:text-zinc-300">{{ $exam->title }}</span>
                </flux:subheading>
            </div>

            <!-- Ringkasan Statistik Kecil -->
            <div class="flex gap-4">
                <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-3 rounded-2xl shadow-sm text-center min-w-[100px]">
                    <p class="text-[10px] font-black text-slate-400 uppercase">Total Soal</p>
                    <p class="text-xl font-bold text-slate-900 dark:text-white">{{ $exam->questions->count() }}</p>
                </div>
                <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-3 rounded-2xl shadow-sm text-center min-w-[100px]">
                    <p class="text-[10px] font-black text-slate-400 uppercase">Peserta</p>
                    <p class="text-xl font-bold text-emerald-600">{{ $students->count() }}</p>
                </div>
            </div>
        </header>
    </div>

    <flux:separator variant="subtle" />

    <!-- Daftar Mahasiswa -->
    <flux:card class="p-0 overflow-hidden border-slate-200 dark:border-zinc-800">
        <table class="w-full text-left">
            <thead class="bg-slate-50 dark:bg-zinc-800/50 border-b border-slate-200 dark:border-zinc-800">
                <tr>
                    <th class="py-4 px-6 text-xs font-black uppercase tracking-widest text-slate-500">Mahasiswa</th>
                    <th class="py-4 px-6 text-xs font-black uppercase tracking-widest text-slate-500">Email</th>
                    <th class="py-4 px-6 text-xs font-black uppercase tracking-widest text-slate-500 text-center">Status</th>
                    <th class="py-4 px-6 text-xs font-black uppercase tracking-widest text-slate-500 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                @forelse($students as $student)
                    <tr class="hover:bg-slate-50 dark:hover:bg-zinc-800/30 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 font-bold">
                                    {{ substr($student->name, 0, 1) }}
                                </div>
                                <span class="text-sm font-bold text-slate-800 dark:text-zinc-200">{{ $student->name }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-sm text-slate-500 font-medium">
                            {{ $student->email }}
                        </td>
                        <td class="py-4 px-6 text-center">
                            <flux:badge color="emerald" variant="pill" size="sm" class="font-bold uppercase text-[9px]">Selesai</flux:badge>
                        </td>
                        <td class="py-4 px-6 text-right">
                            {{-- Ganti rute ini nanti saat kita membuat halaman detail penilaian per mahasiswa --}}
                        <flux:button 
                            href="{{ route('dosen.results.student', ['exam' => $exam->id, 'student' => $student->id]) }}" 
                            variant="ghost" 
                            size="sm" 
                            class="font-bold text-indigo-600"
                        >
                            Periksa Jawaban
                        </flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center">
                            <p class="text-slate-400 font-medium text-sm">Belum ada mahasiswa yang menyelesaikan ujian ini.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </flux:card>
</div>
@endsection