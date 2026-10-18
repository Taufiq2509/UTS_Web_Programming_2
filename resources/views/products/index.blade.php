@extends('layouts.app')

@section('title', 'Daftar Produk & Stok')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1 text-dark" style="font-size: 1.45rem;">Daftar Produk & Stok</h4>
        <p class="text-muted small mb-0">Master data SKU produk, penetapan harga jual/beli, dan monitoring kuota stok aman</p>
    </div>
    @if(Auth::user()->isOwner())
    <a href="{{ route('owner.products.create') }}" class="btn btn-primary-custom btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Tambah Produk Baru
    </a>
    @endif
</div>

<!-- Filter Sesuai Panel 4 Mockup -->
<div class="card-custom p-3 mb-4">
    <form method="GET" action="{{ Auth::user()->isOwner() ? route('owner.products.index') : route('staf.products.index') }}" class="row g-2 align-items-center">
        <div class="col-md-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nama produk atau SKU unik..." value="{{ request('search') }}">
            </div>
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
                <option value="low" {{ request('stock_filter') == 'low' ? 'selected' : '' }}>Stok Rendah (&le; Minimum)</option>
                <option value="out" {{ request('stock_filter') == 'out' ? 'selected' : '' }}>Stok Habis (0)</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-1">
            <button type="submit" class="btn btn-sm btn-primary-custom w-100 py-1">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
            @if(request('search') || request('category_id') || request('stock_filter'))
                <a href="{{ Auth::user()->isOwner() ? route('owner.products.index') : route('staf.products.index') }}" class="btn btn-sm btn-light border py-1">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- Tabel Produk Sesuai Mockup -->
<div class="card-custom">
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>SKU UNIK</th>
                    <th>NAMA PRODUK</th>
                    <th>KATEGORI</th>
                    @if(Auth::user()->isOwner())
                    <th>HARGA BELI</th>
                    @endif
                    <th>HARGA JUAL</th>
                    <th class="text-center">STOK</th>
                    <th class="text-center" style="width: 130px;">STATUS</th>
                    <th class="text-center" style="width: 140px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $index => $prod)
                <tr>
                    <td class="text-secondary fw-semibold">{{ $products->firstItem() + $index }}</td>
                    <td class="fw-semibold text-secondary" style="font-size: 0.85rem;">{{ $prod->sku }}</td>
                    <td class="fw-bold text-dark">
                        {{ $prod->name }}
                    </td>
                    <td>
                        @php
                            $catName = $prod->category->name ?? '-';
                            $pillStyle = 'background: #eff6ff; color: #2563eb;';
                            if (Str::contains(strtolower($catName), ['minuman'])) $pillStyle = 'background: #f0fdf4; color: #16a34a;';
                            elseif (Str::contains(strtolower($catName), ['makanan', 'bumbu'])) $pillStyle = 'background: #fff7ed; color: #ea580c;';
                            elseif (Str::contains(strtolower($catName), ['rumah'])) $pillStyle = 'background: #eff6ff; color: #2563eb;';
                        @endphp
                        <span class="badge" style="{{ $pillStyle }} font-weight: 700; border-radius: 20px; padding: 0.35rem 0.75rem; font-size: 0.75rem;">
                            {{ $catName }}
                        </span>
                    </td>
                    @if(Auth::user()->isOwner())
                    <td class="text-muted small">Rp {{ number_format($prod->purchase_price, 0, ',', '.') }}</td>
                    @endif
                    <td class="fw-bold text-dark">Rp {{ number_format($prod->selling_price, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <span class="fw-bold" style="font-size: 0.9rem;">
                            {{ $prod->stock }} unit
                        </span>
                    </td>
                    <td class="text-center">
                        @if($prod->stock <= 0)
                            <span class="badge" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; font-weight: 700; border-radius: 20px; padding: 0.35rem 0.75rem;">
                                Habis
                            </span>
                        @elseif($prod->stock <= 3)
                            <span class="badge" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; font-weight: 700; border-radius: 20px; padding: 0.35rem 0.75rem;">
                                Kritis
                            </span>
                        @elseif($prod->isLowStock())
                            <span class="badge" style="background: #fffbeb; color: #d97706; border: 1px solid #fef3c7; font-weight: 700; border-radius: 20px; padding: 0.35rem 0.75rem;">
                                Waspada
                            </span>
                        @else
                            <span class="badge" style="background: #dcfce7; color: #16a34a; border: 1px solid #bbf7d0; font-weight: 700; border-radius: 20px; padding: 0.35rem 0.75rem;">
                                Aman
                            </span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="d-inline-flex gap-1 justify-content-center">
                            <a href="{{ Auth::user()->isOwner() ? route('owner.products.show', $prod) : route('staf.products.show', $prod) }}" class="btn-action-view" title="Lihat Detail">
                                <i class="bi bi-eye"></i>
                            </a>

                            @if(Auth::user()->isOwner())
                            <a href="{{ route('owner.products.edit', $prod) }}" class="btn-action-edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('owner.products.destroy', $prod) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');" class="d-inline m-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action-delete" title="Hapus">
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

    <div class="p-3 d-flex justify-content-center border-top">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
