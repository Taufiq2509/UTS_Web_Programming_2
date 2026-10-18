@extends('layouts.app')

@section('title', 'Tambah Kategori Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card-custom">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Tambah Kategori Baru</h5>
                <a href="{{ route('owner.categories.index') }}" class="btn btn-sm btn-light border">&larr; Kembali</a>
            </div>
            <div class="p-4">
                <form action="{{ route('owner.categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold small">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Makanan Instan" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold small">Deskripsi (Opsional)</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Keterangan singkat tentang kategori barang ini...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('owner.categories.index') }}" class="btn btn-light border">Batal</a>
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="bi bi-save me-1"></i> Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
