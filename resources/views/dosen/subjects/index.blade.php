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

    <header class="flex justify-between items-center">
        <div>
            <flux:heading size="xl">Mata Kuliah Saya</flux:heading>
            <flux:subheading>Kelola daftar kelas dan bagikan kode akses ke mahasiswa.</flux:subheading>
        </div>
        
        {{-- Tombol Tambah Kelas --}}
        <flux:modal.trigger name="add-subject">
            <flux:button variant="primary" icon="plus" class="bg-blue-600 hover:bg-blue-700">Tambah Kelas</flux:button>
        </flux:modal.trigger>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($subjects as $subject)
            <flux:card class="flex flex-col justify-between hover:border-blue-300 transition-colors shadow-sm hover:shadow-md">
                <div>
                    {{-- Header Card (Kode Matkul & Aksi) --}}
                    <div class="flex justify-between mb-3">
                        <flux:badge color="blue" variant="subtle" class="font-bold tracking-wider">{{ $subject->subject_code }}</flux:badge>
                        <div class="flex gap-1">
                            {{-- Tombol Edit --}}
                            <flux:modal.trigger name="edit-subject-{{ $subject->id }}">
                                <flux:button variant="subtle" size="sm" icon="pencil-square" class="text-blue-600 hover:bg-blue-50" />
                            </flux:modal.trigger>

                            {{-- Tombol Hapus --}}
                            <flux:modal.trigger name="delete-subject-{{ $subject->id }}">
                                <flux:button variant="subtle" size="sm" icon="trash" class="text-red-500 hover:text-red-600 hover:bg-red-50" />
                            </flux:modal.trigger>
                        </div>
                    </div>
                    
                    {{-- Nama Mata Kuliah --}}
                    <flux:heading size="lg" class="text-slate-800 dark:text-white leading-tight mb-5">{{ $subject->subject_name }}</flux:heading>
                    
                    {{-- KOTAK KODE AKSES KELAS --}}
                    <div class="p-4 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl border border-indigo-100 dark:border-indigo-800 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] text-indigo-600 dark:text-indigo-400 font-bold uppercase tracking-widest mb-1">Kode Akses Kelas</p>
                            <p class="font-mono font-black text-xl text-slate-800 dark:text-slate-200 tracking-[0.2em]">{{ $subject->join_code ?? 'KOSONG' }}</p>
                        </div>
                        <div class="w-10 h-10 bg-white dark:bg-slate-800 rounded-xl flex items-center justify-center shadow-sm">
                            <flux:icon name="key" variant="solid" class="w-5 h-5 text-indigo-500" />
                        </div>
                    </div>
                </div>

                {{-- Modal Edit per Item --}}
                <flux:modal name="edit-subject-{{ $subject->id }}" class="md:w-[400px]">
                    <form action="{{ route('dosen.subjects.update', $subject->id) }}" method="POST" class="space-y-6">
                        @csrf @method('PUT')
                        <flux:heading size="lg">Edit Mata Kuliah</flux:heading>
                        <flux:input label="Kode Matkul" name="subject_code" value="{{ $subject->subject_code }}" required />
                        <flux:input label="Nama Mata Kuliah" name="subject_name" value="{{ $subject->subject_name }}" required />
                        <div class="flex gap-2">
                            <flux:modal.close class="w-full">
                                <flux:button variant="subtle" class="w-full">Batal</flux:button>
                            </flux:modal.close>
                            <flux:button type="submit" variant="primary" class="w-full bg-blue-600 hover:bg-blue-700">Simpan Perubahan</flux:button>
                        </div>
                    </form>
                </flux:modal>

                {{-- Modal Konfirmasi Hapus --}}
                <flux:modal name="delete-subject-{{ $subject->id }}" class="md:w-[400px]">
                    <form action="{{ route('dosen.subjects.destroy', $subject->id) }}" method="POST" class="space-y-6">
                        @csrf @method('DELETE')
                        
                        <div class="flex flex-col items-center text-center space-y-4">
                            <div class="w-14 h-14 bg-red-100 dark:bg-red-900/30 text-red-600 rounded-full flex items-center justify-center">
                                <flux:icon name="exclamation-triangle" variant="solid" class="w-8 h-8" />
                            </div>
                            <div>
                                <flux:heading size="lg">Hapus Permanen?</flux:heading>
                                <flux:text class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                    Apakah Anda yakin ingin menghapus mata kuliah <span class="font-bold text-slate-800 dark:text-slate-200">"{{ $subject->subject_name }}"</span>? Semua data ujian dan nilai di dalamnya akan ikut terhapus dan tidak dapat dikembalikan.
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
            </flux:card>
        @empty
            <div class="col-span-full py-12 text-center border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-2xl">
                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4">
                    <flux:icon name="book-open" variant="outline" class="w-8 h-8" />
                </div>
                <flux:heading size="md" class="text-slate-600 dark:text-slate-300">Belum Ada Mata Kuliah</flux:heading>
                <flux:text class="text-sm mt-1 text-slate-500">Klik "Tambah Kelas" untuk mulai mengelola kelas Anda.</flux:text>
            </div>
        @endforelse
    </div>

    {{-- Modal Tambah Kelas --}}
    <flux:modal name="add-subject" class="md:w-[400px]">
        <form action="{{ route('dosen.subjects.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <flux:heading size="lg">Tambah Kelas Baru</flux:heading>
                <flux:subheading>Kelas ini akan otomatis Anda ampu.</flux:subheading>
            </div>
            <flux:input label="Kode Matkul" name="subject_code" placeholder="Contoh: IF101" required />
            <flux:input label="Nama Mata Kuliah" name="subject_name" placeholder="Contoh: Algoritma" required />
            <div class="flex gap-2">
                <flux:modal.close class="w-full">
                    <flux:button variant="subtle" class="w-full">Batal</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary" class="w-full bg-blue-600 hover:bg-blue-700">Tambah Kelas</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
@endsection