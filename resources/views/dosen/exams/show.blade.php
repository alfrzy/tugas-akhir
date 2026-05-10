@extends('layouts.app')

@section('content')
<div class="p-6 max-w-4xl mx-auto space-y-6">
    <header class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ $exam->title }}</flux:heading>
            <flux:subheading>{{ $exam->subject->subject_name }}</flux:subheading>
        </div>
        <flux:button :href="route('dosen.exams.index')" icon="arrow-left" variant="ghost">Kembali</flux:button>
    </header>

    <div class="space-y-4">
        @foreach($exam->questions as $index => $question)
            <flux:card class="space-y-3">
                <div class="flex items-center justify-between border-b pb-2">
                    <flux:heading size="sm">Pertanyaan #{{ $index + 1 }}</flux:heading>
                </div>
                
                <flux:text class="text-zinc-800 dark:text-white font-medium">
                    {{ $question->question_text }}
                </flux:text>

                <div class="bg-zinc-50 dark:bg-zinc-900 p-4 rounded-lg border border-zinc-200 dark:border-zinc-800 mt-2">
                    <flux:text size="sm" class="font-bold text-indigo-600 mb-1 block uppercase">Kunci Jawaban Referensi:</flux:text>
                    <flux:text size="sm" class="italic italic">
                        {{ $question->key_answer }}
                    </flux:text>
                </div>
            </flux:card>
        @endforeach
    </div>
</div>
@endsection