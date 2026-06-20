@if($orders->isEmpty())
    <div class="card border-0 shadow-sm py-5 text-center mt-4" style="border-radius: 12px;">
        <div class="card-body">
            <div class="mb-3 text-warning">
                <i class="bi bi-check2-circle" style="font-size: 4.5rem; color: var(--theme-teal);"></i>
            </div>
            <h4 class="fw-bold text-dark mb-1">Semua Pesanan Selesai!</h4>
            <p class="text-muted mb-0">Dapur dalam keadaan bersih. Menunggu pesanan masuk dari kasir.</p>
        </div>
    </div>
@else
    <div class="row g-3">
        @foreach($orders as $order)
            @php
                $isGoingToServe = ($order->Status == 2);
                $borderColor = $isGoingToServe ? 'var(--theme-blue)' : 'var(--theme-orange)';
                $cardOpacity = $isGoingToServe ? 'opacity: 0.9;' : '';
                $cardBg = $isGoingToServe ? 'background-color: #f8fafc;' : 'background-color: #ffffff;';
            @endphp
            <div class="col-12 col-md-6 col-lg-4" id="order-card-{{ $order->Oid }}" style="{{ $cardOpacity }}">
                <div class="card border-0 shadow-sm h-100 flex-column justify-content-between" style="border-radius: 12px; border-top: 5px solid {{ $borderColor }} !important; {{ $cardBg }}">
                    
                    <!-- Card Header -->
                    <div class="card-header border-0 pt-3 px-3 pb-2 d-flex justify-content-between align-items-center" style="background: transparent;">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge fw-extrabold px-3 py-2 fs-6" style="border-radius: 8px; {{ $isGoingToServe ? 'background-color: rgba(59, 130, 246, 0.1); color: var(--theme-blue) !important;' : 'background-color: var(--theme-orange-bg); color: var(--theme-orange) !important;' }}">
                                Meja {{ $order->Table_No }}
                            </span>
                            @if($isGoingToServe)
                                <span class="badge bg-primary text-white px-2 py-1.5" style="font-size: 0.72rem; border-radius: 6px;">
                                    <i class="bi bi-send-fill me-1"></i> Going to Serve
                                </span>
                            @endif
                        </div>
                        <small class="text-muted fw-bold">
                            <i class="bi bi-clock me-1"></i> {{ $order->created_at->diffForHumans(null, true) }}
                        </small>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body px-3 py-2 flex-grow-1" style="background: transparent;">
                        <hr class="my-1 border-light">
                        <ul class="list-unstyled mb-0 mt-2">
                            @foreach($order->details as $detail)
                                @php
                                    $itemName = 'Unknown';
                                    if ($detail->product) {
                                        $itemName = $detail->product->Name;
                                    } elseif ($detail->food) {
                                        $itemName = $detail->food->Name;
                                    }
                                @endphp
                                <li class="mb-3 d-flex align-items-start">
                                    <div class="fw-bold me-2 text-dark bg-light px-2 py-1 border rounded" style="font-size: 0.9rem; min-width: 32px; text-align: center;">
                                        {{ $detail->Value }}x
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="fw-semibold text-dark" style="font-size: 0.95rem;">{{ $itemName }}</span>
                                        @if($detail->Note)
                                            <div class="mt-1 p-2 rounded text-danger bg-danger-subtle d-flex align-items-start" style="font-size: 0.8rem; background-color: rgba(239, 68, 68, 0.08); border-left: 3px solid #ef4444;">
                                                <i class="bi bi-chat-left-text-fill me-2 mt-0.5"></i>
                                                <div>
                                                    <strong>Catatan:</strong> {{ $detail->Note }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Card Footer -->
                    <div class="card-footer border-0 px-3 pb-3 pt-0" style="background: transparent;">
                        @if($isGoingToServe)
                            <button onclick="completeOrder({{ $order->Oid }})" class="btn btn-primary w-100 fw-bold py-2.5 d-flex align-items-center justify-content-center" style="border-radius: 8px; font-size: 0.9rem; background-color: var(--theme-blue); border: none; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2);">
                                <i class="bi bi-check-all me-2 fs-5"></i> Selesai Disajikan (Waiter Balik)
                            </button>
                        @else
                            <button onclick="serveOrder({{ $order->Oid }})" class="btn btn-success w-100 fw-bold py-2.5 d-flex align-items-center justify-content-center" style="border-radius: 8px; font-size: 0.9rem; background-color: #10b981; border: none; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);">
                                <i class="bi bi-send-check me-2 fs-5"></i> Selesai Masak (Going to Serve)
                            </button>
                        @endif
                    </div>

                </div>
            </div>
        @endforeach
    </div>
@endif
