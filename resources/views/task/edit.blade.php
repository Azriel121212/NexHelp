@extends('layouts.app')
@section('title', 'Edit Request')

@section('content')
<header class="flex justify-between items-center w-full px-4 h-16 z-50 bg-surface sticky top-0">
    <a href="{{ route('task.show', $task->id) }}" class="flex items-center text-on-surface-variant p-2 -ml-2 rounded-full">
        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">close</span>
    </a>
    <h1 class="text-xl font-bold text-primary">Edit Request</h1>
    <button type="submit" form="editTaskForm" class="text-sm text-primary font-bold p-2 -mr-2 bg-primary-fixed rounded-full px-4 active:scale-95">Update</button>
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

    <form id="editTaskForm" action="{{ route('task.update', $task->id) }}" method="POST" class="flex flex-col gap-6">
        @csrf
        @method('PUT')
        <section class="flex flex-col gap-2">
            <input type="text" name="title" value="{{ old('title', $task->title) }}" class="w-full bg-surface-bright border-none rounded-DEFAULT px-4 py-4 text-xl font-bold text-on-background placeholder:text-outline-variant shadow-level-1 focus:ring-2 focus:ring-primary" placeholder="Butuh jasa apa nih?" required/>
            <textarea name="description" class="w-full bg-surface-bright border-none rounded-DEFAULT px-4 py-3 text-base text-on-background placeholder:text-outline-variant shadow-level-1 resize-none focus:ring-2 focus:ring-primary" placeholder="Jelasin detailnya di sini..." rows="3" required>{{ old('description', $task->description) }}</textarea>
            <input type="text" name="location" value="{{ old('location', $task->location) }}" class="w-full bg-surface-bright border-none rounded-DEFAULT px-4 py-3 text-base text-on-background placeholder:text-outline-variant shadow-level-1 focus:ring-2 focus:ring-primary mt-2" placeholder="Lokasi (Contoh: Gedung A / Kantin)" required/>
        </section>

        <section class="bg-surface-bright rounded-DEFAULT p-4 shadow-level-1 flex flex-col gap-2">
            <h2 class="text-sm font-bold text-on-surface-variant">Kategori Jasa</h2>
            <select name="category" class="w-full bg-surface border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary cursor-pointer" required>
                <option value="Tutor / Teman Belajar" {{ old('category', $task->category) == 'Tutor / Teman Belajar' ? 'selected' : '' }}>Tutor / Teman Belajar</option>
                <option value="Diskusi Koding / Proyek" {{ old('category', $task->category) == 'Diskusi Koding / Proyek' ? 'selected' : '' }}>Diskusi Koding / Proyek</option>
                <option value="Review Jurnal / Proofreading" {{ old('category', $task->category) == 'Review Jurnal / Proofreading' ? 'selected' : '' }}>Review Jurnal / Proofreading</option>
                <option value="Bantuan Penelitian / Kuesioner" {{ old('category', $task->category) == 'Bantuan Penelitian / Kuesioner' ? 'selected' : '' }}>Bantuan Penelitian / Kuesioner</option>
                <option value="Bantuan Event / Kepanitiaan" {{ old('category', $task->category) == 'Bantuan Event / Kepanitiaan' ? 'selected' : '' }}>Bantuan Event / Kepanitiaan</option>
                <option value="Pinjam Buku / Referensi" {{ old('category', $task->category) == 'Pinjam Buku / Referensi' ? 'selected' : '' }}>Pinjam Buku / Alat Praktikum</option>
                <option value="Keperluan Kampus Lainnya" {{ old('category', $task->category) == 'Keperluan Kampus Lainnya' ? 'selected' : '' }}>Keperluan Kampus Lainnya</option>
            </select>
        </section>

        <section class="bg-surface-bright rounded-DEFAULT p-4 shadow-level-1 flex flex-col gap-4">
            <h2 class="text-sm font-bold text-on-surface-variant">Jadwal Jasa</h2>
            
            <div>
                <label class="block text-xs text-on-surface-variant mb-1">Tanggal</label>
                <input type="date" name="schedule_date" value="{{ old('schedule_date', \Carbon\Carbon::parse($task->schedule_date)->format('Y-m-d')) }}" class="w-full bg-surface border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary" required>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-on-surface-variant mb-1">Dari Jam</label>
                    <input type="time" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($task->start_time)->format('H:i')) }}" class="w-full bg-surface border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary" required>
                </div>
                <div>
                    <label class="block text-xs text-on-surface-variant mb-1">Sampai Jam</label>
                    <input type="time" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($task->end_time)->format('H:i')) }}" class="w-full bg-surface border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-primary" required>
                </div>
            </div>
        </section>

        <section class="bg-surface-bright rounded-DEFAULT p-4 shadow-level-1 flex flex-col gap-2">
            <h2 class="text-sm font-bold text-on-surface-variant">Reward Points (Harga Jasa)</h2>
            <div class="flex items-center gap-3">
                <div class="flex-grow relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-primary text-xl font-bold">P</span>
                    <input name="reward_points" class="w-full bg-surface border-none rounded-xl pl-10 pr-4 py-3 text-xl font-bold text-on-background text-right focus:ring-2 focus:ring-primary" type="number" value="{{ old('reward_points', $task->reward_points) }}" min="1" required/>
                </div>
            </div>
            <p class="text-xs text-on-surface-variant mt-1 text-right">Perubahan poin akan menyesuaikan saldo Anda saat ini.</p>
        </section>
    </form>
</main>

<script>
    const startTimeInput = document.querySelector('input[name="start_time"]');
    const endTimeInput = document.querySelector('input[name="end_time"]');
    const dateInput = document.querySelector('input[name="schedule_date"]');
    
    // Set minimal tanggal hari ini
    const today = new Date();
    // Sesuaikan zona waktu lokal
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
                alert("Jam mulainya nggak boleh lewat dari jam sekarang dong!");
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
