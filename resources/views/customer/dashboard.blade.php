@extends('layouts.app')

@section('title', 'Katalog Belanja')

@section('content')
<!-- Hero Welcome Banner Sesuai Mockup Panel 1 & 8 -->
<div class="hero-banner">
    <div>
        <div class="hero-greeting">Selamat Datang,</div>
        <h1 class="hero-title">{{ Auth::user()->name }} <span style="font-size: 1.25rem;">🛍️</span></h1>
        <p class="hero-desc">Temukan berbagai produk berkualitas kebutuhan Anda dengan stok terkini dan proses instan.</p>
    </div>
    <div class="hero-illustration d-none d-lg-block">
        <!-- 3D Warehouse Box & Shopping Cart Graphic -->
        <svg width="220" height="120" viewBox="0 0 220 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <ellipse cx="140" cy="98" rx="70" ry="12" fill="#E2E8F0" opacity="0.6"/>
            <!-- Big Gold Box -->
            <g transform="translate(100, 32)">
                <path d="M40 0L80 18L40 36L0 18L40 0Z" fill="#FBBF24" />
                <path d="M0 18L40 36V70L0 52V18Z" fill="#F59E0B" />
                <path d="M40 36L80 18V52L40 70V36Z" fill="#D97706" />
                <path d="M40 0L40 36" stroke="#FEF3C7" stroke-width="2" stroke-dasharray="3 3"/>
                <path d="M20 9L60 27" stroke="#FEF3C7" stroke-width="2" stroke-dasharray="3 3"/>
            </g>
            <!-- Small Front Box -->
            <g transform="translate(68, 54)">
                <path d="M25 0L50 12L25 24L0 12L25 0Z" fill="#FCD34D" />
                <path d="M0 12L25 24V48L0 36V12Z" fill="#FBBF24" />
                <path d="M25 24L50 12V36L25 48V24Z" fill="#F59E0B" />
            </g>
            <!-- Shopping Bag -->
            <g transform="translate(142, 20)">
                <rect x="6" y="24" width="46" height="52" rx="10" fill="#3B82F6" />
                <path d="M18 24V14C18 9 22 5 29 5C36 5 40 9 40 14V24" stroke="#1D4ED8" stroke-width="4" stroke-linecap="round" />
                <circle cx="29" cy="46" r="8" fill="#FFFFFF" opacity="0.9"/>
                <path d="M29 42V50M25 46H33" stroke="#2563EB" stroke-width="2.5" stroke-linecap="round"/>
            </g>
            <!-- Potted Plant Decoration -->
            <g transform="translate(186, 48)">
                <path d="M12 28L8 45H22L18 28H12Z" fill="#3B82F6" />
                <path d="M15 28C10 20 8 10 15 2C22 10 20 20 15 28Z" fill="#10B981" />
                <path d="M18 28C24 22 28 14 26 6C20 12 18 20 18 28Z" fill="#059669" />
                <path d="M12 28C6 22 2 14 4 6C10 12 12 20 12 28Z" fill="#34D399" />
            </g>
        </svg>
    </div>
</div>

