<nav id="sidebar" class="d-flex flex-column flex-shrink-0 pt-3">
    <a href="/dashboard" class="d-flex align-items-center mb-4 mt-2 px-4 text-decoration-none">
        <!-- Replicating the Dreams POS Logo style loosely -->
        <span class="fs-3 fw-bold" style="color: #1b2850; letter-spacing: -1px;">
            Tri<span style="color: var(--theme-orange); font-size: 0.5em; vertical-align: super;">Fusion</span>
        </span>
    </a>

    <div class="px-3">
        <ul class="nav nav-pills flex-column mb-auto">

            <h6 class="sidebar-heading px-3 mt-2 mb-2 text-muted fw-bold"
                style="font-size: 0.65rem; text-transform: capitalize; color: #888 !important;">
                Main
            </h6>

            <!-- Dashboard Menu Item (Parent) -->
            <!-- <li class="nav-item mb-1">
                <a href="#"
                    class="nav-link nav-link-custom text-muted d-flex align-items-center justify-content-between px-3 py-2 fw-medium">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-grid me-3 fs-6"></i>
                        Dashboard
                    </div>
                    <i class="bi bi-chevron-down" style="font-size: 0.75rem;"></i>
                </a>
            </li>

          <!-- Sub Items for Dashboard (as shown in image) -->
            <!-- <div class="ms-4 ps-2 border-start border-2 border-light mt-1 mb-2">
                <a href="#" class="nav-link nav-sub-custom d-flex align-items-center py-1 px-2 fw-medium"
                    style="font-size: 0.8rem;">
                    <i class="bi bi-circle-fill me-2" style="font-size: 4px;"></i> Admin Dashboard
                </a>
                <a href="#" class="nav-link nav-sub-custom d-flex align-items-center py-1 px-2 text-muted fw-medium"
                    style="font-size: 0.8rem;">
                    <i class="bi bi-circle-fill me-2" style="font-size: 4px;"></i> Admin Dashboard 2
                </a>
                <a href="#" class="nav-link nav-sub-custom d-flex align-items-center py-1 px-2 text-muted fw-medium"
                    style="font-size: 0.8rem;">
                    <i class="bi bi-circle-fill me-2" style="font-size: 4px;"></i> Sales Dashboard
                </a>
            </div> -->

            <!-- Other Main Items -->
            <li class="nav-item mb-1 mt-2">
                <a href="{{ route('pos.index') }}"
                    class="nav-link nav-link-custom {{ request()->routeIs('pos.*') ? 'active' : 'text-muted' }} d-flex align-items-center px-3 py-2 fw-medium">
                    <i class="bi bi-calculator me-3 fs-6"></i>
                    Mesin Kasir (POS)
                </a>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ route('transactions.index') }}"
                    class="nav-link nav-link-custom {{ request()->routeIs('transactions.*') ? 'active' : 'text-muted' }} d-flex align-items-center px-3 py-2 fw-medium">
                    <i class="bi bi-journal-text me-3 fs-6"></i>
                    Log Transaksi
                </a>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ route('users.index') }}"
                    class="nav-link nav-link-custom {{ request()->routeIs('users.*') ? 'active' : 'text-muted' }} d-flex align-items-center px-3 py-2 fw-medium">
                    <i class="bi bi-people me-3 fs-6"></i>
                    Users
                </a>
            </li>
            <!-- <li class="nav-item mb-1">
                <a href="#"
                    class="nav-link nav-link-custom text-muted d-flex align-items-center justify-content-between px-3 py-2 fw-medium">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-window me-3 fs-6"></i>
                        Application
                    </div>
                    <i class="bi bi-chevron-right" style="font-size: 0.75rem;"></i>
                </a>
            </li>
            <li class="nav-item mb-3">
                <a href="#"
                    class="nav-link nav-link-custom text-muted d-flex align-items-center justify-content-between px-3 py-2 fw-medium">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-layout-sidebar me-3 fs-6"></i>
                        Layouts
                    </div>
                    <i class="bi bi-chevron-right" style="font-size: 0.75rem;"></i>
                </a>
            </li> -->
            <!-- Disaat nanti API Initial udah ada gunakan payload initial jangan ambil dari database IsPos Lagi -->
            @php
                $userIsPos = auth()->check() ? auth()->user()->IsPos : 0;
                $groupedMenus = \App\Models\Menu::where('IsPos', $userIsPos)->get()->groupBy('Category');

                $iconMap = [
                    'Menu & Item' => 'bi-menu-button',
                    'Produk & Kategori' => 'bi-tags',
                    'Table Management' => 'bi-ui-checks-grid',
                    'Kitchen Display System' => 'bi-display',
                    'Self-Order QR Code' => 'bi-qr-code-scan',
                    'Multi-Payment' => 'bi-credit-card',
                    'Barcode Scanning' => 'bi-upc-scan',
                    'Refund / Return / Exchange' => 'bi-arrow-repeat',
                    'Loyalty Program' => 'bi-star',
                    'Customer Data & Loyalty' => 'bi-person-lines-fill',
                    'Bahan Baku & HPP' => 'bi-boxes',
                    'Update Stok Real-Time' => 'bi-clock-history',
                    'Supplier & Purchase Order' => 'bi-truck',
                    'Penjualan & Menu Laris' => 'bi-bar-chart',
                    'Laporan Penjualan' => 'bi-file-earmark-bar-graph',
                    'Integrasi Aplikasi' => 'bi-link-45deg',
                    'Sinkronisasi Outlet' => 'bi-arrow-repeat',
                    'Satuan Barang' => 'bi-box-seam',
                ];
            @endphp

            @foreach ($groupedMenus as $category => $features)
                <!-- Dynamic {{ $category }} Section -->
                <h6 class="sidebar-heading px-3 mt-3 mb-2 text-muted fw-bold"
                    style="font-size: 0.65rem; text-transform: uppercase; color: #888 !important;">
                    {{ $category }}
                </h6>
                @foreach ($features as $menu)
                    <li class="nav-item mb-1">
                        @php
                            $routeUrl = '#';
                            $isActive = false;
                            if ($menu->Fitur === 'Produk & Kategori') {
                                $routeUrl = route('products.index');
                                $isActive = request()->routeIs('products.*');
                            } elseif ($menu->Fitur === 'Menu & Item') {
                                $routeUrl = route('foods.index');
                                $isActive = request()->routeIs('foods.*');
                            } elseif ($menu->Fitur === 'Satuan Barang') {
                                $routeUrl = route('itemunits.index');
                                $isActive = request()->routeIs('itemunits.*');
                            } elseif ($menu->Fitur === 'Bahan Baku & HPP') {
                                $routeUrl = route('rawmaterials.index');
                                $isActive = request()->routeIs('rawmaterials.*');
                            }
                        @endphp
                        <a href="{{ $routeUrl }}"
                            class="nav-link nav-link-custom {{ $isActive ? 'active' : 'text-muted' }} d-flex align-items-center px-3 py-2 fw-medium">
                            <i class="bi {{ $iconMap[$menu->Fitur] ?? 'bi-circle' }} me-3 fs-6"></i>
                            {{ $menu->Fitur }}
                        </a>
                    </li>
                @endforeach
            @endforeach

        </ul>
    </div>
</nav>

<style>
    .nav-link-custom {
        color: #64748b;
        border-radius: 8px;
        transition: all 0.2s ease;
        padding: 10px 16px !important;
        margin-bottom: 4px;
    }

    .nav-link-custom:hover {
        background-color: #f1f5f9;
        color: #1e293b;
    }

    /* Active Main Menu Style (Orange tint) */
    .nav-link-custom.active {
        background-color: var(--theme-orange) !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        box-shadow: 0 4px 6px -1px rgba(255, 159, 67, 0.4);
    }

    .nav-sub-custom {
        color: #94a3b8;
        border-radius: 6px;
        padding: 8px 16px !important;
        transition: all 0.2s ease;
        margin-bottom: 2px;
    }

    .nav-sub-custom:hover {
        background-color: #f1f5f9;
        color: #1e293b !important;
    }

    .nav-sub-custom.active {
        color: var(--theme-orange) !important;
        background-color: var(--theme-orange-bg) !important;
        font-weight: 600;
    }

    .sidebar-heading {
        letter-spacing: 0.8px;
        text-transform: uppercase !important;
        font-size: 0.7rem !important;
        color: #94a3b8 !important;
        margin-top: 1.5rem !important;
        margin-bottom: 0.75rem !important;
    }
</style>