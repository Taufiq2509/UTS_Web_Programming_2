@extends('layouts.app')

@section('title', 'Struk & Invoice Penjualan ' . $sale->sale_number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Invoice Penjualan: {{ $sale->sale_number }}</h4>
        <p class="text-muted small mb-0">Rincian struk transaksi kasir dan bukti pengurangan inventaris</p>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-printer me-1"></i> Cetak Struk Penjualan
        </button>
        <a href="{{ Auth::user()->isOwner() ? route('owner.sales.index') : (Auth::user()->isStaf() ? route('staf.sales.index') : route('customer.dashboard')) }}" class="btn btn-sm btn-light border">
            &larr; Kembali
        </a>
    </div>
</div>

<div class="card-custom p-4 mb-4">
    <!-- Status & Info Transaksi -->
    <div class="row g-4 mb-4 pb-4 border-bottom">
        <div class="col-md-3">
            <small class="text-muted d-block mb-1">Status Transaksi</small>
            @if($sale->status === 'completed')
                <span class="badge bg-success bg-opacity-15 text-success fs-6 px-3 py-2">
                    <i class="bi bi-check-circle me-1"></i> Selesai (Lunas)
                </span>
            @else
                <span class="badge bg-danger bg-opacity-15 text-danger fs-6 px-3 py-2">
                    <i class="bi bi-x-circle me-1"></i> Dibatalkan
                </span>
            @endif
        </div>
        <div class="col-md-3">
            <small class="text-muted d-block mb-1">Waktu Transaksi</small>
            <span class="fw-bold text-dark fs-6">{{ $sale->sold_at->format('d F Y, H:i') }}</span>
        </div>
        <div class="col-md-3">
            <small class="text-muted d-block mb-1">Pelanggan</small>
            <span class="fw-bold text-dark fs-6">{{ $sale->customer_name }}</span>
        </div>
        <div class="col-md-3">
            <small class="text-muted d-block mb-1">Kasir / Operator</small>
            <span class="fw-bold text-dark fs-6">{{ $sale->user->name ?? '-' }}</span>
            <small class="text-muted d-block">{{ $sale->user->email ?? '' }}</small>
        </div>
    </div>

    @if($sale->notes)
    <div class="alert alert-light border small mb-4">
        <strong>Catatan Kasir:</strong> {{ $sale->notes }}
    </div>
    @endif

    <!-- Daftar Item Penjualan -->
    <h6 class="fw-bold mb-3"><i class="bi bi-cart-check me-2 text-success"></i> Rincian Produk yang Terjual</h6>
    <div class="table-responsive mb-4">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Kode SKU</th>
                    <th>Nama Produk</th>
                    <th class="text-center">Kuantitas</th>
                    <th class="text-end">Harga Satuan</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $idx => $item)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td class="fw-semibold text-secondary">{{ $item->product->sku ?? '-' }}</td>
                    <td class="fw-bold">{{ $item->product->name ?? 'Produk Dihapus' }}</td>
                    <td class="text-center fw-bold text-danger">-{{ $item->quantity }} unit</td>
                    <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-end fw-bold text-dark">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-light">
                    <td colspan="5" class="text-end fw-bold fs-6">Total Pembayaran:</td>
                    <td class="text-end fw-bold text-success fs-5">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Opsi Pembatalan untuk Owner -->
    @if(Auth::user()->isOwner() && $sale->status === 'completed')
    <div class="p-3 bg-danger bg-opacity-10 rounded-3 d-flex justify-content-between align-items-center">
        <div>
            <h6 class="fw-bold text-danger mb-0"><i class="bi bi-arrow-counterclockwise me-1"></i> Batalkan Penjualan & Pulihkan Stok</h6>
            <small class="text-danger text-opacity-75">Sesuai aturan bisnis Slide 8: Transaksi selesai dapat dibatalkan oleh Owner dengan memulihkan stok kembali ke gudang.</small>
        </div>
        <form action="{{ route('owner.sales.cancel', $sale) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi penjualan ini? Stok barang akan dikembalikan ke inventaris.');">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">
                Batalkan Transaksi & Pulihkan Stok
            </button>
        </form>
    </div>
    @endif
</div>
@endsection
