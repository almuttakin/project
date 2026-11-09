<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="description" content="AutoXML Gateway - Sistem Integrasi Data Jembatan Timbang PKS ke SAP Kantor Pusat PTPN IV Regional III secara Otomatis dan Real-time.">
    <title>AutoXML Gateway · PTPN IV Regional III</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-ptpn4.png') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Fira+Code:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

    <style>
        :root {
            --forest-950: #022011;
            --forest-900: #064e2b;
            --forest-800: #0a6b3a;
            --forest-700: #0f5132;
            --forest-600: #198754;
            --forest-500: #27a76a;
            --forest-400: #47c882;
            --forest-300: #7ddba3;
            --forest-200: #b5eac9;
            --forest-100: #d4f4e0;
            --forest-50:  #ecfbf1;
            --emerald-accent: #10b981;
            --amber-accent: #f59e0b;
            --slate-800: #1e293b;
            --slate-600: #475569;
            --slate-100: #f1f5f9;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 86px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: #1e293b;
            background-color: #f8faf9;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ===== NAVBAR ===== */
        .landing-navbar {
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(15, 81, 50, 0.08);
            transition: all 0.3s ease;
            z-index: 1040;
        }
        .landing-navbar.scrolled {
            box-shadow: 0 10px 30px rgba(6, 78, 43, 0.08);
            background: rgba(255, 255, 255, 0.98);
        }
        .brand-logo-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .brand-logo-wrap img {
            height: 44px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.08));
        }
        .brand-title-wrap {
            display: flex;
            flex-direction: column;
            line-height: 1.15;
        }
        .brand-title-main {
            font-size: 1.08rem;
            font-weight: 800;
            color: var(--forest-900);
            letter-spacing: -0.3px;
        }
        .brand-title-sub {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--forest-600);
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }
        .navbar-nav .nav-link {
            font-size: 0.92rem;
            font-weight: 600;
            color: #334155;
            padding: 8px 16px !important;
            border-radius: 999px;
            transition: all 0.25s ease;
        }
        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link:focus {
            color: var(--forest-700);
            background-color: var(--forest-50);
        }
        .btn-masuk-ti {
            background-color: var(--forest-700);
            color: #ffffff !important;
            font-weight: 700;
            font-size: 0.90rem;
            padding: 9px 22px;
            border-radius: 999px;
            border: 1.5px solid var(--forest-700);
            box-shadow: 0 4px 14px rgba(15, 81, 50, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .btn-masuk-ti:hover {
            background-color: var(--forest-900);
            border-color: var(--forest-900);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(6, 78, 43, 0.3);
            color: #ffffff;
        }

        /* ===== HERO SECTION & SLIDESHOW ===== */
        .hero-section {
            background-color: var(--forest-950);
            position: relative;
            padding-top: 100px;
            padding-bottom: 120px;
            color: #ffffff;
            overflow: hidden;
        }
        .hero-slideshow {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            overflow: hidden;
        }
        .hero-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0;
            visibility: hidden;
            transition: opacity 1.0s ease-in-out;
            will-change: opacity;
        }
        .hero-slide.active {
            opacity: 1;
            visibility: visible;
        }
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(2, 38, 20, 0.85) 0%, rgba(6, 78, 43, 0.80) 50%, rgba(15, 81, 50, 0.85) 100%);
            z-index: 2;
            pointer-events: none;
        }
        .hero-bg-pattern {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: 
                radial-gradient(rgba(255, 255, 255, 0.08) 1.5px, transparent 1.5px),
                radial-gradient(rgba(71, 200, 130, 0.12) 1.5px, transparent 1.5px);
            background-size: 40px 40px;
            background-position: 0 0, 20px 20px;
            opacity: 0.40;
            pointer-events: none;
            z-index: 3;
        }
        .hero-glow-blob {
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(39, 167, 106, 0.20) 0%, rgba(6, 78, 43, 0) 70%);
            top: -150px;
            right: -100px;
            pointer-events: none;
            filter: blur(40px);
            z-index: 3;
        }
        .hero-badge-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.22);
            backdrop-filter: blur(8px);
            padding: 6px 16px;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 24px;
            letter-spacing: 0.3px;
        }
        .hero-title {
            font-size: clamp(2.05rem, 4.2vw, 3.25rem);
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -0.8px;
            color: #ffffff;
            margin-bottom: 22px;
            max-width: 960px;
            margin-left: auto;
            margin-right: auto;
        }
        .hero-desc {
            font-size: clamp(1.0rem, 1.6vw, 1.18rem);
            line-height: 1.68;
            color: rgba(255, 255, 255, 0.88);
            max-width: 820px;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 38px;
            font-weight: 400;
        }
        .hero-btn-primary {
            background: #ffffff;
            color: var(--forest-900) !important;
            font-weight: 700;
            font-size: 1.02rem;
            padding: 14px 32px;
            border-radius: 999px;
            border: 2px solid #ffffff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .hero-btn-primary:hover {
            background-color: var(--forest-50);
            border-color: var(--forest-50);
            transform: translateY(-3px);
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.22);
            color: var(--forest-950) !important;
        }
        .hero-btn-secondary {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff !important;
            font-weight: 600;
            font-size: 1.02rem;
            padding: 14px 28px;
            border-radius: 999px;
            border: 1.5px solid rgba(255, 255, 255, 0.35);
            backdrop-filter: blur(8px);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .hero-btn-secondary:hover {
            background: rgba(255, 255, 255, 0.22);
            border-color: #ffffff;
            transform: translateY(-3px);
            color: #ffffff !important;
        }

        /* ===== STATS CARDS (FLOATING) ===== */
        .stats-floating-wrapper {
            position: relative;
            z-index: 30;
            margin-top: -65px;
            padding-bottom: 20px;
        }
        .stat-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 24px 20px;
            box-shadow: 0 14px 35px rgba(6, 78, 43, 0.08), 0 2px 8px rgba(0,0,0,0.04);
            border: 1px solid rgba(15, 81, 50, 0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 42px rgba(6, 78, 43, 0.13), 0 4px 12px rgba(0,0,0,0.05);
            border-color: var(--forest-300);
        }
        .stat-icon-box {
            width: 58px;
            height: 58px;
            border-radius: 16px;
            background: var(--forest-50);
            color: var(--forest-700);
            font-size: 1.65rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: 1px solid var(--forest-100);
            transition: all 0.3s ease;
        }
        .stat-card:hover .stat-icon-box {
            background: var(--forest-700);
            color: #ffffff;
            transform: scale(1.06);
        }
        .stat-info {
            display: flex;
            flex-direction: column;
        }
        .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.15;
            letter-spacing: -0.5px;
        }
        .stat-label {
            font-size: 0.84rem;
            font-weight: 700;
            color: var(--forest-800);
            margin-top: 3px;
        }
        .stat-subtext {
            font-size: 0.73rem;
            color: #64748b;
        }
        .pulse-indicator {
            display: inline-block;
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background-color: var(--emerald-accent);
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulseGreen 1.8s infinite;
            margin-right: 4px;
        }
        @keyframes pulseGreen {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        /* ===== SECTION STYLING ===== */
        .section-spacer {
            padding-top: 85px;
            padding-bottom: 85px;
        }
        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: var(--forest-700);
            background-color: var(--forest-100);
            padding: 5px 14px;
            border-radius: 999px;
            margin-bottom: 12px;
        }
        .section-title {
            font-size: clamp(1.8rem, 2.7vw, 2.45rem);
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #0f172a;
            line-height: 1.22;
            margin-bottom: 14px;
        }
        .section-desc {
            font-size: 1.02rem;
            color: #475569;
            line-height: 1.65;
        }

        /* ===== TENTANG PERUSAHAAN ===== */
        .perusahaan-text-wrap {
            color: #334155;
            font-size: 1.01rem;
            line-height: 1.74;
        }
        .perusahaan-feature-list {
            list-style: none;
            padding: 0;
            margin: 22px 0 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .perusahaan-feature-list li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 0.94rem;
            font-weight: 500;
            color: #1e293b;
        }
        .perusahaan-feature-list li i {
            font-size: 1.15rem;
            color: var(--forest-600);
            flex-shrink: 0;
            margin-top: 2px;
        }

        /* Kotak Hijau Unit PKS */
        .pks-green-box {
            background: linear-gradient(155deg, var(--forest-900) 0%, var(--forest-700) 60%, var(--forest-800) 100%);
            border-radius: 24px;
            padding: 34px 30px;
            color: #ffffff;
            box-shadow: 0 18px 45px rgba(6, 78, 43, 0.20);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.12);
        }
        .pks-green-box::after {
            content: '';
            position: absolute;
            bottom: -60px;
            right: -60px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.10) 0%, transparent 70%);
            pointer-events: none;
        }
        .pks-box-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            padding-bottom: 16px;
        }
        .pks-box-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
        }
        .pks-box-badge {
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.25);
            font-size: 0.72rem;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 999px;
            color: #ffffff;
            letter-spacing: 0.3px;
        }
        .pks-grid-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        @media (max-width: 576px) {
            .pks-grid-list {
                grid-template-columns: 1fr;
            }
        }
        .pks-unit-pill {
            background: rgba(255, 255, 255, 0.10);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 12px;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.88rem;
            transition: all 0.25s ease;
        }
        .pks-unit-pill:hover {
            background: rgba(255, 255, 255, 0.20);
            transform: translateX(3px);
        }
        .pks-unit-name {
            font-weight: 600;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .pks-unit-name i {
            color: var(--forest-300);
            font-size: 0.95rem;
        }
        .pks-unit-status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #47c882;
            flex-shrink: 0;
        }
        .pks-box-footer-note {
            margin-top: 20px;
            font-size: 0.78rem;
            color: rgba(255, 255, 255, 0.75);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ===== PETA SEBARAN & STATUS KONEKSI ===== */
        .map-section-wrapper {
            background-color: #ffffff;
            border-radius: 24px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }
        #unitMap {
            width: 100%;
            height: 520px;
            border-radius: 20px 0 0 20px;
            z-index: 10;
        }
        @media (max-width: 991px) {
            #unitMap {
                border-radius: 20px 20px 0 0;
                height: 380px;
            }
        }
        .unit-list-panel {
            display: flex;
            flex-direction: column;
            height: 520px;
            background: #ffffff;
        }
        .unit-list-header {
            padding: 18px 20px 14px;
            border-bottom: 1px solid #f1f5f9;
            background: #fcfdfd;
        }
        .search-box-wrap {
            position: relative;
            margin-top: 10px;
        }
        .search-box-wrap i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.95rem;
        }
        .search-box-input {
            width: 100%;
            padding: 9px 14px 9px 38px;
            font-size: 0.85rem;
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            transition: all 0.25s ease;
        }
        .search-box-input:focus {
            outline: none;
            border-color: var(--forest-500);
            box-shadow: 0 0 0 3px rgba(39, 167, 106, 0.15);
        }
        .unit-list-body {
            flex: 1;
            overflow-y: auto;
            padding: 12px 14px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .unit-list-body::-webkit-scrollbar {
            width: 6px;
        }
        .unit-list-body::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 10px;
        }
        .unit-card-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 12px 14px;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .unit-card-item:hover,
        .unit-card-item.active {
            background: var(--forest-50);
            border-color: var(--forest-400);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(15, 81, 50, 0.08);
        }
        .unit-item-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 6px;
        }
        .unit-item-name {
            font-size: 0.92rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }
        .unit-item-loc {
            font-size: 0.75rem;
            color: #64748b;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .unit-status-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            align-items: center;
        }
        .status-pill {
            font-size: 0.68rem;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            letter-spacing: 0.2px;
        }
        .status-pill-online {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }
        .status-pill-trouble {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
        .unit-ip-meta {
            font-family: 'Fira Code', monospace;
            font-size: 0.68rem;
            color: #64748b;
            margin-top: 6px;
        }
        .map-legend-bar {
            padding: 12px 20px;
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            font-size: 0.80rem;
        }
        .legend-items {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }
        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }

        /* Leaflet Custom Marker Pins */
        .leaflet-marker-hq {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            background: #f59e0b;
            color: #ffffff;
            border-radius: 50%;
            border: 3px solid #ffffff;
            box-shadow: 0 4px 14px rgba(245, 158, 11, 0.45);
            font-size: 1.15rem;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        .leaflet-marker-hq:hover {
            transform: scale(1.15);
        }
        .leaflet-marker-pks {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            background: var(--forest-600);
            color: #ffffff;
            border-radius: 50%;
            border: 2.5px solid #ffffff;
            box-shadow: 0 4px 12px rgba(25, 135, 84, 0.35);
            font-size: 0.95rem;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        .leaflet-marker-pks:hover {
            transform: scale(1.18);
        }
        .leaflet-marker-pks.trouble {
            background: #dc2626;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.35);
        }

        /* ===== TENTANG PROYEK & WORKFLOW CARDS ===== */
        .proyek-section {
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
        }
        .workflow-card {
            background: #ffffff;
            border-radius: 22px;
            padding: 34px 26px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            border: 1px solid #e2e8f0;
            position: relative;
            height: 100%;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }
        .workflow-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(6, 78, 43, 0.10);
            border-color: var(--forest-400);
        }
        .workflow-step-num {
            position: absolute;
            top: 22px;
            right: 24px;
            font-size: 2.1rem;
            font-weight: 800;
            color: rgba(15, 81, 50, 0.12);
            font-family: 'Fira Code', monospace;
            line-height: 1;
            transition: color 0.3s ease;
        }
        .workflow-card:hover .workflow-step-num {
            color: var(--forest-500);
        }
        .workflow-icon-wrap {
            width: 62px;
            height: 62px;
            border-radius: 18px;
            background: linear-gradient(135deg, var(--forest-700), var(--forest-500));
            color: #ffffff;
            font-size: 1.65rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            box-shadow: 0 8px 18px rgba(25, 135, 84, 0.25);
            transition: all 0.3s ease;
        }
        .workflow-card:hover .workflow-icon-wrap {
            transform: scale(1.08) rotate(3deg);
        }
        .workflow-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 14px;
        }
        .workflow-desc {
            font-size: 0.92rem;
            color: #475569;
            line-height: 1.68;
            margin-bottom: 20px;
            flex: 1;
        }
        .workflow-feature-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.74rem;
            font-weight: 600;
            color: var(--forest-800);
            background: var(--forest-50);
            border: 1px solid var(--forest-200);
            padding: 5px 12px;
            border-radius: 999px;
        }

        /* ===== FOOTER ===== */
        .landing-footer {
            background-color: var(--forest-950);
            color: #94a3b8;
            padding-top: 56px;
            padding-bottom: 36px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 0.88rem;
        }
        .footer-brand-title {
            color: #ffffff;
            font-size: 1.15rem;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.3px;
        }
        .footer-badge-univ {
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 14px;
            padding: 16px 20px;
            color: #e2e8f0;
        }
        .footer-univ-title {
            font-size: 0.96rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 4px;
        }
        .footer-univ-sub {
            font-size: 0.82rem;
            color: var(--forest-300);
            margin: 0;
        }
        .footer-divider {
            border-color: rgba(255, 255, 255, 0.10);
            margin: 36px 0 24px;
        }
        .scroll-top-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            transition: all 0.25s ease;
            text-decoration: none;
        }
        .scroll-top-btn:hover {
            background: var(--forest-600);
            color: #ffffff;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>

    <!-- ================================================================= -->
    <!-- 1. NAVBAR                                                         -->
    <!-- ================================================================= -->
    <nav class="navbar navbar-expand-lg landing-navbar sticky-top" id="mainNavbar">
        <div class="container">
            <!-- Brand Left: Logo + Nama Aplikasi -->
            <a class="brand-logo-wrap" href="{{ route('home') }}">
                <img src="{{ asset('images/logo-ptpn4.png') }}" alt="Logo PTPN IV" height="44">
                <div class="brand-title-wrap">
                    <span class="brand-title-main">AutoXML Gateway</span>
                    <span class="brand-title-sub">PTPN IV Regional III</span>
                </div>
            </a>

            <!-- Mobile Hamburger Toggle -->
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list fs-1 text-success"></i>
            </button>

            <!-- Menu Navigation & Tombol Masuk TI -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-1">
                    <li class="nav-item">
                        <a class="nav-link active" href="#beranda">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tentang-perusahaan">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#peta-unit">Peta Unit</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tentang-proyek">Tentang Proyek</a>
                    </li>
                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                        <a href="{{ Route::has('login') ? route('login') : url('/login') }}" class="btn-masuk-ti">
                            <i class="bi bi-shield-lock-fill"></i>
                            <span>Masuk Admin TI</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ================================================================= -->
    <!-- 2. HERO SECTION                                                   -->
    <!-- ================================================================= -->
    <section class="hero-section text-center position-relative" id="beranda">
        <!-- Slideshow Background Layers -->
        <div class="hero-slideshow">
            @php
                $heroFiles = glob(public_path('images/hero/hero-*.{jpg,jpeg,png,webp}'), GLOB_BRACE);
                if (empty($heroFiles)) {
                    $heroFiles = [public_path('images/hero/hero-1.jpg')];
                }
                natsort($heroFiles);
            @endphp
            @foreach($heroFiles as $filePath)
                @php $fileName = basename($filePath); @endphp
                <div class="hero-slide {{ $loop->first ? 'active' : '' }}" style="background-image: url('{{ asset('images/hero/' . $fileName) }}');"></div>
            @endforeach
        </div>

        <!-- Dark Green Overlay & Decorative Background -->
        <div class="hero-overlay"></div>
        <div class="hero-bg-pattern"></div>
        <div class="hero-glow-blob"></div>

        <div class="container position-relative" style="z-index: 5;">
            <!-- Badge Tagline -->
            <div class="hero-badge-tag">
                <i class="bi bi-shield-check text-warning fs-6"></i>
                <span>Sistem Gateway Jembatan Timbang · PTPN IV Regional III</span>
            </div>

            <!-- Judul Besar Hero -->
            <h1 class="hero-title">
                Integrasi Data Timbangan PKS Menuju Sistem SAP Kantor Pusat Secara Otomatis
            </h1>

            <!-- Deskripsi Singkat -->
            <p class="hero-desc">
                Solusi jembatan data cerdas yang mentransformasi data penimbangan kelapa sawit secara otomatis menjadi dokumen konfigurasi XML tervalidasi, mengintegrasikan stasiun timbangan unit PKS langsung ke sistem SAP ERP Kantor Pusat Regional III dengan aman, presisi, dan real-time.
            </p>

            <!-- Dua Tombol Aksi -->
            <div class="d-flex flex-wrap justify-content-center align-items-center gap-3">
                <a href="{{ Route::has('login') ? route('login') : url('/login') }}" class="hero-btn-primary">
                    <span>Masuk sebagai Admin TI</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
                <a href="#tentang-proyek" class="hero-btn-secondary">
                    <i class="bi bi-info-circle"></i>
                    <span>Pelajari Proyek Ini</span>
                </a>
            </div>
        </div>
    </section>

    <!-- ================================================================= -->
    <!-- 3. KARTU STATISTIK (MELAYANG DI ATAS HERO)                        -->
    <!-- ================================================================= -->
    <div class="container stats-floating-wrapper">
        <div class="row g-3 g-md-4">
            <!-- Kartu 1: Jumlah Unit PKS -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon-box">
                        <i class="bi bi-buildings"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value">{{ $stats['total_unit'] ?? '12' }} <span class="fs-6 fw-bold text-muted">Unit</span></div>
                        <div class="stat-label">Jumlah Unit PKS</div>
                        <div class="stat-subtext">PKS Aktif Terintegrasi</div>
                    </div>
                </div>
            </div>

            <!-- Kartu 2: Titik PC Timbangan -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon-box">
                        <i class="bi bi-pc-display-horizontal"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value">{{ $stats['total_pc'] ?? '24' }} <span class="fs-6 fw-bold text-muted">Titik</span></div>
                        <div class="stat-label">PC Timbangan</div>
                        <div class="stat-subtext">Dual Weighbridge System</div>
                    </div>
                </div>
            </div>

            <!-- Kartu 3: Kantor Pusat -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon-box">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value">{{ $stats['total_kantor_pusat'] ?? '1' }} <span class="fs-6 fw-bold text-muted">Pusat</span></div>
                        <div class="stat-label">Kantor Pusat Regional</div>
                        <div class="stat-subtext">Pekanbaru, Riau</div>
                    </div>
                </div>
            </div>

            <!-- Kartu 4: Status Monitoring -->
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon-box">
                        <i class="bi bi-activity text-success"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-value d-flex align-items-center">
                            <span class="pulse-indicator"></span>
                            <span>{{ $stats['status_monitoring'] ?? '24/7' }}</span>
                        </div>
                        <div class="stat-label">Status Monitoring</div>
                        <div class="stat-subtext">Real-time Gateway Online</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ================================================================= -->
    <!-- 4. BAGIAN TENTANG PERUSAHAAN (DUA KOLOM)                          -->
    <!-- ================================================================= -->
    <section class="section-spacer" id="tentang-perusahaan">
        <div class="container">
            <div class="row align-items-center g-4 g-lg-5">
                <!-- Kolom Kiri: Deskripsi Singkat Perusahaan -->
                <div class="col-lg-6">
                    <div class="section-badge">
                        <i class="bi bi-building-check"></i>
                        <span>Tentang Perusahaan</span>
                    </div>
                    <h2 class="section-title">
                        PT Perkebunan Nusantara IV (PTPN IV) Regional III
                    </h2>
                    <div class="perusahaan-text-wrap">
                        <p class="mb-3">
                            <strong>PT Perkebunan Nusantara IV</strong> adalah Badan Usaha Milik Negara (BUMN) terdepan di Indonesia yang mengkhususkan diri pada pengelolaan agribisnis kelapa sawit dan karet berkelanjutan. Di bawah naungan Subholding PalmCo, PTPN IV mengemban mandat nasional dalam hilirisasi dan digitalisasi rantai pasok kelapa sawit Indonesia.
                        </p>
                        <p class="mb-3">
                            Di wilayah <strong>Regional III</strong>, operasional pabrik kelapa sawit (PKS) memproses jutaan ton Tandan Buah Segar (TBS) setiap tahunnya. Kecepatan dan integritas data penerimaan buah di jembatan timbang menjadi faktor penentu dalam menjaga akurasi perhitungan rendemen, efisiensi operasional pabrik, serta pelaporan keuangan korporasi.
                        </p>
                        <ul class="perusahaan-feature-list">
                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Otomasi penuh pengiriman data timbangan mentah langsung ke sistem SAP ERP pusat.</span>
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Menghilangkan celah manipulasi data dan selisih tonase (discrepancy) secara drastis.</span>
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Infrastruktur jaringan handal dengan dual-failover VPN Korporat dan WiFi IP Gateway.</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Kolom Kanan: Kotak Hijau Daftar Unit PKS Terhubung -->
                <div class="col-lg-6">
                    <div class="pks-green-box">
                        <div class="pks-box-header">
                            <div>
                                <h3 class="pks-box-title">
                                    <i class="bi bi-hdd-network me-2 text-warning"></i>Unit PKS Terhubung
                                </h3>
                                <small class="text-white-50">Jaringan AutoXML Gateway Regional III</small>
                            </div>
                            <span class="pks-box-badge">
                                {{ $units->count() }} PKS Aktif
                            </span>
                        </div>

                        <!-- Grid Daftar Unit PKS -->
                        <div class="pks-grid-list">
                            @foreach($units as $unit)
                                <div class="pks-unit-pill">
                                    <span class="pks-unit-name">
                                        <i class="bi bi-patch-check-fill"></i>
                                        <span>{{ $unit->nama_unit }}</span>
                                    </span>
                                    <span class="pks-unit-status-dot" title="Status Online" style="{{ ($unit->status_vpn ?? 'Online') === 'Trouble' ? 'background-color: #f87171;' : '' }}"></span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Catatan Kaki Kotak Hijau -->
                        <div class="pks-box-footer-note">
                            <i class="bi bi-shield-lock-fill text-warning"></i>
                            <span>Seluruh unit PKS tersambung melalui enkripsi Private VPN & Subnet Terisolasi.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================================================================= -->
    <!-- 5. BAGIAN PETA SEBARAN UNIT PKS & KANTOR PUSAT REGIONAL            -->
    <!-- ================================================================= -->
    <section class="section-spacer bg-white" id="peta-unit">
        <div class="container">
            <div class="row mb-4">
                <div class="col-lg-8 mx-auto text-center">
                    <div class="section-badge">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>Pemetaan Geografis & Monitoring</span>
                    </div>
                    <h2 class="section-title">
                        Peta Sebaran Unit PKS & Kantor Pusat Regional
                    </h2>
                    <p class="section-desc">
                        Visualisasi interaktif sebaran stasiun jembatan timbang Pabrik Kelapa Sawit (PKS) dan Kantor Pusat Regional III Pekanbaru yang terhubung secara real-time ke sistem AutoXML Gateway.
                    </p>
                </div>
            </div>

            <!-- Wrapper Peta dan Daftar Unit -->
            <div class="map-section-wrapper">
                <div class="row g-0">
                    <!-- Kolom Peta Leaflet.js -->
                    <div class="col-lg-7 col-xl-8">
                        <div id="unitMap"></div>
                        <div class="map-legend-bar">
                            <div class="legend-items">
                                <span><span class="legend-dot" style="background-color: #f59e0b;"></span>Kantor Pusat Regional</span>
                                <span><span class="legend-dot" style="background-color: #198754;"></span>PKS Jaringan Normal (Online)</span>
                                <span><span class="legend-dot" style="background-color: #dc2626;"></span>PKS Kendala Jaringan (Trouble)</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="resetMapView()">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>Reset Tampilan Peta
                            </button>
                        </div>
                    </div>

                    <!-- Kolom Daftar Unit PKS beserta Status Koneksi -->
                    <div class="col-lg-5 col-xl-4 border-start">
                        <div class="unit-list-panel">
                            <div class="unit-list-header">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="fw-bold mb-0 text-dark">
                                        <i class="bi bi-router-fill me-1 text-success"></i>Status Koneksi Unit
                                    </h6>
                                    <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1" id="unitCountBadge">
                                        {{ $units->count() }} Unit
                                    </span>
                                </div>
                                <div class="search-box-wrap">
                                    <i class="bi bi-search"></i>
                                    <input type="text" class="search-box-input" id="unitSearchInput" placeholder="Cari nama unit PKS atau lokasi..." onkeyup="filterUnits()">
                                </div>
                            </div>

                            <!-- Daftar Scrollable Unit -->
                            <div class="unit-list-body" id="unitListContainer">
                                @foreach($units as $idx => $u)
                                    <div class="unit-card-item" id="unit-card-{{ $idx }}" onclick="focusMarker({{ $idx }})">
                                        <div class="unit-item-top">
                                            <h6 class="unit-item-name">{{ $u->nama_unit }}</h6>
                                            <div class="d-flex gap-1">
                                                <!-- Badge VPN -->
                                                @if(strtolower($u->status_vpn ?? 'online') === 'online')
                                                    <span class="status-pill status-pill-online" title="VPN Online">
                                                        <i class="bi bi-shield-check"></i> VPN OK
                                                    </span>
                                                @else
                                                    <span class="status-pill status-pill-trouble" title="VPN Trouble">
                                                        <i class="bi bi-exclamation-triangle"></i> VPN Trouble
                                                    </span>
                                                @endif

                                                <!-- Badge WiFi -->
                                                @if(strtolower($u->status_wifi ?? 'online') === 'online')
                                                    <span class="status-pill status-pill-online" title="WiFi Online">
                                                        <i class="bi bi-wifi"></i> WiFi OK
                                                    </span>
                                                @else
                                                    <span class="status-pill status-pill-trouble" title="WiFi Trouble">
                                                        <i class="bi bi-wifi-off"></i> WiFi Trouble
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="unit-item-loc">
                                            <i class="bi bi-geo-alt"></i>
                                            <span>{{ $u->lokasi }}</span>
                                        </div>

                                        <div class="unit-ip-meta d-flex justify-content-between align-items-center">
                                            <span>VPN: <code>{{ $u->ip_vpn ?? '10.14.x.x' }}</code></span>
                                            <span>WiFi: <code>{{ $u->ip_wifi ?? '192.168.x.x' }}</code></span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================================================================= -->
    <!-- 6. BAGIAN TENTANG PROYEK (AUTO GENERATE CONFIG XML)                -->
    <!-- ================================================================= -->
    <section class="section-spacer proyek-section" id="tentang-proyek">
        <div class="container">
            <div class="row mb-5 text-center">
                <div class="col-lg-8 mx-auto">
                    <div class="section-badge">
                        <i class="bi bi-cpu-fill"></i>
                        <span>Arsitektur & Solusi Sistem</span>
                    </div>
                    <h2 class="section-title">
                        Auto Generate Config XML
                    </h2>
                    <p class="section-desc">
                        Sistem jembatan integrasi enterprise yang mengotomatisasi konversi data penimbangan mentah dari timbangan digital menjadi format file XML terstandar SAP, memvalidasi kepatuhan format data, dan mengirimkannya ke pusat secara aman.
                    </p>
                </div>
            </div>

            <!-- 3 Kartu Alur Kerja (Workflow) -->
            <div class="row g-4">
                <!-- Kartu 01: Terima Data Timbangan -->
                <div class="col-lg-4">
                    <div class="workflow-card">
                        <span class="workflow-step-num">01</span>
                        <div class="workflow-icon-wrap">
                            <i class="bi bi-truck"></i>
                        </div>
                        <h4 class="workflow-title">Terima Data Timbangan</h4>
                        <p class="workflow-desc">
                            PC stasiun jembatan timbang di setiap PKS menerima pembacaan penimbangan bruto, tarra, dan netto truk TBS secara otomatis dari indikator timbangan digital tanpa memerlukan input manual berulang dari petugas timbang.
                        </p>
                        <div>
                            <span class="workflow-feature-pill">
                                <i class="bi bi-shield-check text-success"></i> Penimbangan Otomatis
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Kartu 02: Auto Generate XML -->
                <div class="col-lg-4">
                    <div class="workflow-card">
                        <span class="workflow-step-num">02</span>
                        <div class="workflow-icon-wrap" style="background: linear-gradient(135deg, #0a6b3a, #10b981);">
                            <i class="bi bi-filetype-xml"></i>
                        </div>
                        <h4 class="workflow-title">Auto Generate XML</h4>
                        <p class="workflow-desc">
                            Engine AutoXML Gateway langsung memvalidasi integritas data tiket timbang dan merender struktur dokumen konfigurasi XML standar SAP ERP secara instan dengan timestamp presisi dan pengkodean aman.
                        </p>
                        <div>
                            <span class="workflow-feature-pill">
                                <i class="bi bi-code-slash text-success"></i> Standar Skema SAP ERP
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Kartu 03: Kirim & Pantau ke SAP -->
                <div class="col-lg-4">
                    <div class="workflow-card">
                        <span class="workflow-step-num">03</span>
                        <div class="workflow-icon-wrap" style="background: linear-gradient(135deg, #022011, #0a6b3a);">
                            <i class="bi bi-cloud-arrow-up-fill"></i>
                        </div>
                        <h4 class="workflow-title">Kirim & Pantau ke SAP</h4>
                        <p class="workflow-desc">
                            File XML dikirim melalui jaringan aman VPN/WiFi menuju server SAP Kantor Pusat Regional III. Status pengiriman dipantau real-time 24/7 dengan notifikasi kegagalan dan audit trail yang lengkap.
                        </p>
                        <div>
                            <span class="workflow-feature-pill">
                                <i class="bi bi-broadcast text-success"></i> Monitoring Real-Time 24/7
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Callout CTA di bawah workflow -->
            <div class="mt-5 p-4 rounded-4 bg-white border d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 shadow-sm">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon-box bg-success-subtle text-success">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">Portal Khusus Administrator TI PTPN IV</h6>
                        <small class="text-muted">Masuk ke modul dashboard untuk mengelola konfigurasi XML, monitor status push SAP, dan manajemen transaksi timbangan.</small>
                    </div>
                </div>
                <a href="{{ Route::has('login') ? route('login') : url('/login') }}" class="btn btn-masuk-ti text-nowrap px-4 py-2">
                    <span>Akses Portal Admin TI</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- ================================================================= -->
    <!-- 7. FOOTER                                                         -->
    <!-- ================================================================= -->
    <footer class="landing-footer">
        <div class="container">
            <div class="row g-4 align-items-center">
                <!-- Kiri: Logo + Nama Aplikasi -->
                <div class="col-lg-6">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img src="{{ asset('images/logo-ptpn4.png') }}" alt="Logo PTPN IV" height="42" class="bg-white p-1 rounded-2">
                        <div>
                            <h5 class="footer-brand-title">AutoXML Gateway</h5>
                            <span class="small text-white-50">PT Perkebunan Nusantara IV (Persero) · Regional III</span>
                        </div>
                    </div>
                    <p class="small text-white-50 mb-0" style="max-width: 480px;">
                        Platform jembatan otomasi integrasi data jembatan timbang Pabrik Kelapa Sawit (PKS) menuju sistem SAP ERP Kantor Pusat secara real-time, akurat, dan terenkripsi.
                    </p>
                </div>

                <!-- Kanan: Info Tugas Akhir Universitas Riau -->
                <div class="col-lg-6">
                    <div class="footer-badge-univ d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3">
                        <div>
                            <div class="footer-univ-title">
                                <i class="bi bi-mortarboard-fill me-2 text-warning"></i>Tugas Akhir Mahasiswa
                            </div>
                            <p class="footer-univ-sub">
                                Program Studi D3 Manajemen Informatika · Universitas Riau
                            </p>
                        </div>
                        <a href="#beranda" class="scroll-top-btn" title="Kembali ke atas">
                            <i class="bi bi-arrow-up"></i>
                        </a>
                    </div>
                </div>
            </div>

            <hr class="footer-divider">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 small text-white-50">
                <div>
                    &copy; {{ date('Y') }} PT Perkebunan Nusantara IV (Persero) Regional III. Seluruh hak cipta dilindungi.
                </div>
                <div class="d-flex gap-3">
                    <a href="#beranda" class="text-white-50 text-decoration-none">Beranda</a>
                    <span>·</span>
                    <a href="#tentang-perusahaan" class="text-white-50 text-decoration-none">Tentang Perusahaan</a>
                    <span>·</span>
                    <a href="#peta-unit" class="text-white-50 text-decoration-none">Peta Unit</a>
                    <span>·</span>
                    <a href="#tentang-proyek" class="text-white-50 text-decoration-none">Tentang Proyek</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Map & Interaction Scripts -->
    <script>
        // Data Unit PKS & Kantor Pusat dari Backend Laravel
        const rawUnits = @json($units);
        const kantorPusat = @json($kantorPusat);

        // Navbar shadow on scroll
        window.addEventListener('scroll', function () {
            const navbar = document.getElementById('mainNavbar');
            if (window.scrollY > 30) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Inisialisasi Peta Leaflet
        let map;
        const markers = [];
        const defaultCenter = [1.3, 100.4];
        const defaultZoom = 7;

        function initMap() {
            // Buat Leaflet Map berpusat di wilayah Riau / Sumut
            map = L.map('unitMap', {
                center: defaultCenter,
                zoom: defaultZoom,
                scrollWheelZoom: false
            });

            // Tile Layer OpenStreetMap Standard
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // 1. Marker Kantor Pusat Regional III
            if (kantorPusat && kantorPusat.lat && kantorPusat.lng) {
                const hqIcon = L.divIcon({
                    className: 'custom-hq-marker',
                    html: '<div class="leaflet-marker-hq" title="Kantor Pusat Regional III"><i class="bi bi-buildings-fill"></i></div>',
                    iconSize: [38, 38],
                    iconAnchor: [19, 19],
                    popupAnchor: [0, -20]
                });

                const hqPopup = `
                    <div style="min-width: 220px; font-family: 'Inter', sans-serif;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                            <span style="background: #fef3c7; color: #b45309; padding: 3px 8px; border-radius: 999px; font-size: 0.72rem; font-weight: 700;">HEADQUARTERS</span>
                        </div>
                        <h6 style="font-weight: 800; margin: 0 0 4px; color: #0f172a; font-size: 0.95rem;">${kantorPusat.nama}</h6>
                        <p style="font-size: 0.78rem; color: #64748b; margin-bottom: 8px;"><i class="bi bi-geo-alt"></i> ${kantorPusat.lokasi}</p>
                        <div style="font-size: 0.74rem; background: #f8fafc; padding: 6px 10px; border-radius: 8px; font-family: 'Fira Code', monospace; color: #334155;">
                            Core IP: <strong>${kantorPusat.ip_core}</strong> · ${kantorPusat.status}
                        </div>
                    </div>
                `;

                L.marker([kantorPusat.lat, kantorPusat.lng], { icon: hqIcon })
                    .addTo(map)
                    .bindPopup(hqPopup);
            }

            // 2. Markers untuk Unit-Unit PKS
            rawUnits.forEach((unit, index) => {
                const isTrouble = (unit.status_vpn || '').toLowerCase() === 'trouble' || (unit.status_wifi || '').toLowerCase() === 'trouble';
                const markerClass = isTrouble ? 'leaflet-marker-pks trouble' : 'leaflet-marker-pks';
                const iconSymbol = isTrouble ? '<i class="bi bi-exclamation-triangle-fill"></i>' : '<i class="bi bi-hdd-network-fill"></i>';

                const pksIcon = L.divIcon({
                    className: 'custom-pks-marker',
                    html: `<div class="${markerClass}" title="${unit.nama_unit}">${iconSymbol}</div>`,
                    iconSize: [32, 32],
                    iconAnchor: [16, 16],
                    popupAnchor: [0, -18]
                });

                const vpnBadge = (unit.status_vpn || 'Online').toLowerCase() === 'online'
                    ? '<span style="background: #dcfce7; color: #15803d; padding: 2px 8px; border-radius: 999px; font-size: 0.70rem; font-weight: 700;">VPN Online</span>'
                    : '<span style="background: #fee2e2; color: #b91c1c; padding: 2px 8px; border-radius: 999px; font-size: 0.70rem; font-weight: 700;">VPN Trouble</span>';

                const wifiBadge = (unit.status_wifi || 'Online').toLowerCase() === 'online'
                    ? '<span style="background: #dcfce7; color: #15803d; padding: 2px 8px; border-radius: 999px; font-size: 0.70rem; font-weight: 700;">WiFi Online</span>'
                    : '<span style="background: #fee2e2; color: #b91c1c; padding: 2px 8px; border-radius: 999px; font-size: 0.70rem; font-weight: 700;">WiFi Trouble</span>';

                const popupHtml = `
                    <div style="min-width: 230px; font-family: 'Inter', sans-serif;">
                        <div style="display: flex; gap: 6px; margin-bottom: 6px;">
                            ${vpnBadge}
                            ${wifiBadge}
                        </div>
                        <h6 style="font-weight: 800; margin: 0 0 4px; color: #0f172a; font-size: 0.95rem;">${unit.nama_unit}</h6>
                        <p style="font-size: 0.78rem; color: #64748b; margin-bottom: 8px;"><i class="bi bi-geo-alt"></i> ${unit.lokasi}</p>
                        <div style="background: #f8fafc; padding: 6px 10px; border-radius: 8px; font-family: 'Fira Code', monospace; font-size: 0.72rem; color: #334155;">
                            <div>VPN: <strong>${unit.ip_vpn || '-'}</strong></div>
                            <div>WiFi: <strong>${unit.ip_wifi || '-'}</strong></div>
                        </div>
                    </div>
                `;

                if (unit.lat && unit.lng) {
                    const marker = L.marker([unit.lat, unit.lng], { icon: pksIcon })
                        .addTo(map)
                        .bindPopup(popupHtml);

                    markers.push({
                        id: index,
                        marker: marker,
                        lat: unit.lat,
                        lng: unit.lng,
                        name: unit.nama_unit
                    });

                    // Event marker click highlights sidebar card
                    marker.on('click', function () {
                        highlightSidebarCard(index);
                    });
                }
            });
        }

        // Fokus ke Marker saat item di sidebar list diklik
        function focusMarker(index) {
            const found = markers.find(m => m.id === index);
            if (found && map) {
                map.flyTo([found.lat, found.lng], 11, {
                    animate: true,
                    duration: 1.2
                });
                found.marker.openPopup();
                highlightSidebarCard(index);
            }
        }

        // Highlight card yang sedang aktif
        function highlightSidebarCard(index) {
            document.querySelectorAll('.unit-card-item').forEach(el => el.classList.remove('active'));
            const card = document.getElementById('unit-card-' + index);
            if (card) {
                card.classList.add('active');
                card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }

        // Reset peta ke tampilan awal
        function resetMapView() {
            if (map) {
                map.flyTo(defaultCenter, defaultZoom, {
                    animate: true,
                    duration: 1.0
                });
                document.querySelectorAll('.unit-card-item').forEach(el => el.classList.remove('active'));
            }
        }

        // Live filter nama unit pada search box
        function filterUnits() {
            const query = document.getElementById('unitSearchInput').value.toLowerCase();
            const cards = document.querySelectorAll('.unit-card-item');
            let visibleCount = 0;

            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                if (text.includes(query)) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            const badge = document.getElementById('unitCountBadge');
            if (badge) {
                badge.textContent = visibleCount + ' Unit';
            }
        }

        // Auto Slideshow Hero Background (ganti setiap 10 detik dengan transisi fade halus)
        function initHeroSlideshow() {
            const slides = document.querySelectorAll('.hero-slide');
            if (slides.length > 1) {
                let current = 0;
                setInterval(function () {
                    slides[current].classList.remove('active');
                    current = (current + 1) % slides.length;
                    slides[current].classList.add('active');
                }, 10000); // 10000 ms = 10 detik
            }
        }

        // Jalankan saat dokumen siap
        document.addEventListener('DOMContentLoaded', function () {
            initHeroSlideshow();
            initMap();
        });
    </script>
</body>
</html>

