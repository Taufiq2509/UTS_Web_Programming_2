@extends('layouts.app')

@section('title', 'Daftar Transaksi Penjualan (Sales / Stock-Out)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Daftar Penjualan (Sales / Stock-Out)</h4>
        <p class="text-muted small mb-0">Catatan transaksi penjualan barang ke pelanggan dan riwayat pengurangan stok</p>
    </div>
    <a href="{{ Auth::user()->isOwner() ? route('owner.sales.create') : route('staf.sales.create') }}" class="btn btn-primary-custom btn-sm">
        <i class="bi bi-cart-plus me-1"></i> Input Penjualan Baru
    </a>
</div>

<!-- Filter dan Pencarian Sesuai Slide 13 & 18 -->
<div class="card-custom p-3 mb-4">
    <form method="GET" action="{{ Auth::user()->isOwner() ? route('owner.sales.index') : route('staf.sales.index') }}" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label small fw-semibold text-muted mb-1">Cari No. TRX / Pelanggan</label>
            <input type="text" name="search" class="form-control form-control-sm" placeholder="TRX-2026 atau nama..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold text-muted mb-1">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">Semua Status</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold text-muted mb-1">Dari Tanggal</label>
            <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold text-muted mb-1">Sampai Tanggal</label>
            <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
        </div>
        <div class="col-md-3 d-flex gap-1">
            <button type="submit" class="btn btn-sm btn-primary-custom w-100">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
            @if(request('search') || request('status') || request('start_date') || request('end_date'))
                <a href="{{ Auth::user()->isOwner() ? route('owner.sales.index') : route('staf.sales.index') }}" class="btn btn-sm btn-light border">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- Tabel Penjualan -->
<div class="card-custom">
    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nomor Transaksi</th>
                    <th>Tanggal Penjualan</th>
                    <th>Nama Pelanggan</th>
                    <th>Kasir / Petugas</th>
                    <th>Total Item</th>
                    <th>Total Nilai Penjualan</th>
                    <th>Status</th>
                    <th style="width: 130px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $index => $sale)
                <tr>
                    <td>{{ $sales->firstItem() + $index }}</td>
                    <td class="fw-bold text-primary">
                        <a href="{{ Auth::user()->isOwner() ? route('owner.sales.show', $sale) : route('staf.sales.show', $sale) }}" class="text-decoration-none">
                            {{ $sale->sale_number }}
                        </a>
                    </td>
                    <td class="text-muted small">{{ $sale->sold_at->format('d/m/Y H:i') }}</td>
                    <td class="fw-semibold">{{ $sale->customer_name }}</td>
                    <td><span class="badge bg-light text-secondary border">{{ $sale->user->name ?? '-' }}</span></td>
                    <td>{{ $sale->items->count() }} jenis ({{ $sale->items->sum('quantity') }} unit)</td>
                    <td class="fw-bold text-success fs-6">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                    <td>
                        @if($sale->status === 'completed')
                            <span class="badge bg-success bg-opacity-15 text-success">Selesai</span>
                        @else
                            <span class="badge bg-danger bg-opacity-15 text-danger">Dibatalkan</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ Auth::user()->isOwner() ? route('owner.sales.show', $sale) : route('staf.sales.show', $sale) }}" class="btn btn-sm btn-light border" title="Lihat Invoice">
                                <i class="bi bi-eye"></i> Detail
                            </a>

                            @if(Auth::user()->isOwner() && $sale->status === 'completed')
                            <form action="{{ route('owner.sales.cancel', $sale) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi penjualan ini? Stok barang akan dikembalikan ke inventaris.');" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Batalkan Transaksi & Pulihkan Stok">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">Belum ada transaksi penjualan yang tercatat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-3 d-flex justify-content-center border-top">
        {{ $sales->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
