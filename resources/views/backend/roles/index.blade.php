@extends('layouts.backend.app')

@section('title', 'Role Management')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 fw-bold text-dark" style="font-size: 1.5rem;">Role Management</h3>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Manage user roles and positions via API</p>
        </div>
        <div>
            <button type="button" data-bs-toggle="modal" data-bs-target="#createRoleModal" class="btn btn-warning text-white fw-bold d-flex align-items-center px-3 py-2" style="font-size: 0.85rem; border-color: #e2e8f0; border-radius: 8px; background-color: var(--theme-orange); border: none;">
                <i class="bi bi-shield-plus me-2"></i> Add New Role
            </button>
        </div>
    </div>

    <div id="alertContainer"></div>

    <div class="card" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: none;">
        <div class="card-body p-0">
            <!-- Top Bar -->
            <div class="d-flex justify-content-between align-items-center p-4 border-bottom" style="border-color: #f1f5f9 !important;">
                <h5 class="mb-0 fw-bold text-dark fs-5">Roles</h5>
                <div class="position-relative">
                    <i class="bi bi-search position-absolute text-muted" style="left: 12px; top: 50%; transform: translateY(-50%);"></i>
                    <input type="text" id="searchRoleInput" class="form-control" placeholder="Search Roles" style="padding-left: 36px; border-radius: 8px; border: 1px solid #e2e8f0; width: 250px; font-size: 0.9rem;">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0 custom-table-grid" id="rolesTable">
                    <thead class="bg-white text-muted">
                        <tr style="font-size: 0.85rem;">
                            <th class="py-3 text-center" style="width: 50px;">
                                <input class="form-check-input border-secondary" type="checkbox" style="opacity: 0.5;">
                            </th>
                            <th class="py-3 fw-medium">Role Code</th>
                            <th class="py-3 fw-medium">Role Name</th>
                            <th class="text-center py-3 fw-medium" style="width: 150px;">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white" id="rolesTableBody">
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Loading role data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit Role Modal -->
<div class="modal fade" id="createRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="modalTitle">Add New Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="roleForm">
                    <input type="hidden" id="roleOid">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label for="Code" class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Role Code *</label>
                            <input type="text" class="form-control bg-white border" id="Code" required placeholder="e.g. AD, ST">
                        </div>
                        <div class="col-md-12">
                            <label for="Name" class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Role Name *</label>
                            <input type="text" class="form-control bg-white border" id="Name" required placeholder="e.g. Admin, Staff">
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <button type="button" class="btn btn-light border me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn text-white fw-bold px-4 py-2" style="background-color: var(--theme-orange); border-radius: 8px;">
                                Save Role
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
    const apiBase = '/api/roles';
    const tbody = document.getElementById('rolesTableBody');
    const form = document.getElementById('roleForm');
    const modal = new bootstrap.Modal(document.getElementById('createRoleModal'));
    let allRoles = [];

    // Fetch Roles
    function fetchRoles() {
        fetch(apiBase)
            .then(res => res.json())
            .then(res => {
                allRoles = res.data;
                renderRoles(allRoles);
            })
            .catch(err => {
                console.error(err);
                tbody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-danger">Failed to fetch role data from server.</td></tr>';
            });
    }

    // Render Roles Table
    function renderRoles(roles) {
        tbody.innerHTML = '';
        if (roles.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-muted">No roles found.</td></tr>';
            return;
        }

        roles.forEach(r => {
            const oid = r.Oid || 0;
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="text-center py-3">
                    <input class="form-check-input border-secondary" type="checkbox" style="opacity: 0.5;">
                </td>
                <td class="py-3 font-monospace fw-semibold" style="font-size: 0.85rem;">
                    ${r.Code}
                </td>
                <td class="py-3">
                    <span class="fw-bold text-dark" style="font-size: 0.9rem;">${r.Name}</span>
                </td>
                <td class="text-center py-3">
                    <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-sm btn-icon border hover-bg-light shadow-sm btn-edit" data-id="${oid}" title="Edit" style="width: 30px; height: 30px; border-radius: 6px; display: flex; align-items: center; justify-content: center; background: white;">
                            <i class="bi bi-pencil" style="color: #64748b; font-size: 0.85rem;"></i>
                        </button>
                        <button class="btn btn-sm btn-icon border hover-bg-light shadow-sm btn-delete" data-id="${oid}" title="Delete" style="width: 30px; height: 30px; border-radius: 6px; display: flex; align-items: center; justify-content: center; background: white;">
                            <i class="bi bi-trash" style="color: #ef4444; font-size: 0.85rem;"></i>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    // Filter via Search
    document.getElementById('searchRoleInput').addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        const filtered = allRoles.filter(r => 
            r.Name.toLowerCase().includes(query) || 
            r.Code.toLowerCase().includes(query)
        );
        renderRoles(filtered);
    });

    // Form Submit (Create / Edit)
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const payload = {
            Code: document.getElementById('Code').value,
            Name: document.getElementById('Name').value
        };

        const oid = document.getElementById('roleOid').value;
        const method = currentMode === 'create' ? 'POST' : 'PUT';
        const url = currentMode === 'create' ? apiBase : `${apiBase}/${oid}`;

        fetch(url, {
            method: method,
            headers: { 
                'Content-Type': 'application/json', 
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                modal.hide();
                fetchRoles();
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

    // Edit and Delete Actions
    tbody.addEventListener('click', function(e) {
        const btnDelete = e.target.closest('.btn-delete');
        if (btnDelete) {
            if(confirm('Are you sure you want to delete this role?')) {
                fetch(`${apiBase}/${btnDelete.dataset.id}`, { 
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        fetchRoles();
                        showAlert('success', data.message);
                    } else {
                        showAlert('danger', data.message || 'Failed to delete role.');
                    }
                })
                .catch(err => {
                    console.error(err);
                    showAlert('danger', 'Failed to delete role.');
                });
            }
            return;
        }

        const btnEdit = e.target.closest('.btn-edit');
        if (btnEdit) {
            fetch(`${apiBase}/${btnEdit.dataset.id}`)
            .then(res => res.json())
            .then(res => {
                const r = res.data;
                document.getElementById('modalTitle').textContent = 'Edit Role';
                document.getElementById('roleOid').value = r.Oid;
                document.getElementById('Code').value = r.Code;
                document.getElementById('Name').value = r.Name;
                currentMode = 'edit';
                modal.show();
            })
            .catch(err => {
                console.error(err);
                showAlert('danger', 'Failed to load role details.');
            });
        }
    });

    // Reset Form on Open
    document.querySelector('[data-bs-target="#createRoleModal"]').addEventListener('click', () => {
        currentMode = 'create';
        document.getElementById('modalTitle').textContent = 'Add New Role';
        form.reset();
        document.getElementById('roleOid').value = '';
    });

    function showAlert(type, message) {
        const alertHtml = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;
        document.getElementById('alertContainer').innerHTML = alertHtml;
        setTimeout(() => document.getElementById('alertContainer').innerHTML = '', 3000);
    }

    // Initial load
    fetchRoles();
});
</script>

<style>
    .custom-table-grid th, .custom-table-grid td {
        border: 1px solid #f1f5f9 !important;
        vertical-align: middle;
        color: #334155;
        padding: 12px 16px !important;
    }
    .custom-table-grid th {
        background-color: #ffffff !important;
        color: #475569;
        font-weight: 500;
        border-bottom: 2px solid #e2e8f0 !important;
    }
    .custom-table-grid tbody tr {
        transition: background-color 0.15s ease;
    }
    .custom-table-grid tbody tr:hover {
        background-color: #f8fafc !important;
    }
    .custom-table-grid {
        border-left: 0 !important;
        border-right: 0 !important;
    }
    .custom-table-grid th:first-child, .custom-table-grid td:first-child {
        border-left: 0 !important;
    }
    .custom-table-grid th:last-child, .custom-table-grid td:last-child {
        border-right: 0 !important;
    }

    .hover-bg-light:hover {
        background-color: #f1f5f9 !important;
        border-color: #cbd5e1 !important;
    }
</style>
@endsection
