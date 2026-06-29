<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        /* Custom Alert / Toast styling */
        #toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }

        .custom-toast {
            pointer-events: auto;
            display: flex;
            align-items: center;
            padding: 14px 20px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
            min-width: 320px;
            max-width: 450px;
            transform: translateX(120%);
            transition: all 0.35s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            opacity: 0;
        }

        .custom-toast.show {
            transform: translateX(0);
            opacity: 1;
        }

        .custom-toast .toast-icon {
            font-size: 1.25rem;
            margin-right: 12px;
            display: flex;
            align-items: center;
        }

        .custom-toast .toast-message {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.85rem;
            flex-grow: 1;
            line-height: 1.4;
        }

        .custom-toast .toast-close {
            background: transparent;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            font-size: 1.25rem;
            margin-left: 12px;
            padding: 0;
            line-height: 1;
            transition: color 0.15s ease;
        }

        .custom-toast .toast-close:hover {
            color: #475569;
        }

        /* Toast types */
        .custom-toast.toast-success {
            border-left: 4px solid #10b981;
        }
        .custom-toast.toast-success .toast-icon {
            color: #10b981;
        }

        .custom-toast.toast-danger {
            border-left: 4px solid #ef4444;
        }
        .custom-toast.toast-danger .toast-icon {
            color: #ef4444;
        }

        .custom-toast.toast-warning {
            border-left: 4px solid #f59e0b;
        }
        .custom-toast.toast-warning .toast-icon {
            color: #f59e0b;
        }

        .custom-toast.toast-info {
            border-left: 4px solid #3b82f6;
        }
        .custom-toast.toast-info .toast-icon {
            color: #3b82f6;
        }

        /* Custom Confirm Dialog */
        #custom-confirm-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(4px);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }

        #custom-confirm-overlay.show {
            opacity: 1;
            pointer-events: auto;
        }

        .custom-confirm-box {
            background: #ffffff;
            border-radius: 16px;
            padding: 24px;
            width: 400px;
            max-width: 90%;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            transform: scale(0.9);
            transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
            border: 1px solid #e2e8f0;
        }

        #custom-confirm-overlay.show .custom-confirm-box {
            transform: scale(1);
        }

        .custom-confirm-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .custom-confirm-message {
            font-size: 0.875rem;
            color: #475569;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .custom-confirm-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .custom-confirm-btn {
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .custom-confirm-btn-cancel {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: #475569;
        }

        .custom-confirm-btn-cancel:hover {
            background: #e2e8f0;
            color: #334155;
        }

        .custom-confirm-btn-ok {
            background: var(--theme-orange, #ff9f43);
            border: none;
            color: #ffffff;
        }

        .custom-confirm-btn-ok:hover {
            background: #e08b35;
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
    <script>
        // Global Custom Toast function
        window.showToast = function(message, type = 'info') {
            let container = document.getElementById('toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'toast-container';
                document.body.appendChild(container);
            }

            const toast = document.createElement('div');
            toast.className = `custom-toast toast-${type}`;

            let iconClass = 'bi-info-circle-fill';
            if (type === 'success') iconClass = 'bi-check-circle-fill';
            else if (type === 'danger') iconClass = 'bi-x-circle-fill';
            else if (type === 'warning') iconClass = 'bi-exclamation-triangle-fill';

            toast.innerHTML = `
                <span class="toast-icon"><i class="bi ${iconClass}"></i></span>
                <span class="toast-message">${message}</span>
                <button class="toast-close" onclick="this.parentElement.remove()">&times;</button>
            `;

            container.appendChild(toast);

            // Trigger transition
            setTimeout(() => toast.classList.add('show'), 10);

            // Auto-remove after 4 seconds
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 350);
            }, 4000);
        };

        // Global Custom Confirm Modal
        let confirmCallback = null;
        let cancelCallback = null;

        window.showConfirm = function(message, onConfirm, onCancel = null) {
            let overlay = document.getElementById('custom-confirm-overlay');
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.id = 'custom-confirm-overlay';
                overlay.innerHTML = `
                    <div class="custom-confirm-box">
                        <div class="custom-confirm-title">
                            <i class="bi bi-question-circle-fill text-warning"></i>
                            Konfirmasi
                        </div>
                        <div class="custom-confirm-message" id="custom-confirm-msg"></div>
                        <div class="custom-confirm-buttons">
                            <button class="custom-confirm-btn custom-confirm-btn-cancel" id="custom-confirm-btn-no">Batal</button>
                            <button class="custom-confirm-btn custom-confirm-btn-ok" id="custom-confirm-btn-yes">Ya</button>
                        </div>
                    </div>
                `;
                document.body.appendChild(overlay);

                document.getElementById('custom-confirm-btn-yes').addEventListener('click', () => {
                    overlay.classList.remove('show');
                    if (confirmCallback) confirmCallback();
                });

                document.getElementById('custom-confirm-btn-no').addEventListener('click', () => {
                    overlay.classList.remove('show');
                    if (cancelCallback) cancelCallback();
                });
            }

            document.getElementById('custom-confirm-msg').innerText = message;
            confirmCallback = onConfirm;
            cancelCallback = onCancel;

            overlay.classList.add('show');
        };

        // Redefine window.alert to be our custom beautiful dialog
        window.alert = function(message) {
            window.showToast(message, 'warning');
        };

        // Fullscreen Toggle Logic
        document.addEventListener('DOMContentLoaded', () => {
            const fsBtn = document.getElementById('fullscreenBtn');
            if (fsBtn) {
                fsBtn.addEventListener('click', function() {
                    if (!document.fullscreenElement) {
                        document.documentElement.requestFullscreen().then(() => {
                            fsBtn.querySelector('i').className = 'bi bi-fullscreen-exit text-muted';
                        }).catch(err => {
                            console.error(`Error attempting to enable fullscreen: ${err.message}`);
                        });
                    } else {
                        document.exitFullscreen().then(() => {
                            fsBtn.querySelector('i').className = 'bi bi-fullscreen text-muted';
                        });
                    }
                });

                document.addEventListener('fullscreenchange', () => {
                    if (document.fullscreenElement) {
                        fsBtn.querySelector('i').className = 'bi bi-fullscreen-exit text-muted';
                    } else {
                        fsBtn.querySelector('i').className = 'bi bi-fullscreen text-muted';
                    }
                });
            }

            // Populate Dynamic Notifications
            fetch('/api/config')
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'success' && res.data && res.data.user) {
                        const user = res.data.user;
                        const notifList = document.getElementById('notificationList');
                        const notifBadge = document.getElementById('notificationBadge');
                        const notifCount = document.getElementById('notificationCount');

                        if (notifList && user) {
                            let notifications = [];
                            if (user.IsPos == 1) {
                                // POS Retail Mock Notifications
                                notifications = [
                                    { text: "Stok Sabun Mandi menipis (< 5 pcs)", time: "5 menit yang lalu", type: "warning" },
                                    { text: "Transaksi baru TX-920481 berhasil", time: "1 jam yang lalu", type: "success" }
                                ];
                            } else {
                                // POS Cafe Mock Notifications
                                notifications = [
                                    { text: "Pesanan Baru Meja 4 masuk antrean", time: "2 menit yang lalu", type: "info" },
                                    { text: "Stok Susu UHT menipis (< 2 box)", time: "30 menit yang lalu", type: "warning" }
                                ];
                            }

                            if (notifications.length > 0) {
                                notifList.innerHTML = '';
                                notifications.forEach(n => {
                                    let typeClass = 'text-info';
                                    let bgClass = 'bg-info';
                                    if (n.type === 'warning') {
                                        typeClass = 'text-warning';
                                        bgClass = 'bg-warning';
                                    } else if (n.type === 'success') {
                                        typeClass = 'text-success';
                                        bgClass = 'bg-success';
                                    }

                                    notifList.innerHTML += `
                                        <a href="#" class="list-group-item list-group-item-action p-3 border-bottom d-flex align-items-start" style="font-size: 0.8rem; border-color: #f1f5f9 !important;">
                                            <span class="rounded-circle p-1 me-2 d-inline-flex align-items-center justify-content-center ${bgClass} text-white" style="width: 20px; height: 20px; font-size: 0.65rem;">
                                                <i class="bi ${n.type === 'success' ? 'bi-check' : (n.type === 'warning' ? 'bi-exclamation' : 'bi-info')}"></i>
                                            </span>
                                            <div class="flex-grow-1">
                                                <p class="mb-0 text-dark fw-medium" style="line-height: 1.35;">${n.text}</p>
                                                <small class="text-muted" style="font-size: 0.7rem;">${n.time}</small>
                                            </div>
                                        </a>
                                    `;
                                });

                                if (notifBadge) notifBadge.style.display = 'block';
                                if (notifCount) notifCount.textContent = `${notifications.length} Baru`;
                            }
                        }
                    }
                })
                .catch(err => console.error("Error populating notifications:", err));
        });
    </script>
    @stack('scripts')
</body>
</html>
