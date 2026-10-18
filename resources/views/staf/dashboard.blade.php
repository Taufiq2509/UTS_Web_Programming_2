@extends('layouts.app')

@section('title', 'Dashboard Staf')

@section('content')
<!-- Hero Welcome Banner Sesuai Mockup Panel 1 -->
<div class="hero-banner">
    <div>
        <div class="hero-greeting">Selamat Datang,</div>
        <h1 class="hero-title">{{ Auth::user()->name }} <span style="font-size: 1.25rem;">✨</span></h1>
        <p class="hero-desc">Kelola stok dan transaksi operasional toko Anda dengan mudah dan efisien.</p>
    </div>
    <div class="hero-illustration d-none d-lg-block">
        <!-- 3D Warehouse Box & Clipboard Graphic -->
        <svg width="220" height="120" viewBox="0 0 220 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <ellipse cx="140" cy="98" rx="70" ry="12" fill="#E2E8F0" opacity="0.6"/>
            <!-- Big Gold Box -->
            <g transform="translate(100, 32)">
                <path d="M40 0L80 18L40 36L0 18L40 0Z" fill="#FBBF24" />
                <path d="M0 18L40 36V70L0 52V18Z" fill="#F59E0B" />
                <path d="M40 36L80 18V52L40 70V36Z" fill="#D97706" />
                <path d="M40 0L40 36" stroke="#FEF3C7" stroke-width="2" stroke-dasharray="3 3"/>
                <path d="M20 9L60 27" stroke="#FEF3C7" stroke-width="2" stroke-dasharray="3 3"/>
            </g>
            <!-- Small Front Box -->
            <g transform="translate(68, 54)">
                <path d="M25 0L50 12L25 24L0 12L25 0Z" fill="#FCD34D" />
                <path d="M0 12L25 24V48L0 36V12Z" fill="#FBBF24" />
                <path d="M25 24L50 12V36L25 48V24Z" fill="#F59E0B" />
            </g>
            <!-- Clipboard with Analytics Chart -->
            <g transform="translate(142, 10)">
                <rect x="0" y="4" width="46" height="64" rx="4" fill="#3B82F6" />
                <rect x="3" y="10" width="40" height="55" rx="3" fill="#FFFFFF" />
                <rect x="14" y="0" width="18" height="8" rx="2" fill="#1E40AF" />
                <circle cx="23" cy="4" r="2" fill="#DBEAFE" />
                <!-- Chart Bars -->
                <rect x="8" y="44" width="6" height="15" rx="1.5" fill="#93C5FD" />
                <rect x="17" y="34" width="6" height="25" rx="1.5" fill="#60A5FA" />
                <rect x="26" y="24" width="6" height="35" rx="1.5" fill="#2563EB" />
                <!-- Line Chart -->
                <path d="M8 26L18 20L28 24L36 15" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </g>
            <!-- Potted Plant Decoration -->
            <g transform="translate(186, 48)">
                <path d="M12 28L8 45H22L18 28H12Z" fill="#3B82F6" />
                <path d="M15 28C10 20 8 10 15 2C22 10 20 20 15 28Z" fill="#10B981" />
                <path d="M18 28C24 22 28 14 26 6C20 12 18 20 18 28Z" fill="#059669" />
                <path d="M12 28C6 22 2 14 4 6C10 12 12 20 12 28Z" fill="#34D399" />
            </g>
        </svg>
    </div>
</div>

