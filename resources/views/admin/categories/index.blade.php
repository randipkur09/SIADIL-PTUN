@extends('layouts.app')

@section('title', 'Kategori & Folder')
@section('page-title', 'Kategori & Folder')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Kategori & Folder</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Kategori & Folder</h2>
        <p class="page-subheading">Kelola kategori dan folder arsip</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn-siadil-primary">
        <i class="bi bi-plus-circle"></i>
        Tambah Kategori
    </a>
</div>

<div class="siadil-card">
    <div class="card-header-custom">
        <div class="card-title-custom">
            <i class="bi bi-tags text-primary"></i>
            Daftar Kategori & Folder
        </div>
        <span class="badge bg-primary rounded-pill">{{ $categories->total() }} kategori</span>
    </div>

    @if($categories->count() > 0)
        <div class="table-responsive">
            <table class="table table-siadil mb-0">
                <thead>
                    <tr>
                        <th style="width:50px">#</th>
                        <th>Nama Kategori</th>
                        <th>Icon</th>
                        <th>Jumlah Dokumen</th>
                        <th>Dibuat</th>
                        <th class="text-center" style="width:100px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $i => $cat)
                        <tr>
                            <td class="text-secondary fs-12">{{ $categories->firstItem() + $i }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="btn-icon btn-icon-view" style="pointer-events:none">
                                        <i class="bi {{ $cat->icon ?? 'bi-folder' }}"></i>
                                    </div>
                                    <span class="fw-600">{{ $cat->nama }}</span>
                                </div>
                            </td>
                            <td class="text-secondary fs-13"><i class="bi {{ $cat->icon }}"></i> {{ $cat->icon ?? '-' }}</td>
                            <td>
                                <span class="badge-category">{{ $cat->documents_count }} dokumen</span>
                            </td>
                            <td class="fs-12 text-secondary">{{ $cat->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('admin.categories.edit', $cat) }}" class="btn-icon btn-icon-edit" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn-icon btn-icon-delete" title="Hapus"
                                        onclick="confirmDelete('{{ route('admin.categories.destroy', $cat) }}', '{{ $cat->nama }}', {{ $cat->documents_count }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="d-flex justify-content-between align-items-center px-3 py-3 border-top">
                <div class="fs-12 text-secondary">
                    Menampilkan {{ $categories->firstItem() }}-{{ $categories->lastItem() }} dari {{ $categories->total() }} kategori
                </div>
                {{ $categories->links('vendor.pagination.bootstrap-5') }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <i class="bi bi-folder-x"></i>
            <h6>Belum Ada Kategori</h6>
            <p>Tambahkan kategori folder untuk mengorganisir dokumen.</p>
            <a href="{{ route('admin.categories.create') }}" class="btn-siadil-primary">
                <i class="bi bi-plus-circle"></i> Tambah Kategori
            </a>
        </div>
    @endif
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <div class="modal-title d-flex align-items-center gap-2">
                    <div class="btn-icon btn-icon-delete" style="pointer-events:none"><i class="bi bi-trash"></i></div>
                    <span class="fw-700">Konfirmasi Hapus Kategori</span>
                </div>
            </div>
            <div class="modal-body">
                <p>Anda akan menghapus kategori: <strong id="deleteCatName"></strong></p>
                <div class="alert alert-siadil alert-siadil-danger d-none" id="hasDocAlert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    Kategori ini memiliki dokumen dan tidak dapat dihapus. Pindahkan atau hapus dokumen terlebih dahulu.
                </div>
                <div class="alert alert-siadil alert-siadil-warning d-none" id="noDocAlert">
                    <i class="bi bi-info-circle"></i>
                    Kategori ini tidak memiliki dokumen dan aman untuk dihapus.
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn-siadil-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger fw-600" id="deleteConfirmBtn">
                        <i class="bi bi-trash me-1"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(url, name, docCount) {
    document.getElementById('deleteCatName').textContent = name;
    document.getElementById('deleteForm').action = url;
    const hasDocAlert = document.getElementById('hasDocAlert');
    const noDocAlert  = document.getElementById('noDocAlert');
    const confirmBtn  = document.getElementById('deleteConfirmBtn');

    if (docCount > 0) {
        hasDocAlert.classList.remove('d-none');
        noDocAlert.classList.add('d-none');
        confirmBtn.disabled = true;
    } else {
        hasDocAlert.classList.add('d-none');
        noDocAlert.classList.remove('d-none');
        confirmBtn.disabled = false;
    }
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush
