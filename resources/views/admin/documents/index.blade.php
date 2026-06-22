@extends('layouts.app')

@section('title', 'Data Dokumen')
@section('page-title', 'Data Dokumen')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Data Dokumen</li>
@endsection

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <div>
        <h2 class="page-heading">Data Dokumen</h2>
        <p class="page-subheading">Kelola seluruh arsip dokumen digital</p>
    </div>
    <a href="{{ route('admin.documents.create') }}" class="btn-siadil-primary">
        <i class="bi bi-cloud-upload"></i>
        Upload Dokumen
    </a>
</div>

{{-- Filter & Search --}}
<div class="siadil-card mb-3">
    <div class="card-body-custom py-3">
        <form action="{{ route('admin.documents.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-12 col-md-4">
                <div class="search-wrapper">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama dokumen..."
                           value="{{ request('search') }}" id="searchInput">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <select name="category_id" class="form-select form-select-sm">
                    <option value="">Semua Ruangan</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nama_ruangan }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="deleted" {{ request('status') === 'deleted' ? 'selected' : '' }}>Dihapus</option>
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn-siadil-primary flex-grow-1">
                    <i class="bi bi-filter"></i> Filter
                </button>
                @if(request()->hasAny(['search','category_id','status']))
                    <a href="{{ route('admin.documents.index') }}" class="btn-siadil-secondary">
                        <i class="bi bi-x"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Documents Table --}}
<div class="siadil-card">
    <div class="card-header-custom">
        <div class="card-title-custom">
            <i class="bi bi-file-earmark-text text-primary"></i>
            Daftar Dokumen
        </div>
        <span class="badge bg-primary rounded-pill">{{ $documents->total() }} dokumen</span>
    </div>

    @if($documents->count() > 0)
        <div class="table-responsive">
            <table class="table table-siadil mb-0">
                <thead>
                    <tr>
                        <th style="width:50px">#</th>
                        <th>Nama Dokumen</th>
                        <th>Ruangan</th>
                        <th>Tipe</th>
                        <th>Ukuran</th>
                        <th>Diunggah</th>
                        <th>Status</th>
                        <th class="text-center" style="width:140px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents as $i => $doc)
                        <tr class="{{ $doc->deleted_at ? 'table-danger opacity-75' : '' }}">
                            <td class="text-secondary fs-12">{{ $documents->firstItem() + $i }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi {{ $doc->file_icon }} fs-5"></i>
                                    <div>
                                        <div class="fw-600">{{ Str::limit($doc->nama_file, 40) }}</div>
                                        @if($doc->deskripsi)
                                            <div class="fs-12 text-secondary text-truncate" style="max-width:250px">{{ $doc->deskripsi }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-category">{{ $doc->category->nama_ruangan ?? '-' }}</span>
                            </td>
                            <td>
                                @php $type = strtolower($doc->file_type ?? ''); @endphp
                                <span class="badge-type badge-{{ $type === 'pdf' ? 'pdf' : ($type === 'xls' || $type === 'xlsx' ? 'xls' : ($type === 'doc' || $type === 'docx' ? 'doc' : (in_array($type,['jpg','jpeg','png']) ? 'image' : 'other'))) }}">
                                    {{ strtoupper($doc->file_type ?? '-') }}
                                </span>
                            </td>
                            <td class="fs-12 text-secondary text-nowrap">{{ $doc->file_size_formatted }}</td>
                            <td>
                                <div class="fs-13 fw-500">{{ $doc->uploader->name ?? '-' }}</div>
                                <div class="fs-12 text-secondary">{{ $doc->created_at->format('d M Y') }}</div>
                            </td>
                            <td>
                                @if($doc->deleted_at)
                                    <span class="badge bg-danger-subtle text-danger fs-12">Dihapus</span>
                                @else
                                    <span class="badge bg-success-subtle text-success fs-12">Aktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    @if(!$doc->deleted_at)
                                        <a href="{{ route('admin.documents.show', $doc->id) }}" class="btn-icon btn-icon-view" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.documents.edit', $doc->id) }}" class="btn-icon btn-icon-edit" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="{{ route('download', $doc->id) }}" class="btn-icon btn-icon-download" title="Download">
                                            <i class="bi bi-download"></i>
                                        </a>
                                        <button type="button" class="btn-icon btn-icon-delete" title="Hapus"
                                            onclick="confirmDelete('{{ route('admin.documents.destroy', $doc->id) }}', '{{ $doc->nama_file }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @else
                                        <form action="{{ route('admin.documents.restore', $doc->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn-icon btn-icon-restore" title="Pulihkan">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="btn-icon btn-icon-delete" title="Hapus Permanen"
                                            onclick="confirmForceDelete('{{ route('admin.documents.force-delete', $doc->id) }}', '{{ $doc->nama_file }}')">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($documents->hasPages())
            <div class="d-flex justify-content-between align-items-center px-3 py-3 border-top">
                <div class="fs-12 text-secondary">
                    Menampilkan {{ $documents->firstItem() }}-{{ $documents->lastItem() }} dari {{ $documents->total() }} dokumen
                </div>
                {{ $documents->links('vendor.pagination.bootstrap-5') }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <i class="bi bi-file-earmark-x"></i>
            <h6>Tidak Ada Dokumen</h6>
            <p>Belum ada dokumen yang diunggah. Klik tombol Upload Dokumen untuk menambahkan.</p>
            <a href="{{ route('admin.documents.create') }}" class="btn-siadil-primary">
                <i class="bi bi-cloud-upload"></i> Upload Sekarang
            </a>
        </div>
    @endif
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <div class="modal-title d-flex align-items-center gap-2">
                    <div class="btn-icon btn-icon-delete" style="pointer-events:none">
                        <i class="bi bi-trash"></i>
                    </div>
                    <span class="fw-700">Konfirmasi Hapus</span>
                </div>
            </div>
            <div class="modal-body">
                <p class="text-secondary mb-1">Dokumen berikut akan dipindahkan ke tempat sampah:</p>
                <p class="fw-600" id="deleteDocName"></p>
                <div class="alert alert-siadil alert-siadil-warning">
                    <i class="bi bi-info-circle"></i>
                    Dokumen dapat dipulihkan dari daftar dengan status "Dihapus".
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn-siadil-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger fw-600">
                        <i class="bi bi-trash me-1"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="forceDeleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <div class="modal-title d-flex align-items-center gap-2">
                    <div class="btn-icon btn-icon-delete" style="pointer-events:none">
                        <i class="bi bi-trash3"></i>
                    </div>
                    <span class="fw-700 text-danger">Hapus Permanen</span>
                </div>
            </div>
            <div class="modal-body">
                <p class="text-secondary mb-1">Dokumen berikut akan dihapus secara permanen:</p>
                <p class="fw-600" id="forceDeleteDocName"></p>
                <div class="alert alert-siadil alert-siadil-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    Tindakan ini tidak dapat dibatalkan! File akan dihapus dari server.
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn-siadil-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="forceDeleteForm" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger fw-600">
                        <i class="bi bi-trash3 me-1"></i>Hapus Permanen
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(url, name) {
    document.getElementById('deleteDocName').textContent = name;
    document.getElementById('deleteForm').action = url;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function confirmForceDelete(url, name) {
    document.getElementById('forceDeleteDocName').textContent = name;
    document.getElementById('forceDeleteForm').action = url;
    new bootstrap.Modal(document.getElementById('forceDeleteModal')).show();
}
</script>
@endpush
