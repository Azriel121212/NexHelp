@extends('layouts.app')
@section('title', 'Selamat Datang')

@section('content')
<style>body { padding-bottom: 0 !important; }</style>
<!-- Animated Background Layer -->
<div class="fixed inset-0 z-[-1] overflow-hidden bg-surface">
    <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] bg-primary/10 blur-[100px] rounded-full animate-pulse" style="animation-duration: 4s;"></div>
    <div class="absolute top-[40%] -right-[10%] w-[40%] h-[60%] bg-[#FFB400]/10 blur-[100px] rounded-full animate-pulse" style="animation-duration: 6s; animation-delay: 1s;"></div>
    <div class="absolute -bottom-[20%] left-[20%] w-[60%] h-[40%] bg-secondary-fixed/30 blur-[100px] rounded-full animate-pulse" style="animation-duration: 5s; animation-delay: 2s;"></div>
</div>

<!-- Transparent Navbar -->
<nav class="absolute top-0 w-full flex items-center justify-between px-6 py-4 z-50">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-primary text-on-primary rounded-xl flex items-center justify-center shadow-lg transform -rotate-6">
            <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">school</span>
        </div>
        <span class="font-headline-md text-2xl font-extrabold text-primary tracking-tight">KawanKampus</span>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('login') }}" class="hidden sm:inline-block px-5 py-2.5 font-bold text-primary hover:bg-primary/5 rounded-xl transition-colors">Masuk</a>
        <a href="{{ route('register') }}" class="px-5 py-2.5 bg-primary text-on-primary font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all">Daftar Sekarang</a>
    </div>
</nav>

<!-- Hero Section -->
<main class="min-h-[90vh] flex flex-col items-center justify-center px-4 pt-20 relative">
    <div class="max-w-4xl w-full text-center space-y-6">
        <div class="inline-flex items-center gap-2 bg-surface-container-high/50 backdrop-blur-md px-4 py-2 rounded-full border border-surface-bright/50 text-sm font-bold text-on-surface-variant mb-4 animate-[bounce_2s_infinite]">
            <span class="material-symbols-outlined text-[#FFB400] text-[18px]">verified</span>
            Platform Kolaborasi Mahasiswa #1
        </div>
        <h1 class="text-5xl md:text-7xl font-extrabold text-on-surface tracking-tight leading-tight">
            Saling Bantu, Kumpulkan Poin, <br class="hidden md:block">
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-[#ff6b6b]">Jadilah Pahlawan Kampus!</span>
        </h1>
        <p class="text-lg md:text-xl text-on-surface-variant max-w-2xl mx-auto leading-relaxed">
            Nggak perlu bingung kalau ada tugas susah atau butuh bantuan mendadak. KawanKampus menghubungkanmu dengan teman sekampus yang siap membantu!
        </p>
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-primary text-on-primary text-lg font-bold rounded-2xl shadow-[0_8px_30px_rgba(0,64,223,0.3)] hover:shadow-[0_8px_40px_rgba(0,64,223,0.4)] hover:-translate-y-1 transition-all flex items-center justify-center gap-2">
                Mulai Berkolaborasi <span class="material-symbols-outlined">arrow_forward</span>
            </a>
            <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 bg-surface-container-high text-on-surface-variant text-lg font-bold rounded-2xl hover:bg-surface-container-highest transition-colors flex items-center justify-center gap-2">
                Sudah punya akun? Masuk
            </a>
        </div>
    </div>
</main>

<!-- Features Grid Section -->
<section class="py-20 px-4">
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Feature 1 -->
        <div class="bg-surface/80 backdrop-blur-xl p-8 rounded-[2rem] border border-surface-bright shadow-level-1 hover:shadow-level-2 transition-all hover:-translate-y-1 group">
            <div class="w-14 h-14 bg-primary-container text-on-primary-container rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-3xl">handshake</span>
            </div>
            <h3 class="text-2xl font-bold text-on-surface mb-3">Saling Kolaborasi</h3>
            <p class="text-on-surface-variant leading-relaxed">Dari diskusi proyek bareng, review jurnal, sampai nyari relawan kuesioner. Ada banyak teman sekampus yang siap jadi partner kolaborasimu.</p>
        </div>

        <!-- Feature 2 -->
        <div class="bg-surface/80 backdrop-blur-xl p-8 rounded-[2rem] border border-surface-bright shadow-level-1 hover:shadow-level-2 transition-all hover:-translate-y-1 group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-[#FFB400]/10 rounded-bl-[100px] -z-10 group-hover:scale-110 transition-transform"></div>
            <div class="w-14 h-14 bg-[#FFB400]/20 text-[#4A3400] rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">auto_awesome</span>
            </div>
            <h3 class="text-2xl font-bold text-on-surface mb-3">Skill Matching</h3>
            <p class="text-on-surface-variant leading-relaxed">Isi keahlianmu di profil, dan algoritma cerdas kami akan merekomendasikan tugas yang 100% cocok buatmu.</p>
        </div>

        <!-- Feature 3 -->
        <div class="bg-surface/80 backdrop-blur-xl p-8 rounded-[2rem] border border-surface-bright shadow-level-1 hover:shadow-level-2 transition-all hover:-translate-y-1 group">
            <div class="w-14 h-14 bg-secondary-fixed-dim text-on-secondary-fixed rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-3xl">emoji_events</span>
            </div>
            <h3 class="text-2xl font-bold text-on-surface mb-3">Gamifikasi Poin</h3>
            <p class="text-on-surface-variant leading-relaxed">Kumpulkan poin setiap kali kamu menyelesaikan tugas orang lain. Bersaing di Leaderboard untuk jadi yang terbaik!</p>
        </div>

    </div>
</section>

<!-- Footer -->
<footer class="py-8 text-center text-on-surface-variant text-sm border-t border-surface-bright">
    <p>&copy; 2026 KawanKampus. Solusi Saling Bantu Mahasiswa. Dibuat dengan <span class="text-error">❤</span>.</p>
</footer>

@endsection
