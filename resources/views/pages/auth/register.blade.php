<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - EduClarity</title>
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
        
        <!-- BAGIAN KIRI: BANNER GAMBAR (Aman jika gambar gagal dimuat) -->
        <!-- Jika gambar rusak, bg-blue-600 akan mengambil alih menjadi latar belakang warna solid -->
        <div class="relative hidden w-1/2 flex-col items-center justify-end bg-blue-600 bg-cover bg-center bg-no-repeat p-12 lg:flex animate-slide-in-left" 
             style="background-image: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=2071&auto=format&fit=crop'); background-blend-mode: overlay;">
            
            <!-- Efek gradien gelap di bawah agar teks mudah dibaca -->
            <div class="absolute inset-0 bg-gradient-to-t from-blue-900/90 via-blue-900/40 to-transparent"></div>
            
            <!-- Teks EduClarity di Banner Kiri -->
            <div class="relative z-10 text-center text-white pb-10">
                <h2 class="mb-4 text-4xl font-black tracking-tight">Unlock Your Potential</h2>
                <p class="mx-auto max-w-sm text-lg font-medium text-blue-100 leading-relaxed">
                    Join a community of focused learners. Access curated resources, track your progress, and achieve academic clarity.
                </p>
            </div>
        </div>

        <!-- BAGIAN KANAN: FORM REGISTRASI -->
        <div class="flex w-full flex-col items-center justify-center bg-slate-50 p-6 lg:w-1/2 animate-slide-in-right">
            <div class="w-full max-w-md">
                
                <!-- Judul Halaman -->
                <div class="mb-8 text-center animate-fade-in-scale" style="animation-delay: 0.1s;">
                    <h1 class="text-4xl font-black text-blue-600 tracking-tighter">EduClarity</h1>
                    <p class="mt-2 text-slate-500 font-medium">Create your student account to get started.</p>
                </div>

                <!-- Kartu Form Putih -->
                <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/50 animate-fade-in-scale" style="animation-delay: 0.2s;">
                    <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
                        @csrf
                        
                        <!-- Full Name -->
                        <div class="animate-slide-up" style="animation-delay: 0.3s;">
                            <label class="mb-1 block text-sm font-bold text-slate-700">Full Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required autofocus
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-700 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-300 input-focus-glow"
                                placeholder="Jane Doe">
                        </div>

                        <!-- Register As -->
                        <div class="animate-slide-up" style="animation-delay: 0.4s;">
                            <label class="mb-1 block text-sm font-bold text-slate-700">Register As</label>
                            <select name="role" required
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-300 appearance-none input-focus-glow">
                                <option value="" disabled selected>Pilih Peran...</option>
                                <option value="mahasiswa">Mahasiswa</option>
                                <option value="dosen">Dosen / Guru</option>
                            </select>
                        </div>

                        <!-- NIM / Student ID -->
                        <div class="animate-slide-up" style="animation-delay: 0.5s;">
                            <label class="mb-1 block text-sm font-bold text-slate-700">NIM / Student ID</label>
                            <input type="text" name="nim" value="{{ old('nim') }}"
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-700 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-300 input-focus-glow"
                                placeholder="123456789">
                        </div>

                        <!-- University Email -->
                        <div class="animate-slide-up" style="animation-delay: 0.6s;">
                            <label class="mb-1 block text-sm font-bold text-slate-700">University Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-700 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-300 input-focus-glow"
                                placeholder="jane.doe@university.edu">
                        </div>

                        <!-- Password -->
                        <div class="animate-slide-up" style="animation-delay: 0.7s;">
                            <label class="mb-1 block text-sm font-bold text-slate-700">Password</label>
                            <input type="password" name="password" required
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-700 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-300 input-focus-glow"
                                placeholder="••••••••">
                            <p class="mt-1 text-xs text-slate-400">Must be at least 8 characters long.</p>
                        </div>

                        <!-- Confirm Password -->
                        <div class="animate-slide-up" style="animation-delay: 0.8s;">
                            <label class="mb-1 block text-sm font-bold text-slate-700">Confirm Password</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-700 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all duration-300 input-focus-glow"
                                placeholder="••••••••">
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2 animate-slide-up" style="animation-delay: 0.9s;">
                            <button type="submit" 
                                class="flex w-full items-center justify-center rounded-lg bg-blue-600 px-4 py-3 font-bold text-white transition-all duration-300 hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-500/50 focus:outline-none focus:ring-4 focus:ring-blue-500/30 btn-press">
                                Create Account <span class="ml-2">&rarr;</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer Login -->
                <div class="mt-8 text-center text-sm font-medium text-slate-500 animate-slide-up" style="animation-delay: 1s;">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-700 hover:underline transition-colors duration-300">Log in</a>
                </div>
                
            </div>
        </div>
    </div>
</body>
</html>