@extends('layouts.app')

@section('title', 'Semua Dokumen')
@section('page-title', 'Semua Dokumen')

@section('breadcrumb')
    <li class="breadcrumb-item active">Semua Dokumen</li>
@endsection

@section('content')

{{-- Filter & Search --}}
<div class="siadil-card mb-3">
    <div class="card-body-custom py-3">
        <form action="{{ route('user.documents.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-12 col-md-3">
                <div class="search-wrapper">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" name="search" class="form-control" placeholder="Cari judul / nomor dokumen..."
                           value="{{ request('search') }}" id="searchInput">
                </div>
            </div>
            <div class="col-6 col-md-2">
                <select name="category_id" class="form-select form-select-sm" id="filter_category_id">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="subcategory_id" class="form-select form-select-sm" id="filter_subcategory_id">
                    <option value="">Semua Subkategori</option>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ request('tanggal') }}" title="Filter Tanggal">
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn-siadil-primary flex-grow-1">
                    <i class="bi bi-filter"></i> Filter
                </button>
                @if(request()->hasAny(['search','category_id','subcategory_id','tanggal']))
                    <a href="{{ route('user.documents.index') }}" class="btn-siadil-secondary">
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
                        <th>Judul Dokumen</th>
                        <th>Nomor</th>
                        <th>Kategori</th>
                        <th>Subkategori</th>
                        <th>Tanggal</th>
                        <th>File</th>
                        <th class="text-center" style="width:100px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents as $i => $doc)
                        <tr>
                            <td class="text-secondary fs-12">{{ $documents->firstItem() + $i }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi {{ $doc->file_icon }} fs-5"></i>
                                    <div>
                                        <div class="fw-600">{{ Str::limit($doc->judul, 40) }}</div>
                                        <div class="fs-12 text-secondary">{{ $doc->nama_file }}</div>
                                        @if($doc->keterangan)
                                            <div class="fs-12 text-secondary text-truncate" style="max-width:250px">{{ $doc->keterangan }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $doc->nomor_dokumen ?? '-' }}</td>
                            <td>
                                <span class="badge-category">{{ $doc->category->nama ?? '-' }}</span>
                            </td>
                            <td>
                                @if($doc->subCategory)
                                    <span class="badge bg-secondary rounded-pill" style="font-size: 0.7rem;">{{ $doc->subCategory->nama }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="fs-12 text-secondary text-nowrap">
                                {{ $doc->tanggal ? \Carbon\Carbon::parse($doc->tanggal)->format('d M Y') : '-' }}
                            </td>
                            <td>
                                @php $type = strtolower($doc->ekstensi ?? ''); @endphp
                                <span class="badge-type badge-{{ $type === 'pdf' ? 'pdf' : ($type === 'xls' || $type === 'xlsx' ? 'xls' : ($type === 'doc' || $type === 'docx' ? 'doc' : (in_array($type,['jpg','jpeg','png']) ? 'image' : 'other'))) }} mb-1 d-inline-block">
                                    {{ strtoupper($doc->ekstensi ?? '-') }}
                                </span>
                                <div class="fs-12 text-secondary text-nowrap">{{ $doc->file_size_formatted }}</div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('user.documents.show', $doc->id) }}" class="btn-icon btn-icon-view" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('download', $doc->id) }}" class="btn-icon btn-icon-download" title="Download">
                                        <i class="bi bi-download"></i>
                                    </a>
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
            <p>Belum ada dokumen yang sesuai dengan pencarian Anda.</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
const catSelect = document.getElementById('filter_category_id');
const subSelect = document.getElementById('filter_subcategory_id');
const oldSub = "{{ request('subcategory_id') }}";

if(catSelect) {
    catSelect.addEventListener('change', function() {
        if(!this.value) {
            subSelect.innerHTML = '<option value="">Semua Subkategori</option>';
            return;
        }
        fetch(`/admin/sub-categories/by-category/${this.value}`)
            .then(res => res.json())
            .then(data => {
                let options = '<option value="">Semua Subkategori</option>';
                data.forEach(sub => {
                    options += `<option value="${sub.id}" ${oldSub == sub.id ? 'selected' : ''}>${sub.nama}</option>`;
                });
                subSelect.innerHTML = options;
            });
    });

    if(catSelect.value) {
        catSelect.dispatchEvent(new Event('change'));
    }
}
</script>
@endpush
