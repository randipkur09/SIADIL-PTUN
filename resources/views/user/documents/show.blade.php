@extends('layouts.app')

@section('title', $document->nama_file)
@section('page-title', 'Detail Dokumen')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('user.documents.index') }}">Daftar Dokumen</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Detail Dokumen</h2>
        <p class="page-subheading">Informasi dokumen arsip</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('download', $document->id) }}" class="btn-siadil-primary">
            <i class="bi bi-download"></i> Download
        </a>
        <a href="{{ route('user.documents.index') }}" class="btn-siadil-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="siadil-card">
            {{-- File Header --}}
            <div style="background:linear-gradient(135deg,#e8f0fe,#dbeafe);padding:2rem;text-align:center;border-bottom:1px solid var(--siadil-gray-200)">
                <i class="bi {{ $document->file_icon }}" style="font-size:4.5rem;"></i>
                <h5 class="fw-700 mt-3 mb-1">{{ $document->nama_file }}</h5>
                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <span class="badge-type badge-{{ strtolower($document->file_type) === 'pdf' ? 'pdf' : (in_array(strtolower($document->file_type),['xls','xlsx']) ? 'xls' : (in_array(strtolower($document->file_type),['doc','docx']) ? 'doc' : (in_array(strtolower($document->file_type),['jpg','jpeg','png']) ? 'image' : 'other'))) }}">
                        {{ strtoupper($document->file_type) }}
                    </span>
                    <span class="fs-12 text-secondary">{{ $document->file_size_formatted }}</span>
                </div>
            </div>

            <div class="card-body-custom">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <div class="fs-12 text-secondary fw-600 text-uppercase mb-1">
                                <i class="bi bi-building me-1"></i>Ruangan
                            </div>
                            <div class="fw-600">{{ $document->category->nama_ruangan ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <div class="fs-12 text-secondary fw-600 text-uppercase mb-1">
                                <i class="bi bi-download me-1"></i>Total Download
                            </div>
                            <div class="fw-700 fs-5">{{ $document->downloads->count() }} <span class="fs-13 fw-400 text-secondary">kali</span></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <div class="fs-12 text-secondary fw-600 text-uppercase mb-1">
                                <i class="bi bi-person me-1"></i>Diunggah Oleh
                            </div>
                            <div class="fw-500">{{ $document->uploader->name ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded">
                            <div class="fs-12 text-secondary fw-600 text-uppercase mb-1">
                                <i class="bi bi-calendar me-1"></i>Tanggal Upload
                            </div>
                            <div>{{ $document->created_at->format('d F Y') }}</div>
                        </div>
                    </div>
                    @if($document->deskripsi)
                        <div class="col-12">
                            <div class="p-3 bg-light rounded">
                                <div class="fs-12 text-secondary fw-600 text-uppercase mb-2">
                                    <i class="bi bi-card-text me-1"></i>Deskripsi
                                </div>
                                <p class="mb-0 text-secondary">{{ $document->deskripsi }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="mt-4 pt-3 border-top">
                    <a href="{{ route('download', $document->id) }}" class="btn-siadil-primary me-2">
                        <i class="bi bi-download"></i>
                        Download Dokumen
                    </a>
                    <a href="{{ route('user.documents.index') }}" class="btn-siadil-secondary">
                        <i class="bi bi-arrow-left"></i>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
