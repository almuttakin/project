<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="description" content="Portal Login Administrator TI AutoXML Gateway PTPN IV Regional III.">
    <title>Login Administrator TI · AutoXML Gateway PTPN IV</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ptpn4.png') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Fira+Code:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --forest-950: #022011;
            --forest-900: #064e2b;
            --forest-800: #0a6b3a;
            --forest-700: #0f5132;
            --forest-600: #198754;
            --forest-500: #27a76a;
            --forest-400: #47c882;
            --forest-100: #d4f4e0;
            --forest-50:  #ecfbf1;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(145deg, #022614 0%, #064e2b 45%, #0a6b3a 80%, #0f5132 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 30px 16px;
            color: #1e293b;
            position: relative;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Decorative background patterns */
        .login-bg-pattern {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: 
                radial-gradient(rgba(255, 255, 255, 0.08) 1.5px, transparent 1.5px),
                radial-gradient(rgba(71, 200, 130, 0.12) 1.5px, transparent 1.5px);
            background-size: 36px 36px;
            background-position: 0 0, 18px 18px;
            opacity: 0.55;
            pointer-events: none;
            z-index: 1;
        }

        .login-glow-blob {
            position: absolute;
            width: 480px;
            height: 480px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(39, 167, 106, 0.3) 0%, rgba(6, 78, 43, 0) 70%);
            top: 10%;
            left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
            filter: blur(50px);
            z-index: 1;
        }

        .login-container {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 10;
        }

        .back-home-link {
            color: rgba(255, 255, 255, 0.82);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 18px;
            transition: all 0.25s ease;
        }
        .back-home-link:hover {
            color: #ffffff;
            transform: translateX(-3px);
        }

        .login-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 38px 34px;
            box-shadow: 0 20px 55px rgba(0, 0, 0, 0.25), 0 4px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .login-logo {
            height: 58px;
            width: auto;
            object-fit: contain;
            display: block;
            margin: 0 auto 16px;
            filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.1));
        }

        .login-title {
            font-size: 1.35rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
            letter-spacing: -0.4px;
        }

        .login-subtitle {
            font-size: 0.82rem;
            color: var(--forest-700);
            font-weight: 600;
            margin-bottom: 24px;
        }

        .form-control {
            border-radius: 12px;
            border: 1.5px solid #cbd5e1;
            padding: 11px 16px;
            font-size: 0.92rem;
            transition: all 0.25s ease;
        }
        .form-control:focus {
            border-color: var(--forest-500);
            box-shadow: 0 0 0 3px rgba(39, 167, 106, 0.16);
            outline: none;
        }

        .input-group .btn-outline-toggle {
            border: 1.5px solid #cbd5e1;
            border-left: none;
            border-radius: 0 12px 12px 0;
            background: #f8fafc;
            color: #64748b;
            padding: 0 14px;
            transition: all 0.2s ease;
        }
        .input-group .btn-outline-toggle:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        .form-check-input:checked {
            background-color: var(--forest-600);
            border-color: var(--forest-600);
        }

        .btn-submit-login {
            background: linear-gradient(135deg, var(--forest-700) 0%, var(--forest-600) 100%);
            border: none;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.98rem;
            padding: 13px 24px;
            border-radius: 999px;
            box-shadow: 0 8px 20px rgba(15, 81, 50, 0.28);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
        }
        .btn-submit-login:hover {
            background: linear-gradient(135deg, var(--forest-900) 0%, var(--forest-700) 100%);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 12px 26px rgba(6, 78, 43, 0.35);
        }

        .login-badge-info {
            background: var(--forest-50);
            color: var(--forest-800);
            border: 1px solid var(--forest-200);
            border-radius: 999px;
            font-size: 0.74rem;
            font-weight: 600;
            padding: 6px 14px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .login-bottom-text {
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.82rem;
            text-align: center;
            margin-top: 22px;
            line-height: 1.5;
        }
        .contact-admin-link {
            color: #7ddba3;
            font-weight: 600;
            text-decoration: underline;
            text-underline-offset: 3px;
            transition: color 0.2s ease;
        }
        .contact-admin-link:hover {
            color: #ffffff;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-bg-pattern"></div>
    <div class="login-glow-blob"></div>

    <div class="login-container">
        <!-- Link Kembali ke Beranda -->
        <a href="{{ route('home') }}" class="back-home-link">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Halaman Beranda</span>
        </a>

        <!-- Kartu Login Putih -->
        <div class="login-card">
            <!-- Header Kartu: Logo + Judul + Subjudul -->
            <div class="text-center">
                <img src="{{ asset('images/logo-ptpn4.png') }}" alt="Logo PTPN IV" class="login-logo">
                <h1 class="login-title">Login Administrator TI</h1>
                <div class="login-subtitle">AutoXML Gateway · PTPN IV Regional III</div>
            </div>

            <!-- Pesan Notifikasi Sukses / Info (misal setelah logout) -->
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show d-flex align-items-center gap-2 py-2 px-3 small rounded-3 border-0 mb-3" role="alert" style="background-color: #e0f2fe; color: #0369a1;">
                    <i class="bi bi-info-circle-fill fs-6 flex-shrink-0"></i>
                    <div>{{ session('info') }}</div>
                    <button type="button" class="btn-close ms-auto p-2" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Pesan Error Login Gagal -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 py-2 px-3 small rounded-3 border-0 mb-3" role="alert" style="background-color: #fee2e2; color: #991b1b;">
                    <i class="bi bi-exclamation-octagon-fill fs-6 flex-shrink-0"></i>
                    <div>{{ $errors->first() }}</div>
                    <button type="button" class="btn-close ms-auto p-2" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Form Autentikasi -->
            <form action="{{ route('login.attempt') }}" method="POST">
                @csrf

                <!-- Field Username -->
                <div class="mb-3">
                    <label for="username" class="form-label fw-semibold small text-secondary">
                        <i class="bi bi-person-fill text-success me-1"></i>Username
                    </label>
                    <input 
                        type="text" 
                        name="username" 
                        id="username" 
                        class="form-control @error('username') is-invalid @enderror" 
                        placeholder="Masukkan username admin..." 
                        value="{{ old('username') }}" 
                        required 
                        autofocus
                    >
                </div>

                <!-- Field Password -->
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold small text-secondary">
                        <i class="bi bi-lock-fill text-success me-1"></i>Password
                    </label>
                    <div class="input-group">
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            class="form-control border-end-0" 
                            placeholder="Masukkan password..." 
                            required
                        >
                        <button class="btn btn-outline-toggle" type="button" id="togglePasswordBtn" onclick="togglePasswordVisibility()" title="Tampilkan / Sembunyikan Password">
                            <i class="bi bi-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Checkbox Ingat Saya & Lupa Password -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label small text-secondary" for="remember">
                            Ingat saya
                        </label>
                    </div>
                    <a href="javascript:void(0)" onclick="alert('Silakan hubungi Unit TI Kantor Pusat Regional III untuk bantuan reset password kredensial Anda.')" class="small text-decoration-none text-success fw-semibold">
                        Lupa password?
                    </a>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="btn-submit-login">
                    <span>Masuk ke Dashboard</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <!-- Badge Khusus Admin TI di bawah Form -->
            <div class="text-center mt-3 pt-2">
                <span class="login-badge-info">
                    <i class="bi bi-shield-check text-success"></i>
                    <span>Khusus untuk Admin/Petugas TI PTPN IV Regional III</span>
                </span>
            </div>
        </div>

        <!-- Teks Kecil Paling Bawah -->
        <p class="login-bottom-text">
            Operator Timbangan PKS? <a href="mailto:mhdnuralmuttakin@gmail.com" class="contact-admin-link">Hubungi Admin TI</a> untuk akses akun.
        </p>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script Toggle Password Visibility -->
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('togglePasswordIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>

