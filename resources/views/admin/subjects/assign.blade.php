@extends('layouts.app')

@section('content')
<div class="p-6 max-w-2xl mx-auto space-y-6">
    <header>
        <flux:heading size="xl">Tugaskan Dosen Pengampu</flux:heading>
        <flux:subheading>Pilih dosen untuk mengampu mata kuliah: <b>{{ $subject->subject_name }}</b></flux:subheading>
    </header>

    <flux:card>
        <form action="{{ route('admin.subjects.update-tutor', $subject->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Komponen Select Flux --}}
            <flux:select name="user_id" label="Pilih Dosen" placeholder="Pilih nama dosen...">
                @foreach($dosens as $dosen)
                    <flux:select.option value="{{ $dosen->id }}" :selected="$subject->user_id == $dosen->id">
                        {{ $dosen->name }}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <div class="flex gap-2">
                <flux:button type="submit" variant="primary">Simpan Perubahan</flux:button>
                <flux:button :href="route('admin.subjects.index')" variant="ghost">Batal</flux:button>
            </div>
        </form>
    </flux:card>
</div>
@endsection