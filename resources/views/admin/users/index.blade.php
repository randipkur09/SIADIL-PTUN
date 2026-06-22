@extends('layouts.app')

@section('title', 'Data Pengguna')
@section('page-title', 'Data Pengguna')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Data Pengguna</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2 class="page-heading">Data Pengguna</h2>
        <p class="page-subheading">Kelola akun pengguna sistem</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn-siadil-primary">
        <i class="bi bi-person-plus"></i>
        Tambah Pengguna
    </a>
</div>

{{-- Filter --}}
<div class="siadil-card mb-3">
    <div class="card-body-custom py-3">
        <form action="{{ route('admin.users.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-12 col-md-5">
                <div class="search-wrapper">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama atau username..."
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <select name="role" class="form-select form-select-sm">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user"  {{ request('role') === 'user'  ? 'selected' : '' }}>User</option>
                </select>
            </div>
            <div class="col-6 col-md-4 d-flex gap-2">
                <button type="submit" class="btn-siadil-primary flex-grow-1">
                    <i class="bi bi-filter"></i> Filter
                </button>
                @if(request()->hasAny(['search','role']))
                    <a href="{{ route('admin.users.index') }}" class="btn-siadil-secondary">
                        <i class="bi bi-x"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="siadil-card">
    <div class="card-header-custom">
        <div class="card-title-custom">
            <i class="bi bi-people text-primary"></i>
            Daftar Pengguna
        </div>
        <span class="badge bg-primary rounded-pill">{{ $users->total() }} pengguna</span>
    </div>

    @if($users->count() > 0)
        <div class="table-responsive">
            <table class="table table-siadil mb-0">
                <thead>
                    <tr>
                        <th style="width:50px">#</th>
                        <th>Pengguna</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Hak Akses</th>
                        <th>Terdaftar</th>
                        <th class="text-center" style="width:90px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $i => $user)
                        <tr>
                            <td class="text-secondary fs-12">{{ $users->firstItem() + $i }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="sidebar-user-avatar flex-shrink-0" style="width:34px;height:34px;font-size:0.8rem;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="fw-600">{{ $user->name }}</span>
                                    @if($user->id === auth()->id())
                                        <span class="badge bg-secondary-subtle text-secondary fs-11">Anda</span>
                                    @endif
                                </div>
                            </td>
                            <td class="fs-13 text-secondary">{{ $user->username }}</td>
                            <td class="fs-13 text-secondary">{{ $user->email ?? '-' }}</td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge-admin">Admin</span>
                                @else
                                    <span class="badge-user">User</span>
                                @endif
                            </td>
                            <td class="fs-12 text-secondary">{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn-icon btn-icon-edit" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <button type="button" class="btn-icon btn-icon-delete" title="Hapus"
                                            onclick="confirmDelete('{{ route('admin.users.destroy', $user) }}', '{{ $user->name }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="d-flex justify-content-between align-items-center px-3 py-3 border-top">
                <div class="fs-12 text-secondary">
                    Menampilkan {{ $users->firstItem() }}-{{ $users->lastItem() }} dari {{ $users->total() }} pengguna
                </div>
                {{ $users->links('vendor.pagination.bootstrap-5') }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <i class="bi bi-people"></i>
            <h6>Tidak Ada Pengguna</h6>
            <p>Belum ada data pengguna yang sesuai filter.</p>
        </div>
    @endif
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <div class="modal-title d-flex align-items-center gap-2">
                    <div class="btn-icon btn-icon-delete" style="pointer-events:none"><i class="bi bi-trash"></i></div>
                    <span class="fw-700">Hapus Pengguna</span>
                </div>
            </div>
            <div class="modal-body">
                <p>Anda akan menghapus pengguna: <strong id="deleteUserName"></strong></p>
                <div class="alert alert-siadil alert-siadil-warning">
                    <i class="bi bi-info-circle"></i>
                    Tindakan ini tidak dapat dibatalkan.
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
@endsection

@push('scripts')
<script>
function confirmDelete(url, name) {
    document.getElementById('deleteUserName').textContent = name;
    document.getElementById('deleteForm').action = url;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush
