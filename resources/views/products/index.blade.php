@extends('layouts.app')

@section('title', 'Katalog & Inventaris Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Daftar Produk & Stok</h4>
        <p class="text-muted small mb-0">Master data SKU produk, penetapan harga jual/beli, dan monitoring kuota stok aman</p>
    </div>
    @if(Auth::user()->isOwner())
    <a href="{{ route('owner.products.create') }}" class="btn btn-primary-custom btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Tambah Produk Baru
    </a>
    @endif
</div>

<!-- Filter & Pencarian Sesuai Slide 18 -->
<div class="card-custom p-3 mb-4">
    <form method="GET" action="{{ Auth::user()->isOwner() ? route('owner.products.index') : route('staf.products.index') }}" class="row g-2 align-items-center">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama produk atau SKU unik..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="category_id" class="form-select form-select-sm">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="stock_filter" class="form-select form-select-sm">
                <option value="">Semua Kondisi Stok</option>
                <option value="low" {{ request('stock_filter') == 'low' ? 'selected' : '' }}>⚠️ Stok Rendah (&le; Minimum)</option>
                <option value="out" {{ request('stock_filter') == 'out' ? 'selected' : '' }}>⛔ Stok Habis (0)</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-1">
            <button type="submit" class="btn btn-sm btn-primary-custom w-100">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
            @if(request('search') || request('category_id') || request('stock_filter'))
                <a href="{{ Auth::user()->isOwner() ? route('owner.products.index') : route('staf.products.index') }}" class="btn btn-sm btn-light border">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- Tabel Produk -->
<div class="card-custom">
    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>SKU Unik</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    @if(Auth::user()->isOwner())
                    <th>Harga Beli</th>
                    @endif
                    <th>Harga Jual</th>
                    <th>Sisa Stok</th>
                    <th>Status Stok</th>
                    <th style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $index => $prod)
                <tr>
                    <td>{{ $products->firstItem() + $index }}</td>
                    <td class="fw-semibold text-secondary">{{ $prod->sku }}</td>
                    <td class="fw-bold text-dark">
                        {{ $prod->name }}
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">{{ $prod->category->name ?? '-' }}</span>
                    </td>
                    @if(Auth::user()->isOwner())
                    <td class="text-muted">Rp {{ number_format($prod->purchase_price, 0, ',', '.') }}</td>
                    @endif
                    <td class="fw-bold text-primary">Rp {{ number_format($prod->selling_price, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge {{ $prod->isLowStock() ? 'bg-danger' : 'bg-success' }} fs-6">
                            {{ $prod->stock }} unit
                        </span>
                        <small class="text-muted d-block" style="font-size: 0.725rem;">Min: {{ $prod->minimum_stock }}</small>
                    </td>
                    <td>
                        @if($prod->stock <= 0)
                            <span class="badge bg-dark text-white">Habis</span>
                        @elseif($prod->isLowStock())
                            <span class="badge bg-warning text-dark">Perlu Restock</span>
                        @else
                            <span class="badge bg-success bg-opacity-10 text-success">Aman</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ Auth::user()->isOwner() ? route('owner.products.show', $prod) : route('staf.products.show', $prod) }}" class="btn btn-sm btn-light border" title="Lihat Detail & Riwayat Transaksi">
                                <i class="bi bi-eye"></i>
                            </a>

                            @if(Auth::user()->isOwner())
                            <a href="{{ route('owner.products.edit', $prod) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('owner.products.destroy', $prod) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini? (Data transaksi terkait tetap aman)');" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ Auth::user()->isOwner() ? 9 : 8 }}" class="text-center text-muted py-4">
                        Tidak ada data produk yang sesuai.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination dengan Query String (Slide 18) -->
    <div class="p-3 d-flex justify-content-center border-top">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
