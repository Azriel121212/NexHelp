@extends('layouts.admin')
@section('title', 'Tasks Management')
@section('page_title', 'Semua Tugas')
@section('page_description', 'Kelola semua tugas yang ada di KawanKampus')

@section('content')
<div class="bg-surface rounded-3xl shadow-sm border border-surface-bright overflow-hidden relative z-10">
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
                @foreach($tasks as $task)
                <tr class="border-b border-surface-bright last:border-0 hover:bg-surface-container-low transition-colors group">
                    <td class="p-5 text-on-surface font-semibold">
                        <a href="{{ route('task.show', $task->id) }}" class="hover:text-primary transition-colors flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] text-primary/70">assignment</span>
                            {{ $task->title }}
                        </a>
                    </td>
                    <td class="p-5 text-on-surface-variant">
                        <div class="flex items-center gap-2">
                            <img src="{{ $task->requester->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($task->requester->name).'&color=FFFFFF&background=0040df' }}" class="w-6 h-6 rounded-full" alt="avatar">
                            {{ $task->requester->name }}
                        </div>
                    </td>
                    <td class="p-5">
                        <span class="bg-surface-container-high text-on-surface px-3 py-1.5 rounded-full text-[11px] font-bold tracking-wide">{{ $task->category }}</span>
                    </td>
                    <td class="p-5">
                        <span class="px-3 py-1.5 rounded-full text-[11px] font-bold tracking-wide shadow-sm
                            {{ strtolower($task->status) == 'open' ? 'bg-primary-container text-on-primary-container' : '' }}
                            {{ strtolower($task->status) == 'in progress' || strtolower($task->status) == 'in_progress' ? 'bg-[#FFB400]/20 text-[#4A3400]' : '' }}
                            {{ strtolower($task->status) == 'completed' ? 'bg-tertiary-container text-white' : '' }}
                            {{ strtolower($task->status) == 'cancelled' ? 'bg-error-container text-on-error-container' : '' }}
                            {{ strtolower($task->status) == 'pending approval' ? 'bg-[#FFB400] text-[#4A3400]' : '' }}
                        ">
                            {{ ucfirst($task->status) }}
                        </span>
                    </td>
                    <td class="p-5 text-right opacity-80 group-hover:opacity-100 transition-opacity">
                        <form action="{{ route('admin.task.destroy', $task->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin mau menghapus tugas ini secara paksa?')">
                            @csrf
                            <input type="hidden" name="reason" value="Dihapus dari menu Semua Tugas oleh Admin">
                            <button type="submit" class="inline-flex items-center gap-1.5 text-xs font-bold text-error bg-error/10 px-4 py-2 rounded-xl hover:bg-error hover:text-white transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                                <span class="material-symbols-outlined text-[16px]">delete</span> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
                @if($tasks->isEmpty())
                <tr>
                    <td colspan="5" class="p-10 text-center">
                        <div class="flex flex-col items-center justify-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl mb-2 opacity-50">inbox</span>
                            <p class="font-bold">Belum ada tugas di database.</p>
                        </div>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    @if($tasks->hasPages())
    <div class="p-5 border-t border-surface-bright">
        {{ $tasks->links() }}
    </div>
    @endif
</div>
@endsection
