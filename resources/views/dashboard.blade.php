<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="theme-color" content="#0f5132">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Auto Generate Config XML Timbangan - PTPN IV Regional III</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ptpn4.png') }}">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Fira+Code:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --forest-900: #064e2b;
            --forest-800: #0a6b3a;
            --forest-700: #0f5132;
            --forest-600: #198754;
            --forest-500: #27a76a;
            --forest-400: #47c882;
            --forest-300: #7ddba3;
            --forest-200: #b5eac9;
            --forest-100: #d4f4e0;
            --forest-50: #ecfbf1;
            --sidebar-width: 270px;
            --sidebar-collapsed: 78px;
        }

        * {
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background-color: #f0f4f2;
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            overflow-x: hidden;
            color: #2d3a33;
            -webkit-font-smoothing: antialiased;
        }

        /* ===== SIDEBAR OVERLAY (MOBILE) ===== */
        .sidebar-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100vw; height: 100vh;
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(3px);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0; left: 0;
            background: linear-gradient(175deg, var(--forest-900) 0%, var(--forest-700) 55%, var(--forest-600) 100%);
            color: #fff;
            padding-top: 0;
            z-index: 1050;
            transition: width 0.35s cubic-bezier(.4,0,.2,1), transform 0.35s cubic-bezier(.4,0,.2,1);
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 24px rgba(6, 78, 43, 0.18);
            -webkit-overflow-scrolling: touch;
        }
        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }

        .sidebar-brand {
            padding: 22px 16px 18px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            gap: 8px;
            background: transparent;
            position: relative;
            transition: all 0.3s cubic-bezier(.4,0,.2,1);
        }
        .sidebar-brand .brand-logo-badge {
            width: 68px;
            height: 68px;
            background: transparent;
            border-radius: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            box-shadow: none;
            border: none;
            flex-shrink: 0;
            transition: transform 0.3s cubic-bezier(.4,0,.2,1);
            cursor: pointer;
        }
        .sidebar-brand .brand-logo-badge:hover {
            transform: translateY(-2px) scale(1.06);
            box-shadow: none;
        }
        .sidebar-brand .brand-logo-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
            filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.3));
        }
        .sidebar-brand .brand-text {
            white-space: nowrap;
            overflow: hidden;
            transition: opacity 0.25s ease, max-height 0.3s ease;
            text-align: center;
        }
        .sidebar-brand .brand-title {
            font-size: 0.94rem;
            line-height: 1.25;
            font-weight: 700;
            letter-spacing: 0.3px;
            color: #ffffff;
        }
        .sidebar-brand .brand-sub {
            font-size: 0.68rem;
            opacity: 0.8;
            margin-top: 3px;
            letter-spacing: 0.2px;
            color: var(--forest-100);
        }

        /* Collapsed Sidebar */
        .sidebar.collapsed .sidebar-brand {
            padding: 16px 8px;
            min-height: 76px;
        }
        .sidebar.collapsed .brand-logo-badge {
            width: 48px;
            height: 48px;
            padding: 0;
            background: transparent;
            box-shadow: none;
            border: none;
        }
        .sidebar.collapsed .brand-text {
            opacity: 0;
            max-height: 0;
            margin: 0;
            pointer-events: none;
            display: none;
        }

        /* Toggle Button in Header */
        .sidebar-toggle-header {
            width: 42px; height: 42px;
            background: var(--forest-50);
            border: 1.5px solid var(--forest-200);
            border-radius: 12px;
            color: var(--forest-700);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.15rem;
            transition: all 0.3s ease;
            flex-shrink: 0;
            touch-action: manipulation;
        }
        .sidebar-toggle-header:hover, .sidebar-toggle-header:active {
            background: var(--forest-600);
            color: #fff;
            border-color: var(--forest-600);
            transform: scale(1.04);
            box-shadow: 0 4px 14px rgba(25, 135, 84, 0.3);
        }

        /* Nav Links */
        .sidebar-nav {
            padding: 16px 0;
            flex: 1;
        }
        .sidebar .nav-section-title {
            padding: 8px 20px 4px;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: rgba(255, 255, 255, 0.45);
            font-weight: 700;
        }
        .sidebar.collapsed .nav-section-title {
            display: none;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.75);
            padding: 12px 18px;
            margin: 4px 12px;
            border-radius: 10px;
            font-weight: 500;
            font-size: 0.85rem;
            cursor: pointer;
            text-align: left;
            border: none;
            background: transparent;
            width: calc(100% - 24px);
            display: flex;
            align-items: center;
            gap: 14px;
            transition: all 0.25s ease;
            white-space: nowrap;
            overflow: hidden;
            touch-action: manipulation;
            min-height: 46px;
        }
        .sidebar .nav-link i {
            font-size: 1.2rem;
            flex-shrink: 0;
            width: 22px;
            text-align: center;
            color: var(--forest-300);
            transition: color 0.25s ease;
        }
        .sidebar .nav-link .link-text {
            transition: opacity 0.25s ease;
        }
        .sidebar.collapsed .nav-link .link-text {
            opacity: 0;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link:active {
            color: #fff;
            background: rgba(255, 255, 255, 0.12);
        }
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 2px 10px rgba(0,0,0,0.12);
            font-weight: 600;
        }
        .sidebar .nav-link.active i {
            color: #fff;
        }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.08);
            font-size: 0.75rem;
            color: rgba(255,255,255,0.4);
            white-space: nowrap;
            overflow: hidden;
        }
        .sidebar.collapsed .sidebar-footer span {
            opacity: 0;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 28px 32px;
            transition: margin-left 0.35s cubic-bezier(.4,0,.2,1);
            min-height: 100vh;
        }
        .main-content.expanded {
            margin-left: var(--sidebar-collapsed);
        }

        /* ===== HEADER BAR ===== */
        .header-bar {
            background: #fff;
            border-radius: 16px;
            padding: 16px 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.03);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 1040;
        }
        .header-bar .dropdown-menu {
            z-index: 1060 !important;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.16) !important;
            animation: dropdownFadeIn 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        @keyframes dropdownFadeIn {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .header-bar h3 {
            color: var(--forest-900);
            font-weight: 800;
            font-size: 1.3rem;
            margin-bottom: 2px;
            letter-spacing: -0.3px;
        }
        .header-logo-badge {
            width: 42px;
            height: 42px;
            background: transparent;
            border: none;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: none;
            flex-shrink: 0;
            transition: transform 0.2s ease;
        }
        .header-logo-badge:hover {
            transform: scale(1.08);
            box-shadow: none;
        }
        .header-logo-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        /* ===== HERO BANNER ===== */
        .hero-banner {
            background: linear-gradient(135deg, var(--forest-900) 0%, var(--forest-700) 50%, var(--forest-600) 100%);
            border-radius: 20px;
            color: white;
            padding: 28px 32px;
            margin-bottom: 26px;
            position: relative;
            z-index: 1;
            overflow: hidden;
            box-shadow: 0 6px 24px rgba(6, 78, 43, 0.16);
        }
        .hero-banner::before {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 220px; height: 220px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }
        .hero-banner::after {
            content: '';
            position: absolute;
            bottom: -40px; left: 25%;
            width: 160px; height: 160px;
            background: rgba(255,255,255,0.03);
            border-radius: 50%;
        }
        .hero-banner h2 {
            font-weight: 800;
            font-size: 1.6rem;
            margin-bottom: 6px;
            letter-spacing: -0.4px;
        }

        /* ===== HERO STAT CARDS ===== */
        .hero-card {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(14px);
            border-radius: 14px;
            padding: 18px 16px;
            color: white;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            height: 100%;
        }
        .hero-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            background: rgba(255, 255, 255, 0.18);
        }
        .hero-card small.text-white-50 {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
        }
        .hero-card h3 {
            font-weight: 800;
            font-size: 1.45rem;
        }

        /* ===== CARDS ===== */
        .custom-card {
            background: #ffffff;
            border: none;
            border-radius: 18px;
            box-shadow: 0 2px 14px rgba(0, 0, 0, 0.04);
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }
        .custom-card:hover {
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.07);
        }

        /* ===== BADGES ===== */
        .badge-pending {
            background: linear-gradient(135deg, #ffb703, #fb8500);
            color: #fff;
            font-weight: 700;
            font-size: 0.74rem;
            padding: 6px 12px;
            border-radius: 6px;
            letter-spacing: 0.3px;
        }
        .badge-sent {
            background: linear-gradient(135deg, var(--forest-500), var(--forest-700));
            color: white;
            font-weight: 700;
            font-size: 0.74rem;
            padding: 6px 12px;
            border-radius: 6px;
            letter-spacing: 0.3px;
            box-shadow: 0 2px 6px rgba(39, 167, 106, 0.25);
        }
        .badge-trouble {
            background: linear-gradient(135deg, #ff6b6b, #ee5a5a);
            color: white;
            font-weight: 600;
            font-size: 0.72rem;
            padding: 5px 10px;
            border-radius: 6px;
        }
        .badge-ok {
            background: linear-gradient(135deg, var(--forest-500), var(--forest-600));
            color: white;
            font-weight: 600;
            font-size: 0.72rem;
            padding: 5px 10px;
            border-radius: 6px;
        }

        /* ===== STREAM CODE WIDGET (DARK TERMINAL THEME) ===== */
        .xml-stream-widget {
            background: #0d1117;
            border: 1px solid #21262d;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }
        .xml-stream-header {
            background: #161b22;
            padding: 12px 18px;
            border-bottom: 1px solid #21262d;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .xml-stream-dots {
            display: flex;
            gap: 6px;
        }
        .xml-stream-dots span {
            width: 10px; height: 10px;
            border-radius: 50%;
            display: inline-block;
        }
        .dot-red { background: #ff5f56; }
        .dot-yellow { background: #ffbd2e; }
        .dot-green { background: #27c93f; }

        .xml-stream-body {
            padding: 16px;
            font-family: 'Fira Code', 'Courier New', monospace;
            font-size: 0.82rem;
            color: #c9d1d9;
            max-height: 280px;
            overflow-y: auto;
            line-height: 1.5;
            background: #0d1117;
        }
        .xml-tag { color: #7ee787; font-weight: 600; }
        .xml-attr-name { color: #79c0ff; }
        .xml-attr-val { color: #a5d6ff; }
        .xml-content { color: #ffa657; font-weight: 500; }
        .xml-comment { color: #8b949e; font-style: italic; }

        /* ===== HISTORICAL TABLE STYLING ===== */
        .table-responsive {
            -webkit-overflow-scrolling: touch;
        }
        .table thead th {
            background: var(--forest-50);
            color: var(--forest-900);
            font-weight: 700;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--forest-200);
            padding: 14px;
            white-space: nowrap;
        }
        .table tbody tr {
            transition: background 0.2s ease;
        }
        .table tbody tr:hover {
            background: var(--forest-50);
        }
        .table tbody td {
            padding: 14px;
            font-size: 0.88rem;
            vertical-align: middle;
            white-space: nowrap;
        }

        /* ===== FORM STYLING ===== */
        .form-control, .form-select {
            border-radius: 10px;
            border: 1.5px solid #d6e4dc;
            font-size: 0.9rem;
            padding: 10px 14px;
            transition: all 0.2s ease;
            min-height: 44px;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--forest-500);
            box-shadow: 0 0 0 3px rgba(39, 167, 106, 0.15);
        }
        .form-label {
            color: var(--forest-900);
            font-weight: 600;
            font-size: 0.82rem;
        }

        /* ===== BUTTONS ===== */
        .btn-success {
            background: linear-gradient(135deg, var(--forest-600), var(--forest-500));
            border: none;
            border-radius: 10px;
            padding: 12px 20px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(25, 135, 84, 0.25);
            min-height: 44px;
            touch-action: manipulation;
        }
        .btn-success:hover, .btn-success:active {
            background: linear-gradient(135deg, var(--forest-700), var(--forest-600));
            transform: translateY(-1px);
            box-shadow: 0 5px 16px rgba(25, 135, 84, 0.35);
        }

        .btn-push-sap {
            background: linear-gradient(135deg, #0284c7, #0369a1);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 6px 12px;
            font-weight: 600;
            font-size: 0.78rem;
            transition: all 0.25s ease;
            box-shadow: 0 2px 8px rgba(3, 105, 161, 0.25);
        }
        .btn-push-sap:hover {
            background: linear-gradient(135deg, #0369a1, #075985);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(3, 105, 161, 0.35);
        }

        /* ===== USER AVATAR ===== */
        .user-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--forest-50);
            padding: 6px 14px 6px 6px;
            border-radius: 50px;
            border: 1px solid var(--forest-100);
        }
        .user-badge img {
            border: 2px solid var(--forest-200);
        }
        .user-badge .user-name {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--forest-900);
        }
        .user-badge .user-role {
            font-size: 0.7rem;
            color: var(--forest-600);
            line-height: 1;
        }

        /* Pulse Animation */
        .pulse-dot {
            width: 8px; height: 8px;
            background-color: #22c55e;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
            animation: pulse 1.6s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(34, 197, 94, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
        }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f0f4f2; }
        ::-webkit-scrollbar-thumb { background: var(--forest-300); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--forest-500); }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in {
            animation: fadeInUp 0.45s ease forwards;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                width: 270px !important;
                box-shadow: 8px 0 32px rgba(0, 0, 0, 0.25);
            }
            .sidebar.show-mobile {
                transform: translateX(0);
            }
            .sidebar.collapsed {
                width: 270px !important;
            }
            .sidebar.collapsed .brand-text {
                opacity: 1 !important;
                max-height: none !important;
                display: block !important;
                margin: 0 !important;
                width: auto !important;
            }
            .sidebar.collapsed .brand-logo-badge {
                width: 68px !important;
                height: 68px !important;
                padding: 0 !important;
                background: transparent !important;
                box-shadow: none !important;
                border: none !important;
            }
            .sidebar.collapsed .nav-link .link-text,
            .sidebar.collapsed .sidebar-footer span {
                opacity: 1 !important;
                width: auto !important;
            }
            .main-content, .main-content.expanded {
                margin-left: 0 !important;
                padding: 16px 14px;
            }
            .header-bar {
                padding: 14px 16px;
                border-radius: 12px;
                margin-bottom: 16px;
            }
            .header-bar h3 {
                font-size: 1.1rem;
            }
            .hero-banner {
                padding: 20px 18px;
                border-radius: 16px;
            }
            .hero-banner h2 {
                font-size: 1.25rem;
            }
            .hero-card h3 {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>

    <!-- ===== SIDEBAR OVERLAY (MOBILE) ===== -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- ===== SIDEBAR NAVIGATION ===== -->
    <div class="sidebar" id="sidebar">
        <!-- Brand -->
        <div class="sidebar-brand">
            <div class="brand-logo-badge" title="PTPN 4 Regional 3">
                <img src="{{ asset('images/logo-ptpn4.png') }}" alt="Logo PTPN 4" class="brand-logo-img">
            </div>
            <div class="brand-text">
                <div class="brand-title">PTPN 4 Regional 3</div>
                <div class="brand-sub">XML Engine & SAP Integration</div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <div class="sidebar-nav">
            <div class="nav-section-title">Menu Utama Proyek</div>
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist">
                <button class="nav-link active" id="dashboard-tab" data-bs-toggle="pill" data-bs-target="#tab-dashboard" type="button" role="tab">
                    <i class="bi bi-speedometer2"></i>
                    <span class="link-text">Dashboard & Network</span>
                </button>
                <button class="nav-link" id="timbangan-tab" data-bs-toggle="pill" data-bs-target="#tab-timbangan" type="button" role="tab">
                    <i class="bi bi-cpu-fill"></i>
                    <span class="link-text">Transaksi & XML Engine</span>
                </button>
            </div>

            <!-- Navigasi Publik & Logout -->
            <div class="nav-section-title mt-3">Akses & Sesi</div>
            <div class="nav flex-column">
                <a href="{{ route('home') }}" class="nav-link text-decoration-none">
                    <i class="bi bi-house-door"></i>
                    <span class="link-text">Halaman Depan (Home)</span>
                </a>
                <form action="{{ route('logout') }}" method="POST" class="m-0 p-0" id="sidebarLogoutForm">
                    @csrf
                    <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start" style="color: #fca5a5 !important;">
                        <i class="bi bi-box-arrow-right" style="color: #f87171 !important;"></i>
                        <span class="link-text">Keluar (Logout)</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="sidebar-footer">
            <i class="bi bi-shield-check me-1 text-success"></i>
            <span>v2.4 &middot; PTPN IV Reg III SAP Gateway</span>
        </div>
    </div>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="main-content" id="mainContent">
        
        <!-- Header Bar -->
        <div class="header-bar animate-in">
            <div class="d-flex align-items-center gap-3">
                <button class="sidebar-toggle-header" id="sidebarToggle" title="Perkecil / Perbesar Navigasi">
                    <i class="bi bi-list"></i>
                </button>
                <div class="header-logo-badge d-none d-sm-flex align-items-center justify-content-center" title="PTPN 4 Regional 3">
                    <img src="{{ asset('images/logo-ptpn4.png') }}" alt="Logo PTPN 4" class="header-logo-img">
                </div>
                <div>
                    <h3>PKS Weighbridge SAP Gateway</h3>
                    <small class="text-muted" style="font-size: 0.8rem;">Auto Generate Config XML Pada Timbangan PTPN 4 Regional 3 Untuk Melempar Data Ke Sistem SAP Kantor Pusat</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 gap-sm-3">
                <!-- Notification Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-light rounded-circle shadow-sm position-relative d-flex align-items-center justify-content-center" id="notifDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false" style="width: 42px; height: 42px; color: var(--forest-700); border: 1.5px solid var(--forest-200);" title="Pemberitahuan Sistem">
                        <i class="bi bi-bell-fill fs-5"></i>
                        @if(($xmlStats['total_pending'] ?? 0) > 0)
                            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" style="width: 12px; height: 12px; margin-left: -12px; margin-top: 5px;"></span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end border-0 rounded-4 p-0 mt-2" aria-labelledby="notifDropdownBtn" style="width: 360px; max-width: 92vw; border: 1px solid rgba(0,0,0,0.08) !important;">
                        <div class="p-3 border-bottom d-flex align-items-center justify-content-between bg-light rounded-top-4">
                            <div class="d-flex align-items-center gap-2">
                                <div style="width: 30px; height: 30px; background: var(--forest-100); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--forest-800);">
                                    <i class="bi bi-bell-fill" style="font-size: 0.88rem;"></i>
                                </div>
                                <span class="fw-bold text-dark" style="font-size: 0.92rem;">Pemberitahuan Sistem</span>
                            </div>
                            <span class="badge bg-success text-white rounded-pill font-monospace px-2 py-1" style="font-size: 0.68rem;">
                                {{ ($xmlStats['total_pending'] ?? 0) > 0 ? ($xmlStats['total_pending'] + 1) : 2 }} Info
                            </span>
                        </div>
                        <div class="list-group list-group-flush" style="max-height: 330px; overflow-y: auto;">
                            <!-- Notif 1: Antrean XML PENDING SAP -->
                            @if(($xmlStats['total_pending'] ?? 0) > 0)
                                <a href="javascript:void(0)" onclick="switchToTab('timbangan-tab', 'subtab-sent-tab')" class="list-group-item list-group-item-action p-3 border-bottom d-flex gap-3 align-items-start">
                                    <div style="width: 38px; height: 38px; border-radius: 12px; background: #fef3c7; color: #b45309; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.15rem;">
                                        <i class="bi bi-file-earmark-arrow-up-fill"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <span class="fw-bold text-dark" style="font-size: 0.86rem;">Antrean XML Belum Terkirim</span>
                                            <span class="badge badge-pending" style="font-size: 0.65rem;">PENDING</span>
                                        </div>
                                        <p class="text-muted mb-1" style="font-size: 0.76rem; line-height: 1.35;">Terdapat <strong>{{ $xmlStats['total_pending'] }} berkas XML</strong> timbangan yang siap dilempar ke sistem SAP pusat.</p>
                                        <span class="text-success fw-bold d-inline-flex align-items-center gap-1" style="font-size: 0.72rem;">
                                            <span>Buka & Push ke SAP</span> <i class="bi bi-arrow-right"></i>
                                        </span>
                                    </div>
                                </a>
                            @endif

                            <!-- Notif 2: Status Sesi Aktif & Riwayat Login -->
                            <a href="javascript:void(0)" onclick="switchToTab('dashboard-tab'); document.getElementById('riwayat-login-section')?.scrollIntoView({behavior:'smooth'});" class="list-group-item list-group-item-action p-3 border-bottom d-flex gap-3 align-items-start">
                                <div style="width: 38px; height: 38px; border-radius: 12px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.15rem;">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <span class="fw-bold text-dark" style="font-size: 0.86rem;">Sesi Administrator Aktif</span>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill" style="font-size: 0.65rem;"><span class="pulse-dot me-1"></span>Online</span>
                                    </div>
                                    <p class="text-muted mb-1" style="font-size: 0.76rem; line-height: 1.35;">Login aktif sebagai <strong>{{ Auth::user()->name ?? 'Administrator TI' }}</strong>. Waktu login Anda tercatat di database.</p>
                                    <span class="text-primary fw-bold d-inline-flex align-items-center gap-1" style="font-size: 0.72rem;">
                                        <span>Lihat Riwayat Sesi</span> <i class="bi bi-arrow-right"></i>
                                    </span>
                                </div>
                            </a>

                            <!-- Notif 3: Status Engine AutoXML -->
                            <a href="javascript:void(0)" onclick="switchToTab('timbangan-tab')" class="list-group-item list-group-item-action p-3 d-flex gap-3 align-items-start">
                                <div style="width: 38px; height: 38px; border-radius: 12px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.15rem;">
                                    <i class="bi bi-cpu-fill"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <span class="fw-bold text-dark" style="font-size: 0.86rem;">AutoXML Engine Standby</span>
                                        <span class="badge bg-success-subtle text-success rounded-pill" style="font-size: 0.65rem;">READY</span>
                                    </div>
                                    <p class="text-muted mb-1" style="font-size: 0.76rem; line-height: 1.35;">Sistem siap memproses transaksi bruto dan tarra menjadi berkas konfigurasi XML SAP.</p>
                                    <span class="text-success fw-bold d-inline-flex align-items-center gap-1" style="font-size: 0.72rem;">
                                        <span>Input Data Timbangan</span> <i class="bi bi-arrow-right"></i>
                                    </span>
                                </div>
                            </a>
                        </div>
                        <div class="p-2 border-top text-center bg-light rounded-bottom-4">
                            <button type="button" class="btn btn-sm btn-link text-decoration-none text-muted fw-semibold p-0" style="font-size: 0.76rem;" onclick="bootstrap.Dropdown.getInstance(document.getElementById('notifDropdownBtn'))?.hide();">
                                Tutup Notifikasi
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Admin Profile Dropdown -->
                <div class="dropdown">
                    <div class="user-badge d-flex align-items-center gap-2 px-2 py-1 rounded-pill" role="button" id="userDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false" style="background: var(--forest-50); border: 1.5px solid var(--forest-200); cursor: pointer; transition: all 0.2s ease;">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin TI') }}&background=0f5132&color=fff&size=34&font-size=0.4&bold=true" class="rounded-circle shadow-sm" width="32" height="32" alt="Avatar">
                        <div class="d-none d-sm-block text-start">
                            <div class="user-name" style="font-size: 0.82rem; font-weight: 700; color: #0f172a; line-height: 1.15;">{{ Auth::user()->name ?? 'Administrator TI' }}</div>
                            <div class="user-role" style="font-size: 0.68rem; color: var(--forest-700); font-weight: 600;">{{ Auth::user()->role ?? 'Admin' }}</div>
                        </div>
                        <i class="bi bi-chevron-down text-muted ms-1" style="font-size: 0.75rem;"></i>
                    </div>

                    <div class="dropdown-menu dropdown-menu-end border-0 rounded-4 p-0 mt-2" aria-labelledby="userDropdownBtn" style="width: 300px; border: 1px solid rgba(0,0,0,0.08) !important;">
                        <!-- Header Kartu User -->
                        <div class="p-3 bg-light rounded-top-4 border-bottom">
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin TI') }}&background=0f5132&color=fff&size=44&font-size=0.4&bold=true" class="rounded-circle border border-2 border-white shadow-sm" width="44" height="44" alt="Avatar">
                                <div style="min-width: 0;">
                                    <h6 class="fw-bold mb-0 text-dark text-truncate" style="font-size: 0.90rem;">{{ Auth::user()->name ?? 'Administrator TI Regional III' }}</h6>
                                    <small class="text-muted text-truncate d-block" style="font-size: 0.74rem;">{{ Auth::user()->email ?? 'admin.ti.regional3@ptpn4.co.id' }}</small>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill mt-1" style="font-size: 0.66rem;">
                                        <i class="bi bi-shield-check me-1"></i>Role: {{ Auth::user()->role ?? 'Admin' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Menu Link -->
                        <div class="py-2">
                            <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 text-dark small" href="javascript:void(0)" onclick="switchToTab('dashboard-tab'); document.getElementById('riwayat-login-section')?.scrollIntoView({behavior:'smooth'});">
                                <i class="bi bi-clock-history text-primary fs-6"></i>
                                <span>Riwayat Sesi Saya</span>
                            </a>
                            <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 text-dark small" href="{{ route('home') }}">
                                <i class="bi bi-house-door text-success fs-6"></i>
                                <span>Halaman Depan (Home)</span>
                            </a>
                            <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 text-dark small" href="javascript:void(0)" onclick="switchToTab('timbangan-tab', 'subtab-sent-tab')">
                                <i class="bi bi-journal-check text-warning fs-6"></i>
                                <span>Ledger & Log Integrasi SAP</span>
                            </a>
                        </div>

                        <!-- Logout Divider & Button -->
                        <div class="border-top p-2 bg-light rounded-bottom-4">
                            <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 rounded-3 text-danger fw-semibold small" style="color: #dc2626 !important;">
                                    <i class="bi bi-box-arrow-right fs-6"></i>
                                    <span>Keluar (Logout)</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="background: #e8f5e9; border-left: 4px solid var(--forest-600) !important;">
                <i class="bi bi-check-circle-fill text-success me-2 fs-5"></i> <strong>Berhasil!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Tab Content -->
        <div class="tab-content" id="v-pills-tabContent">
            
            <!-- ==================== TAB 1: DASHBOARD & NETWORK MONITORING ==================== -->
            <div class="tab-pane fade show active" id="tab-dashboard" role="tabpanel">
                
                <!-- Hero Banner -->
                <div class="hero-banner shadow-sm animate-in">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="badge rounded-pill" style="background: rgba(255,255,255,0.18); font-size: 0.74rem; font-weight: 500; padding: 6px 14px;">
                            <i class="bi bi-calendar3 me-1"></i> {{ date('l, d F Y') }}
                        </span>
                        <span class="badge rounded-pill" style="background: rgba(34, 197, 94, 0.25); color: #86efac; border: 1px solid rgba(34,197,94,0.4); font-size: 0.75rem; padding: 5px 12px;">
                            <span class="pulse-dot me-1"></span> SAP Gateway Active
                        </span>
                    </div>
                    <h2>Dashboard XML Generator & SAP Integration Engine 👋</h2>
                    <p class="mb-0 text-white-50" style="font-size: 0.92rem;">Monitoring real-time pembuatan skema XML timbangan PKS & status pelemparan data ke SAP Kantor Pusat.</p>
                    
                    <!-- TOP METRIC CARDS (Fokus Proses XML & Integrasi SAP) -->
                    <div class="row g-2 g-md-3 mt-3">
                        <!-- Card 1: Total XML Generated -->
                        <div class="col-6 col-md-3">
                            <div class="hero-card animate-in">
                                <small class="text-white-50">Total XML Generated (Auto)</small>
                                <h3 class="fw-bold my-1 text-white">{{ $xmlStats['total_xml'] }} <small style="font-size: 0.6em; font-weight: 500;">File XML</small></h3>
                                <small style="color: var(--forest-300);"><i class="bi bi-filetype-xml me-1"></i>Otomatis per-Input Data</small>
                            </div>
                        </div>
                        <!-- Card 2: Status Lempar Data SAP -->
                        <div class="col-6 col-md-3">
                            <div class="hero-card animate-in">
                                <small class="text-white-50">Status Lempar Data SAP</small>
                                <h3 class="fw-bold my-1 text-white">
                                    {{ $xmlStats['total_sent'] }} <small style="font-size: 0.55em; color: #86efac;">Sent</small>
                                    <span style="font-size: 0.7em; opacity: 0.6;">/</span>
                                    <span style="color: #ffb703;">{{ $xmlStats['total_pending'] }}</span> <small style="font-size: 0.55em; color: #ffb703;">Pend</small>
                                </h3>
                                <small style="color: #ffb703;"><i class="bi bi-cloud-arrow-up-fill me-1"></i>{{ $xmlStats['total_pending'] }} XML Antrean Pending</small>
                            </div>
                        </div>
                        <!-- Card 3: Konektivitas VPN/Debian VM -->
                        <div class="col-6 col-md-3">
                            <div class="hero-card animate-in">
                                <small class="text-white-50">Konektivitas VPN / Gateway</small>
                                <h3 class="fw-bold my-1 text-white">
                                    @if($xmlStats['vpn_trouble'] == 0)
                                        <span class="text-success"><i class="bi bi-wifi me-1"></i>Online</span>
                                    @else
                                        <span style="color: #ffd166;">{{ $xmlStats['vpn_online'] }} Online</span>
                                    @endif
                                </h3>
                                <small style="color: #a7f3d0;"><i class="bi bi-hdd-network-fill me-1"></i>Debian VM 10.14.2.10</small>
                            </div>
                        </div>
                        <!-- Card 4: Status SAP ERP Server -->
                        <div class="col-6 col-md-3">
                            <div class="hero-card animate-in">
                                <small class="text-white-50">Status SAP ERP Server</small>
                                <h3 class="fw-bold my-1 text-white" style="color: #86efac;">READY <small style="font-size: 0.55em;">200 OK</small></h3>
                                <small style="color: var(--forest-300);"><i class="bi bi-database-check me-1"></i>RFC Integration Active</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- GRAFIK RELEVAN TA: Jumlah XML Digenerate per Unit PKS -->
                <div class="card custom-card p-3 p-md-4 animate-in shadow-sm mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold mb-0" style="color: var(--forest-900);">
                                <i class="bi bi-bar-chart-line-fill me-2 text-success"></i>Jumlah XML Digenerate per Unit PKS
                            </h5>
                            <small class="text-muted">Distribusi total berkas XML timbangan yang ter-generate otomatis per unit lokasi PKS</small>
                        </div>
                        <span class="badge rounded-pill" style="background: var(--forest-50); color: var(--forest-700); font-weight: 600; font-size: 0.75rem; padding: 6px 14px;">
                            <i class="bi bi-cpu-fill me-1"></i> Auto-Generated Data
                        </span>
                    </div>
                    <div style="position: relative; width: 100%; height: 260px;">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <!-- TABEL HISTORICAL DASHBOARD: Live Stream Historical Timbangan & SAP Push -->
                <div class="card custom-card p-3 p-md-4 animate-in shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width: 38px; height: 38px; background: var(--forest-50); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--forest-600);">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0" style="color: var(--forest-900); font-size: 1.05rem;">Tabel Historical Stream Timbangan & Status SAP</h5>
                                <small class="text-muted" style="font-size: 0.76rem;">Ringkasan historical transaksi penimbangan dan status pelemparan XML ke SAP Kantor Pusat</small>
                            </div>
                        </div>
                        <a href="#tab-timbangan" onclick="switchToTab('timbangan-tab')" class="btn btn-sm btn-outline-success rounded-pill font-monospace" style="font-size: 0.75rem;">
                            <i class="bi bi-arrow-right-circle me-1"></i>Kelola Ledger Lengkap &rarr;
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>No. Nota & Plat</th>
                                    <th>Unit PKS / Gateway</th>
                                    <th>Waktu In / Out</th>
                                    <th>Netto (Kg)</th>
                                    <th>Status VPN</th>
                                    <th>Status SAP</th>
                                    <th class="text-center">Aksi Cepat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $hLog)
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-dark d-block">{{ $hLog['no_plat'] }}</span>
                                            <small class="text-muted font-monospace" style="font-size: 0.72rem;">{{ $hLog['nota_no'] }}</small>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-success d-block">{{ $hLog['pks_loc'] }}</span>
                                            <small class="text-muted font-monospace" style="font-size: 0.72rem;">IP: {{ $hLog['vpn_ip'] }}</small>
                                        </td>
                                        <td>
                                            <small class="d-block"><i class="bi bi-arrow-down-right-circle text-success me-1"></i>{{ $hLog['jam_masuk'] }}</small>
                                            <small class="text-muted"><i class="bi bi-arrow-up-left-circle me-1"></i>{{ $hLog['jam_keluar'] }}</small>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark">{{ number_format($hLog['netto']) }} Kg</span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $hLog['status_vpn'] == 'Connected' ? 'badge-ok' : 'badge-trouble' }}" style="font-size: 0.68rem;">
                                                {{ $hLog['status_vpn'] }}
                                            </span>
                                        </td>
                                        <td>
                                            @if(($hLog['status_sap'] ?? 'PENDING') === 'SENT')
                                                <span class="badge badge-sent"><i class="bi bi-check-circle-fill me-1"></i>SENT / VERIFIED</span>
                                            @else
                                                <span class="badge badge-pending"><i class="bi bi-clock-history me-1"></i>PENDING</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-success px-2 py-1 me-1" onclick="openXmlPreview({{ $hLog['id'] }})" style="font-size: 0.74rem;">
                                                <i class="bi bi-filetype-xml me-1"></i>Preview XML
                                            </button>
                                            @if(($hLog['status_sap'] ?? 'PENDING') === 'PENDING')
                                                <form action="{{ route('bridge.push', $hLog['id']) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-push-sap px-2 py-1" style="font-size: 0.74rem;">
                                                        <i class="bi bi-send-fill me-1"></i>Push
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- LIVE PREVIEW CODE STREAM XML (Fitur Demo Sidang - Collapsible) -->
                <div class="accordion mt-4 animate-in" id="demoStreamAccordion">
                    <div class="accordion-item border-0 custom-card shadow-sm" style="border-radius: 16px; overflow: hidden;">
                        <h2 class="accordion-header" id="headingDemoStream">
                            <button class="accordion-button collapsed bg-white fw-bold text-dark p-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDemoStream" aria-expanded="false" aria-controls="collapseDemoStream" style="border-radius: 16px;">
                                <div class="d-flex align-items-center justify-content-between w-100 me-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width: 32px; height: 32px; background: var(--forest-50); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--forest-600); font-size: 1rem;">
                                            <i class="bi bi-terminal-fill"></i>
                                        </div>
                                        <div>
                                            <span style="color: var(--forest-900); font-size: 0.95rem;">Live Preview Code Stream XML (Fitur Demo Sidang)</span>
                                            <small class="text-muted d-block" style="font-size: 0.72rem; font-weight: normal;">Visualisasi kode skema payload XML (Opsional untuk keperluan Demo Sidang TA)</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-dark text-success border border-success border-opacity-25 px-2 py-1 font-monospace" style="font-size: 0.68rem;">
                                        <span class="pulse-dot me-1"></span> Live Preview
                                    </span>
                                </div>
                            </button>
                        </h2>
                        <div id="collapseDemoStream" class="accordion-collapse collapse" aria-labelledby="headingDemoStream" data-bs-parent="#demoStreamAccordion">
                            <div class="accordion-body p-3 p-md-4 pt-1">
                                <div class="xml-stream-widget">
                                    <div class="xml-stream-header">
                                        <div class="xml-stream-dots">
                                            <span class="dot-red"></span>
                                            <span class="dot-yellow"></span>
                                            <span class="dot-green"></span>
                                        </div>
                                        <small class="text-muted font-monospace" style="font-size: 0.7rem;">
                                            <i class="bi bi-code-square me-1"></i>SAP_NotaTimbangan_Ingest.xml
                                        </small>
                                        <div>
                                            <button class="btn btn-sm btn-outline-secondary py-0 px-2 text-white-50" style="font-size: 0.68rem;" onclick="copyStreamXml()">
                                                <i class="bi bi-clipboard me-1"></i>Copy XML
                                            </button>
                                        </div>
                                    </div>
                                    <div class="xml-stream-body" id="xmlStreamContainer">
                                        <pre style="margin: 0; white-space: pre-wrap;" id="streamXmlText"><code><span class="xml-comment">&lt;!-- Live Stream Payload XML SAP (PTPN IV Regional III) --&gt;</span>
&lt;<span class="xml-tag">PTPN4_SAP_Table_Ingest</span> <span class="xml-attr-name">xmlns:sap</span>=<span class="xml-attr-val">"urn:sap-com:document:sap:rfc"</span> <span class="xml-attr-name">regional</span>=<span class="xml-attr-val">"Regional 3"</span> <span class="xml-attr-name">total_records</span>=<span class="xml-attr-val">"{{ count($logs) }}"</span>&gt;
  &lt;<span class="xml-tag">Header</span>&gt;
    &lt;<span class="xml-tag">TableID</span>&gt;<span class="xml-content">TBL-WEIGHBRIDGE-HISTORICAL-{{ date('Ymd') }}</span>&lt;/<span class="xml-tag">TableID</span>&gt;
    &lt;<span class="xml-tag">TotalTransactions</span>&gt;<span class="xml-content">{{ count($logs) }} Records</span>&lt;/<span class="xml-tag">TotalTransactions</span>&gt;
    &lt;<span class="xml-tag">TotalNettoKg</span>&gt;<span class="xml-content">{{ number_format(is_array($logs) ? array_sum(array_column($logs, 'netto')) : $logs->sum('netto')) }} KG</span>&lt;/<span class="xml-tag">TotalNettoKg</span>&gt;
    &lt;<span class="xml-tag">GeneratedAt</span>&gt;<span class="xml-content">{{ date('Y-m-d H:i:s') }}</span>&lt;/<span class="xml-tag">GeneratedAt</span>&gt;
  &lt;/<span class="xml-tag">Header</span>&gt;
  &lt;<span class="xml-tag">TableData</span>&gt;
    &lt;<span class="xml-tag">NotaTimbanganRecord</span> <span class="xml-attr-name">row_id</span>=<span class="xml-attr-val">"1"</span>&gt;
      &lt;<span class="xml-tag">NotaNumber</span>&gt;<span class="xml-content">NT-PKS-PBT-20260810-001</span>&lt;/<span class="xml-tag">NotaNumber</span>&gt;
      &lt;<span class="xml-tag">NoPlat</span>&gt;<span class="xml-content">BM 8492 AU</span>&lt;/<span class="xml-tag">NoPlat</span>&gt;
      &lt;<span class="xml-tag">PKSLocation</span>&gt;<span class="xml-content">PKS Pabatu</span>&lt;/<span class="xml-tag">PKSLocation</span>&gt;
      &lt;<span class="xml-tag">TimbanganData</span>&gt;
        &lt;<span class="xml-tag">BeratNetto</span>&gt;<span class="xml-content">15,300 KG</span>&lt;/<span class="xml-tag">BeratNetto</span>&gt;
      &lt;/<span class="xml-tag">TimbanganData</span>&gt;
    &lt;/<span class="xml-tag">NotaTimbanganRecord</span>&gt;
  &lt;/<span class="xml-tag">TableData</span>&gt;
&lt;/<span class="xml-tag">PTPN4_SAP_Table_Ingest</span>&gt;</code></pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIWAYAT AKTIVITAS LOGIN & LOGOUT ADMINISTRATOR TI -->
                <div class="custom-card p-3 p-md-4 mt-4 animate-in shadow-sm" id="riwayat-login-section" style="border-radius: 18px; border: 1px solid #e2e8f0; background: #ffffff;">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3 pb-3 border-bottom">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 44px; height: 44px; background: var(--forest-50); border: 1px solid var(--forest-200); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--forest-700); font-size: 1.25rem;">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.05rem;">Riwayat Sesi Login & Logout Petugas TI</h5>
                                <small class="text-muted" style="font-size: 0.78rem;">Catatan audit waktu login, jam logout, IP address, dan status aktivitas sesi pengguna</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill small fw-semibold">
                                <i class="bi bi-shield-check me-1"></i> Audit Trail Aktif
                            </span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.84rem;">
                            <thead class="table-light text-muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                <tr>
                                    <th class="ps-3 py-2">Pengguna / Akun</th>
                                    <th class="py-2">Role</th>
                                    <th class="py-2"><i class="bi bi-box-arrow-in-right text-success me-1"></i>Jam Login</th>
                                    <th class="py-2"><i class="bi bi-box-arrow-right text-danger me-1"></i>Jam Logout</th>
                                    <th class="py-2 text-center">Durasi Sesi</th>
                                    <th class="py-2">IP & Perangkat</th>
                                    <th class="text-center pe-3 py-2">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayatLogins as $rw)
                                    @php
                                        $isOnline = strtolower($rw->status) === 'aktif' || empty($rw->logout_at);
                                        $durasi = '-';
                                        if ($rw->login_at && $rw->logout_at) {
                                            $diff = $rw->login_at->diff($rw->logout_at);
                                            $parts = [];
                                            if ($diff->h > 0) $parts[] = $diff->h . ' jam';
                                            if ($diff->i > 0) $parts[] = $diff->i . ' mnt';
                                            if ($diff->s > 0 && empty($parts)) $parts[] = $diff->s . ' dtk';
                                            $durasi = implode(' ', $parts) ?: '1 mnt';
                                        } elseif ($isOnline) {
                                            $diff = $rw->login_at ? $rw->login_at->diff(now()) : null;
                                            $durasi = $diff ? ($diff->h > 0 ? $diff->h . 'j ' : '') . $diff->i . 'm (Berjalan)' : 'Aktif';
                                        }
                                    @endphp
                                    <tr>
                                        <td class="ps-3 py-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <div style="width: 32px; height: 32px; background: var(--forest-100); color: var(--forest-800); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem;">
                                                    {{ strtoupper(substr($rw->nama_pengguna ?? 'A', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $rw->nama_pengguna }}</div>
                                                    <small class="text-muted font-monospace" style="font-size: 0.72rem;">{{ $rw->username }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border px-2 py-1 rounded-pill" style="font-size: 0.72rem;">
                                                {{ $rw->role ?? 'Admin' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark">
                                                {{ $rw->login_at ? $rw->login_at->translatedFormat('d M Y, H:i:s') . ' WIB' : '-' }}
                                            </div>
                                            <small class="text-muted" style="font-size: 0.70rem;">
                                                {{ $rw->login_at ? $rw->login_at->diffForHumans() : '' }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($rw->logout_at)
                                                <div class="fw-semibold text-dark">
                                                    {{ $rw->logout_at->translatedFormat('d M Y, H:i:s') . ' WIB' }}
                                                </div>
                                                <small class="text-muted" style="font-size: 0.70rem;">
                                                    {{ $rw->logout_at->diffForHumans() }}
                                                </small>
                                            @else
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                                                    <span class="pulse-dot me-1"></span> Sedang Aktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center font-monospace" style="font-size: 0.76rem;">
                                            <span class="badge {{ $isOnline ? 'bg-light text-primary border' : 'bg-light text-secondary border' }}">
                                                {{ $durasi }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="font-monospace text-secondary" style="font-size: 0.75rem;">
                                                <i class="bi bi-hdd-network me-1"></i>{{ $rw->ip_address ?? '127.0.0.1' }}
                                            </div>
                                            <small class="text-muted text-truncate d-inline-block" style="max-width: 140px; font-size: 0.68rem;" title="{{ $rw->user_agent }}">
                                                {{ Str::limit($rw->user_agent ?? 'Desktop Browser', 20) }}
                                            </small>
                                        </td>
                                        <td class="text-center pe-3">
                                            @if($isOnline)
                                                <span class="badge rounded-pill bg-success px-3 py-1" style="font-size: 0.72rem;">
                                                    <i class="bi bi-dot"></i> Online
                                                </span>
                                            @else
                                                <span class="badge rounded-pill bg-secondary bg-opacity-25 text-secondary px-3 py-1" style="font-size: 0.72rem;">
                                                    Logout
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                                            Belum ada catatan riwayat login dan logout tercatat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- ==================== TAB 2: TRANSAKSI & XML GENERATOR ENGINE ==================== -->
            <div class="tab-pane fade" id="tab-timbangan" role="tabpanel">
                <div class="row g-4">
                    <!-- Form Input Transaksi Timbangan -->
                    <div class="col-lg-4">
                        <div class="card custom-card p-3 p-md-4 animate-in shadow-sm">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div style="width: 38px; height: 38px; background: var(--forest-50); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--forest-600); font-size: 1.1rem;">
                                    <i class="bi bi-plus-circle-fill"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-0" style="color: var(--forest-900); font-size: 1rem;">Input Transaksi Timbangan</h5>
                                    <small class="text-muted" style="font-size: 0.74rem;">Otomatis Meng-generate XML SAP Per-Data</small>
                                </div>
                            </div>
                            <form action="{{ route('bridge.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">No. Plat Kendaraan</label>
                                    <input type="text" name="no_plat" class="form-control" placeholder="Contoh: BM 8492 AU" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Lokasi Unit PKS</label>
                                    <select name="pks_loc" id="selectPksLoc" class="form-select" required onchange="handlePksSelectChange(this)">
                                        @if(isset($unitsList) && $unitsList->isNotEmpty())
                                            @foreach($unitsList as $u)
                                                <option value="{{ $u->nama_unit }}" data-vpn="{{ $u->ip_vpn }}">{{ $u->nama_unit }} ({{ $u->lokasi }})</option>
                                            @endforeach
                                        @else
                                            <option value="PKS Pabatu" data-vpn="10.14.2.10">PKS Pabatu</option>
                                            <option value="PKS Sawit Seberang" data-vpn="10.14.3.15">PKS Sawit Seberang</option>
                                            <option value="PKS Bah Jambi" data-vpn="10.14.4.22">PKS Bah Jambi</option>
                                            <option value="PKS Mayang" data-vpn="10.14.5.8">PKS Mayang</option>
                                            <option value="PKS Tanah Hitam" data-vpn="10.14.6.12">PKS Tanah Hitam</option>
                                            <option value="PKS Sei Galuh" data-vpn="10.14.7.18">PKS Sei Galuh</option>
                                            <option value="PKS Sei Pagar" data-vpn="10.14.8.25">PKS Sei Pagar</option>
                                            <option value="PKS Tandun" data-vpn="10.14.9.11">PKS Tandun</option>
                                            <option value="PKS Lubuk Dalam" data-vpn="10.14.10.14">PKS Lubuk Dalam</option>
                                            <option value="PKS Terantam" data-vpn="10.14.11.19">PKS Terantam</option>
                                            <option value="PKS Tanah Putih" data-vpn="10.14.12.30">PKS Tanah Putih</option>
                                            <option value="PKS Sei Tapung" data-vpn="10.14.13.22">PKS Sei Tapung</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">IP Address VPN / VM</label>
                                    <input type="text" name="vpn_ip" id="inputVpnIp" class="form-control font-monospace" value="10.14.2.10" required>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label">Jam Masuk</label>
                                        <input type="time" name="jam_masuk" class="form-control" value="08:15" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Jam Keluar</label>
                                        <input type="time" name="jam_keluar" class="form-control" value="08:45" required>
                                    </div>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label">Gross (Kg)</label>
                                        <input type="number" name="gross" class="form-control" placeholder="24500" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Tarra (Kg)</label>
                                        <input type="number" name="tarra" class="form-control" placeholder="9200" required>
                                    </div>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label">Status Alat</label>
                                        <select name="status_timbangan" class="form-select">
                                            <option value="Normal">Normal</option>
                                            <option value="Mati/Offline">Mati/Offline</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Status VPN</label>
                                        <select name="status_vpn" class="form-select">
                                            <option value="Connected">Connected</option>
                                            <option value="Trouble">Trouble</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success w-100 fw-semibold">
                                    <i class="bi bi-save me-2"></i>Simpan
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- TABEL HISTORICAL LEDGER TRANSAKSI TIMBANGAN -->
                    <div class="col-lg-8">
                        <div class="card custom-card p-3 p-md-4 animate-in shadow-sm">
                            
                            <!-- Sub Navigation Tabs: Semua Transaksi & Log Terverifikasi SAP -->
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom flex-wrap gap-2">
                                <ul class="nav nav-pills custom-subtabs" id="ledgerSubTabs" role="tablist" style="gap: 6px;">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active btn-sm rounded-pill fw-semibold border" id="subtab-all-tab" data-bs-toggle="pill" data-bs-target="#subtab-all" type="button" role="tab" style="font-size: 0.8rem; padding: 6px 16px;">
                                            <i class="bi bi-list-task me-1"></i>Semua Transaksi
                                            <span class="badge bg-dark text-white rounded-pill ms-1" style="font-size: 0.68rem;">{{ count($logs) }}</span>
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link btn-sm rounded-pill fw-semibold border" id="subtab-sent-tab" data-bs-toggle="pill" data-bs-target="#subtab-sent" type="button" role="tab" style="font-size: 0.8rem; padding: 6px 16px;">
                                            <i class="bi bi-patch-check-fill me-1 text-success"></i>Log Terverifikasi SAP
                                            <span class="badge bg-success text-white rounded-pill ms-1" style="font-size: 0.68rem;">{{ count($sentLogs) }}</span>
                                        </button>
                                    </li>
                                </ul>
                                <input type="text" id="historicalSearch" class="form-control form-control-sm" placeholder="Cari Plat / Nota / PKS..." style="width: 170px; font-size: 0.75rem;" onkeyup="filterHistoricalTable()">
                            </div>

                            <div class="tab-content" id="ledgerSubTabsContent">
                                <!-- SUB TAB 1: SEMUA TRANSAKSI -->
                                <div class="tab-pane fade show active" id="subtab-all" role="tabpanel">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h6 class="fw-bold mb-0 text-success" style="font-size: 0.92rem;">
                                                <i class="bi bi-table me-1"></i>Ledger Historis Transaksi Timbangan
                                            </h6>
                                            <small class="text-muted" style="font-size: 0.74rem;">Seluruh riwayat penimbangan & status auto-generate XML / push SAP</small>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-success text-white rounded-pill fw-semibold d-inline-flex align-items-center gap-1 border-0" onclick="openXmlPreview('all')" title="Export Rekap Kode XML Gabungan (1 Tabel) Seluruh Historical Data Timbangan" style="font-size: 0.75rem; padding: 5px 12px; line-height: 1.2; box-shadow: 0 2px 5px rgba(25,135,84,0.2);">
                                            <i class="bi bi-file-earmark-zip"></i> Export Rekap XML (Gabungan)
                                        </button>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0" id="historicalTable">
                                            <thead>
                                                <tr>
                                                    <th>Nota & Plat</th>
                                                    <th>Lokasi PKS / IP</th>
                                                    <th>Netto (Kg)</th>
                                                    <th>Alat / VPN</th>
                                                    <th>STATUS XML</th>
                                                    <th>STATUS SAP</th>
                                                    <th class="text-center">Aksi Engine</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($logs as $log)
                                                    <tr>
                                                        <td>
                                                            <span class="fw-bold d-block text-dark">{{ $log['no_plat'] }}</span>
                                                            <small class="text-muted font-monospace" style="font-size: 0.72rem;">{{ $log['nota_no'] ?? ('NT-' . $log['id']) }}</small>
                                                        </td>
                                                        <td>
                                                            <small class="d-block fw-semibold text-success">{{ $log['pks_loc'] }}</small>
                                                            <small class="text-muted font-monospace" style="font-size: 0.75rem;">{{ $log['vpn_ip'] }}</small>
                                                        </td>
                                                        <td>
                                                            <span class="fw-bold" style="color: var(--forest-700); font-size: 0.95rem;">{{ number_format($log['netto']) }} Kg</span>
                                                            <small class="d-block text-muted" style="font-size: 0.7rem;">G: {{ number_format($log['gross']) }} | T: {{ number_format($log['tarra']) }}</small>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex flex-column gap-1">
                                                                <span class="badge {{ $log['status_timbangan'] == 'Normal' ? 'badge-ok' : 'badge-trouble' }}" style="font-size: 0.68rem; padding: 3px 8px;">
                                                                    Alat: {{ $log['status_timbangan'] }}
                                                                </span>
                                                                <span class="badge {{ $log['status_vpn'] == 'Connected' ? 'badge-ok' : 'badge-trouble' }}" style="font-size: 0.68rem; padding: 3px 8px;">
                                                                    VPN: {{ $log['status_vpn'] }}
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <!-- STATUS XML (AUTO GENERATED) -->
                                                        <td>
                                                            <span class="badge bg-success-subtle text-success border border-success border-opacity-25" style="font-size: 0.72rem; padding: 4px 8px;">
                                                                <i class="bi bi-file-earmark-check-fill me-1"></i>{{ $log['xml_status'] ?? 'Generated' }}
                                                            </span>
                                                            <small class="d-block text-muted font-monospace mt-1" style="font-size: 0.68rem;">Auto-Generated</small>
                                                        </td>
                                                        <!-- STATUS SAP -->
                                                        <td>
                                                            @if(($log['status_sap'] ?? 'PENDING') === 'SENT')
                                                                <span class="badge badge-sent">
                                                                    <i class="bi bi-check-circle-fill me-1"></i>SENT / VERIFIED
                                                                </span>
                                                                <small class="d-block text-muted font-monospace mt-1" style="font-size: 0.68rem;">{{ $log['sap_doc_no'] ?? 'SAP-OK' }}</small>
                                                            @else
                                                                <span class="badge badge-pending">
                                                                    <i class="bi bi-clock-history me-1"></i>PENDING
                                                                </span>
                                                                <small class="d-block text-warning mt-1" style="font-size: 0.68rem;">Belum terkirim</small>
                                                            @endif
                                                        </td>
                                                        <!-- AKSI DENGAN 2 OPSI: PREVIEW XML & PUSH TO SAP -->
                                                        <td class="text-center">
                                                            <div class="d-flex justify-content-center align-items-center gap-1">
                                                                <!-- Option 1: Preview XML -->
                                                                <button type="button" class="btn btn-sm btn-outline-success" onclick="openXmlPreview({{ $log['id'] }})" title="Preview Kode Skema XML SAP" style="border-radius: 8px; font-size: 0.76rem; font-weight: 600;">
                                                                    <i class="bi bi-filetype-xml me-1"></i>Preview XML
                                                                </button>

                                                                <!-- Option 2: Push to SAP -->
                                                                @if(($log['status_sap'] ?? 'PENDING') === 'PENDING')
                                                                    <form action="{{ route('bridge.push', $log['id']) }}" method="POST" class="d-inline">
                                                                        @csrf
                                                                        <button type="submit" class="btn btn-sm btn-push-sap" title="Lempar File XML Ke Server SAP Kantor Pusat">
                                                                            <i class="bi bi-send-fill me-1"></i>Push SAP
                                                                        </button>
                                                                    </form>
                                                                @else
                                                                    <button class="btn btn-sm btn-light border text-muted" disabled style="border-radius: 8px; font-size: 0.76rem;">
                                                                        <i class="bi bi-cloud-check-fill text-success me-1"></i>Sent
                                                                    </button>
                                                                @endif

                                                                <!-- Download XML File -->
                                                                <a href="{{ route('bridge.xml', $log['id']) }}" class="btn btn-sm btn-light border" title="Download File XML Config" style="border-radius: 8px; font-size: 0.76rem;">
                                                                    <i class="bi bi-download"></i>
                                                                </a>

                                                                <!-- Delete -->
                                                                <a href="{{ route('bridge.delete', $log['id']) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus data timbangan ini?')" style="border-radius: 8px; font-size: 0.76rem;">
                                                                    <i class="bi bi-trash3"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center py-5">
                                                            <div class="text-muted">
                                                                <i class="bi bi-inbox" style="font-size: 2.5rem; color: var(--forest-300);"></i>
                                                                <p class="mt-2 mb-0 fw-semibold">Belum ada data transaksi timbangan</p>
                                                                <small>Silakan input data melalui form di samping</small>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- SUB TAB 2: LOG TERVERIFIKASI SAP (READ ONLY) -->
                                <div class="tab-pane fade" id="subtab-sent" role="tabpanel">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h6 class="fw-bold mb-0 text-success" style="font-size: 0.92rem;">
                                                <i class="bi bi-journal-check me-1"></i>Audit Log Integrasi SAP (Terverifikasi)
                                            </h6>
                                            <small class="text-muted" style="font-size: 0.74rem;">Daftar transaksi yang telah berhasil di-push dan di-ingest sistem SAP (Read-Only)</small>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-success text-white rounded-pill fw-semibold d-inline-flex align-items-center gap-1 border-0" onclick="openXmlPreview('sent')" title="Export Rekap Kode XML Gabungan Seluruh Data Terverifikasi SAP" style="font-size: 0.75rem; padding: 5px 12px; line-height: 1.2; box-shadow: 0 2px 5px rgba(25,135,84,0.2);">
                                            <i class="bi bi-file-earmark-zip"></i> Export Rekap XML (Gabungan)
                                        </button>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead>
                                                <tr>
                                                    <th>No. Ref SAP</th>
                                                    <th>No. Nota & Plat</th>
                                                    <th>Unit PKS</th>
                                                    <th>Waktu Push XML</th>
                                                    <th>Response SAP</th>
                                                    <th class="text-center">Aksi Audit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($sentLogs as $sLog)
                                                    <tr>
                                                        <td class="fw-bold font-monospace text-primary" style="font-size: 0.88rem;">
                                                            <i class="bi bi-shield-check me-1 text-success"></i>{{ $sLog['sap_doc_no'] ?? 'SAP-PTPN4-99102' }}
                                                        </td>
                                                        <td>
                                                            <span class="fw-bold d-block text-dark">{{ $sLog['no_plat'] }}</span>
                                                            <small class="text-muted font-monospace" style="font-size: 0.72rem;">{{ $sLog['nota_no'] }}</small>
                                                        </td>
                                                        <td>
                                                            <span class="fw-semibold text-dark">{{ $sLog['pks_loc'] }}</span>
                                                            <small class="d-block text-muted font-monospace" style="font-size: 0.72rem;">IP: {{ $sLog['vpn_ip'] }}</small>
                                                        </td>
                                                        <td>
                                                            <small class="d-block fw-semibold text-dark">{{ $sLog['sap_sent_at'] ?? date('Y-m-d H:i:s') }}</small>
                                                            <small class="text-success"><i class="bi bi-check2 me-1"></i>Success (VPN Encrypted)</small>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-success-subtle text-success border border-success border-opacity-25 px-2 py-1" style="font-size: 0.75rem;">
                                                                HTTP 200 OK - XML Ingested
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="d-flex justify-content-center align-items-center gap-1">
                                                                <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3" onclick="openXmlPreview({{ $sLog['id'] }})" style="font-size: 0.76rem;">
                                                                    <i class="bi bi-eye me-1"></i>Lihat XML Ingest
                                                                </button>
                                                                <a href="{{ route('bridge.xml', $sLog['id']) }}" class="btn btn-sm btn-light border rounded-pill" title="Download File XML" style="font-size: 0.76rem;">
                                                                    <i class="bi bi-download"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center py-5">
                                                            <div class="text-muted">
                                                                <i class="bi bi-cloud-arrow-up text-muted" style="font-size: 2.5rem;"></i>
                                                                <p class="mt-2 mb-0 fw-semibold">Belum Ada File XML Yang Dilempar ke SAP</p>
                                                                <small>Lakukan [ Push to SAP ] pada tab Semua Transaksi</small>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- ==================== MODAL PREVIEW XML SCHEMA ==================== -->
    <div class="modal fade" id="modalXmlPreview" tabindex="-1" aria-labelledby="modalXmlPreviewLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 18px; overflow: hidden;">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, var(--forest-900), var(--forest-700));">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 34px; height: 34px; background: rgba(255,255,255,0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-filetype-xml"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0" id="modalXmlPreviewLabel" style="font-size: 1.05rem;">Preview Skema Kode XML SAP</h5>
                            <small class="text-white-50" id="modalXmlSubtitle" style="font-size: 0.75rem;">Nota Timbangan PTPN IV Regional III</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <!-- Tabs untuk Format XML -->
                    <ul class="nav nav-tabs px-3 pt-3 bg-light border-bottom" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active font-monospace fw-semibold" id="tab-sap-xml-tab" data-bs-toggle="tab" data-bs-target="#tab-sap-xml" type="button" role="tab" style="font-size: 0.82rem;">
                                <i class="bi bi-code-slash me-1 text-success"></i>SAP Ingest Payload XML
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link font-monospace fw-semibold" id="tab-ado-xml-tab" data-bs-toggle="tab" data-bs-target="#tab-ado-xml" type="button" role="tab" style="font-size: 0.82rem;">
                                <i class="bi bi-gear-wide-connected me-1 text-primary"></i>ADO Recordset Terminal XML
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <!-- SAP XML Tab -->
                        <div class="tab-pane fade show active p-3" id="tab-sap-xml" role="tabpanel">
                            <div class="position-relative">
                                <textarea id="xmlSapContent" class="form-control font-monospace bg-dark text-success border-0 p-3" rows="14" readonly style="font-size: 0.82rem; border-radius: 12px; line-height: 1.45;"></textarea>
                            </div>
                        </div>
                        <!-- ADO XML Tab -->
                        <div class="tab-pane fade p-3" id="tab-ado-xml" role="tabpanel">
                            <div class="position-relative">
                                <textarea id="xmlAdoContent" class="form-control font-monospace bg-dark text-info border-0 p-3" rows="14" readonly style="font-size: 0.82rem; border-radius: 12px; line-height: 1.45;"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light justify-content-between">
                    <small class="text-muted" style="font-size: 0.76rem;">
                        <i class="bi bi-shield-check text-success me-1"></i>Format XML ini siap dilempar via REST/RFC API SAP
                    </small>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" onclick="copyModalXml()">
                            <i class="bi bi-clipboard me-1"></i>Copy XML
                        </button>
                        <a id="btnDownloadModalXml" href="#" class="btn btn-success btn-sm rounded-pill px-3">
                            <i class="bi bi-download me-1"></i>Download File XML
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ===== SIDEBAR TOGGLE & DRAWER =====
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            const isMobile = window.innerWidth <= 991.98;
            if (isMobile) {
                sidebar.classList.toggle('show-mobile');
                sidebarOverlay.classList.toggle('active');
            } else {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
            }
        }

        function closeMobileSidebar() {
            sidebar.classList.remove('show-mobile');
            sidebarOverlay.classList.remove('active');
        }

        toggleBtn.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', closeMobileSidebar);

        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 991.98) {
                    closeMobileSidebar();
                }
            });
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 991.98) {
                closeMobileSidebar();
            }
        });

        function switchToTab(tabId, subTabId = null) {
            const targetTabBtn = document.getElementById(tabId);
            if (targetTabBtn) {
                const tab = new bootstrap.Tab(targetTabBtn);
                tab.show();
            }
            if (subTabId) {
                const targetSubTabBtn = document.getElementById(subTabId);
                if (targetSubTabBtn) {
                    const subTab = new bootstrap.Tab(targetSubTabBtn);
                    subTab.show();
                }
            }
        }

        // Tab persistence via Hash (#tab-timbangan, #tab-dashboard, #tab-logsap, ?tab=log-sap)
        window.addEventListener('DOMContentLoaded', () => {
            const hash = window.location.hash;
            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab');

            if (hash === '#tab-logsap' || hash === '#subtab-sent' || tabParam === 'log-sap') {
                switchToTab('timbangan-tab', 'subtab-sent-tab');
            } else if (hash) {
                const targetTabBtn = document.querySelector(`.nav-link[data-bs-target="${hash}"]`);
                if (targetTabBtn) {
                    const tab = new bootstrap.Tab(targetTabBtn);
                    tab.show();
                }
            }
        });

        // Quick Filter for Historical Table
        function filterHistoricalTable() {
            const query = document.getElementById('historicalSearch').value.toLowerCase();
            const rows = document.querySelectorAll('#historicalTable tbody tr');
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        }

        // ===== CHART.JS CONFIGURATION =====
        const ctx = document.getElementById('salesChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 260);
        gradient.addColorStop(0, 'rgba(25, 135, 84, 0.85)');
        gradient.addColorStop(0.5, 'rgba(39, 167, 106, 0.45)');
        gradient.addColorStop(1, 'rgba(39, 167, 106, 0.05)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Jumlah XML Digenerate',
                    data: {!! json_encode($data) !!},
                    backgroundColor: gradient,
                    borderColor: 'rgba(15, 81, 50, 0.85)',
                    borderWidth: 1.5,
                    borderRadius: 6,
                    borderSkipped: false,
                    hoverBackgroundColor: 'rgba(25, 135, 84, 0.95)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f5132',
                        titleFont: { family: 'Inter', weight: '600', size: 12 },
                        bodyFont: { family: 'Inter', size: 12 },
                        padding: 10,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Total XML Digenerate: ' + context.parsed.y + ' Berkas';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: { family: 'Inter', size: 10 },
                            color: '#8a9e93',
                            callback: function(value) {
                                return value + ' XML';
                            }
                        },
                        grid: { color: 'rgba(0, 0, 0, 0.04)', drawBorder: false }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Inter', size: 10, weight: '500' }, color: '#5a7066' }
                    }
                }
            }
        });

        // ===== AUTO-FILL VPN IP UPON PKS CHANGE =====
        function handlePksSelectChange(sel) {
            const opt = sel.options[sel.selectedIndex];
            const vpn = opt.getAttribute('data-vpn');
            if (vpn) {
                const vpnInput = document.getElementById('inputVpnIp');
                if (vpnInput) {
                    vpnInput.value = vpn;
                }
            }
        }

        // ===== MODAL PREVIEW XML AJAX =====
        function openXmlPreview(id = 'all') {
            fetch('/xml-preview/' + id)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('modalXmlSubtitle').innerText = data.subtitle || 'Auto Generate Config XML (1 Tabel)';
                        document.getElementById('xmlSapContent').value = data.xml_sap;
                        document.getElementById('xmlAdoContent').value = data.xml_ado;
                        document.getElementById('btnDownloadModalXml').href = '/generate-xml/' + (id || 'all');

                        const modal = new bootstrap.Modal(document.getElementById('modalXmlPreview'));
                        modal.show();
                    } else {
                        alert(data.message || 'Gagal memuat XML!');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Terjadi kesalahan saat memuat preview XML!');
                });
        }

        // ===== COPY XML FUNCTIONALITY =====
        function copyStreamXml() {
            const text = document.getElementById('streamXmlText').innerText;
            navigator.clipboard.writeText(text).then(() => {
                alert('Kode XML Stream berhasil disalin ke clipboard!');
            });
        }

        function copyModalXml() {
            const activeTab = document.querySelector('.modal-body .tab-pane.active textarea');
            if (activeTab) {
                navigator.clipboard.writeText(activeTab.value).then(() => {
                    alert('Kode XML berhasil disalin ke clipboard!');
                });
            }
        }
    </script>
</body>
</html>