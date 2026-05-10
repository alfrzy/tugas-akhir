@extends('layouts.app')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6">
    <!-- Header Halaman -->
    <header class="flex items-center justify-between">
        <div>
            <flux:heading size="xl" class="font-bold">Rekapitulasi Hasil Ujian</flux:heading>
            <flux:subheading>Pantau partisipasi mahasiswa pada setiap ujian yang Anda ampu.</flux:subheading>
        </div>
    </header>

    <flux:separator variant="subtle" />

    <!-- Tabel Hasil Ujian -->
    <flux:card class="overflow-hidden border-slate-200 dark:border-zinc-800">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 dark:bg-zinc-800/50">
                    <tr>
                        <th class="py-4 px-6 text-xs font-black uppercase tracking-widest text-slate-500 border-b border-slate-200 dark:border-zinc-800">
                            Mata Kuliah
                        </th>
                        <th class="py-4 px-6 text-xs font-black uppercase tracking-widest text-slate-500 border-b border-slate-200 dark:border-zinc-800">
                            Judul Ujian
                        </th>
                        <th class="py-4 px-6 text-xs font-black uppercase tracking-widest text-slate-500 border-b border-slate-200 dark:border-zinc-800 text-center">
                            Peserta Selesai
                        </th>
                        <th class="py-4 px-6 text-xs font-black uppercase tracking-widest text-slate-500 border-b border-slate-200 dark:border-zinc-800 text-right">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                    @forelse($exams as $exam)
                        <tr class="hover:bg-slate-50 dark:hover:bg-zinc-800/30 transition-colors duration-200">
                            <!-- Nama Mata Kuliah -->
                            <td class="py-4 px-6">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-900 dark:text-white">
                                        {{ $exam->subject->subject_name }}
                                    </span>
                                    <span class="text-xs text-slate-500 dark:text-zinc-400">
                                        {{ $exam->subject->subject_code }}
                                </div>
                            </td>

                            <!-- Judul Ujian -->
                            <td class="py-4 px-6">
                                <span class="text-sm text-slate-600 dark:text-zinc-400 font-medium">
                                    {{ $exam->title }}
                                </span>
                            </td>

                            <!-- Jumlah Mahasiswa (total_students berasal dari withCount) -->
                            <td class="py-4 px-6 text-center">
                                <flux:badge color="{{ $exam->total_students > 0 ? 'emerald' : 'zinc' }}" variant="subtle" size="sm" class="font-bold">
                                    {{ $exam->total_students }} Mahasiswa
                                </flux:badge>
                            </td>

                            <!-- Tombol Navigasi -->
                            <td class="py-4 px-6 text-right">
                                <flux:button 
                                    href="{{ route('dosen.results.show', $exam->id) }}" 
                                    variant="ghost" 
                                    size="sm" 
                                    icon-trailing="chevron-right"
                                    class="text-indigo-600 hover:text-indigo-700"
                                >
                                    Lihat Detail
                                </flux:button>
                            </td>
                        </tr>
                    @empty
                        <!-- Tampilan Jika Belum Ada Ujian -->
                        <tr>
                            <td colspan="4" class="py-12 text-center">
                                <flux:icon name="document-magnifying-glass" class="mx-auto h-12 w-12 text-slate-300 mb-4" />
                                <flux:heading size="sm" class="text-slate-400">Belum ada data ujian ditemukan.</flux:heading>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </flux:card>
</div>
@endsection