<!-- 4 Modern Stat Cards Sesuai Mockup Panel 1 -->
<div class="row g-3 mb-4">
    <!-- Card 1: Total Produk Aktif -->
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card wave-blue">
            <div class="kpi-header">
                <div class="kpi-icon-box" style="background: #eff6ff; color: #2563eb;">
                    <i class="bi bi-boxes"></i>
                </div>
                <a href="{{ route('staf.products.index') }}" class="kpi-arrow-btn" style="background: #f1f5f9; color: #64748b;" title="Buka Produk">
                    <i class="bi bi-arrow-up-right"></i>
                </a>
            </div>
            <div>
                <div class="kpi-title">Total Produk Aktif</div>
                <div class="kpi-number text-dark">{{ $totalProducts }}</div>
                <div class="kpi-subtext text-primary">{{ $totalCategories }} Kategori • {{ $totalSuppliers }} Supplier</div>
            </div>
        </div>
    </div>

    <!-- Card 2: Penjualan Hari Ini -->
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card wave-green">
            <div class="kpi-header">
                <div class="kpi-icon-box" style="background: #f0fdf4; color: #16a34a;">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <a href="{{ route('staf.sales.index') }}" class="kpi-arrow-btn" style="background: #f1f5f9; color: #64748b;" title="Buka Penjualan">
                    <i class="bi bi-arrow-up-right"></i>
                </a>
            </div>
            <div>
                <div class="kpi-title">Penjualan Hari Ini</div>
                <div class="kpi-number text-dark">Rp {{ number_format($todaySalesTotal, 0, ',', '.') }}</div>
                <div class="kpi-subtext text-success">{{ $todaySalesCount }} transaksi kasir hari ini</div>
            </div>
        </div>
    </div>

    <!-- Card 3: Pembelian Hari Ini (Stock-in) -->
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card wave-purple">
            <div class="kpi-header">
                <div class="kpi-icon-box" style="background: #faf5ff; color: #9333ea;">
                    <i class="bi bi-receipt-cutoff"></i>
                </div>
                <a href="{{ route('staf.purchases.index') }}" class="kpi-arrow-btn" style="background: #f1f5f9; color: #64748b;" title="Buka Pembelian">
                    <i class="bi bi-arrow-up-right"></i>
                </a>
            </div>
            <div>
                <div class="kpi-title">Stock-in (Pembelian)</div>
                <div class="kpi-number text-dark">{{ $todayPurchasesCount }} Masuk</div>
                <div class="kpi-subtext text-muted">Stock-in supplier selesai</div>
            </div>
        </div>
    </div>

    <!-- Card 4: Peringatan Stok Rendah -->
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card wave-red">
            <div class="kpi-header">
                <div class="kpi-icon-box" style="background: #fef2f2; color: #ef4444;">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <a href="{{ route('staf.purchases.create') }}" class="kpi-arrow-btn" style="background: #fef2f2; color: #ef4444;" title="Buat PO Restock">
                    <i class="bi bi-arrow-up-right"></i>
                </a>
            </div>
            <div>
                <div class="kpi-title">Peringatan Stok Rendah</div>
                <div class="kpi-number text-danger">{{ $lowStockCount }} Item</div>
                <div class="kpi-subtext text-muted">Stok &le; batas minimum</div>
            </div>
        </div>
    </div>
</div>

<!-- Restock Warning Bar Sesuai Panel 1 Mockup -->
@if($lowStockCount > 0)
<div class="restock-alert-banner">
    <div class="d-flex align-items-center gap-3">
        <div class="restock-icon-badge">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div>
            <div class="fw-bold text-dark" style="font-size: 1.05rem;">
                Perhatian: Ada {{ $lowStockCount }} Produk Membutuhkan Restock!
            </div>
            <div class="text-secondary small">
                Stok produk berikut sudah menyentuh atau berada di bawah kuota minimum.
            </div>
        </div>
    </div>
    <a href="{{ route('staf.purchases.create') }}" class="btn-restock-po">
        <i class="bi bi-cart-plus"></i>
        <span>Buat PO Pembelian</span>
    </a>
</div>
@endif

<!-- Quick Actions Staf -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card-custom p-4 bg-white border-start border-4 border-primary d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-bold mb-1 text-dark"><i class="bi bi-cart-plus text-primary me-2"></i>Pemasukan Stok (Purchase / Stock-In)</h6>
                <p class="text-muted small mb-0">Catat barang masuk dari supplier untuk menambah kuota stok otomatis.</p>
            </div>
            <a href="{{ route('staf.purchases.create') }}" class="btn btn-primary-custom btn-sm flex-shrink-0 ms-3">
                Input Pembelian &rarr;
            </a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card-custom p-4 bg-white border-start border-4 border-success d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-bold mb-1 text-dark"><i class="bi bi-cart-check text-success me-2"></i>Penjualan Kasir (Sales / Stock-Out)</h6>
                <p class="text-muted small mb-0">Catat transaksi kasir dan kurangi stok gudang secara otomatis dan atomik.</p>
            </div>
            <a href="{{ route('staf.sales.create') }}" class="btn btn-sm text-white flex-shrink-0 ms-3" style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); border-radius: 10px; font-weight: 600; padding: 0.6rem 1.25rem;">
                Input Penjualan &rarr;
            </a>
        </div>
    </div>
</div>

<!-- Daftar Transaksi Terbaru Sesuai Mockup Panel 1 -->
<div class="card-custom">
    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.15rem;">Daftar Transaksi Terbaru</h5>
            <small class="text-muted">Aktivitas penjualan dan pembelian stok terkini</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('staf.sales.index') }}" class="btn btn-sm btn-light border fw-semibold text-primary">
                Semua Penjualan &rarr;
            </a>
            <a href="{{ route('staf.purchases.index') }}" class="btn btn-sm btn-light border fw-semibold text-primary">
                Semua Pembelian &rarr;
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>No. Transaksi</th>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Pelanggan / Pemasok</th>
                    <th>Total</th>
                    <th class="text-center" style="width: 140px;">Status</th>
                    <th class="text-center" style="width: 80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $combinedTransactions = collect();
                    foreach($recentSales as $sale) {
                        $combinedTransactions->push([
                            'id' => $sale->id,
                            'number' => $sale->sale_number,
                            'date' => $sale->sold_at,
                            'type' => 'Penjualan',
                            'party' => $sale->customer_name ?: 'Pelanggan Umum',
                            'total' => $sale->total,
                            'status' => $sale->status,
                            'view_url' => route('staf.sales.show', $sale)
                        ]);
                    }
                    foreach($recentPurchases as $purchase) {
                        $combinedTransactions->push([
                            'id' => $purchase->id,
                            'number' => $purchase->purchase_number,
                            'date' => $purchase->purchased_at,
                            'type' => 'Pembelian',
                            'party' => $purchase->supplier->name ?? 'Supplier',
                            'total' => $purchase->total,
                            'status' => $purchase->status,
                            'view_url' => route('staf.purchases.show', $purchase)
                        ]);
                    }
                    $sorted = $combinedTransactions->sortByDesc('date')->take(6);
                @endphp

                @forelse($sorted as $index => $item)
                <tr>
                    <td class="text-secondary fw-semibold">{{ $loop->iteration }}</td>
                    <td class="fw-bold text-primary">{{ $item['number'] }}</td>
                    <td class="text-muted small">{{ $item['date']->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($item['type'] === 'Pembelian')
                            <span class="badge" style="background: #eff6ff; color: #2563eb; font-weight: 700; border-radius: 20px; padding: 0.3rem 0.65rem;">
                                Pembelian
                            </span>
                        @else
                            <span class="badge" style="background: #f0fdf4; color: #16a34a; font-weight: 700; border-radius: 20px; padding: 0.3rem 0.65rem;">
                                Penjualan
                            </span>
                        @endif
                    </td>
                    <td class="text-dark fw-medium">{{ $item['party'] }}</td>
                    <td class="fw-bold text-dark">Rp {{ number_format($item['total'], 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($item['status'] === 'completed')
                            <span class="status-badge status-completed">
                                <i class="bi bi-check-circle"></i> Selesai
                            </span>
                        @else
                            <span class="status-badge status-cancelled">
                                <i class="bi bi-x-circle"></i> Dibatalkan
                            </span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ $item['view_url'] }}" class="btn-action-view" title="Lihat Detail Transaksi">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">Belum ada riwayat transaksi tercatat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
