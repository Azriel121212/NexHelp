@extends('layouts.app')
@section('title', 'Chat dengan ' . $partner->name)

@section('content')
<header class="bg-surface text-primary shadow-sm flex items-center px-4 py-3 w-full sticky top-0 z-50">
    <a href="{{ route('chat.index') }}" class="mr-3 p-1 rounded-full hover:bg-surface-bright transition-colors text-on-surface">
        <span class="material-symbols-outlined">arrow_back</span>
    </a>
    <img src="{{ $partner->avatar_url }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover mr-3 border border-outline-variant">
    <div>
        <h1 class="font-headline-md text-base font-bold leading-tight">{{ $partner->name }}</h1>
        <p class="text-[10px] text-on-surface-variant line-clamp-1">Tugas: {{ $task->title }}</p>
    </div>
    <div class="ml-auto">
        <button onclick="window.location.reload();" class="p-2 rounded-full hover:bg-surface-bright transition-colors text-primary" title="Refresh Chat">
            <span class="material-symbols-outlined">refresh</span>
        </button>
    </div>
</header>

<main class="px-4 py-4 max-w-2xl mx-auto w-full flex-1 overflow-y-auto" id="chat-container">
    <div class="space-y-3 pb-24">
        @forelse($messages as $msg)
            @if($msg->sender_id === $user->id)
                <!-- My Message (Right) -->
                <div class="flex justify-end">
                    <div class="bg-primary text-on-primary rounded-2xl rounded-tr-sm px-4 py-2 max-w-[80%] shadow-sm">
                        <p class="text-sm whitespace-pre-wrap">{{ $msg->message }}</p>
                        <p class="text-[9px] text-primary-fixed-dim text-right mt-1">{{ $msg->created_at->format('H:i') }}</p>
                    </div>
                </div>
            @else
                <!-- Partner Message (Left) -->
                <div class="flex justify-start">
                    <div class="bg-surface-container-high text-on-surface rounded-2xl rounded-tl-sm px-4 py-2 max-w-[80%] shadow-sm">
                        <p class="text-sm whitespace-pre-wrap">{{ $msg->message }}</p>
                        <p class="text-[9px] text-outline text-right mt-1">{{ $msg->created_at->format('H:i') }}</p>
                    </div>
                </div>
            @endif
        @empty
            <div class="text-center text-outline text-xs mt-10 bg-surface-container py-2 px-4 rounded-full max-w-xs mx-auto">
                Mulai obrolan untuk bahas detail tugasnya!
            </div>
        @endforelse
    </div>
</main>

<!-- Chat Input Area (Sticky Bottom) -->
<div class="fixed bottom-0 left-0 right-0 bg-surface border-t border-surface-container-high p-3 z-50">
    <div class="max-w-2xl mx-auto w-full">
        <form action="{{ route('chat.store', $task->id) }}" method="POST" class="flex items-center gap-2">
            @csrf
            <textarea name="message" rows="1" placeholder="Ketik pesan..." class="flex-1 bg-surface-container-lowest border border-outline-variant rounded-full px-4 py-2 text-sm focus:ring-primary focus:border-primary resize-none hide-scrollbar" required autofocus oninput="this.style.height = '';this.style.height = this.scrollHeight + 'px'"></textarea>
            <button type="submit" class="bg-primary text-on-primary w-10 h-10 rounded-full flex items-center justify-center shrink-0 hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[20px]">send</span>
            </button>
        </form>
    </div>
</div>

<script>
    // Auto-scroll to bottom of chat
    window.onload = function() {
        window.scrollTo(0, document.body.scrollHeight);
    };
</script>
@endsection
