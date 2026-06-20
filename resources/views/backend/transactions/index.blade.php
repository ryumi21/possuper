@extends('layouts.backend.app')

@section('title', 'Tri Fusion - Log Transaksi')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1 fw-bold text-dark" style="font-size: 1.5rem;">Log Transaksi POS</h3>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Daftar dan detail riwayat transaksi yang tersimpan di sistem</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 8px;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 premium-table-grid" id="transactionsTable">
                    <thead class="table-light text-muted">
                        <tr style="font-size: 0.85rem;">
                            <th class="ps-4">No. Transaksi</th>
                            <th>Tanggal / Waktu</th>
                            <th>No. Meja</th>
                            <th>Total Menu</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $tx)
                            <tr style="font-size: 0.9rem;">
                                <td class="ps-4 fw-bold text-dark">TX-{{ str_pad($tx->Oid, 6, '0', STR_PAD_LEFT) }}</td>
                                <td class="text-muted">{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge bg-light text-dark fw-bold border px-2 py-1" style="font-size: 0.78rem; border-radius: 6px;">
                                        <i class="bi bi-hash text-warning me-1"></i>{{ $tx->Table_No }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-semibold text-secondary">
                                        {{ $tx->details->sum('Value') }} Porsi
                                    </span>
                                </td>
                                <td>
                                    @if($tx->Status == 1)
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1" style="font-size: 0.75rem;">
                                            <i class="bi bi-clock-history me-1"></i> Lunas (Antre Masak)
                                        </span>
                                    @elseif($tx->Status == 2)
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2.5 py-1" style="font-size: 0.75rem;">
                                            <i class="bi bi-send-fill me-1"></i> Sedang Diantar
                                        </span>
                                    @elseif($tx->Status == 3)
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1" style="font-size: 0.75rem;">
                                            <i class="bi bi-check-circle-fill me-1"></i> Selesai / Diterima
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2.5 py-1" style="font-size: 0.75rem;">
                                            Status {{ $tx->Status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <!-- Detail Button triggers modal -->
                                        <button type="button" class="premium-btn-icon" data-bs-toggle="modal" data-bs-target="#detailModal-{{ $tx->Oid }}" title="Detail Menu">
                                            <i class="bi bi-eye text-primary"></i>
                                        </button>
                                        
                                        <!-- Delete form -->
                                        <form action="{{ route('transactions.destroy', $tx->Oid) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="premium-btn-icon delete-btn" title="Hapus Log">
                                                <i class="bi bi-trash text-danger"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Detail Modal for each transaction -->
                            <div class="modal fade" id="detailModal-{{ $tx->Oid }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                                        <div class="modal-header border-0 bg-light p-3">
                                            <h6 class="modal-title fw-bold text-dark">
                                                <i class="bi bi-journal-text text-theme-orange me-2"></i> Detail Order - TX-{{ str_pad($tx->Oid, 6, '0', STR_PAD_LEFT) }}
                                            </h6>
                                            <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="d-flex justify-content-between pb-3 mb-3 border-bottom text-muted small">
                                                <div>
                                                    <span class="d-block">No. Meja: <strong>{{ $tx->Table_No }}</strong></span>
                                                    <span class="d-block">Waktu Order: {{ $tx->created_at->format('d/m/Y H:i:s') }}</span>
                                                </div>
                                                <div class="text-end">
                                                    @if($tx->Status == 1)
                                                        <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-2 py-1">Lunas (Antre Masak)</span>
                                                    @elseif($tx->Status == 2)
                                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-1">Sedang Diantar</span>
                                                    @elseif($tx->Status == 3)
                                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1">Selesai / Diterima</span>
                                                    @else
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2 py-1">Status {{ $tx->Status }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <h6 class="fw-bold text-dark mb-3">Daftar Item Menu</h6>
                                            <ul class="list-group list-group-flush mb-0">
                                                @foreach($tx->details as $detail)
                                                    @php
                                                        // Identify the menu item name from either product or food
                                                        $itemName = 'Unknown Item';
                                                        if ($detail->product) {
                                                            $itemName = $detail->product->Name;
                                                        } elseif ($detail->food) {
                                                            $itemName = $detail->food->Name;
                                                        }
                                                    @endphp
                                                    <li class="list-group-item px-0 py-2.5 d-flex justify-content-between align-items-start border-bottom border-light">
                                                        <div>
                                                            <span class="fw-bold text-dark">{{ $itemName }}</span>
                                                            <span class="text-muted d-block small mt-0.5">Jumlah: {{ $detail->Value }} porsi</span>
                                                            @if($detail->Note)
                                                                <small class="text-warning d-block mt-1" style="font-size: 0.75rem;">
                                                                    <i class="bi bi-chat-right-text me-1"></i> Catatan: "{{ $detail->Note }}"
                                                                </small>
                                                            @endif
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="modal-footer border-0 p-3 bg-light">
                                            <button type="button" class="btn btn-secondary fw-semibold px-4 py-2" data-bs-dismiss="modal" style="border-radius: 10px; font-size:0.85rem;">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-journal-x fs-2 opacity-50 d-block mb-3"></i>
                                    Tidak ada transaksi yang tersimpan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

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
