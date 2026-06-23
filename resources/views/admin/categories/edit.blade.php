@extends('layouts.app')

@section('title', 'Edit Kategori')
@section('page-title', 'Edit Kategori')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Kategori & Folder</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Edit Kategori</h2>
        <p class="page-subheading">Perbarui informasi folder/kategori</p>
    </div>
    <a href="{{ route('admin.categories.index') }}" class="btn-siadil-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="siadil-card">
            <div class="card-header-custom">
                <div class="card-title-custom">
                    <i class="bi bi-pencil-square text-warning"></i>
                    Form Edit Kategori
                </div>
            </div>
            <div class="card-body-custom">
                <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="form-siadil">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror"
                               id="nama" name="nama"
                               value="{{ old('nama', $category->nama) }}">
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="icon" class="form-label">Icon Bootstrap (Opsional)</label>
                        <input type="text" class="form-control @error('icon') is-invalid @enderror"
                                  id="icon" name="icon" value="{{ old('icon', $category->icon) }}">
                        <small class="text-muted">Gunakan class icon dari Bootstrap Icons, misal: <code>bi-folder</code></small>
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-siadil-primary">
                            <i class="bi bi-check-circle"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="btn-siadil-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
