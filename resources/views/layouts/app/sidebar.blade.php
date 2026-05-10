<flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
    <flux:sidebar.header class="flex items-center gap-2">
        <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
        <flux:sidebar.collapse class="lg:hidden" />
    </flux:sidebar.header>

    <flux:sidebar.nav>
        <flux:sidebar.group :heading="__('Platform')" class="grid">
            {{-- Muncul untuk SEMUA ROLE --}}
            <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </flux:sidebar.item>

            {{-- MENU KHUSUS ADMIN --}}
            @if(auth()->user()->role === 'admin')
                <flux:sidebar.group :heading="__('Admin Panel')" class="grid gap-1">
                    
                    {{-- Menu Utama Kelola Pengguna --}}
                    <flux:sidebar.item 
                        icon="users" 
                        :href="route('admin.users.index', ['role' => 'dosen'])"
                        :current="request()->is('admin/users*')"
                        wire:navigate
                    >
                        {{ __('Kelola Pengguna') }}
                    </flux:sidebar.item>

                    {{-- Sub-Menu Otomatis Terbuka jika URL mengandung 'admin/users' --}}
                    @if(request()->is('admin/users*'))
                        <div class="ml-4 mb-2 border-l-2 border-zinc-200 dark:border-zinc-700 pl-3 flex flex-col gap-1 transition-all">
                            <flux:sidebar.item 
                                :href="route('admin.users.index', ['role' => 'dosen'])" 
                                :current="request()->fullUrlIs(route('admin.users.index', ['role' => 'dosen']))" 
                                size="sm"
                                wire:navigate
                            >
                                <span class="{{ request()->fullUrlIs(route('admin.users.index', ['role' => 'dosen'])) ? 'font-bold text-indigo-600' : '' }}">
                                    {{ __('Daftar Dosen') }}
                                </span>
                            </flux:sidebar.item>

                            <flux:sidebar.item 
                                :href="route('admin.users.index', ['role' => 'mahasiswa'])" 
                                :current="request()->fullUrlIs(route('admin.users.index', ['role' => 'mahasiswa']))" 
                                size="sm"
                                wire:navigate
                            >
                                <span class="{{ request()->fullUrlIs(route('admin.users.index', ['role' => 'mahasiswa'])) ? 'font-bold text-indigo-600' : '' }}">
                                    {{ __('Daftar Mahasiswa') }}
                                </span>
                            </flux:sidebar.item>
                        </div>
                    @endif

                    {{-- Menu Kelola Mata Kuliah --}}
                    <flux:sidebar.item 
                        icon="book-open" 
                        :href="route('admin.subjects.index')" 
                        :current="request()->routeIs('admin.subjects.*')" 
                        wire:navigate
                    >
                        {{ __('Kelola Mata Kuliah') }}
                    </flux:sidebar.item>

                </flux:sidebar.group>
            @endif

            {{-- MENU KHUSUS DOSEN --}}
            @if(auth()->user()->role === 'dosen')
                <flux:sidebar.group :heading="__('Dosen Panel')" class="grid gap-1">
                    <flux:sidebar.item icon="academic-cap" :href="route('dosen.subjects.index')" :current="request()->routeIs('subjects.*')" wire:navigate>
                        {{ __('Mata Kuliah Saya') }}
                        <flux:badge size="sm" inset="top bottom" class="ml-auto">
                            {{ auth()->user()->total_students_count }}
                        </flux:badge>
                    </flux:sidebar.item>

                    {{-- Menu Buat Ujian --}}
                    <flux:sidebar.item icon="pencil-square" :href="route('dosen.exams.index')" :current="request()->routeIs('exams.*')" wire:navigate>
                        {{ __('Buat Ujian') }}
                    </flux:sidebar.item>

                    <flux:navlist.item icon="clipboard-document-check" href="{{ route('dosen.results.index') }}">
                        Hasil Ujian
                    </flux:navlist.item>

                    {{-- Menu Daftar Mahasiswa di MK --}}
                    <flux:sidebar.item icon="users" :href="route('dosen.students.index')" :current="request()->routeIs('dosen.students.*')" wire:navigate>
                        {{ __('Daftar Mahasiswa') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            @endif

            {{-- MENU KHUSUS MAHASISWA --}}
           @if(auth()->user()->role === 'mahasiswa')
                <flux:sidebar.item icon="academic-cap" :href="route('mahasiswa.subjects.index')" :current="request()->routeIs('mahasiswa.subjects.*')" wire:navigate>
                    {{ __('Mata Kuliah Saya') }}
                </flux:sidebar.item>
            @endif
        </flux:sidebar.group>
    </flux:sidebar.nav>

    <flux:spacer />

    {{-- Bagian Bawah Sidebar --}}
    <flux:sidebar.nav>
        <flux:sidebar.item icon="cog-6-tooth" :href="route('profile.edit')" :current="request()->routeIs('profile.edit')" wire:navigate>
            {{ __('Settings') }}
        </flux:sidebar.item>
    </flux:sidebar.nav>

    {{-- User Menu di Pojok Kiri Bawah --}}
    <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
</flux:sidebar>

{{-- PENTING: Header untuk Mobile agar sidebar bisa di-toggle --}}
<flux:header class="lg:hidden">
    <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
    <flux:spacer />
</flux:header>

{{-- DISINI KONTEN UTAMA AKAN MUNCUL --}}
<flux:main>
   <div class="p-6 md:p-10"> {{-- Tambahkan padding di sini agar konten tidak mepet --}}
        {{ $slot }}
    </div>
</flux:main>