@extends('layouts.app')

@section('title', 'Daftar Pembelian (Stock-in)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1 text-dark" style="font-size: 1.45rem;">Daftar Pembelian (Stock-in)</h4>
        <p class="text-muted small mb-0">Catatan transaksi pembelian barang dari pemasok ke inventaris</p>
    </div>
    <a href="{{ Auth::user()->isOwner() ? route('owner.purchases.create') : route('staf.purchases.create') }}" class="btn btn-primary-custom btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Input Pembelian Baru
    </a>
</div>

<!-- Filter Sesuai Panel 5 Mockup -->
<div class="card-custom p-3 mb-4">
    <form method="GET" action="{{ Auth::user()->isOwner() ? route('owner.purchases.index') : route('staf.purchases.index') }}" class="row g-2 align-items-center">
        <div class="col-md-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nomor PO, pemasok..." value="{{ request('search') }}">
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
                <a href="{{ Auth::user()->isOwner() ? route('owner.purchases.index') : route('staf.purchases.index') }}" class="btn btn-sm btn-light border py-1">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- Tabel Pembelian Sesuai Mockup -->
<div class="card-custom">
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>NO. PO</th>
                    <th>TANGGAL</th>
                    <th>PEMASOK</th>
                    <th class="text-center">JUMLAH ITEM</th>
                    <th>TOTAL BIAYA</th>
                    <th class="text-center" style="width: 140px;">STATUS</th>
                    <th class="text-center" style="width: 120px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $index => $po)
                <tr>
                    <td class="text-secondary fw-semibold">{{ $purchases->firstItem() + $index }}</td>
                    <td class="fw-bold">
                        <a href="{{ Auth::user()->isOwner() ? route('owner.purchases.show', $po) : route('staf.purchases.show', $po) }}" class="text-decoration-none text-primary" style="font-size: 0.885rem;">
                            {{ $po->purchase_number }}
                        </a>
                    </td>
                    <td class="text-muted small">
                        {{ $po->purchased_at->format('d/m/Y') }}<br>
                        <span style="font-size: 0.725rem;">{{ $po->purchased_at->format('H:i') }}</span>
                    </td>
                    <td class="fw-semibold text-dark">{{ $po->supplier->name ?? '-' }}</td>
                    <td class="text-center">
                        <span class="badge" style="background: #f1f5f9; color: #475569; font-weight: 600; padding: 0.35rem 0.65rem;">
                            {{ $po->items->count() }} item ({{ $po->items->sum('quantity') }} unit)
                        </span>
                    </td>
                    <td class="fw-bold text-dark">Rp {{ number_format($po->total, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($po->status === 'completed')
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
                            <a href="{{ Auth::user()->isOwner() ? route('owner.purchases.show', $po) : route('staf.purchases.show', $po) }}" class="btn-action-view" title="Lihat Faktur">
                                <i class="bi bi-eye"></i>
                            </a>

                            @if(Auth::user()->isOwner() && $po->status === 'completed')
                            <form action="{{ route('owner.purchases.cancel', $po) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi PO ini? Stok barang akan dikurangi kembali secara konsisten.');" class="d-inline m-0">
                                @csrf
                                <button type="submit" class="btn-action-delete" title="Batalkan PO & Kurangi Stok">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">Belum ada transaksi pembelian yang tercatat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-3 d-flex justify-content-center border-top">
        {{ $purchases->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
