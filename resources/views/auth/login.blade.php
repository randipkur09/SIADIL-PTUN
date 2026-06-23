<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login SIADIL - Sistem Informasi Arsip Digital PTUN Bandar Lampung">
    <title>Login - SIADIL PTUN Bandar Lampung</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary: #0f766e; /* Teal-700 */
            --primary-light: #14b8a6; /* Teal-500 */
            --secondary: #0f172a; /* Slate-900 */
            --bg-color: #f8fafc;
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.5);
            --glass-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }

        body {
            font-family: 'Outfit', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: linear-gradient(-45deg, #0f766e, #064e3b, #047857, #115e59);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            position: relative;
            overflow-x: hidden;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Abstract shapes */
        .shape-container {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            animation: float 10s infinite ease-in-out;
        }

        .shape-1 {
            width: 400px;
            height: 400px;
            background: rgba(20, 184, 166, 0.4);
            top: -100px;
            left: -100px;
            animation-delay: 0s;
        }

        .shape-2 {
            width: 500px;
            height: 500px;
            background: rgba(52, 211, 153, 0.3);
            bottom: -150px;
            right: -100px;
            animation-delay: -5s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-30px) scale(1.05); }
        }

        .main-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
            z-index: 10;
        }

        .login-wrapper {
            width: 100%;
            max-width: 450px;
            perspective: 1000px;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 24px;
            border: 1px solid var(--glass-border);
            box-shadow: var(--glass-shadow);
            padding: 3rem 2.5rem;
            animation: cardEnter 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
            transform-style: preserve-3d;
        }

        @keyframes cardEnter {
            from { opacity: 0; transform: translateY(40px) rotateX(-10deg); }
            to { opacity: 1; transform: translateY(0) rotateX(0); }
        }

        .logo-container {
            text-align: center;
            margin-bottom: 2.5rem;
            position: relative;
        }

        .logo-container img {
            height: 85px;
            margin-bottom: 1.5rem;
            filter: drop-shadow(0 10px 15px rgba(0,0,0,0.1));
            transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .logo-container img:hover {
            transform: scale(1.1) rotate(2deg);
        }

        .app-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--secondary);
            margin: 0 0 0.5rem;
            letter-spacing: -0.5px;
        }

        .app-subtitle {
            font-size: 0.95rem;
            color: #64748b;
            font-weight: 500;
            line-height: 1.4;
        }

        .form-floating-custom {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-control-custom {
            width: 100%;
            padding: 1.25rem 1.25rem 1.25rem 3.5rem;
            border: 2px solid transparent;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.9);
            font-family: 'Outfit', sans-serif;
            font-size: 1rem;
            font-weight: 500;
            color: var(--secondary);
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .form-control-custom:focus {
            outline: none;
            border-color: var(--primary-light);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.15), 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .form-icon {
            position: absolute;
            left: 1.2rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.25rem;
            transition: color 0.3s ease;
            z-index: 5;
        }

        .form-control-custom:focus ~ .form-icon,
        .form-control-custom:not(:placeholder-shown) ~ .form-icon {
            color: var(--primary);
        }

        .form-control-custom::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .toggle-password {
            position: absolute;
            right: 1.2rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
            font-size: 1.2rem;
            transition: color 0.3s ease;
            z-index: 5;
            background: transparent;
            border: none;
            padding: 0;
        }

        .toggle-password:hover {
            color: var(--primary);
        }

        .btn-login {
            width: 100%;
            padding: 1.1rem;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.1rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 10px 20px -5px rgba(15, 118, 110, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-top: 2rem;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(255,255,255,0.2), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 15px 25px -5px rgba(15, 118, 110, 0.5);
            color: white;
        }

        .btn-login:hover::before {
            opacity: 1;
        }

        .btn-login:active {
            transform: translateY(0) scale(0.98);
        }

        .form-check-custom {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .form-check-input {
            width: 1.2em;
            height: 1.2em;
            border-radius: 6px;
            border: 2px solid #cbd5e1;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .form-check-label {
            font-size: 0.95rem;
            color: #64748b;
            font-weight: 500;
            cursor: pointer;
        }

        .alert-custom {
            border-radius: 12px;
            padding: 1rem 1.25rem;
            font-weight: 500;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            animation: alertEnter 0.4s ease-out;
        }

        @keyframes alertEnter {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-custom.alert-danger {
            background: #fef2f2;
            color: #991b1b;
        }

        .alert-custom.alert-success {
            background: #f0fdf4;
            color: #166534;
        }

        .footer-container {
            padding: 1.5rem;
            text-align: center;
            position: relative;
            z-index: 10;
        }

        .footer-text {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.85rem;
            font-weight: 400;
            letter-spacing: 0.5px;
            animation: fadeIn 1s ease-out 0.8s both;
            margin: 0;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .footer-text span {
            font-weight: 600;
            color: #fff;
        }

        /* Micro-interactions */
        .input-group-wrapper {
            position: relative;
        }

        .spinner-icon {
            display: none;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            100% { transform: rotate(360deg); }
        }

        .btn-loading .btn-text, .btn-loading .bi-box-arrow-in-right {
            display: none;
        }

        .btn-loading .spinner-icon {
            display: inline-block;
        }

        /* Invalid input style */
        .is-invalid {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1) !important;
        }
        .is-invalid ~ .form-icon {
            color: #ef4444 !important;
        }
        .invalid-feedback-custom {
            color: #ef4444;
            font-size: 0.85rem;
            margin-top: 0.4rem;
            margin-left: 0.5rem;
            font-weight: 500;
        }

    </style>
</head>
<body>

    <!-- Animated Background Shapes -->
    <div class="shape-container">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
    </div>

    <!-- Main Centered Content -->
    <main class="main-container">
        <div class="login-wrapper">
            <div class="glass-card">
                
                <!-- Logo & Header -->
                <div class="logo-container">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo PTUN">
                    <h1 class="app-title">SIADIL</h1>
                    <p class="app-subtitle">Sistem Informasi Arsip Digital<br>Pengadilan Tata Usaha Negara Bandar Lampung</p>
                </div>

                <!-- Alerts -->
                @if($errors->any())
                    <div class="alert alert-custom alert-danger mb-4">
                        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                        <div>{{ $errors->first() }}</div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-custom alert-success mb-4">
                        <i class="bi bi-check-circle-fill fs-5"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('login.post') }}" method="POST" id="loginForm">
                    @csrf
                    
                    <div class="input-group-wrapper">
                        <div class="form-floating-custom mb-3">
                            <input 
                                type="text" 
                                class="form-control-custom @error('username') is-invalid @enderror" 
                                id="username" 
                                name="username" 
                                value="{{ old('username') }}" 
                                placeholder="Username" 
                                autocomplete="username" 
                                required
                            >
                            <i class="bi bi-person form-icon"></i>
                            @error('username')
                                <div class="invalid-feedback-custom">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="input-group-wrapper">
                        <div class="form-floating-custom mb-2">
                            <input 
                                type="password" 
                                class="form-control-custom @error('password') is-invalid @enderror" 
                                id="password" 
                                name="password" 
                                placeholder="Password" 
                                autocomplete="current-password" 
                                required
                            >
                            <i class="bi bi-lock form-icon"></i>
                            <button type="button" class="toggle-password" id="togglePassword" aria-label="Toggle password visibility">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback-custom">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-check-custom">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">
                            Ingat saya
                        </label>
                    </div>

                    <button type="submit" class="btn-login" id="loginBtn">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span class="btn-text">Masuk ke Sistem</span>
                        <i class="bi bi-arrow-repeat spinner-icon"></i>
                    </button>
                </form>
            </div>
        </div>
    </main>
    
    <!-- Footer at the bottom -->
    <footer class="footer-container">
        <p class="footer-text">
            <i class="bi bi-shield-check me-1"></i> Sistem Khusus Pegawai Berwenang<br>
            &copy; {{ date('Y') }} <span>PTUN Bandar Lampung</span>
        </p>
    </footer>

<script>
    // Toggle Password Visibility
    const togglePasswordBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    togglePasswordBtn.addEventListener('click', function (e) {
        // Prevent form submission
        e.preventDefault();
        
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        if (type === 'text') {
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
            togglePasswordBtn.style.color = 'var(--primary)';
        } else {
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
            togglePasswordBtn.style.color = '#94a3b8';
        }
    });

    // Form Submit Loading State
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');

    loginForm.addEventListener('submit', function() {
        loginBtn.classList.add('btn-loading');
        loginBtn.disabled = true;
    });

    // Add staggered animation delay to form elements
    document.addEventListener('DOMContentLoaded', () => {
        const elements = document.querySelectorAll('.form-floating-custom, .form-check-custom, .btn-login');
        elements.forEach((el, index) => {
            el.style.opacity = '0';
            el.style.animation = `alertEnter 0.4s ease-out ${0.4 + (index * 0.1)}s forwards`;
        });
    });
</script>
</body>
</html>
