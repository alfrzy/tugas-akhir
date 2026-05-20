@extends('layouts.app')

@section('content')
<div class="p-6 max-w-4xl mx-auto space-y-8" x-data="examForm()">

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

    <form action="{{ route('dosen.exams.store') }}" method="POST" enctype="multipart/form-data">
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
            <div class="flex items-center justify-between mt-8">
                <flux:heading level="3" size="lg">Upload Soal Ujian (CSV)</flux:heading>
                <flux:button href="{{ route('dosen.exams.template') }}" variant="outline" icon="arrow-down-tray" size="sm" class="!border-indigo-200 !text-indigo-600">
                    Download Template
                </flux:button>
            </div>
            <flux:card class="border-indigo-100 bg-indigo-50/30">
                <flux:text class="mb-4 text-sm text-slate-600">Anda dapat mengunggah soal secara massal menggunakan file berformat CSV. Kosongkan input ini jika hanya ingin mengetik soal secara manual.</flux:text>
                <input type="file" @change="handleFileUpload" accept=".csv,.txt" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer transition-colors" />
            </flux:card>

            <div class="flex items-center justify-between mt-8">
                <flux:heading level="3" size="lg">Atau Ketik Manual</flux:heading>
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
                        x-model="question.text"
                        placeholder="Masukkan pertanyaan esai... (Opsional jika upload CSV)" 
                        rows="3"
                    />

                    <flux:textarea 
                        label="Kunci Jawaban (Referensi)" 
                        ::name="'questions['+index+'][key]'" 
                        x-model="question.key"
                        placeholder="Jawaban acuan penilaian... (Opsional jika upload CSV)" 
                        rows="4"
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

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('examForm', () => ({
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
            },
            handleFileUpload(event) {
                const file = event.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = (e) => {
                    const text = e.target.result;
                    
                    // Hapus pertanyaan manual yang kosong jika hanya ada 1
                    if(this.questions.length === 1 && this.questions[0].text.trim() === '' && this.questions[0].key.trim() === '') {
                        this.questions = [];
                    }
                    
                    // Pisahkan teks berdasarkan baris (newline)
                    const lines = text.split(/\r?\n/);
                    for(let i = 0; i < lines.length; i++) {
                        const line = lines[i];
                        if (!line.trim()) continue;
                        
                        // Lewati baris pertama jika terdeteksi sebagai header
                        if (i === 0 && line.toLowerCase().includes('pertanyaan')) continue;
                        
                        // Pisahkan kolom berdasarkan koma, abaikan koma yang ada di dalam tanda kutip
                        const cols = line.split(/,(?=(?:(?:[^"]*"){2})*[^"]*$)/);
                        
                        if (cols.length >= 2) {
                            let qText = cols[0].replace(/^"|"$/g, '').replace(/""/g, '"').trim();
                            let qKey = cols[1].replace(/^"|"$/g, '').replace(/""/g, '"').trim();
                            
                            if(qText && qKey) {
                                this.questions.push({
                                    id: Date.now() + i,
                                    text: qText,
                                    key: qKey
                                });
                            }
                        }
                    }
                    // Kosongkan value input agar user tidak mengirim file secara double, cukup data array saja
                    event.target.value = '';
                };
                reader.readAsText(file);
            }
        }));
    });
</script>
@endsection