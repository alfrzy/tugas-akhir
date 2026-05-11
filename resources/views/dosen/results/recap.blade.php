@extends('layouts.app')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-10">
    <header class="flex items-center justify-between">
        <div>
            <flux:heading size="xl" class="font-bold">Rekap Nilai Keseluruhan</flux:heading>
            <flux:subheading>Pantau peringkat mahasiswa dan status pengerjaan ujian secara lengkap.</flux:subheading>
        </div>
        <flux:button icon="printer" variant="outline" onclick="window.print()">Cetak Rekap</flux:button>
    </header>

    @forelse($subjects as $subject)
        <div class="space-y-6">
            {{-- Nama Mata Kuliah --}}
            <div class="flex items-center gap-3 border-b-2 border-indigo-500 pb-2">
                <flux:heading size="lg" class="text-indigo-600 dark:text-indigo-400 font-black uppercase">{{ $subject->subject_name }}</flux:heading>
                <flux:badge color="zinc" size="sm" variant="subtle">{{ $subject->subject_code }}</flux:badge>
            </div>

            @forelse($subject->exams as $exam)
                <div class="ml-4 space-y-3">
                    {{-- Nama Ujian --}}
                    <div class="flex items-center justify-between bg-slate-100 dark:bg-slate-800 p-3 rounded-xl">
                        <flux:heading size="md" class="font-bold text-slate-700 dark:text-slate-200 italic">Ujian: {{ $exam->title }}</flux:heading>
                        <flux:text size="sm">Total Peserta: {{ $subject->students->count() }} Mahasiswa</flux:text>
                    </div>

                    {{-- Tabel Nilai Mahasiswa --}}
                    <flux:card class="p-0 overflow-hidden shadow-sm">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 dark:bg-zinc-900">
                                <tr>
                                    <th class="py-3 px-6 text-xs font-black uppercase text-slate-500">Peringkat</th>
                                    <th class="py-3 px-6 text-xs font-black uppercase text-slate-500">Mahasiswa</th>
                                    <th class="py-3 px-6 text-xs font-black uppercase text-slate-500 text-center">Nilai</th>
                                    <th class="py-3 px-6 text-xs font-black uppercase text-slate-500 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                                @php
                                    // Logika: Ambil semua mahasiswa di subject, lalu cek submissionnya untuk exam ini
                                    $allData = $subject->students->map(function($student) use ($exam) {
                                        $submission = $exam->submissions->where('user_id', $student->id)->first();
                                        return [
                                            'student' => $student,
                                            'score' => $submission ? $submission->total_score : -1, // -1 untuk yang belum mengerjakan
                                            'submission' => $submission
                                        ];
                                    })->sortByDesc('score'); // Urutkan dari nilai tertinggi
                                    
                                    $rank = 1;
                                @endphp

                                @foreach($allData as $data)
                                    <tr class="{{ $data['score'] == -1 ? 'bg-red-50/30 dark:bg-red-900/10' : '' }}">
                                        {{-- Peringkat --}}
                                        <td class="py-4 px-6 text-sm font-bold text-slate-400">
                                            {{ $data['score'] != -1 ? '#'.$rank++ : '-' }}
                                        </td>

                                        {{-- Info Mahasiswa --}}
                                        <td class="py-4 px-6">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-bold text-slate-800 dark:text-zinc-200">{{ $data['student']->name }}</span>
                                                <span class="text-xs text-slate-500">{{ $data['student']->nim }}</span>
                                            </div>
                                        </td>

                                        {{-- Nilai --}}
                                        <td class="py-4 px-6 text-center">
                                            @if($data['score'] != -1)
                                                @php
                                                    $color = $data['score'] >= 80 ? 'green' : ($data['score'] >= 60 ? 'amber' : 'red');
                                                @endphp
                                                <flux:badge color="{{ $color }}" size="lg" class="font-black">
                                                    {{ number_format($data['score'], 1) }}
                                                </flux:badge>
                                            @else
                                                <span class="text-slate-300 font-bold">0.0</span>
                                            @endif
                                        </td>

                                        {{-- Status --}}
                                        <td class="py-4 px-6 text-right">
                                            @if($data['score'] != -1)
                                                <flux:badge color="emerald" variant="subtle" size="sm">Tuntas</flux:badge>
                                            @else
                                                <flux:badge color="red" variant="solid" size="sm">Belum Mengerjakan</flux:badge>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </flux:card>
                </div>
            @empty
                <div class="ml-4 p-4 border border-dashed border-slate-200 rounded-xl text-center text-slate-400 text-sm">
                    Belum ada ujian dibuat untuk mata kuliah ini.
                </div>
            @endforelse
        </div>
    @empty
        <flux:card class="py-20 text-center">
            <flux:text>Anda belum memiliki data mata kuliah.</flux:text>
        </flux:card>
    @endforelse
</div>
@endsection