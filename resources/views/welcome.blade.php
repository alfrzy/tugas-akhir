<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoGrader - NLP Scoring Engine</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-sans text-slate-900 antialiased overflow-x-hidden selection:bg-blue-200 selection:text-blue-900">

    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200/50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            
            <a href="#" class="flex items-center gap-3 transition-transform hover:scale-105">
                <div class="relative flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 shadow-lg shadow-blue-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7" /></svg>
                    </div>
                </div>
                <div class="flex flex-col">
                    <span class="font-black text-xl leading-none tracking-tight text-slate-900">
                        Auto<span class="text-blue-600">Grader</span>
                    </span>
                    <span class="text-[9px] font-bold uppercase tracking-widest text-slate-500 mt-0.5">NLP Scoring Engine</span>
                </div>
            </a>

            <div class="hidden md:flex items-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="font-bold text-slate-600 hover:text-blue-600 transition-colors">Masuk Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="font-bold text-slate-600 hover:text-blue-600 transition-colors">Log in</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2.5 rounded-xl shadow-lg shadow-blue-500/30 transition-all hover:-translate-y-0.5">
                        Daftar Gratis
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="absolute top-0 right-0 -translate-y-12 translate-x-1/3 w-[800px] h-[800px] bg-gradient-to-br from-blue-100 to-indigo-50 rounded-full blur-3xl opacity-50 -z-10"></div>
        <div class="absolute bottom-0 left-0 translate-y-1/3 -translate-x-1/3 w-[600px] h-[600px] bg-gradient-to-tr from-emerald-50 to-blue-50 rounded-full blur-3xl opacity-50 -z-10"></div>

        <div class="max-w-7xl mx-auto px-6 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 border border-blue-100 text-blue-600 text-sm font-bold tracking-wide mb-8 animate-fade-in-up">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                </span>
                Versi 1.0 Telah Rilis!
            </div>

            <h1 class="text-5xl md:text-6xl lg:text-7xl font-black tracking-tight text-slate-900 mb-6 max-w-5xl mx-auto leading-tight">
                Koreksi Ujian Esai <br class="hidden md:block" />
                Lebih Cepat dengan <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Kecerdasan Buatan</span>
            </h1>
            
            <p class="text-lg md:text-xl text-slate-600 mb-10 max-w-2xl mx-auto leading-relaxed">
                Tinggalkan cara manual. AutoGrader menggunakan teknologi <strong>Natural Language Processing (NLP)</strong> untuk menganalisis dan menilai jawaban mahasiswa secara otomatis, adil, dan instan.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold text-lg px-8 py-4 rounded-xl shadow-xl shadow-blue-500/30 transition-all hover:-translate-y-1 flex items-center justify-center gap-2">
                    Mulai Sekarang <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </a>
                <a href="#cara-kerja" class="w-full sm:w-auto bg-white border-2 border-slate-200 hover:border-slate-300 text-slate-700 font-bold text-lg px-8 py-4 rounded-xl transition-all flex items-center justify-center gap-2">
                    Pelajari Cara Kerja
                </a>
            </div>
        </div>
    </section>

    <section id="cara-kerja" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-black text-slate-900">Dibangun untuk Pendidikan Modern</h2>
                <p class="mt-4 text-slate-500 text-lg">Inovasi yang memudahkan Dosen dan mempercepat Mahasiswa.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100 hover:shadow-xl transition-shadow duration-300 group">
                    <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Algoritma NLP Presisi</h3>
                    <p class="text-slate-600 leading-relaxed">Sistem mendeteksi kemiripan semantik antara jawaban mahasiswa dan kunci jawaban dosen menggunakan metode TF-IDF Cosine Similarity.</p>
                </div>

                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100 hover:shadow-xl transition-shadow duration-300 group">
                    <div class="w-14 h-14 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Manajemen Jadwal Cerdas</h3>
                    <p class="text-slate-600 leading-relaxed">Atur waktu mulai dan *deadline* dengan sistem penalti otomatis. Terlambat mengumpulkan? Sistem otomatis memotong persentase nilai.</p>
                </div>

                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100 hover:shadow-xl transition-shadow duration-300 group">
                    <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Analisis Rinci & Transparan</h3>
                    <p class="text-slate-600 leading-relaxed">Baik dosen maupun mahasiswa dapat melihat log perhitungan *scoring* untuk memastikan transparansi dan mencegah protes nilai.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-slate-900 text-slate-400 py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between">
            <div class="flex items-center gap-2 mb-4 md:mb-0">
                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                <span class="font-bold text-slate-200 tracking-tight">AutoGrader Engine</span>
            </div>
            <p class="text-sm">Hak Cipta &copy; 2026. Dibangun untuk efisiensi pendidikan.</p>
        </div>
    </footer>

</body>
</html>