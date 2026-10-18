@extends('layouts.app')

@section('title', 'Dashboard Staf')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Dashboard Operasional Staf</h4>
        <p class="text-muted small mb-0">Manajemen pergerakan stok harian: Pencatatan Pembelian Masuk dan Transaksi Penjualan</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('staf.purchases.create') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-cart-plus me-1"></i> Input Pembelian Stok
        </a>
        <a href="{{ route('staf.sales.create') }}" class="btn btn-primary-custom btn-sm">
            <i class="bi bi-cart-check me-1"></i> Input Penjualan Baru
        </a>
    </div>
</div>

<!-- Metrik Staf -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card-custom stat-card">
            <div>
                <span class="text-muted small fw-semibold d-block mb-1">Produk Terdaftar</span>
                <h3 class="fw-bold mb-0 text-dark">{{ $totalProducts }}</h3>
                <small class="text-muted">Katalog aktif</small>
            </div>
            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-boxes"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-custom stat-card">
            <div>
                <span class="text-muted small fw-semibold d-block mb-1">Perlu Restock</span>
                <h3 class="fw-bold mb-0 {{ $lowStockCount > 0 ? 'text-danger' : 'text-success' }}">{{ $lowStockCount }}</h3>
                <small class="text-muted">Stok &le; batas minimum</small>
            </div>
            <div class="stat-icon {{ $lowStockCount > 0 ? 'bg-danger text-danger' : 'bg-success text-success' }} bg-opacity-10">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-custom stat-card">
            <div>
                <span class="text-muted small fw-semibold d-block mb-1">Penjualan Hari Ini</span>
                <h3 class="fw-bold mb-0 text-success">{{ $todaySalesCount }}</h3>
                <small class="text-muted">Transaksi selesai</small>
            </div>
            <div class="stat-icon bg-success bg-opacity-10 text-success">
                <i class="bi bi-receipt"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-custom stat-card">
            <div>
                <span class="text-muted small fw-semibold d-block mb-1">Pembelian Hari Ini</span>
                <h3 class="fw-bold mb-0 text-info">{{ $todayPurchasesCount }}</h3>
                <small class="text-muted">Stock-in tercatat</small>
            </div>
            <div class="stat-icon bg-info bg-opacity-10 text-info">
                <i class="bi bi-truck"></i>
            </div>
        </div>
    </div>
</div>

<!-- Aksi Cepat Operasional -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card-custom p-4 bg-white border-start border-4 border-primary">
            <h5 class="fw-bold mb-2"><i class="bi bi-cart-plus text-primary me-2"></i>Pemasukan Stok (Purchase / Stock-In)</h5>
            <p class="text-muted small mb-3">Gunakan form ini ketika barang pesanan dari supplier telah sampai di toko atau gudang untuk menambah stok secara otomatis.</p>
            <a href="{{ route('staf.purchases.create') }}" class="btn btn-primary-custom btn-sm">
                Buka Form Pembelian &rarr;
            </a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card-custom p-4 bg-white border-start border-4 border-success">
            <h5 class="fw-bold mb-2"><i class="bi bi-cart-check text-success me-2"></i>Pengeluaran Stok (Sales / Stock-Out)</h5>
            <p class="text-muted small mb-3">Catat penjualan kasir atau grosir. Sistem secara otomatis akan memvalidasi kecukupan stok dan mengunci data harga dari database server.</p>
            <a href="{{ route('staf.sales.create') }}" class="btn btn-success btn-sm">
                Buka Form Kasir Penjualan &rarr;
            </a>
        </div>
    </div>
</div>

<!-- Transaksi Terkini Staf -->
<div class="card-custom">
    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-secondary"></i> Transaksi Penjualan Terakhir</h6>
        <a href="{{ route('staf.sales.index') }}" class="btn btn-sm btn-link text-decoration-none">Semua Transaksi &rarr;</a>
    </div>
    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>No. Transaksi</th>
                    <th>Nama Pelanggan</th>
                    <th>Jumlah Item</th>
                    <th>Total Transaksi</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentSales as $sale)
                <tr>
                    <td class="fw-bold text-primary">{{ $sale->sale_number }}</td>
                    <td>{{ $sale->customer_name }}</td>
                    <td>{{ $sale->items->sum('quantity') }} unit</td>
                    <td class="fw-bold">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                    <td class="text-muted small">{{ $sale->sold_at->diffForHumans() }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-3">Belum ada transaksi penjualan tercatat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
