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
                        <label for="judul" class="form-label">Judul Dokumen <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('judul') is-invalid @enderror"
                               id="judul" name="judul"
                               value="{{ old('judul', $document->judul) }}"
                               placeholder="Judul dokumen">
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nomor_dokumen" class="form-label">Nomor Dokumen</label>
                            <input type="text" class="form-control @error('nomor_dokumen') is-invalid @enderror"
                                   id="nomor_dokumen" name="nomor_dokumen" value="{{ old('nomor_dokumen', $document->nomor_dokumen) }}"
                                   placeholder="Contoh: 001/PTUN/2024">
                            @error('nomor_dokumen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tanggal" class="form-label">Tanggal Dokumen</label>
                            <input type="date" class="form-control @error('tanggal') is-invalid @enderror"
                                   id="tanggal" name="tanggal" value="{{ old('tanggal', $document->tanggal ? $document->tanggal->format('Y-m-d') : '') }}">
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">Kategori / Folder <span class="text-danger">*</span></label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $document->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3" id="subcategory_container" style="display: none;">
                            <label for="subcategory_id" class="form-label">Subkategori</label>
                            <select class="form-select @error('subcategory_id') is-invalid @enderror" id="subcategory_id" name="subcategory_id">
                                <option value="">-- Pilih Subkategori --</option>
                            </select>
                            @error('subcategory_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                  id="keterangan" name="keterangan" rows="3"
                                  placeholder="Keterangan dokumen...">{{ old('keterangan', $document->keterangan) }}</textarea>
                        @error('keterangan')
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
                                    {{ strtoupper($document->ekstensi) }} &bull; {{ $document->file_size_formatted }}
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

// Load subcategories
const categorySelect = document.getElementById('category_id');
const subcategoryContainer = document.getElementById('subcategory_container');
const subcategorySelect = document.getElementById('subcategory_id');
const oldSubcategory = "{{ old('subcategory_id', $document->subcategory_id) }}";

function fetchSubcategories(categoryId, selectedSub = null) {
    if (!categoryId) {
        subcategoryContainer.style.display = 'none';
        subcategorySelect.innerHTML = '<option value="">-- Pilih Subkategori --</option>';
        return;
    }
    
    fetch(`/admin/sub-categories/by-category/${categoryId}`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                subcategoryContainer.style.display = 'block';
                let options = '<option value="">-- Pilih Subkategori --</option>';
                data.forEach(sub => {
                    const isSelected = selectedSub == sub.id ? 'selected' : '';
                    options += `<option value="${sub.id}" ${isSelected}>${sub.nama}</option>`;
                });
                subcategorySelect.innerHTML = options;
            } else {
                subcategoryContainer.style.display = 'none';
                subcategorySelect.innerHTML = '<option value="">-- Pilih Subkategori --</option>';
            }
        });
}

categorySelect.addEventListener('change', function() {
    fetchSubcategories(this.value);
});

if (categorySelect.value) {
    fetchSubcategories(categorySelect.value, oldSubcategory);
}
</script>
@endpush
