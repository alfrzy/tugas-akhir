<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in - EduClarity</title>
    <!-- Memastikan Tailwind dimuat -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans text-slate-900 antialiased">
    <style>
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-slide-in-left {
            animation: slideInLeft 0.6s ease-out;
        }
        .animate-slide-in-right {
            animation: slideInRight 0.6s ease-out;
        }
        .animate-fade-in-scale {
            animation: fadeInScale 0.5s ease-out;
        }
        .animate-slide-up {
            animation: slideUp 0.5s ease-out;
        }
        .input-focus-glow:focus {
            box-shadow: 0 0 20px rgba(37, 99, 235, 0.3);
        }
        .btn-press:active {
            transform: scale(0.98);
        }
    </style>
    <div class="flex min-h-screen w-full">
        
        <!-- BAGIAN KIRI: BANNER GAMBAR (Sama dengan halaman Register) -->
        <div class="relative hidden w-1/2 flex-col items-center justify-end bg-blue-600 bg-cover bg-center bg-no-repeat p-12 lg:flex animate-slide-in-left" 
             style="background-image: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=2071&auto=format&fit=crop'); background-blend-mode: overlay;">
            
            <div class="absolute inset-0 bg-gradient-to-t from-blue-900/90 via-blue-900/40 to-transparent"></div>
            
            <div class="relative z-10 text-center text-white pb-10">
                <h2 class="mb-4 text-4xl font-black tracking-tight">Unlock Your Potential</h2>
                <p class="mx-auto max-w-sm text-lg font-medium text-blue-100 leading-relaxed">
                    Join a community of focused learners. Access curated resources, track your progress, and achieve academic clarity.
                </p>
            </div>
        </div>

        <!-- BAGIAN KANAN: FORM LOGIN -->
        <div class="flex w-full flex-col items-center justify-center bg-slate-50 p-6 lg:w-1/2 animate-slide-in-right">
            <div class="w-full max-w-md">
                
                <!-- Judul Halaman -->
                <div class="mb-8 text-center animate-fade-in-scale" style="animation-delay: 0.1s;">
                    <h1 class="text-4xl font-black text-blue-600 tracking-tighter">EduClarity</h1>
                    <p class="mt-2 text-slate-500 font-medium">Log in to your account to continue.</p>
                </div>

                <!-- Session Status (Untuk pesan error/sukses dari Laravel) -->
                @if (session('status'))
                    <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm font-medium text-green-600 border border-green-200 text-center">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Kartu Form Putih -->
                <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/50 animate-fade-in-scale" style="animation-delay: 0.2s;">
                    <form method="POST" action="{{ route('login.store') }}" class="space-y-6">
                        @csrf
                        
                        <!-- Email Address -->
                        <div class="animate-slide-up" style="animation-delay: 0.3s;">
                            <label class="mb-1 block text-sm font-bold text-slate-700">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-700 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-300 input-focus-glow"
                                placeholder="email@example.com">
                            @error('email')
                                <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="animate-slide-up" style="animation-delay: 0.4s;">
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-sm font-bold text-slate-700">Password</label>
                                <!-- Forgot Password Link -->
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline">
                                        Forgot your password?
                                    </a>
                                @endif
                            </div>
                            <input type="password" name="password" required autocomplete="current-password"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-700 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-300 input-focus-glow"
                                placeholder="••••••••">
                            @error('password')
                                <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center animate-slide-up" style="animation-delay: 0.5s;">
                            <input id="remember_me" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer transition-colors">
                            <label for="remember_me" class="ml-2 block text-sm font-medium text-slate-600 cursor-pointer">
                                Remember me
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2 animate-slide-up" style="animation-delay: 0.6s;">
                            <button type="submit" 
                                class="flex w-full items-center justify-center rounded-lg bg-blue-600 px-4 py-3 font-bold text-white transition-all duration-300 hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-500/50 focus:outline-none focus:ring-4 focus:ring-blue-500/30 btn-press">
                                Log in <span class="ml-2">&rarr;</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer Register -->
                @if (Route::has('register'))
                    <div class="mt-8 text-center text-sm font-medium text-slate-500 animate-slide-up" style="animation-delay: 0.7s;">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:text-blue-700 hover:underline transition-colors duration-300">Sign up</a>
                    </div>
                @endif
                
            </div>
        </div>
    </div>
</body>
</html>