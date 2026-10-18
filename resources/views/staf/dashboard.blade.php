@extends('layouts.app')

@section('title', 'Dashboard Staf')

@section('content')
<!-- Hero Banner Staf -->
<div class="card-custom p-4 mb-4 border-0" style="background: linear-gradient(135deg, #e0f2fe 0%, #e0e7ff 60%, #f0fdf4 100%); border-radius: 20px;">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <span class="text-secondary small fw-semibold">Selamat Datang,</span>
            <h3 class="fw-bold text-dark mb-1">{{ Auth::user()->name }} 👋 (Staf Operasional)</h3>
            <p class="text-muted small mb-0">Catat pergerakan barang masuk dari supplier dan penjualan langsung ke pelanggan.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('staf.purchases.create') }}" class="btn btn-outline-primary btn-sm rounded-3">
                <i class="bi bi-cart-plus me-1"></i> Input Pembelian Stok
            </a>
            <a href="{{ route('staf.sales.create') }}" class="btn btn-primary-custom btn-sm rounded-3">
                <i class="bi bi-cart-check me-1"></i> Input Penjualan Baru
            </a>
        </div>
    </div>
</div>

<!-- Metrik Staf -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card-custom p-3 bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-semibold d-block mb-1">Produk Terdaftar</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ $totalProducts }}</h3>
                    <small class="text-primary fw-semibold">Katalog aktif</small>
                </div>
                <div class="p-3 rounded-4" style="background: #eff6ff; color: #2563eb; font-size: 1.35rem;">
                    <i class="bi bi-boxes"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-custom p-3 bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-semibold d-block mb-1">Perlu Restock</span>
                    <h3 class="fw-bold mb-0 {{ $lowStockCount > 0 ? 'text-danger' : 'text-success' }}">{{ $lowStockCount }} Item</h3>
                    <small class="text-muted">Stok &le; minimum</small>
                </div>
                <div class="p-3 rounded-4 {{ $lowStockCount > 0 ? 'bg-danger bg-opacity-10 text-danger' : 'bg-success bg-opacity-10 text-success' }}" style="font-size: 1.35rem;">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-custom p-3 bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-semibold d-block mb-1">Penjualan Hari Ini</span>
                    <h3 class="fw-bold mb-0 text-success">{{ $todaySalesCount }} Transaksi</h3>
                    <small class="text-muted">Kasir selesai</small>
                </div>
                <div class="p-3 rounded-4" style="background: #f0fdf4; color: #16a34a; font-size: 1.35rem;">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-custom p-3 bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-semibold d-block mb-1">Pembelian Hari Ini</span>
                    <h3 class="fw-bold mb-0 text-info">{{ $todayPurchasesCount }} Transaksi</h3>
                    <small class="text-muted">Stock-in masuk</small>
                </div>
                <div class="p-3 rounded-4" style="background: #faf5ff; color: #9333ea; font-size: 1.35rem;">
                    <i class="bi bi-truck"></i>
                </div>
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
