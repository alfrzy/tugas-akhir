@extends('layouts.app')

@section('content')
<div class="p-6 max-w-3xl mx-auto space-y-8">
    
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm">
            <flux:icon name="check-circle" variant="solid" class="w-5 h-5 text-emerald-500" />
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <header>
        <flux:heading size="xl">Pengaturan Sistem</flux:heading>
        <flux:subheading>Konfigurasi parameter utama untuk aplikasi akademik.</flux:subheading>
    </header>

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        <flux:card class="space-y-6 shadow-sm">
            
            <div>
                <flux:heading size="lg">Tahun Ajaran & Semester</flux:heading>
                <flux:text class="text-xs text-slate-500 mb-4">Pengaturan ini akan muncul di dashboard dosen dan mahasiswa.</flux:text>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input label="Tahun Ajaran Aktif" name="academic_year" value="{{ $currentSettings['academic_year'] }}" placeholder="Contoh: 2025/2026" required />
                    
                    <flux:select label="Semester Aktif" name="semester" required>
                        <option value="Ganjil" {{ $currentSettings['semester'] == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                        <option value="Genap" {{ $currentSettings['semester'] == 'Genap' ? 'selected' : '' }}>Genap</option>
                        <option value="Pendek" {{ $currentSettings['semester'] == 'Pendek' ? 'selected' : '' }}>Semester Pendek</option>
                    </flux:select>
                </div>
            </div>

            <hr class="border-slate-200 dark:border-slate-700">

            <div>
                <flux:heading size="lg">Keamanan & Akses</flux:heading>
                <flux:text class="text-xs text-slate-500 mb-4">Atur status akses ke dalam sistem saat ini.</flux:text>
                
                <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-100 dark:border-slate-700">
                    <div>
                        <p class="font-bold text-sm text-slate-800 dark:text-slate-200">Mode Perbaikan (Maintenance Mode)</p>
                        <p class="text-xs text-slate-500 mt-1">Jika aktif, mahasiswa tidak dapat mengakses ujian.</p>
                    </div>
                    {{-- Ganti dengan komponen Toggle bawaan flux jika ada, atau gunakan checkbox --}}
                    <flux:checkbox name="maintenance_mode" label="Aktifkan" :checked="$currentSettings['maintenance_mode']" />
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <flux:button type="submit" variant="primary" icon="check" class="bg-blue-600 hover:bg-blue-700">Simpan Pengaturan</flux:button>
            </div>
            
        </flux:card>
    </form>
</div>
@endsection