@extends('layouts.admin')
@section('title', 'Users Management')
@section('page_title', 'Manajemen Users')
@section('page_description', 'Kelola semua akun pengguna KawanKampus')

@section('content')
<div class="bg-surface rounded-3xl shadow-sm border border-surface-bright overflow-hidden relative z-10">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-low/50 text-on-surface-variant text-xs uppercase tracking-wider border-b border-surface-bright">
                    <th class="p-5 font-bold">User</th>
                    <th class="p-5 font-bold">Email</th>
                    <th class="p-5 font-bold">Poin</th>
                    <th class="p-5 font-bold">Status</th>
                    <th class="p-5 font-bold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @foreach($users as $user)
                <tr class="border-b border-surface-bright last:border-0 hover:bg-surface-container-low transition-colors group">
                    <td class="p-5 text-on-surface flex items-center gap-3 font-medium">
                        <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=FFFFFF&background=0040df' }}" class="w-9 h-9 rounded-full border border-surface-bright" alt="avatar">
                        <div>
                            <div class="font-bold">{{ $user->name }}</div>
                            @if($user->is_admin)
                                <span class="text-[10px] bg-primary text-white px-2 py-0.5 rounded-full shadow-sm mt-1 inline-block">Admin</span>
                            @endif
                        </div>
                    </td>
                    <td class="p-5 text-on-surface-variant">{{ $user->email }}</td>
                    <td class="p-5 font-bold text-secondary">{{ number_format($user->points, 0, ',', '.') }} pts</td>
                    <td class="p-5">
                        @if($user->is_banned)
                            <span class="px-3 py-1.5 rounded-full text-[11px] font-bold tracking-wide shadow-sm bg-error text-white">Banned</span>
                        @else
                            <span class="px-3 py-1.5 rounded-full text-[11px] font-bold tracking-wide shadow-sm bg-tertiary-container text-white">Active</span>
                        @endif
                    </td>
                    <td class="p-5 text-right opacity-80 group-hover:opacity-100 transition-opacity">
                        @if(!$user->is_admin && !$user->is_banned)
                        <form action="{{ route('admin.user.ban', $user->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" onclick="return confirm('Yakin mau nge-BANNED {{ $user->name }}?')" class="inline-flex items-center gap-1.5 text-xs font-bold text-error hover:bg-error hover:text-white border border-error px-4 py-2 rounded-xl transition-all duration-300">
                                <span class="material-symbols-outlined text-[16px]">block</span> Banned
                            </button>
                        </form>
                        @elseif($user->is_banned)
                            <span class="text-xs font-bold text-on-surface-variant bg-surface-container px-3 py-1.5 rounded-lg border border-surface-bright italic">Banned</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="p-5 border-t border-surface-bright">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
