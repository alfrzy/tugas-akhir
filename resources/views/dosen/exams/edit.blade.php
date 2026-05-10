@extends('layouts.app')

@section('content')
<div class="p-6 max-w-4xl mx-auto space-y-8" x-data="{ 
    questions: {{ $exam->questions->map(fn($q) => ['id' => $q->id, 'text' => $q->question_text, 'key' => $q->key_answer])->toJson() }},
    addQuestion() {
        this.questions.push({ id: Date.now(), text: '', key: '' });
    },
    removeQuestion(index) {
        if(this.questions.length > 1) this.questions.splice(index, 1);
    }
}">
    <header>
        <flux:heading size="xl">Edit Ujian: {{ $exam->title }}</flux:heading>
        <flux:subheading>{{ $exam->subject->subject_name }}</flux:subheading>
    </header>

    <form action="{{ route('dosen.exams.update', $exam->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <flux:card>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input label="Judul Materi / BAB" name="title" value="{{ $exam->title }}" required />
                    <flux:input type="number" label="Durasi (Menit)" name="duration" value="{{ $exam->duration }}" required />
                </div>
            </flux:card>

            <div class="flex items-center justify-between">
                <flux:heading level="3" size="lg">Butir Soal Esai</flux:heading>
                <flux:button type="button" @click="addQuestion()" variant="subtle" icon="plus" size="sm">Tambah Soal</flux:button>
            </div>
            
            <template x-for="(question, index) in questions" :key="question.id">
                <flux:card class="space-y-4">
                    <div class="flex justify-between items-center border-b pb-4">
                        <flux:heading size="sm">Soal #<span x-text="index + 1"></span></flux:heading>
                        <flux:button type="button" @click="removeQuestion(index)" variant="ghost" color="red" icon="trash" size="sm" x-show="questions.length > 1" />
                    </div>

                    <flux:textarea label="Pertanyaan" ::name="'questions['+index+'][text]'" x-model="question.text" required />
                    <flux:textarea label="Kunci Jawaban" ::name="'questions['+index+'][key]'" x-model="question.key" required />
                </flux:card>
            </template>

            <div class="flex gap-2">
                <flux:button type="submit" variant="primary">Simpan Perubahan</flux:button>
                <flux:button :href="route('dosen.exams.index')" variant="ghost">Batal</flux:button>
            </div>
        </div>
    </form>
</div>
@endsection