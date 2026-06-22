@extends('layouts.backend.app')

@section('title', 'Tri Fusion - Raw Materials')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 fw-bold text-dark" style="font-size: 1.5rem;">Raw Materials</h3>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Manage your store raw materials via API</p>
        </div>
        <div>
            <button type="button" data-bs-toggle="modal" data-bs-target="#createMaterialModal" class="btn btn-warning text-white fw-bold d-flex align-items-center px-3 py-2" style="font-size: 0.85rem; border-color: #e2e8f0; border-radius: 8px; background-color: var(--theme-orange); border: none;">
                <i class="bi bi-plus-circle me-2"></i> Add Raw Material
            </button>
        </div>
    </div>

    <div id="alertContainer"></div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 premium-table-grid" id="materialsTable">
                    <thead class="table-light text-muted">
                        <tr style="font-size: 0.85rem;">
                            <th class="ps-4">Name</th>
                            <th>Category</th>
                            <th>Unit</th>
                            <th>Current Stock</th>
                            <th>Min Stock</th>
                            <th>Purchase Price</th>
                            <th>Harga Satuan</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody id="materialsTableBody">
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">Loading data from API...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit Material Modal -->
<div class="modal fade" id="createMaterialModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="modalTitle">Add Raw Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="materialForm">
                    <input type="hidden" id="materialOid">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Material Name *</label>
                            <input type="text" class="form-control bg-white border" id="Name" required placeholder="e.g. Flour, Sugar, Milk">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Category</label>
                            <input type="text" class="form-control bg-white border" id="Category" placeholder="e.g. Baking, Dairy, Spices">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Unit *</label>
                            <select class="form-select bg-white border" id="unit" required>
                                <option value="">Select Unit</option>
                                @foreach($units as $u)
                                    <option value="{{ $u->Oid }}">{{ $u->Name }} ({{ $u->Code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Purchase Price</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border text-muted">Rp</span>
                                <input type="number" class="form-control bg-white border" id="purchase_price" step="1" placeholder="e.g. 15000">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Harga Satuan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border text-muted">Rp</span>
                                <input type="number" class="form-control bg-white border" id="unit_price" step="1" placeholder="e.g. 1500">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Current Stock</label>
                            <input type="number" class="form-control bg-white border" id="current_stock" step="0.01" placeholder="e.g. 10.50">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Minimum Stock Alert</label>
                            <input type="number" class="form-control bg-white border" id="minimum_stock" step="0.01" placeholder="e.g. 2.00">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Status</label>
                            <select class="form-select bg-white border" id="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn text-white fw-bold px-4 py-2" style="background-color: var(--theme-orange); border-radius: 8px;">
                                Save Material
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const apiBase = '/api/rawmaterials';
    const tbody = document.getElementById('materialsTableBody');
    const form = document.getElementById('materialForm');
    const modal = new bootstrap.Modal(document.getElementById('createMaterialModal'));
    let currentMode = 'create';

    // Fetch Materials via API
    function fetchMaterials() {
        fetch(apiBase)
            .then(res => res.json())
            .then(res => {
                const materials = res.data;
                tbody.innerHTML = '';
                if(materials.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="9" class="text-center py-4 text-muted">No raw materials found.</td></tr>';
                    return;
                }
                
                materials.forEach(p => {
                    const price = new Intl.NumberFormat('id-ID').format(p.purchase_price || 0);
                    const unitName = p.item_unit ? `${p.item_unit.Name} (${p.item_unit.Code})` : `-`;
                    const currentStock = parseFloat(p.current_stock || 0).toFixed(2);
                    const minStock = parseFloat(p.minimum_stock || 0).toFixed(2);

                    const unitPriceVal = parseFloat(p.unit_price || 0);
                    const unitPrice = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 2 }).format(unitPriceVal);

                    const statusHtml = p.status === 'active' ? 
                        '<span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1">Active</span>' : 
                        '<span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2 py-1">Inactive</span>';

                    // Highlight row if stock falls below minimum stock
                    const isLowStock = parseFloat(p.current_stock || 0) <= parseFloat(p.minimum_stock || 0);
                    const stockBadge = isLowStock ? 
                        `<span class="badge bg-danger bg-opacity-10 text-danger rounded px-2 py-1 fw-bold"><i class="bi bi-exclamation-triangle-fill me-1"></i> ${currentStock}</span>` :
                        `<span class="badge bg-light text-dark rounded px-2 py-1">${currentStock}</span>`;

                    const row = document.createElement('tr');
                    row.style.fontSize = '0.9rem';
                    row.innerHTML = `
                        <td class="ps-4 fw-medium text-dark">${p.Name}</td>
                        <td><span class="badge bg-light text-secondary fw-semibold" style="font-size: 0.75rem; padding: 4px 8px; border-radius: 6px;">${p.Category || '-'}</span></td>
                        <td class="text-muted">${unitName}</td>
                        <td>${stockBadge}</td>
                        <td class="text-muted">${minStock}</td>
                        <td class="fw-bold text-dark">Rp ${price}</td>
                        <td class="fw-semibold text-dark">Rp ${unitPrice}</td>
                        <td>${statusHtml}</td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <button class="premium-btn-icon btn-edit" data-id="${p.Oid}" title="Edit"><i class="bi bi-pencil text-warning"></i></button>
                                <button class="premium-btn-icon delete-btn btn-delete" data-id="${p.Oid}" title="Delete"><i class="bi bi-trash text-danger"></i></button>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            })
            .catch(err => {
                console.error(err);
                tbody.innerHTML = '<tr><td colspan="9" class="text-center py-4 text-danger">Failed to fetch data from server.</td></tr>';
            });
    }

    // Submit form via API
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const payload = {
            Name: document.getElementById('Name').value,
            Category: document.getElementById('Category').value,
            unit: document.getElementById('unit').value,
            purchase_price: document.getElementById('purchase_price').value || 0,
            unit_price: document.getElementById('unit_price').value || 0,
            current_stock: document.getElementById('current_stock').value || 0,
            minimum_stock: document.getElementById('minimum_stock').value || 0,
            status: document.getElementById('status').value
        };

        const oid = document.getElementById('materialOid').value;
        const method = currentMode === 'create' ? 'POST' : 'PUT';
        const url = currentMode === 'create' ? apiBase : `${apiBase}/${oid}`;

        fetch(url, {
            method: method,
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                modal.hide();
                fetchMaterials();
                showAlert('success', data.message);
            } else {
                showAlert('danger', data.message || 'Error saving data.');
            }
        })
        .catch(err => {
            console.error(err);
            showAlert('danger', 'Server error occurred.');
        });
    });

    // Delete and Edit Delegation
    tbody.addEventListener('click', function(e) {
        const btnDelete = e.target.closest('.btn-delete');
        if (btnDelete) {
            if(confirm('Are you sure you want to delete this raw material?')) {
                fetch(`${apiBase}/${btnDelete.dataset.id}`, { method: 'DELETE' })
                .then(res => res.json())
                .then(data => {
                    fetchMaterials();
                    showAlert('success', data.message);
                })
                .catch(err => {
                    console.error(err);
                    showAlert('danger', 'Failed to delete raw material.');
                });
            }
            return;
        }

        const btnEdit = e.target.closest('.btn-edit');
        if (btnEdit) {
            fetch(`${apiBase}/${btnEdit.dataset.id}`)
            .then(res => res.json())
            .then(res => {
                const p = res.data;
                document.getElementById('modalTitle').textContent = 'Edit Raw Material';
                document.getElementById('materialOid').value = p.Oid;
                document.getElementById('Name').value = p.Name;
                document.getElementById('Category').value = p.Category || '';
                document.getElementById('unit').value = p.unit;
                document.getElementById('purchase_price').value = p.purchase_price;
                document.getElementById('unit_price').value = p.unit_price || 0;
                document.getElementById('current_stock').value = p.current_stock;
                document.getElementById('minimum_stock').value = p.minimum_stock;
                document.getElementById('status').value = p.status || 'active';
                currentMode = 'edit';
                modal.show();
            })
            .catch(err => {
                console.error(err);
                showAlert('danger', 'Failed to fetch material details.');
            });
        }
    });

    // Reset form on open
    document.querySelector('[data-bs-target="#createMaterialModal"]').addEventListener('click', () => {
        currentMode = 'create';
        document.getElementById('modalTitle').textContent = 'Add Raw Material';
        form.reset();
        document.getElementById('materialOid').value = '';
        document.getElementById('status').value = 'active';
    });

    function showAlert(type, message) {
        const alertHtml = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;
        document.getElementById('alertContainer').innerHTML = alertHtml;
        setTimeout(() => document.getElementById('alertContainer').innerHTML = '', 3000);
    }

    // Initial Load
    fetchMaterials();
});
</script>

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
