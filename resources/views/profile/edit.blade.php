@extends('layouts.app')
@section('title', 'Edit Profil')

@section('content')
<header class="bg-surface text-primary shadow-sm flex items-center px-4 py-3 w-full sticky top-0 z-50">
    <a href="{{ route('profile') }}" class="mr-3 p-1 rounded-full hover:bg-surface-bright transition-colors text-on-surface">
        <span class="material-symbols-outlined">arrow_back</span>
    </a>
    <h1 class="font-headline-md text-xl font-bold">Edit Profil</h1>
</header>

<main class="px-4 py-6 max-w-xl mx-auto w-full">
    @if ($errors->any())
        <div class="bg-error-container text-on-error-container p-4 rounded-xl text-sm mb-6 shadow-sm border border-error">
            <span class="font-bold">Error:</span>
            <ul class="list-disc ml-4 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-6">
        @csrf

        <!-- Avatar Upload -->
        <section class="flex flex-col items-center gap-4 bg-surface rounded-2xl p-6 shadow-level-1">
            <div class="relative group cursor-pointer" onclick="document.getElementById('avatar-input').click()">
                <img src="{{ $user->avatar_url }}" alt="Profile Picture" class="w-24 h-24 rounded-full object-cover shadow-sm border-4 border-surface-bright group-hover:opacity-75 transition-opacity">
                <div class="absolute inset-0 bg-black/40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <span class="material-symbols-outlined text-white">photo_camera</span>
                </div>
            </div>
            <p class="text-sm font-bold text-primary cursor-pointer hover:underline" onclick="document.getElementById('avatar-input').click()">Ubah Foto</p>
            <input type="file" name="avatar" id="avatar-input" class="hidden" accept="image/*">
            <p class="text-xs text-on-surface-variant text-center -mt-2">Format: JPG, PNG, GIF (Max 2MB)</p>
        </section>

        <!-- Bio & Skills -->
        <section class="bg-surface rounded-2xl p-6 shadow-level-1 flex flex-col gap-4">
            <div>
                <label class="block text-sm font-bold text-on-surface-variant mb-1">Keahlian (Skills)</label>
                <input name="skills" type="text" class="w-full bg-surface-bright border border-outline-variant rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="Contoh: PHP, Video Editing, Bikin Makalah" value="{{ old('skills', $user->skills) }}"/>
                <p class="text-[10px] text-on-surface-variant mt-1">Pisahkan dengan koma (,). Bakal dijadiin *badge* di profil lu.</p>
            </div>

            <div>
                <label class="block text-sm font-bold text-on-surface-variant mb-1">Bio Singkat</label>
                <textarea name="bio" rows="3" class="w-full bg-surface-bright border border-outline-variant rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="Tulis sedikit tentang diri lu atau keahlian lu...">{{ old('bio', $user->bio) }}</textarea>
            </div>
        </section>

        <button type="submit" class="w-full bg-primary text-on-primary font-bold px-6 py-4 rounded-xl shadow-level-1 active:scale-95 transition-transform">
            Simpan Perubahan
        </button>
    </form>
</main>
@endsection
