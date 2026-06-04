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
    <div class="ml-auto flex items-center gap-1">
        <button onclick="window.location.reload();" class="p-2 rounded-full hover:bg-surface-bright transition-colors text-primary" title="Refresh Chat">
            <span class="material-symbols-outlined">refresh</span>
        </button>
        <button onclick="openReportModal()" class="p-2 rounded-full hover:bg-error-container transition-colors text-error" title="Laporkan User">
            <span class="material-symbols-outlined">flag</span>
        </button>
    </div>
</header>

<!-- Modal Laporkan -->
<div id="reportModal" class="fixed inset-0 bg-black/50 z-[60] hidden items-center justify-center p-4 backdrop-blur-sm transition-opacity">
    <div class="bg-surface w-full max-w-sm rounded-2xl p-6 shadow-level-3 transform scale-95 transition-transform" id="reportModalContent">
        <div class="flex items-center gap-3 text-error mb-4">
            <span class="material-symbols-outlined text-3xl">flag</span>
            <h2 class="text-xl font-bold">Laporkan {{ $partner->name }}</h2>
        </div>
        <p class="text-sm text-on-surface-variant mb-4">Punya masalah sama user ini? Ceritain ke Admin biar bisa ditindak tegas (Banned).</p>
        
        <form action="{{ route('report.store', $partner->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="reason" class="block text-sm font-bold text-on-surface mb-2">Alasan Laporan</label>
                <textarea name="reason" id="reason" rows="3" class="w-full bg-surface-container border border-outline-variant rounded-xl p-3 text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" placeholder="Misal: User ini nipu, ngomong kasar, dll..." required></textarea>
            </div>
            <div class="flex gap-3 justify-end mt-6">
                <button type="button" onclick="closeReportModal()" class="px-4 py-2 text-sm font-bold text-on-surface-variant hover:bg-surface-container-high rounded-xl transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-bold bg-error text-white hover:bg-error-container hover:text-error rounded-xl transition-colors shadow-sm">Kirim Laporan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openReportModal() {
        const modal = document.getElementById('reportModal');
        const content = document.getElementById('reportModalContent');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }, 10);
    }

    function closeReportModal() {
        const modal = document.getElementById('reportModal');
        const content = document.getElementById('reportModalContent');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }
</script>

<main class="px-4 py-4 max-w-2xl mx-auto w-full flex-1 overflow-y-auto" id="chat-container">
    <div class="space-y-3 pb-24" id="chat-messages-box">
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
        <form id="chat-form" action="{{ route('chat.store', $task->id) }}" method="POST" class="flex items-center gap-2">
            @csrf
            <textarea name="message" id="chat-message" rows="1" placeholder="Ketik pesan..." class="flex-1 bg-surface-container-lowest border border-outline-variant rounded-full px-4 py-2 text-sm focus:ring-primary focus:border-primary resize-none hide-scrollbar" required autofocus oninput="this.style.height = '';this.style.height = this.scrollHeight + 'px'"></textarea>
            <button type="submit" id="chat-submit-btn" class="bg-primary text-on-primary w-10 h-10 rounded-full flex items-center justify-center shrink-0 hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                <span class="material-symbols-outlined text-[20px]" id="chat-submit-icon">send</span>
            </button>
        </form>
    </div>
</div>

<script>
    const chatForm = document.getElementById('chat-form');
    const chatMsgInput = document.getElementById('chat-message');
    const chatBox = document.getElementById('chat-messages-box');
    const btn = document.getElementById('chat-submit-btn');
    const icon = document.getElementById('chat-submit-icon');

    // Handle Form Submit via AJAX
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const msg = chatMsgInput.value.trim();
        if(msg === '') return;

        // Disable button to prevent spam
        btn.disabled = true;
        icon.innerText = 'hourglass_empty';
        icon.classList.add('animate-pulse');

        fetch(chatForm.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ message: msg })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                chatMsgInput.value = '';
                chatMsgInput.style.height = '';
                fetchMessages(); // Immediately fetch new messages
            }
        })
        .finally(() => {
            btn.disabled = false;
            icon.innerText = 'send';
            icon.classList.remove('animate-pulse');
        });
    });

    function fetchMessages() {
        fetch('{{ route("chat.messages", $task->id) }}')
            .then(res => res.json())
            .then(data => {
                if (data.html !== undefined) {
                    chatBox.innerHTML = data.html;
                    window.scrollTo(0, document.body.scrollHeight);
                }
            })
            .catch(err => console.error(err));
    }

    // Polling every 3 seconds
    setInterval(fetchMessages, 3000);

    // Auto-scroll to bottom on load
    window.onload = function() {
        window.scrollTo(0, document.body.scrollHeight);
    };
</script>
@endsection
