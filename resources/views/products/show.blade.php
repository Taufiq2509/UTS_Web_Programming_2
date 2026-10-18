@extends('layouts.app')

@section('title', 'Detail Produk ' . $product->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Detail & Buku Riwayat Stok Produk</h4>
        <p class="text-muted small mb-0">Informasi spesifikasi barang serta riwayat pergerakan stok masuk dan keluar</p>
    </div>
    <a href="{{ Auth::user()->isOwner() ? route('owner.products.index') : route('staf.products.index') }}" class="btn btn-sm btn-light border">
        &larr; Kembali ke Daftar
    </a>
</div>

<!-- Header Info Produk -->
<div class="row g-4 mb-4">
    <div class="col-lg-5">
        <div class="card-custom p-4 h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <span class="badge bg-light text-dark border px-3 py-2 fs-6">
                    <i class="bi bi-tag me-1"></i> {{ $product->category->name ?? 'Tanpa Kategori' }}
                </span>
                <span class="badge {{ $product->isLowStock() ? 'bg-danger' : 'bg-success' }} px-3 py-2 fs-6">
                    Stok: {{ $product->stock }} unit
                </span>
            </div>

            <h3 class="fw-bold text-dark mb-2">{{ $product->name }}</h3>
            <p class="text-secondary small mb-4">Kode SKU: <strong>{{ $product->sku }}</strong></p>

            <div class="border rounded-3 p-3 bg-light mb-3">
                <div class="row g-2">
                    @if(Auth::user()->isOwner())
                    <div class="col-6">
                        <small class="text-muted d-block">Harga Beli Terakhir</small>
                        <span class="fw-bold fs-6">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="col-6">
                        <small class="text-muted d-block">Harga Jual Konsumen</small>
                        <span class="fw-bold text-primary fs-6">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="col-6 mt-2">
                        <small class="text-muted d-block">Batas Minimum Stok</small>
                        <span class="fw-bold">{{ $product->minimum_stock }} unit</span>
                    </div>
                    @if(Auth::user()->isOwner())
                    <div class="col-6 mt-2">
                        <small class="text-muted d-block">Estimasi Margin</small>
                        <span class="fw-bold text-success">
                            Rp {{ number_format($product->selling_price - $product->purchase_price, 0, ',', '.') }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            @if(Auth::user()->isOwner())
            <div class="d-flex gap-2">
                <a href="{{ route('owner.products.edit', $product) }}" class="btn btn-outline-warning btn-sm w-100">
                    <i class="bi bi-pencil me-1"></i> Ubah Data Produk
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Riwayat Pembelian (Stock-in) untuk produk ini -->
    <div class="col-lg-7">
        <div class="card-custom mb-4">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-cart-plus me-2"></i>Riwayat Pembelian Masuk (Stock-In)</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>No. PO</th>
                            <th>Tanggal</th>
                            <th>Pemasok</th>
                            <th>Qty Masuk</th>
                            <th>Biaya Beli</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($product->purchaseItems as $item)
                        <tr>
                            <td class="fw-bold text-dark">{{ $item->purchase->purchase_number ?? '-' }}</td>
                            <td class="text-muted small">{{ $item->purchase->purchased_at->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ $item->purchase->supplier->name ?? '-' }}</td>
                            <td class="fw-bold text-success">+{{ $item->quantity }} unit</td>
                            <td>Rp {{ number_format($item->unit_cost, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">Belum ada riwayat pembelian untuk produk ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Riwayat Penjualan (Stock-out) untuk produk ini -->
        <div class="card-custom">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-success"><i class="bi bi-cart-check me-2"></i>Riwayat Penjualan Keluar (Stock-Out)</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>No. Transaksi</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Qty Keluar</th>
                            <th>Harga Jual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($product->saleItems as $sItem)
                        <tr>
                            <td class="fw-bold text-dark">{{ $sItem->sale->sale_number ?? '-' }}</td>
                            <td class="text-muted small">{{ $sItem->sale->sold_at->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ $sItem->sale->customer_name ?? '-' }}</td>
                            <td class="fw-bold text-danger">-{{ $sItem->quantity }} unit</td>
                            <td>Rp {{ number_format($sItem->price, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">Belum ada riwayat penjualan untuk produk ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