<!-- 3 KPI Cards Ringkasan Akun Pembeli -->
<div class="row g-3 mb-4">
    <!-- Card 1: Total Belanja -->
    <div class="col-sm-6 col-md-4">
        <div class="kpi-card wave-green">
            <div class="kpi-header">
                <div class="kpi-icon-box" style="background: #f0fdf4; color: #16a34a;">
                    <i class="bi bi-wallet2"></i>
                </div>
                <a href="#riwayat-belanja" class="kpi-arrow-btn" style="background: #f1f5f9; color: #64748b;" title="Lihat Riwayat">
                    <i class="bi bi-arrow-down-right"></i>
                </a>
            </div>
            <div>
                <div class="kpi-title">Total Pembelian Saya</div>
                <div class="kpi-number text-dark">Rp {{ number_format($myTotalSpent, 0, ',', '.') }}</div>
                <div class="kpi-subtext text-success">Akumulasi transaksi berhasil</div>
            </div>
        </div>
    </div>

    <!-- Card 2: Pesanan Selesai -->
    <div class="col-sm-6 col-md-4">
        <div class="kpi-card wave-blue">
            <div class="kpi-header">
                <div class="kpi-icon-box" style="background: #eff6ff; color: #2563eb;">
                    <i class="bi bi-bag-check-fill"></i>
                </div>
                <a href="#riwayat-belanja" class="kpi-arrow-btn" style="background: #f1f5f9; color: #64748b;" title="Lihat Pesanan">
                    <i class="bi bi-arrow-down-right"></i>
                </a>
            </div>
            <div>
                <div class="kpi-title">Pesanan Selesai</div>
                <div class="kpi-number text-dark">{{ $myOrdersCount }} Transaksi</div>
                <div class="kpi-subtext text-primary">Pesanan terverifikasi sistem</div>
            </div>
        </div>
    </div>

    <!-- Card 3: Produk Siap Beli -->
    <div class="col-sm-12 col-md-4">
        <div class="kpi-card wave-purple">
            <div class="kpi-header">
                <div class="kpi-icon-box" style="background: #faf5ff; color: #9333ea;">
                    <i class="bi bi-boxes"></i>
                </div>
                <div class="kpi-arrow-btn" style="background: #f1f5f9; color: #64748b;">
                    <i class="bi bi-check2-circle"></i>
                </div>
            </div>
            <div>
                <div class="kpi-title">Produk Siap Beli</div>
                <div class="kpi-number text-dark">{{ $totalAvailableProducts }} Item</div>
                <div class="kpi-subtext text-muted">Stok langsung dari inventaris</div>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Pencarian Produk Sesuai Mockup -->
<div class="card-custom p-3 mb-4">
    <form method="GET" action="{{ route('customer.dashboard') }}" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nama barang atau SKU..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-4">
            <select name="category_id" class="form-select form-select-sm">
                <option value="">Semua Kategori Produk</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-sm btn-primary-custom w-100 py-1">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
            @if(request('search') || request('category_id'))
                <a href="{{ route('customer.dashboard') }}" class="btn btn-sm btn-light border py-1">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- Grid Produk Tersedia Sesuai Desain Modern -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-grid-fill me-2 text-primary"></i> Daftar Produk Tersedia</h5>
    <small class="text-muted">Klik tombol "Beli" untuk melakukan pemesanan instan</small>
</div>

