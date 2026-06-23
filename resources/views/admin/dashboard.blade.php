@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@push('styles')
<style>
    /* ── Welcome Banner ── */
    .welcome-banner {
        background: linear-gradient(135deg, #0d9488 0%, #0f766e 50%, #115e59 100%);
        border-radius: 16px;
        padding: 28px 32px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -5%;
        width: 260px;
        height: 260px;
        background: rgba(255,255,255,0.06);
        border-radius: 50%;
    }
    .welcome-banner::after {
        content: '';
        position: absolute;
        bottom: -50%;
        right: 10%;
        width: 180px;
        height: 180px;
        background: rgba(255,255,255,0.04);
        border-radius: 50%;
    }
    .welcome-banner h2 {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 4px;
    }
    .welcome-banner p {
        font-size: 0.85rem;
        opacity: 0.85;
        margin: 0;
    }
    .welcome-date {
        font-size: 0.78rem;
        opacity: 0.7;
        margin-top: 8px;
    }

    /* ── Metric Tiles ── */
    .metric-tile {
        background: #fff;
        border-radius: 14px;
        padding: 20px 22px;
        border: 1px solid #e8ecf1;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
    }
    .metric-tile:hover {
        border-color: #c7d2db;
        box-shadow: 0 6px 20px rgba(0,0,0,0.06);
        transform: translateY(-2px);
    }
    .metric-tile .tile-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    .metric-tile .tile-data {
        flex: 1;
        min-width: 0;
    }
    .metric-tile .tile-number {
        font-size: 1.75rem;
        font-weight: 800;
        line-height: 1;
        color: #1a202c;
        letter-spacing: -0.03em;
    }
    .metric-tile .tile-label {
        font-size: 0.75rem;
        color: #718096;
        font-weight: 500;
        margin-top: 3px;
    }
    .metric-tile .tile-trend {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 2px 7px;
        border-radius: 6px;
        position: absolute;
        top: 12px;
        right: 14px;
    }
    .tile-trend.up { background: #ecfdf5; color: #059669; }

    .tile-icon.docs { background: #eef2ff; color: #4f46e5; }
    .tile-icon.cats { background: #f0fdf4; color: #16a34a; }
    .tile-icon.users { background: #fff7ed; color: #ea580c; }
    .tile-icon.dls { background: #faf5ff; color: #9333ea; }

    /* ── Section Heading ── */
    .section-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }
    .section-head h3 {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .section-head h3 .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    /* ── Shortcut Grid ── */
    .shortcut-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }
    .shortcut-item {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #fff;
        border: 1px solid #e8ecf1;
        border-radius: 12px;
        padding: 14px 16px;
        text-decoration: none;
        color: #334155;
        font-weight: 600;
        font-size: 0.82rem;
        transition: all 0.2s ease;
    }
    .shortcut-item:hover {
        border-color: var(--siadil-primary);
        color: var(--siadil-primary);
        background: #f0fdfa;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(13,148,136,0.08);
    }
    .shortcut-item .sc-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }
    .shortcut-item:nth-child(1) .sc-icon { background: #ecfdf5; color: #059669; }
    .shortcut-item:nth-child(2) .sc-icon { background: #eef2ff; color: #4f46e5; }
    .shortcut-item:nth-child(3) .sc-icon { background: #fff7ed; color: #ea580c; }
    .shortcut-item:nth-child(4) .sc-icon { background: #faf5ff; color: #9333ea; }

    /* ── Doc List Clean ── */
    .doc-list-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 16px;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.15s;
    }
    .doc-list-item:last-child { border-bottom: none; }
    .doc-list-item:hover { background: #f8fafc; }
    .doc-list-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 0.03em;
        flex-shrink: 0;
        text-transform: uppercase;
        color: #fff;
    }
    .doc-list-icon.pdf  { background: #ef4444; }
    .doc-list-icon.docx, .doc-list-icon.doc { background: #3b82f6; }
    .doc-list-icon.xlsx, .doc-list-icon.xls { background: #22c55e; }
    .doc-list-icon.other { background: #94a3b8; }
    .doc-list-info { flex: 1; min-width: 0; }
    .doc-list-title {
        font-size: 0.82rem;
        font-weight: 600;
        color: #1e293b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
        text-decoration: none;
    }
    .doc-list-title:hover { color: var(--siadil-primary); }
    .doc-list-meta {
        font-size: 0.72rem;
        color: #94a3b8;
        margin-top: 2px;
    }
    .doc-list-date {
        font-size: 0.72rem;
        color: #94a3b8;
        white-space: nowrap;
        flex-shrink: 0;
    }

    /* ── Activity Feed ── */
    .activity-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 10px 16px;
        border-bottom: 1px solid #f1f5f9;
    }
    .activity-item:last-child { border-bottom: none; }
    .activity-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #d1d5db;
        flex-shrink: 0;
        margin-top: 6px;
    }
    .activity-dot.recent { background: var(--siadil-primary); }
    .activity-text {
        font-size: 0.78rem;
        color: #475569;
        line-height: 1.4;
    }
    .activity-text strong { color: #1e293b; }
    .activity-time {
        font-size: 0.68rem;
        color: #94a3b8;
        margin-top: 2px;
    }

    /* ── Info Cards Bottom ── */
    .info-mini {
        background: #fff;
        border: 1px solid #e8ecf1;
        border-radius: 12px;
        padding: 18px 20px;
    }
    .info-mini h5 {
        font-size: 0.82rem;
        font-weight: 700;
        color: #475569;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .info-mini .info-val {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1e293b;
    }
    .info-mini .info-sub {
        font-size: 0.72rem;
        color: #94a3b8;
    }
</style>
@endpush

@section('content')

{{-- Welcome Banner --}}
<div class="welcome-banner mb-4">
    <h2>Selamat datang, {{ auth()->user()->name }}.</h2>
    <p>Pantau arsip digital PTUN Bandar Lampung dari sini.</p>
    <div class="welcome-date">
        <i class="bi bi-calendar3 me-1"></i>
        {{ now()->translatedFormat('l, d F Y') }}
    </div>
</div>

{{-- Metrics --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="metric-tile">
            <div class="tile-icon docs">
                <i class="bi bi-file-earmark-text"></i>
            </div>
            <div class="tile-data">
                <div class="tile-number">{{ number_format($totalDocuments) }}</div>
                <div class="tile-label">Dokumen</div>
            </div>
            @if($dokumenBulanIni > 0)
                <span class="tile-trend up">+{{ $dokumenBulanIni }} bln ini</span>
            @endif
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="metric-tile">
            <div class="tile-icon cats">
                <i class="bi bi-folder2"></i>
            </div>
            <div class="tile-data">
                <div class="tile-number">{{ number_format($totalCategories) }}</div>
                <div class="tile-label">Kategori & Folder</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="metric-tile">
            <div class="tile-icon users">
                <i class="bi bi-building"></i>
            </div>
            <div class="tile-data">
                <div class="tile-number">{{ number_format($totalUsers) }}</div>
                <div class="tile-label">Ruangan</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="metric-tile">
            <div class="tile-icon dls">
                <i class="bi bi-arrow-down-circle"></i>
            </div>
            <div class="tile-data">
                <div class="tile-number">{{ number_format($totalDownloads) }}</div>
                <div class="tile-label">Unduhan</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- Recent Documents --}}
    <div class="col-lg-8">
        <div class="siadil-card h-100" style="border-radius:14px;">
            <div class="section-head px-3 pt-3">
                <h3>
                    <span class="dot" style="background:#4f46e5;"></span>
                    Dokumen Masuk Terbaru
                </h3>
                <a href="{{ route('admin.documents.index') }}" class="btn-siadil-secondary btn-sm" style="font-size:0.75rem;">
                    Lihat semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div>
                @forelse($recentDocuments as $doc)
                    @php $ext = strtolower($doc->ekstensi ?? 'other'); @endphp
                    <div class="doc-list-item">
                        <div class="doc-list-icon {{ in_array($ext, ['pdf']) ? 'pdf' : (in_array($ext, ['doc','docx']) ? 'docx' : (in_array($ext, ['xls','xlsx']) ? 'xlsx' : 'other')) }}">
                            {{ strtoupper($ext) }}
                        </div>
                        <div class="doc-list-info">
                            <a href="{{ route('admin.documents.show', $doc) }}" class="doc-list-title">{{ Str::limit($doc->judul, 50) }}</a>
                            <div class="doc-list-meta">
                                {{ $doc->category->nama ?? '-' }}
                                @if($doc->room) &middot; {{ $doc->room->name }} @endif
                            </div>
                        </div>
                        <div class="doc-list-date">{{ $doc->created_at->format('d M Y') }}</div>
                    </div>
                @empty
                    <div class="text-center py-4 text-secondary" style="font-size:0.82rem;">
                        <i class="bi bi-inbox d-block mb-1" style="font-size:1.5rem;opacity:0.4;"></i>
                        Belum ada dokumen masuk
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Right Column --}}
    <div class="col-lg-4 d-flex flex-column gap-3">
        {{-- Shortcut Panel --}}
        <div class="siadil-card" style="border-radius:14px;">
            <div class="section-head px-3 pt-3">
                <h3>
                    <span class="dot" style="background:#059669;"></span>
                    Pintasan
                </h3>
            </div>
            <div class="px-3 pb-3">
                <div class="shortcut-grid">
                    <a href="{{ route('admin.documents.create') }}" class="shortcut-item">
                        <div class="sc-icon"><i class="bi bi-cloud-arrow-up"></i></div>
                        Upload Surat
                    </a>
                    <a href="{{ route('admin.categories.create') }}" class="shortcut-item">
                        <div class="sc-icon"><i class="bi bi-folder-plus"></i></div>
                        Buat Folder
                    </a>
                    <a href="{{ route('admin.users.create') }}" class="shortcut-item">
                        <div class="sc-icon"><i class="bi bi-person-plus"></i></div>
                        Tambah Ruangan
                    </a>
                    <a href="{{ route('admin.documents.index') }}" class="shortcut-item">
                        <div class="sc-icon"><i class="bi bi-search"></i></div>
                        Cari Arsip
                    </a>
                </div>
            </div>
        </div>

        {{-- Download Activity --}}
        <div class="siadil-card flex-grow-1" style="border-radius:14px;">
            <div class="section-head px-3 pt-3">
                <h3>
                    <span class="dot" style="background:#9333ea;"></span>
                    Aktivitas Unduhan
                </h3>
            </div>
            <div>
                @forelse($recentDownloads->take(5) as $dl)
                    <div class="activity-item">
                        <div class="activity-dot {{ $loop->index < 2 ? 'recent' : '' }}"></div>
                        <div>
                            <div class="activity-text">
                                <strong>{{ $dl->user->name ?? 'Pengguna' }}</strong> mengunduh
                                <strong>{{ Str::limit($dl->document->judul ?? '-', 28) }}</strong>
                            </div>
                            <div class="activity-time">{{ $dl->downloaded_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-secondary" style="font-size:0.82rem;">
                        <i class="bi bi-download d-block mb-1" style="font-size:1.5rem;opacity:0.4;"></i>
                        Belum ada aktivitas
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Bottom info row --}}
<div class="row g-3">
    <div class="col-md-4">
        <div class="info-mini">
            <h5><i class="bi bi-calendar-check" style="color:#059669;"></i> Bulan Ini</h5>
            <div class="info-val">{{ $dokumenBulanIni }}</div>
            <div class="info-sub">dokumen diunggah</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-mini">
            <h5><i class="bi bi-clock" style="color:#4f46e5;"></i> Upload Terakhir</h5>
            <div class="info-val" style="font-size:1rem;">
                @if($uploadTerbaru)
                    {{ Str::limit($uploadTerbaru->judul, 30) }}
                @else
                    —
                @endif
            </div>
            <div class="info-sub">
                @if($uploadTerbaru)
                    {{ $uploadTerbaru->created_at->diffForHumans() }}
                @else
                    belum ada data
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-mini">
            <h5><i class="bi bi-hdd-stack" style="color:#ea580c;"></i> Kapasitas Arsip</h5>
            @php
                $totalSize = \App\Models\Document::sum('ukuran_file');
                $formattedSize = $totalSize > 1048576
                    ? number_format($totalSize / 1048576, 1) . ' MB'
                    : number_format($totalSize / 1024, 0) . ' KB';
            @endphp
            <div class="info-val">{{ $formattedSize }}</div>
            <div class="info-sub">total penyimpanan terpakai</div>
        </div>
    </div>
</div>

@endsection
