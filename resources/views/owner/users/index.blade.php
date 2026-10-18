@extends('layouts.app')

@section('title', 'Manajemen Pengguna & Hak Akses (Role)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1 text-dark" style="font-size: 1.45rem;">Manajemen Pengguna & Hak Akses (Role)</h4>
        <p class="text-muted small mb-0">Kelola akun dan otorisasi 3 role pengguna: Owner, Staf, dan Pembeli</p>
    </div>
    <a href="{{ route('owner.users.create') }}" class="btn btn-primary-custom btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Tambah Pengguna Baru
    </a>
</div>

<!-- Filter Sesuai Panel 7 Mockup -->
<div class="card-custom p-3 mb-4">
    <form method="GET" action="{{ route('owner.users.index') }}" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nama atau email..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-4">
            <select name="role" class="form-select form-select-sm">
                <option value="">Semua Role Akses</option>
                <option value="owner" {{ request('role') == 'owner' ? 'selected' : '' }}>Owner (Admin Utama)</option>
                <option value="staf" {{ request('role') == 'staf' ? 'selected' : '' }}>Staf Operasional</option>
                <option value="pembeli" {{ request('role') == 'pembeli' ? 'selected' : '' }}>Pembeli (Customer)</option>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-1">
            <button type="submit" class="btn btn-sm btn-primary-custom w-100 py-1">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
            @if(request('search') || request('role'))
                <a href="{{ route('owner.users.index') }}" class="btn btn-sm btn-light border py-1">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- Tabel Pengguna Sesuai Mockup -->
<div class="card-custom">
    <div class="table-responsive">
        <table class="table table-custom align-middle">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>NAMA LENGKAP</th>
                    <th>EMAIL</th>
                    <th class="text-center" style="width: 140px;">ROLE AKSES</th>
                    <th>BERGABUNG SEJAK</th>
                    <th class="text-center" style="width: 120px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                <tr>
                    <td class="text-secondary fw-semibold">{{ $users->firstItem() + $index }}</td>
                    <td class="fw-bold text-dark">{{ $user->name }}</td>
                    <td class="text-secondary small">{{ $user->email }}</td>
                    <td class="text-center">
                        @if($user->role === 'owner')
                            <span class="badge" style="background: #fef3c7; color: #b45309; font-weight: 800; border-radius: 20px; padding: 0.35rem 0.85rem; font-size: 0.725rem; letter-spacing: 0.04em;">
                                OWNER
                            </span>
                        @elseif($user->role === 'staf')
                            <span class="badge" style="background: #e0e7ff; color: #3730a3; font-weight: 800; border-radius: 20px; padding: 0.35rem 0.85rem; font-size: 0.725rem; letter-spacing: 0.04em;">
                                STAF
                            </span>
                        @else
                            <span class="badge" style="background: #dcfce7; color: #15803d; font-weight: 800; border-radius: 20px; padding: 0.35rem 0.85rem; font-size: 0.725rem; letter-spacing: 0.04em;">
                                PEMBELI
                            </span>
                        @endif
                    </td>
                    <td class="text-muted small">{{ $user->created_at->format('d/m/Y') }}</td>
                    <td class="text-center">
                        <div class="d-inline-flex gap-1 justify-content-center">
                            <a href="{{ route('owner.users.edit', $user) }}" class="btn-action-edit" title="Ubah User & Role">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($user->id !== Auth::id())
                            <form action="{{ route('owner.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pengguna ini?');" class="d-inline m-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action-delete" title="Hapus User">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Tidak ada data pengguna yang sesuai.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-3 d-flex justify-content-center border-top">
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
