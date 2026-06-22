@extends('layouts.app')

@section('title', 'Daftar Dokumen')
@section('page-title', 'Daftar Dokumen')

@section('breadcrumb')
    <li class="breadcrumb-item active">Daftar Dokumen</li>
@endsection

@section('content')

{{-- Search Header --}}
<div class="user-search-header">
    <h4><i class="bi bi-archive me-2"></i>Arsip Dokumen Digital</h4>
    <p>PTUN Bandar Lampung - Sistem Informasi Arsip Digital</p>

    <form action="{{ route('user.documents.index') }}" method="GET">
        <div class="row g-2">
            <div class="col-12 col-md-6">
                <div class="search-wrapper">
                    <i class="bi bi-search search-icon search-icon-user"></i>
                    <input type="text" name="search" class="form-control search-input"
                           placeholder="Cari nama dokumen..." value="{{ request('search') }}" id="searchInput">
                </div>
            </div>
            <div class="col-8 col-md-4">
                <select name="category_id" class="form-select form-select-sm"
                    style="background:rgba(255,255,255,0.15);border:1.5px solid rgba(255,255,255,0.3);color:#fff;"
                    onchange="this.form.submit()">
                    <option value="" style="color:#333">Semua Ruangan</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" style="color:#333"
                            {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nama_ruangan }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-4 col-md-2">
                <button type="submit" class="btn btn-light fw-600 w-100">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>
    </form>
</div>

{{-- Results Summary --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <span class="fw-600">{{ $documents->total() }} dokumen ditemukan</span>
        @if(request('search') || request('category_id'))
            <a href="{{ route('user.documents.index') }}" class="btn-siadil-secondary ms-2" style="font-size:0.78rem;padding:0.25rem 0.65rem">
                <i class="bi bi-x"></i> Reset
            </a>
        @endif
    </div>
    @if(request('category_id'))
        @php $activeCat = $categories->firstWhere('id', request('category_id')); @endphp
        @if($activeCat)
            <span class="badge-category">
                <i class="bi bi-building me-1"></i>{{ $activeCat->nama_ruangan }}
            </span>
        @endif
    @endif
</div>

{{-- Category Filter Pills --}}
<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="{{ route('user.documents.index', array_merge(request()->all(), ['category_id' => ''])) }}"
       class="badge rounded-pill {{ !request('category_id') ? 'bg-primary text-white' : 'bg-light text-secondary border' }}"
       style="padding:0.4rem 0.9rem;font-size:0.8rem;text-decoration:none">
        Semua
    </a>
    @foreach($categories as $cat)
        <a href="{{ route('user.documents.index', array_merge(request()->all(), ['category_id' => $cat->id])) }}"
           class="badge rounded-pill {{ request('category_id') == $cat->id ? 'bg-primary text-white' : 'bg-light text-secondary border' }}"
           style="padding:0.4rem 0.9rem;font-size:0.8rem;text-decoration:none">
            {{ $cat->nama_ruangan }}
        </a>
    @endforeach
</div>

{{-- Document Grid --}}
@if($documents->count() > 0)
    <div class="row g-3">
        @foreach($documents as $doc)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="doc-grid-card">
                    <div class="doc-card-header">
                        <i class="bi {{ $doc->file_icon }} doc-icon"></i>
                    </div>
                    <div class="doc-card-body">
                        <div class="doc-card-title">{{ $doc->nama_file }}</div>
                        @if($doc->deskripsi)
                            <div class="fs-12 text-secondary text-truncate-2 mb-2">{{ $doc->deskripsi }}</div>
                        @endif
                        <div class="doc-card-meta">
                            <span class="badge-category fs-11">{{ $doc->category->nama_ruangan ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="doc-card-footer">
                        <span class="fs-12 text-secondary">{{ $doc->created_at->format('d M Y') }}</span>
                        <div class="d-flex gap-1">
                            <a href="{{ route('user.documents.show', $doc) }}"
                               class="btn-icon btn-icon-view" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('download', $doc->id) }}"
                               class="btn-icon btn-icon-download" title="Download">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($documents->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $documents->links('vendor.pagination.bootstrap-5') }}
        </div>
    @endif
@else
    <div class="siadil-card">
        <div class="empty-state">
            <i class="bi bi-file-earmark-x"></i>
            <h6>Dokumen Tidak Ditemukan</h6>
            <p>Tidak ada dokumen yang sesuai dengan pencarian Anda.</p>
            <a href="{{ route('user.documents.index') }}" class="btn-siadil-secondary">
                <i class="bi bi-arrow-left"></i> Lihat Semua
            </a>
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
// Real-time search with debounce
let searchTimer;
document.getElementById('searchInput')?.addEventListener('input', function() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => this.form.submit(), 500);
});
</script>
@endpush
