@extends('layouts.app')

@section('content')
<div class="p-6 space-y-8">
    {{-- ALERT PESAN SUKSES --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm animate-fade-in-down">
            <flux:icon name="check-circle" variant="solid" class="w-5 h-5 text-emerald-500" />
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
    @endif
    
    <header class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">Manajemen Ujian Esai</flux:heading>
            <flux:subheading>Kelola soal ujian dan jadwal pengerjaan per mata kuliah.</flux:subheading>
        </div>
    </header>

    {{-- BAGIAN 1: Kotak Cepat Buat Ujian Baru --}}
    <section class="space-y-4">
        <flux:heading size="lg" class="px-2">Pilih Mata Kuliah untuk Membuat Ujian</flux:heading>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($mySubjects as $subject)
                <flux:card class="flex flex-col justify-between shadow-sm hover:border-blue-300 transition-all">
                    <div>
                        <div class="flex items-center justify-between">
                            <flux:badge color="indigo" size="sm" inset="top bottom">{{ $subject->subject_code }}</flux:badge>
                        </div>
                        <flux:heading size="lg" class="mt-2">{{ $subject->subject_name }}</flux:heading>
                        <flux:text class="mt-2 text-zinc-500">Buat instrumen soal esai baru untuk kelas ini.</flux:text>
                    </div>

                    <div class="mt-6">
                        <flux:button variant="primary" icon="plus" class="w-full bg-blue-600 hover:bg-blue-700" :href="route('dosen.exams.create', $subject->id)" wire:navigate>
                            Buat Ujian Baru
                        </flux:button>
                    </div>
                </flux:card>
            @endforeach
        </div>
    </section>

    <flux:separator variant="subtle" />

    {{-- BAGIAN 2: Daftar Ujian Terbit (Dikelompokkan per Subject) --}}
    <section class="space-y-8">
        <flux:heading size="lg" class="px-2">Daftar Ujian yang Telah Diterbitkan</flux:heading>

        @php $hasExams = false; @endphp

        @foreach($mySubjects as $subject)
            @if($subject->exams->isNotEmpty())
                @php $hasExams = true; @endphp
                <div class="space-y-3">
                    {{-- Sub-header Nama Mata Kuliah --}}
                    <div class="flex items-center gap-2 px-2">
                        <div class="w-1 h-6 bg-indigo-500 rounded-full"></div>
                        <flux:heading size="md" class="text-indigo-600">{{ $subject->subject_name }}</flux:heading>
                        <flux:badge color="zinc" size="sm" variant="subtle">{{ $subject->subject_code }}</flux:badge>
                    </div>

                    <flux:card class="p-0 overflow-hidden shadow-sm border-slate-200 dark:border-zinc-700">
                        <flux:table>
                            <flux:table.columns>
                                {{-- Gunakan span dengan margin-left (ml-4) --}}
                                <flux:table.column><span class="ml-4">Judul / BAB</span></flux:table.column>
                                <flux:table.column>Jadwal Mulai</flux:table.column>
                                <flux:table.column>Deadline</flux:table.column>
                                <flux:table.column>Durasi</flux:table.column>
                                {{-- Gunakan span dengan margin-right (mr-4) --}}
                                <flux:table.column align="end"><span class="mr-4">Aksi</span></flux:table.column>
                            </flux:table.columns>

                            <flux:table.rows>
                                @foreach($subject->exams as $exam)
                                    <flux:table.row>
                                        {{-- Bungkus teks dengan span dan ml-4 --}}
                                        <flux:table.cell>
                                            <span class="ml-4 font-bold text-slate-800 dark:text-zinc-200">
                                                {{ $exam->title }}
                                            </span>
                                        </flux:table.cell>
                                        
                                        <flux:table.cell class="text-zinc-500 italic text-xs">
                                            {{ $exam->start_time ? $exam->start_time->format('d M Y, H:i') : '-' }}
                                        </flux:table.cell>
                                        
                                        <flux:table.cell class="text-zinc-500 italic text-xs">
                                            {{ $exam->end_time ? $exam->end_time->format('d M Y, H:i') : '-' }}
                                        </flux:table.cell>
                                        
                                        <flux:table.cell>
                                            <flux:badge size="sm" variant="subtle" color="zinc">{{ $exam->duration }} Menit</flux:badge>
                                        </flux:table.cell>
                                        
                                        {{-- Bungkus deretan tombol dengan div dan mr-4 --}}
                                        <flux:table.cell align="end">
                                            <div class="mr-4 flex justify-end gap-2">
                                                <flux:button variant="ghost" size="sm" icon="eye" class="text-blue-600 hover:bg-blue-50" :href="route('dosen.exams.show', $exam->id)" wire:navigate />
                                                <flux:button variant="ghost" size="sm" icon="pencil-square" class="text-indigo-600 hover:bg-indigo-50" :href="route('dosen.exams.edit', $exam->id)" wire:navigate />

                                                <flux:modal.trigger name="delete-exam-{{ $exam->id }}">
                                                    <flux:button variant="ghost" size="sm" icon="trash" class="text-red-500 hover:text-red-600 hover:bg-red-50" />
                                                </flux:modal.trigger>

                                                <flux:modal name="delete-exam-{{ $exam->id }}" class="md:w-[400px]">
                                                    <form action="{{ route('dosen.exams.destroy', $exam->id) }}" method="POST" class="space-y-6">
                                                        @csrf @method('DELETE')
                                                        <div class="flex flex-col items-center text-center space-y-4">
                                                            <div class="w-14 h-14 bg-red-100 dark:bg-red-900/30 text-red-600 rounded-full flex items-center justify-center">
                                                                <flux:icon name="exclamation-triangle" variant="solid" class="w-8 h-8" />
                                                            </div>
                                                            <div>
                                                                <flux:heading size="lg">Hapus Ujian Permanen?</flux:heading>
                                                                <flux:text class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                                                    Yakin menghapus <span class="font-bold text-slate-500">"{{ $exam->title }}"</span>? Data nilai mahasiswa pada ujian ini akan hilang selamanya.
                                                                </flux:text>
                                                            </div>
                                                        </div>
                                                        <div class="flex gap-3 w-full">
                                                            <flux:modal.close class="w-full">
                                                                <flux:button variant="subtle" class="w-full">Batal</flux:button>
                                                            </flux:modal.close>
                                                            <flux:button type="submit" variant="danger" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold">Ya, Hapus</flux:button>
                                                        </div>
                                                    </form>
                                                </flux:modal>
                                            </div>
                                        </flux:table.cell>
                                    </flux:table.row>
                                @endforeach
                            </flux:table.rows>
                        </flux:table>
                    </flux:card>
                </div>
            @endif
        @endforeach

        @if(!$hasExams)
            <flux:card class="py-16 text-center border-dashed border-2 shadow-none">
                <flux:icon name="document-plus" class="mx-auto h-12 w-12 text-slate-300 mb-4" />
                <flux:heading size="md" class="text-slate-500">Belum ada ujian yang diterbitkan.</flux:heading>
                <flux:text class="text-xs">Klik tombol "Buat Ujian Baru" di atas untuk memulai.</flux:text>
            </flux:card>
        @endif
    </section>
</div>
@endsection