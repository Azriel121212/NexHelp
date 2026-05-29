@extends('layouts.app')
@section('title', 'Home Dashboard')

@section('content')
<!-- TopAppBar -->
<header class="bg-surface text-primary shadow-sm flex justify-between items-center px-4 py-2 w-full sticky top-0 z-50">
    <a href="{{ route('profile') }}" class="flex items-center gap-2 sm:gap-3 hover:bg-surface-bright p-2 -ml-2 rounded-xl transition-colors">
        <img alt="User Profile" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover shadow-sm border border-surface-bright" src="{{ Auth::user()->avatar_url }}"/>
        <div>
            <h1 class="font-headline-md text-xl sm:text-2xl text-primary font-extrabold tracking-tight">KawanKampus</h1>
            <p class="font-label-sm text-[10px] sm:text-xs text-on-surface-variant line-clamp-1">{{ $user->name }}</p>
        </div>
    </a>
    <div class="flex items-center gap-1 sm:gap-3">
        @if($user->is_admin)
        <a href="{{ route('admin.dashboard') }}" class="hidden sm:flex items-center justify-center bg-error-container text-on-error-container hover:bg-error hover:text-white p-2 rounded-full transition-colors" title="Admin Panel">
            <span class="material-symbols-outlined">admin_panel_settings</span>
        </a>
        @endif
        <a href="{{ route('activity.index') }}" class="flex items-center justify-center bg-surface-bright hover:bg-surface-container-high p-2 rounded-full transition-colors relative" title="Aktivitas">
            <span class="material-symbols-outlined text-on-surface-variant">receipt_long</span>
            @if(isset($activeTaskCount) && $activeTaskCount > 0)
                <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-error rounded-full border-2 border-surface-bright"></span>
            @endif
        </a>
        <a href="{{ route('chat.index') }}" class="flex items-center justify-center bg-surface-bright hover:bg-surface-container-high p-2 rounded-full transition-colors" title="Pesan">
            <span class="material-symbols-outlined text-on-surface-variant">chat</span>
        </a>
        <a href="{{ route('wallet.index') }}" class="flex items-center gap-1.5 bg-[#FFB400] text-[#4A3400] px-3 py-1.5 rounded-full font-bold text-sm shadow-sm hover:opacity-90 transition-opacity">
            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">monetization_on</span>
            {{ $user->points }} pts
        </a>
        <form method="POST" action="{{ route('logout') }}" class="ml-1">
            @csrf
            <button type="submit" class="flex items-center justify-center text-error hover:bg-error-container p-2 rounded-full transition-colors" title="Keluar">
                <span class="material-symbols-outlined">logout</span>
            </button>
        </form>
    </div>
</header>

