@extends('layouts.app')

@section('title', 'Katalog Belanja')

@section('content')
<div class="mb-4">
    <div class="p-4 rounded-4 text-white mb-4" style="background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);">
        <h3 class="fw-bold mb-1">Katalog Produk & Pembelian Online</h3>
        <p class="mb-0 text-white-50">Selamat datang, <strong>{{ Auth::user()->name }}</strong>. Temukan produk kebutuhan Anda dengan stok terkini dan harga terbaik.</p>
    </div>

    <!-- Filter & Pencarian Produk -->
    <div class="card-custom p-3 mb-4">
        <form method="GET" action="{{ route('customer.dashboard') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama barang atau SKU..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="category_id" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary-custom w-100">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                @if(request('search') || request('category_id'))
                    <a href="{{ route('customer.dashboard') }}" class="btn btn-light border">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Grid Produk Tersedia -->
    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-grid me-2 text-primary"></i> Daftar Produk Tersedia</h5>
    <div class="row g-3 mb-4">
        @forelse($products as $product)
        <div class="col-sm-6 col-md-4 col-xl-3">
            <div class="card-custom h-100 d-flex flex-column p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge bg-light text-secondary border small">{{ $product->category->name ?? 'Umum' }}</span>
                    <span class="badge bg-success bg-opacity-15 text-success">Stok: {{ $product->stock }} unit</span>
                </div>
                <h6 class="fw-bold text-dark mb-1">{{ $product->name }}</h6>
                <small class="text-muted d-block mb-3">SKU: {{ $product->sku }}</small>

                <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Harga Satuan</small>
                        <span class="fw-bold text-primary fs-5">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</span>
                    </div>
                    <button type="button" class="btn btn-sm btn-primary-custom" data-bs-toggle="modal" data-bs-target="#orderModal{{ $product->id }}">
                        <i class="bi bi-cart-plus me-1"></i> Beli
                    </button>
                </div>
            </div>

            <!-- Modal Checkout Pembelian -->
            <div class="modal fade" id="orderModal{{ $product->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">Konfirmasi Pembelian</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('customer.order') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <h6 class="fw-bold mb-1">{{ $product->name }}</h6>
                                    <p class="text-muted small mb-0">SKU: {{ $product->sku }} | Sisa Stok: <strong>{{ $product->stock }}</strong> unit</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Harga Satuan</label>
                                    <input type="text" class="form-control" value="Rp {{ number_format($product->selling_price, 0, ',', '.') }}" readonly disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="qty{{ $product->id }}" class="form-label fw-semibold small">Jumlah Beli (Kuantitas)</label>
                                    <input type="number" name="quantity" id="qty{{ $product->id }}" class="form-control" min="1" max="{{ $product->stock }}" value="1" required>
                                    <small class="text-muted">Maksimal {{ $product->stock }} unit sesuai stok tersedia.</small>
                                </div>
                                <div class="mb-3">
                                    <label for="notes{{ $product->id }}" class="form-label fw-semibold small">Catatan Pesanan (Opsional)</label>
                                    <textarea name="notes" id="notes{{ $product->id }}" class="form-control" rows="2" placeholder="Catatan alamat pengiriman atau preferensi..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary-custom">
                                    <i class="bi bi-bag-check-fill me-1"></i> Konfirmasi Bayar & Pesan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card-custom p-5 text-center text-muted">
                <i class="bi bi-box2 fs-1 text-secondary mb-2 d-block"></i>
                Tidak ada produk yang sesuai dengan pencarian atau filter Anda.
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination Produk -->
    <div class="d-flex justify-content-center mb-5">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>

    <!-- Riwayat Belanja Pribadi Pembeli -->
    <div class="card-custom">
        <div class="p-3 border-bottom">
            <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-clock-history me-2 text-info"></i> Riwayat Belanja Saya</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>No. Invoice</th>
                        <th>Waktu Pesan</th>
                        <th>Rincian Produk</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($myOrders as $order)
                    <tr>
                        <td class="fw-bold text-primary">{{ $order->sale_number }}</td>
                        <td class="text-muted small">{{ $order->sold_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <ul class="list-unstyled mb-0 small">
                                @foreach($order->items as $item)
                                    <li>• {{ $item->product->name ?? 'Produk Dihapus' }} ({{ $item->quantity }}x @ Rp {{ number_format($item->price, 0, ',', '.') }})</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="fw-bold text-success">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td>
                            @if($order->status === 'completed')
                                <span class="badge bg-success bg-opacity-15 text-success">Berhasil Selesai</span>
                            @else
                                <span class="badge bg-danger bg-opacity-15 text-danger">Dibatalkan</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Anda belum pernah melakukan transaksi pembelian.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 d-flex justify-content-center border-top">
            {{ $myOrders->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
