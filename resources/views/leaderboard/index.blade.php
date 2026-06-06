@extends('layouts.app')
@section('title', 'Leaderboard')

@section('content')
<!-- TopAppBar -->
<header class="bg-surface text-primary shadow-sm flex items-center px-4 py-3 w-full sticky top-0 z-50">
    <a href="{{ route('home') }}" class="flex items-center gap-2 hover:bg-surface-bright p-2 -ml-2 rounded-xl transition-colors">
        <span class="material-symbols-outlined text-on-surface-variant">arrow_back</span>
        <h1 class="font-headline-md text-xl sm:text-2xl text-primary font-extrabold tracking-tight">Leaderboard Pahlawan Kampus</h1>
    </a>
</header>

<main class="px-4 py-6 max-w-3xl mx-auto space-y-6 w-full pb-24">
    
    <div class="text-center mb-8">
        <span class="material-symbols-outlined text-6xl text-[#FFB400] mb-2" style="font-variation-settings: 'FILL' 1;">trophy</span>
        <h2 class="text-2xl font-bold text-on-surface">Top 10 Mahasiswa</h2>
        <p class="text-on-surface-variant text-sm mt-1">Mereka yang paling banyak berkontribusi saling bantu di kampus.</p>
    </div>

    <!-- Podium for Top 3 -->
    <div class="flex items-end justify-center gap-2 sm:gap-4 mb-8 h-48">
        <!-- Rank 2 -->
        @if(isset($topUsers[1]))
        <div class="flex flex-col items-center w-24">
            <img src="{{ $topUsers[1]->avatar_url }}" class="w-12 h-12 rounded-full object-cover border-4 border-[#C0C0C0] mb-2 z-10 bg-surface">
            <div class="bg-[#F5F5F5] w-full h-24 rounded-t-lg flex flex-col items-center pt-2 shadow-inner border border-outline-variant relative">
                <span class="text-[#C0C0C0] font-bold text-xl">2</span>
                <p class="text-[10px] font-bold text-on-surface truncate w-full text-center px-1">{{ $topUsers[1]->name }}</p>
                <p class="text-[9px] text-[#FFB400] font-bold">{{ $topUsers[1]->tasks_helped_count }} Tugas Selesai</p>
            </div>
        </div>
        @endif

        <!-- Rank 1 -->
        @if(isset($topUsers[0]))
        <div class="flex flex-col items-center w-28">
            <span class="material-symbols-outlined text-[#FFB400] text-3xl mb-[-10px] z-20" style="font-variation-settings: 'FILL' 1;">workspace_premium</span>
            <img src="{{ $topUsers[0]->avatar_url }}" class="w-16 h-16 rounded-full object-cover border-4 border-[#FFB400] mb-2 z-10 bg-surface">
            <div class="bg-[#FFF8E7] w-full h-32 rounded-t-lg flex flex-col items-center pt-2 shadow-inner border border-[#FFE082] relative">
                <span class="text-[#FFB400] font-bold text-2xl">1</span>
                <p class="text-xs font-bold text-on-surface truncate w-full text-center px-1">{{ $topUsers[0]->name }}</p>
                <p class="text-[10px] text-[#FFB400] font-bold">{{ $topUsers[0]->tasks_helped_count }} Tugas Selesai</p>
            </div>
        </div>
        @endif

        <!-- Rank 3 -->
        @if(isset($topUsers[2]))
        <div class="flex flex-col items-center w-24">
            <img src="{{ $topUsers[2]->avatar_url }}" class="w-12 h-12 rounded-full object-cover border-4 border-[#CD7F32] mb-2 z-10 bg-surface">
            <div class="bg-[#FAF0E6] w-full h-20 rounded-t-lg flex flex-col items-center pt-2 shadow-inner border border-[#E6C287] relative">
                <span class="text-[#CD7F32] font-bold text-xl">3</span>
                <p class="text-[10px] font-bold text-on-surface truncate w-full text-center px-1">{{ $topUsers[2]->name }}</p>
                <p class="text-[9px] text-[#FFB400] font-bold">{{ $topUsers[2]->tasks_helped_count }} Tugas Selesai</p>
            </div>
        </div>
        @endif
    </div>

    <!-- The Rest of the List (4-10) -->
    <div class="bg-surface rounded-2xl shadow-sm border border-surface-bright overflow-hidden">
        @foreach($topUsers->skip(3) as $user)
        <div class="flex items-center gap-4 p-4 border-b border-surface-bright last:border-0 hover:bg-surface-container-low transition-colors">
            <div class="w-8 text-center text-on-surface-variant font-bold text-lg">
                {{ $loop->iteration + 3 }}
            </div>
            <img src="{{ $user->avatar_url }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover border border-surface-bright">
            <div class="flex-1">
                <h4 class="text-sm font-bold text-on-surface">{{ $user->name }}</h4>
                <p class="text-[10px] text-on-surface-variant">{{ $user->faculty }}</p>
            </div>
            <div class="text-right">
                <span class="bg-primary-container text-on-primary-container px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">task_alt</span>
                    {{ $user->tasks_helped_count }} Tugas
                </span>
            </div>
        </div>
        @endforeach
    </div>
</main>

@endsection
