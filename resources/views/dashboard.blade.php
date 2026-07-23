<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard & Manajemen Timbangan - PTPN IV reg III</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
            --sidebar-width: 260px;
            --sidebar-collapsed: 78px;
        }

        * { box-sizing: border-box; }

        body {
            background-color: #f0f4f2;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            overflow-x: hidden;
            color: #2d3a33;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0; left: 0;
            background: linear-gradient(175deg, var(--forest-900) 0%, var(--forest-700) 50%, var(--forest-600) 100%);
            color: #fff;
            padding-top: 0;
            z-index: 1000;
            transition: width 0.35s cubic-bezier(.4,0,.2,1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 24px rgba(6, 78, 43, 0.15);
        }
        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }

        .sidebar-brand {
            padding: 22px 20px 18px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 72px;
        }
        .sidebar-brand .brand-icon {
            width: 38px; height: 38px;
            background: rgba(255,255,255,0.15);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        .sidebar-brand .brand-text {
            white-space: nowrap;
            overflow: hidden;
            transition: opacity 0.25s ease, width 0.3s ease;
        }
        .sidebar.collapsed .brand-text {
            opacity: 0;
            width: 0;
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
        }
        .sidebar-toggle-header:hover {
            background: var(--forest-600);
            color: #fff;
            border-color: var(--forest-600);
            transform: scale(1.06);
            box-shadow: 0 4px 14px rgba(25, 135, 84, 0.3);
        }
        .sidebar-toggle-header i {
            transition: transform 0.35s ease;
        }
        .sidebar.collapsed ~ #mainContent .sidebar-toggle-header i {
            transform: rotate(180deg);
        }

        /* Nav Links */
        .sidebar-nav {
            padding: 12px 0;
            flex: 1;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.7);
            padding: 11px 20px;
            margin: 3px 12px;
            border-radius: 10px;
            font-weight: 500;
            font-size: 0.88rem;
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
        }
        .sidebar .nav-link i {
            font-size: 1.15rem;
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
        .sidebar .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
        }
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.18);
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .sidebar .nav-link.active i {
            color: var(--forest-200);
        }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.08);
            font-size: 0.75rem;
            color: rgba(255,255,255,0.35);
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
            border-radius: 14px;
            padding: 16px 24px;
            margin-bottom: 24px;
            box-shadow: 0 1px 8px rgba(0,0,0,0.04);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-bar h3 {
            color: var(--forest-900);
            font-weight: 700;
            font-size: 1.35rem;
            margin-bottom: 2px;
        }

        /* ===== HERO BANNER ===== */
        .hero-banner {
            background: linear-gradient(135deg, var(--forest-800) 0%, var(--forest-600) 60%, var(--forest-500) 100%);
            border-radius: 18px;
            color: white;
            padding: 28px 30px;
            margin-bottom: 26px;
            position: relative;
            overflow: hidden;
        }
        .hero-banner::before {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 200px; height: 200px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
        }
        .hero-banner::after {
            content: '';
            position: absolute;
            bottom: -40px; left: 30%;
            width: 150px; height: 150px;
            background: rgba(255,255,255,0.03);
            border-radius: 50%;
        }
        .hero-banner h2 {
            font-weight: 800;
            font-size: 1.55rem;
            margin-bottom: 6px;
        }

        /* ===== HERO STAT CARDS ===== */
        .hero-card {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            border-radius: 14px;
            padding: 18px 16px;
            color: white;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .hero-card::after {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(255,255,255,0.08) 0%, transparent 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .hero-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
            background: rgba(255, 255, 255, 0.16);
        }
        .hero-card:hover::after {
            opacity: 1;
        }
        .hero-card small.text-white-50 {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        .hero-card h3 {
            font-weight: 800;
            font-size: 1.5rem;
        }

        /* ===== CARDS ===== */
        .custom-card {
            background: #ffffff;
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }
        .custom-card:hover {
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.07);
        }

        /* ===== BADGES ===== */
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

        /* ===== TABLE ===== */
        .table thead th {
            background: var(--forest-50);
            color: var(--forest-900);
            font-weight: 700;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--forest-200);
            padding: 12px 14px;
        }
        .table tbody tr {
            transition: background 0.2s ease;
        }
        .table tbody tr:hover {
            background: var(--forest-50);
        }
        .table tbody td {
            padding: 12px 14px;
            font-size: 0.88rem;
            vertical-align: middle;
        }

        /* ===== FORM STYLING ===== */
        .form-control, .form-select {
            border-radius: 10px;
            border: 1.5px solid #d6e4dc;
            font-size: 0.88rem;
            padding: 9px 14px;
            transition: all 0.2s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--forest-500);
            box-shadow: 0 0 0 3px rgba(39, 167, 106, 0.15);
        }
        .form-label {
            color: var(--forest-900);
            font-weight: 600;
            font-size: 0.8rem;
        }

        /* ===== BUTTONS ===== */
        .btn-success {
            background: linear-gradient(135deg, var(--forest-600), var(--forest-500));
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(25, 135, 84, 0.25);
        }
        .btn-success:hover {
            background: linear-gradient(135deg, var(--forest-700), var(--forest-600));
            transform: translateY(-1px);
            box-shadow: 0 5px 16px rgba(25, 135, 84, 0.35);
        }

        /* ===== PROBLEM CARD ===== */
        .problem-card {
            border-left: 4px solid #ff6b6b;
            border-radius: 16px;
        }
        .problem-item {
            padding: 14px 0;
        }
        .problem-item + .problem-item {
            border-top: 1px solid #f0f0f0;
        }
        .problem-item .problem-icon {
            width: 38px; height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        /* ===== ALERT ===== */
        .alert-success {
            background: var(--forest-50);
            border: 1px solid var(--forest-200);
            color: var(--forest-900);
            border-radius: 12px;
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

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f0f4f2; }
        ::-webkit-scrollbar-thumb { background: var(--forest-300); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--forest-500); }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in {
            animation: fadeInUp 0.5s ease forwards;
        }
        .animate-in:nth-child(1) { animation-delay: 0.05s; }
        .animate-in:nth-child(2) { animation-delay: 0.1s; }
        .animate-in:nth-child(3) { animation-delay: 0.15s; }
        .animate-in:nth-child(4) { animation-delay: 0.2s; }
    </style>
