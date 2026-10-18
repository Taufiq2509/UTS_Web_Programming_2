@extends('layouts.app')

@section('title', 'Dashboard Owner')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Dashboard Eksekutif Owner</h4>
        <p class="text-muted small mb-0">Ringkasan performa penjualan, perputaran stok barang, dan kontrol penuh transaksi</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('owner.products.create') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Produk
        </a>
        <a href="{{ route('owner.purchases.create') }}" class="btn btn-primary-custom btn-sm">
            <i class="bi bi-cart-plus me-1"></i> Input Pembelian Stok
        </a>
    </div>
</div>

<!-- Grid Kartu Statistik Agregat Real-Time (Slide 18) -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card-custom stat-card">
            <div>
                <span class="text-muted small fw-semibold d-block mb-1">Total Produk Aktif</span>
                <h3 class="fw-bold text-dark mb-0">{{ number_format($totalProducts) }}</h3>
                <small class="text-primary mt-1 d-block">{{ $totalCategories }} Kategori | {{ $totalSuppliers }} Supplier</small>
            </div>
            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-boxes"></i>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card-custom stat-card">
            <div>
                <span class="text-muted small fw-semibold d-block mb-1">Penjualan Hari Ini</span>
                <h3 class="fw-bold text-success mb-0">Rp {{ number_format($salesToday, 0, ',', '.') }}</h3>
                <small class="text-muted mt-1 d-block">Real-time agregat hari ini</small>
            </div>
            <div class="stat-icon bg-success bg-opacity-10 text-success">
                <i class="bi bi-cash-stack"></i>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card-custom stat-card">
            <div>
                <span class="text-muted small fw-semibold d-block mb-1">Penjualan Bulan Ini</span>
                <h3 class="fw-bold text-dark mb-0">Rp {{ number_format($salesThisMonth, 0, ',', '.') }}</h3>
                <small class="text-muted mt-1 d-block">{{ $totalTransactions }} total transaksi</small>
            </div>
            <div class="stat-icon bg-info bg-opacity-10 text-info">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card-custom stat-card">
            <div>
                <span class="text-muted small fw-semibold d-block mb-1">Peringatan Stok Rendah</span>
                <h3 class="fw-bold {{ $lowStockCount > 0 ? 'text-danger' : 'text-success' }} mb-0">
                    {{ $lowStockCount }} Item
                </h3>
                <small class="text-muted mt-1 d-block">Stok &le; batas minimum</small>
            </div>
            <div class="stat-icon {{ $lowStockCount > 0 ? 'bg-danger text-danger' : 'bg-success text-success' }} bg-opacity-10">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
        </div>
    </div>
</div>

<!-- Alert Khusus Produk Stok Menipis -->
@if($lowStockCount > 0)
<div class="card-custom border-danger border-opacity-25 mb-4 overflow-hidden">
    <div class="p-3 bg-danger bg-opacity-10 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-bell-fill text-danger fs-5"></i>
            <div>
                <h6 class="fw-bold text-danger mb-0">Perhatian: Ada {{ $lowStockCount }} Produk Membutuhkan Restock!</h6>
                <small class="text-danger text-opacity-75">Stok produk berikut sudah menyentuh atau berada di bawah kuota minimum.</small>
            </div>
        </div>
        <a href="{{ route('owner.purchases.create') }}" class="btn btn-danger btn-sm">
            <i class="bi bi-cart-plus me-1"></i> Buat PO Pembelian
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Sisa Stok</th>
                    <th>Batas Minimum</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lowStockProducts as $prod)
                <tr>
                    <td class="fw-semibold text-secondary">{{ $prod->sku }}</td>
                    <td class="fw-bold">{{ $prod->name }}</td>
                    <td><span class="badge bg-light text-dark border">{{ $prod->category->name ?? '-' }}</span></td>
                    <td>
                        <span class="badge bg-danger fs-6">{{ $prod->stock }} unit</span>
                    </td>
                    <td class="text-muted">{{ $prod->minimum_stock }} unit</td>
                    <td>
                        @if($prod->stock <= 0)
                            <span class="badge bg-dark text-white">Habis Total</span>
                        @else
                            <span class="badge bg-warning text-dark">Kritis</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Tabel Transaksi Terbaru (Penjualan & Pembelian) -->
<div class="row g-4">
    <!-- Penjualan Terakhir -->
    <div class="col-lg-6">
        <div class="card-custom h-100">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-cart-check text-success me-2"></i> Penjualan Terakhir</h6>
                <a href="{{ route('owner.sales.index') }}" class="btn btn-sm btn-link text-decoration-none">Lihat Semua &rarr;</a>
            </div>
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>No. Transaksi</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSales as $sale)
                        <tr>
                            <td>
                                <a href="{{ route('owner.sales.show', $sale) }}" class="fw-bold text-decoration-none text-primary">
                                    {{ $sale->sale_number }}
                                </a>
                                <br><small class="text-muted">{{ $sale->sold_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td>{{ $sale->customer_name }}</td>
                            <td class="fw-bold">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                            <td>
                                @if($sale->status === 'completed')
                                    <span class="badge bg-success bg-opacity-15 text-success">Selesai</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-15 text-danger">Dibatalkan</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('owner.sales.show', $sale) }}" class="btn btn-sm btn-light border" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">Belum ada transaksi penjualan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pembelian Terakhir -->
    <div class="col-lg-6">
        <div class="card-custom h-100">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-truck text-primary me-2"></i> Pembelian Stok Terakhir</h6>
                <a href="{{ route('owner.purchases.index') }}" class="btn btn-sm btn-link text-decoration-none">Lihat Semua &rarr;</a>
            </div>
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>No. PO</th>
                            <th>Pemasok</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPurchases as $po)
                        <tr>
                            <td>
                                <a href="{{ route('owner.purchases.show', $po) }}" class="fw-bold text-decoration-none text-primary">
                                    {{ $po->purchase_number }}
                                </a>
                                <br><small class="text-muted">{{ $po->purchased_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td>{{ $po->supplier->name ?? '-' }}</td>
                            <td class="fw-bold">Rp {{ number_format($po->total, 0, ',', '.') }}</td>
                            <td>
                                @if($po->status === 'completed')
                                    <span class="badge bg-success bg-opacity-15 text-success">Selesai</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-15 text-danger">Dibatalkan</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('owner.purchases.show', $po) }}" class="btn btn-sm btn-light border" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">Belum ada transaksi pembelian.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
