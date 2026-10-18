<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru - UTS Web Programming 2</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
        .register-card {
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
            max-width: 500px;
            width: 100%;
            overflow: hidden;
        }
        .register-header {
            background: #f8fafc;
            padding: 2rem 2rem 1.5rem;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>

<div class="register-card">
    <div class="register-header">
        <h4 class="fw-bold text-dark mb-1">Registrasi Pembeli</h4>
        <p class="text-muted small mb-0">Daftarkan akun pelanggan Anda untuk mulai berbelanja</p>
    </div>

    <div class="p-4 p-sm-5">
        @if($errors->any())
            <div class="alert alert-danger border-0 small mb-3">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label fw-semibold text-secondary small">Nama Lengkap</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="Contoh: Rendi Pratama" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-semibold text-secondary small">Alamat Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="nama@email.com" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-semibold text-secondary small">Password (Min. 6 Karakter)</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label fw-semibold text-secondary small">Ulangi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%); border: none;">
                <i class="bi bi-person-check-fill me-1"></i> Selesaikan Pendaftaran
            </button>

            <div class="text-center mt-3">
                <span class="text-muted small">Sudah memiliki akun?</span>
                <a href="{{ route('login') }}" class="small fw-semibold text-primary text-decoration-none ms-1">Login di sini</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
