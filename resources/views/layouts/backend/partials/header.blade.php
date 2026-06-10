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
        <button class="btn bg-dark text-white rounded d-flex align-items-center px-3 py-1">
            <i class="bi bi-display me-1"></i> POS
        </button>

        <!-- Action Icons -->
        <div class="d-flex align-items-center gap-1 ms-2">
            <!-- Language -->
            <button
                class="btn btn-light bg-white border rounded-circle d-flex align-items-center justify-content-center p-0"
                style="width: 35px; height: 35px;">
                <img src="https://flagcdn.com/w20/us.png" alt="US" width="16">
            </button>

            <button
                class="btn btn-light bg-white border rounded-circle d-flex align-items-center justify-content-center p-0"
                style="width: 35px; height: 35px;">
                <i class="bi bi-fullscreen text-muted" style="font-size: 0.9rem;"></i>
            </button>

            <button
                class="btn btn-light bg-white border rounded-circle d-flex align-items-center justify-content-center p-0 position-relative"
                style="width: 35px; height: 35px;">
                <i class="bi bi-envelope text-muted" style="font-size: 0.9rem;"></i>
            </button>

            <!-- Notifications -->
            <button
                class="btn btn-light bg-white border rounded-circle d-flex align-items-center justify-content-center p-0 position-relative"
                style="width: 35px; height: 35px;">
                <i class="bi bi-bell text-muted" style="font-size: 0.9rem;"></i>
                <span class="position-absolute translate-middle bg-danger border border-light rounded-circle"
                    style="top: 8px; right: -2px; width: 8px; height: 8px;"></span>
            </button>
        </div>

        <!-- User Profile Dropdown -->
        <div class="dropdown ms-2">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown"
                aria-expanded="false">
                <img src="https://ui-avatars.com/api/?name=Admin&background=10b981&color=fff" alt="mdo" width="35"
                    height="35" class="rounded-circle border">
            </a>
            <ul class="dropdown-menu text-small shadow dropdown-menu-end border-0 mt-2">
                <li><a class="dropdown-item py-2" href="#"><i class="bi bi-person me-2 text-muted"></i>Profile</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item py-2 text-danger" style="cursor: pointer; text-align: left; width: 100%;">
                            <i class="bi bi-box-arrow-right me-2"></i>Sign out
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>