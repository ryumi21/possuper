<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts: Inter for the clean look similar to the image -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* Custom CSS to closely match the provided UI */
        :root {
            --bs-body-font-family: 'Inter', sans-serif;
            --bs-body-bg: #ffffff;
            --bs-border-color: #f1f1f1; 
            
            /* Theme Colors */
            --theme-orange: #ff9f43;
            --theme-orange-bg: #fff5ec;
            --theme-dark: #1f2937;
            --theme-teal: #10b981;
            --theme-blue: #3b82f6;
            
            --sidebar-width: 260px;
            --header-height: 70px;
        }

        body {
            font-family: var(--bs-body-font-family);
            background-color: var(--bs-body-bg);
            color: #4b5563;
            font-size: 0.875rem; /* Base font size a bit smaller */
        }
        
        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        #sidebar {
            min-width: var(--sidebar-width);
            max-width: var(--sidebar-width);
            background: #ffffff;
            border-right: 1px solid var(--bs-border-color);
            transition: all 0.3s ease-in-out;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1040;
        }
        
        /* Custom Scrollbar for sidebar */
        #sidebar::-webkit-scrollbar {
            width: 5px;
        }
        #sidebar::-webkit-scrollbar-track {
            background: transparent;
        }
        #sidebar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        #sidebar.collapsed {
            margin-left: calc(-1 * var(--sidebar-width));
        }

        #content {
            width: 100%;
            min-height: 100vh;
            transition: all 0.3s ease-in-out;
            display: flex;
            flex-direction: column;
            background-color: #ffffff;
        }

        /* General Card Customization */
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02), 0 2px 4px -1px rgba(0,0,0,0.02);
        }

        /* Header top */
        .header-top {
            background: #ffffff;
            height: var(--header-height);
            border-bottom: 1px solid var(--bs-border-color);
        }

        .btn-theme-orange {
            background-color: var(--theme-orange);
            color: #fff;
            border: none;
            font-weight: 600;
        }
        .btn-theme-orange:hover {
            background-color: #e88c38;
            color: #fff;
        }

        @media (max-width: 768px) {
            #sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
                position: fixed;
            }
            #sidebar.active {
                margin-left: 0;
            }
            #sidebarOverlay {
                display: none;
                position: fixed;
                width: 100vw;
                height: 100vh;
                background: rgba(0,0,0,0.3);
                z-index: 1030;
                top: 0;
                left: 0;
            }
            #sidebarOverlay.active {
                display: block;
            }
        }
    </style>
</head>
<body>

    <div class="wrapper">
        <div id="sidebarOverlay"></div>

        <!-- Leftbar (Sidebar) -->
        @include('layouts.backend.partials.sidebar')

        <!-- Main Content -->
        <div id="content">
            
            <!-- Top Header -->
            @include('layouts.backend.partials.header')

            <!-- Main Content Area -->
            <main class="flex-grow-1 p-4">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const sidebarBtn = document.getElementById('sidebarCollapse');
            const mobileBtn = document.getElementById('mobileSidebarCollapse');
            const overlay = document.getElementById('sidebarOverlay');

            if (sidebarBtn) {
                sidebarBtn.addEventListener('click', function () {
                    sidebar.classList.toggle('collapsed');
                });
            }

            if (mobileBtn) {
                mobileBtn.addEventListener('click', function () {
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('active');
                });
            }

            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                });
            }

            // Fetch Global Config API
            fetch('/api/config')
                .then(response => response.json())
                .then(data => {
                    if(data.status === 'success') {
                        window.AppConfig = data.data;
                        console.log('AppConfig loaded:', window.AppConfig);
                    }
                })
                .catch(err => console.error('Error loading config:', err));
        });
    </script>
    @stack('scripts')
</body>
</html>
