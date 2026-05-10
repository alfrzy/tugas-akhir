@extends('layouts.app')

@section('content')
@php
    // 1. KITA AMBIL DATA SUBMISSION DARI DATABASE
    // Kita cari rekap ujian berdasarkan ID mahasiswa dan ID ujian
    $submission = \App\Models\Submission::where('user_id', $student->id)
                                        ->where('exam_id', $exam->id)
                                        ->first();
@endphp

<div class="p-6 max-w-5xl mx-auto space-y-6">
    <!-- Navigasi Atas -->
    <div class="flex items-center justify-between">
        <flux:button href="{{ route('dosen.results.show', $exam->id) }}" variant="subtle" icon="chevron-left" size="sm">
            Kembali
        </flux:button>
        
        <flux:badge color="indigo" variant="subtle" class="font-black">MODUL PENILAIAN</flux:badge>
    </div>

    <!-- Header Informasi Mahasiswa -->
    <header class="bg-white dark:bg-zinc-900 p-6 rounded-3xl border border-slate-200 dark:border-zinc-800 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-2xl bg-indigo-600 flex items-center justify-center text-white text-2xl font-black">
                    {{ substr($student->name, 0, 1) }}
                </div>
                <div>
                    <flux:heading size="xl" class="font-black tracking-tight">{{ $student->name }}</flux:heading>
                    <flux:subheading>{{ $exam->title }}</flux:subheading>
                </div>
            </div>
            
            <div class="text-right">
                <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Total Skor Akhir</p>
                <!-- 2. TAMPILKAN NILAI DARI TABEL SUBMISSION -->
                <!-- Jika submission ada, tampilkan total_score. Jika belum ada, tampilkan 0.0 -->
                <p class="text-4xl font-black text-indigo-600">
                    {{ $submission ? number_format($submission->total_score, 1) : '0.0' }}
                </p>
            </div>

            <div class="flex flex-col items-end gap-3">
                @php
                    // 3. CEK STATUS PUBLISH DARI TABEL SUBMISSION
                    $isAlreadyPublished = $submission ? $submission->is_published : false;
                @endphp

                @if(!$isAlreadyPublished && $answers->count() > 0)
                    <form action="{{ route('dosen.results.publish', ['exam' => $exam->id, 'student' => $student->id]) }}" method="POST">
                        @csrf
                        <flux:button type="submit" variant="filled" color="emerald" icon="check-circle" size="sm">
                            Publish Nilai
                        </flux:button>
                    </form>
                @elseif($answers->count() > 0)
                    <flux:badge color="emerald" variant="subtle" icon="check">Nilai Sudah Terbit</flux:badge>
                @endif
            </div>
        </div>
    </header>

    <flux:separator variant="subtle" />

    <!-- Daftar Soal, Jawaban Mahasiswa, dan Jawaban Referensi -->
    <div class="space-y-6">
        @foreach($exam->questions as $index => $question)
            @php
                $answer = $answers->get($question->id);
            @endphp
            <flux:card class="p-0 overflow-hidden border-slate-200 dark:border-zinc-800 shadow-md">
                <!-- Header Kartu Soal -->
                <div class="bg-slate-50 dark:bg-zinc-800/50 p-4 border-b border-slate-100 dark:border-zinc-800 flex justify-between items-center">
                    <span class="text-xs font-black uppercase tracking-widest text-indigo-500">Pertanyaan #{{ $index + 1 }}</span>
                    <flux:badge color="indigo" size="sm" class="font-bold">
                        Skor: {{ $answer->score ?? '0' }} / 100
                    </flux:badge>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Area Pertanyaan -->
                    <div>
                        <p class="text-lg font-medium text-slate-800 dark:text-zinc-200 leading-relaxed">
                            {{ $question->question_text }}
                        </p>
                    </div>

                    <!-- Grid Jawaban -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        
                        <!-- Kolom Jawaban Mahasiswa -->
                        <div class="flex flex-col">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest flex items-center gap-2 mb-2">
                                <flux:icon name="pencil-square" variant="micro" />
                                Jawaban Mahasiswa
                            </label>
                            <div class="grow p-5 rounded-2xl border border-slate-200 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/50">
                                <p class="text-slate-700 dark:text-zinc-300 leading-relaxed text-sm whitespace-pre-line">
                                    {{ $answer->answer_text ?? 'Mahasiswa tidak mengisi jawaban.' }}
                                </p>
                            </div>
                        </div>

                        <!-- Kolom Jawaban Referensi -->
                        <div class="flex flex-col">
                            <label class="text-[10px] font-black uppercase text-emerald-500 tracking-widest flex items-center gap-2 mb-2">
                                <flux:icon name="check-badge" variant="micro" />
                                Jawaban Referensi (Kunci)
                            </label>
                            <div class="grow p-5 rounded-2xl border border-emerald-100 dark:border-emerald-900/30 bg-emerald-50/30 dark:bg-emerald-900/10">
                                <p class="text-emerald-800 dark:text-emerald-400 leading-relaxed text-sm italic whitespace-pre-line">
                                    {{ $question->key_answer ?? 'Referensi belum diatur.' }}
                                </p>
                            </div>
                        </div>

                    </div>
                    
                    {{-- Feedback NLP (Jika ada) --}}
                    @if(isset($answer->feedback))
                    <div class="mt-4 p-4 rounded-xl bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-900/20">
                        <p class="text-xs text-amber-700 dark:text-amber-400 font-medium">
                            <strong>Analisis Sistem:</strong> {{ $answer->feedback }}
                        </p>
                    </div>
                    @endif

                    {{-- DETAIL PERHITUNGAN TF-IDF & COSINE SIMILARITY (ASLI DARI SISTEM) --}}
                    @if(isset($answer->calculation_log) && is_array($answer->calculation_log))
                        <div class="mt-6 border border-indigo-200 dark:border-indigo-900/50 rounded-2xl overflow-hidden bg-white dark:bg-zinc-900">
                            <details class="group">
                                <summary class="flex items-center justify-between p-4 cursor-pointer bg-indigo-50/50 dark:bg-indigo-900/20 hover:bg-indigo-100/50 dark:hover:bg-indigo-900/40 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <flux:icon name="calculator" variant="outline" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
                                        <span class="font-bold text-sm text-indigo-900 dark:text-indigo-300">Lihat Detail Perhitungan Algoritma (Log Mesin Asli)</span>
                                    </div>
                                    <span class="transition-transform duration-300 group-open:-rotate-180 text-indigo-500">
                                        <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                                    </span>
                                </summary>
                                
                                <div class="p-5 border-t border-indigo-100 dark:border-indigo-900/50 space-y-5 text-sm text-slate-700 dark:text-slate-300">
                                    
                                    <!-- Tahap 1: Preprocessing -->
                                    <div>
                                        <h4 class="font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-2">
                                            <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 py-0.5 px-2 rounded text-xs">Tahap 1</span>
                                            Preprocessing (Pembersihan Teks)
                                        </h4>
                                        <div class="grid grid-cols-2 gap-4 bg-slate-50 dark:bg-zinc-800/50 p-3 rounded-lg border border-slate-100 dark:border-zinc-800 text-xs font-mono">
                                            <div>
                                                <span class="text-slate-500 block mb-1">Vektor Kunci (A):</span>
                                                <span class="text-emerald-600 dark:text-emerald-400">"{{ $answer->calculation_log['clean_key'] ?? '-' }}"</span>
                                            </div>
                                            <div>
                                                <span class="text-slate-500 block mb-1">Vektor Jawaban (B):</span>
                                                <span class="text-indigo-600 dark:text-indigo-400">"{{ $answer->calculation_log['clean_student'] ?? '-' }}"</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tahap 2: Pembobotan TF-IDF -->
                                    <div>
                                        <h4 class="font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-2">
                                            <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 py-0.5 px-2 rounded text-xs">Tahap 2</span>
                                            Pembobotan TF-IDF (Unigram & Bigram)
                                        </h4>
                                        <div class="overflow-x-auto">
                                            <table class="w-full text-xs text-left border-collapse">
                                                <thead>
                                                    <tr class="bg-slate-100 dark:bg-zinc-800">
                                                        <th class="p-2 border border-slate-200 dark:border-zinc-700">Term (Kata/Frasa)</th>
                                                        <th class="p-2 border border-slate-200 dark:border-zinc-700 text-center">Bobot Kunci (A)</th>
                                                        <th class="p-2 border border-slate-200 dark:border-zinc-700 text-center">Bobot Jawaban (B)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(isset($answer->calculation_log['terms']))
                                                        @foreach($answer->calculation_log['terms'] as $term => $data)
                                                            <tr>
                                                                <td class="p-2 border border-slate-200 dark:border-zinc-700 font-mono">{{ $term }}</td>
                                                                <td class="p-2 border border-slate-200 dark:border-zinc-700 text-center">{{ $data['bobot_kunci'] }}</td>
                                                                <td class="p-2 border border-slate-200 dark:border-zinc-700 text-center">{{ $data['bobot_jawaban'] }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="3" class="p-2 text-center text-slate-500 italic">Data term tidak ditemukan.</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Tahap 3: Cosine Similarity -->
                                    <div>
                                        <h4 class="font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-2">
                                            <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 py-0.5 px-2 rounded text-xs">Tahap 3</span>
                                            Perhitungan Cosine Similarity
                                        </h4>
                                        <div class="bg-slate-800 text-emerald-400 p-4 rounded-lg font-mono text-xs overflow-x-auto">
                                            @php
                                                // Kita ambil data dari log, jika tidak ada set 0
                                                $dot = $answer->calculation_log['dot_product'] ?? 0;
                                                $mag1 = $answer->calculation_log['magnitude_kunci'] ?? 0;
                                                $mag2 = $answer->calculation_log['magnitude_jawaban'] ?? 0;
                                                
                                                // Hindari error pembagian dengan nol
                                                $pembagi = $mag1 * $mag2;
                                                $cosine = $pembagi > 0 ? ($dot / $pembagi) : 0;
                                            @endphp
                                            <p class="mb-2 text-slate-400">// Rumus: (A • B) / (||A|| * ||B||)</p>
                                            <p>1. Dot Product (A • B) = {{ number_format($dot, 3) }}</p>
                                            <p>2. Magnitude A (||A||) = {{ number_format($mag1, 3) }}</p>
                                            <p>3. Magnitude B (||B||) = {{ number_format($mag2, 3) }}</p>
                                            <p class="mt-2 text-white border-t border-slate-600 pt-2">
                                                Cosine Similarity = {{ number_format($dot, 3) }} / ({{ number_format($mag1, 3) }} * {{ number_format($mag2, 3) }}) = <span class="text-yellow-400 font-bold">{{ number_format($cosine, 3) }} ({{ number_format($cosine * 100, 1) }}%)</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </details>
                        </div>
                    @else
                        {{-- Fallback jika ujian dilakukan sebelum fitur log dibuat --}}
                        <div class="mt-6 p-4 rounded-xl bg-slate-50 dark:bg-zinc-800/50 border border-slate-200 dark:border-zinc-700 text-center">
                            <p class="text-xs text-slate-500 font-medium">
                                Log perhitungan mesin tidak tersedia untuk jawaban ini (Mungkin ini adalah data ujian lama).
                            </p>
                        </div>
                    @endif
                </div>
            </flux:card>
        @endforeach
    </div>
</div>
@endsection