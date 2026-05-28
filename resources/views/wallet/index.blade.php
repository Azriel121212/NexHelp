@extends('layouts.app')
@section('title', 'Dompet Gw')

@section('content')
<header class="bg-surface text-primary shadow-sm flex items-center px-4 py-3 w-full sticky top-0 z-50">
    <a href="{{ route('home') }}" class="p-2 rounded-full hover:bg-surface-bright transition-colors text-on-surface mr-2">
        <span class="material-symbols-outlined">arrow_back</span>
    </a>
    <h1 class="font-headline-md text-xl font-bold flex-1 text-center pr-8">Dompet Poin</h1>
</header>

<main class="px-4 py-8 max-w-md mx-auto w-full pb-24">
    <!-- Saldo Card -->
    <div class="bg-gradient-to-r from-primary to-[#5b4a9c] rounded-3xl p-6 text-on-primary shadow-lg mb-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-20">
            <span class="material-symbols-outlined text-6xl">account_balance_wallet</span>
        </div>
        <p class="text-sm font-medium mb-1 opacity-90">Total Saldo Aktif</p>
        <div class="flex items-baseline gap-2 mb-4">
            <span class="text-4xl font-bold">{{ number_format($user->points, 0, ',', '.') }}</span>
            <span class="text-lg font-medium">Pts</span>
        </div>
        <div class="text-xs opacity-90 font-medium">
            Setara dengan Rp {{ number_format($user->points * 1000, 0, ',', '.') }}
        </div>
    </div>

    <!-- Menu Tabs -->
    <div class="flex bg-surface-container-low rounded-full p-1 mb-6">
        <button id="btn-topup" class="flex-1 py-2 text-sm font-bold text-center rounded-full bg-primary text-on-primary shadow-sm transition-colors" onclick="switchTab('topup')">Isi Poin</button>
        <button id="btn-withdraw" class="flex-1 py-2 text-sm font-bold text-center rounded-full text-on-surface-variant hover:text-primary transition-colors" onclick="switchTab('withdraw')">Tarik Tunai</button>
    </div>

    <!-- Top Up Section -->
    <div id="section-topup">
        <h2 class="font-bold text-on-surface mb-4">Pilih Paket Poin</h2>
        
        <form action="{{ route('wallet.topup') }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-3 mb-6">
                <!-- Package 20 -->
                <label class="cursor-pointer relative">
                    <input type="radio" name="package" value="20" class="peer hidden" required>
                    <div class="bg-surface border-2 border-surface-container-high rounded-2xl p-4 peer-checked:border-primary peer-checked:bg-primary-container transition-all">
                        <div class="text-center">
                            <span class="material-symbols-outlined text-[#FFB400] text-3xl mb-1">monetization_on</span>
                            <div class="font-bold text-lg text-on-surface">20 Pts</div>
                            <div class="text-xs text-on-surface-variant mb-2">Rp 20.000</div>
                            <div class="text-[10px] bg-primary text-on-primary px-2 py-0.5 rounded-full inline-block font-bold shadow-sm">
                                Total: Rp 23.000
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Package 50 -->
                <label class="cursor-pointer relative">
                    <input type="radio" name="package" value="50" class="peer hidden">
                    <div class="bg-surface border-2 border-surface-container-high rounded-2xl p-4 peer-checked:border-primary peer-checked:bg-primary-container transition-all">
                        <div class="text-center">
                            <span class="material-symbols-outlined text-[#FFB400] text-3xl mb-1">monetization_on</span>
                            <div class="font-bold text-lg text-on-surface">50 Pts</div>
                            <div class="text-xs text-on-surface-variant mb-2">Rp 50.000</div>
                            <div class="text-[10px] bg-primary text-on-primary px-2 py-0.5 rounded-full inline-block font-bold shadow-sm">
                                Total: Rp 53.000
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Package 100 -->
                <label class="cursor-pointer relative">
                    <input type="radio" name="package" value="100" class="peer hidden">
                    <div class="bg-surface border-2 border-surface-container-high rounded-2xl p-4 peer-checked:border-primary peer-checked:bg-primary-container transition-all">
                        <div class="text-center">
                            <span class="material-symbols-outlined text-[#FFB400] text-3xl mb-1">monetization_on</span>
                            <div class="font-bold text-lg text-on-surface">100 Pts</div>
                            <div class="text-xs text-on-surface-variant mb-2">Rp 100.000</div>
                            <div class="text-[10px] bg-primary text-on-primary px-2 py-0.5 rounded-full inline-block font-bold shadow-sm">
                                Total: Rp 103.000
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Package 150 -->
                <label class="cursor-pointer relative">
                    <input type="radio" name="package" value="150" class="peer hidden">
                    <div class="bg-surface border-2 border-surface-container-high rounded-2xl p-4 peer-checked:border-primary peer-checked:bg-primary-container transition-all">
                        <div class="text-center">
                            <span class="material-symbols-outlined text-[#FFB400] text-3xl mb-1">monetization_on</span>
                            <div class="font-bold text-lg text-on-surface">150 Pts</div>
                            <div class="text-xs text-on-surface-variant mb-2">Rp 150.000</div>
                            <div class="text-[10px] bg-primary text-on-primary px-2 py-0.5 rounded-full inline-block font-bold shadow-sm">
                                Total: Rp 153.000
                            </div>
                        </div>
                    </div>
                </label>
            </div>
            <p class="text-xs text-on-surface-variant text-center mb-6">
                *Semua paket dikenakan Flat Admin Fee Rp 3.000
            </p>
            <button type="submit" class="w-full bg-primary text-on-primary py-3.5 rounded-full font-bold hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-md">
                Bayar Sekarang
            </button>
        </form>
    </div>

    <!-- Withdraw Section -->
    <div id="section-withdraw" class="hidden">
        <h2 class="font-bold text-on-surface mb-4">Tarik Tunai Poin</h2>
        <div class="bg-surface-bright rounded-2xl p-4 mb-6 border border-surface-container-high">
            <p class="text-sm text-on-surface-variant mb-2">Aturan Penarikan:</p>
            <ul class="text-xs text-on-surface list-disc pl-4 space-y-1">
                <li>1 Poin setara dengan Rp 1.000 murni.</li>
                <li>Minimal penarikan adalah 50 Poin.</li>
                <li>Tanpa potongan biaya admin.</li>
            </ul>
        </div>
        
        <form action="{{ route('wallet.withdraw') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-bold text-on-surface mb-2">Pilih E-Wallet / Bank</label>
                <select name="payment_method" class="w-full bg-surface-container-lowest border border-outline-variant rounded-xl px-4 py-3 text-sm font-bold focus:ring-primary focus:border-primary" required>
                    <option value="" disabled selected>Pilih Tujuan Penarikan</option>
                    <option value="DANA">DANA</option>
                    <option value="OVO">OVO</option>
                    <option value="GoPay">GoPay</option>
                    <option value="ShopeePay">ShopeePay</option>
                    <option value="Bank BCA">Bank BCA</option>
                    <option value="Bank Mandiri">Bank Mandiri</option>
                    <option value="Bank BRI">Bank BRI</option>
                </select>
                @error('payment_method')
                    <p class="text-error text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold text-on-surface mb-2">Nomor Rekening / HP</label>
                <div class="relative">
                    <input type="text" name="account_number" placeholder="Contoh: 081234567890" class="w-full bg-surface-container-lowest border border-outline-variant rounded-xl px-4 py-3 pl-12 text-sm font-bold focus:ring-primary focus:border-primary" required>
                    <span class="material-symbols-outlined absolute left-4 top-3.5 text-outline-variant">account_balance</span>
                </div>
                @error('account_number')
                    <p class="text-error text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-on-surface mb-2">Jumlah Poin yang ditarik</label>
                <div class="relative">
                    <input type="number" name="points" id="withdraw-points" min="50" max="{{ $user->points }}" placeholder="Minimal 50" class="w-full bg-surface-container-lowest border border-outline-variant rounded-xl px-4 py-3 pl-12 text-sm font-bold focus:ring-primary focus:border-primary" required oninput="calculateWithdraw()">
                    <span class="material-symbols-outlined absolute left-4 top-3.5 text-outline-variant">payments</span>
                </div>
                @error('points')
                    <p class="text-error text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-between items-center mb-6 px-2">
                <span class="text-sm text-on-surface-variant font-medium">Dana Diterima:</span>
                <span class="text-lg font-bold text-primary" id="withdraw-estimate">Rp 0</span>
            </div>

            <button type="submit" class="w-full bg-primary text-on-primary py-3.5 rounded-full font-bold hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-md" {{ $user->points < 50 ? 'disabled' : '' }}>
                Tarik Tunai Sekarang
            </button>
            @if($user->points < 50)
                <p class="text-error text-xs text-center mt-2">Saldo poin lu belum cukup (Min. 50).</p>
            @endif
        </form>
    </div>

    <!-- Riwayat Transaksi -->
    <div class="mt-8">
        <h2 class="font-bold text-on-surface mb-4">Riwayat Transaksi</h2>
        <div class="space-y-3">
            @forelse($transactions as $trx)
            <div class="bg-surface flex items-center p-4 rounded-2xl shadow-sm border border-surface-container">
                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 {{ $trx->type == 'topup' ? 'bg-[#E8F5E9] text-[#2E7D32]' : 'bg-[#FFEBEE] text-[#C62828]' }}">
                    <span class="material-symbols-outlined text-[20px]">{{ $trx->type == 'topup' ? 'arrow_downward' : 'arrow_upward' }}</span>
                </div>
                <div class="ml-3 flex-grow min-w-0">
                    <h3 class="text-sm font-bold text-on-surface truncate">
                        {{ $trx->type == 'topup' ? 'Top-Up Poin' : 'Tarik Tunai' }}
                    </h3>
                    <p class="text-[10px] text-on-surface-variant">
                        {{ $trx->created_at->format('d M Y, H:i') }}
                        @if($trx->type == 'withdraw')
                            • {{ $trx->payment_method }} ({{ substr($trx->account_number, 0, -4) }}****)
                        @endif
                    </p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-bold {{ $trx->type == 'topup' ? 'text-[#2E7D32]' : 'text-[#C62828]' }}">
                        {{ $trx->type == 'topup' ? '+' : '-' }} {{ $trx->points }} Pts
                    </p>
                    <p class="text-[10px] text-on-surface-variant">Rp {{ number_format($trx->amount, 0, ',', '.') }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-6 bg-surface rounded-2xl border border-surface-container">
                <span class="material-symbols-outlined text-4xl text-outline-variant mb-2">receipt_long</span>
                <p class="text-sm text-on-surface-variant">Belum ada transaksi.</p>
            </div>
            @endforelse
        </div>
    </div>
</main>

<script>
    function switchTab(tab) {
        if (tab === 'topup') {
            document.getElementById('section-topup').classList.remove('hidden');
            document.getElementById('section-withdraw').classList.add('hidden');
            
            document.getElementById('btn-topup').classList.add('bg-primary', 'text-on-primary', 'shadow-sm');
            document.getElementById('btn-topup').classList.remove('text-on-surface-variant');
            
            document.getElementById('btn-withdraw').classList.remove('bg-primary', 'text-on-primary', 'shadow-sm');
            document.getElementById('btn-withdraw').classList.add('text-on-surface-variant');
        } else {
            document.getElementById('section-withdraw').classList.remove('hidden');
            document.getElementById('section-topup').classList.add('hidden');
            
            document.getElementById('btn-withdraw').classList.add('bg-primary', 'text-on-primary', 'shadow-sm');
            document.getElementById('btn-withdraw').classList.remove('text-on-surface-variant');
            
            document.getElementById('btn-topup').classList.remove('bg-primary', 'text-on-primary', 'shadow-sm');
            document.getElementById('btn-topup').classList.add('text-on-surface-variant');
        }
    }

    function calculateWithdraw() {
        const input = document.getElementById('withdraw-points');
        const est = document.getElementById('withdraw-estimate');
        let pts = parseInt(input.value) || 0;
        let amt = pts * 1000;
        est.innerText = 'Rp ' + amt.toLocaleString('id-ID');
    }
</script>
@endsection
