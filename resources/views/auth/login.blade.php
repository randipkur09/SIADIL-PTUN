<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login SIADIL - Sistem Informasi Arsip Digital PTUN Bandar Lampung">
    <title>Login - SIADIL PTUN Bandar Lampung</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/siadil.css') }}">
</head>
<body>

<div class="login-page">
    <div class="login-card">
        {{-- Logo & Header --}}
        <div class="login-logo">
            <div class="logo-emblem">
                <i class="bi bi-archive-fill"></i>
            </div>
            <div class="login-title">SIADIL</div>
            <div class="login-subtitle">Sistem Informasi Arsip Digital<br>Pengadilan Tata Usaha Negara Bandar Lampung</div>
        </div>

        <div class="login-divider"></div>

        {{-- Error Messages --}}
        @if($errors->any())
            <div class="alert alert-siadil alert-siadil-danger mb-3">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-siadil alert-siadil-success mb-3">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- Login Form --}}
        <form action="{{ route('login.post') }}" method="POST" id="loginForm">
            @csrf
            <div class="mb-3">
                <label for="username" class="form-label-custom">Username</label>
                <div class="input-group">
                    <span class="input-group-text border-end-0" style="background:#f8fafc;border:1.5px solid #e2e8f0;border-right:none;">
                        <i class="bi bi-person text-secondary"></i>
                    </span>
                    <input
                        type="text"
                        class="form-control form-control-custom border-start-0 @error('username') is-invalid @enderror"
                        id="username"
                        name="username"
                        value="{{ old('username') }}"
                        placeholder="Masukkan username"
                        autocomplete="username"
                        required
                        style="border-left:none;"
                    >
                </div>
                @error('username')
                    <div class="text-danger fs-12 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label-custom">Password</label>
                <div class="input-group">
                    <span class="input-group-text border-end-0" style="background:#f8fafc;border:1.5px solid #e2e8f0;border-right:none;">
                        <i class="bi bi-lock text-secondary"></i>
                    </span>
                    <input
                        type="password"
                        class="form-control form-control-custom border-start-0 @error('password') is-invalid @enderror"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required
                        style="border-left:none;"
                    >
                    <button type="button" class="input-group-text" id="togglePassword" style="background:#f8fafc;border:1.5px solid #e2e8f0;border-left:none;cursor:pointer;">
                        <i class="bi bi-eye text-secondary" id="toggleIcon"></i>
                    </button>
                </div>
                @error('password')
                    <div class="text-danger fs-12 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4 d-flex align-items-center">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label fs-13 text-secondary" for="remember">
                        Ingat saya
                    </label>
                </div>
            </div>

            <button type="submit" class="btn-login" id="loginBtn">
                <i class="bi bi-box-arrow-in-right me-2"></i>
                Masuk ke Sistem
            </button>
        </form>

        <div class="login-divider"></div>

        {{-- Footer Info --}}
        <div class="text-center">
            <p class="fs-12 text-secondary mb-0">
                <i class="bi bi-shield-lock-fill me-1"></i>
                Sistem ini hanya untuk pegawai yang berwenang.
            </p>
            <p class="fs-12 text-secondary mt-1 mb-0">
                &copy; {{ date('Y') }} PTUN Bandar Lampung
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Toggle Password
document.getElementById('togglePassword').addEventListener('click', function() {
    const pwd = document.getElementById('password');
    const icon = document.getElementById('toggleIcon');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.className = 'bi bi-eye-slash text-secondary';
    } else {
        pwd.type = 'password';
        icon.className = 'bi bi-eye text-secondary';
    }
});

// Loading state on submit
document.getElementById('loginForm').addEventListener('submit', function() {
    const btn = document.getElementById('loginBtn');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    btn.disabled = true;
});
</script>
</body>
</html>
