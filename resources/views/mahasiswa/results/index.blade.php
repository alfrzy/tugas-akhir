@extends('layouts.app')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-8">
    
    <header>
        <flux:heading size="xl" class="font-bold">Riwayat Nilai Akademik</flux:heading>
        <flux:subheading>Pantau hasil evaluasi dan perkembangan belajar Anda di sini.</flux:subheading>
    </header>

    {{-- Banner Statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <flux:card class="bg-gradient-to-br from-blue-600 to-indigo-700 text-white border-none shadow-lg shadow-blue-500/30">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-white/20 rounded-xl">
                    <flux:icon name="chart-bar-square" variant="solid" class="w-8 h-8 text-white" />
                </div>
                <div>
                    <p class="text-blue-100 text-sm font-medium uppercase tracking-wider">Rata-rata Keseluruhan</p>
                    <h2 class="text-4xl font-black mt-1">
                        {{ $averageOverall ? number_format($averageOverall, 1) : '0.0' }}<span class="text-xl text-blue-200">/100</span>
                    </h2>
                </div>
            </div>
        </flux:card>

        <flux:card class="flex items-center gap-4 shadow-sm">
            <div class="p-3 bg-emerald-100 text-emerald-600 rounded-xl">
                <flux:icon name="document-check" variant="solid" class="w-8 h-8" />
            </div>
            <div>
                <p class="text-slate-500 text-sm font-medium uppercase tracking-wider">Total Ujian Selesai</p>
                <h2 class="text-3xl font-black text-slate-800">{{ $submissions->count() }} <span class="text-lg text-slate-500 font-medium">Ujian</span></h2>
            </div>
        </flux:card>
    </div>

    {{-- Tabel Riwayat --}}
    <flux:card class="p-0 overflow-hidden shadow-sm border-slate-200 dark:border-slate-800">
        <div class="overflow-x-auto">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column class="pl-6">Mata Kuliah</flux:table.column>
                    <flux:table.column>Ujian</flux:table.column>
                    <flux:table.column>Waktu Selesai</flux:table.column>
                    <flux:table.column align="center">Nilai Akhir</flux:table.column>
                    <flux:table.column align="end" class="pr-6">Aksi</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($submissions as $submission)
                        <flux:table.row class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <flux:table.cell class="pl-6 whitespace-normal max-w-xs">
                                <span class="block font-bold text-slate-800 dark:text-slate-200">{{ $submission->exam->subject->subject_name }}</span>
                                <span class="text-xs text-slate-500">{{ $submission->exam->subject->subject_code }}</span>
                            </flux:table.cell>
                            
                            <flux:table.cell class="whitespace-normal max-w-xs">
                                <span class="block font-medium">{{ $submission->exam->title }}</span>
                            </flux:table.cell>
                            
                            <flux:table.cell class="text-slate-500 text-sm">
                                {{ $submission->finished_at ? \Carbon\Carbon::parse($submission->finished_at)->format('d M Y, H:i') : '-' }}
                            </flux:table.cell>
                            
                            <flux:table.cell align="center">
                                @if($submission->is_published)
                                    @php
                                        // Mewarnai badge berdasarkan nilai
                                        $scoreColor = $submission->total_score >= 80 ? 'green' : ($submission->total_score >= 60 ? 'amber' : 'red');
                                    @endphp
                                    <flux:badge color="{{ $scoreColor }}" size="lg" class="font-bold">
                                        {{ number_format($submission->total_score, 1) }}
                                    </flux:badge>
                                @else
                                    <flux:badge color="zinc" variant="subtle" icon="clock">Proses Penilaian</flux:badge>
                                @endif
                            </flux:table.cell>
                            
                            <flux:table.cell align="end" class="pr-6">
                                @if($submission->is_published)
                                    <flux:button 
                                        variant="ghost" 
                                        size="sm" 
                                        icon="document-magnifying-glass" 
                                        class="text-blue-600 hover:bg-blue-50"
                                        :href="route('mahasiswa.exams.result', $submission->exam_id)" 
                                        wire:navigate
                                    >
                                        Lihat Detail
                                    </flux:button>
                                @else
                                    <span class="text-xs text-slate-400 italic">Menunggu Dosen</span>
                                @endif
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <flux:icon name="document-text" class="w-12 h-12 mb-3 opacity-50" />
                                    <p class="text-slate-500 font-medium">Belum ada ujian yang dikerjakan.</p>
                                    <p class="text-sm mt-1">Riwayat nilai akan muncul setelah Anda menyelesaikan ujian.</p>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>
    </flux:card>

</div>
@endsection