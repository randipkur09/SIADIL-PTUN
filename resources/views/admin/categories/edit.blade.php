@extends('layouts.app')

@section('title', 'Edit Ruangan')
@section('page-title', 'Edit Ruangan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Data Ruangan</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Edit Ruangan</h2>
        <p class="page-subheading">Perbarui informasi ruangan</p>
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
                    Form Edit Ruangan
                </div>
            </div>
            <div class="card-body-custom">
                <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="form-siadil">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="nama_ruangan" class="form-label">Nama Ruangan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_ruangan') is-invalid @enderror"
                               id="nama_ruangan" name="nama_ruangan"
                               value="{{ old('nama_ruangan', $category->nama_ruangan) }}">
                        @error('nama_ruangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                                  id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $category->deskripsi) }}</textarea>
                        @error('deskripsi')
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
