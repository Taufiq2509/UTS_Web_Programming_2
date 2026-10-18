@extends('layouts.app')

@section('title', 'Dashboard Owner')

@section('styles')
<style>
    /* Welcome Banner */
    .hero-banner {
        background: linear-gradient(135deg, #e0f2fe 0%, #e0e7ff 55%, #f0fdf4 100%);
        border: 1px solid #dbeafe;
        border-radius: 20px;
        padding: 2rem 2.5rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .hero-greeting {
        font-size: 0.95rem;
        font-weight: 500;
        color: #64748b;
        margin-bottom: 0.25rem;
    }

    .hero-title {
        font-size: 1.85rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.02em;
        margin-bottom: 0.35rem;
    }

    .hero-desc {
        color: #475569;
        font-size: 0.95rem;
        max-width: 580px;
        margin-bottom: 0;
    }

    .hero-illustration {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Modern Metric Stat Cards */
    .kpi-card {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 20px;
        padding: 1.6rem 1.4rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.04);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 165px;
    }

    .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px -4px rgba(15, 23, 42, 0.08);
    }

    .kpi-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .kpi-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }

    .kpi-arrow-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .kpi-title {
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 0.35rem;
    }

    .kpi-number {
        font-size: 1.65rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        line-height: 1.15;
        margin-bottom: 0.35rem;
    }

    .kpi-subtext {
        font-size: 0.775rem;
        font-weight: 500;
    }

    /* Wave tints at card bottom */
    .wave-blue { background: linear-gradient(180deg, rgba(255,255,255,0) 0%, rgba(239,246,255,0.7) 100%); }
    .wave-green { background: linear-gradient(180deg, rgba(255,255,255,0) 0%, rgba(240,253,244,0.7) 100%); }
    .wave-purple { background: linear-gradient(180deg, rgba(255,255,255,0) 0%, rgba(250,245,255,0.7) 100%); }
    .wave-red { background: linear-gradient(180deg, rgba(255,255,255,0) 0%, rgba(254,242,242,0.7) 100%); }

    /* Alert Restock Banner */
    .restock-alert-banner {
        background: #fef2f2;
        border: 1px solid #fee2e2;
        border-radius: 18px;
        padding: 1.25rem 1.75rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .restock-icon-badge {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #fee2e2;
        color: #ef4444;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .btn-restock-po {
        background-color: #ef4444;
        color: #ffffff;
        font-weight: 700;
        font-size: 0.885rem;
        padding: 0.65rem 1.4rem;
        border-radius: 30px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
        transition: all 0.2s;
        border: none;
    }

    .btn-restock-po:hover {
        background-color: #dc2626;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(239, 68, 68, 0.35);
    }

    /* Attention Table Card */
    .attention-card {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 20px;
        box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.03);
        overflow: hidden;
        margin-bottom: 2.5rem;
    }

    .attention-header {
        padding: 1.25rem 1.75rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-clean th {
        font-size: 0.725rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        background-color: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        padding: 1rem 1.25rem;
    }

    .table-clean td {
        padding: 1.15rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #f8fafc;
    }

    .product-thumb-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
    }

    .cat-pill {
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.3rem 0.75rem;
        border-radius: 20px;
        display: inline-block;
    }

    .cat-pill-blue { background: #eff6ff; color: #2563eb; }
    .cat-pill-green { background: #f0fdf4; color: #16a34a; }
    .cat-pill-orange { background: #fff7ed; color: #ea580c; }
    .cat-pill-purple { background: #faf5ff; color: #9333ea; }

    .stock-badge-danger {
        background: #ef4444;
        color: #ffffff;
        font-size: 0.785rem;
        font-weight: 700;
        padding: 0.35rem 0.85rem;
        border-radius: 20px;
        display: inline-block;
    }

    .stock-badge-warning {
        background: #f59e0b;
        color: #ffffff;
        font-size: 0.785rem;
        font-weight: 700;
        padding: 0.35rem 0.85rem;
        border-radius: 20px;
        display: inline-block;
    }

    .status-pill-kritis {
        background: #fef2f2;
        color: #ef4444;
        border: 1px solid #fee2e2;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.3rem 0.75rem;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .status-pill-waspada {
        background: #fffbeb;
        color: #d97706;
        border: 1px solid #fef3c7;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.3rem 0.75rem;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }
</style>
@endsection

@section('content')

<!-- 1. Hero Welcome Banner (Mockup Identik) -->
<div class="hero-banner">
    <div>
        <span class="hero-greeting">Selamat Datang,</span>
        <h2 class="hero-title">{{ Auth::user()->name }} 👋</h2>
        <p class="hero-desc">Kelola stok dan transaksi produk Anda dengan mudah dan efisien.</p>
    </div>
    <div class="hero-illustration d-none d-md-flex">
        <!-- Modern 3D Illustration Graphic Vector -->
        <svg width="220" height="110" viewBox="0 0 220 110" fill="none" xmlns="http://www.w3.org/2000/svg">
            <!-- Delivery Box (Parcel) -->
            <path d="M40 50L75 30L110 50L75 70L40 50Z" fill="#F59E0B" />
            <path d="M40 50L75 70V105L40 85V50Z" fill="#D97706" />
            <path d="M75 70L110 50V85L75 105V70Z" fill="#B45309" />
            <!-- Box Tape -->
            <path d="M68 34L75 30L82 34V66L75 70L68 66V34Z" fill="#FDE68A" opacity="0.9" />
            <path d="M68 66L75 70V105H71V71L68 66Z" fill="#FDE68A" opacity="0.8" />
            <!-- Clipboard Card -->
            <rect x="110" y="15" width="75" height="92" rx="10" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="2" />
            <rect x="125" y="8" width="45" height="14" rx="4" fill="#3B82F6" />
            <circle cx="147.5" cy="15" r="3" fill="#FFFFFF" />
            <!-- Chart Bars on Clipboard -->
            <rect x="122" y="70" width="10" height="25" rx="3" fill="#93C5FD" />
            <rect x="137" y="55" width="10" height="40" rx="3" fill="#60A5FA" />
            <rect x="152" y="40" width="10" height="55" rx="3" fill="#2563EB" />
            <rect x="167" y="25" width="10" height="70" rx="3" fill="#1D4ED8" />
            <!-- Upward Trend Line -->
            <path d="M127 65L142 50L157 36L172 20" stroke="#38BDF8" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
            <!-- Plant Element -->
            <path d="M195 95C195 75 180 65 180 65C180 65 198 75 200 95H195Z" fill="#10B981" />
            <path d="M190 95C180 85 182 75 182 75C182 75 192 82 190 95Z" fill="#34D399" />
        </svg>
    </div>
</div>

<!-- 2. Grid 4 Kartu Metrik KPI (Mockup Identik) -->
<div class="row g-3 mb-4">
    <!-- Card 1: Total Produk Aktif -->
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card wave-blue">
            <div>
                <div class="kpi-header">
                    <div class="kpi-icon-box" style="background: #eff6ff; color: #2563eb;">
                        <i class="bi bi-boxes"></i>
                    </div>
                    <a href="{{ route('owner.products.index') }}" class="kpi-arrow-btn" style="background: #eff6ff; color: #2563eb;" title="Lihat Produk">
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="kpi-title">Total Produk Aktif</div>
                <div class="kpi-number text-dark">{{ $totalProducts }}</div>
            </div>
            <div class="kpi-subtext text-primary fw-semibold">
                {{ $totalCategories }} Kategori &nbsp;|&nbsp; {{ $totalSuppliers }} Supplier
            </div>
        </div>
    </div>

    <!-- Card 2: Penjualan Hari Ini -->
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card wave-green">
            <div>
                <div class="kpi-header">
                    <div class="kpi-icon-box" style="background: #f0fdf4; color: #16a34a;">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <a href="{{ route('owner.sales.index') }}" class="kpi-arrow-btn" style="background: #f0fdf4; color: #16a34a;" title="Lihat Penjualan">
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="kpi-title">Penjualan Hari Ini</div>
                <div class="kpi-number text-dark">Rp {{ number_format($salesToday, 0, ',', '.') }}</div>
            </div>
            <div class="kpi-subtext text-muted">
                Real-time agregat hari ini
            </div>
        </div>
    </div>

    <!-- Card 3: Penjualan Bulan Ini -->
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card wave-purple">
            <div>
                <div class="kpi-header">
                    <div class="kpi-icon-box" style="background: #faf5ff; color: #9333ea;">
                        <i class="bi bi-cart"></i>
                    </div>
                    <a href="{{ route('owner.sales.index') }}" class="kpi-arrow-btn" style="background: #faf5ff; color: #9333ea;" title="Lihat Penjualan">
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="kpi-title">Penjualan Bulan Ini</div>
                <div class="kpi-number text-dark">Rp {{ number_format($salesThisMonth, 0, ',', '.') }}</div>
            </div>
            <div class="kpi-subtext text-muted">
                {{ $totalTransactions }} total transaksi
            </div>
        </div>
    </div>

    <!-- Card 4: Peringatan Stok Rendah -->
    <div class="col-sm-6 col-xl-3">
        <div class="kpi-card wave-red">
            <div>
                <div class="kpi-header">
                    <div class="kpi-icon-box" style="background: #fef2f2; color: #ef4444;">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <a href="#lowStockSection" class="kpi-arrow-btn" style="background: #fef2f2; color: #ef4444;" title="Lihat Peringatan">
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="kpi-title">Peringatan Stok Rendah</div>
                <div class="kpi-number text-danger">{{ $lowStockCount }} Item</div>
            </div>
            <div class="kpi-subtext text-muted">
                Stok &le; batas minimum
            </div>
        </div>
    </div>
</div>

<!-- 3. Alert Restock Banner (Mockup Identik) -->
@if($lowStockCount > 0)
<div class="restock-alert-banner" id="lowStockSection">
    <div class="d-flex align-items-center gap-3">
        <div class="restock-icon-badge">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-1" style="color: #991b1b; font-size: 1.05rem;">
                Perhatian: Ada {{ $lowStockCount }} Produk Membutuhkan Restock!
            </h6>
            <span style="color: #b91c1c; font-size: 0.885rem;">
                Stok produk berikut sudah menyentuh atau berada di bawah kuota minimum.
            </span>
        </div>
    </div>
    <a href="{{ route('owner.purchases.create') }}" class="btn-restock-po">
        <i class="bi bi-cart-plus-fill"></i>
        <span>Buat PO Pembelian &rarr;</span>
    </a>
</div>
@endif

<!-- 4. Tabel "Daftar Produk yang Perlu Diperhatikan" (Mockup Identik) -->
<div class="attention-card">
    <div class="attention-header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-boxes text-primary fs-5"></i>
            <h6 class="fw-bold mb-0 text-dark" style="font-size: 1.05rem;">Daftar Produk yang Perlu Diperhatikan</h6>
        </div>
        <a href="{{ route('owner.products.index', ['stock_filter' => 'low']) }}" class="text-decoration-none fw-bold text-primary" style="font-size: 0.885rem;">
            Lihat Semua &rarr;
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-clean mb-0 align-middle">
            <thead>
                <tr>
                    <th style="width: 150px;">SKU</th>
                    <th>NAMA PRODUK</th>
                    <th>KATEGORI</th>
                    <th class="text-center" style="width: 140px;">SISA STOK</th>
                    <th style="width: 150px;">BATAS MINIMUM</th>
                    <th style="width: 150px;">STATUS</th>
                    <th style="width: 50px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($lowStockProducts as $prod)
                <tr>
                    <!-- SKU -->
                    <td>
                        <span class="fw-semibold text-secondary" style="font-size: 0.875rem;">{{ $prod->sku }}</span>
                    </td>

                    <!-- Nama Produk + Thumbnail Icon -->
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="product-thumb-box">
                                @if(Str::contains(strtolower($prod->name), ['detergen', 'sabun', 'cuci']))
                                    🧴
                                @elseif(Str::contains(strtolower($prod->name), ['teh', 'jus', 'air', 'botol', 'minuman']))
                                    🧃
                                @elseif(Str::contains(strtolower($prod->name), ['kopi', 'beras', 'gula', 'minyak']))
                                    ☕
                                @else
                                    📦
                                @endif
                            </div>
                            <div>
                                <span class="fw-bold text-dark d-block" style="font-size: 0.95rem;">
                                    {{ $prod->name }}
                                </span>
                            </div>
                        </div>
                    </td>

                    <!-- Kategori Pill -->
                    <td>
                        @php
                            $catName = $prod->category->name ?? 'Umum';
                            $pillClass = 'cat-pill-blue';
                            if (Str::contains(strtolower($catName), ['minuman'])) $pillClass = 'cat-pill-green';
                            elseif (Str::contains(strtolower($catName), ['makanan', 'bumbu'])) $pillClass = 'cat-pill-orange';
                            elseif (Str::contains(strtolower($catName), ['rumah'])) $pillClass = 'cat-pill-blue';
                        @endphp
                        <span class="cat-pill {{ $pillClass }}">{{ $catName }}</span>
                    </td>

                    <!-- Sisa Stok Pill -->
                    <td class="text-center">
                        @if($prod->stock <= 3)
                            <span class="stock-badge-danger">{{ $prod->stock }} unit</span>
                        @else
                            <span class="stock-badge-warning">{{ $prod->stock }} unit</span>
                        @endif
                    </td>

                    <!-- Batas Minimum -->
                    <td>
                        <span class="text-secondary fw-semibold">{{ $prod->minimum_stock }} unit</span>
                    </td>

                    <!-- Status Pill (Kritis / Waspada) -->
                    <td>
                        @if($prod->stock <= 3)
                            <span class="status-pill-kritis">
                                <i class="bi bi-exclamation-triangle"></i> Kritis
                            </span>
                        @else
                            <span class="status-pill-waspada">
                                <i class="bi bi-exclamation-circle"></i> Waspada
                            </span>
                        @endif
                    </td>

                    <!-- Actions Dropdown -->
                    <td class="text-end pe-3">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light border-0 p-1 text-secondary" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical fs-6"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('owner.products.show', $prod) }}">
                                        <i class="bi bi-eye me-2 text-primary"></i> Detail Produk
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('owner.purchases.create') }}">
                                        <i class="bi bi-cart-plus me-2 text-success"></i> Input Pembelian Stok
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('owner.products.edit', $prod) }}">
                                        <i class="bi bi-pencil me-2 text-warning"></i> Ubah Produk
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="bi bi-check-circle text-success me-1"></i> Semua stok produk dalam kondisi aman & mencukupi kuota minimum.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- 5. Tabel "Daftar Transaksi Terbaru" (Panel 1 Mockup) -->
<div class="card-custom mb-4">
    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-receipt text-primary fs-5"></i>
            <h6 class="fw-bold mb-0 text-dark" style="font-size: 1.05rem;">Daftar Transaksi Terbaru</h6>
        </div>
        <a href="{{ route('owner.sales.index') }}" class="text-decoration-none fw-bold text-primary" style="font-size: 0.885rem;">Lihat Semua &rarr;</a>
    </div>

    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>No. Transaksi</th>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Total</th>
                    <th class="text-center" style="width: 140px;">Status</th>
                    <th class="text-center" style="width: 130px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentSales as $index => $sale)
                <tr>
                    <td class="text-secondary fw-semibold">{{ $index + 1 }}</td>
                    <td class="fw-bold">
                        <a href="{{ route('owner.sales.show', $sale) }}" class="text-decoration-none text-primary" style="font-size: 0.885rem;">
                            {{ $sale->sale_number }}
                        </a>
                    </td>
                    <td class="text-muted small">
                        {{ $sale->sold_at->format('d/m/Y') }} {{ $sale->sold_at->format('H:i') }}
                    </td>
                    <td>
                        <span class="badge" style="background: #eff6ff; color: #2563eb; font-weight: 700; border-radius: 20px; padding: 0.3rem 0.65rem; font-size: 0.725rem;">
                            Penjualan
                        </span>
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
                            <a href="{{ route('owner.sales.show', $sale) }}" class="btn-action-view" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('owner.sales.show', $sale) }}" class="btn-action-edit" title="Cetak / Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($sale->status === 'completed')
                            <form action="{{ route('owner.sales.cancel', $sale) }}" method="POST" onsubmit="return confirm('Batalkan transaksi ini? Stok akan dipulihkan.');" class="d-inline m-0">
                                @csrf
                                <button type="submit" class="btn-action-delete" title="Batalkan Transaksi">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-3">Belum ada transaksi penjualan terbaru.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
