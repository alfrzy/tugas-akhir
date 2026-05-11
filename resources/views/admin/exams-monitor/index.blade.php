@extends('layouts.app')

@section('content')
<div class="p-6 space-y-8">
    <header class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">Pantau Ujian Global</flux:heading>
            <flux:subheading>Lihat seluruh aktivitas ujian dari semua dosen di sistem.</flux:subheading>
        </div>
    </header>

    <flux:card class="p-0 overflow-hidden shadow-sm">
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Mata Kuliah & Dosen</flux:table.column>
                <flux:table.column>Judul Ujian</flux:table.column>
                <flux:table.column>Tanggal Dibuat</flux:table.column>
                <flux:table.column align="center">Esai Masuk</flux:table.column>
                <flux:table.column align="end">Status</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse($exams as $exam)
                    <flux:table.row>
                        <flux:table.cell>
                            <p class="font-bold text-slate-800 dark:text-slate-200">{{ $exam->subject->subject_name }}</p>
                            <p class="text-xs text-slate-500">Oleh: {{ $exam->subject->user->name ?? 'Dosen Tidak Diketahui' }}</p>
                        </flux:table.cell>
                        <flux:table.cell>{{ $exam->title }}</flux:table.cell>
                        <flux:table.cell class="text-zinc-500">{{ $exam->created_at->format('d M Y, H:i') }}</flux:table.cell>
                        <flux:table.cell align="center">
                            <flux:badge color="blue">{{ $exam->submissions_count }} Lembar</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell align="end">
                            @if($exam->status === 'Aktif')
                                <flux:badge color="emerald" variant="subtle" icon="play-circle">Sedang Aktif</flux:badge>
                            @elseif($exam->status === 'Belum Mulai')
                                <flux:badge color="amber" variant="subtle" icon="clock">Belum Mulai</flux:badge>
                            @else
                                <flux:badge color="red" variant="subtle" icon="stop-circle">Ditutup</flux:badge>
                            @endif
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="5" class="text-center py-10 text-zinc-500">
                            Belum ada ujian yang dibuat oleh dosen mana pun.
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
        
        {{-- Navigasi Paginasi (jika data lebih dari 10) --}}
        <div class="p-4 border-t border-slate-100 dark:border-slate-800">
            {{ $exams->links() }}
        </div>
    </flux:card>
</div>
@endsection