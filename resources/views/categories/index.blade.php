@extends('layouts.app')

@section('title', 'Kategori Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1 text-dark" style="font-size: 1.45rem;">Kategori Produk</h4>
        <p class="text-muted small mb-0">Kelola pengelompokan produk barang untuk memudahkan inventarisasi</p>
    </div>
    @if(Auth::user()->isOwner())
    <a href="{{ route('owner.categories.create') }}" class="btn btn-primary-custom btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
    </a>
    @endif
</div>

<div class="card-custom">
    <!-- Filter Search Bar Sesuai Panel 2 Mockup -->
    <div class="p-3 border-bottom d-flex align-items-center">
        <form method="GET" action="{{ Auth::user()->isOwner() ? route('owner.categories.index') : route('staf.categories.index') }}" class="d-flex align-items-center gap-2">
            <div class="input-group input-group-sm" style="width: 280px;">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nama kategori..." value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn btn-sm btn-primary-custom py-1 px-3">Cari</button>
            @if(request('search'))
                <a href="{{ Auth::user()->isOwner() ? route('owner.categories.index') : route('staf.categories.index') }}" class="btn btn-sm btn-light border py-1">Reset</a>
            @endif
        </form>
    </div>

    <!-- Tabel Kategori Sesuai Mockup -->
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th class="text-center" style="width: 160px;">Jumlah Produk</th>
                    <th class="text-center" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $index => $category)
                <tr>
                    <td class="text-secondary fw-semibold">{{ $categories->firstItem() + $index }}</td>
                    <td class="fw-bold text-dark">{{ $category->name }}</td>
                    <td class="text-muted small">{{ $category->description ?: '-' }}</td>
                    <td class="text-center">
                        <span class="badge" style="background: #eff6ff; color: #2563eb; font-weight: 700; border-radius: 20px; padding: 0.35rem 0.85rem; font-size: 0.775rem;">
                            {{ $category->products_count }} Produk
                        </span>
                    </td>
                    <td class="text-center">
                        @if(Auth::user()->isOwner())
                        <div class="d-inline-flex gap-1 justify-content-center">
                            <a href="{{ route('owner.categories.edit', $category) }}" class="btn-action-edit" title="Edit Kategori">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('owner.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');" class="d-inline m-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action-delete" title="Hapus Kategori">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                        @else
                        <span class="badge bg-light text-muted border px-2 py-1" style="font-size: 0.75rem;">Lihat Saja</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Belum ada data kategori.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-3 d-flex justify-content-center border-top">
        {{ $categories->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
