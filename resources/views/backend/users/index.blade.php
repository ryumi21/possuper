@extends('layouts.backend.app')

@section('title', 'User Management')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 fw-bold text-dark" style="font-size: 1.5rem;">User Management</h3>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Manage application users and roles via API</p>
        </div>
        <div>
            <button type="button" data-bs-toggle="modal" data-bs-target="#createUserModal" class="btn btn-warning text-white fw-bold d-flex align-items-center px-3 py-2" style="font-size: 0.85rem; border-color: #e2e8f0; border-radius: 8px; background-color: var(--theme-orange); border: none;">
                <i class="bi bi-person-plus me-2"></i> Add New User
            </button>
        </div>
    </div>

    <div id="alertContainer"></div>

    <div class="card" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: none;">
        <div class="card-body p-0">
            <!-- Top Bar mimicking reference image -->
            <div class="d-flex justify-content-between align-items-center p-4 border-bottom" style="border-color: #f1f5f9 !important;">
                <h5 class="mb-0 fw-bold text-dark fs-5">Users</h5>
                <div class="position-relative">
                    <i class="bi bi-search position-absolute text-muted" style="left: 12px; top: 50%; transform: translateY(-50%);"></i>
                    <input type="text" class="form-control" placeholder="Search Users" style="padding-left: 36px; border-radius: 8px; border: 1px solid #e2e8f0; width: 250px; font-size: 0.9rem;">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0 custom-table-grid" id="usersTable">
                    <thead class="bg-white text-muted">
                        <tr style="font-size: 0.85rem;">
                            <!-- Adding a checkbox column like the image -->
                            <th class="py-3 text-center" style="width: 50px;">
                                <input class="form-check-input border-secondary" type="checkbox" style="opacity: 0.5;">
                            </th>
                            <th class="py-3 fw-medium">User Details <i class="bi bi-arrow-down-up ms-1" style="font-size: 0.7rem; opacity: 0.5;"></i></th>
                            <th class="py-3 fw-medium">Code <i class="bi bi-arrow-down-up ms-1" style="font-size: 0.7rem; opacity: 0.5;"></i></th>
                            <th class="py-3 fw-medium">Role <i class="bi bi-arrow-down-up ms-1" style="font-size: 0.7rem; opacity: 0.5;"></i></th>
                            <th class="py-3 fw-medium">Sistem POS <i class="bi bi-arrow-down-up ms-1" style="font-size: 0.7rem; opacity: 0.5;"></i></th>
                            <th class="py-3 fw-medium">Status <i class="bi bi-arrow-down-up ms-1" style="font-size: 0.7rem; opacity: 0.5;"></i></th>
                            <th class="text-center py-3 fw-medium">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white" id="usersTableBody">
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Loading data from API...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer Pagination mimicking reference image -->
            <div class="d-flex justify-content-between align-items-center p-3 border-top" style="border-color: #f1f5f9 !important;">
                <div class="d-flex align-items-center">
                    <span class="text-dark fw-medium me-2" style="font-size: 0.85rem;">Show</span>
                    <select class="form-select form-select-sm border-secondary text-dark" style="width: 60px; font-size: 0.85rem; border-color: #e2e8f0 !important; cursor: pointer;">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                    </select>
                    <span class="text-dark fw-medium ms-2" style="font-size: 0.85rem;">per page</span>
                </div>
                
                <div class="d-flex align-items-center gap-3">
                    <span class="text-dark fw-medium" style="font-size: 0.85rem;">1-5 of 15</span>
                    <div class="d-flex align-items-center gap-1">
                        <button class="btn btn-sm btn-icon border-0" style="color: #64748b;"><i class="bi bi-arrow-left"></i></button>
                        <button class="btn btn-sm btn-light border-0 fw-medium" style="background-color: #f1f5f9; color: #334155;">1</button>
                        <button class="btn btn-sm btn-icon border-0 text-muted">2</button>
                        <button class="btn btn-sm btn-icon border-0 text-muted">3</button>
                        <button class="btn btn-sm btn-icon border-0" style="color: #0f172a;"><i class="bi bi-arrow-right"></i></button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Create/Edit User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="modalTitle">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="userForm">
                    <input type="hidden" id="userOid">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="Code" class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Code *</label>
                            <input type="text" class="form-control bg-white border" id="Code" required>
                        </div>
                        <div class="col-md-6">
                            <label for="Name" class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Name *</label>
                            <input type="text" class="form-control bg-white border" id="Name" required>
                        </div>
                        
                        <div class="col-md-12">
                            <label for="IsRole" class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Role *</label>
                            <select class="form-select bg-white border" id="IsRole" required>
                                <option value="">-- Select Role --</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->Oid }}">{{ $role->Name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-12">
                            <label for="IsActive" class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Status *</label>
                            <select class="form-select bg-white border" id="IsActive" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        
                        <div class="col-md-12">
                            <label for="IsPos" class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Sistem POS *</label>
                            <select class="form-select bg-white border" id="IsPos" required>
                                <option value="1">POS Retail</option>
                                <option value="2">POS Cafe</option>
                            </select>
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <button type="button" class="btn btn-light border me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn text-white fw-bold px-4 py-2" style="background-color: var(--theme-orange); border-radius: 8px;">
                                Save User
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
    const apiBase = '/api/users';
    const tbody = document.getElementById('usersTableBody');
    const form = document.getElementById('userForm');
    const modal = new bootstrap.Modal(document.getElementById('createUserModal'));
    let currentMode = 'create';

    function fetchUsers() {
        fetch(apiBase)
            .then(res => res.json())
            .then(res => {
                const users = res.data;
                tbody.innerHTML = '';
                if(users.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-muted">No users found.</td></tr>';
                    return;
                }
                
                users.forEach(u => {
                    const statusHtml = u.IsActive ? 
                        '<span class="badge rounded-pill fw-medium px-2 py-1" style="background-color: #10b981; color: #fff; font-size: 0.70rem;">Active</span>' : 
                        '<span class="badge rounded-pill fw-medium px-2 py-1" style="background-color: #ef4444; color: #fff; font-size: 0.70rem;">Inactive</span>';
                        
                    const initial = u.Name ? u.Name.substring(0, 1).toUpperCase() : '?';
                    const oid = u.Oid || u.oid || 0;
                    const roleName = u.role ? u.role.Name : 'No Role';

                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="text-center py-3">
                            <input class="form-check-input border-secondary" type="checkbox" style="opacity: 0.5;">
                        </td>
                        <td class="py-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-3 d-flex justify-content-center align-items-center text-white fw-bold shadow-sm" style="width: 36px; height: 36px; border-radius: 50%; font-size: 0.9rem; background: #64748b;">
                                    ${initial}
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">${u.Name}</h6>
                                    <small class="text-muted" style="font-size: 0.8rem;">ID: ${oid}</small>
                                </div>
                            </div>
                        </td>
                        <td class="py-3">
                            <span class="text-secondary fw-medium" style="font-size: 0.85rem;">${u.Code}</span>
                        </td>
                        <td class="py-3">
                            <span class="fw-medium text-dark" style="font-size: 0.85rem;">${roleName}</span>
                        </td>
                        <td class="py-3">
                            <span class="badge rounded-pill fw-medium px-2 py-1" style="font-size: 0.70rem; ${u.IsPos == 1 ? 'background-color: #3b82f6; color: #fff;' : 'background-color: #ec4899; color: #fff;'}">${u.IsPos == 1 ? 'Retail' : 'Cafe'}</span>
                        </td>
                        <td class="py-3 ">
                            ${statusHtml}
                        </td>
                        <td class="text-center py-3">
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-sm btn-icon border hover-bg-light shadow-sm btn-reset" data-id="${oid}" title="Reset Password to 123" style="width: 30px; height: 30px; border-radius: 6px; display: flex; align-items: center; justify-content: center; background: white;">
                                    <i class="bi bi-key" style="color: #f59e0b; font-size: 0.85rem;"></i>
                                </button>
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
            });
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const payload = {
            Code: document.getElementById('Code').value,
            Name: document.getElementById('Name').value,
            IsRole: document.getElementById('IsRole').value,
            IsActive: document.getElementById('IsActive').value,
            IsPos: document.getElementById('IsPos').value
        };

        const oid = document.getElementById('userOid').value;
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
                fetchUsers();
                showAlert('success', data.message);
            } else {
                showAlert('danger', 'Error saving data.');
            }
        });
    });

    tbody.addEventListener('click', function(e) {
        const btnDelete = e.target.closest('.btn-delete');
        if (btnDelete) {
            showConfirm('Are you sure you want to delete this user?', function() {
                fetch(`${apiBase}/${btnDelete.dataset.id}`, { 
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(res => res.json())
                .then(data => {
                    fetchUsers();
                    showAlert('success', data.message);
                });
            });
            return;
        }

        const btnReset = e.target.closest('.btn-reset');
        if (btnReset) {
            showConfirm('Are you sure you want to reset the password to 123 for this user?', function() {
                fetch(`${apiBase}/${btnReset.dataset.id}/reset-password`, { 
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(res => res.json())
                .then(data => {
                    showAlert('success', data.message);
                });
            });
            return;
        }

        const btnEdit = e.target.closest('.btn-edit');
        if (btnEdit) {
            fetch(`${apiBase}/${btnEdit.dataset.id}`)
            .then(res => res.json())
            .then(res => {
                const u = res.data;
                document.getElementById('modalTitle').textContent = 'Edit User';
                document.getElementById('userOid').value = u.Oid;
                document.getElementById('Code').value = u.Code;
                document.getElementById('Name').value = u.Name;
                document.getElementById('IsRole').value = u.IsRole;
                document.getElementById('IsActive').value = u.IsActive;
                document.getElementById('IsPos').value = u.IsPos;
                currentMode = 'edit';
                modal.show();
            });
        }
    });

    document.querySelector('[data-bs-target="#createUserModal"]').addEventListener('click', () => {
        currentMode = 'create';
        document.getElementById('modalTitle').textContent = 'Add New User';
        form.reset();
        document.getElementById('userOid').value = '';
    });

    function showAlert(type, message) {
        const alertHtml = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;
        document.getElementById('alertContainer').innerHTML = alertHtml;
        setTimeout(() => document.getElementById('alertContainer').innerHTML = '', 3000);
    }

    fetchUsers();
});
</script>

<style>
    /* Styling to match reference image */
    .custom-table-grid th, .custom-table-grid td {
        border: 1px solid #f1f5f9 !important;
        vertical-align: middle;
        color: #334155;
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
    /* Hide outer borders to avoid double bordering with card */
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
