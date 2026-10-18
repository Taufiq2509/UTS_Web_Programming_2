@extends('layouts.app')

@section('title', 'Manajemen Kategori')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Kategori Produk</h4>
        <p class="text-muted small mb-0">Kelola pengelompokan produk barang untuk memudahkan inventarisasi</p>
    </div>
    @if(Auth::user()->isOwner())
    <a href="{{ route('owner.categories.create') }}" class="btn btn-primary-custom btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
    </a>
    @endif
</div>

<div class="card-custom">
    <div class="p-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
        <form method="GET" action="{{ route('owner.categories.index') }}" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama kategori..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-sm btn-outline-secondary">Cari</button>
            @if(request('search'))
                <a href="{{ route('owner.categories.index') }}" class="btn btn-sm btn-light border">Reset</a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th>Jumlah Produk</th>
                    <th style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $index => $category)
                <tr>
                    <td>{{ $categories->firstItem() + $index }}</td>
                    <td class="fw-bold">{{ $category->name }}</td>
                    <td class="text-muted">{{ $category->description ?: '-' }}</td>
                    <td>
                        <span class="badge bg-primary bg-opacity-10 text-primary">
                            {{ $category->products_count }} Produk
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('owner.categories.edit', $category) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('owner.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
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
