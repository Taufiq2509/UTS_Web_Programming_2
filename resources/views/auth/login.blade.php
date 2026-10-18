<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Sistem - UTS Web Programming 2</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #1d4ed8 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .login-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.4);
            max-width: 480px;
            width: 100%;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }
        .login-header {
            background: linear-gradient(135deg, #f0f7ff 0%, #e0f2fe 100%);
            padding: 2.5rem 2rem 2rem;
            text-align: center;
            border-bottom: 1px solid #dbeafe;
        }
        .brand-logo-icon {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.7rem;
            box-shadow: 0 8px 16px -4px rgba(37, 99, 235, 0.4);
            margin-bottom: 0.75rem;
        }
        .brand-title {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #1e40af;
            line-height: 1.1;
        }
        .brand-subtitle {
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 600;
            display: block;
            margin-top: 3px;
        }
        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
        }
        .demo-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 1.1rem;
            margin-top: 1.5rem;
        }
        .btn-demo {
            font-size: 0.8rem;
            padding: 0.4rem 0.75rem;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <div class="brand-logo-icon">
            <i class="bi bi-boxes"></i>
        </div>
        <div class="brand-title">INVENTRA</div>
        <div class="brand-subtitle">Inventory Management System</div>
    </div>

    <div class="p-4 p-sm-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 small mb-3">
                <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger border-0 small mb-3">
                <i class="bi bi-exclamation-triangle me-1"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label fw-semibold text-secondary small">Alamat Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" id="email" class="form-control border-start-0 ps-0" placeholder="nama@email.com" value="{{ old('email', 'owner@example.com') }}" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-semibold text-secondary small">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" id="password" class="form-control border-start-0 ps-0" placeholder="••••••••" value="password123" required>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
                    <label class="form-check-label text-muted small" for="remember">
                        Ingat Saya
                    </label>
                </div>
                <a href="{{ route('register') }}" class="small text-decoration-none fw-bold text-primary">Daftar Akun Baru</a>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border: none; border-radius: 10px; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);">
                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sekarang
            </button>
        </form>

        <!-- Kotak Akun Demo untuk Dosen / Penguji -->
        <div class="demo-box">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bold small text-dark"><i class="bi bi-shield-check text-primary me-1"></i> Akun Uji Coba:</span>
                <span class="badge bg-light text-muted border" style="font-size: 0.7rem;">Password: password123</span>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <button type="button" class="btn btn-sm btn-outline-primary btn-demo" onclick="fillCreds('owner@example.com')">
                    👑 Owner (Budi Santoso)
                </button>
                <button type="button" class="btn btn-sm btn-outline-info btn-demo" onclick="fillCreds('staf@example.com')">
                    🧑‍💼 Staf (Siti Rahma)
                </button>
                <button type="button" class="btn btn-sm btn-outline-success btn-demo" onclick="fillCreds('pembeli@example.com')">
                    🛒 Pembeli (Andi Wijaya)
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function fillCreds(email) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = 'password123';
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
