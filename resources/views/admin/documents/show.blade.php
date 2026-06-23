@extends('layouts.app')

@section('title', 'Detail Dokumen')
@section('page-title', 'Detail Dokumen')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.documents.index') }}">Data Dokumen</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Detail Dokumen</h2>
        <p class="page-subheading">Informasi lengkap dokumen</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('download', $document->id) }}" class="btn-siadil-primary">
            <i class="bi bi-download"></i> Download
        </a>
        <a href="{{ route('admin.documents.edit', $document) }}" class="btn-siadil-secondary">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('admin.documents.index') }}" class="btn-siadil-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        {{-- Document Info --}}
        <div class="siadil-card mb-3">
            <div class="card-header-custom">
                <div class="card-title-custom">
                    <i class="bi bi-info-circle text-primary"></i>
                    Informasi Dokumen
                </div>
            </div>
            <div class="card-body-custom">
                <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded">
                    <i class="bi {{ $document->file_icon }}" style="font-size:3.5rem;"></i>
                    <div>
                        <h5 class="fw-700 mb-1">{{ $document->judul }}</h5>
                        <div class="fs-13 text-secondary mb-2"><i class="bi bi-file-earmark-text"></i> {{ $document->nama_file }}</div>
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="badge-type badge-{{ strtolower($document->ekstensi) === 'pdf' ? 'pdf' : (in_array(strtolower($document->ekstensi),['xls','xlsx']) ? 'xls' : (in_array(strtolower($document->ekstensi),['doc','docx']) ? 'doc' : (in_array(strtolower($document->ekstensi),['jpg','jpeg','png']) ? 'image' : 'other'))) }}">
                                {{ strtoupper($document->ekstensi) }}
                            </span>
                            <span class="fs-12 text-secondary">{{ $document->file_size_formatted }}</span>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="fs-12 text-secondary fw-600 text-uppercase mb-1">Nomor Dokumen</div>
                        <div class="fw-500">{{ $document->nomor_dokumen ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="fs-12 text-secondary fw-600 text-uppercase mb-1">Kategori / Subkategori</div>
                        <span class="badge-category fs-13">{{ $document->category->nama ?? '-' }}</span>
                        @if($document->subCategory)
                            <span class="badge bg-secondary rounded-pill ms-1">{{ $document->subCategory->nama }}</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="fs-12 text-secondary fw-600 text-uppercase mb-1">Tujuan Ruangan</div>
                        <div class="fw-500">
                            @if($document->room)
                                <span class="badge-user fs-13">{{ $document->room->name }}</span>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="fs-12 text-secondary fw-600 text-uppercase mb-1">Tanggal Dokumen</div>
                        <div class="fw-500">{{ $document->tanggal ? \Carbon\Carbon::parse($document->tanggal)->format('d F Y') : '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="fs-12 text-secondary fw-600 text-uppercase mb-1">Diunggah Oleh</div>
                        <div class="fw-500">{{ $document->uploader->name ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="fs-12 text-secondary fw-600 text-uppercase mb-1">Tanggal Upload</div>
                        <div>{{ $document->created_at->format('d F Y, H:i') }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="fs-12 text-secondary fw-600 text-uppercase mb-1">Terakhir Diperbarui</div>
                        <div>{{ $document->updated_at->format('d F Y, H:i') }}</div>
                    </div>
                    <div class="col-12">
                        <div class="fs-12 text-secondary fw-600 text-uppercase mb-1">Total Download</div>
                        <div class="fw-700 fs-5">{{ $document->downloads->count() }} <span class="fs-13 fw-400 text-secondary">kali</span></div>
                    </div>
                    @if($document->keterangan)
                        <div class="col-12">
                            <div class="fs-12 text-secondary fw-600 text-uppercase mb-1">Keterangan</div>
                            <p class="mb-0 text-secondary">{{ $document->keterangan }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Download History --}}
    <div class="col-lg-4">
        <div class="siadil-card">
            <div class="card-header-custom">
                <div class="card-title-custom">
                    <i class="bi bi-clock-history text-info"></i>
                    Riwayat Download
                </div>
                <span class="badge bg-info-subtle text-info rounded-pill">{{ $document->downloads->count() }}</span>
            </div>
            <div class="card-body-custom p-0" style="max-height:400px;overflow-y:auto">
                @forelse($document->downloads->sortByDesc('downloaded_at') as $dl)
                    <div class="d-flex align-items-center gap-2 px-3 py-2 border-bottom">
                        <div class="sidebar-user-avatar flex-shrink-0" style="width:30px;height:30px;font-size:0.7rem;">
                            {{ strtoupper(substr($dl->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <div class="fs-13 fw-600">{{ $dl->user->name ?? 'Unknown' }}</div>
                            <div class="fs-12 text-secondary">{{ $dl->downloaded_at->format('d M Y H:i') }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-secondary fs-13">
                        <i class="bi bi-download d-block fs-2 mb-2 opacity-50"></i>
                        Belum ada yang mendownload
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
