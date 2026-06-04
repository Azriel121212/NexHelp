@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<!-- TopAppBar -->
<header class="bg-surface text-primary shadow-sm flex items-center px-4 py-3 w-full sticky top-0 z-50">
    <a href="{{ route('home') }}" class="flex items-center gap-2 hover:bg-surface-bright p-2 -ml-2 rounded-xl transition-colors">
        <span class="material-symbols-outlined text-on-surface-variant">arrow_back</span>
        <h1 class="font-headline-md text-xl sm:text-2xl text-error font-extrabold tracking-tight">Admin Panel</h1>
    </a>
</header>

<main class="px-4 py-6 max-w-7xl mx-auto space-y-6 w-full">
    
    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-surface rounded-2xl p-5 shadow-sm border border-surface-bright flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-primary-container text-on-primary-container flex justify-center items-center">
                <span class="material-symbols-outlined">group</span>
            </div>
            <div>
                <p class="text-on-surface-variant text-xs font-bold uppercase">Total Users</p>
                <h3 class="text-2xl font-bold text-on-surface">{{ $totalUsers }}</h3>
            </div>
        </div>
        <div class="bg-surface rounded-2xl p-5 shadow-sm border border-surface-bright flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-secondary-fixed text-on-secondary-fixed flex justify-center items-center">
                <span class="material-symbols-outlined">task</span>
            </div>
            <div>
                <p class="text-on-surface-variant text-xs font-bold uppercase">Total Tasks</p>
                <h3 class="text-2xl font-bold text-on-surface">{{ $totalTasks }}</h3>
            </div>
        </div>
        <div class="bg-surface rounded-2xl p-5 shadow-sm border border-surface-bright flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-error-container text-on-error-container flex justify-center items-center">
                <span class="material-symbols-outlined">pending_actions</span>
            </div>
            <div>
                <p class="text-on-surface-variant text-xs font-bold uppercase">Active Tasks</p>
                <h3 class="text-2xl font-bold text-on-surface">{{ $activeTasks }}</h3>
            </div>
        </div>
    </div>

    <!-- Pending Tasks List -->
    <section>
        <h2 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-error">pending_actions</span>
            Menunggu Persetujuan
        </h2>
        <div class="bg-surface rounded-2xl shadow-sm border border-surface-bright overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-on-surface-variant text-xs uppercase tracking-wider border-b border-surface-bright">
                            <th class="p-4 font-bold">Judul</th>
                            <th class="p-4 font-bold">Pembuat</th>
                            <th class="p-4 font-bold">Kategori</th>
                            <th class="p-4 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm" id="pending-tasks-tbody">
                        @forelse($pendingTasks as $pt)
                        <tr class="border-b border-surface-bright last:border-0 hover:bg-surface-container-low transition-colors">
                            <td class="p-4 text-on-surface font-semibold">
                                <a href="{{ route('task.show', $pt->id) }}" class="hover:underline text-primary">{{ $pt->title }}</a>
                            </td>
                            <td class="p-4 text-on-surface-variant">
                                {{ $pt->requester->name }}
                            </td>
                            <td class="p-4">
                                <span class="bg-surface-container-high text-on-surface px-2 py-1 rounded text-xs">{{ $pt->category }}</span>
                            </td>
                            <td class="p-4 text-right flex justify-end gap-2">
                                <form action="{{ route('admin.task.approve', $pt->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1 text-xs font-bold text-on-primary bg-primary px-3 py-1.5 rounded-lg hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-sm">
                                        <span class="material-symbols-outlined text-[14px]">check_circle</span> ACC
                                    </button>
                                </form>
                                <button type="button" onclick="confirmDelete({{ $pt->id }})" class="inline-flex items-center gap-1 text-xs font-bold text-error bg-error-container px-3 py-1.5 rounded-lg hover:bg-error hover:text-white transition-colors shadow-sm">
                                    <span class="material-symbols-outlined text-[14px]">cancel</span> Tolak
                                </button>
                                
                                <form id="delete-form-{{ $pt->id }}" action="{{ route('admin.task.destroy', $pt->id) }}" method="POST" class="hidden">
                                    @csrf
                                    <input type="hidden" name="reason" id="delete-reason-{{ $pt->id }}">
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-on-surface-variant">Tidak ada request yang menunggu persetujuan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Tasks List -->
    <section>
        <h2 class="text-lg font-bold text-on-surface mb-4">Semua Task</h2>
        <div class="bg-surface rounded-2xl shadow-sm border border-surface-bright overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-on-surface-variant text-xs uppercase tracking-wider border-b border-surface-bright">
                            <th class="p-4 font-bold">Judul</th>
                            <th class="p-4 font-bold">Pembuat</th>
                            <th class="p-4 font-bold">Kategori</th>
                            <th class="p-4 font-bold">Status</th>
                            <th class="p-4 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($tasks as $task)
                        <tr class="border-b border-surface-bright last:border-0 hover:bg-surface-container-low transition-colors">
                            <td class="p-4 text-on-surface font-semibold">
                                <a href="{{ route('task.show', $task->id) }}" class="hover:underline text-primary">{{ $task->title }}</a>
                            </td>
                            <td class="p-4 text-on-surface-variant">
                                {{ $task->requester->name }}
                            </td>
                            <td class="p-4">
                                <span class="bg-surface-container-high text-on-surface px-2 py-1 rounded text-xs">{{ $task->category }}</span>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded text-xs font-bold 
                                    {{ $task->status == 'open' ? 'bg-primary-fixed text-on-primary-fixed' : '' }}
                                    {{ $task->status == 'in_progress' ? 'bg-secondary-fixed text-on-secondary-fixed' : '' }}
                                    {{ $task->status == 'completed' ? 'bg-tertiary-container text-white' : '' }}
                                    {{ $task->status == 'cancelled' ? 'bg-error-container text-on-error-container' : '' }}
                                ">
                                    {{ ucfirst($task->status) }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <button type="button" onclick="confirmDelete({{ $task->id }})" class="inline-flex items-center gap-1 text-xs font-bold text-error bg-error-container px-3 py-1.5 rounded-lg hover:bg-error hover:text-white transition-colors">
                                    <span class="material-symbols-outlined text-[14px]">delete</span> Hapus
                                </button>
                                
                                <form id="delete-form-{{ $task->id }}" action="{{ route('admin.task.destroy', $task->id) }}" method="POST" class="hidden">
                                    @csrf
                                    <input type="hidden" name="reason" id="delete-reason-{{ $task->id }}">
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-on-surface-variant">Belum ada task.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Reports List -->
    <section class="mt-8">
        <h2 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-error">flag</span>
            Laporan User (Report)
        </h2>
        <div class="bg-surface rounded-2xl shadow-sm border border-surface-bright overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-on-surface-variant text-xs uppercase tracking-wider border-b border-surface-bright">
                            <th class="p-4 font-bold">Pelapor</th>
                            <th class="p-4 font-bold">Terlapor</th>
                            <th class="p-4 font-bold">Alasan</th>
                            <th class="p-4 font-bold">Status</th>
                            <th class="p-4 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($reports ?? [] as $report)
                        <tr class="border-b border-surface-bright last:border-0 hover:bg-surface-container-low transition-colors">
                            <td class="p-4 text-on-surface">
                                {{ $report->reporter->name }}
                            </td>
                            <td class="p-4 text-error font-bold">
                                {{ $report->reported->name }}
                                @if($report->reported->is_banned)
                                    <span class="ml-1 text-[10px] bg-error text-white px-2 py-0.5 rounded-full">BANNED</span>
                                @endif
                            </td>
                            <td class="p-4 text-on-surface-variant max-w-xs truncate" title="{{ $report->reason }}">
                                {{ $report->reason }}
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded text-xs font-bold 
                                    {{ $report->status == 'Pending' ? 'bg-surface-container-high text-on-surface-variant' : '' }}
                                    {{ $report->status == 'Action Taken' ? 'bg-primary text-white' : '' }}
                                ">
                                    {{ $report->status }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                @if(!$report->reported->is_banned)
                                <form action="{{ route('admin.user.ban', $report->reported_id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="report_id" value="{{ $report->id }}">
                                    <button type="submit" onclick="return confirm('Yakin mau nge-BANNED {{ $report->reported->name }}? Dia nggak bakal bisa login lagi.')" class="inline-flex items-center gap-1 text-xs font-bold text-white bg-error px-3 py-1.5 rounded-lg hover:bg-error-container hover:text-error transition-colors">
                                        <span class="material-symbols-outlined text-[14px]">block</span> Banned User
                                    </button>
                                </form>
                                @else
                                <span class="text-xs text-on-surface-variant italic">Sudah Ditindak</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-on-surface-variant">Belum ada laporan. Aman terkendali!</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>

<script>
    function confirmDelete(taskId) {
        Swal.fire({
            title: 'Hapus Task Ini?',
            text: "Berikan alasan kenapa task ini dihapus (misal: melanggar aturan, tidak sesuai kriteria):",
            icon: 'warning',
            input: 'text',
            inputPlaceholder: 'Masukkan alasan penghapusan...',
            inputAttributes: {
                required: true
            },
            showCancelButton: true,
            confirmButtonColor: '#ba1a1a',
            cancelButtonColor: '#747688',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            preConfirm: (reason) => {
                if (!reason) {
                    Swal.showValidationMessage('Alasan wajib diisi!');
                }
                return reason;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Set value ke hidden input
                document.getElementById('delete-reason-' + taskId).value = result.value;
                // Submit form
                document.getElementById('delete-form-' + taskId).submit();
            }
        })
    }

    function promptReject(form) {
        Swal.fire({
            title: 'Tolak Task Ini?',
            text: "Berikan alasan kenapa ditolak (misal: spam, kurang detail):",
            icon: 'warning',
            input: 'text',
            inputPlaceholder: 'Masukkan alasan...',
            inputAttributes: { required: true },
            showCancelButton: true,
            confirmButtonColor: '#ba1a1a',
            cancelButtonColor: '#747688',
            confirmButtonText: 'Ya, Tolak!',
            cancelButtonText: 'Batal',
            preConfirm: (reason) => {
                if (!reason) Swal.showValidationMessage('Alasan wajib diisi!');
                return reason;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.querySelector('.reject-reason-input').value = result.value;
                form.submit();
            }
        })
    }

    // Polling Pending Tasks every 5 seconds
    setInterval(() => {
        fetch('{{ route("admin.tasks.pending_html") }}')
            .then(res => res.json())
            .then(data => {
                if (data.html !== undefined) {
                    document.getElementById('pending-tasks-tbody').innerHTML = data.html;
                }
            })
            .catch(err => console.error(err));
    }, 5000);
</script>
@endsection
