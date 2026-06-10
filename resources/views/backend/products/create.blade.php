@extends('layouts.backend.app')

@section('title', 'Add New Product')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 fw-bold text-dark" style="font-size: 1.5rem;">Add New Product</h3>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Create a new product for your catalog</p>
        </div>
        <div>
            <a href="{{ route('products.index') }}" class="btn btn-light text-muted fw-medium border px-3 py-2" style="font-size: 0.85rem; border-radius: 8px;">
                <i class="bi bi-arrow-left me-2"></i> Back to Products
            </a>
        </div>
    </div>

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

    <div class="card border-0 shadow-sm" style="border-radius: 10px;">
        <div class="card-body p-4">
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
                        <button type="submit" class="btn text-white fw-bold px-4 py-2" style="background-color: var(--theme-orange); border-radius: 8px;">
                            Save Product
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
