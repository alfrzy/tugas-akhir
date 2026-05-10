<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        {{-- Gunakan komponen sidebar bawaan flux sebagai wrapper --}}
        <x-layouts::app.sidebar :title="$title ?? null">
            
            {{-- Flux Main adalah area konten di sebelah kanan sidebar --}}
            <flux:main>
                @yield('content')
                {{ $slot ?? '' }}
            </flux:main>

        </x-layouts::app.sidebar>

        @fluxScripts
    </body>
</html>