<main class="px-4 py-4 max-w-7xl mx-auto space-y-4 w-full">
    @if($user->is_admin)
    <div class="sm:hidden mb-2">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-center gap-2 bg-error-container text-on-error-container hover:bg-error hover:text-white p-3 rounded-xl transition-colors font-bold w-full shadow-sm">
            <span class="material-symbols-outlined">admin_panel_settings</span> Buka Panel Admin
        </a>
    </div>
    @endif
    @if(session('success'))
        <div class="bg-primary-container text-on-primary-container p-4 rounded-xl text-sm mb-2 shadow-sm border border-primary-fixed">
            <span class="font-bold">Mantap!</span> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-error-container text-on-error-container p-4 rounded-xl text-sm mb-2 shadow-sm border border-error">
            <span class="font-bold">Waduh!</span> {{ session('error') }}
        </div>
    @endif

    <!-- Search & Filter -->
    <section class="space-y-2">
        <form action="{{ route('home') }}" method="GET" class="relative w-full">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input name="search" value="{{ $currentSearch }}" class="w-full bg-surface border-none rounded-full py-3 pl-12 pr-4 text-sm shadow-level-1 focus:ring-2 focus:ring-primary focus:outline-none placeholder-outline" placeholder="Search tasks..." type="text"/>
            <button type="submit" class="hidden"></button>
        </form>
        <div class="flex overflow-x-auto hide-scrollbar gap-2 py-1">
            @php
                $categories = [
                    'Semua', 
                    'Tutor / Teman Belajar', 
                    'Bantuan Koding / Tugas', 
                    'Review Jurnal / Proofreading', 
                    'Bantuan Penelitian / Kuesioner', 
                    'Pinjam Buku / Referensi', 
                    'Lainnya'
                ];
            @endphp
            @foreach($categories as $cat)
                <a href="{{ route('home', ['category' => $cat, 'search' => $currentSearch]) }}" 
                   class="flex-shrink-0 px-4 py-2 rounded-full text-xs font-bold shadow-sm transition-colors 
                   {{ $currentCategory == $cat ? 'bg-primary-container text-on-primary-container' : 'bg-surface-container-high text-on-surface-variant hover:bg-surface-container-low' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>
    </section>

    <!-- Task Feed Dynamic from Database -->
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 pb-24">
        @forelse($tasks as $task)
        <article class="bg-surface rounded-DEFAULT p-4 shadow-level-1 {{ $task->category == 'Urgent' ? 'border-l-4 border-error' : '' }} flex flex-col justify-between h-full">
            <div>
                <!-- Pembuat Tugas -->
            <div class="flex items-center gap-3 mb-3 border-b border-surface-bright pb-3">
                <img src="{{ $task->requester->avatar_url }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover">
                <div>
                    <h4 class="text-xs font-bold text-on-surface">{{ $task->requester->name }}</h4>
                    <p class="text-[10px] text-on-surface-variant">{{ $task->requester->faculty }}</p>
                </div>
            </div>

            <div class="flex justify-between items-start mb-2">
                @if($task->category == 'Urgent')
                    <span class="bg-error-container text-on-error-container px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">{{ $task->category }}</span>
                @elseif($task->category == 'Lost & Found')
                    <span class="bg-secondary-fixed text-on-secondary-fixed px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">{{ $task->category }}</span>
                @else
                    <span class="bg-primary-fixed text-on-primary-fixed px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">{{ $task->category }}</span>
                @endif
                <span class="text-outline text-[10px] font-bold">{{ \Carbon\Carbon::parse($task->created_at)->diffForHumans(null, true, true) }}</span>
            </div>
            <a href="{{ route('task.show', $task->id) }}" class="hover:underline block">
                <h3 class="text-lg font-bold text-on-surface mb-3">{{ $task->title }}</h3>
            </a>
            </div>
            <div class="flex justify-between items-end mt-4">
                <div class="flex items-center text-on-surface-variant text-xs sm:text-sm gap-1">
                    <span class="material-symbols-outlined text-[16px]">location_on</span>
                    {{ $task->location }}
                </div>
                <div class="flex items-center gap-2">
                    @if($task->requester_id == Auth::id())
                        <form action="{{ route('task.cancel', $task->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center gap-1 text-xs font-bold text-error bg-error-container px-3 py-1.5 rounded-full hover:bg-error hover:text-white transition-colors" onclick="return confirm('Yakin mau batalin jasa ini? Poin lu bakal balik 100%.')">
                                <span class="material-symbols-outlined text-[14px]">cancel</span> Batal
                            </button>
                        </form>
                    @else
                        @php
                            $hasApplied = \App\Models\TaskApplication::where('task_id', $task->id)->where('user_id', Auth::id())->exists();
                        @endphp
                        @if($hasApplied)
                            <button disabled class="flex items-center gap-1 text-xs font-bold text-on-surface-variant bg-surface-container-high px-4 py-1.5 rounded-full shadow-sm cursor-not-allowed">
                                <span class="material-symbols-outlined text-[14px]">hourglass_top</span> Menunggu Acc
                            </button>
                        @else
                            <form action="{{ route('task.apply', $task->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="flex items-center gap-1 text-xs font-bold text-on-primary bg-primary px-4 py-1.5 rounded-full hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-sm" onclick="return confirm('Yakin mau nawarin bantuan ke orang ini?')">
                                    <span class="material-symbols-outlined text-[14px]">back_hand</span> Tawarkan Bantuan
                                </button>
                            </form>
                        @endif
                    @endif
                    <div class="flex items-center font-semibold text-sm px-3 py-1.5 rounded-full {{ $task->category == 'Lost & Found' ? 'text-secondary-container bg-secondary-fixed-dim' : 'text-primary bg-primary-fixed-dim' }}">
                        <span class="material-symbols-outlined text-[16px] mr-1">monetization_on</span>
                        {{ $task->reward_points }} Pts
                    </div>
                </div>
            </div>
        </article>
        @empty
        <div class="col-span-full bg-surface rounded-2xl p-8 text-center shadow-sm border-2 border-dashed border-outline-variant mt-8">
            <span class="material-symbols-outlined text-5xl text-outline mb-4">post_add</span>
            <h3 class="text-lg font-bold text-on-surface mb-2">Belum ada request bantuan nih!</h3>
            <p class="text-on-surface-variant text-sm mb-6">Jangan malu-malu, jadilah yang pertama buat minta tolong ke anak-anak kampus!</p>
            <a href="{{ route('task.create') }}" class="inline-flex items-center gap-2 bg-primary text-on-primary font-bold px-6 py-3 rounded-full hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[18px]">add</span> Bikin Request Baru
            </a>
        </div>
        @endforelse
    </section>
</main>

<!-- Floating Action Button untuk Bikin Request Baru -->
<a href="{{ route('task.create') }}" class="fixed bottom-8 right-8 bg-primary text-on-primary w-14 h-14 rounded-full flex items-center justify-center shadow-level-2 hover:bg-primary-container hover:text-on-primary-container transition-colors z-50 group" title="Bikin Request Baru">
    <span class="material-symbols-outlined text-3xl group-hover:scale-110 transition-transform">add</span>
</a>

<!-- BottomNavBar -->
<nav class="fixed bottom-0 left-0 w-full z-50 flex justify-around items-center bg-surface px-2 pb-4 pt-2 shadow-[0_-4px_20px_rgba(0,0,0,0.04)] md:hidden">
    <a href="{{ route('home') }}" class="flex flex-col items-center justify-center bg-primary-container text-on-primary-container rounded-xl px-4 py-1">
        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">home</span>
        <span class="text-xs font-bold mt-1">Home</span>
    </a>
    <a href="#" class="flex flex-col items-center justify-center text-on-surface-variant px-4 py-1 hover:bg-surface-container-high rounded-xl">
        <span class="material-symbols-outlined">assignment_turned_in</span>
        <span class="text-xs font-bold mt-1">Tasks</span>
    </a>
    <a href="{{ route('profile') }}" class="flex flex-col items-center justify-center text-on-surface-variant px-4 py-1 hover:bg-surface-container-high rounded-xl">
        <span class="material-symbols-outlined">person</span>
        <span class="text-xs font-bold mt-1">Profile</span>
    </a>
</nav>
@endsection
