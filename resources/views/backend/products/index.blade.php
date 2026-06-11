@extends('layouts.backend.app')

@section('title', 'Tri Fusion')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 fw-bold text-dark" style="font-size: 1.5rem;">Products list</h3>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Manage your store products</p>
        </div>
        <div>
            <button type="button" data-bs-toggle="modal" data-bs-target="#createProductModal" class="btn btn-warning text-white fw-bold d-flex align-items-center px-3 py-2" style="font-size: 0.85rem; border-color: #e2e8f0; border-radius: 8px; background-color: var(--theme-orange); border: none;">
                <i class="bi bi-plus-circle me-2"></i> Add New Product
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" id="successAlert" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <script>
            setTimeout(function() {
                var alertNode = document.getElementById('successAlert');
                if (alertNode) {
                    var bsAlert = new bootstrap.Alert(alertNode);
                    bsAlert.close();
                }
            }, 2000);
        </script>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 premium-table-grid">
                    <thead class="table-light text-muted">
                        <tr style="font-size: 0.85rem;">
                            <th class="ps-4">Code</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr style="font-size: 0.9rem;">
                                <td class="ps-4 fw-medium text-dark">{{ $product->Code }}</td>
                                <td class="text-muted">{{ $product->Name }}</td>
                                <td><span class="badge bg-light text-secondary fw-semibold" style="font-size: 0.75rem; padding: 4px 8px; border-radius: 6px;">{{ $product->Type }}</span></td>
                                <td class="fw-bold text-dark">Rp {{ number_format($product->Price, 0, ',', '.') }}</td>
                                <td>
                                    @if ($product->IsStock)
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1"><i class="bi bi-check-circle me-1"></i> In Stock</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2 py-1"><i class="bi bi-x-circle me-1"></i> Out of Stock</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($product->IsActive)
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-1">Active</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2 py-1">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('products.edit', $product->Oid) }}" class="premium-btn-icon" data-bs-toggle="tooltip" title="Edit">
                                            <i class="bi bi-pencil text-warning"></i>
                                        </a>
                                        <form action="{{ route('products.destroy', $product->Oid) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="premium-btn-icon delete-btn" data-bs-toggle="tooltip" title="Delete">
                                                <i class="bi bi-trash text-danger"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create Product Modal -->
<div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="createProductModalLabel">Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="Code" class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Product Code *</label>
                            <input type="text" class="form-control bg-white border" id="Code" name="Code" required value="{{ old('Code') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="Name" class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Product Name *</label>
                            <input type="text" class="form-control bg-white border" id="Name" name="Name" required value="{{ old('Name') }}">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="Type" class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Product Type</label>
                            <input type="text" class="form-control bg-white border" id="Type" name="Type" value="{{ old('Type') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="Price" class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Price *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border text-muted">$</span>
                                <input type="number" class="form-control bg-white border" id="Price" name="Price" step="1" required value="{{ old('Price') }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="IsStock" class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Stock Status *</label>
                            <select class="form-select bg-white border" id="IsStock" name="IsStock" required>
                                <option value="1" {{ old('IsStock') == '1' ? 'selected' : '' }}>In Stock</option>
                                <option value="0" {{ old('IsStock') == '0' ? 'selected' : '' }}>Out of Stock</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="IsActive" class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Active Status *</label>
                            <select class="form-select bg-white border" id="IsActive" name="IsActive" required>
                                <option value="1" {{ old('IsActive') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('IsActive') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn text-white fw-bold px-4 py-2" style="background-color: var(--theme-orange); border-radius: 8px;">
                                Save Product
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if($errors->any())
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var createModal = new bootstrap.Modal(document.getElementById('createProductModal'));
        createModal.show();
    });
</script>
@endif

<style>
    .premium-table-grid th, .premium-table-grid td {
        border-bottom: 1px solid #f1f5f9 !important;
        vertical-align: middle;
        color: #475569;
        padding: 14px 16px !important;
        border-top: none !important;
    }
    .premium-table-grid th {
        background-color: #f8fafc !important;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e2e8f0 !important;
    }
    .premium-table-grid tbody tr {
        transition: background-color 0.2s ease;
    }
    .premium-table-grid tbody tr:hover {
        background-color: rgba(255, 159, 67, 0.02) !important;
    }
    
    .premium-btn-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
        background-color: #ffffff;
        text-decoration: none;
    }
    .premium-btn-icon:hover {
        transform: translateY(-2px);
        background-color: var(--theme-orange-bg) !important;
        border-color: rgba(255, 159, 67, 0.2) !important;
        box-shadow: var(--premium-shadow-sm);
    }
    .premium-btn-icon.delete-btn:hover {
        background-color: rgba(239, 68, 68, 0.05) !important;
        border-color: rgba(239, 68, 68, 0.1) !important;
    }
    .premium-btn-icon.delete-btn:hover i {
        color: #dc2626 !important;
    }
</style>

@endsection
