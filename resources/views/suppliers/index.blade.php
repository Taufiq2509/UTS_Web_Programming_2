@extends('layouts.app')

@section('title', 'Daftar Pemasok')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Pemasok (Suppliers)</h4>
        <p class="text-muted small mb-0">Kelola informasi mitra pemasok barang untuk proses pengadaan dan pembelian stok</p>
    </div>
    @if(Auth::user()->isOwner())
    <a href="{{ route('owner.suppliers.create') }}" class="btn btn-primary-custom btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Tambah Pemasok
    </a>
    @endif
</div>

<div class="card-custom">
    <div class="p-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
        <form method="GET" action="{{ route('owner.suppliers.index') }}" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama atau kontak pemasok..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-sm btn-outline-secondary">Cari</button>
            @if(request('search'))
                <a href="{{ route('owner.suppliers.index') }}" class="btn btn-sm btn-light border">Reset</a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Perusahaan</th>
                    <th>Kontak Person</th>
                    <th>No. Telepon / WhatsApp</th>
                    <th>Alamat</th>
                    <th>Transaksi PO</th>
                    <th style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $index => $supplier)
                <tr>
                    <td>{{ $suppliers->firstItem() + $index }}</td>
                    <td class="fw-bold text-dark">{{ $supplier->name }}</td>
                    <td>{{ $supplier->contact_person ?: '-' }}</td>
                    <td>
                        @if($supplier->phone)
                            <a href="tel:{{ $supplier->phone }}" class="text-decoration-none">
                                <i class="bi bi-telephone me-1 text-primary"></i> {{ $supplier->phone }}
                            </a>
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-muted small">{{ Str::limit($supplier->address, 40) ?: '-' }}</td>
                    <td>
                        <span class="badge bg-info bg-opacity-10 text-info">
                            {{ $supplier->purchases_count }} Pembelian
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('owner.suppliers.edit', $supplier) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('owner.suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pemasok ini?');" class="d-inline">
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
                    <td colspan="7" class="text-center text-muted py-4">Belum ada data pemasok tersimpan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-3 d-flex justify-content-center border-top">
        {{ $suppliers->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
