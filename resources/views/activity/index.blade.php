@extends('layouts.app')
@section('title', 'Aktivitas')

@section('content')
<header class="bg-surface text-primary shadow-sm flex items-center px-4 py-3 w-full sticky top-0 z-50">
    <a href="{{ route('home') }}" class="mr-3 p-1 rounded-full hover:bg-surface-bright transition-colors text-on-surface">
        <span class="material-symbols-outlined">arrow_back</span>
    </a>
    <h1 class="font-headline-md text-xl font-bold">Aktivitas</h1>
</header>

<main class="px-4 py-6 max-w-2xl mx-auto w-full pb-24">
    @if(session('success'))
        <div class="bg-primary-container text-on-primary-container p-4 rounded-xl text-sm mb-4 shadow-sm border border-primary-fixed">
            <span class="font-bold">Mantap!</span> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-error-container text-on-error-container p-4 rounded-xl text-sm mb-4 shadow-sm border border-error">
            <span class="font-bold">Waduh!</span> {{ session('error') }}
        </div>
    @endif

    <!-- Tabs -->
    <div class="flex border-b border-surface-bright mb-6" x-data="{ tab: 'request' }">
        <button @click="tab = 'request'" :class="tab === 'request' ? 'border-primary text-primary font-bold' : 'border-transparent text-on-surface-variant font-medium'" class="flex-1 pb-3 text-center border-b-2 transition-colors">
            Request Gw
        </button>
        <button @click="tab = 'tugas'" :class="tab === 'tugas' ? 'border-primary text-primary font-bold' : 'border-transparent text-on-surface-variant font-medium'" class="flex-1 pb-3 text-center border-b-2 transition-colors">
            Tugas Gw (Kerjaan)
        </button>

        <!-- Tab Content: Request Gw -->
        <div x-show="tab === 'request'" class="w-full absolute left-0 right-0 px-4 mt-12">
            @forelse($requestedTasks as $task)
            <article class="bg-surface rounded-xl p-4 shadow-level-1 mb-4 border border-surface-container-high">
                <div class="flex justify-between items-start mb-2">
                    <span class="bg-primary-fixed text-on-primary-fixed px-2 py-1 rounded-full text-[10px] font-bold uppercase">{{ $task->category }}</span>
                    @if($task->status == 'In Progress')
                        <span class="text-xs font-bold text-secondary bg-secondary-container px-2 py-1 rounded-md">In Progress</span>
                    @elseif($task->status == 'Pending Verification')
                        <span class="text-xs font-bold text-error bg-error-container px-2 py-1 rounded-md animate-pulse">Menunggu Verifikasi</span>
                    @elseif($task->status == 'Completed')
                        <span class="text-xs font-bold text-success bg-success-container px-2 py-1 rounded-md">Selesai</span>
                    @elseif($task->status == 'Rejected')
                        <span class="text-xs font-bold text-error bg-error-container px-2 py-1 rounded-md">Ditolak</span>
                    @else
                        <span class="text-xs font-bold text-outline bg-surface-bright px-2 py-1 rounded-md">{{ $task->status }}</span>
                    @endif
                </div>
                
                <h3 class="text-base font-bold text-on-surface mb-2">{{ $task->title }}</h3>
                <p class="text-sm text-on-surface-variant mb-4 line-clamp-2">{{ $task->description }}</p>
                
                @if(in_array($task->status, ['Rejected', 'cancelled']) && $task->reject_reason)
                <div class="bg-error-container text-on-error-container p-3 rounded-lg mb-4 text-sm border border-error">
                    <strong>{{ $task->status == 'cancelled' ? 'Alasan Dibatalkan:' : 'Alasan Ditolak:' }}</strong> {{ $task->reject_reason }}
                </div>
                @endif
                
                @php
                    $startDateTime = \Carbon\Carbon::parse($task->schedule_date . ' ' . $task->start_time);
                    if(now()->gt($startDateTime)) {
                        $timeText = "Jadwal sedang berlangsung / lewat";
                        $timeColor = "text-error bg-error-container";
                    } else {
                        $diff = now()->diff($startDateTime);
                        $timeText = "Dimulai dalam: " . ($diff->d > 0 ? $diff->d . ' hari ' : '') . $diff->h . " jam " . $diff->i . " menit";
                        $timeColor = "text-primary bg-primary-container";
                    }
                @endphp
                <div class="flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-[16px] text-outline">schedule</span>
                    <span class="text-xs font-bold px-2 py-1 rounded-md {{ $timeColor }}">{{ $timeText }}</span>
                </div>
                
                @if($task->helper)
                <div class="bg-surface-bright p-3 rounded-lg flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ $task->helper->avatar_url }}" alt="Helper Avatar" class="w-8 h-8 rounded-full object-cover">
                        <div>
                            <p class="text-xs text-on-surface-variant">Helper:</p>
                            <p class="text-sm font-bold text-on-surface">{{ $task->helper->name }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <div class="font-bold text-sm text-primary">{{ $task->reward_points }} Pts</div>
                        <a href="{{ route('chat.show', $task->id) }}" class="text-[10px] font-bold text-on-primary bg-primary px-3 py-1 rounded-full flex items-center gap-1 shadow-sm">
                            <span class="material-symbols-outlined text-[14px]">chat</span> Chat
                        </a>
                    </div>
                </div>
                @endif

                <div class="flex justify-end gap-2">
                    @if(in_array($task->status, ['Open', 'Pending Approval', 'In Progress', 'Pending Verification']))
                        <form action="{{ route('task.cancel', $task->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs font-bold text-error bg-error-container px-4 py-2 rounded-lg hover:bg-error hover:text-white transition-colors" onclick="return confirm('Yakin batalin? Poin lu balik 100%.')">
                                Batalin
                            </button>
                        </form>
                    @endif
                    @if($task->status == 'Pending Verification')
                        <form action="{{ route('task.verify', $task->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs font-bold text-on-primary bg-primary px-4 py-2 rounded-lg hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-sm" onclick="return confirm('Poin bakal ditransfer ke Helper. Lanjutkan?')">
                                Verifikasi & Bayar Poin
                            </button>
                        </form>
                    @endif
                    @if($task->status == 'Completed' && !$task->review)
                        <a href="{{ route('review.create', $task->id) }}" class="text-xs font-bold text-on-primary bg-[#FFB400] px-4 py-2 rounded-lg hover:opacity-90 transition-opacity shadow-sm flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span> Beri Ulasan
                        </a>
                    @endif
                    @if(in_array($task->status, ['Open', 'Pending Approval', 'In Progress', 'Pending Verification', 'Completed']))
                        <a href="{{ route('task.show', $task->id) }}" class="text-xs font-bold text-on-surface-variant bg-surface-container-high px-4 py-2 rounded-lg hover:bg-surface-container-low transition-colors shadow-sm">
                            Lihat Detail
                        </a>
                    @endif
                </div>
            </article>
            @empty
                <div class="text-center py-12">
                    <p class="text-on-surface-variant text-sm">Lu belum bikin request apapun yang aktif.</p>
                </div>
            @endforelse
        </div>

        <!-- Tab Content: Tugas Gw -->
        <div x-show="tab === 'tugas'" class="w-full absolute left-0 right-0 px-4 mt-12" style="display: none;">
            @forelse($helperTasks as $task)
            <article class="bg-surface rounded-xl p-4 shadow-level-1 mb-4 border border-surface-container-high">
                <div class="flex justify-between items-start mb-2">
                    <span class="bg-primary-fixed text-on-primary-fixed px-2 py-1 rounded-full text-[10px] font-bold uppercase">{{ $task->category }}</span>
                    @if($task->status == 'In Progress')
                        <span class="text-xs font-bold text-secondary bg-secondary-container px-2 py-1 rounded-md">Lagi Dikerjain</span>
                    @elseif($task->status == 'Pending Verification')
                        <span class="text-xs font-bold text-outline bg-surface-bright px-2 py-1 rounded-md">Nunggu Verifikasi Requester</span>
                    @elseif($task->status == 'Completed')
                        <span class="text-xs font-bold text-success bg-success-container px-2 py-1 rounded-md">Selesai (+{{ $task->reward_points }} Pts)</span>
                    @else
                        <span class="text-xs font-bold text-outline bg-surface-bright px-2 py-1 rounded-md">{{ $task->status }}</span>
                    @endif
                </div>
                
                <h3 class="text-base font-bold text-on-surface mb-2">{{ $task->title }}</h3>
                <p class="text-sm text-on-surface-variant mb-4 line-clamp-2">{{ $task->description }}</p>
                
                @php
                    $startDateTime = \Carbon\Carbon::parse($task->schedule_date . ' ' . $task->start_time);
                    if(now()->gt($startDateTime)) {
                        $timeText = "Jadwal sedang berlangsung / lewat";
                        $timeColor = "text-error bg-error-container";
                    } else {
                        $diff = now()->diff($startDateTime);
                        $timeText = "Dimulai dalam: " . ($diff->d > 0 ? $diff->d . ' hari ' : '') . $diff->h . " jam " . $diff->i . " menit";
                        $timeColor = "text-on-primary-container bg-primary-container";
                    }
                @endphp
                <div class="flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-[16px] text-outline">schedule</span>
                    <span class="text-xs font-bold px-2 py-1 rounded-md {{ $timeColor }}">{{ $timeText }}</span>
                </div>

                <div class="bg-surface-bright p-3 rounded-lg flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ $task->requester->avatar_url }}" alt="Requester Avatar" class="w-8 h-8 rounded-full object-cover">
                        <div>
                            <p class="text-xs text-on-surface-variant">Requester:</p>
                            <p class="text-sm font-bold text-on-surface">{{ $task->requester->name }}</p>
                        </div>
                    </div>
                    <a href="{{ route('chat.show', $task->id) }}" class="text-[10px] font-bold text-on-primary bg-primary px-3 py-1 rounded-full flex items-center gap-1 shadow-sm">
                        <span class="material-symbols-outlined text-[14px]">chat</span> Chat
                    </a>
                </div>

                @if($task->status == 'In Progress')
                    @php
                        $startDateTime = \Carbon\Carbon::parse($task->schedule_date . ' ' . $task->start_time);
                        $isStarted = now()->gte($startDateTime);
                    @endphp
                    <div class="flex justify-end">
                        @if($isStarted)
                            <form action="{{ route('task.complete', $task->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-xs font-bold text-on-primary bg-primary px-4 py-2 rounded-lg hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-sm" onclick="return confirm('Peringatan: Tombol ini cuma dipencet KALAU KERJAAN LU UDAH BERES ya! Udah beneran kelar?')">
                                    Lapor Tugas Selesai
                                </button>
                            </form>
                        @else
                            <button disabled class="text-xs font-bold text-on-surface-variant bg-surface-container-high px-4 py-2 rounded-lg opacity-50 cursor-not-allowed">
                                Belum Waktunya Lapor Selesai
                            </button>
                        @endif
                    </div>
                @endif
            </article>
            @empty
                <div class="text-center py-12">
                    <p class="text-on-surface-variant text-sm">Lu belum ngambil kerjaan apapun.</p>
                </div>
            @endforelse
        </div>
    </div>
</main>
@endsection
