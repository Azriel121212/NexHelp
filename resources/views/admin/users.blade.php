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
                    <td class="p-5 text-on-surface font-medium">
                        <div class="flex items-center gap-3">
                            <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=FFFFFF&background=0040df' }}" class="w-9 h-9 rounded-full border border-surface-bright" alt="avatar">
                            <div>
                                <div class="font-bold">{{ $user->name }}</div>
                                @if($user->is_admin)
                                    <span class="text-[10px] bg-primary text-white px-2 py-0.5 rounded-full shadow-sm mt-1 inline-block">Admin</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="p-5 text-on-surface-variant">{{ $user->email }}</td>
                    <td class="p-5 font-bold text-secondary">{{ number_format($user->points, 0, ',', '.') }} pts</td>
                    <td class="p-5">
                        @if($user->is_banned)
                            <span class="px-3 py-1.5 rounded-full text-[11px] font-bold tracking-wide shadow-sm bg-error text-white">Banned Permanen</span>
                        @elseif($user->banned_until && $user->banned_until->isFuture())
                            <span class="px-3 py-1.5 rounded-full text-[11px] font-bold tracking-wide shadow-sm bg-[#FFB400] text-[#4A3400]" title="Sampai {{ $user->banned_until->format('d M Y H:i') }}">Suspend</span>
                        @else
                            <span class="px-3 py-1.5 rounded-full text-[11px] font-bold tracking-wide shadow-sm bg-tertiary-container text-white">Active</span>
                        @endif
                    </td>
                    <td class="p-5 text-right opacity-80 group-hover:opacity-100 transition-opacity">
                        @if(!$user->is_admin)
                            @if(!$user->is_banned)
                            <form id="ban-form-{{ $user->id }}" action="{{ route('admin.user.ban', $user->id) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="duration" id="ban-duration-{{ $user->id }}" value="">
                                <button type="button" onclick="banUserOptions({{ $user->id }}, '{{ addslashes($user->name) }}')" class="inline-flex items-center gap-1.5 text-xs font-bold text-white bg-gradient-to-r from-error to-[#ff6b6b] px-4 py-2 rounded-xl hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                                    <span class="material-symbols-outlined text-[16px]">block</span> Tindak User
                                </button>
                            </form>
                            @else
                            <span class="text-xs font-bold text-error bg-error/10 px-3 py-1.5 rounded-lg border border-error/20">Banned Permanen</span>
                            @endif
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
<script>
    function banUserOptions(userId, userName, reportId = null) {
        Swal.fire({
            title: 'Tindak User: ' + userName,
            text: 'Pilih jenis sanksi yang akan diberikan:',
            icon: 'warning',
            input: 'select',
            inputOptions: {
                '1': 'Suspend 1 Hari',
                '3': 'Suspend 3 Hari',
                '7': 'Suspend 7 Hari',
                '14': 'Suspend 14 Hari',
                '30': 'Suspend 1 Bulan',
                'permanent': 'Banned Permanen'
            },
            inputPlaceholder: 'Pilih durasi sanksi...',
            showCancelButton: true,
            confirmButtonColor: '#ba1a1a',
            cancelButtonColor: '#747688',
            confirmButtonText: 'Terapkan',
            cancelButtonText: 'Batal',
            inputValidator: (value) => {
                if (!value) {
                    return 'Anda harus memilih salah satu opsi!'
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('ban-duration-' + userId).value = result.value;
                if (reportId) {
                    const reportInput = document.getElementById('ban-report-' + userId);
                    if (reportInput) reportInput.value = reportId;
                }
                document.getElementById('ban-form-' + userId).submit();
            }
        });
    }
</script>
@endsection
