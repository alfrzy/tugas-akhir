@extends('layouts.app')

@section('content')
<div class="p-6 max-w-[1600px] mx-auto" 
     x-data="examApp({{ $remainingSeconds }}, {{ $exam->duration * 60 }}, {{ $exam->questions->count() }}, {{ $exam->questions->pluck('id')->toJson() }}, {{ $exam->id }})"
     x-cloak>
    
    <!-- HEADER TIMER: Tetap Kecil dan Informatif -->
    <header class="bg-white dark:bg-zinc-900 p-4 rounded-2xl shadow-sm sticky top-4 z-40 border border-slate-200 dark:border-zinc-800 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="lg">{{ $exam->title }}</flux:heading>
                <flux:subheading class="text-xs uppercase font-bold text-indigo-500">{{ $exam->subject->subject_name }}</flux:subheading>
            </div>

            <div class="flex items-center gap-6">
                <div class="text-right">
                    <p class="text-[10px] text-slate-400 font-bold uppercase">Sisa Waktu</p>
                    <p class="font-mono text-2xl font-black text-indigo-600 dark:text-indigo-400" x-text="formatTime()"></p>
                </div>
                <div class="w-32 h-1.5 rounded-full bg-slate-100 dark:bg-zinc-800 overflow-hidden hidden md:block">
                    <div class="h-full transition-all duration-500" :class="timerColor" :style="`width: ${progress}%` text-slate-500"></div>
                </div>
            </div>
        </div>
    </header>

    <div class="flex flex-col lg:flex-row gap-6 items-start">
        
        <!-- KOLOM KIRI: Area Soal (9/12) -->
        <div class="flex-grow w-full lg:w-3/4">
        <form action="{{ route('mahasiswa.exams.submit', $exam->id) }}" 
            method="POST" 
            id="exam-form" 
            @submit="clearStorage()">
                @csrf
                @foreach($exam->questions as $index => $q)
                    <div x-show="currentQuestion === {{ $index }}" x-transition:enter="transition ease-out duration-200">
                        <flux:card class="p-6 shadow-md border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
                            <div class="flex justify-between items-center mb-6">
                                <flux:heading size="md" class="text-indigo-600 font-bold uppercase">Pertanyaan #{{ $index + 1 }}</flux:heading>
                                <flux:badge variant="subtle" color="zinc" class="font-bold">ESAI</flux:badge>
                            </div>
                            
                            <div class="mb-6 p-6 bg-slate-50 dark:bg-zinc-800/50 rounded-2xl border border-slate-100 dark:border-zinc-700/50">
                                <p class="text-xl text-slate-800 dark:text-zinc-200 leading-relaxed font-medium">
                                    {{ $q->question_text }}
                                </p>
                            </div>

                            <flux:textarea
                                name="answers[{{ $q->id }}]"
                                x-model="answers['question_{{ $q->id }}']"
                                placeholder="Tulis jawaban lengkap Anda di sini..."
                                rows="12"
                                class="text-lg shadow-inner"
                            />

                            <div class="flex justify-between items-center mt-10 pt-6 border-t border-slate-100 dark:border-zinc-800">
                                <flux:button variant="subtle" icon="chevron-left" x-bind:disabled="currentQuestion === 0" x-on:click="currentQuestion--">
                                    Sebelumnya
                                </flux:button>
                                
                                <span class="text-xs font-black text-slate-400 uppercase tracking-widest">
                                    SOAL <span x-text="currentQuestion + 1" class="text-indigo-600"></span> DARI {{ $exam->questions->count() }}
                                </span>

                                <flux:button variant="subtle" icon-trailing="chevron-right" x-bind:disabled="currentQuestion === totalQuestions - 1" x-on:click="currentQuestion++">
                                    Selanjutnya
                                </flux:button>
                            </div>
                        </flux:card>
                    </div>
                @endforeach
            </form>
        </div>

        <!-- KOLOM KANAN: Navigasi Ringkas (Fixed Width & Sticky) -->
        <div class="w-full lg:w-[300px] lg:sticky lg:top-28">
            <flux:card class="p-5 shadow-lg border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
                <div class="mb-5 flex items-center justify-between border-b border-slate-100 dark:border-zinc-800 pb-3">
                    <flux:heading size="sm" class="font-bold">Daftar Soal</flux:heading>
                    <span class="text-[10px] bg-indigo-50 dark:bg-indigo-900/30 px-2 py-0.5 rounded font-bold text-indigo-600 uppercase">
                        {{ $exam->questions->count() }} Item
                    </span>
                </div>

                <!-- Grid Nomor Soal: UKURAN KECIL DAN RAPI -->
                <div class="flex flex-wrap gap-2 justify-start mb-6">
                    @foreach($exam->questions as $index => $q)
                        <button
                            type="button"
                            x-on:click="currentQuestion = {{ $index }}"
                            :class="{
                                'bg-indigo-600 text-white border-indigo-600 shadow-md ring-2 ring-indigo-500/20 z-10': currentQuestion === {{ $index }},
                                'bg-emerald-500 text-white border-emerald-500': currentQuestion !== {{ $index }} && answers['question_{{ $q->id }}']?.trim().length > 0,
                                'bg-slate-50 dark:bg-zinc-800 text-slate-400 border-slate-200 dark:border-zinc-700': currentQuestion !== {{ $index }} && !answers['question_{{ $q->id }}']?.trim().length
                            }"
                            class="w-10 h-10 rounded-lg border flex items-center justify-center text-xs font-black transition-all duration-150 hover:scale-105"
                        >
                            {{ $index + 1 }}
                        </button>
                    @endforeach
                </div>

                <div class="space-y-4 pt-2">
                    <div class="flex justify-between text-[10px] font-black uppercase text-slate-400 px-1 border-t border-slate-50 dark:border-zinc-800 pt-4">
                        <span>Sudah: <span class="text-emerald-500" x-text="answeredCount"></span></span>
                        <span>Belum: <span class="text-rose-500" x-text="totalQuestions - answeredCount"></span></span>
                    </div>

                    <flux:button type="submit" form="exam-form" variant="primary" class="w-full font-bold py-4 shadow-lg shadow-indigo-100 dark:shadow-none" x-on:click="return confirm('Selesaikan ujian sekarang?')">
                        Selesai & Kirim
                    </flux:button>
                </div>
            </flux:card>
        </div>

    </div>
