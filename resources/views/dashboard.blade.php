@extends('layouts.app')

@section('content')
<div class="space-y-8">
    {{-- Header Dashboard --}}
    <header class="flex items-center justify-between">
        <div>
            <flux:heading size="xl" level="1" class="text-blue-600 dark:text-blue-400">{{ __('Dashboard') }}</flux:heading>
            <flux:subheading>
                Selamat datang kembali, <span class="font-bold text-zinc-800 dark:text-white">{{ Auth::user()->name }}</span> 
                <flux:badge color="blue" inset="top bottom" class="ml-2 uppercase">{{ Auth::user()->role }}</flux:badge>
            </flux:subheading>
        </div>
    </header>

    <flux:separator variant="subtle" class="bg-gradient-to-r from-blue-200 via-blue-100 to-blue-200 dark:from-blue-500/30 dark:via-blue-400/30 dark:to-blue-500/30" />

    {{-- BAGIAN UNTUK ADMIN --}}
    @if(Auth::user()->role === 'admin')
        {{-- Stat Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            {{-- Total Mahasiswa --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-blue-200 dark:border-blue-500/30 shadow-sm hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <flux:text size="sm" class="text-slate-600 dark:text-slate-400 font-medium">Total Mahasiswa</flux:text>
                        <flux:heading size="xl" class="mt-2 text-slate-900 dark:text-white">{{ $totalMahasiswa }}</flux:heading>
                    </div>
                    <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 10a3 3 0 100-6 3 3 0 000 6zm0 1.5a6 6 0 00-6 6v1.5a1.5 1.5 0 001.5 1.5h9a1.5 1.5 0 001.5-1.5v-1.5a6 6 0 00-6-6z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Dosen --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-blue-200 dark:border-blue-500/30 shadow-sm hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <flux:text size="sm" class="text-slate-600 dark:text-slate-400 font-medium">Total Dosen</flux:text>
                        <flux:heading size="xl" class="mt-2 text-slate-900 dark:text-white">{{ $totalDosen }}</flux:heading>
                    </div>
                    <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 7H7v6h6V7z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Mata Kuliah --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-blue-200 dark:border-blue-500/30 shadow-sm hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <flux:text size="sm" class="text-slate-600 dark:text-slate-400 font-medium">Total Mata Kuliah</flux:text>
                        <flux:heading size="xl" class="mt-2 text-slate-900 dark:text-white">{{ $totalMatkuliah }}</flux:heading>
                    </div>
                    <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 6a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Ujian --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-blue-200 dark:border-blue-500/30 shadow-sm hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <flux:text size="sm" class="text-slate-600 dark:text-slate-400 font-medium">Total Ujian</flux:text>
                        <flux:heading size="xl" class="mt-2 text-slate-900 dark:text-white">{{ $totalUjian }}</flux:heading>
                    </div>
                    <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Recent Activity Table (2/3 width) --}}
            <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
                <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                    <flux:heading level="3" size="lg" class="text-slate-900 dark:text-white">Aktivitas Terbaru</flux:heading>
                    <flux:text size="sm" class="text-slate-500 dark:text-slate-400 mt-1">Pengumpulan ujian terbaru dari mahasiswa</flux:text>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-700/50 border-b border-slate-200 dark:border-slate-700">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">Mahasiswa</th>
                                <th class="px-6 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">Mata Kuliah</th>
                                <th class="px-6 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">Ujian</th>
                                <th class="px-6 py-3 text-left font-semibold text-slate-700 dark:text-slate-300">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($recentSubmissions as $submission)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <td class="px-6 py-3 text-slate-900 dark:text-slate-200">
                                        <div>
                                            <p class="font-medium">{{ $submission->user->name ?? 'N/A' }}</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $submission->user->nim ?? '-' }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 text-slate-700 dark:text-slate-300">
                                        {{ $submission->exam->subject->subject_name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-3 text-slate-700 dark:text-slate-300">
                                        {{ $submission->exam->title ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-3 text-slate-600 dark:text-slate-400 text-xs">
                                        {{ $submission->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                                        Tidak ada aktivitas terbaru
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- list progres mata kuliah --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <flux:heading level="3" size="lg" class="text-slate-900 dark:text-white mb-6">Progres Ujian Mata Kuliah</flux:heading>
                
                <div class="space-y-6 max-h-96 overflow-y-auto pr-2">
                    @forelse($subjectsWithInstructors as $subject)
                        @php
                            $progress = $subject['progress'];
                            if ($progress == 100) {
                                $barColor = 'bg-emerald-500';
                                $textColor = 'text-emerald-600 dark:text-emerald-400';
                            } elseif ($progress < 40) {
                                $barColor = 'bg-amber-500';
                                $textColor = 'text-amber-600 dark:text-amber-400';
                            } else {
                                $barColor = 'bg-blue-600 dark:bg-blue-500'; // Diubah dari indigo ke blue agar senada
                                $textColor = 'text-blue-600 dark:text-blue-400';
                            }
                        @endphp
                        
                        <div class="space-y-2 group cursor-default">
                            <div class="flex justify-between items-end">
                                <div>
                                    <p class="font-bold text-slate-800 dark:text-zinc-100 text-sm group-hover:text-blue-600 transition-colors">{{ $subject['name'] }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Dosen: {{ $subject['instructors'] }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-black {{ $textColor }}">{{ $progress }}%</span>
                                </div>
                            </div>

                            <div class="w-full bg-slate-100 dark:bg-slate-700/50 rounded-full h-2.5 overflow-hidden shadow-inner">
                                <div class="{{ $barColor }} h-2.5 rounded-full transition-all duration-1000 ease-out" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-slate-500 dark:text-slate-400">
                            <p class="text-sm">Belum ada mata kuliah yang terdaftar.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    {{-- BAGIAN UNTUK DOSEN --}}
    @elseif(Auth::user()->role === 'dosen')
        <div class="space-y-6">
            
            {{-- 1. Kartu Welcome (Utama) --}}
            <div class="rounded-2xl p-8 bg-white dark:bg-slate-800 shadow-sm border border-blue-200 dark:border-blue-500/30 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div>
                    <flux:heading level="2" size="lg" class="text-blue-600 dark:text-blue-400">Halo, Dosen {{ Auth::user()->name }}!</flux:heading>
                    <flux:text class="mt-2 text-slate-600 dark:text-slate-300 max-w-2xl">
                        Selamat datang di panel pengajar. Mesin NLP TF-IDF siap membantu Anda memproses penilaian esai mahasiswa secara otomatis dan akurat.
                    </flux:text>
                </div>
                <flux:button href="{{ route('dosen.subjects.index') }}" variant="primary" icon="academic-cap" wire:navigate class="bg-white text-blue-600 hover:bg-blue-50 transition-all duration-300 shadow-lg hover:shadow-blue-500/30 whitespace-nowrap shrink-0">
                    Kelola Mata Kuliah & Soal
                </flux:button>
            </div>

            {{-- 2. Kartu Statistik Cepat --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-slate-800 rounded-xl p-5 border border-blue-100 dark:border-blue-900/30 shadow-sm flex items-center gap-4 hover:border-blue-300 transition-colors">
                    <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/20 text-blue-600 rounded-lg flex items-center justify-center shrink-0">
                        <flux:icon name="book-open" variant="solid" />
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Kelas Diampu</p>
                        <p class="text-2xl font-black text-slate-800 dark:text-white">{{ $totalKelas }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl p-5 border border-blue-100 dark:border-blue-900/30 shadow-sm flex items-center gap-4 hover:border-blue-300 transition-colors">
                    <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 rounded-lg flex items-center justify-center shrink-0">
                        <flux:icon name="document-text" variant="solid" />
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Ujian</p>
                        <p class="text-2xl font-black text-slate-800 dark:text-white">{{ $totalUjian }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl p-5 border border-emerald-100 dark:border-emerald-900/30 shadow-sm flex items-center gap-4 hover:border-emerald-300 transition-colors">
                    <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 rounded-lg flex items-center justify-center shrink-0">
                        <flux:icon name="check-badge" variant="solid" />
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Esai Dinilai</p>
                        <p class="text-2xl font-black text-slate-800 dark:text-white">{{ $totalDinilai }}</p>
                    </div>
                </div>
            </div>

            {{-- 3. Panel Dua Kolom (Ujian Aktif & Aktivitas Mahasiswa) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                {{-- Kolom Kiri: Jalan Pintas Ujian Terbaru --}}
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <flux:heading level="3" size="md" class="text-slate-900 dark:text-white font-bold">Ujian Terbaru Anda</flux:heading>
                    </div>
                    
                    <div class="space-y-3">
                        @forelse($ujianTerbaru as $ujian)
                            <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors group">
                                <div>
                                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-200 group-hover:text-blue-600">{{ $ujian->title }}</p>
                                    <p class="text-xs text-slate-500">{{ $ujian->subject->subject_name ?? 'Tanpa Mata Kuliah' }} • {{ $ujian->submissions->count() }} Terkumpul</p>
                                </div>
                                <flux:button href="{{ route('dosen.results.show', $ujian->id) }}" variant="filled" color="blue" size="sm" icon="chart-bar" class="shrink-0" wire:navigate>Hasil</flux:button>
                            </div>
                        @empty
                            <div class="text-center py-4 text-sm text-slate-500 dark:text-slate-400">
                                Belum ada ujian yang dibuat.
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Kolom Kanan: Aktivitas Submission Terbaru --}}
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <flux:heading level="3" size="md" class="text-slate-900 dark:text-white font-bold mb-4">Submission Masuk</flux:heading>
                    
                    <div class="space-y-4">
                        @forelse($submissionTerbaru as $submission)
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center text-blue-600 text-xs font-bold shrink-0 mt-0.5 uppercase">
                                    {{ substr($submission->user->name ?? 'U', 0, 2) }}
                                </div>
                                <div>
                                    <p class="text-sm text-slate-800 dark:text-slate-200">
                                        <span class="font-bold">{{ $submission->user->name ?? 'Mahasiswa' }}</span> baru saja mengumpulkan esai.
                                    </p>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $submission->exam->title ?? 'Ujian' }} • {{ $submission->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-sm text-slate-500 dark:text-slate-400">
                                Belum ada aktivitas pengumpulan esai.
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>

    {{-- BAGIAN UNTUK MAHASISWA --}}
    @elseif(Auth::user()->role === 'mahasiswa')
        <div class="space-y-6">
            
            {{-- 1. Kartu Welcome (Utama) --}}
            <div class="rounded-2xl p-8 bg-white dark:bg-slate-800 shadow-sm border border-blue-200 dark:border-blue-500/30 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div>
                    <flux:heading level="2" size="lg" class="text-blue-600 dark:text-blue-400">Halo, {{ Auth::user()->name }}!</flux:heading>
                    <flux:text class="mt-2 text-slate-600 dark:text-slate-300 max-w-2xl">
                        Selamat datang di portal mahasiswa. Siapkan dirimu, kerjakan ujian esai dengan jujur, dan pantau perkembangan nilaimu di sini.
                    </flux:text>
                </div>
                <flux:badge color="blue" size="lg" class="font-bold tracking-widest shrink-0 shadow-sm">
                    NIM: {{ Auth::user()->nim }}
                </flux:badge>
            </div>

            {{-- 2. Kartu Statistik Cepat --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-slate-800 rounded-xl p-5 border border-blue-100 dark:border-blue-900/30 shadow-sm flex items-center gap-4 hover:border-blue-300 transition-colors">
                    <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/20 text-blue-600 rounded-lg flex items-center justify-center shrink-0">
                        <flux:icon name="academic-cap" variant="solid" />
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Kelas Diikuti</p>
                        <p class="text-2xl font-black text-slate-800 dark:text-white">{{ $totalKelasMahasiswa }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl p-5 border border-indigo-100 dark:border-indigo-900/30 shadow-sm flex items-center gap-4 hover:border-indigo-300 transition-colors">
                    <div class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 rounded-lg flex items-center justify-center shrink-0">
                        <flux:icon name="pencil-square" variant="solid" />
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Ujian Selesai</p>
                        <p class="text-2xl font-black text-slate-800 dark:text-white">{{ $ujianSelesai }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl p-5 border border-emerald-100 dark:border-emerald-900/30 shadow-sm flex items-center gap-4 hover:border-emerald-300 transition-colors">
                    <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 rounded-lg flex items-center justify-center shrink-0">
                        <flux:icon name="star" variant="solid" />
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Rata-rata Nilai</p>
                        <p class="text-2xl font-black text-slate-800 dark:text-white">{{ number_format($rataRataNilai, 1) }}</p>
                    </div>
                </div>
            </div>

            {{-- 3. Panel Dua Kolom (Kelas Saya & Aksi Cepat) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Kolom Kiri: Kelas Saya --}}
                <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <flux:heading level="3" size="md" class="text-slate-900 dark:text-white font-bold">Mata Kuliah Anda</flux:heading>
                    </div>
                    
                    <div class="space-y-3">
                        @forelse($kelasSaya as $kelas)
                            <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-300 group">
                                <div class="flex items-center gap-4">
                                    <!-- Singkatan nama kelas (2 huruf awal) -->
                                    <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 font-bold shrink-0 uppercase">
                                        {{ substr($kelas->subject_name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800 dark:text-slate-200 group-hover:text-blue-600">{{ $kelas->subject_name }}</p>
                                        <p class="text-xs text-slate-500 mt-0.5">Dosen: {{ $kelas->user->name ?? '-' }} • {{ $kelas->exams->count() }} Ujian</p>
                                    </div>
                                </div>
                                <flux:button href="{{ route('mahasiswa.subjects.exams', $kelas->id) }}" variant="filled" color="blue" size="sm" icon="arrow-right" class="shrink-0" wire:navigate>Masuk Kelas</flux:button>
                            </div>
                        @empty
                            <div class="text-center py-8 text-sm text-slate-500 dark:text-slate-400 border border-dashed border-slate-200 dark:border-slate-700 rounded-xl">
                                <p class="mb-2">Anda belum bergabung ke kelas mana pun.</p>
                                <flux:button href="{{ route('mahasiswa.subjects.available') }}" variant="subtle" size="sm" wire:navigate>Cari Kelas</flux:button>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Kolom Kanan: Aksi Cepat (Tetap sama seperti yang kamu buat sebelumnya) --}}
                <div class="space-y-6">
                    <div class="p-6 bg-gradient-to-br from-blue-50 to-blue-100/50 dark:from-blue-900/30 dark:to-blue-800/30 rounded-xl border border-blue-200 dark:border-blue-500/50 transition-all duration-300 hover:border-blue-400 dark:hover:border-blue-400/80 hover:-translate-y-1 shadow-sm">
                        <flux:heading size="md" class="text-blue-800 dark:text-blue-300">🔍 Cari Kelas Baru</flux:heading>
                        <flux:text class="mt-2 text-xs text-slate-700 dark:text-slate-300">Gabung ke mata kuliah menggunakan kode akses dari dosen.</flux:text>
                        <flux:button href="{{ route('mahasiswa.subjects.available') }}" variant="subtle" class="mt-4 w-full bg-white dark:bg-slate-800 border border-blue-200 dark:border-blue-500/30 hover:border-blue-400 text-blue-600 transition-all duration-300 shadow-sm" wire:navigate>
                            Lihat & Gabung Kelas
                        </flux:button>
                    </div>

                    <div class="p-6 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/30 dark:to-emerald-900/30 rounded-xl border border-green-200 dark:border-green-500/50 transition-all duration-300 hover:border-green-400 dark:hover:border-green-400/80 hover:-translate-y-1 shadow-sm">
                        <flux:heading size="md" class="text-green-800 dark:text-green-300">📊 Hasil Penilaian</flux:heading>
                        <flux:text class="mt-2 text-xs text-slate-700 dark:text-slate-300">Cek skor esai Anda yang sudah dipublikasikan oleh dosen.</flux:text>
                        <flux:button :href="route('mahasiswa.results.index')" variant="subtle" class="mt-4 w-full bg-white dark:bg-slate-800 border border-green-200 dark:border-green-500/30 hover:border-green-400 text-green-600 transition-all duration-300 shadow-sm">
                            Lihat Transkrip Nilai
                        </flux:button>
                    </div>
                </div>

            </div>
        </div>
    @endif
</div>
@endsection