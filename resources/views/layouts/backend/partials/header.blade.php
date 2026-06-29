@php
    $currentUser = auth()->user();
    $currentUserName = $currentUser ? $currentUser->Name : 'Admin';
    $currentUserRole = ($currentUser && $currentUser->IsRole == 1) ? 'Super Admin' : 'Staff';
    $currentUserPosName = ($currentUser && $currentUser->IsPos == 1) ? 'Retail' : 'Cafe';
@endphp

<header class="header-top px-3 d-flex align-items-center justify-content-between sticky-top">

    <!-- Left side: Mobile Toggle & Sidebar Toggle -->
    <div class="d-flex align-items-center">
        <!-- Desktop Toggle -->
        <button
            class="btn border-0 d-none d-md-block me-3 text-muted bg-light rounded-circle d-flex align-items-center justify-content-center btn-sm"
            id="sidebarCollapse" style="width: 32px; height: 32px;">
            <i class="bi bi-chevron-double-left" style="font-size: 0.8rem;"></i>
        </button>

        <!-- Mobile Toggle -->
        <button class="btn btn-light d-md-none me-2 rounded-circle" id="mobileSidebarCollapse"
            style="width: 35px; height: 35px;">
            <i class="bi bi-list"></i>
        </button>

        <!-- Search Form (Pill shaped) -->
        <form class="d-none d-md-flex align-items-center bg-light rounded-pill px-3 py-1 border" style="width: 250px;">
            <i class="bi bi-search text-muted" style="font-size: 0.85rem;"></i>
            <input type="text" class="form-control border-0 bg-transparent shadow-none" placeholder="Search"
                style="font-size: 0.85rem;">
            <i class="bi bi-command text-muted bg-white rounded px-1 border" style="font-size: 0.70rem;">K</i>
        </form>
    </div>

    <!-- Right side: Actions -->
    <div class="d-flex align-items-center gap-2">

        <!-- Store Selector -->
        <button class="btn btn-light bg-white border rounded d-flex align-items-center px-3 py-1 d-none d-lg-flex">
            <i class="bi bi-shop text-success me-2"></i>
            <span class="fw-medium text-dark" style="font-size: 0.85rem;">Freshmart</span>
            <i class="bi bi-chevron-down ms-2 text-muted" style="font-size: 0.7rem;"></i>
        </button>


        <!-- POS Button -->
        <a href="{{ route('pos.index') }}" class="btn bg-dark text-white rounded d-flex align-items-center px-3 py-1 text-decoration-none" style="font-size: 0.85rem; font-weight: 600;">
            <i class="bi bi-display me-1"></i> POS
        </a>

        <!-- Action Icons -->
        <div class="d-flex align-items-center gap-1 ms-2">
            <!-- Language -->
            <button
                class="btn btn-light bg-white border rounded-circle d-flex align-items-center justify-content-center p-0"
                style="width: 35px; height: 35px;">
                <img src="https://flagcdn.com/w20/id.png" alt="ID" width="16">
            </button>

            <!-- Fullscreen Button -->
            <button
                class="btn btn-light bg-white border rounded-circle d-flex align-items-center justify-content-center p-0"
                id="fullscreenBtn" style="width: 35px; height: 35px;" title="Full Screen">
                <i class="bi bi-fullscreen text-muted" style="font-size: 0.9rem;"></i>
            </button>

            <!-- Messages (Mock) -->
            <button
                class="btn btn-light bg-white border rounded-circle d-flex align-items-center justify-content-center p-0 position-relative"
                style="width: 35px; height: 35px;" title="Pesan">
                <i class="bi bi-envelope text-muted" style="font-size: 0.9rem;"></i>
            </button>

            <!-- Notifications Dropdown -->
            <div class="dropdown">
                <button
                    class="btn btn-light bg-white border rounded-circle d-flex align-items-center justify-content-center p-0 position-relative"
                    id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                    style="width: 35px; height: 35px;" title="Notifikasi">
                    <i class="bi bi-bell text-muted" style="font-size: 0.9rem;"></i>
                    <span class="position-absolute translate-middle bg-danger border border-light rounded-circle"
                        id="notificationBadge" style="top: 8px; right: -2px; width: 8px; height: 8px; display: none;"></span>
                </button>
                <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2 p-0" aria-labelledby="notificationDropdown" style="width: 320px; border-radius: 12px; overflow: hidden; z-index: 1050;">
                    <div class="bg-light p-3 border-bottom d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-dark" style="font-size: 0.9rem;">Notifikasi</span>
                        <span class="badge bg-danger text-white" id="notificationCount">0 Baru</span>
                    </div>
                    <div class="list-group list-group-flush" id="notificationList" style="max-height: 250px; overflow-y: auto;">
                        <div class="text-center text-muted p-4 fs-7" style="font-size: 0.8rem;">Tidak ada notifikasi baru</div>
                    </div>
                    <div class="p-2 border-top text-center bg-white">
                        <a href="#" class="text-theme-orange text-decoration-none fw-semibold" style="font-size: 0.8rem;">Lihat Semua</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Profile Dropdown -->
        <div class="dropdown ms-2">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($currentUserName) }}&background=ff9f43&color=fff" alt="mdo" width="35"
                    height="35" class="rounded-circle border">
            </a>
            <ul class="dropdown-menu text-small shadow dropdown-menu-end border-0 mt-2 p-0" style="border-radius: 12px; overflow: hidden; width: 220px;">
                <div class="px-3 py-2.5 border-bottom bg-light">
                    <span class="d-block fw-bold text-dark" style="font-size: 0.85rem;">{{ $currentUserName }}</span>
                    <span class="d-block text-muted" style="font-size: 0.72rem; font-weight: 500;">{{ $currentUserRole }} ({{ $currentUserPosName }})</span>
                </div>
                <li><a class="dropdown-item py-2" href="#" style="font-size: 0.82rem;"><i class="bi bi-person me-2 text-muted"></i>Profile</a></li>
                <li><a class="dropdown-item py-2" href="#" style="font-size: 0.82rem;"><i class="bi bi-gear me-2 text-muted"></i>Settings</a></li>
                <li>
                    <hr class="dropdown-divider my-0">
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item py-2 text-danger" style="cursor: pointer; text-align: left; width: 100%; font-size: 0.82rem; font-weight: 600;">
                            <i class="bi bi-box-arrow-right me-2"></i>Sign out
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>