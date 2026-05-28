@extends('layouts.app')
@section('title', 'Detail Jasa')

@section('content')
<header class="bg-surface text-primary shadow-sm flex items-center px-4 py-3 w-full sticky top-0 z-50">
    <a href="{{ route('home') }}" class="mr-3 p-1 rounded-full hover:bg-surface-bright transition-colors text-on-surface">
        <span class="material-symbols-outlined">arrow_back</span>
    </a>
    <h1 class="font-headline-md text-xl font-bold">Detail Jasa</h1>
</header>

<main class="px-4 py-6 max-w-2xl mx-auto w-full flex flex-col gap-6 pb-24">
    @if(session('success'))
        <div class="bg-primary-container text-on-primary-container p-4 rounded-xl text-sm shadow-sm border border-primary-fixed">
            <span class="font-bold">Mantap!</span> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-error-container text-on-error-container p-4 rounded-xl text-sm shadow-sm border border-error">
            <span class="font-bold">Waduh!</span> {{ session('error') }}
        </div>
    @endif

    <section class="bg-surface rounded-2xl p-6 shadow-level-1">
        <div class="flex justify-between items-start mb-4">
            <span class="bg-primary-fixed text-on-primary-fixed px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">{{ $task->category }}</span>
            <div class="flex items-center font-bold text-sm px-3 py-1.5 rounded-full text-primary bg-primary-fixed-dim">
                <span class="material-symbols-outlined text-[18px] mr-1">monetization_on</span>
                {{ $task->reward_points }} Pts
            </div>
        </div>
        
        <h2 class="text-2xl font-extrabold text-on-surface mb-2">{{ $task->title }}</h2>
        <p class="text-on-surface-variant text-sm mb-6 whitespace-pre-line">{{ $task->description }}</p>

        <div class="grid grid-cols-2 gap-4 text-sm text-on-surface-variant">
            <div class="flex items-start gap-2">
                <span class="material-symbols-outlined text-[18px] text-primary">calendar_today</span>
                <div>
                    <span class="block font-bold text-on-surface">Tanggal</span>
                    {{ \Carbon\Carbon::parse($task->schedule_date)->translatedFormat('d F Y') }}
                </div>
            </div>
            <div class="flex items-start gap-2">
                <span class="material-symbols-outlined text-[18px] text-primary">schedule</span>
                <div>
                    <span class="block font-bold text-on-surface">Jam</span>
                    {{ substr($task->start_time, 0, 5) }} - {{ substr($task->end_time, 0, 5) }}
                </div>
            </div>
            <div class="flex items-start gap-2 col-span-2">
                <span class="material-symbols-outlined text-[18px] text-primary">location_on</span>
                <div>
                    <span class="block font-bold text-on-surface">Lokasi</span>
                    {{ $task->location }}
                </div>
            </div>
        </div>
    </section>

    @if($task->requester_id === $user->id)
        <!-- Bagian khusus pembuat tugas: Daftar Pelamar -->
        <section class="flex flex-col gap-4 mt-4">
            <h3 class="text-lg font-bold text-on-surface">Kandidat Helper ({{ $task->applications->count() }})</h3>
            
            @if($task->applications->isEmpty())
                <div class="bg-surface-bright rounded-2xl p-8 text-center border-2 border-dashed border-outline-variant">
                    <span class="material-symbols-outlined text-4xl text-outline mb-2">group_off</span>
                    <p class="text-on-surface-variant text-sm">Belum ada yang nawarin bantuan nih.<br>Sabar ya der, tunggu bentar lagi!</p>
                </div>
            @else
                <div class="flex flex-col gap-3">
                    @foreach($task->applications as $app)
                    <article class="bg-surface rounded-xl p-4 shadow-sm border border-surface-container-high flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                        <div class="flex items-start gap-4 flex-grow min-w-0">
                            <img src="{{ $app->user->avatar_url }}" alt="Avatar" class="w-12 h-12 rounded-full object-cover shadow-sm border-2 border-surface-bright flex-shrink-0">
                            <div class="min-w-0 flex-grow">
                                <div class="flex items-center gap-2">
                                    <h4 class="font-bold text-on-surface text-base truncate">{{ $app->user->name }}</h4>
                                    <div class="flex items-center text-xs bg-surface-container-high px-2 py-0.5 rounded-full text-on-surface-variant font-bold">
                                        <span class="material-symbols-outlined text-[14px] text-[#FFB400]" style="font-variation-settings: 'FILL' 1;">star</span>
                                        <span class="ml-1">{{ $app->user->reviewsReceived->count() > 0 ? number_format($app->user->reviewsReceived->avg('rating'), 1) : 'Baru' }}</span>
                                    </div>
                                </div>
                                <p class="text-xs text-on-surface-variant truncate">{{ $app->user->faculty }} • {{ $app->user->major }}</p>
                                
                                @if($app->user->skills)
                                <div class="flex flex-wrap gap-1 mt-2">
                                    @foreach(explode(',', $app->user->skills) as $skill)
                                        <span class="bg-secondary-container text-on-secondary-container px-2 py-0.5 rounded-md text-[10px] font-bold">{{ trim($skill) }}</span>
                                    @endforeach
                                </div>
                                @endif
                                
                                @if($app->user->bio)
                                <p class="text-xs text-outline mt-2 italic break-words">"{{ $app->user->bio }}"</p>
                                @endif
                            </div>
                        </div>
                        
                        <form action="{{ route('task.accept', ['task' => $task->id, 'application' => $app->id]) }}" method="POST" class="w-full sm:w-auto mt-2 sm:mt-0 flex-shrink-0">
                            @csrf
                            <button type="submit" class="w-full sm:w-auto bg-primary text-on-primary font-bold px-4 py-2 rounded-xl text-sm shadow-sm hover:bg-primary-container hover:text-on-primary-container transition-colors whitespace-nowrap" onclick="return confirm('Yakin mau milih {{ $app->user->name }} buat ngerjain tugas ini?')">
                                Terima Bantuan Dia
                            </button>
                        </form>
                    </article>
                    @endforeach
                </div>
            @endif
        </section>
    @else
        <!-- Kalau bukan pembuat tugas, nampilin tombol Apply lagi di bawah -->
        @if($task->status === 'Open')
            @php
                $hasApplied = \App\Models\TaskApplication::where('task_id', $task->id)->where('user_id', Auth::id())->exists();
            @endphp
            @if($hasApplied)
                <div class="bg-surface-container-high text-on-surface-variant p-4 rounded-xl text-center font-bold text-sm shadow-sm">
                    Lu udah nawarin bantuan. Menunggu persetujuan...
                </div>
            @else
                <form action="{{ route('task.apply', $task->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-primary text-on-primary font-bold px-4 py-4 rounded-xl text-base shadow-sm hover:bg-primary-container hover:text-on-primary-container transition-colors" onclick="return confirm('Yakin mau nawarin bantuan ke orang ini?')">
                        Tawarkan Bantuan Sekarang
                    </button>
                </form>
            @endif
        @endif
    @endif
</main>
@endsection
