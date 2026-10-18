<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Inventory & Sales System') - UTS Web Programming 2</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #eef2ff;
            --dark-surface: #0f172a;
            --sidebar-width: 260px;
            --card-radius: 14px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
        }

        /* Top Navbar */
        .navbar-custom {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.75rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1020;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        }

        .brand-logo {
            font-weight: 800;
            font-size: 1.25rem;
            letter-spacing: -0.025em;
            background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
            min-height: calc(100vh - 65px);
            padding: 1.25rem 0.75rem;
        }

        .nav-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94a3b8;
            padding: 0.5rem 0.75rem;
            margin-top: 0.5rem;
        }

        .nav-link-custom {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.85rem;
            color: #475569;
            font-weight: 500;
            font-size: 0.925rem;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
            margin-bottom: 0.25rem;
        }

        .nav-link-custom:hover {
            color: var(--primary-color);
            background-color: var(--primary-light);
            transform: translateX(2px);
        }

        .nav-link-custom.active {
            color: #ffffff;
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            font-weight: 600;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
        }

        .nav-link-custom i {
            font-size: 1.15rem;
        }

        /* Content Area */
        .main-content {
            flex: 1;
            padding: 1.75rem 2rem;
            max-width: 1400px;
        }

        /* Cards & Metric Badges */
        .card-custom {
            background: #ffffff;
            border-radius: var(--card-radius);
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-custom:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.07);
        }

        .stat-card {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        /* Table Design */
        .table-custom {
            margin-bottom: 0;
        }

        .table-custom th {
            font-size: 0.785rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #64748b;
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.9rem 1rem;
        }

        .table-custom td {
            padding: 0.95rem 1rem;
            vertical-align: middle;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
        }

        .role-badge {
            font-size: 0.75rem;
            padding: 0.35em 0.75em;
            font-weight: 600;
            border-radius: 20px;
        }
        .role-owner { background-color: #fef3c7; color: #92400e; }
        .role-staf { background-color: #e0e7ff; color: #3730a3; }
        .role-pembeli { background-color: #d1fae5; color: #065f46; }

        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: #ffffff;
            font-weight: 500;
            border-radius: 8px;
            padding: 0.55rem 1.15rem;
            transition: all 0.2s;
        }
        .btn-primary-custom:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            color: #ffffff;
            transform: translateY(-1px);
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Header / Navbar -->
    <nav class="navbar navbar-custom">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('dashboard') }}" class="brand-logo">
                    <i class="bi bi-boxes me-1"></i> INVENTRA
                </a>
                <span class="badge bg-light text-secondary border d-none d-md-inline-block">
                    UTS Web Programming 2
                </span>
            </div>

            @auth
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex flex-column text-end d-none d-sm-block">
                    <span class="fw-semibold text-dark fs-6">{{ Auth::user()->name }}</span>
                    <span class="role-badge role-{{ Auth::user()->role }} align-self-end mt-1">
                        {{ strtoupper(Auth::user()->role) }}
                    </span>
                </div>

                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center p-2" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle fs-5"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                        <li class="px-3 py-2 border-bottom">
                            <small class="text-muted d-block">Masuk sebagai:</small>
                            <span class="fw-bold text-dark">{{ Auth::user()->email }}</span>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger py-2">
                                    <i class="bi bi-box-arrow-right me-2"></i> Keluar (Logout)
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            @endauth
        </div>
    </nav>

    <!-- Main App Container -->
    <div class="d-flex">
        @auth
        <!-- Sidebar Navigation -->
        <aside class="sidebar d-none d-lg-block">
            <div class="nav-label">Menu Utama</div>

            @if(Auth::user()->isOwner())
                <a href="{{ route('owner.dashboard') }}" class="nav-link-custom {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard Owner
                </a>

                <div class="nav-label">Master Data</div>
                <a href="{{ route('owner.categories.index') }}" class="nav-link-custom {{ request()->routeIs('owner.categories.*') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i> Kategori Produk
                </a>
                <a href="{{ route('owner.suppliers.index') }}" class="nav-link-custom {{ request()->routeIs('owner.suppliers.*') ? 'active' : '' }}">
                    <i class="bi bi-truck"></i> Pemasok (Suppliers)
                </a>
                <a href="{{ route('owner.products.index') }}" class="nav-link-custom {{ request()->routeIs('owner.products.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i> Produk & Stok
                </a>

                <div class="nav-label">Transaksi</div>
                <a href="{{ route('owner.purchases.index') }}" class="nav-link-custom {{ request()->routeIs('owner.purchases.*') ? 'active' : '' }}">
                    <i class="bi bi-cart-plus"></i> Pembelian (Stock-in)
                </a>
                <a href="{{ route('owner.sales.index') }}" class="nav-link-custom {{ request()->routeIs('owner.sales.*') ? 'active' : '' }}">
                    <i class="bi bi-cart-check"></i> Penjualan (Sales)
                </a>

                <div class="nav-label">Pengaturan</div>
                <a href="{{ route('owner.users.index') }}" class="nav-link-custom {{ request()->routeIs('owner.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Kelola Pengguna
                </a>

            @elseif(Auth::user()->isStaf())
                <a href="{{ route('staf.dashboard') }}" class="nav-link-custom {{ request()->routeIs('staf.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard Staf
                </a>

                <div class="nav-label">Katalog & Stok</div>
                <a href="{{ route('staf.products.index') }}" class="nav-link-custom {{ request()->routeIs('staf.products.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i> Cek Stok Produk
                </a>

                <div class="nav-label">Operasional Transaksi</div>
                <a href="{{ route('staf.purchases.index') }}" class="nav-link-custom {{ request()->routeIs('staf.purchases.*') ? 'active' : '' }}">
                    <i class="bi bi-cart-plus"></i> Input Pembelian Stok
                </a>
                <a href="{{ route('staf.sales.index') }}" class="nav-link-custom {{ request()->routeIs('staf.sales.*') ? 'active' : '' }}">
                    <i class="bi bi-cart-check"></i> Input Transaksi Penjualan
                </a>

            @elseif(Auth::user()->isPembeli())
                <a href="{{ route('customer.dashboard') }}" class="nav-link-custom {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-shop"></i> Belanja & Riwayat
                </a>
            @endif
        </aside>
        @endauth

        <!-- Main Body Content -->
        <main class="main-content">
            <!-- Flash Message Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <h6 class="fw-bold mb-1"><i class="bi bi-exclamation-octagon-fill me-2"></i> Terjadi Kesalahan Input:</h6>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
