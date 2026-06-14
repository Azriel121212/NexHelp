@extends('layouts.app')
@section('title', 'New Request')

@section('content')
<header class="flex justify-between items-center w-full px-4 h-16 z-50 bg-surface sticky top-0">
    <a href="{{ route('home') }}" class="flex items-center text-on-surface-variant p-2 -ml-2 rounded-full">
        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">close</span>
    </a>
    <h1 class="text-xl font-bold text-primary">New Request</h1>
    <button type="submit" form="createTaskForm" class="text-sm text-primary font-bold p-2 -mr-2 bg-primary-fixed rounded-full px-4 active:scale-95">Post</button>
</header>

<main class="flex-grow px-4 py-4 flex flex-col gap-6 max-w-2xl mx-auto w-full pb-32">
    @if($errors->any())
    <div class="bg-error-container text-on-error-container p-4 rounded-xl text-sm mb-4">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form id="createTaskForm" action="{{ route('task.store') }}" method="POST" class="flex flex-col gap-6">
        @csrf
        <section class="flex flex-col gap-2">
            <input type="text" name="title" value="{{ old('title') }}" class="w-full bg-surface-bright border-none rounded-DEFAULT px-4 py-4 text-xl font-bold text-on-background placeholder:text-outline-variant shadow-level-1 focus:ring-2 focus:ring-primary" placeholder="Butuh jasa apa nih?" required/>
            <textarea name="description" class="w-full bg-surface-bright border-none rounded-DEFAULT px-4 py-3 text-base text-on-background placeholder:text-outline-variant shadow-level-1 resize-none focus:ring-2 focus:ring-primary" placeholder="Jelasin detailnya di sini..." rows="3" required>{{ old('description') }}</textarea>
            <input type="text" name="location" value="{{ old('location') }}" class="w-full bg-surface-bright border-none rounded-DEFAULT px-4 py-3 text-base text-on-background placeholder:text-outline-variant shadow-level-1 focus:ring-2 focus:ring-primary mt-2" placeholder="Lokasi (Contoh: Gedung A / Kantin)" required/>
        </section>

        <section class="bg-surface-bright rounded-DEFAULT p-4 shadow-level-1 flex flex-col gap-2">
            <h2 class="text-sm font-bold text-on-surface-variant">Kategori Jasa</h2>
            <select name="category" class="w-full bg-surface border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary cursor-pointer" required>
                <option value="Tutor / Teman Belajar" {{ old('category') == 'Tutor / Teman Belajar' ? 'selected' : '' }}>Tutor / Teman Belajar</option>
                <option value="Diskusi Koding / Proyek" {{ old('category') == 'Diskusi Koding / Proyek' ? 'selected' : '' }}>Diskusi Koding / Proyek</option>
                <option value="Review Jurnal / Proofreading" {{ old('category') == 'Review Jurnal / Proofreading' ? 'selected' : '' }}>Review Jurnal / Proofreading</option>
                <option value="Responden Kuesioner / Wawancara" {{ old('category') == 'Responden Kuesioner / Wawancara' ? 'selected' : '' }}>Responden Kuesioner / Wawancara</option>
                <option value="Bantuan Event / Kepanitiaan" {{ old('category') == 'Bantuan Event / Kepanitiaan' ? 'selected' : '' }}>Bantuan Event / Kepanitiaan</option>
                <option value="Pinjam Buku / Referensi" {{ old('category') == 'Pinjam Buku / Referensi' ? 'selected' : '' }}>Pinjam Buku / Alat Praktikum</option>
                <option value="Keperluan Kampus Lainnya" {{ old('category') == 'Keperluan Kampus Lainnya' ? 'selected' : '' }}>Keperluan Kampus Lainnya</option>
            </select>
        </section>

        <section class="bg-surface-bright rounded-DEFAULT p-4 shadow-level-1 flex flex-col gap-4">
            <h2 class="text-sm font-bold text-on-surface-variant">Jadwal Jasa</h2>
            
            <div>
                <label class="block text-xs text-on-surface-variant mb-1">Tanggal</label>
                <input type="date" name="schedule_date" value="{{ old('schedule_date') }}" class="w-full bg-surface border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary" required>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-on-surface-variant mb-1">Dari Jam</label>
                    <input type="time" name="start_time" value="{{ old('start_time') }}" class="w-full bg-surface border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary" required>
                </div>
                <div>
                    <label class="block text-xs text-on-surface-variant mb-1">Sampai Jam</label>
                    <input type="time" name="end_time" value="{{ old('end_time') }}" class="w-full bg-surface border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary" required>
                </div>
            </div>
        </section>

        <section class="bg-surface-bright rounded-DEFAULT p-4 shadow-level-1 flex flex-col gap-2">
            <h2 class="text-sm font-bold text-on-surface-variant">Reward Points (Harga Jasa)</h2>
            <div class="flex items-center gap-3">
                <div class="flex-grow relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-primary text-xl font-bold">P</span>
                    <input name="reward_points" class="w-full bg-surface border-none rounded-xl pl-10 pr-4 py-3 text-xl font-bold text-on-background text-right focus:ring-2 focus:ring-primary" type="number" value="{{ old('reward_points', 50) }}" min="1" required/>
                </div>
            </div>
            <p class="text-xs text-on-surface-variant mt-1 text-right">Poin Anda otomatis terpotong saat membuat request ini.</p>
        </section>
    </form>
