@extends('layouts.backend.app')
@section('title', 'POS - Point of Sale')

@section('content')
<div class="container-fluid p-0 pos-wrapper">
    <!-- Header Page POS -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold text-dark mb-0">Mesin Kasir (POS)</h4>
            <div class="text-muted small mt-1">
                <i class="bi bi-shop text-success me-1"></i> Freshmart &bull; <i class="bi bi-clock ms-1 me-1"></i> <span id="clockNow"></span>
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <!-- Scanner Mode Toggle -->
            <div class="scanner-mode-toggle d-flex align-items-center gap-2 px-3 py-2" id="scannerModeToggleBox">
                <i class="bi bi-keyboard text-muted" id="iconManualMode" style="font-size: 1.1rem;"></i>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" role="switch" id="scannerModeSwitch" onchange="toggleScannerMode(this.checked)" style="width: 2.5em; height: 1.4em; cursor: pointer;">
                </div>
                <i class="bi bi-upc-scan text-muted" id="iconScannerMode" style="font-size: 1.1rem;"></i>
                <span class="scanner-mode-label fw-semibold" id="scannerModeLabel">Mode: Manual</span>
            </div>
            <button class="btn btn-outline-secondary reset-btn-custom btn-sm d-flex align-items-center px-3 py-2" onclick="clearCartConfirm()">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Order
            </button>
        </div>
    </div>

    <div class="row g-3 pos-main-row">
        <!-- Area Kiri: Katalog Produk & Fitur Search Manual -->
        <div class="col-lg-8 d-flex flex-column h-100">
            <div class="card shadow-sm border-0 d-flex flex-column h-100" style="overflow: hidden;">
                <div class="card-header bg-white border-bottom p-3">
                    <!-- Manual Search Input (visible in Manual Mode) -->
                    <div class="mb-3" id="manualSearchWrapper">
                        <div class="input-group search-input-group shadow-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" id="searchInput" class="form-control border-start-0 ps-0" placeholder="Cari Produk Manual (Ketik Nama / Kode Produk)..." style="box-shadow: none;">
                        </div>
                    </div>

                    <!-- Scanner Input Field (visible in Scanner Mode) -->
                    <div class="mb-3" id="scannerInputWrapper" style="display: none;">
                        <div class="input-group scanner-input-group shadow-sm" id="scannerInputGroupEl">
                            <span class="input-group-text bg-white border-end-0" style="color: var(--theme-orange);"><i class="bi bi-upc-scan" style="font-size: 1.2rem;"></i></span>
                            <input type="text" id="scannerInput" class="form-control border-start-0 ps-0 fw-semibold" 
                                   placeholder="Arahkan scanner ke barcode produk..." 
                                   style="box-shadow: none; font-size: 0.95rem; letter-spacing: 0.5px;"
                                   autocomplete="off">
                            <span class="input-group-text bg-white border-start-0 text-muted scanner-status-badge" id="scannerStatusBadge">
                                <span class="badge scanner-ready-badge px-2 py-1"><i class="bi bi-circle-fill me-1" style="font-size: 0.45rem;"></i>Siap Scan</span>
                            </span>
                        </div>
                        <small class="text-muted mt-1 d-block" style="font-size: 0.76rem;"><i class="bi bi-info-circle me-1"></i>Scanner akan otomatis menambah produk saat kode terdeteksi (tekan Enter).</small>
                    </div>
                    
                    <!-- Scrollable Category Tabs -->
                    <div class="category-tabs d-flex gap-2 overflow-auto pb-1" style="scrollbar-width: none; -ms-overflow-style: none;">
                        <button type="button" class="category-pill active" onclick="filterType('All', this)">Semua</button>
                        <button type="button" class="category-pill" onclick="filterType('Food', this)">Food</button>
                        <button type="button" class="category-pill" onclick="filterType('Beverage', this)">Beverage</button>
                        <button type="button" class="category-pill" onclick="filterType('Grocery', this)">Grocery</button>
                        <button type="button" class="category-pill" onclick="filterType('Household', this)">Household</button>
                        <button type="button" class="category-pill" onclick="filterType('Utility', this)">Utility</button>
                        <button type="button" class="category-pill" onclick="filterType('Health', this)">Health</button>
                    </div>
                </div>
                
                <!-- Product Catalog scrollable -->
                <div class="card-body overflow-auto bg-light p-3 position-relative" id="productListContainer" style="flex-grow: 1;">
                    <!-- Loading Overlay -->
                    <div id="loadingOverlay" class="position-absolute top-0 start-0 w-100 h-100 bg-white justify-content-center align-items-center" style="z-index: 10; opacity: 0.8; display: none;">
                        <div class="spinner-border text-warning" role="status" style="width: 2.5rem; height: 2.5rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    
                    <div class="row g-3" id="productGrid">
                        <!-- Produk di-render via AJAX disini -->
                    </div>
                </div>
                
                <!-- Pagination footer -->
                <div class="card-footer bg-white border-top py-2 px-3 d-flex justify-content-center" id="paginationContainer">
                </div>
            </div>
        </div>

        <!-- Area Kanan: Keranjang (Cart) -->
        <div class="col-lg-4 d-flex flex-column h-100">
            <div class="card shadow-sm border-0 d-flex flex-column h-100 border-0" style="overflow: hidden;">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-cart3 text-theme-orange me-2"></i> Detail Pesanan</h6>
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-sm btn-light border p-1" onclick="showDiscountModal()" title="Atur Diskon" style="border-radius: 8px;">
                            <i class="bi bi-tags text-muted fs-6"></i>
                        </button>
                        <span class="badge bg-warning text-white rounded-pill px-2 py-1" id="cartBadgeCount" style="font-size: 0.75rem;">0 Item</span>
                    </div>
                </div>
                
                <!-- Input Nomor Meja -->
                <div class="bg-light border-bottom p-2 d-flex align-items-center gap-2 px-3">
                    <span class="text-dark fw-bold small flex-shrink-0" style="font-size: 0.82rem;"><i class="bi bi-hash text-warning"></i> No. Meja:</span>
                    <input type="text" id="tableNumberInput" class="form-control form-control-sm bg-white border" placeholder="Set Nomor Meja (opsional)..." style="border-radius: 8px; font-weight: 600; font-size: 0.8rem; height: 32px;">
                </div>
                
                <!-- Cart items list scrollable -->
                <div class="card-body overflow-auto p-3 bg-white" id="cartContainer" style="flex-grow: 1;">
                    <ul class="list-group list-group-flush" id="cartList">
                        <!-- Item keranjang akan dirender JS disin -->
                    </ul>
                    <div id="emptyCartMsg" class="text-center text-muted p-4 my-auto w-100">
                        <div class="bg-light rounded-circle d-inline-flex justify-content-center align-items-center mb-3" style="width: 70px; height: 70px;">
                            <i class="bi bi-cart-dash fs-2 opacity-50"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Keranjang Kosong</h6>
                        <small class="text-muted d-block">Pilih produk di katalog sebelah kiri untuk memulai pesanan.</small>
                    </div>
                </div>
                
                <!-- Section Total Pembayaran & Action -->
                <div class="card-footer bg-light border-top p-3 mt-auto">
                    <div class="d-flex justify-content-between mb-2 text-muted fw-semibold">
                        <span>Subtotal</span>
                        <span id="cartSubtotalText">Rp 0</span>
                    </div>
                    <div id="discountContainer"></div>
                    <div class="d-flex justify-content-between mb-3 text-dark fw-bold fs-4">
                        <span>Total</span>
                        <span id="cartTotalText" class="text-theme-teal">Rp 0</span>
                    </div>
                    <button class="btn btn-theme-orange checkout-btn-premium btn-lg w-100 py-3 fw-bold shadow-sm" id="btnCheckout" onclick="processCheckout()">
                        Proses Tagihan <i class="bi bi-arrow-right-circle ms-1"></i>
                    </button>
                    <button class="btn btn-soft-danger btn-sm w-100 mt-2 fw-semibold py-2" onclick="clearCartConfirm()">
                        <i class="bi bi-trash"></i> Batalkan Semua
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Discount -->
<div class="modal fade" id="discountModal" tabindex="-1" aria-labelledby="discountModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content border-0 shadow" style="border-radius: 16px;">
      <div class="modal-header border-bottom-0 pb-0">
        <h6 class="modal-title fw-bold" id="discountModalLabel"><i class="bi bi-tags text-theme-orange me-2"></i> Atur Diskon</h6>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
         <label class="form-label text-muted small mb-1">Nominal Diskon (Rp)</label>
         <div class="input-group mb-3">
           <span class="input-group-text bg-light border-end-0">Rp</span>
           <input type="number" class="form-control border-start-0" id="inputDiscountAmount" placeholder="0" min="0">
         </div>
         <button type="button" class="btn btn-theme-orange w-100 fw-bold" onclick="applyDiscount()" style="border-radius: 10px;">Terapkan Diskon</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Payment (Pembayaran) -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
      <div class="modal-header border-0 bg-light p-3">
        <h5 class="modal-title fw-bold text-dark" id="paymentModalLabel"><i class="bi bi-wallet2 text-theme-orange me-2"></i> Proses Pembayaran</h5>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <!-- Billing Summary -->
        <div class="bg-theme-orange-bg rounded-3 p-3 mb-4 text-center border border-warning border-opacity-20">
            <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">TOTAL TAGIHAN</span>
            <h2 class="fw-extrabold text-theme-orange mb-0 mt-1" id="paymentTotalLabel" style="letter-spacing: -1px; font-weight: 800;">Rp 0</h2>
        </div>

        <!-- Payment Method -->
        <div class="mb-3">
            <label class="form-label fw-bold text-dark mb-2" style="font-size: 0.9rem;">Metode Pembayaran</label>
            <div class="row g-2">
                <div class="col-4">
                    <input type="radio" class="btn-check" name="paymentMethod" id="payCash" value="Tunai" checked onchange="togglePaymentMethod()">
                    <label class="btn btn-outline-secondary w-100 py-3 fw-semibold method-tab-btn" for="payCash">
                        <i class="bi bi-cash d-block fs-3 mb-1"></i> Tunai
                    </label>
                </div>
                <div class="col-4">
                    <input type="radio" class="btn-check" name="paymentMethod" id="payQris" value="QRIS" onchange="togglePaymentMethod()">
                    <label class="btn btn-outline-secondary w-100 py-3 fw-semibold method-tab-btn" for="payQris">
                        <i class="bi bi-qr-code d-block fs-3 mb-1"></i> QRIS
                    </label>
                </div>
                <div class="col-4">
                    <input type="radio" class="btn-check" name="paymentMethod" id="payDebit" value="Debit/Card" onchange="togglePaymentMethod()">
                    <label class="btn btn-outline-secondary w-100 py-3 fw-semibold method-tab-btn" for="payDebit">
                        <i class="bi bi-credit-card d-block fs-3 mb-1"></i> Debit/Card
                    </label>
                </div>
            </div>
        </div>

        <!-- Cash Section (Visible when payCash is selected) -->
        <div id="cashPaymentSection">
            <div class="mb-3">
                <label for="inputCashAmount" class="form-label fw-bold text-dark mb-1" style="font-size: 0.9rem;">Uang Diterima (Rp)</label>
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-white border-end-0 fw-bold">Rp</span>
                    <input type="number" class="form-control border-start-0 fw-bold text-theme-teal" id="inputCashAmount" placeholder="0" min="0" oninput="calculateChange()">
                </div>
            </div>

            <!-- Quick Cash Helpers -->
            <div class="mb-4">
                <span class="text-muted small fw-semibold d-block mb-2">Pecahan Uang Pas / Cepat</span>
                <div class="d-flex flex-wrap gap-2" id="quickCashContainer">
                    <!-- Helper buttons added dynamically by JS -->
                </div>
            </div>
            
            <!-- Change Calculator -->
            <div class="p-3 rounded-3 d-flex justify-content-between align-items-center" id="changeCalculatorBox" style="background-color: #f8fafc; border: 1px solid #e2e8f0; transition: all 0.3s ease;">
                <span class="fw-bold text-muted" id="changeLabelText">Kembalian</span>
                <h4 class="fw-bold text-dark mb-0" id="changeAmountText">Rp 0</h4>
            </div>
        </div>
        
        <!-- Non-Cash Info Section -->
        <div id="nonCashSection" style="display: none;">
            <div class="alert alert-info border-0 rounded-3 mb-0 p-3" style="background-color: var(--theme-blue-light); color: var(--theme-blue);">
                <div class="d-flex">
                    <i class="bi bi-info-circle-fill fs-5 me-2"></i>
                    <div>
                        <span class="fw-bold d-block" style="font-size: 0.88rem;">Pembayaran Non-Tunai</span>
                        <small style="font-size: 0.78rem;">Silakan scan QRIS atau gesek kartu debit pada mesin EDC merchant. Nominal pembayaran akan dicocokkan otomatis.</small>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer border-0 p-3 bg-light">
         <button type="button" class="btn btn-light border fw-semibold px-4 py-2" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
         <button type="button" class="btn btn-theme-orange fw-bold px-4 py-2" id="btnConfirmPayment" onclick="confirmPayment()" style="border-radius: 10px;">
             Konfirmasi & Bayar <i class="bi bi-arrow-right ms-1"></i>
         </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Order Success & Receipt -->
