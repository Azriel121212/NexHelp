@extends('layouts.app')
@section('title', 'Daftar Akun')

@section('content')
<div class="flex-grow flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-surface rounded-3xl p-8 shadow-level-1 my-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-primary tracking-tight">Daftar NexHelp</h1>
            <p class="text-on-surface-variant mt-2">Mulai bantu teman dan dapatkan bantuan!</p>
        </div>

        @if($errors->any())
        <div class="bg-error-container text-on-error-container p-4 rounded-xl mb-6 text-sm">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-bold text-on-surface mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-surface-bright border-none rounded-xl px-4 py-3 text-on-surface shadow-sm focus:ring-2 focus:ring-primary placeholder-outline-variant" placeholder="Contoh: Azriel">
            </div>

            <div>
                <label class="block text-sm font-bold text-on-surface mb-1">NIM (Nomor Induk Mahasiswa)</label>
                <input type="text" name="nim" value="{{ old('nim') }}" required class="w-full bg-surface-bright border-none rounded-xl px-4 py-3 text-on-surface shadow-sm focus:ring-2 focus:ring-primary placeholder-outline-variant" placeholder="1234567890">
            </div>

            <div>
                <label class="block text-sm font-bold text-on-surface mb-1">Email Kampus</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full bg-surface-bright border-none rounded-xl px-4 py-3 text-on-surface shadow-sm focus:ring-2 focus:ring-primary placeholder-outline-variant" placeholder="azriel@kampus.edu">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-on-surface mb-1">Fakultas</label>
                    <select name="faculty" id="faculty-select" required class="w-full bg-surface-bright border-none rounded-xl px-4 py-3 text-on-surface shadow-sm focus:ring-2 focus:ring-primary cursor-pointer appearance-none">
                        <option value="" disabled selected>Pilih Fakultas...</option>
                        <option value="Fakultas Ilmu Komputer">Fasilkom (Ilmu Komputer)</option>
                        <option value="Fakultas Teknik">Fakultas Teknik</option>
                        <option value="Fakultas Ekonomi">Fakultas Ekonomi</option>
                        <option value="Fakultas Ilmu Komunikasi">Fakultas Ilmu Komunikasi</option>
                        <option value="Fakultas Desain">Fakultas Desain</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-on-surface mb-1">Program Studi</label>
                    <select name="major" id="major-select" required disabled class="w-full bg-surface-bright border-none rounded-xl px-4 py-3 text-on-surface shadow-sm focus:ring-2 focus:ring-primary cursor-pointer appearance-none disabled:opacity-50 disabled:cursor-not-allowed">
                        <option value="" disabled selected>Pilih Fakultas Dulu...</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-on-surface mb-1">Keahlian (Skills)</label>
                <input type="text" name="skills" value="{{ old('skills') }}" class="w-full bg-surface-bright border-none rounded-xl px-4 py-3 text-on-surface shadow-sm focus:ring-2 focus:ring-primary placeholder-outline-variant" placeholder="Contoh: PHP, Desain Grafis, Video Editing">
                <p class="text-xs text-on-surface-variant mt-1">Pisahkan dengan koma. Ini ngebantu orang nemuin jasa lu!</p>
            </div>

            <div>
                <label class="block text-sm font-bold text-on-surface mb-1">Bio Singkat</label>
                <textarea name="bio" class="w-full bg-surface-bright border-none rounded-xl px-4 py-3 text-on-surface shadow-sm focus:ring-2 focus:ring-primary placeholder-outline-variant resize-none" rows="2" placeholder="Ceritain dikit tentang lu... (Opsional)">{{ old('bio') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-on-surface mb-1">Password</label>
                <input type="password" name="password" required class="w-full bg-surface-bright border-none rounded-xl px-4 py-3 text-on-surface shadow-sm focus:ring-2 focus:ring-primary placeholder-outline-variant" placeholder="Minimal 8 karakter">
            </div>

            <button type="submit" class="w-full bg-primary text-on-primary font-bold rounded-xl py-3 mt-4 active:scale-95 transition-transform">
                Buat Akun
            </button>
        </form>

        <p class="text-center text-sm text-on-surface-variant mt-8">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-primary font-bold hover:underline">Masuk di sini</a>
        </p>
    </div>
</div>

<script>
    // Data relasi Fakultas -> Program Studi
    const facultyMajors = {
        "Fakultas Ilmu Komputer": ["Teknik Informatika", "Sistem Informasi", "Data Science"],
        "Fakultas Teknik": ["Teknik Sipil", "Teknik Mesin", "Teknik Elektro", "Arsitektur"],
        "Fakultas Ekonomi": ["Manajemen", "Akuntansi", "Bisnis Digital"],
        "Fakultas Ilmu Komunikasi": ["Ilmu Komunikasi", "Jurnalistik", "Hubungan Masyarakat"],
        "Fakultas Desain": ["Desain Komunikasi Visual (DKV)", "Desain Interior", "Desain Produk"]
    };

    const facultySelect = document.getElementById('faculty-select');
    const majorSelect = document.getElementById('major-select');

    facultySelect.addEventListener('change', function() {
        const selectedFaculty = this.value;
        const majors = facultyMajors[selectedFaculty] || [];
        
        // Bersihkan opsi Prodi sebelumnya
        majorSelect.innerHTML = '<option value="" disabled selected>Pilih Prodi...</option>';
        
        if(majors.length > 0) {
            majorSelect.disabled = false; // Aktifkan dropdown Prodi
            // Tambahkan opsi baru
            majors.forEach(function(major) {
                const option = document.createElement('option');
                option.value = major;
                option.textContent = major;
                majorSelect.appendChild(option);
            });
        } else {
            majorSelect.disabled = true;
            majorSelect.innerHTML = '<option value="" disabled selected>Pilih Fakultas Dulu...</option>';
        }
    });
</script>
@endsection
