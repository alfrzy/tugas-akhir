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
            <flux:subheading>Pilih mata kuliah untuk membuat soal ujian berdasarkan BAB.</flux:subheading>
        </div>
    </header>

    {{-- Daftar Mata Kuliah yang Diampu --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($mySubjects as $subject)
            <flux:card class="flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow">
                <div>
                    <div class="flex items-center justify-between">
                        <flux:badge color="indigo" size="sm" inset="top bottom">{{ $subject->subject_code }}</flux:badge>
                    </div>
                    <flux:heading size="lg" class="mt-2">{{ $subject->subject_name }}</flux:heading>
                    <flux:text class="mt-2 text-zinc-500">Kelola soal dan kunci jawaban untuk mata kuliah ini.</flux:text>
                </div>

                <div class="mt-6">
                    <flux:button variant="primary" icon="plus" class="w-full bg-blue-600 hover:bg-blue-700" :href="route('dosen.exams.create', $subject->id)" wire:navigate>
                        Buat Ujian Baru
                    </flux:button>
                </div>
            </flux:card>
        @endforeach
    </div>

    <hr class="border-zinc-200 dark:border-zinc-800">

    {{-- Daftar Ujian yang Sudah Terbit --}}
    <section class="space-y-4">
        <flux:heading size="lg">Ujian yang Telah Diterbitkan</flux:heading>
        
        <flux:card class="p-0 overflow-hidden shadow-sm">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Mata Kuliah</flux:table.column>
                    <flux:table.column>Judul / BAB</flux:table.column>
                    <flux:table.column>Tanggal Dibuat</flux:table.column>
                    <flux:table.column>Durasi</flux:table.column>
                    <flux:table.column align="end">Aksi</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($exams as $exam)
                        <flux:table.row>
                            <flux:table.cell class="font-medium">{{ $exam->subject->subject_name }}</flux:table.cell>
                            <flux:table.cell>{{ $exam->title }}</flux:table.cell>
                            <flux:table.cell class="text-zinc-500">{{ $exam->created_at->format('d M Y') }}</flux:table.cell>
                            <flux:table.cell>{{ $exam->duration }} Menit</flux:table.cell>
                            <flux:table.cell align="end" class="flex justify-end gap-2">
                                
                                {{-- Tombol Lihat Detail --}}
                                <flux:button variant="ghost" size="sm" icon="eye" class="text-blue-600 hover:bg-blue-50" :href="route('dosen.exams.show', $exam->id)" wire:navigate />
                                
                                {{-- Tombol Edit Baru --}}
                                <flux:button variant="ghost" size="sm" icon="pencil-square" class="text-indigo-600 hover:bg-indigo-50" :href="route('dosen.exams.edit', $exam->id)" wire:navigate />

                                {{-- Tombol Pemicu Modal Hapus --}}
                                <flux:modal.trigger name="delete-exam-{{ $exam->id }}">
                                    <flux:button variant="ghost" size="sm" icon="trash" class="text-red-500 hover:text-red-600 hover:bg-red-50" />
                                </flux:modal.trigger>

                                {{-- Modal Konfirmasi Hapus --}}
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
                                                    Apakah Anda yakin ingin menghapus ujian <span class="font-bold text-slate-800 dark:text-slate-200">"{{ $exam->title }}"</span>? Semua soal esai dan data nilai mahasiswa terkait akan ikut terhapus dan tidak dapat dikembalikan.
                                                </flux:text>
                                            </div>
                                        </div>

                                        <div class="flex gap-3 w-full">
                                            <flux:modal.close class="w-full">
                                                <flux:button variant="subtle" class="w-full">Batal</flux:button>
                                            </flux:modal.close>
                                            <flux:button type="submit" variant="danger" class="w-full bg-red-600 hover:bg-red-700 text-white">Ya, Hapus Permanen</flux:button>
                                        </div>
                                    </form>
                                </flux:modal>

                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="5" class="text-center py-10 text-zinc-500">
                                Belum ada ujian yang dibuat.
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </flux:card>
    </section>
</div>
@endsection