</div>

<script>
    function examApp(remainingSeconds, totalDurationSeconds, total, ids, examId) {
        return {
            duration: totalDurationSeconds, // Durasi asli untuk hitung % progress bar
            remaining: remainingSeconds,    // Sisa waktu yang dikirim dari server
            currentQuestion: 0,
            totalQuestions: total,
            questionIds: ids,
            examId: examId,
            answers: {},
             
            init() {
                this.questionIds.forEach((id) => {
                    this.answers[`question_${id}`] = '';
                });

                const savedAnswers = localStorage.getItem(`answers_exam_${this.examId}_user_${ {{ auth()->id() }} }`);
                    if (savedAnswers) {
                        this.answers = JSON.parse(savedAnswers);
                    } else {
                        // Jika tidak ada, inisialisasi kosong
                        this.questionIds.forEach((id) => {
                            this.answers[`question_${id}`] = '';
                        });
                    }
                    setInterval(() => {
                    localStorage.setItem(
                        `answers_exam_${this.examId}_user_${ {{ auth()->id() }} }`, 
                        JSON.stringify(this.answers)
                    );
                }, 5000);

                const timer = setInterval(() => {
                    if (this.remaining > 0) {
                        this.remaining--;
                    } else {
                        clearInterval(timer);
                        this.clearStorage();
                        document.getElementById('exam-form').submit();
                    }
                }, 1000);
            },
            clearStorage() {
            localStorage.removeItem(`answers_exam_${this.examId}_user_${ {{ auth()->id() }} }`);
        },
            // Fungsi format dan get progress lainnya tetap sama seperti sebelumnya...
             formatTime() {
                // Math.floor digunakan untuk membulatkan angka desimal ke bawah
                const totalSeconds = Math.floor(this.remaining);
                
                // Hitung menit dan detik dari total detik yang tersisa
                const m = Math.floor(totalSeconds / 60);
                const s = totalSeconds % 60;

                // Pastikan menit tidak menampilkan angka negatif
                const displayMinutes = m < 0 ? 0 : m;
                // Pastikan detik tidak menampilkan angka negatif
                const displaySeconds = s < 0 ? 0 : s;

                // Format string agar selalu dua digit (contoh: 05:09)
                return `${displayMinutes}:${displaySeconds < 10 ? '0' : ''}${displaySeconds}`;
            },

            get progress() {
                return (this.remaining / this.duration) * 100;
            },

            get timerColor() {
                if (this.remaining <= 60) return 'bg-rose-500 animate-pulse';
                if (this.remaining <= 300) return 'bg-amber-400';
                return 'bg-indigo-500';
            },

            get answeredCount() {
                return Object.values(this.answers).filter(v => v.trim().length > 0).length;
            }
        };
    }
</script>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection