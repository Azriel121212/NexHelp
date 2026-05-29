@extends('layouts.app')
@section('title', 'Profile')

@section('content')
<header class="bg-surface text-primary shadow-sm flex justify-between items-center px-4 py-2 w-full sticky top-0 z-50">
    <div class="flex items-center gap-2">
        <a href="{{ route('home') }}" class="p-2 rounded-full hover:bg-surface-bright transition-colors text-on-surface">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h1 class="font-headline-md text-2xl font-extrabold tracking-tight">KawanKampus</h1>
    </div>
    <a href="{{ route('wallet.index') }}" class="flex items-center gap-1.5 bg-[#FFB400] text-[#4A3400] px-3 py-1.5 rounded-full font-bold text-sm shadow-sm hover:opacity-90 transition-opacity">
        <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">monetization_on</span>
        {{ $user->points }} pts
    </a>
</header>

<main class="px-4 py-8 max-w-xl mx-auto w-full pb-24">
    @if(session('success'))
        <div class="bg-primary-container text-on-primary-container p-4 rounded-xl text-sm mb-6 shadow-sm border border-primary-fixed">
            <span class="font-bold">Mantap!</span> {{ session('success') }}
        </div>
    @endif

    <!-- Profile Header -->
    <section class="flex flex-col items-center mb-8 relative">
        <a href="{{ route('profile.edit') }}" class="absolute right-0 top-0 bg-surface-container-high text-on-surface-variant p-2 rounded-full hover:bg-primary hover:text-on-primary transition-colors shadow-sm" title="Edit Profil">
            <span class="material-symbols-outlined text-[20px]">edit</span>
        </a>
        <div class="relative mb-4">
            <img alt="User Profile" class="w-24 h-24 rounded-full object-cover shadow-level-1 border-4 border-surface" src="{{ $user->avatar_url }}"/>
            <div class="absolute bottom-0 right-0 bg-primary text-on-primary w-6 h-6 rounded-full flex items-center justify-center border-2 border-surface">
                <span class="material-symbols-outlined text-[14px]">verified</span>
            </div>
        </div>
        <div>
            <h1 class="text-2xl font-extrabold text-on-surface">{{ $user->name }}</h1>
            <p class="text-on-surface-variant text-sm">{{ $user->faculty }} • {{ $user->major }}</p>
        </div>
        
        <!-- Achievement Badge -->
        <div class="mt-3 inline-flex items-center gap-1 {{ $achievementColor }} px-3 py-1.5 rounded-full font-bold text-xs shadow-sm">
            <span class="material-symbols-outlined text-[14px]">social_leaderboard</span>
            {{ $achievementTitle }}
        </div>
    
    @if($user->bio)
        <p class="mt-4 text-on-surface-variant text-sm italic">"{{ $user->bio }}"</p>
    @endif

    @if($user->skills)
        <div class="mt-4 flex flex-wrap gap-2">
            @foreach(explode(',', $user->skills) as $skill)
                <span class="bg-primary-container text-on-primary-container px-3 py-1 rounded-full text-xs font-bold">{{ trim($skill) }}</span>
            @endforeach
        </div>
    @endif
    </section>

    <section class="grid grid-cols-3 gap-2">
        <div class="bg-surface-bright rounded-lg p-3 text-center shadow-level-1">
            <span class="block text-2xl font-bold text-primary">{{ $completedTasks }}</span>
            <span class="block text-xs font-bold text-on-surface-variant mt-1">Completed</span>
        </div>
        <div class="bg-surface-bright rounded-lg p-3 text-center shadow-level-1">
            <span class="block text-2xl font-bold text-primary">{{ $requestedTasks }}</span>
            <span class="block text-xs font-bold text-on-surface-variant mt-1">Requested</span>
        </div>
        <div class="bg-surface-bright rounded-lg p-3 text-center shadow-level-1">
            <div class="flex items-center justify-center gap-1">
                <span class="text-2xl font-bold text-primary">{{ $reviews->count() > 0 ? number_format($reviews->avg('rating'), 1) : '-' }}</span>
                <span class="material-symbols-outlined text-secondary-container text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
            </div>
            <span class="block text-xs font-bold text-on-surface-variant mt-1">Rating</span>
        </div>
    </section>

    <section>
        <h3 class="text-xl font-bold text-on-surface mb-2">Recent Reviews</h3>
        <div class="space-y-4">
            @foreach($reviews as $review)
            <div class="bg-surface-bright p-4 rounded-[24px] shadow-level-1">
                <div class="flex justify-between items-start mb-2">
                    <h4 class="text-sm font-bold text-on-surface">Task Review</h4>
                    <div class="flex text-secondary-container">
                        @for($i = 0; $i < $review->rating; $i++)
                            <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">star</span>
                        @endfor
                    </div>
                </div>
                <p class="text-sm text-on-surface-variant italic">"{{ $review->comment }}"</p>
            </div>
            @endforeach
            
            @if($reviews->isEmpty())
                <p class="text-sm text-on-surface-variant text-center py-4">No reviews yet.</p>
            @endif
        </div>
    </section>

    <section class="pt-6 pb-8">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full bg-error-container text-on-error-container font-bold rounded-xl py-3 active:scale-95 transition-transform flex items-center justify-center gap-2">
                <span class="material-symbols-outlined">logout</span>
                Logout
            </button>
        </form>
    </section>
</main>

<nav class="fixed bottom-0 left-0 w-full z-50 flex justify-around items-center bg-surface px-2 pb-4 pt-2 shadow-[0_-4px_20px_rgba(0,0,0,0.04)] md:hidden">
    <a href="{{ route('home') }}" class="flex flex-col items-center justify-center text-on-surface-variant px-4 py-1 hover:bg-surface-container-high rounded-xl">
        <span class="material-symbols-outlined">home</span>
        <span class="text-xs font-bold mt-1">Home</span>
    </a>
    <a href="#" class="flex flex-col items-center justify-center text-on-surface-variant px-4 py-1 hover:bg-surface-container-high rounded-xl">
        <span class="material-symbols-outlined">assignment_turned_in</span>
        <span class="text-xs font-bold mt-1">Tasks</span>
    </a>
    <a href="{{ route('profile') }}" class="flex flex-col items-center justify-center bg-primary-container text-on-primary-container rounded-xl px-4 py-1">
        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">person</span>
        <span class="text-xs font-bold mt-1">Profile</span>
    </a>
</nav>
@endsection