</main>

<script>
    const startTimeInput = document.querySelector('input[name="start_time"]');
    const endTimeInput = document.querySelector('input[name="end_time"]');
    const dateInput = document.querySelector('input[name="schedule_date"]');
    
    // Set minimal tanggal hari ini
    const today = new Date();
    // Sesuaikan zona waktu lokal (kalau pakai toISOString bisa beda hari gara2 UTC)
    const offset = today.getTimezoneOffset();
    const localToday = new Date(today.getTime() - (offset*60*1000));
    const todayStr = localToday.toISOString().split('T')[0];
    
    dateInput.setAttribute('min', todayStr);

    function validateStartTime() {
        if (dateInput.value === todayStr && startTimeInput.value) {
            const now = new Date();
            const currentHour = now.getHours().toString().padStart(2, '0');
            const currentMin = now.getMinutes().toString().padStart(2, '0');
            const currentTime = `${currentHour}:${currentMin}`;
            
            if (startTimeInput.value < currentTime) {
                alert("Masa mau pakai mesin waktu der? Jam mulainya nggak boleh lewat dari jam sekarang dong!");
                startTimeInput.value = currentTime;
            }
        }
    }

    dateInput.addEventListener('change', validateStartTime);

    // Otomatis set End Time kalau Start Time berubah
    startTimeInput.addEventListener('change', function() {
        validateStartTime();
        
        if (this.value && (!endTimeInput.value || endTimeInput.value <= this.value)) {
            let [hours, minutes] = this.value.split(':');
            let nextHour = parseInt(hours) + 1;
            if (nextHour >= 24) nextHour = 23; 
            endTimeInput.value = `${nextHour.toString().padStart(2, '0')}:${minutes}`;
        }
    });

    // Cegah End Time lebih kecil dari Start Time
    endTimeInput.addEventListener('change', function() {
        if (startTimeInput.value && this.value < startTimeInput.value) {
            alert("Waktu selesai tidak boleh lebih awal dari waktu mulai. Mohon perbaiki waktu selesai!");
            
            // Revert ke jam mulai + 1 jam
            let [hours, minutes] = startTimeInput.value.split(':');
            let nextHour = parseInt(hours) + 1;
            if (nextHour >= 24) nextHour = 23; 
            this.value = `${nextHour.toString().padStart(2, '0')}:${minutes}`;
        }
    });
</script>
@endsection
