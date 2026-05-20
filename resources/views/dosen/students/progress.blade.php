@extends('layouts.app')

@section('content')
<div class="p-6 max-w-5xl mx-auto space-y-6">
    <header class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <flux:button href="{{ route('dosen.students.index') }}" variant="subtle" icon="chevron-left" size="sm">
                Kembali
            </flux:button>
            <div>
                <flux:heading size="xl" class="font-bold">Progress Mahasiswa</flux:heading>
                <flux:subheading>Detail riwayat ujian dan nilai akhir.</flux:subheading>
            </div>
        </div>
    </header>

    <!-- Header Profil Singkat -->
    <div class="bg-white dark:bg-zinc-900 p-6 rounded-3xl border border-slate-200 dark:border-zinc-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="h-16 w-16 rounded-2xl bg-indigo-600 flex items-center justify-center text-white text-3xl font-black">
                {{ substr($student->name, 0, 1) }}
            </div>
            <div>
                <flux:heading size="xl" class="font-black tracking-tight">{{ $student->name }}</flux:heading>
                <flux:subheading>{{ $student->nim }}</flux:subheading>
            </div>
        </div>
        <div class="text-right">
            <p class="text-xs font-black uppercase text-slate-400 tracking-widest">Mata Kuliah</p>
            <p class="text-xl font-bold text-indigo-600 dark:text-indigo-400">{{ $subject->subject_name }}</p>
        </div>
    </div>

    <!-- Riwayat Ujian -->
    <flux:card class="p-0 overflow-hidden shadow-sm">
        <div class="p-4 bg-slate-50 dark:bg-zinc-900 border-b border-slate-100 dark:border-zinc-800">
            <flux:heading size="lg" class="font-bold text-slate-700 dark:text-slate-300">Riwayat Ujian</flux:heading>
        </div>
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-100 dark:bg-zinc-800/50">
                <tr>
                    <th class="py-3 px-6 text-xs font-black uppercase text-slate-500">Nama Ujian</th>
                    <th class="py-3 px-6 text-xs font-black uppercase text-slate-500">Batas Waktu</th>
                    <th class="py-3 px-6 text-xs font-black uppercase text-slate-500 text-center">Skor Akhir</th>
                    <th class="py-3 px-6 text-xs font-black uppercase text-slate-500 text-right">Status Publish</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                @forelse($exams as $exam)
                    @php
                        $submission = $submissions->get($exam->id);
                        $score = $submission ? $submission->total_score : null;
                    @endphp
                    <tr class="hover:bg-slate-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <td class="py-4 px-6 font-medium text-slate-800 dark:text-slate-200">
                            {{ $exam->title }}
                        </td>
                        <td class="py-4 px-6 text-sm text-slate-600 dark:text-slate-400">
                            {{ $exam->end_time ? \Carbon\Carbon::parse($exam->end_time)->translatedFormat('d M Y, H:i') : 'Tidak Ada Batas Waktu' }}
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if($score !== null)
                                @php
                                    $color = $score >= 80 ? 'green' : ($score >= 60 ? 'amber' : 'red');
                                @endphp
                                <flux:badge color="{{ $color }}" size="lg" class="font-black">
                                    {{ number_format($score, 1) }}
                                </flux:badge>
                            @else
                                <flux:badge color="zinc" variant="solid" size="sm">Belum Dikerjakan</flux:badge>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right">
                            @if($submission)
                                @if($submission->is_published)
                                    <flux:badge color="indigo" variant="subtle" size="sm" icon="check-badge">Dipublish</flux:badge>
                                @else
                                    <flux:badge color="amber" variant="subtle" size="sm" icon="clock">Belum Publish</flux:badge>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-slate-400 italic">
                            Belum ada ujian pada mata kuliah ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </flux:card>
</div>
@endsection
