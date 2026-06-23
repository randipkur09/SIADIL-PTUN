@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card blue">
            <div class="stat-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
            <div class="stat-value">{{ number_format($totalDocuments) }}</div>
            <div class="stat-label">Total Dokumen</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card green">
            <div class="stat-icon"><i class="bi bi-folder-fill"></i></div>
            <div class="stat-value">{{ number_format($totalCategories) }}</div>
            <div class="stat-label">Kategori / Folder</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card orange">
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <div class="stat-value">{{ number_format($totalUsers) }}</div>
            <div class="stat-label">Total Pengguna</div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card purple">
            <div class="stat-icon"><i class="bi bi-download"></i></div>
            <div class="stat-value">{{ number_format($totalDownloads) }}</div>
            <div class="stat-label">Total Download</div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Recent Documents --}}
    <div class="col-lg-8">
        <div class="siadil-card h-100">
            <div class="card-header-custom">
                <div class="card-title-custom">
                    <i class="bi bi-file-earmark-text text-primary"></i>
                    Dokumen Terbaru
                </div>
                <a href="{{ route('admin.documents.index') }}" class="btn-siadil-secondary btn-sm">
                    <i class="bi bi-arrow-right"></i> Lihat Semua
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-siadil mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Judul Dokumen</th>
                            <th>Kategori</th>
                            <th>Tipe</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentDocuments as $i => $doc)
                            <tr>
                                <td class="text-secondary fs-12">{{ $i + 1 }}</td>
                                <td>
                                    <a href="{{ route('admin.documents.show', $doc) }}" class="fw-600 text-decoration-none">
                                        {{ Str::limit($doc->judul, 40) }}
                                    </a>
                                    <div class="fs-12 text-secondary">Oleh: {{ $doc->uploader->name ?? '-' }}</div>
                                </td>
                                <td>
                                    <span class="badge-category">{{ $doc->category->nama ?? '-' }}</span>
                                </td>
                                <td>
                                    @php $type = strtolower($doc->ekstensi ?? 'other'); @endphp
                                    <span class="badge-type badge-{{ in_array($type, ['pdf','doc','docx','xls','xlsx']) ? ($type === 'pdf' ? 'pdf' : ($type === 'xls' || $type === 'xlsx' ? 'xls' : 'doc')) : (in_array($type, ['jpg','jpeg','png']) ? 'image' : 'other') }}">
                                        {{ strtoupper($doc->ekstensi ?? '-') }}
                                    </span>
                                </td>
                                <td class="fs-12 text-secondary text-nowrap">
                                    {{ $doc->created_at->format('d M Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state py-3">
                                        <i class="bi bi-file-earmark-x"></i>
                                        <p class="mb-0 fs-13">Belum ada dokumen</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Quick Actions + Recent Downloads --}}
    <div class="col-lg-4 d-flex flex-column gap-3">
        {{-- Quick Actions --}}
        <div class="siadil-card">
            <div class="card-header-custom">
                <div class="card-title-custom">
                    <i class="bi bi-lightning-fill text-warning"></i>
                    Aksi Cepat
                </div>
            </div>
            <div class="card-body-custom d-grid gap-2">
                <a href="{{ route('admin.documents.create') }}" class="btn-siadil-primary justify-content-center">
                    <i class="bi bi-cloud-upload"></i>
                    Upload Dokumen
                </a>
                <a href="{{ route('admin.categories.create') }}" class="btn-siadil-secondary justify-content-center">
                    <i class="bi bi-folder-plus"></i>
                    Tambah Kategori/Folder
                </a>
                <a href="{{ route('admin.users.create') }}" class="btn-siadil-secondary justify-content-center">
                    <i class="bi bi-person-plus"></i>
                    Tambah Pengguna
                </a>
            </div>
        </div>

        {{-- Riwayat Download Terbaru --}}
        <div class="siadil-card flex-grow-1">
            <div class="card-header-custom">
                <div class="card-title-custom">
                    <i class="bi bi-clock-history text-info"></i>
                    Download Terbaru
                </div>
            </div>
            <div class="card-body-custom p-0">
                @forelse($recentDownloads as $dl)
                    <div class="d-flex align-items-start gap-2 px-3 py-2 border-bottom">
                        <div class="sidebar-user-avatar flex-shrink-0" style="width:30px;height:30px;font-size:0.7rem;">
                            {{ strtoupper(substr($dl->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="flex-grow-1 min-width-0">
                            <div class="fs-13 fw-600 text-truncate">{{ Str::limit($dl->document->judul ?? '-', 30) }}</div>
                            <div class="fs-12 text-secondary">{{ $dl->user->name ?? '-' }} &bull; {{ $dl->downloaded_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-3 text-secondary fs-13">Belum ada riwayat download</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
