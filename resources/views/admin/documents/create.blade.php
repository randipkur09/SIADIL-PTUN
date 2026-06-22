@extends('layouts.app')

@section('title', 'Upload Dokumen')
@section('page-title', 'Upload Dokumen')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.documents.index') }}">Data Dokumen</a></li>
    <li class="breadcrumb-item active">Upload Dokumen</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Upload Dokumen</h2>
        <p class="page-subheading">Unggah dokumen digital ke arsip sistem</p>
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
                    <i class="bi bi-cloud-upload text-primary"></i>
                    Form Upload Dokumen
                </div>
            </div>
            <div class="card-body-custom">
                <form action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data" class="form-siadil" id="uploadForm">
                    @csrf

                    <div class="mb-3">
                        <label for="nama_file" class="form-label">Nama Dokumen <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_file') is-invalid @enderror"
                               id="nama_file" name="nama_file" value="{{ old('nama_file') }}"
                               placeholder="Contoh: Surat Keputusan No. 001/2024">
                        @error('nama_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori Ruangan <span class="text-danger">*</span></label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                            <option value="">-- Pilih Ruangan --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
                                  placeholder="Keterangan singkat tentang dokumen ini...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">File Dokumen <span class="text-danger">*</span></label>
                        <div class="upload-zone" id="uploadZone" onclick="document.getElementById('file').click()">
                            <i class="bi bi-cloud-arrow-up" id="uploadIcon"></i>
                            <p id="uploadText"><strong>Klik atau seret file ke sini</strong></p>
                            <p class="file-formats" id="uploadSub">
                                Format: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG &bull; Maks. 10 MB
                            </p>
                        </div>
                        <input type="file" id="file" name="file" class="d-none @error('file') is-invalid @enderror"
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                        @error('file')
                            <div class="text-danger fs-12 mt-1"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                        @enderror

                        {{-- Preview --}}
                        <div id="filePreview" class="mt-2 d-none">
                            <div class="d-flex align-items-center gap-2 p-2 bg-light rounded border">
                                <i class="bi bi-file-earmark fs-4" id="previewIcon"></i>
                                <div>
                                    <div class="fw-600 fs-13" id="previewName"></div>
                                    <div class="fs-12 text-secondary" id="previewSize"></div>
                                </div>
                                <button type="button" class="btn-icon btn-icon-delete ms-auto" onclick="clearFile()" title="Hapus file">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-siadil-primary" id="submitBtn">
                            <i class="bi bi-cloud-upload"></i>
                            Upload Dokumen
                        </button>
                        <a href="{{ route('admin.documents.index') }}" class="btn-siadil-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const fileInput   = document.getElementById('file');
const uploadZone  = document.getElementById('uploadZone');
const filePreview = document.getElementById('filePreview');
const uploadIcon  = document.getElementById('uploadIcon');
const uploadText  = document.getElementById('uploadText');
const uploadSub   = document.getElementById('uploadSub');
const submitBtn   = document.getElementById('submitBtn');

const icons = {
    pdf: 'bi-file-earmark-pdf text-danger',
    doc: 'bi-file-earmark-word text-primary',
    docx:'bi-file-earmark-word text-primary',
    xls: 'bi-file-earmark-excel text-success',
    xlsx:'bi-file-earmark-excel text-success',
    jpg: 'bi-file-earmark-image text-warning',
    jpeg:'bi-file-earmark-image text-warning',
    png: 'bi-file-earmark-image text-warning',
};

fileInput.addEventListener('change', showPreview);

uploadZone.addEventListener('dragover', e => { e.preventDefault(); uploadZone.classList.add('dragover'); });
uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('dragover'));
uploadZone.addEventListener('drop', e => {
    e.preventDefault();
    uploadZone.classList.remove('dragover');
    fileInput.files = e.dataTransfer.files;
    showPreview();
});

function showPreview() {
    const file = fileInput.files[0];
    if (!file) return;
    const ext = file.name.split('.').pop().toLowerCase();
    const size = file.size > 1048576 ? (file.size/1048576).toFixed(2)+' MB' : (file.size/1024).toFixed(1)+' KB';
    document.getElementById('previewIcon').className = 'bi ' + (icons[ext] || 'bi-file-earmark text-secondary') + ' fs-4';
    document.getElementById('previewName').textContent = file.name;
    document.getElementById('previewSize').textContent = size;
    filePreview.classList.remove('d-none');
    uploadZone.style.display = 'none';
}

function clearFile() {
    fileInput.value = '';
    filePreview.classList.add('d-none');
    uploadZone.style.display = '';
}

document.getElementById('uploadForm').addEventListener('submit', function() {
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengunggah...';
    submitBtn.disabled = true;
});
</script>
@endpush
