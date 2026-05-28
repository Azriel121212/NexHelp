<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>NexHelp - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Config Tailwind bawaan UI lu -->
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#0040df",
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
        body { background-color: #f8f9fa; -webkit-tap-highlight-color: transparent; min-height: max(884px, 100dvh); }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .shadow-level-1 { box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.04); }
        
        /* Loading Animation */
        #page-loader {
            position: fixed;
            inset: 0;
            background: #f8f9fa;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #dde1ff;
            border-top: 5px solid #0040df;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .loader-hidden {
            opacity: 0;
            visibility: hidden;
        }
    </style>
</head>
<body class="bg-background text-on-background font-body-md min-h-screen flex flex-col pb-[80px]">

    <!-- Loading Screen -->
    <div id="page-loader">
        <div class="spinner"></div>
        <p class="mt-4 text-primary font-bold animate-pulse">Menyiapkan NexHelp...</p>
    </div>

    @yield('content')

    <script>
        // Remove loader on page load
        window.addEventListener('load', function() {
            setTimeout(() => {
                document.getElementById('page-loader').classList.add('loader-hidden');
            }, 300); // Small delay for visual effect
        });
    </script>

    @if (session('welcome_message'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Welcome Back!',
                text: '{{ session('welcome_message') }}',
                icon: 'success',
                confirmButtonColor: '#0040df',
                confirmButtonText: 'Lanjut'
            });
        });
    </script>
    @endif
    
    @if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        });
    </script>
    @endif
    
    @if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
                confirmButtonColor: '#0040df'
            });
        });
    </script>
    @endif

    <!-- Global form double submit prevention -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    // Check if form is valid before disabling
                    if (form.checkValidity && !form.checkValidity()) {
                        return;
                    }
                    
                    const submitBtns = form.querySelectorAll('button[type="submit"]');
                    submitBtns.forEach(btn => {
                        // Don't disable if button has a 'no-disable' class
                        if (!btn.classList.contains('no-disable')) {
                            // Let the form submit, but disable the button shortly after
                            setTimeout(() => {
                                btn.disabled = true;
                                btn.classList.add('opacity-50', 'cursor-not-allowed');
                                // Optionally change text or icon
                                if(btn.innerText.trim() !== '' && !btn.querySelector('.material-symbols-outlined')) {
                                    btn.dataset.originalText = btn.innerText;
                                    btn.innerText = 'Memproses...';
                                }
                            }, 10);
                        }
                    });
                });
            });
        });
    </script>

</body>
</html>