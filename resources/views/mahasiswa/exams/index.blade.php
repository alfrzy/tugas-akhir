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

    @php
    $flashes = [
        'success' => ['icon' => 'check-circle',       'classes' => 'bg-emerald-50 border-emerald-200 text-emerald-700'],
        'warning' => ['icon' => 'exclamation-triangle','classes' => 'bg-amber-50 border-amber-200 text-amber-700'],
        'info'    => ['icon' => 'information-circle', 'classes' => 'bg-blue-50 border-blue-200 text-blue-700'],
        'error'   => ['icon' => 'x-circle',           'classes' => 'bg-red-50 border-red-200 text-red-700'],
    ];
@endphp

@foreach($flashes as $type => $cfg)
    @if(session($type))
        <div class="border px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm {{ $cfg['classes'] }}">
            <flux:icon name="{{ $cfg['icon'] }}" variant="solid" class="w-5 h-5 shrink-0" />
            <span class="font-medium text-sm">{{ session($type) }}</span>
        </div>
    @endif
@endforeach

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
                                <flux:icon name="stop-circle" variant="micro" class="text-red-500" />
                                <span>Deadline: <strong>{{ $exam->end_time ? $exam->end_time->format('d M Y, H:i') : 'Belum diatur' }}</strong></span>
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

                        <flux:modal.trigger name="start-exam-{{ $exam->id }}">
                        <flux:button type="button" variant="primary" icon="pencil-square" class="{{ $masaToleransi ? 'bg-amber-500 hover:bg-amber-600 text-white' : 'bg-blue-600 hover:bg-blue-700' }}">
                            Kerjakan Ujian
                        </flux:button>
                    </flux:modal.trigger>
                <flux:modal name="start-exam-{{ $exam->id }}" class="md:w-[450px]">
                    <div class="flex flex-col items-center text-center space-y-4">
                        {{-- Ikon Play --}}
                        <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center shadow-inner">
                            <flux:icon name="play-circle" variant="solid" class="w-10 h-10" />
                        </div>
                        
                        <div>
                            <flux:heading size="lg" class="font-black text-slate-800 dark:text-slate-200">Mulai Ujian Sekarang?</flux:heading>
                            <flux:text class="mt-3 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                                Apakah Anda sudah siap? Durasi ujian selama <span class="font-bold text-slate-900 dark:text-white">{{ $exam->duration }} menit</span> akan langsung dihitung mundur setelah Anda menekan tombol di bawah. <br><br>
                                <span class="text-xs italic text-red-500">Pastikan koneksi internet Anda stabil sebelum memulai.</span>
                            </flux:text>
                        </div>
                    </div>

                    <div class="flex gap-3 w-full mt-8">
                        {{-- Tombol Batal (Menutup Modal) --}}
                        <flux:modal.close class="w-full">
                            <flux:button variant="subtle" class="w-full font-bold">Batal</flux:button>
                        </flux:modal.close>
                        
                        {{-- Tombol Asli untuk Navigasi (Mulai Ujian) --}}
                        <flux:button 
                            :href="route('mahasiswa.exams.take', $exam->id)" 
                            variant="primary" 
                            wire:navigate 
                            class="w-full font-bold shadow-lg {{ $masaToleransi ? 'bg-amber-500 hover:bg-amber-600 text-white shadow-amber-500/30' : 'bg-blue-600 hover:bg-blue-700 shadow-blue-500/30' }}"
                        >
                            Ya, Mulai Ujian
                        </flux:button>
                    </div>
                </flux:modal>
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