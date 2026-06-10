@extends('layouts.backend.app')

@section('title', 'User Management')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 fw-bold text-dark" style="font-size: 1.5rem;">User Management</h3>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Manage application users and roles</p>
        </div>
        <div>
            <button type="button" data-bs-toggle="modal" data-bs-target="#createUserModal" class="btn btn-warning text-white fw-bold d-flex align-items-center px-3 py-2" style="font-size: 0.85rem; border-color: #e2e8f0; border-radius: 8px; background-color: var(--theme-orange); border: none;">
                <i class="bi bi-person-plus me-2"></i> Add New User
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
                <!-- Using explicit full width borders a la reference -->
                <table class="table align-middle mb-0 custom-table-grid">
                    <thead class="bg-white text-muted">
                        <tr style="font-size: 0.85rem;">
                            <!-- Adding a checkbox column like the image -->
                            <th class="py-3 text-center" style="width: 50px;">
                                <input class="form-check-input border-secondary" type="checkbox" style="opacity: 0.5;">
                            </th>
                            <th class="py-3 fw-medium">User Details <i class="bi bi-arrow-down-up ms-1" style="font-size: 0.7rem; opacity: 0.5;"></i></th>
                            <th class="py-3 fw-medium">Code <i class="bi bi-arrow-down-up ms-1" style="font-size: 0.7rem; opacity: 0.5;"></i></th>
                            <th class="py-3 fw-medium">Role <i class="bi bi-arrow-down-up ms-1" style="font-size: 0.7rem; opacity: 0.5;"></i></th>
                            <th class="py-3 fw-medium">Status <i class="bi bi-arrow-down-up ms-1" style="font-size: 0.7rem; opacity: 0.5;"></i></th>
                            <th class="text-center py-3 fw-medium">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse ($users as $user)
                            <tr>
                                <td class="text-center py-3">
                                    <input class="form-check-input border-secondary" type="checkbox" style="opacity: 0.5;">
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-3 d-flex justify-content-center align-items-center text-white fw-bold shadow-sm" style="width: 36px; height: 36px; border-radius: 50%; font-size: 0.9rem; background: #64748b;">
                                            {{ strtoupper(substr($user->Name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">{{ $user->Name }}</h6>
                                            <small class="text-muted" style="font-size: 0.8rem;">ID: {{ $user->Oid ?? $user->oid ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="text-secondary fw-medium" style="font-size: 0.85rem;">
                                         {{ $user->Code }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    <!-- In the reference image, rating was stars, but here we keep Role as a clean text/badge -->
                                    <span class="fw-medium text-dark" style="font-size: 0.85rem;">
                                        {{ $user->role ? $user->role->Name : 'No Role' }}
                                    </span>
                                </td>
                                <td class="py-3 ">
                                    @if ($user->IsActive)
                                        <span class="badge rounded-pill fw-medium px-2 py-1" style="background-color: #10b981; color: #fff; font-size: 0.70rem;">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge rounded-pill fw-medium px-2 py-1" style="background-color: #ef4444; color: #fff; font-size: 0.70rem;">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center py-3">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button type="button" class="btn btn-sm btn-icon border hover-bg-light shadow-sm" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->Oid ?? $user->oid ?? 0 }}" title="Edit" style="width: 30px; height: 30px; border-radius: 6px; display: flex; align-items: center; justify-content: center; background: white;">
                                            <i class="bi bi-pencil" style="color: #64748b; font-size: 0.85rem;"></i>
                                        </button>
                                        <form action="{{ route('users.destroy', $user->Oid ?? $user->oid ?? 0) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon border hover-bg-light shadow-sm" data-bs-toggle="tooltip" title="Delete" style="width: 30px; height: 30px; border-radius: 6px; display: flex; align-items: center; justify-content: center; background: white;">
                                                <i class="bi bi-trash" style="color: #ef4444; font-size: 0.85rem;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit User Modal specifically for this loop -->
                            <div class="modal fade" id="editUserModal{{ $user->Oid ?? $user->oid ?? 0 }}" tabindex="-1" aria-labelledby="editUserModalLabel{{ $user->Oid ?? $user->oid ?? 0 }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow" style="border-radius: 12px;">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold text-dark" id="editUserModalLabel{{ $user->Oid ?? $user->oid ?? 0 }}">Edit User</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <form action="{{ route('users.update', $user->Oid ?? $user->oid ?? 0) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="row g-4">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Code *</label>
                                                        <input type="text" class="form-control bg-white border" name="Code" required value="{{ old('Code', $user->Code) }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Name *</label>
                                                        <input type="text" class="form-control bg-white border" name="Name" required value="{{ old('Name', $user->Name) }}">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Role *</label>
                                                        <select class="form-select bg-white border" name="IsRole" required>
                                                            <option value="">-- Select Role --</option>
                                                            @foreach ($roles as $role)
                                                                <option value="{{ $role->Oid }}" {{ old('IsRole', $user->IsRole) == $role->Oid ? 'selected' : '' }}>
                                                                    {{ $role->Name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Status *</label>
                                                        <select class="form-select bg-white border" name="IsActive" required>
                                                            <option value="1" {{ old('IsActive', $user->IsActive) == '1' ? 'selected' : '' }}>Active</option>
                                                            <option value="0" {{ old('IsActive', $user->IsActive) == '0' ? 'selected' : '' }}>Inactive</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-12 mt-4 text-end">
                                                        <button type="button" class="btn btn-light border me-2" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn text-white fw-bold px-4 py-2" style="background-color: var(--theme-orange); border-radius: 8px;">
                                                            Update User
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No users found.</td>
                            </tr>
                        @endforelse
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

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="createUserModalLabel">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="Code" class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Code *</label>
                            <input type="text" class="form-control bg-white border" id="Code" name="Code" required value="{{ old('Code') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="Name" class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Name *</label>
                            <input type="text" class="form-control bg-white border" id="Name" name="Name" required value="{{ old('Name') }}">
                        </div>
                        
                        <div class="col-md-12">
                            <label for="IsRole" class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Role *</label>
                            <select class="form-select bg-white border" id="IsRole" name="IsRole" required>
                                <option value="">-- Select Role --</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->Oid }}" {{ old('IsRole') == $role->Oid ? 'selected' : '' }}>
                                        {{ $role->Name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-12">
                            <label for="IsActive" class="form-label fw-medium text-dark" style="font-size: 0.9rem;">Status *</label>
                            <select class="form-select bg-white border" id="IsActive" name="IsActive" required>
                                <option value="1" {{ old('IsActive') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('IsActive') == '0' ? 'selected' : '' }}>Inactive</option>
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

@if($errors->any() && !old('_method'))
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var createModal = new bootstrap.Modal(document.getElementById('createUserModal'));
        createModal.show();
    });
</script>
@endif

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
