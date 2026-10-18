@extends('layouts.app')

@section('title', 'Manajemen Pengguna & Role')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Manajemen Pengguna & Hak Akses (Role)</h4>
        <p class="text-muted small mb-0">Kelola akun dan otorisasi 3 role pengguna: Owner, Staf, dan Pembeli</p>
    </div>
    <a href="{{ route('owner.users.create') }}" class="btn btn-primary-custom btn-sm">
        <i class="bi bi-person-plus me-1"></i> Tambah Pengguna Baru
    </a>
</div>

<div class="card-custom">
    <div class="p-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
        <form method="GET" action="{{ route('owner.users.index') }}" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama atau email..." value="{{ request('search') }}">
            <select name="role" class="form-select form-select-sm">
                <option value="">Semua Role</option>
                <option value="owner" {{ request('role') == 'owner' ? 'selected' : '' }}>Owner</option>
                <option value="staf" {{ request('role') == 'staf' ? 'selected' : '' }}>Staf</option>
                <option value="pembeli" {{ request('role') == 'pembeli' ? 'selected' : '' }}>Pembeli</option>
            </select>
            <button type="submit" class="btn btn-sm btn-outline-secondary">Filter</button>
            @if(request('search') || request('role'))
                <a href="{{ route('owner.users.index') }}" class="btn btn-sm btn-light border">Reset</a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Lengkap</th>
                    <th>Alamat Email</th>
                    <th>Role Akses</th>
                    <th>Bergabung Sejak</th>
                    <th style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                <tr>
                    <td>{{ $users->firstItem() + $index }}</td>
                    <td class="fw-bold">{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="role-badge role-{{ $user->role }}">
                            {{ strtoupper($user->role) }}
                        </span>
                    </td>
                    <td class="text-muted small">{{ $user->created_at->format('d/m/Y') }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('owner.users.edit', $user) }}" class="btn btn-sm btn-outline-warning" title="Ubah Role / Data">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($user->id !== Auth::id())
                            <form action="{{ route('owner.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?');" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Akun">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Tidak ada data pengguna.</td>
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
