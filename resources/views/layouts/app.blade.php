<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'INVENTRA') - Inventory Management System</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary-blue: #2563eb;
            --primary-blue-hover: #1d4ed8;
            --primary-blue-light: #eff6ff;
            --sidebar-width: 260px;
            --surface-bg: #f8fafc;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--surface-bg);
            color: #0f172a;
            min-height: 100vh;
            margin: 0;
            display: flex;
        }

        /* Sidebar Styling */
        .app-sidebar {
            width: var(--sidebar-width);
            background: #ffffff;
            border-right: 1px solid #f1f5f9;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1030;
            padding: 1.5rem 1.15rem;
            overflow-y: auto;
        }

        .brand-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 2rem;
            text-decoration: none;
        }

        .brand-logo-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.4rem;
            box-shadow: 0 8px 16px -4px rgba(37, 99, 235, 0.35);
        }

        .brand-title {
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #1e40af;
            line-height: 1.1;
        }

        .brand-subtitle {
            font-size: 0.725rem;
            color: #94a3b8;
            font-weight: 500;
            display: block;
            margin-top: 2px;
        }

        .nav-section-title {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #94a3b8;
            margin: 1.25rem 0 0.5rem 0.5rem;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .nav-item-link {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.75rem 1rem;
            color: #475569;
            font-size: 0.925rem;
            font-weight: 600;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .nav-item-link i {
            font-size: 1.2rem;
            color: #64748b;
            transition: color 0.2s ease;
        }

        .nav-item-link:hover {
            color: var(--primary-blue);
            background-color: var(--primary-blue-light);
        }

        .nav-item-link:hover i {
            color: var(--primary-blue);
        }

        .nav-item-link.active {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff;
            box-shadow: 0 8px 20px -6px rgba(37, 99, 235, 0.45);
        }

        .nav-item-link.active i {
            color: #ffffff;
        }

        /* Sidebar Bottom Motivational Card */
        .sidebar-bottom-card {
            margin-top: auto;
            padding: 1rem;
            background: linear-gradient(135deg, #f0f7ff 0%, #e0f2fe 100%);
            border: 1px solid #dbeafe;
            border-radius: 16px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-card-icon {
            width: 40px;
            height: 40px;
            background: #ffffff;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2563eb;
            font-size: 1.25rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        /* Main Wrapper */
        .app-main {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Top Header Navbar */
        .app-header {
            height: 72px;
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .search-box-wrapper {
            position: relative;
            max-width: 420px;
            width: 100%;
        }

        .search-box-input {
            width: 100%;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.65rem 4rem 0.65rem 2.6rem;
            font-size: 0.875rem;
            color: #1e293b;
            transition: all 0.2s;
        }

        .search-box-input:focus {
            background: #ffffff;
            border-color: #3b82f6;
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .search-icon {
            position: absolute;
            left: 0.95rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.95rem;
        }

        .search-shortcut-badge {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            color: #64748b;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.2rem 0.45rem;
            border-radius: 6px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .notification-btn {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #475569;
            font-size: 1.15rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .notification-btn:hover {
            background: #f8fafc;
            color: #1e293b;
        }

        .notification-dot {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid #ffffff;
        }

        .user-profile-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: transparent;
            border: none;
            padding: 0.25rem 0.5rem;
            cursor: pointer;
            border-radius: 12px;
            transition: background 0.2s;
        }

        .user-profile-btn:hover {
            background: #f8fafc;
        }

        .avatar-circle {
            width: 42px;
            height: 42px;
            background: #e0e7ff;
            color: #3730a3;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            font-weight: 700;
        }

        .role-tag-owner {
            background-color: #fef3c7;
            color: #b45309;
            font-size: 0.7rem;
            font-weight: 800;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            letter-spacing: 0.03em;
        }

        .role-tag-staf {
            background-color: #e0e7ff;
            color: #3730a3;
            font-size: 0.7rem;
            font-weight: 800;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
        }

        .role-tag-pembeli {
            background-color: #dcfce7;
            color: #15803d;
            font-size: 0.7rem;
            font-weight: 800;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
        }

        /* Page Content */
        .page-content {
            padding: 2rem 2.25rem;
            max-width: 1440px;
        }

        /* Badges & Tables */
        .card-custom {
            background: #ffffff;
            border-radius: 18px;
            border: 1px solid #eef2f6;
            box-shadow: 0 4px 12px -2px rgba(15, 23, 42, 0.03);
            transition: all 0.2s ease;
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 0.35rem 0.75rem;
            font-weight: 700;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            white-space: nowrap;
        }
        .status-completed {
            background-color: #dcfce7 !important;
            color: #166534 !important;
            border: 1px solid #86efac !important;
        }
        .status-cancelled {
            background-color: #fee2e2 !important;
            color: #991b1b !important;
            border: 1px solid #fca5a5 !important;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border: none;
            color: #ffffff;
            font-weight: 600;
            border-radius: 10px;
            padding: 0.6rem 1.25rem;
            transition: all 0.2s;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
        }
        .btn-primary-custom:hover {
            background: #1d4ed8;
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(37, 99, 235, 0.3);
        }

        @media (max-width: 992px) {
            .app-sidebar {
                display: none;
            }
            .app-main {
                margin-left: 0;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Left Sidebar -->
    <aside class="app-sidebar">
        <!-- Brand / Logo -->
        <a href="{{ route('dashboard') }}" class="brand-header">
            <div class="brand-logo-icon">
                <i class="bi bi-boxes"></i>
            </div>
            <div>
                <span class="brand-title">INVENTRA</span>
                <span class="brand-subtitle">Inventory Management System</span>
            </div>
        </a>

        @auth
        <!-- Navigation Menu -->
        <ul class="sidebar-nav">
            @if(Auth::user()->isOwner())
                <li>
                    <a href="{{ route('owner.dashboard') }}" class="nav-item-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-house-door-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <div class="nav-section-title">MASTER DATA</div>
                <li>
                    <a href="{{ route('owner.categories.index') }}" class="nav-item-link {{ request()->routeIs('owner.categories.*') ? 'active' : '' }}">
                        <i class="bi bi-tag"></i>
                        <span>Kategori Produk</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('owner.suppliers.index') }}" class="nav-item-link {{ request()->routeIs('owner.suppliers.*') ? 'active' : '' }}">
                        <i class="bi bi-truck"></i>
                        <span>Pemasok (Suppliers)</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('owner.products.index') }}" class="nav-item-link {{ request()->routeIs('owner.products.*') ? 'active' : '' }}">
                        <i class="bi bi-box"></i>
                        <span>Produk & Stok</span>
                    </a>
                </li>

                <div class="nav-section-title">TRANSAKSI</div>
                <li>
                    <a href="{{ route('owner.purchases.index') }}" class="nav-item-link {{ request()->routeIs('owner.purchases.*') ? 'active' : '' }}">
                        <i class="bi bi-cart-plus"></i>
                        <span>Pembelian (Stock-in)</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('owner.sales.index') }}" class="nav-item-link {{ request()->routeIs('owner.sales.*') ? 'active' : '' }}">
                        <i class="bi bi-cart"></i>
                        <span>Penjualan (Sales)</span>
                    </a>
                </li>

                <div class="nav-section-title">PENGATURAN</div>
                <li>
                    <a href="{{ route('owner.users.index') }}" class="nav-item-link {{ request()->routeIs('owner.users.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span>Kelola Pengguna</span>
                    </a>
                </li>

            @elseif(Auth::user()->isStaf())
                <li>
                    <a href="{{ route('staf.dashboard') }}" class="nav-item-link {{ request()->routeIs('staf.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-house-door-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <div class="nav-section-title">KATALOG & STOK</div>
                <li>
                    <a href="{{ route('staf.products.index') }}" class="nav-item-link {{ request()->routeIs('staf.products.*') ? 'active' : '' }}">
                        <i class="bi bi-box"></i>
                        <span>Produk & Stok</span>
                    </a>
                </li>

                <div class="nav-section-title">OPERASIONAL TRANSAKSI</div>
                <li>
                    <a href="{{ route('staf.purchases.index') }}" class="nav-item-link {{ request()->routeIs('staf.purchases.*') ? 'active' : '' }}">
                        <i class="bi bi-cart-plus"></i>
                        <span>Pembelian (Stock-in)</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('staf.sales.index') }}" class="nav-item-link {{ request()->routeIs('staf.sales.*') ? 'active' : '' }}">
                        <i class="bi bi-cart"></i>
                        <span>Penjualan (Sales)</span>
                    </a>
                </li>

            @elseif(Auth::user()->isPembeli())
                <li>
                    <a href="{{ route('customer.dashboard') }}" class="nav-item-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-shop"></i>
                        <span>Katalog Belanja</span>
                    </a>
                </li>
            @endif
        </ul>

        <!-- Bottom Motivation Card -->
        <div class="sidebar-bottom-card">
            <div class="sidebar-card-icon">
                <i class="bi bi-box-seam"></i>
            </div>
            <div>
                <span class="d-block fw-bold text-dark" style="font-size: 0.825rem;">Stok Aman,</span>
                <span class="d-block fw-semibold text-primary" style="font-size: 0.775rem;">Bisnis Lancar</span>
            </div>
        </div>
        @endauth
    </aside>

    <!-- Main Container -->
    <div class="app-main">
        <!-- Top Header Bar -->
        <header class="app-header">
            <!-- Search Bar -->
            <div class="search-box-wrapper">
                <i class="bi bi-search search-icon"></i>
                <input type="text" class="search-box-input" placeholder="Cari produk, kategori, atau supplier..." id="globalSearchInput">
                <span class="search-shortcut-badge">Ctrl + K</span>
            </div>

            <!-- Header Right Actions -->
            <div class="header-actions">
                <!-- Notifications -->
                <button type="button" class="notification-btn" title="Pemberitahuan">
                    <i class="bi bi-bell"></i>
                    <span class="notification-dot"></span>
                </button>

                @auth
                <!-- User Profile Dropdown -->
                <div class="dropdown">
                    <button class="user-profile-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar-circle">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="d-flex flex-column text-start d-none d-sm-flex">
                            <span class="fw-bold text-dark" style="font-size: 0.885rem; line-height: 1.2;">
                                {{ Auth::user()->name }}
                            </span>
                            <span class="role-tag-{{ Auth::user()->role }} align-self-start mt-1">
                                {{ strtoupper(Auth::user()->role) }}
                            </span>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2" style="min-width: 200px;">
                        <li class="px-3 py-2 border-bottom">
                            <small class="text-muted d-block" style="font-size: 0.75rem;">Email Anda:</small>
                            <span class="fw-semibold text-dark small">{{ Auth::user()->email }}</span>
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
                @endauth
            </div>
        </header>

        <!-- Main Page Content -->
        <main class="page-content">
            <!-- Flash Message Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Keyboard Shortcut: Ctrl + K focus search
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                const searchInput = document.getElementById('globalSearchInput');
                if (searchInput) searchInput.focus();
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
