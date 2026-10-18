@extends('layouts.app')

@section('title', 'Daftar Pembelian Stok (Purchase / Stock-In)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Daftar Pembelian (Purchase / Stock-In)</h4>
        <p class="text-muted small mb-0">Catatan pengadaan stok barang masuk dari pemasok ke inventaris toko</p>
    </div>
    <a href="{{ Auth::user()->isOwner() ? route('owner.purchases.create') : route('staf.purchases.create') }}" class="btn btn-primary-custom btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Input Pembelian Baru
    </a>
</div>

<!-- Filter dan Pencarian Transaksi Sesuai Slide 13 & 18 -->
<div class="card-custom p-3 mb-4">
    <form method="GET" action="{{ Auth::user()->isOwner() ? route('owner.purchases.index') : route('staf.purchases.index') }}" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label small fw-semibold text-muted mb-1">Cari No. PO</label>
            <input type="text" name="search" class="form-control form-control-sm" placeholder="PO-2026..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-semibold text-muted mb-1">Pemasok</label>
            <select name="supplier_id" class="form-select form-select-sm">
                <option value="">Semua Pemasok</option>
                @foreach($suppliers as $sup)
                    <option value="{{ $sup->id }}" {{ request('supplier_id') == $sup->id ? 'selected' : '' }}>
                        {{ $sup->name }}
                    </option>
                @endforeach
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
        <div class="col-md-2 d-flex gap-1">
            <button type="submit" class="btn btn-sm btn-primary-custom w-100">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
            @if(request('search') || request('supplier_id') || request('start_date') || request('end_date'))
                <a href="{{ Auth::user()->isOwner() ? route('owner.purchases.index') : route('staf.purchases.index') }}" class="btn btn-sm btn-light border">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- Tabel Transaksi Pembelian -->
<div class="card-custom">
    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nomor Pembelian (PO)</th>
                    <th>Tanggal Beli</th>
                    <th>Pemasok</th>
                    <th>Dicatat Oleh</th>
                    <th>Jumlah Jenis Item</th>
                    <th>Total Biaya</th>
                    <th>Status</th>
                    <th style="width: 130px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $index => $po)
                <tr>
                    <td>{{ $purchases->firstItem() + $index }}</td>
                    <td class="fw-bold text-primary">
                        <a href="{{ Auth::user()->isOwner() ? route('owner.purchases.show', $po) : route('staf.purchases.show', $po) }}" class="text-decoration-none">
                            {{ $po->purchase_number }}
                        </a>
                    </td>
                    <td class="text-muted small">{{ $po->purchased_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $po->supplier->name ?? '-' }}</td>
                    <td><span class="badge bg-light text-secondary border">{{ $po->user->name ?? '-' }}</span></td>
                    <td>{{ $po->items->count() }} item ({{ $po->items->sum('quantity') }} unit)</td>
                    <td class="fw-bold text-dark">Rp {{ number_format($po->total, 0, ',', '.') }}</td>
                    <td>
                        @if($po->status === 'completed')
                            <span class="status-badge status-completed"><i class="bi bi-check-circle"></i> Selesai (Masuk)</span>
                        @else
                            <span class="status-badge status-cancelled"><i class="bi bi-x-circle"></i> Dibatalkan</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ Auth::user()->isOwner() ? route('owner.purchases.show', $po) : route('staf.purchases.show', $po) }}" class="btn btn-sm btn-light border" title="Lihat Invoice">
                                <i class="bi bi-eye"></i> Detail
                            </a>

                            @if(Auth::user()->isOwner() && $po->status === 'completed')
                            <form action="{{ route('owner.purchases.cancel', $po) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi PO ini? Stok barang akan dikurangi kembali secara konsisten.');" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Batalkan Pembelian & Kurangi Stok">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">Belum ada transaksi pembelian yang tercatat.</td>
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
