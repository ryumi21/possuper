@extends('layouts.backend.app')
@section('title', 'POS - Point of Sale')

@section('content')
<div class="container-fluid p-0">
    <!-- Header Page POS (Opsional) -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold text-dark mb-0">Mesin Kasir (POS)</h4>
        <div class="text-muted small">
            <i class="bi bi-clock"></i> <span id="clockNow"></span>
        </div>
    </div>

    <div class="row g-3 h-100">
        <!-- Area Kiri: Katalog Produk & Fitur Search Manual -->
        <div class="col-lg-8 d-flex flex-column" style="min-height: calc(100vh - 160px);">
            <div class="card flex-grow-1 shadow-sm d-flex flex-column h-100">
                <div class="card-header bg-white border-bottom p-3">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Input Search -->
                            <div class="input-group input-group-lg shadow-sm">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                                <input type="text" id="searchInput" class="form-control border-start-0 ps-0" placeholder="Cari Produk Manual (Ketik Nama / Kode Produk)..." style="box-shadow: none;">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-body overflow-auto bg-light position-relative" id="productListContainer" style="max-height: calc(100vh - 250px);">
                    <!-- Loading Overlay -->
                    <div id="loadingOverlay" class="position-absolute top-0 start-0 w-100 h-100 bg-white justify-content-center align-items-center" style="z-index: 10; opacity: 0.8; display: none;">
                        <div class="spinner-border text-warning" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    
                    <div class="row g-3" id="productGrid">
                        <!-- Produk di-render via AJAX disini -->
                    </div>
                    
                    <!-- Pagination Container -->
                    <div class="d-flex justify-content-center mt-4 pb-3" id="paginationContainer">
                    </div>
                </div>
            </div>
        </div>

        <!-- Area Kanan: Keranjang (Cart) -->
        <div class="col-lg-4 d-flex flex-column" style="min-height: calc(100vh - 160px);">
            <div class="card flex-grow-1 shadow-sm d-flex flex-column h-100 border-0">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-cart3 text-theme-orange me-2"></i> Keranjang</h5>
                    <div class="d-flex align-items-center">
                        <button class="btn btn-sm btn-outline-secondary border-0 p-1 me-2 shadow-none" onclick="showDiscountModal()" title="Atur Diskon Keseluruhan" style="background: transparent;">
                            <i class="bi bi-tags-fill fs-5 text-muted"></i>
                        </button>
                        <span class="badge bg-danger rounded-pill" id="cartBadgeCount">0 Item</span>
                    </div>
                </div>
                
                <div class="card-body overflow-auto p-0 bg-white" id="cartContainer" style="max-height: calc(100vh - 360px);">
                    <ul class="list-group list-group-flush" id="cartList">
                        <!-- Item keranjang akan dirender JS disin -->
                    </ul>
                    <div id="emptyCartMsg" class="text-center text-muted p-5 mt-4">
                        <div class="bg-light rounded-circle d-inline-flex justify-content-center align-items-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-cart-x fs-1 opacity-50"></i>
                        </div>
                        <h6>Belum ada pesanan</h6>
                        <small>Pilih produk di sebelah kiri</small>
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
                    <button class="btn btn-theme-orange btn-lg w-100 py-3 fw-bold shadow-sm" id="btnCheckout" onclick="processCheckout()">
                        Proses Tagihan <i class="bi bi-check-circle ms-1"></i>
                    </button>
                    <button class="btn btn-light text-danger w-100 mt-2 fw-semibold border" onclick="clearCart()">
                        <i class="bi bi-trash"></i> Kosongkan Keranjang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Discount -->
<div class="modal fade" id="discountModal" tabindex="-1" aria-labelledby="discountModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-bottom-0">
        <h6 class="modal-title fw-bold" id="discountModalLabel"><i class="bi bi-tags text-theme-orange me-2"></i> Atur Diskon</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pt-0">
         <label class="form-label text-muted small mb-1">Nominal Diskon (Rp)</label>
         <div class="input-group mb-3">
           <span class="input-group-text bg-light border-end-0">Rp</span>
           <input type="number" class="form-control border-start-0" id="inputDiscountAmount" placeholder="0" min="0">
         </div>
         <button type="button" class="btn btn-theme-orange w-100 fw-bold" onclick="applyDiscount()">Terapkan Diskon</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Order Success -->
<div class="modal fade" id="orderSuccessModal" tabindex="-1" aria-labelledby="orderSuccessModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content border-0 shadow-lg text-center p-4">
      <div class="modal-body p-0">
        <div class="mb-3 mt-2">
            <div class="bg-success rounded-circle d-inline-flex justify-content-center align-items-center mb-3" style="width: 80px; height: 80px; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);">
                <i class="bi bi-check-lg text-white" style="font-size: 3rem;"></i>
            </div>
            <h4 class="fw-bold text-dark mb-1">Order Success!</h4>
            <p class="text-muted small mb-4">Transaksi telah berhasil diproses.</p>
        </div>
        
        <div class="d-grid gap-2">
            <!-- Tombol Cetak Struk dengan Ikon Print -->
            <button type="button" class="btn btn-dark fw-bold btn-lg" onclick="window.print()">
                <i class="bi bi-printer me-2 fs-5"></i> Cetak Struk
            </button>
            <!-- Tombol Selesai & Order Baru -->
            <button type="button" class="btn btn-light border fw-semibold mt-2" data-bs-dismiss="modal">
                Selesai & Lanjut Order Baru
            </button>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.c-pointer { cursor: pointer; }
.product-card { 
    transition: all 0.2s ease; 
}
.product-card:hover { 
    transform: translateY(-4px); 
    border-color: var(--theme-orange) !important; 
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.08) !important; 
}
#cartList .list-group-item { border-left: none; border-right: none; }
.btn-outline-secondary { border-color: #dee2e6; color: #495057; }
.btn-outline-secondary:hover { background-color: #f8f9fa; color: #212529; }

/* Custom Pagination styling */
.pagination .page-link { color: #495057; border-color: #dee2e6; outline: none !important; box-shadow: none; }
.pagination .page-item.active .page-link { background-color: var(--theme-orange); border-color: var(--theme-orange); color: #fff; }
.pagination .page-link:hover { color: var(--theme-orange); background-color: #e9ecef; }
</style>

@push('scripts')
<script>
    // Inisialisasi State Keranjang
    let cart = [];
    let currentDiscount = 0;
    
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

    // AJAX Product Loading & Pagination
    let searchTimeout = null;

    function formatNumber(num) {
        return parseInt(num).toLocaleString('id-ID');
    }

    window.loadProducts = function(page = 1) {
        let keyword = document.getElementById('searchInput').value.trim();
        const loading = document.getElementById('loadingOverlay');
        loading.style.display = 'flex';
        
        fetch(`/pos/data?page=${page}&search=${encodeURIComponent(keyword)}`)
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
                        
                        grid.innerHTML += `
                            <div class="col-6 col-md-4 col-sm-6 col-lg-3 product-item mb-3">
                                <div class="card h-100 border-0 shadow-sm c-pointer product-card" onclick="addToCart(${p.Oid}, '${cleanName}', ${p.Price})">
                                    <div class="card-body text-center p-3 d-flex flex-column justify-content-between">
                                        <div class="mb-3 mt-1">
                                            <div class="bg-theme-orange-bg rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="bi bi-box-seam text-theme-orange fs-4"></i>
                                            </div>
                                        </div>
                                        <h6 class="card-title fw-bold text-dark mb-1 lh-sm" style="font-size: 0.9rem;">${p.Name}</h6>
                                        <p class="text-muted mb-2" style="font-size: 0.75rem;">${p.Code}</p>
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
             // Smart truncation
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
            loadProducts(1); // Mulai dari hal 1 setiap pencarian baru
        }, 300);
    });

    // Initial Load at First Boot
    document.addEventListener('DOMContentLoaded', function() {
        loadProducts(1);
    });

    // Menambah data produk ke keranjang
    window.addToCart = function(id, name, price) {
        let existingItem = cart.find(item => item.id == id);
        if(existingItem) {
            existingItem.qty += 1;
        } else {
            cart.push({ id, name, price, qty: 1 });
        }
        renderCart();
    }

    // Mengubah Qty Produk dari Cart
    window.updateQty = function(id, change) {
        let item = cart.find(i => i.id == id);
        if(item) {
            item.qty += change;
            if(item.qty <= 0) {
                // Hapus produk jika qty minus/0
                cart = cart.filter(i => i.id != id); 
            }
            renderCart();
        }
    }

    // Mengosongkan Cart Seluruhnya
    window.clearCart = function() {
        if(cart.length > 0) {
            if(confirm('Yakin ingin mengosongkan keranjang?')) {
                cart = [];
                currentDiscount = 0; // Reset diskon
                renderCart();
            }
        }
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
                <li class="list-group-item p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div style="max-width: 65%;">
                            <h6 class="mb-0 fw-bold text-dark lh-sm">${item.name}</h6>
                            <small class="text-muted">${formatRupiah(item.price)}</small>
                        </div>
                        <span class="text-theme-teal fw-bold">${formatRupiah(subtotal)}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <!-- Controls QTY -->
                        <div class="input-group input-group-sm" style="width: 110px;">
                            <button class="btn btn-outline-secondary" type="button" onclick="updateQty(${item.id}, -1)">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="text" class="form-control text-center px-1 bg-white" value="${item.qty}" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="updateQty(${item.id}, 1)">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
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

    // Instance Modal Order Success
    const successModal = document.getElementById('orderSuccessModal') ? new bootstrap.Modal(document.getElementById('orderSuccessModal')) : null;

    // Simulasi Tombol Checkout
    window.processCheckout = function() {
        if(cart.length === 0) {
            alert('Keranjang masih kosong! Silakan pilih produk terlebih dahulu.');
            return;
        }
        
        // Memunculkan Pop-up Alert Order Success
        if(successModal) successModal.show();
        
        // Eksekusi API/Logika Backend bisa ditaruh di sini nantinya.
        
        // Reset setelah pop-up muncul, keranjang dikosongkan agar siap order berikutnya
        cart = [];
        currentDiscount = 0;
        renderCart();
    }
</script>
@endpush
@endsection
