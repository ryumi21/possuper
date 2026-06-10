<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            
            <!-- Modal Header -->
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <div>
                    <h5 class="modal-title fw-bold text-dark" id="addProductModalLabel">Add New Product</h5>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">Fill in the details to add a new product to your inventory.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.8rem;"></button>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body p-4">
                <form action="#" method="POST" enctype="multipart/form-data">
                    <div class="row g-4">
                        <!-- Left Column: Image Upload & Basics -->
                        <div class="col-md-5">
                            <div class="mb-3">
                                <label class="form-label fw-medium text-dark" style="font-size: 0.85rem;">Product Image</label>
                                <div class="border border-dashed rounded text-center p-4" style="border-color: #cbd5e1; background-color: #f8fafc;">
                                    <i class="bi bi-cloud-arrow-up fs-1 text-muted mb-2"></i>
                                    <p class="text-muted mb-1" style="font-size: 0.85rem;">Drag and drop or <span class="text-primary text-decoration-underline" style="cursor: pointer;">choose a file</span></p>
                                    <small class="text-muted" style="font-size: 0.75rem;">JPG, PNG up to 2MB</small>
                                    <input type="file" class="d-none">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="productCategory" class="form-label fw-medium text-dark" style="font-size: 0.85rem;">Category</label>
                                <select class="form-select form-select-sm" id="productCategory" style="border-radius: 6px;">
                                    <option selected disabled>Select a category</option>
                                    <option value="1">Smartphones</option>
                                    <option value="2">Laptops</option>
                                    <option value="3">Accessories</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="productStatus" class="form-label fw-medium text-dark" style="font-size: 0.85rem;">Status</label>
                                <select class="form-select form-select-sm" id="productStatus" style="border-radius: 6px;">
                                    <option value="active" selected>Active</option>
                                    <option value="draft">Draft</option>
                                    <option value="archived">Archived</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Right Column: Product Details -->
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label for="productName" class="form-label fw-medium text-dark" style="font-size: 0.85rem;">Product Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="productName" placeholder="e.g. Apple iPhone 15 Pro" style="border-radius: 6px;">
                            </div>
                            
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label for="productSKU" class="form-label fw-medium text-dark" style="font-size: 0.85rem;">SKU <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" id="productSKU" placeholder="e.g. IPH-15P-256" style="border-radius: 6px;">
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="productStock" class="form-label fw-medium text-dark" style="font-size: 0.85rem;">Stock <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control form-control-sm" id="productStock" placeholder="0" style="border-radius: 6px;">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label for="productPrice" class="form-label fw-medium text-dark" style="font-size: 0.85rem;">Selling Price ($) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control form-control-sm" id="productPrice" placeholder="0.00" style="border-radius: 6px;">
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="costPrice" class="form-label fw-medium text-dark" style="font-size: 0.85rem;">Cost Price ($)</label>
                                    <input type="number" class="form-control form-control-sm" id="costPrice" placeholder="0.00" style="border-radius: 6px;">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="productDescription" class="form-label fw-medium text-dark" style="font-size: 0.85rem;">Description</label>
                                <textarea class="form-control form-control-sm" id="productDescription" rows="4" placeholder="Brief product description..." style="border-radius: 6px;"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer border-top-0 pt-0 pb-4 px-4 d-flex justify-content-end">
                <button type="button" class="btn btn-light px-4 py-2 text-muted fw-medium border" data-bs-dismiss="modal" style="font-size: 0.85rem; border-radius: 8px;">Cancel</button>
                <button type="button" class="btn btn-theme-orange px-4 py-2" style="font-size: 0.85rem; border-radius: 8px;">Save Product</button>
            </div>
        </div>
    </div>
</div>

<style>
    .border-dashed {
        border-style: dashed !important;
    }
</style>
