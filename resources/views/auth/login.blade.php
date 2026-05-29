@extends('layouts.app')
@section('title', 'Masuk')

@section('content')
<div class="flex-grow flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-surface rounded-3xl p-8 shadow-level-1">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-primary tracking-tight">KawanKampus</h1>
            <p class="text-on-surface-variant mt-2">Selamat datang kembali! Silakan masuk.</p>
        </div>

        @if($errors->any())
        <div class="bg-error-container text-on-error-container p-4 rounded-xl mb-6 text-sm">
            {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-bold text-on-surface mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full bg-surface-bright border-none rounded-xl px-4 py-3 text-on-surface shadow-sm focus:ring-2 focus:ring-primary placeholder-outline-variant" placeholder="email@kampus.edu">
            </div>

            <div>
                <label class="block text-sm font-bold text-on-surface mb-1">Password</label>
                <input type="password" name="password" required class="w-full bg-surface-bright border-none rounded-xl px-4 py-3 text-on-surface shadow-sm focus:ring-2 focus:ring-primary placeholder-outline-variant" placeholder="••••••••">
            </div>

            <button type="submit" class="w-full bg-primary text-on-primary font-bold rounded-xl py-3 mt-4 active:scale-95 transition-transform">
                Masuk
            </button>
        </form>

        <p class="text-center text-sm text-on-surface-variant mt-8">
            Belum punya akun? <a href="{{ route('register') }}" class="text-primary font-bold hover:underline">Daftar di sini</a>
        </p>
    </div>
</div>
@endsection
