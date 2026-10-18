@extends('layouts.app')

@section('title', 'Daftar Penjualan (Sales / Stock-Out)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1 text-dark" style="font-size: 1.45rem;">Daftar Penjualan (Sales / Stock-Out)</h4>
        <p class="text-muted small mb-0">Catatan transaksi penjualan barang ke pelanggan dan riwayat pengurangan stok</p>
    </div>
    <a href="{{ Auth::user()->isOwner() ? route('owner.sales.create') : route('staf.sales.create') }}" class="btn btn-primary-custom btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Input Penjualan Baru
    </a>
</div>

<!-- Filter Sesuai Panel 6 Mockup -->
<div class="card-custom p-3 mb-4">
    <form method="GET" action="{{ Auth::user()->isOwner() ? route('owner.sales.index') : route('staf.sales.index') }}" class="row g-2 align-items-center">
        <div class="col-md-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nomor TRX, pelanggan..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <input type="date" name="start_date" class="form-control form-control-sm" placeholder="Dari Tanggal" value="{{ request('start_date') }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="end_date" class="form-control form-control-sm" placeholder="Sampai Tanggal" value="{{ request('end_date') }}">
        </div>
        <div class="col-md-2 d-flex gap-1">
            <button type="submit" class="btn btn-sm btn-primary-custom w-100 py-1">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
            @if(request('search') || request('start_date') || request('end_date'))
                <a href="{{ Auth::user()->isOwner() ? route('owner.sales.index') : route('staf.sales.index') }}" class="btn btn-sm btn-light border py-1">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- Tabel Penjualan Sesuai Mockup -->
<div class="card-custom">
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>NO. TRX</th>
                    <th>TANGGAL</th>
                    <th>PELANGGAN</th>
                    <th>KASIR / PETUGAS</th>
                    <th>TOTAL BIAYA</th>
                    <th class="text-center" style="width: 140px;">STATUS</th>
                    <th class="text-center" style="width: 120px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $index => $sale)
                <tr>
                    <td class="text-secondary fw-semibold">{{ $sales->firstItem() + $index }}</td>
                    <td class="fw-bold">
                        <a href="{{ Auth::user()->isOwner() ? route('owner.sales.show', $sale) : route('staf.sales.show', $sale) }}" class="text-decoration-none text-primary" style="font-size: 0.885rem;">
                            {{ $sale->sale_number }}
                        </a>
                    </td>
                    <td class="text-muted small">
                        {{ $sale->sold_at->format('d/m/Y') }}<br>
                        <span style="font-size: 0.725rem;">{{ $sale->sold_at->format('H:i') }}</span>
                    </td>
                    <td class="fw-bold text-dark">{{ $sale->customer_name }}</td>
                    <td>
                        <span class="text-secondary small">{{ $sale->user->name ?? '-' }}</span>
                    </td>
                    <td class="fw-bold text-dark">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($sale->status === 'completed')
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
                        <div class="d-inline-flex gap-1 justify-content-center">
                            <a href="{{ Auth::user()->isOwner() ? route('owner.sales.show', $sale) : route('staf.sales.show', $sale) }}" class="btn-action-view" title="Lihat Invoice">
                                <i class="bi bi-eye"></i>
                            </a>

                            @if(Auth::user()->isOwner() && $sale->status === 'completed')
                            <form action="{{ route('owner.sales.cancel', $sale) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi penjualan ini? Stok barang akan dikembalikan ke inventaris.');" class="d-inline m-0">
                                @csrf
                                <button type="submit" class="btn-action-delete" title="Batalkan Transaksi & Pulihkan Stok">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">Belum ada transaksi penjualan yang tercatat.</td>
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
