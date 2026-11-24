{{-- Kerangka utama untuk halaman publik dan halaman aplikasi Bootstrap. --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PARKING POINT - @yield('title', 'Dashboard')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #0a2540;
            --primary-dark: #061a30;
            --primary-light: #1e3a5f;
            --accent: #4a90e2;
            --bg-light: #f4f6f9;
            --text-dark: #2c3e50;
        }

        * { font-family: 'Poppins', sans-serif; }

        body {
            background: var(--bg-light);
            color: var(--text-dark);
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            background: linear-gradient(180deg, var(--primary) 0%, var(--primary-dark) 100%);
            min-height: 100vh;
            width: 260px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-brand {
            padding: 25px 20px;
            color: #fff;
            font-size: 1.4rem;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-user {
            padding: 20px;
            color: #fff;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }

        .sidebar-user .avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0 auto 10px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 15px 0;
            margin: 0;
        }

        .sidebar-menu .menu-label {
            color: rgba(255,255,255,0.5);
            font-size: 0.75rem;
            text-transform: uppercase;
            padding: 10px 20px 5px;
            letter-spacing: 1px;
        }

        .sidebar-menu a {
            color: rgba(255,255,255,0.85);
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.08);
            color: #fff;
            border-left-color: var(--accent);
        }

        .sidebar-menu a i { font-size: 1.1rem; width: 20px; }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            transition: all 0.3s;
        }

        /* ===== NAVBAR ===== */
        .top-navbar {
            background: #fff;
            padding: 15px 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .top-navbar .page-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary);
            margin: 0;
        }

        /* ===== CARDS ===== */
        .card-custom {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(10,37,64,0.08);
            transition: transform 0.2s;
        }

        .card-custom:hover { transform: translateY(-2px); }

        .stat-card {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: #fff;
            border-radius: 12px;
            padding: 25px;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 120px;
            height: 120px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .stat-card .stat-icon {
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }

        .stat-card .stat-label {
            opacity: 0.85;
            font-size: 0.9rem;
        }

        /* ===== BUTTONS ===== */
        .btn-primary-custom {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        .btn-primary-custom:hover {
            background: var(--primary-light);
            border-color: var(--primary-light);
            color: #fff;
        }

        /* ===== TABLE ===== */
        .table-custom thead {
            background: var(--primary);
            color: #fff;
        }

        .table-custom thead th {
            font-weight: 500;
            padding: 12px;
            border: none;
        }

        .table-custom tbody td {
            padding: 12px;
            vertical-align: middle;
        }

        /* ===== BADGES ===== */
        .badge-soft {
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.8rem;
        }

        /* ===== LANDING ===== */
        .hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: #fff;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 500px; height: 500px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
            transform: translate(30%, -30%);
        }

        .parking-landing { --ink: #10251f; --muted: #66736d; --paper: #f5f7f2; --lime: #c9f36b; background: var(--paper); color: var(--ink); min-height: 100vh; font-family: 'Poppins', sans-serif; }
        .landing-nav, .landing-hero, .feature-section, .trust-strip, .landing-footer { max-width: 1180px; margin-inline: auto; }
        .landing-nav { display: flex; align-items: center; justify-content: space-between; padding: 28px 28px 18px; }
        .brand-mark { color: var(--ink); display: flex; align-items: center; gap: 10px; font-weight: 600; letter-spacing: .04em; text-decoration: none; font-size: .85rem; }
        .brand-mark strong { font-weight: 800; }.brand-icon { width: 34px; height: 34px; display: grid; place-items: center; border-radius: 10px; background: var(--ink); color: var(--lime); font-size: 1.2rem; }
        .landing-login { color: var(--ink); text-decoration: none; font-weight: 600; font-size: .85rem; border-bottom: 1px solid var(--ink); padding-bottom: 4px; }
        .landing-hero { display: grid; grid-template-columns: 1fr 1fr; gap: 58px; align-items: center; padding: 80px 28px 110px; }
        .eyebrow { color: #63716a; font-size: .72rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; display: flex; align-items: center; gap: 9px; margin-bottom: 22px; }.eyebrow span { width: 8px; height: 8px; border-radius: 50%; background: #7cae2b; }
        .hero-copy h1 { font-size: clamp(2.7rem, 6vw, 5.3rem); line-height: .98; letter-spacing: -.06em; margin: 0 0 25px; font-weight: 700; }.hero-copy h1 em, .section-heading h2 em { color: #71952e; font-style: normal; }
        .hero-description { color: var(--muted); max-width: 430px; line-height: 1.8; font-size: .98rem; margin-bottom: 28px; }.hero-actions { display: flex; gap: 12px; flex-wrap: wrap; }.landing-button { border-radius: 8px; display: inline-flex; align-items: center; gap: 12px; padding: 13px 19px; text-decoration: none; font-weight: 600; font-size: .82rem; transition: transform .2s, box-shadow .2s; }.landing-button:hover { transform: translateY(-2px); }.landing-button-primary { background: var(--ink); color: white; box-shadow: 0 9px 20px rgba(16,37,31,.15); }.landing-button-ghost { border: 1px solid #ccd3c9; color: var(--ink); }.hero-note { color: #7b877f; font-size: .73rem; margin-top: 25px; }.hero-note i { color: #7cae2b; margin-right: 6px; }
        .hero-preview { min-height: 390px; position: relative; display: grid; place-items: center; }.preview-glow { width: 330px; height: 330px; background: #e2efb9; border-radius: 46% 54% 62% 38%; position: absolute; transform: rotate(25deg); }.preview-window { position: relative; width: min(100%, 440px); border: 8px solid #1b3028; border-radius: 18px; background: #fff; box-shadow: 15px 20px 0 rgba(16,37,31,.08); padding: 15px; transform: rotate(2deg); }.preview-topbar, .preview-heading, .chart-label { display: flex; align-items: center; justify-content: space-between; }.preview-topbar { color: #87928b; font-size: .63rem; border-bottom: 1px solid #edf0e9; padding-bottom: 13px; }.preview-dots { display: flex; gap: 4px; }.preview-dots i { width: 6px; height: 6px; background: #d7ded4; border-radius: 50%; }.preview-heading { padding: 22px 4px 18px; }.preview-heading small, .preview-stats small, .chart-label small { color: #94a098; font-size: .6rem; }.preview-heading h2 { font-size: 1.15rem; margin: 4px 0 0; }.live-dot { color: #588319; background: #eef7d8; border-radius: 20px; padding: 5px 9px; font-size: .6rem; font-weight: 700; }.preview-stats { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }.preview-stats > div { background: #f4f7f0; padding: 12px; border-radius: 8px; }.preview-stats strong { display: block; font-size: 1.25rem; margin: 5px 0; }.trend-up { color: #71a129; font-size: .58rem; }
        .preview-chart { margin-top: 18px; padding: 12px; border: 1px solid #edf0e9; border-radius: 8px; }.chart-label { font-size: .7rem; font-weight: 600; }.chart-bars { height: 76px; display: flex; align-items: end; gap: 7px; padding-top: 12px; }.chart-bars i { flex: 1; background: #b9db62; border-radius: 3px 3px 0 0; }.chart-bars i:nth-child(6), .chart-bars i:nth-child(8) { background: var(--ink); }.floating-ticket { position: absolute; right: -16px; bottom: 25px; display: flex; gap: 10px; align-items: center; background: #fff; padding: 12px 15px; border-radius: 10px; box-shadow: 0 12px 25px rgba(16,37,31,.15); font-size: .7rem; }.floating-ticket > i { font-size: 1.35rem; color: #75a52a; }.floating-ticket small, .floating-ticket strong { display: block; }.floating-ticket small { color: #8b9790; margin-bottom: 3px; }
        .trust-strip { border-top: 1px solid #dfe5dc; border-bottom: 1px solid #dfe5dc; padding: 20px 28px; display: flex; justify-content: space-between; color: #88938b; font-size: .72rem; }.trust-strip div { display: flex; gap: 23px; color: var(--ink); font-weight: 600; }.trust-strip i { color: #7cae2b; margin-right: 5px; }.feature-section { padding: 100px 28px; }.section-heading h2 { font-size: clamp(2rem, 4vw, 3.4rem); line-height: 1.08; letter-spacing: -.045em; margin-bottom: 42px; }.feature-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; }.feature-card { border: 1px solid #dce3d9; border-radius: 12px; padding: 28px; min-height: 230px; display: flex; flex-direction: column; }.feature-card-dark { background: var(--ink); color: #fff; border-color: var(--ink); }.feature-card-accent { background: #e6f2bf; border-color: #e6f2bf; }.feature-icon { font-size: 1.55rem; margin-bottom: 28px; color: #79a52f; }.feature-card h3 { font-size: 1.15rem; margin-bottom: 8px; }.feature-card p { color: #78857c; font-size: .82rem; line-height: 1.7; margin-bottom: 22px; }.feature-card-dark p { color: #b5c2b8; }.feature-card a { color: inherit; margin-top: auto; text-decoration: none; font-size: .73rem; font-weight: 700; }.feature-card a i { margin-left: 5px; }.landing-footer { border-top: 1px solid #dfe5dc; display: flex; justify-content: space-between; padding: 22px 28px 35px; color: #8b968e; font-size: .7rem; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .landing-hero { grid-template-columns: 1fr; padding-top: 55px; gap: 35px; }.hero-preview { min-height: 350px; }.trust-strip { display: block; }.trust-strip div { margin-top: 12px; flex-wrap: wrap; gap: 12px; }.feature-grid { grid-template-columns: 1fr; }
        }

        /* ===== PRINT ===== */
        @media print {
            .sidebar, .top-navbar, .no-print { display: none !important; }
            .main-content { margin-left: 0; }
        }

        @media (max-width: 576px) {
            .top-navbar { padding: 12px 16px; }
            .top-navbar > div:last-child > .text-muted { display: none; }
            .top-navbar .dropdown-toggle { max-width: 42px; overflow: hidden; white-space: nowrap; }
            .top-navbar .dropdown-toggle i { margin-right: 0; }
            .page-title { font-size: 1.05rem !important; }
            .main-content > .container-fluid { padding-left: 16px !important; padding-right: 16px !important; }
            .landing-nav, .landing-hero, .feature-section, .trust-strip, .landing-footer { padding-left: 20px; padding-right: 20px; }.landing-hero { padding-bottom: 65px; }.hero-copy h1 { font-size: 3.15rem; }.preview-window { border-width: 6px; }.floating-ticket { right: -5px; bottom: 12px; }.floating-ticket strong { font-size: .64rem; }.landing-footer { display: block; }.landing-footer span { display: block; }.landing-footer span + span { margin-top: 7px; }
        }
    </style>
</head>
<body>
    {{-- Pengguna terautentikasi mendapat navigasi; pengunjung hanya melihat konten halaman. --}}
    @if(auth()->check() && !request()->routeIs('landing'))
        @include('components.sidebar')
        <div class="main-content">
            @include('components.navbar')
            <main class="container-fluid py-4">
                @include('components.alert')
                @yield('content')
            </main>
        </div>
    @else
        @yield('content')
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Menutup sidebar terbuka ketika pengguna menekan area di luar navigasi.
        document.addEventListener('click', function (event) {
            const sidebar = document.querySelector('.sidebar');
            const toggle = event.target.closest('.top-navbar button');

            if (sidebar && !toggle && !sidebar.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        });
    </script>
</body>
</html>