<div class="row g-3 mb-4">
    @forelse($products as $product)
    <div class="col-sm-6 col-md-4 col-xl-3">
        <div class="card-custom h-100 d-flex flex-column p-3" style="transition: transform 0.2s ease, box-shadow 0.2s ease;">
            <div class="d-flex justify-content-between align-items-start mb-2">
                @php
                    $catName = $product->category->name ?? 'Umum';
                    $pillStyle = 'background: #eff6ff; color: #2563eb;';
                    if (Str::contains(strtolower($catName), ['minuman'])) $pillStyle = 'background: #f0fdf4; color: #16a34a;';
                    elseif (Str::contains(strtolower($catName), ['makanan', 'bumbu'])) $pillStyle = 'background: #fff7ed; color: #ea580c;';
                @endphp
                <span class="badge" style="{{ $pillStyle }} font-weight: 700; border-radius: 20px; padding: 0.3rem 0.65rem; font-size: 0.725rem;">
                    {{ $catName }}
                </span>
                <span class="badge" style="background: #dcfce7; color: #166534; font-weight: 700; border-radius: 20px; padding: 0.3rem 0.65rem; font-size: 0.725rem;">
                    Stok: {{ $product->stock }}
                </span>
            </div>
            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">{{ $product->name }}</h6>
            <small class="text-secondary d-block mb-3" style="font-size: 0.775rem;">SKU: {{ $product->sku }}</small>

            <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted d-block" style="font-size: 0.725rem;">Harga</small>
                    <span class="fw-bold text-dark fs-6">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</span>
                </div>
                <button type="button" class="btn btn-sm btn-primary-custom" data-bs-toggle="modal" data-bs-target="#orderModal{{ $product->id }}">
                    <i class="bi bi-cart-plus me-1"></i> Beli
                </button>
            </div>
        </div>

        <!-- Modal Checkout Pembelian Langsung -->
        <div class="modal fade" id="orderModal{{ $product->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header border-bottom">
                        <h5 class="modal-title fw-bold text-dark">
                            <i class="bi bi-bag-check text-primary me-2"></i> Konfirmasi Pembelian
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('customer.order') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="modal-body p-4">
                            <div class="mb-3 p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                <h6 class="fw-bold mb-1 text-dark">{{ $product->name }}</h6>
                                <p class="text-muted small mb-0">SKU: {{ $product->sku }} | Tersedia: <strong>{{ $product->stock }}</strong> unit</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-muted">Harga Satuan</label>
                                <input type="text" class="form-control bg-light fw-bold" value="Rp {{ number_format($product->selling_price, 0, ',', '.') }}" readonly disabled>
                            </div>
                            <div class="mb-3">
                                <label for="qty{{ $product->id }}" class="form-label fw-semibold small text-muted">Jumlah Pembelian</label>
                                <input type="number" name="quantity" id="qty{{ $product->id }}" class="form-control" min="1" max="{{ $product->stock }}" value="1" required>
                                <small class="text-muted" style="font-size: 0.75rem;">Maksimal pembelian {{ $product->stock }} unit sesuai stok inventaris.</small>
                            </div>
                            <div class="mb-3">
                                <label for="notes{{ $product->id }}" class="form-label fw-semibold small text-muted">Catatan Tambahan (Opsional)</label>
                                <textarea name="notes" id="notes{{ $product->id }}" class="form-control" rows="2" placeholder="Catatan pengiriman atau instruksi khusus..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer border-top">
                            <button type="button" class="btn btn-sm btn-light border px-3" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-sm btn-primary-custom px-4">
                                <i class="bi bi-check2-circle me-1"></i> Pesan Sekarang
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
            Tidak ada produk yang sesuai dengan pencarian atau filter Anda saat ini.
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination Produk -->
<div class="d-flex justify-content-center mb-5">
    {{ $products->links('pagination::bootstrap-5') }}
</div>

<!-- Riwayat Belanja Pribadi Pembeli Sesuai Tabel Mockup Panel 1 & 6 -->
<div class="card-custom" id="riwayat-belanja">
    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.15rem;">
                <i class="bi bi-clock-history me-2 text-primary"></i> Riwayat Belanja Saya
            </h5>
            <small class="text-muted">Daftar transaksi pembelian barang yang pernah Anda lakukan</small>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>No. Transaksi</th>
                    <th>Tanggal Belanja</th>
                    <th>Rincian Produk</th>
                    <th>Total Bayar</th>
                    <th class="text-center" style="width: 140px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($myOrders as $index => $order)
                <tr>
                    <td class="text-secondary fw-semibold">{{ $myOrders->firstItem() + $index }}</td>
                    <td class="fw-bold text-primary">{{ $order->sale_number }}</td>
                    <td class="text-muted small">{{ $order->sold_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <ul class="list-unstyled mb-0 small">
                            @foreach($order->items as $item)
                                <li>• {{ $item->product->name ?? 'Produk Dihapus' }} ({{ $item->quantity }}x @ Rp {{ number_format($item->price, 0, ',', '.') }})</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="fw-bold text-dark">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($order->status === 'completed')
                            <span class="status-badge status-completed">
                                <i class="bi bi-check-circle"></i> Selesai
                            </span>
                        @else
                            <span class="status-badge status-cancelled">
                                <i class="bi bi-x-circle"></i> Dibatalkan
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Anda belum memiliki riwayat transaksi belanja.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3 d-flex justify-content-center border-top">
        {{ $myOrders->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
