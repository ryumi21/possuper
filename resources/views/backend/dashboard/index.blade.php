@extends('layouts.backend.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid px-0">
    
    <!-- Welcome Header & Date Picker -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-1 fw-bold text-dark" style="font-size: 1.5rem;">Welcome, Admin</h3>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">You have <span class="text-warning fw-bold">200+</span> Orders, Today</p>
        </div>
        <div>
            <button class="btn btn-outline-secondary bg-white text-muted d-flex align-items-center px-3 py-1 pb-1 pt-1" style="font-size: 0.85rem; border-color: #e2e8f0;">
                <i class="bi bi-calendar3 me-2"></i>
                03/29/2026 - 04/04/2026
            </button>
        </div>
    </div>

    <!-- Alert Banner -->
    <div class="alert alert-dismissible fade show d-flex align-items-center mb-4 border-0 rounded" role="alert" style="background-color: var(--theme-orange-bg); color: #d97706; padding: 10px 15px;">
        <i class="bi bi-exclamation-circle text-danger me-2"></i>
        <span style="font-size: 0.85rem;">
            Your Product <strong class="text-danger">Apple Iphone 15</strong> is running Low, already below 5 Pcs.. 
            <a href="#" class="text-danger fw-bold text-decoration-underline ms-1">Add Stock</a>
        </span>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close" style="padding: 12px; font-size: 0.75rem;"></button>
    </div>

    <!-- Stats Grid Row 1 (Colored Blocks) -->
    <div class="row g-3 mb-3">
        
        <!-- Card 1 (Orange) -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card card-stats text-white border-0" style="background-color: var(--theme-orange);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="icon-box bg-white text-warning rounded text-center d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                        <i class="bi bi-currency-dollar fs-4" style="color: var(--theme-orange);"></i>
                    </div>
                    <div>
                        <p class="mb-0 card-title-soft text-white-50">Total Sales</p>
                        <div class="d-flex align-items-center">
                            <h4 class="mb-0 fw-bold me-2">Rp {{ number_format($totalSales, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2 (Dark Slate) -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card card-stats text-white border-0" style="background-color: #1e293b;">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="icon-box bg-white text-dark rounded text-center d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                        <i class="bi bi-star-fill fs-4 text-warning"></i>
                    </div>
                    <div>
                        <p class="mb-0 card-title-soft text-white-50">Product Paling Laku</p>
                        <div class="d-flex align-items-center">
                            <h5 class="mb-0 fw-bold me-2 text-truncate" style="max-width: 130px;">{{ $bestSellingProduct }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Card 3 (Teal) -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card card-stats text-white border-0" style="background-color: var(--theme-teal);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="icon-box bg-white rounded text-center d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                        <i class="bi bi-bag-check fs-4" style="color: var(--theme-teal);"></i>
                    </div>
                    <div>
                        <p class="mb-0 card-title-soft text-white-50">Total Purchase</p>
                        <div class="d-flex align-items-center">
                            <h4 class="mb-0 fw-bold me-2">Rp {{ number_format($totalPurchase, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4 (Blue) -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card card-stats text-white border-0" style="background-color: var(--theme-blue);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="icon-box bg-white rounded text-center d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                        <i class="bi bi-box-seam fs-4" style="color: var(--theme-blue);"></i>
                    </div>
                    <div>
                        <p class="mb-0 card-title-soft text-white-50">Total Product</p>
                        <div class="d-flex align-items-center">
                            <h4 class="mb-0 fw-bold me-2">{{ number_format($totalProduct, 0) }} Pcs</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Stats Grid Row 2 (White Blocks) -->
    <div class="row g-3 mb-4">
        
        <!-- Profit -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card card-stats-light border-0 h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h4 class="fw-bold mb-1 text-dark">$8,458,798</h4>
                            <small class="text-muted fw-medium">Profit</small>
                        </div>
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                           <i class="bi bi-layers-fill" style="font-size: 0.9rem;"></i>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-end mt-auto">
                        <small class="fw-medium"><span class="text-success">+35%</span> vs Last Month</small>
                        <a href="#" class="text-dark text-decoration-underline fw-bold" style="font-size: 0.75rem;">View All</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Due -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card card-stats-light border-0 h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h4 class="fw-bold mb-1 text-dark">$48,988,78</h4>
                            <small class="text-muted fw-medium">Invoice Due</small>
                        </div>
                        <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                           <i class="bi bi-file-earmark-text" style="font-size: 0.9rem;"></i>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-end mt-auto">
                        <small class="fw-medium"><span class="text-success">+35%</span> vs Last Month</small>
                        <a href="#" class="text-dark text-decoration-underline fw-bold" style="font-size: 0.75rem;">View All</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Expenses -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card card-stats-light border-0 h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h4 class="fw-bold mb-1 text-dark">$8,980,097</h4>
                            <small class="text-muted fw-medium">Total Expenses</small>
                        </div>
                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                           <i class="bi bi-box" style="font-size: 0.9rem;"></i>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-end mt-auto">
                        <small class="fw-medium"><span class="text-success">+41%</span> vs Last Month</small>
                        <a href="#" class="text-dark text-decoration-underline fw-bold" style="font-size: 0.75rem;">View All</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Payment Returns -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card card-stats-light border-0 h-100">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h4 class="fw-bold mb-1 text-dark">$78,458,798</h4>
                            <small class="text-muted fw-medium">Total Payment Returns</small>
                        </div>
                        <div class="bg-purple bg-opacity-10 text-purple rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                           <i class="bi bi-hash text-purple" style="font-size: 0.9rem;"></i>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-end mt-auto">
                        <small class="fw-medium"><span class="text-danger">-20%</span> vs Last Month</small>
                        <a href="#" class="text-dark text-decoration-underline fw-bold" style="font-size: 0.75rem;">View All</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Charts Section -->
    <div class="row g-3 mb-4">
        <!-- Sales Chart -->
        <div class="col-12 col-lg-8">
            <div class="card card-stats-light border-0 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 text-dark">Total Transaksi & Penjualan (Bulan Ini)</h5>
                    <div style="height: 300px;">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Best Selling Chart -->
        <div class="col-12 col-lg-4">
            <div class="card card-stats-light border-0 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 text-dark">Produk / Menu Terlaris</h5>
                    <div style="position: relative; height: 260px; display: flex; justify-content: center;">
                        <canvas id="bestSellingChart"></canvas>
                    </div>
                    <div class="mt-4 text-center">
                        <small class="text-muted">Berdasarkan Total Pcs Terjual</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card-title-soft {
        font-size: 0.8rem;
        font-weight: 500;
    }
    .card-stats {
        border-radius: 8px;
    }
    .card-stats h4 {
        letter-spacing: -0.5px;
    }
    .card-stats-light {
        background-color: #ffffff;
        border-radius: 8px;
    }
    .text-purple {
        color: #8b5cf6;
    }
    .bg-purple {
        background-color: #8b5cf6;
    }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dummy data for Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                datasets: [
                    {
                        label: 'Total Transaksi',
                        data: [120, 150, 180, 130, 200, 280, 250],
                        borderColor: '#ff9f43',
                        backgroundColor: 'rgba(255, 159, 67, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Total Product Terjual',
                        data: [200, 240, 290, 210, 320, 450, 400],
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Dummy data for Best Selling Chart
        const bestCtx = document.getElementById('bestSellingChart').getContext('2d');
        const bestSellingChart = new Chart(bestCtx, {
            type: 'doughnut',
            data: {
                labels: ['Iphone 15', 'Samsung S24', 'Nasi Goreng', 'Kopi Susu', 'Aksesoris'],
                datasets: [{
                    label: 'Terjual (Pcs)',
                    data: [300, 150, 450, 500, 200],
                    backgroundColor: [
                        '#1e293b', // dark slate
                        '#10b981', // teal
                        '#ff9f43', // orange
                        '#3b82f6', // blue
                        '#8b5cf6'  // purple
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            boxWidth: 12
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
