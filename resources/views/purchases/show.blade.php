@extends('layouts.app')

@section('title', 'Detail Transaksi Pembelian ' . $purchase->purchase_number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Purchase Order (PO): {{ $purchase->purchase_number }}</h4>
        <p class="text-muted small mb-0">Rincian tanda terima dan dokumen pengadaan barang inventaris</p>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-printer me-1"></i> Cetak Dokumen
        </button>
        <a href="{{ Auth::user()->isOwner() ? route('owner.purchases.index') : route('staf.purchases.index') }}" class="btn btn-sm btn-light border">
            &larr; Kembali
        </a>
    </div>
</div>

<div class="card-custom p-4 mb-4">
    <!-- Status & Info Transaksi -->
    <div class="row g-4 mb-4 pb-4 border-bottom">
        <div class="col-md-3">
            <small class="text-muted d-block mb-1">Status Transaksi</small>
            @if($purchase->status === 'completed')
                <span class="badge bg-success bg-opacity-15 text-success fs-6 px-3 py-2">
                    <i class="bi bi-check-circle me-1"></i> Selesai (Stok Diterima)
                </span>
            @else
                <span class="badge bg-danger bg-opacity-15 text-danger fs-6 px-3 py-2">
                    <i class="bi bi-x-circle me-1"></i> Dibatalkan
                </span>
            @endif
        </div>
        <div class="col-md-3">
            <small class="text-muted d-block mb-1">Waktu Pembelian</small>
            <span class="fw-bold text-dark fs-6">{{ $purchase->purchased_at->format('d F Y, H:i') }}</span>
        </div>
        <div class="col-md-3">
            <small class="text-muted d-block mb-1">Pemasok (Supplier)</small>
            <span class="fw-bold text-dark fs-6">{{ $purchase->supplier->name ?? '-' }}</span>
            <small class="text-muted d-block">{{ $purchase->supplier->phone ?? '' }}</small>
        </div>
        <div class="col-md-3">
            <small class="text-muted d-block mb-1">Petugas Pencatat</small>
            <span class="fw-bold text-dark fs-6">{{ $purchase->user->name ?? '-' }}</span>
            <small class="text-muted d-block">{{ $purchase->user->email ?? '' }}</small>
        </div>
    </div>

    @if($purchase->notes)
    <div class="alert alert-light border small mb-4">
        <strong>Catatan Transaksi:</strong> {{ $purchase->notes }}
    </div>
    @endif

    <!-- Daftar Item Pembelian -->
    <h6 class="fw-bold mb-3"><i class="bi bi-list-check me-2 text-primary"></i> Rincian Barang yang Diterima</h6>
    <div class="table-responsive mb-4">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Kode SKU</th>
                    <th>Nama Produk</th>
                    <th class="text-center">Kuantitas Masuk</th>
                    <th class="text-end">Biaya Beli Satuan</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchase->items as $idx => $item)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td class="fw-semibold text-secondary">{{ $item->product->sku ?? '-' }}</td>
                    <td class="fw-bold">{{ $item->product->name ?? 'Produk Dihapus' }}</td>
                    <td class="text-center fw-bold text-success">+{{ $item->quantity }} unit</td>
                    <td class="text-end">Rp {{ number_format($item->unit_cost, 0, ',', '.') }}</td>
                    <td class="text-end fw-bold text-dark">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-light">
                    <td colspan="5" class="text-end fw-bold fs-6">Total Pembelian:</td>
                    <td class="text-end fw-bold text-primary fs-5">Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Opsi Pembatalan untuk Owner -->
    @if(Auth::user()->isOwner() && $purchase->status === 'completed')
    <div class="p-3 bg-danger bg-opacity-10 rounded-3 d-flex justify-content-between align-items-center">
        <div>
            <h6 class="fw-bold text-danger mb-0"><i class="bi bi-shield-exclamation me-1"></i> Batalkan Transaksi Pembelian</h6>
            <small class="text-danger text-opacity-75">Sistem akan memeriksa ketersediaan stok aktual dan menarik kembali penambahan stok secara atomik.</small>
        </div>
        <form action="{{ route('owner.purchases.cancel', $purchase) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi PO ini? Stok barang akan dikurangi kembali secara konsisten.');">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">
                Batalkan PO & Kurangi Stok
            </button>
        </form>
    </div>
    @endif
</div>
@endsection
