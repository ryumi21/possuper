@extends('layouts.backend.app')

@section('title', 'Tri Fusion - Menu & Item')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 fw-bold text-dark" style="font-size: 1.5rem;">Menu & Item List</h3>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Manage your store menu and items via API</p>
        </div>
        <div>
            <button type="button" data-bs-toggle="modal" data-bs-target="#createFoodModal" class="btn btn-warning text-white fw-bold d-flex align-items-center px-3 py-2" style="font-size: 0.85rem; border-color: #e2e8f0; border-radius: 8px; background-color: var(--theme-orange); border: none;">
                <i class="bi bi-plus-circle me-2"></i> Add New Menu/Item
            </button>
        </div>
    </div>

    <div id="alertContainer"></div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 premium-table-grid" id="foodsTable">
                    <thead class="table-light text-muted">
                        <tr style="font-size: 0.85rem;">
                            <th class="ps-4">Code</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Bahan Baku</th>
                            <th>Price</th>
                            <th>Harga Modal / HPP</th>
                            <th>Sell Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody id="foodsTableBody">
                        <tr>
                            <td colspan="10" class="text-center py-4 text-muted">Loading data from API...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit Food Modal -->
<div class="modal fade" id="createFoodModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="modalTitle">Add New Menu/Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="foodForm">
                    <input type="hidden" id="foodOid">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Code *</label>
                            <input type="text" class="form-control bg-white border" id="Code" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Name *</label>
                            <input type="text" class="form-control bg-white border" id="Name" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Type</label>
                            <input type="text" class="form-control bg-white border" id="Type">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Price *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border text-muted">Rp</span>
                                <input type="number" class="form-control bg-white border" id="Price" step="1" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Buy Price *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border text-muted">Rp</span>
                                <input type="number" class="form-control bg-white border" id="BuyPrice" step="1" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Sell Price *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border text-muted">Rp</span>
                                <input type="number" class="form-control bg-white border" id="SellPrice" step="1" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Stock Status *</label>
                            <select class="form-select bg-white border" id="IsStock" required>
                                <option value="1">In Stock</option>
                                <option value="0">Out of Stock</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Active Status *</label>
                            <select class="form-select bg-white border" id="IsActive" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="col-12"><hr class="my-2 text-muted opacity-25"></div>
                        
                        <div class="col-12">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">
                                        <i class="bi bi-layers me-2 text-warning"></i>Product Detail (Bahan Baku)
                                    </h6>
                                    <small class="text-muted">Tambahkan bahan baku yang digunakan untuk membuat produk ini</small>
                                </div>
                                <button type="button" id="addMaterialBtn"
                                    class="btn btn-sm fw-semibold"
                                    style="background-color: var(--theme-orange-bg); color: var(--theme-orange); border: 1px dashed var(--theme-orange); border-radius: 8px; font-size: 0.8rem;">
                                    <i class="bi bi-plus-circle me-1"></i> Add Material
                                </button>
                            </div>

                            {{-- Column headers (hidden until a row is added) --}}
                            <div id="materialHeader" class="d-none">
                                <div class="row gx-2 mb-1 px-1">
                                    <div class="col-4">
                                        <small class="text-muted fw-semibold text-uppercase" style="font-size:0.7rem; letter-spacing:0.5px;">Bahan Baku</small>
                                    </div>
                                    <div class="col-3">
                                        <small class="text-muted fw-semibold text-uppercase" style="font-size:0.7rem; letter-spacing:0.5px;">Jumlah Bahan</small>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted fw-semibold text-uppercase" style="font-size:0.7rem; letter-spacing:0.5px;">Item Unit</small>
                                    </div>
                                    <div class="col-1"></div>
                                </div>
                            </div>

                            {{-- Dynamic rows --}}
                            <div id="materialRows"></div>
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn text-white fw-bold px-4 py-2" style="background-color: var(--theme-orange); border-radius: 8px;">
                                Save Menu/Item
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<template id="materialRowTemplate">
    <div class="material-row row gx-2 mb-2 align-items-center">
        <div class="col-4">
            <select class="form-select form-select-sm bg-white border mat-name">
                <option value="">-- Pilih Bahan --</option>
                @foreach($materials as $m)
                    <option value="{{ $m->Name }}">{{ $m->Name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-3">
            <input type="number" class="form-control form-control-sm bg-white border mat-qty"
                step="0.01" min="0" placeholder="0.00">
        </div>
        <div class="col-4">
            <select class="form-select form-select-sm bg-white border mat-unit">
                <option value="">-- Satuan --</option>
                @foreach($units as $u)
                    <option value="{{ $u->Oid }}">{{ $u->Name }} ({{ $u->Code }})</option>
                @endforeach
            </select>
        </div>
        <div class="col-1 text-center">
            <button type="button" class="remove-material-btn"
                style="color:#ef4444; background:rgba(239,68,68,0.06); border:1px solid rgba(239,68,68,0.15);
                       border-radius:6px; width:28px; height:28px; padding:0;
                       display:inline-flex; align-items:center; justify-content:center;">
                <i class="bi bi-x" style="font-size:0.9rem;"></i>
            </button>
        </div>
    </div>
</template>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const apiBase = '/api/foods';
    const tbody = document.getElementById('foodsTableBody');
    const form = document.getElementById('foodForm');
    const modal = new bootstrap.Modal(document.getElementById('createFoodModal'));
    let currentMode = 'create';

    // ===================== MATERIAL ROWS =====================
    const addMaterialBtn = document.getElementById('addMaterialBtn');
    const materialRows   = document.getElementById('materialRows');
    const materialHeader = document.getElementById('materialHeader');
    const rowTemplate    = document.getElementById('materialRowTemplate');

    function addMaterialRow(name = '', qty = '', unitId = '') {
        materialHeader.classList.remove('d-none');

        const clone = rowTemplate.content.cloneNode(true);
        const row   = clone.querySelector('.material-row');

        if (name)   row.querySelector('.mat-name').value = name;
        if (qty)    row.querySelector('.mat-qty').value  = qty;
        if (unitId) row.querySelector('.mat-unit').value = String(unitId);

        row.querySelector('.remove-material-btn').addEventListener('click', () => {
            row.remove();
            if (materialRows.children.length === 0) {
                materialHeader.classList.add('d-none');
            }
        });

        materialRows.appendChild(row);
    }

    addMaterialBtn.addEventListener('click', () => addMaterialRow());

    function clearMaterialRows() {
        materialRows.innerHTML = '';
        materialHeader.classList.add('d-none');
    }

    function getMaterialsPayload() {
        const result = [];
        materialRows.querySelectorAll('.material-row').forEach(row => {
            const name   = row.querySelector('.mat-name').value;
            const qty    = row.querySelector('.mat-qty').value;
            const unitId = row.querySelector('.mat-unit').value;
            if (name) result.push({ name, qty: qty || 0, unit_id: unitId || null });
        });
        return result;
    }

    // ===================== FETCH & RENDER TABLE =====================
    function fetchFoods() {
        fetch(apiBase)
            .then(res => res.json())
            .then(res => {
                const foods = res.data;
                tbody.innerHTML = '';
                if(foods.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="10" class="text-center py-4 text-muted">No menu items found.</td></tr>';
                    return;
                }
                
                foods.forEach(p => {
                    const price = new Intl.NumberFormat('id-ID').format(p.Price || 0);
                    const buyPrice = new Intl.NumberFormat('id-ID').format(p.BuyPrice || 0);
                    const sellPrice = new Intl.NumberFormat('id-ID').format(p.SellPrice || 0);
                    
                    // Material badges
                    let materialBadges = '-';
                    if (p.product_materials && p.product_materials.length > 0) {
                        materialBadges = p.product_materials.map(m => {
                            const unitLabel = m.unit
                                ? `<span class="text-muted" style="font-size:0.7rem;"> (${m.unit.Code})</span>`
                                : '';
                            return `<span class="badge bg-light border text-dark fw-medium me-1 mb-1"
                                        style="font-size:0.72rem; padding:3px 7px; border-radius:5px;">
                                        ${m.Name} × ${m.Create_Cost}${unitLabel}
                                    </span>`;
                        }).join('');
                    }
                    
                    const stockHtml = p.IsStock ? 
                        '<span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1"><i class="bi bi-check-circle me-1"></i> In Stock</span>' : 
                        '<span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2 py-1"><i class="bi bi-x-circle me-1"></i> Out of Stock</span>';
                        
                    const statusHtml = p.IsActive ? 
                        '<span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-1">Active</span>' : 
                        '<span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2 py-1">Inactive</span>';

                    const row = document.createElement('tr');
                    row.style.fontSize = '0.9rem';
                    row.innerHTML = `
                        <td class="ps-4 fw-medium text-dark">${p.Code}</td>
                        <td class="text-muted">${p.Name}</td>
                        <td><span class="badge bg-light text-secondary fw-semibold" style="font-size: 0.75rem; padding: 4px 8px; border-radius: 6px;">${p.Type || '-'}</span></td>
                        <td style="max-width:200px;">${materialBadges}</td>
                        <td class="fw-bold text-dark">Rp ${price}</td>
                        <td class="fw-bold text-dark">Rp ${buyPrice}</td>
                        <td class="fw-bold text-dark">Rp ${sellPrice}</td>
                        <td>${stockHtml}</td>
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
            });
    }

    // ===================== FORM SUBMIT =====================
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const payload = {
            Code: document.getElementById('Code').value,
            Name: document.getElementById('Name').value,
            Type: document.getElementById('Type').value,
            Price: document.getElementById('Price').value,
            BuyPrice: document.getElementById('BuyPrice').value,
            SellPrice: document.getElementById('SellPrice').value,
            IsStock: document.getElementById('IsStock').value,
            IsActive: document.getElementById('IsActive').value,
            materials: getMaterialsPayload()
        };

        const oid = document.getElementById('foodOid').value;
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
                fetchFoods();
                showAlert('success', data.message);
            } else {
                showAlert('danger', data.message || 'Error saving data.');
            }
        })
        .catch(() => showAlert('danger', 'Server error occurred.'));
    });

    // ===================== DELETE & EDIT =====================
    tbody.addEventListener('click', function(e) {
        const btnDelete = e.target.closest('.btn-delete');
        if (btnDelete) {
            if(confirm('Are you sure you want to delete this menu/item?')) {
                fetch(`${apiBase}/${btnDelete.dataset.id}`, { method: 'DELETE' })
                .then(res => res.json())
                .then(data => {
                    fetchFoods();
                    showAlert('success', data.message);
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
                document.getElementById('modalTitle').textContent = 'Edit Menu/Item';
                document.getElementById('foodOid').value = p.Oid;
                document.getElementById('Code').value = p.Code;
                document.getElementById('Name').value = p.Name;
                document.getElementById('Type').value = p.Type || '';
                document.getElementById('Price').value = p.Price;
                document.getElementById('BuyPrice').value = p.BuyPrice;
                document.getElementById('SellPrice').value = p.SellPrice;
                document.getElementById('IsStock').value = p.IsStock;
                document.getElementById('IsActive').value = p.IsActive;
                
                // Load existing material rows
                clearMaterialRows();
                if (p.product_materials && p.product_materials.length > 0) {
                    p.product_materials.forEach(m => addMaterialRow(m.Name, m.Create_Cost, m.ItemUnit));
                }

                currentMode = 'edit';
                modal.show();
            });
        }
    });

    // ===================== RESET ON ADD =====================
    document.querySelector('[data-bs-target="#createFoodModal"]').addEventListener('click', () => {
        currentMode = 'create';
        document.getElementById('modalTitle').textContent = 'Add New Menu/Item';
        form.reset();
        document.getElementById('foodOid').value = '';
        clearMaterialRows();
    });

    function showAlert(type, message) {
        const alertHtml = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;
        document.getElementById('alertContainer').innerHTML = alertHtml;
        setTimeout(() => document.getElementById('alertContainer').innerHTML = '', 3000);
    }

    fetchFoods();
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

    /* Material row entry animation */
    .material-row { animation: slideInRow 0.18s ease-out; }
    @keyframes slideInRow {
        from { opacity: 0; transform: translateY(-6px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .remove-material-btn:hover {
        background: rgba(239,68,68,0.12) !important;
        border-color: rgba(239,68,68,0.3) !important;
    }
</style>
@endsection
