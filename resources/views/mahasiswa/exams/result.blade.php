@extends('layouts.app')

@section('content')
@php
    // KITA AMBIL NILAI AKHIR LANGSUNG DARI TABEL SUBMISSIONS
    $submission = \App\Models\Submission::where('user_id', auth()->id())
                            ->where('exam_id', $exam->id)
                            ->first();
                            
    // Fallback yang aman jika data tidak ditemukan
    $finalScore = $submission ? $submission->total_score : 0;
@endphp

<div class="p-6 max-w-4xl mx-auto space-y-8">
    <header class="text-center space-y-2">
        <flux:heading size="xl">Hasil Ujian: {{ $exam->title }}</flux:heading>
        <flux:subheading>{{ $exam->subject->subject_name }}</flux:subheading>
    </header>

    {{-- Ringkasan Skor Utama (Membaca dari Submission) --}}
    <flux:card class="flex flex-col items-center justify-center p-8 border-t-4 border-indigo-500">
        <flux:text class="uppercase tracking-widest text-zinc-500 font-bold">Skor Akhir Anda</flux:text>
        <div class="text-6xl font-black text-indigo-600 my-4">
            {{ number_format($finalScore, 1) }}
        </div>
        <flux:badge color="{{ $finalScore >= 70 ? 'green' : 'orange' }}" size="lg">
            {{ $finalScore >= 70 ? 'Lulus Kompetensi' : 'Perlu Belajar Lagi' }}
        </flux:badge>
    </flux:card>

    <div class="space-y-6">
        <flux:heading level="3" size="lg">Analisis Jawaban Per Soal</flux:heading>

        @foreach($answers as $index => $answer)
            <flux:card class="space-y-4">
                <div class="flex justify-between items-center border-b pb-4">
                    <flux:heading size="sm">Soal #{{ $index + 1 }}</flux:heading>
                    <flux:badge variant="subtle" color="indigo">Kemiripan: {{ number_format($answer->score, 1) }}%</flux:badge>
                </div>

                <div class="space-y-4">
                    <div>
                        <flux:text size="sm" class="font-bold text-zinc-500 uppercase">Pertanyaan:</flux:text>
                        <flux:text class="text-zinc-800 dark:text-zinc-200">{{ $answer->question->question_text }}</flux:text>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 bg-zinc-50 dark:bg-zinc-900 rounded-lg border border-zinc-200 dark:border-zinc-800">
                            <flux:text size="xs" class="font-bold text-green-600 uppercase mb-2 block">Kunci Jawaban (Referensi):</flux:text>
                            <flux:text size="sm" class="italic">{{ $answer->question->key_answer }}</flux:text>
                        </div>
                        <div class="p-4 bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700">
                            <flux:text size="xs" class="font-bold text-indigo-600 uppercase mb-2 block">Jawaban Anda:</flux:text>
                            <flux:text size="sm">{{ $answer->answer_text }}</flux:text>
                        </div>
                    </div>
                </div>
            </flux:card>
        @endforeach
    </div>

    <div class="flex justify-center">
        <flux:button :href="route('mahasiswa.subjects.index')" variant="primary">Kembali ke Dashboard</flux:button>
    </div>
</div>
@endsection