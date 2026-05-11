@extends('layouts.app')

@section('content')
<div class="p-6 max-w-4xl mx-auto space-y-8" x-data="{ 
    questions: [
        { id: Date.now(), text: '', key: '' }
    ],
    addQuestion() {
        this.questions.push({ id: Date.now(), text: '', key: '' });
    },
    removeQuestion(index) {
        if(this.questions.length > 1) {
            this.questions.splice(index, 1);
        }
    }
}">

    {{-- ALERT PESAN SUKSES --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm animate-fade-in-down">
            <flux:icon name="check-circle" variant="solid" class="w-5 h-5 text-emerald-500" />
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
    @endif

    {{-- ALERT PESAN ERROR (Validasi Form) --}}
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-start gap-3 shadow-sm animate-fade-in-down">
            <flux:icon name="exclamation-circle" variant="solid" class="w-5 h-5 text-red-500 shrink-0 mt-0.5" />
            <div class="text-sm font-medium">
                <p class="font-bold mb-1">Terdapat kesalahan pengisian:</p>
                <ul class="list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <header>
        <flux:heading size="xl">Buat Ujian Baru: {{ $subject->subject_name }}</flux:heading>
        <flux:subheading>Masukkan judul BAB dan tambahkan butir-butir soal esai.</flux:subheading>
    </header>

    <form action="{{ route('dosen.exams.store') }}" method="POST">
        @csrf
        <input type="hidden" name="subject_id" value="{{ $subject->id }}">

        <div class="space-y-6">
            {{-- Informasi Ujian --}}
            <flux:card>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input 
                        label="Judul Materi / BAB" 
                        name="title" 
                        placeholder="Contoh: BAB 1 Dasar Pemrograman" 
                        required 
                    />
                    <flux:input 
                        type="number"
                        label="Durasi Ujian (Menit)" 
                        name="duration" 
                        placeholder="Contoh: 60" 
                        suffix="Menit"
                        required 
                    />

                    <flux:input type="datetime-local" label="Waktu Mulai" name="start_time" required />
                    <flux:input type="datetime-local" label="Batas Akhir (Deadline)" name="end_time" required />
                </div>
            </flux:card>
            <div class="flex items-center justify-between">
                <flux:heading level="3" size="lg">Butir Soal Esai</flux:heading>
                <flux:button type="button" @click="addQuestion()" variant="subtle" icon="plus" size="sm">
                    Tambah Soal
                </flux:button>
            </div>
            
            {{-- Loop Input Soal --}}
            <template x-for="(question, index) in questions" :key="question.id">
                <flux:card class="space-y-4 relative">
                    <div class="flex justify-between items-center border-b pb-4 mb-4 border-slate-100 dark:border-slate-700">
                        <flux:heading size="sm">Soal #<span x-text="index + 1"></span></flux:heading>
                        <flux:button type="button" @click="removeQuestion(index)" variant="ghost" color="red" icon="trash" size="sm" x-show="questions.length > 1" />
                    </div>

                    <flux:textarea 
                        label="Pertanyaan" 
                        ::name="'questions['+index+'][text]'" 
                        placeholder="Masukkan pertanyaan esai..." 
                        rows="3"
                        required
                    />

                    <flux:textarea 
                        label="Kunci Jawaban (Referensi)" 
                        ::name="'questions['+index+'][key]'" 
                        placeholder="Jawaban acuan penilaian..." 
                        rows="4"
                        required
                    />
                </flux:card>
            </template>

            <div class="flex gap-2 pt-4">
                <flux:button type="submit" variant="primary" icon="paper-airplane" class="bg-blue-600 hover:bg-blue-700">Terbitkan Ujian (BAB)</flux:button>
                <flux:button :href="route('dosen.exams.index')" variant="ghost">Batal</flux:button>
            </div>
        </div>
    </form>
</div>
@endsection