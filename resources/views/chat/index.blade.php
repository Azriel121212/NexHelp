@extends('layouts.app')
@section('title', 'Daftar Chat')

@section('content')
<header class="bg-surface text-primary shadow-sm flex items-center px-4 py-3 w-full sticky top-0 z-50">
    <a href="{{ route('home') }}" class="mr-3 p-1 rounded-full hover:bg-surface-bright transition-colors text-on-surface">
        <span class="material-symbols-outlined">arrow_back</span>
    </a>
    <h1 class="font-headline-md text-xl font-bold">Obrolan Gw</h1>
</header>

<main class="px-4 py-6 max-w-2xl mx-auto w-full pb-24">
    <div class="space-y-4">
        @forelse($chatTasks as $task)
            @php
                // Tentukan siapa lawan bicaranya (partner)
                $partner = $task->requester_id === $user->id ? $task->helper : $task->requester;
                // Ambil pesan terakhir (jika ada)
                $lastMessage = $task->messages->first();
            @endphp
            <a href="{{ route('chat.show', $task->id) }}" class="block bg-surface rounded-xl p-4 shadow-sm border border-surface-container-high hover:border-primary transition-colors">
                <div class="flex items-center gap-4">
                    <img src="{{ optional($partner)->avatar_url }}" alt="Avatar" class="w-12 h-12 rounded-full object-cover border-2 border-primary-fixed">
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-bold text-on-surface">{{ optional($partner)->name }}</h3>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-secondary-container text-secondary-fixed">{{ $task->status }}</span>
                        </div>
                        <p class="text-xs text-primary font-bold mb-1 line-clamp-1">Tugas: {{ $task->title }}</p>
                        <p class="text-sm text-on-surface-variant line-clamp-1">
                            @if($lastMessage)
                                @if($lastMessage->sender_id === $user->id)
                                    <span class="text-outline material-symbols-outlined text-[14px] align-middle">done_all</span> 
                                @endif
                                {{ $lastMessage->message }}
                            @else
                                <span class="italic text-outline">Belum ada pesan. Sapa duluan yuk!</span>
                            @endif
                        </p>
                    </div>
                </div>
            </a>
        @empty
            <div class="text-center py-12">
                <span class="material-symbols-outlined text-6xl text-outline-variant mb-4">forum</span>
                <p class="text-on-surface-variant font-bold">Belum ada obrolan.</p>
                <p class="text-sm text-outline">Lu harus nerima helper atau diterima sebagai helper dulu buat mulai chattingan.</p>
            </div>
        @endforelse
    </div>
</main>
@endsection
