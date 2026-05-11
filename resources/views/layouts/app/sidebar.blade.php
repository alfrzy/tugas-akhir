<flux:sidebar sticky collapsible="mobile" class="border-e border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900 shadow-sm transition-all duration-300">
    
    {{-- HEADER & CUSTOM LOGO --}}
    <flux:sidebar.header class="flex items-center justify-between w-full pt-2 pb-4">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 transition-transform hover:scale-105" wire:navigate>
            {{-- Ikon Logo (Gradasi Biru-Indigo dengan efek shadow) --}}
            <div class="relative flex items-center justify-center w-9 h-9 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 shadow-lg shadow-blue-500/30">
                {{-- Menggunakan icon dokumen dan AI/Sparkles bawaan Flux/Heroicons --}}
                <flux:icon name="sparkles" variant="solid" class="w-5 h-5 text-white" />
                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 border-2 border-white dark:border-slate-900 rounded-full flex items-center justify-center">
                    <flux:icon name="check" variant="micro" class="text-white w-3 h-3 font-black" />
                </div>
            </div>
            
            {{-- Teks Logo --}}
            <div class="flex flex-col">
                <span class="font-black text-xl leading-none tracking-tight text-slate-900 dark:text-white">
                    Auto<span class="text-blue-600 dark:text-blue-400">Grader</span>
                </span>
                <span class="text-[9px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mt-0.5">NLP Scoring Engine</span>
            </div>
        </a>
        
        {{-- Tombol Tutup Sidebar untuk Mobile --}}
        <flux:sidebar.collapse class="lg:hidden" />
    </flux:sidebar.header>

    <flux:sidebar.nav class="mt-4">
        <flux:sidebar.group :heading="__('Platform')" class="grid gap-1">
            
            {{-- Muncul untuk SEMUA ROLE --}}
            <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate class="hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 transition-colors">
                {{ __('Dashboard') }}
            </flux:sidebar.item>

            {{-- ======================================= --}}
            {{-- MENU KHUSUS ADMIN --}}
            {{-- ======================================= --}}
            @if(auth()->user()->role === 'admin')
                <flux:sidebar.group :heading="__('Admin Panel')" class="grid gap-1 mt-4">
                    <flux:sidebar.item icon="users" :href="route('admin.users.index', ['role' => 'dosen'])" :current="request()->is('admin/users*')" wire:navigate class="hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600">
                        {{ __('Kelola Pengguna') }}
                    </flux:sidebar.item>

                    @if(request()->is('admin/users*'))
                        <div class="ml-4 mb-2 border-l-2 border-slate-200 dark:border-slate-700 pl-3 flex flex-col gap-1 transition-all">
                            <flux:sidebar.item :href="route('admin.users.index', ['role' => 'dosen'])" :current="request()->fullUrlIs(route('admin.users.index', ['role' => 'dosen']))" size="sm" wire:navigate>
                                <span class="{{ request()->fullUrlIs(route('admin.users.index', ['role' => 'dosen'])) ? 'font-bold text-blue-600' : '' }}">{{ __('Daftar Dosen') }}</span>
                            </flux:sidebar.item>
                            <flux:sidebar.item :href="route('admin.users.index', ['role' => 'mahasiswa'])" :current="request()->fullUrlIs(route('admin.users.index', ['role' => 'mahasiswa']))" size="sm" wire:navigate>
                                <span class="{{ request()->fullUrlIs(route('admin.users.index', ['role' => 'mahasiswa'])) ? 'font-bold text-blue-600' : '' }}">{{ __('Daftar Mahasiswa') }}</span>
                            </flux:sidebar.item>
                        </div>
                    @endif

                    <flux:sidebar.item icon="book-open" :href="route('admin.subjects.index')" :current="request()->routeIs('admin.subjects.*')" wire:navigate class="hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600">
                        {{ __('Kelola Mata Kuliah') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="clipboard-document-list" href="{{ route('admin.exams.monitor') }}" :current="request()->routeIs('admin.exams.monitor')" wire:navigate class="hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600">
                        {{ __('Pantau Ujian') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="adjustments-horizontal" href="{{ route('admin.settings.index') }}" :current="request()->routeIs('admin.settings.index')" wire:navigate class="hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600">
                        {{ __('Pengaturan Sistem') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            @endif

            {{-- ======================================= --}}
            {{-- MENU KHUSUS DOSEN --}}
            {{-- ======================================= --}}
            @if(auth()->user()->role === 'dosen')
                <flux:sidebar.group :heading="__('Dosen Panel')" class="grid gap-1 mt-4">
                    <flux:sidebar.item icon="academic-cap" :href="route('dosen.subjects.index')" :current="request()->routeIs('dosen.subjects.*')" wire:navigate class="hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600">
                        {{ __('Mata Kuliah Saya') }}
                        <flux:badge size="sm" color="blue" inset="top bottom" class="ml-auto">
                            {{ \App\Models\Subject::where('user_id', auth()->id())->count() }}
                        </flux:badge>
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="pencil-square" :href="route('dosen.exams.index')" :current="request()->routeIs('dosen.exams.*')" wire:navigate class="hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600">
                        {{ __('Buat Ujian') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="clipboard-document-check" :href="route('dosen.results.index')" :current="request()->routeIs('dosen.results.*')" wire:navigate class="hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600">
                        {{ __('Hasil Ujian') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="users" :href="route('dosen.students.index')" :current="request()->routeIs('dosen.students.*')" wire:navigate class="hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600">
                        {{ __('Daftar Mahasiswa') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="table-cells" :href="route('dosen.results.recap')" :current="request()->routeIs('dosen.results.recap')" wire:navigate class="hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600">
                        {{ __('Rekap Nilai') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            @endif

            {{-- ======================================= --}}
            {{-- MENU KHUSUS MAHASISWA --}}
            {{-- ======================================= --}}
            @if(auth()->user()->role === 'mahasiswa')
                <flux:sidebar.group :heading="__('Ruang Belajar')" class="grid gap-1 mt-4">
                    <flux:sidebar.item icon="academic-cap" :href="route('mahasiswa.subjects.index')" :current="request()->routeIs('mahasiswa.subjects.index', 'mahasiswa.subjects.exams')" wire:navigate class="hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600">
                        {{ __('Mata Kuliah Saya') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="magnifying-glass-plus" :href="route('mahasiswa.subjects.available')" :current="request()->routeIs('mahasiswa.subjects.available')" wire:navigate class="hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600">
                        {{ __('Gabung Kelas Baru') }}
                    </flux:sidebar.item>

                    {{-- Link Riwayat Nilai bisa diarahkan nanti --}}
                    <flux:sidebar.item icon="chart-bar" :href="route('mahasiswa.results.index')" wire:navigate class="hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600">
                        {{ __('Riwayat Nilai') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            @endif

        </flux:sidebar.group>
    </flux:sidebar.nav>

    <flux:spacer />

    {{-- Bagian Bawah Sidebar --}}
    <flux:sidebar.nav>
        <flux:sidebar.item icon="cog-6-tooth" :href="route('profile.edit')" :current="request()->routeIs('profile.edit')" wire:navigate class="hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600">
            {{ __('Pengaturan Akun') }}
        </flux:sidebar.item>
    </flux:sidebar.nav>

    {{-- User Menu di Pojok Kiri Bawah --}}
    <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-800">
        <x-desktop-user-menu class="hidden lg:block w-full" :name="auth()->user()->name" />
    </div>
</flux:sidebar>

{{-- HEADER MOBILE --}}
<flux:header class="lg:hidden bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800">
    <div class="flex items-center justify-between w-full px-4 py-2">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-3" />
        
        {{-- Logo Versi Mobile --}}
        <div class="flex items-center gap-2">
            <flux:icon name="sparkles" variant="solid" class="w-5 h-5 text-blue-600" />
            <span class="font-black text-lg tracking-tight text-slate-900 dark:text-white">
                Auto<span class="text-blue-600">Grader</span>
            </span>
        </div>
        
        {{-- Placeholder untuk keseimbangan layout header --}}
        <div class="w-8"></div> 
    </div>
</flux:header>

{{-- KONTEN UTAMA --}}
<flux:main class="bg-slate-50 dark:bg-slate-950 min-h-screen">
    <div class="p-4 md:p-8 lg:p-10"> 
        {{ $slot }}
    </div>
</flux:main>