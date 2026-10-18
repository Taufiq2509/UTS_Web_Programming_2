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
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .login-card {
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
            max-width: 480px;
            width: 100%;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .login-header {
            background: #f8fafc;
            padding: 2.25rem 2rem 1.75rem;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
        }
        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #eef2ff;
            color: #4338ca;
            padding: 0.4rem 0.9rem;
            border-radius: 30px;
            font-weight: 700;
            font-size: 0.85rem;
            margin-bottom: 0.75rem;
        }
        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
        }
        .demo-box {
            background: #f1f5f9;
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1.5rem;
        }
        .btn-demo {
            font-size: 0.8rem;
            padding: 0.35rem 0.6rem;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <div class="brand-badge">
            <i class="bi bi-box-seam-fill"></i> UTS WEB PROGRAMMING 2
        </div>
        <h4 class="fw-bold text-dark mb-1">Inventory & Sales System</h4>
        <p class="text-muted small mb-0">Silakan login untuk mengakses sistem sesuai role Anda</p>
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
                <a href="{{ route('register') }}" class="small text-decoration-none fw-semibold text-primary">Daftar Akun Baru</a>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); border: none;">
                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sekarang
            </button>
        </form>

        <!-- Kotak Akun Demo untuk Dosen / Penguji -->
        <div class="demo-box">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bold small text-dark"><i class="bi bi-key-fill text-warning me-1"></i> Quick Demo Akun:</span>
                <span class="badge bg-secondary" style="font-size: 0.7rem;">Password: password123</span>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <button type="button" class="btn btn-sm btn-outline-primary btn-demo" onclick="fillCreds('owner@example.com')">
                    👑 Login Owner
                </button>
                <button type="button" class="btn btn-sm btn-outline-info btn-demo" onclick="fillCreds('staf@example.com')">
                    🧑‍💼 Login Staf
                </button>
                <button type="button" class="btn btn-sm btn-outline-success btn-demo" onclick="fillCreds('pembeli@example.com')">
                    🛒 Login Pembeli
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
