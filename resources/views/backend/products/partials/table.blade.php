<!-- Action Toolbar Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <!-- Search Bar -->
    <div class="input-group" style="width: 300px;">
        <span class="input-group-text bg-white border-end-0 text-muted" id="search-addon" style="border-radius: 8px 0 0 8px;">
            <i class="bi bi-search"></i>
        </span>
        <input type="text" class="form-control border-start-0" placeholder="Search products..." aria-label="Search products" aria-describedby="search-addon" style="border-radius: 0 8px 8px 0; font-size: 0.85rem; box-shadow: none; border-color: #dee2e6;">
    </div>
    
    <!-- Filters / Actions -->
    <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary bg-white text-muted d-flex align-items-center px-3 py-2" style="font-size: 0.85rem; border-color: #e2e8f0; border-radius: 8px;">
            <i class="bi bi-funnel me-2"></i> Filter
        </button>
        <button class="btn btn-outline-secondary bg-white text-muted d-flex align-items-center px-3 py-2" style="font-size: 0.85rem; border-color: #e2e8f0; border-radius: 8px;">
            <i class="bi bi-download me-2"></i> Export
        </button>
    </div>
</div>

<!-- Table Section -->
<div class="table-responsive">
    <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
        <thead style="background-color: #f8fafc;">
            <tr>
                <th scope="col" class="py-3 text-muted fw-semibold border-bottom-0 rounded-start" style="width: 50px;">
                    <div class="form-check m-0">
                        <input class="form-check-input" type="checkbox" value="" id="selectAll">
                    </div>
                </th>
                <th scope="col" class="py-3 text-muted fw-semibold border-bottom-0">Product Image</th>
                <th scope="col" class="py-3 text-muted fw-semibold border-bottom-0">Product Name</th>
                <th scope="col" class="py-3 text-muted fw-semibold border-bottom-0">Category</th>
                <th scope="col" class="py-3 text-muted fw-semibold border-bottom-0">Price</th>
                <th scope="col" class="py-3 text-muted fw-semibold border-bottom-0">Stock</th>
                <th scope="col" class="py-3 text-muted fw-semibold border-bottom-0">Status</th>
                <th scope="col" class="py-3 text-muted fw-semibold border-bottom-0 rounded-end text-end">Actions</th>
            </tr>
        </thead>
        <tbody class="border-top-0">
            <!-- Dummy Data Row 1 -->
            <tr>
                <td class="py-3">
                    <div class="form-check m-0">
                        <input class="form-check-input" type="checkbox" value="">
                    </div>
                </td>
                <td class="py-3">
                    <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 48px; height: 48px;">
                        <i class="bi bi-image fs-5"></i>
                    </div>
                </td>
                <td class="py-3">
                    <div class="fw-bold text-dark">Apple iPhone 15 Pro</div>
                    <small class="text-muted">SKU: IPH-15P-256</small>
                </td>
                <td class="py-3 text-muted">Smartphones</td>
                <td class="py-3 fw-medium text-dark">$999.00</td>
                <td class="py-3">
                    <span class="fw-medium text-dark">45</span> <small class="text-muted">Pcs</small>
                </td>
                <td class="py-3">
                    <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill fw-medium">Active</span>
                </td>
                <td class="py-3 text-end">
                    <button class="btn btn-sm btn-light text-muted me-1" title="View"><i class="bi bi-eye"></i></button>
                    <button class="btn btn-sm btn-light text-primary me-1" title="Edit"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-sm btn-light text-danger" title="Delete"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
            
            <!-- Dummy Data Row 2 -->
            <tr>
                <td class="py-3">
                    <div class="form-check m-0">
                        <input class="form-check-input" type="checkbox" value="">
                    </div>
                </td>
                <td class="py-3">
                    <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 48px; height: 48px;">
                        <i class="bi bi-image fs-5"></i>
                    </div>
                </td>
                <td class="py-3">
                    <div class="fw-bold text-dark">Samsung Galaxy S23 Ultra</div>
                    <small class="text-muted">SKU: SAM-S23U-512</small>
                </td>
                <td class="py-3 text-muted">Smartphones</td>
                <td class="py-3 fw-medium text-dark">$1,199.00</td>
                <td class="py-3">
                    <span class="fw-medium text-dark">12</span> <small class="text-muted">Pcs</small>
                </td>
                <td class="py-3">
                    <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1 rounded-pill fw-medium">Low Stock</span>
                </td>
                <td class="py-3 text-end">
                    <button class="btn btn-sm btn-light text-muted me-1" title="View"><i class="bi bi-eye"></i></button>
                    <button class="btn btn-sm btn-light text-primary me-1" title="Edit"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-sm btn-light text-danger" title="Delete"><i class="bi bi-trash"></i></button>
                </td>
            </tr>

            <!-- Dummy Data Row 3 -->
            <tr>
                <td class="py-3 border-bottom-0">
                    <div class="form-check m-0">
                        <input class="form-check-input" type="checkbox" value="">
                    </div>
                </td>
                <td class="py-3 border-bottom-0">
                    <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 48px; height: 48px;">
                        <i class="bi bi-image fs-5"></i>
                    </div>
                </td>
                <td class="py-3 border-bottom-0">
                    <div class="fw-bold text-dark">MacBook Pro 14" M3 Max</div>
                    <small class="text-muted">SKU: MAC-14M3-1TB</small>
                </td>
                <td class="py-3 border-bottom-0 text-muted">Laptops</td>
                <td class="py-3 border-bottom-0 fw-medium text-dark">$3,199.00</td>
                <td class="py-3 border-bottom-0">
                    <span class="fw-medium text-dark">0</span> <small class="text-muted">Pcs</small>
                </td>
                <td class="py-3 border-bottom-0">
                    <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 rounded-pill fw-medium">Out of Stock</span>
                </td>
                <td class="py-3 border-bottom-0 text-end">
                    <button class="btn btn-sm btn-light text-muted me-1" title="View"><i class="bi bi-eye"></i></button>
                    <button class="btn btn-sm btn-light text-primary me-1" title="Edit"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-sm btn-light text-danger" title="Delete"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Pagination Section -->
<div class="d-flex justify-content-between align-items-center mt-4">
    <div class="text-muted" style="font-size: 0.85rem;">
        Showing <span class="fw-medium text-dark">1</span> to <span class="fw-medium text-dark">3</span> of <span class="fw-medium text-dark">24</span> results
    </div>
    <nav aria-label="Page navigation">
        <ul class="pagination pagination-sm mb-0">
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
            </li>
            <li class="page-item active" style="--bs-pagination-active-bg: var(--theme-orange); --bs-pagination-active-border-color: var(--theme-orange);"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link text-muted" href="#">2</a></li>
            <li class="page-item"><a class="page-link text-muted" href="#">3</a></li>
            <li class="page-item">
                <a class="page-link text-muted" href="#">Next</a>
            </li>
        </ul>
    </nav>
</div>
