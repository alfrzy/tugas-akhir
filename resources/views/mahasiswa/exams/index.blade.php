@extends('layouts.app')

@section('content')
<div class="p-6 space-y-8">
    <header class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ $subject->subject_name }}</flux:heading>
            <flux:subheading>Daftar ujian BAB yang tersedia untuk Anda kerjakan.</flux:subheading>
        </div>
        <flux:button :href="route('mahasiswa.subjects.index')" variant="ghost" icon="arrow-left">Kembali</flux:button>
    </header>

    <div class="grid grid-cols-1 gap-4">
        @forelse($exams as $exam)
            <flux:card class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-lg">
                        <flux:icon.document-text class="text-indigo-600 dark:text-indigo-300" />
                    </div>
                    <div>
                        <flux:heading size="lg">{{ $exam->title }}</flux:heading>
                        <div class="flex gap-4 mt-1">
                            <flux:text size="sm" class="flex items-center gap-1">
                                <flux:icon.clock variant="mini" /> {{ $exam->duration }} Menit
                            </flux:text>
                            <flux:text size="sm" class="flex items-center gap-1">
                                <flux:icon.list-bullet variant="mini" /> {{ $exam->questions_count }} Soal Esai
                            </flux:text>
                        </div>
                    </div>
                </div>

                <div>
                    @php
                        // MENGUBAH PENCARIAN DARI ANSWER MENJADI SUBMISSION
                        $submission = \App\Models\Submission::where('user_id', auth()->id())
                                                ->where('exam_id', $exam->id)
                                                ->first();
                    @endphp

                    @if($submission)
                        <div class="flex items-center gap-3">
                            {{-- Status Selesai tetap muncul --}}
                            <flux:badge color="green" variant="subtle" icon="check-circle">Selesai</flux:badge>
                            
                            {{-- Cek apakah Dosen sudah mem-publish nilai di tabel submission --}}
                            @if($submission->is_published)
                                <flux:button 
                                    size="sm" 
                                    variant="ghost" 
                                    icon="chart-bar" 
                                    :href="route('mahasiswa.exams.result', $exam->id)"
                                    wire:navigate
                                >
                                    Lihat Nilai
                                </flux:button>
                            @else
                                <flux:badge color="zinc" variant="subtle" icon="clock">Proses Penilaian</flux:badge>
                            @endif
                        </div>
                    @else
                        {{-- Jika belum mengerjakan --}}
                        <flux:button 
                            :href="route('mahasiswa.exams.take', $exam->id)" 
                            variant="primary" 
                            icon="pencil-square" 
                            wire:navigate
                        >
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