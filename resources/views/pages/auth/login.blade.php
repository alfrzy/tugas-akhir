<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in - AutoGrader NLP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-in-left { animation: slideInLeft 0.6s ease-out; }
        .animate-slide-in-right { animation: slideInRight 0.6s ease-out; }
        .animate-fade-in-scale { animation: fadeInScale 0.5s ease-out backwards; }
        .animate-slide-up { animation: slideUp 0.5s ease-out backwards; }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-900 antialiased overflow-x-hidden">
    
    <div class="flex min-h-screen w-full">
        
        <div class="relative hidden w-1/2 flex-col items-center justify-end bg-blue-600 bg-cover bg-center bg-no-repeat p-12 lg:flex animate-slide-in-left" 
             style="background-image: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=2071&auto=format&fit=crop'); background-blend-mode: overlay;">
            
            <div class="absolute inset-0 bg-gradient-to-t from-blue-900/95 via-blue-900/60 to-transparent"></div>
            
            <div class="relative z-10 text-center text-white pb-10">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md mb-6 border border-white/20 shadow-2xl">
                    <flux:icon name="sparkles" variant="solid" class="w-8 h-8 text-blue-300" />
                </div>
                <h2 class="mb-4 text-4xl font-black tracking-tight">Revolusi Penilaian Esai</h2>
                <p class="mx-auto max-w-md text-lg font-medium text-blue-100 leading-relaxed">
                    Platform evaluasi ujian berbasis <strong>Natural Language Processing (NLP)</strong>. Mengoreksi jawaban mahasiswa secara instan, objektif, dan akurat.
                </p>
            </div>
        </div>

        <div class="flex w-full flex-col items-center justify-center bg-slate-50 p-6 lg:w-1/2 animate-slide-in-right">
            
            <div class="w-full max-w-md">
                
                <div class="mb-8 text-center flex flex-col items-center animate-fade-in-scale" style="animation-delay: 0.1s;">
                    {{-- Logo Custom --}}
                    <div class="relative flex items-center justify-center w-14 h-14 mb-4 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 shadow-lg shadow-blue-500/30">
                        <flux:icon name="sparkles" variant="solid" class="w-8 h-8 text-white" />
                        <div class="absolute -bottom-1.5 -right-1.5 w-6 h-6 bg-emerald-500 border-[3px] border-slate-50 rounded-full flex items-center justify-center">
                            <flux:icon name="check" variant="micro" class="text-white w-4 h-4 font-black" />
                        </div>
                    </div>

                    {{-- Nama App --}}
                    <h1 class="text-3xl font-black tracking-tight text-slate-900">
                        Auto<span class="text-blue-600">Grader</span>
                    </h1>
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-500 mt-1">NLP Scoring Engine</p>
                </div>

                @if (session('status'))
                    <div class="mb-4 rounded-lg bg-emerald-50 p-4 text-sm font-medium text-emerald-600 border border-emerald-200 text-center animate-slide-up">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/50 animate-fade-in-scale" style="animation-delay: 0.2s;">
                    <form method="POST" action="{{ route('login.store') }}" class="space-y-6">
                        @csrf
                        
                        <div class="animate-slide-up" style="animation-delay: 0.3s;">
                            <label class="mb-1.5 block text-sm font-bold text-slate-700">Alamat Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-700 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:shadow-[0_0_15px_rgba(37,99,235,0.15)] transition-all duration-300"
                                placeholder="dosen@universitas.ac.id">
                            @error('email')
                                <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="animate-slide-up" style="animation-delay: 0.4s;">
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-sm font-bold text-slate-700">Kata Sandi</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline transition-colors">
                                        Lupa sandi?
                                    </a>
                                @endif
                            </div>
                            
                            <div class="relative">
                                <input type="password" id="password-input" name="password" required autocomplete="current-password"
                                    class="w-full rounded-lg border border-slate-300 bg-white pl-4 pr-12 py-2.5 text-slate-700 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:shadow-[0_0_15px_rgba(37,99,235,0.15)] transition-all duration-300"
                                    placeholder="••••••••">
                                
                                {{-- Tombol Mata (Show/Hide) --}}
                                <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-blue-600 transition-colors focus:outline-none">
                                    <span id="eye-icon"><flux:icon name="eye" variant="mini" /></span>
                                    <span id="eye-slash-icon" class="hidden"><flux:icon name="eye-slash" variant="mini" /></span>
                                </button>
                            </div>
                            
                            @error('password')
                                <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center animate-slide-up" style="animation-delay: 0.5s;">
                            <input id="remember_me" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer transition-colors">
                            <label for="remember_me" class="ml-2 block text-sm font-medium text-slate-600 cursor-pointer select-none">
                                Ingat saya
                            </label>
                        </div>

                        <div class="pt-2 animate-slide-up" style="animation-delay: 0.6s;">
                            <button type="submit" 
                                class="flex w-full items-center justify-center rounded-lg bg-blue-600 px-4 py-3 font-bold text-white transition-all duration-300 hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-500/40 focus:outline-none focus:ring-4 focus:ring-blue-500/30 active:scale-95">
                                Masuk ke Sistem <span class="ml-2">&rarr;</span>
                            </button>
                        </div>
                    </form>
                </div>

                @if (Route::has('register'))
                    <div class="mt-8 text-center text-sm font-medium text-slate-500 animate-slide-up" style="animation-delay: 0.7s;">
                        Belum punya akun akademik? 
                        <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:text-blue-700 hover:underline transition-colors duration-300">Daftar sekarang</a>
                    </div>
                @endif
                
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password-input');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeSlashIcon = document.getElementById('eye-slash-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeSlashIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeSlashIcon.classList.add('hidden');
            }
        }
    </script>
</body>
</html>