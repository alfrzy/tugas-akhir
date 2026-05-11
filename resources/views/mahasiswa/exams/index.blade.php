@extends('layouts.app')

@section('content')
<div class="p-6 space-y-8">
    <header class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ $subject->subject_name }}</flux:heading>
            <flux:subheading>Daftar ujian BAB yang tersedia untuk Anda kerjakan.</flux:subheading>
        </div>
        <flux:button :href="route('mahasiswa.subjects.index')" variant="ghost" icon="arrow-left" wire:navigate>Kembali</flux:button>
    </header>

    {{-- ALERT ERROR JIKA DITOLAK OLEH CONTROLLER --}}
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm animate-fade-in-down">
            <flux:icon name="exclamation-circle" variant="solid" class="w-5 h-5 text-red-500" />
            <span class="font-medium text-sm">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4">
        @forelse($exams as $exam)
            @php
                // 1. CEK STATUS WAKTU UJIAN
                $sekarang = now();
                $batasToleransi = $exam->end_time ? $exam->end_time->copy()->addHours(5) : null;
                
                $belumMulai = $exam->start_time && $sekarang < $exam->start_time;
                $sudahTutup = $batasToleransi && $sekarang > $batasToleransi;
                $masaToleransi = $exam->end_time && $sekarang > $exam->end_time && !$sudahTutup;

                // 2. CEK APAKAH SUDAH DIKERJAKAN
                $submission = \App\Models\Submission::where('user_id', auth()->id())
                                                    ->where('exam_id', $exam->id)
                                                    ->first();
            @endphp

            <flux:card class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                
                {{-- Bagian Kiri: Info Ujian & Jadwal --}}
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-lg shrink-0 mt-1">
                        <flux:icon.document-text class="text-indigo-600 dark:text-indigo-300" />
                    </div>
                    <div>
                        <flux:heading size="lg">{{ $exam->title }}</flux:heading>
                        
                        <div class="flex flex-wrap gap-4 mt-2">
                            <flux:text size="sm" class="flex items-center gap-1 font-medium">
                                <flux:icon.clock variant="mini" class="text-slate-400" /> {{ $exam->duration }} Menit
                            </flux:text>
                            <flux:text size="sm" class="flex items-center gap-1 font-medium">
                                <flux:icon.list-bullet variant="mini" class="text-slate-400" /> {{ $exam->questions_count }} Soal Esai
                            </flux:text>
                        </div>

                        {{-- Tampilan Jadwal Ujian --}}
                        <div class="mt-3 p-3 bg-slate-50 dark:bg-slate-800/50 rounded-lg border border-slate-100 dark:border-slate-700 space-y-1">
                            <div class="flex items-center gap-2 text-xs text-slate-600 dark:text-slate-400">
                                <flux:icon name="play-circle" variant="micro" class="text-emerald-500" />
                                <span>Mulai: <strong>{{ $exam->start_time ? $exam->start_time->format('d M Y, H:i') : 'Belum diatur' }}</strong></span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-slate-600 dark:text-slate-400">
                                <flux:icon name="stop-circle" variant="micro" class="text-red-500" />
                                <span>Tutup: <strong>{{ $exam->end_time ? $exam->end_time->format('d M Y, H:i') : 'Belum diatur' }}</strong></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bagian Kanan: Aksi / Tombol --}}
                <div class="flex flex-col items-end gap-3 shrink-0">
                    
                    @if($submission)
                        {{-- Skenario A: Sudah Selesai Dikerjakan --}}
                        <div class="flex items-center gap-3">
                            <flux:badge color="green" variant="subtle" icon="check-circle">Selesai</flux:badge>
                            
                            @if($submission->is_published)
                                <flux:button size="sm" variant="ghost" icon="chart-bar" :href="route('mahasiswa.exams.result', $exam->id)" wire:navigate>
                                    Lihat Nilai
                                </flux:button>
                            @else
                                <flux:badge color="zinc" variant="subtle" icon="clock">Proses Penilaian</flux:badge>
                            @endif
                        </div>

                    @elseif($belumMulai)
                        {{-- Skenario B: Belum Waktunya Mulai --}}
                        <flux:badge color="zinc" icon="lock-closed" size="lg">Belum Terbuka</flux:badge>
                        <span class="text-[10px] text-slate-400 uppercase tracking-widest font-bold">Tunggu Jadwal</span>

                    @elseif($sudahTutup)
                        {{-- Skenario C: Sudah Lewat Toleransi (Tutup Total) --}}
                        <flux:badge color="red" icon="x-circle" size="lg">Ujian Ditutup</flux:badge>
                        <span class="text-[10px] text-red-400 uppercase tracking-widest font-bold">Batas Waktu Habis</span>

                    @else
                        {{-- Skenario D: Aktif atau Masa Toleransi (Bisa Dikerjakan) --}}
                        @if($masaToleransi)
                            <div class="text-right mb-1 animate-pulse">
                                <span class="text-xs text-amber-600 font-bold block">⚠️ Masa Toleransi</span>
                                <span class="text-[10px] text-amber-500 block">Denda nilai -10% per jam</span>
                            </div>
                        @else
                            <flux:badge color="emerald" variant="subtle" class="mb-2">Sedang Berlangsung</flux:badge>
                        @endif

                        <flux:button :href="route('mahasiswa.exams.take', $exam->id)" variant="primary" icon="pencil-square" wire:navigate class="{{ $masaToleransi ? 'bg-amber-500 hover:bg-amber-600' : 'bg-blue-600 hover:bg-blue-700' }}">
                            Kerjakan Ujian
                        </flux:button>
                    @endif

                </div>
            </flux:card>
        @empty
            <flux:card class="p-10 text-center">
                <flux:text>Belum ada ujian yang diterbitkan untuk mata kuliah ini.</flux:text>
            </flux:card>
        @endforelse
    </div>
</div>
@endsection