<div class="modal fade" id="orderSuccessModal" tabindex="-1" aria-labelledby="orderSuccessModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content border-0 shadow-lg p-4" style="border-radius: 16px;">
      <div class="modal-body p-0">
        <div class="text-center mb-4">
            <div class="bg-success rounded-circle d-inline-flex justify-content-center align-items-center mb-3" style="width: 70px; height: 70px; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.35);">
                <i class="bi bi-check-lg text-white" style="font-size: 2.5rem;"></i>
            </div>
            <h4 class="fw-bold text-dark mb-1">Transaksi Berhasil!</h4>
            <p class="text-muted small mb-0">Pembayaran telah diterima dan diproses.</p>
        </div>
        
        <!-- Receipt Box mimicking thermal printer paper -->
        <div class="receipt-box bg-light p-3 rounded-3 mb-4 text-start font-monospace" style="font-size: 0.82rem; border: 1px dashed #cbd5e1; max-height: 250px; overflow-y: auto;">
            <div class="text-center mb-3">
                <h6 class="fw-bold mb-0">TRI FUSION COFFEE & RETAIL</h6>
                <small class="text-muted d-block">Freshmart - Cabang Utama</small>
                <small class="text-muted d-block">Telp: 0812-3456-7890</small>
            </div>
            
            <div class="border-bottom border-secondary border-dashed pb-2 mb-2">
                <div class="d-flex justify-content-between">
                    <span>Kasir: Admin</span>
                    <span id="receiptTime">10/06/2026 18:30</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>ID Transaksi:</span>
                    <span id="receiptTxId">TX-8492048</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Metode:</span>
                    <span id="receiptMethod">Tunai</span>
                </div>
                <div class="d-flex justify-content-between" id="receiptTableNumberRow">
                    <span>No. Meja:</span>
                    <span id="receiptTableNumber">-</span>
                </div>
            </div>
            
            <!-- Items list -->
            <div class="border-bottom border-secondary border-dashed pb-2 mb-2" id="receiptItems">
                <!-- Dynamically populated by JS -->
            </div>
            
            <div class="fw-bold">
                <div class="d-flex justify-content-between mb-1">
                    <span>Subtotal:</span>
                    <span id="receiptSubtotal">Rp 0</span>
                </div>
                <div class="d-flex justify-content-between mb-1 text-success" id="receiptDiscountRow">
                    <span>Diskon:</span>
                    <span id="receiptDiscount">Rp 0</span>
                </div>
                <div class="d-flex justify-content-between mb-2 fs-6 border-top pt-2">
                    <span>TOTAL:</span>
                    <span id="receiptTotal">Rp 0</span>
                </div>
                <div class="d-flex justify-content-between mb-1 text-muted" id="receiptPayRow">
                    <span>Bayar:</span>
                    <span id="receiptPay">Rp 0</span>
                </div>
                <div class="d-flex justify-content-between" id="receiptChangeRow">
                    <span>Kembalian:</span>
                    <span id="receiptChange">Rp 0</span>
                </div>
            </div>
            
            <div class="text-center mt-3 pt-2 border-top border-secondary border-dashed">
                <span class="d-block fw-bold">TERIMA KASIH</span>
                <span class="d-block text-muted" style="font-size: 0.75rem;">Silakan datang kembali</span>
            </div>
        </div>
        
        <div class="d-grid gap-2">
            <!-- Tombol Cetak Struk dengan Ikon Print -->
            <button type="button" class="btn btn-dark fw-bold btn-lg" onclick="printReceipt()" style="border-radius: 12px;">
                <i class="bi bi-printer me-2"></i> Cetak Struk
            </button>
            <!-- Tombol Selesai & Order Baru -->
            <button type="button" class="btn btn-light border fw-semibold mt-1" data-bs-dismiss="modal" style="border-radius: 12px;">
                Selesai & Lanjut Order Baru
            </button>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
