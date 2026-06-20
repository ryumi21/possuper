@extends('layouts.backend.app')

@section('title', 'Tri Fusion - Item Units')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 fw-bold text-dark" style="font-size: 1.5rem;">Item Units</h3>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Manage your store item units via API</p>
        </div>
        <div>
            <button type="button" data-bs-toggle="modal" data-bs-target="#createUnitModal" class="btn btn-warning text-white fw-bold d-flex align-items-center px-3 py-2" style="font-size: 0.85rem; border-color: #e2e8f0; border-radius: 8px; background-color: var(--theme-orange); border: none;">
                <i class="bi bi-plus-circle me-2"></i> Add New Unit
            </button>
        </div>
    </div>

    <div id="alertContainer"></div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 premium-table-grid" id="unitsTable">
                    <thead class="table-light text-muted">
                        <tr style="font-size: 0.85rem;">
                            <th class="ps-4" style="width: 25%;">Code</th>
                            <th style="width: 45%;">Name</th>
                            <th style="width: 15%;">Status</th>
                            <th class="text-end pe-4" style="width: 15%;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="unitsTableBody">
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Loading data from API...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit Unit Modal -->
<div class="modal fade" id="createUnitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="modalTitle">Add New Unit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="unitForm">
                    <input type="hidden" id="unitOid">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Unit Code *</label>
                            <input type="text" class="form-control bg-white border" id="Code" placeholder="e.g. PCS, BOX, KG" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Unit Name *</label>
                            <input type="text" class="form-control bg-white border" id="Name" placeholder="e.g. Pieces, Box, Kilogram" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Status *</label>
                            <select class="form-select bg-white border" id="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn text-white fw-bold px-4 py-2" style="background-color: var(--theme-orange); border-radius: 8px;">
                                Save Unit
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
    const apiBase = '/api/itemunits';
    const tbody = document.getElementById('unitsTableBody');
    const form = document.getElementById('unitForm');
    const modal = new bootstrap.Modal(document.getElementById('createUnitModal'));
    let currentMode = 'create';

    // Fetch Units via API
    function fetchUnits() {
        fetch(apiBase)
            .then(res => res.json())
            .then(res => {
                const units = res.data;
                tbody.innerHTML = '';
                if(units.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-muted">No units found.</td></tr>';
                    return;
                }
                
                units.forEach(p => {
                    const statusHtml = p.status === 'active' ? 
                        '<span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1">Active</span>' : 
                        '<span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2 py-1">Inactive</span>';

                    const row = document.createElement('tr');
                    row.style.fontSize = '0.9rem';
                    row.innerHTML = `
                        <td class="ps-4 fw-medium text-dark">${p.Code}</td>
                        <td class="text-muted">${p.Name}</td>
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
                tbody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-danger">Failed to fetch data from server.</td></tr>';
            });
    }

    // Submit form via API
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const payload = {
            Code: document.getElementById('Code').value,
            Name: document.getElementById('Name').value,
            status: document.getElementById('status').value
        };

        const oid = document.getElementById('unitOid').value;
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
                fetchUnits();
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
            if(confirm('Are you sure you want to delete this unit?')) {
                fetch(`${apiBase}/${btnDelete.dataset.id}`, { method: 'DELETE' })
                .then(res => res.json())
                .then(data => {
                    fetchUnits();
                    showAlert('success', data.message);
                })
                .catch(err => {
                    console.error(err);
                    showAlert('danger', 'Failed to delete unit.');
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
                document.getElementById('modalTitle').textContent = 'Edit Item Unit';
                document.getElementById('unitOid').value = p.Oid;
                document.getElementById('Code').value = p.Code;
                document.getElementById('Name').value = p.Name;
                document.getElementById('status').value = p.status;
                currentMode = 'edit';
                modal.show();
            })
            .catch(err => {
                console.error(err);
                showAlert('danger', 'Failed to fetch unit details.');
            });
        }
    });

    // Reset form on open
    document.querySelector('[data-bs-target="#createUnitModal"]').addEventListener('click', () => {
        currentMode = 'create';
        document.getElementById('modalTitle').textContent = 'Add New Unit';
        form.reset();
        document.getElementById('unitOid').value = '';
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
    fetchUnits();
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
