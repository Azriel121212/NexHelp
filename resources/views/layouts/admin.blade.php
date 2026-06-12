<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>KawanKampus Admin - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#0040df",
                        "on-primary": "#ffffff",
                        "primary-container": "#2d5bff",
                        "on-primary-container": "#efefff",
                        "primary-fixed": "#dde1ff",
                        "on-primary-fixed": "#001355",
                        "primary-fixed-dim": "#b8c3ff",
                        "secondary-container": "#fd8b00",
                        "secondary-fixed": "#ffdcc3",
                        "on-secondary-fixed": "#2f1500",
                        "secondary-fixed-dim": "#ffb77d",
                        "error": "#ba1a1a",
                        "error-container": "#ffdad6",
                        "on-error-container": "#93000a",
                        "surface": "#f8f9fa",
                        "surface-bright": "#f8f9fa",
                        "surface-container": "#edeeef",
                        "surface-container-high": "#e7e8e9",
                        "surface-container-low": "#f3f4f5",
                        "surface-container-lowest": "#ffffff",
                        "on-surface": "#191c1d",
                        "on-surface-variant": "#434656",
                        "outline": "#747688",
                        "outline-variant": "#c4c5d9",
                        "background": "#f8f9fa",
                        "on-background": "#191c1d",
                        "tertiary-container": "#007e31",
                    },
                    borderRadius: { "DEFAULT": "1rem", "lg": "2rem", "xl": "3rem", "full": "9999px" },
                    fontFamily: {
                        "label-sm": ["Plus Jakarta Sans"], "body-sm": ["Plus Jakarta Sans"], "label-md": ["Plus Jakarta Sans"], "headline-sm": ["Plus Jakarta Sans"], "headline-md": ["Plus Jakarta Sans"], "body-md": ["Plus Jakarta Sans"]
                    }
                }
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #f3f4f5; -webkit-tap-highlight-color: transparent; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-surface-container-low text-on-surface font-body-md min-h-screen flex" x-data="{ sidebarOpen: false }">

    <!-- Sidebar Overlay for Mobile -->
    <div x-show="sidebarOpen" x-transition.opacity 
         class="fixed inset-0 bg-black/50 z-40 lg:hidden"
         @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
           class="fixed inset-y-0 left-0 z-50 w-64 bg-[#191c1d] text-white transition-transform duration-300 lg:translate-x-0 lg:static lg:block flex flex-col shadow-2xl">
        
        <!-- Logo Area -->
        <div class="h-16 flex items-center px-6 border-b border-white/10 shrink-0">
            <span class="font-headline-md text-xl font-extrabold tracking-tight text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-primary-fixed-dim">admin_panel_settings</span>
                KawanAdmin
            </span>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            <p class="px-3 text-xs font-bold text-white/40 uppercase tracking-wider mb-2 mt-4">Dashboard</p>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-primary/20 text-primary-fixed-dim font-bold transition-colors">
                <span class="material-symbols-outlined text-[20px]">dashboard</span>
                Overview
            </a>

            <p class="px-3 text-xs font-bold text-white/40 uppercase tracking-wider mb-2 mt-6">Manajemen</p>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 hover:bg-white/5 hover:text-white transition-colors">
                <span class="material-symbols-outlined text-[20px]">people</span>
                Users
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 hover:bg-white/5 hover:text-white transition-colors">
                <span class="material-symbols-outlined text-[20px]">task</span>
                Semua Tugas
            </a>

            <p class="px-3 text-xs font-bold text-white/40 uppercase tracking-wider mb-2 mt-6">Utilitas</p>
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 hover:bg-white/5 hover:text-white transition-colors">
                <span class="material-symbols-outlined text-[20px]">open_in_new</span>
                Buka Aplikasi Web
            </a>
        </nav>
    </aside>

    <!-- Main Content Shell -->
    <div class="flex-1 flex flex-col min-h-screen overflow-hidden">
        
        <!-- Top Navbar -->
        <header class="h-16 bg-white shrink-0 flex items-center justify-between px-4 lg:px-8 shadow-sm z-30">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="p-2 -ml-2 text-on-surface-variant hover:bg-surface-container rounded-full lg:hidden">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                
                <!-- Search (Visual Only for now) -->
                <div class="hidden sm:flex items-center bg-surface-container-low px-4 py-2 rounded-full border border-surface-bright focus-within:ring-2 focus-within:ring-primary/20 transition-all w-64 lg:w-96">
                    <span class="material-symbols-outlined text-outline text-[18px] mr-2">search</span>
                    <input type="text" placeholder="Cari di admin panel..." class="bg-transparent border-none p-0 text-sm w-full focus:ring-0 text-on-surface placeholder-outline-variant">
                </div>
            </div>

            <!-- Profile Info -->
            <div class="flex items-center gap-4">
                <div class="hidden sm:block text-right">
                    <p class="text-sm font-bold text-on-surface">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-on-surface-variant">Administrator</p>
                </div>
                <img src="{{ Auth::user()->avatar_url }}" alt="Admin Avatar" class="w-9 h-9 rounded-full object-cover border-2 border-primary-fixed">
            </div>
        </header>

        <!-- Dynamic Content Area -->
        <main class="flex-1 overflow-y-auto bg-surface-container-low relative">
            
            <!-- Aesthetic Colored Header Background -->
            <div class="absolute top-0 left-0 w-full h-64 bg-primary z-0">
                <!-- Subtle pattern or gradient inside -->
                <div class="absolute inset-0 bg-gradient-to-b from-primary to-primary-container opacity-90"></div>
                <!-- Title in header -->
                <div class="relative z-10 px-4 lg:px-8 pt-8 text-white">
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">@yield('page_title', 'Projects Overview')</h1>
                    <p class="text-primary-fixed-dim text-sm mt-1">@yield('page_description', 'Pantau aktivitas aplikasi secara real-time')</p>
                </div>
            </div>

            <!-- Yield Content (Will be pulled up via negative margin) -->
            <div class="relative z-10 px-4 lg:px-8 pb-12 pt-8">
                @yield('content')
            </div>

        </main>
    </div>

    @if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: '{{ session('success') }}', showConfirmButton: false, timer: 3000, timerProgressBar: true });
        });
    </script>
    @endif
    @if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({ icon: 'error', title: 'Oops...', text: '{{ session('error') }}', confirmButtonColor: '#0040df' });
        });
    </script>
    @endif

</body>
</html>
