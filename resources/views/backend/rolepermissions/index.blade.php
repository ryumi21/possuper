@extends('layouts.backend.app')

@section('title', 'Role Permission')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 fw-bold text-dark" style="font-size: 1.5rem;">Role Permission</h3>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Manage feature access and visible modules for each user</p>
        </div>
    </div>

    <div id="alertContainer"></div>

    <div class="card" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: none;">
        <div class="card-body p-0">
            <!-- Top Bar -->
            <div class="d-flex justify-content-between align-items-center p-4 border-bottom" style="border-color: #f1f5f9 !important;">
                <h5 class="mb-0 fw-bold text-dark fs-5">User Access Rights</h5>
                <div class="position-relative">
                    <i class="bi bi-search position-absolute text-muted" style="left: 12px; top: 50%; transform: translateY(-50%);"></i>
                    <input type="text" id="searchUserInput" class="form-control" placeholder="Search Users" style="padding-left: 36px; border-radius: 8px; border: 1px solid #e2e8f0; width: 250px; font-size: 0.9rem;">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0 custom-table-grid" id="permissionsTable">
                    <thead class="bg-white text-muted">
                        <tr style="font-size: 0.85rem;">
                            <th class="py-3 text-center" style="width: 50px;">
                                <input class="form-check-input border-secondary" type="checkbox" style="opacity: 0.5;">
                            </th>
                            <th class="py-3 fw-medium">User Details</th>
                            <th class="py-3 fw-medium">Code</th>
                            <th class="py-3 fw-medium">Role</th>
                            <th class="py-3 fw-medium">Allowed Modules</th>
                            <th class="text-center py-3 fw-medium" style="width: 150px;">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white" id="permissionsTableBody">
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Loading user data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit Permission Modal -->
<div class="modal fade" id="editPermissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">Akses Fitur User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="bg-light p-3 rounded mb-4 d-flex align-items-center">
                    <div class="avatar-circle-sm me-3 d-flex justify-content-center align-items-center text-white fw-bold shadow-sm" id="modalUserAvatar" style="width: 40px; height: 40px; border-radius: 50%; font-size: 1rem; background: #64748b;">
                        U
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold text-dark" id="modalUserName">User Name</h6>
                        <small class="text-muted" id="modalUserRole">Role: Administrator</small>
                    </div>
                </div>

                <form id="permissionForm">
                    <input type="hidden" id="permissionUserOid">
                    
                    @php
                        $groupedMenus = $menus->groupBy('Category');
                    @endphp
                    
                    <div class="row g-4">
                        @foreach($groupedMenus as $category => $categoryMenus)
                            <div class="col-md-12">
                                <div class="card border border-light shadow-sm" style="border-radius: 8px;">
                                    <div class="card-header bg-white border-bottom border-light py-3 px-3">
                                        <h6 class="fw-bold text-primary mb-0 d-flex align-items-center">
                                            <i class="bi bi-folder-fill me-2 text-warning"></i>{{ $category }}
                                        </h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="row g-3">
                                            @foreach($categoryMenus as $menu)
                                                <div class="col-md-6 col-lg-4">
                                                    <div class="form-check form-switch card p-2 border border-light hover-shadow-sm transition-all" style="cursor: pointer;">
                                                        <div class="d-flex align-items-center">
                                                            <input class="form-check-input menu-checkbox ms-0 me-3" type="checkbox" value="{{ $menu->Oid }}" id="menu_{{ $menu->Oid }}">
                                                            <label class="form-check-label text-dark fw-semibold" for="menu_{{ $menu->Oid }}" style="cursor: pointer; font-size: 0.88rem;">
                                                                {{ $menu->Fitur }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="col-12 mt-4 text-end">
                            <button type="button" class="btn btn-light border me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn text-white fw-bold px-4 py-2" style="background-color: var(--theme-orange); border-radius: 8px;">
                                Save Permissions
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
    const apiBase = '/api/rolepermissions';
    const tbody = document.getElementById('permissionsTableBody');
    const form = document.getElementById('permissionForm');
    const modal = new bootstrap.Modal(document.getElementById('editPermissionModal'));
    let allUsers = [];

    // Fetch Users & Allowed Modules
    function fetchPermissions() {
        fetch(apiBase)
            .then(res => res.json())
            .then(res => {
                allUsers = res.data;
                renderUsers(allUsers);
            })
            .catch(err => {
                console.error(err);
                tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-danger">Failed to fetch permissions data from server.</td></tr>';
            });
    }

    // Render Users list
    function renderUsers(users) {
        tbody.innerHTML = '';
        if (users.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-muted">No users found.</td></tr>';
            return;
        }

        users.forEach(u => {
            const initial = u.Name ? u.Name.substring(0, 1).toUpperCase() : '?';
            const oid = u.Oid || 0;
            const roleName = u.role ? u.role.Name : 'No Role';
            
            // Build badges for allowed modules
            let modulesHtml = '';
            if (u.menus && u.menus.length > 0) {
                modulesHtml = u.menus.map(m => 
                    `<span class="badge bg-light border text-dark fw-semibold me-1 mb-1" style="font-size: 0.72rem; padding: 4px 8px; border-radius: 6px;">${m.Fitur}</span>`
                ).join('');
            } else {
                modulesHtml = `<span class="text-muted italic" style="font-size: 0.8rem; font-style: italic;">Menggunakan Akses Default (Berdasarkan IsPos)</span>`;
            }

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
                <td class="py-3" style="max-width: 350px; white-space: normal;">
                    ${modulesHtml}
                </td>
                <td class="text-center py-3">
                    <button class="btn btn-sm btn-icon border hover-bg-light shadow-sm btn-manage-access" data-id="${oid}" title="Akses Fitur" style="width: 34px; height: 34px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; background: white;">
                        <i class="bi bi-shield-lock text-primary" style="font-size: 0.95rem;"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    // Filter Users via Search Input
    document.getElementById('searchUserInput').addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        const filtered = allUsers.filter(u => 
            u.Name.toLowerCase().includes(query) || 
            u.Code.toLowerCase().includes(query) ||
            (u.role && u.role.Name.toLowerCase().includes(query))
        );
        renderUsers(filtered);
    });

    // Handle Edit Permissions Click
    tbody.addEventListener('click', function(e) {
        const btnAccess = e.target.closest('.btn-manage-access');
        if (btnAccess) {
            const userId = btnAccess.dataset.id;
            
            fetch(`${apiBase}/${userId}`)
                .then(res => res.json())
                .then(res => {
                    const data = res.data;
                    const u = data.user;
                    const assigned = data.assigned_menu_ids;

                    // Update modal user header details
                    document.getElementById('permissionUserOid').value = u.Oid;
                    document.getElementById('modalUserName').textContent = u.Name;
                    document.getElementById('modalUserRole').textContent = `Role ID: ${u.IsRole} | Code: ${u.Code}`;
                    document.getElementById('modalUserAvatar').textContent = u.Name.substring(0,1).toUpperCase();

                    // Check checkboxes
                    document.querySelectorAll('.menu-checkbox').forEach(cb => {
                        cb.checked = assigned.includes(parseInt(cb.value));
                    });

                    modal.show();
                })
                .catch(err => {
                    console.error(err);
                    showAlert('danger', 'Failed to load user permissions.');
                });
        }
    });

    // Handle Checkbox Wrapper Click
    document.querySelectorAll('.form-check').forEach(div => {
        div.addEventListener('click', function(e) {
            if (e.target.tagName !== 'INPUT') {
                const cb = this.querySelector('.menu-checkbox');
                cb.checked = !cb.checked;
                cb.dispatchEvent(new Event('change'));
            }
        });
    });

    // Save Permissions via API
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const userId = document.getElementById('permissionUserOid').value;
        const menuIds = [];
        document.querySelectorAll('.menu-checkbox:checked').forEach(cb => {
            menuIds.push(parseInt(cb.value));
        });

        fetch(`${apiBase}/${userId}`, {
            method: 'PUT',
            headers: { 
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ menu_ids: menuIds })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                modal.hide();
                fetchPermissions();
                showAlert('success', data.message);
            } else {
                showAlert('danger', data.message || 'Failed to save permissions.');
            }
        })
        .catch(err => {
            console.error(err);
            showAlert('danger', 'Server error occurred.');
        });
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
    fetchPermissions();
});
</script>

<style>
    /* Styling alignment with premium design system */
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

    /* Checkbox list premium wrapper styling */
    .hover-shadow-sm {
        transition: all 0.2s ease;
    }
    .hover-shadow-sm:hover {
        border-color: var(--theme-orange) !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        background-color: #fffbf7 !important;
    }
    .transition-all {
        transition: all 0.2s ease-in-out;
    }
</style>
@endsection
