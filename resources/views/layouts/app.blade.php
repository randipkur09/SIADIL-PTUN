<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIADIL - Sistem Informasi Arsip Digital PTUN Bandar Lampung">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIADIL') - PTUN Bandar Lampung</title>

    {{-- Bootstrap 5 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/siadil.css') }}">
    @stack('styles')
</head>
<body>
<div class="app-wrapper">
    {{-- ─── Sidebar ─────────────────────────────────── --}}
    <aside class="sidebar" id="sidebar">
        {{-- Brand --}}
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon">
                <i class="bi bi-archive-fill"></i>
            </div>
            <div class="sidebar-brand-text">
                <div class="brand-title">SIADIL</div>
                <div class="brand-sub">PTUN Bandar Lampung</div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="sidebar-nav">
            @if(auth()->user()->role === 'admin')
                <div class="sidebar-section-label">Menu Utama</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                </ul>

                <div class="sidebar-section-label">Manajemen</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.documents.index') }}" class="nav-link {{ request()->routeIs('admin.documents*') ? 'active' : '' }}">
                            <i class="bi bi-file-earmark-text"></i>
                            <span>Data Dokumen</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                            <i class="bi bi-building"></i>
                            <span>Data Ruangan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                            <i class="bi bi-people"></i>
                            <span>Data Pengguna</span>
                        </a>
                    </li>
                </ul>
            @else
                <div class="sidebar-section-label">Menu</div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('user.documents.index') }}" class="nav-link {{ request()->routeIs('user.documents*') ? 'active' : '' }}">
                            <i class="bi bi-file-earmark-text"></i>
                            <span>Daftar Dokumen</span>
                        </a>
                    </li>
                </ul>
            @endif
        </nav>

        {{-- Footer / User Info --}}
        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="sidebar-user-info">
                    <div class="user-name">{{ Str::limit(auth()->user()->name, 20) }}</div>
                    <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout-sidebar">
                    <i class="bi bi-box-arrow-left"></i>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- Overlay for Mobile --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- ─── Main Content ─────────────────────────────── --}}
    <div class="main-content">
        {{-- Top Navbar --}}
        <header class="top-navbar">
            <button class="btn btn-link p-0 d-lg-none me-2" id="sidebarToggle" style="color: #64748b;">
                <i class="bi bi-list fs-5"></i>
            </button>

            <div>
                <h1 class="page-title mb-0">@yield('page-title', 'Dashboard')</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        @yield('breadcrumb')
                    </ol>
                </nav>
            </div>

            <div class="navbar-right">
                <div class="text-end d-none d-md-block">
                    <div class="navbar-clock" id="navbarClock"></div>
                    <div class="navbar-date" id="navbarDate"></div>
                </div>
                <div class="vr mx-2 d-none d-md-block"></div>
                <div class="d-flex align-items-center gap-2">
                    <div class="sidebar-user-avatar" style="width:32px;height:32px;font-size:0.75rem;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="content-wrapper">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-siadil alert-siadil-success alert-dismissible mb-3" role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-siadil alert-siadil-danger alert-dismissible mb-3" role="alert">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="py-3 px-4 border-top" style="background:#fff;font-size:0.75rem;color:#94a3b8;">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <span>&copy; {{ date('Y') }} SIADIL - PTUN Bandar Lampung. Hak cipta dilindungi.</span>
                <span>Sistem Informasi Arsip Digital</span>
            </div>
        </footer>
    </div>
</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Clock
function updateClock() {
    const now = new Date();
    const timeStr = now.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit', second:'2-digit' });
    const dateStr = now.toLocaleDateString('id-ID', { weekday:'long', year:'numeric', month:'long', day:'numeric' });
    const el = document.getElementById('navbarClock');
    const el2 = document.getElementById('navbarDate');
    if (el) el.textContent = timeStr;
    if (el2) el2.textContent = dateStr;
}
updateClock();
setInterval(updateClock, 1000);

// Sidebar Toggle
const sidebar        = document.getElementById('sidebar');
const overlay        = document.getElementById('sidebarOverlay');
const sidebarToggle  = document.getElementById('sidebarToggle');

if (sidebarToggle) {
    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
    });
}
if (overlay) {
    overlay.addEventListener('click', () => {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
    });
}

// Auto dismiss alerts
setTimeout(() => {
    document.querySelectorAll('.alert-dismissible').forEach(el => {
        const bsAlert = bootstrap.Alert.getOrCreateInstance(el);
        if (bsAlert) bsAlert.close();
    });
}, 5000);
</script>

@stack('scripts')
</body>
</html>
