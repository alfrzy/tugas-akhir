@extends('layouts.app')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-8">
    <header class="flex items-center justify-between">
        <div>
            <flux:heading size="xl" class="font-bold">Rekapitulasi Hasil Ujian</flux:heading>
            <flux:subheading>Pantau partisipasi mahasiswa pada setiap ujian yang Anda ampu.</flux:subheading>
        </div>
    </header>

    <flux:separator variant="subtle" />

    @forelse($subjects as $subject)
        <div class="space-y-4">
            {{-- Header Mata Kuliah --}}
            <div class="flex items-center justify-between px-2">
                <div>
                    <div class="flex items-center gap-3">
                        <flux:heading size="lg" class="text-indigo-600 dark:text-indigo-400">{{ $subject->subject_name }}</flux:heading>
                        <flux:badge color="zinc" size="sm">{{ $subject->subject_code }}</flux:badge>
                    </div>
                    <flux:text size="sm" class="mt-1 text-slate-500">
                        Total Peserta Kelas: <strong>{{ $subject->students_count }} Mahasiswa</strong>
                    </flux:text>
                </div>
            </div>

            {{-- Tabel Ujian untuk Mata Kuliah ini --}}
            <flux:card class="overflow-hidden p-0 border-slate-200 dark:border-zinc-800">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 dark:bg-zinc-800/50">
                            <tr>
                                <th class="py-3 px-6 text-xs font-black uppercase tracking-widest text-slate-500 border-b border-slate-200 dark:border-zinc-800">
                                    Judul Ujian
                                </th>
                                <th class="py-3 px-6 text-xs font-black uppercase tracking-widest text-slate-500 border-b border-slate-200 dark:border-zinc-800 text-center">
                                    Selesai Mengerjakan
                                </th>
                                <th class="py-3 px-6 text-xs font-black uppercase tracking-widest text-slate-500 border-b border-slate-200 dark:border-zinc-800 text-center">
                                    Belum Mengerjakan
                                </th>
                                <th class="py-3 px-6 text-xs font-black uppercase tracking-widest text-slate-500 border-b border-slate-200 dark:border-zinc-800 text-right">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                            @forelse($subject->exams as $exam)
                                @php
                                    // Hitung yang belum mengerjakan: Total Mahasiswa - Yang Sudah Submit
                                    $belumMengerjakan = max(0, $subject->students_count - $exam->submissions_count);
                                @endphp
                                <tr class="hover:bg-slate-50 dark:hover:bg-zinc-800/30 transition-colors duration-200">
                                    
                                    <td class="py-4 px-6">
                                        <span class="text-sm font-bold text-slate-800 dark:text-zinc-200">
                                            {{ $exam->title }}
                                        </span>
                                    </td>

                                    <td class="py-4 px-6 text-center">
                                        <flux:badge color="{{ $exam->submissions_count > 0 ? 'emerald' : 'zinc' }}" variant="subtle" size="sm" class="font-bold">
                                            {{ $exam->submissions_count }} Mahasiswa
                                        </flux:badge>
                                    </td>

                                    <td class="py-4 px-6 text-center">
                                        @if($belumMengerjakan > 0)
                                            <flux:badge color="red" variant="subtle" size="sm" class="font-bold">
                                                {{ $belumMengerjakan }} Mahasiswa
                                            </flux:badge>
                                        @else
                                            <flux:badge color="emerald" variant="solid" size="sm" icon="check" class="font-bold">
                                                Tuntas Semua
                                            </flux:badge>
                                        @endif
                                    </td>

                                    <td class="py-4 px-6 text-right">
                                        <flux:button 
                                            href="{{ route('dosen.results.show', $exam->id) }}" 
                                            variant="ghost" 
                                            size="sm" 
                                            icon-trailing="chevron-right"
                                            class="text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50"
                                            wire:navigate
                                        >
                                            Lihat Detail
                                        </flux:button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center">
                                        <flux:text size="sm" class="text-slate-400">Belum ada ujian di mata kuliah ini.</flux:text>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </flux:card>
        </div>
    @empty
        <flux:card class="py-16 text-center border-dashed border-2 shadow-none">
            <flux:icon name="book-open" class="mx-auto h-12 w-12 text-slate-300 mb-4" />
            <flux:heading size="md" class="text-slate-500">Anda belum memiliki mata kuliah aktif.</flux:heading>
        </flux:card>
    @endforelse
</div>
@endsection