</head>
<body>

    <!-- ===== SIDEBAR NAVIGATION ===== -->
    <div class="sidebar" id="sidebar">
        <!-- Brand -->
        <div class="sidebar-brand">
            <div class="brand-icon">
                <i class="bi bi-truck"></i>
            </div>
            <div class="brand-text">
                <div class="fw-bold" style="font-size: 0.95rem; line-height: 1.2;">PTPN IV Regional III</div>
                <div style="font-size: 0.68rem; opacity: 0.5; margin-top: 2px;">Sistem Timbangan PKS</div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="sidebar-nav">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist">
                <button class="nav-link active" id="dashboard-tab" data-bs-toggle="pill" data-bs-target="#tab-dashboard" type="button" role="tab">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span class="link-text">Dashboard Utama</span>
                </button>
                <button class="nav-link" id="timbangan-tab" data-bs-toggle="pill" data-bs-target="#tab-timbangan" type="button" role="tab">
                    <i class="bi bi-pencil-square"></i>
                    <span class="link-text">Kelola Timbangan</span>
                </button>
            </div>
        </div>

        <!-- Footer -->
        <div class="sidebar-footer">
            <i class="bi bi-shield-check me-1"></i>
            <span>v2.0 &middot; PTPN IV Reg III</span>
        </div>
    </div>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="main-content" id="mainContent">
        
        <!-- Header Bar -->
        <div class="header-bar animate-in">
            <div class="d-flex align-items-center gap-3">
                <!-- Toggle Navigasi di Depan -->
                <button class="sidebar-toggle-header" id="sidebarToggle" title="Perkecil / Perbesar Navigasi">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <h3>Panel Sistem PKS</h3>
                    <small class="text-muted" style="font-size: 0.8rem;">Monitoring Timbangan & SAP XML Generator</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light rounded-circle shadow-sm position-relative" style="width: 40px; height: 40px; color: var(--forest-700);">
                    <i class="bi bi-bell"></i>
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" style="width: 10px; height: 10px; margin-left: -12px; margin-top: 4px;"></span>
                </button>
                <div class="user-badge">
                    <img src="https://ui-avatars.com/api/?name=Akin+PTPN&background=0f5132&color=fff&size=36&font-size=0.4&bold=true" class="rounded-circle" width="34" height="34" alt="Avatar">
                    <div>
                        <div class="user-name">Akin</div>
                        <div class="user-role">Petugas PKS</div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Tab Content -->
        <div class="tab-content" id="v-pills-tabContent">
            
            <!-- ===== TAB 1: DASHBOARD UTAMA ===== -->
            <div class="tab-pane fade show active" id="tab-dashboard" role="tabpanel">
                
                <!-- Hero Banner -->
                <div class="hero-banner shadow-sm animate-in">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge rounded-pill" style="background: rgba(255,255,255,0.15); font-size: 0.72rem; font-weight: 500; padding: 5px 12px;">
                            <i class="bi bi-calendar3 me-1"></i> {{ date('l, d F Y') }}
                        </span>
                    </div>
                    <h2>Selamat Datang, Akin! 👋</h2>
                    <p class="mb-0 text-white-50" style="font-size: 0.9rem;">Ringkasan performa & analisis gangguan timbangan PKS hari ini.</p>
                    
                    <div class="row g-3 mt-3">
                        <div class="col-md-3 col-6">
                            <div class="hero-card animate-in">
                                <small class="text-white-50">Total Kendaraan</small>
                                <h3 class="fw-bold my-1">{{ count($logs) }} <small style="font-size: 0.6em; font-weight: 500;">Truk</small></h3>
                                <small style="color: var(--forest-300);"><i class="bi bi-truck me-1"></i>Hari ini</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="hero-card animate-in">
                                <small class="text-white-50">Total Tonase (Netto)</small>
                                <h3 class="fw-bold my-1">{{ number_format(collect($logs)->sum('netto')) }} <small style="font-size: 0.6em; font-weight: 500;">Kg</small></h3>
                                <small style="color: var(--forest-300);"><i class="bi bi-bar-chart-fill me-1"></i>Akumulasi</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="hero-card animate-in">
                                <small class="text-white-50">Timbangan Mati</small>
                                <h3 class="fw-bold my-1 text-warning">1 <small style="font-size: 0.6em; font-weight: 500;">Lokasi</small></h3>
                                <small class="text-warning"><i class="bi bi-exclamation-triangle me-1"></i>Perlu dicek</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="hero-card animate-in">
                                <small class="text-white-50">VPN Trouble</small>
                                <h3 class="fw-bold my-1" style="color: #ff8a8a;">1 <small style="font-size: 0.6em; font-weight: 500;">IP</small></h3>
                                <small style="color: #ff8a8a;"><i class="bi bi-wifi-off me-1"></i>Offline</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart & Activity -->
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card custom-card p-4 animate-in">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h5 class="fw-bold mb-0" style="color: var(--forest-900);">Grafik Est. Tonase Masuk</h5>
                                    <small class="text-muted">Data estimasi harian per lokasi PKS</small>
                                </div>
                                <span class="badge rounded-pill" style="background: var(--forest-50); color: var(--forest-700); font-weight: 600; font-size: 0.75rem; padding: 6px 14px;">
                                    <i class="bi bi-bar-chart-fill me-1"></i> Live
                                </span>
                            </div>
                            <canvas id="salesChart" height="120"></canvas>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card custom-card p-4 problem-card animate-in">
                            <h5 class="fw-bold mb-3" style="color: #ee5a5a;">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>Historical Problem
                            </h5>
                            <div class="problem-item">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="problem-icon bg-warning-subtle text-warning">
                                        <i class="bi bi-lightning-charge-fill"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted fw-bold d-block" style="font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.5px;">Timbangan Sering Mati</small>
                                        <p class="fw-bold text-dark mb-0" style="font-size: 0.92rem;">PKS Sawit Seberang</p>
                                        <small class="text-danger"><i class="bi bi-arrow-repeat me-1"></i>3x Offline minggu ini</small>
                                    </div>
                                </div>
                            </div>
                            <div class="problem-item">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="problem-icon bg-danger-subtle text-danger">
                                        <i class="bi bi-wifi-off"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted fw-bold d-block" style="font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.5px;">VPN Sering Trouble</small>
                                        <p class="fw-bold text-dark mb-0" style="font-size: 0.92rem;">IP: 10.14.2.10 (PKS Pabatu)</p>
                                        <small class="text-danger"><i class="bi bi-clock-history me-1"></i>Sering RTO saat sync SAP</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ===== TAB 2: KELOLA DATA TIMBANGAN ===== -->
            <div class="tab-pane fade" id="tab-timbangan" role="tabpanel">
                <div class="row g-4">
                    <!-- Form Input -->
                    <div class="col-lg-4">
                        <div class="card custom-card p-4 animate-in">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div style="width: 36px; height: 36px; background: var(--forest-50); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--forest-600);">
                                    <i class="bi bi-plus-circle-fill"></i>
                                </div>
                                <h5 class="fw-bold mb-0" style="color: var(--forest-900); font-size: 1rem;">Input Transaksi Timbangan</h5>
                            </div>
                            <form action="{{ route('bridge.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">No. Plat BM</label>
                                    <input type="text" name="no_plat" class="form-control" placeholder="Contoh: BM 8492 AU" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Lokasi PKS</label>
                                    <select name="pks_loc" class="form-select" required>
                                        <option value="PKS Pabatu">PKS Pabatu</option>
                                        <option value="PKS Sawit Seberang">PKS Sawit Seberang</option>
                                        <option value="PKS Bah Jambi">PKS Bah Jambi</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">IP Address VPN</label>
                                    <input type="text" name="vpn_ip" class="form-control" value="10.14.2.10" required>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label">Jam Masuk</label>
                                        <input type="time" name="jam_masuk" class="form-control" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Jam Keluar</label>
                                        <input type="time" name="jam_keluar" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label">Gross (Kg)</label>
                                        <input type="number" name="gross" class="form-control" placeholder="25000" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Tarra (Kg)</label>
                                        <input type="number" name="tarra" class="form-control" placeholder="9000" required>
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
                                    <i class="bi bi-save me-2"></i>Simpan Data
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Tabel Data -->
                    <div class="col-lg-8">
                        <div class="card custom-card p-4 animate-in">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width: 36px; height: 36px; background: var(--forest-50); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--forest-600);">
                                        <i class="bi bi-table"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0" style="color: var(--forest-900); font-size: 1rem;">Data Transaksi & Export SAP</h5>
                                </div>
                                <span class="badge rounded-pill" style="background: var(--forest-50); color: var(--forest-700); font-weight: 600; font-size: 0.75rem; padding: 6px 14px;">
                                    {{ count($logs) }} Record
                                </span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Plat BM</th>
                                            <th>PKS / VPN IP</th>
                                            <th>Jam In/Out</th>
                                            <th>Netto</th>
                                            <th>Alat</th>
                                            <th>VPN</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($logs as $log)
                                            <tr>
                                                <td class="fw-bold">{{ $log['no_plat'] }}</td>
                                                <td>
                                                    <small class="d-block fw-semibold">{{ $log['pks_loc'] }}</small>
                                                    <small class="text-muted" style="font-size: 0.75rem;">{{ $log['vpn_ip'] }}</small>
                                                </td>
                                                <td>
                                                    <small class="d-block"><i class="bi bi-arrow-right-circle text-success me-1"></i>{{ $log['jam_masuk'] }}</small>
                                                    <small class="text-muted"><i class="bi bi-arrow-left-circle text-muted me-1"></i>{{ $log['jam_keluar'] }}</small>
                                                </td>
                                                <td><span class="fw-bold" style="color: var(--forest-600);">{{ number_format($log['netto']) }} Kg</span></td>
                                                <td>
                                                    <span class="badge {{ $log['status_timbangan'] == 'Normal' ? 'badge-ok' : 'badge-trouble' }}">
                                                        {{ $log['status_timbangan'] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $log['status_vpn'] == 'Connected' ? 'badge-ok' : 'badge-trouble' }}">
                                                        {{ $log['status_vpn'] }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('bridge.xml', $log['id']) }}" class="btn btn-sm btn-outline-success me-1" title="Download Config XML SAP" style="border-radius: 8px; font-size: 0.78rem;">
                                                        <i class="bi bi-filetype-xml"></i> XML
                                                    </a>
                                                    <a href="{{ route('bridge.delete', $log['id']) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus data ini?')" style="border-radius: 8px; font-size: 0.78rem;">
                                                        <i class="bi bi-trash3"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-5">
                                                    <div class="text-muted">
                                                        <i class="bi bi-inbox" style="font-size: 2.5rem; color: var(--forest-300);"></i>
                                                        <p class="mt-2 mb-0 fw-semibold">Belum ada data transaksi</p>
                                                        <small>Mulai input data melalui form di samping</small>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ===== SIDEBAR TOGGLE =====
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const toggleBtn = document.getElementById('sidebarToggle');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        });

        // ===== CHART.JS - Premium Forest Theme =====
        const ctx = document.getElementById('salesChart').getContext('2d');
        
        const gradient = ctx.createLinearGradient(0, 0, 0, 280);
        gradient.addColorStop(0, 'rgba(25, 135, 84, 0.75)');
        gradient.addColorStop(0.5, 'rgba(39, 167, 106, 0.4)');
        gradient.addColorStop(1, 'rgba(39, 167, 106, 0.05)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Tonase (Kg)',
                    data: {!! json_encode($data) !!},
                    backgroundColor: gradient,
                    borderColor: 'rgba(15, 81, 50, 0.8)',
                    borderWidth: 1.5,
                    borderRadius: 8,
                    borderSkipped: false,
                    hoverBackgroundColor: 'rgba(25, 135, 84, 0.9)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f5132',
                        titleFont: { family: 'Inter', weight: '600' },
                        bodyFont: { family: 'Inter' },
                        padding: 12,
                        cornerRadius: 10,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Tonase: ' + context.parsed.y.toLocaleString('id-ID') + ' Kg';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.04)',
                            drawBorder: false
                        },
                        ticks: {
                            font: { family: 'Inter', size: 11 },
                            color: '#8a9e93',
                            callback: function(value) {
                                return value.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { family: 'Inter', size: 11, weight: '500' },
                            color: '#5a7066'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>