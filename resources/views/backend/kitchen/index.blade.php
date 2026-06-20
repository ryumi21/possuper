@extends('layouts.backend.app')

@section('title', 'Kitchen Display System')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 fw-bold text-dark" style="font-size: 1.5rem;">Kitchen Display System (KDS)</h3>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Daftar pesanan aktif dari meja pelanggan</p>
        </div>
        <div class="d-flex align-items-center">
            <span class="badge bg-light text-muted border me-3 py-2 px-3 d-flex align-items-center" style="font-size: 0.8rem; border-radius: 8px;">
                <span class="spinner-grow spinner-grow-sm text-warning me-2" role="status" style="width: 10px; height: 10px;"></span>
                Auto-update aktif
            </span>
            <button onclick="refreshOrders()" class="btn btn-warning text-white fw-bold d-flex align-items-center px-3 py-2" style="font-size: 0.85rem; border-radius: 8px; background-color: var(--theme-orange); border: none;">
                <i class="bi bi-arrow-clockwise me-1"></i> Refresh
            </button>
        </div>
    </div>

    <div id="kitchenOrdersContainer">
        @include('backend.kitchen.orders_grid')
    </div>
</div>

@push('scripts')
<script>
    function refreshOrders() {
        fetch("{{ route('kitchen.index') }}", {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error("HTTP error " + response.status);
            return response.text();
        })
        .then(html => {
            document.getElementById('kitchenOrdersContainer').innerHTML = html;
        })
        .catch(err => {
            console.error("Gagal memperbarui pesanan dapur:", err);
        });
    }

    function serveOrder(transactionId) {
        const card = document.getElementById(`order-card-${transactionId}`);
        if (card) {
            // Soft transition when cooking completes
            card.style.transition = 'all 0.3s ease';
            card.style.transform = 'scale(0.95)';
            card.style.opacity = '0.6';
            
            fetch(`/kitchen/${transactionId}/serve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error("HTTP error " + response.status);
                return response.json();
            })
            .then(res => {
                if (res.status === 'success') {
                    refreshOrders();
                }
            })
            .catch(err => {
                console.error("Error serving order:", err);
                card.style.transform = 'scale(1)';
                card.style.opacity = '1';
                alert("Gagal memperbarui status masakan. Coba lagi.");
            });
        }
    }

    function completeOrder(transactionId) {
        const card = document.getElementById(`order-card-${transactionId}`);
        if (card) {
            // Dismiss card completely when served
            card.style.transition = 'all 0.4s ease';
            card.style.opacity = '0';
            card.style.transform = 'scale(0.9)';
            
            setTimeout(() => {
                fetch(`/kitchen/${transactionId}/complete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error("HTTP error " + response.status);
                    return response.json();
                })
                .then(res => {
                    if (res.status === 'success') {
                        refreshOrders();
                    }
                })
                .catch(err => {
                    console.error("Error completing order:", err);
                    card.style.opacity = '0.9';
                    card.style.transform = 'scale(1)';
                    alert("Gagal menyelesaikan pesanan. Coba lagi.");
                });
            }, 400);
        }
    }

    // Auto poll every 10 seconds to get fresh data
    setInterval(refreshOrders, 10000);
</script>
@endpush
@endsection
