@extends('layouts.app')

@section('title', 'Folder Ruangan')
@section('page-title', 'Folder Ruangan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Folder Ruangan</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Folder Ruangan</h2>
        <p class="page-subheading">Kelola dokumen berdasarkan ruangan tujuan</p>
    </div>
</div>

<div class="row g-4">
    @foreach($rooms as $room)
        <div class="col-md-6 col-lg-4">
            <div class="siadil-card h-100">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="bg-primary-subtle text-primary rounded d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-folder-fill fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-700 mb-0 text-dark">{{ $room->name }}</h5>
                            <span class="fs-12 text-secondary">{{ '@'.$room->username }}</span>
                        </div>
                    </div>
                    
                    <div class="mb-4 flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-file-earmark-text text-secondary"></i>
                            <span class="fw-600 fs-5">{{ $room->room_documents_count }}</span>
                            <span class="text-secondary fs-13">Dokumen Tersimpan</span>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 mt-auto border-top pt-3">
                        <a href="{{ route('admin.documents.index', ['room_id' => $room->id]) }}" class="btn-siadil-secondary flex-grow-1 text-center justify-content-center" style="padding: 0.5rem;">
                            <i class="bi bi-eye"></i> Lihat Folder
                        </a>
                        <a href="{{ route('admin.documents.create', ['room_id' => $room->id]) }}" class="btn-siadil-primary" style="padding: 0.5rem 0.75rem;" title="Upload ke Ruangan ini">
                            <i class="bi bi-cloud-upload"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