/* POS Screen layout forces heights and scroll controls */
.pos-wrapper {
    height: calc(100vh - var(--header-height) - 48px);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
.pos-main-row {
    flex-grow: 1;
    height: 0;
}
.search-input-group {
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid rgba(226, 232, 240, 0.8);
    transition: all 0.3s ease;
}
.search-input-group:focus-within {
    border-color: var(--theme-orange);
    box-shadow: 0 0 0 3px rgba(255, 159, 67, 0.15) !important;
}
.search-input-group .form-control {
    border: none !important;
    background-color: #ffffff;
    height: 46px;
    font-size: 0.9rem;
}
.search-input-group .input-group-text {
    border: none !important;
    background-color: #ffffff;
    padding-left: 16px;
}

/* Category filter tabs */
.category-pill {
    padding: 8px 18px;
    background-color: #ffffff;
    border: 1px solid rgba(226, 232, 240, 0.7);
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    color: #64748b;
    white-space: nowrap;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: var(--premium-shadow-sm);
}
.category-pill:hover {
    color: var(--theme-orange);
    background-color: var(--theme-orange-bg);
    border-color: rgba(255, 159, 67, 0.2);
    transform: translateY(-1px);
}
.category-pill.active {
    color: #ffffff;
    background: linear-gradient(135deg, var(--theme-orange) 0%, #ffb05c 100%);
    border-color: var(--theme-orange);
    box-shadow: 0 4px 10px rgba(255, 159, 67, 0.25);
}

.c-pointer { cursor: pointer; }
.product-card { 
    border-radius: 16px !important;
    border: 1px solid rgba(226, 232, 240, 0.7) !important;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    background-color: #ffffff;
    box-shadow: var(--premium-shadow-sm) !important;
}
.product-card:hover { 
    transform: translateY(-6px); 
    border-color: var(--theme-orange) !important; 
    box-shadow: 0 12px 24px -4px rgba(255, 159, 67, 0.15) !important;
}

.product-card .bg-theme-orange-bg {
    transition: all 0.3s ease;
}

.product-card:hover .bg-theme-orange-bg {
    background-color: var(--theme-orange) !important;
}

.product-card:hover .bg-theme-orange-bg i {
    color: #ffffff !important;
}

/* Custom indicator color text badges */
.text-warning-custom { color: #d97706 !important; }
.text-info-custom { color: #2563eb !important; }
.text-success-custom { color: #059669 !important; }
.text-primary-custom { color: #7c3aed !important; }
.text-teal-custom { color: #0d9488 !important; }

#cartList .list-group-item {
    border-left: none;
    border-right: none;
    border-color: #f1f5f9;
}

.cart-qty-btn {
    border-radius: 8px !important;
    border: 1px solid rgba(226, 232, 240, 0.8) !important;
    background-color: #ffffff;
    color: #64748b;
    transition: all 0.2s ease;
}

.cart-qty-btn:hover {
    color: var(--theme-orange);
    background-color: var(--theme-orange-bg);
    border-color: rgba(255, 159, 67, 0.2);
}

/* Custom Pagination styling */
.pagination .page-link {
    color: #64748b;
    border-color: #e2e8f0;
    outline: none !important;
    box-shadow: none;
    border-radius: 8px;
    margin: 0 2px;
    padding: 6px 12px;
    transition: all 0.2s ease;
}
.pagination .page-item.active .page-link {
    background-color: var(--theme-orange);
    border-color: var(--theme-orange);
    color: #fff;
    box-shadow: 0 4px 10px rgba(255, 159, 67, 0.2);
}
.pagination .page-link:hover {
    color: var(--theme-orange);
    background-color: var(--theme-orange-bg);
    border-color: rgba(255, 159, 67, 0.2);
}

.checkout-btn-premium {
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
    animation: pulseGlow 3s infinite;
    border-radius: 12px !important;
}

.checkout-btn-premium:hover {
    transform: translateY(-2px);
}

.btn-soft-danger {
    background-color: rgba(239, 68, 68, 0.05);
    border-color: rgba(239, 68, 68, 0.1) !important;
    color: #ef4444;
    border-radius: 12px;
    transition: all 0.2s ease;
}

.btn-soft-danger:hover {
    background-color: #ef4444;
    color: #ffffff;
    border-color: #ef4444 !important;
}

.reset-btn-custom {
    border-radius: 10px !important;
    border: 1px solid rgba(226, 232, 240, 0.8);
    transition: all 0.2s ease;
}
.reset-btn-custom:hover {
    background-color: #f1f5f9 !important;
    color: #334155 !important;
}

/* Method radio buttons */
.method-tab-btn {
    border-radius: 12px !important;
    border: 1px solid rgba(226, 232, 240, 0.8) !important;
    color: #64748b;
    transition: all 0.25s ease;
}
.btn-check:checked + .method-tab-btn {
    border-color: var(--theme-orange) !important;
    background-color: var(--theme-orange-bg) !important;
    color: var(--theme-orange) !important;
    box-shadow: 0 0 0 3px rgba(255, 159, 67, 0.15);
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(16px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}
.cart-item-anim {
    animation: slideInRight 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.border-dashed-custom {
    border: 1px dashed rgba(226, 232, 240, 0.8) !important;
}
.bg-opacity-15 {
    background-color: rgba(0, 0, 0, 0.06) !important;
}

/* Scanner Mode Toggle */
.scanner-mode-toggle {
    background: #f8fafc;
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 12px;
    transition: all 0.3s ease;
}
.scanner-mode-toggle.scanner-active {
    background: linear-gradient(135deg, rgba(255, 159, 67, 0.08) 0%, rgba(255, 159, 67, 0.04) 100%);
    border-color: rgba(255, 159, 67, 0.35);
    box-shadow: 0 0 0 3px rgba(255, 159, 67, 0.08);
}
.scanner-mode-toggle.scanner-active #iconScannerMode {
    color: var(--theme-orange) !important;
    animation: pulseGlow 1.5s infinite;
}
.scanner-mode-toggle.scanner-active #scannerModeLabel {
    color: var(--theme-orange);
}
#scannerModeSwitch:checked {
    background-color: var(--theme-orange);
    border-color: var(--theme-orange);
}

/* Scanner Input Field */
.scanner-input-group {
    border-radius: 12px;
    overflow: hidden;
    border: 2px solid rgba(255, 159, 67, 0.4);
    transition: all 0.3s ease;
    animation: scannerBorderPulse 2s ease-in-out infinite;
}
.scanner-input-group:focus-within {
    border-color: var(--theme-orange);
    box-shadow: 0 0 0 3px rgba(255, 159, 67, 0.15) !important;
}
.scanner-input-group .form-control {
    border: none !important;
    background-color: #ffffff;
    height: 46px;
    font-size: 0.95rem;
    color: #1e293b;
}
.scanner-input-group .input-group-text {
    border: none !important;
    background-color: #ffffff;
    padding-left: 16px;
}
@keyframes scannerBorderPulse {
    0%, 100% { border-color: rgba(255, 159, 67, 0.4); }
    50% { border-color: rgba(255, 159, 67, 0.75); }
}

/* Scanner status badge */
.scanner-ready-badge {
    background-color: rgba(16, 185, 129, 0.1);
    color: #10b981;
    border-radius: 8px;
    font-size: 0.72rem;
    font-weight: 600;
    transition: all 0.3s ease;
}
.scanner-processing-badge {
    background-color: rgba(255, 159, 67, 0.12);
    color: var(--theme-orange);
    border-radius: 8px;
    font-size: 0.72rem;
    font-weight: 600;
    animation: fadeInOut 0.5s ease;
}
.scanner-success-badge {
    background-color: rgba(16, 185, 129, 0.12);
    color: #059669;
    border-radius: 8px;
    font-size: 0.72rem;
    font-weight: 600;
}
.scanner-error-badge {
    background-color: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border-radius: 8px;
    font-size: 0.72rem;
    font-weight: 600;
}
@keyframes fadeInOut {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}

/* Scanner toast notification */
#scannerToast {
    position: fixed;
    top: 80px;
    left: 50%;
    transform: translateX(-50%) translateY(-20px);
    background: white;
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: 16px;
    padding: 14px 22px;
    box-shadow: 0 20px 40px -8px rgba(0, 0, 0, 0.12);
    z-index: 9999;
    opacity: 0;
    transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    min-width: 280px;
    text-align: center;
    pointer-events: none;
}
#scannerToast.show {
    opacity: 1;
    transform: translateX(-50%) translateY(0px);
}

/* Print utility styles */
@media print {
    body * {
        visibility: hidden;
    }
    .receipt-box, .receipt-box * {
        visibility: visible;
    }
    .receipt-box {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        border: none !important;
    }
}
</style>

@push('scripts')
<!-- Scanner Toast Notification -->
<div id="scannerToast">
    <div id="scannerToastContent"></div>
</div>

<script>
    // Inisialisasi State Keranjang & Katalog
    let cart = [];
    let currentDiscount = 0;
    let currentType = 'All'; // State kategori terpilih
    let isScannerMode = false; // State mode scanner
    let scannerFocusInterval = null;
    
    // Fitur Discount Modal
    const discountModal = document.getElementById('discountModal') ? new bootstrap.Modal(document.getElementById('discountModal')) : null;

    window.showDiscountModal = function() {
        document.getElementById('inputDiscountAmount').value = currentDiscount > 0 ? currentDiscount : '';
        if(discountModal) discountModal.show();
    }

    window.applyDiscount = function() {
        let val = parseInt(document.getElementById('inputDiscountAmount').value) || 0;
        currentDiscount = val;
        if(discountModal) discountModal.hide();
        renderCart();
    }

    window.removeDiscount = function() {
        currentDiscount = 0;
        renderCart();
    }

    // Fungsi Format Rupiah Standard
    function formatRupiah(number) {
        return 'Rp ' + parseInt(number).toLocaleString('id-ID');
    }
    
    // Fitur Jam Digital
    setInterval(() => {
        let date = new Date();
        document.getElementById('clockNow').innerText = date.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) + ' ' + date.toLocaleTimeString('id-ID');
    }, 1000);

    // Category Filtering
    window.filterType = function(type, element) {
        currentType = type;
        
        // Atur status aktif pil kategori
        document.querySelectorAll('.category-pill').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
        
        loadProducts(1);
    }
    
    // AJAX Product Loading & Pagination
    let searchTimeout = null;

    function formatNumber(num) {
        return parseInt(num).toLocaleString('id-ID');
    }

    window.loadProducts = function(page = 1) {
        let keyword = document.getElementById('searchInput').value.trim();
        const loading = document.getElementById('loadingOverlay');
        loading.style.display = 'flex';
        
        fetch(`/pos/data?page=${page}&search=${encodeURIComponent(keyword)}&type=${currentType}`)
            .then(res => res.json())
            .then(data => {
                const grid = document.getElementById('productGrid');
                grid.innerHTML = '';
                
                if(data.data.length === 0) {
                    grid.innerHTML = `
                        <div class="col-12 text-center text-muted mt-5 w-100">
                            <i class="bi bi-inboxes text-muted opacity-50 d-block mb-3" style="font-size: 4rem;"></i>
                            <h5>Produk tidak ditemukan</h5>
                        </div>
                    `;
                } else {
                    data.data.forEach(p => {
                        let priceDisp = formatNumber(p.Price);
                        let cleanName = p.Name.replace(/'/g, "\\'");
                        
                        // Menyesuaikan warna badge tipe produk
                        let badgeClass = 'bg-secondary text-white';
                        let badgeIcon = 'bi-box-seam';
                        if (p.Type === 'Food') {
                            badgeClass = 'bg-warning-custom text-warning-custom';
                            badgeIcon = 'bi-egg-fried';
                        } else if (p.Type === 'Beverage') {
                            badgeClass = 'bg-info-custom text-info-custom';
                            badgeIcon = 'bi-cup-hot';
                        } else if (p.Type === 'Grocery') {
                            badgeClass = 'bg-success-custom text-success-custom';
                            badgeIcon = 'bi-shop';
                        } else if (p.Type === 'Household') {
                            badgeClass = 'bg-primary-custom text-primary-custom';
                            badgeIcon = 'bi-house-heart';
                        } else if (p.Type === 'Utility') {
                            badgeClass = 'bg-dark bg-opacity-10 text-dark';
                            badgeIcon = 'bi-tools';
                        } else if (p.Type === 'Health') {
                            badgeClass = 'bg-teal-custom text-teal-custom';
                            badgeIcon = 'bi-heart-pulse';
                        }
                        
                        grid.innerHTML += `
                            <div class="col-6 col-md-4 col-sm-6 col-lg-3 product-item mb-3">
                                <div class="card h-100 border-0 shadow-sm c-pointer product-card" onclick="addToCart(${p.Oid}, '${cleanName}', ${p.Price})">
                                    <div class="card-body text-center p-3 d-flex flex-column justify-content-between">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="badge ${badgeClass} border-0 d-flex align-items-center gap-1" style="font-size: 0.7rem; padding: 4px 8px;">
                                                <i class="bi ${badgeIcon}"></i> ${p.Type || 'Item'}
                                            </span>
                                            <small class="text-muted" style="font-size: 0.7rem;">${p.Code}</small>
                                        </div>
                                        <div class="mb-3 mt-1 d-flex justify-content-center">
                                            ${p.image ? 
                                                `<img src="/storage/${p.image}" class="rounded object-fit-cover shadow-sm" style="width: 64px; height: 64px; border: 1px solid #e2e8f0;" alt="${cleanName}">` :
                                                `<div class="bg-theme-orange-bg rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    <i class="bi bi-box text-theme-orange fs-4"></i>
                                                 </div>`
                                            }
                                        </div>
                                        <h6 class="card-title fw-bold text-dark mb-2 lh-sm" style="font-size: 0.88rem;">${p.Name}</h6>
                                        <span class="badge w-100 bg-white border border-success text-success fw-bold p-2 fs-6 mt-auto">Rp ${priceDisp}</span>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                }
                
                renderPagination(data);
            })
            .catch(err => console.error("Error fetching products:", err))
            .finally(() => {
                loading.style.display = 'none';
            });
    }

    function renderPagination(data) {
        const pCont = document.getElementById('paginationContainer');
        pCont.innerHTML = '';
        
        if (data.last_page <= 1) return;

        let html = '<ul class="pagination pagination-sm m-0">';
        
        html += `<li class="page-item ${data.current_page == 1 ? 'disabled' : ''}">
                    <a class="page-link fw-semibold" href="#" onclick="event.preventDefault(); loadProducts(${data.current_page - 1})">&laquo; Prev</a>
                 </li>`;
                 
        for(let i=1; i<=data.last_page; i++) {
             if (i === 1 || i === data.last_page || (i >= data.current_page - 2 && i <= data.current_page + 2)) {
                 html += `<li class="page-item ${data.current_page == i ? 'active' : ''}">
                            <a class="page-link fw-semibold" href="#" onclick="event.preventDefault(); loadProducts(${i})">${i}</a>
                          </li>`;
             } else if (i === data.current_page - 3 || i === data.current_page + 3) {
                 html += `<li class="page-item disabled"><span class="page-link text-muted border-0 bg-transparent">...</span></li>`;
             }
        }
        
        html += `<li class="page-item ${data.current_page == data.last_page ? 'disabled' : ''}">
                    <a class="page-link fw-semibold" href="#" onclick="event.preventDefault(); loadProducts(${data.current_page + 1})">Next &raquo;</a>
                 </li>`;
                 
        html += '</ul>';
        pCont.innerHTML = html;
    }

    // Live Search with Debounce
    document.getElementById('searchInput').addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            loadProducts(1);
        }, 300);
    });

    // ============================================================
    // SCANNER MODE LOGIC
    // ============================================================

    // Toggle antara mode manual dan scanner
    window.toggleScannerMode = function(enabled) {
        isScannerMode = enabled;
        const toggleBox       = document.getElementById('scannerModeToggleBox');
        const manualWrapper   = document.getElementById('manualSearchWrapper');
        const scannerWrapper  = document.getElementById('scannerInputWrapper');
        const label           = document.getElementById('scannerModeLabel');
        const iconManual      = document.getElementById('iconManualMode');
        const iconScanner     = document.getElementById('iconScannerMode');

        if (isScannerMode) {
            // Aktifkan scanner mode
            toggleBox.classList.add('scanner-active');
            manualWrapper.style.display  = 'none';
            scannerWrapper.style.display = 'block';
            label.innerText = 'Mode: Scanner';
            iconManual.style.opacity  = '0.4';
            iconScanner.style.opacity = '1';

            // Auto-focus dan jaga fokus tetap di scanner input
            const scannerInput = document.getElementById('scannerInput');
            scannerInput.value = '';
            scannerInput.focus();

            // Interval untuk selalu kembalikan fokus ke scanner
            if (scannerFocusInterval) clearInterval(scannerFocusInterval);
            scannerFocusInterval = setInterval(() => {
                if (isScannerMode && document.activeElement !== scannerInput) {
                    // Hanya refocus jika tidak ada modal aktif
                    const anyModalOpen = document.querySelector('.modal.show');
                    if (!anyModalOpen) {
                        scannerInput.focus();
                    }
                }
            }, 500);

            showScannerToast('<i class="bi bi-upc-scan me-2 text-warning"></i><strong>Mode Scanner Aktif</strong> &mdash; Arahkan barcode ke scanner', 'success');
        } else {
            // Kembali ke mode manual
            toggleBox.classList.remove('scanner-active');
            manualWrapper.style.display  = 'block';
            scannerWrapper.style.display = 'none';
            label.innerText = 'Mode: Manual';
            iconManual.style.opacity  = '1';
            iconScanner.style.opacity = '0.4';

            if (scannerFocusInterval) {
                clearInterval(scannerFocusInterval);
                scannerFocusInterval = null;
            }

            // Kembalikan fokus ke pencarian manual
            document.getElementById('searchInput').focus();
            showScannerToast('<i class="bi bi-keyboard me-2 text-muted"></i><strong>Mode Manual Aktif</strong> &mdash; Ketik nama / kode produk', 'info');
        }
    }

    // Scanner Input Event: Tangkap Enter dari barcode scanner
    document.getElementById('scannerInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const code = this.value.trim();
            if (code.length > 0) {
                processScannerCode(code);
            }
        }
    });

    // Proses kode yang discan
    function processScannerCode(code) {
        const scannerInput  = document.getElementById('scannerInput');
        const statusBadge   = document.getElementById('scannerStatusBadge');

        // Set status: memproses
        statusBadge.innerHTML = '<span class="badge scanner-processing-badge px-2 py-1"><i class="bi bi-arrow-repeat me-1"></i>Memproses...</span>';
        scannerInput.disabled = true;

        fetch(`/pos/data?search=${encodeURIComponent(code)}&type=All&page=1`)
            .then(res => res.json())
            .then(data => {
                if (data.data && data.data.length > 0) {
                    // Ambil produk pertama yang paling cocok
                    const product = data.data[0];
                    addToCart(product.Oid, product.Name, product.Price);

                    // Feedback sukses
                    statusBadge.innerHTML = '<span class="badge scanner-success-badge px-2 py-1"><i class="bi bi-check-circle me-1"></i>Ditambahkan!</span>';
                    showScannerToast(`<i class="bi bi-check-circle-fill me-2" style="color:#10b981"></i><strong>${product.Name}</strong> &mdash; ${formatRupiah(product.Price)} ditambahkan ke keranjang`, 'success');

                    setTimeout(() => {
                        statusBadge.innerHTML = '<span class="badge scanner-ready-badge px-2 py-1"><i class="bi bi-circle-fill me-1" style="font-size: 0.45rem;"></i>Siap Scan</span>';
                    }, 2000);
                } else {
                    // Produk tidak ditemukan
                    statusBadge.innerHTML = '<span class="badge scanner-error-badge px-2 py-1"><i class="bi bi-x-circle me-1"></i>Tidak Ditemukan</span>';
                    showScannerToast(`<i class="bi bi-exclamation-triangle-fill me-2" style="color:#f59e0b"></i>Produk dengan kode <strong>&quot;${code}&quot;</strong> tidak ditemukan`, 'warning');

                    setTimeout(() => {
                        statusBadge.innerHTML = '<span class="badge scanner-ready-badge px-2 py-1"><i class="bi bi-circle-fill me-1" style="font-size: 0.45rem;"></i>Siap Scan</span>';
                    }, 2500);
                }
            })
            .catch(err => {
                console.error('Scanner fetch error:', err);
                statusBadge.innerHTML = '<span class="badge scanner-error-badge px-2 py-1"><i class="bi bi-wifi-off me-1"></i>Error</span>';
                setTimeout(() => {
                    statusBadge.innerHTML = '<span class="badge scanner-ready-badge px-2 py-1"><i class="bi bi-circle-fill me-1" style="font-size: 0.45rem;"></i>Siap Scan</span>';
                }, 2500);
            })
            .finally(() => {
                scannerInput.disabled = false;
                scannerInput.value = '';
                scannerInput.focus();
            });
    }

    // Toast notification untuk feedback scanner
    let toastTimeout = null;
    function showScannerToast(html, type = 'success') {
        const toast = document.getElementById('scannerToast');
        document.getElementById('scannerToastContent').innerHTML = html;

        // Warna border berdasarkan tipe
        const borderColors = {
            success: 'rgba(16, 185, 129, 0.3)',
            warning: 'rgba(245, 158, 11, 0.3)',
            info: 'rgba(100, 116, 139, 0.2)'
        };
        toast.style.borderColor = borderColors[type] || borderColors.success;

        toast.classList.add('show');
        if (toastTimeout) clearTimeout(toastTimeout);
        toastTimeout = setTimeout(() => {
            toast.classList.remove('show');
        }, 2500);
    }

    // Initial Load at First Boot
    document.addEventListener('DOMContentLoaded', function() {
        loadProducts(1);
        renderCart();
    });

    // Menambah data produk ke keranjang
    window.addToCart = function(id, name, price) {
        let existingItem = cart.find(item => item.id == id);
        if(existingItem) {
            existingItem.qty += 1;
        } else {
            cart.push({ id, name, price, qty: 1, note: '' });
        }
        renderCart();
    }

    // Mengubah catatan item keranjang
    window.updateNote = function(id, note) {
        let item = cart.find(i => i.id == id);
        if(item) {
            item.note = note;
        }
    }

    // Mengubah Qty Produk dari Cart
    window.updateQty = function(id, change) {
        let item = cart.find(i => i.id == id);
        if(item) {
            item.qty += change;
            if(item.qty <= 0) {
                cart = cart.filter(i => i.id != id); 
            }
            renderCart();
        }
    }

    // Mengosongkan Cart Seluruhnya dengan Konfirmasi
    window.clearCartConfirm = function() {
        const tableNumInput = document.getElementById('tableNumberInput');
        if(cart.length > 0 || (tableNumInput && tableNumInput.value !== '')) {
            if(confirm('Yakin ingin membatalkan/mengosongkan semua pesanan?')) {
                cart = [];
                currentDiscount = 0;
                if(tableNumInput) tableNumInput.value = '';
                renderCart();
            }
        }
    }

    // Clear cart silently
    window.clearCart = function() {
        cart = [];
        currentDiscount = 0;
        const tableNumInput = document.getElementById('tableNumberInput');
        if(tableNumInput) tableNumInput.value = '';
        renderCart();
    }

    // Render Ulang DOM Cart
    function renderCart() {
        const cartList = document.getElementById('cartList');
        const emptyMsg = document.getElementById('emptyCartMsg');
        const cartSubtotalText = document.getElementById('cartSubtotalText');
        const cartTotalText = document.getElementById('cartTotalText');
        const badgeCount = document.getElementById('cartBadgeCount');
        
        cartList.innerHTML = '';
        let total = 0;
        let totalQty = 0;

        if(cart.length === 0) {
            emptyMsg.style.display = 'block';
            cartSubtotalText.innerText = 'Rp 0';
            cartTotalText.innerText = 'Rp 0';
            badgeCount.innerText = '0 Item';
            document.getElementById('discountContainer').innerHTML = '';
            currentDiscount = 0;
            return;
        }

        emptyMsg.style.display = 'none';

        cart.forEach(item => {
            let subtotal = item.price * item.qty;
            total += subtotal;
            totalQty += item.qty;
            
            let html = `
                <li class="list-group-item p-3 cart-item-anim border border-dashed-custom rounded-3 mb-2 bg-light bg-opacity-15">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div style="max-width: 65%;">
                            <h6 class="mb-0 fw-bold text-dark lh-sm" style="font-size: 0.88rem;">${item.name}</h6>
                            <small class="text-muted" style="font-size: 0.78rem;">${formatRupiah(item.price)}</small>
                        </div>
                        <span class="text-theme-teal fw-bold" style="font-size: 0.88rem;">${formatRupiah(subtotal)}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <!-- Controls QTY -->
                        <div class="input-group input-group-sm" style="width: 100px;">
                            <button class="btn cart-qty-btn" type="button" onclick="updateQty(${item.id}, -1)">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="text" class="form-control text-center px-1 bg-transparent border-0 fw-bold text-dark" value="${item.qty}" readonly style="font-size: 0.85rem;">
                            <button class="btn cart-qty-btn" type="button" onclick="updateQty(${item.id}, 1)">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Catatan Item -->
                    <div class="mt-2 pt-2 border-top border-dashed-custom">
                        <input type="text" class="form-control form-control-sm bg-white border text-muted" 
                               placeholder="Tambahkan catatan (cth: Less Ice)..." 
                               value="${item.note || ''}" 
                               oninput="updateNote(${item.id}, this.value)"
                               style="font-size: 0.75rem; border-radius: 6px; height: 28px; box-shadow: none;">
                    </div>
                </li>
            `;
            cartList.insertAdjacentHTML('beforeend', html);
        });

        cartSubtotalText.innerText = formatRupiah(total);
        
        const discountContainer = document.getElementById('discountContainer');
        if(currentDiscount > 0) {
            discountContainer.innerHTML = `
                <div class="d-flex justify-content-between mb-2 text-success fw-semibold">
                    <span>Diskon <i class="bi bi-x-circle ms-1 text-danger" style="cursor:pointer;" onclick="removeDiscount()" title="Hapus Diskon"></i></span>
                    <span>- ${formatRupiah(currentDiscount)}</span>
                </div>
            `;
        } else {
            discountContainer.innerHTML = '';
        }

        let finalTotal = total - currentDiscount;
        if(finalTotal < 0) finalTotal = 0; // Prevent negative total

        cartTotalText.innerText = formatRupiah(finalTotal);
        badgeCount.innerText = totalQty + ' Item';
    }

    // Toggle Payment Method view (Cash vs Non-Cash)
    window.togglePaymentMethod = function() {
        let method = document.querySelector('input[name="paymentMethod"]:checked').value;
        if(method === 'Tunai') {
            document.getElementById('cashPaymentSection').style.display = 'block';
            document.getElementById('nonCashSection').style.display = 'none';
        } else {
            document.getElementById('cashPaymentSection').style.display = 'none';
            document.getElementById('nonCashSection').style.display = 'block';
        }
    }

    // Quick cash preset helpers builder
    function setupQuickCash(total) {
        const container = document.getElementById('quickCashContainer');
        container.innerHTML = '';
        
        let presets = [total];
        let denoms = [10000, 20000, 50000, 100000];
        denoms.forEach(d => {
            if (d > total && !presets.includes(d)) {
                presets.push(d);
            }
        });
        
        presets.sort((a,b) => a-b);
        presets = presets.slice(0, 5); // limit to top 5 options
        
        presets.forEach(val => {
            let label = val === total ? 'Uang Pas' : formatRupiah(val);
            container.innerHTML += `
                <button type="button" class="btn btn-sm btn-outline-warning text-dark border fw-medium px-3 py-2" onclick="setCashAmount(${val})" style="border-radius: 8px;">
                    ${label}
                </button>
            `;
        });
    }

    window.setCashAmount = function(val) {
        document.getElementById('inputCashAmount').value = val;
        calculateChange();
    }

    // Live Change calculator
    window.calculateChange = function() {
        let total = 0;
        cart.forEach(item => total += item.price * item.qty);
        let finalTotal = total - currentDiscount;
        if(finalTotal < 0) finalTotal = 0;
        
        let cash = parseInt(document.getElementById('inputCashAmount').value) || 0;
        let change = cash - finalTotal;
        
        let changeBox = document.getElementById('changeCalculatorBox');
        let changeText = document.getElementById('changeAmountText');
        let changeLabel = document.getElementById('changeLabelText');
        
        if (cash < finalTotal) {
            changeBox.style.backgroundColor = 'rgba(239, 68, 68, 0.05)';
            changeBox.style.borderColor = 'rgba(239, 68, 68, 0.15)';
            changeLabel.innerText = 'Uang Kurang';
            changeLabel.style.color = '#ef4444';
            changeText.innerText = '- ' + formatRupiah(Math.abs(change));
            changeText.style.color = '#ef4444';
        } else {
            changeBox.style.backgroundColor = '#ecfdf5';
            changeBox.style.borderColor = '#a7f3d0';
            changeLabel.innerText = 'Kembalian';
            changeLabel.style.color = '#10b981';
            changeText.innerText = formatRupiah(change);
            changeText.style.color = '#10b981';
        }
    }

    // Modal Instance payment & success
    let paymentModalInstance = null;
    let successModalInstance = null;

    // Trigger Payment Modal
    window.processCheckout = function() {
        if(cart.length === 0) {
            alert('Keranjang masih kosong! Silakan pilih produk terlebih dahulu.');
            return;
        }
        
        let total = 0;
        cart.forEach(item => total += item.price * item.qty);
        let finalTotal = total - currentDiscount;
        if(finalTotal < 0) finalTotal = 0;
        
        // Set values in payment modal
        document.getElementById('paymentTotalLabel').innerText = formatRupiah(finalTotal);
        document.getElementById('inputCashAmount').value = '';
        
        // Reset method Tunai
        document.getElementById('payCash').checked = true;
        togglePaymentMethod();
        calculateChange();
        setupQuickCash(finalTotal);
        
        // Launch Payment Modal
        paymentModalInstance = new bootstrap.Modal(document.getElementById('paymentModal'));
        paymentModalInstance.show();
    }

    // Confirm Payment & launch receipt success modal
    window.confirmPayment = function() {
        let total = 0;
        cart.forEach(item => total += item.price * item.qty);
        let finalTotal = total - currentDiscount;
        if(finalTotal < 0) finalTotal = 0;
        
        let method = document.querySelector('input[name="paymentMethod"]:checked').value;
        let cash = parseInt(document.getElementById('inputCashAmount').value) || 0;
        let change = cash - finalTotal;
        
        if (method === 'Tunai' && cash < finalTotal) {
            alert('Uang yang diterima kurang dari total tagihan!');
            return;
        }
        
        const tableNumInput = document.getElementById('tableNumberInput');
        const tableNo = tableNumInput ? tableNumInput.value.trim() : 'TBA';
        
        // Prepare API payload
        const payload = {
            Table_No: tableNo,
            Status: 1, // Status: 1 (Paid / Berhasil)
            items: cart.map(item => ({
                id: item.id,
                qty: item.qty,
                note: item.note || ''
            }))
        };

        const btnConfirm = document.getElementById('btnConfirmPayment');
        const originalText = btnConfirm.innerHTML;
        btnConfirm.disabled = true;
        btnConfirm.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';

        fetch('/api/transactions', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                // Sembunyikan Modal Pembayaran
                if(paymentModalInstance) {
                    let modalElement = document.getElementById('paymentModal');
                    let modalObj = bootstrap.Modal.getInstance(modalElement);
                    if(modalObj) modalObj.hide();
                }
                
                // Populasikan Struk Cetak
                renderReceipt(method, cash, change, finalTotal, total);
                
                // Tampilkan Modal Struk Sukses
                successModalInstance = new bootstrap.Modal(document.getElementById('orderSuccessModal'));
                successModalInstance.show();
                
                // Reset Keranjang
                cart = [];
                currentDiscount = 0;
                if(tableNumInput) tableNumInput.value = '';
                renderCart();
            } else {
                alert('Gagal menyimpan transaksi: ' + (data.message || 'Error'));
            }
        })
        .catch(err => {
            console.error('Checkout error:', err);
            alert('Gagal menyimpan transaksi ke server.');
        })
        .finally(() => {
            btnConfirm.disabled = false;
            btnConfirm.innerHTML = originalText;
        });
    }

    // Render receipt inside success modal
    function renderReceipt(method, cash, change, finalTotal, subtotal) {
        // ID Transaksi
        let txId = 'TX-' + Math.floor(100000 + Math.random() * 900000);
        document.getElementById('receiptTxId').innerText = txId;
        
        // Metode
        document.getElementById('receiptMethod').innerText = method;
        
        // No. Meja
        const tableNumInput = document.getElementById('tableNumberInput');
        const tableNum = tableNumInput ? tableNumInput.value.trim() : '';
        const receiptTableNumber = document.getElementById('receiptTableNumber');
        const receiptTableNumberRow = document.getElementById('receiptTableNumberRow');
        if (tableNum && receiptTableNumberRow) {
            receiptTableNumberRow.style.setProperty('display', 'flex', 'important');
            receiptTableNumber.innerText = tableNum;
        } else if (receiptTableNumberRow) {
            receiptTableNumberRow.style.setProperty('display', 'none', 'important');
        }
        
        // Waktu
        let now = new Date();
        document.getElementById('receiptTime').innerText = now.toLocaleDateString('id-ID') + ' ' + now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        
        // Daftar Item
        const receiptItems = document.getElementById('receiptItems');
        receiptItems.innerHTML = '';
        
        cart.forEach(item => {
            let lineSubtotal = item.price * item.qty;
            let noteHtml = item.note ? `<div class="text-muted small ps-2" style="font-size:0.72rem; margin-top:-2px; margin-bottom:4px;">* Catatan: ${item.note}</div>` : '';
            receiptItems.innerHTML += `
                <div class="mb-1">
                    <div class="d-flex justify-content-between">
                        <span>${item.name} (${item.qty}x)</span>
                        <span>${formatRupiah(lineSubtotal)}</span>
                    </div>
                    ${noteHtml}
                </div>
            `;
        });
        
        // Summary
        document.getElementById('receiptSubtotal').innerText = formatRupiah(subtotal);
        
        const discountRow = document.getElementById('receiptDiscountRow');
        if (currentDiscount > 0) {
            discountRow.style.setProperty('display', 'flex', 'important');
            document.getElementById('receiptDiscount').innerText = '- ' + formatRupiah(currentDiscount);
        } else {
            discountRow.style.setProperty('display', 'none', 'important');
        }
        
        document.getElementById('receiptTotal').innerText = formatRupiah(finalTotal);
        
        const payRow = document.getElementById('receiptPayRow');
        const changeRow = document.getElementById('receiptChangeRow');
        
        if (method === 'Tunai') {
            payRow.style.setProperty('display', 'flex', 'important');
            changeRow.style.setProperty('display', 'flex', 'important');
            document.getElementById('receiptPay').innerText = formatRupiah(cash);
            document.getElementById('receiptChange').innerText = formatRupiah(change);
        } else {
            payRow.style.setProperty('display', 'none', 'important');
            changeRow.style.setProperty('display', 'none', 'important');
        }
    }

    // Cetak struk POS
    window.printReceipt = function() {
        const receiptContent = document.querySelector('.receipt-box').innerHTML;
        const printWindow = window.open('', '_blank', 'width=380,height=550');
        
        printWindow.document.write(`
            <html>
                <head>
                    <title>Cetak Struk POS</title>
                    <style>
                        body {
                            font-family: monospace;
                            padding: 15px;
                            width: 280px;
                            margin: 0 auto;
                            font-size: 0.8rem;
                        }
                        .text-center { text-align: center; }
                        .mb-0 { margin-bottom: 0; }
                        .mb-1 { margin-bottom: 4px; }
                        .mb-2 { margin-bottom: 8px; }
                        .mb-3 { margin-bottom: 12px; }
                        .mt-3 { margin-top: 12px; }
                        .d-block { display: block; }
                        .d-flex { display: flex; }
                        .justify-content-between { justify-content: space-between; }
                        .border-bottom { border-bottom: 1px dashed #000; }
                        .border-top { border-top: 1px dashed #000; }
                        .pb-2 { padding-bottom: 8px; }
                        .pt-2 { padding-top: 8px; }
                        .fw-bold { font-weight: bold; }
                        .text-muted { color: #666; }
                        .fs-6 { font-size: 1.1em; }
                    </style>
                </head>
                <body onload="window.print(); window.close();">
                    ${receiptContent}
                </body>
            </html>
        `);
        printWindow.document.close();
    }
</script>
@endpush
@endsection
