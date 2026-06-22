@extends('layouts.app')

@section('title', 'Edit Dokumen')
@section('page-title', 'Edit Dokumen')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.documents.index') }}">Data Dokumen</a></li>
    <li class="breadcrumb-item active">Edit Dokumen</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Edit Dokumen</h2>
        <p class="page-subheading">Perbarui informasi dokumen</p>
    </div>
    <a href="{{ route('admin.documents.index') }}" class="btn-siadil-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="siadil-card">
            <div class="card-header-custom">
                <div class="card-title-custom">
                    <i class="bi bi-pencil-square text-warning"></i>
                    Form Edit Dokumen
                </div>
            </div>
            <div class="card-body-custom">
                <form action="{{ route('admin.documents.update', $document) }}" method="POST" enctype="multipart/form-data" class="form-siadil">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nama_file" class="form-label">Nama Dokumen <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_file') is-invalid @enderror"
                               id="nama_file" name="nama_file"
                               value="{{ old('nama_file', $document->nama_file) }}"
                               placeholder="Nama dokumen">
                        @error('nama_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori Ruangan <span class="text-danger">*</span></label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                            <option value="">-- Pilih Ruangan --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $document->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->nama_ruangan }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                                  id="deskripsi" name="deskripsi" rows="3"
                                  placeholder="Keterangan dokumen...">{{ old('deskripsi', $document->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- File saat ini --}}
                    <div class="mb-3">
                        <label class="form-label">File Saat Ini</label>
                        <div class="d-flex align-items-center gap-2 p-2 bg-light rounded border">
                            <i class="bi {{ $document->file_icon }} fs-4"></i>
                            <div>
                                <div class="fw-600 fs-13">{{ basename($document->file_path) }}</div>
                                <div class="fs-12 text-secondary">
                                    {{ strtoupper($document->file_type) }} &bull; {{ $document->file_size_formatted }}
                                </div>
                            </div>
                            <a href="{{ route('download', $document->id) }}" class="btn-siadil-secondary btn-sm ms-auto">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="file" class="form-label">Ganti File (opsional)</label>
                        <div class="upload-zone" id="uploadZone" onclick="document.getElementById('file').click()">
                            <i class="bi bi-arrow-repeat"></i>
                            <p><strong>Klik untuk mengganti file</strong></p>
                            <p class="file-formats">Format: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG &bull; Maks. 10 MB</p>
                        </div>
                        <input type="file" id="file" name="file" class="d-none @error('file') is-invalid @enderror"
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                        @error('file')
                            <div class="text-danger fs-12 mt-1">{{ $message }}</div>
                        @enderror
                        <div id="newFilePreview" class="mt-2 d-none">
                            <div class="d-flex align-items-center gap-2 p-2 bg-light rounded border">
                                <i class="bi bi-file-earmark fs-4" id="newPreviewIcon"></i>
                                <div>
                                    <div class="fw-600 fs-13" id="newPreviewName"></div>
                                    <div class="fs-12 text-secondary" id="newPreviewSize"></div>
                                </div>
                                <button type="button" class="btn-icon btn-icon-delete ms-auto" onclick="clearNewFile()">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-siadil-primary">
                            <i class="bi bi-check-circle"></i>
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.documents.index') }}" class="btn-siadil-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const fileInput = document.getElementById('file');
const icons = { pdf:'bi-file-earmark-pdf text-danger', doc:'bi-file-earmark-word text-primary', docx:'bi-file-earmark-word text-primary', xls:'bi-file-earmark-excel text-success', xlsx:'bi-file-earmark-excel text-success', jpg:'bi-file-earmark-image text-warning', jpeg:'bi-file-earmark-image text-warning', png:'bi-file-earmark-image text-warning' };

fileInput.addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    const ext = file.name.split('.').pop().toLowerCase();
    const size = file.size > 1048576 ? (file.size/1048576).toFixed(2)+' MB' : (file.size/1024).toFixed(1)+' KB';
    document.getElementById('newPreviewIcon').className = 'bi ' + (icons[ext] || 'bi-file-earmark text-secondary') + ' fs-4';
    document.getElementById('newPreviewName').textContent = file.name;
    document.getElementById('newPreviewSize').textContent = size;
    document.getElementById('newFilePreview').classList.remove('d-none');
    document.getElementById('uploadZone').style.display = 'none';
});

function clearNewFile() {
    fileInput.value = '';
    document.getElementById('newFilePreview').classList.add('d-none');
    document.getElementById('uploadZone').style.display = '';
}
</script>
@endpush
