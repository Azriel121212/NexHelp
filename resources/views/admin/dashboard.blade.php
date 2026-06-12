@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard')
@section('page_description', 'Pusat Kendali Data KawanKampus')

@section('content')

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 relative z-10">
        <div class="bg-surface rounded-3xl p-6 shadow-sm border border-surface-bright flex items-center gap-5 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <div class="w-14 h-14 rounded-2xl bg-primary/10 text-primary flex justify-center items-center shadow-inner">
                <span class="material-symbols-outlined text-2xl">group</span>
            </div>
            <div>
                <p class="text-on-surface-variant text-xs font-bold uppercase tracking-wider mb-1">Total Users</p>
                <h3 class="text-3xl font-extrabold text-on-surface">{{ $totalUsers }}</h3>
            </div>
        </div>
        
        <div class="bg-surface rounded-3xl p-6 shadow-sm border border-surface-bright flex items-center gap-5 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <div class="w-14 h-14 rounded-2xl bg-[#FFB400]/10 text-[#FFB400] flex justify-center items-center shadow-inner">
                <span class="material-symbols-outlined text-2xl">task</span>
            </div>
            <div>
                <p class="text-on-surface-variant text-xs font-bold uppercase tracking-wider mb-1">Total Tasks</p>
                <h3 class="text-3xl font-extrabold text-on-surface">{{ $totalTasks }}</h3>
            </div>
        </div>
        
        <div class="bg-surface rounded-3xl p-6 shadow-sm border border-surface-bright flex items-center gap-5 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <div class="w-14 h-14 rounded-2xl bg-error/10 text-error flex justify-center items-center shadow-inner">
                <span class="material-symbols-outlined text-2xl">pending_actions</span>
            </div>
            <div>
                <p class="text-on-surface-variant text-xs font-bold uppercase tracking-wider mb-1">Active Tasks</p>
                <h3 class="text-3xl font-extrabold text-on-surface">{{ $activeTasks }}</h3>
            </div>
        </div>

        <div class="bg-surface rounded-3xl p-6 shadow-sm border border-surface-bright flex items-center gap-5 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <div class="w-14 h-14 rounded-2xl bg-secondary/10 text-secondary flex justify-center items-center shadow-inner">
                <span class="material-symbols-outlined text-2xl">toll</span>
            </div>
            <div>
                <p class="text-on-surface-variant text-xs font-bold uppercase tracking-wider mb-1">Poin Beredar</p>
                <h3 class="text-3xl font-extrabold text-on-surface">{{ number_format($totalPointsCirculating, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <!-- Pending Tasks List -->
    <section class="animate-fade-in-up mt-8">
        <h2 class="text-xl font-extrabold text-on-surface mb-5 flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-error/10 flex items-center justify-center text-error">
                <span class="material-symbols-outlined text-[18px]">pending_actions</span>
            </div>
            Menunggu Persetujuan
        </h2>
        <div class="bg-surface rounded-3xl shadow-sm border border-surface-bright overflow-hidden hover:shadow-md transition-shadow duration-300">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low/50 text-on-surface-variant text-xs uppercase tracking-wider border-b border-surface-bright">
                            <th class="p-5 font-bold">Judul</th>
                            <th class="p-5 font-bold">Pembuat</th>
                            <th class="p-5 font-bold">Kategori</th>
                            <th class="p-5 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm" id="pending-tasks-tbody">
                        @forelse($pendingTasks as $pt)
                        <tr class="border-b border-surface-bright last:border-0 hover:bg-surface-container-low transition-colors group">
                            <td class="p-5 text-on-surface font-semibold">
                                <a href="{{ route('task.show', $pt->id) }}" class="hover:text-primary transition-colors flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px] text-primary/70">assignment</span>
                                    {{ $pt->title }}
                                </a>
                            </td>
                            <td class="p-5 text-on-surface-variant flex items-center gap-2">
                                <img src="{{ $pt->requester->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($pt->requester->name).'&color=FFFFFF&background=0040df' }}" class="w-6 h-6 rounded-full" alt="avatar">
                                {{ $pt->requester->name }}
                            </td>
                            <td class="p-5">
                                <span class="bg-surface-container-high text-on-surface px-3 py-1.5 rounded-full text-[11px] font-bold tracking-wide">{{ $pt->category }}</span>
                            </td>
                            <td class="p-5 text-right flex justify-end gap-2 opacity-80 group-hover:opacity-100 transition-opacity">
                                <form action="{{ route('admin.task.approve', $pt->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 text-xs font-bold text-white bg-gradient-to-r from-primary to-primary-container px-4 py-2 rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                                        <span class="material-symbols-outlined text-[16px]">check_circle</span> ACC
                                    </button>
                                </form>
                                <button type="button" onclick="confirmDelete({{ $pt->id }})" class="inline-flex items-center gap-1.5 text-xs font-bold text-error bg-error/10 px-4 py-2 rounded-xl hover:bg-error hover:text-white transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                                    <span class="material-symbols-outlined text-[16px]">cancel</span> Tolak
                                </button>
                                
                                <form id="delete-form-{{ $pt->id }}" action="{{ route('admin.task.destroy', $pt->id) }}" method="POST" class="hidden">
                                    @csrf
                                    <input type="hidden" name="reason" id="delete-reason-{{ $pt->id }}">
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-10 text-center">
                                <div class="flex flex-col items-center justify-center text-on-surface-variant">
                                    <span class="material-symbols-outlined text-4xl mb-2 opacity-50">done_all</span>
                                    <p class="font-bold">Tidak ada request yang menunggu persetujuan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Reports List -->
    <section class="animate-fade-in-up" style="animation-delay: 100ms;">
        <h2 class="text-xl font-extrabold text-on-surface mb-5 flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-[#FFB400]/10 flex items-center justify-center text-[#FFB400]">
                <span class="material-symbols-outlined text-[18px]">flag</span>
            </div>
            Laporan User (Report)
        </h2>
        <div class="bg-surface rounded-3xl shadow-sm border border-surface-bright overflow-hidden hover:shadow-md transition-shadow duration-300">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low/50 text-on-surface-variant text-xs uppercase tracking-wider border-b border-surface-bright">
                            <th class="p-5 font-bold">Pelapor</th>
                            <th class="p-5 font-bold">Terlapor</th>
                            <th class="p-5 font-bold">Alasan</th>
                            <th class="p-5 font-bold">Status</th>
                            <th class="p-5 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($reports ?? [] as $report)
                        <tr class="border-b border-surface-bright last:border-0 hover:bg-surface-container-low transition-colors group">
                            <td class="p-5 text-on-surface flex items-center gap-2 font-medium">
                                <img src="{{ $report->reporter->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($report->reporter->name).'&color=FFFFFF&background=0040df' }}" class="w-6 h-6 rounded-full" alt="avatar">
                                {{ $report->reporter->name }}
                            </td>
                            <td class="p-5 text-error font-bold flex items-center gap-2">
                                <img src="{{ $report->reported->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($report->reported->name).'&color=FFFFFF&background=ba1a1a' }}" class="w-6 h-6 rounded-full" alt="avatar">
                                {{ $report->reported->name }}
                                @if($report->reported->is_banned)
                                    <span class="ml-1 text-[10px] bg-error text-white px-2 py-0.5 rounded-full shadow-sm">BANNED</span>
                                @endif
                            </td>
                            <td class="p-5 text-on-surface-variant max-w-xs">
                                <div class="truncate bg-surface-container-low px-3 py-1.5 rounded-lg border border-surface-bright" title="{{ $report->reason }}">
                                    {{ $report->reason }}
                                </div>
                            </td>
                            <td class="p-5">
                                <span class="px-3 py-1.5 rounded-full text-[11px] font-bold tracking-wide shadow-sm
                                    {{ $report->status == 'Pending' ? 'bg-[#FFB400]/20 text-[#4A3400]' : '' }}
                                    {{ $report->status == 'Action Taken' ? 'bg-error text-white' : '' }}
                                ">
                                    {{ $report->status }}
                                </span>
                            </td>
                            <td class="p-5 text-right opacity-80 group-hover:opacity-100 transition-opacity">
                                @if(!$report->reported->is_banned)
                                <form action="{{ route('admin.user.ban', $report->reported_id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="report_id" value="{{ $report->id }}">
                                    <button type="submit" onclick="return confirm('Yakin mau nge-BANNED {{ $report->reported->name }}? Dia nggak bakal bisa login lagi.')" class="inline-flex items-center gap-1.5 text-xs font-bold text-white bg-gradient-to-r from-error to-[#ff6b6b] px-4 py-2 rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                                        <span class="material-symbols-outlined text-[16px]">block</span> Banned User
                                    </button>
                                </form>
                                @else
                                <span class="text-xs font-bold text-on-surface-variant bg-surface-container px-3 py-1.5 rounded-lg border border-surface-bright italic">Sudah Ditindak</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-10 text-center">
                                <div class="flex flex-col items-center justify-center text-on-surface-variant">
                                    <span class="material-symbols-outlined text-4xl mb-2 text-[#FFB400]/50">verified_user</span>
                                    <p class="font-bold">Belum ada laporan. Aman terkendali!</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Tasks List -->
    <section class="animate-fade-in-up" style="animation-delay: 200ms;">
        <h2 class="text-xl font-extrabold text-on-surface mb-5 flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-[18px]">list_alt</span>
            </div>
            Semua Task (Log)
        </h2>
        <div class="bg-surface rounded-3xl shadow-sm border border-surface-bright overflow-hidden hover:shadow-md transition-shadow duration-300">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low/50 text-on-surface-variant text-xs uppercase tracking-wider border-b border-surface-bright">
                            <th class="p-5 font-bold">Judul</th>
                            <th class="p-5 font-bold">Pembuat</th>
                            <th class="p-5 font-bold">Kategori</th>
                            <th class="p-5 font-bold">Status</th>
                            <th class="p-5 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($tasks as $task)
                        <tr class="border-b border-surface-bright last:border-0 hover:bg-surface-container-low transition-colors group">
                            <td class="p-5 text-on-surface font-semibold">
                                <a href="{{ route('task.show', $task->id) }}" class="hover:text-primary transition-colors flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px] text-primary/70">assignment</span>
                                    {{ $task->title }}
                                </a>
                            </td>
                            <td class="p-5 text-on-surface-variant flex items-center gap-2">
                                <img src="{{ $task->requester->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($task->requester->name).'&color=FFFFFF&background=0040df' }}" class="w-6 h-6 rounded-full" alt="avatar">
                                {{ $task->requester->name }}
                            </td>
                            <td class="p-5">
                                <span class="bg-surface-container-high text-on-surface px-3 py-1.5 rounded-full text-[11px] font-bold tracking-wide">{{ $task->category }}</span>
                            </td>
                            <td class="p-5">
                                <span class="px-3 py-1.5 rounded-full text-[11px] font-bold tracking-wide shadow-sm
                                    {{ $task->status == 'open' ? 'bg-primary-container text-on-primary-container' : '' }}
                                    {{ $task->status == 'in_progress' ? 'bg-[#FFB400]/20 text-[#4A3400]' : '' }}
                                    {{ $task->status == 'completed' ? 'bg-tertiary-container text-white' : '' }}
                                    {{ $task->status == 'cancelled' ? 'bg-error-container text-on-error-container' : '' }}
                                ">
                                    {{ ucfirst($task->status) }}
                                </span>
                            </td>
                            <td class="p-5 text-right opacity-80 group-hover:opacity-100 transition-opacity">
                                <button type="button" onclick="confirmDelete({{ $task->id }})" class="inline-flex items-center gap-1.5 text-xs font-bold text-error bg-error/10 px-4 py-2 rounded-xl hover:bg-error hover:text-white transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                                    <span class="material-symbols-outlined text-[16px]">delete</span> Hapus
                                </button>
                                
                                <form id="delete-form-{{ $task->id }}" action="{{ route('admin.task.destroy', $task->id) }}" method="POST" class="hidden">
                                    @csrf
                                    <input type="hidden" name="reason" id="delete-reason-{{ $task->id }}">
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-10 text-center">
                                <div class="flex flex-col items-center justify-center text-on-surface-variant">
                                    <span class="material-symbols-outlined text-4xl mb-2 opacity-50">inbox</span>
                                    <p class="font-bold">Belum ada task di database.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Analytics Chart -->
    <section class="animate-fade-in-up" style="animation-delay: 300ms;">
        <h2 class="text-xl font-extrabold text-on-surface mb-5 flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-[18px]">pie_chart</span>
            </div>
            Statistik Kategori Tugas
        </h2>
        <div class="bg-surface rounded-3xl p-6 shadow-sm border border-surface-bright flex justify-center items-center hover:shadow-md transition-shadow duration-300">
            <div class="w-full max-w-md">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </section>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.5s ease-out forwards;
        opacity: 0;
    }
</style>

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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('categoryChart').getContext('2d');
        const tasksByCategory = @json($tasksByCategory);
        
        const labels = Object.keys(tasksByCategory);
        const data = Object.values(tasksByCategory);
        
        // Warna cerah untuk setiap kategori
        const backgroundColors = [
            '#0040df', // Primary
            '#ff6b6b', // Error
            '#FFB400', // Warning
            '#4caf50', // Success
            '#9c27b0', // Purple
            '#00bcd4', // Cyan
            '#ff9800', // Orange
            '#607d8b'  // Blue Grey
        ];

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: backgroundColors.slice(0, labels.length),
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                family: "'Nunito Sans', sans-serif",
                                size: 12,
                                weight: 'bold'
                            },
                            color: '#44474f'
                        }
                    }
                },
                cutout: '70%'
            }
        });
    });
</script>
@endsection
