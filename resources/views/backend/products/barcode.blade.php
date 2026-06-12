@extends('layouts.backend.app')

@section('title', 'Product Barcode - ' . $product->Name)

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 fw-bold text-dark" style="font-size: 1.5rem;">Product Barcode</h3>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Generated barcode for: {{ $product->Name }}</p>
        </div>
        <div>
            <a href="{{ route('products.index') }}" class="btn btn-light border-0 shadow-sm fw-bold px-3 py-2 me-2" style="font-size: 0.85rem; border-radius: 8px;">
                <i class="bi bi-arrow-left me-2"></i> Back to List
            </a>
            <button onclick="window.print()" class="btn btn-primary text-white fw-bold px-3 py-2" style="font-size: 0.85rem; border-radius: 8px;">
                <i class="bi bi-printer me-2"></i> Print Barcode
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="max-width: 400px; margin: 0 auto;">
        <div class="card-body p-5 text-center">
            <h5 class="fw-bold mb-4">{{ $product->Name }}</h5>
            
            <div class="barcode-container mb-3 d-flex justify-content-center">
                {!! $barcode !!}
            </div>
            
            <p class="text-muted fw-bold ls-1 mb-0" style="letter-spacing: 2px;">{{ $product->Code }}</p>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .card, .card * {
            visibility: visible;
        }
        .card {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            box-shadow: none !important;
            border: none !important;
            margin: 0 !important;
            width: 100%;
            max-width: 100%;
        }
        .card-body {
            padding: 0 !important;
        }
    }
    .barcode-container svg {
        width: 100%;
        height: auto;
        max-width: 300px;
    }
</style>
@endsection
