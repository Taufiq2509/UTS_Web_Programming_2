@extends('layouts.app')

@section('title', 'Tambah Pemasok Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card-custom">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Tambah Pemasok (Supplier) Baru</h5>
                <a href="{{ route('owner.suppliers.index') }}" class="btn btn-sm btn-light border">&larr; Kembali</a>
            </div>
            <div class="p-4">
                <form action="{{ route('owner.suppliers.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold small">Nama Perusahaan / Supplier <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: PT Sumber Pangan Makmur" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="contact_person" class="form-label fw-semibold small">Nama Kontak Person (PIC)</label>
                            <input type="text" name="contact_person" id="contact_person" class="form-control" value="{{ old('contact_person') }}" placeholder="Contoh: Bpk. Gunawan">
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-semibold small">No. Telepon / HP</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" placeholder="Contoh: 08123456789">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold small">Email Perusahaan</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="sales@perusahaan.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="address" class="form-label fw-semibold small">Alamat Lengkap</label>
                        <textarea name="address" id="address" class="form-control" rows="3" placeholder="Alamat gudang / kantor operasional supplier...">{{ old('address') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('owner.suppliers.index') }}" class="btn btn-light border">Batal</a>
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="bi bi-save me-1"></i> Simpan Pemasok
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
